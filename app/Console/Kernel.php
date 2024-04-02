<?php

namespace App\Console;

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
       
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('cronJob:cancelApplicationOffer')->everyMinute();//->daily(5);
        //$schedule->command('cronJob:cancelComplaintAppointment')->everyMinute();//->daily(5);
        //$schedule->command('cronJob:cronJobTenantsPenalty')->everyMinute();//->daily(5);
        $schedule->command('cronJob:addYear')->everyMinute();//->yearlyOn(12, 31, '17:00');//->twiceDaily(1, 13);
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
