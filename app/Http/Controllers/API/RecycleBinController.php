<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgramPlanTemplateDetailsAdd;
use App\Models\Observation;
use App\Models\Room;
use App\Models\Reflection;
use App\Models\Snapshot;
use App\Services\RecycleBinService;
use Illuminate\Support\Facades\Auth;

class RecycleBinController extends Controller
{
    public function modules(Request $request)
    {
        $centerId = $this->resolveCenterId($request);

        return response()->json([
            'success' => true,
            'data' => [
                'program_plans' => ProgramPlanTemplateDetailsAdd::onlyTrashed()->where('centerid', $centerId)->count(),
                'observations' => Observation::onlyTrashed()->where('centerid', $centerId)->count(),
                'reflections' => Reflection::onlyTrashed()->where('centerid', $centerId)->count(),
                'snapshots' => Snapshot::onlyTrashed()->where('centerid', $centerId)->count(),
            ],
        ]);
    }

    public function programPlans(Request $request)
    {
        $data = $this->formatProgramPlans($request);
        return response()->json([
            'success' => true,
            'module' => 'program-plan',
            'data' => $data,
            'message' => $data->isEmpty() ? 'There are no deleted program plans from the last 7 days.' : null,
        ]);
    }

    public function observations(Request $request)
    {
        $data = $this->formatObservations($request);
        return response()->json([
            'success' => true,
            'module' => 'observation',
            'data' => $data,
            'message' => $data->isEmpty() ? 'There are no deleted observations from the last 7 days.' : null,
        ]);
    }

    public function reflections(Request $request)
    {
        $data = $this->formatReflections($request);
        return response()->json([
            'success' => true,
            'module' => 'reflection',
            'data' => $data,
            'message' => $data->isEmpty() ? 'There are no deleted reflections from the last 7 days.' : null,
        ]);
    }

    public function snapshots(Request $request)
    {
        $data = $this->formatSnapshots($request);
        return response()->json([
            'success' => true,
            'module' => 'snapshot',
            'data' => $data,
            'message' => $data->isEmpty() ? 'There are no deleted snapshots from the last 7 days.' : null,
        ]);
    }

    public function restoreProgramPlan($id)
    {
        $plan = $this->resolveTrashedModel(ProgramPlanTemplateDetailsAdd::class, $id);
        if ($plan !== true) {
            return $plan;
        }

        $plan = ProgramPlanTemplateDetailsAdd::onlyTrashed()->findOrFail($id);
        $plan->restore();
        $plan->status = 'Draft';
        $plan->save();

        return response()->json(['success' => true, 'message' => 'Program plan restored.']);
    }

    public function forceDeleteProgramPlan($id)
    {
        $plan = $this->resolveTrashedModel(ProgramPlanTemplateDetailsAdd::class, $id);
        if ($plan !== true) {
            return $plan;
        }

        $plan = ProgramPlanTemplateDetailsAdd::onlyTrashed()->findOrFail($id);
        $plan->forceDelete();

        return response()->json(['success' => true, 'message' => 'Program plan permanently deleted.']);
    }

    public function restoreObservation($id)
    {
        $observation = $this->resolveTrashedModel(Observation::class, $id);
        if ($observation !== true) {
            return $observation;
        }

        $observation = Observation::onlyTrashed()->findOrFail($id);
        $observation->restore();
        $observation->status = 'Draft';
        $observation->save();

        return response()->json(['success' => true, 'message' => 'Observation restored.']);
    }

    public function forceDeleteObservation($id, RecycleBinService $service)
    {
        $observation = $this->resolveTrashedModel(Observation::class, $id);
        if ($observation !== true) {
            return $observation;
        }

        $observation = Observation::onlyTrashed()->findOrFail($id);
        $service->forceDeleteObservation($observation);

        return response()->json(['success' => true, 'message' => 'Observation permanently deleted.']);
    }

    public function restoreReflection($id)
    {
        $reflection = $this->resolveTrashedModel(Reflection::class, $id);
        if ($reflection !== true) {
            return $reflection;
        }

        $reflection = Reflection::onlyTrashed()->findOrFail($id);
        $reflection->restore();
        $reflection->status = 'Draft';
        $reflection->save();

        return response()->json(['success' => true, 'message' => 'Reflection restored.']);
    }

    public function forceDeleteReflection($id)
    {
        $reflection = $this->resolveTrashedModel(Reflection::class, $id);
        if ($reflection !== true) {
            return $reflection;
        }

        $reflection = Reflection::onlyTrashed()->findOrFail($id);
        $reflection->forceDelete();

        return response()->json(['success' => true, 'message' => 'Reflection permanently deleted.']);
    }

