<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $data = $request->only(['name', 'email', 'gender', 'phone']);
        $user = $request->user();

        $validator = Validator::make($data, [
            'name'   => ['required', 'min:4', Rule::unique('users')->ignore($user->id)],
            'email'  => ['required', 'email:dns', Rule::unique('users')->ignore($user->id)],
            'gender' => ['required'],
            'phone'  => ['required', 'numeric', Rule::unique('users')->ignore($user->id), 'digits_between:11,13'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $user = User::where('id', $user->id)->first();

        if ($user) {
            $user->name   = $data['name'];
            $user->email  = $data['email'];
            $user->gender = $data['gender'];
            $user->phone  = $data['phone'];

            $status = $user->save();

            if ($status) {
                return response()->json([
                    'status' => 'success',
                    'data' => $user
                ], 200);
            }
        }

        return response()->json([
            'status' => 'failed',
            'data' => []
        ], 200);
    }

    public function getAvatar($file_name)
    {
        $file = storage_path('/app/images/' . $file_name);
        return response()->file($file);
    }
}
