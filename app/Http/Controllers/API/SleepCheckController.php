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

class SleepCheckController extends Controller
{

     
public function getSleepChecksList(Request $request)
{
    try {
        // $user = User::where('userid', $request->userid)->first();

      
        $user = Auth::user();
        // dd($user);
  if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid user.'
            ], 404);
        }
        $userid = $user->userid;
        $userType = $user->userType;

        $centerid = $request->centerid;
        if (empty($centerid)) {
            $centerId = Usercenter::where('userid', $userid)->pluck('centerid')->first();
            $center = Center::find($centerId);
            $centerid = $center?->id;
        } else {
            $centerId = $centerid;
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
        $roomname = $roomname ?? $room->name ?? null;
        $roomcolor = $roomcolor ?? $room->color ?? null;

        $date = !empty($request->date)
            ? date('Y-m-d', strtotime($request->date))
            : date('Y-m-d');

        // Permissions
        $permission = null;
        if ($userType === "Staff") {
            $permission = \App\Models\PermissionsModel::where('userid', $userid)
                ->where('centerid', $centerId)
                ->first();
        }

        $children = Child::where('room', $roomid)->get();
        $sleepChecks = DailyDiarySleepCheckList::where(['createdBy' => $userid, 'roomid' => $roomid])
            ->whereDate('created_at', $date)
            ->get();

        return response()->json([
            'status' => 'true',
            'message' => 'Sleep checks fetched successfully.',
            'data' => [
                'centerid'    => $centerid,
                'date'        => $date,
                'roomid'      => $roomid,
                'roomname'    => $roomname,
                'roomcolor'   => $roomcolor,
                'rooms'       => $centerRooms,
                'children'    => $children,
                'sleepChecks' => $sleepChecks,
                'permissions' => $permission,
                'centers'     => $centers,
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


        public function sleepcheckSave(Request $request)
{
       $validator = Validator::make($request->all(), [
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
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }


    // Convert date to Y-m-d
    $date = \DateTime::createFromFormat('d-m-Y', $request->diarydate);
    $mysqlDate = $date ? $date->format('Y-m-d') : null;

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
        'created_at'       => now(),
    ]);

    if ($check) {
        return response()->json([
            'status' => true,
            'message' => 'Saved successfully'
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'Failed to save'
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
