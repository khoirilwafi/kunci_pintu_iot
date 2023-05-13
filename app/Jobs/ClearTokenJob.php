<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ClearTokenJob implements ShouldQueue
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
        $expirationDate = Carbon::now()->subDays(7);

        try {
            DB::table('personal_access_tokens')->where('created_at', '<', $expirationDate)->delete();
            Log::info('token kadaluarsa berhasil dibersihkan');
        } catch (Exception $e) {
            Log::error('token kadaluarsa gagal dibersihkan', ['error' => $e]);
        }
    }
}
