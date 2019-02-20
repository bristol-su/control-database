<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Packages\GroupSheetUpload\SheetRow;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class GenerateGroupSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'groupsheet:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the group sheet and upload to google drive.';

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
        $groups = Group::withTrashed()->get();

        // Gather together each of the sheet rows
        foreach($groups as $group)
        {
            $sheetRows[] = new SheetRow($group);
        }

        // Get any data to construct the rows in full
        $counter = 0;
        $sheetRows->each(function($sheetRow) use (&$counter){
            if($sheetRow->generateData() === false){
                $this->generateSheet = false;
                $counter++;
            }
        });

        // Order the sheet rows
        $sheetRows = $sheetRows->sortBy(function($sheetRow) {
            return $sheetRow->group_name;
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
            $this->error('Won\'t upload on local site.');
        }
    }

    public function uploadSheet($data)
    {
        $filename = sys_get_temp_dir().'/'.Carbon::now()->format('dmyHis').'groupsheet_generation.csv';
        $csv = Writer::createFromPath($filename, 'w+');
        $csv->insertAll($data);

        // Get the current file so we can overwrite it later
        $path = config('app.group_sheet_drive_id');

        if(Storage::disk('google')->put($path, $csv, ['mimetype' => 'application/vnd.google-apps.spreadsheet'])){
            $this->info('Successfully uploaded to Google Drive.');
        } else {
            $this->error('Sorry, an error occurred uploading the sheet to Google Drive');
        }
    }

}
