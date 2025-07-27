<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MontessoriSubject;
use Illuminate\Http\JsonResponse;
use App\Models\MontessoriActivity;
use App\Models\MontessoriSubActivity;
use App\Models\MontessoriSubActivityAccess;
use App\Models\Montessoriactivityaccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

class ObservationController extends Controller
{
       public function getSubjects(): JsonResponse
    {
        $subjects = MontessoriSubject::all(); // Fetch all records

        if(!$subjects){

                $response = [
            'status' => false,
            'message' => "Subject cannot be retrived successfully",
            "data" => []
        ];
        return response()->json($response); // Return as JSON

        }

        $response = [
            'status' => true,
            'message' => "Subject retrived successfully",
            "data" => $subjects
        ];

        return response()->json($response); // Return as JSON
    }

     public function getActivitiesBySubject(Request $request): JsonResponse
    {
        $idSubject = $request->query('idSubject');

        // Validate input

          if (!$idSubject) {
        return response()->json([
            'status' => false,
            'message' => 'Error! Subject ID is missing.'
        ], 400);
    }

        // Fetch filtered activities
        $activities = MontessoriActivity::where('idSubject', $idSubject)->get();

        if(!$activities){
 $response = [
            'status' => false,
            'message' => "Activities retrived successfully",
            "data" => []
        ];

        return response()->json($response);
        }

          $response = [
            'status' => true,
            'message' => "Activities retrived successfully",
            "data" => $activities
        ];

        return response()->json($response);
    }

public function addSubActivity(Request $request)
{
    // Validate input
    $validator = Validator::make($request->all(), [
        'idActivity'           => 'required|integer',
        'title'                => 'required|string|max:255',
        'subjectSelectForSub'  => 'required|string',
    ], [
        'idActivity.required'           => 'Activity ID is required.',
        'idActivity.integer'            => 'Activity ID must be an integer.',
        'title.required'                => 'Title is required.',
        'title.string'                  => 'Title must be a string.',
        'title.max'                     => 'Title must not exceed 255 characters.',
        'subjectSelectForSub.required' => 'Subject is required.',
        'subjectSelectForSub.string'   => 'Subject must be a string.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422);
    }

    // Get validated input
    $validated = $validator->validated();

    // Check center ID
    $centerId = $request->center_id;
    if (!$centerId) {
        return response()->json([
            'status' => false,
            'message' => 'Error! center ID is missing.'
        ], 400);
    }

    try {
        DB::beginTransaction();

        // Create sub-activity
        $subActivity = MontessoriSubActivity::create([
            'idActivity' => $validated['idActivity'],
            'title'      => $validated['title'],
            'subject'    => $validated['subjectSelectForSub'],
            'added_by'   => Auth::user()->userid,
            'added_at'   => now(),
        ]);

        // Create access record
        MontessoriSubActivityAccess::create([
            'idSubActivity' => $subActivity->idSubActivity,
            'centerid'      => $centerId,
            'added_by'      => Auth::user()->userid,
            'added_at'      => now(),
        ]);

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Sub-Activity added successfully',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Database error occurred',
            'error'   => $e->getMessage(),
        ], 500);
    }
}


public function addActivity(Request $request)
{
    // Validation
    $validator = Validator::make($request->all(), [
        'idSubject' => 'required|integer|exists:montessorisubjects,idSubject',
        'title'     => 'required|string|max:255',
        'center_id' => 'required|integer', // Make sure center_id is validated too
    ], [
        'idSubject.required' => 'Subject ID is required.',
        'idSubject.integer'  => 'Subject ID must be an integer.',
        'idSubject.exists'   => 'The selected subject does not exist.',
        'title.required'     => 'Title is required.',
        'title.string'       => 'Title must be a string.',
        'title.max'          => 'Title must not exceed 255 characters.',
        'center_id.required' => 'Center ID is required.',
        'center_id.integer'  => 'Center ID must be an integer.',
    ]);

    // If validation fails, return error response
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422); // Unprocessable Entity
    }

    $validated = $validator->validated();

    try {
        DB::beginTransaction();

        // Insert into montessoriactivity
        $activity = Montessoriactivity::create([
            'idSubject' => $validated['idSubject'],
            'title'     => $validated['title'],
            'added_by'  => Auth::user()->userid,
        ]);

        // dd($activity);

        // Insert into montessoriactivityaccess
        Montessoriactivityaccess::create([
            'idActivity' => $activity->idActivity,
            'centerid'   => $validated['center_id'],
            'added_by'   => Auth::user()->userid,
            'added_at'   => now(),
        ]);

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Activity added successfully'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Database error occurred.',
            'error'   => $e->getMessage()
        ], 500);
    }
}
}
