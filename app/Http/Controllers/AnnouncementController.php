<?php

namespace App\Http\Controllers;

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
use App\Notifications\AnnouncementAdded;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;



class AnnouncementController extends Controller
{

public function updateStatus(Request $request)
{
  
    $id = $request->id;
    $status = $request->status;
if($status == 'Draft'){
    $updateStatus = 'Pending';
}else{
     $updateStatus = 'Sent';
}
    $announcement = AnnouncementsModel::find($id);

    if (!$announcement) {
        return response()->json([
            'status' => false,
            'message' => 'Announcement not found.'
        ], 404);
    }

    $announcement->status = $updateStatus;
    $announcement->save();

    return response()->json([
        'status' => true,
        'message' => "Status changed to {$status} successfully."
    ]);
}

    public function Filterlist(Request $request)
{
    $centerId = Session::get('user_center_id');
    $user = Auth::user();
    $userId = $user->userid;
    $userType = $user->userType;

    // Base query - must filter by current center
    if ($userType === 'Staff' || $userType === 'Superadmin') {
        $query = AnnouncementsModel::with('creator')
            ->where('centerid', $centerId);

        if ($userType === 'Staff') {
            $query->where('createdBy', $userId);
        }
    } else {
        $childIds = ChildParent::where('parentid', $userId)->pluck('childid');

        $query = AnnouncementsModel::select('announcement.*')
            ->join('announcementchild', 'announcement.id', '=', 'announcementchild.aid')
            ->where('status','Sent')
            ->where('announcement.centerid', $centerId)
            ->whereIn('announcementchild.childid', $childIds);
    }

    // ✅ Apply filters
    if ($request->filled('title')) {
        $query->where('title', 'like', "%{$request->title}%");
    }

    if ($request->filled('createdBy')) {
        $query->where('createdBy', $request->createdBy);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('createdAt', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('createdAt', '<=', $request->date_to);
    }

    // ✅ Fetch all filtered records
    $records = $query->orderByDesc('id')->get();

    // Attach creator name manually
    foreach ($records as $announcement) {
        $creator = User::where('userid', $announcement->createdBy)->first();
        $announcement->creatorName = $creator->name ?? 'Not Available';
    }

      $permissions = PermissionsModel::where('userid', $userId)
            ->where('centerid', $centerId)
            ->first();

    return response()->json([
        'status' => true,          // ✅ Boolean status
        'count' => $records->count(),
        'records' => $records,
        'permission' => $permissions
    ]);
}


    public function list(Request $request)
    {
        // dd('here');
        $centerId = Session::get('user_center_id');
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

            if( $userType === 'Staff' && Auth::user()->admin == 1){
                    $query = AnnouncementsModel::with('creator') // optional relationship
                            ->where('centerid', $centerId);
            }

            $records = $query->orderByDesc('id')->paginate(12); // ✅ Pagination applied
        } else {
            // For Parents or other roles - get related children announcements
            $childIds = ChildParent::where('parentid', $userId)->pluck('childid');

            $records = AnnouncementsModel::select('announcement.*')
                ->join('announcementchild', 'announcement.id', '=', 'announcementchild.aid')
                ->whereIn('announcementchild.childid', $childIds)
                ->where('status','Sent')
                ->orderByDesc('announcement.id')
                ->paginate(12); 
        }

        // Attach creator name manually if needed
        foreach ($records as $announcement) {
            $creator = User::where('userid', $announcement->createdBy)->first();
            $announcement->createdBy = $creator->name ?? 'Not Available';
        }

        // Permissions
        $permissions = PermissionsModel::where('userid', $userId)

            ->first();

        return view('Announcement.list', compact(
            'records',
            'permissions',
            'centers',
            'centerId',
            'userType'
        ));
    }



