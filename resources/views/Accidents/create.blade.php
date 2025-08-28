@extends('layout.master')
@section('title', 'Create Accident')
@section('parentPageTitle', 'Dashboard')

@section('page-styles') {{-- ✅ Injects styles into layout --}}
<style>
.is-invalid {
    border-color: #dc3545 !important;
}

.toast-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
}

.toast {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.toast-success {
    background-color: #28a745;
    /* Green for success */
}

.toast-error {
    background-color: #dc3545;
    /* Red for error */
}

.toast-close-button {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    color: white;
    margin-left: 10px;
}

.toast-message {
    flex: 1;

}



   /* Wrapper styling */
.form-wrapper {
    margin-bottom: 20px;
}

/* Label styling */
.custom-label {
    display: block;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 6px;
    font-size: 15px;
}

/* Input styling */
.custom-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    background-color: #fff;
    font-size: 14px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: border-color 0.3s, box-shadow 0.3s;
}

.custom-input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
}

        main{
padding-top:1em;
padding-bottom: 2em;
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
    .radioFlex {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.radio-pill {
    position: relative;
    display: flex;
    align-items: center;
    padding: 8px 16px;
    border: 1px solid #ccc;
    border-radius: 25px;
    background-color: #f1f1f1;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
    user-select: none;
}

.radio-pill input[type="radio"] {
    display: none; /* Hide the default radio circle */
}

.radio-pill:hover {
    background-color: #e0e0e0;
}

.radio-pill input[type="radio"]:checked + label,
.radio-pill input[type="radio"]:checked ~ span,
.radio-pill input[type="radio"]:checked ~ * {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
input[type="radio"]:checked + .radio-pill {
    background-color: #007bff;
    color: white;
    border-color: #0056b3;
}


.service-title {
    font-size: 1.4rem;
    margin-bottom: 1rem;
    color: #0056b3;
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 5px;
}

.editbtn {
    font-size: 0.9rem;
    cursor: pointer;
} 

/* Base style for the switch */
.checkbox-pill {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
    margin-right: 10px;
    vertical-align: middle;
}

.checkbox-pill input {
    opacity: 0;
    width: 0;
    height: 0;
}

.checkbox-pill .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 50px;
}

.checkbox-pill .slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

.checkbox-pill input:checked + .slider {
    background-color: #28a745; /* ON - green */
}

.checkbox-pill input:checked + .slider:before {
    transform: translateX(24px);
}

.injuiry-ul{
    list-style: none;
}

</style>
@endsection
@section('content')
  <hr class="mt-2">
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
                <div class="col-12 mb-5 card pt-2">
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
                                <label for="name" class="custom-label">Name</label>
                         <input type="text" 
           class="form-control custom-input @error('person_name') is-invalid @enderror"  
           id="name" 
           name="person_name" 
           value="{{ old('person_name') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role" class="custom-label">Position Role</label>
                            <input type="text" 
       class="form-control custom-input @error('person_role') is-invalid @enderror" 
       id="role" 
       name="person_role" 
       value="{{ old('person_role') }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Record" class="custom-label">Date Record was made</label>
                               <input type="date" 
       class="form-control custom-input @error('date') is-invalid @enderror" 
       id="Record" 
       name="date" 
       value="{{ old('date') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Time" class="custom-label">Time</label>
                          <input type="time" 
       class="form-control custom-input @error('time') is-invalid @enderror" 
       id="Time" 
       name="time" 
       value="{{ old('time') }}">
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
        <select name="childid" id="childid" 
                class="w-100 form-control js-example-basic-single custom-input @error('childid') is-invalid @enderror">
            <option value="">--Select Children--</option>
            @foreach ($Childrens as $chobj)
                <option value="{{ $chobj->id }}" {{ old('childid') == $chobj->id ? 'selected' : '' }}>
                    {{ $chobj->details }}
                </option>
            @endforeach
        </select>
      
        <input type="hidden" class="form-control" id="childfullname" name="child_name" value="{{ old('child_name') }}">
    </div>

    <div class="form-group col-md-6">
        <label for="birthdate">Date of Birth</label>
        <input type="date" class="form-control custom-input" id="birthdate" name="child_dob" value="{{ old('child_dob') }}">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="age">Age</label>
        <input type="text" class="form-control custom-input" id="age" name="child_age" value="{{ old('child_age') }}">
    </div>

    <div class="form-group col-md-6">
        <label for="name">Gender</label>
        <div class="radioFlex">
            <input type="radio" id="Male" name="gender" value="Male" {{ old('gender') == 'Male' ? 'checked' : '' }} hidden>
            <label class="radio-pill" for="Male">Male</label>

            <input type="radio" id="Female" name="gender" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }} hidden>
            <label class="radio-pill" for="Female">Female</label>

            <input type="radio" id="Others" name="gender" value="Others" {{ old('gender') == 'Others' ? 'checked' : '' }} hidden>
            <label class="radio-pill" for="Others">Others</label>
        </div>
    </div>
