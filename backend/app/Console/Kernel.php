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
        $symbol_list = collect([
            'BTC_JPY',
            'ETH_JPY',
            'BCH_JPY',
            'LTC_JPY',
            'XRP_JPY',
        ]);
        $daily_interval_list = collect([
            // '1min',
            // '5min',
            // '10min',
            // '15min',
            '30min',
            '1hour',
        ]);
        $year_interval_list = collect([
            '4hour',
            '8hour',
            '12hour',
            '1day',
            '1week',
            '1month',
        ]);

        $symbol_list->each(function($symbol) use ($schedule, $daily_interval_list, $year_interval_list) {
            $daily_interval_list->each(function($interval) use ($schedule, $symbol) {
                $schedule->command(sprintf('%s %s %s %s', 'gmoapi:getkline', $symbol, today()->format('Ymd'), $interval))
                    ->everyTenMinutes();
            });

            $year_interval_list->each(function($interval) use ($schedule, $symbol) {
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
