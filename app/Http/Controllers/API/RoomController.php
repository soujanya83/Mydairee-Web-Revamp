<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Child;
use App\Models\Room;
use App\Models\RoomStaff;
use App\Models\Usercenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoomController extends Controller
{
public function bulkDelete(Request $request)
{
   

    // Validation
    $validator = Validator::make($request->all(), [
        'selected_rooms'   => 'required|array|min:1',
        'selected_rooms.*' => 'exists:room,id',
    ]);

     $roomIds = $request->selected_rooms;

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        Room::whereIn('id', $roomIds)->delete();
        RoomStaff::whereIn('roomid', $roomIds)->delete();

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Selected rooms deleted successfully.',
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong while deleting rooms.',
            'error'   => $e->getMessage() // optional, remove in production
        ], 500);
    }
}



public function rooms_create(Request $request)
{
    $validator = Validator::make($request->all(), [
        'room_name'     => 'required|string|max:255',
        'room_capacity' => 'required|integer|min:1',
        'ageFrom'       => 'required|numeric|min:0',
        'ageTo'         => 'required|numeric|min:' . $request->input('ageFrom'),
        'room_status'   => 'required|in:Active,Inactive',
        'room_color'    => 'required|string',
        'educators'     => 'array',
        'educators.*'   => 'exists:users,userid',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Generate a unique 9-digit room ID
        do {
            $roomId = random_int(100000000, 999999999);
        } while (Room::where('id', $roomId)->exists());

        $room = Room::create([
            'id'        => $roomId,
            'name'      => $request->input('room_name'),
            'capacity'  => $request->input('room_capacity'),
            'ageFrom'   => $request->input('ageFrom'),
            'ageTo'     => $request->input('ageTo'),
            'status'    => $request->input('room_status'),
            'color'     => $request->input('room_color'),
            'centerid'  => $request->input('dcenterid'),
            'created_by'=> Auth::user()->id,
            'userId'    => Auth::user()->id,
        ]);

        // Assign educators if provided
        if ($request->has('educators')) {
            foreach ($request->input('educators') as $educatorId) {
                RoomStaff::create([
                    'roomid'  => $roomId,
                    'staffid' => $educatorId,
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => 'Room created successfully.',
            'room_id' => $roomId,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status'  => false,
            'message' => 'Failed to create room.',
            'error'   => $e->getMessage(), // optional, remove in production
        ], 500);
    }
}



   public function rooms_list(Request $request)
{
    $userId = Auth::id();
    $authId = Auth::user()->id;
    $centerid = $request->user_center_id;

    // Get centers based on user type
    if (Auth::user()->userType === "Superadmin") {
        $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerid)->get();
    }

    // Get rooms
    $roomQuery = Room::select('room.id as roomid', 'room.*')
        ->join('centers', 'centers.id', '=', 'room.centerid')
        ->where('room.userId', $userId);

    if ($centerid) {
        $roomQuery->where('room.centerid', $centerid);
    }

    $getrooms = $roomQuery->get();

    // Attach children and educators to each room
    foreach ($getrooms as $room) {
        $room->children = Child::where('room', $room->roomid)->get();

        $room->educators = DB::table('room_staff')
            ->leftJoin('users', 'users.userid', '=', 'room_staff.staffid')
            ->select('users.userid', 'users.name', 'users.gender', 'users.imageUrl')
            ->where('room_staff.roomid', $room->roomid)
            ->get();
    }

    // Get all active staff for dropdown or selection purposes
    $roomStaffs = RoomStaff::join('users', 'users.id', '=', 'room_staff.staffid')
        ->where('users.userType', 'Staff')
        ->where('users.status', 'Active')
        ->select('room_staff.staffid', 'users.name')
        ->distinct('room_staff.staffid')
        ->get();

    return response()->json([
        'status'      => true,
        'message'     => 'Room list fetched successfully.',
        'rooms'       => $getrooms,
        'centers'     => $centers,
        'centerid'    => $centerid,
        'roomStaffs'  => $roomStaffs,
    ]);
}



  public function showChildren($roomid)
{
    $allchilds = Child::where('room', $roomid)->get();

    $attendance = [
        'Mon' => $allchilds->sum('mon'),
        'Tue' => $allchilds->sum('tue'),
        'Wed' => $allchilds->sum('wed'),
        'Thu' => $allchilds->sum('thu'),
        'Fri' => $allchilds->sum('fri'),
    ];

    $totalAttendance = array_sum($attendance);

    $patterns = $allchilds->map(function ($child) {
        return [
            'pattern' => $child->mon . $child->tue . $child->wed . $child->thu . $child->fri,
            'days' => [
                'Mon' => $child->mon,
                'Tue' => $child->tue,
                'Wed' => $child->wed,
                'Thu' => $child->thu,
                'Fri' => $child->fri,
            ],
        ];
    });

    $breakdowns = [
        'Mon' => $patterns->pluck('days.Mon')->implode('+'),
        'Tue' => $patterns->pluck('days.Tue')->implode('+'),
        'Wed' => $patterns->pluck('days.Wed')->implode('+'),
        'Thu' => $patterns->pluck('days.Thu')->implode('+'),
        'Fri' => $patterns->pluck('days.Fri')->implode('+'),
    ];

    $activechilds = Child::where('room', $roomid)->where('status', 'Active')->count();
    $enrolledchilds = Child::where('room', $roomid)->where('status', 'Enrolled')->count();
    $malechilds = Child::where('room', $roomid)->where('gender', 'Male')->count();
    $femalechilds = Child::where('room', $roomid)->where('gender', 'Female')->count();

    $rooms = Room::where('capacity', '!=', 0)->where('status', 'Active')->get();
    $roomcapacity = Room::where('id', $roomid)->first();

    return response()->json([
        'status'           => true,
        'message'          => 'Room children details fetched successfully.',
        'room_id'          => $roomid,
        'room_capacity'    => $roomcapacity,
        'attendance'       => $attendance,
        'total_attendance' => $totalAttendance,
        'patterns'         => $patterns,
        'breakdowns'       => $breakdowns,
        'active_children'  => $activechilds,
        'enrolled_children'=> $enrolledchilds,
        'male_children'    => $malechilds,
        'female_children'  => $femalechilds,
        'all_children'     => $allchilds,
        'other_rooms'      => $rooms
    ]);
}


   public function add_new_children(Request $request)
{
    $validator = Validator::make($request->all(), [
        'firstname'   => 'required|string|max:255',
        'lastname'    => 'required|string|max:255',
        'dob'         => 'required|date',
        'startDate'   => 'required|date',
        'gender'      => 'required|in:Male,Female,Other',
        'status'      => 'required|in:Active,Enrolled',
        'file'        => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'id'          => 'required|integer|exists:room,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    try {
        $center = Room::findOrFail($request->id);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $imagePath = $file->store('children_images', 'public');
        }

        // Create new child
        $child = new Child();
        $child->room       = $request->id;
        $child->name       = $request->firstname;
        $child->lastname   = $request->lastname;
        $child->dob        = $request->dob;
        $child->startDate  = $request->startDate;
        $child->gender     = $request->gender;
        $child->status     = $request->status;
        $child->imageUrl   = $imagePath;
        $child->centerid   = $center->centerid;
        $child->createdBy  = Auth::id();

        // Attendance Days: Mon–Fri
        $child->daysAttending =
            ($request->has('mon') ? '1' : '0') .
            ($request->has('tue') ? '1' : '0') .
            ($request->has('wed') ? '1' : '0') .
            ($request->has('thu') ? '1' : '0') .
            ($request->has('fri') ? '1' : '0');

        $child->save();

        return response()->json([
            'status'  => true,
            'message' => 'Child added successfully.',
            'data'    => $child,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong while adding the child.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}


   public function edit_child($id)
{
    $child = Child::find($id);

    if (!$child) {
        return response()->json([
            'status' => false,
            'message' => 'Child not found.'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Child data retrieved successfully.',
        'data' => $child
    ]);
}


    public function update_child(Request $request)
{
    //   dd('here'); 
    $validator = Validator::make($request->all(), [
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'dob' => 'required|date',
        'startDate' => 'required|date',
        'gender' => 'required|in:Male,Female,Other',
        'status' => 'required|in:Active,Active,Enrolled',
        'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'id' => 'required'
    ]);
  
    $id = $request->id;

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $child = Child::findOrFail($id);

        $child->room = $request->roomid ?? null;
        $child->name = $request->firstname;
        $child->lastname = $request->lastname;
        $child->dob = $request->dob;
        $child->startDate = $request->startDate;
        $child->gender = $request->gender;
        $child->status = $request->status;
        $child->centerid = $request->centerid ?? null;
        $child->createdBy = Auth::user()->id;

        if ($request->hasFile('file')) {
            $child->imageUrl = $request->file('file')->store('children_images', 'public');
        }

        $daysMap = ['mon', 'tue', 'wed', 'thu', 'fri'];
        $daysString = '';
        foreach ($daysMap as $day) {
            $daysString .= in_array($day, $request->input('days', [])) ? '1' : '0';
        }
        $child->daysAttending = $daysString;

        $child->save();

        return response()->json([
            'status' => true,
            'message' => 'Child updated successfully.',
            'data' => $child
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to update child.',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function moveChildren(Request $request)
{
    $childIds = $request->input('child_ids');
    $roomId = $request->room_id;

    if (empty($childIds) || !$roomId) {
        return response()->json([
            'status' => false,
            'message' => 'Please select at least one child and a room.'
        ], 422);
    }

    Child::whereIn('id', $childIds)->update(['room' => $roomId]);

    return response()->json([
        'status' => true,
        'message' => 'Children moved successfully.'
    ]);
}

public function delete_selected_children(Request $request)
{
    $childIds = $request->input('child_ids');

    if (!$childIds || !is_array($childIds)) {
        return response()->json([
            'status' => false,
            'message' => 'No children selected for deletion.'
        ], 422);
    }

    Child::whereIn('id', $childIds)->delete();

    return response()->json([
        'status' => true,
        'message' => 'Selected children deleted successfully.'
    ]);
}
 

}
