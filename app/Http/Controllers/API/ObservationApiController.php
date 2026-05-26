<?php

namespace App\Http\Controllers\API;

use App\Models\DevMilestone;
use App\Models\DevMilestoneMain;
use App\Models\DevMilestoneSub;
use App\Models\Observation;
use App\Models\ObservationLink;
use App\Models\Reflection;
use App\Models\ProgramPlanTemplateDetailsAdd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ObservationApiController extends ObservationsController
{
    private ProgramPlanApiController $programPlanApiController;

    public function __construct(ProgramPlanApiController $programPlanApiController)
    {
        $this->programPlanApiController = $programPlanApiController;
    }

    public function formData(Request $request, $id = null, $activeTab = 'observation', $activesubTab = 'MONTESSORI')
    {
        return $this->storepage($id, $activeTab, $activesubTab);
    }

    public function storeObservation(Request $request)
    {
        return $this->store($request);
    }

    public function show($id)
    {
        return $this->view($id);
    }

    public function saveMontessoriData(Request $request)
    {
        return $this->storeMontessoriData($request);
    }

    public function getSubjects(Request $request)
    {
        return $this->programPlanApiController->subjects($request);
    }

    public function getActivitiesBySubject(Request $request)
    {
        return $this->programPlanApiController->modules($request);
    }

    public function getSubActivitiesByActivity(Request $request)
    {
        return $this->programPlanApiController->subModules($request);
    }

    public function getDevelopmentSubjects(Request $request)
    {
        $milestones = DevMilestone::select('id', 'ageGroup')
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Developmental milestone age groups fetched successfully.',
            'data' => $milestones,
        ]);
    }

    public function getDevelopmentModules(Request $request)
    {
        $request->validate([
            'age_id' => 'required|integer|exists:devmilestone,id',
        ]);

        $modules = DevMilestoneMain::where('ageId', $request->age_id)
            ->select('id', 'ageId as age_id', 'name')
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Developmental milestone modules fetched successfully.',
            'data' => $modules,
        ]);
    }

    public function getDevelopmentSubModules(Request $request)
    {
        $request->validate([
            'milestone_id' => 'required|integer|exists:devmilestonemain,id',
        ]);

        $subModules = DevMilestoneSub::where('milestoneid', $request->milestone_id)
            ->select('id', 'milestoneid as module_id', 'name')
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Developmental milestone submodules fetched successfully.',
            'data' => $subModules,
        ]);
    }

    public function saveEylfData(Request $request)
    {
        return $this->storeEylfData($request);
    }

    public function saveDevelopmentMilestone(Request $request)
    {
        return $this->storeDevMilestone($request);
    }

    public function linkObservationData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'obsId' => 'required|integer|exists:observation,id',
        ], [
            'obsId.required' => 'Observation ID is required.',
            'obsId.integer' => 'Observation ID must be an integer.',
            'obsId.exists' => 'Observation not found.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $obsId = (int) $request->input('obsId');
        $linkedIds = ObservationLink::where('observationId', $obsId)
            ->where('linktype', 'OBSERVATION')
            ->get(['id', 'observationId', 'linkid', 'linktype'])
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'observationId' => $link->observationId,
                    'ObservationId' => $link->linkid,
                    'linktype' => $link->linktype,
                ];
            })
            ->values();

        // Fetch the linked observations and include media and creator
        $obsIds = $linkedIds->pluck('ObservationId')->unique()->values()->all();
        $observations = [];
        if (!empty($obsIds)) {
            $observations = Observation::whereIn('id', $obsIds)
                ->with(['media', 'user'])
                ->get()
                ->map(function ($o) {
                    return [
                        'id' => $o->id,
                        'title' => $o->title ?? $o->obestitle ?? null,
                        'created_by' => $o->user?->name ?? null,
                        'created_by_id' => $o->user?->id ?? null,
                        'media' => $o->media->map(function ($m) {
                            return [
                                'id' => $m->id,
                                'mediaUrl' => $m->mediaUrl,
                                'mediaType' => $m->mediaType,
                            ];
                        })->values(),
                    ];
                })->values();
        }

        return response()->json([
            'status' => true,
            'linked_ids' => $linkedIds,
            'observations' => $observations,
        ]);
    }

    public function storeLinkedObservation(Request $request)
    {
        return $this->storelinkobservation($request);
    }

    public function linkReflectionData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'obsId' => 'required|integer|exists:observation,id',
        ], [
            'obsId.required' => 'Observation ID is required.',
            'obsId.integer' => 'Observation ID must be an integer.',
            'obsId.exists' => 'Observation not found.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $obsId = (int) $request->input('obsId');
        $linkedIds = ObservationLink::where('observationId', $obsId)
            ->where('linktype', 'REFLECTION')
            ->get(['id', 'observationId', 'linkid', 'linktype'])
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'observationId' => $link->observationId,
                    'ReflectionId' => $link->linkid,
                    'linktype' => $link->linktype,
                ];
            })
            ->values();

        // Fetch the linked reflections and include media and creator
        $reflectionIds = $linkedIds->pluck('ReflectionId')->unique()->values()->all();
        $reflections = [];
        if (!empty($reflectionIds)) {
            $reflections = Reflection::whereIn('id', $reflectionIds)
                ->with(['media', 'creator'])
                ->get()
                ->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'title' => $r->title ?? null,
                        'created_by' => $r->creator?->name ?? null,
                        'created_by_id' => $r->creator?->id ?? null,
                        'media' => $r->media->map(function ($m) {
                            return [
                                'id' => $m->id,
                                'mediaUrl' => $m->mediaUrl,
                                'mediaType' => $m->mediaType ?? null,
                            ];
                        })->values(),
                    ];
                })->values();
        }

        return response()->json([
            'status' => true,
            'linked_ids' => $linkedIds,
            'reflections' => $reflections,
        ]);
    }

    public function storeLinkedReflection(Request $request)
    {
        return $this->storelinkreflection($request);
    }

    public function linkProgramPlanData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'obsId' => 'required|integer|exists:observation,id',
        ], [
            'obsId.required' => 'Observation ID is required.',
            'obsId.integer' => 'Observation ID must be an integer.',
            'obsId.exists' => 'Observation not found.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $obsId = (int) $request->input('obsId');
        $linkedIds = ObservationLink::where('observationId', $obsId)
            ->where('linktype', 'PROGRAMPLAN')
            ->get(['id', 'observationId', 'linkid', 'linktype'])
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'observationId' => $link->observationId,
                    'ProgramPlanId' => $link->linkid,
                    'linktype' => $link->linktype,
                ];
            })
            ->values();

        $planIds = $linkedIds->pluck('ProgramPlanId')->unique()->values()->all();
        $programPlans = [];
        if (!empty($planIds)) {
            $programPlans = ProgramPlanTemplateDetailsAdd::whereIn('id', $planIds)
                ->with(['room', 'creator'])
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'month_name' => $this->getMonthName((int) $p->months),
                        'room_name' => $p->room?->name ?? null,
                        'created_by' => $p->creator?->name ?? null,
                        'created_by_id' => $p->creator?->id ?? null,
                    ];
                })->values();
        }

        return response()->json([
            'status' => true,
            'linked_ids' => $linkedIds,
            'program_plans' => $programPlans,
        ]);
    }

    private function getMonthName(int $monthNumber)
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
        ];

        return $months[$monthNumber] ?? null;
    }

    public function storeLinkedProgramPlan(Request $request)
    {
        return $this->storelinkprogramplan($request);
    }

    public function updateObservationStatus(Request $request)
    {
        return $this->updateStatus($request);
    }
}
