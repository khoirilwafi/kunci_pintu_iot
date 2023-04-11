<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\sendOTP;
use Exception;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $code_id =  $this->generateOTP($request);

        if ($code_id) {
            return view('auth.otp', ['email' => $request->user, 'id' => $code_id[0]->id]);
        }

        return redirect('logout');
    }

    protected function generateOTP(Request $request)
    {
        $user = $request->user();
        $id = $user->id;

        $data = array(
            'user_id'     => $id,
            'code_otp'    => rand(123456, 999999),
            'valid_until' => Carbon::now()->addMinutes(5),
        );

        try {
            Otp::updateOrCreate(['user_id' => $id], $data);
            $user->notify(new sendOTP($data['code_otp']));
        } catch (Exception $error) {
            return null;
        }

        return Otp::where('user_id', $id)->get('id');
    }

    public function verify(Request $request)
    {
        $now = Carbon::now();

        $data_otp = $request->validate([
            'id'  => ['required', 'string'],
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        $otp = Otp::where('id', $data_otp['id'])->where('code_otp', $data_otp['otp'])->first();

        if ($otp && $now->lessThanOrEqualTo($otp->valid_until)) {
            User::where('id', $otp->user_id)->update(['email_verified_at' => $now]);
            Otp::with('id', $data_otp['id'])->delete();
            return redirect()->intended('dashboard');
        } elseif ($otp) {
            return redirect()->back()->with('failed', 'OTP sudah kadaluarsa');
        }

        return redirect()->back()->with('failed', 'OTP tidak sesuai');
    }
}
