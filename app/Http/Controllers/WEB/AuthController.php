<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // user data
        $user = User::where('email', $credentials['email'])->first();

        // check level (only moderator and operator can login)
        if ($user && $user->role != 'pengguna') {

            // login attempt
            if (Auth::attempt($credentials, $request->get('remember'))) {

                // generate session
                $request->session()->regenerate();
                Log::info('login', ['user' => $user]);

                // redirect
                return redirect()->intended('/dashboard');
            }

            // error credential
            return back()->with('login_failed', 'Login Gagal, Data Tidak Sesuai');
        }

        // error credential
        return back()->with('login_failed', 'Login Gagal, Data Tidak Ditemukan');
    }

    public function logout(Request $request)
    {
        // logout
        Log::info('logout', ['user' => $request->user()]);
        Auth::logout();

        // delete session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // redirect
        return redirect('/login');
    }

    public function requestResetPassword()
    {
        return view('auth.forgot-password');
    }

    public function handleResetPassword(Request $request)
    {
        // validate data
        $request->validate([
            'email' => ['required', 'email:dns'],
        ]);

        // get email
        $email = $request->only('email');

        // send link to user via email
        $status = Password::sendResetLink($email);

        // check status
        if ($status === Password::RESET_LINK_SENT) {
            Log::info('reset link send', ['email' => $email]);
            back()->with(['status' => __($status)]);
        }

        // redirect error
        return back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        // validate input
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email:dns'],
            'password' => ['required', 'min:8', 'max:20', 'confirmed'],
        ]);

        // reset password
        $status = Password::reset(

            // get input data
            $request->only('email', 'password', 'password_confirmation', 'token'),

            // reset function
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)]);
                Log::info('password reset', ['user' => $user]);
                $user->save();
                event(new PasswordReset($user));
            }
        );

        // redirect
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
