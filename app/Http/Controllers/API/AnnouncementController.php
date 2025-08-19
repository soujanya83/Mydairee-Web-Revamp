<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\support\Facades\Auth;
use App\Models\AnnouncementChildModel;
use App\Models\User;
use App\Models\Childparent;
use App\Models\PermissionsModel;
use App\Models\AnnouncementsModel;
use App\Models\Usercenter;
use App\Models\Center;
use Illuminate\Support\Facades\DB;
use App\Models\Child;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class AnnouncementController extends Controller
{
    public function list(Request $request)
{
    // $centerId = Session::get('user_center_id');
    $centerId = $request->centerid;
    // $user = User::where('userid',$request->userid)->first();
    $user = Auth::user();
    $userId = $user->userid;
    $userType = $user->userType;
    $permissions = null;

    if ($userType === "Superadmin") {
        $centerIds = Usercenter::where('userid', $userId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerId)->get();
    }

    // Fetch announcements with pagination
    if ($userType === 'Staff' || $userType === 'Superadmin') {
        $query = AnnouncementsModel::with('creator') // optional relationship
            ->where('centerid', $centerId);

        if ($userType === 'Staff') {
            $query->where('createdBy', $userId);
        }

        $records = $query->orderByDesc('id')->get(); // ✅ Pagination applied
    } else {
        // For Parents or other roles - get related children announcements
        $childIds = ChildParent::where('parentid', $userId)->pluck('childid');

        $records = AnnouncementsModel::select('announcement.*')
            ->join('announcementchild', 'announcement.id', '=', 'announcementchild.aid')
            ->whereIn('announcementchild.childid', $childIds)
            ->orderByDesc('announcement.id')
            ->get(); // ✅ Pagination here too
    }

    // Attach creator name manually if needed
    foreach ($records as $announcement) {
        $creator = User::where('userid', $announcement->createdBy)->first();
        $announcement->createdBy = $creator->name ?? 'Not Available';
    }

    // Permissions
    $permissions = PermissionsModel::where('userid', $userId)
        ->where('centerid', $centerId)
        ->first();

    return response()->json([
    'status' => true,
    'data' => [
        'records' => $records,
        'permissions' => $permissions,
        'centers' => $centers,
        'centerId' => $centerId,
        'userType' => $userType
    ]
]);

}



public function AnnouncementCreate(Request $request)
{
    
    $announcement = null;
    
    // $centerid = Session('user_center_id');
    $centerid = $request->centerid;

    if(!$request->centerid){
        return response()->json([
            'status' => false,
            'msg' => 'please provide center id'
        ]);
    }
    $Childrens = [];
    $Groups = [];
    $Rooms = [];

    $announcementid = $request->id;

    if($announcementid){
        $announcement = AnnouncementsModel::find($announcementid);
        // dd($announcement);
    }


        // Children List
        $childs = DB::table('child as c')
            ->join('room as r', 'c.room', '=', 'r.id')
            ->select('c.*', 'r.*', 'c.name as name', 'c.id as childid')
            ->where('r.centerid', $centerid)
            ->get();

        $now = Carbon::now();

        foreach ($childs as $childobj) {
            $dob = Carbon::parse($childobj->dob);
            $checked = false;
            if (isset($announcementid)) {
                $check = DB::table('announcementchild')
                    ->where('aid', $announcementid)
                    ->where('childid', $childobj->childid)
                    ->exists();
                $checked = $check;
            }

          $Childrens[] = (object) [
    'childid' => $childobj->childid,
    'name' => $childobj->name . ' ' . $childobj->lastname,
    'imageUrl' => $childobj->imageUrl,
    'dob' => $dob->format('d-m-Y'),
    'age' => $dob->diff($now)->format('%y years %m months'),
    'gender' => $childobj->gender,
    'checked' => $checked
];
            
        }
        // Groups
        $childGroups = DB::table('child_group')
            ->when($centerid, fn($q) => $q->where('centerid', $centerid))
            ->get();

        foreach ($childGroups as $group) {
            $groupChildren = [];
            $groupChilds = DB::table('child')
                ->join('child_group_member', 'child.id', '=', 'child_group_member.child_id')
                ->where('child_group_member.group_id', $group->id)
                ->select('child.*')
                ->get();

            foreach ($groupChilds as $child) {
                $dob = Carbon::parse($child->dob);
                $checked = false;
                if (isset($announcementid)) {
                    $check = DB::table('announcementchild')
                        ->where('aid', $announcementid)
                        ->where('childid', $child->id)
                        ->exists();
                    $checked = $check;
                }

                $groupChildren[] =  (object) [
                    'childid' => $child->id,
                    'name' => $child->name . ' ' . $child->lastname,
                    'imageUrl' => $child->imageUrl,
                    'dob' => $dob->format('d-m-Y'),
                    'age' => $dob->diff($now)->format('%y years %m months'),
                    'gender' => $child->gender,
                    'checked' => $checked
                ];
            }

            $Groups[] = (object) [
                'groupid' => $group->id,
                'name' => $group->name,
                'Childrens' => $groupChildren
            ];
        }

        // dd($Groups);

        // Rooms
        $rooms = DB::table('room')->where('centerid', $centerid)->get();

        foreach ($rooms as $room) {
            $roomChildren = [];
            $roomChilds = DB::table('child as c')
                ->join('room as r', 'c.room', '=', 'r.id')
                ->where('r.id', $room->id)
                ->select('c.*', 'r.*', 'c.id as childid', 'c.name as name')
                ->get();

            foreach ($roomChilds as $child) {
                $dob = Carbon::parse($child->dob);
                $checked = false;
                if (isset($json->annId)) {
                    $check = DB::table('announcementchild')
                        ->where('aid', $json->annId)
                        ->where('childid', $child->childid)
                        ->exists();
                    $checked = $check;
                }

                $roomChildren[] = (object) [
                    'childid' => $child->childid,
                    'name' => $child->name . ' ' . $child->lastname,
                    'imageUrl' => $child->imageUrl,
                    'dob' => $dob->format('d-m-Y'),
                    'age' => $dob->diff($now)->format('%y years %m months'),
                    'gender' => $child->gender,
                    'checked' => $checked
                ];
            }

            $Rooms[] = (object) [
                'roomid' => $room->id,
                'name' => $room->name,
                'Childrens' => $roomChildren
            ];
        }

    // Permissions
    $userid = Auth::user()->userid;
    // $userid = $request->userid;
    // dd($userid);
    $user = Auth::user();
 

          
   $permissions = PermissionsModel::where('userid',$userid)
            ->where('centerid', $centerid)
            ->get();
       
return response()->json([
    'status' => true,
    'message' => 'Announcement create data fetched successfully',
    'data' => [
        'announcement' => $announcement,
        'centerid' => $centerid,
        'Childrens' => $Childrens,
        'Groups' => $Groups,
        'Rooms' => $Rooms,
        'permissions' => $permissions
    ]
]);

}


public function AnnouncementStore(Request $request)
{

    // dd($request->all());
    // $request->validate([
    //     'title'      => 'required|string|max:255',
    //     'text'       => 'required|string',
    //     'eventDate'  => 'nullable|date_format:d-m-Y',
    //     'childId'    => 'required|array',
    //     'childId.*'  => 'required|numeric|exists:child,id',
    //     'media'      => 'nullable|array',
    //     'media.*'    => 'file|mimes:jpeg,jpg,png|max:200', // 200KB per image
    // ], [
    //     'childId.required' => 'Children are required.',
    //     'text.required'    => 'Description is required.',
    //     'media.*.max'      => 'Each image must be under 200KB.',
    //     'media.*.mimes'    => 'Only JPG, JPEG, PNG files are allowed.',
    // ]);

    $validator = Validator::make($request->all(), [
    'title'      => 'required|string|max:255',
    'text'       => 'required|string',
    'eventDate'  => 'nullable|date_format:d-m-Y',
    'childId'    => 'required|array',
    'childId.*'  => 'required|numeric|exists:child,id',
    'media'      => 'nullable|array',
    'media.*'    => 'file|mimes:jpeg,jpg,png|max:400',
], [
    'childId.required' => 'Children are required.',
    'text.required'    => 'Description is required.',
    'media.*.max'      => 'Each image must be under 200KB.',
    'media.*.mimes'    => 'Only JPG, JPEG, PNG files are allowed.',
]);

if ($validator->fails()) {
    return response()->json([
        'status' => 'false',
        'message' => 'Validation failed.',
        'errors' => $validator->errors()
    ], 422);
}

    try {
        $userid = Auth::user()->userid;
        $user = Auth::user();
        // $userid = $request->userid;
        // $user = User::where('userid',$userid)->first();
        // $centerid = session('user_center_id');/
        $centerid = $request->centerid;
        $announcementId = null;

        // Format date
        $eventDate = empty($request->eventDate)
             ? now()->addDay()->format('Y-m-d')
            : Carbon::createFromFormat('d-m-Y', $request->eventDate)->format('Y-m-d');

        $role = $user->userType;
        $run = 0;
        $status = 'Pending';

        // Permissions
        if ($role === "Superadmin") {
            $run = 1;
            $status = "Sent";
        } elseif ($role === "Staff") {
            $permission = \App\Models\PermissionsModel::where('userid', $userid)
                ->where('centerid', $centerid)
                ->first();

            if ($permission && ($permission->addAnnouncement || $permission->updateAnnouncement)) {
                $run = 1;
                $status = $permission->approveAnnouncement ? "Sent" : "Pending";
            }
        }

        if ($run !== 1) {
            return response()->json([
                'status' => 'false',
                'message' => 'Permission Denied!',
            ]);
        }

        $mediaFiles = [];

        // Store new files (in public/assets/media)
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('assets/media');
                $file->move($destinationPath, $filename);
                $mediaFiles[] = $filename;
            }
        }

        // UPDATE
        if ($request->annId) {
            $announcement = \App\Models\AnnouncementsModel::find($request->annId);

            if (!$announcement) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'Announcement not found!',
                ]);
            }

            // Remove old media if new media uploaded
            if (!empty($mediaFiles) && $announcement->announcementMedia) {
                $oldMedia = json_decode($announcement->announcementMedia, true);
                foreach ($oldMedia as $oldFile) {
                    $filePath = public_path('assets/media/' . $oldFile);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            $announcement->update([
                'title'             => $request->title,
                'eventDate'         => $eventDate,
                'text'              => $request->text,
                'status'            => $status,
                'announcementMedia' => !empty($mediaFiles) ? json_encode($mediaFiles) : $announcement->announcementMedia,
            ]);

            $announcementId = $announcement->id;

            \App\Models\AnnouncementChildModel::where('aid', $announcementId)->delete();
        } 
        // CREATE
        else {
            $announcement = \App\Models\AnnouncementsModel::create([
                'title'             => $request->title,
                'text'              => $request->text,
                'eventDate'         => $eventDate,
                'status'            => $status,
                'createdBy'         => $userid,
                'centerid'          => $centerid,
                'createdAt'         => now(),
                'announcementMedia' => json_encode($mediaFiles),
            ]);

            if (!$announcement) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'Failed to create announcement!',
                ]);
            }

            $announcementId = $announcement->id;
        }

        // Link children
        foreach ($request->childId as $childId) {
            if (!empty($childId)) {
                \App\Models\AnnouncementChildModel::create([
                    'aid'     => $announcementId,
                    'childid' => $childId,
                ]);
            }
        }

        return response()->json([
            'status' => 'true',
            'msg' => $request->annId ? 'Announcement updated successfully' : 'Announcement created successfully',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'false',
            'message' => 'Something went wrong! ' . $e->getMessage(),
        ]);
    }
}



