<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MontessoriSubject;
use Illuminate\Http\JsonResponse;
use App\Models\MontessoriActivity;
use App\Models\MontessoriSubActivity;
use App\Models\MontessoriSubActivityAccess;
use App\Models\Montessoriactivityaccess;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;


class ObservationController extends Controller
{

        public function updateActivity(Request $request){
                $subActivity = MontessoriActivity::where('idActivity',$request->activityid)->first();
        $subActivity->title = $request->title;
        $subActivity->save();

        return redirect()->back()->with('message','activity updated successfully');

    }

     public function updateSubActivity(Request $request){
        $subActivity = MontessoriSubActivity::where('idSubActivity',$request->subactivityid)->first();
        $subActivity->title = $request->title;
        $subActivity->save();

        return redirect()->back()->with('message','sub activity updated successfully');


        // dd($request->all());

    }

  public function deleteActivity(Request $request)
{
    $activityId = $request->idActivity;

    // Find the activity
    $activity = MontessoriActivity::where('idActivity', $activityId)->first();

    if (!$activity) {
        return response()->json([
            'status' => 'error',
            'message' => 'Activity not found'
        ], 404);
    }

    // 1️⃣ Delete all subactivity access related to this activity
    $subActivityIds = MontessoriSubActivity::where('idActivity', $activityId)->pluck('idSubActivity');
    MontessoriSubActivityAccess::whereIn('idSubActivity', $subActivityIds)->delete();

    // 2️⃣ Delete all subactivities related to this activity
    MontessoriSubActivity::where('idActivity', $activityId)->delete();

    // 3️⃣ Delete all activity access related to this activity
    MontessoriActivityAccess::where('idActivity', $activityId)->delete();

    // 4️⃣ Delete the activity itself
    $activity->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Activity and all related subactivities/access deleted successfully'
    ]);
}


    public function deleteSubActivity(Request $request) {
    $subActivity = MontessoriSubActivity::where('idSubActivity', $request->idSubActivity)->first();

    if ($subActivity) {
        MontessoriSubActivityAccess::where('idSubActivity', $request->idSubActivity)->delete();
        $subActivity->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Subactivity deleted successfully'
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Subactivity not found'
        ], 404);
    }
}


public function activityList()
{
    $centerId = session('user_center_id');

// $subjects = MontessoriSubject::with([
//     'activities' => function ($query) use ($centerId) {
//         $query->whereHas('montessoriAccess', function ($q) use ($centerId) {
//             $q->where('centerid', $centerId);
//         })
//         ->with(['subActivities' => function ($subQuery) use ($centerId) {
//             $subQuery->whereHas('montessoriSubActivityAccess', function ($sq) use ($centerId) {
//                 $sq->where('centerid', $centerId);
//             });
//         }]);
//     }
// ])->get();

$subjects = MontessoriSubject::with([
    'activities.subActivities'
])->get();




    // dd( $subjects);

    return view('ProgramPlan.activityList', compact('subjects'));
}



// public function SubactivityList() {
//     $subactivities = MontessoriSubActivity::with('subactivity')->whereNotNull('userid')->get();
//     return view('ProgramPlan.activityList',compact('subactivities'));
// }

     public function getSubjects(): JsonResponse
    {
        $subjects = MontessoriSubject::all(); // Fetch all records

        return response()->json($subjects); // Return as JSON
    }

     public function getActivitiesBySubject(Request $request): JsonResponse
    {
        $idSubject = $request->query('idSubject');

        // Validate input
        if (empty($idSubject)) {
            return response()->json([]);
        }

        // Fetch filtered activities
        $activities = MontessoriActivity::where('idSubject', $idSubject)->get();

        return response()->json($activities);
    }

    public function addSubActivity(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'idActivity' => 'required|integer',
        'title' => 'required|string|max:255',
        'subjectSelectForSub' => 'required'
    ]);
$centerId = Session('user_center_id');
    try {
        DB::beginTransaction();

        // Create sub-activity
        $subActivity = MontessoriSubActivity::create([
            'idActivity' => $validated['idActivity'],
            'title' => $validated['title'],
            'subject' => $request->subjectSelectForSub,
            'added_by' => Auth::user()->userid,
            'added_at' => now(),
        ]);

        // dd($subActivity);

        // Create access record
        MontessoriSubActivityAccess::create([
            'idSubActivity' => $subActivity->idSubActivity, // Assuming primary key is `id`
            'centerid' => $centerId,
            'added_by' => Auth::user()->userid,
            'added_at' => now(), 
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Sub-Activity added successfully',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Database error occurred',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function addActivity(Request $request)
{
    // Validate input
    $request->validate([
        'idSubject' => 'required|integer|exists:montessorisubjects,idSubject',
        'title' => 'required|string|max:255',
    ]);
 $centerId = Session('user_center_id');
    DB::beginTransaction();

    try {
        // Insert into montessoriactivity
        $activity = Montessoriactivity::create([
            'idSubject' => $request->idSubject,
            'title' => $request->title,
            'added_by' => Auth::user()->userid,
        ]);

        // Insert into montessoriactivityaccess
        Montessoriactivityaccess::create([
    'idActivity' => $activity->idActivity,
    'centerid' => $centerId,
    'added_by' => Auth::user()->userid, // assuming user is logged in
    'added_at' => now(),        // Laravel's current timestamp
]);


        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Activity added successfully'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ], 500);
    }
}

}
