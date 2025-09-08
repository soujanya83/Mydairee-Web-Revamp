<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WifiIP_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WifiIPController extends Controller
{
    public function wifi_add_form()
    {
        $all_wifi = WifiIP_Model::latest()->get();

        return view('wifi_ip.wifi_ip_list', compact('all_wifi'));
    }


    function wifi_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wifi_ip'        => 'required|string|max:255',
            'wifi_name'        => 'required|string|max:255',
            'wifi_status'        => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        WifiIP_Model::insert([
            'wifi_ip' => $request->wifi_ip,
            'wifi_name' => $request->wifi_name,
            'wifi_address' => $request->wifi_address,
            'status' => $request->wifi_status,

        ]);

        return back()->with('success', 'Wifi IP Story Successfully');
    }

    public function userwifi_changeStatus(Request $request, $id)
    {

        $staff = User::findOrFail($id);

        if ($staff->wifi_status == 1) {
            // Access remove
            $staff->wifi_status = 0;
            $staff->wifi_access_until = null;
        } else {
            $request->validate([
                'hours' => 'required|integer|in:1,4,8,168,720,8760'
            ]);

            // Access give with selected hours
            $staff->wifi_status = 1;
            $staff->wifi_access_until = now()->addHours((int) $request->hours);
        }

        $staff->save();

        return back()->with('success', 'IP access updated successfully.');
    }

    // ✅ Change Status
    public function changeStatus($id)
    {
        $wifi = WifiIP_Model::findOrFail($id);
        $wifi->status = $wifi->status == 1 ? 0 : 1; // toggle
        $wifi->save();

        return back()->with('success', 'WiFi status updated');
    }

    // ✅ Delete WiFi
    public function destroy($id)
    {
        $wifi = WifiIP_Model::findOrFail($id);
        $wifi->delete();

        return back()->with('success', 'WiFi deleted successfully');
    }
}
