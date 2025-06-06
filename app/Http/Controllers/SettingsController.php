<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User; // Add this at the top if not already added
use App\Models\Center; // Add this at the top if not already added
use App\Models\Usercenter; // Add this at the top if not already added


class SettingsController extends Controller
{
   
    public function superadminSettings()
    {
        $superadmins = User::where('userType', 'Superadmin')->get();
    
        return view('settings.superadmin', compact('superadmins'));
    }

    public function store(Request $request)
    {
        // Step 1: Validate input
        $request->validate([
            // 'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'contactNo' => 'required|string|min:9',
            'name' => 'required|string',
            'gender' => 'required',
            // 'title' => 'required',
            'centerName' => 'required|string',
            'adressStreet' => 'required|string',
            'addressCity' => 'required|string',
            'addressState' => 'required|string',
            'addressZip' => 'required',
            'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);
    
        // Step 2: Create user
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->emailid = $request->email;
        $user->userType = 'Superadmin';
        $user->center_status = 1;
    
        // Step 3: Handle image upload
        if ($request->hasFile('imageUrl')) {
            $file = $request->file('imageUrl');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/superadmins'), $filename);
            $user->imageUrl = 'uploads/superadmins/' . $filename;
        }
    
        // Save user
        $user->save();
    
        // Step 4: Update 'userid' field with the auto-generated 'id'
        $user->userid = $user->id;
        $user->save();
    
        // Step 5: Add data in Center model
        $center = new Center();
        $center->userid = $user->id;
        $center->centerName = $request->centerName;
        $center->adressStreet = $request->adressStreet;
        $center->addressCity = $request->addressCity;
        $center->addressState = $request->addressState;
        $center->addressZip = $request->addressZip;
        $center->save();
    
        // Step 6: Add data in Usercenter model
        $userCenter = new Usercenter();
        $userCenter->userid = $user->id;
        $userCenter->centerid = $center->id;
        $userCenter->save();
    
        return response()->json(['status' => 'success']);
    }

    public function edit($id)
{
    $user = User::findOrFail($id);
    return response()->json($user);
}


public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'contactNo' => 'required|string|min:9',
        'gender' => 'required',
        'password' => 'nullable|string|min:6',
        'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->emailid = $request->email;
    $user->contactNo = $request->contactNo;
    $user->gender = $request->gender;

    // Only update password if provided
    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

   // Step 3: Handle image upload
if ($request->hasFile('imageUrl')) {
    // Delete old image if it exists
    if ($user->imageUrl && file_exists(public_path($user->imageUrl))) {
        unlink(public_path($user->imageUrl));
    }

    $file = $request->file('imageUrl');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('uploads/superadmins'), $filename);
    $user->imageUrl = 'uploads/superadmins/' . $filename;
}
    $user->save();

    return response()->json(['status' => 'success']);
}



public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'User not found']);
    }

    try {
        $user->delete();
        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Delete failed']);
    }
}


public function center_settings()
{
    $superadmins = User::where('userType', 'Superadmin')->get();

    return view('settings.center', compact('superadmins'));
}

    

public function staff_settings()
{
    $authId = Auth::user()->id; 
    $centerid = Session('user_center_id');

    // Get all user IDs in the center
    $usersid = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

    // Exclude current user and Superadmins
    $staff = User::whereIn('id', $usersid)
                ->where('id', '!=', $authId)
                ->where('userType', '!=', 'Superadmin')
                ->get();

    return view('settings.staff', compact('staff'));
}




}