    public function restoreSnapshot($id)
    {
        $snapshot = $this->resolveTrashedModel(Snapshot::class, $id);
        if ($snapshot !== true) {
            return $snapshot;
        }

        $snapshot = Snapshot::onlyTrashed()->findOrFail($id);
        $snapshot->restore();
        $snapshot->status = 'Draft';
        $snapshot->save();

        return response()->json(['success' => true, 'message' => 'Snapshot restored.']);
    }

    public function forceDeleteSnapshot($id)
    {
        $snapshot = $this->resolveTrashedModel(Snapshot::class, $id);
        if ($snapshot !== true) {
            return $snapshot;
        }

        $snapshot = Snapshot::onlyTrashed()->findOrFail($id);
        $snapshot->forceDelete();

        return response()->json(['success' => true, 'message' => 'Snapshot permanently deleted.']);
    }

    private function resolveCenterId(Request $request): int|string|null
    {
        return $request->input('centerid')
            ?? $request->input('center_id')
            ?? Auth::user()?->centerid
            ?? Auth::user()?->userid;
    }

    private function formatProgramPlans(Request $request)
    {
        $centerId = $this->resolveCenterId($request);
        $query = ProgramPlanTemplateDetailsAdd::onlyTrashed()
            ->with(['creator:id,name', 'room:id,name', 'deletedByUser:id,name'])
            ->where('centerid', $centerId)
            ->orderByDesc('deleted_at');

        return $query->get()->map(function ($item) {
            $roomNames = [];
            if (!empty($item->room_id)) {
                $roomIds = array_filter(array_map('trim', explode(',', $item->room_id)));
                $roomNames = Room::whereIn('id', $roomIds)->pluck('name')->values()->all();
            }

            return [
                'id' => $item->id,
                'module' => 'program-plan',
                'title' => trim(($item->months ?? '') . ' ' . ($item->years ?? '')) ?: 'Program Plan',
                'status' => $item->status ?? 'Draft',
                'deleted_at' => optional($item->deleted_at)->format('Y-m-d H:i:s'),
                'deleted_by' => $item->deletedByUser?->name,
                'creator' => $item->creator?->name,
                'rooms' => $roomNames,
            ];
        })->values();
    }

    private function formatObservations(Request $request)
    {
        $centerId = $this->resolveCenterId($request);
        $query = Observation::onlyTrashed()
            ->with(['user:id,name', 'child.child:id,name,lastname', 'media:id,observationId,mediaUrl', 'deletedByUser:id,name'])
            ->where('centerid', $centerId)
            ->orderByDesc('deleted_at');

        return $query->get()->map(function ($item) {
            $firstChild = $item->child->first()?->child;

            return [
                'id' => $item->id,
                'module' => 'observation',
                'title' => $item->title ?? $item->obestitle ?? 'Observation',
                'status' => $item->status ?? 'Draft',
                'deleted_at' => optional($item->deleted_at)->format('Y-m-d H:i:s'),
                'deleted_by' => $item->deletedByUser?->name,
                'creator' => $item->user?->name,
                'child' => $firstChild ? trim(($firstChild->name ?? '') . ' ' . ($firstChild->lastname ?? '')) : null,
                'media_count' => $item->media->count(),
            ];
        })->values();
    }

    private function formatReflections(Request $request)
    {
        $centerId = $this->resolveCenterId($request);
        $query = Reflection::onlyTrashed()
            ->with(['creator:id,name', 'children.child', 'media', 'staff.staff', 'Seen.user', 'deletedByUser:id,name'])
            ->where('centerid', $centerId)
            ->orderByDesc('deleted_at');

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'module' => 'reflection',
                'title' => $item->title ?? 'Reflection',
                'status' => $item->status ?? 'Draft',
                'deleted_at' => optional($item->deleted_at)->format('Y-m-d H:i:s'),
                'deleted_by' => $item->deletedByUser?->name,
                'creator' => $item->creator?->name,
                'children_count' => $item->children->count(),
                'media_count' => $item->media->count(),
            ];
        })->values();
    }

    private function formatSnapshots(Request $request)
    {
        $centerId = $this->resolveCenterId($request);
        $query = Snapshot::onlyTrashed()
            ->with(['creator:id,name', 'children.child', 'media', 'deletedByUser:id,name'])
            ->where('centerid', $centerId)
            ->orderByDesc('deleted_at');

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'module' => 'snapshot',
                'title' => $item->title ?? 'Snapshot',
                'status' => $item->status ?? 'Draft',
                'deleted_at' => optional($item->deleted_at)->format('Y-m-d H:i:s'),
                'deleted_by' => $item->deletedByUser?->name,
                'creator' => $item->creator?->name,
                'children_count' => $item->children->count(),
                'media_count' => $item->media->count(),
            ];
        })->values();
    }

    private function resolveTrashedModel(string $modelClass, int|string $id)
    {
        $record = $modelClass::withTrashed()->find($id);

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => 'Can\'t find this id.',
            ], 404);
        }

        if (! $record->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'This item is already restored.',
            ], 409);
        }

        return true;
    }
}
