<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
use Illuminate\Validation\Rule;


    class SettingsController extends Controller
{

       public function show(Request $request)
    {
        
        $userId = $request->userid;
      
        $username = User::where('userid', $userId)->first();

        $Permissions = Permission::where('userid', $userId)->first();

  $userPermissions = [
    'user' => $username ,
    'permissions' => $Permissions
  ];


return response()->json([
'status' => true,
'message' => 'User Permission retrived',
'data' => $userPermissions
]);
    }

 public function updateUserPermissions(Request $request)
{

$userId = $request->userid;

$centerid = $request->centerid;

    $permissions = $request->input('permissions', []);
    $allColumns = Schema::getColumnListing('permissions');

    // Exclude non-permission columns
    $exclude = ['id', 'userid', 'created_at', 'updated_at'];
    $permissionCols = array_diff($allColumns, $exclude);

    // Find or create record
    $permissionRow = Permission::where('userid', $userId)->first();

    $data['userid'] =  $userId;
    $data ['centerid' ] =  $request->centerid;
    // dd(  $data ['centerid' ]);
    
foreach ($permissionCols as $col) {
    $data[$col] = isset($permissions[$col]) && $permissions[$col] == "1" ? 1 : 0;
}

    // dd($data);

    if ($permissionRow) {
        Permission::where('userid', $userId)->update($data);
    } else {
        Permission::insert($data);
    }

    return response()->json([
        'success' => true,
        'message' => 'Permissions updated successfully!',
        'data' => $data
    ]);
}




    //   function assigned_permissions()
    // {
    //     $colors = ['xl-pink', 'xl-turquoise', 'xl-parpl', 'xl-blue', 'xl-khaki'];

    //     $assignedUserList = User::select('users.id', 'users.name')
    //         ->join('permissions', 'permissions.userid', '=', 'users.id')
    //         ->distinct()
    //         ->get()
    //         ->map(function ($user, $index) use ($colors) {
    //             $user->colorClass = $colors[$index % count($colors)];
    //             return $user;
    //         });

    //     return view('settings.assigned_permissions_list', compact('assignedUserList'));
    // }



// public function assigned_permissions()
// {
//     $colors = ['xl-pink', 'xl-turquoise', 'xl-parpl', 'xl-blue', 'xl-khaki'];

//     // Get all permission columns except id, userid, timestamps
//     $permissionColumns = collect(Schema::getColumnListing('permissions'))
//         ->reject(fn($col) => in_array($col, ['id', 'userid', 'created_at', 'updated_at']))
//         ->values()
//         ->toArray();

//     $assignedUserList = User::with(['permissions' => function ($query) use ($permissionColumns) {
//             $query->select(array_merge(['id', 'userid'], $permissionColumns));
//         }])
//         ->get()
//         ->map(function ($user, $index) use ($colors, $permissionColumns) {
//             $user->colorClass = $colors[$index % count($colors)];

//             // Map permissions dynamically
//             $user->assigned_permissions = optional($user->permissions)->map(function ($perm) use ($permissionColumns) {
//                 return collect($perm)->only($permissionColumns);
//             });

//             return $user;
//         });

//     // Prepare permission column info for front-end
//     $permissionColumnsInfo = collect($permissionColumns)
//         ->map(fn($col) => [
//             'name' => $col,
//             'label' => Str::headline($col),
//         ]);

//     return response()->json([
//         'success' => true,
//         'assigned_users' => $assignedUserList,
//         'permissions' => $permissionColumnsInfo,
//     ]);
// }

public function assigned_permissions()
{
    $colors = ['xl-pink', 'xl-turquoise', 'xl-parpl', 'xl-blue', 'xl-khaki'];

    // Get all permission columns except id, userid, timestamps
    $permissionColumns = collect(Schema::getColumnListing('permissions'))
        ->reject(fn($col) => in_array($col, ['id', 'userid', 'created_at', 'updated_at']))
        ->values()
        ->toArray();

    $authId = Usercenter::where('userid',Auth::user()->userid)->pluck('centerid');

    $usercenter = Usercenter::whereIn('centerid',$authId)->pluck('userid');

    $assignedUserList = User::with(['permissions' => function ($query) use ($permissionColumns) {
            $query->select(array_merge(['id', 'userid'], $permissionColumns));
        }])
         ->whereIn('userid', $usercenter)
        ->get()
        ->map(function ($user, $index) use ($colors, $permissionColumns) {
            $user->colorClass = $colors[$index % count($colors)];

            // Map permissions dynamically
            $user->assigned_permissions = optional($user->permissions)->map(function ($perm) use ($permissionColumns) {
                return collect($perm)->only($permissionColumns);
            });

            return $user;
        });

        $permissionColumns1 = collect((new Permission())->getConnection()
    ->getSchemaBuilder()
    ->getColumnListing((new Permission())->getTable()))
    ->reject(fn($col) => in_array($col, ['id', 'userid', 'created_at', 'updated_at']))
  
    ->toArray();

    // Prepare permission column info for front-end
    $permissionColumnsInfo = collect($permissionColumns1)
        ->map(fn($col) => [
            'name' => $col,
            'label' => Str::headline($col),
        ]);

    return response()->json([
        'success' => true,
        'assigned_users' => $assignedUserList,
        'permissions' => $permissionColumnsInfo,
    ]);
}

