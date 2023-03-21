<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use App\Models\Office;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Notifications\sendOTP;
use App\Notifications\resetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // validate input
        $credentials = $request->validate([
            'email'    => ['required', 'email:dns'],
            'password' => ['required'],
        ]);

        // ambil data user
        $user = User::where('email', $credentials['email'])->first();

        // cek user dan levelnya
        if ($user && $user->role != 'pengguna') {

            // login
            if (Auth::attempt($credentials, $request->get('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }

            // kembali dengan error credential
            return back()->with('login_failed', 'Login Gagal, Data Tidak Sesuai');
        }


        // kembali dengan error akses
        return back()->with('login_failed', 'Login Gagal, Data Tidak Ditemukan');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function requestResetPassword()
    {
        return view('auth.forgot-password');
    }

    public function handleResetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email:dns'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email:dns'],
            'password' => ['required', 'min:8', 'max:20', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // public function generateCode(Request $request)
    // {
    //     $validated_data = $request->validate([
    //         'email' => ['required', 'email:dns'],
    //     ]);

    //     $user = User::where('email', $validated_data['email'])->first();

    //     if ($user) {

    //         $id = $user->id;

    //         $data = array(
    //             'uuid'        => md5(Str::random(20)),
    //             'user_id'     => $id,
    //             'code_otp'    => rand(123456, 999999),
    //             'type'        => 'reset_password',
    //             'valid_until' => Carbon::now()->addMinutes(15),
    //         );

    //         Otp::updateOrCreate(['user_id' => $id, 'type' => 'reset_password'], $data);
    //         // $user->notify(new resetPassword($user->name, $data['code_otp']));

    //         return redirect('/reset-password/' . $data['uuid']);
    //     }

    //     return redirect()->back()->with('email_error', 'email tidak ditemukan');
    // }

    // public function resetPasswordForm($key)
    // {
    //     $secret = Otp::with('user')->where('uuid', $key)->first();

    //     $email = $secret->user->email;
    //     $uuid = $secret->uuid;

    //     return view('authentication.reset_password', ['title' => 'Reset Password', 'email' => $email, 'uuid' => $uuid]);
    // }

    // public function resetPassword(Request $request)
    // {
    //     $now = Carbon::now();

    //     $validated_data = $request->validate([
    //         'email'    => ['required'],
    //         'uuid'     => ['required'],
    //         'kode'     => ['required', 'numeric', 'digits:6'],
    //         'password' => ['required', 'min:8', 'max:255', 'confirmed'],
    //     ]);

    //     $secret = Otp::where('uuid', $validated_data['uuid'])->where('code_otp', $validated_data['kode'])->where('type', 'reset_password')->first();

    //     if ($secret && $now->lessThanOrEqualTo($secret->valid_until)) {
    //         User::where('email', $validated_data['email'])->update(['password' => Hash::make($validated_data['password'])]);
    //         Otp::with('uuid', $secret['uuid'])->delete();
    //         return redirect('/login')->with('reset_sukses', 'Password berhasil diperbarui');
    //     } elseif ($secret) {
    //         return redirect()->back()->with('reset_error', 'Kode sudah kadaluarsa');
    //     }

    //     return redirect()->back()->with('reset_error', 'Kode tidak sesuai');
    // }
}
