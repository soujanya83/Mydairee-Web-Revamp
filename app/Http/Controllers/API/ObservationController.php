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
    private function currentUserIdentifier()
    {
        $user = Auth::user();

        return $user?->userid ?? $user?->id ?? null;
    }

       public function getSubjects(): JsonResponse
    {
        $subjects = MontessoriSubject::all(); // Fetch all records

        if ($subjects->isEmpty()) {

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
        $activities = MontessoriActivity::with('subActivities')
            ->where('idSubject', $idSubject)
            ->orderBy('title')
            ->get();

        if ($activities->isEmpty()) {
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

    public function getSubActivitiesByActivity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idActivity' => 'required|integer|exists:montessoriactivity,idActivity',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $subActivities = MontessoriSubActivity::where('idActivity', $request->idActivity)
            ->orderBy('title')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Sub-activities retrieved successfully.',
            'data' => $subActivities,
        ]);
    }

public function addSubActivity(Request $request)
{
    // Validate input
    $validator = Validator::make($request->all(), [
        'idActivity'           => 'required|integer',
        'title'                => 'required|string|max:255',
        'subjectSelectForSub'  => 'nullable|string|max:255',
        'center_id'            => 'required|integer',
    ], [
        'idActivity.required'           => 'Activity ID is required.',
        'idActivity.integer'            => 'Activity ID must be an integer.',
        'title.required'                => 'Title is required.',
        'title.string'                  => 'Title must be a string.',
        'title.max'                     => 'Title must not exceed 255 characters.',
        'subjectSelectForSub.string'   => 'Subject must be a string.',
        'subjectSelectForSub.max'      => 'Subject must not exceed 255 characters.',
        'center_id.required'           => 'Center ID is required.',
        'center_id.integer'            => 'Center ID must be an integer.',
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

    $centerId = (int) $validated['center_id'];
    $userId = $this->currentUserIdentifier();
    $activity = MontessoriActivity::with('subject')->find($validated['idActivity']);
    $subjectValue = $validated['subjectSelectForSub'] ?? $activity?->subject?->name;

    if (!$userId) {
        return response()->json([
            'status' => false,
            'message' => 'Authenticated user not found.'
        ], 401);
    }

    if (!$subjectValue) {
        return response()->json([
            'status' => false,
            'message' => 'Subject information is required for the sub-activity.',
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Create sub-activity
        $subActivity = MontessoriSubActivity::create([
            'idActivity' => $validated['idActivity'],
            'title'      => $validated['title'],
            'subject'    => $subjectValue,
            'added_by'   => $userId,
            'added_at'   => now(),
        ]);

        // Create access record
        MontessoriSubActivityAccess::create([
            'idSubActivity' => $subActivity->idSubActivity,
            'centerid'      => $centerId,
            'added_by'      => $userId,
            'added_at'      => now(),
        ]);

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Sub-Activity added successfully',
            'data' => $subActivity,
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
        'center_id' => 'required|integer',
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
    $userId = $this->currentUserIdentifier();

    if (!$userId) {
        return response()->json([
            'status' => false,
            'message' => 'Authenticated user not found.'
        ], 401);
    }

    try {
        DB::beginTransaction();

        // Insert into montessoriactivity
        $activity = MontessoriActivity::create([
            'idSubject' => $validated['idSubject'],
            'title'     => $validated['title'],
            'added_by'  => $userId,
            'added_at'  => now(),
        ]);

        // dd($activity);

        // Insert into montessoriactivityaccess
        Montessoriactivityaccess::create([
            'idActivity' => $activity->idActivity,
            'centerid'   => $validated['center_id'],
            'added_by'   => $userId,
            'added_at'   => now(),
        ]);

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Activity added successfully'
            , 'data' => $activity
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

    public function updateActivity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idActivity' => 'required|integer|exists:montessoriactivity,idActivity',
            'title'      => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $activity = MontessoriActivity::find($request->idActivity);

        if (!$activity) {
            return response()->json([
                'status' => false,
                'message' => 'Activity not found.',
            ], 404);
        }

        $activity->title = $request->title;
        $activity->save();

        return response()->json([
            'status' => true,
            'message' => 'Activity updated successfully.',
            'data' => $activity,
        ]);
    }

    public function updateSubActivity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idSubActivity' => 'required|integer|exists:montessorisubactivity,idSubActivity',
            'title'         => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $subActivity = MontessoriSubActivity::find($request->idSubActivity);

        if (!$subActivity) {
            return response()->json([
                'status' => false,
                'message' => 'Sub-activity not found.',
            ], 404);
        }

        $subActivity->title = $request->title;
        $subActivity->save();

        return response()->json([
            'status' => true,
            'message' => 'Sub-activity updated successfully.',
            'data' => $subActivity,
        ]);
    }

    public function deleteActivity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idActivity' => 'required|integer|exists:montessoriactivity,idActivity',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $activity = MontessoriActivity::find($request->idActivity);

        if (!$activity) {
            return response()->json([
                'status' => false,
                'message' => 'Activity not found.',
            ], 404);
        }

        try {
            DB::beginTransaction();

            $subActivityIds = MontessoriSubActivity::where('idActivity', $activity->idActivity)
                ->pluck('idSubActivity');

            if ($subActivityIds->isNotEmpty()) {
                MontessoriSubActivityAccess::whereIn('idSubActivity', $subActivityIds)->delete();
                MontessoriSubActivity::whereIn('idSubActivity', $subActivityIds)->delete();
            }

            Montessoriactivityaccess::where('idActivity', $activity->idActivity)->delete();
            $activity->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Activity and related sub-activities deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteSubActivity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idSubActivity' => 'required|integer|exists:montessorisubactivity,idSubActivity',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $subActivity = MontessoriSubActivity::find($request->idSubActivity);

        if (!$subActivity) {
            return response()->json([
                'status' => false,
                'message' => 'Sub-activity not found.',
            ], 404);
        }

        try {
            DB::beginTransaction();

            MontessoriSubActivityAccess::where('idSubActivity', $subActivity->idSubActivity)->delete();
            $subActivity->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Sub-activity deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
