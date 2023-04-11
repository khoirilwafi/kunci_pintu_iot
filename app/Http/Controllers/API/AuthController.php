<?php

namespace App\Http\Controllers\api;

use App\Models\Door;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function doorLogin(Request $request)
    {
        $credential = $request->validate([
            'key' => ['required', 'string']
        ]);

        $door = Door::where('key', $credential['key'])->first();

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
        return response()->json(['message' => 'success']);
    }
}
