<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User; // Add this at the top if not already added
use App\Models\Center; // Add this at the top if not already added
use App\Models\Usercenter; // Add this at the top if not already added
use App\Models\Child; // Add this at the top if not already added
use App\Models\Childparent; // Add this at the top if not already added
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
class SettingsController extends Controller
{

    public function filterByParentName(Request $request)
    {
        $request->validate([
            'parent_name' => 'nullable|string'
        ]);

        $authId = Auth::id();
        $centerid = Session('user_center_id');

        $userIds = Usercenter::where('centerid', $centerid)->pluck('userid');

        $query = User::with(['children' => function ($query) {
            $query->select('child.id', 'name', 'lastname');
        }])
            ->whereIn('id', $userIds)
            ->where('id', '!=', $authId)
            ->where('userType', 'Parent');

        if (!empty($request->parent_name)) {
            $query->where('name', 'like', '%' . $request->parent_name . '%');
        }

        $parents = $query->get();

        return response()->json([
            'success' => true,
            'parents' => $parents->map(function ($parent) {
                return [
                    'id' => $parent->id,
                    'name' => $parent->name,
                    'email' => $parent->email,
                    'contactNo' => $parent->contactNo,
                    'gender' => $parent->gender,
                    'imageUrl' => $parent->imageUrl ? asset($parent->imageUrl) : null,
                    'children' => $parent->children->map(function ($child) {
                        return [
                            'name' => $child->name,
                            'lastname' => $child->lastname,
                            'relation' => $child->pivot->relation ?? '',
                        ];
                    })
                ];
            })
        ]);
    }


    public function filterStaffByName(Request $request)
    {
        $request->validate([
            'staff_name' => 'nullable|string'
        ]);

        $authId = Auth::id();
        $centerid = Session('user_center_id');

        // Get all user IDs in the center
        $usersid = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

        // Base query for staff
        $query = User::whereIn('id', $usersid)
            ->where('id', '!=', $authId)
            ->where('userType', 'Staff');

        // Apply filter if name is provided
        if (!empty($request->staff_name)) {
            $query->where('name', 'like', '%' . $request->staff_name . '%');
        }

        $staff = $query->get();

        return response()->json([
            'success' => true,
            'staff' => $staff
        ]);
    }


