<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GlobalRoomsChildrenController extends Controller
{
    public function getCenterRooms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'centerid' => 'required|integer|exists:centers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $centerId = (int) $validator->validated()['centerid'];

        $rooms = Room::where('centerid', $centerId)
            ->select('id', 'name', 'centerid', 'status')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'centerid' => $centerId,
            'rooms' => $rooms,
        ]);
    }


    public function getUserCenterRooms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'centerid' => 'required|integer|exists:centers,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $centerid = (int) $request->input('centerid');
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        $userType = $user->userType;
        $authId = $user->id;
        if ($userType === 'Superadmin') {
            // All rooms in the center
            $rooms = Room::where('centerid', $centerid)->get();
        } elseif ($userType === 'Staff') {
            // Rooms in center where staff is attached
            $roomIds = \App\Models\RoomStaff::where('staffid', $authId)->pluck('roomid');
            $rooms = Room::where('centerid', $centerid)
                ->whereIn('id', $roomIds)
                ->get();
        } elseif ($userType === 'Parent') {
            // Rooms in center where any of parent's children are attached
            $childIds = \App\Models\Childparent::where('parentid', $authId)->pluck('childid');
            $roomIds = \App\Models\Child::whereIn('id', $childIds)
                ->where('centerid', $centerid)
                ->pluck('room')
                ->filter()
                ->unique();
            $rooms = Room::where('centerid', $centerid)
                ->whereIn('id', $roomIds)
                ->get();
        } else {
            $rooms = collect();
        }

        return response()->json([
            'status' => true,
            'centerid' => $centerid,
            'userType' => $userType,
            'rooms' => $rooms,
        ]);
    }

    
    public function getRoomChildren(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|integer|exists:room,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $roomId = (int) $validator->validated()['room_id'];

        $children = Child::where('room', $roomId)
            ->select('id', 'name', 'lastname', 'room', 'centerid', 'status')
            ->orderBy('name')
            ->orderBy('lastname')
            ->get()
            ->map(function ($child) {
                return [
                    'id' => (int) $child->id,
                    'name' => trim(($child->name ?? '') . ' ' . ($child->lastname ?? '')),
                    'room' => (int) $child->room,
                    'centerid' => (int) $child->centerid,
                    'status' => $child->status,
                ];
            });

        return response()->json([
            'status' => true,
            'room_id' => $roomId,
            'children' => $children,
        ]);
    }
}
