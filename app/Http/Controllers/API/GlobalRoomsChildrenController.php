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
