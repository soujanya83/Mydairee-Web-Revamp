<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\HeadCheck;
use App\Models\Room;
use App\Models\Center;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyDiaryHeadCheckModel;
use Illuminate\Support\Facades\Response;
use App\Models\Usercenter;
use App\Models\Child;
use App\Models\DailyDiarySleepCheckList;
use Illuminate\Support\Facades\Validator;
use App\Models\Childparent;


class SleepCheckController extends Controller
{

     
public function getSleepChecksList(Request $request)
{
    $user = Auth::user();
    $userid = $user->userid;
    $userType = $user->userType;
    $search = trim((string) $request->input('search', ''));
    $perPage = max((int) $request->input('per_page', 10), 1);
    // dd( $userType);
    // Determine center ID
    $centerid = $request->centerid;
    if (empty($centerid)) {
        $centerId = Usercenter::where('userid', $userid)->pluck('centerid')->first();
        $centerid = $centerId;
    } else {
        $centerId = $centerid;
    }

    // Fetch centers for user
    if ($userType === "Superadmin" || $userType === "Centeradmin") {
        $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerId)->get();
    }

    // Determine room
    if (empty($request->roomid)) {
        $centerRoom = Room::where('centerid', $centerid)->first();
        $roomid = $centerRoom->id ?? null;
        $roomname = $centerRoom->name ?? '';
        $roomcolor = $centerRoom->color ?? '';
        $centerRooms = Room::where('centerid', $centerid)->get();
    } else {
        $roomid = $request->roomid;
        $room = Room::find($roomid);
        $roomname = $room->name ?? '';
        $roomcolor = $room->color ?? '';
        $centerRooms = Room::where('centerid', $centerid)->get();
    }
   

    $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

    // Fetch children
    $role = $user->userType;
    $childrenQuery = Child::query()
        ->where('room', $roomid)
        ->where('status', 'Active');

    if ($role === "Parent") {
        $childIDs = Childparent::where('parentid', $userid)->pluck('childid');
        $childrenQuery->whereIn('id', $childIDs);
    }

    if ($search !== '') {
        $childrenQuery->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('lastname', 'like', '%' . $search . '%')
                ->orWhereRaw("CONCAT(COALESCE(name, ''), ' ', COALESCE(lastname, '')) LIKE ?", ['%' . $search . '%']);
        });
    }

    $children = $childrenQuery->orderBy('name')->paginate($perPage);

    // Fetch all sleep checks for the room and date for the current page children only
    $pageChildIds = $children->getCollection()->pluck('id')->all();
    $sleepChecks = DailyDiarySleepCheckList::where('roomid', $roomid)
        ->whereDate('created_at', $date)
        ->whereIn('childid', $pageChildIds)
        ->get()
        ->groupBy('childid');

    // Attach sleepchecks to children
    $childrenWithSleepChecks = $children->getCollection()->map(function ($child) use ($sleepChecks) {
        $child->sleepchecks = $sleepChecks->get($child->id, collect([]));
        return $child;
    });

    $children->setCollection($childrenWithSleepChecks);


    // Handle permissions
   
    if ($role === "Superadmin" || $role === "Centeradmin") {
         $permission = \App\Models\PermissionsModel::where('userid', $userid)
            ->where('centerid', $centerId)
            ->first();
    } elseif ($role === "Staff") {
        $permission = \App\Models\PermissionsModel::where('userid', $userid)
            ->where('centerid', $centerId)
            ->first();
    } else {
        $permission = null;
    }

    // Return JSON response
    return response()->json([
        'status'      => true,
        'message'     => 'Sleep checks list fetched successfully.',
        'centerid'    => $centerid,
        'date'        => $date,
        'roomid'      => $roomid,
        'roomname'    => $roomname,
        'roomcolor'   => $roomcolor,
        'children'    => $childrenWithSleepChecks,
        'pagination'  => [
            'current_page' => $children->currentPage(),
            'per_page' => $children->perPage(),
            'total' => $children->total(),
            'last_page' => $children->lastPage(),
        ]
        // 'rooms'       => $centerRooms ?? [],
        // 'permissions' => $permission,
        // 'centers'     => $centers
    ]);
}

