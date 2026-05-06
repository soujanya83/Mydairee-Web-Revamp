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
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Illuminate\Pagination\Paginator;
use Barryvdh\DomPDF\Facade\Pdf;



class LessonPlanList extends Controller
{
    public function getProgramPlanById($id)
    {
        $plan = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])->find($id);
        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Program plan not found',
            ], 404);
        }

        // Return all fields from the model, including related names for room, creator, children, and educators
        $planArray = $plan->toArray();

        // Add related names for convenience (optional)
        $monthNumber = (int) ($plan->months ?? 0);
        $planArray['month_name'] = $monthNumber > 0 ? \Carbon\Carbon::create()->month($monthNumber)->format('F') : 'December';
        $roomIds = isset($plan->room_id) ? explode(',', $plan->room_id) : [];
        $planArray['room_name'] = !empty($roomIds) ? Room::whereIn('id', $roomIds)->pluck('name')->implode(', ') : null;
        $planArray['creator_name'] = $plan->creator->name ?? null;
        $children_ids = isset($plan->children) ? explode(',', $plan->children) : [];
        $planArray['children_names'] = !empty($children_ids) ? Child::whereIn('id', $children_ids)->pluck('name')->implode(', ') : null;
        $educator_ids = isset($plan->educators) ? explode(',', $plan->educators) : [];
        $planArray['educator_names'] = !empty($educator_ids) ? User::whereIn('id', $educator_ids)->pluck('name')->implode(', ') : null;

        return response()->json([
            'status' => true,
            'data' => $planArray
        ]);
    }

    public function updatestatus(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'planid' => 'required|exists:ProgramPlanTemplateDetailsAdd,id',
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
                     ->where('status',"Published")
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

public function filterProgramPlan(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $user = Auth::user();
    $authId = $user->id;
    $defaultCenterId = session('user_center_id');
    $centerId = $request->input('center_id', $defaultCenterId);

    if (!$centerId) {
        return response()->json([
            'success' => false,
            'message' => 'Center ID is required.'
        ], 422);
    }

    $query = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])
        ->where('centerid', $centerId);

    if ($user->userType === 'Superadmin') {
        $accessibleCenters = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
        if (!in_array($centerId, $accessibleCenters)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized center access'], 403);
        }
    } elseif ($user->userType === 'Staff') {
        $query->where(function ($q) use ($authId) {
            $q->where('created_by', $authId)
              ->orWhereRaw('FIND_IN_SET(?, educators)', [$authId]);
        });
    } elseif ($user->userType === 'Parent') {
        $childIds = Childparent::where('parentid', $authId)->pluck('childid');
        if ($childIds->isNotEmpty()) {
            $query->where(function ($q) use ($childIds) {
                foreach ($childIds as $childId) {
                    $q->orWhereRaw('FIND_IN_SET(?, children)', [$childId]);
                }
            });
        } else {
            return response()->json(['success' => true, 'data' => []]);
        }
    }

    if ($request->filled('room')) {
        $query->whereHas('room', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->room . '%');
        });
    }

    if ($request->filled('created_by')) {
        $query->whereHas('creator', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->created_by . '%');
        });
    }

    if ($request->filled('status')) {
        $query->where('status', 'like', '%' . $request->status . '%');
    }

    if ($request->filled('month')) {
        $query->where('months', $request->month);
    }

    if ($request->filled('year')) {
        $query->where('years', $request->year);
    }

    $programPlans = $query->orderByDesc('created_at')->get();

    $permission = Permission::where('userid', $user->userid)->first();

    $data = $programPlans->map(function ($plan) use ($permission, $user) {
        $monthNumber = (int) $plan->months;
        $monthName = $monthNumber > 0
            ? Carbon::create()->month($monthNumber)->format('F')
            : 'December';

        $roomIds = explode(',', (string) $plan->room_id);
        $rooms = !empty($roomIds) ? Room::whereIn('id', $roomIds)->pluck('name')->toArray() : [];
        $room_name = implode(',', $rooms);

        $deleteProgramPlan = 0;
        $viewProgramPlan = 0;
        $editProgramPlan = 0;

        if ($user->userType === 'Superadmin' || $user->admin === '1') {
            $deleteProgramPlan = 1;
            $viewProgramPlan = 1;
            $editProgramPlan = 1;
        } elseif ($permission) {
            $deleteProgramPlan = $permission->deleteProgramPlan ? 1 : 0;
            $viewProgramPlan = $permission->viewProgramPlan ? 1 : 0;
            $editProgramPlan = $permission->editProgramPlan ? 1 : 0;
        }

        return [
            'id' => $plan->id,
            'month' => $plan->months,
            'month_name' => $monthName,
            'years' => $plan->years,
            'room_name' => $room_name,
            'creator_name' => $plan->creator->name ?? '',
            'created_at_formatted' => optional($plan->created_at)->format('d M Y / H:i'),
            'updated_at_formatted' => optional($plan->updated_at)->format('d M Y / H:i'),
            'can_edit' => $editProgramPlan,
            'can_delete' => $deleteProgramPlan,
            'status' => $plan->status ?? ''
        ];
    });

    return response()->json([
        'status' => true,
        'data' => $data
    ]);
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



