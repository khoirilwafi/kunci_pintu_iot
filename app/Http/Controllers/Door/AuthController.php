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
        $data = $request->only(['id', 'key']);

        $validator = Validator::make($data, [
            'id' => ['required', 'string'],
            'key' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $door = Door::where('device_id', $data['id'])->where('key', $data['key'])->first();

        if ($door) {
            $token = $door->createToken('auth_token')->plainTextToken;
            return response()->json(['message' => 'success', 'id' => $door->id, 'token' => $token], 200);
        }

        return response()->json(['message' => 'failed'], 401);
    }

    public function signature(Request $request)
    {
        $data = $request->only(['socket', 'id']);

        $validator = Validator::make($data, [
            'socket' => ['required', 'string'],
            'id' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $door = Door::where('device_id', $data['id'])->first();

        $signature = $data['socket'] . ':private-door.' . $door->id;
        $hash = hash_hmac('sha256', $signature, env('PUSHER_APP_SECRET', null));

        return response()->json(['message' => 'success', 'signature' => $hash], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'success'], 200);
    }

    public function getDoor()
    {
        $door = Door::all();
        return response()->json(['message' => 'success', 'data' => $door], 200);
    }
}