public function getmernSleepChecksList(Request $request)
{
    $user = Auth::user();
    $userid = $user->userid;
    $userType = $user->userType;
    $search = trim((string) $request->input('search', ''));
    $perPage = max((int) $request->input('per_page', 10), 1);
    // dd( $userType);
    // Determine center ID
    $centerid = $request->centerid;
    if (empty($centerid)) {
        $centerId = Usercenter::where('userid', $userid)->pluck('centerid')->first();
        $centerid = $centerId;
    } else {
        $centerId = $centerid;
    }

    // Fetch centers for user
    if ($userType === "Superadmin" || $userType === "Centeradmin") {
        $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerId)->get();
    }

    $parentChildIds = collect();
    $selectedChildId = null;
    $selectedChildSource = null;
    $selectedChild = null;

    if ($userType === 'Parent') {
        $parentChildIds = Childparent::where('parentid', $userid)->pluck('childid')->values();
        $savedChildId = User::where('userid', $userid)->value('selectedchildreanid');
        $requestedChildId = $request->input('child_id', $request->input('childid'));

        if (!empty($requestedChildId) && trim((string) $requestedChildId) !== '') {
            $requestedChildId = (int) $requestedChildId;

            if ($parentChildIds->contains($requestedChildId)) {
                $selectedChildId = $requestedChildId;
                $selectedChildSource = 'request';
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'This child does not belong to this parent'
                ], 403);
            }
        }

        if (!$selectedChildId && !empty($savedChildId) && $parentChildIds->contains((int) $savedChildId)) {
            $selectedChildId = (int) $savedChildId;
            $selectedChildSource = 'saved';
        }

        if (!$selectedChildId && $parentChildIds->isNotEmpty()) {
            $selectedChildId = (int) $parentChildIds->first();
            $selectedChildSource = 'fallback';
        }
    }

    // Determine room
    if (empty($request->roomid)) {
        $centerRoom = Room::where('centerid', $centerid)->first();
        $roomid = $centerRoom->id ?? null;
        $roomname = $centerRoom->name ?? '';
        $roomcolor = $centerRoom->color ?? '';
        $centerRooms = Room::where('centerid', $centerid)->get();
    } else {
        $roomid = $request->roomid;
        $room = Room::find($roomid);
        $roomname = $room->name ?? '';
        $roomcolor = $room->color ?? '';
        $centerRooms = Room::where('centerid', $centerid)->get();
    }

    if ($selectedChildId) {
        $selectedChild = Child::where('id', $selectedChildId)->first();

        if ($selectedChild) {
            $childRoom = Room::where('id', $selectedChild->room)->first();

            if ($childRoom) {
                $roomid = $childRoom->id;
                $roomname = $childRoom->name ?? '';
                $roomcolor = $childRoom->color ?? '';
            }
        }
    }
   

    $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

    // Fetch children
    $role = $user->userType;
    $childrenQuery = Child::query()
        ->where('room', $roomid)
        ->where('status', 'Active');

    if ($role === "Parent") {
        $targetChildIds = $selectedChildId ? collect([$selectedChildId]) : $parentChildIds;
        $childrenQuery->whereIn('id', $targetChildIds);
    }

    if ($search !== '') {
        $childrenQuery->where(function ($query) use ($search) {
            if (is_numeric($search)) {
                $query->where('id', (int) $search);
            } else {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%')
                    ->orWhereRaw("CONCAT(COALESCE(name, ''), ' ', COALESCE(lastname, '')) LIKE ?", ['%' . $search . '%']);
            }
        });
    }

    $children = $childrenQuery->orderBy('name')->paginate($perPage);

    // Fetch all sleep checks for the room and date for the current page children only
    $pageChildIds = $children->getCollection()->pluck('id')->all();
    $sleepChecks = DailyDiarySleepCheckList::where('roomid', $roomid)
        ->whereDate('diarydate', $date)
        ->whereIn('childid', $pageChildIds)
        ->get()
        ->groupBy('childid');

    // Attach sleepchecks to children
    $childrenWithSleepChecks = $children->getCollection()->map(function ($child) use ($sleepChecks) {
        $child->sleepchecks = $sleepChecks->get($child->id, collect([]));
        return $child;
    });

    $children->setCollection($childrenWithSleepChecks);

    $hasSleepChecks = $childrenWithSleepChecks->contains(function ($child) {
        return !empty($child->sleepchecks) && $child->sleepchecks->isNotEmpty();
    });


    // Handle permissions
   
    if ($role === "Superadmin" || $role === "Centeradmin") {
         $permission = \App\Models\PermissionsModel::where('userid', $userid)
            ->where('centerid', $centerId)
            ->first();
    } elseif ($role === "Staff") {
        $permission = \App\Models\PermissionsModel::where('userid', $userid)
            ->where('centerid', $centerId)
            ->first();
    } else {
        $permission = null;
    }

    // Choose message based on user type and presence of data
    if ($userType === 'Parent') {
        $responseMessage = $hasSleepChecks ? 'Sleep checks list fetched successfully.' : 'No sleep checks found for the selected child.';
    } else {
        $responseMessage = $hasSleepChecks ? 'Sleep checks list fetched successfully.' : 'No sleep checks found for the selected room.';
    }

    // Return JSON response
    return response()->json([
        'status'      => true,
        'message'     => $responseMessage,
        'centerid'    => $centerid,
        'date'        => $date,
        'roomid'      => $roomid,
        'roomname'    => $roomname,
        'roomcolor'   => $roomcolor,
        'children'    => $childrenWithSleepChecks,
        'pagination'  => [
            'current_page' => $children->currentPage(),
            'per_page' => $children->perPage(),
            'total' => $children->total(),
            'last_page' => $children->lastPage(),
        ]
        // 'rooms'       => $centerRooms ?? [],
        // 'permissions' => $permission,
        // 'centers'     => $centers
    ]);
}

