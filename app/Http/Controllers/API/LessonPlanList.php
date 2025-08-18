<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Models\ProgramPlanTemplateDetailsAdd;
use App\Models\Center;
use App\Models\ServiceDetailsModel;
use App\Models\User; // Add this at the top if not already added
use App\Models\Usercenter; // Add this at the top if not already added
use App\Models\Child; // Add this at the top if not already added
use App\Models\Childparent; // Add this at the top if not already added
use App\Models\Room;
use App\Models\EYLFOutcome;
use App\Models\MontessoriSubject;
use App\Models\MontessoriActivity;
use App\Models\ProgramPlan;
use App\Models\MontessoriSubActivity;
use App\Models\RoomStaff;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Illuminate\Pagination\Paginator;
use Barryvdh\DomPDF\Facade\Pdf;

class LessonPlanList extends Controller
{

public function updatestatus(Request $r)
{
    $validator = Validator::make($r->all(), [
        'planid' => 'required|exists:program_plan_template_details_adds,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed',
            'errors'  => $validator->errors(),
        ], 422);
    }

    // Find plan
    $plan = ProgramPlanTemplateDetailsAdd::find($r->planid);

    // Toggle status
    $plan->status = ($plan->status == 'Draft') ? 'Published' : 'Draft';
    $plan->save();

    return response()->json([
        'status'  => true,
        'message' => 'Status updated successfully',
        'new_status' => $plan->status
    ]);
}

    public function centers(){
         $user = Auth::user();
            $authId = $user->id;
             if ($user->userType == "Superadmin") {
            // dd('here');
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
              $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        }

        return response()->json([
            'status' => true,
            'data' => $centers
        ]);

    }
    
    public function programPlanList(Request $request)
{
    
        $user = Auth::user();
        // dd($user);
        if (!$user) {
              return response()->json([
        'success' => false,
        'message' => 'User not found or not authenticated',
        
    ], 401);
    // return response()->json(['error' => 'User not found or not authenticated'], 401);
}
        $authId = $user->id;
        // $centerId = Session('user_center_id');
       $validator = Validator::make($request->all(), [
    'centerid' => 'required|integer|min:1|exists:centers,id', // Adjust table/column as needed
], [
    'centerid.required' => 'Center ID is required.',
    'centerid.integer'  => 'Center ID must be an integer.',
    'centerid.min'      => 'Center ID must be greater than 0.',
    'centerid.exists'   => 'Selected Center ID does not exist.',
]);

if ($validator->fails()) {
    return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors'  => $validator->errors(),
    ], 422);
}

$validated = $validator->validated();
$centerId = $validated['centerid'];

        if ($user->userType == "Superadmin") {
            // dd('here');
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerId)->get();
        }

        $programPlans = [];

        if ($user->userType === 'Superadmin') {
            $programPlans = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])
                ->where('centerid', $centerId)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($user->userType === 'Staff') {
            $programPlans = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])
                ->where('centerid', $centerId)
                ->where(function ($query) use ($authId) {
                    $query->where('created_by', $authId)
                          ->orWhereRaw('FIND_IN_SET(?, educators)', [$authId]);
                })
                ->orderByDesc('created_at')
                ->get();
        } elseif ($user->userType === 'Parent') {
            $childIds = ChildParent::where('parentid', $authId)->pluck('childid');

            if ($childIds->isNotEmpty()) {
                $programPlans = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])
                    ->where('centerid', $centerId)
                    ->where(function ($query) use ($childIds) {
                        foreach ($childIds as $childId) {
                            $query->orWhereRaw('FIND_IN_SET(?, children)', [$childId]);
                        }
                    })
                    ->orderByDesc('created_at')
                    ->get();
            }
        }

        // Month name helper
        // $getMonthName = function ($monthNumber) {
            $getMonthName = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
            // return $months[$monthNumber] ?? '';
        // };

        $userType = $user->userType;
        $userId = $authId;

        // return view('programPlan.list', compact(
        //     'programPlans', 'userType', 'userId', 'centerId', 'centers', 'getMonthName'
        // ));
        return response()->json([
    'status' => true,
    'data' => [
        'programPlans' => $programPlans,
        'userType' => $userType,
        'userId' => $userId,
        'centerId' => $centerId,
        'centers' => $centers,
        'getMonthName' => $getMonthName,
    ]
]);

    // } else {
    //     // return redirect('login');
    // }
}


public function programPlanPrintPage(Request $request)
{

  $validator = Validator::make($request->all(),[
    'id' => 'required|integer|min:1', // or add `exists:table,id` for DB check
], [
    'id.required' => 'ID is required.',
    'id.integer'  => 'ID must be an integer.',
    'id.min'      => 'ID must be greater than 0.',
]);

  if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422);
    }

