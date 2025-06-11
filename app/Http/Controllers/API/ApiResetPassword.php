<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiResetPassword extends Controller
{
    public function apiResetPassword(Request $request)
    {
        try {
            // Validate email
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Generate OTP
            $otp = rand(100000, 999999);

            // Store OTP and timestamp in session (optional: could also use DB or cache)
            session([
                'otp' => $otp,
                'otp_email' => $request->email,
                'otp_created_at' => now(),
            ]);

            // Send OTP via email
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Your OTP for Password Reset');
            });

            return response()->json([
                'status' => 200,
                'message' => 'OTP sent to your email.',
                'email' => $request->email,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
