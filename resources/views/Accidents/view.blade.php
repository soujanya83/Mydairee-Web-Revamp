@extends('layout.master')
@section('title', 'Daily Journel')
@section('parentPageTitle', 'Accidents')

@section('page-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.signature/1.2.1/jquery.signature.css">

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!-- Example: Simple Line Icons CSS -->
 <!-- Font Awesome 5.15.4 - Compatible with Bootstrap 4 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <style>
    main{
padding-block:4em;
padding-inline:2em;
    }
    @media screen and (max-width: 600px) {
    main{

padding-inline:0;
    }
}

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

        /* #incharge_sign{
            display: none;
        } */

        /* #supervisor_sign{
            display: none;
        } */

        .check-control{
            width: 35px;
        }
        .select2-container{
            width:100% !important;
        }


        .print-button {
            position: absolute;
            /* top: 9px; */
            right: 60px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 10; /* Add this */
            /* margin-right: 10px; */
        }

        .email-button {
    position: absolute;
    /* top: 9px; */
    right: 210px; 
    padding: 10px 20px;
    background-color: #007BFF; /* Blue to distinguish from print */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    z-index: 10; /* Ensure it’s clickable */
}

.email-button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}


          /* Print Styles */
                @media print {
            body {
                margin: 20mm;
            }
           .no-print2 {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
    }
        
            .print-container {
                font-size: 16px;
                line-height: 1.6;
            }
            .print-container h2 {
                text-align: center;
                border-bottom: 2px solid #333;
                padding-bottom: 5px;
            }
            .print-container .row {
                display: flex;
                justify-content: space-between;
                border-bottom: 1px dashed #ccc;
                padding: 8px 0;
            }
            .print-container .label {
                font-weight: bold;
            }
        }

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
<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px; margin-top: -60px;">
 
   
     <button onclick="printMainContent()" class="print-button no-print " style="margin-inline:0.5rem;">Print Pages&nbsp;<i class="fa-solid fa-print fa-beat-fade"></i></button>
   


    
      @if(Auth::user()->userType != 'Parent')
    <button onclick="sendReportToParent()" class="email-button no-print ml-2">
        Send to Parent <i class="fa-solid fa-envelope fa-beat-fade" ></i>
    </button>
@endif

    

   
</div>
  <hr class="mt-5">
 
    <main >
        
        <div class="container-fluid">
           <div class="row no-print2">
    <div class="col-12">
        <!-- <h1> Accidents Details</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item">
                    <a href="">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                  <a href="{{ route('Accidents.list', ['centerid' => $AccidentInfo->centerid, 'roomid' => $AccidentInfo->roomid]) }}">Accident</a>


                </li>
                <li class="breadcrumb-item active" aria-current="page">View Accidents</li>
            </ol>
        </nav> -->
       
    </div>
