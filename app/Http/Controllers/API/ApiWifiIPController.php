<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WifiIP_Model;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiWifiIPController extends Controller
{
    /**
     * Get all WiFi IP entries
     */
    public function index()
    {
        try {
            $wifiIPs = WifiIP_Model::latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'WiFi IP list retrieved successfully.',
                'data' => $wifiIPs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve WiFi IP list: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new WiFi IP entry
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wifi_ip'       => 'required|string|max:255',
            'wifi_name'     => 'required|string|max:255',
            'wifi_address'  => 'nullable|string|max:255',
            'status'        => 'required|in:1,0,active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Normalize status (accept both numeric and string)
            $status = in_array($request->status, ['1', 'active']) ? 1 : 0;

            $wifiIP = WifiIP_Model::create([
                'wifi_ip'       => $request->wifi_ip,
                'wifi_name'     => $request->wifi_name,
                'wifi_address'  => $request->wifi_address ?? null,
                'status'        => $status,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'WiFi IP created successfully.',
                'data' => $wifiIP
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create WiFi IP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single WiFi IP entry
     */
    public function show($id)
    {
        try {
            $wifiIP = WifiIP_Model::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'WiFi IP retrieved successfully.',
                'data' => $wifiIP
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'WiFi IP not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve WiFi IP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update WiFi IP entry
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'wifi_ip'       => 'nullable|string|max:255',
            'wifi_name'     => 'nullable|string|max:255',
            'wifi_address'  => 'nullable|string|max:255',
            'status'        => 'nullable|in:1,0,active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $wifiIP = WifiIP_Model::findOrFail($id);

            // Update only provided fields
            if ($request->filled('wifi_ip')) {
                $wifiIP->wifi_ip = $request->wifi_ip;
            }
            if ($request->filled('wifi_name')) {
                $wifiIP->wifi_name = $request->wifi_name;
            }
            if ($request->filled('wifi_address')) {
                $wifiIP->wifi_address = $request->wifi_address;
            }
            if ($request->filled('status')) {
                $wifiIP->status = in_array($request->status, ['1', 'active']) ? 1 : 0;
            }

            $wifiIP->save();

            return response()->json([
                'status' => true,
                'message' => 'WiFi IP updated successfully.',
                'data' => $wifiIP
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'WiFi IP not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update WiFi IP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle WiFi IP status
     */
    public function toggleStatus($id)
    {
        try {
            $wifiIP = WifiIP_Model::findOrFail($id);
            $wifiIP->status = $wifiIP->status == 1 ? 0 : 1;
            $wifiIP->save();

            return response()->json([
                'status' => true,
                'message' => 'WiFi IP status toggled successfully.',
                'data' => $wifiIP
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'WiFi IP not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to toggle WiFi IP status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete WiFi IP entry
     */
    public function destroy($id)
    {
        try {
            $wifiIP = WifiIP_Model::findOrFail($id);
            $wifiIP->delete();

            return response()->json([
                'status' => true,
                'message' => 'WiFi IP deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'WiFi IP not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete WiFi IP: ' . $e->getMessage()
            ], 500);
        }
    }


}
