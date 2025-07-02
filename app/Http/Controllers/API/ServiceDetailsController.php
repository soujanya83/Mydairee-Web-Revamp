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


    function store(Request $request){
        // dd('here');
        $request->validate([
  'serviceName' => 'required',
  'serviceApprovalNumber' => 'required',
  'serviceStreet' => 'required',
  'serviceSuburb' => 'required',
  'serviceState' => 'required',
  'servicePostcode' => 'required',
  'contactTelephone' => 'required',
  'contactMobile' => 'required',
  'contactFax' => 'required',
  'contactEmail' => 'required|email',
  'providerContact' => 'required',
  'providerTelephone' => 'required',
  'providerMobile' => 'required',
  'providerFax' => 'required',
  'providerEmail' => 'required|email',
  'supervisorName' => 'required',
  'supervisorTelephone' => 'required',
  'supervisorMobile' => 'required',
  'supervisorFax' => 'required',
  'supervisorEmail' => 'required|email',
  'postalStreet' => 'required',
  'postalSuburb' => 'required',
  'postalState' => 'required',
  'postalPostcode' => 'required',
  'eduLeaderName' => 'required', 
  'eduLeaderTelephone' => 'required',
  'eduLeaderEmail' => 'required|email ',
  'strengthSummary' => 'required',
  'childGroupService' => 'required',
  'personSubmittingQip' => 'required',
  'educatorsData' => 'required',
  'philosophyStatement' => 'required',
    ]);

    // dd('here');
        // $centerid = Session('user_center_id');
        $centerid = $request->centerid;
// check if the service details already present or not based on center_id 
   $check = ServiceDetailsModel::where('centerid', $centerid)->first();


    if($check){
        $request->merge(['centerid' => $centerid]);
        $response = $check->update($request->all());

         if($response){
            $msg = "Service details updated successfully";
        }else{
            $msg = " Error! Service details could not be updated. Please try again";
        }

    }else{
        $request->merge(['centerid' => $centerid]);
        $response = ServiceDetailsModel::create($request->all());

        if($response){
            $msg = "Service details created successfully";
        }else{
            $msg = " Error! Service details could not be created. Please try again";
        }
    }

    return response()->json(['status' => true , 'msg'=> $msg ]);


    }
}
