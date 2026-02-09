<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Childparent;
use App\Models\Child;
use App\Models\ObservationChild;
use App\Models\ReflectionChild;
use App\Models\SnapshotChild;
use App\Models\ObservationMedia;
use App\Models\ReflectionMedia;
use App\Models\SnapshotMedia;

class ParentSlideshowController extends Controller
{
	public function getSlideshowData(Request $request)
	{
		$parentId = $request->user()->id;
		$children = Childparent::where('parentid', $parentId)->pluck('childid');
		$result = [];
		foreach ($children as $childId) {
			$child = Child::find($childId);
			$lastObservationChild = ObservationChild::where('childId', $childId)->latest('id')->first();
			$lastReflectionChild = ReflectionChild::where('childid', $childId)->latest('id')->first();
			$lastSnapshotChild = SnapshotChild::where('childid', $childId)->latest('id')->first();

			$lastObservation = $lastObservationChild ? $lastObservationChild->observation : null;
			$lastReflection = $lastReflectionChild ? $lastReflectionChild->reflection : null;
			$lastSnapshot = $lastSnapshotChild ? $lastSnapshotChild->snapshot : null;

			$firstObservationImage = $lastObservation ? ObservationMedia::where('observationId', $lastObservation->id)->orderBy('id')->first() : null;
			$firstReflectionImage = $lastReflection ? ReflectionMedia::where('reflectionid', $lastReflection->id)->orderBy('id')->first() : null;
			$firstSnapshotImage = $lastSnapshot ? SnapshotMedia::where('snapshotid', $lastSnapshot->id)->orderBy('id')->first() : null;

			$result[] = [
				'child' => $child,
				'last_observation' => [
					'data' => $lastObservation,
					'first_image' => $firstObservationImage ? $firstObservationImage->mediaUrl : null,
				],
				'last_reflection' => [
					'data' => $lastReflection,
					'first_image' => $firstReflectionImage ? $firstReflectionImage->mediaUrl : null,
				],
				'last_snapshot' => [
					'data' => $lastSnapshot,
					'first_image' => $firstSnapshotImage ? $firstSnapshotImage->mediaUrl : null,
				],
			];
		}
		return response()->json($result);
	}
}
