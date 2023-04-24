<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                return redirect()->intended('/dashboard');
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
}
