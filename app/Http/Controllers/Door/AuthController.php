<?php

namespace App\Http\Controllers\Door;

use App\Models\Door;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $data = $request->only(['device_name', 'device_pass']);

        $validator = Validator::make($data, [
            'device_name' => ['required', 'string'],
            'device_pass' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $door = Door::where('device_name', $data['device_name'])->first();

        if (!$door || !Hash::check($data['device_pass'], $door->device_pass)) {
            return response()->json([
                'status' => 'failed',
                'data' => []
            ], 200);
        }

        $token = $door->createToken('auth_token')->plainTextToken;
        Log::info('door device login', ['door' => $door]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'door_id' => $door->id,
                'office_id' => $door->office_id,
            ],
            'token' => $token
        ], 200);
    }

    public function register(Request $request)
    {
        $data = $request->only(['id', 'device_name']);

        $validator = Validator::make($data, [
            'id' => ['required', 'string'],
            'device_name' => ['required', 'string'],
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

        if ($door->device_name != null) {
            return response()->json([
                'status' => 'already_exist',
                'data' => []
            ], 200);
        }

        $pass = Random::generate(20);

        $door->device_name = $data['device_name'];

        $door->forceFill(['device_pass' => Hash::make($pass)]);

        try {
            $door->save();
            Log::info('door device register', ['door' => $door]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'device_name' => $data['device_name'],
                    'device_pass' => $pass,
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('door device register failed', ['door' => $door, 'error' => $e]);

            return response()->json([
                'status' => 'failed',
                'data' => []
            ], 200);
        }
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
        $hash = hash_hmac('sha256', $signature, config('broadcasting.connections.pusher.secret'));

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
