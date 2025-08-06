<?php
namespace App\Http\Controllers;

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




class LessonPlanList extends Controller
{

    public function updatestatus(Request $r)
{
    $validator = Validator::make($r->all(), [
        'planid' => 'required|exists:programplantemplatedetailsadd,id',
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

// public function filterProgramPlan(Request $request)
// {
//     if (!Auth::check()) {
//         return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
//     }

//     $user = Auth::user();
//     $authId = $user->id;
//     $defaultCenterId = session('user_center_id');

//     // Determine which center to use
//     $centerId = $request->center_id ?? $defaultCenterId;

//     // Base query with relationships
//     $query = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])
//         ->where('centerid', $centerId);

//     // Filter by user type
//     if ($user->userType === 'Superadmin') {
//         // Optionally validate superadmin's centers
//         $accessibleCenters = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
//         if (!in_array($centerId, $accessibleCenters)) {
//             return response()->json(['success' => false, 'message' => 'Unauthorized center access'], 403);
//         }

//     } elseif ($user->userType === 'Staff') {
//         $query->where(function ($q) use ($authId) {
//             $q->where('created_by', $authId)
//               ->orWhereRaw('FIND_IN_SET(?, educators)', [$authId]);
//         });

//     } elseif ($user->userType === 'Parent') {
//         $childIds = ChildParent::where('parentid', $authId)->pluck('childid');
//         if ($childIds->isNotEmpty()) {
//             $query->where(function ($q) use ($childIds) {
//                 foreach ($childIds as $childId) {
//                     $q->orWhereRaw('FIND_IN_SET(?, children)', [$childId]);
//                 }
//             });
//         } else {
//             return response()->json(['success' => true, 'data' => []]);
//         }
//     }

//     // 🔹 Optional filters
//     if ($request->filled('room')) {
//         $query->whereHas('room', function ($q) use ($request) {
//             $q->where('name', 'like', '%' . $request->room . '%');
//         });
//     }

//     if ($request->filled('created_by')) {
//         $query->whereHas('creator', function ($q) use ($request) {
//             $q->where('name', 'like', '%' . $request->created_by . '%');
//         });
//     }

//     if($request->month){
//   $query->whereHas('creator', function ($q) use ($request) {
//             $q->where('name', 'like', '%' . $request->created_by . '%');
//         });
//     }

//     // Fetch data
//     $programPlans = $query->orderByDesc('created_at')->get();

//     // Transform response
//     $data = $programPlans->map(function ($plan) {
//         return [
//             'id' => $plan->id,
//             'month' => $plan->months,
//             'month_name' => \Carbon\Carbon::create()->month($plan->months)->format('F'),
//             'years' => $plan->years,
//             'room_name' => $plan->room->name ?? '',
//             'creator_name' => $plan->creator->name ?? '',
//             'created_at_formatted' => $plan->created_at->format('d M Y / H:i'),
//             'updated_at_formatted' => $plan->updated_at->format('d M Y / H:i'),
//             'can_edit' => auth()->user()->userType !== 'Parent',  // example permission
//             'can_delete' => auth()->user()->userType === 'Superadmin', // example permission
//         ];
//     });

//     return response()->json([
//         'status' => true,
//         'data' => $data
//     ]);
// }
public function filterProgramPlan(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $user = Auth::user();
    $authId = $user->id;
    $defaultCenterId = session('user_center_id');

    // Determine which center to use
    $centerId = $request->center_id ?? $defaultCenterId;

    // Base query
    $query = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])
        ->where('centerid', $centerId);

    // 🔹 User type filters
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
        $childIds = ChildParent::where('parentid', $authId)->pluck('childid');
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

    // 🔹 Optional filters
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

    // dd($request->month);

    // $months = ['january' => 1 , 'febuary' => 1 , 'march' => 2 , 'april' => 4];

    // foreach($months as $key=>$month){
    //     if($request->month == $key){
    //         $month = $value;

    //     }


    // }
    // // 🔹 Month filter (0-11 or 1-12 based on DB)
    // if ($request->filled('month')) {
    //     $query->where('months', $month);
    // }

    // Fetch data
    $programPlans = $query->orderByDesc('created_at')->get();

    // Transform response
    $data = $programPlans->map(function ($plan) {
        $monthNumber = (int) $plan->months;
        $monthName = $monthNumber > 0 
            ? \Carbon\Carbon::create()->month($monthNumber)->format('F')
            : 'December'; // if 0 is December in your DB

        return [
            'id' => $plan->id,
            'month' => $plan->months,
            'month_name' => $monthName,
            'years' => $plan->years,
            'room_name' => $plan->room->name ?? '',
            'creator_name' => $plan->creator->name ?? '',
            'created_at_formatted' => $plan->created_at->format('d M Y / H:i'),
            'updated_at_formatted' => $plan->updated_at->format('d M Y / H:i'),
            'can_edit' => auth()->user()->userType !== 'Parent',
            'can_delete' => auth()->user()->userType === 'Superadmin',
            'status' => $plan->status ?? ''
        ];
    });

    return response()->json([
        'status' => true,
        'data' => $data
    ]);
}


public function programPlanList(Request $request)
{
    if (Auth::check()) {
        $user = Auth::user();
        $authId = $user->id;
        $centerId = Session('user_center_id');

        if ($user->userType == "Superadmin") {
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
                ->paginate(12);
        } elseif ($user->userType === 'Staff') {
            $programPlans = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])
                ->where('centerid', $centerId)
                ->where(function ($query) use ($authId) {
                    $query->where('created_by', $authId)
                          ->orWhereRaw('FIND_IN_SET(?, educators)', [$authId]);
                })
                ->orderByDesc('created_at')
                ->paginate(12);
        } elseif ($user->userType === 'Parent') {
            $childIds = ChildParent::where('parentid', $authId)->pluck('childid');

            if ($childIds->isNotEmpty()) {
                $programPlans = ProgramPlanTemplateDetailsAdd::with(['creator:id,name', 'room:id,name'])
                ->where('status','Published')
                    ->where('centerid', $centerId)
                    ->where(function ($query) use ($childIds) {
                        foreach ($childIds as $childId) {
                            $query->orWhereRaw('FIND_IN_SET(?, children)', [$childId]);
                        }
                    })
                    ->orderByDesc('created_at')
                    ->paginate(12);
            }
        }

        // Month name helper
        $getMonthName = function ($monthNumber) {
            $months = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
            return $months[$monthNumber] ?? '';
        };

        $userType = $user->userType;
        $userId = $authId;

        return view('ProgramPlan.list', compact(
            'programPlans', 'userType', 'userId', 'centerId', 'centers', 'getMonthName'
        ));
    } else {
        return redirect('login');
    }
}


