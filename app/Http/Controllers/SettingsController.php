<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User; 
use App\Models\Center; 
use App\Models\Usercenter; 
use App\Models\Child; 
use App\Models\Childparent; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;



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
        $center->user_id = $user->id;
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
        $userid = Auth::user()->id;

        $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
        // Get all centers where the ID is in the list of center IDs
        $centers = Center::whereIn('id', $centerIds)->get();

        return view('settings.center', compact('centers'));
    }

    public function center_store(Request $request)
    {

        $request->validate([
            'centerName' => 'required|string',
            'adressStreet' => 'required|string',
            'addressCity' => 'required|string',
            'addressState' => 'required|string',
            'addressZip' => 'required|min:3',
        ]);

        // Step 5: Add data in Center model
        $center = new Center();
        $center->user_id = Auth::user()->id;
        $center->centerName = $request->centerName;
        $center->adressStreet = $request->adressStreet;
        $center->addressCity = $request->addressCity;
        $center->addressState = $request->addressState;
        $center->addressZip = $request->addressZip;
        $center->save();

        $userCenter = new Usercenter();
        $userCenter->userid = Auth::user()->id;
        $userCenter->centerid = $center->id;
        $userCenter->save();

        return response()->json(['status' => 'success']);
    }

    public function center_edit($id)
    {
        $user = Center::findOrFail($id);
        return response()->json($user);
    }

    public function center_update(Request $request, $id)
    {
        $center = Center::findOrFail($id);

        $request->validate([
            'centerName' => 'required|string',
            'adressStreet' => 'required|string',
            'addressCity' => 'required|string',
            'addressState' => 'required|string',
            'addressZip' => 'required|min:3',
        ]);

        $center->centerName = $request->centerName;
        $center->adressStreet = $request->adressStreet;
        $center->addressCity = $request->addressCity;
        $center->addressState = $request->addressState;
        $center->addressZip = $request->addressZip;
        $center->save();




        return response()->json(['status' => 'success']);
    }


    public function destroycenter($id)
    {
        $center = Center::find($id);

        if (!$center) {
            return response()->json(['status' => 'error', 'message' => 'Center not found']);
        }

        try {
            $center->delete();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Delete failed']);
        }
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
            ->where('userType', 'Staff')
            ->get();


        // dd($staff);

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }


        return view('settings.staff', compact('staff', 'centers'));
    }


    public function changeCenter(Request $request)
    {
        Session(['user_center_id' => $request->center_id]);
        return response()->json(['status' => 'success']);
    }


    public function staff_store(Request $request)
    {
        // Step 1: Validate input
        $request->validate([
            // 'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'contactNo' => 'required|string|min:9',
            'name' => 'required|string',
            'gender' => 'required',
            'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        // Step 2: Create user
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->emailid = $request->email;
        $user->userType = 'Staff';
        $user->center_status = 1;

        // Step 3: Handle image upload
        if ($request->hasFile('imageUrl')) {
            $file = $request->file('imageUrl');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/staffs'), $filename);
            $user->imageUrl = 'uploads/staffs/' . $filename;
        }

        // Save user
        $user->save();

        // Step 4: Update 'userid' field with the auto-generated 'id'
        $user->userid = $user->id;
        $user->save();

        $centerid = Session('user_center_id');

        $userCenter = new Usercenter();
        $userCenter->userid = $user->id;
        $userCenter->centerid = $centerid;
        $userCenter->save();




        return response()->json(['status' => 'success']);
    }


    public function staff_edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }



    public function staff_update(Request $request, $id)
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
            $file->move(public_path('uploads/staffs'), $filename);
            $user->imageUrl = 'uploads/staffs/' . $filename;
        }
        $user->save();

        return response()->json(['status' => 'success']);
    }


    public function staff_destroy($id)
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







    public function parent_settings()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Get all user IDs in the center
        $usersid = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

        // Exclude current user and Superadmins
        $parents = User::whereIn('id', $usersid)
            ->where('id', '!=', $authId)
            ->where('userType', 'Parent')
            ->with(['children' => function ($query) {
                $query->select('child.id', 'name', 'lastname');
            }])
            ->get();

        // dd($parents);

        $children = Child::where('centerid', $centerid)->get();
        // dd($children);

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }


        return view('settings.parent', compact('parents', 'centers', 'children'));
    }


    public function parent_store(Request $request)
    {
        // Step 1: Validate input
        $request->validate([
            // 'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'contactNo' => 'required|string|min:9',
            'name' => 'required|string',
            'gender' => 'required',
            'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'children' => 'required|array|min:1', // Ensure children is a non-empty array
            'children.*.childid' => 'required|string', // Each childid must be a non-empty string
            'children.*.relation' => 'required|string|in:Father,Mother,Brother,Sister,Relative', // Restrict relation to specific values
        ]);

        // Step 2: Create user
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->emailid = $request->email;
        $user->userType = 'Parent';
        $user->center_status = 1;

        // Step 3: Handle image upload
        if ($request->hasFile('imageUrl')) {
            $file = $request->file('imageUrl');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/parents'), $filename);
            $user->imageUrl = 'uploads/parents/' . $filename;
        }

        // Save user
        $user->save();

        // Step 4: Update 'userid' field with the auto-generated 'id'
        $user->userid = $user->id;
        $user->save();

        $centerid = Session('user_center_id');


        // Step 6: Add data in Usercenter model
        $userCenter = new Usercenter();
        $userCenter->userid = $user->id;
        $userCenter->centerid = $centerid;
        $userCenter->save();


        foreach ($request->children as $childData) {
            Childparent::create([
                'parentid' => $user->id, // the new parent's ID
                'childid' => $childData['childid'],
                'relation' => $childData['relation'],
            ]);
        }

        return response()->json(['status' => 'success']);
    }


    public function getParentData($id)
    {
        $parent = User::findOrFail($id);

        $children = Childparent::where('parentid', $id)
            ->with(['child' => function ($query) {
                $query->select('id', 'name', 'lastname');
            }])
            ->get()
            ->map(function ($rel) {
                return [
                    'id' => $rel->id,
                    'childid' => $rel->childid,
                    'relation' => $rel->relation,
                ];
            });

        return response()->json([
            'parent' => $parent,
            'children' => $children
        ]);
    }


    public function parent_update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id' => 'required|exists:users,id',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'nullable|min:6',
            'contactNo' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'children' => 'required|array|min:1',
            'children.*.childid' => 'required',
            'children.*.relation' => 'required',
        ]);

        $user = User::findOrFail($request->id);
        $user->fill([
            'name' => $request->name,
            'emailid' => $request->email,
            'contactNo' => $request->contactNo,
            'gender' => $request->gender,
        ]);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('imageUrl')) {
            $file = $request->file('imageUrl');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/parents'), $filename);
            $user->imageUrl = 'uploads/parents/' . $filename;
        }

        $user->save();

        // Sync children
        $existing = Childparent::where('parentid', $user->id)->pluck('id')->toArray();
        $submitted = collect($request->children)->pluck('id')->filter()->toArray();

    $user = User::findOrFail($request->id);
    $user->fill([
        'name' => $request->name,
        'emailid' => $request->email,
        'email' => $request->email,
        'contactNo' => $request->contactNo,
        'gender' => $request->gender,
    ]);
        // Delete removed relations
        $toDelete = array_diff($existing, $submitted);
        if (!empty($toDelete)) {
            Childparent::whereIn('id', $toDelete)->delete();
        }

        // Add/update
        foreach ($request->children as $data) {
            if (!empty($data['id'])) {
                // Update
                $cp = Childparent::find($data['id']);
                if ($cp) {
                    $cp->update([
                        'childid' => $data['childid'],
                        'relation' => $data['relation'],
                    ]);
                }
            } else {
                // New relation
                Childparent::create([
                    'parentid' => $user->id,
                    'childid' => $data['childid'],
                    'relation' => $data['relation'],
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }


    public function getprofile_page()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');


        $user = User::where('userid', $authId)->first();

        return view('settings.profile', compact('user'));
    }


    public function uploadImage(Request $request)
    {

        $request->validate([
            'imageUrl' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $user = auth()->user();

        if ($request->hasFile('imageUrl')) {
            // Remove old image if exists
            if ($user->imageUrl && file_exists(public_path($user->imageUrl))) {
                unlink(public_path($user->imageUrl));
            }

            $file = $request->file('imageUrl');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Choose folder based on userType
            switch ($user->userType) {
                case 'Superadmin':
                    $folder = 'uploads/superadmins';
                    break;
                case 'Staff':
                    $folder = 'uploads/staffs';
                    break;
                case 'Parent':
                    $folder = 'uploads/parents';
                    break;
                default:
                    $folder = 'uploads/others';
                    break;
            }

            // Ensure directory exists
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $file->move(public_path($folder), $filename);
            $user->imageUrl = $folder . '/' . $filename;
            $user->save();

            return response()->json(['status' => 'success', 'success' => true, 'image_url' => asset($user->imageUrl)]);
        }

        return response()->json(['success' => false]);
    }



    public function profileupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contactNo' => 'required|digits_between:7,15',
            'gender' => 'required|in:MALE,FEMALE,OTHERS',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->emailid = $request->email;
        $user->contactNo = $request->contactNo;
        $user->gender = $request->gender;
        $user->save();

        return response()->json(['status' => 'success', 'success' => true]);
    }


    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'new_password.confirmed' => 'New password and confirmation do not match.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'error' => 'Current password is incorrect.',
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['status' => 'success']);
    }
}
