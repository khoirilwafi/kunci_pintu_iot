<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Scedule;
use Illuminate\Bus\Queueable;
use App\Events\DoorScheduleEvent;
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


        $schedules = Scedule::with(['door' => function ($query) {
            $query->select(['device_id', 'token']);
        }])
            ->where('status', 'waiting')
            ->where('time_begin', '<=', $time)
            ->where('time_end', '>=', $time)
            ->where('date_end', '>=', $date)

            ->where(function ($date_query) use ($date, $day) {
                $date_query->where('date_begin', $date)->orWhere(function ($day_query) use ($day) {
                    $day_query->where('is_repeating', 1)->whereRaw("find_in_set(?, day_repeating) > 0", [$day]);
                });
            })

            ->select(['id', 'office_id', 'time_end'])
            ->get();


        foreach ($schedules as $schedule) {

            $office_id = $schedule->office_id;
            $time_end = $schedule->time_end;
            $doors = $schedule->door;

            foreach ($doors as $door) {
                if ($door->device_id != null) event(new DoorScheduleEvent($office_id, $door->device_id, $door->token, $time_end));
            }

            Scedule::where('id', $schedule->id)->update(['status' => 'done']);
        }
    }
}
