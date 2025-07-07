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
use App\Models\Child;
use App\Models\DailyDiarySleepCheckList;
use Illuminate\Support\Facades\Validator;

class SleepCheckController extends Controller
{
  

public function getSleepChecksList(Request $request)
{
        $user = Auth::user(); // implement this logic in your LoginModel
   
        $userid = $user->userid;
        $userType = $user->userType;
      
        
        $centerid = Session('user_center_id') ;

    if (empty($centerid)) {
    // Get the first center ID assigned to the user
    $centerId = Usercenter::where('userid', $userid)->pluck('centerid')->first();

    // Fetch full center data for that ID
    $center = Center::find($centerId);

    // Use this center's ID for further logic
    $centerid = $center?->id;
}


    if ($userType === "Superadmin") {
        $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerId)->get();
    }

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

            $roomid = $roomid ?? $room->id ?? null;
            $roomname = $room->name ?? null;
            $roomcolor = $room->color ?? null;

            $date = !empty($request->date)
                ? date('Y-m-d', strtotime($request->date))
                : date('Y-m-d');

            $role = Auth::user()->userType; // implement method
            if ($role === "Superadmin") {
                $permission = null;
            } elseif ($role === "Staff") {
                 $permission = \App\Models\PermissionsModel::where('userid', $user->userid)
                            ->where('centerid', $centerId)
                            ->first(); // implement method
            } else {
                $permission = null;
            }
  $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

            $children = Child::where('room', $roomid)->get();

            $sleepChecks = DailyDiarySleepCheckList::where(['createdBy'=>$userid, 'roomid'=>$roomid])
             ->whereDate('created_at', $date)
             ->get();

            //  dd($sleepChecks);

           return view('SleepChecks.List', [
    'centerid'     => $centerid,
    'date'         => $date,
    'roomid'       => $roomid,
    'children'     => $children,
    'roomname'     => $roomname,
    'roomcolor'    => $roomcolor,
    'rooms'        => $centerRooms ?? [],
    'sleepChecks'  => $sleepChecks,
    'permissions'  => $permission,
    'centers' => $centers
]);

        }

      

        public function sleepcheckSave(Request $request)
{
    // Validate incoming request
    $validator = $request->validate( [
        'childid'          => 'required|integer|exists:child,id',
        'diarydate'        => 'required|date_format:d-m-Y',
        'roomid'           => 'required|integer|exists:room,id',
        'time'             => 'required|string',
        'breathing'        => 'nullable|string',
        'body_temperature' => 'nullable|string',
        'notes'            => 'nullable|string',
    ]);


    // Convert date to Y-m-d
    $date = \DateTime::createFromFormat('d-m-Y', $request->diarydate);
    $mysqlDate = $date ? $date->format('Y-m-d') : null;

    // Get logged in user ID (you can also use Auth::id() if using Laravel auth)
    $createdBy = Auth::user()->userid;

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
        'created_at'       => now(),
    ]);

    if ($check) {
        return response()->json([
            'success' => true,
            'message' => 'Saved successfully'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Failed to save'
    ]);
}


public function sleepcheckUpdate(Request $request)
{
    // Validate input
    $validator = $request->validate([
        'id'               => 'required|integer|exists:dailydiarysleepchecklist,id',
        'childid'          => 'required|integer|exists:child,id',
        'diarydate'        => 'required|date_format:d-m-Y',
        'roomid'           => 'required|integer|exists:room,id',
        'time'             => 'required|string',
        'breathing'        => 'nullable|string',
        'body_temperature' => 'nullable|string',
        'notes'            => 'nullable|string',
    ]);

    // Convert diarydate to Y-m-d
    $date = \DateTime::createFromFormat('d-m-Y', $request->diarydate);
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
                'id' => 'required|integer|exists:dailydiarysleepchecklist,id',
            ]);

            $deleted = DailyDiarySleepChecklist::where('id', $request->id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete or already removed'
                ]);
            }
        }


    }
    




