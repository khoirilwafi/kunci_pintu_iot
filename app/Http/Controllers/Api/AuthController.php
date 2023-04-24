<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use App\Models\Access;
use Illuminate\Http\Request;
use App\Notifications\sendOTP;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    protected function generateOTP($id)
    {
        $data = array(
            'user_id'     => $id,
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
            'email' => ['required', 'string', "email:dns"],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'missing_parameter',
                'data' => $validator->messages()
            ], 200);
        }

        $user = User::with('avatar')->where('email', $data['email'])->first();

        // cek jika email valid dan password sesuai
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => 'failed',
                'data' => []
            ], 200);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        // cek apakah user sudah verifikasi email
        if ($user->email_verified_at == null) {

            // kirim kode otp
            $otp = $this->generateOTP($user->id);
            // $user->notify(new sendOTP($otp));

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
            'data' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
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
            Otp::with('user_id', $data['id'])->delete();

            return response()->json([
                'status' => 'success',
                'data' => []
            ], 200);
        } elseif ($otp) {
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
}