</div>


                   <div class="row mb-4">
    <div class="col-12">
        <h3 class="service-title fw-bold">Incident Details</h3>
    </div>

@php
    $today = \Carbon\Carbon::now()->format('Y-m-d');
    $incidentDate = old('incident_date', isset($incident) ? \Carbon\Carbon::parse($incident->incident_date)->format('Y-m-d') : $today);
@endphp

<div class="col-md-6 mb-3">
    <label for="incidentdate" class="form-label">Incident Date</label>
    <input type="date" 
           class="form-control shadow-sm custom-input @error('incident_date') is-invalid @enderror" 
           id="incidentdate" 
           name="incident_date" 
           value="{{ $incidentDate }}"
           @if($incidentDate === $today)  @endif>
</div>


    <div class="col-md-6 mb-3">
        <label for="incidenttime" class="form-label">Time</label>
        <input type="time" class="form-control shadow-sm custom-input" id="incidenttime" name="incident_time">
    </div>

    <div class="col-md-6 mb-3">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control shadow-sm custom-input" id="location" name="incident_location" placeholder="E.g., Playground">
    </div>

    <div class="col-md-6 mb-3">
        <label for="witnessname" class="form-label">Name of Witness</label>
        <input type="text" class="form-control shadow-sm custom-input" id="witnessname" name="witness_name" placeholder="Witness full name">
    </div>

    <div class="col-md-6 mb-3">
        <label for="witness-date" class="form-label">Witness Date</label>
        <input type="date" class="form-control shadow-sm custom-input" id="witness-date" name="witness_date">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">
            Signature
          
        </label>
        <input type="text" class="form-control mb-2 shadow-sm custom-input" data-toggle="modal" data-target="#signModal" data-identity="witness_sign" style="cursor: pointer;" readonly>
        <div id="witness_sign" class="border rounded bg-light p-2 shadow-sm">
            <input type="hidden" name="witness_sign" id="witness_sign_txt">
           <div id="witness_sign_container" style="position: relative; display: inline-block;">
    <img src="" height="120" width="300" id="witness_sign_img" 
         class="img-thumbnail" alt="Witness Signature" style="display:none;">

    <!-- close button -->
    <span id="removewitness_sign_txt"
          style="position: absolute; top: 5px; right: 8px; 
                 cursor: pointer; color: #fff; background: red; 
                 border-radius: 50%; padding: 0 8px; font-weight: bold; 
                 font-size: 16px; line-height: 20px; display:none;">
        ×
    </span>
</div>

        </div>
    </div>
