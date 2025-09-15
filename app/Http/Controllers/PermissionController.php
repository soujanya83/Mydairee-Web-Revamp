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

class PermissionController extends Controller
{

public function updatepermission(Request $request)
{
    $request->validate([
        'userid' => 'required|integer',
    ]);

    $userId = $request->userid;

    $userPermissions = Permission::where('userid', $userId)->first();

    if (!$userPermissions) {
        return redirect()->back()->with('error', 'User permissions not found');
    }

    // 1️⃣ Get all columns in permissions table (except id/userid/timestamps)
    $allColumns = \Schema::getColumnListing('permissions');
    $exclude = ['id', 'userid', 'created_at', 'updated_at'];
    $permissionColumns = array_diff($allColumns, $exclude);

    // 2️⃣ Set all to 0 first
    $updateData = [];
    foreach ($permissionColumns as $col) {
        $updateData[$col] = 0;
    }

    // 3️⃣ Set checked ones to 1
    foreach ($request->except(['_token', 'userid']) as $key => $val) {
        if (in_array($key, $permissionColumns)) {
            $updateData[$key] = 1;
        }
    }

    // 4️⃣ Save
    $userPermissions->update($updateData);

    return redirect()->back()->with('success', 'Permissions updated successfully');
}

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
            'otherPermissions'
        ));
    }
}
