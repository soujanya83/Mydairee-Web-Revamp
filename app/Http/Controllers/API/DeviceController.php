<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\UserDevice;
use Illuminate\Http\JsonResponse;
use App\Services\Firebase\FirebaseNotificationService;

class DeviceController extends Controller
{
    public function saveToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'required|string|in:android,ios',
        ]);

        try {
            $user = $request->user();

            DB::beginTransaction();

            $device = UserDevice::where('fcm_token', $validated['fcm_token'])->first();

            if ($device) {
                $device->update([
                    'device_type' => $validated['device_type'],
                    'last_used_at' => Carbon::now(),
                ]);
            } else {
                UserDevice::create([
                    'user_id' => $user->id,
                    'fcm_token' => $validated['fcm_token'],
                    'device_type' => $validated['device_type'],
                    'last_used_at' => Carbon::now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'FCM token saved successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error saving FCM token: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save FCM token.'
            ], 500);
        }
    }


    public static function notifyParentsModuleCreated(
        array $childIds,
        string $moduleType,
        int $moduleId,
        $createdBy,
        FirebaseNotificationService $service,
        $section = null,
        array $data = []
    ) {
        $titles = [
            'observation' => 'New Observation Added',
            'reflection'  => 'New Reflection Added',
            'snapshot'    => 'New Snapshot Added',
            'diary'       => 'New Daily Diary Entry',
        ];

        $bodies = [
            'observation' => 'A new observation has been added for your child.',
            'reflection'  => 'A new reflection has been added for your child.',
            'snapshot'    => 'A new snapshot has been added for your child.',
            'diary'       => 'A new daily diary entry has been added for your child.',
        ];

        $title = $titles[$moduleType] ?? 'New Update';
        $body  = $bodies[$moduleType] ?? 'A new update has been added for your child.';

        $parentUsers = \App\Models\User::whereHas('children', function ($q) use ($childIds) {
                $q->whereIn('childparent.childid', $childIds);
            })
            ->where('allow_notifications', true)
            ->get();

        $notified = [];

        foreach ($parentUsers as $parent) {

            $devices = \App\Models\UserDevice::where('user_id', $parent->id)
                ->whereNotNull('fcm_token')
                ->where('fcm_token', '!=', '')
                ->where('fcm_token', 'not like', 'test%')
                ->get();

            foreach ($devices as $device) {

                // ✅ If no data passed, build minimal fallback
                if (empty($data)) {
                    $data = [
                        'type' => (string)$moduleType,
                        'module_id' => (string)$moduleId,
                        'child_ids' => implode(',', $childIds),
                        'created_by' => (string)$createdBy,
                    ];
                }

                if ($section) {
                    $data['section'] = $section;
                }

                // Optional debug log
                // Log::info('Sending FCM to parent', [
                //     'parent_id' => $parent->id,
                //     'token' => $device->fcm_token,
                //     'data' => $data
                // ]);

                $response = $service->sendToToken(
                    $device->fcm_token,
                    $title,
                    $body,
                    $data
                );

                $notified[] = [
                    'parent_id' => $parent->id,
                    'token' => $device->fcm_token,
                    'response' => $response->getData(),
                    'deeplink_data' => $data,
                ];
            }
        }

        return $notified;
    }
}