//    $pdf = Pdf::loadView('ProgramPlan.apiprint', [
//         'plan' => $plan,
//         'room_name' => $room_name,
//         'educator_names' => $educator_names,
//         'children_names' => $children_names,
//         'month_name' => $month_name
//     ])->setPaper('a4', 'landscape'); // or 'landscape' if needed

//     // ✅ Return as a file download (inline or attachment)
//     return $pdf->download("programplan_{$id}.pdf");
    // Helper to split text like splitItems in Blade
    $splitItems = function($text) {
        if (!is_string($text) || trim($text) === '') return [];
        $text = html_entity_decode($text);
        $text = preg_replace('/<\s*br\s*\/?\s*>/i', '|||', $text);
        $text = preg_replace('/<\s*\/p\s*>/i', '|||', $text);
        $text = preg_replace('/<\s*p\s*>/i', '', $text);
        $lines = preg_split('/\|\|\||\\n|\\r|\n/', $text);
        $items = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '') {
                $items[] = $line;
            }
        }
        return $items;
    };

    $fieldsToSplit = [
        'focus_area', 'art_craft', 'art_craft_experiences', 'outdoor_experiences',
        'inquiry_topic', 'sustainability_topic', 'special_events',
        'children_voices', 'families_input', 'group_experience',
        'spontaneous_experience', 'mindfulness_experiences',
        'working', 'notworking'
    ];
    $planArr = $plan->toArray();
    foreach ($fieldsToSplit as $field) {
        $planArr[$field . '_split'] = $splitItems($planArr[$field] ?? '');
    }

    return response()->json([
        'status' => 'true',
        'message' => 'Program plan retrived successfull',
        'plan' => $planArr,
        'room_name' => $room_name,
        'educator_names' => $educator_names,
        'children_names' => $children_names,
        'month_name' => $month_name
    ]);


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

            // Fetch all staff for the center
            $users = User::where('userType', 'Staff')
                ->where('status', 'ACTIVE')
                ->whereIn('userid', function ($query) use ($centerId) {
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
          // Prepare selected room as {id, name}
          $selected_room = null;
          if ($plan_data && isset($plan_data->room_id)) {
              $roomIds = explode(',', $plan_data->room_id);
              $roomNames = Room::whereIn('id', $roomIds)->pluck('name', 'id');
              // If multiple, return as array of objects; if single, as object
              if (count($roomIds) > 1) {
                  $selected_room = [];
                  foreach ($roomIds as $id) {
                      $selected_room[] = [
                          'id' => $id,
                          'name' => $roomNames[$id] ?? null
                      ];
                  }
              } else {
                  $id = $roomIds[0];
                  $selected_room = [
                      'id' => $id,
                      'name' => $roomNames[$id] ?? null
                  ];
              }
          }

          return response()->json([
              'rooms' => $rooms,
              'selected_room' => $selected_room,
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
        'status' => $request->input('status', 'Draft'),
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

// public function programplanMonthYear(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'months' => 'required',
//         'years' => 'required',
//         'centerid' => 'nullable|integer|exists:centers,id',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Validation failed.',
//             'errors' => $validator->errors(),
//         ], 422);
//     }

//     $centerid = $request->centerid ?? Auth::user()?->user_center_id ?? session('user_center_id');
//     if (!$centerid) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Center not found for current user.',
//         ], 400);
//     }

//     $programPlan = ProgramPlanTemplateDetailsAdd::create([
//         'months' => $request->months,
//         'years' => $request->years,
//         'created_by' => Auth::user()->userid,
//         'centerid' => $centerid,
//     ]);

//     return response()->json([
//         'status' => true,
//         'message' => 'Program plan month/year initialized successfully.',
//         'data' => [
//             'planId' => $programPlan->id,
//             'centerId' => $centerid,
//         ]
//     ]);
// }

public function programplanAutosave(Request $request)
{
    $validator = Validator::make($request->all(), [
        'plan_id' => 'required|integer|exists:programplantemplatedetailsadd,id',
        'focus_area' => 'nullable|string',
        'art_craft' => 'nullable|string',
        'outdoor_experiences' => 'nullable|string',
        'inquiry_topic' => 'nullable|string',
        'sustainability_topic' => 'nullable|string',
        'special_events' => 'nullable|string',
        'children_voices' => 'nullable|string',
        'families_input' => 'nullable|string',
        'group_experience' => 'nullable|string',
        'spontaneous_experience' => 'nullable|string',
        'mindfulness_experiences' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $validated = $validator->validated();
    $planId = $validated['plan_id'];
    unset($validated['plan_id']);

    $updated = ProgramPlanTemplateDetailsAdd::where('id', $planId)->update($validated);

    if ($updated) {
        return response()->json([
            'status' => true,
            'message' => 'Program plan autosaved successfully.',
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'No changes were made or plan not found.',
    ]);
}

public function getProgramPlanEylf(Request $request)
{
    // Keep parity with web create flow: outcomes with activities.
    $outcomes = EYLFOutcome::with('activities')->orderBy('title')->get();

    return response()->json([
        'status' => true,
        'message' => 'EYLF outcomes fetched successfully.',
        'data' => $outcomes,
    ]);
}

public function getProgramPlanEylfFull(Request $request)
{
    // Full payload including subactivities for API clients that need all data in one call.
    $outcomes = EYLFOutcome::with('activities.subActivities')->orderBy('title')->get();

    return response()->json([
        'status' => true,
        'message' => 'EYLF outcomes with subactivities fetched successfully.',
        'data' => $outcomes,
    ]);
}

public function getProgramPlanMontessori(Request $request)
{
    $subjects = MontessoriSubject::with('activities.subActivities')->orderBy('idSubject')->get();

    return response()->json([
        'status' => true,
        'message' => 'Montessori subjects with activities and subactivities fetched successfully.',
        'data' => $subjects,
    ]);
}

public function getProgramPlanSubActivities(Request $request)
{
    $validator = Validator::make($request->all(), [
        'activity_id' => 'required|integer|exists:montessoriactivity,idActivity',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $subActivities = MontessoriSubActivity::where('idActivity', $request->activity_id)
        ->select('idSubActivity', 'idActivity', 'title')
        ->orderBy('idSubActivity')
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Subactivities fetched successfully.',
        'data' => $subActivities,
    ]);
}

public function getProgramPlanEylfSubActivities(Request $request)
{
    $validator = Validator::make($request->all(), [
        'activity_id' => 'required|integer|exists:eylfactivity,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $subActivities = DB::table('eylfsubactivity')
        ->where('activityid', $request->activity_id)
        ->select('id', 'activityid', 'title')
        ->orderBy('id')
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'EYLF subactivities fetched successfully.',
        'data' => $subActivities,
    ]);
}
 public function generatePDF($id)
    {
        try {
            // Fetch the program plan by ID
            $plan = ProgramPlanTemplateDetailsAdd::find($id);
            
            if (!$plan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program plan not found'
                ], 404);
            }

            // Get room name
            $room_name = '';
            if ($plan->room_id) {
                $roomIds = explode(',', $plan->room_id);
                $names = Room::whereIn('id', $roomIds)->pluck('name')->toArray();
                $room_name = implode(', ', $names);
            }

            // Get educator names
            $educator_ids = explode(',', $plan->educators);
            $educator_names = !empty($educator_ids) ? 
                User::whereIn('id', $educator_ids)->pluck('name')->implode(', ') : 
                'No Educators';

            // Get child names
            $child_ids = explode(',', $plan->children);
            $children_names = !empty($child_ids) ? 
                Child::whereIn('id', $child_ids)->pluck('name')->implode(', ') : 
                'No Children';

            // Get month name
            $month_name = strtoupper(Carbon::createFromDate(null, $plan->months)->format('F'));

            // Prepare data for PDF
            $data = [
                'plan' => $plan,
                'room_name' => $room_name,
                'educator_names' => $educator_names,
                'children_names' => $children_names,
                'month_name' => $month_name,
                'current_date' => Carbon::now()->format('d/m/Y'),
            ];

            // Generate PDF
            $pdf = PDF::loadView('api.program-plan-pdf', $data);
            $pdf->setPaper('A3', 'landscape');
            
            // Return PDF as download response
            return $pdf->download("program-plan-{$id}.pdf");
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

}
