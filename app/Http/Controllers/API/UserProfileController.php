<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function getProfilePicture(Request $request)
    {
        $user = $request->user();
            return response()->json([
                'profile_picture' => asset('storage/' . $user->imageUrl),
            ]);
    }

    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        //profile_picture  on this key form data image object want
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if ($user->imageUrl && Storage::disk('public')->exists($user->imageUrl)) {
            Storage::disk('public')->delete($user->imageUrl);
        }

        $path = $request->file('profile_picture')->store(
            'profile_pictures',
            'public'
        );

        $user->imageUrl = $path;
        $user->save();

        return response()->json([
            'message' => 'Profile picture updated successfully',
            'profile_picture' => asset('storage/' . $path),
        ]);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->contactNo,
            'gender' => $user->gender,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = $request->user();
        $user->name = $request->name;
        $user->contactNo = $request->phone;
        $user->save();
        return response()->json(['message' => 'Profile updated successfully', 'name' => $user->name, 'phone' => $user->contactNo]);
    }
}