 public function manage_permissions()
{
    
    $authId = Usercenter::where('userid',Auth::user()->userid)->pluck('centerid');
    // dd($authId);
    $usercenter = Usercenter::whereIn('centerid',$authId)->pluck('userid');
 

$users = User::where('userType', 'Staff')->whereIn('userid',$usercenter)->get();

    $permissionColumns = collect(Schema::getColumnListing('permissions'))
        ->filter(function ($column) {
            return !in_array($column, ['id', 'userid', 'centerid', 'created_at', 'updated_at']);
        })
        ->map(function ($column) {
            return [
                'name' => $column,
                'label' => Str::headline($column),
            ];
        })
        ->values();

    return response()->json([
        'success' => true,
        'data' => [
            'users' => $users,
            'permissionColumns' => $permissionColumns,
        ],
    ]);
}


public function assign_user_permissions(Request $request)
{
    $userIds = $request->input('user_ids', []);
    $checkedPermissions = $request->input('permissions', []);

    if (empty($userIds)) {
        return response()->json([
            'success' => false,
            'message' => 'No users selected.'
        ], 400);
    }

    $updatedUsers = [];

    foreach ($userIds as $userId) {
        $permissionRecord = Permission::firstOrNew(['userid' => $userId]);

        $allColumns = Schema::getColumnListing('permissions');
        $permissionColumns = collect($allColumns)->filter(fn($col) => !in_array($col, ['id', 'userid', 'centerid', 'created_at', 'updated_at']));

        foreach ($permissionColumns as $col) {
            $permissionRecord->{$col} = isset($checkedPermissions[$col]) ? 1 : 0;
        }

        $permissionRecord->save();
        $updatedUsers[] = $userId;
    }

    return response()->json([
        'success' => true,
        'message' => 'Permissions updated successfully!',
        'updated_users' => $updatedUsers
    ]);
}



  public function superadminSettings()
{
    $superadmins = User::where('userType', 'Superadmin')->get();

    if ($superadmins->isEmpty()) {
    return response()->json([
        'status' => false,
        'message' => 'No Superadmin users found.',
        'data' => []
    ]);
}

    return response()->json([
        'status' => true,
        'message' => 'Superadmin settings fetched successfully.',
        'data' => $superadmins
    ]);
}


public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email'         => 'required|email|unique:users,email',
        'password'      => 'required|string|min:6',
        'contactNo'     => 'required|string|min:9',
        'name'          => 'required|string',
        'gender'        => 'required',
        'centerName'    => 'required|string',
        'adressStreet'  => 'required|string',
        'addressCity'   => 'required|string',
        'addressState'  => 'required|string',
        'addressZip'    => 'required',
        'imageUrl'      => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        // Create user
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->emailid = $request->email;
        $user->userType = 'Superadmin';
        $user->center_status = 1;

