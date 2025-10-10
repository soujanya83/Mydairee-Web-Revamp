<?php

namespace App\Http\Controllers;

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



class LnPcontroller extends Controller
{
//     public function index(Request $request){

//         $authId = Auth::user()->id; 
//         // $centerid = session('user_center_id');


//         $centerid = session('user_center_id') ?? $request->query('center_id');

//         // Store centerid back into session if it came from query
//         if (!session()->has('user_center_id') && $centerid) {
//             session(['user_center_id' => $centerid]);
//         }

    
//         if(Auth::user()->userType == "Superadmin"){
//             $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
//             $centers = Center::whereIn('id', $center)->get();
//         } else {
//             $centers = Center::where('id', $centerid)->get();
//         }
    
//         $room = $this->getrooms5();
       
    
//         // Try to find the selected room in the available rooms
//         // dd($request->room_id);
//         $selectedroom = $room->where('id', $request->room_id)->first();
    
//         // If not found or not selected, fallback to first room
//         if (!$selectedroom) {
//             $selectedroom = $room->first();
           
//         }
// //   dd( $selectedroom);
//         $children = collect();

//         if (auth()->user()->userType == 'Parent') {
//             $parentId = auth()->user()->id;

//             $childIds = Childparent::where('parentid', $parentId)->pluck('childid');

//             $children = Child::whereIn('id', $childIds)
           
//             ->get();

//         }else{

//         if ($selectedroom) {
//             $children = Child::where('room', $selectedroom->id)
//                 ->get();
//         }

//         }

//         return view('LnP.index', compact('centers', 'room', 'selectedroom', 'children'));
    

    
//     }

public function index(Request $request)
{
    $authId = Auth::id();

    // Center handling
    $centerid = session('user_center_id') ?? $request->query('center_id');
    if (!session()->has('user_center_id') && $centerid) {
        session(['user_center_id' => $centerid]);
    }

    // Fetch centers based on user type
    if (Auth::user()->userType == "Superadmin") {
        $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $centerIds)->get();
    } else {
        $centers = Center::where('id', $centerid)->get();
    }

    // Get all rooms (for selected center ideally)
    $room = $this->getrooms5();

    // Determine selected room
    $roomId = $request->query('room_id') 
             ?? session('selected_room_id') 
             ?? optional($room->first())->id;

    // Save selected room to session
    if ($roomId) {
        session(['selected_room_id' => $roomId]);
    }

    // Find selected room in available list
    $selectedroom = $room->firstWhere('id', $roomId);

    // Fallback to first if not found
    if (!$selectedroom && $room->isNotEmpty()) {
        $selectedroom = $room->first();
        session(['selected_room_id' => $selectedroom->id]);
    }

    // Get children based on user type
    $children = collect();
    if (auth()->user()->userType == 'Parent') {
        $parentId = auth()->user()->id;
        $childIds = Childparent::where('parentid', $parentId)->pluck('childid');
        $children = Child::whereIn('id', $childIds)->get();
    } elseif ($selectedroom) {
        $children = Child::where('room', $selectedroom->id)->get();
    }

    return view('LnP.index', compact('centers', 'room', 'selectedroom', 'children'));
}


    public function getrooms5(){
        try {
            $user = Auth::user();
            $rooms = collect();
            
        if($user->userType === 'Superadmin') {
            $rooms = $this->getroomsforSuperadmin();
            }elseif($user->userType === 'Staff'){
            $rooms = $this->getroomsforStaff();
            }else{
            $rooms = $this->getroomsforParent();
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
    



    private function getroomsforSuperadmin(){
        $authId = Auth::user()->id; 
        $centerid = Session('user_center_id');
    
        $rooms = Room::where('centerid', $centerid)->get();
        return $rooms;
    }
    
    private function getroomsforStaff(){
        $authId = Auth::user()->id; 
        $centerid = Session('user_center_id');
    
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
    
    
    



    public function lnpData($id){

        $child = Child::where('id',$id)->first();

         // Get latest entry for each subid by created_at
        $progessPlanData = Userprogressplan::with('subActivity.activity.subject')->where('childid', $id)
            ->select('userprogressplan.*')
            ->join(DB::raw('(
                SELECT MAX(id) as id FROM userprogressplan
                WHERE childid = ' . (int)$id . '
                GROUP BY subid
            ) as latest'), 'userprogressplan.id', '=', 'latest.id')
            ->get();

    

        return view('LnP.datapage', compact('child', 'progessPlanData'));

    }



    public function updateAssessmentStatus(Request $request)
{
    // dd($request->all());
    try {
        // Validate the request
        $request->validate([
            'assessment_id' => 'required|integer|exists:userprogressplan,id',
            'status' => 'required|in:Introduced,Practicing,Completed'
        ]);

        // Find the assessment record
        $assessment = Userprogressplan::find($request->assessment_id);
        
        if (!$assessment) {
            return response()->json([
                'success' => false,
                'message' => 'Assessment not found'
            ], 404);
        }

        // Update the status
        if($request->status == "Practicing"){
            $assessment->status = "Working";
        }else{
            $assessment->status = $request->status;
        }
        $assessment->updated_at = now();
        $assessment->save();

        // Log the update (optional)
        // \Log::info('Assessment status updated', [
        //     'assessment_id' => $assessment->id,
        //     'old_status' => $assessment->getOriginal('status'),
        //     'new_status' => $request->status,
        //     'updated_by' => auth()->id() ?? 'system'
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => [
                'assessment_id' => $assessment->id,
                'new_status' => $assessment->status,
                'updated_at' => $assessment->updated_at->format('Y-m-d H:i:s')
            ]
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        \Log::error('Error updating assessment status', [
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
