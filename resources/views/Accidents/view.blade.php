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

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .form-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        border: 1px solid #dee2e6;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    h1,
    h2 {
        color: #007bff;
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
    }

    .::after {
        content: '*';
        color: red;
        margin-left: 5px;
    }

    .body-diagram {
        max-width: 200px;
        margin: 10px auto;
    }

    .no-print {
        display: block;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .form-container {
            margin: 0;
            box-shadow: none;
        }

        .form-section {
            border: none;
        }

        .formatted-row {
            padding: 8px 0;
            border-bottom: 1px dashed #ccc;
        }

        .label {
            font-weight: bold;
            margin-right: 5px;
        }

        .value {
            font-weight: normal;
        }
    }

    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px;
        border-radius: 5px;
        z-index: 9999;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
    }
</style>
@endsection
@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px; margin-top: -60px;">


    <button onclick="printMainContent()" class="btn btn-success print-button no-print "
        style="margin-inline:0.5rem;">Print
        Pages&nbsp;<i class="fa-solid fa-print fa-beat-fade"></i></button>




    @if(Auth::user()->userType != 'Parent')
    <button onclick="sendReportToParent()" class="btn btn-info email-button no-print ml-2">
        Send to Parent <i class="fa-solid fa-envelope fa-beat-fade"></i>
    </button>
    @endif




</div>
<hr class="mt-5">

