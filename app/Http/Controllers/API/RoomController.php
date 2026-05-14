<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Child;
use App\Models\Childparent;
use App\Models\Room;
use App\Models\User;
use App\Models\RoomStaff;
use App\Models\Usercenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoomController extends Controller
{

      public function staffs()
{
    try {
        $staff = User::where('userType', 'Staff')
                     ->orderBy('name', 'asc') // Optional: sort by name
                     ->get();

        if ($staff->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No staff found.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Staff fetched successfully.',
            'count' => $staff->count(),
            'data' => $staff
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong while fetching staff.',
            'error' => $e->getMessage()
        ], 500);
    }
} 
public function assignStaffToRoom(Request $request)
{
    $staffIdsRaw = $request->input('staff_ids', []);
    $removeStaffIdsRaw = $request->input('remove_staff_ids', $request->input('removed_staff_id', []));

    if (is_string($staffIdsRaw)) {
        $staffIdsRaw = str_contains($staffIdsRaw, ',') ? explode(',', $staffIdsRaw) : [$staffIdsRaw];
    }
    if (is_string($removeStaffIdsRaw)) {
        $removeStaffIdsRaw = str_contains($removeStaffIdsRaw, ',') ? explode(',', $removeStaffIdsRaw) : [$removeStaffIdsRaw];
    }

    $staffIdsRaw = is_array($staffIdsRaw) ? $staffIdsRaw : [];
    $removeStaffIdsRaw = is_array($removeStaffIdsRaw) ? $removeStaffIdsRaw : [];

    $staffIds = array_values(array_filter(array_unique(array_map(function($id) {
        $trimmed = trim((string)$id);
        return ($trimmed !== '' && is_numeric($trimmed)) ? (int)$trimmed : null;
    }, $staffIdsRaw)), fn($id) => $id !== null));

    $removeStaffIds = array_values(array_filter(array_unique(array_map(function($id) {
        $trimmed = trim((string)$id);
        return ($trimmed !== '' && is_numeric($trimmed)) ? (int)$trimmed : null;
    }, $removeStaffIdsRaw)), fn($id) => $id !== null));

    $validator = Validator::make([
        'room_id' => $request->input('room_id'),
        'staff_ids' => $staffIds,
        'remove_staff_ids' => $removeStaffIds,
    ], [
        'room_id'           => 'required|integer|exists:room,id',
        'staff_ids'         => 'nullable|array',
        'remove_staff_ids'  => 'nullable|array',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $roomId = (int) $request->input('room_id');

    if (!empty($staffIds)) {
        $validStaffIdsToAdd = User::whereIn('id', $staffIds)
            ->where('userType', 'Staff')
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        if (count($validStaffIdsToAdd) !== count($staffIds)) {
            return response()->json([
                'status' => false,
                'message' => 'Some staff IDs to add are invalid or not Staff role.',
            ], 422);
        }
    } else {
        $validStaffIdsToAdd = [];
    }

    if (!empty($removeStaffIds)) {
        $validStaffIdsToRemove = User::whereIn('id', $removeStaffIds)
            ->where('userType', 'Staff')
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        if (count($validStaffIdsToRemove) !== count($removeStaffIds)) {
            return response()->json([
                'status' => false,
                'message' => 'Some staff IDs to remove are invalid or not Staff role.',
            ], 422);
        }
    } else {
        $validStaffIdsToRemove = [];
    }

    try {
        DB::beginTransaction();

        if (!empty($validStaffIdsToRemove)) {
            RoomStaff::where('roomid', $roomId)
                ->whereIn('staffid', $validStaffIdsToRemove)
                ->delete();
        }

        $existingStaffIds = RoomStaff::where('roomid', $roomId)
            ->pluck('staffid')
            ->map(fn($id) => (int) $id)
            ->toArray();

        $actuallyAdded = [];
        foreach ($validStaffIdsToAdd as $staffId) {
            if (!in_array($staffId, $existingStaffIds)) {
                RoomStaff::create([
                    'roomid' => $roomId,
                    'staffid' => $staffId,
                ]);
                $actuallyAdded[] = $staffId;
            }
        }

        $finalStaffIds = RoomStaff::where('roomid', $roomId)
            ->pluck('staffid')
            ->map(fn($id) => (int) $id)
            ->toArray();

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Room staff updated successfully.',
            'room_id' => $roomId,
            'current_staff_ids' => $finalStaffIds,
            'added_staff_ids' => $actuallyAdded,
            'removed_staff_ids' => $validStaffIdsToRemove,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Failed to update room staff.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

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
    $userId   = Auth::id();
    $authId   = Auth::user()->id;
    $userType = Auth::user()->userType;
    $centerid = $request->user_center_id;

    /*
    |--------------------------------------------------------------------------
    | Get Centers
    |--------------------------------------------------------------------------
    */
    if ($userType == "Superadmin") {

        $centerIds = Usercenter::where('userid', $authId)
            ->pluck('centerid')
            ->toArray();

        $centers = Center::whereIn('id', $centerIds)->get();

    } else {

        $centers = Center::where('id', $centerid)->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Base Room Query
    |--------------------------------------------------------------------------
    */
    // We'll build the room query per user type below

    /*
    |--------------------------------------------------------------------------
    | Fetch Rooms Based On User Type
    |--------------------------------------------------------------------------
    */
    if ($userType == "Superadmin") {
        // Superadmin: all rooms of the current center
        if (!empty($centerid)) {
            $getrooms = Room::where('centerid', $centerid)->get();
        } else {
            $getrooms = collect(); // No center selected, return empty
        }
    } elseif ($userType == "Staff") {
        // Staff: all rooms they are attached to
        $roomIds = RoomStaff::where('staffid', $authId)->pluck('roomid');
        $getrooms = Room::whereIn('id', $roomIds)->get();
    } else {
        // Parent: all rooms their children are in
        $childids = Childparent::where('parentid', $authId)->pluck('childid');
        $roomIds = Child::whereIn('id', $childids)->pluck('room')->filter()->unique();
        $getrooms = Room::whereIn('id', $roomIds)->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Attach Children & Educators
    |--------------------------------------------------------------------------
    */
    foreach ($getrooms as $room) {

        // Room children
        $room->children = Child::where('room', $room->id)->get();

        // Room educators
        $room->educators = DB::table('room_staff')
            ->leftJoin('users', 'users.id', '=', 'room_staff.staffid')
            ->select(
                'users.id as userid',
                'users.name',
                'users.gender',
                'users.imageUrl'
            )
            ->where('room_staff.roomid', $room->id)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Staff List Center Wise
    |--------------------------------------------------------------------------
    */
    $roomStaffs = [];

    if ($userType != "Parent") {

        $roomStaffs = RoomStaff::join(
                'users',
                'users.id',
                '=',
                'room_staff.staffid'
            )
            ->join(
                'room',
                'room.id',
                '=',
                'room_staff.roomid'
            )
            ->where('users.userType', 'Staff')
            ->where('users.status', 'Active')
            ->where('room.userId', $userId)
            ->when($centerid, function ($q) use ($centerid) {
                $q->where('room.centerid', $centerid);
            })
            ->select(
                'room_staff.staffid',
                'users.name'
            )
            ->distinct()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */
    return response()->json([
        'status'     => true,
        'message'    => 'Room list fetched successfully.',
        'rooms'      => $getrooms,
        // 'centers'    => $centers,
        'centerid'   => $centerid,
        'roomStaffs' => $roomStaffs,
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
        'firstname'   => ['required','string','max:255','not_regex:/\\d/'],
        'lastname'    => ['required','string','max:255','not_regex:/\\d/'],
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
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/child'), $filename);
            $imagePath = 'uploads/child/' . $filename;
        }

        // dd( $imagePath);

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
        'firstname' => ['required','string','max:255','not_regex:/\\d/'],
        'lastname' => ['required','string','max:255','not_regex:/\\d/'],
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
    // 🔹 Delete old file if it exists
    if (!empty($child->imageUrl) && file_exists(public_path($child->imageUrl))) {
        unlink(public_path($child->imageUrl));
    }

    // 🔹 Upload new file
    $file = $request->file('file');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('uploads/child'), $filename);

    // 🔹 Save new path
    $imagePath = 'uploads/child/' . $filename;
    $child->imageUrl = $imagePath;
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

public function filterChildren(Request $request)
{
    // Get current user's center
    $authId = Auth::user()->id;
    $userType = Auth::user()->userType;
    $userCenterId = $request->user_center_id;

    // Check if center_id is provided in request, otherwise use user's center
    $centerid = $request->input('center_id', $userCenterId);

    // For Superadmin, get first assigned center if not provided
    if ($userType == "Superadmin" && empty($centerid)) {
        $centerid = Usercenter::where('userid', $authId)->first()->centerid ?? null;
    }

    if (empty($centerid)) {
        return response()->json([
            'status' => false,
            'message' => 'No center assigned to user or center_id not provided.',
            'data' => []
        ], 403);
    }

    // Normalize form-data inputs with defaults (case-insensitive)
    $roomId = $request->input('room_id');
    $genderRaw = strtolower($request->input('gender', 'all'));
    $statusRaw = strtolower($request->input('status', 'all'));
    $sortInput = strtolower($request->input('sort', 'asc')); // 'asc' for A-Z, 'desc' for Z-A
    $searchInput = $request->input('search', '');

    // Normalize gender to proper case
    $genderMap = [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        'all' => 'All',
    ];
    $genderInput = $genderMap[$genderRaw] ?? 'All';

    // Normalize status to proper case
    $statusMap = [
        'active' => 'Active',
        'enrolled' => 'Enrolled',
        'inactive' => 'Inactive',
        'all' => 'All',
    ];
    $statusInput = $statusMap[$statusRaw] ?? 'All';

    // Validation - all optional with defaults
    $validator = Validator::make([
        'center_id' => $centerid,
        'room_id' => $roomId,
        'gender' => $genderInput,
        'status' => $statusInput,
        'sort' => $sortInput,
    ], [
        'center_id' => 'required|integer|exists:centers,id',
        'room_id' => 'nullable|integer|exists:room,id',
        'gender' => 'nullable|in:Male,Female,Other,All',
        'status' => 'nullable|in:Active,Enrolled,Inactive,All',
        'sort' => 'nullable|in:asc,desc',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    try {
        // Build query - filter by center
        $query = Child::where('centerid', (int)$centerid);

        // Filter by room_id if provided, otherwise get all children from center
        if (!empty($roomId)) {
            $query->where('room', (int)$roomId);
        }

        // Apply gender filter
        if ($genderInput !== 'All') {
            $query->where('gender', $genderInput);
        }

        // Apply status filter
        if ($statusInput !== 'All') {
            $query->where('status', $statusInput);
        }

        // Apply search filter
        if (!empty($searchInput)) {
            $query->where(function($q) use ($searchInput) {
                $q->where('name', 'like', '%' . $searchInput . '%')
                  ->orWhere('lastname', 'like', '%' . $searchInput . '%');
            });
        }

        // Apply sorting (A-Z or Z-A)
        if ($sortInput === 'desc') {
            $query->orderBy('name', 'desc')->orderBy('lastname', 'desc');
        } else {
            $query->orderBy('name', 'asc')->orderBy('lastname', 'asc');
        }

        // Get filtered children
        $children = $query->get();

        // Calculate summary based on scope (all or specific room) but only for current center
        if (!empty($roomId)) {
            $allChildrenInScope = Child::where('centerid', (int)$centerid)->where('room', (int)$roomId)->get();
        } else {
            $allChildrenInScope = Child::where('centerid', (int)$centerid)->get();
        }

        $summary = [
            'total' => $allChildrenInScope->count(),
            'male' => $allChildrenInScope->where('gender', 'Male')->count(),
            'female' => $allChildrenInScope->where('gender', 'Female')->count(),
            'other' => $allChildrenInScope->where('gender', 'Other')->count(),
            'active' => $allChildrenInScope->where('status', 'Active')->count(),
            'enrolled' => $allChildrenInScope->where('status', 'Enrolled')->count(),
            'inactive' => $allChildrenInScope->where('status', 'Inactive')->count(),
            'filtered_count' => $children->count(),
        ];

        return response()->json([
            'status' => true,
            'message' => $children->count() > 0 ? 'Children filtered successfully.' : 'No children found matching the filters.',
            'room_id' => !empty($roomId) ? (int)$roomId : null,
            'scope' => !empty($roomId) ? 'specific_room' : 'all_rooms',
            'center_id' => (int)$centerid,
            'filters' => [
                'gender' => $genderInput,
                'status' => $statusInput,
                'sort' => $sortInput,
                'search' => $searchInput,
            ],
            'summary' => $summary,
            'data' => $children,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to filter children.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
 

}
