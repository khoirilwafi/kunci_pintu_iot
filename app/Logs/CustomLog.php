<?php

namespace App\Logs;

use App\Models\AccessLog;
use Illuminate\Support\Facades\Log;

class CustomLog
{
    protected $user_id, $door_id, $office_id, $log;

    public function __construct($user_id, $door_id, $office_id, $log)
    {
        $this->user_id = $user_id;
        $this->door_id = $door_id;
        $this->office_id = $office_id;
        $this->log = $log;

        $this->submit();
    }

    protected function submit()
    {
        $log = AccessLog::create([
            'user_id' => $this->user_id,
            'door_id' => $this->door_id,
            'office_id' => $this->office_id,
            'log' => $this->log,
        ]);

        // if ($log) {
        //     Log::info('custom log event', ['data' => $log]);
        // }
    }
}
