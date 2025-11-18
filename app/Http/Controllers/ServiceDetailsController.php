<?php

namespace App\Http\Controllers;

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

      $authId = Auth::user()->id; 
    $centerid = Session('user_center_id');

      if(Auth::user()->userType == "Superadmin"){
    $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
 
    $centers = Center::whereIn('id', $center)->get();
//    dd($centers);
     }else{
    $centers = Center::where('id', $centerid)->get();
     }

    // $centers = Center::all();
    // $selectedCenterId = $request->centerid;

    $serviceDetails = null;
    $selectedCenter = null;

    if ($centerid) {
        $serviceDetails = ServiceDetailsModel::where('centerid', $centerid)->first();
        $selectedCenter = Center::find($centerid);
    }

    return view('Service.details', compact('centers', 'serviceDetails', 'selectedCenter'));
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
        $centerid = Session('user_center_id');
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

    return redirect()->back()->with('status',$msg);


    }
}