// public function getSleepChecksList(Request $request)
// {
//     $user = Auth::user();
//     $userid = $user->userid;
//     $userType = $user->userType;
//     $centerid = $request->centerid;

//     if (empty($centerid)) {
//         $centerId = Usercenter::where('userid', $userid)->pluck('centerid')->first();
//         $center = Center::find($centerId);
//         $centerid = $center?->id;
//     } else {
//         $centerId = $centerid;
//     }

//     if ($userType === "Superadmin") {
//         $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
//         $centers = Center::whereIn('id', $centerIds)->get();
//     } else {
//         $centers = Center::where('id', $centerid)->get();
//     }

//     if (empty($request->roomid)) {
//         $centerRoom = Room::where('centerid', $centerid)->first();
//         $roomid = $centerRoom->id ?? null;
//         $roomname = $centerRoom->name ?? '';
//         $roomcolor = $centerRoom->color ?? '';
//         $centerRooms = Room::where('centerid', $centerid)->get();
//     } else {
//         $roomid = $request->roomid;
//         $room = Room::find($roomid);
//         $roomname = $room->name ?? '';
//         $roomcolor = $room->color ?? '';
//         $centerRooms = Room::where('centerid', $centerid)->get();
//     }

//     $roomid = $roomid ?? $room->id ?? null;
//     $roomname = $roomname ?? null;
//     $roomcolor = $roomcolor ?? null;

//     $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

//     if ($userType === "Superadmin" || $userType === "Staff") {
//         $permission = \App\Models\PermissionsModel::where('userid', $user->userid)
//             ->where('centerid', $centerId)
//             ->first();
//     } else {
//         $permission = null;
//     }

//     if ($userType == 'Parent') {
//         $childIDs = Childparent::where('parentid', $userid)->pluck('childid');
//         $children = Child::whereIn('id', $childIDs)->get();

//         $sleepChecks = DailyDiarySleepCheckList::where(['createdBy' => $userid, 'roomid' => $roomid])
//             ->whereDate('created_at', $date)
//             ->whereIn('childid', $childIDs)
//             ->get();
//     } else {
//         $children = Child::where('room', $roomid)->get();

//         $sleepChecks = DailyDiarySleepCheckList::where(['createdBy' => $userid, 'roomid' => $roomid])
//             ->whereDate('created_at', $date)
//             ->get();
//     }