</div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="genActivity">General activity at the time of incident/ injury/ trauma/ illness:</label>
                                <textarea class="form-control custom-input" id="genActivity" name="gen_actyvt"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="causeInjury">Cause of injury/ trauma:</label>
                                <textarea class="form-control custom-input" id="causeInjury" name="cause"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="symptoms">Circumstances surrounding any illness, including apparent symptoms: </label>
                                <textarea class="form-control custom-input" id="symptoms" name="illness_symptoms"></textarea>
                            </div>
                          
                        </div>
                        <div class="form-row">
  <div class="form-group col-md-12">
                                <label for="missingChild">Circumstances if child appeared to be missing or otherwise unaccounted for (incl duration, who found child etc.):</label>
                                <textarea class="form-control custom-input" id="missingChild" name="missing_unaccounted"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="Circumstances">Circumstances if child appeared to have been taken or removed from service or was locked in/out of service (incl who took the child, duration): </label>
                                <textarea class="form-control custom-input" id="Circumstances" name="taken_removed"></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Nature of Injury/ Trauma/ Illness:</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="svgFlex col-12 row ">
                                  <!-- <span class="col-md-6 col-sm-12">
                                        <div class="canvas-container" style="width: 500px; height: 500px; position: relative; user-select: none;"><canvas id="c" width="625" height="625" class="lower-canvas" style="position: absolute; width: 500px; height: 500px; left: 0px; top: 0px; touch-action: none; user-select: none;"></canvas><canvas class="upper-canvas " width="625" height="625" style="position: absolute; width: 500px; height: 500px; left: 0px; top: 0px; touch-action: none; user-select: none; cursor: crosshair;"></canvas></div>
                                    </span> -->
                                    <span class="col-md-6 col-sm-12">
                                        <canvas id="c" width="500" height="500"></canvas>
                                    </span>
                                    <span class="col-md-6 col-sm-12">
                                        <input type="hidden" name="injury_image" id="injury-image" value="">
                                    <ul class="col-12 row injuiry-ul">
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="abrasion" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Abrasion / Scrape</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="electric_shock" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Electric Shock</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="allergic_reaction" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Allergic Reaction</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="high_temperature" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">High Temperature</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="amputation" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Amputation</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="infectious_disease" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Infectious Disease</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="anaphylaxis" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Anaphylaxis</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="ingestion" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Ingestion / Inhalation / Insertion</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="asthma" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Asthma / Respiratory</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="internal_injury" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Internal Injury / Infection</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="bite_wound" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Bite Wound</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="poisoning" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Poisoning</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="broken_bone" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Broken Bone / Fracture / Dislocation</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="rash" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Rash</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="burn" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Burn / Sunburn</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="respiratory" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Respiratory</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="choking" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Choking</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="seizure" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Seizure / Unconscious / Convulsion</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="concussion" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Concussion</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="sprain" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Sprain / Swelling</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="crush" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Crush / Jam</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="stabbing" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Stabbing / Piercing</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="cut" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Cut / Open Wound</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="tooth" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Tooth</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="drowning" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Drowning (Nonfatal)</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="venomous_bite" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Venomous Bite / Sting</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="eye_injury" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Eye Injury</span>
    </li>
    <li class="col-md-6 col-sm-12">
        <label class="checkbox-pill">
            <input type="checkbox" name="other" value="1" id="otherCheckbox">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Other (Please specify)</span>
    </li>
    <li class="col-md-12" id="injury-remarks" style="display: none;">
        <input type="text" name="remarks" class="form-control mt-2  custom-input" placeholder="Write here...">
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
                                <textarea class="form-control custom-input" id="takenAction" name="action_taken"></textarea>    
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label>Did emergency services attend:</label>
                                        <div class="custom-switch custom-switch-secondary-inverse mb-2 col-md-6 col-sm-12 d-flex align-items-center gap-2 mb-2">
                                             <label class="checkbox-pill">
        <input type="checkbox" name="emrg_serv_attend" id="togBtn" value="1">
        <span class="slider"></span>
    </label>
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
                                      <div class="col-md-6 col-sm-12 d-flex align-items-center gap-2 mb-2">
    <label class="checkbox-pill">
        <input type="checkbox" name="med_attention" id="togBtn-second" value="1">
        <span class="slider"></span>
    </label>

