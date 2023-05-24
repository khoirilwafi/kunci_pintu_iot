<?php

namespace App\Http\Controllers\Door;

use Exception;
use App\Models\Door;
use App\Logs\CustomLog;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use App\Events\DoorAlertEvent;
use App\Events\DoorStatusEvent;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DoorEventController extends Controller
{
    public function update_status(Request $request)
    {
        $data = $request->only(['door_id', 'office_id', 'socket_id', 'user_id', 'lock_status']);

        $validator = Validator::make($data, [
            'door_id'     => ['required', 'string'],
            'office_id'   => ['required', 'string'],
            'socket_id'   => ['required', 'string'],
            'user_id'     => ['required', 'string'],
            'lock_status' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $door = Door::where('id', $data['door_id'])->first();

        // generate key
        $key = Random::generate(20);

        if ($door) {
            $door->socket_id = $data['socket_id'];
            $door->is_lock = $data['lock_status'];
            $door->key = $key;
            $status = $door->save();
        }

        if ($status) {

            // broadcast event
            event(new DoorStatusEvent($data['office_id']));

            // save log
            if ($data['door_id'] == $data['user_id']) {
                Log::info('door self update', ['door' => $door]);
            } else {
                new CustomLog($data['user_id'], $data['door_id'], $door->office_id, ($data['lock_status'] == 0) ? 'pintu terbuka' : 'pintu terkunci');
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'key' => $key
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'failed',
            'data' => []
        ], 401);
    }

    public function alert(Request $request)
    {
        $data = $request->only(['door_id', 'office_id', 'message']);

        $validator = Validator::make($data, [
            'office_id' => ['required', 'string'],
            'door_id' => ['required', 'string'],
            'message' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $door = Door::where('id', $data['door_id'])->first();

        if (!$door) {
            return response()->json([
                'status' => 'no_data',
                'data' => []
            ], 200);
        }

        event(new DoorAlertEvent($data['office_id'], $door->name, $data['message']));
        Log::alert('door alert', ['door' => $door, 'message' => $data['message']]);

        return response()->json([
            'status' => 'success',
            'data' => []
        ], 200);
    }
}
