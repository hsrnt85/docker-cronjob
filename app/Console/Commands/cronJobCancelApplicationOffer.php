<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronJobController;
use Illuminate\Console\Command;

class cronJobCancelApplicationOffer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronJob:cancelApplicationOffer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command : cancelApplicationOffer';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //insert year if not exist
        CronJobController::cancelApplicationOffer();
    }
}