</div>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="provideDetails">If yes to either of the above, provide details:</label>
                                <textarea class="form-control custom-input" id="provideDetails" name="med_attention_details"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="provideDetails">List the steps that have been taken to prevent or minimise this type of incident in the future:</label>
                                <ol>
                                    <li><input type="text" class="form-control custom-input" id="one" name="prevention_step_1" value=""></li>
                                    <li><input type="text" class="form-control custom-input" id="two" name="prevention_step_2" value=""></li>
                                    <li><input type="text" class="form-control custom-input" id="three" name="prevention_step_3" value=""></li>
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
                                <input type="text" class="form-control custom-input" id="parentname" name="parent1_name" value="">    
                            </div>
                            <div class="form-group col-md-6">
                                <label for="method">Method of Contact:</label>
                                <input type="text" class="form-control custom-input" id="method" name="contact1_method" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactDate">Date</label>
                                <input type="date" class="form-control custom-input" id="contactDate" name="contact1_date" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contactTime">Time</label>
                                <input type="time" class="form-control custom-input" id="contactTime" name="contact1_time" value="">
                            </div>
                        </div>
                      <div class="form-row">
    <!-- Contact Made -->
    <div class="form-group col-md-6 d-flex align-items-center gap-2 mb-3">
        <label class="checkbox-pill mb-0">
            <input type="checkbox" id="contactmade" name="contact1_made" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Contact Made</span>
    </div>

    <!-- Message Left -->
    <div class="form-group col-md-6 d-flex align-items-center gap-2 mb-3">
        <label class="checkbox-pill mb-0">
            <input type="checkbox" id="messageleft" name="contact1_msg" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Message Left</span>
    </div>
</div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="parentname2">Parent/ Guardian name:</label>
                                <input type="text" class="form-control custom-input" id="parentname2" name="parent2_name" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="method2">Method of Contact:</label>
                                <input type="text" class="form-control custom-input" id="method2" name="contact2_method" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactDate2">Date</label>
                                <input type="date" class="form-control custom-input" id="contactDate2" name="contact2_date" value=""> 
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contactTime2">Time</label>
                                <input type="time" class="form-control custom-input" id="contactTime2" name="contact2_time" value="">
                            </div>
                        </div>
                      <div class="form-row">
    <!-- Contact Made -->
    <div class="form-group col-md-6 d-flex align-items-center gap-2 mb-2">
        <label class="checkbox-pill mb-0">
            <input type="checkbox" id="contactmade2" name="contact2_made" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Contact Made</span>
    </div>

    <!-- Message Left -->
    <div class="form-group col-md-6 d-flex align-items-center gap-2 mb-2">
        <label class="checkbox-pill mb-0">
            <input type="checkbox" id="messageleft2" name="contact2_msg" value="1">
            <span class="slider"></span>
        </label>
        <span class="pill-label">Message Left</span>
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
                                <input type="text" class="form-control custom-input" id="res_pinc" name="responsible_person_name" value=""> 
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Signature
                                    <!-- <span class=" editbtn" data-toggle="modal" data-target="#signModal" data-identity="incharge_sign"> <i class="fas fa-pencil-alt"></i></span> -->
                                </label>
                                <input type="text" class="form-control custom-input" id="res_pinc_dt" data-toggle="modal" data-target="#signModal" data-identity="incharge_sign" readonly>
                                <div id="incharge_sign">
                                    <input type="hidden" name="responsible_person_sign" id="res_pinc_txt" value="">
                                   <div id="res_pinc_container" style="position: relative; display: inline-block;">
    <img src="" height="120" width="300" id="res_pinc_img" 
         style="border: 1px solid #ccc; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); display:none;">
    
    <!-- Stylish close button -->
    <span id="removeres_pinc_txt" 
          style="position: absolute; top: 5px; right: 10px; cursor: pointer; color: #fff; background: red; border-radius: 50%; padding: 2px 8px; font-weight: bold; font-size: 16px; display:none;">
        ×
    </span>
