@extends('layout.master')
@section('title', 'Accident Create page')
@section('parentPageTitle', 'Accidents')

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

    main {
        padding-top: 1em;
        padding-bottom: 2em;
        padding-inline: 2em;
    }

    @media screen and (max-width: 600px) {
        main {

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
        color: var(--sd-bg, #fff);
        background: var(--sd-accent, #0056b3);
        border-bottom: 2px solid var(--sd-accent, #0056b3);
        padding: 8px 16px 5px 16px;
        border-radius: 6px 6px 0 0;
    }
    .section-heading {
        color: var(--sd-bg, #fff);
        background: var(--sd-accent, #0056b3);
        border-radius: 6px 6px 0 0;
        padding: 8px 16px;
        margin-bottom: 0;
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


        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-22px">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        @endif

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-22px">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif



        <div class="row">
            <h3 class="service-title">INCIDENT, INJURY, TRAUMA, & ILLNESS RECORD</h3>

            <div class="col-12 mb-5 card pt-2">
                <form action="{{ route('Accidents.saveAccident') }}" class="flexDirColoumn" method="post" id="acc-form"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <input type="hidden" name="centerid" value="{{ $centerid }}">
                    <input type="hidden" name="roomid" value="{{ $roomid }}">

                    <div class="row">
                        <div class="col-sm-12 mt-1">
                            <h5 class="section-heading">Details of person completing this record</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label for="name" class="custom-label">Name</label>
                            <input type="text"
                                class="form-control custom-input @error('person_name') is-invalid @enderror" id="name"
                                name="person_name" value="{{ old('person_name') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role" class="custom-label">Position / Role</label>
                            <input type="text"
                                class="form-control custom-input @error('person_role') is-invalid @enderror" id="role"
                                name="person_role" value="{{ old('person_role') }}">
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label for="service_name" class="custom-label">Service Name</label>
                            <input type="text"
                                class="form-control custom-input @error('service_name') is-invalid @enderror"
                                id="service_name" name="service_name" value="{{ old('service_name') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="date_record_made" class="custom-label">Date Record was made</label>
                            <input type="date"
                                class="form-control custom-input @error('made_record_date') is-invalid @enderror"
                                id="date_record_made" name="made_record_date" value="{{ old('made_record_date') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="date_record_time" class="custom-label">Time record was made</label>
                            <input type="time"
                                class="form-control custom-input @error('made_record_time') is-invalid @enderror"
                                id="date_record_time" name="made_record_time" value="{{ old('made_record_time') }}">
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Signature</label>
                            <input type="text" class="form-control colo-md-12 shadow-sm custom-input"
                                data-toggle="modal" data-target="#signModal" data-identity="author_sign"
                                style="cursor: pointer;" readonly>

                            <div id="author_sign" class="border rounded bg-light p-2 shadow-sm" style="display:none;">
                                <input type="hidden" name="made_person_sign" id="author_sign_txt">

                                <div id="author_sign_container" style="position: relative; display: inline-block;">
                                    <img src="" height="120" width="350" id="author_sign_img" class="img-thumbnail"
                                        alt="Author Signature" style="display:none;">

                                    <!-- close button -->
                                    <span id="removeauthor_sign_txt" style="position: absolute; top: 5px; right: 8px;
                                    cursor: pointer; color: #fff; background: red;
                                    border-radius: 50%; padding: 0 8px; font-weight: bold;
                                    font-size: 16px; line-height: 20px; display:none;">
                                        ×
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>





                    <div class="row mt-1">
                        <div class="col-sm-12 mt-1">
                            <h5 class="section-heading">Child Details</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label for="childid" class="col-sm-12">Select Child</label>
                            <select name="childid" id="childid" style="height: 44px;"
                                class="w-100 form-control js-example-basic-single custom-input @error('childid') is-invalid @enderror">
                                <option value="">--Select Children--</option>
                                @foreach ($Childrens as $chobj)
                                <option value="{{ $chobj->id }}" {{ old('childid')==$chobj->id ? 'selected' : '' }}>
                                    {{ $chobj->details }}
                                </option>
                                @endforeach
                            </select>

                            <input type="hidden" class="form-control" id="childfullname" name="child_name"
                                value="{{ old('child_name') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="birthdate">Date of Birth</label>
                            <input type="date" class="form-control custom-input" id="birthdate" name="child_dob"
                                value="{{ old('child_dob') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="age">Age</label>
                            <input type="text" class="form-control custom-input" id="age" name="child_age"
                                value="{{ old('child_age') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">Gender</label>
                            <div class="radioFlex">
                                <input type="radio" id="Male" name="child_gender" value="Male" {{
                                    old('child_gender')=='Male' ? 'checked' : '' }} hidden>
                                <label class="radio-pill" for="Male">Male</label>

                                <input type="radio" id="Female" name="child_gender" value="Female" {{
                                    old('child_gender')=='Female' ? 'checked' : '' }} hidden>
                                <label class="radio-pill" for="Female">Female</label>

                                <input type="radio" id="Others" name="child_gender" value="Others" {{
                                    old('child_gender')=='Others' ? 'checked' : '' }} hidden>
                                <label class="radio-pill" for="Others">Others</label>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-1">
                        <div class="col-sm-12 mt-1">
                            <h5 class="section-heading">Incident/injury/trauma/illness Details</h5>
                        </div>
                    </div>

                    @php
                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                    $incidentDate = old('incident_date', isset($incident) ?
                    \Carbon\Carbon::parse($incident->incident_date)->format('Y-m-d') : $today);
                    @endphp
                    <div class="form-row mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="incidentdate" class="form-label">Incident/injury/trauma/illness Date</label>
                            <input type="date" class="form-control shadow-sm custom-input" id="incidentdate"
                                name="incident_date" value="{{ $incidentDate }}">
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="incidenttime" class="form-label"> Incident/injury/trauma/illness Time</label>
                            <input type="time" class="form-control shadow-sm custom-input" id="incidenttime"
                                name="incident_time">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location of service</label>
                            <input type="text" class="form-control shadow-sm custom-input" id="location"
                                name="incident_location" placeholder="E.g., Playground">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="location_of_incident" class="form-label">Location of incident/
                                injury/trauma/illness</label>
                            <input type="text" class="form-control shadow-sm custom-input" id="location_of_incident"
                                name="location_of_incident">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="witnessname" class="form-label">Name of person who
                                witnessed the incident/injury/trauma/illness</label>
                            <input type="text" class="form-control shadow-sm custom-input" id="witnessname"
                                name="witness_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="witness-date" class="form-label">Witness Date</label>
                            <input type="date" class="form-control shadow-sm custom-input" id="witness-date"
                                name="witness_date">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Witness Signature

                            </label>
                            <input type="text" class="form-control mb-2 shadow-sm custom-input" data-toggle="modal"
                                data-target="#signModal" data-identity="witness_sign" style="cursor: pointer;" readonly>
                            <div id="witness_sign" class="border rounded bg-light p-2 shadow-sm">
                                <input type="hidden" name="witness_sign" id="witness_sign_txt">
                                <div id="witness_sign_container" style="position: relative; display: inline-block;">
                                    <img src="" height="120" width="300" id="witness_sign_img" class="img-thumbnail"
                                        alt="Witness Signature" style="display:none;">

                                    <!-- close button -->
                                    <span id="removewitness_sign_txt" style="position: absolute; top: 5px; right: 8px;
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
                        <div class="form-group col-md-12">
                            <label for="details_injury">Details of incident/
                                injury/trauma/illness</label>
                            <textarea class="form-control custom-input" id="details_injury"
                                name="details_injury"></textarea>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="circumstances_leading">Circumstances leading
                                to the incident/
                                injury/trauma/illness,
                                including any apparent
                                symptoms</label>
                            <textarea class="form-control custom-input" id="circumstances_leading"
                                name="circumstances_leading"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="circumstances_child_missing">Circumstances if
                                child appeared to be
                                missing or otherwise
                                unaccounted for
                                (incl. duration, who
                                found child, etc.)</label>
                            <textarea class="form-control custom-input" id="circumstances_child_missing"
                                name="circumstances_child_missingd"></textarea>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="circumstances_child_removed">Circumstances if child
                                appeared to have been
                                taken or removed from
                                service or was locked
                                in/out of service
                                (incl. who took the
                                child, duration)</label>
                            <textarea class="form-control custom-input" id="circumstances_child_removed"
                                name="circumstances_child_removed"></textarea>
                        </div>
                    </div>



                    <div class="row mt-1">
                        <div class="col-sm-12 mt-1">
                            <h5 class="section-heading">Nature of Injury/ Trauma/ Illness:</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-12">
                            <div class="svgFlex col-12 row ">

                                <span class="col-md-6 col-sm-12">
                                    <canvas id="c" width="500" height="500"></canvas>

                                    <strong style="color:#0056b3"><span>Indicate the part of the body affected
                                            on this diagram</span> </strong>
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
                                            <input type="text" name="remarks" class="form-control mt-2  custom-input"
                                                placeholder="Write here...">
                                        </li>
                                    </ul>

                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-sm-12 mt-1">
                            <h5 class="section-heading">Action Taken</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-12">
                            <label for="takenAction">Details of action taken (including first aid, administration of
                                medication etc.):</label>
                            <textarea class="form-control custom-input" id="takenAction" name="action_taken"></textarea>
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
                                                id="emrg_yes" value="Yes">
                                            <label class="form-check-label" for="emrg_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            &nbsp; &nbsp; <input class="form-check-input" type="radio"
                                                name="emrg_serv_attend" id="emrg_no" value="No" checked>
                                            <label class="form-check-label" for="emrg_no">No</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <label for="emrg_serv_time" class="form-label"> Time emergency services
                                        contacted</label>
                                    <input type="time" class="form-control shadow-sm custom-input" id="emrg_serv_time"
                                        name="emrg_serv_time">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="form-group row">

                                <div class="col-md-6">
                                    <label for="emrg_serv_arrived" class="form-label"> Time emergency services
                                        arrived</label>
                                    <input type="time" class="form-control shadow-sm custom-input"
                                        id="emrg_serv_arrived" name="emrg_serv_arrived">
                                </div>


                                <div class="col-md-6">
                                    <label class="form-label d-block">
                                        Was medical attention sought from a registered practitioner / hospital:
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="med_attention"
                                                id="med_yes" value="Yes">
                                            <label class="form-check-label" for="med_yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            &nbsp; &nbsp; <input class="form-check-input" type="radio"
                                                name="med_attention" id="med_no" value="No" checked>
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
                                name="med_attention_details"></textarea>
                        </div>
                    </div>

                    <div class="form-row">

                        <div class="form-group col-md-12">
                            <label for="provideDetails_minimise">Have any steps been
                                taken to prevent or
                                minimise this type of
                                incident in the future?
                                If yes, provide details.</label>
                            <textarea class="form-control custom-input" id="provideDetails_minimise"
                                name="provideDetails_minimise"></textarea>
                        </div>
                    </div>


                    {{-- <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="provideDetails">List the steps that have been taken to prevent or minimise this
                                type of incident in the future:</label>
                            <ol>
                                <li><input type="text" class="form-control custom-input" id="one"
                                        name="prevention_step_1" value="">
                                </li>
                                <li><input type="text" class="form-control custom-input" id="two"
                                        name="prevention_step_2" value="">
                                </li>
                                <li><input type="text" class="form-control custom-input" id="three"
                                        name="prevention_step_3" value=""></li>
                            </ol>
                        </div>
                    </div> --}}

                    <div class="row mt-1">
                        <div class="col-sm-12 mt-1">
                            <h5 class="section-heading">Parent/Guardian Notifications (including
                                attempted notifications)
                            </h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label for="parentname">Parent/guardian/carer</label>
                            <input type="text" class="form-control custom-input" id="parentname" name="parent1_name"
                                value="">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="carers_date" class="form-label">Date (Parent/guardian/carer)</label>
                            <input type="date" class="form-control shadow-sm custom-input" id="carers_date"
                                name="carers_date">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="carers_time" class="form-label">Time (Parent/guardian/carer)
                            </label>
                            <input type="time" class="form-control shadow-sm custom-input" id="carers_time"
                                name="carers_time">
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="director_educator_coordinator" class="form-label">Director/educator/
                                coordinator
                            </label>
                            <input type="text" class="form-control shadow-sm custom-input"
                                id="director_educator_coordinator" name="director_educator_coordinator">
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="educator_date" class="form-label">Date (Director/educator/
                                coordinator)</label>
                            <input type="date" class="form-control shadow-sm custom-input" id="educator_date"
                                name="educator_date">
                        </div>

                        <div class="col-md-6">
                            <label for="educator_time" class="form-label">Time (Director/educator/
                                coordinator)
                            </label>
                            <input type="time" class="form-control shadow-sm custom-input" id="educator_time"
                                name="educator_time">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="other_agency" class="form-label">Other agency
                                (if applicable)
                            </label>
                            <input type="text" class="form-control shadow-sm custom-input" id="other_agency"
                                name="other_agency">
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="other_agency_date" class="form-label">Date (Other agency)</label>
                            <input type="date" class="form-control shadow-sm custom-input" id="other_agency_date"
                                name="other_agency_date">
                        </div>

                        <div class="col-md-6">
                            <label for="other_agency_time" class="form-label">Time (Other agency)
                            </label>
                            <input type="time" class="form-control shadow-sm custom-input" id="other_agency_time"
                                name="other_agency_time">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="regulatory_authority" class="form-label">Regulatory authority
                                (if applicable)
                            </label>
                            <input type="text" class="form-control shadow-sm custom-input" id="regulatory_authority"
                                name="regulatory_authority">
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="regulatory_authority_date" class="form-label">Date (Regulatory
                                authority)</label>
                            <input type="date" class="form-control shadow-sm custom-input"
                                id="regulatory_authority_date" name="regulatory_authority_date">
                        </div>

                        <div class="col-md-6">
                            <label for="regulatory_authority_time" class="form-label">Time (Regulatory authority)
                            </label>
                            <input type="time" class="form-control shadow-sm custom-input"
                                id="regulatory_authority_time" name="regulatory_authority_time">
                        </div>
                    </div>
                    {{-- @if (isset($id)) --}}
                    <div class="row mt-1">
                        <div class="col-sm-12 mt-1">
                            <h5 class="section-heading">Parental acknowledgement</h5>

                        </div>
                    </div>
                    <div class="inlineInput mt-3 mb-3">
                        <b>I,</b> <input type="text" name="ack_parent_name" class="custom-input"> (name of parent /
                        guardian) have been notified of my child’s incident / injury / trauma / illness.

                        <div class="d-flex flex-wrap gap-3 mt-1">
                            <div class="form-check">
                                <!-- Hidden input ensures unchecked state sends 0 -->
                                <input type="hidden" name="ack_incident" value="0">
                                <input class="form-check-input" type="checkbox" name="ack_incident" id="ackIncident"
                                    value="1" {{ old('ack_incident') ? 'checked' : '' }}>
                                <label class="form-check-label" for="ackIncident">Incident</label>
                            </div>

                            <div class="form-check ml-3">
                                <input type="hidden" name="ack_injury" value="0">
                                <input class="form-check-input" type="checkbox" name="ack_injury" id="ackInjury"
                                    value="1" {{ old('ack_injury') ? 'checked' : '' }}>
                                <label class="form-check-label" for="ackInjury">Injury</label>
                            </div>

                            <div class="form-check ml-3">
                                <input type="hidden" name="ack_trauma" value="0">
                                <input class="form-check-input" type="checkbox" name="ack_trauma" id="ackTrauma"
                                    value="1" {{ old('ack_trauma') ? 'checked' : '' }}>
                                <label class="form-check-label" for="ackTrauma">Trauma</label>
                            </div>

                            <div class="form-check ml-3">
                                <input type="hidden" name="ack_illness" value="0">
                                <input class="form-check-input" type="checkbox" name="ack_illness" id="ackIllness"
                                    value="1" {{ old('ack_illness') ? 'checked' : '' }}>
                                <label class="form-check-label" for="ackIllness">Illness</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="RegulatoryauthorityDate">Date</label>
                            <input type="date" class="form-control custom-input" id="RegulatoryauthorityDate"
                                name="ack_date" value="">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="RegulatoryauthorityTime">Time</label>
                            <input type="time" class="form-control custom-input" id="RegulatoryauthorityTime"
                                name="ack_time" value="">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Final Signature</label>

                            <!-- Input (opens signature modal) -->
                            <input type="text" class="form-control shadow-sm custom-input" data-toggle="modal"
                                data-target="#signModal" data-identity="final_sign" id="final_sign_input"
                                style="cursor: pointer;" placeholder="Click to add signature" readonly>

                            <!-- Signature preview box (hidden by default) -->
                            <div id="final_sign_preview" class="border rounded bg-light p-2 shadow-sm mt-2"
                                style="display:none;">
                                <input type="hidden" name="final_sign" id="final_sign_txt">

                                <div id="final_sign_container" style="position: relative; display: inline-block;">
                                    <img src="" height="120" width="350" id="final_sign_img" class="img-thumbnail"
                                        alt="Final Signature" style="display:none;">

                                    <!-- remove (×) button -->
                                    <span id="remove_final_sign" style="position: absolute; top: 5px; right: 8px;
                         cursor: pointer; color: #fff; background: red;
                         border-radius: 50%; padding: 0 8px; font-weight: bold;
                         font-size: 16px; line-height: 20px; display:none;">
                                        ×
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}
                    <div class="row mt-1">
                        <div class="col-sm-12 mt-1">
                            <h5 class="section-heading">Additional notes</h5>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="form-group col-md-12">
                            <textarea class="form-control custom-input" id="takenAction" name="add_notes"
                                rows="8"></textarea>
                        </div>
                    </div>



                    <div class="row m-2">
                        <div class="col-sm-12 text-right">
                            <div class="formSubmit">
                                <button type="button" id="form-submit" class="btn btn-success">Save &amp;
                                    Next</button>
                                <!-- <button type="button" class="btn btn-default btn-danger">Cancel</button> -->
                                <a class="btn-warning p-2 rounded"
                                    href="{{ route('Accidents.list', ['centerid' => request()->get('centerid'), 'roomid' => request()->get('roomid')]) }}">
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
                    <button type="button" class="btn btn-danger btn-sm btn-danger" id="btnSignaturecancel"
                        data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success btn-sm btn-success " id="btnSignature" data-identity=""
                        data-dismiss="modal">Save</button>
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

 $('#remove_final_sign').on('click', function () {
        $('#final_sign_img').hide().attr('src', '');
        $('#final_sign_txt').val('');
        $('#final_sign_preview').hide();
        $('#remove_final_sign').hide();
        $('#final_sign_input').show();
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
$('#removeauthor_sign_txt').on('click', function () {
    $('#author_sign_img').hide().attr('src', '');
    $('#author_sign_txt').val('');
    $(this).hide();
    $('#author_sign').hide(); // hide the whole box again
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
        } else if (_identity === "author_sign") {
    $('#author_sign').show(); // show wrapper when signature exists
    $('#author_sign_img').attr('src', _signature).show();
    $('#author_sign_txt').val(_signature);
    $('#removeauthor_sign_txt').show();
}else if (_identity === "final_sign") {
    $('#final_sign_preview').show(); // show wrapper when signature exists
    $('#final_sign_img').attr('src', _signature).show();
    $('#final_sign_txt').val(_signature);
    $('#remove_final_sign').show();
    $('#final_sign_input').hide();
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
@stop
