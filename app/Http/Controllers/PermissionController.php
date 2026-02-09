<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\Permission_Role;

class PermissionController extends Controller
{

 public function delete_role_permission(Request $request)
{
    $id = $request->id;

    // Option 1: Using destroy
    $check = Permission_Role::destroy($id);

    // Option 2: Using find and delete
    // $role = Permission_Role::find($id);
    // $check = $role ? $role->delete() : false;

    if (!$check) {
        return redirect()->back()->with('error', 'Try again, something went wrong');
    }

    return redirect()->back()->with('success', 'Role deleted successfully');
}


public function update_role_permissions(Request $request)
{
    $request->validate([
        'id' => 'required|integer',
    ]);

    $roleId = $request->input('id');
    $permissionRole = Permission_Role::find($roleId);

    if (!$permissionRole) {
        return redirect()->back()->with('error', 'User permissions not found');
    }

    // Get permission columns excluding meta columns
    $allColumns = \Schema::getColumnListing('permission_role');
    $exclude = ['id','centerid','name','created_by','created_at','updated_at'];
    $permissionColumns = array_values(array_diff($allColumns, $exclude));

    // Submitted checkboxes are inside the nested 'permissions' array
    $submitted = $request->input('permissions', []); // e.g. ['addObservation' => 'on', ...]
    // Ensure it's an array
    if (!is_array($submitted)) {
        $submitted = [];
    }

    // Build the final data: 1 if checked, 0 if not
    $updateData = [];
    foreach ($permissionColumns as $col) {
        $updateData[$col] = array_key_exists($col, $submitted) ? 1 : 0;
    }

    // Assign to model attributes and save()
    foreach ($updateData as $column => $value) {
        $permissionRole->$column = $value;
    }

    $permissionRole->save();

    return redirect()->back()->with('success', 'Permissions updated successfully');
}

    public function create_role_permission($id){

        $userPermissions = Permission_Role::where('id', $id)->first();
        // dd( $userPermissions);
            $permissionColumns = collect(Schema::getColumnListing('permission_role'))
            ->filter(function ($column) {
                return !in_array($column, ['id', 'created_by', 'centerid','created_at','updated_at','name']); // exclude default columns
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
        $ActivityPermission = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'activity'))->values();

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
            ->merge($ActivityPermission)
            ->pluck('name')
            ->toArray();

        // Get other permissions (not matched above)
        $otherPermissions = $permissionColumns->reject(function ($item) use ($allMatched) {
            return in_array($item['name'], $allMatched);
        })->values();

        $role_id = $id;

        return view(
            'settings.assign_role_permission',
            compact(
                'userPermissions',
                'role_id',
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
                'otherPermissions',
                'ActivityPermission'
            )
        );
    }

    public function store_role(Request $request){
        // dd($request->all());
        $role = $request->role;

           $centerid = Session('user_center_id');
        $role = Permission_Role::create([
            'name'=> $role,
            'centerid' => $centerid,
            'created_by' => Auth::user()->userid    
            ]);

            return redirect()->back()->with('success', 'Permissions updated successfully');
    }

    public function manage_role(Request $request){
        $centerid = Session('user_center_id');
        $role = Permission_Role::where('centerid',$centerid)->get();

        return view('settings.manage_role',compact('role'));

    }

    public function updatepermission(Request $request)
    {
        $request->validate([
            'userid' => 'required|integer',
        ]);

        $userId   = $request->input('userid');
        $centerId = session('user_center_id'); // ✅ use helper, consistent

        $userPermissions = Permission::where('userid', $userId)->first();

        if (!$userPermissions) {
            return redirect()->back()->with('error', 'User permissions not found');
        }

        // 1️⃣ Get all columns in permissions table (except id/userid/timestamps/centerid)
        $allColumns = \Schema::getColumnListing('permissions');
        $exclude    = ['id', 'userid', 'centerid', 'created_at', 'updated_at'];
        $permissionColumns = array_diff($allColumns, $exclude);

        // 2️⃣ Start with all permissions set to 0
        $updateData = array_fill_keys($permissionColumns, 0);

        // 3️⃣ Set checked ones to 1
        foreach ($request->except(['_token', 'userid']) as $key => $val) {
            if (in_array($key, $permissionColumns)) {
                $updateData[$key] = 1;
            }
        }

        // 4️⃣ Update record (permissions + centerid)
        $updateData['centerid'] = $centerId;
        $userPermissions->update($updateData);

        return redirect()->back()->with('success', 'Permissions updated successfully');
    }


    // public function updatepermission(Request $request)
    // {
    //     $request->validate([
    //         'userid' => 'required|integer',
    //     ]);
    //     // dd($request->all());

    //     $userId = $request->userid;
    //     $centerid = Session('user_center_id');
    //     // dd(  $centerid );

    //     $userPermissions = Permission::where('userid', $userId)->first();

    //     if (!$userPermissions) {
    //         return redirect()->back()->with('error', 'User permissions not found');
    //     }

    //     // 1️⃣ Get all columns in permissions table (except id/userid/timestamps)
    //     $allColumns = \Schema::getColumnListing('permissions');
    //     $exclude = ['id', 'userid', 'created_at', 'updated_at'];
    //     $permissionColumns = array_diff($allColumns, $exclude);

    //     // 2️⃣ Set all to 0 first
    //     $updateData = [];
    //     foreach ($permissionColumns as $col) {
    //         $updateData[$col] = 0;
    //     }

    //     // 3️⃣ Set checked ones to 1
    //     foreach ($request->except(['_token', 'userid']) as $key => $val) {
    //         if (in_array($key, $permissionColumns)) {
    //             $updateData[$key] = 1;
    //         }
    //     }

    //     // 4️⃣ Save
    //     $userPermissions->centerid = $centerid;
    //     $userPermissions->update($updateData);

    //     return redirect()->back()->with('success', 'Permissions updated successfully');
    // }

    // Updated show method to display assigned permissions with all data needed for the template
    public function show($userId)
    {
        $username = User::where('userid', $userId)->first();

        $userPermissions = Permission::where('userid', $userId)->first();

        // Get all permission columns (same logic as manage_permissions)
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

        // Group permissions (same grouping logic as manage_permissions)
        $ObservationPermissions = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'observation'))->values();
        $RoomPermissions        = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'room'))->values();
        $DailyPermissions       = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'daily'))->values();
        $ReflectionPermissions  = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'reflection'))->values();
        $QipPermissions         = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'qip'))->values();
        $ProgramPlanPermissions = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'programplan'))->values();
        $AnnouncementPermissions= $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'announcement'))->values();
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
        $ActivitiesPermissions  = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'activity'))->values();
        $PTMPermissions         = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'ptm'))->values();
        $MessagingPermissions   = $permissionColumns->filter(fn($item) => Str::contains(strtolower($item['name']), 'message'))->values();
        // Combine all matched permissions
        $allMatched = $ObservationPermissions
            ->merge($RoomPermissions)
            ->merge($SnapshotsPermissions)
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
            ->merge($ActivitiesPermissions)
            ->merge($PTMPermissions)
            ->merge($MessagingPermissions)
            ->pluck('name')
            ->toArray();

        // Get other permissions (not matched above)
        $otherPermissions = $permissionColumns->reject(function ($item) use ($allMatched) {
            return in_array($item['name'], $allMatched);
        })->values();

        return view('settings.new_assigned_permissions_list', compact(
            'userPermissions',
            'username',
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
            'otherPermissions',
            'ActivitiesPermissions',
            'PTMPermissions',
            'MessagingPermissions'
        ));
    }
}
