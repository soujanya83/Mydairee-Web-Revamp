<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class RagisterController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'username'    => 'required|string|max:255|unique:users,username|regex:/^\S*$/u',
                'contactNo'   => 'required|digits_between:7,15',
                'dob'         => 'required|date',
                'emailid'     => 'required|email|unique:users,emailid',
                'password'    => 'required|string|min:6',
                'gender'      => 'required|in:MALE,FEMALE,OTHERS',
                'imageUrl'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'title'       => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('imageUrl')) {
                $imagePath = $request->file('imageUrl')->store('profile_images', 'public');
            }

            // Create user
            $user = new User();
            $user->name         = $request->name;
            $user->username     = $request->username;
            $user->contactNo    = $request->contactNo;
            $user->dob          = $request->dob;
            $user->email        = $request->emailid;
            $user->emailid      = $request->emailid;
            $user->password     = Hash::make($request->password);
            $user->gender       = $request->gender;
            $user->imageUrl     = $imagePath ?? '';
            $user->title        = $request->title;
            $user->userType     = 'Superadmin';
            $user->AuthToken    = Str::random(128);
            $user->status       = 'ACTIVE';
            $user->deviceid     = $request->deviceid ?? '';
            $user->devicetype   = $request->devicetype ?? '';
            $user->companyLogo  = $request->companyLogo ?? '';
            $user->theme        = $request->theme ?? 1;
            $user->image_position = $request->image_position ?? '';
            $user->created_by   = auth::user()->id ?? 0;

            $user->save();

            // Set userid = id
            $user->userid = $user->id;
            $user->save();

            // Generate API token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Prepare response
            return response()->json([
                'status' => 201,
                'message' => 'Superadmin created successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
