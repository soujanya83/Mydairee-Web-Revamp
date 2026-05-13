<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Observation;
use App\Models\ProgramPlanTemplateDetailsAdd;
use App\Models\Reflection;
use App\Models\Snapshot;
use App\Models\Usercenter;
use App\Services\RecycleBinService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RecycleBinController extends Controller
{
    public function index()
    {
        // Show modules overview (extensible list of modules)
        $user = Auth::user();
        $centerId = Session::get('user_center_id');

        $modules = [
            [
                'key' => 'program-plan',
                'label' => 'Program Plans',
                'icon' => 'far fa-clipboard',
                'count' => ProgramPlanTemplateDetailsAdd::onlyTrashed()->where('centerid', $centerId)->count(),
            ],
            [
                'key' => 'observation',
                'label' => 'Observations',
                'icon' => 'icon-equalizer',
                'count' => Observation::onlyTrashed()->where('centerid', $centerId)->count(),
            ],
            [
                'key' => 'reflection',
                'label' => 'Reflections',
                'icon' => 'fa-solid fa-rotate-left',
                'count' => Reflection::onlyTrashed()->where('centerid', $centerId)->count(),
            ],
            [
                'key' => 'snapshot',
                'label' => 'Snapshots',
                'icon' => 'fa-solid fa-camera-retro',
                'count' => Snapshot::onlyTrashed()->where('centerid', $centerId)->count(),
            ],
        ];

        return view('recycle-bin.modules', compact('modules'));
    }

    public function moduleItems($module)
    {
        $centerId = Session::get('user_center_id');
        $user = Auth::user();

        if ($module === 'program-plan') {
            $query = ProgramPlanTemplateDetailsAdd::onlyTrashed()->with(['creator:id,name', 'room:id,name', 'deletedByUser:id,name'])->where('centerid', $centerId)->orderByDesc('deleted_at');
            if ($user->userType === 'Staff') {
                $query->where(function ($q) use ($user) {
                    $q->where('created_by', $user->id)
                        ->orWhereRaw('FIND_IN_SET(?, educators)', [$user->id]);
                });
            }
            $items = $query->get();
            $viewData = ['module' => 'program-plan', 'items' => $items];
            return request()->boolean('embed') ? view('recycle-bin.partials.items', $viewData) : view('recycle-bin.items', $viewData);
        }

        if ($module === 'observation') {
            $query = Observation::onlyTrashed()->with(['user', 'child', 'media', 'deletedByUser:id,name'])->where('centerid', $centerId)->orderByDesc('deleted_at');
            if ($user->userType === 'Staff') {
                $query->where('userId', $user->userid);
            }
            $items = $query->get();
            $viewData = ['module' => 'observation', 'items' => $items];
            return request()->boolean('embed') ? view('recycle-bin.partials.items', $viewData) : view('recycle-bin.items', $viewData);
        }

        if ($module === 'reflection') {
            $query = Reflection::onlyTrashed()->with(['creator', 'children.child', 'media', 'staff.staff', 'Seen.user', 'deletedByUser:id,name'])
                ->where('centerid', $centerId)
                ->orderByDesc('deleted_at');

            if ($user->userType === 'Staff') {
                $query->where(function ($q) use ($user) {
                    $q->where('createdBy', $user->id)
                        ->orWhereRaw('FIND_IN_SET(?, tagged_staff)', [$user->id]);
                });
            }

            $items = $query->get();
            $viewData = ['module' => 'reflection', 'items' => $items];
            return request()->boolean('embed') ? view('recycle-bin.partials.items', $viewData) : view('recycle-bin.items', $viewData);
        }

        if ($module === 'snapshot') {
            $query = Snapshot::onlyTrashed()->with(['creator', 'children.child', 'media', 'deletedByUser:id,name'])
                ->where('centerid', $centerId)
                ->orderByDesc('deleted_at');

            if ($user->userType === 'Staff') {
                $query->where('createdBy', $user->id);
            }

            $items = $query->get();
            $viewData = ['module' => 'snapshot', 'items' => $items];
            return request()->boolean('embed') ? view('recycle-bin.partials.items', $viewData) : view('recycle-bin.items', $viewData);
        }

        abort(404);
    }

    public function restoreProgramPlan($id)
    {
        $plan = ProgramPlanTemplateDetailsAdd::onlyTrashed()->findOrFail($id);
        $plan->restore();
        $plan->status = 'Draft';
        $plan->save();

        return redirect()->route('recycle-bin.index')->with('message', 'Program plan restored successfully.');
    }

    public function forceDeleteProgramPlan($id)
    {
        $plan = ProgramPlanTemplateDetailsAdd::onlyTrashed()->findOrFail($id);
        $plan->forceDelete();

        return redirect()->route('recycle-bin.index')->with('message', 'Program plan deleted permanently.');
    }

    public function restoreObservation($id)
    {
        $observation = Observation::onlyTrashed()->findOrFail($id);
        $observation->restore();
        $observation->status = 'Draft';
        $observation->save();

        return redirect()->route('recycle-bin.index')->with('message', 'Observation restored successfully.');
    }

    public function forceDeleteObservation($id, RecycleBinService $recycleBinService)
    {
        $observation = Observation::onlyTrashed()->findOrFail($id);
        $recycleBinService->forceDeleteObservation($observation);

        return redirect()->route('recycle-bin.index')->with('message', 'Observation deleted permanently.');
    }

    public function restoreReflection($id)
    {
        $reflection = Reflection::onlyTrashed()->findOrFail($id);
        $reflection->restore();
        $reflection->status = 'Draft';
        $reflection->save();

        return redirect()->route('recycle-bin.index')->with('message', 'Reflection restored successfully.');
    }

    public function forceDeleteReflection($id)
    {
        $reflection = Reflection::onlyTrashed()->findOrFail($id);
        $reflection->forceDelete();

        return redirect()->route('recycle-bin.index')->with('message', 'Reflection deleted permanently.');
    }

    public function restoreSnapshot($id)
    {
        $snapshot = Snapshot::onlyTrashed()->findOrFail($id);
        $snapshot->restore();
        $snapshot->status = 'Draft';
        $snapshot->save();

        return redirect()->route('recycle-bin.index')->with('message', 'Snapshot restored successfully.');
    }

    public function forceDeleteSnapshot($id)
    {
        $snapshot = Snapshot::onlyTrashed()->findOrFail($id);
        $snapshot->forceDelete();

        return redirect()->route('recycle-bin.index')->with('message', 'Snapshot deleted permanently.');
    }
}