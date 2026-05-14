<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Childparent;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChildDetailsController extends Controller
{
    public function show($id)
    {
        $child = Child::with(['parents', 'reflections'])
            ->findOrFail($id);

        $room = $child->room ? Room::find($child->room) : null;

        // Get siblings (children with same parents, excluding self)
        $parentIds = $child->parents->pluck('id');
        $siblingIds = $parentIds->isEmpty()
            ? collect()
            : Childparent::whereIn('parentid', $parentIds)
                ->where('childid', '!=', $child->id)
                ->pluck('childid')
                ->unique();
        $siblings = $siblingIds->isEmpty()
            ? collect()
            : Child::whereIn('id', $siblingIds)->get();

        // Format parents
        $parents = $child->parents->map(function($parent) {
            return [
                'id' => $parent->id,
                'name' => $parent->name,
                'relation' => $parent->pivot->relation ?? null,
                'phone' => $parent->contactNo ?? null,
            ];
        });
        // Format siblings
        $siblingsArr = $siblings->map(function($sibling) {
            return [
                'id' => $sibling->id,
                'childname' => $sibling->name,
                'lastname' => $sibling->lastname,
            ];
        });

        return response()->json([
            'id' => $child->id,
            'name' => $child->name,
            'lastname' => $child->lastname,
            'dob' => $child->dob,
            'startDate' => $child->startDate,
            'room' => $room ? $room->name : null,
            // 'room_id' => $room ? $room->id : null,
            'imageUrl' => $child->imageUrl,
            'gender' => $child->gender,
            'status' => $child->status,
            'address' => $child->address ?? null,
            'parents' => $parents,
            'siblings' => $siblingsArr,
            'other_details' => $child->other_details ?? null,
        ]);
    }

    
    public function toggleStatus(Request $request, $id)
    {
        $child = Child::findOrFail($id);
        $oldStatus = $child->status;
        $child->status = $oldStatus === 'Active' ? 'In Active' : 'Active';
        $child->save();
        // Optionally log status history here
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $child->status,
        ]);
    }
}
