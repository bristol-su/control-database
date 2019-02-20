<?php

namespace App\Console;

use App\Console\Commands\GenerateContactSheet;
use App\Console\Commands\GenerateGroupSheet;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // TODO Ensure this relies on the .env file
        $schedule->command(GenerateContactSheet::class)
            ->everyFifteenMinutes()
            ->evenInMaintenanceMode()
            ->environments(['production']);


        $schedule->command(GenerateGroupSheet::class)
            ->everyFifteenMinutes()
            ->evenInMaintenanceMode()
            ->environments(['production']);


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
