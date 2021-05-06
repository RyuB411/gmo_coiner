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
        config('const.CRYPT.SYMBOL_LIST')->each(function($symbol) use ($schedule) {
            config('const.CRYPT.INTERVAL_LIST.DAILY')->each(function($interval) use ($schedule, $symbol) {
                $schedule->command(sprintf('%s %s %s %s', 'gmoapi:getkline', $symbol, today()->format('Ymd'), $interval))
                    ->everyTenMinutes();
            });

            config('const.CRYPT.INTERVAL_LIST.YEAR')->each(function($interval) use ($schedule, $symbol) {
                $schedule->command(sprintf('%s %s %s %s', 'gmoapi:getkline', $symbol, today()->format('Y'), $interval))
                    ->cron('0 1,5,9,13,17,21 * * *');
            });
        });

        $schedule->command('slack:notification')->cron('0 1,5,9,13,17,21 * * *');
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
