<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
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
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid credentials',
    //             ], 401);
    //         }

    //         Session::forget('auth_token');
    //         $token = $user->createToken('api-token')->plainTextToken;
    //         $datasave = DB::table('user_token')->updateOrInsert(
    //             ['userId' => $user->id],
    //             ['token' => $token,'updated_at'=>now()]
    //         );

    //         if ($datasave) {
    //             Session::put('auth_token', $token);
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Failed to save token.'
    //             ], 500);
    //         }
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Login Successfully',
    //             'token' => $token,
    //             'user' => $user,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }



    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid email or password.',
                ], 401);
            }

            $plainPassword = $request->password;
            $storedPassword = $user->password;

            $isBcrypt = false;
            $isLegacy = false;

            // ✅ Check bcrypt password
            if (Str::startsWith($storedPassword, '$2y$')) {
                $isBcrypt = Hash::check($plainPassword, $storedPassword);
            } else {
                // ✅ Check SHA1 legacy
                $isLegacy = sha1($plainPassword) === $storedPassword;
            }

            if ($isBcrypt || $isLegacy) {
                // ⏫ Upgrade SHA1 to bcrypt if matched
                if ($isLegacy) {
                    $user->password = Hash::make($plainPassword);
                    $user->save();
                }

                if($user->userType == "Superadmin" || $user->userType == "Staff"){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Superadmin and Staff login is currently under maintenance. Please try again later.',
                    ], 401);
                }

                // ✅ Generate token
                $token = $user->createToken('api-token')->plainTextToken;

                // ✅ Save token in user_token table
                DB::table('user_token')->updateOrInsert(
                    ['userId' => $user->id],
                    ['token' => $token, 'updated_at' => now()]
                );

                // ✅ Optional session (if used for web panels)
                Session::put('auth_token', $token);

                return response()->json([


                    'status'  => 'success',
                    'message' => 'Login successful',
                    'token'   => $token,
                    'user'    => $user,
                ], 200);
            }

            // ❌ Wrong password
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password.',
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
