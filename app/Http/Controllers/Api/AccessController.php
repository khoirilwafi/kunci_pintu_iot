<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Door;
use App\Models\Office;
use Illuminate\Http\Request;

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

    public function verifyAccess(Request $request)
    {
    }
}
