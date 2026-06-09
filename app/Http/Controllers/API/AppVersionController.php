<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    public function appVersion(Request $request)
    {
        $platform = strtolower($request->get('platform', 'android'));

        $version = AppVersion::first();

        if (!$version) {
            return response()->json([
                'status' => 'error',
                'message' => 'Version configuration not found'
            ], 404);
        }

        if ($platform === 'ios') {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'min_version' => $version->ios_min_version,
                    'latest_version' => $version->ios_latest_version,
                    'update_available' => (bool) $version->update_available,
                    'force_update' => (bool) $version->force_update,
                    'store_url' => $version->ios_store_url,
                    'update_message' => $version->update_message,
                ]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'min_version' => $version->android_min_version,
                'latest_version' => $version->android_latest_version,
                'update_available' => (bool) $version->update_available,
                'force_update' => (bool) $version->force_update,
                'store_url' => $version->android_store_url,
                'update_message' => $version->update_message,
            ]
        ]);
    }

    public function updateVersion(Request $request)
    {
        $request->validate([
            'android_min_version' => 'sometimes|string',
            'android_latest_version' => 'sometimes|string',
            'ios_min_version' => 'sometimes|string',
            'ios_latest_version' => 'sometimes|string',
            'force_update' => 'nullable|boolean',
            'update_available' => 'nullable|boolean',
            'update_message' => 'nullable|string',
        ]);

        // Invalid combination
        if (
            $request->exists('update_available') &&
            !$request->boolean('update_available') &&
            $request->boolean('force_update')
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'force_update cannot be true when update_available is false'
            ], 422);
        }

        $version = AppVersion::first();

        if (!$version) {
            $version = new AppVersion();
        }

        $updatedFields = [];

        if ($request->exists('android_min_version')) {
            $version->android_min_version = $request->android_min_version;
            $updatedFields['android_min_version'] = $request->android_min_version;
        }

        if ($request->exists('android_latest_version')) {
            $version->android_latest_version = $request->android_latest_version;
            $updatedFields['android_latest_version'] = $request->android_latest_version;
        }

        if ($request->exists('ios_min_version')) {
            $version->ios_min_version = $request->ios_min_version;
            $updatedFields['ios_min_version'] = $request->ios_min_version;
        }

        if ($request->exists('ios_latest_version')) {
            $version->ios_latest_version = $request->ios_latest_version;
            $updatedFields['ios_latest_version'] = $request->ios_latest_version;
        }

        if ($request->exists('force_update')) {
            $version->force_update = $request->boolean('force_update');
            $updatedFields['force_update'] = $request->boolean('force_update');
        }

        if ($request->exists('update_available')) {
            $version->update_available = $request->boolean('update_available');
            $updatedFields['update_available'] = $request->boolean('update_available');
        }

        if ($request->exists('update_message')) {
            $version->update_message = $request->update_message;
            $updatedFields['update_message'] = $request->update_message;
        }

        if (empty($updatedFields)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No fields provided for update'
            ], 422);
        }

        $version->save();

        return response()->json([
            'status' => 'success',
            'message' => 'App version updated successfully',
            'data' => $updatedFields
        ]);
    }
}