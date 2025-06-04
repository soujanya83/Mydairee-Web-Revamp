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

    public function resend_otp(Request $request)
    {
        $otp = rand(100000, 999999);
        session()->forget(['otp', 'otp_created_at']);
        session([
            'otp' => $otp,
            'otp_email' => session('otp_email'),
            'otp_created_at' => now(), // set timestamp for expiry check
        ]);
        Mail::send('emails.otp', ['otp' => $otp], function ($message) {
            $message->to(session('otp_email'))
                ->subject('Your OTP for Password Reset');
        });
        return redirect()->route('verify_otp')->with('success', 'A new OTP has been sent to your email.');
    }


    public function showResetForm()
    {
        return view('authentication.reset-password-form'); // Adjust the view path as needed
    }
    public function reset_password(Request $request)
    {
        session()->forget('otp_email');

        // Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Clear any existing OTP session
        session()->forget('otp');

        // Store OTP and timestamp in session
        session([
            'otp' => $otp,
            'otp_email' => $request->email,
            'otp_created_at' => now(), // store creation time
        ]);

        // Send OTP via email
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

        $sessionOtp = session('otp');
        $otpCreatedAt = session('otp_created_at');

        // Check if OTP exists and was created within 10 minutes
        if (!$sessionOtp || !$otpCreatedAt || now()->diffInMinutes($otpCreatedAt) > 10) {
            return back()->with('otp_error', 'OTP has expired. Please request a new one.')->withInput();
        }

        if ($sessionOtp != $request->otp) {
            return back()->with('otp_error', 'Invalid OTP. Please try again.')->withInput();
        }

        return redirect()->route('reset_password_form')->with('success', 'OTP verified successfully.');
    }



    // public function update_password(Request $request)
    // {

    //     $request->validate([
    //         'password' => 'required|confirmed|min:6',
    //     ]);

    //     $user = User::where('email', session('otp_email'))->first();

    //     $user->password = bcrypt($request->password);
    //     $user->save();

    //     session()->forget(['otp', 'otp_email']);

    //     return redirect()->route('authentication.login')->with('success', 'Password updated successfully.');
    // }

    public function updatePassword(Request $request)
    {
        // Validate form input
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Check if the email matches the one stored in session during OTP verification
        if ($request->email !== session('otp_email')) {
            return back()->withErrors(['email' => 'Email does not match the verified OTP email.'])->withInput();
        }

        // Fetch user and update password
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No user found with this email.'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear the OTP session
        session()->forget('otp_email');
        session()->forget(['otp', 'otp_created_at']);

        return redirect()->route('authentication.login')->with('success', 'Password updated successfully.');
    }
}
