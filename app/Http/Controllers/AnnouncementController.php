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
use Illuminate\Validation\Rule;
use App\Models\PubicHoliday_Model;
use Illuminate\Support\Facades\Log;


class AnnouncementController extends Controller
{

    public function updateStatus(Request $request)
    {

        $id = $request->id;
        $status = $request->status;
        if ($status == 'Draft') {
            $updateStatus = 'Pending';
        } else {
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
        $check = $announcement->save();

        $centerid = Session('user_center_id');

        if ($check) {
            $childIds = AnnouncementChildModel::whereIn('aid', (array) $id)->pluck('childid');

            // Get unique parent IDs directly
            $userIds = Childparent::whereIn('childid', $childIds)
                ->pluck('parentid')
                ->unique();


            // Fetch all users in one query
            $users = User::whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                $user->notify(new AnnouncementAdded($announcement));
            }
        }

if ($check) {
    $users = User::join('usercenters', 'users.userid', '=', 'usercenters.userid')
                 ->where('usercenters.centerid', $centerid)
                 ->whereIn('users.userType', ['staff', 'Superadmin'])
                 ->select('users.*') // important to avoid duplicate/extra cols from join
                 ->get();

    foreach ($users as $user) {
        $user->notify(new AnnouncementAdded($announcement));
    }
}




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
            if ($userType === 'Staff') {
                $query->whereIn('audience', ['parents', 'all']);
            }
        } else {
            $childIds = ChildParent::where('parentid', $userId)->pluck('childid');

            $query = AnnouncementsModel::select('announcement.*')
                ->join('announcementchild', 'announcement.id', '=', 'announcementchild.aid')
                ->where('status', 'Sent')
                ->whereIn('audience', ['parents', 'all'])
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

            if ($userType === 'Staff' && Auth::user()->admin == 1) {
                $query = AnnouncementsModel::with('creator') // optional relationship
                    ->whereIn('audience', ['staff', 'all'])
                    ->where('createdBy', $userId)
                    ->where('centerid', $centerId);
            }

            $records = $query->orderByDesc('id')->paginate(12); // ✅ Pagination applied
        } else {
            // For Parents or other roles - get related children announcements
            $childIds = ChildParent::where('parentid', $userId)->pluck('childid');

            $records = AnnouncementsModel::select('announcement.*')
                ->join('announcementchild', 'announcement.id', '=', 'announcementchild.aid')
                ->whereIn('announcementchild.childid', $childIds)
                ->where('status', 'Sent')
                ->whereIn('audience', ['parents', 'all'])
                ->orderByDesc('announcement.id')
                ->paginate(12);
        }

        // Attach creator name manually if needed
        foreach ($records as $announcement) {
            $creator = User::where('userid', $announcement->createdBy)->first();
            $announcement->createdBy = $creator->name ?? 'Not Available';
        }

        // Permissions
        $permissionsData = PermissionsModel::where('userid', $userId)
            ->first();

        $holidays = PubicHoliday_Model::orderBy('date', 'desc')
            ->orderBy('month', 'desc')
            ->get();


