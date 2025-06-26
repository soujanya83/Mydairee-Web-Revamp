<?php

namespace App\Http\Controllers;

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



class HeadChecks extends Controller
{
  public function index(Request $request)
    {
        // Validate user token (simulate $res = getAuthUserId)
        $user = Auth::user();
        $userType = $user->userType;
        $userId = $user->userid;


        $centerId = Session('user_center_id');
     

         if ($userType === "Superadmin") {
        $centerIds = Usercenter::where('userid', $userId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerId)->get();
    }

        // Determine room ID
        if (empty($payload['roomid'])) {
            $centerRoom = Room::where('centerid', $centerId)->first();
            $roomid = $centerRoom->id ?? null;
            $roomname = $centerRoom->name ?? '';
            $roomcolor = $centerRoom->color ?? '';
             $centerRooms = Room::where('centerid', $centerId)->get();
        } else {
            $roomid = $request->roomid;
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $centerRooms = Room::where('centerid', $centerId)->get();
        }

        // Date
        // dd($request->date);
      

        // Role-based permission check (customize based on your DB)
        $role = $user->role; // or $user->user_type
        $permission = null;

        if ($role === 'Staff') {
            $permission = \App\Models\PermissionsModel::where('userid', $user->userid)
                            ->where('centerid', $centerId)
                            ->first();
        }

          $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');
        // Get head checks
        $headChecks = DailyDiaryHeadCheckModel::where('createdBy', $user->userid)
                        ->where('roomid', $roomid)
                        ->whereDate('createdAt', $date)
                        ->get();

                        // dd($headChecks);

      return view('headchecks.index', [
    'centerid' => $centerId,
    'date' => $date,
    'roomid' => $roomid,
    'roomname' => $roomname,
    'roomcolor' => $roomcolor,
    'rooms' => $centerRooms,
    'headChecks' => $headChecks,
    'permissions' => $permission,
    'centers'=>$centers
]);

    }

 // if needed to check user auth (or a custom AuthService)

public function getCenterRooms(Request $request)
{

    $userId = Auth::user()->userid;
    $centerid = Session('user_center_id');

    // Fetch rooms from DB
    $rooms = Room::where('centerid', $centerid)->get();

    return response()->json([
        'Status' => 'SUCCESS',
        'Rooms' => $rooms
    ]);
}


 public function headchecksStore(Request $request){

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

    $diaryDate = str_replace("/", "-", $request->input('diarydate'));
    $diaryDate = date('Y-m-d', strtotime($diaryDate));
    $count = count($validated['hour']);
    $headcounts = [];
  $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');
  if ($request->headcheck) {
    DailyDiaryHeadCheckModel::where('roomid', $request->roomid)
        ->whereDate('createdAt', $date)
        ->delete();
}


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

    // Bulk insert
   $check =  DailyDiaryHeadCheckModel::insert($headcounts);

    // Redirect or respond
    return redirect()->route('headChecks', [
        'roomid' => $validated['roomid'],
        'date' => $diaryDate,
        'centerid' => $validated['centerid'],
    ])->with('success', 'Records added successfully');
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