<main>



    <div class="container form-container" id="printArea">


        <div class="row mt-1" style="color:#0056b3">
            <div class="col-sm-12 mt-1" style="color:#0056b3">
                <h3 style="color:#0056b3;margin-left: 19%;"><b>Incident, Injury, Trauma and
                        Illness Record</b></h3>
            </div>
        </div>

        <form action="#!" method="post" id="acc-form" enctype="multipart/form-data" autocomplete="off" class="mt-5">
            @csrf
            <input type="hidden" name="centerid" value="{{ $AccidentInfo->centerid ?? '' }}">
            <input type="hidden" name="roomid" value="{{ $AccidentInfo->roomid ?? '' }}">
            <input type="hidden" name="student_id" id="student_id" value="{{ $AccidentInfo->childid ?? '' }}">

            <!-- Details of person completing this record -->
            <h2>Details of person completing this record</h2>
            <div class="form-section">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label ">Name</label>
                        <input type="text" class="form-control" name="person_name"
                            value="{{ $AccidentInfo->person_name ?? '' }}"
                            placeholder="{{ $AccidentInfo->person_name ?? 'Enter name' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label ">Position/role</label>
                        <input type="text" class="form-control" name="person_role"
                            value="{{ $AccidentInfo->person_role ?? '' }}"
                            placeholder="{{ $AccidentInfo->person_role ?? 'Enter position/role' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label ">Service name</label>
                        <input type="text" class="form-control" name="service_name"
                            value="{{ $AccidentInfo->service_name ?? '' }}"
                            placeholder="{{ $AccidentInfo->service_name ?? 'Enter service name' }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="date_record_made" class="custom-label">Date Record was made</label>
                        <input type="" class="form-control custom-input @error('made_record_date') is-invalid @enderror"
                            id="date_record_made" name="made_record_date"
                            value="{{ old('made_record_date', \Carbon\Carbon::parse($AccidentInfo->made_record_date)->format('d-m-Y')) }}">
                    </div>




                    <div class="col-md-6">
                        <label for="made_record_time" class="custom-label">Time record was made</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="time" id="made_record_time"
                                value="{{ !empty($AccidentInfo->made_record_time) ? \Carbon\Carbon::parse($AccidentInfo->made_record_time)->format('h:i A') : '' }}">
                        </div>
                    </div>





                    <div class="col-md-6">
                        <label class="form-label ">Signature</label>
                        <div id="person_sign">
                            <input type="hidden" name="person_sign" id="person_sign_txt"
                                value="{{ $AccidentInfo->made_person_sign ?? '' }}">
                            <img src="{{ $AccidentInfo->made_person_sign ?? '' }}" height="100px" width="400px"
                                id="person_sign_img" class="border rounded">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Child details -->
            <h2>Child details</h2>
            <div class="form-section">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label ">Child's full name</label>
                        <input type="text" class="form-control" id="age" name="child_age"
                            value="{{ $AccidentInfo->child_name }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label ">Date of birth</label>
                        <input type="date" class="form-control" id="birthdate" name="child_dob"
                            value="{{ isset($AccidentInfo->child_dob) ? \Carbon\Carbon::parse($AccidentInfo->child_dob)->format('Y-m-d') : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label ">Age</label>
                        <input type="number" class="form-control" id="age" name="child_age"
                            value="{{ $AccidentInfo->child_age ?? '' }}">
                    </div>


                    <div class="col-md-6 mt-4">
                        <label class="form-label  d-block">Gender: {{ $AccidentInfo->child_gender }}</label>




                    </div>




                </div>
            </div>

            <!-- Incident/injury/trauma/illness details -->
            <h2>Incident/injury/trauma/illness details</h2>
            <div class="form-section">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label ">Date</label>
                        <input type="" class="form-control" name="incident_date"
                            value="{{Carbon\Carbon::parse($AccidentInfo->incident_date)->format('d-m-Y') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label ">Time</label>
                        <div class="input-group">
                            <input type="time" class="form-control" name="incident_time"
                                value="{{ $AccidentInfo->incident_time ?? '' }}">

                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label ">Location of service</label>
                        <input type="text" class="form-control" name="service_location"
                            value="{{ $AccidentInfo->incident_location ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label ">Location of incident/injury/trauma/illness</label>
                        <input type="text" class="form-control" name="incident_location"
                            value="{{ $AccidentInfo->location_of_incident ?? '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Name of person who witnessed the
                            incident/injury/trauma/illness</label>
                        <input type="text" class="form-control" name="witness_name"
                            value="{{ $AccidentInfo->witness_name ?? '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="witness_date"
                            value="{{ isset($AccidentInfo->witness_date) ? \Carbon\Carbon::parse($AccidentInfo->witness_date)->format('Y-m-d') : '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Witness signature</label>
                        <div id="witness_sign" class="bordered">
                            <input type="hidden" name="witness_sign" id="witness_sign_txt"
                                value="{{ $AccidentInfo->witness_sign ?? '' }}">
                            <img src="{{ $AccidentInfo->witness_sign ?? '' }}" height="100px" width="400px"
                                id="witness_sign_img" class="border rounded">

                        </div>
                    </div>

                </div>
                <div class="mb-3">
                    <label class="form-label ">Details of incident/injury/trauma/illness</label>
                    <textarea class="form-control" name="gen_actyvt"
                        rows="4">{{ $AccidentInfo->details_injury ?? '' }}</textarea>
                </div>
            </div>

            <!-- Circumstances -->
            <h2>Circumstances</h2>
            <div class="form-section">
                <div class="mb-3">
                    <label class="form-label">Circumstances leading to the incident/injury/trauma/illness, including any
                        apparent symptoms</label>
                    <textarea class="form-control" name="illness_symptoms"
                        rows="4">{{ $AccidentInfo->circumstances_leading ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Circumstances if child appeared to be missing or otherwise unaccounted for
                        (incl. duration, who found child, etc.)</label>
                    <textarea class="form-control" name="missing_unaccounted"
                        rows="4">{{ $AccidentInfo->circumstances_child_missingd ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Circumstances if child appeared to have been taken or removed from service
                        or was locked in/out of service (incl. who took the child, duration)</label>
                    <textarea class="form-control" name="taken_removed"
                        rows="4">{{ $AccidentInfo->circumstances_child_removed ?? '' }}</textarea>
                </div>
            </div>

            <!-- Nature of injury/trauma/illness -->
            <h2>Nature of injury/trauma/illness</h2>
            <div class="form-section">
                <div class="row">


                    <label class="form-label ml-3">Indicate the part of the body affected on this diagram</label>
                    <span class="col-md-12">
                        <img src="{{ $AccidentInfo->injury_image }}" alt="Injury Image" class="img-fluid border rounded"
                            style="max-width: 100%; height: auto; display: block;margin-left: 190px;">
                    </span>



                    <div class="col-md-6 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="abrasion" id="abrasion" value="1" {{
                                isset($AccidentInfo->abrasion) && $AccidentInfo->abrasion == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="abrasion">Abrasion / scrape</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="allergic_reaction"
                                id="allergicReaction" value="1" {{ isset($AccidentInfo->allergic_reaction) &&
                            $AccidentInfo->allergic_reaction == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="allergicReaction">Allergic reaction (incl.
                                gastrointestinal) (not anaphylaxis)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="amputation" id="amputation" value="1"
                                {{ isset($AccidentInfo->amputation) && $AccidentInfo->amputation == 1 ? 'checked' : ''
                            }}>
                            <label class="form-check-label" for="amputation">Amputation</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="anaphylaxis" id="anaphylaxis"
                                value="1" {{ isset($AccidentInfo->anaphylaxis) && $AccidentInfo->anaphylaxis == 1 ?
                            'checked' : '' }}>
                            <label class="form-check-label" for="anaphylaxis">Anaphylaxis</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="asthma" id="asthma" value="1" {{
                                isset($AccidentInfo->asthma) && $AccidentInfo->asthma == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="asthma">Asthma / respiratory</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bite_wound" id="biteWound" value="1"
                                {{ isset($AccidentInfo->bite_wound) && $AccidentInfo->bite_wound == 1 ? 'checked' : ''
                            }}>
                            <label class="form-check-label" for="biteWound">Bite wound</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="bruise" id="bruise" value="1" {{
                                isset($AccidentInfo->bruise) && $AccidentInfo->bruise == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="bruise">Bruise</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="broken_bone" id="brokenBone" value="1"
                                {{ isset($AccidentInfo->broken_bone) && $AccidentInfo->broken_bone == 1 ? 'checked' : ''
                            }}>
                            <label class="form-check-label" for="brokenBone">Broken bone / fracture /
                                dislocation</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="burn" id="burn" value="1" {{
                                isset($AccidentInfo->burn) && $AccidentInfo->burn == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="burn">Burn / sunburn</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="choking" id="choking" value="1" {{
                                isset($AccidentInfo->choking) && $AccidentInfo->choking == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="choking">Choking</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="concussion" id="concussion" value="1"
                                {{ isset($AccidentInfo->concussion) && $AccidentInfo->concussion == 1 ? 'checked' : ''
                            }}>
                            <label class="form-check-label" for="concussion">Concussion</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crush" id="crush" value="1" {{
                                isset($AccidentInfo->crush) && $AccidentInfo->crush == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="crush">Crush / jam</label>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cut" id="cut" value="1" {{
                                isset($AccidentInfo->cut) && $AccidentInfo->cut == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="cut">Cut / open wound</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="drowning" id="drowning" value="1" {{
                                isset($AccidentInfo->drowning) && $AccidentInfo->drowning == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="drowning">Drowning (non-fatal)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="electric_shock" id="electricShock"
                                value="1" {{ isset($AccidentInfo->electric_shock) && $AccidentInfo->electric_shock == 1
                            ? 'checked' : '' }}>
                            <label class="form-check-label" for="electricShock">Electric shock</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="eye_injury" id="eyeInjury" value="1"
                                {{ isset($AccidentInfo->eye_injury) && $AccidentInfo->eye_injury == 1 ? 'checked' : ''
                            }}>
                            <label class="form-check-label" for="eyeInjury">Eye injury</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="infectious_disease"
                                id="infectiousDisease" value="1" {{ isset($AccidentInfo->infectious_disease) &&
                            $AccidentInfo->infectious_disease == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="infectiousDisease">Infectious disease</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="high_temperature" id="highTemperature"
                                value="1" {{ isset($AccidentInfo->high_temperature) && $AccidentInfo->high_temperature
                            == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="highTemperature">High temperature</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="ingestion" id="ingestion" value="1" {{
                                isset($AccidentInfo->ingestion) && $AccidentInfo->ingestion == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="ingestion">Ingestion / inhalation / insertion</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="internal_injury" id="internalInjury"
                                value="1" {{ isset($AccidentInfo->internal_injury) && $AccidentInfo->internal_injury ==
                            1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="internalInjury">Internal injury / infection</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="poisoning" id="poisoning" value="1" {{
                                isset($AccidentInfo->poisoning) && $AccidentInfo->poisoning == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="poisoning">Poisoning</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="rash" id="rash" value="1" {{
                                isset($AccidentInfo->rash) && $AccidentInfo->rash == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="rash">Rash</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="respiratory" id="respiratory"
                                value="1" {{ isset($AccidentInfo->respiratory) && $AccidentInfo->respiratory == 1 ?
                            'checked' : '' }}>
                            <label class="form-check-label" for="respiratory">Respiratory</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="seizure" id="seizure" value="1" {{
                                isset($AccidentInfo->seizure) && $AccidentInfo->seizure == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="seizure">Seizure / unconscious / convulsion</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="other" id="other" value="1" {{
                                isset($AccidentInfo->other) && $AccidentInfo->other == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="other">Other (please specify)</label>
                            <input type="text" class="form-control mt-1" name="remarks" id="otherSpecify"
                                value="{{ $AccidentInfo->remarks ?? '' }}"
                                style="{{ isset($AccidentInfo->other) && $AccidentInfo->other == 1 ? 'display:block;' : 'display:none;' }}">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Taken -->
            <h2>Action Taken</h2>
            <div class="form-section">
                <div class="mb-3">
                    <label class="form-label ">Details of action taken (including first aid,
                        administration of medication, etc.)</label>
                    <textarea class="form-control" name="action_taken"
                        rows="4">{{ $AccidentInfo->action_taken ?? '' }}</textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Did emergency services attend?</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="emrg_serv_attend" id="emergencyYes"
                                    value="yes" {{ ($AccidentInfo->emrg_serv_attend ?? '') == 'yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="emergencyYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="emrg_serv_attend" id="emergencyNo"
                                    value="no" {{ ($AccidentInfo->emrg_serv_attend ?? '') == 'no' ? 'checked' : '' }}>
                                <label class="form-check-label" for="emergencyNo">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Time emergency services contacted</label>
                        <div class="input-group">
                            <input type="time" class="form-control" name="emrg_contact_time"
                                value="{{ $AccidentInfo->emrg_serv_time ?? '' }}">




                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Time emergency services arrived</label>
                        <div class="input-group">
                            <input type="time" class="form-control" name="emrg_arrived_time"
                                value="{{ $AccidentInfo->emrg_serv_arrived ?? '' }}">

                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Was medical attention sought from a registered practitioner /
                        hospital?</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="med_attention" id="medicalYes"
                                value="yes" {{ ($AccidentInfo->med_attention ?? '') == 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="medicalYes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="med_attention" id="medicalNo" value="no"
                                {{ ($AccidentInfo->med_attention ?? '') == 'no' ? 'checked' : '' }}>
                            <label class="form-check-label" for="medicalNo">No</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">If yes to either of the above, provide details</label>
                    <textarea class="form-control" name="med_attention_details"
                        rows="4">{{ $AccidentInfo->med_attention_details ?? '' }}</textarea>
                </div>


                <!-- Prevention -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="provideDetails_minimise">Have any steps been taken to prevent or minimise
                            this
                            type of incident in the future? If yes, provide details.</label>
                        <textarea class="form-control custom-input" id="provideDetails_minimise"
                            name="provideDetails_minimise">{{ old('provideDetails_minimise', $AccidentInfo->provideDetails_minimise) }}</textarea>
                    </div>
                </div>
            </div>
            <!-- Notifications -->
            <h2>Notifications (including attempted notifications)</h2>
            <div class="form-section">
                <div class="row">



                    <div class="col-md-6 mb-3">
                        <label class="form-label">Parent/guardian/carer</label>
                        <input type="text" class="form-control custom-input" id="parentname" name="parent1_name"
                            value="{{ old('parent1_name', $AccidentInfo->parent1_name) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="carers_date" class="form-label">Date (Parent/guardian/carer)</label>
                        <input type="date" class="form-control shadow-sm custom-input" id="carers_date"
                            name="carers_date"
                            value="{{ old('carers_date', $AccidentInfo->carers_date ? \Carbon\Carbon::parse($AccidentInfo->carers_date)->format('Y-m-d') : '') }}">
                    </div>






                    <div class="col-md-6 mb-3">
                        <label for="carers_time" class="form-label">Time (Parent/guardian/carer)</label>
                        <input type="time" class="form-control shadow-sm custom-input" id="carers_time"
                            name="carers_time"
                            value="{{ old('carers_time', $AccidentInfo->carers_time ? \Carbon\Carbon::parse($AccidentInfo->carers_time)->format('H:i') : '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="director_educator_coordinator"
                            class="form-label">Director/educator/coordinator</label>
                        <input type="text" class="form-control shadow-sm custom-input"
                            id="director_educator_coordinator" name="director_educator_coordinator"
                            value="{{ old('director_educator_coordinator', $AccidentInfo->director_educator_coordinator) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="educator_date" class="form-label">Date
                            (Director/educator/coordinator)</label>
                        <input type="date" class="form-control shadow-sm custom-input" id="educator_date"
                            name="educator_date"
                            value="{{ old('educator_date', $AccidentInfo->educator_date ? \Carbon\Carbon::parse($AccidentInfo->educator_date)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="educator_time" class="form-label">Time
                            (Director/educator/coordinator)</label>
                        <input type="time" class="form-control shadow-sm custom-input" id="educator_time"
                            name="educator_time"
                            value="{{ old('educator_time', $AccidentInfo->educator_time ? \Carbon\Carbon::parse($AccidentInfo->educator_time)->format('H:i') : '') }}">
                    </div>

                </div>


                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="other_agency" class="form-label">Other agency (if applicable)</label>
                        <input type="text" class="form-control shadow-sm custom-input" id="other_agency"
                            name="other_agency" value="{{ old('other_agency', $AccidentInfo->other_agency) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="other_agency_date" class="form-label">Date (Other agency)</label>
                        <input type="date" class="form-control shadow-sm custom-input" id="other_agency_date"
                            name="other_agency_date"
                            value="{{ old('other_agency_date', $AccidentInfo->other_agency_date ? \Carbon\Carbon::parse($AccidentInfo->other_agency_date)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="other_agency_time" class="form-label">Time (Other agency)</label>
                        <input type="time" class="form-control shadow-sm custom-input" id="other_agency_time"
                            name="other_agency_time"
                            value="{{ old('other_agency_time', $AccidentInfo->other_agency_time ? \Carbon\Carbon::parse($AccidentInfo->other_agency_time)->format('H:i') : '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="regulatory_authority" class="form-label">Regulatory authority (if
                            applicable)</label>
                        <input type="text" class="form-control shadow-sm custom-input" id="regulatory_authority"
                            name="regulatory_authority"
                            value="{{ old('regulatory_authority', $AccidentInfo->regulatory_authority) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="regulatory_authority_date" class="form-label">Date (Regulatory
                            authority)</label>
                        <input type="date" class="form-control shadow-sm custom-input" id="regulatory_authority_date"
                            name="regulatory_authority_date"
                            value="{{ old('regulatory_authority_date', $AccidentInfo->regulatory_authority_date ? \Carbon\Carbon::parse($AccidentInfo->regulatory_authority_date)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="regulatory_authority_time" class="form-label">Time (Regulatory
                            authority)</label>
                        <input type="time" class="form-control shadow-sm custom-input" id="regulatory_authority_time"
                            name="regulatory_authority_time"
                            value="{{ old('regulatory_authority_time', $AccidentInfo->regulatory_authority_time ? \Carbon\Carbon::parse($AccidentInfo->regulatory_authority_time)->format('H:i') : '') }}">
                    </div>
                </div>
            </div>

            <!-- Parental acknowledgement -->
            <h2>Parental acknowledgement</h2>
            <div class="form-section">
                <div class="mb-3">
                    <label class="form-label">I,</label>
                    <input type="text" class="form-control" name="ack_parent_name"
                        value="{{ $AccidentInfo->ack_parent_name ?? '' }}" placeholder="Name of parent/guardian/carer">
                    <div class="mt-2">
                        <label class="form-label">have been notified of my child's</label>
                        <div class="d-flex flex-wrap gap-3 mt-1">
                            <div class="form-check">
                                &nbsp;
                                <input class="form-check-input" type="checkbox" name="ack_incident" id="ackIncident"
                                    value="1" {{ isset($AccidentInfo->ack_incident) && $AccidentInfo->ack_incident == 1
                                ? 'checked' : '' }}>
                                <label class="form-check-label" for="ackIncident"> &nbsp;incident</label>
                            </div>
                            <div class="form-check">
                                &nbsp;
                                <input class="form-check-input" type="checkbox" name="ack_injury" id="ackInjury"
                                    value="1" {{ isset($AccidentInfo->ack_injury) && $AccidentInfo->ack_injury == 1 ?
                                'checked' : '' }}>
                                <label class="form-check-label" for="ackInjury">&nbsp;injury</label>
                            </div>
                            <div class="form-check">
                                &nbsp;
                                <input class="form-check-input" type="checkbox" name="ack_trauma" id="ackTrauma"
                                    value="1" {{ isset($AccidentInfo->ack_trauma) && $AccidentInfo->ack_trauma == 1 ?
                                'checked' : '' }}>
                                <label class="form-check-label" for="ackTrauma">&nbsp;trauma</label>
                            </div>
                            <div class="form-check">
                                &nbsp;
                                <input class="form-check-input" type="checkbox" name="ack_illness" id="ackIllness"
                                    value="1" {{ isset($AccidentInfo->ack_illness) && $AccidentInfo->ack_illness == 1 ?
                                'checked' : '' }}>
                                <label class="form-check-label" for="ackIllness">&nbsp;illness</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="ack_date"
                            value="{{ isset($AccidentInfo->ack_date) ? \Carbon\Carbon::parse($AccidentInfo->ack_date)->format('Y-m-d') : '' }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="RegulatoryauthorityTime">Time</label>
                        <input type="time" class="form-control custom-input" id="RegulatoryauthorityTime"
                            name="ack_time"
                            value="{{ old('ack_time', $AccidentInfo->ack_time ? \Carbon\Carbon::parse($AccidentInfo->ack_time)->format('H:i') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"> Final Signature</label>
                        <div id="parent_sign">
                            <input type="hidden" name="parent_sign" id="parent_sign_txt"
                                value="{{ $AccidentInfo->final_sign ?? '' }}">
                            <img src="{{ $AccidentInfo->final_sign ?? '' }}" height="100px" width="400px"
                                id="parent_sign_img" class="border rounded">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional notes -->
            <h2>Additional notes</h2>
            <div class="form-section">
                <textarea class="form-control" name="add_notes" rows="4">{{ $AccidentInfo->add_notes ?? '' }}</textarea>
            </div>

            <div class="mt-4 no-print" id="formSubmit">
                {{-- <button type="button" id="form-submit" class="btn btn-primary">Save</button> --}}
                <button type="button" onclick="printMainContent()" class="btn btn-secondary">Print</button>
                <button type="button" onclick="sendReportToParent()" class="btn btn-success">Send to Parent</button>
                {{-- <button type="reset" class="btn btn-outline-secondary">Clear Form</button> --}}
            </div>
        </form>
    </div>



</main>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.signature/1.2.1/jquery.signature.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
            $('.js-example-basic-single').select2();
            var sig = $('#sig').signature();

            $('#btnSignature').on('click', function() {
                let _identity = $("#identityVal").val();
                let _signature = $('#sig').signature('toDataURL');
                if (_identity == "person_sign") {
                    $('#person_sign_img').attr('src', _signature).show();
                    $('#person_sign_txt').val(_signature);
                } else if (_identity == "witness_sign") {
                    $('#witness_sign_img').attr('src', _signature).show();
                    $('#witness_sign_txt').val(_signature);
                } else if (_identity == "parent_sign") {
                    $('#parent_sign_img').attr('src', _signature).show();
                    $('#parent_sign_txt').val(_signature);
                }
                $('#sig').signature('clear');
                $('#signModal').modal('hide');
            });

            $(document).on('show.bs.modal', '#signModal', function (event) {
                var button = $(event.relatedTarget);
                var identity = button.data('identity');
                $("#identityVal").val(identity);
            });

            $('input[name="other"]').on('click', function() {
                $("#otherSpecify").toggle(this.checked);
            });

            $("#childid").on("change", function() {
                let _val = $(this).val();
                if (_val != "") {
                    $.ajax({
                        url: "{{ route('Accidents.getCenterRooms') }}",
                        type: 'post',
                        data: {'childid': _val},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    .done(function(json) {
                        var res = $.parseJSON(json);
                        if (res.Status == "SUCCESS") {
                            $("#birthdate").val(res.Child.dob);
                            $("#age").val(res.Child.age);
                            if (res.Child.gender == "Male") {
                                $("#male").prop('checked', true);
                                $("#female").prop('checked', false);
                                $("#other").prop('checked', false);
                            } else if (res.Child.gender == "Female") {
                                $("#male").prop('checked', false);
                                $("#female").prop('checked', true);
                                $("#other").prop('checked', false);
                            } else {
                                $("#male").prop('checked', false);
                                $("#female").prop('checked', false);
                                $("#other").prop('checked', true);
                            }
                        }
                    });
                }
            });
        });

        var canvas = new fabric.Canvas('c', { isDrawingMode: true });
        fabric.Image.fromURL("{{ asset('assets/images/baby.jpg') }}", function(myImg) {
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
            var pngURL = canvas.toDataURL();
            $("#injury-image").val(pngURL);
        }

        $("#form-submit").click(function(event) {
            saveImage();
            $('#acc-form').submit();
        });

        function printMainContent() {
            var content = document.getElementById("printArea").cloneNode(true);
            $('#formSubmit').remove();

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
                    valueText = field.checked ? "✔ Yes" : "✖ No";
                } else if (field.type === "radio") {
                    valueText = field.checked ? field.value : "";
                } else {
                    valueText = field.value;
                }

                var formattedRow = document.createElement("div");
                formattedRow.classList.add("formatted-row");
                formattedRow.innerHTML = `<strong class="label">${labelText}</strong> - <span class="value">${valueText}</span>`;

                parent.replaceChild(formattedRow, field);
            });

            content.querySelectorAll("canvas").forEach(canvas => {
                var img = document.createElement("img");
                img.src = canvas.toDataURL();
                img.style.maxWidth = "100%";
                img.style.height = "auto";
                canvas.parentNode.replaceChild(img, canvas);
            });

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
                        .no-print { display: none !important; }
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
            showLoading("Preparing PDF and sending email...");
            var content = document.getElementById("printArea").cloneNode(true);

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
                    valueText = field.checked ? "✔ Yes" : "✖ No";
                } else if (field.type === "radio") {
                    valueText = field.checked ? field.value : "";
                } else {
                    valueText = field.value;
                }

                var formattedRow = document.createElement("div");
                formattedRow.classList.add("formatted-row");
                formattedRow.innerHTML = `<strong class="label">${labelText}</strong> - <span class="value">${valueText}</span>`;

                parent.replaceChild(formattedRow, field);
            });

            content.querySelectorAll("canvas").forEach(canvas => {
                var img = document.createElement("img");
                img.src = canvas.toDataURL();
                img.style.maxWidth = "100%";
                img.style.height = "auto";
                canvas.parentNode.replaceChild(img, canvas);
            });

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
                        .no-print { display: none !important; }
                    </style>
                </head>
                <body>
                    <div class="print-container">${content.innerHTML}</div>
                </body>
                </html>
            `;

            let student_id = document.getElementById('student_id').value;

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
                success: function(data) {
                    hideLoading();
                    if (data.success) {
                        showAlert('success', 'Report sent successfully to parent!');
                    } else {
                        showAlert('error', 'Failed to send report: ' + data.message);
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    showAlert('error', 'An error occurred while sending the report.');
                    console.error('AJAX Error:', error);
                }
            });
        }

        function showLoading(message) {
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
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
</script>
