<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    public function appVersion(Request $request)
    {
        //default android if platform is not provided
        $platform = strtolower($request->get('platform', 'android'));

        $version = AppVersion::first();

        if (!$version) {
            return response()->json([
                'status' => 'error',
                'message' => 'Version configuration not found'
            ], 404);
        }

        if ($platform == 'ios') {

            return response()->json([
                'status' => 'success',
                'data' => [
                    'min_version' => $version->ios_min_version,
                    'latest_version' => $version->ios_latest_version,
                    'update_available'=> $version->update_available,
                    'force_update' => (bool)$version->force_update,
                    'update_available' => (bool)$version->update_available,
                    'store_url_ios' => $version->ios_store_url,
                    'update_message' => $version->update_message
                ]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'min_version' => $version->android_min_version,
                'latest_version' => $version->android_latest_version,
                'force_update' => (bool)$version->force_update,
                'update_available' => (bool)$version->update_available,
                'store_url_android' => $version->android_store_url,
                'update_message' => $version->update_message
            ]
        ]);
    }


    public function updateVersion(Request $request)
    {
        $request->validate([
            'android_min_version' => 'required|string',
            'android_latest_version' => 'required|string',
            'ios_min_version' => 'required|string',
            'ios_latest_version' => 'required|string',
            'force_update' => 'required|boolean',
            'update_message' => 'nullable|string',
            'update_available' => 'nullable|boolean',
        ]);

        $version = AppVersion::first();

        if (!$version) {
            $version = new AppVersion();
        }

        $version->android_min_version = $request->android_min_version;
        $version->android_latest_version = $request->android_latest_version;
        $version->ios_min_version = $request->ios_min_version;
        $version->ios_latest_version = $request->ios_latest_version;
        $version->force_update = $request->force_update;
        $version->update_available = $request->update_available;
        $version->update_message = $request->update_message;

        $version->save();

        return response()->json([
            'status' => 'success',
            'message' => 'App version updated successfully',
            'data' => [
                'android_min_version' => $version->android_min_version,
                'android_latest_version' => $version->android_latest_version,
                'ios_min_version' => $version->ios_min_version,
                'ios_latest_version' => $version->ios_latest_version,
                'update_available' => (bool) $version->update_available,
                'force_update' => (bool) $version->force_update,
                'update_message' => $version->update_message,
            ]
        ]);
    }











}