//     return response()->json([
//         'centerid'     => $centerid,
//         'date'         => $date,
//         'roomid'       => $roomid,
//         'children'     => $children,
//         'roomname'     => $roomname,
//         'roomcolor'    => $roomcolor,
//         'rooms'        => $centerRooms ?? [],
//         'sleepChecks'  => $sleepChecks,
//         'permissions'  => $permission,
//         'centers'      => $centers
//     ]);
// }



        public function sleepcheckSave(Request $request)
{
       $validator = Validator::make($request->all(), [
        'childid'          => 'required|integer|exists:child,id',
        'roomid'           => 'required|integer|exists:room,id',
        'time'             => 'required|string',
        'breathing'        => 'nullable|string',
        'body_temperature' => 'nullable|string',
        'notes'            => 'nullable|string',
        'signature'        => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }


    // Convert date to Y-m-d
     $date = \DateTime::createFromFormat('d-m-Y', $request->diarydate, new \DateTimeZone('Australia/Sydney'));
    $mysqlDate = $date ? $date->format('Y-m-d') : null;

    // Get current datetime in Australia/Sydney
    $nowSydney = now()->setTimezone('Australia/Sydney');

  

    // Get logged in user ID (you can also use Auth::id() if using Laravel auth)
    $createdBy = Auth::user()->userid;
    // $createdBy = $request->userid;

    // Save record
    $check = DailyDiarySleepChecklist::create([
        'childid'          => $request->childid,
        'diarydate'        => $mysqlDate,
        'roomid'           => $request->roomid,
        'time'             => $request->time,
        'breathing'        => $request->breathing,
        'body_temperature' => $request->body_temperature,
        'notes'            => $request->notes,
        'createdBy'        => $createdBy,
        'created_at'       =>  $nowSydney,
        'signature'        => $request->signature
    ]);

    if ($check) {
        return response()->json([
            'status' => true,
            'message' => 'Saved successfully',
             'data' => $check->id
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'Failed to save',
       
    ]);
}

    /**
     * Bulk save sleep check entries for multiple children (API)
     */
    public function bulkSave(Request $request)
    {
        if ($request->has('child_ids') && is_string($request->child_ids)) {
            $request->merge([
                'child_ids' => array_map('intval', explode(',', $request->child_ids))
            ]);
        }
        $validator = Validator::make($request->all(), [
            'child_ids'        => 'required|array|min:1',
            'child_ids.*'      => 'required|integer|exists:child,id',
            'diarydate'        => 'required|date_format:d-m-Y',
            'roomid'           => 'required|integer|exists:room,id',
            'time'             => 'required|string',
            'breathing'        => 'nullable|string',
            'temperature'      => 'nullable|string',
            'notes'            => 'nullable|string',
            'signature'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $date = \DateTime::createFromFormat('d-m-Y', $request->diarydate, new \DateTimeZone('Australia/Sydney'));
        $mysqlDate = $date ? $date->format('Y-m-d') : null;
        $nowSydney = now()->setTimezone('Australia/Sydney');
        $createdBy = Auth::user()->userid;

        $successCount = 0;
        foreach ($request->child_ids as $childid) {
            $check = DailyDiarySleepCheckList::create([
                'childid'          => $childid,
                'diarydate'        => $mysqlDate,
                'roomid'           => $request->roomid,
                'time'             => $request->time,
                'breathing'        => $request->breathing,
                'body_temperature' => $request->temperature,
                'notes'            => $request->notes,
                'createdBy'        => $createdBy,
                'created_at'       => $nowSydney,
                'signature'        => $request->signature
            ]);
            if ($check) $successCount++;
        }

        if ($successCount > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Bulk entries saved successfully!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Bulk save failed.'
        ]);
    }

public function sleepcheckUpdate(Request $request)
{
    // // Validate input
    // $validator = $request->validate([

    //     'childid'          => 'required|integer|exists:child,id',
    //     'diarydate'        => 'required|date_format:d-m-Y',
    //     'roomid'           => 'required|integer|exists:room,id',
    //     'time'             => 'required|string',
    //     'breathing'        => 'nullable|string',
    //     'body_temperature' => 'nullable|string',
    //     'notes'            => 'nullable|string',
    // ]);
// dd('here');
       $validator = Validator::make($request->all(), [
        'id'               => 'required|integer|exists:dailydiarysleepchecklist,id',
        'childid'          => 'required|integer|exists:child,id',
        'diarydate'        => 'required|date_format:d-m-Y',
        'roomid'           => 'required|integer|exists:room,id',
        'time'             => 'required|string',
        'breathing'        => 'nullable|string',
        'body_temperature' => 'nullable|string',
        'notes'            => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }


    // Convert diarydate to Y-m-d
     $date = \DateTime::createFromFormat('d-m-Y', $request->diarydate, new \DateTimeZone('Australia/Sydney'));
    $mysqlDate = $date ? $date->format('Y-m-d') : null;

    // Find and update
    $entry = DailyDiarySleepChecklist::find($request->id);
    $entry->childid = $request->childid;
    $entry->diarydate = $mysqlDate;
    $entry->roomid = $request->roomid;
    $entry->time = $request->time;
    $entry->breathing = $request->breathing;
    $entry->body_temperature = $request->body_temperature;
    $entry->notes = $request->notes;
      $entry->signature = $request->signature;

    $updated = $entry->isDirty() ? $entry->save() : false;

    if ($updated) {
        return response()->json([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'No changes made or update failed'
        ]);
    }
}
            
        public function sleepcheckDelete(Request $request)
        {
           $request->validate([
        'id' => 'required|integer',
    ]);

    // Check if record exists first
    $record = DailyDiarySleepChecklist::find($request->id);



    if (!$record) {
        return response()->json([
            'status' => false,
            'message' => 'Record not found or already deleted.'
        ], 404);
    }

            $deleted = DailyDiarySleepChecklist::where('id', $request->id)->delete();

            if ($deleted) {
                return response()->json([
                    'status' => true,
                    'message' => 'Deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete or already removed'
                ]);
            }
        }


}