        // Upload image
        if ($request->hasFile('imageUrl')) {
            $file = $request->file('imageUrl');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/superadmins'), $filename);
            $user->imageUrl = 'uploads/superadmins/' . $filename;
        }

        $user->save();

        // Update userid
        $user->userid = $user->id;
        $user->save();

        // Create center
        $center = new Center();
        $center->user_id = $user->id;
        $center->centerName = $request->centerName;
        $center->adressStreet = $request->adressStreet;
        $center->addressCity = $request->addressCity;
        $center->addressState = $request->addressState;
        $center->addressZip = $request->addressZip;
        $center->save();

        // Link user to center
        $userCenter = new Usercenter();
        $userCenter->userid = $user->id;
        $userCenter->centerid = $center->id;
        $userCenter->save();

        return response()->json(['status' => true, 'message' => 'Superadmin created successfully.']);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => 'Error creating Superadmin: ' . $e->getMessage()], 500);
    }
}


 public function edit(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:users,id',
    ], [
        'id.required' => 'User ID is required.',
        'id.exists' => 'The selected user does not exist.'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422);
    }

    // Fetch and return user data
    $user = User::findOrFail($request->id);

    return response()->json([
        'status' => true,
        'message' => 'User retrieved successfully.',
        'data' => $user
    ]);
}


 public function update(Request $request)
{
    $id = $request->id;
    // dd(1);

       if(!$id){
         return response()->json([
            'status'  => false,
            'message' => 'user id no found',
           
        ], 422);
    }

    $user = User::findOrFail($id); // Will throw 404 if not found

 

    // Step 1: Validate inputs
    $validator = Validator::make($request->all(), [
        'name'      => 'required|string',
        'email'     => 'required|email|unique:users,email,' . $user->id,
        'contactNo' => 'required|string|min:9',
        'gender'    => 'required',
        'password'  => 'nullable|string|min:6',
        'imageUrl'  => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
    ], [
        'email.unique' => 'This email is already in use by another user.',
        'contactNo.min' => 'Contact number must be at least 9 characters.'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors()
        ], 422);
    }

    // Step 2: Update user fields
    $user->name       = $request->name;
    $user->email      = $request->email;
    $user->emailid    = $request->email; // If needed elsewhere
    $user->contactNo  = $request->contactNo;
    $user->gender     = $request->gender;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // Step 3: Handle image upload
    if ($request->hasFile('imageUrl')) {
        // Delete old image if exists
        if ($user->imageUrl && file_exists(public_path($user->imageUrl))) {
            unlink(public_path($user->imageUrl));
        }

        $file = $request->file('imageUrl');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/superadmins'), $filename);
        $user->imageUrl = 'uploads/superadmins/' . $filename;
    }

    $user->save();

    return response()->json([
        'status'  => true,
        'message' => 'User updated successfully.'
    ]);
}



public function destroy(Request $request)
{

      $validator = Validator::make($request->all(), [
        'id' => 'required|exists:users,id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422);
    }
  
    $id = $request->id;
  
  $user = User::find($id);
    if (!$user) {
        return response()->json([
            'status'  => false,
            'message' => 'User not found.'
        ], 404);
    }

    try {
        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User deleted successfully.'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Delete failed. Please try again later.'
        ], 500);
    }
}



public function center_settings()
{
    $userid = Auth::user()->id;

    // Get associated center IDs
    $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();

    // Fetch centers data
    $centers = Center::whereIn('id', $centerIds)->get();

    return response()->json([
        'status' => true,
        'message' => 'Center settings fetched successfully.',
        'data' => $centers
    ]);
}