    public function AnnouncementCreate(Request $request, $announcementid = null)
    {
        $announcement = null;

        $centerid = Session('user_center_id');

        $Childrens = [];
        $Groups = [];
        $Rooms = [];

        if ($announcementid) {
            $announcement = AnnouncementsModel::find($announcementid);
        }


        // Children List
        $childs = DB::table('child as c')
            ->join('room as r', 'c.room', '=', 'r.id')
            ->select('c.*', 'r.*', 'c.name as name', 'c.id as childid')
            ->where('r.centerid', $centerid)
            ->where('c.status','Active')
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
                ->where('child.status','Active')
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
                ->where('c.status','Active')
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
        $permissions = Auth::user()->userType === 'Superadmin'
            ? null
            : PermissionsModel::where('userid', Auth::user()->userid)
            ->first();

        // dd($permissions);

        return view('Announcement.create', compact(
            'announcement',
            'centerid',
            'Childrens',
            'Groups',
            'Rooms',
            'permissions'
        ));
    }


public function AnnouncementStore(Request $request)
{
    $request->validate([
        'title'      => 'required|string|max:255',
        'text'       => 'required|string',
        'eventDate'  => 'nullable|date_format:d-m-Y',
        'childId'    => 'required|array',
        'childId.*'  => 'required|numeric|exists:child,id',
        'media'      => 'nullable|array',
        'media.*'    => 'file|mimes:jpeg,jpg,png,pdf|max:2048',
    ], [
        'childId.required' => 'Children are required.',
        'text.required'    => 'Description is required.',
        'media.*.max'      => 'File must be under 2MB.',
        'media.*.mimes'    => 'Only JPG, JPEG, PNG, or PDF files are allowed.',
    ]);

    // ✅ Check if both image and PDF types are mixed
    if ($request->hasFile('media')) {
        $hasImage = false;
        $hasPdf   = false;

        foreach ($request->file('media') as $file) {
            $mime = $file->getMimeType();
            if (str_starts_with($mime, 'image/')) {
                $hasImage = true;
            } elseif ($mime === 'application/pdf') {
                $hasPdf = true;
            }
        }

        if ($hasImage && $hasPdf) {
            return redirect()->back()->withInput()->withErrors([
                'media' => 'You can upload either images or PDFs, not both together.',
            ]);
        }
    }

    try {
        $userid    = Auth::user()->userid;
        $centerid  = session('user_center_id');
        $announcementId = null;

        $eventDate = empty($request->eventDate)
            ? now()->addDay()->format('Y-m-d')
            : Carbon::createFromFormat('d-m-Y', $request->eventDate)->format('Y-m-d');

        $role   = Auth::user()->userType;
        $run    = 0;
        $status = 'Pending';

        if ($role === "Superadmin") {
            $run = 1;
            $status = "Pending";
        } elseif ($role === "Staff") {
            $permission = \App\Models\PermissionsModel::where('userid', $userid)
                ->first();

            if ($permission && ($permission->addAnnouncement || $permission->updateAnnouncement)) {
                $run = 1;
                $status = "Pending";
            }
        }

        if ($run !== 1) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Permission Denied!',
            ]);
        }

        $mediaFiles = [];
        $manager = new ImageManager(new Driver()); // ✅ Intervention v3 way

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $destinationPath = public_path('uploads/announcements');
                File::ensureDirectoryExists($destinationPath);

                if (str_starts_with($file->getMimeType(), 'image/')) {
          $image = $manager->read($file)
    ->scaleDown(900, 900)        // keep full image, no crop
    ->pad(900, 900, 'white');    // only add padding where needed


                    $quality   = 90;
                    $maxSize   = 500 * 1024; // 500 KB
                    $tempPath  = storage_path('app/temp_' . Str::random(10) . '.jpg');

                    do {
                        $image->save($tempPath, quality: $quality);
                        $size = filesize($tempPath);
                        $quality -= 5;
                    } while ($size > $maxSize && $quality > 10);

                    $filename = uniqid() . '.jpg';
                    $finalPath = $destinationPath . '/' . $filename;
                    rename($tempPath, $finalPath);

                    $mediaFiles[] = url('uploads/announcements/' . $filename);
                } else {
                    // ✅ PDF, just move
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    $file->move($destinationPath, $filename);
                    $mediaFiles[] = url('uploads/announcements/' . $filename);
                }
            }
        }

        // ✅ UPDATE CASE
        if ($request->annId) {
            $announcement = \App\Models\AnnouncementsModel::find($request->annId);

            if (!$announcement) {
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Announcement not found!',
                ]);
            }

            if (!empty($mediaFiles) && $announcement->announcementMedia) {
                $oldMedia = json_decode($announcement->announcementMedia, true);
                foreach ($oldMedia as $oldFileUrl) {
                    $oldFilePath = public_path(str_replace(url('/') . '/', '', $oldFileUrl));
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
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
        // ✅ CREATE CASE
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
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Failed to create announcement!',
                ]);
            }

            $announcementId = $announcement->id;
        }

        foreach ($request->childId as $childId) {
            if (!empty($childId)) {
                \App\Models\AnnouncementChildModel::create([
                    'aid'     => $announcementId,
                    'childid' => $childId,
                ]);
            }
        }

        $userIds = Usercenter::where('centerid', $centerid)
            ->pluck('userid')
            ->unique();

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->notify(new AnnouncementAdded($announcement));
            }
        }

        return redirect()->back()->with([
            'status' => 'success',
            'msg'    => $request->annId ? 'Announcement updated successfully' : 'Announcement created successfully',
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->with([
            'status' => 'error',
            'message' => 'Something went wrong! ' . $e->getMessage(),
        ]);
    }
}



    public function AnnouncementView(Request $request)
    {
        // dd('here');
        $announcementId = $request->annid;

        //    dd($announcementId);

        if (empty($announcementId)) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Invalid announcement ID!'
            ]);
        }


        $announcementInfo = AnnouncementsModel::find($announcementId);
        // dd($announcementInfo);

        if (!$announcementInfo) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => "Announcement record doesn't exist!"
            ]);
        }


        $type = Auth::user()->userType; // Replace with actual logic if you have userType stored

        $permission = null;
        if ($type === 'Staff') {
            $permission = PermissionsModel::where('userid', Auth::user()->userid)
                ->where('centerid', $announcementInfo->centerid)
                ->first();
        }

        $user = User::where('userid', $announcementInfo->createdBy)->first();

        $announcementInfo->username = $user->name ?? 'Unknown';

        return view('Announcement.view', [
            'Status' => 'SUCCESS',
            'Info' => $announcementInfo,
            'Permissions' => $permission,
            'centerid' => $announcementInfo->centerid
        ]);
    }



    public function AnnouncementDelete(Request $request)
    {
        $announcementId = $request->announcementid;

        if (!$announcementId) {
            return redirect()->back()->with('msg', 'Error! Invalid Announcement ID.');
        }

        $userid = Auth::user()->userid;
        $role = Auth::user()->userType;

        $announcementInfo = AnnouncementsModel::find($announcementId);

        if (!$announcementInfo) {
            return redirect()->back()->with('msg', 'Error! Announcement not found.');
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
            // Delete child records and announcement
            AnnouncementChildModel::where('aid', $announcementId)->delete();
            AnnouncementsModel::where('id', $announcementId)->delete();

            return redirect()->back()->with('msg', 'Success! Announcement deleted successfully.');
        }

        return redirect()->back()->with('msg', 'Error! Permission Denied.');
    }
}
