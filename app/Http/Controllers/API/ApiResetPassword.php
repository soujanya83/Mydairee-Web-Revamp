<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ApiResetPassword extends Controller
{

    public function apiUpdatePassword(Request $request)
    {
        try {
            // Validate request input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Get OTP record
            $otpRecord = DB::table('otpusers')->where('email', $request->email)->first();

            if (!$otpRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP verification record not found. Please verify OTP again.',
                ], 400);
            }

            // Check OTP expiry
            if (Carbon::parse($otpRecord->otp_timeing)->diffInMinutes(now()) > 10) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired. Please request a new one.',
                ], 400);
            }

            // Update user password
            $user = User::where('emailid', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete OTP record/
            DB::table('otpusers')->where('email', $request->email)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Password updated successfully. Please login.',
            ], 200);
        } catch (\Exception $e) {


            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(), // remove in production if needed
            ], 500);
        }
    }


    public function apiResendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $otp = rand(100000, 999999);

            // Store/update OTP in database
            DB::table('otpusers')->updateOrInsert(
                ['email' => $request->email],
                [
                    'otp' => $otp,
                    'otp_timeing' => now(),
                ]
            );

            // Send OTP to user
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Your OTP for Password Reset');
            });

            return response()->json([
                'status' => 200,
                'message' => 'A new OTP has been sent to your email.',
                'email' => $request->email
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend OTP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function apiResetPassword(Request $request)
    {
        try {
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
            $otp = rand(100000, 999999);
            DB::table('otpusers')->updateOrInsert(
                ['email' => $request->email], // Unique key to check
                [
                    'otp' => $otp,
                    'otp_timeing' => now(),
                ]
            );
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


    public function apiVerifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|digits:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $otpRecord = DB::table('otpusers')->where('email', $request->email)->first();

            if (!$otpRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP not found for this email.',
                ], 404);
            }
            if (Carbon::parse($otpRecord->otp_timeing)->diffInMinutes(now()) > 10) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired. Please request a new one.',
                ], 400);
            }
            if ($otpRecord->otp != $request->otp) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP. Please try again.',
                ], 401);
            }
            return response()->json([
                'status' => 200,
                'message' => 'OTP verified successfully.',
                'next' => route('reset_password_form'), // You can pass token/user if needed
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
