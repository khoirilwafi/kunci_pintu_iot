<?php

namespace App\Jobs;

use App\Models\Schedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScheduleDailyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // hapus jadwal yang sudah expired
        try {
            Schedule::where('date_end', '<', Carbon::now()->toDateString())->delete();
        } catch (Exception $e) {
        }

        // reset status jadwal
        Schedule::query()->update(['status' => 'waiting']);
    }
}
