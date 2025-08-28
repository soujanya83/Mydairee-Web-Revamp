@extends('layout.master')
@section('title', 'Create Announcement')
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


    main {
        padding-block: 4em;
        padding-inline: 2em;
    }

    @media screen and (max-width: 600px) {
        main {
            padding-block: 4em;
            padding-inline: 0;
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

    .modal-body {
        padding: 0px 30px;
    }

    #person_sign {
        display: none;
    }

    #witness_sign {
        display: none;
    }

    #incharge_sign {
        display: none;
    }

    #supervisor_sign {
        display: none;
    }

    .check-control {
        width: 30px;
    }

    .select2 {
        width: 100% !important;
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
        display: none;
        /* Hide the default radio circle */
    }

    .radio-pill:hover {
        background-color: #e0e0e0;
    }

    .radio-pill input[type="radio"]:checked+label,
    .radio-pill input[type="radio"]:checked~span,
    .radio-pill input[type="radio"]:checked~* {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    input[type="radio"]:checked+.radio-pill {
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

    .checkbox-pill input:checked+.slider {
        background-color: #28a745;
        /* ON - green */
    }

    .checkbox-pill input:checked+.slider:before {
        transform: translateX(24px);
    }

    .injuiry-ul {
        list-style: none;
    }
</style>
@endsection
@section('content')
<hr class="mt-2">
<main>
    <div class="container-fluid">

        <div class="row" style="margin-top:-50px">
            <h3 class="service-title text-primary">INCIDENT, INJURY, TRAUMA, & ILLNESS RECORD</h3>

            <div class="col-12 mb-5 card pt-2">

               

                <form action="{{ route('Accidents.saveAccident') }}" class="flexDirColoumn" method="post" id="acc-form"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf

                    <input type="hidden" name="centerid" value="{{ $centerid }}">
                    <input type="hidden" name="roomid" value="{{ $roomid }}">
                    <input type="hidden" name="id" value="{{ $AccidentInfo->id }}">

                    <div class="row" style="background-color: #0056b3;color:#fff">
                        <div class="col-sm-12 mt-1" style="background-color: #0056b3;color:#fff">
                            <h5 style="background-color: #0056b3;color:#fff">Details of person completing this record
                            </h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label for="name" class="custom-label">Name</label>
                            <input type="text"
                                class="form-control custom-input @error('person_name') is-invalid @enderror" id="name"
                                name="person_name" value="{{ old('person_name', $AccidentInfo->person_name) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role" class="custom-label">Position / Role</label>
                            <input type="text"
                                class="form-control custom-input @error('person_role') is-invalid @enderror" id="role"
                                name="person_role" value="{{ old('person_role', $AccidentInfo->person_role) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="service_name" class="custom-label">Service Name</label>
                            <input type="text"
                                class="form-control custom-input @error('service_name') is-invalid @enderror"
                                id="service_name" name="service_name"
                                value="{{ old('service_name', $AccidentInfo->service_name) }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="date_record_made" class="custom-label">Date Record was made</label>
                            <input type="date"
                                class="form-control custom-input @error('made_record_date') is-invalid @enderror"
                                id="date_record_made" name="made_record_date"
                                value="{{ old('made_record_date', \Carbon\Carbon::parse($AccidentInfo->made_record_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="date_record_time" class="custom-label">Time record was made</label>
                            <input type="time"
                                class="form-control custom-input @error('made_record_time') is-invalid @enderror"
                                id="date_record_time" name="made_record_time"
                                value="{{ old('made_record_time', \Carbon\Carbon::parse($AccidentInfo->made_record_time)->format('H:i')) }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="form-group col-md-12">
                                <label>
                                    Author Signature
                                    <span class="editbtn" data-toggle="modal" data-target="#signModal"
                                        data-identity="author_sign">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                </label>

                                {{-- Disabled text input just for display --}}
                                <input type="text" class="form-control custom-input" id="author_sign_dt" disabled>

                                <div id="author_sign">
                                    <input type="hidden" name="made_person_sign" id="author_sign_txt"
                                        value="{{ $AccidentInfo->made_person_sign ?? '' }}">

                                    @if (!empty($AccidentInfo->made_person_sign))
                                    <img src="{{ $AccidentInfo->made_person_sign }}" height="120px" width="300px"
                                        id="author_sign_img">
                                    @else
                                    <img src="" height="120px" width="300px" id="author_sign_img" style="display:none;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>





                    <div class="row mt-1" style="background-color: #0056b3;color:#fff">
                        <div class="col-sm-12 mt-1" style="background-color: #0056b3;color:#fff">
                            <h5 style="background-color: #0056b3;color:#fff">Child Details</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label for="childid" class="col-sm-12">Select Child</label>
                            <select name="childid" id="childid" style="height: 44px;"
                                class="w-100 form-control js-example-basic-single custom-input @error('childid') is-invalid @enderror">
                                <option value="">--Select Children--</option>
                                @foreach ($Childrens as $chobj)
                                <option value="{{ $chobj->id }}" {{ old('childid', $AccidentInfo->childid) ==
                                    $chobj->id
                                    ? 'selected' : '' }}>
                                    {{ $chobj->details }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" class="form-control" id="childfullname" name="child_name"
                                value="{{ old('child_name', $AccidentInfo->child_name) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="birthdate">Date of Birth</label>
                            <input type="date" class="form-control custom-input" id="birthdate" name="child_dob"
                                value="{{ old('child_dob', \Carbon\Carbon::parse($AccidentInfo->child_dob)->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="age">Age</label>
                            <input type="text" class="form-control custom-input" id="age" name="child_age"
                                value="{{ old('child_age', $AccidentInfo->child_age) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name">Gender</label>
                            <div class="radioFlex">
                                <input type="radio" id="Male" name="child_gender" value="Male" {{ old('child_gender',
                                    $AccidentInfo->child_gender) == 'Male' ? 'checked' : '' }}
                                hidden>
                                <label class="radio-pill" for="Male">Male</label>
                                <input type="radio" id="Female" name="child_gender" value="Female" {{
                                    old('child_gender', $AccidentInfo->child_gender) == 'Female' ? 'checked' : '' }}
                                hidden>
                                <label class="radio-pill" for="Female">Female</label>
                                <input type="radio" id="Others" name="child_gender" value="Others" {{
                                    old('child_gender', $AccidentInfo->child_gender) == 'Others' ? 'checked' : '' }}
                                hidden>
                                <label class="radio-pill" for="Others">Others</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1" style="background-color: #0056b3;color:#fff">
                        <div class="col-sm-12 mt-1" style="background-color: #0056b3;color:#fff">
                            <h5 style="background-color: #0056b3;color:#fff">Incident/injury/trauma/illness Details
                            </h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="incidentdate" class="form-label">Incident/injury/trauma/illness Date</label>
                            <input type="date" class="form-control shadow-sm custom-input" id="incidentdate"
                                name="incident_date"
                                value="{{ old('incident_date', \Carbon\Carbon::parse($AccidentInfo->incident_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="incidenttime" class="form-label">Incident/injury/trauma/illness Time</label>
                            <input type="time" class="form-control shadow-sm custom-input" id="incidenttime"
                                name="incident_time"
                                value="{{ old('incident_time', \Carbon\Carbon::parse($AccidentInfo->incident_time)->format('H:i')) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location of service</label>
                            <input type="text" class="form-control shadow-sm custom-input" id="location"
                                name="incident_location"
                                value="{{ old('incident_location', $AccidentInfo->incident_location) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location_of_incident" class="form-label">Location of
                                incident/injury/trauma/illness</label>
                            <input type="text" class="form-control shadow-sm custom-input" id="location_of_incident"
                                name="location_of_incident"
                                value="{{ old('location_of_incident', $AccidentInfo->location_of_incident) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="witnessname" class="form-label">Name of person who witnessed the
                                incident/injury/trauma/illness</label>
                            <input type="text" class="form-control shadow-sm custom-input" id="witnessname"
                                name="witness_name" value="{{ old('witness_name', $AccidentInfo->witness_name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="witness-date" class="form-label">Witness Date</label>
                            <input type="date" class="form-control shadow-sm custom-input" id="witness-date"
                                name="witness_date"
                                value="{{ old('witness_date', $AccidentInfo->witness_date ? \Carbon\Carbon::parse($AccidentInfo->witness_date)->format('Y-m-d') : '') }}">
                        </div>


                        <div class="form-group col-md-12">
                            <label>
                                Witness Signature
                                <span class="editbtn" data-toggle="modal" data-target="#signModal"
                                    data-identity="witness_sign">
                                    <i class="fas fa-pencil-alt"></i>
                                </span>
                            </label>

                            <input type="text" class="form-control custom-input" id="witness_sign_dt" disabled>

                            <div id="witness_sign">
                                {{-- Hidden input contains old image (URL or base64) --}}
                                <input type="hidden" name="witness_sign" id="witness_sign_txt"
                                    value="{{ $AccidentInfo->witness_sign ?? '' }}">

                                @if (!empty($AccidentInfo->witness_sign))
                                <img src="{{ $AccidentInfo->witness_sign }}" height="120px" width="300px"
                                    id="witness_sign_img">
                                @else
                                <img src="" height="120px" width="300px" id="witness_sign_img" style="display:none;">
                                @endif
                            </div>
                        </div>





                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="details_injury">Details of incident/injury/trauma/illness</label>
                            <textarea class="form-control custom-input" id="details_injury"
                                name="details_injury">{{ old('details_injury', $AccidentInfo->details_injury) }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="circumstances_leading">Circumstances leading to the
                                incident/injury/trauma/illness, including any apparent symptoms</label>
                            <textarea class="form-control custom-input" id="circumstances_leading"
                                name="circumstances_leading">{{ old('circumstances_leading', $AccidentInfo->circumstances_leading) }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="circumstances_child_missing">Circumstances if child appeared to be missing
                                or
                                otherwise unaccounted for (incl. duration, who found child, etc.)</label>
                            <textarea class="form-control custom-input" id="circumstances_child_missing"
                                name="circumstances_child_missingd">{{ old('circumstances_child_missingd', $AccidentInfo->circumstances_child_missingd) }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="circumstances_child_removed">Circumstances if child appeared to have been
                                taken
                                or removed from service or was locked in/out of service (incl. who took the child,
                                duration)</label>
                            <textarea class="form-control custom-input" id="circumstances_child_removed"
                                name="circumstances_child_removed">{{ old('circumstances_child_removed', $AccidentInfo->circumstances_child_removed) }}</textarea>
                        </div>
                    </div>

                    <div class="row mt-1" style="background-color: #0056b3;color:#fff">
                        <div class="col-sm-12 mt-1" style="background-color: #0056b3;color:#fff">
                            <h5 style="background-color: #0056b3;color:#fff">Nature of Injury/ Trauma/ Illness:</h5>
                        </div>
                    </div>
                    {{-- <div class="form-row mt-3">
                        <div class="form-group col-md-12">
                            <div class="svgFlex col-12 row">
                                <span class="col-md-6 col-sm-12">
                                    <canvas id="c" width="500" height="500"></canvas>
                                    <strong style="color:#0056b3"><span>Indicate the part of the body affected on
                                            this
                                            diagram</span></strong>
                                </span>
                                <span class="col-md-6 col-sm-12">
                                    <input type="hidden" name="injury_image" id="injury-image"
                                        value="{{ $AccidentInfo->injury_image }}">
                                    <ul class="col-12 row injuiry-ul">
                                        @php
                                        $injuries = json_decode($AccidentInfo->details_injury, true) ?? [];
                                        @endphp
                                        <li class="col-md-6 col-sm-12">
                                            <label class="checkbox-pill">
                                                <input type="checkbox" name="abrasion" value="1" {{ in_array('abrasion',
                                                    $injuries) ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="pill-label">Abrasion / Scrape</span>
                                        </li>
                                        <li class="col-md-6 col-sm-12">
                                            <label class="checkbox-pill">
                                                <input type="checkbox" name="electric_shock" value="1" {{
                                                    in_array('electric_shock', $injuries) ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="pill-label">Electric Shock</span>
                                        </li>
                                        <!-- Repeat for other injury types -->
                                        <li class="col-md-6 col-sm-12">
                                            <label class="checkbox-pill">
                                                <input type="checkbox" name="other" value="1" id="otherCheckbox" {{
                                                    in_array('other', $injuries) ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="pill-label">Other (Please specify)</span>
                                        </li>
                                        <li class="col-md-12" id="injury-remarks"
                                            style="display: {{ in_array('other', $injuries) ? 'block' : 'none' }};">
                                            <input type="text" name="remarks" class="form-control mt-2 custom-input"
                                                placeholder="Write here..."
                                                value="{{ old('remarks', $AccidentInfo->remarks) }}">
                                        </li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div> --}}

                    <div class="form-row mt-3">
                        <div class="form-group col-md-12">
                            <div class="svgFlex col-12 row">
                                <!-- <span class="col-md-6 col-sm-12">
                                        <div class="canvas-container" style="width: 500px; height: 500px; position: relative; user-select: none;"><canvas id="c" width="625" height="625" class="lower-canvas" style="position: absolute; width: 500px; height: 500px; left: 0px; top: 0px; touch-action: none; user-select: none;"></canvas><canvas class="upper-canvas " width="625" height="625" style="position: absolute; width: 500px; height: 500px; left: 0px; top: 0px; touch-action: none; user-select: none; cursor: crosshair;"></canvas></div>
                                    </span> -->
                                <span class="col-md-6 col-sm-12">
                                    <canvas id="c" width="500" height="500"></canvas>
                                    <strong style="color:#0056b3"><span>Indicate the part of the body affected on
                                            this
                                            diagram</span></strong>
                                </span>
                                <span class="col-md-6 col-sm-12 m-0 p-0">
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
                                                <input type="checkbox" name="{{ $field }}" value="1" {{
                                                    isset($AccidentInfo->$field) && $AccidentInfo->$field == 1 ?
                                                'checked' : '' }}>
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


                    <div class="row mt-1" style="background-color: #0056b3;color:#fff">
                        <div class="col-sm-12 mt-1" style="background-color: #0056b3;color:#fff">
                            <h5 style="background-color: #0056b3;color:#fff">Action Taken</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-12">
                            <label for="takenAction">Details of action taken (including first aid, administration of
                                medication etc.):</label>
                            <textarea class="form-control custom-input" id="takenAction"
                                name="action_taken">{{ old('action_taken', $AccidentInfo->action_taken) }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-label d-block">Did emergency services attend:</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="emrg_serv_attend"
                                                id="emrg_yes" value="Yes" {{ old('emrg_serv_attend',
                                                $AccidentInfo->emrg_serv_attend) == 'Yes'
                                            ? 'checked' : '' }}>
                                            <label class="form-check-label" for="emrg_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            &nbsp; &nbsp; <input class="form-check-input" type="radio"
                                                name="emrg_serv_attend" id="emrg_no" value="No" {{
                                                old('emrg_serv_attend', $AccidentInfo->emrg_serv_attend) == 'No' ?
                                            'checked' : '' }}>
                                            <label class="form-check-label" for="emrg_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="emrg_serv_time" class="form-label">Time emergency services
                                        contacted</label>
                                    <input type="time" class="form-control shadow-sm custom-input" id="emrg_serv_time"
                                        name="emrg_serv_time"
                                        value="{{ old('emrg_serv_time', $AccidentInfo->emrg_serv_time ? \Carbon\Carbon::parse($AccidentInfo->emrg_serv_time)->format('H:i') : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="emrg_serv_arrived" class="form-label">Time emergency services
                                        arrived</label>
                                    <input type="time" class="form-control shadow-sm custom-input"
                                        id="emrg_serv_arrived" name="emrg_serv_arrived"
                                        value="{{ old('emrg_serv_arrived', $AccidentInfo->emrg_serv_arrived ? \Carbon\Carbon::parse($AccidentInfo->emrg_serv_arrived)->format('H:i') : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Was medical attention sought from a registered
                                        practitioner / hospital:</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="med_attention"
                                                id="med_yes" value="Yes" {{ old('med_attention',
                                                $AccidentInfo->med_attention) == 'Yes' ?
                                            'checked' : '' }}>
                                            <label class="form-check-label" for="med_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            &nbsp; &nbsp; <input class="form-check-input" type="radio"
                                                name="med_attention" id="med_no" value="No" {{ old('med_attention',
                                                $AccidentInfo->med_attention) ==
                                            'No' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="med_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="provideDetails">If yes to either of the above, provide details:</label>
                            <textarea class="form-control custom-input" id="provideDetails"
                                name="med_attention_details">{{ old('med_attention_details', $AccidentInfo->med_attention_details) }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="provideDetails_minimise">Have any steps been taken to prevent or minimise
                                this
                                type of incident in the future? If yes, provide details.</label>
                            <textarea class="form-control custom-input" id="provideDetails_minimise"
                                name="provideDetails_minimise">{{ old('provideDetails_minimise', $AccidentInfo->provideDetails_minimise) }}</textarea>
                        </div>
                    </div>

                    <div class="row mt-1" style="background-color: #0056b3;color:#fff">
                        <div class="col-sm-12 mt-1" style="background-color: #0056b3;color:#fff">
                            <h5 style="background-color: #0056b3;color:#fff">Parent/Guardian Notifications
                                (including
                                attempted notifications)</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label for="parentname">Parent/guardian/carer</label>
                            <input type="text" class="form-control custom-input" id="parentname" name="parent1_name"
                                value="{{ old('parent1_name', $AccidentInfo->parent1_name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
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
                            <input type="date" class="form-control shadow-sm custom-input"
                                id="regulatory_authority_date" name="regulatory_authority_date"
                                value="{{ old('regulatory_authority_date', $AccidentInfo->regulatory_authority_date ? \Carbon\Carbon::parse($AccidentInfo->regulatory_authority_date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="regulatory_authority_time" class="form-label">Time (Regulatory
                                authority)</label>
                            <input type="time" class="form-control shadow-sm custom-input"
                                id="regulatory_authority_time" name="regulatory_authority_time"
                                value="{{ old('regulatory_authority_time', $AccidentInfo->regulatory_authority_time ? \Carbon\Carbon::parse($AccidentInfo->regulatory_authority_time)->format('H:i') : '') }}">
                        </div>
                    </div>
                    <div class="row mt-1" style="background-color: #0056b3;color:#fff">
                        <div class="col-sm-12 mt-1" style="background-color: #0056b3;color:#fff">
                            <h5 style="background-color: #0056b3;color:#fff">Parental acknowledgement</h5>
                        </div>
                    </div>
                    <div class="inlineInput mt-3 mb-3">
                        <b>I</b> <input type="text" name="ack_parent_name" class="custom-input"
                            value="{{ old('ack_parent_name', $AccidentInfo->ack_parent_name) }}"> (name of parent /
                        guardian) have been notified of my child’s incident / injury / trauma / illness.
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="RegulatoryauthorityDate">Date</label>
                            <input type="date" class="form-control custom-input" id="RegulatoryauthorityDate"
                                name="ack_date"
                                value="{{ old('ack_date', $AccidentInfo->ack_date ? \Carbon\Carbon::parse($AccidentInfo->ack_date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="RegulatoryauthorityTime">Time</label>
                            <input type="time" class="form-control custom-input" id="RegulatoryauthorityTime"
                                name="ack_time"
                                value="{{ old('ack_time', $AccidentInfo->ack_time ? \Carbon\Carbon::parse($AccidentInfo->ack_time)->format('H:i') : '') }}">
                        </div>
                        {{-- <div class="col-md-12 mb-3">
                            <label class="form-label">Final Signature</label>
                            <input type="text" class="form-control shadow-sm custom-input" data-toggle="modal"
                                data-target="#signModal" data-identity="final_sign" id="final_sign_input"
                                style="cursor: pointer; display: {{ $AccidentInfo->final_sign ? 'none' : 'block' }};"
                                placeholder="Click to add signature" readonly>
                            <div id="final_sign_preview" class="border rounded bg-light p-2 shadow-sm mt-2"
                                style="display: {{ $AccidentInfo->final_sign ? 'block' : 'none' }};">
                                <input type="hidden" name="final_sign" id="final_sign_txt"
                                    value="{{ $AccidentInfo->final_sign }}">
                                <div id="final_sign_container" style="position: relative; display: inline-block;">
                                    <img src="{{ $AccidentInfo->final_sign }}" height="120" width="350"
                                        id="final_sign_img" class="img-thumbnail" alt="Final Signature"
                                        style="display: {{ $AccidentInfo->final_sign ? 'block' : 'none' }};">
                                    <span id="remove_final_sign"
                                        style="position: absolute; top: 5px; right: 8px; cursor: pointer; color: #fff; background: red; border-radius: 50%; padding: 0 8px; font-weight: bold; font-size: 16px; line-height: 20px; display: {{ $AccidentInfo->final_sign ? 'block' : 'none' }};">
                                        ×
                                    </span>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group col-md-12">
                            <label>
                                Final Signature
                                <span class="editbtn" data-toggle="modal" data-target="#signModal"
                                    data-identity="final_sign">
                                    <i class="fas fa-pencil-alt"></i>
                                </span>
                            </label>

                            {{-- Disabled text input just for display --}}
                            <input type="text" class="form-control custom-input" id="final_sign_dt" disabled>

                            <div id="final_sign">
                                <input type="hidden" name="final_sign" id="final_sign_txt"
                                    value="{{ $AccidentInfo->final_sign ?? '' }}">

                                @if (!empty($AccidentInfo->final_sign))
                                <img src="{{ $AccidentInfo->final_sign }}" height="120px" width="300px"
                                    id="final_sign_img">
                                @else
                                <img src="" height="120px" width="300px" id="final_sign_img" style="display:none;">
                                @endif
                            </div>
                        </div>




                    </div>
                    <div class="row mt-1" style="background-color: #0056b3;color:#fff">
                        <div class="col-sm-12 mt-1" style="background-color: #0056b3;color:#fff">
                            <h5 style="background-color: #0056b3;color:#fff">Additional notes</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-12">
                            <textarea class="form-control custom-input" id="takenAction" name="add_notes"
                                rows="8">{{ old('add_notes', $AccidentInfo->add_notes) }}</textarea>
                        </div>
                    </div>
                    <div class="row m-2">
                        <div class="col-sm-12 text-right">
                            <div class="formSubmit">
                                <button type="button" id="form-submit" class="btn btn-success">Update &amp;
                                    Save</button>
                                <a class="btn-warning p-2 rounded"
                                    href="{{ route('Accidents.list', ['centerid' => request()->get('centerid'), 'roomid' => request()->get('roomid')]) }}">Cancel</a>


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
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <input type="hidden" id="identityVal">
            </div>
            <div class="modal-body text-center">
                <div id="sig" class="kbw-signature mx-auto">
                    <span class="col-md-6 col-sm-12 ">
                        <canvas id="d" width="500" height="500" class="border mx-auto"></canvas>
                    </span>
                </div>
                <div class="modal-footer text-right">
                    <br>
                    <button type="button" class="btn btn-default btn-sm btn-danger" data-dismiss="modal">Exit</button>
                    <button type="button" class="btn btn-default btn-sm btn-success " id="btnSignature" data-identity=""
                        data-dismiss="modal">Use</button>
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
    $(document).ready(function(){

    // --- Initial signature check ---
    let src = $('#witness_sign_img').attr('src');
    let personsrc = $('#author_sign_img').attr('src');
    let res_pinc_img = $('#res_pinc_img').attr('src');
    let nsv_sign_img = $('#nsv_sign_img').attr('src');

    if (src && src.trim() !== '') {
        $('#witness_sign').show();
        $('#witness_sign_dt').hide();
    } else if (personsrc && personsrc.trim() !== '') {
        $('#person_sign').show();
        $('#person_sign_dt').hide();
    } else if (res_pinc_img && res_pinc_img.trim() !== '') {
        $('#incharge_sign').show();
        $('#res_pinc_dt').hide();
    } else if (nsv_sign_img && nsv_sign_img.trim() !== '') {
        $('#supervisor_sign').show();
        $('#nom_svs_dt').hide();
    }

    // --- Save signature ---
    $('#btnSignature').on('click', function() {
        let _identity = $("#identityVal").val();

        // Get data from Fabric.js canvas
        var _signature = canvas1.toDataURL({ format: 'png' });

        if (_identity === "person_sign") {
            $('#person_sign').show();
            $('#person_sign_dt').hide();
            $('#person_sign_img').attr('src', _signature);
            $('#person_sign_txt').val(_signature);
        }else if (_identity === "witness_sign") {
    $('#witness_sign').show();

    // Only update if new signature is provided
    if (_signature && _signature.trim() !== "") {
        $('#witness_sign_img').attr('src', _signature).show();
        $('#witness_sign_txt').val(_signature); // overwrite with new base64
    }
}


 else if (_identity === "incharge_sign") {
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

else if (_identity === "final_sign") {
    $('#final_sign_preview').show(); // show wrapper when signature exists
    $('#final_sign_img').attr('src', _signature).show();
    $('#final_sign_txt').val(_signature);
    $('#remove_final_sign').show();
    $('#final_sign_input').hide();
}else if (_identity === "author_sign") {
    $('#author_sign').show(); // show wrapper when signature exists
    $('#author_sign_img').attr('src', _signature).show();
    $('#author_sign_txt').val(_signature);
    $('#remove_author_sign').show();   // optional remove button
    $('#author_sign_input').hide();    // optional input toggle
}



        // Clear the canvas after using
        canvas1.clear();
        canvas1.setBackgroundColor('#ffffff', canvas1.renderAll.bind(canvas1));
    });

    // --- Apply select2 styles ---
    $('.select2-container').addClass('select2-container--bootstrap select2-container--below select2-container--focus');
    $('.select2-container').removeClass('select2-container--default');

    // --- Child details fetch ---
    $("#childid").on("change", function(){
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
    if ($("#childid").val() !== "") {
        $("#childid").trigger("change");
    }

    // --- Other checkbox toggle ---
    $('input[name="other"]').on('click', function(){
        if ($(this).is(':checked')) {
            $("#injury-remarks").show();
        } else {
            $("#injury-remarks").hide();
        }
    });

    // --- Save accident image before submit ---
    $("#form-submit").click(function(event) {
        saveImage();
        $('#acc-form').submit();
    });

}); // END $(document).ready


// ================== FABRIC.JS ===================

// Drawing canvas for injury image
var canvas = new fabric.Canvas('c', {
    isDrawingMode: true,
    width: 500,
    height: 500
});

   enableCircleMode(canvas, 10, "green");

    function enableCircleMode(fCanvas, radius = 15, color = "red") {
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

// Drawing canvas for signatures
var canvas1 = new fabric.Canvas('d', {
    isDrawingMode: true,
    width: 400,
    height: 200,
     backgroundColor: '#ffffff'
});

// Brush settings
canvas.freeDrawingBrush.width = 2;
canvas.freeDrawingBrush.color = '#000000';

canvas1.freeDrawingBrush.width = 2;
canvas1.freeDrawingBrush.color = '#000000';

// Load injury background image
fabric.Image.fromURL("{{ $AccidentInfo->injury_image }}", function(myImg) {
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

// Save injury canvas as image
function saveImage() {
    var jpegURL = canvas.toDataURL({
        format: 'jpeg',
        quality: 0.5,
        multiplier: 0.8
    });
    $("#injury-image").val(jpegURL);
}

// ================== MODAL FIX ===================

// Ensure signature canvas renders when modal opens
$(document).on('show.bs.modal', '#signModal', function (event) {
    var button = $(event.relatedTarget);
    var identity = button.data('identity');
    $("#identityVal").val(identity);

    setTimeout(function () {
        canvas1.calcOffset();
        canvas1.renderAll();
    }, 200); // Wait until modal transition finishes
});


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
        $toast.fadeOut(500, function () {
            $(this).remove();
        });
    }, 3000);

    }
</script>

@endpush
@include('layout.footer')