</div>

                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="rp_internal_notif_date">Date</label>
                                <input type="date" class="form-control custom-input" id="rp_internal_notif_date" name="rp_internal_notif_date" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="rp_internal_notif_time">Time</label>
                                <input type="time" class="form-control custom-input" id="rp_internal_notif_time" name="rp_internal_notif_time" value="">
                            </div>
                        </div>
                     
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nom_sv">Nominated Supervisor Name:</label>
                                    <input type="text" class="form-control custom-input" id="nom_sv" name="nominated_supervisor_name" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Signature
                                        <!-- <span class=" editbtn" data-toggle="modal" data-target="#signModal" data-identity="supervisor_sign"><i class="fas fa-pencil-alt"></i></span> -->
                                    </label>
                                    <input type="text" class="form-control custom-input" id="nom_svs_dt" data-toggle="modal" data-target="#signModal" data-identity="supervisor_sign" readonly>
                                    <div id="supervisor_sign">
                                        <input type="hidden" name="nominated_supervisor_sign" id="nsv_sign_txt" value="">
                                      <div id="nsv_sign_container" style="position: relative; display: inline-block;">
    <img src="" height="120" width="300" id="nsv_sign_img" 
         style="border:1px solid #ccc; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); display:none;">
    
    <!-- close button -->
    <span id="removensv_sign_txt"
          style="position: absolute; top: 5px; right: 8px; cursor: pointer; 
                 color: #fff; background: red; border-radius: 50%; 
                 padding: 0 8px; font-weight: bold; font-size: 16px; line-height: 20px; display:none;">
        ×
    </span>
</div>

                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nsv_date">Date</label>
                                    <input type="date" class="form-control custom-input" id="nsv_date" name="nsv_date" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nsv_time">Time</label>
                                    <input type="time" class="form-control custom-input" id="nsv_time" name="nsv_time" value="">
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
                                <input type="text" class="form-control custom-input" id="otheragency" name="otheragency" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="agencyDate">Date</label>
                                        <input type="date" class="form-control custom-input" id="agencyDate" name="enor_date" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="agencyTime">Time</label>
                                        <input type="time" class="form-control custom-input" id="agencyTime" name="enor_time" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Regulatoryauthority">Regulatory authority:</label>
                                <input type="text" class="form-control custom-input" id="Regulatoryauthority" name="Regulatoryauthority" value="">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="enra_date">Date</label>
                                        <input type="date" class="form-control custom-input" id="enra_date" name="enra_date" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="enra_time">Time</label>
                                        <input type="time" class="form-control custom-input" id="enra_time" name="enra_time" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (isset($id))
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="service-title">Parental acknowledgement</h3>
                                    <div class="inlineInput">
                                        I <input type="text" name="ack_parent_name" class="custom-input"> (name of parent / guardian) have been notified of my child’s incident / injury / trauma / illness.
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="RegulatoryauthorityDate">Date</label>
                                    <input type="date" class="form-control custom-input" id="RegulatoryauthorityDate" name="ack_date" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="RegulatoryauthorityTime">Time</label>
                                    <input type="time" class="form-control custom-input" id="RegulatoryauthorityTime" name="ack_time" value="">
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
                                <textarea class="form-control custom-input" id="takenAction" name="add_notes" rows="8"></textarea>
                            </div>
                        </div>

                        <div class="row m-2">
                            <div class="col-sm-12 text-right">
                                <div class="formSubmit">
                                    <button type="button" id="form-submit" class="btn btn-default btn-success">Save &amp; Next</button>
                                    <!-- <button type="button" class="btn btn-default btn-danger">Cancel</button> -->
                                   <a class="btn-warning p-2 rounded" href="{{ route('Accidents.list', ['centerid' => request()->get('centerid'), 'roomid' => request()->get('roomid')]) }}">
   Cancel
</a>

                                </div>
                            </div>
                        </div>
                    </form>
                     <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
                </div>
            </div> 
        </div>
    </main>

