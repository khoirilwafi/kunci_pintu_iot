<?php

namespace App\Http\Controllers\Api;

use App\Events\DoorCommandEvent;
use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Door;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccessController extends Controller
{
    public function myAccess(Request $request)
    {
        $user = $request->user();

        $access = Access::with('door')->where('user_id', $user->id)->get();

        if ($access) {
            return response()->json([
                'status' => 'success',
                'data' => $access
            ], 200);
        }

        return response()->json([
            'status' => 'no_data',
            'data' => []
        ], 200);
    }

    public function getOfficeDoor(Request $request)
    {
        $user = $request->user();

        if ($user->role != 'operator') {
            return response()->json([
                'status' => 'not authorized',
                'data' => []
            ], 200);
        }

        $office = Office::select('id')->where('user_id', $user->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => Door::where('office_id', $office->id)->get()
        ], 200);
    }

    public function verifyAccess(Request $request, $door_id)
    {
        $user = $request->user();
        $door = Door::with('office')->where('id', $door_id)->first();

        if (!$door) {
            return response()->json([
                'status' => 'no_data',
                'data' => []
            ], 200);
        }

        if ($user->role == 'operator' && $user->id == $door->office->user_id) {
            return response()->json([
                'status' => 'success',
                'data' => $door
            ], 200);
        }

        $now = Carbon::now();

        $date = $now->toDateString();
        $time = $now->toTimeString();

        $access = Access::with('door')
            ->where('user_id', $user->id)
            ->where('door_id', $door_id)
            ->where('is_running', 1)
            ->where('time_begin', '<=', $time)
            ->where('date_begin', '<=',  $date)
            ->where('time_end', '>=',  $time)
            ->where('date_end', '>=',  $date)
            ->first();

        if ($access) {
            return response()->json([
                'status' => 'success',
                'data' => $access->door
            ], 200);
        }

        return response()->json([
            'status' => 'failed',
            'data' => []
        ], 200);
    }

    public function remoteAccess(Request $request)
    {
        $user = $request->user();

        if ($user->role != 'operator') {
            return response()->json([
                'status' => 'not authorized',
                'data' => []
            ], 200);
        }

        $data = $request->only(['door_id', 'locking']);

        $validator = Validator::make($data, [
            'door_id' => ['required', 'string'],
            'locking' => ['required', 'numeric']
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

        event(new DoorCommandEvent($door->office_id, $door->id, $data['locking']));

        return response()->json([
            'status' => 'success',
            'data' => []
        ], 200);
    }
}
