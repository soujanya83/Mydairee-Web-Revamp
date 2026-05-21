<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EYLFActivity;
use App\Models\EYLFOutcome;
use App\Models\EYLFSubActivity;
use App\Models\MontessoriActivity;
use App\Models\MontessoriSubject;
use App\Models\MontessoriSubActivity;
use App\Models\ProgramPlanTemplateDetailsAdd;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProgramPlanApiController extends Controller
{
    public function subjects(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'framework' => 'required|in:montessori,eylf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $framework = $request->input('framework');

        if ($framework === 'montessori') {
            $subjects = MontessoriSubject::select('idSubject as id', 'name')
                ->orderBy('idSubject')
                ->get();
        } else {
            $subjects = EYLFOutcome::select('id', 'title')
                ->orderBy('title')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->title,
                    ];
                })
                ->values();
        }

        return response()->json([
            'status' => true,
            'message' => 'Subjects fetched successfully.',
            'data' => $subjects,
        ]);
    }

    public function modules(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'framework' => 'required|in:montessori,eylf',
            'subject_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $framework = $request->input('framework');
        $subjectId = (int) $request->input('subject_id');

        if ($framework === 'montessori') {
            $modules = MontessoriActivity::where('idSubject', $subjectId)
                ->select('idActivity as id', 'idSubject as subject_id', 'title')
                ->orderBy('idActivity')
                ->get();
        } else {
            $modules = EYLFActivity::where('outcomeId', $subjectId)
                ->select('id', 'outcomeId as subject_id', 'title')
                ->orderBy('id')
                ->get();
        }

        return response()->json([
            'status' => true,
            'message' => 'Modules fetched successfully.',
            'data' => $modules,
        ]);
    }

    public function subModules(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'framework' => 'required|in:montessori,eylf',
            'module_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $framework = $request->input('framework');
        $moduleId = (int) $request->input('module_id');

        if ($framework === 'montessori') {
            $subModules = MontessoriSubActivity::where('idActivity', $moduleId)
                ->select('idSubActivity as id', 'idActivity as module_id', 'title')
                ->orderBy('idSubActivity')
                ->get();
        } else {
            $subModules = EYLFSubActivity::where('activityid', $moduleId)
                ->select('id', 'activityid as module_id', 'title')
                ->orderBy('id')
                ->get();
        }

        return response()->json([
            'status' => true,
            'message' => 'Submodules fetched successfully.',
            'data' => $subModules,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|integer',
            'months' => 'required|string',
            'users' => 'required|array',
            'children' => 'required|array',
            'centerid' => 'required',
        ], [
            'centerid.required' => 'Center ID is required',
            'room_id.required' => 'Room ID is required.',
            'room_id.integer' => 'Room ID must be an integer.',
            'months.required' => 'Month is required.',
            'months.string' => 'Month must be a string.',
            'users.required' => 'At least one user is required.',
            'users.array' => 'Users must be an array.',
            'children.required' => 'At least one child is required.',
            'children.array' => 'Children must be an array.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $programData = [
            'room_id' => $validated['room_id'],
            'months' => $validated['months'],
            'years' => $request->input('years'),
            'centerid' => $validated['centerid'],
            'created_by' => Auth::user()->userid,
            'educators' => implode(',', $validated['users']),
            'children' => implode(',', $validated['children']),
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
            'working' => $request->input('working'),
            'notworking' => $request->input('notworking'),
        ];

        $planId = $request->input('plan_id');

        if ($planId) {
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
                'message' => 'Error updating program plan. Please try again.',
            ]);
        }

        $programData['created_at'] = Carbon::now('Australia/Sydney');
        $newPlan = ProgramPlanTemplateDetailsAdd::create($programData);

        if ($newPlan) {
            return response()->json([
                'status' => true,
                'message' => 'Program plan created successfully',
                'plan_id' => $newPlan->id,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Error creating program plan. Please try again.',
        ]);
    }
    
}
