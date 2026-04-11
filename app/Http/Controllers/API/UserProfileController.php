<?php
namespace App\Http\Controllers\API;
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
                'profile_picture' => asset( $user->imageUrl),
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

        $imagePath = null;
        // if ($request->hasFile('profile_picture')) {
        //     $file = $request->file('profile_picture');
        //     $filename = time() . '.' . $file->getClientOriginalExtension();
        //     $file->storeAs('profile_images', $filename, 'public');
        //     $imagePath = 'profile_images/' . $filename;
        // }

        // if ($user->imageUrl && Storage::disk('public')->exists($user->imageUrl)) {
        //     Storage::disk('public')->delete($user->imageUrl);
        // }

        // $imagePath = $request->file('profile_picture')->store(
        //     'profile_pictures',
        //     'public'
        // );

        $imagePath = $request->file('profile_picture')->store('profile_images', 'public');

        $imageUrl = Storage::url($imagePath);

        $user->imageUrl = $imageUrl;
        $user->save();

        return response()->json([
            'message' => 'Profile picture updated successfully',
            // 'profile_picture' => asset('storage/' . $imagePath),
            'profile_picture' => asset($imageUrl),
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
            'profile_image' => $user->imageUrl ? asset(  $user->imageUrl) : null,
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