public function AnnouncementView(Request $request)
{
    // dd('here');
    $announcementId = $request->annid;

    if (empty($announcementId)) {
        return response()->json([
            'status' => 'false',
            'message' => 'Invalid announcement ID!'
        ], 400);
    }

    $announcementInfo = AnnouncementsModel::find($announcementId);

    if (!$announcementInfo) {
        return response()->json([
            'status' => 'false',
            'message' => "Announcement record doesn't exist!"
        ], 404);
    }

    $type = Auth::user()->userType ?? null;
    // $user = User::where('userid',$request->userid)->first();
    // $type = $user->userType;

    $permission = null;
    if ($type === 'Staff') {
        $permission = PermissionsModel::where('userid', Auth::user()->userid)
            ->where('centerid', $announcementInfo->centerid)
            ->first();
    }

    $user = User::where('userid', $announcementInfo->createdBy)->first();
    $announcementInfo->username = $user->name ?? 'Unknown';

    return response()->json([
        'status' => 'true',
        'data' => [
            'info' => $announcementInfo,
            'permissions' => $permission,
            'centerid' => $announcementInfo->centerid
        ]
    ]);
}




public function AnnouncementDelete(Request $request)
{
    $announcementId = $request->announcementid;

    if (!$announcementId) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid Announcement ID.'
        ], 400);
    }
    if (!Auth::check()) {
    return response()->json([
        'status' => false,
        'message' => 'User not authenticated.'
    ], 401);
}

    // $userid = $request->userid;
    $userid = Auth::user()->userid;
    $role = Auth::user()->userType;

    if (empty($userid)) {
        return response()->json([
            'status' => false,
            'message' => 'User ID is required.'
        ], 400);
    }

    $user = User::where('userid', $userid)->first();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found.'
        ], 404);
    }

    // $role = $user->userType;

    $announcementInfo = AnnouncementsModel::find($announcementId);

    if (!$announcementInfo) {
        return response()->json([
            'status' => false,
            'message' => 'Announcement not found.'
        ], 404);
    }

    $centerid = $announcementInfo->centerid;
    $run = 0;

    // Role-based permission check
    if ($role === "Superadmin") {
        $run = 1;
    } elseif ($role === "Staff") {
        $permission = PermissionsModel::where('userid', $userid)
            ->where('centerid', $centerid)
            ->first();

        if ($permission && $permission->deleteAnnouncement == 1) {
            $run = 1;
        }
    }

    if ($run === 1) {
        AnnouncementChildModel::where('aid', $announcementId)->delete();
        AnnouncementsModel::where('id', $announcementId)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Announcement deleted successfully.'
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'Permission Denied.'
    ], 403);
}

}
