<?php

namespace App\Jobs;

use App\Models\Access;
use App\Models\Schedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DailyJob implements ShouldQueue
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
        $date = Carbon::now()->toDateString();

        try {

            // hapus jadwal yang sudah expired
            Schedule::where('date_end', '<', $date)->delete();

            // hapus akses yang sudah expired
            Access::where('date_end', '<', $date)->delete();

            // reset status jadwal
            Schedule::query()->update(['status' => 'waiting']);
        } catch (Exception $e) {
            Log::error('daily job failed', ['error' => $e]);
        }
    }
}
