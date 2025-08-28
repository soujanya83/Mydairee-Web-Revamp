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
            'idSubActivity' => $subActivity->idActivity, // Assuming primary key is `id`
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
