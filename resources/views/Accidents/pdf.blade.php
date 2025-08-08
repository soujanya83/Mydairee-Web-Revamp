<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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



    </style>
    <title>MyDiaree</title>
  </head>
  <body>
 
    <main >
        
    <div class="container-fluid">
    <div id="printArea">
        <div class="row">
            <div class="col-12 mb-5 card pt-4">
                <h3 class="service-title text-primary">INCIDENT, INJURY, TRAUMA, & ILLNESS RECORD</h3>

                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="service-title">Details of person completing this record</h3>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-6">
                        <h4>Name</h4>
                        <p>{{ $AccidentInfo->person_name }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Position Role</h4>
                        <p>{{ $AccidentInfo->person_role }}</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-3">
                        <h4>Date Record was made</h4>
                        <p>{{ $AccidentInfo->date }}</p>
                    </div>
                    <div class="form-group col-md-3">
                        <h4>Time</h4>
                        <p>{{ $AccidentInfo->time }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Signature</h4>
                        <img src="{{ $AccidentInfo->person_sign }}" height="120px" width="300px">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="service-title">Child Details</h3>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-6">
                        <h4>Child</h4>
                        <p>{{ $AccidentInfo->child_name }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Date of Birth</h4>
                        <p>{{ $AccidentInfo->child_dob }}</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-6">
                        <h4>Age</h4>
                        <p>{{ $AccidentInfo->child_age }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Gender</h4>
                        <p>{{ $AccidentInfo->child_gender }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="service-title">Incident Details</h3>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-6">
                        <h4>Incident Date</h4>
                        <p>{{ $AccidentInfo->incident_date }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Time</h4>
                        <p>{{ $AccidentInfo->incident_time }}</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-6">
                        <h4>Location</h4>
                        <p>{{ $AccidentInfo->incident_location }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Name of Witness</h4>
                        <p>{{ $AccidentInfo->witness_name }}</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-6">
                        <h4>Date</h4>
                        <p>{{ $AccidentInfo->witness_date }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Witness Signature</h4>
                        <img src="{{ $AccidentInfo->witness_sign }}" class="bordered" height="120px" width="300px">
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-6">
                        <h4>General activity at the time of incident/ injury/ trauma/ illness:</h4>
                        <p>{{ $AccidentInfo->gen_actyvt }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Cause of injury/ trauma:</h4>
                        <p>{{ $AccidentInfo->cause }}</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group col-md-6">
                        <h4>Circumstances surrounding any illness, including apparent symptoms:</h4>
                        <p>{{ $AccidentInfo->illness_symptoms }}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <h4>Circumstances if child appeared to be missing or otherwise unaccounted for (incl duration, who found child etc.):</h4>
                        <p>{{ $AccidentInfo->missing_unaccounted }}</p>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <h4>Circumstances if child appeared to have been taken or removed from service or was locked in/out of service (incl who took the child, duration):</h4>
                    <p>{{ $AccidentInfo->taken_removed }}</p>
                </div>

            </div>
        </div>
    </div>
</div>


<ul class="row">
    @php
        $injuries = [
            'abrasion' => 'Abrasion/ Scrape',
            'electric_shock' => 'Electric Shock',
            'allergic_reaction' => 'Allergic Reaction',
            'high_temperature' => 'High Temperature',
            'amputation' => 'Amputation',
            'infectious_disease' => 'Infectious Disease (inc gastrointestinal)',
            'anaphylaxis' => 'Anaphylaxis',
            'ingestion' => 'Ingestion/ Inhalation/ Insertion',
            'asthma' => 'Asthma/ Respiratory',
            'internal_injury' => 'Internal Injury/ Infection',
            'bite_wound' => 'Bite Wound',
            'poisoning' => 'Poisoning',
            'broken_bone' => 'Broken Bone/ Fracture/ Dislocation',
            'rash' => 'Rash',
            'burn' => 'Burn/ Sunburn',
            'respiratory' => 'Respiratory',
            'choking' => 'Choking',
            'seizure' => 'Seizure/ Unconscious/ Convulsion',
            'concussion' => 'Concussion',
            'sprain' => 'Sprain/ Swelling',
            'crush' => 'Crush/ Jam',
            'stabbing' => 'Stabbing/ Piercing',
            'cut' => 'Cut/ Open Wound',
            'tooth' => 'Tooth',
            'drowning' => 'Drowning (nonfatal)',
            'venomous_bite' => 'Venomous Bite/ Sting',
            'eye_injury' => 'Eye Injury',
            'other' => 'Other (Please specify)',
        ];
    @endphp

    @foreach ($injuries as $key => $label)
        @php
            $status = $AccidentInfo->$key ?? 0;
        @endphp
        <li class="col-md-6 col-sm-12 mb-2 d-flex align-items-center">
            <h4 class="mb-0 me-2">{{ $label }}:</h4>
            @if($status)
                <span class="text-success ms-2">✅ Yes</span>
            @else
                <span class="text-danger ms-2">❌ No</span>
            @endif
        </li>
    @endforeach

    @if($AccidentInfo->remarks)
        <li class="col-md-12 mt-3">
            <h4>Remarks (Other):</h4>
            <p class="form-control">{{ $AccidentInfo->remarks }}</p>
        </li>
    @endif
</ul>


       <div class="row">
    <div class="col-sm-12">
        <h3 class="service-title">Action Taken</h3>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h4>Details of action taken:</h4>
        <p>{{ $AccidentInfo->action_taken }}</p>
    </div>
    <div class="col-md-6">
        <h4>Did emergency services attend:</h4>
        <p>{{ $AccidentInfo->emrg_serv_attend }}</p>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h4>Medical attention sought?</h4>
        <p>{{ $AccidentInfo->med_attention }}</p>
    </div>
    <div class="col-md-6">
        <h4>Medical attention details:</h4>
        <p>{{ $AccidentInfo->med_attention_details }}</p>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <h4>Step 1:</h4>
        <p>{{ $AccidentInfo->prevention_step_1 }}</p>
    </div>
    <div class="col-md-4">
        <h4>Step 2:</h4>
        <p>{{ $AccidentInfo->prevention_step_2 }}</p>
    </div>
    <div class="col-md-4">
        <h4>Step 3:</h4>
        <p>{{ $AccidentInfo->prevention_step_3 }}</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <h3 class="service-title">Parent/Guardian Notifications</h3>
    </div>
</div>

@php
$parents = [
    [
        'name' => $AccidentInfo->parent1_name,
        'method' => $AccidentInfo->contact1_method,
        'date' => $AccidentInfo->contact1_date,
        'time' => $AccidentInfo->contact1_time,
        'made' => $AccidentInfo->contact1_made,
        'msg' => $AccidentInfo->contact1_msg
    ],
    [
        'name' => $AccidentInfo->parent2_name,
        'method' => $AccidentInfo->contact2_method,
        'date' => $AccidentInfo->contact2_date,
        'time' => $AccidentInfo->contact2_time,
        'made' => $AccidentInfo->contact2_made,
        'msg' => $AccidentInfo->contact2_msg
    ]
];
@endphp

@foreach($parents as $index => $p)
<div class="row mb-3">
    <div class="col-md-6">
        <h4>Parent/ Guardian name:</h4>
        <p>{{ $p['name'] }}</p>
    </div>
    <div class="col-md-6">
        <h4>Method of Contact:</h4>
        <p>{{ $p['method'] }}</p>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <h4>Date</h4>
        <p>{{ $p['date'] }}</p>
    </div>
    <div class="col-md-6">
        <h4>Time</h4>
        <p>{{ $p['time'] }}</p>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-6">
        <h4>Contact Made:</h4>
        <p>{{ $p['made'] }}</p>
    </div>
    <div class="col-md-6">
        <h4>Message Left:</h4>
        <p>{{ $p['msg'] }}</p>
    </div>
</div>
@endforeach

<div class="row">
    <div class="col-sm-12">
        <h3 class="service-title">Internal Notifications</h3>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h4>Responsible Person in Charge Name:</h4>
        <p>{{ $AccidentInfo->responsible_person_name }}</p>
    </div>
    <div class="col-md-6">
        <h4>Signature:</h4>
        <img src="{{ $AccidentInfo->responsible_person_sign }}" height="120px" width="300px">
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <h4>Date</h4>
        <p>{{ $AccidentInfo->rp_internal_notif_date }}</p>
    </div>
    <div class="col-md-6">
        <h4>Time</h4>
        <p>{{ $AccidentInfo->rp_internal_notif_time }}</p>
    </div>
</div>

@if(!empty($AccidentInfo->id))
<div class="row">
    <div class="col-sm-12">
        <h3 class="service-title">Nominated Supervisor</h3>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <h4>Nominated Supervisor Name:</h4>
        <p>{{ $AccidentInfo->nominated_supervisor_name }}</p>
    </div>
    <div class="col-md-6">
        <h4>Signature:</h4>
        <img src="{{ $AccidentInfo->nominated_supervisor_sign }}" height="120px" width="300px">
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <h4>Date</h4>
        <p>{{ $AccidentInfo->nominated_supervisor_date }}</p>
    </div>
    <div class="col-md-6">
        <h4>Time</h4>
        <p>{{ $AccidentInfo->nominated_supervisor_time }}</p>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        <h3 class="service-title">External Notifications</h3>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <h4>Other agency:</h4>
        <p>{{ $AccidentInfo->ext_notif_other_agency }}</p>
    </div>
    <div class="col-md-3">
        <h4>Date</h4>
        <p>{{ $AccidentInfo->enor_date }}</p>
    </div>
    <div class="col-md-3">
        <h4>Time</h4>
        <p>{{ $AccidentInfo->enor_time }}</p>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <h4>Regulatory authority:</h4>
        <p>{{ $AccidentInfo->ext_notif_regulatory_auth }}</p>
    </div>
    <div class="col-md-3">
        <h4>Date</h4>
        <p>{{ $AccidentInfo->enra_date ? \Carbon\Carbon::parse($AccidentInfo->enra_date)->format('Y-m-d') : '' }}</p>
    </div>
    <div class="col-md-3">
        <h4>Time</h4>
        <p>{{ $AccidentInfo->enra_time }}</p>
    </div>
</div>

@if(!empty($AccidentInfo->id))
<div class="row">
    <div class="col-sm-12">
        <h3 class="service-title">Parental acknowledgement</h3>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <h4>Parental acknowledgement</h4>
        <p>{{ $AccidentInfo->ack_parent_name }} (name of parent / guardian) have been notified of my child's incident / injury / trauma / illness.</p>
    </div>
    <div class="col-md-3">
        <h4>Date</h4>
        <p>{{ $AccidentInfo->ack_date ? \Carbon\Carbon::parse($AccidentInfo->ack_date)->format('Y-m-d') : '' }}</p>
    </div>
    <div class="col-md-3">
        <h4>Time</h4>
        <p>{{ $AccidentInfo->ack_time }}</p>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        <h3 class="service-title">Additional notes</h3>
    </div>
</div>
<div class="row mb-5">
    <div class="col-md-12">
        <p>{{ $AccidentInfo->add_notes }}</p>
    </div>
</div>

  </div>
</div>
  </div>
</div>
    </main>

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


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>