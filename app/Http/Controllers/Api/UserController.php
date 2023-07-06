<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
                Log::info('user update profile using api', ['user' => $user]);
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

    public function getAvatar(Request $request)
    {
        $file_name = request()->user()->avatar;

        $file = storage_path('/app/images/' . $file_name);
        return response()->file($file);
    }

    public function updateAvatar(Request $request)
    {
        $data = $request->only('avatar');

        $validator = Validator::make($data, [
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $user = User::where('id', $request->user()->id)->first();

        if ($user->avatar != null) {
            Storage::disk('local')->delete('/images/' . $user->avatar);
        }

        $name = md5(Carbon::now()) . '.' . $data['avatar']->getClientOriginalExtension();

        try {

            // store avatar
            $data['avatar']->storeAs('images', $name);
            $user->avatar = $name;
            $user->save();
            Log::info('avatar change', ['user' => $user]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'avatar' => $name,
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('avatar change failed', ['user' => $user, 'error' => $e]);
        }

        return response()->json(['status' => 'error'], 200);
    }

    public function changePassword(Request $request)
    {
        $user = request()->user();
        $data = request()->only(['password_now', 'password', 'password_confirmation']);

        $validator = Validator::make($data, [
            'password_now'          => ['required', 'string', 'min:8', 'max:50'],
            'password'              => ['required', 'string', 'min:8', 'max:50', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        if (!Hash::check($data['password_now'], $user->password)) {
            return response()->json([
                'status' => 'failed',
                'data' => [
                    'message' => 'password not match',
                ]
            ], 200);
        }

        try {
            // update password
            $user->forceFill(['password' => Hash::make($data['password'])]);
            $user->save();

            Log::info('change password', ['user' => $user]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'password saved'
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('change password failed', ['user' => $user, 'error' => $e]);
        }

        return response()->json([
            'status' => 'failed',
            'data' => [
                'message' => 'password save failed',
            ]
        ], 200);
    }
}
