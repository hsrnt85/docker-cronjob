<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronJobController;
use Illuminate\Console\Command;

class cronJobAddYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronJob:addYear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command : addYear';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //insert year if not exist
        CronJobController::addYear();
        //return 0;
    }
}
