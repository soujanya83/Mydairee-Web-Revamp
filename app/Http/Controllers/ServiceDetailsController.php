<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Center;
use App\Models\ServiceDetailsModel;

class ServiceDetailsController extends Controller
{

    public function create(Request $request)
{
    $centers = Center::all();
    $selectedCenterId = $request->centerid;

    $serviceDetails = null;
    $selectedCenter = null;

    if ($selectedCenterId) {
        $serviceDetails = ServiceDetailsModel::where('centerid', $selectedCenterId)->first();
        $selectedCenter = Center::find($selectedCenterId);
    }

    return view('Service.details', compact('centers', 'serviceDetails', 'selectedCenterId', 'selectedCenter'));
}


    function store(Request $request){

        // dd($request->all());
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
  'centerid' => 'required'
    ]);

    // dd('here');
// check if the service details already present or not based on center_id 
   $check = ServiceDetailsModel::where('centerid', $request->centerid)->first();


    if($check){

        $response = $check->update($request->all());

         if($response){
            $msg = "Service details updated successfully";
        }else{
            $msg = " Error! Service details could not be updated. Please try again";
        }

    }else{
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