<!-- Signature Modal -->

<div class="modal" id="signModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="opacity: 1;">
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
        <button type="button" class="btn btn-default btn-sm btn-danger"  id="btnSignaturecancel" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-default btn-sm btn-success " id="btnSignature" data-identity="" data-dismiss="modal">Save</button>
      </div>
    </div>
  </div>
</div>
                                    </div>

                                   

                                    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach ($errors->all() as $error)
                showToast('error', @json($error));
            @endforeach
        });
    </script>
@endif

@endsection
@push("scripts")
<!-- jQuery -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->

<!-- jQuery UI -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script> -->

<!-- Bootstrap 4 (JS + Popper) -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js"></script> -->

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

<script>

  $('#removensv_sign_txt').click(function () {
    $('#nsv_sign_img').attr('src', '').hide(); // clear + hide image
    $('#nsv_sign_txt').val('');                // unset hidden input
    $(this).hide();                            // also hide the × button itself
});

  $('#removeres_pinc_txt').click(function () {
    $('#res_pinc_img').attr('src', '').hide(); // clear + hide image
    $('#res_pinc_txt').val('');                // unset hidden input
    $(this).hide();                            // also hide the × button itself
});

    
       $('#removewitness_sign_txt').click(function(){
        $('#witness_sign_img').attr('src','').hide();
         $('#witness_sign_txt').val('');
          $(this).hide(); 
    })

       $('#removeperson_sign_txt').click(function(){
        $('#person_sign_img').attr('src','').hide();
         $('#person_sign_txt').val('');
          $(this).hide(); 
    })

$('#btnSignaturecancel').on('click', function() {
    clearSignatureCanvas();
});

// Also clear if modal closed by "X" or outside click
$('#signModal').on('hidden.bs.modal', function () {
    clearSignatureCanvas();
});

// Reusable function
function clearSignatureCanvas() {
    canvas1.clear();
    canvas1.setBackgroundColor('#ffffff', canvas1.renderAll.bind(canvas1));
}

$('#signModal').on('hidden.bs.modal', function () {
    clearSignatureCanvas(true); // clear + reset identity
});

