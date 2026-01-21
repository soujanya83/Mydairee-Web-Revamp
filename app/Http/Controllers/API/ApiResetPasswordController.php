<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiResetPasswordController extends Controller
{
    // Authenticated user password update
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
    public function requestReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid email'], 422);
        }

        $otp = rand(100000, 999999);
        cache()->put('otp_' . $request->email, $otp, now()->addMinutes(10));

        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email)->subject('Your OTP for Password Reset');
        });

        return response()->json(['message' => 'OTP sent to your email']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $otp = cache()->get('otp_' . $request->email);
        if (!$otp) {
            return response()->json(['error' => 'OTP expired or not found'], 422);
        }
        if ($otp != $request->otp) {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }

        cache()->put('otp_verified_' . $request->email, true, now()->addMinutes(10));
        return response()->json(['message' => 'OTP verified']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!cache()->get('otp_verified_' . $request->email)) {
            return response()->json(['error' => 'OTP not verified'], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        cache()->forget('otp_' . $request->email);
        cache()->forget('otp_verified_' . $request->email);

        return response()->json(['message' => 'Password updated successfully']);
    }
}
