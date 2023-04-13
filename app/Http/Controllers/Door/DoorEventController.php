<?php

namespace App\Http\Controllers\Door;

use App\Events\DoorStatusEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Door;
use Illuminate\Support\Facades\Validator;

class DoorEventController extends Controller
{
    public function update_status(Request $request)
    {
        $data = $request->only(['door_id', 'office_id', 'socket_id', 'lock_status']);

        $validator = Validator::make($data, [
            'door_id' => ['required', 'string'],
            'office_id' => ['required', 'string'],
            'socket_id' => ['required', 'string'],
            'lock_status' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $door = Door::where('id', $data['door_id'])->first();

        if ($door) {
            $door->socket_id = $data['socket_id'];
            $door->is_lock = $data['lock_status'];
            $status = $door->save();
        }

        if ($status) {
            event(new DoorStatusEvent($data['office_id']));
            return response()->json([
                'status' => 'success',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => 'failed',
            'data' => []
        ], 401);
    }

    public function alarm($device_id)
    {
    }
}