public function center_store(Request $request)
{
    // Step 1: Validate request using Validator
    $validator = Validator::make($request->all(), [
        'centerName'     => 'required|string',
        'adressStreet'   => 'required|string',
        'addressCity'    => 'required|string',
        'addressState'   => 'required|string',
        'addressZip'     => 'required|min:3',
    ]);

    // Step 2: Return validation errors if any
    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        // Step 3: Save Center
$check = Center::whereRaw('LOWER(centerName) = ?', [strtolower($request->centerName)])
               ->where('user_id', Auth::user()->id)
               ->first();

if ($check) {
    return response()->json([
        'status'  => false,
        'message' => 'Center name already exists for this user.',
        'errors'  => ['centerName' => ['This center name is already taken.']]
    ], 422);
}

        $center = new Center();
        $center->user_id       = Auth::user()->id;
        $center->centerName    = $request->centerName;
        $center->adressStreet  = $request->adressStreet;
        $center->addressCity   = $request->addressCity;
        $center->addressState  = $request->addressState;
        $center->addressZip    = $request->addressZip;
        $center->save();

        // Step 4: Create Usercenter record
        $userCenter = new Usercenter();
        $userCenter->userid   = Auth::user()->id;
        $userCenter->centerid = $center->id;
        $userCenter->save();

        // Step 5: Return success response
        return response()->json([
            'status'  => true,
            'message' => 'Center created successfully.',
            'data'    => [ 'center' => $center ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Failed to store center: ' . $e->getMessage()
        ], 500);
    }
}

    public function center_edit($id)
    {
        $user = Center::findOrFail($id);
        return response()->json($user);
    }

public function center_update(Request $request, $id)
{
   

    // Step 1: Validate input
    $validator = Validator::make($request->all(), [
        'centerName'     => 'required|string',
        'adressStreet'   => 'required|string',
        'addressCity'    => 'required|string',
        'addressState'   => 'required|string',
        'addressZip'     => 'required|min:3',
    ]);

    // Step 2: Handle validation errors
    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors()
        ], 422);
    }

     $center = Center::findOrFail($id);
    // Step 3: Check for duplicate center name for same user (excluding current)
 $exists = Center::whereRaw('LOWER(centerName) = ?', [strtolower($request->centerName)])
    ->where('user_id', Auth::id())
    ->where('id', '!=', $id)
    ->exists();

if ($exists) {
    return response()->json([
        'status'  => false,
        'message' => 'Center name already exists.',
        'errors'  => ['centerName' => ['This center name is already taken.']]
    ], 422);
}

    // Step 4: Update center
    $center->centerName    = $request->centerName;
    $center->adressStreet  = $request->adressStreet;
    $center->addressCity   = $request->addressCity;
    $center->addressState  = $request->addressState;
    $center->addressZip    = $request->addressZip;
    $center->save();

    return response()->json([
        'status'  => true,
        'message' => 'Center updated successfully.',
        'data'    => $center
    ]);
}


    public function destroycenter($id)
    {
        $center = Center::find($id);

        if (!$center) {
            return response()->json(['status' => 'error', 'message' => 'Center not found']);
        }

        try {
            $center->delete();
            return response()->json(['status' => true,'message' => 'center deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Delete failed']);
        }
    }


public function staff_settings(Request $request)
{
    // Step 1: Validate request
    $validator = Validator::make($request->all(), [
        'center_id' => 'required|exists:centers,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors()
        ], 422);
    }

    $authId = Auth::id();
    $centerid = $request->center_id;

    // Step 2: Get all user IDs in the center
    $userIds = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

    // Step 3: Exclude current user and filter Staff
    $staff = User::whereIn('id', $userIds)
        ->where('id', '!=', $authId)
        ->where('userType', 'Staff')
        ->get();

    // Step 4: Get centers based on user type
    if (Auth::user()->userType === "Superadmin") {
        $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerid)->get();
    }

    // Step 5: Return JSON response
    return response()->json([
        'status'  => true,
        'message' => 'Staff and centers retrieved successfully.',
        'data'    => [
            'staff'   => $staff,
            'centers' => $centers
        ]
    ]);
}


    public function changeCenter(Request $request)
    {
        Session(['user_center_id' => $request->center_id]);
        return response()->json(['status' => 'success']);
    }


