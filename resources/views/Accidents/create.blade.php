@extends('layout.master')
@section('title', 'Create Accident')
@section('parentPageTitle', 'Dashboard')

@section('page-styles') {{-- ✅ Injects styles into layout --}}
<style>
   
        main{
padding-top:2em;
padding-bottom: 4em;
padding-inline:2em;
    }
    @media screen and (max-width: 600px) {
    main{

padding-inline:0;
    }
}
</style>
    <style>
	.modal-footer {
	   display: inline-block;
	   width: 100%;
	   padding: 0px 30px 15px;
	   height: inherit;
	   margin: 0px;
	}

	.modal-body{
		padding: 0px 30px;
	}

	#person_sign{
		display: none;
	} 

	#witness_sign{
		display: none;
	}

	#incharge_sign{
		display: none;
	}

	#supervisor_sign{
		display: none;
	}

	.check-control{
		width: 30px;
	}
    .select2{
        width:100% !important;
    }
</style>
@endsection
@section('content')
  <hr class="mt-5">
    <main>
        <div class="container-fluid">
            <!-- <div class="row">
                <div class="col-12">
                    <h1>Add Accidents</h1>
                    
                    <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.university') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.university') }}">Daily Journal</a>
                            </li>
                            <li class="breadcrumb-item">
                               <a href="{{ route('Accidents.list', ['centerid' => request()->get('centerid'), 'roomid' => request()->get('roomid')]) }}">
    Accidents
