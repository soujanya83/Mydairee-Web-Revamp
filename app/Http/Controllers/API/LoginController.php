<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                ], 401);
            }

            Session::forget('auth_token');
            $token = $user->createToken('api-token')->plainTextToken;
            $datasave = DB::table('user_token')->updateOrInsert(
                ['userId' => $user->id],
                ['token' => $token,'updated_at'=>now()]
            );

            if ($datasave) {
                Session::put('auth_token', $token);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to save token.'
                ], 500);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Login Successfully',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function login(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email',
    //             'password' => 'required',
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Validation failed',
    //                 'errors' => $validator->errors(),
    //             ], 422);
    //         }
    //         $user = User::where('email', $request->email)->first();
    //         if (! $user || ! Hash::check($request->password, $user->password)) {
    //             return response()->json(['message' => 'Invalid credentials'], 401);
    //         }
    //         $token = $user->createToken('api-token')->plainTextToken;
    //         return response()->json([
    //             'token' => $token,
    //             'user' => $user,
    //             'status' => 200,
    //             'message' => 'Login Successfully'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Something went wrong. Please try again.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
