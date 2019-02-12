<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Position;
use App\Packages\ContactSheetUpload\SheetRow;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
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
        $this->warn('This feature relies on an implementation of the cache being in place (See PSR-6).');
        // TODO As above
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
        $sheetRows->each(function($sheetRow) use (&$counter){
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
        $filename = sys_get_temp_dir().'/'.Carbon::now()->format('dmyHis').'contactsheet_generation.csv';
        $csv = Writer::createFromPath($filename, 'w+');
        $csv->insertAll($data);

        // Get the current file so we can overwrite it later
        $path = 'Committee Contact Details.csv';
        foreach(Storage::disk('google')->listContents('/', false) as $doc)
        {
            if($doc['mimetype'] === 'application/vnd.google-apps.spreadsheet')
            {
                $path = $doc['path'];
            }
        }

        if(Storage::disk('google')->put($path, $csv, ['mimetype' => 'application/vnd.google-apps.spreadsheet'])){
            $this->info('Successfully uploaded to Google Drive.');
        } else {
            $this->error('Sorry, an error occurred uploading the sheet to Google Drive');
        }
    }

}
