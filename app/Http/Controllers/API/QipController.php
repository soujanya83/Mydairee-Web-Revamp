<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Qip;
use App\Models\QipDescussionBoard;
use App\Models\QipStandard;
use App\Models\Qiparea;
use App\Models\Usercenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QipController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $requestedCenterId = $request->input('centerid') ?? $request->input('center_id');
        $centerIds = Usercenter::where('userid', $authUser->userid)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();

        if (!$requestedCenterId) {
            $requestedCenterId = $centerIds[0] ?? null;
        }

        $qipQuery = Qip::query();

        if ($authUser->userType === 'Superadmin' || $authUser->userType === 'Centeradmin') {
            if ($requestedCenterId) {
                $qipQuery->where('centerId', $requestedCenterId);
            }
        } else {
            $qipQuery->where('created_by', $authUser->id);

            if ($requestedCenterId) {
                $qipQuery->where('centerId', $requestedCenterId);
            }
        }

        $qips = $qipQuery->orderByDesc('id')->get();

        return response()->json([
            'status' => true,
            'message' => 'QIP data fetched successfully',
            'data' => [
                'centers' => $centers,
                'selected_center_id' => $requestedCenterId,
                'qips' => $qips,
            ],
        ]);
    }

    public function addnew(Request $request)
    {
        $authUser = Auth::user();
        $centerId = $request->input('centerid') ?? $request->input('center_id');

        if (!$centerId) {
            $centerId = Usercenter::where('userid', $authUser->userid)->value('centerid');
        }

        if (!$centerId) {
            return response()->json([
                'status' => false,
                'message' => 'Center ID is required to load QIP data.',
            ], 422);
        }

        if ($request->filled('id')) {
            $qip = Qip::findOrFail($request->input('id'));
        } else {
            $qip = new Qip();
            $qip->centerId = $centerId;
            $qip->name = 'Create By ' . Carbon::now()->format('F Y');
            $qip->created_by = $authUser->id;
            $qip->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'QIP form data fetched successfully',
            'data' => [
                'qip' => $qip,
                'qip_area' => Qiparea::all(),
            ],
        ]);
    }

    public function updateName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:qip,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $qip = Qip::findOrFail($request->input('id'));
        $qip->name = $request->input('name');
        $qip->save();

        return response()->json([
            'status' => true,
            'message' => 'QIP name updated successfully',
            'data' => $qip,
        ]);
    }

    public function viewArea($id, $area)
    {
        $qip = Qip::findOrFail($id);
        $qipArea = Qiparea::where('id', $area)->get();
        $allAreas = Qiparea::all();
        $qipStandards = QipStandard::with(['elements'])->where('areaId', $area)->get();
        $discussionBoard = QipDescussionBoard::with(['user'])
            ->where('qipid', $id)
            ->where('areaid', $area)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'QIP area data fetched successfully',
            'data' => [
                'qip' => $qip,
                'qip_area' => $qipArea,
                'all_areas' => $allAreas,
                'qip_standard' => $qipStandards,
                'discussion_board' => $discussionBoard,
            ],
        ]);
    }

    public function sendDiscussion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qipid' => 'required|integer|exists:qip,id',
            'areaid' => 'required|integer|exists:qip_area,id',
            'commentText' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $comment = QipDescussionBoard::create([
            'qipid' => $request->input('qipid'),
            'areaid' => $request->input('areaid'),
            'commentText' => $request->input('commentText'),
            'added_by' => Auth::id(),
        ]);

        $comment->load('user');

        return response()->json([
            'status' => true,
            'message' => 'Discussion comment added successfully',
            'data' => $comment,
        ], 201);
    }

    public function destroy($id)
    {
        $authUser = Auth::user();

        $qip = Qip::find($id);

        if (!$qip) {
            return response()->json([
                'status' => false,
                'message' => 'QIP not found.',
            ], 404);
        }

        // Non-superadmin can only delete their own QIPs
        if ($authUser->userType !== 'Superadmin' && (int) $qip->created_by !== (int) $authUser->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to delete this QIP.',
            ], 403);
        }

        $qip->delete();

        return response()->json([
            'status' => true,
            'message' => 'QIP deleted successfully.',
        ]);
    }
}