<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    // 1. GET /api/user/profile-picture
    public function getProfilePicture(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'profile_picture' => url($user->imageUrl),
        ]);
    }

    // 2. PUT /api/user/profile-picture
      public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|string', // adjust to file if needed
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

            $imagePath = null;
        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profile_images', 'public');
        }

        $user = $request->user();
        $user->profile_picture = $imagePath;
        $user->save();
        return response()->json(['message' => 'Profile picture updated successfully', 'profile_picture' => $user->profile_picture]);
    }

    // 3. GET /api/user/profile
    public function getProfile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'profile_picture' => $user->profile_picture,
        ]);
    }

    // 4. PUT /api/user/profile
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|string|max:10',
            'email' => 'required|email|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = $request->user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->save();
        return response()->json(['message' => 'Profile updated successfully', 'name' => $user->name, 'phone' => $user->phone]);
    }
}
