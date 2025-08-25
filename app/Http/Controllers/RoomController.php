<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Child;
use App\Models\ChildStatusHistory;
use App\Models\Room;
use App\Models\RoomStaff;
use App\Models\Usercenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoomController extends Controller
{

    public function toggleStatus($id)
    {
        $child = Child::findOrFail($id);
        $oldStatus = $child->status;
        $child->status = $child->status === 'Active' ? 'In Active' : 'Active';
        $child->save();
        ChildStatusHistory::create([
            'user_id'      => Auth::id(),
            'child_id'     => $child->id,
            'old_status'   => $oldStatus,
            'new_status'   => $child->status,
            'date_time'    => now(),
        ]);

        return redirect()->back()->with('success', 'Status updated successfully!');
    }




    public function assignEducators(Request $request, $roomid)
    {
        DB::table('room_staff')->where('roomid', $roomid)->delete();
        if ($request->has('educators')) {
            foreach ($request->educators as $staffid) {
                DB::table('room_staff')->updateOrInsert(
                    ['roomid' => $roomid, 'staffid' => $staffid], // Unique constraint
                    [] // No fields to update (only insert if not exists)
                );
            }
        }

        return redirect()->back()->with('success', 'Educators updated successfully.');
    }


    public function rooms_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'room_name' => 'required|string|max:255',
            'room_capacity' => 'required|integer|min:1',
            'ageFrom' => 'required|numeric|min:0',
            'ageTo' => 'required|numeric|min:' . $request->input('ageFrom'),
            'room_status' => 'required|in:Active,Inactive',
            'room_color' => 'required|string',
            'educators' => 'array',
            'educators.*' => 'exists:users,userid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $room = Room::findOrFail($id);
            $room->update([
                'name' => $request->input('room_name'),
                'capacity' => $request->input('room_capacity'),
                'ageFrom' => $request->input('ageFrom'),
                'ageTo' => $request->input('ageTo'),
                'status' => $request->input('room_status'),
                'color' => $request->input('room_color'),
            ]);

            // Remove existing educators and add new
            RoomStaff::where('roomid', $id)->delete();
            if ($request->has('educators')) {
                foreach ($request->input('educators') as $educatorId) {
                    RoomStaff::create([
                        'roomid' => $id,
                        'staffid' => $educatorId,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update room: ' . $e->getMessage());
        }
    }


    public function update_child_progress(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'dob' => 'required|date',
            'startDate' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'status' => 'required|in:Active,In Active,Enrolled',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $child = Child::findOrFail($id);
        $child->room = $request->roomid ?? null;
        $child->name = $request->firstname;
        $child->lastname = $request->lastname;
        $child->dob = $request->dob;
        $child->startDate = $request->startDate;
        $child->gender = $request->gender;
        $child->status = $request->status;

        $child->centerid =  $request->centerid ?? null;
        $child->createdBy = Auth::user()->id;

        // if ($request->hasFile('file')) {
        //     $child->imageUrl = $request->file('file')->store('children_images', 'public');
        // }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/child'), $filename);
            $child->imageUrl = 'uploads/child/' . $filename;
        }


        $daysMap = ['mon', 'tue', 'wed', 'thu', 'fri'];
        $daysString = '';
        foreach ($daysMap as $day) {
            $daysString .= in_array($day, $request->input('days', [])) ? '1' : '0';
        }
        $child->daysAttending = $daysString;
        $child->save();

        // return redirect()->route('room.children',['roomid'=>$request->roomid])->with('success', 'Child updated successfully.');
        return redirect()->route('childrens_list')->with('success', 'Child updated successfully.');
    }

    public function childrens_edit($id)
    {

        $data = Child::where('id', $id)->first();

        return view('rooms.edit_child_progress', compact('data'));
    }

    public function children_destroy($id)
    {

        $childIds = Child::find($id);
        if (!$childIds) {
            return redirect()->back()->with('error', 'No children for deletion.');
        }
        $childIds->delete();
        return redirect()->back()->with('success', 'Children deleted successfully.');
    }

    public function childrens_list(Request $request)
    {
        $userId = Auth::user()->id;
        if ($userId == 145) {
            $userId = $userId - 1;
        }

        $rooms = Room::where('name', '!=', null)
            ->where('userId', $userId)
            ->get();

        $chilData = Child::select(
            'child.*',
            'child.status as childstatus',
            'child.id as childId',
            'child.name as childname',
            'room.name as roomname',
            'room.*',
            'child.createdAt as childcreatedate'
        )
            ->where('child.createdBy', $userId)
            ->join('room', 'room.id', '=', 'child.room');

        // Filter by roomId if provided
        if ($request->filled('roomId')) {
            $chilData->where('child.room', $request->roomId);
        }

        // Filter by full name (childname + lastname) if provided
        if ($request->filled('childName')) {
            $chilData->whereRaw(
                "CONCAT(child.name, ' ', COALESCE(child.lastname, '')) LIKE ?",
                ['%' . $request->childName . '%']
            );
        }

        $chilData = $chilData->get();

        return view('rooms.childrens_list', [
            'chilData' => $chilData,
            'rooms' => $rooms,
            'selectedRoom' => $request->roomId
        ]);
    }

    // public function childrens_list(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     if ($userId == 145) {
    //         $userId = $userId - 1;
    //     }

    //     $rooms = Room::where('name', '!=', null)
    //     ->where('userId',$userId)
    //     ->get();

    //     $chilData = Child::select(
    //         'child.*',
    //         'child.id as childId',
    //         'child.name as childname',
    //         'room.name as roomname',
    //         'room.*',
    //         'child.createdAt as childcreatedate'
    //     )
    //         ->where('child.createdBy', $userId)
    //         ->join('room', 'room.id', '=', 'child.room');

    //     if ($request->filled('roomId')) {
    //         $chilData->where('child.room', $request->roomId);
    //     }

    //     $chilData = $chilData->get();

    //     return view('rooms.childrens_list', [
    //         'chilData' => $chilData,
    //         'rooms' => $rooms,
    //         'selectedRoom' => $request->roomId
    //     ]);
    // }

    public function bulkDelete(Request $request)
    {
        $roomIds = $request->input('selected_rooms', []);

        // Validation
        $validator = Validator::make($request->all(), [
            'selected_rooms' => 'required|array|min:1',
            'selected_rooms.*' => 'exists:room,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Start DB transaction for safety (optional)
            DB::beginTransaction();

            Room::whereIn('id', $roomIds)->delete();
            RoomStaff::whereIn('roomid', $roomIds)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Selected rooms deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong while deleting rooms.');
        }
    }



    public function rooms_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_name' => 'required|string|max:255',
            'room_capacity' => 'required|integer|min:1',
            'ageFrom' => 'required|numeric|min:0',
            'ageTo' => 'required|numeric|min:' . $request->input('ageFrom'),
            'room_status' => 'required|in:Active,Inactive',
            'room_color' => 'required|string',
            'educators' => 'array',
            'educators.*' => 'exists:users,userid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Start DB transaction (optional but good for grouped operations)
            DB::beginTransaction();

            // Generate a unique 10-digit ID
            do {
                $roomId = random_int(100000000, 999999999);
            } while (\App\Models\Room::where('id', $roomId)->exists());

            $room = Room::create([
                'id' => $roomId,
                'name' => $request->input('room_name'),
                'capacity' => $request->input('room_capacity'),
                'ageFrom' => $request->input('ageFrom'),
                'ageTo' => $request->input('ageTo'),
                'status' => $request->input('room_status'),
                'color' => $request->input('room_color'),
                'centerid' => $request->input('dcenterid'),
                'created_by' => Auth::user()->id,
                'userId' => Auth::user()->id,
            ]);

            // Assign educators
            if ($request->has('educators')) {
                foreach ($request->input('educators') as $educatorId) {
                    RoomStaff::create([
                        'roomid' => $roomId,
                        'staffid' => $educatorId,
                    ]);
                }
            }

            DB::commit(); // Success, commit transaction
            return redirect()->back()->with('success', 'Room created successfully.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack(); // Rollback on error
            return redirect()->back()->with('error', 'Failed to create room: ' . $e->getMessage());
        }
    }


    public function rooms_list(Request $request)
    {
        $userId = Auth::id();
        // $centerId = $request->centerId ?? session('user_center_id');
        // $centers = Center::take(3)->get();

        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');


        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }
        // dd($centers);

        $getrooms = Room::select('room.id as roomid', 'room.*')
            ->join('centers', 'centers.id', '=', 'room.centerid')
            ->where('room.userId', $userId);
        if ($centerid) {
            $getrooms->where('room.centerid', $centerid);
        }


        if (Auth::user()->userType == "Superadmin") {
            $getrooms = $getrooms->get();
        } else {
            // Get room IDs assigned to staff
            $roomIds = RoomStaff::where('staffid', $authId)->pluck('roomid');

            // Get room data using those room IDs
            $getrooms = Room::whereIn('id', $roomIds)->get();
        }

        // dd($getrooms);

        foreach ($getrooms as $room) {
            $room->children = Child::where('room', $room->roomid)->where('status','Active')->get();
            $room->educators = DB::table('room_staff')
                ->leftJoin('users', 'users.userid', '=', 'room_staff.staffid')
                ->select('users.userid', 'users.name', 'users.gender', 'users.imageUrl')
                ->where('room_staff.roomid', $room->roomid)
                ->get();
        }

        $roomStaffs = RoomStaff::join('users', 'users.id', '=', 'room_staff.staffid')
            ->where('users.userType', 'Staff')
            ->where('users.status', 'Active')
            ->select('room_staff.staffid', 'users.name')
            ->distinct('room_staff.staffid')
            ->get();
        return view('rooms.list', compact('getrooms', 'centers', 'centerid', 'roomStaffs'));
    }



    public function showChildren($roomid)
    {
        $allchilds = Child::where('room', $roomid)->where('status', 'Active')->get();
        $attendance = [
            'Mon' => $allchilds->sum('mon'),
            'Tue' => $allchilds->sum('tue'),
            'Wed' => $allchilds->sum('wed'),
            'Thu' => $allchilds->sum('thu'),
            'Fri' => $allchilds->sum('fri'),
        ];
        $centerid = Session('user_center_id');

        // Calculate the total sum of children attending across all days
        $totalAttendance = array_sum($attendance);

        // Generate attendance patterns and breakdowns
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

        // Generate breakdowns for each day
        $breakdowns = [
            'Mon' => $patterns->pluck('days.Mon')->implode('+'),
            'Tue' => $patterns->pluck('days.Tue')->implode('+'),
            'Wed' => $patterns->pluck('days.Wed')->implode('+'),
            'Thu' => $patterns->pluck('days.Thu')->implode('+'),
            'Fri' => $patterns->pluck('days.Fri')->implode('+'),
        ];
        $activechilds = Child::where('room', $roomid)->where('status', 'Active')->count();
        $enrolledchilds = Child::where('room', $roomid)->where('status', 'Active')->count();
        $malechilds = Child::where('room', $roomid)->where('gender', 'Male')->where('status', 'Active')->count();
        $femalechilds = Child::where('room', $roomid)->where('gender', 'Female')->where('status', 'Active')->count();
        $rooms = Room::where('capacity', '!=', 0)->where(['status' => 'Active', 'centerid' => $centerid])->get();
        $roomcapacity = Room::where('id', $roomid)->first();

        $educatorsQuery = DB::table('room_staff')
            ->leftJoin('users', 'users.userid', '=', 'room_staff.staffid')
            ->select('users.userid', 'users.name', 'users.gender', 'users.imageUrl');

        // Get all unique educators (across all rooms)
        $AllEducators = $educatorsQuery
            ->groupBy('users.userid', 'users.name', 'users.gender', 'users.imageUrl') // ensure uniqueness
            ->get();

        // Get educators for a specific room
        $roomEducators = (clone $educatorsQuery)
            ->where('room_staff.roomid', $roomid)
            ->get();
        $assignedEducatorIds = $roomEducators->pluck('userid')->toArray();

        return view('rooms.children_details', compact('assignedEducatorIds', 'roomEducators', 'AllEducators', 'attendance', 'roomcapacity', 'rooms', 'allchilds', 'activechilds', 'enrolledchilds', 'malechilds', 'femalechilds', 'roomid', 'totalAttendance', 'patterns', 'breakdowns'));
    }


    public function add_new_children(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'dob' => 'required|date',
            'startDate' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'status' => 'required|in:Active,In Active,Enrolled',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // optional image
            'id' => 'required|integer|exists:room,id',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $centerId = Room::where('id', $request->id)->first();
        // Handle image upload
        $imagePath = null;
        // if ($request->hasFile('file')) {
        //     $file = $request->file('file');
        //     $imagePath = $file->store('children_images', 'public');
        // }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/child'), $filename);
            $imagePath = 'uploads/child/' . $filename;
        }

        // Create new child
        $child = new Child();
        $child->room = $request->id;
        $child->name = $request->firstname;
        $child->lastname = $request->lastname;
        $child->dob = $request->dob;
        $child->startDate = $request->startDate;
        $child->gender = $request->gender;
        $child->status = $request->status;
        $child->imageUrl = $imagePath;
        $child->centerid = $centerId->centerid;
        $child->createdBy = Auth::user()->id;

        $days = '';
        $days .= $request->has('mon') ? '1' : '0';
        $days .= $request->has('tue') ? '1' : '0';
        $days .= $request->has('wed') ? '1' : '0';
        $days .= $request->has('thu') ? '1' : '0';
        $days .= $request->has('fri') ? '1' : '0';

        $child->daysAttending = $days;

        $child->save();

        return redirect()->back()->with('success', 'Child added successfully.');
    }


    public function edit_child($id)
    {
        $data = Child::where('id', $id)->first();

        return view('rooms.edit_child', compact('data'));
    }

    public function update_child(Request $request, $id)
    {

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'dob' => 'required|date',
            'startDate' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'status' => 'required|in:Active,In Active,Enrolled',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $child = Child::findOrFail($id);
        $child->room = $request->roomid ?? null;
        $child->name = $request->firstname;
        $child->lastname = $request->lastname;
        $child->dob = $request->dob;
        $child->startDate = $request->startDate;
        $child->gender = $request->gender;
        $child->status = $request->status;

        $child->centerid =  $request->centerid ?? null;
        $child->createdBy = Auth::user()->id;

        // if ($request->hasFile('file')) {
        //     $child->imageUrl = $request->file('file')->store('children_images', 'public');
        // }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/child'), $filename);
            $child->imageUrl = 'uploads/child/' . $filename;
        }

        $daysMap = ['mon', 'tue', 'wed', 'thu', 'fri'];
        $daysString = '';
        foreach ($daysMap as $day) {
            $daysString .= in_array($day, $request->input('days', [])) ? '1' : '0';
        }
        $child->daysAttending = $daysString;
        $child->save();

        // return redirect()->route('room.children',['roomid'=>$request->roomid])->with('success', 'Child updated successfully.');
        return redirect()->route('room.children', ['roomid' => $request->roomid])->with('success', 'Child updated successfully.');
    }

    public function moveChildren(Request $request)
    {
        $childIds = $request->input('child_ids', []);
        $roomId = $request->input('room_id');

        if (empty($childIds) || !$roomId) {
            return redirect()->back()->with('error', 'Please select at least one child and a room.');
        }
        Child::whereIn('id', $childIds)->update(['room' => $roomId]);
        return redirect()->back()->with('success', 'Children moved successfully.');
    }


    public function delete_selected_children(Request $request)
    {
        $childIds = $request->input('child_ids');

        if (!$childIds) {
            return redirect()->back()->with('error', 'No children selected for deletion.');
        }

        Child::whereIn('id', $childIds)->delete();

        return redirect()->back()->with('success', 'Selected children deleted successfully.');
    }
}
