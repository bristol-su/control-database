<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Position;
use App\Packages\ContactSheetUpload\SheetRow;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class GenerateContactSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contactsheet:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the contact sheet and upload to google drive.';

    protected $generateSheet = true;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sheetRows = new Collection();
        $positions = Position::all();

        // Gather together each of the sheet rows
        foreach($positions as $position)
        {
            foreach($position->students as $student)
            {
                $group = Group::withTrashed()->where('id', $student->pivot->group_id)->get()->first();
                $sheetRows[] = new SheetRow($position, $student, $group);
            }
        }

        // Get any data to construct the rows in full
        $counter = 0;
        $sheetRows->each(function($sheetRow) use ($counter){
            if($sheetRow->generateData() === false){
                $this->generateSheet = false;
                $counter++;
            }
        });

        // Create the raw data
        $rawData = [];
        $rawData[] = SheetRow::getHeaders();
        foreach($sheetRows as $sheetRow)
        {
            $rawData[] = $sheetRow->getElements();
        }

        if($this->generateSheet)
        {
            $this->uploadSheet($rawData);
        } else {
            $this->error('We\'re just collecting some student data ('.$counter.' to collect). Please try again later.');
        }
    }

    public function uploadSheet($data)
    {
        $csv = Writer::createFromPath(sys_get_temp_dir().'/'.Carbon::now()->format('dmyHi').'contactsheet_generation.csv', 'w+');
        $csv->insertAll($data);
        Storage::disk('google')->put('csv-test', $csv->getContent());
    }

}
