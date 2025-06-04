<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Controller
{
    public function showResetForm()
    {
        return view('authentication.reset-password-form'); // Adjust the view path as needed
    }
    public function reset_password(Request $request)
    {
        // Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $otp = rand(100000, 999999);
        session()->forget('otp');
        // Store OTP in session (or optionally in database)
        session(['otp' => $otp, 'otp_email' => $request->email]);

        // Send OTP email
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your OTP for Password Reset');
        });


        return redirect()->route('verify_otp')->with('success', 'OTP sent to your email.');
    }

    public function show_verify_otp()
    {
        return view('authentication.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $sessionOtp = session('otp'); // OTP saved in session earlier

        if ($sessionOtp != $request->otp) {
            return back()->with('otp_error', 'Invalid OTP. Please try again.')->withInput();
        }
        return redirect()->route('reset_password_form')->with('success', 'OTP verified successfully.');
    }


    public function update_password(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('email', session('otp_email'))->first();

        $user->password = bcrypt($request->password);
        $user->save();

        session()->forget(['otp', 'otp_email']);

        return redirect()->route('authentication.login')->with('success', 'Password updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['otp' => 'Invalid OTP or email'])->withInput();
        }
        $user->password = Hash::make($request->password);
        $user->save();
        session()->forget('otp'); // Optional: clear OTP
        return redirect()->route('authentication.login')->with('success', 'Password updated successfully.');
    }
}
