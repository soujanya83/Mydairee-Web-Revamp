<?php

namespace App\Services;

use App\Models\Observation;
use App\Models\ObservationChild;
use App\Models\ObservationComment;
use App\Models\ObservationDevMilestoneSub;
use App\Models\ObservationEYLF;
use App\Models\ObservationLink;
use App\Models\ObservationMedia;
use App\Models\ObservationMontessori;
use App\Models\ObservationStaff;
use App\Models\ProgramPlanTemplateDetailsAdd;
use App\Models\Reflection;
use App\Models\Snapshot;
use App\Models\SeenObservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class RecycleBinService
{
    public function purgeExpiredItems(): array
    {
        $cutoff = Carbon::now()->subDays(7);

        $programPlans = ProgramPlanTemplateDetailsAdd::onlyTrashed()
            ->where('deleted_at', '<=', $cutoff)
            ->get();

        foreach ($programPlans as $plan) {
            $plan->forceDelete();
        }

        $observations = Observation::onlyTrashed()
            ->where('deleted_at', '<=', $cutoff)
            ->get();

        foreach ($observations as $observation) {
            $this->forceDeleteObservation($observation);
        }

        $reflections = Reflection::onlyTrashed()
            ->where('deleted_at', '<=', $cutoff)
            ->get();

        foreach ($reflections as $reflection) {
            $reflection->forceDelete();
        }

        $snapshots = Snapshot::onlyTrashed()
            ->where('deleted_at', '<=', $cutoff)
            ->get();

        foreach ($snapshots as $snapshot) {
            $snapshot->forceDelete();
        }

        return [
            'program_plans' => $programPlans->count(),
            'observations' => $observations->count(),
            'reflections' => $reflections->count(),
            'snapshots' => $snapshots->count(),
        ];
    }

    public function forceDeleteObservation(Observation $observation): void
    {
        $mediaItems = ObservationMedia::where('observationId', $observation->id)->get();

        foreach ($mediaItems as $media) {
            if (!empty($media->mediaUrl) && File::exists(public_path($media->mediaUrl))) {
                File::delete(public_path($media->mediaUrl));
            }
        }

        ObservationChild::where('observationId', $observation->id)->delete();
        ObservationComment::where('observationId', $observation->id)->delete();
        ObservationDevMilestoneSub::where('observationId', $observation->id)->delete();
        ObservationEYLF::where('observationId', $observation->id)->delete();
        ObservationLink::where('observationId', $observation->id)->delete();
        ObservationMedia::where('observationId', $observation->id)->delete();
        ObservationMontessori::where('observationId', $observation->id)->delete();
        ObservationStaff::where('observationId', $observation->id)->delete();
        SeenObservation::where('observation_id', $observation->id)->delete();

        $observation->forceDelete();
    }
}