</a>

                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Add Accidents</li>
                        </ol>
                    </nav>
                    <div class="separator mb-5"></div>
                </div>
            </div>   -->
            <div class="row">
                <div class="col-12 mb-5 card pt-4">
                    <h3 class="service-title text-primary">INCIDENT, INJURY, TRAUMA, & ILLNESS RECORD</h3>
                    <form action="{{ route('Accidents.saveAccident') }}" class="flexDirColoumn" method="post" id="acc-form" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                    <input type="hidden" name="centerid" value="{{ $centerid }}">
                        <input type="hidden" name="roomid" value="{{ $roomid }}">

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Details of person completing this record</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="person_name" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role">Position Role</label>
                                <input type="text" class="form-control" id="role" name="person_role" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Record">Date Record was made</label>
                                <input type="date" class="form-control" id="Record" name="date" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Time">Time</label>
                                <input type="time" class="form-control" id="Time" name="time" value="">
                            </div>
                          
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Child Details</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="childid" class="col-sm-12">Select Child</label>
                                <select name="childid" id="childid" class="w-100 form-control js-example-basic-single">
                                    <option>--Select Children--</option>
                                    <?php foreach ($Childrens as $chkey => $chobj) { ?>
                                    <option value="<?= $chobj->id;?>"><?= $chobj->details;?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" class="form-control" id="childfullname" name="child_name" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="birthdate">Date of Birth</label>
                                <input type="date" class="form-control" id="birthdate" name="child_dob" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="age">Age</label>
                                <input type="text" class="form-control" id="age" name="child_age" value="">     
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">Gender </label>
                                <div class="radioFlex">
                                    <label for="Male"><input class="m-1" type="radio" id="Male" name="gender" value="Male">Male</label>
                                    <label for="Female"><input class="m-1" type="radio" id="Female" name="gender" value="Female">Female</label>
                                    <label for="Others"><input class="m-1" type="radio" id="Others" name="gender" value="Others">Others</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Incident Details</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="incidentdate">Incident Date</label>
                                <input type="date" class="form-control" id="incidentdate" name="incident_date" value="">     
                            </div>
                            <div class="form-group col-md-6">
                                <label for="incidenttime">Time</label>
                                <input type="time" class="form-control" id="incidenttime" name="incident_time" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="incident_location" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="witnessname">Name of Witness</label>
                                <input type="text" class="form-control" id="witnessname" name="witness_name" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="witness-date">Date</label>
                                <input type="date" class="form-control" id="witness-date" name="witness_date" value="">     
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Signature
                                    <span class=" editbtn" data-toggle="modal" data-target="#signModal" data-identity="witness_sign"> <i class="fas fa-pencil-alt"></i></span>
                                </label>
                                <input type="text" class="form-control" id="witness_sign_dt" disabled>
                                <div id="witness_sign">
                                    <input type="hidden" name="witness_sign" id="witness_sign_txt" value="">
                                    <img src="" height="120px" width="300px" id="witness_sign_img">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="genActivity">General activity at the time of incident/ injury/ trauma/ illness:</label>
                                <textarea class="form-control" id="genActivity" name="gen_actyvt"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="causeInjury">Cause of injury/ trauma:</label>
                                <textarea class="form-control" id="causeInjury" name="cause"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="symptoms">Circumstances surrounding any illness, including apparent symptoms: </label>
                                <textarea class="form-control" id="symptoms" name="illness_symptoms"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="missingChild">Circumstances if child appeared to be missing or otherwise unaccounted for (incl duration, who found child etc.):</label>
                                <textarea class="form-control" id="missingChild" name="missing_unaccounted"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="Circumstances">Circumstances if child appeared to have been taken or removed from service or was locked in/out of service (incl who took the child, duration): </label>
                                <textarea class="form-control" id="Circumstances" name="taken_removed"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Nature of Injury/ Trauma/ Illness:</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="svgFlex col-12 row">
                                  <!-- <span class="col-md-6 col-sm-12">
                                        <div class="canvas-container" style="width: 500px; height: 500px; position: relative; user-select: none;"><canvas id="c" width="625" height="625" class="lower-canvas" style="position: absolute; width: 500px; height: 500px; left: 0px; top: 0px; touch-action: none; user-select: none;"></canvas><canvas class="upper-canvas " width="625" height="625" style="position: absolute; width: 500px; height: 500px; left: 0px; top: 0px; touch-action: none; user-select: none; cursor: crosshair;"></canvas></div>
                                    </span> -->
                                    <span class="col-md-6 col-sm-12">
                                        <canvas id="c" width="500" height="500"></canvas>
                                    </span>
                                    <span class="col-md-6 col-sm-12">
                                        <input type="hidden" name="injury_image" id="injury-image" value="">
                                        <ul class="col-12 row">
                                            <li class="col-md-6 col-sm-12">
                                                
                                                <label for="type1">
                                                    <input type="checkbox" name="abrasion" value="1"> Abrasion/ Scrape
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type2">
                                                    <input type="checkbox" name="electric_shock" value="1"> Electric Shock
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type3">
                                                    <input type="checkbox" name="allergic_reaction" value="1"> Allergic reaction
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type4">
                                                    <input type="checkbox" name="high_temperature" value="1"> High Temperature
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type5">
                                                    <input type="checkbox" name="amputation" value="1"> Amputation
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type6">
                                                    <input type="checkbox" name="infectious_disease" value="1"> Infectious Disease (inc gastrointestinal)
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type7">
                                                    <input type="checkbox" name="anaphylaxis" value="1"> Anaphylaxis
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type8">
                                                    <input type="checkbox" name="ingestion" value="1"> Ingestion/ Inhalation/ Insertion
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type9">
                                                    <input type="checkbox" name="asthma" value="1"> Asthma/ Respiratory
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type10">
                                                    <input type="checkbox" name="internal_injury" value="1"> Internal injury/ Infection
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type11">
                                                    <input type="checkbox" name="bite_wound" value="1"> Bite Wound
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type12">
                                                    <input type="checkbox" name="poisoning" value="1"> Poisoning
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type13">
                                                    <input type="checkbox" name="broken_bone" value="1"> Broken Bone/ Fracture/ Dislocation
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type14">
                                                    <input type="checkbox" name="rash" value="1"> Rash
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type15">
                                                    <input type="checkbox" name="burn" value="1"> Burn/ Sunburn
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type16">
                                                    <input type="checkbox" name="respiratory" value="1"> Respiratory
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type17">
                                                    <input type="checkbox" name="choking" value="1"> Choking
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type18">
                                                    <input type="checkbox" name="seizure" value="1"> Seizure/ unconscious/ convulsion
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type19">
                                                    <input type="checkbox" name="concussion" value="1"> Concussion
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type20">
                                                    <input type="checkbox" name="sprain" value="1"> Sprain/ swelling
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type21">
                                                    <input type="checkbox" name="crush" value="1"> Crush/ Jam
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type22">
                                                    <input type="checkbox" name="stabbing" value="1"> Stabbing/ piercing
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type23">
                                                    <input type="checkbox" name="cut" value="1"> Cut/ Open Wound
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type24">
                                                    <input type="checkbox" name="tooth" value="1"> Tooth
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type25">
                                                    <input type="checkbox" name="drowning" value="1"> Drowning (nonfatal)
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type26">
                                                    <input type="checkbox" name="venomous_bite" value="1"> Venomous bite/ sting
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type27">
                                                    <input type="checkbox" name="eye_injury" value="1"> Eye Injury
                                                </label>
                                            </li>
                                            <li class="col-md-6 col-sm-12">
                                                <label for="type28">
                                                    <input type="checkbox" name="other" value="1"> Other (Please specify)
                                                </label>
                                            </li>
                                            <li id="injury-remarks" style="width: 100%;display: none;">
                                                <input type="text" name="remarks" placeholder="Write here..." class="form-control col-md-6 col-sm-12" style="width: 100%;">
                                            </li>
                                        </ul>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Action Taken</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="takenAction">Details of action taken (including first aid, administration of medication etc.):</label>
                                <textarea class="form-control" id="takenAction" name="action_taken"></textarea>    
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label>Did emergency services attend:</label>
                                        <div class="custom-switch custom-switch-secondary-inverse mb-2">
                                            <input class="custom-switch-input mandatory-field" id="togBtn" type="checkbox" name="emrg_serv_attend" value="1">
                                            <label class="custom-switch-btn" for="togBtn"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label>Was medical attention sought from a registered practitioner / hospital:</label>
                                        <div class="custom-switch custom-switch-secondary-inverse mb-2">
                                            <input class="custom-switch-input mandatory-field" id="togBtn-second" type="checkbox" name="med_attention" value="1">
                                            <label class="custom-switch-btn" for="togBtn-second"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="provideDetails">If yes to either of the above, provide details:</label>
                                <textarea class="form-control" id="provideDetails" name="med_attention_details"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="provideDetails">List the steps that have been taken to prevent or minimise this type of incident in the future:</label>
                                <ol>
                                    <li><input type="text" class="form-control" id="one" name="prevention_step_1" value=""></li>
                                    <li><input type="text" class="form-control" id="two" name="prevention_step_2" value=""></li>
                                    <li><input type="text" class="form-control" id="three" name="prevention_step_3" value=""></li>
                                </ol>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Parent/Guardian Notifications (including attempted notifications)</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="parentname">Parent/ Guardian name:</label>
                                <input type="text" class="form-control" id="parentname" name="parent1_name" value="">    
                            </div>
                            <div class="form-group col-md-6">
                                <label for="method">Method of Contact:</label>
                                <input type="text" class="form-control" id="method" name="contact1_method" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactDate">Date</label>
                                <input type="date" class="form-control" id="contactDate" name="contact1_date" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contactTime">Time</label>
                                <input type="time" class="form-control" id="contactTime" name="contact1_time" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactmade">Contact Made: </label>
                                <input type="checkbox" class="form-control check-control" id="contactmade" name="contact1_made" value="1">   
                            </div>
                            <div class="form-group col-md-6">
                                <label for="messageleft">Message Left:</label>
                                <input type="checkbox" class="form-control check-control" id="messageleft" name="contact1_msg" value="1">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="parentname2">Parent/ Guardian name:</label>
                                <input type="text" class="form-control" id="parentname2" name="parent2_name" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="method2">Method of Contact:</label>
                                <input type="text" class="form-control" id="method2" name="contact2_method" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactDate2">Date</label>
                                <input type="date" class="form-control" id="contactDate2" name="contact2_date" value=""> 
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contactTime2">Time</label>
                                <input type="time" class="form-control" id="contactTime2" name="contact2_time" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactmade2">Contact Made: </label>
                                <input type="checkbox" class="form-control check-control" id="contactmade2" name="contact2_made" value="1">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="messageleft2">Message Left:</label>
                                <input type="checkbox" class="form-control check-control" id="messageleft2" name="contact2_msg" value="1">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Internal Notifications</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="res_pinc">Responsible Person in Charge Name:</label>
                                <input type="text" class="form-control" id="res_pinc" name="responsible_person_name" value=""> 
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Signature
                                    <span class=" editbtn" data-toggle="modal" data-target="#signModal" data-identity="incharge_sign"> <i class="fas fa-pencil-alt"></i></span>
                                </label>
                                <input type="text" class="form-control" id="res_pinc_dt" disabled>
                                <div id="incharge_sign">
                                    <input type="hidden" name="responsible_person_sign" id="res_pinc_txt" value="">
                                    <img src="" height="120px" width="300px" id="res_pinc_img">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="rp_internal_notif_date">Date</label>
                                <input type="date" class="form-control" id="rp_internal_notif_date" name="rp_internal_notif_date" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="rp_internal_notif_time">Time</label>
                                <input type="time" class="form-control" id="rp_internal_notif_time" name="rp_internal_notif_time" value="">
                            </div>
                        </div>
                     
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nom_sv">Nominated Supervisor Name:</label>
                                    <input type="text" class="form-control" id="nom_sv" name="nominated_supervisor_name" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Signature
                                        <span class=" editbtn" data-toggle="modal" data-target="#signModal" data-identity="supervisor_sign"><i class="fas fa-pencil-alt"></i></span>
                                    </label>
                                    <input type="text" class="form-control" id="nom_svs_dt" disabled>
                                    <div id="supervisor_sign">
                                        <input type="hidden" name="nominated_supervisor_sign" id="nsv_sign_txt" value="">
                                        <img src="" height="120px" width="300px" id="nsv_sign_img">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nsv_date">Date</label>
                                    <input type="date" class="form-control" id="nsv_date" name="nsv_date" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nsv_time">Time</label>
                                    <input type="time" class="form-control" id="nsv_time" name="nsv_time" value="">
                                </div>
                            </div>
                       

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">External Notifications</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="otheragency">Other agency:</label>
                                <input type="text" class="form-control" id="otheragency" name="otheragency" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="agencyDate">Date</label>
                                        <input type="date" class="form-control" id="agencyDate" name="enor_date" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="agencyTime">Time</label>
                                        <input type="time" class="form-control" id="agencyTime" name="enor_time" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Regulatoryauthority">Regulatory authority:</label>
                                <input type="text" class="form-control" id="Regulatoryauthority" name="Regulatoryauthority" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="enra_date">Date</label>
                                        <input type="date" class="form-control" id="enra_date" name="enra_date" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="enra_time">Time</label>
                                        <input type="time" class="form-control" id="enra_time" name="enra_time" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (isset($id))
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="service-title">Parental acknowledgement</h3>
                                    <div class="inlineInput">
                                        I <input type="text" name="ack_parent_name"> (name of parent / guardian) have been notified of my child’s incident / injury / trauma / illness.
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="RegulatoryauthorityDate">Date</label>
                                    <input type="date" class="form-control" id="RegulatoryauthorityDate" name="ack_date" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="RegulatoryauthorityTime">Time</label>
                                    <input type="time" class="form-control" id="RegulatoryauthorityTime" name="ack_time" value="">
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Additional notes</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <textarea class="form-control" id="takenAction" name="add_notes"></textarea>
                            </div>
                        </div>

                        <div class="row m-2">
                            <div class="col-sm-12 text-right">
                                <div class="formSubmit">
                                    <button type="button" id="form-submit" class="btn btn-default btn-success">Save &amp; Next</button>
                                    <!-- <button type="button" class="btn btn-default btn-danger">Cancel</button> -->
                                   <a href="{{ route('Accidents.list', ['centerid' => request()->get('centerid'), 'roomid' => request()->get('roomid')]) }}">
   Cancel
</a>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </main>

<!-- Signature Modal -->

<div class="modal fade" id="signModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="opacity: 1;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-primary" id="myModalLabel">Signature</h4>
        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <input type="hidden" id="identityVal">
      </div>
      <div class="modal-body text-center">
        <div id="sig" class="kbw-signature mx-auto">
           <span class="col-md-6 col-sm-12 ">
                                        <canvas id="d" width="500"  height="500" class="border mx-auto"></canvas>
                                    </span>
      </div>
      <div class="modal-footer text-right">
      	<br>
        <button type="button" class="btn btn-default btn-sm btn-danger" data-dismiss="modal">Exit</button>
        <button type="button" class="btn btn-default btn-sm btn-success " id="btnSignature" data-identity="" data-dismiss="modal">Use</button>
      </div>
    </div>
  </div>
</div>
                                    </div>
@endsection
@push("scripts")
<!-- jQuery -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->

<!-- jQuery UI -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

<!-- Bootstrap 4 (JS + Popper) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js"></script>

<!-- Moment.js (required by FullCalendar) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<!-- FullCalendar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.5/fullcalendar.min.js"></script>

<!-- Perfect Scrollbar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.5/perfect-scrollbar.min.js"></script>

<!-- Mousetrap (shortcut handling) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.5/mousetrap.min.js"></script>

<!-- Glide.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.5.2/glide.min.js"></script>

<!-- DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.4/jquery.dataTables.min.js"></script>

<!-- Dore scripts (custom) – keep as local if unavailable on CDN -->
<!-- <script src="('assets/v3/js/dore.script.js') "></script>
<script src="('assets/v3/js/scripts.js') "></script>
<script src="('assets/v3/js/survey.js') "></script> -->

<!-- Fabric.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>

<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

<!-- metisMenu -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js"></script>

<!-- jQuery Signature -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.signature/1.2.1/jquery.signature.min.js"></script>

<script style="opacity: 1;">

    $('#btnSignature').on('click', function() {
    let _identity = $("#identityVal").val();

    // Get data from Fabric.js canvas
    var _signature = canvas1.toDataURL({ format: 'png' });

    if (_identity === "person_sign") {
       
        $('#person_sign').show();
        $('#person_sign_dt').hide();
        $('#person_sign_img').attr('src', _signature);
        $('#person_sign_txt').val(_signature);
    } else if (_identity === "witness_sign") {
        $('#witness_sign').show();
        $('#witness_sign_dt').hide();
        $('#witness_sign_img').attr('src', _signature);
        $('#witness_sign_txt').val(_signature);
    } else if (_identity === "incharge_sign") {
        $('#incharge_sign').show();
        $('#res_pinc_dt').hide();
        $('#res_pinc_img').attr('src', _signature);
        $('#res_pinc_txt').val(_signature);
    } else if (_identity === "supervisor_sign") {
        $('#supervisor_sign').show();
        $('#nom_svs_dt').hide();
        $('#nsv_sign_img').attr('src', _signature);
        $('#nsv_sign_txt').val(_signature);
    }

    // Clear the canvas after using
    canvas1.clear();

    // Optional: Reset canvas background
    canvas1.setBackgroundColor('#ffffff', canvas1.renderAll.bind(canvas1));

    	$(document).on('show.bs.modal', '#signModal',function (event) {
		  var button = $(event.relatedTarget);
		  var identity = button.data('identity');
		  $("#identityVal").val(identity);
		});

        $('.select2-container').addClass('select2-container--bootstrap select2-container--below select2-container--focus');
        $('.select2-container').removeClass('select2-container--default');
});


    var canvas = new fabric.Canvas('c', {
    isDrawingMode: true,
    // Set lower resolution for the canvas
    width: 500,
    height: 500
});

   var canvas1 = new fabric.Canvas('d', {
    isDrawingMode: true,
    // Set lower resolution for the canvas
    width: 400,
    height: 200
});

// Configure drawing brush to use less data
canvas.freeDrawingBrush.width = 2; // Thinner lines
canvas.freeDrawingBrush.color = '#000000'; // Simple color

canvas1.freeDrawingBrush.width = 2; // Thinner lines
canvas1.freeDrawingBrush.color = '#000000'; // Simple color

fabric.Image.fromURL("{{ asset('assets/media/baby.jpg')}}", function(myImg) {
    var img1 = myImg.set({ 
        left: 0, 
        top: 0,
        scaleX: 500 / myImg.width,
        scaleY: 500 / myImg.height,
        selectable: false,
        hasControls: false
    });

    canvas.add(img1);
}, { crossOrigin: 'Anonymous' });

function saveImage() {
    // Use JPEG format with compression instead of PNG
    // The second parameter is the quality (0 to 1)
    var jpegURL = canvas.toDataURL({
        format: 'jpeg',
        quality: 0.5,    // Lower value = smaller file, but lower quality
        multiplier: 0.8  // Reduces the resolution of the output
    });
    
    $("#injury-image").val(jpegURL);
}

	$("#form-submit").click(function(event) {
		saveImage();
		$('#acc-form').submit();
	});

	$('input[name="other"]').on('click',function(){

		if ($(this).is(':checked')) {
			$("#injury-remarks").show();
		}else{
			$("#injury-remarks").hide();
		}
	});


    $("#childid").on("change",function(){
		let _val = $(this).val();
		if (_val != "") {
			$.ajax({
					url: "{{route('Accident/getChildDetails') }}",
				type: 'post',
                         headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
				data: {'childid': _val},
			})
			.done(function(json) {
				var res = json;
				if (res.Status == "SUCCESS") {
                    // alert();
					$("#childfullname").val(res.Child.name + res.Child.lastname);
					$("#birthdate").val(res.Child.dob);

                               let birthDate = new Date(res.Child.dob);
                    let today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();

                    // Adjust age if birthday hasn't occurred yet this year
                    let m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
					$("#age").val(age);
					if(res.Child.gender == "Male"){
						$("#Male").prop('checked', true);
						$("#Female").prop('checked', false);
						$("#Others").prop('checked', false);
					}else if(res.Child.gender == "Female"){
						$("#Male").prop('checked', false);
						$("#Female").prop('checked', true);
						$("#Others").prop('checked', false);
					}else{
						$("#Male").prop('checked', false);
						$("#Female").prop('checked', false);
						$("#Others").prop('checked', true);
					}
				}
			});
		}
	});
</script>
@endpush
@include('layout.footer')