$validated = $validator->validated();
$id = $validated['id'];


    // Fetch the program plan by ID
    $plan = ProgramPlanTemplateDetailsAdd::find($id);

     if (!$plan) {
        return response()->json([
            'status' => false,
            'message' => 'Program plan not found.'
        ], 404);
    }

    // Convert month number to full uppercase name
 $month_name = $plan->months
        ? strtoupper(\Carbon\Carbon::createFromDate(null, $plan->months)->format('F'))
        : '';

    // Get room name
    $room_name = optional($plan->room)->name ?? 'Unknown Room';

    // Get educator names
    $educator_ids = explode(',', $plan->educators);
    $educator_names = !empty($educator_ids)
        ? User::whereIn('id', $educator_ids)->pluck('name')->implode(', ')
        : 'No Educators';

    // Get child names
    $child_ids = explode(',', $plan->children);
    $children_names = !empty($child_ids)
        ? \App\Models\Child::whereIn('id', $child_ids)->pluck('name')->implode(', ')
        : 'No Children';



   $pdf = Pdf::loadView('ProgramPlan.apiprint', [
        'plan' => $plan,
        'room_name' => $room_name,
        'educator_names' => $educator_names,
        'children_names' => $children_names,
        'month_name' => $month_name
    ])->setPaper('a4', 'landscape'); // or 'landscape' if needed

    // ✅ Return as a file download (inline or attachment)
    return $pdf->download("programplan_{$id}.pdf");


}


 public function createForm(Request $request)
    {
        // if (!Auth::check()) {
        // return redirect('login');
        // }

      $authId = Auth::user()->userid; 
      $user = Auth::user();
    // $centerId = Session('user_center_id');
$validator = Validator::make($request->all(), [
    'centerid' => 'required|integer|min:1',
], [
    'user_center_id.required' => 'Center ID is required.',
    'user_center_id.integer'  => 'Center ID must be an integer.',
    'user_center_id.min'      => 'Center ID must be greater than 0.',
]);

if ($validator->fails()) {
    return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors'  => $validator->errors(),
    ], 422);
}

// ✅ Passed validation
$validated = $validator->validated();
$centerId = $validated['centerid'];

    // dd($centerId);
        // if (Auth::check()) {
            // $centerid = $request->centerid;
            // dd($centerid);
            // $userid = session('LoginId');
            // $usertype = session('UserType');
            $planid = $request->planId;
            // dd($planid);

            $admin = ($user->userType == "Superadmin") ? 1 : 0;

            // Fetch rooms
            $rooms = Room::when($admin == 1, function ($query) use ($centerId) {
                return $query->where('centerid', $centerId);
            })
            ->when($admin == 0, function ($query) use ($authId) {
                return $query->whereHas('staff', function ($query) use ($authId) {
                    return $query->where('staffid', $authId);
                });
            })
            ->get();

            // Fetch users
            $users = User::whereIn('userid', function ($query) use ($centerId) {
                $query->select('userid')->from('usercenters')->where('centerid', $centerId);
            })->get();

            // Fetch EYLF Outcomes with Activities
            $eylf_outcomes = EYLFOutcome::with('activities')->orderBy('title')->get();

            // Fetch Montessori Subjects with Activities and Sub-Activities
            $montessori_subjects = MontessoriSubject::with(['activities.subActivities'])->orderBy('idSubject')->get();

            // Fetch Program Plan Data if editing
            $plan_data = null;
            $selected_educators = [];
            $selected_children = [];

            if ($planid) {
                $plan_data = ProgramPlanTemplateDetailsAdd::find($planid);

                if ($plan_data) {
                    $selected_educators = explode(',', $plan_data->educators);
                    $selected_children = explode(',', $plan_data->children);
                }
            }

            $userId = $authId;

            // Return view with data
            // dd($plan_data);
          return response()->json([
    'rooms' => $rooms,
    'users' => $users,
    'centerId' => $centerId,
    'userId' => $userId,
    'eylf_outcomes' => $eylf_outcomes,
    'montessori_subjects' => $montessori_subjects,
    'plan_data' => $plan_data,
    'selected_educators' => $selected_educators,
    'selected_children' => $selected_children
]);

        // } else {
        //     return redirect()->route('login');
        // }
    }

public function getRoomUsers(Request $request)
{
   $validator = Validator::make($request->all(), [
    'room_id' => 'required|integer|exists:room_staff,roomid',
], [
    'room_id.required' => 'Room ID is required.',
    'room_id.integer'  => 'Room ID must be an integer.',
    'room_id.exists'   => 'The selected Room ID does not exist in room_staff.',
]);

if ($validator->fails()) {
    return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors'  => $validator->errors(),
    ], 422);
}

// ✅ Passed validation
$validated = $validator->validated();
$roomId = $validated['room_id'];

    // Get staff IDs assigned to the room
    $staffIds = RoomStaff::where('roomid', $roomId)->pluck('staffid');

 if ($staffIds->isEmpty()) {
    return response()->json([
        'status' => false,
        'message' => 'No users found for this room.',
        'users' => []
    ]);
}


    // Get user details
    $users = User::whereIn('userid', $staffIds)
        ->select('userid as id', 'name')
        ->get();

    return response()->json([
        'status' => true,
        'users' => $users
    ]);
}


