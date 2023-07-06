<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendOTPNotification;


class AuthController extends Controller
{
    protected function generateOTP($id)
    {
        $data = array(
            'code_otp'    => rand(123456, 999999),
            'valid_until' => Carbon::now()->addMinutes(5),
        );

        Otp::updateOrCreate(['user_id' => $id], $data);

        return $data['code_otp'];
    }

    public function authenticate(Request $request)
    {
        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, [
            'email' => ['required', 'string', "email"],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $user = User::where('email', $data['email'])->first();

        // cek jika email valid dan password sesuai
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => 'failed',
                'data' => []
            ], 200);
        }

        // generate token
        $token = $user->createToken('api-token')->plainTextToken;
        Log::info('user login with api', ['user' => $user]);

        // cek apakah user sudah verifikasi email
        if ($user->email_verified_at == null) {

            // get otp
            $last_otp = Otp::where('user_id', $user->id)->first();

            // kirim kode otp
            if ($last_otp == null) {
                $otp = $this->generateOTP($user->id);
                $user->notify(new SendOTPNotification($otp));
            }

            // send email notifcation
            return response()->json([
                'status' => 'email_unverified',
                'data' => [
                    'id' => $user->id
                ],
                'token' => $token
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                "id"     => $user->id,
                "email"  => $user->email,
                "name"   => $user->name,
                "phone"  => $user->phone,
                "gender" => $user->gender,
                "role"   => $user->role,
                "avatar" => $user->avatar,
            ],
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        Log::info('user logout using api', ['user' => $user]);

        $user->tokens()->delete();
        return response()->json(['status' => 'success', 'data' => []], 200);
    }

    public function verifyEmail(Request $request)
    {
        $now = Carbon::now();

        $data = $request->only(['id', 'otp']);

        $validator = Validator::make($data, [
            'id'  => ['required', 'string'],
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $otp = Otp::where('user_id', $data['id'])->where('code_otp', $data['otp'])->first();

        if ($otp && $now->lessThanOrEqualTo($otp->valid_until)) {
            User::where('id', $data['id'])->update(['email_verified_at' => $now]);
            Otp::where('user_id', $data['id'])->delete();

            Log::info('user verify email using api', ['user' => $request->user()]);

            return response()->json([
                'status' => 'success',
                'data' => User::where('id', $data['id'])->select(['id', 'email', 'name', 'phone', 'gender', 'role', 'avatar'])->first(),
            ], 200);
        } else if ($otp) {
            Otp::where('user_id', $data['id'])->delete();
            return response()->json([
                'status' => 'otp_expired',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => 'otp_not_match',
            'data' => []
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $data = request()->only('email');

        $validator = Validator::make($data, [
            'email' => ['required', 'email:dns'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $status = Password::sendResetLink($data);

        // check status
        if ($status === Password::RESET_LINK_SENT) {
            Log::info('reset link send', ['email' => $data['email']]);
            return response()->json([
                'status' => 'success',
                'data' => [
                    'email' => $data['email']
                ]
            ], 200);
        }

        // return error
        return response()->json([
            'status' => 'failed',
            'data' => [
                'message' => 'email not found',
                'email' => $data['email'],
            ]
        ], 200);
    }
}
