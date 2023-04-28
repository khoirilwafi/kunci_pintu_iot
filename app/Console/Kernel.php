<?php

namespace App\Console;

use App\Events\PublicTestEvent;
use App\Jobs\DoorScheduleJob;
use App\Jobs\ScheduleDailyJob;
use Carbon\Carbon;
use App\Models\TestModel;
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
        // $schedule->command('inspire')->hourly();

        $schedule->command('auth:clear-resets')->everyThirtyMinutes();

        $schedule->job(new DoorScheduleJob)->everyMinute();
        $schedule->job(new ScheduleDailyJob)->dailyAt('00:02:00');
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