</div>
       <div id="printArea">
            <div class="row">
                <div class="col-12 mb-5 card pt-4">
                    <h3 class="service-title text-primary">INCIDENT, INJURY, TRAUMA, & ILLNESS RECORD</h3>
                    <form action="#!" class="flexDirColoumn" method="post" id="acc-form" enctype="multipart/form-data" autocomplete="off">
                    @csrf    
                    <input type="hidden" name="centerid" value="{{ $AccidentInfo->centerid }}">
                        <input type="hidden" name="roomid" value="{{ $AccidentInfo->roomid}}"> 

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Details of person completing this record</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control custom-input" id="name" name="person_name" placeholder="<?= $AccidentInfo->person_name; ?>" value="<?= $AccidentInfo->person_name; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role">Position Role</label>
                                <input type="text" class="form-control custom-input" id="role" name="person_role" placeholder="<?= $AccidentInfo->person_role; ?>" value="<?= $AccidentInfo->person_role; ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Record">Date Record was made</label>
                                <input type="date" class="form-control custom-input custom-input" id="Record" name="date" placeholder="<?= $AccidentInfo->date; ?>" value="<?= $AccidentInfo->date; ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Time">Time</label>
                                <input type="text" class="form-control custom-input custom-input" id="Time" name="time" placeholder="<?= $AccidentInfo->time; ?>" value="<?= $AccidentInfo->time; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Signature&nbsp;
                                    <span class="editbtn text-primary" data-toggle="modal" data-target="#signModal" data-identity="person_sign"><i class="simple-icon-pencil"></i></span>
                                </label>
                                <input type="hidden" class="form-control custom-input custom-input" id="person_sign_dt" disabled>
                                <div id="#person_sign">
                                    <input type="hidden" name="person_sign" id="person_sign_txt"  value="{{ $AccidentInfo->person_sign }}">
                                    <input type="hidden" name="student_id" id="student_id"  value="<?= $AccidentInfo->childid; ?>">

                                    <img src="{{ asset('assets/media/'.$AccidentInfo->person_sign) }}" height="120px" width="300px" id="person_sign_img">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Child Details</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="childid" class="col-sm-12 pl-0">Child</label>
                                <select name="childid" id="childid" class="form-control js-example-basic-single custom-input">
                                    <option value="<?php $AccidentInfo->child_name ?>"> <span class="no-print3"><?php echo $AccidentInfo->child_name ?></span> </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="birthdate">Date of Birth</label>
                                <input type="text" class="form-control custom-input" id="birthdate" name="child_dob" value="<?php echo $AccidentInfo->child_dob ?>" placeholder="<?php echo $AccidentInfo->child_dob ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="age">Age</label>
                                <input type="text" class="form-control custom-input" id="age" name="child_age" value="<?= $AccidentInfo->child_age; ?>" placeholder="<?= $AccidentInfo->child_age; ?>">     
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gender">Gender </label>
                                <div class="radioFlex">
                                   
                                    <input type="radio" id="<?= $AccidentInfo->child_gender; ?>" name="gender" value="<?= $AccidentInfo->child_gender; ?>" <?php if($AccidentInfo->child_gender) { echo "checked"; } ?> hidden>
                               <label class="radio-pill" for="Others"><?= $AccidentInfo->child_gender; ?></label>
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
                                <input type="text" class="form-control custom-input" id="incidentdate" name="incident_date" value="<?= $AccidentInfo->incident_date; ?>" placeholder="<?= $AccidentInfo->incident_date; ?>">     
                            </div>
                            <div class="form-group col-md-6">
                                <label for="incidenttime">Time</label>
                                <input type="text" class="form-control custom-input" id="incidenttime" name="incident_time" value="<?= $AccidentInfo->incident_time; ?>" placeholder="<?= $AccidentInfo->incident_time; ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="location">Location</label>
                                <input type="text" class="form-control custom-input" id="location" name="incident_location" value="<?= $AccidentInfo->incident_location; ?>" placeholder="<?= $AccidentInfo->incident_location; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="witnessname">Name of Witness</label>
                                <input type="text" class="form-control custom-input" id="witnessname" name="witness_name" value="<?= $AccidentInfo->witness_name; ?>" placeholder="<?= $AccidentInfo->witness_name; ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="witness-date">Date</label>
                                <input type="text" class="form-control custom-input" id="witness-date" name="witness_date" value="<?= $AccidentInfo->witness_date; ?>" placeholder="<?= $AccidentInfo->witness_date; ?>">     
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Signature
                                    <span class="simple-icon-pencil text-primary editbtn" data-toggle="modal" data-target="#signModal" data-identity="witness_sign"></span>
                                </label>
                                <!-- <input type="text" class="form-control" id="witness_sign_dt" disabled> -->
                                <div id="#witness_sign " class="bordered">
                                    <input type="hidden" name="witness_sign" id="witness_sign_txt" value="_{{ $AccidentInfo->witness_sign }}">
                                    <img src="{{ asset('assets/media/'.$AccidentInfo->witness_sign) }}" class="bordered" height="120px" width="300px" id="witness_sign_img">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="genActivity">General activity at the time of incident/ injury/ trauma/ illness:</label>
                                <textarea class="form-control custom-input" id="genActivity" name="gen_actyvt"><?= $AccidentInfo->gen_actyvt; ?></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="causeInjury">Cause of injury/ trauma:</label>
                                <textarea class="form-control custom-input" id="causeInjury" name="cause"><?= $AccidentInfo->cause; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="symptoms">Circumstances surrounding any illness, including apparent symptoms: </label>
                                <textarea class="form-control custom-input" id="symptoms" name="illness_symptoms"><?= $AccidentInfo->illness_symptoms; ?></textarea>
                            </div>
                          
                        </div>
                        <div class="form-row">
  <div class="form-group col-md-12">
                                <label for="missingChild">Circumstances if child appeared to be missing or otherwise unaccounted for (incl duration, who found child etc.):</label>
                                <textarea class="form-control custom-input" id="missingChild" name="missing_unaccounted"><?= $AccidentInfo->missing_unaccounted; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="Circumstances">Circumstances if child appeared to have been taken or removed from service or was locked in/out of service (incl who took the child, duration): </label>
                                <textarea class="form-control custom-input" id="Circumstances" name="taken_removed"><?= $AccidentInfo->taken_removed; ?></textarea>
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
                                 <span class="col-md-6 col-sm-12">
    <img 
        src="{{ asset('assets/media/' . $AccidentInfo->injury_image) }}" 
        alt="Injury Image"
        class="img-fluid border rounded" 
        style="max-width: 100%; height: auto; display: block;"
    >