// Reusable function
function clearSignatureCanvas(resetIdentity = false) {
    canvas1.clear();
    canvas1.setBackgroundColor('#ffffff', canvas1.renderAll.bind(canvas1));

    if (resetIdentity) {
        $("#identityVal").val(""); // set empty
    }
}
    // ------------------ Signature Canvas ------------------
    var canvas1 = new fabric.Canvas('d', {
        isDrawingMode: true,
        width: 400,
        height: 200
    });
    canvas1.setBackgroundColor('#ffffff', canvas1.renderAll.bind(canvas1));
    canvas1.freeDrawingBrush.width = 2;
    canvas1.freeDrawingBrush.color = '#000000';

    // When modal is fully visible → set identity + reset offsets
    $(document).on('shown.bs.modal', '#signModal', function (event) {
        var button = $(event.relatedTarget);
        var identity = button.data('identity');
        $("#identityVal").val(identity);

        // Fix Fabric offset inside modal
        canvas1.calcOffset();
        canvas1.renderAll();
    });

    // Save signature button
    $('#btnSignature').on('click', function() {
        let _identity = $("#identityVal").val();
        var _signature = canvas1.toDataURL({ format: 'png' });

        if (_identity === "person_sign") {
            $('#person_sign').show();
            // $('#person_sign_dt').hide();
            $('#person_sign_img').attr('src', _signature).show();
            $('#person_sign_txt').val(_signature);
              $('#removeperson_sign_txt').show();
        } else if (_identity === "witness_sign") {
            $('#witness_sign').show();
            // $('#witness_sign_dt').hide();
            $('#witness_sign_img').attr('src', _signature).show();
            $('#removewitness_sign_txt').show();
            $('#witness_sign_txt').val(_signature);
        } else if (_identity === "incharge_sign") {
            $('#incharge_sign').show();
            // $('#res_pinc_dt').hide();
            $('#res_pinc_img').attr('src', _signature).show();
            $('#res_pinc_txt').val(_signature);
             $('#removeres_pinc_txt').show();
        } else if (_identity === "supervisor_sign") {
            $('#supervisor_sign').show();
            // $('#nom_svs_dt').hide();
            $('#nsv_sign_img').attr('src', _signature).show();
             $('#removensv_sign_txt').show();
            $('#nsv_sign_txt').val(_signature);
        }

        // Clear the canvas after saving
        canvas1.clear();
        canvas1.setBackgroundColor('#ffffff', canvas1.renderAll.bind(canvas1));
    });


    // ------------------ Main Drawing Canvas ------------------
    var canvas = new fabric.Canvas('c', {
        isDrawingMode: true,
        width: 500,
        height: 500
    });

    canvas.freeDrawingBrush.width = 4;
    canvas.freeDrawingBrush.color = '#fd0707ff';

    // Add background image
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

    // Enable circle placement on click
    enableCircleMode(canvas, 4, "green");

    function enableCircleMode(fCanvas, radius = 50, color = "red") {
        fCanvas.on('mouse:down', function (options) {
            if (options.pointer) {
                var circle = new fabric.Circle({
                    left: options.pointer.x - radius,
                    top: options.pointer.y - radius,
                    radius: radius,
                    fill: 'transparent',
                    stroke: color,
                    strokeWidth: 2,
                    selectable: false
                });
                fCanvas.add(circle);
            }

            
        });
    }

    // Save main canvas image
    function saveImage() {
          canvas.renderAll();
        var jpegURL = canvas.toDataURL({
            format: 'jpeg',
            quality: 0.5,
            multiplier: 0.8
        });
        $("#injury-image").val(jpegURL);
    }

    $("#form-submit").click(function(event) {
        saveImage();
        $('#acc-form').submit();
    });

    // ------------------ Injury Remarks Toggle ------------------
    $('input[name="other"]').on('click', function() {
        if ($(this).is(':checked')) {
            $("#injury-remarks").show();
        } else {
            $("#injury-remarks").hide();
        }
    });

    // ------------------ Child Details AJAX ------------------
    $("#childid").on("change", function() {
        let _val = $(this).val();
        if (_val != "") {
            $.ajax({
                url: "{{route('Accident/getChildDetails') }}",
                type: 'post',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: { 'childid': _val },
            })
            .done(function(json) {
                var res = json;
                if (res.Status == "SUCCESS") {
                    $("#childfullname").val(res.Child.name + res.Child.lastname);
                    $("#birthdate").val(res.Child.dob);

                    let birthDate = new Date(res.Child.dob);
                    let today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    let m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    $("#age").val(age);

                    if(res.Child.gender == "Male"){
                        $("#Male").prop('checked', true);
                        $("#Female").prop('checked', false);
                        $("#Others").prop('checked', false);
                    } else if(res.Child.gender == "Female"){
                        $("#Male").prop('checked', false);
                        $("#Female").prop('checked', true);
                        $("#Others").prop('checked', false);
                    } else {
                        $("#Male").prop('checked', false);
                        $("#Female").prop('checked', false);
                        $("#Others").prop('checked', true);
                    }
                }
            });
        }
    });

    // ------------------ Toast Notifications ------------------
    function showToast(type, message) {
        const toastType = type === 'success' ? 'toast-success' : 'toast-error';
        const toast = `
            <div class="toast ${toastType}" style="min-width: 250px; margin-bottom: 10px; color: white;" aria-live="assertive">
                <button type="button" class="toast-close-button" onclick="this.parentElement.remove()">×</button>
                <div class="toast-message">${message}</div>
            </div>
        `;
        $('#toast-container').append(toast);
        setTimeout(() => {
            $('.toast').fadeOut(500, function () {
                $(this).remove();
            });
        }, 3000);
    }
</script>

@endpush
@include('layout.footer')