public function getRoomChildren(Request $request)
{
  $validator = Validator::make($request->all(), [
    'room_id' => 'required|integer|exists:child,room'
], [
    'room_id.required' => 'Room ID is required.',
    'room_id.integer'  => 'Room ID must be an integer.',
    'room_id.exists'   => 'Selected room ID does not exist.',
]);

if ($validator->fails()) {
    return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $validator->errors(),
    ], 422);
}

// If validation passes:
$validated = $validator->validated();
$roomId = $validated['room_id'];

    $roomId = $request->input('room_id');

    $children = Child::where('room', $roomId)
        ->select('id', 'name', 'lastname')
        ->get()
        ->map(function ($child) {
            return [
                'id' => $child->id,
                'name' => trim($child->name . ' ' . $child->lastname),
            ];
        });

    if ($children->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No children found for this room.',
            'children' => []
        ]);
    }

    return response()->json([
        'status' => true,
        'children' => $children
    ]);
}


// 
public function saveProgramPlan(Request $request)
{
    // Validate required fields
    // dd($request->all());
 $validator = Validator::make($request->all(), [
    'room_id'  => 'required|integer',
    'months'   => 'required|string',
    'users'    => 'required|array',
    'children' => 'required|array',
    'centerid' => 'required',
], [
    'centerid.required' => 'Center ID is required',
    'room_id.required'  => 'Room ID is required.',
    'room_id.integer'   => 'Room ID must be an integer.',
    'months.required'   => 'Month is required.',
    'months.string'     => 'Month must be a string.',
    'users.required'    => 'At least one user is required.',
    'users.array'       => 'Users must be an array.',
    'children.required' => 'At least one child is required.',
    'children.array'    => 'Children must be an array.',
]);

if ($validator->fails()) {
    return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors'  => $validator->errors(),
    ], 422);
}

$validated = $validator->validated();
$roomId = $validated['room_id'];
$months = $validated['months'];
$users = $validated['users'];
$children = $validated['children'];
$centerId = $validated['centerid'];
    // dd('here');
    

    // Convert arrays to comma-separated strings
    $educators = implode(',', $request->input('users'));
    $children = implode(',', $request->input('children'));

    // Prepare data
    $programData = [
        'room_id' => $roomId,
        'months' => $months,
        'years' => $request->input('years'),
        'centerid' => $centerId,
        'created_by' => Auth::user()->userid,
        'educators' => $educators,
        'children' => $children,
        'practical_life' => $request->input('practical_life'),
        'focus_area' => $request->input('focus_area'),
        'practical_life_experiences' => $request->input('practical_life_experiences'),
        'sensorial' => $request->input('sensorial'),
        'sensorial_experiences' => $request->input('sensorial_experiences'),
        'math' => $request->input('math'),
        'math_experiences' => $request->input('math_experiences'),
        'language' => $request->input('language'),
        'language_experiences' => $request->input('language_experiences'),
        'culture' => $request->input('culture'),
        'culture_experiences' => $request->input('culture_experiences'),
        'art_craft' => $request->input('art_craft'),
        'art_craft_experiences' => $request->input('art_craft_experiences'),
        'eylf' => $request->input('eylf'),
        'outdoor_experiences' => $request->input('outdoor_experiences'),
        'inquiry_topic' => $request->input('inquiry_topic'),
        'sustainability_topic' => $request->input('sustainability_topic'),
        'special_events' => $request->input('special_events'),
        'children_voices' => $request->input('children_voices'),
        'families_input' => $request->input('families_input'),
        'group_experience' => $request->input('group_experience'),
        'spontaneous_experience' => $request->input('spontaneous_experience'),
        'mindfulness_experiences' => $request->input('mindfulness_experiences'),
    ];

    $planId = $request->input('plan_id');

    if ($planId) {
        // Update
        $programData['updated_at'] = Carbon::now('Australia/Sydney');
        $updated = ProgramPlanTemplateDetailsAdd::where('id', $planId)->update($programData);

        if ($updated) {
            return response()->json([
                'status' => true,
                'message' => 'Program plan updated successfully',
               
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Error updating program plan. Please try again.'
        ]);
    } else {
        // Insert
        $programData['created_at'] = Carbon::now('Australia/Sydney');
        $newPlan = ProgramPlanTemplateDetailsAdd::create($programData);

        if ($newPlan) {
            return response()->json([
                'status' => true,
                'message' => 'Program plan created successfully',
              
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Error creating program plan. Please try again.'
        ]);
    }

}

public function deleteProgramPlan(Request $request)
{
    if(!$request->program_id){
           return response()->json([
                'success' => false,
                'message' => 'Program plan id not found. Please provide'
            ]);
    }

    try {
        $deleted = ProgramPlanTemplateDetailsAdd::where('id', $request->program_id)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Program plan deleted successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Program plan not found or already deleted.'
            ]);
        }
    } catch (\Exception $e) {
        Log::error('Program Plan Delete Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Unexpected error occurred while deleting program plan.'
        ], 500);
    }
}

}
