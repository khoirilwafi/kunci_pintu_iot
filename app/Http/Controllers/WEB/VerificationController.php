<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendOTPNotification;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        // get user data
        $user = $request->user();

        // check if last otp is not available
        $last_otp = Otp::where('user_id', $user->id)->first();
        if ($last_otp == null) {

            // generate new otp code
            $otp  = $this->generateOTP($user->id);

            // send to user via email
            $user->notify(new SendOTPNotification($otp));
            Log::info('otp send', ['user' => $user]);
        }

        return view('auth.otp', ['id' => $user->id]);
    }

    protected function generateOTP($id)
    {
        // prepare data
        $data = array(
            'user_id'     => $id,
            'code_otp'    => rand(123456, 999999),
            'valid_until' => Carbon::now()->addMinutes(5),
        );

        // insert into db
        Otp::updateOrCreate(['user_id' => $id], $data);

        return $data['code_otp'];
    }

    public function verify(Request $request)
    {
        // get date and time now
        $now = Carbon::now();

        // validate input
        $data_otp = $request->validate([
            'id'  => ['required', 'string'],
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        // get otp
        $otp = Otp::where('user_id', $data_otp['id'])->where('code_otp', $data_otp['otp'])->first();

        // check otp
        if ($otp && $now->lessThanOrEqualTo($otp->valid_until)) {

            // update user data and delete otp
            User::where('id', $otp->user_id)->update(['email_verified_at' => $now]);
            Otp::where('user_id', $data_otp['id'])->delete();
            Log::info('verify email', ['user' => $request->user()]);
            return redirect()->intended('dashboard');
        } elseif ($otp) {

            // otp expired
            Otp::where('user_id', $data_otp['id'])->delete();

            // logout
            Auth::logout();

            // delete session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // redirect
            return redirect('/login')->with('login_failed', 'OTP sudah kadaluarsa');
        }

        // otp not match
        return redirect()->back()->with('failed', 'OTP tidak sesuai');
    }
}