public function programPlanPrintPage($id)
{
    // Check if user is authenticated
    if (!Auth::check()) {
        return redirect('login');
    }
    // dd($id);

    // Fetch the program plan by ID
    $plan = ProgramPlanTemplateDetailsAdd::find($id);

    if (!$plan) {
        abort(404); // Show 404 if not found
    }

    // Convert month number to full uppercase name
    $month_name = strtoupper(\Carbon\Carbon::createFromDate(null, $plan->months)->format('F'));

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

    return view('ProgramPlan.print', [
        'plan' => $plan,
        'room_name' => $room_name,
        'educator_names' => $educator_names,
        'children_names' => $children_names,
        'month_name' => $month_name
    ]);
}


 public function createForm(Request $request)
    {
        if (!Auth::check()) {
        return redirect('login');
        }

      $authId = Auth::user()->userid; 
    $centerId = Session('user_center_id');

    // dd($centerId);
        if (Auth::check()) {
            $centerid = $request->centerid;
            // dd($centerid);
            // $userid = session('LoginId');
            // $usertype = session('UserType');
            $planid = $request->planId;
            // dd($planid);

            $admin = (Auth::user()->userType == "Superadmin") ? 1 : 0;

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
            // dd($montessori_subjects);
//             foreach( $montessori_subjects as $subject){
//   if($subject->name == "Sensorial"){
//                 dd($subject->activities );

//             }
//             }
          
            return view('ProgramPlan.create', compact(
                'rooms', 'users', 'centerId', 'userId', 'eylf_outcomes', 'montessori_subjects', 'plan_data', 'selected_educators', 'selected_children'
            ));
        } else {
            return redirect()->route('login');
        }
    }

    // ajax here 
