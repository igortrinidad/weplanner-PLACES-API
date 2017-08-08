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
        \App\Console\Commands\UpdateTracker::class,
        \App\Console\Commands\PlaceMonthlyReport::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Update the tracker autimaticaly to keep places interesting on our platform while we do not have much users
        $schedule->command('update:tracker')->dailyAt('17:58');

        //Envia o email de insights semanais
        $schedule->command('place:monthly-report')->weekly()->tuesdays()->at('17:05');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
