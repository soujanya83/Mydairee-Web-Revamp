<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\support\Facades\Auth;
use App\Models\Usercenter; 
use App\Models\Center; 
use App\Models\Room;
use App\Models\Childparent;
use App\Models\Userprogressplan;
use App\Models\MontessoriSubject;
use App\Models\Child;
use App\Models\RoomStaff;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Services\AuthTokenService; // Custom service to verify token
use App\Models\DailyDiaryModel;
use Illuminate\Support\Carbon;   
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LnPcontroller extends Controller
{
   public function index(Request $request)
{
    $authId = Auth::user()->id; 
    $centerid = $request->centerid;
    $roomId = $request->room_id;

    if (!$centerid) {
        return response()->json([
            'status' => false,
            'message' => 'Center id required'
        ], 400);
    }

    // Fetch centers based on user type
    if (Auth::user()->userType == "Superadmin") {
        $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerid)->get();
    }

    // Get rooms
    $room = $this->getrooms5($centerid);

    // Determine selected room
    $selectedroom = $room->where('id', $roomId)->first() ?? $room->first();

    // Fetch children
    $children = collect();

    if (auth()->user()->userType == 'Parent') {
        $parentId = auth()->user()->id;
        $childIds = Childparent::where('parentid', $parentId)->pluck('childid');
        $children = Child::whereIn('id', $childIds)->get();
    } else {
        if ($selectedroom) {
            $children = Child::where('room', $selectedroom->id)->get();
        }
    }

    return response()->json([
        'status' => true,
        'message' => 'Data fetched successfully',
        'data' => [
            'centers' => $centers,
            'rooms' => $room,
            'selected_room' => $selectedroom,
            'children' => $children
        ]
    ]);
}



    public function getrooms5($centerid){
        try {
            $user = Auth::user();
            $rooms = collect();
            
        if($user->userType === 'Superadmin') {
            $rooms = $this->getroomsforSuperadmin($centerid);
            }elseif($user->userType === 'Staff'){
            $rooms = $this->getroomsforStaff($centerid);
            }else{
            $rooms = $this->getroomsforParent($centerid);
            }
    
            return $rooms;
        
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    



    private function getroomsforSuperadmin($centerid){
        $authId = Auth::user()->id; 
    
        $rooms = Room::where('centerid', $centerid)->get();
        return $rooms;
    }
    
    private function getroomsforStaff(  $centerid ){
        $authId = Auth::user()->id;
    
        $roomIdsFromStaff = RoomStaff::where('staffid', $authId)->pluck('roomid');
        
        // Get room IDs where user is the owner (userId matches)
        $roomIdsFromOwner = Room::where('userId', $authId)->pluck('id');

        
        // Merge both collections and remove duplicates
        $allRoomIds = $roomIdsFromStaff->merge($roomIdsFromOwner)->unique();
       
        // dd($allRoomIds);
    
        $rooms = Room::whereIn('id', $allRoomIds->toArray())->get();
        // dd($rooms);
        return $rooms;
    }


    private function getroomsforParent()
    {
        $authId = Auth::user()->id;
    
        // Step 1: Get child IDs linked to the parent
        $childIds = Childparent::where('parentid', $authId)->pluck('childid');
    
        // Step 2: Get room IDs from Child records
        $roomIds = Child::whereIn('id', $childIds)->pluck('room');
    
        // Step 3: Get Room data for those room IDs
        $rooms = Room::whereIn('id', $roomIds)->get();
    
        return $rooms;
    }
    
    
    



public function lnpData(Request $request)
{
    $id = $request->id;

    $child = Child::find($id);

    if (!$child) {
        return response()->json([
            'status' => false,
            'message' => 'Child not found'
        ], 404);
    }

    // Get latest entry for each subid by created_at
    $progessPlanData = Userprogressplan::with('subActivity.activity.subject')
        ->where('childid', $id)
        ->select('userprogressplan.*')
        ->join(DB::raw('(
            SELECT MAX(id) as id 
            FROM userprogressplan
            WHERE childid = ' . (int)$id . '
            GROUP BY subid
        ) as latest'), 'userprogressplan.id', '=', 'latest.id')
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Data fetched successfully',
        'data' => [
            'child' => $child,
            'progress_plan' => $progessPlanData
        ]
    ]);
}




public function updateAssessmentStatus(Request $request)
{
    try {
        // Manual validation using Validator
        $validator = Validator::make($request->all(), [
            'assessment_id' => 'required|integer|exists:userprogressplan,id',
            'status' => 'required|in:Introduced,Practicing,Completed'
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the assessment record
        $assessment = Userprogressplan::find($request->assessment_id);

        if (!$assessment) {
            return response()->json([
                'success' => false,
                'message' => 'Assessment not found'
            ], 404);
        }

        // Update the status (convert Practicing -> Working)
        $assessment->status = $request->status === "Practicing" ? "Working" : $request->status;
        $assessment->updated_at = now();
        $assessment->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => [
                'assessment_id' => $assessment->id,
                'new_status' => $assessment->status,
                'updated_at' => $assessment->updated_at->format('Y-m-d H:i:s')
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error updating assessment status', [
            'error' => $e->getMessage(),
            'assessment_id' => $request->assessment_id ?? null,
            'status' => $request->status ?? null
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the status'
        ], 500);
    }
}





}
