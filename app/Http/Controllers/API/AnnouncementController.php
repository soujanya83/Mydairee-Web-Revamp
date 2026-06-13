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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Support\Facades\Log;


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

        if ($userType === "Superadmin" || $userType === "Centeradmin") {
            $centerIds = Usercenter::where('userid', $userId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $centerIds)->get();
        } else {
            $centers = Center::where('id', $centerId)->get();
        }

        // Fetch announcements with pagination
        if ($userType === 'Staff' || $userType === 'Superadmin' || $userType === 'Centeradmin') {
            $query = AnnouncementsModel::with('creator') // optional relationship
                ->where('centerid', $centerId);

            if ($userType === 'Staff') {
                $staffRoomIds = \App\Models\RoomStaff::where('staffid', $user->id)->pluck('roomid');
                $staffChildIds = $staffRoomIds->isNotEmpty()
                    ? Child::whereIn('room', $staffRoomIds)->pluck('id')
                    : collect();
                $taggedAnnouncementIds = $staffChildIds->isNotEmpty()
                    ? AnnouncementChildModel::whereIn('childid', $staffChildIds)->pluck('aid')->unique()->values()
                    : collect();

                $query->where(function ($staffQuery) use ($userId, $taggedAnnouncementIds) {
                    $staffQuery->where('createdBy', $userId);

                    if ($taggedAnnouncementIds->isNotEmpty()) {
                        $staffQuery->orWhereIn('id', $taggedAnnouncementIds);
                    }
                });
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

    public function mernlist(Request $request)
    {
        // $centerId = Session::get('user_center_id');
        $centerId = $request->centerid;
        $perPage = $request->input('per_page', 9);
        // $user = User::where('userid',$request->userid)->first();
        $user = Auth::user();
        $userId = $user->userid;
        $userType = $user->userType;
        $permissions = null;
        $selectedChildId = null;
        $selectedChildSource = null;

        if ($userType === "Superadmin" || $userType === "Centeradmin") {
            $centerIds = Usercenter::where('userid', $userId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $centerIds)->get();
        } else {
            $centers = Center::where('id', $centerId)->get();
        }

        // Fetch announcements with pagination
        if ($userType === 'Staff' || $userType === 'Superadmin' || $userType === 'Centeradmin') {
            $query = AnnouncementsModel::with('creator') // optional relationship
                ->where('centerid', $centerId);

            if ($userType === 'Staff') {
                $staffRoomIds = \App\Models\RoomStaff::where('staffid', $user->id)->pluck('roomid');
                $staffChildIds = $staffRoomIds->isNotEmpty()
                    ? Child::whereIn('room', $staffRoomIds)->pluck('id')
                    : collect();
                $taggedAnnouncementIds = $staffChildIds->isNotEmpty()
                    ? AnnouncementChildModel::whereIn('childid', $staffChildIds)->pluck('aid')->unique()->values()
                    : collect();

                $query->where(function ($staffQuery) use ($userId, $taggedAnnouncementIds) {
                    $staffQuery->where('createdBy', $userId);

                    if ($taggedAnnouncementIds->isNotEmpty()) {
                        $staffQuery->orWhereIn('id', $taggedAnnouncementIds);
                    }
                });
            }

            $records = $query->orderByDesc('id')->paginate($perPage); // ✅ Pagination applied
        } else {
            // For Parents - resolve one selected child first
            $childIds = Childparent::where('parentid', $userId)->pluck('childid')->values();
            $requestedChildId = $request->input('child_id', $request->input('childid'));
            $savedChildId = User::where('userid', $userId)->value('selectedchildreanid');

            if (!empty($requestedChildId) && trim((string) $requestedChildId) !== '') {
                $requestedChildId = (int) $requestedChildId;

                if (!$childIds->contains($requestedChildId)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'This child does not belong to this parent',
                    ], 403);
                }

                $selectedChildId = $requestedChildId;
                $selectedChildSource = 'request';
            }

            if (!$selectedChildId && !empty($savedChildId) && $childIds->contains((int) $savedChildId)) {
                $selectedChildId = (int) $savedChildId;
                $selectedChildSource = 'saved';
            }

            if (!$selectedChildId && $childIds->isNotEmpty()) {
                $selectedChildId = (int) $childIds->first();
                $selectedChildSource = 'fallback';
            }

            if (!$selectedChildId) {
                return response()->json([
                    'status' => false,
                    'message' => 'No affiliated child found for this parent.',
                    'data' => [
                        'records' => [],
                        'permissions' => $permissions,
                        'centers' => $centers,
                        'centerId' => $centerId,
                        'userType' => $userType,
                        'selectedChildId' => null,
                        'selectedChildSource' => null,
                    ]
                ]);
            }

            $announcementIds = AnnouncementChildModel::where('childid', $selectedChildId)
                ->distinct()
                ->pluck('aid')
                ->filter()
                ->values();

            $records = AnnouncementsModel::with('creator')
                ->whereIn('id', $announcementIds)
                ->where('status', 'Sent')
                ->orderByDesc('id')
                 ->paginate($perPage);
        }

        if ($userType === 'Parent' && $records->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No announcements found for the selected child.',
                'data' => [
                    'records' => [],
                    'permissions' => $permissions,
                    'centers' => $centers,
                    'centerId' => $centerId,
                    'userType' => $userType,
                    'selectedChildId' => $selectedChildId,
                    'selectedChildSource' => $selectedChildSource,
                ]
            ]);
        }

        // Attach creator name manually if needed
        foreach ($records->items() as $announcement) {
            $creator = User::where('userid', $announcement->createdBy)->first();
            $announcement->createdBy = $creator->name ?? 'Not Available';
        }

        // Fix old image URLs
        foreach ($records->items() as $announcement){
            $announcement->announcementMedia = str_replace(
                'mydiaree.com.au',
                'api.mydiaree.com.au',
                $announcement->announcementMedia
            );
            // Fix double api prefix if already present
            $announcement->announcementMedia = str_replace(
                'api.api.mydiaree.com.au',
                'api.mydiaree.com.au',
                $announcement->announcementMedia
            );
        }



        $selectionMeta = $userType === 'Parent'
            ? [
                'selectedChildId' => $selectedChildId,
                'selectedChildSource' => $selectedChildSource,
            ]
            : [];

        return response()->json([ 
        'status' => true,
        'data' => [
          
            
            'centerId' => $centerId,
            'records' => $records,

        ] + $selectionMeta
        ]);

    }

    public function Filterlist(Request $request)
    {
        $centerId = $request->centerid ?? session('user_center_id');
        $user = \Auth::user();
        $userId = $user->userid;
        $userType = $user->userType;

        $query = AnnouncementsModel::query();

        if ($centerId) {
            $query->where('centerid', $centerId);
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('eventDate', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('eventDate', '<=', $request->date_to);
        }

        if ($request->filled('createdBy')) {
            $query->whereHas('creator', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->createdBy . '%');
            });
        }

        $records = $query->orderByDesc('id')->get();

        // Attach creator name if needed
        foreach ($records as $announcement) {
            $creator = \App\Models\User::where('userid', $announcement->createdBy)->first();
            $announcement->createdBy = $creator->name ?? 'Not Available';
        }

        return response()->json([
            'status' => true,
            'records' => $records,
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
                'permissions' => $permissions,
                'userType' =>  $user->userType
            ]
        ]);

    }


    public function AnnouncementStore(Request $request)
    {
        $eventDateInput = $request->input('eventDate');
        if (!empty($eventDateInput)) {
            $request->merge([
                'eventDate' => str_replace('/', '-', $eventDateInput),
            ]);
        }

        $status = $request->input('status', 'Pending');
                $childIds = $request->input('childId', []);
            if (is_string($childIds)) {
                $childIds = explode(',', $childIds);
            }

        if (is_array($childIds)) {
            $flattenedChildIds = [];

            foreach ($childIds as $childId) {
                if (is_string($childId) && str_contains($childId, ',')) {
                    foreach (explode(',', $childId) as $nestedChildId) {
                        $nestedChildId = trim($nestedChildId);
                        if ($nestedChildId !== '') {
                            $flattenedChildIds[] = $nestedChildId;
                        }
                    }

                    continue;
                }

                $childId = trim((string) $childId);
                if ($childId !== '') {
                    $flattenedChildIds[] = $childId;
                }
            }

            $request->merge([
                'childId' => array_values(array_unique($flattenedChildIds)),
            ]);
        }

        // ✅ Use Validator instead of $request->validate()
        $validator = Validator::make($request->all(), [
            'title'      => 'required|string|max:255',
            'text'       => 'required|string',
            'eventDate'  => 'nullable|date_format:d-m-Y',
            'eventColor' => 'nullable|string|max:50',
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

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
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
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'You can upload either images or PDFs, not both together.',
                    ], 422);
                }
            }

            $userid    = Auth::user()->userid;
            $centerid  = $request->centerid;
            $announcementId = null;
            $eventColor = $request->input('eventColor', $request->input('color'));

            $eventDate = empty($request->eventDate)
                ? now()->addDay()->format('Y-m-d')
                : Carbon::createFromFormat('d-m-Y', $request->eventDate)->format('Y-m-d');

            $role   = Auth::user()->userType;
            $run    = 0;
            

            if ($role === "Superadmin" || $role === "Centeradmin") {
                $run = 1;
                
            } elseif ($role === "Staff") {
                $permission = \App\Models\PermissionsModel::where('userid', $userid)
                    ->where('centerid', $centerid)
                    ->first();

                if ($permission && ($permission->addAnnouncement || $permission->updateAnnouncement)) {
                    $run = 1;
                
                }
            }

            if ($run !== 1) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Permission Denied!',
                ], 403);
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
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Announcement not found!',
                    ], 404);
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
                    'eventColor'        => $eventColor,
                    'announcementMedia' => !empty($mediaFiles) ? json_encode($mediaFiles) : $announcement->announcementMedia,
                    'type'              => $request->type,
                    'audience'          => $request->audience,
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
                    'eventColor'        => $eventColor,
                    'announcementMedia' => json_encode($mediaFiles),
                    'type'              => $request->type,
                    'audience'          => $request->audience,
                ]);

                if (!$announcement) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Failed to create announcement!',
                    ], 500);
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

 
            if (strtolower($status) === 'sent') {
                $parentChildIds = \App\Models\AnnouncementChildModel::where('aid', $announcementId)
                    ->pluck('childid')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                // Get center staff to notify them as well
                $centerUserIds = \App\Models\Usercenter::where('centerid', $centerid)
                    ->pluck('userid')
                    ->unique()
                    ->values()
                    ->all();

                if (!empty($parentChildIds) || !empty($centerUserIds)) {
                    Log::info('ANNOUNCEMENT_FCM_TRIGGER', [
                        'announcement_id' => $announcementId,
                        'status' => $status,
                        'child_ids' => $parentChildIds,
                        'user_ids' => $centerUserIds,
                    ]);

                    $firebaseService = app(\App\Services\Firebase\FirebaseNotificationService::class);
                    $moduleType = strtolower($announcement->type) === 'events' ? 'event' : 'announcement';
                    \App\Http\Controllers\API\DeviceController::notifyParentsModuleCreated(
                        $parentChildIds,
                        $moduleType,
                        $announcementId,
                        $userid,
                        $firebaseService,
                        null,
                        [
                            'type' => $moduleType,
                            'module_id' => (string)$announcementId,
                            'child_ids' => implode(',', $parentChildIds),
                            'created_by' => (string)$userid,
                        ],
                        $centerUserIds
                    );
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => $request->annId 
                    ? 'Announcement updated successfully' 
                    : 'Announcement created successfully',
                'announcement' => $announcement,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong!',
                'error'   => $e->getMessage(),
            ], 500);
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

        // Normalize legacy media URLs for old announcements

        $media = json_decode($announcementInfo->announcementMedia, true);

        if (is_array($media)) {
            foreach ($media as &$url) {
                $url = preg_replace(
                    '#https?://mydiaree\.com\.au#',
                    'https://api.mydiaree.com.au',
                    $url
                );
            }

            $announcementInfo->announcementMedia = json_encode($media);
        }

        // Fetch children linked to this announcement
        $childIds = \App\Models\AnnouncementChildModel::where('aid', $announcementId)->pluck('childid');
        $children = \App\Models\Child::whereIn('id', $childIds)->get();

        return response()->json([
            'status' => 'true',
            'data' => [
                'info' => $announcementInfo,
                'children' => $children,
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
        if ($role === "Superadmin" || $role === "Centeradmin") {
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
