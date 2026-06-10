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
use App\Models\User;
use App\Notifications\GenericModuleNotification;

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
                array $data = [],
                array $centerUserIds  = []) 
    {

          $allowedModules = ['announcement', 'event'];

            if (!in_array($moduleType, $allowedModules)) {
                return [];
            }    
        $titles = [
            // 'observation' => 'New Observation Added',
            // 'reflection'  => 'New Reflection Added',
            // 'snapshot'    => 'New Snapshot Added',
            // 'diary'       => 'New Daily Diary Entry',
            'announcement' => 'New Announcement Added',
            'event' => 'New Event Added',
        ];

        $bodies = [
            // 'observation' => 'A new observation has been added for your child.',
            // 'reflection'  => 'A new reflection has been added for your child.',
            // 'snapshot'    => 'A new snapshot has been added for your child.',
            // 'diary'       => 'A new daily diary entry has been added for your child.',
            'announcement' => 'A new announcement has been added for your child.',
            'event' => 'A new event has been added for your child.',
        ];

        $title = $titles[$moduleType] ?? 'New Update';
        $body  = $bodies[$moduleType] ?? 'A new update has been added for your child.';

        $normalizedChildIds = array_values(array_filter(array_map(function ($childId) {
            return trim((string)$childId);
        }, $childIds), fn ($value) => $value !== ''));

        $normalizedUserIds = array_values(
        array_filter(
                    array_map(function ($userId) {
                        return (int) trim((string) $userId);
                    }, $centerUserIds ?? []),
                    fn ($value) => $value > 0
                )
            );

        Log::info('NOTIFY_FUNCTION_CALLED', [
            'module' => $moduleType,
            'module_id' => $moduleId,
            'created_by' => $createdBy,
            'section' => $section,
            'child_ids' => $normalizedChildIds,
            'user_ids' => $normalizedUserIds,
        ]);

        if (empty($normalizedChildIds) && empty($normalizedUserIds)) {
            Log::info('NOTIFY_FUNCTION_ABORTED', ['reason' => 'no_recipients', 'module' => $moduleType]);
            return [];
        }

        $notified = [];

        // Fetch creator name once for use in notifications
        $creator = User::where('userid', $createdBy)->first();
        $creatorName = $creator ? $creator->name : 'A colleague';

        // ✅ Notify Parents: PUSH (FCM) + WEB (Database)
        if (!empty($normalizedChildIds)) {
            $parentUsers = \App\Models\User::whereHas('children', function ($q) use ($normalizedChildIds) {
                    $q->whereIn('childparent.childid', $normalizedChildIds);
                })
                ->where('allow_notifications', true)
                ->where('userid', '!=', $createdBy)
                ->distinct()
                ->get();

            Log::info('PARENTS_FOUND', [
                'module' => $moduleType,
                'count' => $parentUsers->count(),
                'parent_ids' => $parentUsers->pluck('id')->all(),
            ]);

            foreach ($parentUsers as $parent) {
                // 1️⃣ Send PUSH Notification (FCM)
                $devices = \App\Models\UserDevice::where('user_id', $parent->id)
                    ->whereNotNull('fcm_token')
                    ->where('fcm_token', '!=', '')
                    ->where('fcm_token', 'not like', 'test%')
                    ->get();

                Log::info('DEVICES_FOUND', [
                    'module' => $moduleType,
                    'parent_id' => $parent->id,
                    'device_count' => $devices->count(),
                ]);

                if (!$devices->isEmpty()) {
                    foreach ($devices as $device) {
                        if (empty($data)) {
                            $data = [
                                'type' => (string)$moduleType,
                                'module_id' => (string)$moduleId,
                                'child_ids' => implode(',', $normalizedChildIds),
                                'created_by' => (string)$createdBy,
                            ];
                        }

                        if ($section) {
                            $data['section'] = $section;
                        }

                        Log::info('FCM_SEND_ATTEMPT', [
                            'parent_id' => $parent->id,
                            'token' => $device->fcm_token,
                            'title' => $title,
                            'body' => $body,
                            'data' => $data,
                        ]);

                        $response = $service->sendToToken(
                            $device->fcm_token,
                            $title,
                            $body,
                            $data
                        );

                        $responseData = method_exists($response, 'getData') ? $response->getData() : $response;
                        Log::info('FCM_RESPONSE', [
                            'parent_id' => $parent->id,
                            'token' => $device->fcm_token,
                            'response' => $responseData,
                        ]);

                        $notified[] = [
                            'parent_id' => $parent->id,
                            'token' => $device->fcm_token,
                            'response' => $responseData,
                            'deeplink_data' => $data,
                        ];
                    }
                }

                // 2️⃣ Send WEB Notification (Database)
                try {
                    $parent->notify(new \App\Notifications\GenericModuleNotification(
                        $moduleType,
                        $moduleId,
                        $title,
                        $body,
                        $normalizedChildIds,
                        $createdBy,
                        'parent',
                        $creatorName
                    ));
                    Log::info('WEB_NOTIFICATION_SENT_PARENT', [
                        'module' => $moduleType,
                        'parent_id' => $parent->id,
                        'module_id' => $moduleId,
                    ]);
                } catch (\Exception $e) {
                    Log::error('WEB_NOTIFICATION_FAILED_PARENT', [
                        'module' => $moduleType,
                        'parent_id' => $parent->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $users = collect();
        // ✅ Notify Tagged Staff: WEB ONLY (Database) - NO PUSH
        if (!empty($normalizedUserIds)) {
        $users = User::whereIn('userid', $normalizedUserIds)
                ->where('userid', '!=', $createdBy)
                ->where('allow_notifications', true)
                ->get();
        }
                        Log::info('CENTER_USERS_FOUND', [
                'module' => $moduleType,
                'count' => $users->count(),
                'user_ids' => $users->pluck('userid')->all(),
            ]);

           foreach ($users as $user){
                // Send WEB Notification ONLY (Database) - NO PUSH
                try {
                    $user->notify(
                        new GenericModuleNotification(
                            $moduleType,
                            $moduleId,
                            $title,
                            $body,
                            $normalizedChildIds,
                            $createdBy,
                            'staff',
                            $creatorName
                        )
                    );
                    Log::info('WEB_NOTIFICATION_SENT_STAFF', [
                        'module' => $moduleType,
                       'user_id' => $user->userid,
                        'module_id' => $moduleId,
                    ]);
                } catch (\Exception $e) {
                    Log::error('WEB_NOTIFICATION_FAILED_STAFF', [
                        'module' => $moduleType,
                        'user_id' => $user->userid,
                        'error' => $e->getMessage(),
                    ]);
                } 
            }

        return $notified;
    }


    

    public function testFcm(Request $request, FirebaseNotificationService $service) 
    {
        $request->validate([
            'token' => 'required|string'
        ]);

      try {

        $response = $service->sendToToken(
            $request->token,
            'Test Notification',
            'FCM is working successfully.',
            [
                'type' => 'test',
                'timestamp' => now()->toDateTimeString()
            ]
        );

        return response()->json([
            'success' => true,
            'response' => method_exists($response, 'getData')
                ? $response->getData()
                : $response
        ]);

     } catch (\Exception $e) {

        \Log::error('FCM Test Error', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
     }
    }
}