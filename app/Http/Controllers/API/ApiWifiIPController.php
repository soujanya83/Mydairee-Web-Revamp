<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WifiIP_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiWifiIPController extends Controller
{
    /**
     * Resolve the active center id from the request or authenticated context.
     */
    private function resolveCenterId(Request $request, $fallbackCenterId = null)
    {
        $centerId = $request->input('center_id', $request->input('centerid'));

        if (!empty($centerId)) {
            return (int) $centerId;
        }

        if (!empty($fallbackCenterId)) {
            return (int) $fallbackCenterId;
        }

        $user = Auth::user();

        if ($user) {
            return (int) ($user->center_id ?? $user->centerid ?? session('user_center_id') ?? 0) ?: null;
        }

        return session('user_center_id') ? (int) session('user_center_id') : null;
    }

    /**
     * Build the WiFi IP query for a center.
     */
    private function wifiQueryForCenter($centerId = null)
    {
        $query = WifiIP_Model::query();

        if (!empty($centerId)) {
            $query->where('center_id', $centerId);
        }

        return $query;
    }

    /**
     * Get all WiFi IP entries
     */
    public function index(Request $request)
    {
        try {
            $centerId = $this->resolveCenterId($request);

            if (!$centerId) {
                return response()->json([
                    'status' => false,
                    'message' => 'center_id is required.'
                ], 422);
            }

            $wifiIPs = $this->wifiQueryForCenter($centerId)->latest()->get();

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
            'wifi_address'  => 'nullable|string',
            'status'        => 'required|in:1,0,active,inactive',
            'center_id'     => 'required|integer|exists:centers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $centerId = $this->resolveCenterId($request);

            if (!$centerId) {
                return response()->json([
                    'status' => false,
                    'message' => 'center_id is required.'
                ], 422);
            }

            // Normalize status (accept both numeric and string)
            $status = in_array($request->status, ['1', 'active']) ? 1 : 0;

            $wifiIP = WifiIP_Model::create([
                'wifi_ip'       => $request->wifi_ip,
                'wifi_name'     => $request->wifi_name,
                'wifi_address'  => $request->wifi_address ?? null,
                'status'        => $status,
                'center_id'     => $centerId,
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
            'wifi_address'  => 'nullable|string',
            'status'        => 'nullable|in:1,0,active,inactive',
            'center_id'     => 'required|integer|exists:centers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $centerId = $this->resolveCenterId($request);

            if (!$centerId) {
                return response()->json([
                    'status' => false,
                    'message' => 'center_id is required.'
                ], 422);
            }


            if (!$centerId) {
                return response()->json([
                    'status' => false,
                    'message' => 'center_id is required.'
                ], 422);
            }

            $wifiIP = $this->wifiQueryForCenter($centerId)->findOrFail($id);

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
            if ($request->filled('center_id')) {
                $wifiIP->center_id = $request->center_id;
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
            $centerId = $this->resolveCenterId(request());

            if (!$centerId) {
                return response()->json([
                    'status' => false,
                    'message' => 'center_id is required.'
                ], 422);
            }

            $wifiIP = $this->wifiQueryForCenter($centerId)->findOrFail($id);
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
     * Grant or revoke temporary login access for a staff user.
     * Mirrors the web staff-list hour-based access flow.
     */
    public function userwifiChangeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'action' => 'nullable|in:grant,revoke',
            'hours'  => 'nullable|integer|in:1,4,8,168,720,8760',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $staff = User::findOrFail($request->user_id);

            // Only allow this operation for users whose userType is Staff
            if (empty($staff->userType) || strtolower($staff->userType) !== 'staff') {
                return response()->json([
                    'status' => false,
                    'message' => 'Only users with userType "Staff" can be granted or revoked access.'
                ], 422);    
            }

            $action = $request->input('action');

            // Only an explicit 'grant' action will grant access. Any other value (including null)
            // will be treated as a revoke.
            if ($action === 'grant') {
                $request->validate([
                    'hours' => 'required|integer|in:1,4,8,168,720,8760',
                ]);

                $staff->wifi_status = 1;
                $staff->wifi_access_until = now()->addHours((int) $request->hours);

                $message = 'Staff access granted successfully.';
            } else {
                // Revoke for any non-grant action
                $staff->wifi_status = 0;
                $staff->wifi_access_until = null;

                $message = 'Staff access revoked successfully.';
            }

            $staff->save();

            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $staff,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Staff user not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update staff access: ' . $e->getMessage(),
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
