<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\Office;
use App\Models\AccessLog;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExportAccessLogJob implements ShouldQueue
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
        $now = Carbon::now()->format('d-m-Y');
        $offices = Office::select(['id', 'name'])->get();

        foreach ($offices as $office) {

            $contents = '';
            $file_name = "access-" . Str::slug($office->name) . "-" . $now . ".log";

            $logs = AccessLog::with(['user', 'door'])->where('office_id', $office->id)->orderBy('created_at', 'desc')->get();

            foreach ($logs as $log) {
                $contents .= "[" . $log->created_at . "] {'user':'" . $log->user->name . "','door':'" . $log->door->name . "','action':'" . $log->log . "'}\n";
            }

            try {
                Storage::put("logs/{$file_name}", $contents);
                Log::info('access log export', ['file' => $file_name]);
            } catch (Exception $e) {
                Log::error('access log export error', ['error' => $e]);
            }
        }

        try {
            AccessLog::truncate();
            Log::info('access log clean');
        } catch (Exception $e) {
            Log::error('access log clean error', ['error' => $e]);
        }
    }
}