        return view('Announcement.list', compact(
            'records',
            'permissionsData',
            'centers',
            'centerId',
            'userType',
            'holidays'
        ));
    }



    public function AnnouncementCreate(Request $request, $announcementid = null)
    {
        $announcement = null;

        $centerid = Session('user_center_id');
        $selectedDate = $request->query('date') ?? "";


        $Childrens = [];
        $Groups = [];
        $Rooms = [];

        if ($announcementid) {
            $announcement = AnnouncementsModel::find($announcementid);
        }
        // dd( $announcement );


        // Children List
        $childs = DB::table('child as c')
            ->join('room as r', 'c.room', '=', 'r.id')
            ->select('c.*', 'r.*', 'c.name as name', 'c.id as childid', 'c.imageUrl as imageUrl')
            ->where('r.centerid', $centerid)
            ->where('c.status', 'Active')
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
                ->where('child.status', 'Active')
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

        // Rooms
        $rooms = DB::table('room')->where('centerid', $centerid)->get();

        foreach ($rooms as $room) {
            $roomChildren = [];
            $roomChilds = DB::table('child as c')
                ->join('room as r', 'c.room', '=', 'r.id')
                ->where('r.id', $room->id)
                ->where('c.status', 'Active')
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
        $permissionsData = Auth::user()->userType === 'Superadmin'
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
            'permissionsData',
            'selectedDate'
        ));
    }



    public function AnnouncementStore(Request $request)
    {
        // ✅ Validation rules
        // dd($request->all());
        $rules = [
            'title'     => 'required|string|max:255',
            'text'      => 'nullable|string',
            'eventDate' => 'nullable|date_format:d-m-Y',
            'date'      => 'nullable|date_format:d-m-Y',
            'audience'  => 'required|string|in:all,parents,staff',
            'type'      => 'required|string|in:announcement,events',
            'color'     => 'nullable'
        ];



        // If audience requires children
        if (in_array($request->audience, ['all', 'parents'])) {
            $rules['childId']   = 'required|array';
            $rules['childId.*'] = 'numeric|exists:child,id';
        }

        // Media rules
        if ($request->annId) {
            // Update → media optional
            $rules['media']   = 'nullable|array';
            $rules['media.*'] = 'file|mimes:jpeg,jpg,png,pdf|max:2048';
        } else {
            // Create → media required
            $rules['media']   = 'nullable|array|min:1';
            $rules['media.*'] = 'file|mimes:jpeg,jpg,png,pdf|max:2048';
        }

        // Custom messages
        $messages = [
            'childId.required' => 'Children are required when audience is All or Parents.',
            'text.required'    => 'Description is required.',
            'media.required'   => 'At least one media file is required.',
            'media.min'        => 'At least one media file must be uploaded.',
            'media.*.max'      => 'File must be under 2MB.',
            'media.*.mimes'    => 'Only JPG, JPEG, PNG, or PDF files are allowed.',
        ];

        // ✅ Validate request
        $request->validate($rules, $messages);

        try {
            $userid   = Auth::user()->userid;
            $centerid = session('user_center_id');
            $announcementId = null;

            if (!empty($request->date)) {
                $eventDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
            } elseif (!empty($request->eventDate)) {
                $eventDate = Carbon::createFromFormat('d-m-Y', $request->eventDate)->format('Y-m-d');
            } else {
                $eventDate = now()->format('Y-m-d');
            }


            // dd($eventDate);
            // ✅ Role-based permission check
            $role   = Auth::user()->userType;
            $run    = 0;
            $status = 'Pending';

            if ($role === "Superadmin") {
                $run = 1;
            } elseif ($role === "Staff") {
                $permission = \App\Models\PermissionsModel::where('userid', $userid)->first();
                if ($permission && ($permission->addAnnouncement || $permission->updateAnnouncement)) {
                    $run = 1;
                }
            }

            if ($run !== 1) {
                // dd('pemission denied');
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Permission Denied!',
                    "type" => $request->type
                ]);
            }

            // ✅ Save media files
            $mediaFiles = [];
            if ($request->hasFile('media')) {
                $mediaFiles = $this->saveMediaFiles($request->file('media'));
            }

            // ✅ UPDATE CASE
            if ($request->annId) {
                // dd($eventDate);
                // dd('here');
                $announcement = \App\Models\AnnouncementsModel::findOrFail($request->annId);

                // Delete old media if new uploaded
                if (!empty($mediaFiles) && $announcement->announcementMedia) {
                    $oldMedia = json_decode($announcement->announcementMedia, true);
                    foreach ($oldMedia as $oldFileUrl) {
                        $oldFilePath = public_path(str_replace(url('/') . '/', '', $oldFileUrl));
                        if (file_exists($oldFilePath)) {
                            @unlink($oldFilePath);
                        }
                    }
                    $announcement->announcementMedia = json_encode($mediaFiles);
                }

                // Update fields
                $announcement->title     = $request->title;
                $announcement->eventDate = $eventDate;
                $announcement->text      = $request->text;
                $announcement->status    = $status;
                $announcement->audience  = $request->audience;
                $announcement->type      = $request->type;
                if ($request->type == "events") {
                    $announcement->eventColor = $request->color;
                }
                $announcement->save();

                $announcementId = $announcement->id;

                // Update child relations
                if (in_array($request->audience, ['all', 'parents'])) {
                    $existingChildIds = \App\Models\AnnouncementChildModel::where('aid', $announcementId)
                        ->pluck('childid')->toArray();

                    $newChildIds = $request->childId ?? [];

                    // Add new
                    foreach (array_diff($newChildIds, $existingChildIds) as $childId) {
                        \App\Models\AnnouncementChildModel::create([
                            'aid'     => $announcementId,
                            'childid' => $childId,
                        ]);
                    }

                    // Remove old
                    foreach (array_diff($existingChildIds, $newChildIds) as $childId) {
                        \App\Models\AnnouncementChildModel::where('aid', $announcementId)
                            ->where('childid', $childId)
                            ->delete();
                    }
                } else {
                    \App\Models\AnnouncementChildModel::where('aid', $announcementId)->delete();
                }
            }
            // ✅ CREATE CASE
            else {

                // dd('here in 728');
                $data = [
                    'title'             => $request->title,
                    'text'              => $request->text,
                    'eventDate'         => $eventDate,
                    'status'            => $status,
                    'createdBy'         => $userid,
                    'centerid'          => $centerid,
                    'createdAt'         => now(),
                    'announcementMedia' => json_encode($mediaFiles),
                    'audience'          => $request->audience,
                    'type'              => $request->type,
                ];

                if ($request->type == "events") {
                    $data['eventColor'] = $request->color;
                }
                //  dd($data);
                $announcement = \App\Models\AnnouncementsModel::create($data);
                $announcementId = $announcement->id;

                // Save child relations
                if (in_array($request->audience, ['all', 'parents']) && $request->childId) {
                    foreach ($request->childId as $childId) {
                        \App\Models\AnnouncementChildModel::create([
                            'aid'     => $announcementId,
                            'childid' => $childId,
                        ]);
                    }
                }
            }

            // ✅ Send notifications
            // $userIds = Usercenter::where('centerid', $centerid)->pluck('userid')->unique();
            // foreach ($userIds as $userId) {
            //     $user = User::find($userId);
            //     if ($user) {
            //         $user->notify(new AnnouncementAdded($announcement));
            //     }
            // }

            // Delete holiday if exists
            if ($request->holidayid) {
                PubicHoliday_Model::where('id', $request->holidayid)->delete();

                return redirect()->route('settings.public_holiday')->with([
                    'status' => 'success',
                    'msg'    =>  "{$request->type} updated successfully",
                    'type'   => $request->type,
                ]);
            }

            return redirect()->route('announcements.list')->with([
                'status' => 'success',
                'msg'    => $request->annId
                    ? "{$request->type} updated successfully"
                    : "{$request->type} created successfully",
                'type'   => $request->type,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
            // Validation errors
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('type', $request->input('type'));
        } catch (\Exception $e) {
            // Other errors
            dd($e);
            Log::error($e);
            return redirect()->back()
                ->with('status', 'error')
                ->with('message', 'Something went wrong: ' . $e->getMessage())
                ->withInput()
                ->with('type', $request->input('type'));
        }
    }

    /**
     * ✅ Helper to save media files (images/PDFs)
     */
    private function saveMediaFiles($files)
    {
        $mediaFiles = [];
        $manager = new ImageManager(new Driver()); // Intervention v3

        $destinationPath = public_path('uploads/announcements');
        File::ensureDirectoryExists($destinationPath);

        foreach ($files as $file) {
            if (str_starts_with($file->getMimeType(), 'image/')) {
                // ✅ Compress + resize image
                $image = $manager->read($file)
                    ->scaleDown(900, 900)
                    ->pad(900, 900, 'white');

                $quality   = 90;
                $maxSize   = 500 * 1024; // 500 KB
                $tempPath  = storage_path('app/temp_' . Str::random(10) . '.jpg');

                do {
                    $image->save($tempPath, quality: $quality);
                    $size = filesize($tempPath);
                    $quality -= 5;
                } while ($size > $maxSize && $quality > 10);

                $filename  = uniqid() . '.jpg';
                $finalPath = $destinationPath . '/' . $filename;
                rename($tempPath, $finalPath);

                $mediaFiles[] = url('uploads/announcements/' . $filename);
            } else {
                // ✅ PDF
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $mediaFiles[] = url('uploads/announcements/' . $filename);
            }
        }

        return $mediaFiles;
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
