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
    /**
     * Store or update the user's FCM device token.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @param array $childIds
     * @param string $title
     * @param string $body
     * @param array $data
     * @param string $moduleType (e.g., 'observation', 'reflection', 'snapshot', 'diary')
     * @param int $moduleId (ID of the created module record)
     * @param int|null $createdBy (optional, user ID who created the record)
     * @param \App\Services\Firebase\FirebaseNotificationService $service
     * @return array
     */
    public function saveToken(Request $request): JsonResponse
    {
        // Validate request
        $validated = $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'required|string|in:android,ios',
        ]);

        try {
            $user = $request->user(); // Get authenticated user

            // Use transaction for safety
            DB::beginTransaction();

            // Find existing device by token
            $device = UserDevice::where('fcm_token', $validated['fcm_token'])->first();

            if ($device) {
                // Update device_type and last_used_at
                $device->update([
                    'device_type' => $validated['device_type'],
                    'last_used_at' => Carbon::now(),
                ]);
            } else {
                // Create new device record
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

    public function testNotification(FirebaseNotificationService $service)
    {
        $devices = UserDevice::where('user_id', auth()->id())
            ->whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->where('fcm_token', 'not like', 'test%')
            ->get();

        if ($devices->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No valid device tokens found for this user.'
            ], 404);
        }

        $results = [];
        foreach ($devices as $device) {
            $response = $service->sendToToken(
                $device->fcm_token,
                'Test Notification',
                'Hello from Laravel FCM V1 🚀',
                ['type' => 'test']
            );
            $results[] = [
                'token' => $device->fcm_token,
                'response' => $response->getData(),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifications sent to all valid tokens.',
            'results' => $results,
        ]);
    }

    public function updateNotificationPreference(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'allow_notifications' => 'required|boolean',
        ]);

        $user = $request->user();

        $user->update([
            'allow_notifications' => $request->boolean('allow_notifications'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification preference updated successfully.',
            'allow_notifications' => $user->allow_notifications,
        ]);
    }

    /**
     * Send notification to all parents of given children (if allow_notifications is true).
     *

     */
    public static function notifyParentsOfChildren(array $childIds, string $title, string $body, array $data, \App\Services\Firebase\FirebaseNotificationService $service)
    {
        // Get all parent users for the given children, only if allow_notifications is true
        $parentUsers = \App\Models\User::whereHas('children', function ($q) use ($childIds) {
                $q->whereIn('child.id', $childIds);
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
                $data = [
                    'type' => (string)$moduleType,
                    'module_id' => (string)$moduleId,
                    'child_ids' => implode(',', $childIds), // convert array to string
                    'created_by' => (string)$createdBy,
                ];
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


    public static function notifyParentsModuleCreated(array $childIds, string $moduleType, int $moduleId, $createdBy, \App\Services\Firebase\FirebaseNotificationService $service, $section = null)
    {
        // Module-specific titles and bodies
        $titles = [
            'observation' => 'New Observation Added',
            'reflection' => 'New Reflection Added',
            'snapshot' => 'New Snapshot Added',
            'diary' => 'New Daily Diary Entry',
        ];
        $bodies = [
            'observation' => 'A new observation has been added for your child.',
            'reflection' => 'A new reflection has been added for your child.',
            'snapshot' => 'A new snapshot has been added for your child.',
            'diary' => 'A new daily diary entry has been added for your child.',
        ];
        $title = $titles[$moduleType] ?? 'New Update';
        $body = $bodies[$moduleType] ?? 'A new update has been added for your child.';

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
                $data = [
                    'type' => (string)$moduleType,
                    'module_id' => (string)$moduleId,
                    'child_ids' => implode(',', $childIds), // convert array to string
                    'created_by' => (string)$createdBy,
                ];
                if ($section) {
                    $data['section'] = $section;
                }
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
