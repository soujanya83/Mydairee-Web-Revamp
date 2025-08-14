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
use App\Models\Childparent;
use App\Models\DailyDiarySleepCheckList;
use Illuminate\Support\Facades\Validator;

class SleepCheckController extends Controller
{
  
    public function fetchSleepChecks(Request $request)
{
    $user = Auth::user();
    $userid = $user->userid;
    $userType = $user->userType;

    // Get selected center (from request or session)
    $centerid = $request->centerid ?? session('user_center_id');
    if (empty($centerid)) {
        $centerid = Usercenter::where('userid', $userid)->pluck('centerid')->first();
    }

    // Get centers for dropdown
    if ($userType === "Superadmin") {
        $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerid)->get();
    }

    // if($userType === "Parent"){
    //      $centers = Center::where('id', $centerid)->get();

    // }

    // Room filter
    $roomid = $request->roomid ?? Room::where('centerid', $centerid)->value('id');
    $room   = Room::find($roomid);
    $roomname  = $room->name ?? '';
    $roomcolor = $room->color ?? '';
    $centerRooms = Room::where('centerid', $centerid)->get();

    // Date filter
    $date = $request->date 
        ? \Carbon\Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d') 
        : now()->format('Y-m-d');

    // Fetch children in the selected room
 $children = Child::where('room', $roomid)
                 ->where('name', 'like', '%' . $request->child_name . '%')
                 ->get();


    // Fetch sleep checks filtered by room and date
    $sleepChecks = DailyDiarySleepCheckList::where('roomid', $roomid)
        ->whereDate('created_at', $date)
        ->get();

    // Prepare JSON response
    $result = [];

    foreach ($children as $child) {
        $childChecks = $sleepChecks->where('childid', $child->id)->sortBy('time')->values();

        $result[] = [
            'child' => [
                'id'       => $child->id,
                'name'     => $child->name,
                'lastname' => $child->lastname,
                'image'    => asset($child->imageUrl) ? asset('assets/media/'.$child->imageUrl) : null,
            ],
            'sleep_checks' => $childChecks->map(function($check){
                return [
                    'id'               => $check->id,
                    'time'             => $check->time,
                    'breathing'        => $check->breathing,
                    'body_temperature' => $check->body_temperature,
                    'notes'            => $check->notes,
                ];
            })->toArray()
        ];
    }

    return response()->json([
        'centerid' => $centerid,
        'roomid'   => $roomid,
        'date'     => $date,
        'roomname' => $roomname,
        'roomcolor'=> $roomcolor,
        'centers'  => $centers,
        'rooms'    => $centerRooms,
        'data'     => $result
    ]);
}


public function getSleepChecksList(Request $request)
{
        $user = Auth::user(); // implement this logic in your LoginModel
   
        $userid = $user->userid;
        $userType = $user->userType;
      
        $centerid = Session('user_center_id');

    if (empty($centerid)) {
    // Get the first center ID assigned to the user
    $centerId = Usercenter::where('userid', $userid)->pluck('centerid')->first();

    // Fetch full center data for that ID
    $center = Center::find($centerId);

    // Use this center's ID for further logic
    $centerid = $center?->id;
}


    if ($userType === "Superadmin") {
        $centerIds = Usercenter::where('userid', $userid)->pluck('centerid');
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerid)->get();
    }

    if (empty($request->roomid)) {
        
            $centerRoom = Room::where('centerid', $centerid)->first();
            $roomid = $centerRoom->id ?? null;
            $roomname = $centerRoom->name ?? '';
            $roomcolor = $centerRoom->color ?? '';
             $centerRooms = Room::where('centerid', $centerid)->get();
              $selectedRoom = Room::where('id', $roomid)->first();
    } else {
            $roomid = $request->roomid;
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $centerRooms = Room::where('centerid', $centerid)->get();
             $selectedRoom = Room::where('id', $roomid)->first();
    }

    if($userType === "Parent"){
            $roomid = $request->roomid;
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $childids = Childparent::where('parentid',$userid)->pluck('childid');
            $roomIds = Child::whereIn('id',$childids)->pluck('room');
            $centerRooms = Room::whereIn('id', $roomIds)->get();
            $selectedRoom = Room::where('id', $roomid)->first();
    }
 
        $roomid = $roomid ?? $room->id ?? null;
            $roomname = $room->name ?? null;
            $roomcolor = $room->color ?? null;

           

            $date = !empty($request->date)
                ? date('Y-m-d', strtotime($request->date))
                : date('Y-m-d');

            $role = Auth::user()->userType; // implement method
            if ($role === "Superadmin") {
                $permission = \App\Models\PermissionsModel::where('userid', $userid)
                            ->where('centerid', $centerid)
                            ->first();
            } elseif ($role === "Staff") {
                 $permission = \App\Models\PermissionsModel::where('userid', $userid)
                            ->where('centerid', $centerid)
                            ->first(); // implement method
            } else {
                $permission = null;
            }
  $date = !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

  if(Auth::user()->userType == 'Parent'){
    // dd('here');
    $childIDs = Childparent::where('parentid',Auth::user()->userid)->pluck('childid');
  $children = Child::whereIn('id', $childIDs)->get();
//   dd( $childIDs );

            $sleepChecks = DailyDiarySleepCheckList::where(['roomid'=>$roomid])
             ->whereDate('created_at', $date)
             ->whereIn('childid', $childIDs)
             ->get();

            //  dd( $sleepChecks);
  }else{
  $children = Child::where('room', $roomid)->get();

            $sleepChecks = DailyDiarySleepCheckList::where(['createdBy'=>$userid, 'roomid'=>$roomid])
             ->whereDate('created_at', $date)
             ->get();
  }
          

            //  dd($children);

           return view('SleepChecks.List', [
    'centerid'     => $centerid,
    'date'         => $date,
    'roomid'       => $roomid,
    'children'     => $children,
    'roomname'     => $roomname,
    'roomcolor'    => $roomcolor,
    'rooms'        => $centerRooms ?? [],
    'sleepChecks'  => $sleepChecks,
    'permission'  => $permission,
    'centers' => $centers,
    'selectedroom' => $selectedRoom
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
    




