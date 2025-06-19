<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Child;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    // public function rooms_list(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     $getrooms = Room::select('room.id as roomid', 'room.*')->where('room.userId', $userId)->join('centers', 'centers.id', '=', 'room.centerid')->get();
    //     foreach ($getrooms as $room) {
    //         $room->children = Child::where('room', $room->roomid)->get();
    //     }

    //     return view('rooms.list', compact('getrooms'));
    // }

    public function rooms_list(Request $request)
    {
        $userId = Auth::id();
        $centerId = $request->centerId ?? session('user_center_id');
        $centers = Center::take(3)->get();
        $getrooms = Room::select('room.id as roomid', 'room.*')
            ->join('centers', 'centers.id', '=', 'room.centerid')
            ->where('room.userId', $userId);
        if ($centerId) {
            $getrooms->where('room.centerid', $centerId);
        }
        $getrooms = $getrooms->get();
        foreach ($getrooms as $room) {
            $room->children = Child::where('room', $room->roomid)->get();
            $room->educators = DB::table('room_staff')
                ->leftJoin('users', 'users.userid', '=', 'room_staff.staffid')
                ->select('users.userid', 'users.name', 'users.gender', 'users.imageUrl')
                ->where('room_staff.roomid', $room->roomid)
                ->get();
        }
        return view('rooms.list', compact('getrooms', 'centers', 'centerId'));
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
        $enrolledchilds = Child::where('room', $roomid)->where('status', 'Enrolled')->count();
        $malechilds = Child::where('room', $roomid)->where('gender', 'Male')->count();
        $femalechilds = Child::where('room', $roomid)->where('gender', 'Female')->count();
        $rooms = Room::where('capacity', '!=', 0)->where('status', 'Active')->get();
        $roomcapacity = Room::where('id', $roomid)->first();

        return view('rooms.children_details', compact('attendance', 'roomcapacity', 'rooms', 'allchilds', 'activechilds', 'enrolledchilds', 'malechilds', 'femalechilds', 'roomid', 'totalAttendance', 'patterns', 'breakdowns'));
    }


    public function add_new_children(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'dob' => 'required|date',
            'startDate' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'status' => 'required|in:Active,Active,Enrolled',
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
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $imagePath = $file->store('children_images', 'public');
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
            'status' => 'required|in:Active,Active,Enrolled',
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