    public function filterByAdminName(Request $request)
    {
        $validated = $request->validate([
            'admin_name' => ['nullable', 'string']
        ]);

        $superadmins = User::query()
            ->where('userType', 'Superadmin')
            ->when($validated['admin_name'] ?? null, function ($query, $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->get();

        return response()->json([
            'success' => true,
            'superadmins' => $superadmins
        ]);
    }


    public function filterByCenterName(Request $request)
    {
        $request->validate([
            'centername' => 'nullable|string'
        ]);

        $userid = Auth::id();
        $centerIds = Usercenter::where('userid', $userid)->pluck('centerid');

        $query = Center::whereIn('id', $centerIds);

        if ($request->filled('centername')) {
            $query->where('centerName', 'like', '%' . $request->centername . '%');
        }

        $centers = $query->get();

        return response()->json([
            'success' => true,
            'centers' => $centers
        ]);
    }

public function updateStatusSuperadmin(Request $request)
{
    try {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'id'     => 'required|integer',
            'status' => 'required|string|in:ACTIVE,IN-ACTIVE,PENDING'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // ✅ Check if user exists
        $user = User::where('userid', $validated['id'])->first();
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.'
            ], 404);
        }

        // ✅ Update status
        $user->status = $validated['status'];
        if (!$user->save()) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to update user status. Please try again.'
            ], 500);
        }

        // ✅ Success
        return response()->json([
            'status'  => true,
            'message' => 'Status updated successfully to ' . $user->status,
            'data'    => [
                'id'     => $user->userid,
                'status' => $user->status
            ]
        ], 200);

    } catch (\Exception $e) {
        // ✅ Catch unexpected errors
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong.',
            'error'   => $e->getMessage()
        ], 500);
    }
}


    public function updateUserPermissions(Request $request, $userId)
    {
        $permissions = $request->input('permissions', []);
        // dd( $permissions);
        $allColumns = Schema::getColumnListing('permissions');

        // Exclude non-permission columns
        $exclude = ['id', 'userid', 'created_at', 'updated_at'];
        $permissionCols = array_diff($allColumns, $exclude);

        // Find or create record
        $permissionRow = Permission::where('userid', $userId)->first();

        $data = ['userid' => $userId];

        foreach ($permissionCols as $col) {
            $data[$col] = isset($permissions[$col]) ? 1 : 0;
        }

        if ($permissionRow) {
            Permission::where('userid', $userId)->update($data);
        } else {
            Permission::insert($data);
        }

        return redirect()->back()->with('success', 'Permissions updated successfully!');
    }




    function assigned_permissions()
    {
        $assignedUserList = User::select('users.id', 'users.name')
            ->join('permissions', 'permissions.userid', '=', 'users.id')
            ->where('permissions.centerid', session('user_center_id'))
            ->distinct()
            ->get();
        return view('settings.assigned_permissions_list', compact('assignedUserList'));
    }

    public function manage_permissions()
    {
        // dd('here');
        $users = User::where(['users.userType' => 'Staff', 'usercenters.centerid' => session('user_center_id')])->join('usercenters', 'usercenters.userid', '=', 'users.userid')->get();

        $permissionColumns = collect(Schema::getColumnListing('permissions'))
            ->filter(function ($column) {
                return !in_array($column, ['id', 'userid', 'centerid']); // exclude default columns
            })
            ->map(function ($column) {
                $label = Str::headline($column);
                $label = str_replace('Qip', 'QIP', $label);
                return [
                    'name' => $column,
                    'label' => $label
                ];
            })
            ->sortBy('label')
            ->values();

        // Group filters
        $ObservationPermissions = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'observation'))->values();
        $RoomPermissions        = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'room'))->values();
        $DailyPermissions       = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'daily'))->values();
        $ReflectionPermissions  = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'reflection'))->values();
        $QipPermissions         = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'qip'))->values();
        $ProgramPlanPermissions = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'programplan'))->values();
        $AnnouncementPermissions = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'announcement'))->values();
        $SurveyPermissions      = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'survey'))->values();
        $RecipePermissions      = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'recipe'))->values();
        $MenuPermissions        = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'menu'))->values();
        $UsersPermissions       = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'users'))->values();
        $CentersPermissions     = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'centers'))->values();
        $ChildPermissions       = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'child'))->values();
        $ParentPlanPermissions  = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'parent'))->values();
        $ProgressPermissions    = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'progress'))->values();
        $LessonPermissions      = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'lesson'))->values();
        $AssessmentPermissions  = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'assessment'))->values();
        $AccidentsPermissions   = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'accidents'))->values();
        $SnapshotsPermissions   = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'snapshots'))->values();

        // Combine all matched permissions
        $allMatched = $ObservationPermissions
            ->merge($RoomPermissions)
            ->merge($DailyPermissions)
            ->merge($ReflectionPermissions)
            ->merge($QipPermissions)
            ->merge($ProgramPlanPermissions)
            ->merge($AnnouncementPermissions)
            ->merge($SurveyPermissions)
            ->merge($RecipePermissions)
            ->merge($MenuPermissions)
            ->merge($UsersPermissions)
            ->merge($CentersPermissions)
            ->merge($ChildPermissions)
            ->merge($ParentPlanPermissions)
            ->merge($ProgressPermissions)
            ->merge($LessonPermissions)
            ->merge($AssessmentPermissions)
            ->merge($AccidentsPermissions)
            ->merge($SnapshotsPermissions)
            ->pluck('name')
            ->toArray();

        // Get other permissions (not matched above)
        $otherPermissions = $permissionColumns->reject(function ($item) use ($allMatched) {
            return in_array($item['name'], $allMatched);
        })->values();

        return view(
            'settings.new_alluser_assign_permission',
            compact(
                'users',
                'SnapshotsPermissions',
                'permissionColumns',
                'ObservationPermissions',
                'RoomPermissions',
                'AccidentsPermissions',
                'AssessmentPermissions',
                'LessonPermissions',
                'ProgressPermissions',
                'ParentPlanPermissions',
                'ChildPermissions',
                'CentersPermissions',
                'UsersPermissions',
                'MenuPermissions',
                'RecipePermissions',
                'SurveyPermissions',
                'AnnouncementPermissions',
                'ProgramPlanPermissions',
                'QipPermissions',
                'ReflectionPermissions',
                'DailyPermissions',
                'otherPermissions'
            )
        );
    }



    public function assign_user_permissions(Request $request)
    {
        try {
            // dd($request->admin);
            $userIds = $request->input('user_ids', []);
            $checkedPermissions = $request->input('permissions', []);

            // ✅ Check if centerid exists in session
            $centerId = session('user_center_id');
            if (empty($centerId)) {
                return redirect()->back()->with('error', 'Center ID is missing in session.');
            }

            foreach ($userIds as $userId) {
                // Check if the record exists
                $user = User::find($userId);

            if (isset($validated['admin'])) {
                $user->admin = $validated['admin'];
                $user->save();
            }
              
                $permissionRecord = Permission::where('userid', $userId)->first();

                if (!$permissionRecord) {
                    $permissionRecord = new Permission();
                    $permissionRecord->userid = $userId;
                    $permissionRecord->centerid = $centerId;
                    
                }

                // Get all permission column names from table (excluding id, userid, centerid)
                $allColumns = Schema::getColumnListing('permissions');
                $permissionColumns = collect($allColumns)
                    ->filter(fn($col) => !in_array($col, ['id', 'userid', 'centerid']));

                // Set 1 for checked, 0 for unchecked
                foreach ($permissionColumns as $col) {
                    $permissionRecord->{$col} = isset($checkedPermissions[$col]) ? 1 : 0;
                }

                $permissionRecord->save();
            }

            return redirect()->back()->with('success', 'Permissions updated successfully!');
        } catch (\Exception $e) {
            // dd($e);
            // ✅ Catch unexpected errors
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }




    // public function assign_user_permissions(Request $request)
    // {
    //     $userIds = $request->input('user_ids', []);
    //     $checkedPermissions = $request->input('permissions', []); // ['addRoom' => '1', 'editRoom' => '1', ...]


    //     foreach ($userIds as $userId) {
    //         // Check if the record exists
    //         $permissionRecord = Permission::where('userid', $userId)->first();

    //         if (!$permissionRecord) {
    //             $permissionRecord = new Permission();
    //             $permissionRecord->userid = $userId;
    //             $permissionRecord->centerid = session('user_center_id'); // ✅ Store from session
    //         }

    //         // Get all permission column names from table (excluding id, userid, centerid)
    //         $allColumns = Schema::getColumnListing('permissions');

    //         $permissionColumns = collect($allColumns)->filter(fn($col) => !in_array($col, ['id', 'userid', 'centerid']));
    //         // Set 1 for checked, 0 for unchecked
    //         foreach ($permissionColumns as $col) {
    //             $permissionRecord->{$col} = isset($checkedPermissions[$col]) ? 1 : 0;
    //         }

    //         $permissionRecord->save();
    //     }

    //     return redirect()->back()->with('success', 'Permissions updated successfully!');
    // }




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

        $this->sendWelcomeEmail($request->email, $request->password, $request->children);


        return response()->json(['status' => 'success']);
    }


    private function sendWelcomeEmail($email, $password, $childrenData)
    {
        try {
            // Get child details for each child ID
            $childrenDetails = [];
            foreach ($childrenData as $child) {
                $childId = $child['childid'];
                $relation = $child['relation'];

                // Query to get child details from Child model
                $childInfo = Child::select('name', 'lastname', 'dob', 'imageUrl')
                    ->where('id', $childId)
                    ->first();

                if ($childInfo) {
                    $childArray = $childInfo->toArray();
                    $childArray['relation'] = $relation;
                    $childrenDetails[] = $childArray;
                }
            }

            // Generate HTML for each child
            $childrenHTML = '';
            foreach ($childrenDetails as $child) {
                // Handle child image URL
                if (!empty($child['imageUrl'])) {
                    $childImageUrl = asset($child['imageUrl']);
                } else {
                    $childImageUrl = 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=150&h=150&fit=crop&crop=face';
                }

                $childFullName = trim($child['name'] . ' ' . $child['lastname']);
                $dob = !empty($child['dob']) ? date('d M Y', strtotime($child['dob'])) : 'Not provided';

                $childrenHTML .= '
            <div class="child-card">
                <div class="child-photo">
                    <img src="' . $childImageUrl . '" alt="' . htmlspecialchars($childFullName) . '" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #e3f2fd;">
                </div>
                <div class="child-info">
                    <h3>' . htmlspecialchars($childFullName) . '</h3>
                    <p><strong>Date of Birth:</strong> ' . $dob . '</p>
                    <p><strong>Your Relation:</strong> ' . htmlspecialchars($child['relation']) . '</p>
                </div>
            </div>';
            }

            // Create HTML email with Bootstrap 4 inspired design
            $messageContent = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Welcome to MyDiaree</title>
            <style>
                * {
                    box-sizing: border-box;
                }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    line-height: 1.6;
                    color: #212529;
                    margin: 0;
                    padding: 0;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                }
                .email-wrapper {
                    padding: 20px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                }
                .container {
                    max-width: 700px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    border-radius: 15px;
                    overflow: hidden;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                }
                .header {
                    background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
                    color: white;
                    padding: 40px 30px;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                }
                .header::before {
                    content: "";
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    width: 200%;
                    height: 200%;
                    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                    animation: shimmer 3s ease-in-out infinite;
                }
                @keyframes shimmer {
                    0%, 100% { transform: translateX(-50%) translateY(-50%) rotate(0deg); }
                    50% { transform: translateX(-50%) translateY(-50%) rotate(180deg); }
                }
                .content {
                    padding: 40px 30px;
                    background-color: #ffffff;
                }
                h1 {
                    color: #ffffff;
                    margin: 0;
                    font-size: 32px;
                    font-weight: 700;
                    letter-spacing: 1px;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
                    position: relative;
                    z-index: 1;
                }
                h2 {
                    color: #007bff;
                    margin: 0 0 20px 0;
                    font-size: 24px;
                    font-weight: 600;
                    border-bottom: 3px solid #e9ecef;
                    padding-bottom: 10px;
                }
                .welcome-message {
                    font-size: 18px;
                    margin-bottom: 25px;
                    color: #495057;
                }
                .login-details {
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                    border: 1px solid #dee2e6;
                    border-left: 5px solid #007bff;
                    padding: 25px;
                    margin: 25px 0;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0,123,255,0.1);
                }
                .login-details h3 {
                    color: #007bff;
                    margin-top: 0;
                    font-size: 20px;
                }
                .credentials {
                    font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
                    font-size: 16px;
                    background-color: #ffffff;
                    padding: 8px 12px;
                    border-radius: 6px;
                    border: 1px solid #ced4da;
                    display: inline-block;
                    margin-left: 10px;
                    font-weight: 600;
                    color: #495057;
                }
                .features-list {
                    margin: 25px 0;
                }
                .feature-item {
                    margin-bottom: 15px;
                    position: relative;
                    padding-left: 35px;
                    font-size: 16px;
                    color: #495057;
                }
                .feature-item:before {
                    content: "✓";
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 24px;
                    height: 24px;
                    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                    border-radius: 50%;
                    color: white;
                    font-weight: bold;
                    text-align: center;
                    line-height: 24px;
                    font-size: 14px;
                }
                .btn-primary {
                    display: inline-block;
                    background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
                    color: white !important;
                    padding: 15px 35px;
                    text-decoration: none;
                    border-radius: 50px;
                    margin: 25px 0;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    font-size: 14px;
                    box-shadow: 0 8px 20px rgba(0,123,255,0.3);
                    transition: all 0.3s ease;
                    border: none;
                }
                .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 12px 25px rgba(0,123,255,0.4);
                }
                .text-center {
                    text-align: center;
                }
                .child-section {
                    margin: 35px 0;
                    padding: 25px;
                    background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
                    border-radius: 15px;
                    border: 1px solid #e3f2fd;
                }
                .child-card {
                    display: flex;
                    align-items: center;
                    background-color: #ffffff;
                    border-radius: 12px;
                    padding: 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
                    border: 1px solid #f1f3f4;
                    transition: all 0.3s ease;
                }
                .child-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
                }
                .child-photo {
                    margin-right: 25px;
                    flex-shrink: 0;
                }
                .child-info {
                    flex-grow: 1;
                }
                .child-info h3 {
                    margin: 0 0 10px 0;
                    color: #007bff;
                    font-size: 20px;
                    font-weight: 600;
                }
                .child-info p {
                    margin: 8px 0;
                    color: #6c757d;
                    font-size: 15px;
                }
                .highlight {
                    color: #007bff;
                    font-weight: 600;
                }
                .divider {
                    height: 2px;
                    background: linear-gradient(90deg, transparent, #dee2e6, transparent);
                    margin: 30px 0;
                    border: none;
                }
                .footer {
                    text-align: center;
                    padding: 30px;
                    font-size: 14px;
                    color: #6c757d;
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                    border-top: 1px solid #dee2e6;
                }
                .footer p {
                    margin: 5px 0;
                }
                .support-email {
                    color: #007bff;
                    text-decoration: none;
                    font-weight: 600;
                }
                .support-email:hover {
                    text-decoration: underline;
                }
                @media (max-width: 600px) {
                    .container {
                        margin: 10px;
                        border-radius: 10px;
                    }
                    .content {
                        padding: 25px 20px;
                    }
                    .header {
                        padding: 30px 20px;
                    }
                    h1 {
                        font-size: 24px;
                    }
                    .child-card {
                        flex-direction: column;
                        text-align: center;
                    }
                    .child-photo {
                        margin-right: 0;
                        margin-bottom: 15px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="email-wrapper">
                <div class="container">
                    <div class="header">
                        <h1>🎉 Welcome to MyDiaree!</h1>
                    </div>
                    <div class="content">
                        <h2>Dear Parent,</h2>
                        <p class="welcome-message">
                            We are thrilled to welcome you to <span class="highlight">MyDiaree (Beta)</span> - your gateway to staying connected with your child\'s educational journey and development!
                        </p>

                        <div class="login-details">
                            <h3>🔐 Your Login Credentials</h3>
                            <p><strong>Email:</strong><span class="credentials">' . htmlspecialchars($email) . '</span></p>
                            <p><strong>Password:</strong><span class="credentials">' . htmlspecialchars($password) . '</span></p>
                            <p style="margin-top: 15px; color: #dc3545; font-weight: 500;">
                                <em>⚠️ Please save these credentials securely for future access.</em>
                            </p>
                        </div>

                        <p style="font-size: 18px; margin: 25px 0 15px 0; color: #495057;">
                            <strong>🚀 What you can do with MyDiaree:</strong>
                        </p>
                        <div class="features-list">
                            <div class="feature-item">Monitor your child\'s daily activities and academic progress</div>
                            <div class="feature-item">Receive real-time updates and school announcements</div>
                            <div class="feature-item">Communicate seamlessly with teachers and staff</div>
                            <div class="feature-item">Access personalized learning resources and activities</div>
                            <div class="feature-item">View photos and updates from school events</div>
                            <div class="feature-item">Track homework assignments and important dates</div>
                        </div>

                        <div class="text-center">
                            <a href="https://mydiaree.com.au" class="btn-primary">
                                🚪 Access Your Account Now
                            </a>
                        </div>

                        <div class="child-section">
                            <h2>👨‍👩‍👧‍👦 Your Connected Children</h2>
                            <p style="margin-bottom: 20px; color: #6c757d;">
                                You have been successfully linked to the following children in our system:
                            </p>

                            ' . $childrenHTML . '
                        </div>

                        <hr class="divider">

                        <p style="font-size: 16px; color: #495057; margin: 20px 0;">
                            We believe MyDiaree will revolutionize how you stay connected with your child\'s educational experience, making parent-school collaboration more effective and meaningful than ever before.
                        </p>

                        <p style="margin: 20px 0;">
                            <strong>Need help?</strong> Our dedicated support team is ready to assist you at
                            <a href="mailto:mydairee47@gmail.com" class="support-email">mydairee47@gmail.com</a>
                        </p>

                        <p style="margin: 25px 0 5px 0;">
                            Welcome to the MyDiaree family! 🎊
                        </p>

                        <p style="margin: 5px 0;">
                            <strong>Warm regards,</strong><br>
                            <span class="highlight">The MyDiaree Team</span><br>
                            <em>Nextgen Montessori</em>
                        </p>
                    </div>
                    <div class="footer">
                        <p>&copy; ' . date('Y') . ' MyDiaree. All rights reserved.</p>
                        <p>This is an automated welcome email. Please do not reply directly to this message.</p>
                        <p style="margin-top: 15px; font-size: 12px;">
                            You are receiving this email because an account was created for you on MyDiaree.
                        </p>
                    </div>
                </div>
            </div>
        </body>
        </html>';

            // Send email using Laravel Mail
            Mail::send([], [], function ($mail) use ($email, $messageContent) {
                $mail->to($email)
                    ->from('mydairee47@gmail.com', 'MyDiaree Support')
                    ->subject('🎉 Welcome to MyDiaree - Your Child\'s Learning Journey Begins!')
                    ->html($messageContent);
            });

            return true;
        } catch (\Exception $e) {
            // Log the error
            // Log::error('Failed to send welcome email: ' . $e->getMessage());
            return false;
        }
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
        $user = auth::user();

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