public function staff_store(Request $request)
{
    // Step 1: Validate input
    $validator = Validator::make($request->all(), [
        'email'     => 'required|email|unique:users,email',
        'password'  => 'required|string|min:6',
        'contactNo' => 'required|string|min:9',
        'name'      => 'required|string',
        'gender'    => 'required|in:Male,Female,Other',
        'imageUrl'  => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        'center_id' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        // Step 2: Create user
        $user = new User();
        $user->email     = $request->email;
        $user->emailid   = $request->email;
        $user->password  = bcrypt($request->password);
        $user->contactNo = $request->contactNo;
        $user->name      = $request->name;
        $user->gender    = $request->gender;
        $user->userType  = 'Staff';
        $user->center_status = $request->center_id;

        // Step 3: Handle image upload
        if ($request->hasFile('imageUrl')) {
            $file = $request->file('imageUrl');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/staffs'), $filename);
            $user->imageUrl = 'uploads/staffs/' . $filename;
        }

        $user->save();

        // Step 4: Update 'userid' with auto ID
        $user->userid = $user->id;
        $user->save();

        // Step 5: Link to Center
        $centerid = $request->center_id;
        $userCenter = new Usercenter();
        $userCenter->userid = $user->id;
        $userCenter->centerid = $centerid;
        $userCenter->save();

        return response()->json([
            'status' => true,
            'message' => 'Staff user created successfully.',
            'data' => $user
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Error creating staff: ' . $e->getMessage()
        ], 500);
    }
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




public function parent_settings(Request $request)
{
    $validator = Validator::make($request->all(), [
        'center_id' => 'required|exists:centers,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    $authId = Auth::user()->id;
    $centerid = $request->center_id;

    // Get all user IDs in the center
    $usersid = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

    // Get parents excluding current user
    $parents = User::whereIn('id', $usersid)
        ->where('id', '!=', $authId)
        ->where('userType', 'Parent')
        ->with(['children:id,name,lastname'])
        ->get();

    $children = Child::where('centerid', $centerid)->get();

    if (Auth::user()->userType === 'Superadmin') {
        $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerid)->get();
    }

    return response()->json([
        'success' => true,
        'data' => [
            'parents' => $parents,
            'children' => $children,
            'centers' => $centers,
        ],
    ]);
}


public function parent_store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'contactNo' => 'required|string|min:9',
        'name' => 'required|string',
        'gender' => 'required|string',
        'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        'children' => 'required|array|min:1',
        'children.*.childid' => 'required|string',
        'children.*.relation' => 'required|string|in:Father,Mother,Brother,Sister,Relative',
        'center_id' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = new User($request->all());
    $user->password = bcrypt($request->password);
    $user->emailid = $request->email;
    $user->userType = 'Parent';
    $user->center_status = 1;

    if ($request->hasFile('imageUrl')) {
        $file = $request->file('imageUrl');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/parents'), $filename);
        $user->imageUrl = 'uploads/parents/' . $filename;
    }

    $user->save();

    $user->userid = $user->id;
    $user->save();

    $centerid = $request->center_id;

    Usercenter::create([
        'userid' => $user->id,
        'centerid' => $centerid,
    ]);

    foreach ($request->children as $childData) {
        Childparent::create([
            'parentid' => $user->id,
            'childid' => $childData['childid'],
            'relation' => $childData['relation'],
        ]);
    }

    $this->sendWelcomeEmail($request->email, $request->password, $request->children);

    return response()->json([
        'status' => 'success',
        'message' => 'Parent created successfully',
        'user' => $user,
    ]);
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
        \Log::error('Failed to send welcome email: ' . $e->getMessage());
        return false;
    }
}



public function getParentData($id)
{
    $validator = Validator::make(['id' => $id], [
        'id' => 'required|exists:users,id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid parent ID.',
            'errors' => $validator->errors()
        ], 422);
    }

    $parent = User::findOrFail($id);

    $children = Childparent::where('parentid', $id)
        ->with(['child:id,name,lastname'])
        ->get()
        ->map(function ($rel) {
            return [
                'id' => $rel->id,
                'childid' => $rel->childid,
                'relation' => $rel->relation,
            ];
        });

    return response()->json([
        'status' => true,
        'parent' => $parent,
        'children' => $children
    ]);
}


