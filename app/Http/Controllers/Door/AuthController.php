<?php

namespace App\Http\Controllers\Door;

use App\Models\Door;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $data = $request->only(['device_id', 'device_key']);

        $validator = Validator::make($data, [
            'device_id' => ['required', 'string'],
            'device_key' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $door = Door::where('device_id', $data['device_id'])->where('key', $data['device_key'])->first();

        if ($door) {
            $token = $door->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'data' => [
                    'door_id' => $door->id,
                    'office_id' => $door->office_id,
                    'token' => $token
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'failed',
            'data' => []
        ], 401);
    }

    public function signature(Request $request)
    {
        $data = $request->only(['socket_id', 'office_id', 'channel_data']);

        $validator = Validator::make($data, [
            'socket_id' => ['required', 'string'],
            'office_id' => ['required', 'string'],
            'channel_data' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $signature = $data['socket_id'] . ':presence-office.' . $data['office_id'] . ':' . $data['channel_data'];
        $hash = hash_hmac('sha256', $signature, env('PUSHER_APP_SECRET', null));

        return response()->json([
            'status' => 'success',
            'data' => [
                'signature' => $hash
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['status' => 'success'], 200);
    }

    public function getDoor()
    {
        $door = Door::all();
        return response()->json(['message' => 'success', 'data' => $door], 200);
    }
}
