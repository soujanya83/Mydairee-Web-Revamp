<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Center;
use App\Models\ServiceDetailsModel;
use App\Models\User; // Add this at the top if not already added
use App\Models\Usercenter; // Add this at the top if not already added
use App\Models\Child; // Add this at the top if not already added
use App\Models\Childparent; // Add this at the top if not already added
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ServiceDetailsController extends Controller
{
    
    public function create(Request $request)
{
// dd('here');
      $authId = Auth::user()->id; 
    // $centerid = Session('user_center_id');
    // $authId = $request->user_id;
    $centerid = $request->user_center_id;
    // dd($authId);

    $user = User::where('userid',$authId)->first();

      if($user->userType == "Superadmin"){
    $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
 
    $centers = Center::whereIn('id', $center)->get();
//    dd($centers);
     }else{
    $centers = Center::where('id', $centerid)->get();
     }
    //  dd($centers);

    // $centers = Center::all();
    // $selectedCenterId = $request->centerid;

    $serviceDetails = null;
    $selectedCenter = null;
    $data = [];

    if ($centerid) {
        $serviceDetails = ServiceDetailsModel::where('centerid', $centerid)->first();
        $selectedCenter = Center::find($centerid);

        $data = [
        'centers' => $centers,
        'serviceDetails' => $serviceDetails,
        'selectedCenter' => $selectedCenter
        ];
        $response = [
            'status' => true,
            'msg' => 'data retrived successfully',
            'data' => $data
        ];
    }

    $response = [
            'status' => false,
            'msg' => 'data donot exist',
            'data' => $data
        ];

  return response()->json($response);

}


public function store(Request $request)
{
    // Define validation rules
    $rules = [
        'serviceName'           => 'required|string|max:80',
        'serviceApprovalNumber' => 'required|string|max:80',
        'serviceStreet'         => 'required|string|max:80',
        'serviceSuburb'         => 'required|string|max:80',
        'serviceState'          => 'required|string|max:80',
        'servicePostcode'       => 'required|string|max:10',

        'contactTelephone'      => 'required|string|max:20',
        'contactMobile'         => 'required|string|max:20',
        'contactFax'            => 'required|string|max:20',
        'contactEmail'          => 'required|email|max:80',

        'providerContact'       => 'required|string|max:80',
        'providerTelephone'     => 'required|string|max:20',
        'providerMobile'        => 'required|string|max:20',
        'providerFax'           => 'required|string|max:20',
        'providerEmail'         => 'required|email|max:80',

        'supervisorName'        => 'required|string|max:80',
        'supervisorTelephone'   => 'required|string|max:20',
        'supervisorMobile'      => 'required|string|max:20',
        'supervisorFax'         => 'required|string|max:20',
        'supervisorEmail'       => 'required|email|max:80',

        'postalStreet'          => 'required|string|max:80',
        'postalSuburb'          => 'required|string|max:80',
        'postalState'           => 'required|string|max:30',
        'postalPostcode'        => 'required|string|max:10',

        'eduLeaderName'         => 'required|string|max:60',
        'eduLeaderTelephone'    => 'required|string|max:20',
        'eduLeaderEmail'        => 'required|email|max:80',

        'strengthSummary'       => 'required|string',
        'childGroupService'     => 'required|string',
        'personSubmittingQip'   => 'required|string',
        'educatorsData'         => 'required|string',
        'philosophyStatement'   => 'required|string',

        'centerid'              => 'nullable|integer',
    ];

    // Validate request manually
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $validated = $validator->validated();
    $centerid = $validated['centerid'] ?? null;

    // Check if a record exists for this center
    $check = ServiceDetailsModel::where('centerid', $centerid)->first();

    // Prepare data to insert/update
    $data = collect($validated)->except('centerid')->merge(['centerid' => $centerid])->toArray();

    if ($check) {
        $updated = $check->update($data);
        $msg = $updated 
            ? "Service details updated successfully" 
            : "Error! Service details could not be updated. Please try again";
    } else {
        $created = ServiceDetailsModel::create($data);
        $msg = $created 
            ? "Service details created successfully" 
            : "Error! Service details could not be created. Please try again";
    }

    return response()->json(['status' => true, 'message' => $msg]);
}

}
