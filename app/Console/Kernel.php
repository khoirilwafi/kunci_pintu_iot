<?php

namespace App\Console;

use App\Jobs\ClearTokenJob;
use App\Jobs\DoorScheduleJob;
use App\Jobs\ExportAccessLogJob;
use App\Jobs\DailyJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('auth:clear-resets')->everyThirtyMinutes();

        $schedule->job(new DoorScheduleJob)->everyMinute();
        $schedule->job(new DailyJob)->dailyAt('00:01:00');
        $schedule->job(new ClearTokenJob)->weeklyOn(1, '00:03:00');
        $schedule->job(new ExportAccessLogJob)->monthlyOn(1, '00:02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
