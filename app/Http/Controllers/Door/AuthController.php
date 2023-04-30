<?php

namespace App\Http\Controllers\Door;

use App\Models\Door;
use Ramsey\Uuid\Uuid;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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

        $door = Door::where('device_id', $data['device_id'])->first();

        if (!$door || !Hash::check($data['device_key'], $door->device_key)) {
            return response()->json([
                'status' => 'failed',
                'data' => []
            ], 200);
        }

        $token = $door->createToken('auth_token')->plainTextToken;
        Log::info('door device login', ['door' => $door, 'token' => $token]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'door_id' => $door->id,
                'office_id' => $door->office_id,
                'token' => $token
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        $data = $request->only(['id', 'device_id']);

        $validator = Validator::make($data, [
            'id' => ['required', 'string'],
            'device_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $door = Door::where('id', $data['id'])->first();

        if (!$door) {
            return response()->json([
                'status' => 'no_data',
                'data' => []
            ], 200);
        }

        if ($door->device_id != null) {
            return response()->json([
                'status' => 'already_exist',
                'data' => []
            ], 200);
        }

        $key = Random::generate(30);

        $door->device_id = $data['device_id'];
        $door->device_key = Hash::make($key);
        $door->token = (string) Uuid::uuid4();

        $status = $door->save();

        if ($status) {
            Log::info('door device register', ['door' => $door]);
            return response()->json([
                'status' => 'success',
                'data' => [
                    'device_key' => $key,
                    ''
                ],
            ], 200);
        }

        return response()->json([
            'status' => 'failed',
            'data' => []
        ], 200);
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

        Log::info('door device signature', ['door' => $data]);

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

        Log::info('door device logout', ['door' => $user]);

        $user->tokens()->delete();
        return response()->json(['status' => 'success'], 200);
    }
}
