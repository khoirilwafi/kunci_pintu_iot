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
            return response()->json(['message' => 'success', 'token' => $token], 200);
        }

        return response()->json(['message' => 'failed'], 401);
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
