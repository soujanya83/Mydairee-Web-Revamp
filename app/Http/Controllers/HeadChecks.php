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
use App\Models\RoomStaff;
use Illuminate\Support\Facades\Response;
use App\Models\Usercenter;



class HeadChecks extends Controller
{
public function headcheckprint(Request $request)
{
    $request->validate([
        'roomid' => 'required',
        'centerid' => 'required',
        'diarydate' => 'required'
    ]);

    $roomid = $request->roomid;
    $inputDate = $request->diarydate;

    // Convert from d-m-Y to Y-m-d
    $date = \Carbon\Carbon::createFromFormat('d-m-Y', $inputDate);
    $formattedDate = $date->format('Y-m-d');

    // Extract month & year for filtering
    $month = $date->format('m');
    $year = $date->format('Y');

    $room = Room::where('id', $roomid)->select('name')->first();

    // Filter by same month and year
    $headchecks = DailyDiaryHeadCheckModel::where('roomid', $roomid)
        ->whereMonth('diarydate', $month)
        ->whereYear('diarydate', $year)
        ->get();

        // dd($headchecks);

    return view('headChecks.print', compact('room', 'month', 'headchecks'));
}


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
$staff = RoomStaff::where('roomid', $request->roomid)->pluck('staffid');

$staffDetails = User::whereIn('userid', $staff)->get();

      

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

      return view('headChecks.index', [
    'centerid' => $centerId,
    'date' => $date,
    'roomid' => $roomid,
    'roomname' => $roomname,
    'roomcolor' => $roomcolor,
    'rooms' => $centerRooms,
    'headChecks' => $headChecks,
    'permissions' => $permission,
    'centers'=>$centers,
    'staffs' => $staffDetails
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


public function headchecksStore(Request $request)
{
    $validated = $request->validate([
        'timePicker' => 'required|array',
        'headCount'  => 'required|array',
        'signature'  => 'required|array',
        'roomid'     => 'required|integer',
        'centerid'   => 'required|integer',
        'diarydate'  => 'required|string',
    ]);

    // Format diary date
    $diaryDate = str_replace("/", "-", $validated['diarydate']);
    $diaryDate = date('Y-m-d', strtotime($diaryDate));

    // Optional date (for delete filter)
    $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

    // Delete existing records if 'headcheck' flag is sent
    if ($request->headcheck) {
        DailyDiaryHeadCheckModel::where('roomid', $validated['roomid'])
            ->whereDate('createdAt', $date)
            ->delete();
    }

    $headchecks = [];

    for ($i = 0; $i < count($validated['timePicker']); $i++) {
        // Convert HH:MM to 1h:00m
        [$hours, $minutes] = explode(':', $validated['timePicker'][$i]);
        $convertedTime = intval($hours) . 'h:' . intval($minutes) . 'm';

        $headchecks[] = [
            'time'      => $convertedTime,
            'diarydate' => $diaryDate,
            'headcount' => $validated['headCount'][$i],
            'signature' => $validated['signature'][$i],
            'roomid'    => $validated['roomid'],
            'createdBy' => Auth::id(),  // use Auth::user()->userid if needed
            'createdAt' => now(),
        ];
    }

    // Insert all rows
    DailyDiaryHeadCheckModel::insert($headchecks);

    return redirect()->route('headChecks', [
        'roomid'   => $validated['roomid'],
        'date'     => $diaryDate,
        'centerid' => $validated['centerid'],
    ])->with('success', 'Records added successfully.');
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
