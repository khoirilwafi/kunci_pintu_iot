<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use App\Events\DoorScheduleEvent;
use App\Logs\CustomLog;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DoorScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();

        $date = $now->toDateString();
        $time = $now->toTimeString();

        $day_week = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $day = $day_week[$now->format('w')];

        $schedules = Schedule::with(['door', 'office'])
            ->where('status', 'waiting')
            ->where('time_begin', '<=', $time)
            ->where('time_end', '>=', $time)
            ->where('date_end', '>=', $date)

            ->where(function ($date_query) use ($date, $day) {
                $date_query->where('date_begin', $date)->orWhere(function ($day_query) use ($date, $day) {
                    $day_query->where('date_begin', '<', $date)->where('is_repeating', 1)->whereRaw("find_in_set(?, day_repeating) > 0", [$day]);
                });
            })

            ->select(['id', 'office_id', 'time_end'])
            ->get();

        foreach ($schedules as $schedule) {

            $doors = $schedule->door;

            foreach ($doors as $door) {
                if ($door->device_id != null) {

                    // event broadcast
                    event(new DoorScheduleEvent($door->office_id, $schedules->office->user_id, $door->id, $schedules->time_end, 'run', $door->key));

                    // save log
                    new CustomLog($schedules->office->user_id, $door->id, $door->office_id, 'mengirim jadwal');
                }
            }

            Schedule::where('id', $schedule->id)->update(['status' => 'running']);
        }

        // set done to schedule
        Schedule::where('status', 'running')->where('time_end', '<', $time)->update(['status' => 'done']);
    }
}
