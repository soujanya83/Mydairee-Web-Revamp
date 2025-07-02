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
use App\Models\PermissionsModel;


class HeadChecks extends Controller
{
public function index(Request $request)
{
    // Authenticated user
    $user = Auth::user();
    // $user = User::where('userid',$request->userid)->first();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized access.'
        ], 401);
    }

    $userType = $user->userType;
    $userId = $user->userid;

    // Center ID from session
    // $centerId = session('user_center_id');
    $centerId   = $request->centerid;

    if (!$centerId) {
        return response()->json([
            'status' => 'error',
            'message' => 'Center ID missing from session.'
        ], 400);
    }

    // Get centers based on user role
    if ($userType === "Superadmin") {
        $centerIds = Usercenter::where('userid', $userId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerId)->get();
    }

    // Determine room ID and room details
    $roomid = $request->roomid;
    $centerRooms = Room::where('centerid', $centerId)->get();

    if (empty($roomid)) {
        $centerRoom = $centerRooms->first();
        $roomid = $centerRoom->id ?? null;
        $roomname = $centerRoom->name ?? '';
        $roomcolor = $centerRoom->color ?? '';
    } else {
        $room = Room::find($roomid);
        $roomname = $room->name ?? '';
        $roomcolor = $room->color ?? '';
    }

    // Current or selected date
    $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

    // Get permissions if staff
    $permission = null;
    if ($userType === 'Staff') {
        $permission = PermissionsModel::where('userid', $userId)
            ->where('centerid', $centerId)
            ->first();
    }

    // Get head checks
    $headChecks = DailyDiaryHeadCheckModel::where('createdBy', $userId)
        ->where('roomid', $roomid)
        ->whereDate('createdAt', $date)
        ->get();

    // Respond as JSON if requested
    
        return response()->json([
            'status' => 'success',
            'message' => 'Head checks data fetched successfully.',
            'data' => [
                'centerid' => $centerId,
                'date' => $date,
                'roomid' => $roomid,
                'roomname' => $roomname,
                'roomcolor' => $roomcolor,
                'rooms' => $centerRooms,
                'headChecks' => $headChecks,
                'permissions' => $permission,
                'centers' => $centers
            ]
        ]);
    
}


 // if needed to check user auth (or a custom AuthService)

public function getCenterRooms(Request $request)
{

    // $userId = Auth::user()->userid;
     $centerid   = $request->centerid;

    if (!$centerid) {
        return response()->json([
            'status' => 'error',
            'message' => 'Center ID missing from session.'
        ], 400);
    }

      $user = User::where('userid',Auth::user()->userid)->first();

    // Fetch rooms from DB
    $rooms = Room::where('centerid', $centerid)->get();
    if(!$rooms){
           return response()->json([
        'Status' => 'false',
        'Rooms' => []
    ]);
    }

    return response()->json([
        'Status' => 'true',
        'Rooms' => $rooms
    ]);
}


//  public function headchecksStore(Request $request){

//     $validated = $request->validate([
//         'hour'      => 'required|array',
//         'mins'      => 'required|array',
//         'headCount' => 'required|array',
//         'signature' => 'required|array',
//         'comments'  => 'required|array',
//         'roomid'    => 'required|integer',
//         'centerid'  => 'required|integer',
//         'diarydate' => 'required|string',
//     ]);

//     $diaryDate = str_replace("/", "-", $request->input('diarydate'));
//     $diaryDate = date('Y-m-d', strtotime($diaryDate));
//     $count = count($validated['hour']);
//     $headcounts = [];
//   $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');
//   if ($request->headcheck) {
//     DailyDiaryHeadCheckModel::where('roomid', $request->roomid)
//         ->whereDate('createdAt', $date)
//         ->delete();
// }


//     for ($i = 0; $i < $count; $i++) {
//         $headcounts[] = [
//             'time'      => $validated['hour'][$i] . 'h:' . $validated['mins'][$i] . 'm',
//             'diarydate' => $diaryDate,
//             'headCount' => $validated['headCount'][$i],
//             'signature' => $validated['signature'][$i],
//             'comments'  => $validated['comments'][$i],
//             'roomid'    => $validated['roomid'],
//             'createdBy' => Auth::user()->userid,
//             'createdAt' => now(),
//         ];
//     }

//     // Bulk insert
//    $check =  DailyDiaryHeadCheckModel::insert($headcounts);

//     // Redirect or respond
//     return redirect()->route('headChecks', [
//         'roomid' => $validated['roomid'],
//         'date' => $diaryDate,
//         'centerid' => $validated['centerid'],
//     ])->with('success', 'Records added successfully');
// }


public function headchecksStore(Request $request)
{
    $validated = $request->validate([
        'hour'      => 'required|array',
        'mins'      => 'required|array',
        'headCount' => 'required|array',
        'signature' => 'required|array',
        'comments'  => 'required|array',
        'roomid'    => 'required|integer',
        'centerid'  => 'required|integer',
        'diarydate' => 'required|string',
    ]);

    try {
        $diaryDate = str_replace("/", "-", $request->input('diarydate'));
        $diaryDate = date('Y-m-d', strtotime($diaryDate));
        $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

        if ($request->headcheck) {
            DailyDiaryHeadCheckModel::where('roomid', $validated['roomid'])
                ->whereDate('createdAt', $date)
                ->delete();
        }

        $count = count($validated['hour']);
        $headcounts = [];

        for ($i = 0; $i < $count; $i++) {
            $headcounts[] = [
                'time'      => $validated['hour'][$i] . 'h:' . $validated['mins'][$i] . 'm',
                'diarydate' => $diaryDate,
                'headCount' => $validated['headCount'][$i],
                'signature' => $validated['signature'][$i],
                'comments'  => $validated['comments'][$i],
                'roomid'    => $validated['roomid'],
                'createdBy' => Auth::user()->userid,
                'createdAt' => now(),
            ];
        }

        DailyDiaryHeadCheckModel::insert($headcounts);

        return response()->json([
            'status' => 'true',
            'message' => 'Records added successfully.',
            'data' => [
                'roomid' => $validated['roomid'],
                'centerid' => $validated['centerid'],
                'date' => $diaryDate
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'false',
            'message' => 'Something went wrong!',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function headcheckDelete(Request $request)
{
    $request->validate([
        'headCheckId' => 'required|integer|exists:dailydiaryheadcheck,id',
    ]);

    $headCheckId = $request->headCheckId;

    $deleted = DailyDiaryHeadCheckModel::where('id', $headCheckId)->delete();

    if ($deleted) {
        return response()->json([
            'Status' => 'SUCCESS',
            'Message' => 'Record deleted successfully'
        ]);
    } else {
        return response()->json([
            'Status' => 'ERROR',
            'Message' => 'Failed to delete the record'
        ]);
    }
}
}
