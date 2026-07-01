<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Room;
use App\Models\User;
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
        if ($userType === 'Superadmin' || $userType === 'Centeradmin') {
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

        if ($rooms->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have access to any rooms in this center.',
                'centerid' => $centerid,
                'userType' => $userType,
            ], 403);
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
            ->select('id', 'name', 'lastname', 'imageUrl', 'room', 'centerid', 'status')
            ->orderBy('name')
            ->orderBy('lastname')
            ->get()
            ->map(function ($child) {
                return [
                    'id' => (int) $child->id,
                    'name' => trim(($child->name ?? '') . ' ' . ($child->lastname ?? '')),
                    'image' => !empty($child->imageUrl) ? asset($child->imageUrl) : null,
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

    public function getChildParents($childId)
    {
        if (!filter_var($childId, FILTER_VALIDATE_INT)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid child id.',
            ], 400);
        }

        $child = Child::with(['parents' => function ($query) {
            $query->select('users.id', 'userid', 'title', 'name', 'email', 'contactNo', 'imageUrl', 'gender', 'status');
        }])->find((int) $childId);

        if (!$child) {
            return response()->json([
                'status' => false,
                'message' => 'Child not found.',
            ], 404);
        }

        $parents = $child->parents->map(function ($parent) {
            return [
                'id' => (int) $parent->id,
                'userid' => $parent->userid ?? null,
                'title' => $parent->title ?? null,
                'name' => $parent->name ?? null,
                'full_name' => trim(($parent->title ? $parent->title . ' ' : '') . ($parent->name ?? '')),
                'email' => $parent->email ?? null,
                'contactNo' => $parent->contactNo ?? null,
                'imageUrl' => $parent->imageUrl ?? null,
                'gender' => $parent->gender ?? null,
                'status' => $parent->status ?? null,
                'relation' => $parent->pivot->relation ?? null,
            ];
        })->values();

        return response()->json([
            'status' => true,
            'child' => [
                'id' => (int) $child->id,
                'name' => $child->name,
                'lastname' => $child->lastname,
                'full_name' => trim(($child->name ?? '') . ' ' . ($child->lastname ?? '')),
            ],
            'parents' => $parents,
        ]);
    }

    public function getParentChildren($parentId)
    {
        if (!filter_var($parentId, FILTER_VALIDATE_INT)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid parent id.',
            ], 400);
        }

        $parent = User::where('userType', 'Parent')
            ->with(['children' => function ($query) {
                $query->select('child.id', 'name', 'lastname', 'dob', 'room', 'imageUrl', 'gender', 'status', 'centerid');
            }])
            ->find((int) $parentId);

        if (!$parent) {
            return response()->json([
                'status' => false,
                'message' => 'Parent not found.',
            ], 404);
        }

        $children = $parent->children->map(function ($child) {
            return [
                'id' => (int) $child->id,
                'name' => $child->name ?? null,
                'lastname' => $child->lastname ?? null,
                'full_name' => trim(($child->name ?? '') . ' ' . ($child->lastname ?? '')),
                'dob' => $child->dob ?? null,
                'room' => $child->room ?? null,
                'imageUrl' => $child->imageUrl ?? null,
                'gender' => $child->gender ?? null,
                'status' => $child->status ?? null,
                'centerid' => $child->centerid ?? null,
                'relation' => $child->pivot->relation ?? null,
            ];
        })->values();

        return response()->json([
            'status' => true,
            'parent' => [
                'id' => (int) $parent->id,
                'userid' => $parent->userid ?? null,
                'title' => $parent->title ?? null,
                'name' => $parent->name ?? null,
                'full_name' => trim(($parent->title ? $parent->title . ' ' : '') . ($parent->name ?? '')),
            ],
            'children' => $children,
        ]);
    }

    public function getRoomStaff(Request $request, $roomId = null)
    {
        $roomId = $roomId ?? $request->input('room_id', $request->input('roomid'));

        $validator = Validator::make([
            'room_id' => $roomId,
        ], [
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

        $room = Room::with(['staff' => function ($query) {
            $query->select(
                'users.id',
                'users.userid',
                'users.title',
                'users.name',
                'users.email',
                'users.contactNo',
                'users.imageUrl',
                'users.gender',
                'users.status',
                'users.userType'
            )->orderBy('users.name');
        }])->find($roomId);

        if (! $room) {
            return response()->json([
                'status' => false,
                'message' => 'Room not found.',
            ], 404);
        }

        $staffs = $room->staff->map(function ($staff) {
            return [
                'id' => (int) $staff->id,
                'userid' => $staff->userid ?? null,
                'title' => $staff->title ?? null,
                'name' => $staff->name ?? null,
                'full_name' => trim(($staff->title ? $staff->title . ' ' : '') . ($staff->name ?? '')),
                'email' => $staff->email ?? null,
                'contactNo' => $staff->contactNo ?? null,
                'imageUrl' => $staff->imageUrl ?? null,
                'gender' => $staff->gender ?? null,
                'status' => $staff->status ?? null,
                'userType' => $staff->userType ?? null,
            ];
        })->values();

        return response()->json([
            'status' => true,
            'room_id' => $roomId,
            'room' => [
                'id' => (int) $room->id,
                'name' => $room->name,
                'centerid' => $room->centerid,
                'status' => $room->status,
            ],
            'count' => $staffs->count(),
            'staffs' => $staffs,
        ]);
    }

    public function getCenterStaff(Request $request, $centerId = null)
    {
        $centerId = $centerId ?? $request->input('center_id', $request->input('centerid'));

        $validator = Validator::make([
            'center_id' => $centerId,
        ], [
            'center_id' => 'required|integer|exists:centers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $centerId = (int) $validator->validated()['center_id'];

        $staffs = User::join('room_staff', 'users.id', '=', 'room_staff.staffid')
            ->join('room', 'room.id', '=', 'room_staff.roomid')
            ->where('room.centerid', $centerId)
            ->where('users.userType', 'Staff')
            ->select(
                'users.id',
                'users.userid',
                'users.title',
                'users.name',
                'users.email',
                'users.contactNo',
                'users.imageUrl',
                'users.gender',
                'users.status',
                'users.userType'
            )
            ->distinct()
            ->orderBy('users.name')
            ->get()
            ->map(function ($staff) {
                return [
                    'id' => (int) $staff->id,
                    'userid' => $staff->userid ?? null,
                    'title' => $staff->title ?? null,
                    'name' => $staff->name ?? null,
                    'full_name' => trim(($staff->title ? $staff->title . ' ' : '') . ($staff->name ?? '')),
                    'email' => $staff->email ?? null,
                    'contactNo' => $staff->contactNo ?? null,
                    'imageUrl' => $staff->imageUrl ?? null,
                    'gender' => $staff->gender ?? null,
                    'status' => $staff->status ?? null,
                    'userType' => $staff->userType ?? null,
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'center_id' => $centerId,
            'count' => $staffs->count(),
            'staffs' => $staffs,
        ]);
    }
}