public function getRoomUsers(Request $request)
{
    $request->validate([
        'room_id' => 'required|array',
        'room_id.*' => 'exists:room_staff,roomid', // validate each room ID
        'center_id' => 'required|integer', 
    ]);

    $roomIds = $request->input('room_id');

    // Get unique staff IDs from selected rooms
    $staffIds = RoomStaff::whereIn('roomid', $roomIds)
        ->pluck('staffid')
        ->unique()
        ->values();

    if ($staffIds->isEmpty()) {
        return response()->json([]);
    }

    // Get user details for all staff
    $users = User::whereIn('userid', $staffIds)
        ->select('userid as id', 'name')
        ->get();

    return response()->json($users);
}


public function getRoomChildren(Request $request)
{
    $request->validate([
        'room_id' => 'required|array',
        'room_id.*' => 'exists:room,id', // ideally validate against rooms table
        'center_id' => 'required|integer',
    ]);

    $roomIds = $request->input('room_id');
    // dd($roomIds);

    $children = Child::whereIn('room', $roomIds)
        ->select('id', 'name', 'lastname')
        ->get()
        ->map(function ($child) {
            return [
                'id' => $child->id,
                'name' => trim($child->name . ' ' . $child->lastname),
            ];
        })
        ->unique('id')  // ensure no duplicates if a child is linked multiple times
        ->values();     // reset array keys

    return response()->json($children);
}


// 
public function saveProgramPlan(Request $request)
{
    // Validate required fields
    $validated = $request->validate([
        'room' => 'required|array',
        'months' => 'required|string',
        'users' => 'required|array',
        'children' => 'required|array',
    ]);
    // dd($request->all());

    // Convert arrays to comma-separated strings
    $educators = implode(',', $request->input('users'));
    $children = implode(',', $request->input('children'));
        $room = implode(',', $request->input('room'));

    // Prepare data
    $programData = [
        'room_id' => $room,
        'months' => $request->input('months'),
        'years' => $request->input('years'),
        'centerid' => $request->input('centerid'),
        'created_by' => $request->input('user_id'),
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
                'success' => true,
                'message' => 'Program plan updated successfully',
                'redirect_url' => route('print.programplan', $planId)
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Error updating program plan. Please try again.'
        ]);
    } else {
        // Insert
        $programData['created_at'] = Carbon::now('Australia/Sydney');
        $newPlan = ProgramPlanTemplateDetailsAdd::create($programData);

        if ($newPlan) {
            return response()->json([
                'success' => true,
                'message' => 'Program plan created successfully',
                'redirect_url' => route('print.programplan', $newPlan->id)
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Error creating program plan. Please try again.'
        ]);
    }

}

public function deleteProgramPlan(Request $request)
{
    // Check if user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'You must be logged in to perform this action'
        ], 403);
    }

    // Validate request is AJAX (optional in Laravel)
    if (!$request->ajax()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid request method'
        ], 400);
    }

    // Validate input
    $request->validate([
        'program_id' => 'required|integer|exists:programplantemplatedetailsadd,id'
    ]);

    try {
        // Delete the record
        $deleted = ProgramPlanTemplateDetailsAdd::where('id', $request->program_id)->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Program plan deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete program plan'
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ], 500);
    }
}

}