</span>

                                    <span class="col-md-6 col-sm-12">
                                        <input type="hidden" name="injury_image" id="injury-image" value="">
                                          <ul class="row injuiry-ul">
    @php
        $injuries = [
            'abrasion' => 'Abrasion / Scrape',
            'electric_shock' => 'Electric Shock',
            'allergic_reaction' => 'Allergic Reaction',
            'high_temperature' => 'High Temperature',
            'amputation' => 'Amputation',
            'infectious_disease' => 'Infectious Disease',
            'anaphylaxis' => 'Anaphylaxis',
            'ingestion' => 'Ingestion / Inhalation / Insertion',
            'asthma' => 'Asthma / Respiratory',
            'internal_injury' => 'Internal Injury / Infection',
            'bite_wound' => 'Bite Wound',
            'poisoning' => 'Poisoning',
            'broken_bone' => 'Broken Bone / Fracture / Dislocation',
            'rash' => 'Rash',
            'burn' => 'Burn / Sunburn',
            'respiratory' => 'Respiratory',
            'choking' => 'Choking',
            'seizure' => 'Seizure / Unconscious / Convulsion',
            'concussion' => 'Concussion',
            'sprain' => 'Sprain / Swelling',
            'crush' => 'Crush / Jam',
            'stabbing' => 'Stabbing / Piercing',
            'cut' => 'Cut / Open Wound',
            'tooth' => 'Tooth',
            'drowning' => 'Drowning (Nonfatal)',
            'venomous_bite' => 'Venomous Bite / Sting',
            'eye_injury' => 'Eye Injury',
            'other' => 'Other (Please specify)',
        ];
    @endphp

    @foreach ($injuries as $field => $label)
        <li class="col-md-6 col-sm-12 mb-2 d-flex align-items-center">
            <label class="checkbox-pill me-2">
                <input type="checkbox" name="{{ $field }}" value="1"
                       {{ isset($AccidentInfo->$field) && $AccidentInfo->$field == 1 ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
            <span class="pill-label">{{ $label }}</span>
        </li>
    @endforeach

    <li class="col-md-12 mt-2" id="injury-remarks"
        style="{{ isset($AccidentInfo->other) && $AccidentInfo->other == 1 ? 'display:block;' : 'display:none;' }}">
        <input type="text" name="remarks" class="form-control custom-input"
               placeholder="Write here..." value="{{ $AccidentInfo->remarks ?? '' }}">
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
                                <textarea class="form-control custom-input" id="takenAction" name="action_taken"><?= $AccidentInfo->action_taken; ?></textarea>    
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label>Did emergency services attend:</label>
                                        <div class="custom-switch custom-switch-secondary-inverse mb-2">
                                            <!-- <input class="custom-switch-input mandatory-field" id="togBtn" type="text" name="emrg_serv_attend" value="1" placeholder="<?= $AccidentInfo->emrg_serv_attend;?>"> -->
                                            <label class="custom-switch-btn" for="togBtn"><?= $AccidentInfo->emrg_serv_attend;?></label>
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
                                            <!-- <input class="custom-switch-input mandatory-field" id="togBtn-second" type="checkbox" name="med_attention" value="1"> -->
                                            <label class="custom-switch-btn" for="togBtn-second"><?= $AccidentInfo->med_attention;?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="provideDetails">If yes to either of the above, provide details:</label>
                                <textarea class="form-control custom-input" id="provideDetails" name="med_attention_details"><?= $AccidentInfo->med_attention_details;?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="provideDetails">List the steps that have been taken to prevent or minimise this type of incident in the future:</label>
                                <ol>
                                    <li><input type="text" class="form-control custom-input" id="one" name="prevention_step_1" value="<?= $AccidentInfo->prevention_step_1;?>" placeholder="<?= $AccidentInfo->prevention_step_1;?>"></li>
                                    <li><input type="text" class="form-control custom-input" id="two" name="prevention_step_2" value="<?= $AccidentInfo->prevention_step_2;?>" placeholder="<?= $AccidentInfo->prevention_step_2;?>"></li>
                                    <li><input type="text" class="form-control custom-input" id="three" name="prevention_step_3" value="<?= $AccidentInfo->prevention_step_3;?>" placeholder="<?= $AccidentInfo->prevention_step_3;?>"></li>
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
                                <input type="text" class="form-control custom-input" id="parentname" name="parent1_name" value="<?= $AccidentInfo->parent1_name;?>" placeholder="<?= $AccidentInfo->parent1_name;?>">    
                            </div>
                            <div class="form-group col-md-6">
                                <label for="method">Method of Contact:</label>
                                <input type="text" class="form-control custom-input" id="method" name="contact1_method" value="<?= $AccidentInfo->contact1_method;?>" placeholder="<?= $AccidentInfo->contact1_method;?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactDate">Date</label>
                                <input type="text" class="form-control custom-input" id="contactDate" name="contact1_date" value="<?= $AccidentInfo->contact1_date;?>" placeholder="<?= $AccidentInfo->contact1_date;?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contactTime">Time</label>
                                <input type="text" class="form-control custom-input" id="contactTime" name="contact1_time" value="<?= $AccidentInfo->contact1_time;?>" placeholder="<?= $AccidentInfo->contact1_time;?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactmade">Contact Made: </label>
                                <input type="text" class="form-control custom-input" id="contactmade" name="contact1_made" value="<?= $AccidentInfo->contact1_made;?>" placeholder="<?= $AccidentInfo->contact1_made;?>">   
                            </div>
                            <div class="form-group col-md-6">
                                <label for="messageleft">Message Left:</label>
                                <input type="text" class="form-control custom-input" id="messageleft" name="contact1_msg" value="<?= $AccidentInfo->contact1_msg;?>" placeholder="<?= $AccidentInfo->contact1_msg;?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="parentname2">Parent/ Guardian name:</label>
                                <input type="text" class="form-control custom-input" id="parentname2" name="parent2_name" value="<?= $AccidentInfo->parent2_name;?>" placeholder="<?= $AccidentInfo->parent2_name;?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="method2">Method of Contact:</label>
                                <input type="text" class="form-control custom-input" id="method2" name="contact2_method" value="<?= $AccidentInfo->contact2_method;?>" placeholder="<?= $AccidentInfo->contact2_method;?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactDate2">Date</label>
                                <input type="text" class="form-control custom-input" id="contactDate2" name="contact2_date" value="<?= $AccidentInfo->contact2_date;?>" placeholder="<?= $AccidentInfo->contact2_date;?>"> 
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contactTime2">Time</label>
                                <input type="text" class="form-control custom-input" id="contactTime2" name="contact2_time" value="<?= $AccidentInfo->contact2_time;?>" placeholder="<?= $AccidentInfo->contact2_time;?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contactmade2">Contact Made: </label>
                                <input type="text" class="form-control custom-input" id="contactmade2" name="contact2_made" value="<?= $AccidentInfo->contact2_made;?>" placeholder="<?= $AccidentInfo->contact2_made;?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="messageleft2">Message Left:</label>
                                <input type="text" class="form-control custom-input" id="messageleft2" name="contact2_msg" value="<?= $AccidentInfo->contact2_msg;?>" placeholder="<?= $AccidentInfo->contact2_msg;?>">
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
                                <input type="text" class="form-control custom-input" id="res_pinc" name="responsible_person_name" value="<?= $AccidentInfo->responsible_person_name;?>" placeholder="<?= $AccidentInfo->responsible_person_name;?>"> 
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Signature
                                    <span class="simple-icon-pencil text-primary editbtn" data-toggle="modal" data-target="#signModal" data-identity="incharge_sign"></span>
                                </label>
                                <!-- <input type="text" class="form-control" id="res_pinc_dt" disabled> -->
                                <div id="incharge_sign">
                                    <!-- <input type="hidden" name="responsible_person_sign" id="res_pinc_txt" value=""> -->
                                    <img src="{{ asset('assets/media/'.$AccidentInfo->responsible_person_sign) }}" height="120px" width="300px" id="res_pinc_img">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="rp_internal_notif_date">Date</label>
                                <input type="text" class="form-control custom-input" id="rp_internal_notif_date" name="rp_internal_notif_date" value="<?= $AccidentInfo->rp_internal_notif_date; ?>" placeholder="<?= $AccidentInfo->rp_internal_notif_date;?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="rp_internal_notif_time">Time</label>
                                <input type="text" class="form-control custom-input" id="rp_internal_notif_time" name="rp_internal_notif_time" value="<?= $AccidentInfo->rp_internal_notif_time; ?>" placeholder="<?= $AccidentInfo->rp_internal_notif_time;?>">
                            </div>
                        </div>
                    @if(!empty($AccidentInfo->id))
                     <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Nominated Supervisor</h3>
                            </div>
                        </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nom_sv">Nominated Supervisor Name:</label>
                                    <input type="text" class="form-control custom-input" id="nom_sv" name="nominated_supervisor_name" value="<?= $AccidentInfo->nominated_supervisor_name;?>" placeholder="<?= $AccidentInfo->nominated_supervisor_name;?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        Signature
                                        <span class="simple-icon-pencil text-primary editbtn" data-toggle="modal" data-target="#signModal" data-identity="supervisor_sign"></span>
                                    </label>
                                    <!-- <input type="text" class="form-control" id="nom_svs_dt" disabled placeholder="<?#= $AccidentInfo->nominated_supervisor_sign;?>"> -->
                                    <div id="supervisor_sign">
                                        <!-- <input type="hidden" name="nominated_supervisor_sign" id="nsv_sign_txt" value="" placeholder="<?#= $AccidentInfo->nominated_supervisor_sign;?>"> -->
                                        <img src="{{ asset('assets/media/'.$AccidentInfo->nominated_supervisor_sign) }}" height="120px" width="300px" id="nsv_sign_img">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nsv_date">Date</label>
                                    <input type="text" class="form-control custom-input" id="nsv_date" name="nsv_date" value="<?= $AccidentInfo->nominated_supervisor_date;?>" placeholder="<?= $AccidentInfo->nominated_supervisor_date;?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nsv_time">Time</label>
                                    <input type="text" class="form-control custom-input" id="nsv_time" name="nsv_time" value="<?= $AccidentInfo->nominated_supervisor_time;?>" placeholder="<?= $AccidentInfo->nominated_supervisor_time;?>">
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">External Notifications</h3>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="otheragency">Other agency:</label>
                                <input type="text" class="form-control custom-input" id="otheragency" name="otheragency" value="<?= $AccidentInfo->ext_notif_other_agency;?>" placeholder="<?= $AccidentInfo->ext_notif_other_agency;?>">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="agencyDate">Date</label>
                                        <input type="text" class="form-control custom-input" id="agencyDate" name="enor_date" value="<?= $AccidentInfo->enor_date;?>" placeholder="<?= $AccidentInfo->enor_date;?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="agencyTime">Time</label>
                                        <input type="text" class="form-control custom-input" id="agencyTime" name="enor_time" value="<?= $AccidentInfo->enor_time;?>" placeholder="<?= $AccidentInfo->enor_time;?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Regulatoryauthority">Regulatory authority:</label>
                                <input type="text" class="form-control custom-input" id="Regulatoryauthority" name="Regulatoryauthority" value="<?= $AccidentInfo->ext_notif_regulatory_auth;?>" placeholder="<?= $AccidentInfo->ext_notif_regulatory_auth;?>">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="enra_date">Date</label>
                                    <input type="date" class="form-control custom-input" id="enra_date" name="enra_date"
                                    value="{{ isset($AccidentInfo->enra_date) ? \Carbon\Carbon::parse($AccidentInfo->enra_date)->format('Y-m-d') : '' }}"
                                    placeholder="{{ isset($AccidentInfo->enra_date) ? \Carbon\Carbon::parse($AccidentInfo->enra_date)->format('Y-m-d') : '' }}">

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="enra_time">Time</label>
                                        <input type="text" class="form-control" id="enra_time" name="enra_time" value="<?= $AccidentInfo->enra_time;?>" placeholder="<?= $AccidentInfo->enra_time;?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!empty($AccidentInfo->id))
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="service-title">Parental acknowledgement</h3>
                                    <div class="form-group col-md-12">
                                    <label for="ack_parent_name">Parental acknowledgement</label>
                                         <input type="text" name="ack_parent_name" class="form-control custom-input col-12" value="<?= $AccidentInfo->ack_parent_name;?>" placeholder="<?= $AccidentInfo->ack_parent_name;?>"> (name of parent / guardian) have been notified of my child's incident / injury / trauma / illness.
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="RegulatoryauthorityDate">Date</label>
<input type="date" class="form-control custom-input" id="RegulatoryauthorityDate" name="ack_date"
       value="{{ isset($AccidentInfo->ack_date) ? \Carbon\Carbon::parse($AccidentInfo->ack_date)->format('Y-m-d') : '' }}">


                                </div>
                                <div class="form-group col-md-6">
                                    <label for="RegulatoryauthorityTime">Time</label>
                                    <input type="text" class="form-control custom-input" id="RegulatoryauthorityTime" name="ack_time" value="<?= $AccidentInfo->ack_time;?>" placeholder="<?= $AccidentInfo->ack_time;?>">
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="service-title">Additional notes</h3>
                            </div>
                        </div>
                        <div class="form-row mb-5">
                            <div class="form-group col-md-12">
                                <textarea class="form-control custom-input" id="takenAction" name="add_notes" rows="8"><?= $AccidentInfo->add_notes;?></textarea>
                            </div>
                        </div>

                         <!-- <div class="row m-2" id="formSubmit">
                            <div class="col-sm-12 text-right">
                                <div class="formSubmit">
                                    <button type="button" id="form-submit" class="btn btn-default btn-success">update &amp; Next</button>
                                    <button type="button" class="btn btn-default btn-danger">Cancel</button>
                                </div>
                            </div>
                        </div>  -->

                    </form>
                </div>
            </div>
                        </div> 
        </div>
    </main>
@endsection

@push('scripts')
    <!-- Include Select2 or other JS libraries if needed -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.signature/1.2.1/jquery.signature.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>

    <script>
        $(document).ready(function(){
            $('.js-example-basic-single').select2();
            var sig = $('#sig').signature();
            $('#btnSignature').on('click', function() {
                let _identity = $("#identityVal").val();
                if (_identity == "person_sign") {
                    $('#person_sign').show();
                    $('#person_sign_dt').hide();
                    var _signature = $('#sig').signature('toDataURL');
                    $('#person_sign_img').attr('src', _signature);
                    $('#person_sign_txt').val(_signature);
                } else if (_identity == "witness_sign") {
                    $('#witness_sign').show();
                    $('#witness_sign_dt').hide();
                    var _signature = $('#sig').signature('toDataURL');
                    $('#witness_sign_img').attr('src', _signature);
                    $('#witness_sign_txt').val(_signature);
                } else if (_identity == "incharge_sign") {
                    $('#incharge_sign').show();
                    $('#res_pinc_dt').hide();
                    var _signature = $('#sig').signature('toDataURL');
                    $('#res_pinc_img').attr('src', _signature);
                    $('#res_pinc_txt').val(_signature);
                } else if (_identity == "supervisor_sign") {
                    $('#supervisor_sign').show();
                    $('#nom_svs_dt').hide();
                    var _signature = $('#sig').signature('toDataURL');
                    $('#nsv_sign_img').attr('src', _signature);
                    $('#nsv_sign_txt').val(_signature);
                }
                $('#sig').signature('clear');
            });

            $(document).on('show.bs.modal', '#signModal',function (event) {
            var button = $(event.relatedTarget);
            var identity = button.data('identity');
            $("#identityVal").val(identity);
            });

            // $('.select2-container').addClass('select2-container--bootstrap select2-container--below select2-container--focus w-100');
            $('.select2-selection__rendered').addClass('select2-container--bootstrap select2-container--below select2-container--focus w-100');
            
            $('.select2-container').removeClass('select2-container--default');

        });

        var canvas = new fabric.Canvas('c',{isDrawingMode:true});

   fabric.Image.fromURL("{{ asset('assets/images/baby.jpg') }}", function(myImg) 
 {
            
            var img1 = myImg.set({ 
                left: 0, 
                top: 0,
                scaleX: 500 / myImg.width,
                scaleY: 500 / myImg.height,
                selectable: false,
                hasControls: false
            });

            // setCorners(img1);
            canvas.add(img1);   
        },{ crossOrigin: 'Anonymous' });

        function saveImage(){
            var pngURL = canvas.toDataURL();
            $("#injury-image").val(pngURL);
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
                    url: "{{ route('Accidents.getCenterRooms') }}",
                    type: 'post',
                    data: {'childid': _val},
                })
                .done(function(json) {
                    var res = $.parseJSON(json);
                    if (res.Status == "SUCCESS") {
                        $("#childfullname").val(res.Child.name + res.Child.lastname);
                        $("#birthdate").val(res.Child.dob);
                        $("#age").val(res.Child.age);
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

<script>
    
    function printMainContent() {
        var content = document.getElementById("printArea").cloneNode(true);
  $('#formSubmit').remove();
        // Convert input fields, textareas, and selects to plain text with label formatting
        content.querySelectorAll("input, textarea, select").forEach(field => {
            var parent = field.parentNode;
            var label = parent.querySelector("label"); // Get label
          

            if (field.type === "hidden") {
                // Remove hidden inputs (they won't be printed)
                field.remove();
                return;
            }

            if (label) {
                var labelText = label.textContent.trim();
                label.remove(); // Remove original label
            } else {
                var labelText = ''; // If no label exists, keep it empty
            }

            var valueText = ""; // Store the field value
            if (field.tagName === "SELECT") {
                valueText = field.options[field.selectedIndex]?.text || "";
            } else if (field.type === "checkbox") {
                valueText = field.checked ? "&#x2714" : "&#x2716"; // Show checkmark for selected, cross for unselected
            } else {
                valueText = field.value;
            }

            // Create formatted output: Label - Value
            var formattedRow = document.createElement("div");
            formattedRow.classList.add("formatted-row");
            formattedRow.innerHTML = `<strong class="label">${labelText}</strong> - <span class="value">${valueText}</span>`;

            parent.replaceChild(formattedRow, field);
        });

        // Create print window
        var printWindow = window.open("", "", "width=1000,height=800");
        printWindow.document.write(`
            <html>
            <head>
                <title>Print</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20mm; }
                    .print-container { font-size: 16px; line-height: 1.6; }
                    .print-container h2 { text-align: center; border-bottom: 2px solid #333; padding-bottom: 5px; }
                    .formatted-row { padding: 8px 0; border-bottom: 1px dashed #ccc; }
                    .label { font-weight: bold; margin-right: 5px; }
                    .value { font-weight: normal; }
                    .no-print2 {  display: none !important; }
                    .no-print3 {  display: none !important; }
                    #formSubmit {display:none !important; }
                </style>
            </head>
            <body>
                <div class="print-container">${content.innerHTML}</div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }


    function sendReportToParent() {
        // alert();
    // Show loading indicator
    showLoading("Preparing PDF and sending email...");
    
    // Get the content just like in printMainContent()
    var content = document.getElementById("printArea").cloneNode(true);

    // Apply the same formatting as in printMainContent()
    content.querySelectorAll("input, textarea, select").forEach(field => {
        var parent = field.parentNode;
        var label = parent.querySelector("label");

        if (field.type === "hidden") {
            field.remove();
            return;
        }

        if (label) {
            var labelText = label.textContent.trim();
            label.remove();
        } else {
            var labelText = '';
        }

        var valueText = "";
        if (field.tagName === "SELECT") {
            valueText = field.options[field.selectedIndex]?.text || "";
        } else if (field.type === "checkbox") {
            valueText = field.checked ? "&#10004; Yes" : "&#10008; No";  // Using HTML entities
        } else {
            valueText = field.value;
        }

        var formattedRow = document.createElement("div");
        formattedRow.classList.add("formatted-row");
        formattedRow.innerHTML = `<strong class="label">${labelText}</strong> - <span class="value">${valueText}</span>`;

        parent.replaceChild(formattedRow, field);
    });

    // Create HTML content for PDF
    var htmlContent = `
        <html>
        <head>
            <title>Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20mm; }
                .print-container { font-size: 16px; line-height: 1.6; }
                .print-container h2 { text-align: center; border-bottom: 2px solid #333; padding-bottom: 5px; }
                .formatted-row { padding: 8px 0; border-bottom: 1px dashed #ccc; }
                .label { font-weight: bold; margin-right: 5px; }
                .value { font-weight: normal; }
                .no-print2 { display: none !important; }
                .no-print3 { display: none !important; }
                #formSubmit { display:none !important; }
            </style>
        </head>
        <body>
            <div class="print-container">${content.innerHTML}</div>
        </body>
        </html>
    `;

    // alert(htmlContent);
   let student_id = document.getElementById('student_id').value;
 
//    alert(student_id);

    // Send the HTML content to the server for PDF generation and email sending
//     fetch("{{ route('Accidents.sendEmail') }}"
// , {
//         method: 'POST',
//         headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     },
//         body: JSON.stringify({
//             html_content: htmlContent,
//             student_id:student_id// Assuming you have a student ID field
//         })
//     })
//     .then(response => response.json())
//     .then(data => {
//         hideLoading();
//         if (data.success) {
//             showAlert('success', 'Report sent successfully to parent!');
//         } else {
//             showAlert('error', 'Failed to send report: ' + data.message);
//         }
//     })
//     .catch(error => {
//         hideLoading();
//         showAlert('error', 'An error occurred while sending the report.');
//         console.error('Error:', error);
//     });


    $.ajax({
        url: "{{ route('Accidents.sendEmail') }}",
        type: "POST",
        dataType: "json",
           headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     },
        data: {
            html_content: htmlContent,
            student_id: student_id
        },
        success: function (data) {
            hideLoading();
            if (data.success) {
                showAlert('success', 'Report sent successfully to parent!');
            } else {
                showAlert('error', 'Failed to send report: ' + data.message);
            }
        },
        error: function (xhr, status, error) {
            hideLoading();
            showAlert('error', 'An error occurred while sending the report.');
            console.error('AJAX Error:', error);
        }
    });
}

function showLoading(message) {
    // Create or show a loading indicator
    if (!document.getElementById('loading-overlay')) {
        const overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;justify-content:center;align-items:center;z-index:9999;';
        
        const spinner = document.createElement('div');
        spinner.style.cssText = 'background:white;padding:20px;border-radius:5px;text-align:center;';
        spinner.innerHTML = `<div class="spinner"></div><p>${message}</p>`;
        
        overlay.appendChild(spinner);
        document.body.appendChild(overlay);
    } else {
        document.getElementById('loading-overlay').style.display = 'flex';
    }
}

function hideLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) overlay.style.display = 'none';
}

function showAlert(type, message) {
    // Create or show an alert message
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = message;
    alertDiv.style.cssText = 'position:fixed;top:20px;right:20px;padding:15px;border-radius:5px;z-index:9999;';
    
    if (type === 'success') {
        alertDiv.style.background = '#d4edda';
        alertDiv.style.color = '#155724';
    } else {
        alertDiv.style.background = '#f8d7da';
        alertDiv.style.color = '#721c24';
    }
    
    document.body.appendChild(alertDiv);
    
    // Remove the alert after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

</script>

@endpush