public function parent_update(Request $request)
{
    // dd('here');
  $validator = Validator::make($request->all(), [
    'id' => 'required|integer|exists:users,id',
    'email' => [
        'required',
        'email',
        Rule::unique('users', 'email')->ignore($request->id, 'id'),
    ],
    'password' => 'nullable|string|min:6',
    'contactNo' => 'required|string',
    'name' => 'required|string',
    'gender' => 'required|string',
    'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
    'children' => 'required|array|min:1',
    'children.*.childid' => 'required',
    'children.*.relation' => 'required|string',
]);



    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = User::findOrFail($request->id);
    $user->fill([
        'name' => $request->name,
        'emailid' => $request->email,
        'email' => $request->email,
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

    // Delete removed child relations
    $toDelete = array_diff($existing, $submitted);
    if (!empty($toDelete)) {
        Childparent::whereIn('id', $toDelete)->delete();
    }

    // Add/update children
    foreach ($request->children as $childData) {
        if (!empty($childData['id'])) {
            // Update existing child-parent record
            $cp = Childparent::find($childData['id']);
            if ($cp) {
                $cp->update([
                    'childid' => $childData['childid'],
                    'relation' => $childData['relation'],
                ]);
            }
        } else {
            // Add new child-parent relation
            Childparent::create([
                'parentid' => $user->id,
                'childid' => $childData['childid'],
                'relation' => $childData['relation'],
            ]);
        }
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Parent and child data updated successfully.'
    ]);
}


public function getprofile_page()
{
    $authId = Auth::id();

    $user = User::where('userid', $authId)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User profile not found.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'user' => $user,
    ]);
}


 public function uploadImage(Request $request)
{
    $validator = Validator::make($request->all(), [
        'imageUrl' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Image validation failed.',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = Auth::user();

    if ($request->hasFile('imageUrl')) {
        // Remove old image if exists
        if ($user->imageUrl && file_exists(public_path($user->imageUrl))) {
            @unlink(public_path($user->imageUrl));
        }

        $file = $request->file('imageUrl');
        $filename = time() . '.' . $file->getClientOriginalExtension();

        // Set folder based on userType
        $folder = match ($user->userType) {
            'Superadmin' => 'uploads/superadmins',
            'Staff'      => 'uploads/staffs',
            'Parent'     => 'uploads/parents',
            default      => 'uploads/others',
        };

        // Ensure folder exists
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        $file->move(public_path($folder), $filename);

        // Update user record
        $user->imageUrl = $folder . '/' . $filename;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully.',
            'image_url' => asset($user->imageUrl)
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'No image file found in request.'
    ], 400);
}



public function profileupdate(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'contactNo' => 'required|digits_between:7,15',
        'gender' => 'required|in:MALE,FEMALE,OTHERS',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'emailid' => $request->email,
        'contactNo' => $request->contactNo,
        'gender' => $request->gender,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully.',
        'user' => $user,
    ]);
}


public function changePassword(Request $request, $id)
{
    $user = User::where('userid',$id)->first();

    $validator = Validator::make($request->all(), [
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:6|confirmed',
    ], [
        'new_password.confirmed' => 'New password and confirmation do not match.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Current password is incorrect.',
        ], 422);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Password changed successfully.',
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
}
