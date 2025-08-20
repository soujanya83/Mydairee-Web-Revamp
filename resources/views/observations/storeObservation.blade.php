@extends('layout.master')
@section('title', 'Store Observation')
@section('parentPageTitle', 'Observation')
<link rel="stylesheet" href="{{ asset('assets/vendor/summernote/dist/summernote.css') }}"/>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- (Optional) Flatpickr Theme Example -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
/* Assessment Container Styles */
.assessment-container {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.assessment-container:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
}

/* Triangle Indicator Styles */
.triangle-indicator {
    position: relative;
    width: 60px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.triangle-wrapper {
    position: relative;
    width: 50px;
    height: 45px;
}

.triangle-side {
    position: absolute;
    background: #e9ecef;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

/* Triangle Side 1 - Bottom */
.triangle-side.side-1 {
    bottom: 0;
    left: 0;
    width: 50px;
    height: 4px;
    border-radius: 2px;
}

/* Triangle Side 2 - Left */
.triangle-side.side-2 {
    bottom: 0;
    left: 0;
    width: 4px;
    height: 45px;
    border-radius: 2px;
    transform-origin: bottom center;
    transform: rotate(30deg);
}

/* Triangle Side 3 - Right */
.triangle-side.side-3 {
    bottom: 0;
    right: 0;
    width: 4px;
    height: 45px;
    border-radius: 2px;
    transform-origin: bottom center;
    transform: rotate(-30deg);
}

/* Active Triangle States */
.triangle-indicator.level-1 .side-1 {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    transform: scaleY(1.1);
}

.triangle-indicator.level-2 .side-1,
.triangle-indicator.level-2 .side-2 {
  
    background: linear-gradient(45deg, #176ba6, #00a8ff);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    transform: scaleY(1.1);
}

.triangle-indicator.level-2 .side-2 {
    transform: rotate(30deg) scaleY(1.1);
}

.triangle-indicator.level-3 .side-1,
.triangle-indicator.level-3 .side-2,
.triangle-indicator.level-3 .side-3 {
    background: linear-gradient(45deg, #28a745, #20c997);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    transform: scaleY(1.1);

}

.triangle-indicator.level-3 .side-2 {
    transform: rotate(30deg) scaleY(1.1);
}

.triangle-indicator.level-3 .side-3 {
    transform: rotate(-30deg) scaleY(1.1);
}

/* Custom Radio Button Styles */
.custom-control-input:checked ~ .assessment-label {
    color: #007bff;
    font-weight: 600;
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.assessment-label {
    font-size: 14px;
    font-weight: 500;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 6px;
    margin-left: 5px;
}

.assessment-label:hover {
    background-color: #f8f9fa;
    color: #007bff;
}

.custom-control-input:checked ~ .assessment-label {
    background-color: #e3f2fd;
    border: 1px solid #007bff;
}

/* Clear Button Styles */
.clear-btn {
    border-radius: 20px;
    font-size: 12px;
    padding: 6px 12px;
    transition: all 0.3s ease;
    border: 1px solid #dc3545;
}

.clear-btn:hover {
    background-color: #dc3545;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

/* Options Container */
.options-container {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .triangle-indicator {
        width: 45px;
        height: 40px;
    }

    .triangle-wrapper {
        width: 35px;
        height: 32px;
    }

    .triangle-side.side-1 {
        width: 35px;
    }

    .triangle-side.side-2,
    .triangle-side.side-3 {
        height: 32px;
    }

    .options-container {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* Animation for state changes */
@keyframes trianglePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.triangle-indicator.animate {
    animation: trianglePulse 0.6s ease-in-out;
}
</style>



<style>
.media-upload-box {
    border: 2px dashed #007bff;
    background-color: #f8f9fa;
    position: relative;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}

.media-upload-box:hover {
    background-color: #e9f0ff;
}

.media-thumb {
    height: 150px;
    object-fit: cover;
    width: 100%;
}

.remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    padding: 2px 5px;
    font-size: 12px;
}

#mediaPreview .btn {
    margin-right: 5px;
    margin-top: 5px;
}
.media-thumb {
    max-height: 200px;
    object-fit: cover;
    width: 100%;
    border: 1px solid #ddd;
    box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
}
</style>


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

.c_list .avatar {
    height: 45px;
    width: 50px;
}
</style>

<style>
   .blur-nav {
    display: flex;
    gap: 10px;
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.blur-nav .nav-item {
    flex: 1;
    text-align: center;
}

.blur-nav .nav-link {
    display: block;
    padding: 12px 20px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    color: #333;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.blur-nav .nav-link:hover {
    background: rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border-color: rgba(0, 0, 0, 0.05);
    text-decoration: none;
}

.blur-nav .nav-link.active {
    background: rgba(255, 255, 255, 0.5);
    color: #000;
    font-weight: bold;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.blur-nav .nav-link i {
    margin-right: 8px;
    color: #555;
    transition: color 0.3s ease;
}

.blur-nav .nav-link:hover i,
.blur-nav .nav-link.active i {
    color: #000;
}

/* Form group container for each select section */
.select-section {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    transition: box-shadow 0.3s ease;
}

.select-section:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}

/* Label style */
.select-section label {
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 10px;
    display: block;
    color: #333;
}

/* Button styling */
.select-section .btn {
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

/* Preview badges */
#selectedChildrenPreview .badge,
#selectedRoomsPreview .badge {
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 8px;
    background: linear-gradient(to right, #00bcd4, #2196f3);
    color: white;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 4px;
}

/* Room badge override for green */
#selectedRoomsPreview .badge {
    background: linear-gradient(to right, #4caf50, #81c784);
}


</style>
<style>
/* Section styling */
.form-section {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    padding: 18px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    position: relative;
    transition: box-shadow 0.3s ease;
}

/* Label styling */
.form-section label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
}

/* Textarea styling - CKEditor wrapper */
.form-section .form-control {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    border: 1px solid #ccc;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: border-color 0.3s ease;
}

.form-section .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.15);
}

/* Refine button container - floated to right bottom */
.refine-container {
    display: flex;
    justify-content: flex-end;
    margin-top: 8px;
}

/* Refine button styling */
.refine-btn {
    font-size: 13px;
    padding: 6px 14px;
    border-radius: 8px;
    background: linear-gradient(to right, #007bff, #339af0);
    color: white;
    border: none;
    box-shadow: 0 3px 6px rgba(0, 123, 255, 0.25);
    transition: background 0.3s ease, transform 0.2s ease;
}

.refine-btn:hover {
    background: linear-gradient(to right, #0056b3, #007bff);
    transform: translateY(-1px);
}

.refine-btn:active {
    transform: translateY(1px);
}

</style>

<style>
/* Container card for each subject's activity */
.tab-content .card {
    border-radius: 10px;
    overflow: hidden;
    border: none;
    background: #fdfdfd;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s ease;
}

.tab-content .card:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}

/* Activity Header button */
.card-header button {
    width: 100%;
    text-align: left;
    color: #007bff;
    font-weight: 600;
    font-size: 15px;
    background: transparent;
    border: none;
    padding: 12px 16px;
    transition: background 0.2s ease;
}

.card-header button:hover {
    text-decoration: none;
    background: rgba(0, 123, 255, 0.05);
}

/* Subactivity row */
.form-row {
    background: #f8f9fa;
    padding: 10px 14px;
    margin-bottom: 8px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
    align-items: center;
}

.form-row .col-md-4 {
    font-weight: 500;
    color: #333;
}

/* Radio label spacing */
.custom-control-label {
    margin-right: 18px;
    font-size: 14px;
}

/* Subject dropdown styling */
#subjectSelect {
    border-radius: 8px;
    padding: 10px;
    font-size: 15px;
}

/* Save button styling */
#saveMontessoriData {
    margin-top: 10px;
    margin-bottom: 50px;
    float: right;
    padding: 10px 28px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 8px;
    background: linear-gradient(to right, #007bff, #339af0);
    border: none;
    color: white;
    transition: background 0.3s ease;
}

#saveMontessoriData:hover {
    background: linear-gradient(to right, #0056b3, #007bff);
}

/* Highlight active tab-pane */
.tab-pane.show {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}


</style>

<style>
    /* Dropdown container */

/* Custom select styling */
#subjectSelect {
    border: 1px solid #ced4da;
    border-radius: 10px;
    padding: 7px 14px;
    font-size: 15px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    appearance: none; /* remove default arrow */
    background-image: url("data:image/svg+xml,%3Csvg fill='%23343a40' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px 16px;
}

/* On focus */
#subjectSelect:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
    outline: none;
}

/* Placeholder style */
#subjectSelect option:first-child {
    color: #6c757d;
    font-style: italic;
}

</style>


<style>
/* Dropdown select for EYLF */
#eylfOutcomeSelect {
    border: 1px solid #ced4da;
    border-radius: 10px;
    padding: 7px 14px;
    font-size: 15px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg fill='%2328a745' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px 16px;
}

#eylfOutcomeSelect:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
    outline: none;
}

#eylfOutcomeSelect option:first-child {
    color: #6c757d;
    font-style: italic;
}

/* EYLF Accordion Styling */
#eylf-tabs .card {
    border-radius: 10px;
    background: #fdfdfd;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s ease;
}

#eylf-tabs .card:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}

#eylf-tabs .card-header button {
    width: 100%;
    text-align: left;
    color: #28a745;
    font-weight: 600;
    font-size: 15px;
    background: transparent;
    border: none;
    padding: 12px 16px;
    transition: background 0.2s ease;
}

#eylf-tabs .card-header button:hover {
    text-decoration: none;
    background: rgba(40, 167, 69, 0.05);
}

/* Checkbox Styling */
#eylf-tabs .custom-control-label {
    font-size: 14px;
    margin-left: 5px;
    font-weight: 500;
}

/* Save button */
#saveEylfData {
    margin-top: 10px;
    margin-bottom: 50px;
    float: right;
    padding: 10px 28px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 8px;
    background: linear-gradient(to right, #28a745, #5dd39e);
    border: none;
    color: white;
    transition: background 0.3s ease;
}

#saveEylfData:hover {
    background: linear-gradient(to right, #218838, #28a745);
}
</style>


<style>
/* Dropdown for selecting age group */
#devAgeSelect {
    border: 1px solid #ced4da;
    border-radius: 10px;
    padding: 7px 14px;
    font-size: 15px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg fill='%23ff9800' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px 16px;
}

#devAgeSelect:focus {
    border-color: #ff9800;
    box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.15);
    outline: none;
}

#devAgeSelect option:first-child {
    color: #6c757d;
    font-style: italic;
}

/* Accordion styling */
#devmilestone-tabs .card {
    border-radius: 10px;
    background: #fdfdfd;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s ease;
}

#devmilestone-tabs .card:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}

#devmilestone-tabs .card-header button {
    width: 100%;
    text-align: left;
    color: #ff9800;
    font-weight: 600;
    font-size: 15px;
    background: transparent;
    border: none;
    padding: 12px 16px;
    transition: background 0.2s ease;
}

#devmilestone-tabs .card-header button:hover {
    text-decoration: none;
    background: rgba(255, 152, 0, 0.05);
}

/* Sub-milestone row styling */
#devmilestone-tabs .form-row {
    background: #f8f9fa;
    padding: 10px 14px;
    margin-bottom: 8px;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
    align-items: center;
}

#devmilestone-tabs .form-row .col-md-4 {
    font-weight: 500;
    color: #333;
}

/* Radio options */
.custom-control-label {
    margin-right: 18px;
    font-size: 14px;
}

/* Save button */
#saveDevMilestone {
    margin-top: 10px;
    margin-bottom: 50px;
    float: right;
    padding: 10px 28px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 8px;
    background: linear-gradient(to right, #ff9800, #ffc107);
    border: none;
    color: white;
    transition: background 0.3s ease;
}

#saveDevMilestone:hover {
    background: linear-gradient(to right, #e68900, #ff9800);
}
</style>
<style>
 /* Styling for the submit button */
.btn-primary.submit-btn {
    padding: 10px 20px; /* Comfortable padding */
    font-size: 16px; /* Readable font size */
    font-weight: 500; /* Medium weight for emphasis */
    border-radius: 6px; /* Softer corners */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    transition: all 0.3s ease; /* Smooth transitions */
    float: right; /* Retain float:right */
    display: flex; /* Align icon and text */
    align-items: center; /* Center vertically */
    gap: 8px; /* Space between icon and text */
    position: relative; /* For animation positioning */
}

/* Icon styling */
.btn-primary.submit-btn .fas {
    font-size: 14px; /* Slightly smaller icon */
    transition: transform 0.3s ease; /* Smooth icon movement */
}

/* Hover effect */
.btn-primary.submit-btn:hover {
    background-color: #0056b3; /* Darker primary color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Enhanced shadow */
    transform: translateY(-2px); /* Slight lift */
}

/* Icon animation on hover */
.btn-primary.submit-btn:hover .fas {
    transform: translateX(4px); /* Icon slides right */
}

/* Click animation */
.btn-primary.submit-btn:active {
    transform: translateY(0); /* Press down effect */
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); /* Reduced shadow */
    animation: pulse 0.2s ease-in-out; /* Pulse effect on click */
}

/* Disabled state */
.btn-primary.submit-btn:disabled {
    opacity: 0.7; /* Faded when disabled */
    cursor: not-allowed; /* Clear cursor feedback */
    transform: none; /* No transform when disabled */
}

/* Focus state for accessibility */
.btn-primary.submit-btn:focus {
    outline: 2px solid #80bdff; /* Visible focus ring */
    outline-offset: 2px; /* Offset for visibility */
}

/* Pulse animation for click */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Optional: Loading state animation */
.btn-primary.submit-btn.loading {
    pointer-events: none; /* Prevent interaction */
    opacity: 0.85; /* Slightly faded */
}

.btn-primary.submit-btn.loading .fas {
    animation: spin 1s linear infinite; /* Spinning icon for loading */
}

/* Spin animation for loading */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<style>
.btn-animated {
    position: relative;
    transition: all 0.3s ease-in-out;
    transform: translateY(0);
}

.btn-animated:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
</style>


<style>
.obs-card {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.obs-img {
    height: 200px;
    object-fit: cover;
}

.card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
</style>


<style>
 /* Completely disable all collapse transitions */
.collapse,
.collapse.show,
.collapsing {
    transition: none !important;
    animation: none !important;
    -webkit-transition: none !important;
    -moz-transition: none !important;
    -o-transition: none !important;
}

/* Force immediate height behavior */
.collapse:not(.show) {
    height: 0 !important;
    overflow: hidden !important;
}

.collapse.show {
    height: auto !important;
    overflow: visible !important;
}

/* Remove collapsing class behavior completely */
.collapsing {
    height: auto !important;
    overflow: visible !important;
}
    </style>

@section('content')

@if(isset($observation) && $observation->id)
<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;margin-bottom:30px;">


{{-- Date Display and Picker --}}
<div id="createdAtContainer" class="mr-3" style="cursor:pointer;">
    <span id="createdAtDisplay" class="badge badge-info" style="font-size:16px; padding:8px;">
        <i class="far fa-calendar-alt mr-1"></i>
        {{ \Carbon\Carbon::parse($observation->created_at)->format('d M Y') }}
    </span>
    <input type="text" id="editCreatedAt" class="form-control"
        style="display:none; min-width:170px;"
        value="{{ \Carbon\Carbon::parse($observation->created_at)->format('d M Y') }}">
</div>


    <button type="button" id="publishObservation" class="btn btn-success shadow-lg btn-animated mr-2">
        <i class="fas fa-upload mr-1"></i> Publish Now
    </button>
    <button type="button" id="draftObservation" class="btn btn-warning shadow-lg btn-animated">
        <i class="fas fa-file-alt mr-1"></i> Make Draft
    </button>
</div>
@endif

<script>
    $(function(){
        // Handle subject select within this specific form section
        $('#subjectSelect').on('change', function(){
            var subId = $(this).val();
            $('#learning-tabs .tab-pane.active').removeClass('active show');
            if (subId) {
                $('#' + subId).addClass('active show');
            }
        });

        // Prevent global collapse targeting by scoping the ID to learning-accordion
        // No need to add custom logic here because data-parent is already scoped per subject
    });
</script>


<div class="row clearfix">

    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="body">
            <ul class="nav nav-tabs-new2 blur-nav">
    <li class="nav-item">
        <a class="nav-link {{ $activeTab == 'observation' ? 'active show' : '' }}" data-toggle="tab" href="#Home">
            <i class="fas fa-eye"></i> <span>OBSERVATIONS</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab == 'assessment' ? 'active show' : '' }}" data-toggle="tab" href="#Profile">
            <i class="fas fa-tasks"></i> <span>ASSESSMENT</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab == 'link' ? 'active show' : '' }}" data-toggle="tab" href="#Contact">
            <i class="fas fa-link"></i> <span>LINK</span>
        </a>
    </li>
</ul>

                <hr>
                <div class="tab-content">

                <!-- OBSERVATIONS Tabs -->
                <div class="tab-pane {{ $activeTab == 'observation' ? 'show active' : '' }}" id="Home">

                    <form id="observationform" method="POST" enctype="multipart/form-data">

      <div class="row">

    <!-- Select Children -->
<div class="col-md-6 select-section">
    <label>Children</label><br>
    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#childrenModal">Select Children</button>
    <input type="hidden" name="selected_children" id="selected_children" value="{{ isset($childrens) ? implode(',', collect($childrens)->pluck('id')->toArray()) : '' }}">
    <div id="selectedChildrenPreview" class="mt-3">
        @if(isset($childrens))
            @foreach($childrens as $child)
                <span class="badge badge-info mr-1">{{ $child->name }}</span>
            @endforeach
        @endif
    </div>
</div>

<!-- Select Rooms -->
<div class="col-md-6 select-section">
    <label>Rooms</label><br>
    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#roomsModal">Select Rooms</button>
    <input type="hidden" name="selected_rooms" id="selected_rooms" value="{{ isset($rooms) ? implode(',', collect($rooms)->pluck('id')->toArray()) : '' }}">
    <div id="selectedRoomsPreview" class="mt-3">
        @if(isset($rooms))
            @foreach($rooms as $room)
                <span class="badge badge-success mr-1">{{ $room['name'] }}</span>
            @endforeach
        @endif
    </div>
</div>



<input type="hidden" name="id" value="{{ isset($observation) ? $observation->id : '' }}">
        <!-- Add more form elements -->
        <div class="col-md-6 mt-4 form-section">
    <label for="editor6">Title</label>
    <textarea id="editor6" name="obestitle" class="form-control ckeditor">{!! isset($observation) ? $observation->obestitle : '' !!}</textarea>
    <div class="refine-container">
<button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor6"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>

<div class="col-md-6 mt-4 form-section">
    <label for="editor1">Observation</label>
    <textarea id="editor1" name="title" class="form-control ckeditor">{!! isset($observation) ? $observation->title : '' !!}</textarea>
    <div class="refine-container">
 <button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor1"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>

<div class="col-md-6 mt-4 form-section">
    <label for="editor2">Analysis/Evaluation</label>
    <textarea id="editor2" name="notes" class="form-control ckeditor">{!! isset($observation) ? $observation->notes : '' !!}</textarea>
    <div class="refine-container">
 <button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor2"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>

<div class="col-md-6 mt-4 form-section">
    <label for="editor3">Reflection</label>
    <textarea id="editor3" name="reflection" class="form-control ckeditor">{!! isset($observation) ? $observation->reflection : '' !!}</textarea>
    <div class="refine-container">
 <button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor3"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>

<div class="col-md-6 mt-4 form-section">
    <label for="editor4">Child's Voice</label>
    <textarea id="editor4" name="child_voice" class="form-control ckeditor">{!! isset($observation) ? $observation->child_voice : '' !!}</textarea>
    <div class="refine-container">
 <button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor4"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>

<div class="col-md-6 mt-4 form-section">
    <label for="editor5">Future Plan/Extension</label>
    <textarea id="editor5" name="future_plan" class="form-control ckeditor">{!! isset($observation) ? $observation->future_plan : '' !!}</textarea>
    <div class="refine-container">
 <button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor5"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>



<div class="col-md-12 mt-4">
    <h4>Media Upload Section</h4>
    <div class="media-upload-box p-4 border rounded bg-light text-center">
        <label for="mediaInput" class="btn btn-outline-primary">
            Select up to 10 Images/Videos
        </label>
        <input type="file" id="mediaInput" name="media[]" class="d-none" multiple accept="image/*,video/*">
        <small class="form-text text-muted mt-2">Only images and videos are allowed. Max 10 files.</small>
    </div>

    <div id="mediaPreview" class="row mt-4"></div>


    @if(isset($observation) && $observation->media->isNotEmpty())
    <span>Uploaded Images/Videos</span>
    <div id="uploadedMedia" class="row mt-4">
        @foreach($observation->media as $media)
            <div class="col-md-3 position-relative mb-3" id="media-{{ $media->id }}">
            @if(Str::startsWith($media->mediaType, ['image', 'Image']))
                                <img src="{{ asset($media->mediaUrl) }}" class="media-thumb img-fluid rounded">
                @elseif(Str::startsWith($media->mediaType, 'video'))
                    <video controls class="media-thumb rounded">
                        <source src="{{ asset($media->mediaUrl) }}" type="{{ $media->mediaType }}">
                        Your browser does not support the video tag.
                    </video>
                @endif
                <button type="button" class="btn btn-sm btn-danger remove-btn"
    onclick="deleteMedia({{ $media->id }}, '{{ asset($media->mediaUrl) }}')">Remove</button>            </div>
        @endforeach
    </div>
@endif


</div>


        <!-- Submit -->
        <div class="col-12 mt-4">
            <button type="submit" style="float:right" class="btn btn-primary submit-btn"><i class="fas fa-arrow-right"></i>Submit</button>
        </div>

    </div>
</form>






                    </div>
              <!-- end OBSERVATIONS -->




              <!-- ASSESSMENT Tabs -->
              <div class="tab-pane {{ $activeTab == 'assessment' ? 'show active' : '' }}" id="Profile">

<ul class="nav nav-tabs-new">
    <li class="nav-item">
        <a class="nav-link {{ $activesubTab == 'MONTESSORI' ? 'active show' : '' }}" data-toggle="tab" href="#MONTESSORI"><i class="fa-regular fa-clipboard fa-beat-fade"></i>&nbsp;&nbsp;MONTESSORI</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activesubTab == 'EYLF' ? 'active show' : '' }}" data-toggle="tab" href="#EYLF"><i class="fa-solid fa-list fa-beat-fade"></i>&nbsp;&nbsp;EYLF</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activesubTab == 'MILESTONE' ? 'active show' : '' }}" data-toggle="tab" href="#MILESTONE"><i class="fa-solid fa-layer-group fa-beat"></i>&nbsp;&nbsp;DEVELOPMENTAL MILESTONE</a>
    </li>
</ul>


 <div class="tab-content">


      <div class="tab-pane {{ $activesubTab == 'MONTESSORI' ? 'show active' : '' }}" id="MONTESSORI">



      <div class="form-group">
    <label><strong>Select Subject</strong></label>
    <select id="subjectSelect" class="form-control">
        <option value="">-- Choose Subject --</option>
        @foreach($subjects as $subject)
            <option value="subject-{{ $subject->idSubject }}">{{ $subject->name }}</option>
        @endforeach
    </select>
</div>

    <input type="hidden" name="id" id="observation_id" value="{{ isset($observation) ? $observation->id : '' }}">


    {{-- Tab panes for each subject --}}
    <div class="tab-content mt-3" id="learning-tabs">
    @foreach($subjects as $subject)
        <div class="tab-pane" id="subject-{{ $subject->idSubject }}" role="tabpanel">
            <div id="learning-accordion-{{ $subject->idSubject }}">
                @foreach($subject->activities as $act)
                    <div class="card mb-2">
                        <div class="card-header" id="learning-heading-{{ $act->idActivity }}">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse"
                                        data-target="#learning-collapse-{{ $act->idActivity }}"
                                        aria-expanded="false"
                                        aria-controls="learning-collapse-{{ $act->idActivity }}">
                                        <i class="fas fa-tasks mr-2 text-primary"></i> {{ $act->title }}
                                </button>
                            </h5>
                        </div>
                        <div id="learning-collapse-{{ $act->idActivity }}" class="collapse"
                             aria-labelledby="learning-heading-{{ $act->idActivity }}"
                             data-parent="#learning-accordion-{{ $subject->idSubject }}">
                            <div class="card-body">
                                {{-- Subactivities with radio options --}}
                                @foreach($act->subActivities as $sub)
                                    <div class="form-row align-items-center mb-2">
                                        <div class="col-md-4">{{ $sub->title }}</div>
                                        <div class="col-md-8">
                                        @php
                $opts = ['introduced' => 'Introduced', 'working' => 'Working', 'completed' => 'Completed'];
                $selectedAssessment = $observation && $observation->montessoriLinks
                    ? $observation->montessoriLinks->firstWhere('idSubActivity', $sub->idSubActivity)->assesment ?? ''
                    : '';
            @endphp

            <div class="assessment-container d-flex align-items-center">
                <!-- Triangle Visual Indicator -->
                <div class="triangle-indicator mr-3" id="triangle-{{ $sub->idSubActivity }}">
                    <div class="triangle-wrapper">
                        <div class="triangle-side side-1" data-level="1"></div>
                        <div class="triangle-side side-2" data-level="2"></div>
                        <div class="triangle-side side-3" data-level="3"></div>
                    </div>
                </div>

                <!-- Radio Button Options -->
                <div class="options-container">
                    @foreach($opts as $val => $label)
                    @php
                        $displayLabel = ($label === 'Working') ? 'Practicing' : $label;
                    @endphp
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio"
                                   class="custom-control-input assessment-radio"
                                   id="sa-{{ $sub->idSubActivity }}-{{ $val }}"
                                   name="subactivity[{{ $sub->idSubActivity }}]"
                                   value="{{ $label }}"
                                   data-sub-id="{{ $sub->idSubActivity }}"
                                   data-level="{{ $loop->iteration }}"
                                   {{ $selectedAssessment == $label ? 'checked' : '' }}>
                            <label class="custom-control-label assessment-label" style="margin-right:14px !important;"
                                   for="sa-{{ $sub->idSubActivity }}-{{ $val }}">
                                {{ $displayLabel }}
                            </label>
                        </div>
                    @endforeach

                    <!-- Clear Button -->
                    <button type="button"
                            class="btn btn-sm btn-outline-danger ml-2 clear-btn"
                            onclick="clearAssessment('{{ $sub->idSubActivity }}')"
                            title="Clear Selection">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>


<button type="button" id="saveMontessoriData" class="btn btn-primary">Save Montessori Assessment</button>


      </div>


      <div class="tab-pane {{ $activesubTab == 'EYLF' ? 'show active' : '' }}" id="EYLF" role="tabpanel">


  <div class="form-group">
    <label><strong>Select Outcome</strong></label>
    <select id="eylfOutcomeSelect" class="form-control">
      <option value="">-- Choose Outcome --</option>
      @foreach($outcomes as $o)
        <option value="eylf-outcome-{{ $o->id }}">{{ $o->title }}</option>
      @endforeach
    </select>
  </div>

  <div class="tab-content" id="eylf-tabs">
    @foreach($outcomes as $o)
      <div class="tab-pane" id="eylf-outcome-{{ $o->id }}">
        <div id="eylf-accordion-{{ $o->id }}">
          @foreach($o->activities as $act)
            <div class="card mb-2">
              <div class="card-header" id="eylf-heading-{{ $act->id }}">
                <h5 class="mb-0">
                  <button class="btn btn-link collapsed" data-toggle="collapse"
                          data-target="#eylf-collapse-{{ $act->id }}">
                    {{ $act->title }}
                  </button>
                </h5>
              </div>
              <div id="eylf-collapse-{{ $act->id }}" class="collapse"
                   data-parent="#eylf-accordion-{{ $o->id }}">
                <div class="card-body">
                  @foreach($act->subActivities as $sub)
                    @php
                      $checked = $observation && $observation->eylfLinks
                        ? $observation->eylfLinks
                            ->where('eylfSubactivityId', $sub->id)
                            ->first() !== null
                        : false;
                    @endphp
                    <div class="custom-control custom-checkbox mb-2">
                      <input type="checkbox"
                             class="custom-control-input"
                             id="eylf-sa-{{ $sub->id }}"
                             name="eylf_subactivity[]"
                             value="{{ $sub->id }}"
                             {{ $checked ? 'checked' : '' }}>
                      <label class="custom-control-label"
                             for="eylf-sa-{{ $sub->id }}">{{ $sub->title }}</label>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>

  <button type="button" id="saveEylfData" class="btn btn-success">Save EYLF Selection</button>



      </div>


      <div class="tab-pane {{ $activesubTab == 'MILESTONE' ? 'show active' : '' }}" id="MILESTONE">


      <div class="form-group">
    <label><strong>Select Age Group</strong></label>
    <select id="devAgeSelect" class="form-control">
      <option value="">-- Choose Age Group --</option>
      @foreach($milestones as $ms)
        <option value="dev-age-{{ $ms->id }}">{{ $ms->ageGroup }}</option>
      @endforeach
    </select>
  </div>

  <div class="tab-content" id="devmilestone-tabs">
    @foreach($milestones as $ms)
      <div class="tab-pane" id="dev-age-{{ $ms->id }}">
        <div id="devmilestone-accordion-{{ $ms->id }}">
          @foreach($ms->mains as $main)
            <!-- Accordion Card -->
            <div class="card mb-2">
              <div class="card-header" id="dev-heading-{{ $main->id }}">
                <button class="btn btn-link collapsed" data-toggle="collapse"
                        data-target="#dev-collapse-{{ $main->id }}">
                  {{ $main->name }}
                </button>
              </div>
              <div id="dev-collapse-{{ $main->id }}" class="collapse"
                   data-parent="#devmilestone-accordion-{{ $ms->id }}">
                <div class="card-body">
                  @foreach($main->subs as $sub)
                    @php
                      $sel = $observation && $observation->devMilestoneSubs
                             ? $observation->devMilestoneSubs
                               ->where('devMilestoneId', $sub->id)
                               ->first()
                               ->assessment ?? null
                             : null;
                    @endphp
                    <div class="form-row align-items-center mb-2">
                      <div class="col-md-4">{{ $sub->name }}</div>
                      <div class="col-md-8">
                        @foreach(['Introduced','Working towards','Achieved'] as $label)
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio"
                                   class="custom-control-input"
                                   id="devsub-{{ $sub->id }}-{{ Str::slug($label) }}"
                                   name="devsub_{{ $sub->id }}"
                                   data-subid="{{ $sub->id }}"
                                   value="{{ $label }}"
                                   {{ ($sel === $label) ? 'checked' : '' }}>
                            <label class="custom-control-label"
                                   for="devsub-{{ $sub->id }}-{{ Str::slug($label) }}">{{ $label }}</label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>

  <button type="button" id="saveDevMilestone" class="btn btn-warning">Save Development Milestone</button>



      </div>


</div>

                </div>
             <!-- end ASSESSMENT -->






             <!-- Link Tabs -->
             <style>
.obs-card {
    display: flex;
    flex-direction: column;
    height: 100%;
}
.obs-img {
    height: 200px;
    object-fit: cover;
}
</style>


<!-- Buttons -->
<div class="tab-pane {{ $activeTab == 'link' ? 'show active' : '' }}" id="Contact">
    <button type="button" class="btn btn-primary mb-3" id="btnLinkObservation">+ Link Observation</button>
    <button type="button" class="btn btn-secondary mb-3 ml-2" id="btnLinkReflection">+ Link Reflection</button>
    <button type="button" class="btn btn-info mb-3 ml-2" id="btnLinkProgramPlan">+ Link Program Plan</button>

    @if(isset($observation) && $observation->links->where('linktype', 'OBSERVATION')->count())
    <p>Linked Observations</p>
    <div class="row mt-4">
        @foreach($observation->links->where('linktype', 'OBSERVATION') as $link)
            @php
                $linked = \App\Models\Observation::with(['media', 'user'])->find($link->linkid);
            @endphp

            @if($linked)
                <div class="col-md-4 mb-3">

                    <div class="card h-100 shadow-sm obs-card">
                    @php
                       $media = $linked->media->first();
                       $imageUrl = $media && $media->mediaUrl ? asset($media->mediaUrl) : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg';
                   @endphp

<img src="{{ $imageUrl }}" class="card-img-top obs-img" alt="{{ $linked->obestitle ?? 'Untitled' }}">
                        <div class="card-body">
                        <h5 class="card-title">{!! $linked->obestitle ?? 'Untitled' !!}</h5>
                            <p class="card-text"><small class="text-muted">Created by: {{ $linked->user->name ?? 'Unknown' }}</small></p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif





@if(isset($observation) && $observation->links->where('linktype', 'REFLECTION')->count())
    <p>Linked Reflections</p>
    <div class="row mt-4">
        @foreach($observation->links->where('linktype', 'REFLECTION') as $link)
            @php
                $linked = \App\Models\Reflection::with(['media', 'creator'])->find($link->linkid);
            @endphp

            @if($linked)
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm obs-card">
                        @php
                           $media = $linked->media->first();
                           $imageUrl = $media && $media->mediaUrl ? asset($media->mediaUrl) : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg';
                       @endphp

                        <img src="{{ $imageUrl }}" class="card-img-top obs-img" alt="{{ $linked->title ?? 'Untitled' }}">
                        <div class="card-body">
                            <h5 class="card-title">{!! $linked->title ?? 'Untitled' !!}</h5>
                            <p class="card-text"><small class="text-muted">Created by: {{ $linked->creator->name ?? 'Unknown' }}</small></p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif



@if(isset($observation) && $observation->links->where('linktype', 'PROGRAMPLAN')->count())
    <p>Linked Program Plans</p>
    <div class="row mt-4">
        @foreach($observation->links->where('linktype', 'PROGRAMPLAN') as $link)
            @php
                $linked = \App\Models\ProgramPlanTemplateDetailsAdd::with(['room', 'creator'])->find($link->linkid);
            @endphp

            @if($linked)
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm obs-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                @php
                                    $monthNames = [
                                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                    ];
                                    $monthName = $monthNames[$linked->months] ?? 'Unknown Month';
                                @endphp
                                {{ $monthName }} {{ $linked->year }}
                            </h5>
                            <p class="card-text"><strong>Room:</strong> {{ $linked->room->name ?? 'Unknown Room' }}</p>
                            <p class="card-text"><small class="text-muted">Created by: {{ $linked->creator->name ?? 'Unknown' }}</small></p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif




    <!-- Modal -->
    <div class="modal" id="observationModal" tabindex="-1" role="dialog" aria-labelledby="obsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Observations</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body" style="overflow-y:auto;max-height:550px;">
                    <input type="text" id="searchObservation" class="form-control mb-3" placeholder="Search by title...">

                    <div id="observationList" class="row mb-3"></div>
                </div>
                <div class="modal-footer">
                <button id="submitSelectedObs" class="btn btn-success">Submit Selected Observations</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Reflection Modal -->
<div class="modal" id="reflectionModal" tabindex="-1" role="dialog" aria-labelledby="refModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Reflections</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="overflow-y:auto;max-height:550px;">
                <input type="text" id="searchReflection" class="form-control mb-3" placeholder="Search by title...">
                <div id="reflectionList" class="row mb-3"></div>
            </div>
            <div class="modal-footer">
                <button id="submitSelectedRef" class="btn btn-success">Submit Selected Reflections</button>
            </div>
        </div>
    </div>
</div>



<!-- Program Plan Modal -->
<div class="modal" id="programPlanModal" tabindex="-1" role="dialog" aria-labelledby="ppModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Program Plans</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="overflow-y:auto;max-height:550px;">
                <input type="text" id="searchProgramPlan" class="form-control mb-3" placeholder="Search by month name...">
                <div id="programPlanList" class="row mb-3"></div>
            </div>
            <div class="modal-footer">
                <button id="submitSelectedPP" class="btn btn-success">Submit Selected Program Plans</button>
            </div>
        </div>
    </div>
</div>

              <!-- end ASSESSMENT -->





                </div>
            </div>
        </div>
    </div>


</div>





<!-- Modal -->
<div class="modal" id="childrenModal" tabindex="-1" role="dialog" aria-labelledby="childrenModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center justify-content-between">
        <h5 class="modal-title" id="childrenModalLabel">Select Children</h5>
        <input type="text" id="childSearch" class="form-control ml-3" placeholder="Search children..." style="max-width: 250px;">
        <button type="button" class="close ml-2" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height:550px;overflow-y:auto;">
        <div id="childrenList" class="row"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmChildren" class="btn btn-success" >Confirm Selection</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>



<div class="modal" id="roomsModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <h5>Select Rooms</h5>
        <input type="text" id="roomSearch" class="form-control ml-3" placeholder="Search rooms..." style="max-width: 250px;">
      </div>
      <div class="modal-body" style="max-height:550px;overflow-y:auto;">
        <div id="roomsList" class="row"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmRooms" class="btn btn-success">Confirm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


<!-- title modal -->
<div class="modal" id="TitleModal" tabindex="-1" role="dialog" aria-labelledby="staffModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center justify-content-center">
       
      <h4 class="">New Observation</h4>
      </div>
      <form action="{{ route('observation.storeTitle') }}" method="post">
        @csrf
      <div class="modal-body" style="max-height:550px;overflow-y:auto;">
<div class="col-md-12 mt-4 form-section">
    <label for="editor">Title</label>
    <textarea id="editor" name="obestitle" class="form-control ckeditor" rows="5"></textarea>
    <div class="refine-container">
 <button type="button" class="btn btn-sm btn-primary mt-2 refine-btn" data-editor="editor"><i class="fas fa-magic mr-1"></i>Refine with Ai</button>
</div>
</div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="" class="btn btn-success" >Submit</button>
          <button type="button" class="btn btn-secondary" onclick="window.history.back()">
    <i class="fas fa-times mr-1"></i> Cancel
  </button>
      
      </div>
      </form>
    </div>
  </div>
</div>


<div id="toast-container" class="toast-bottom-right"
        style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;"></div>





        <script>
        $(document).ready(function () {
        let reflection = @json($observation);

        if (!reflection) {
            $('#TitleModal').modal('show');
        }
    });
    
</script>

<script>
let editors = {}; // store all CKEditor instances

document.addEventListener("DOMContentLoaded", function () {
    // Get all textareas with .ckeditor class and initialize them
    document.querySelectorAll(".ckeditor").forEach((textarea) => {
        let id = textarea.getAttribute("id");

        ClassicEditor.create(textarea)
            .then(editor => {
                editors[id] = editor;
                console.log(id + " ready ✅");

                // Attach change listener for autosave
                editor.model.document.on("change:data", () => {
                    AutoSave();
                });
            })
            .catch(error => console.error(id + " error ❌", error));
    });
});

// AutoSave function
function AutoSave() {
    // Collect data from all editors
    let dataToSave = {
        obestitle: editors["editor6"] ? editors["editor6"].getData() : "",
        title: editors["editor1"] ? editors["editor1"].getData() : "",
        notes: editors["editor2"] ? editors["editor2"].getData() : "",
        reflection: editors["editor3"] ? editors["editor3"].getData() : "",
        child_voice: editors["editor4"] ? editors["editor4"].getData() : "",
        future_plan: editors["editor5"] ? editors["editor5"].getData() : "",
        observation_id: document.querySelector('#observation_id') ? document.querySelector('#observation_id').value : null
    };

    console.log("AutoSaving...", dataToSave);

    fetch("{{ route('observation.autosave-observation') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify(dataToSave)
    })
    .then(response => response.json())
    .then(data => {
        console.log("AutoSave response ✅", data);

        if (data.status === 'success') {
            // Optional success toast
            // Swal.fire({
            //     icon: 'success',
            //     title: 'Saved!',
            //     text: data.message,
            //     timer: 1500,
            //     showConfirmButton: false
            // });

            // Update hidden observation_id if returned
            if (data.observation_id) {
                let hiddenIdField = document.querySelector('#observation_id');
                if (hiddenIdField) {
                    hiddenIdField.value = data.observation_id;
                }
            }
        } 
        else if (data.status === 'error') {
            // Show general error
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Error',
            //     text: data.message || 'Something went wrong.',
            // });

            // If there are validation errors, log or display them
            if (data.errors) {
        const friendlyNames = {
    obestitle: "Title",
    title: "Observation",
    notes: "Notes",
    reflection: "Reflection",
    child_voice: "Child Voice",
    future_plan: "Future Plan",
};

console.log("Validation errors:", data.errors);

Object.keys(data.errors).forEach(key => {
    let fieldName = friendlyNames[key] || key;
    // Show simple "Field is required" message
    // showToast('toast-error', `${fieldName} is required`);
});
               
                 
            }
        } 
        else {
            // Fallback for unexpected response
            Swal.fire({
                icon: 'warning',
                title: 'Unexpected response',
                text: 'Autosave returned an unknown status.',
            });
        }
    })
    .catch(error => {
        // Fallback for network/server errors
        console.error("AutoSave failed ❌", error);
    
    });
}

</script>

<script>
$(document).ready(function () {
    let selectedChildren = new Set($('#selected_children').val().split(',').filter(id => id));

    // Load children on modal open
    $('#childrenModal').on('show.bs.modal', function () {
        $.ajax({
            url: '{{ route("observation.get.children") }}',
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    let html = '';
                    response.children.forEach(child => {
                        const checked = selectedChildren.has(child.id.toString()) ? 'checked' : '';
                        html += `
                            <div class="col-md-4 mb-2 child-item">
                                <div class="form-check">
                                    <input class="form-check-input child-checkbox" type="checkbox" value="${child.id}" id="child-${child.id}" ${checked}>
                                    <label class="form-check-label" for="child-${child.id}">
                                        ${child.name} ${child.lastname}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    $('#childrenList').html(html);
                }
            }
        });
    });

    // Filter children
    $('#childSearch').on('keyup', function () {
        const search = $(this).val().toLowerCase();
        $('.child-item').each(function () {
            const name = $(this).find('.form-check-label').text().toLowerCase();
            $(this).toggle(name.includes(search));
        });
    });

    // Confirm selection
    $('#confirmChildren').on('click', function () {
        selectedChildren = new Set();
        let nameHtml = '';
        $('.child-checkbox:checked').each(function () {
            selectedChildren.add($(this).val());
            nameHtml += `<span class="badge badge-info mr-1">${$(this).next('label').text()}</span>`;
        });

        $('#selected_children').val([...selectedChildren].join(','));
        $('#selectedChildrenPreview').html(nameHtml);
        
        $('#childrenModal').modal('hide');
    });




    let selectedRooms = new Set($('#selected_rooms').val().split(',').filter(id => id));

$('#roomsModal').on('show.bs.modal', function () {
    $.get('{{ route("observation.get.rooms") }}', function (res) {
        if (res.success) {
            let html = '';
            res.rooms.forEach(room => {
                const checked = selectedRooms.has(room.id.toString()) ? 'checked' : '';
                html += `<div class="col-md-4 mb-2 room-item">
                    <div class="form-check">
                        <input class="form-check-input room-checkbox" type="checkbox" value="${room.id}" id="room-${room.id}" ${checked}>
                        <label class="form-check-label" for="room-${room.id}">${room.name}</label>
                    </div>
                </div>`;
            });
            $('#roomsList').html(html);
        }
    });
});

$('#roomSearch').on('keyup', function () {
    const val = $(this).val().toLowerCase();
    $('.room-item').each(function () {
        const name = $(this).find('label').text().toLowerCase();
        $(this).toggle(name.includes(val));
    });
});

$('#confirmRooms').on('click', function () {
    selectedRooms = new Set();
    let nameHtml = '';
    $('.room-checkbox:checked').each(function () {
        selectedRooms.add($(this).val());
        nameHtml += `<span class="badge badge-success mr-1">${$(this).next('label').text()}</span>`;
    });
    $('#selected_rooms').val([...selectedRooms].join(','));
    $('#selectedRoomsPreview').html(nameHtml);
    $('#roomsModal').modal('hide');
});





});
</script>

<!-- <script>
    ClassicEditor
        .create(document.querySelector('#editor1'))
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#editor2'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor3'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor4'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor5'))
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#editor6'))
        .catch(error => {
            console.error(error);
        });
</script> -->

<script>
    const editors = {};

    document.querySelectorAll('.ckeditor').forEach((el) => {
        ClassicEditor
            .create(el)
            .then(editor => {
                editors[el.id] = editor;
            })
            .catch(error => {
                console.error(error);
            });
    });

    document.querySelectorAll('.refine-btn').forEach(button => {
        button.addEventListener("click", function () {
            const editorId = this.getAttribute("data-editor");
            const editor = editors[editorId];

            if (!editor) return alert("Editor not found!");

            const content = editor.getData();
            const originalText = this.innerText;
            this.innerText = "Refining...";
            this.disabled = true;

            fetch("{{ route('observation.refine.text') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ text: content })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        editor.setData(data.refined_text);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(err => {
                    console.error("Refine Error:", err);
                    alert("Something went wrong!");
                })
                .finally(() => {
                    this.innerText = originalText;
                    this.disabled = false;
                });
        });
    });
</script>

<script>
let selectedFiles = [];

document.getElementById('mediaInput').addEventListener('change', function (event) {
    const previewContainer = document.getElementById('mediaPreview');
    const newFiles = Array.from(event.target.files);
    const totalFiles = selectedFiles.length + newFiles.length;

    if (totalFiles > 10) {
        alert("You can upload a maximum of 10 files.");
        this.value = '';
        return;
    }

    newFiles.forEach((file, index) => {
        const reader = new FileReader();
        const fileIndex = selectedFiles.length;

        reader.onload = function (e) {
            const col = document.createElement('div');
            col.className = 'col-md-3 position-relative mb-3';

            let mediaContent = '';

            if (file.type.startsWith('image/')) {
                mediaContent = `<img src="${e.target.result}" class="media-thumb rounded">`;
            } else if (file.type.startsWith('video/')) {
                mediaContent = `<video src="${e.target.result}" class="media-thumb rounded" controls></video>`;
            }

            col.innerHTML = `
                <div class="position-relative">
                    ${mediaContent}
                    <button type="button" class="btn btn-danger btn-sm remove-btn" data-index="${fileIndex}">✕</button>
                </div>
            `;

            previewContainer.appendChild(col);
        };

        reader.readAsDataURL(file);
        selectedFiles.push(file);
    });

    updateFileInput();
});

// Remove handler
document.getElementById('mediaPreview').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-btn')) {
        const index = parseInt(e.target.getAttribute('data-index'));
        selectedFiles.splice(index, 1);
        updateFileInput();
        renderPreview();
    }
});

// Re-render preview
function renderPreview() {
    const previewContainer = document.getElementById('mediaPreview');
    previewContainer.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function (e) {
            const col = document.createElement('div');
            col.className = 'col-md-3 position-relative mb-3';

            let mediaContent = '';

            if (file.type.startsWith('image/')) {
                mediaContent = `<img src="${e.target.result}" class="media-thumb rounded">`;
            } else if (file.type.startsWith('video/')) {
                mediaContent = `<video src="${e.target.result}" class="media-thumb rounded" controls></video>`;
            }

            col.innerHTML = `
                <div class="position-relative">
                    ${mediaContent}
                    <button type="button" class="btn btn-danger btn-sm remove-btn" data-index="${index}">✕</button>
                </div>
            `;

            previewContainer.appendChild(col);
        };

        reader.readAsDataURL(file);
    });
}

// Update file input value from selectedFiles
function updateFileInput() {
    const input = document.getElementById('mediaInput');
    const dataTransfer = new DataTransfer();

    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
}


function showToast(type, message) {
        const isSuccess = type === 'success';
        const toastType = isSuccess ? 'toast-success' : 'toast-error';
        const ariaLive = isSuccess ? 'polite' : 'assertive';

        const toast = `
        <div class="toast ${toastType}" aria-live="${ariaLive}" style="min-width: 250px; margin-bottom: 10px;">
            <button type="button" class="toast-close-button" role="button" onclick="this.parentElement.remove()">×</button>
            <div class="toast-message" style="color: white;">${message}</div>
        </div>
    `;

        // Append the toast to the container
        $('#toast-container').append(toast);

        // Automatically fade out and remove this specific toast after 3 seconds
        setTimeout(() => {
            $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }



$(document).ready(function () {
    $('#observationform').on('submit', function (e) {
        e.preventDefault();



        const form = $('#observationform')[0];
        const formData = new FormData(form);

        // Append selected files (including rotated ones)
        // selectedFiles.forEach((file, index) => {
        //     formData.append('media[]', file);
        // });

        $.ajax({
            url: "{{ route('observation.store') }}", // 👈 Your Laravel route
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for Laravel
            },
            beforeSend: function () {
                // Optional: show loader or disable button
                $('button[type=submit]').prop('disabled', true).text('Submitting...');
            },
            success: function(response) {
    if (response.status === 'success') {
        showToast('success', 'Observation Added Successfully!');
        setTimeout(() => {
            window.location.href = '/observation/addnew/' + response.id + '/assessment'; // or 'link', or 'observation'
        }, 1500);
    } else {
        showToast('error', response.message || 'Update failed');
    }
},
            error: function(xhr) {
                if (xhr.status === 422) {
                    Object.values(xhr.responseJSON.errors).forEach(error => {
                        showToast('error', error[0]);
                    });
                } else {
                    showToast('error', 'Server error occurred');
                }
            },
            complete: function () {
                $('button[type=submit]').prop('disabled', false).text('Submit');
            }
        });
    });
});
</script>

<script>
 function deleteMedia(id, fileUrl) {
    Swal.fire({
        title: 'What do you want to do?',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Download & Delete',
        denyButtonText: 'Delete Only',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#0d6efd',
        denyButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Download first
            const link = document.createElement('a');
            link.href = fileUrl;
            link.download = '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Then delete
            performDelete(id);
        } else if (result.isDenied) {
            // Delete without download
            performDelete(id);
        }
    });
}

function performDelete(id) {
    fetch(`/observation/observation-media/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            document.getElementById(`media-${id}`).remove();
            Swal.fire('Deleted!', 'The media has been removed.', 'success');
        } else {
            throw new Error('Delete failed');
        }
    })
    .catch(() => {
        Swal.fire('Error!', 'Something went wrong.', 'error');
    });
}

</script>


<script>

function showToast(type, message) {
        const isSuccess = type === 'success';
        const toastType = isSuccess ? 'toast-success' : 'toast-error';
        const ariaLive = isSuccess ? 'polite' : 'assertive';

        const toast = `
        <div class="toast ${toastType}" aria-live="${ariaLive}" style="min-width: 250px; margin-bottom: 10px;">
            <button type="button" class="toast-close-button" role="button" onclick="this.parentElement.remove()">×</button>
            <div class="toast-message" style="color: white;">${message}</div>
        </div>
    `;

        // Append the toast to the container
        $('#toast-container').append(toast);

        // Automatically fade out and remove this specific toast after 3 seconds
        setTimeout(() => {
            $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }



    $('#saveMontessoriData').on('click', function (e) {
    e.preventDefault();

    let $button = $(this);
    let originalText = $button.html();

    // Change button text and disable it
    $button.html('Saving...').prop('disabled', true);

    let observationId = $('#observation_id').val();

    if (!observationId) {
        showToast('error', 'Please Create Observation First');
        $button.html(originalText).prop('disabled', false);
        return;
    }

    let data = {
        observationId: observationId,
        subactivities: []
    };

    // FIX: Only select radio buttons within the MONTESSORI tab
    $('#MONTESSORI input[type=radio]:checked').each(function () {
        // Also ensure we're only getting Montessori subactivity radio buttons
        let name = $(this).attr('name');
        if (name && name.startsWith('subactivity[')) {
            let subId = name.match(/\d+/)[0];
            let value = $(this).val();
            if (value !== 'Not Assessed') {
                data.subactivities.push({
                    idSubActivity: subId,
                    assesment: value
                });
            }
        }
    });

    $.ajax({
        url: "{{ route('observation.montessori.store') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            ...data
        },
        success: function(response) {
            if (response.status === 'success') {
                showToast('success', 'Montessori Added Successfully');
                $button.html('Saved!');
                setTimeout(() => {
                    window.location.href = '/observation/addnew/' + response.id + '/assessment' + '/EYLF';
                }, 100);
            } else {
                showToast('error', response.message || 'Update failed');
            }
        },
        error: function(xhr) {
            $button.html(originalText).prop('disabled', false);
            if (xhr.status === 422) {
                Object.values(xhr.responseJSON.errors).forEach(error => {
                    showToast('error', error[0]);
                });
            } else {
                showToast('error', 'Server error occurred');
            }
        }
    });
});

</script>



<script>

function showToast(type, message) {
        const isSuccess = type === 'success';
        const toastType = isSuccess ? 'toast-success' : 'toast-error';
        const ariaLive = isSuccess ? 'polite' : 'assertive';

        const toast = `
        <div class="toast ${toastType}" aria-live="${ariaLive}" style="min-width: 250px; margin-bottom: 10px;">
            <button type="button" class="toast-close-button" role="button" onclick="this.parentElement.remove()">×</button>
            <div class="toast-message" style="color: white;">${message}</div>
        </div>
    `;

        // Append the toast to the container
        $('#toast-container').append(toast);

        // Automatically fade out and remove this specific toast after 3 seconds
        setTimeout(() => {
            $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }




$(function () {
    // EYLF: Show outcome's tab
    $('#eylfOutcomeSelect').on('change', function () {
        var selected = $(this).val();
        $('#eylf-tabs .tab-pane').removeClass('active show');
        if (selected) {
            $('#' + selected).addClass('active show');
        }
    });

    // Save EYLF Subactivities
    $('#saveEylfData').on('click', function (e) {
        e.preventDefault();

        let $button = $(this);
        let originalText = $button.html();

    // Change button text and disable it
        $button.html('Saving...').prop('disabled', true);

        let observationId = $('#observation_id').val();
        if (!observationId) {
            showToast('error', 'Please Create Observation First');
            $button.html(originalText).prop('disabled', false);
            return;
        }

        let selectedSubactivities = [];
        $('input[name="eylf_subactivity[]"]:checked').each(function () {
            selectedSubactivities.push($(this).val());
        });

        $.ajax({
            url: "{{ route('observation.eylf.store') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                observationId: observationId,
                subactivityIds: selectedSubactivities
            },
            success: function (response) {
                if (response.status === 'success') {
                    showToast('success', 'EYLF Activities Added Successfully');
                    $button.html('Saved!');
                    setTimeout(() => {
                        window.location.href = '/observation/addnew/' + response.id + '/assessment' + '/MILESTONE';
                    }, 100);
                } else {
                    showToast('error', response.message || 'Update failed');
                }
            },
            error: function (xhr) {
                $button.html(originalText).prop('disabled', false);
                if (xhr.status === 422) {
                    Object.values(xhr.responseJSON.errors).forEach(error => {
                        showToast('error', error[0]);
                    });
                } else {
                    showToast('error', 'Server error occurred');
                }
            }
        });
    });
});
</script>


<script>

function showToast(type, message) {
        const isSuccess = type === 'success';
        const toastType = isSuccess ? 'toast-success' : 'toast-error';
        const ariaLive = isSuccess ? 'polite' : 'assertive';

        const toast = `
        <div class="toast ${toastType}" aria-live="${ariaLive}" style="min-width: 250px; margin-bottom: 10px;">
            <button type="button" class="toast-close-button" role="button" onclick="this.parentElement.remove()">×</button>
            <div class="toast-message" style="color: white;">${message}</div>
        </div>
    `;

        // Append the toast to the container
        $('#toast-container').append(toast);

        // Automatically fade out and remove this specific toast after 3 seconds
        setTimeout(() => {
            $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }




$(function() {
    // Handle age group selection
    $('#devAgeSelect').on('change', function() {
        const sel = $(this).val();
        $('#devmilestone-tabs .tab-pane').removeClass('active show');
        if (sel) $('#' + sel).addClass('active show');
    });

    // Save milestone form
    $('#saveDevMilestone').on('click', function(e) {

        let $button = $(this);
        let originalText = $button.html();

    // Change button text and disable it
        $button.html('Saving...').prop('disabled', true);

        e.preventDefault();
        const obsId = $('#observation_id').val();
        if (!obsId) {
            showToast('error', 'Please create the observation first');
            $button.html(originalText).prop('disabled', false);
            return;
        }

        const selections = [];
        $('input[name^="devsub_"]:checked').each(function() {
            selections.push({
                idSub: $(this).data('subid'),
                assessment: $(this).val()
            });
        });

        $.ajax({
            url: "{{ route('observation.devmilestone.store') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                observationId: obsId,
                selections: selections
            },
            success: function(res) {
                showToast('success', 'Development milestones saved!');
                $button.html('Saved!');
                setTimeout(() => {
                    window.location.href = '/observation/addnew/' + res.id + '/link';
                }, 100);
            },
            error: function(xhr) {
                $button.html(originalText).prop('disabled', false);
                if (xhr.status === 422) {
                    Object.values(xhr.responseJSON.errors).forEach(err => showToast('error', err[0]));
                } else showToast('error', 'Server error occurred');


            }
        });
    });
});
</script>

<script>

function showToast(type, message) {
        const isSuccess = type === 'success';
        const toastType = isSuccess ? 'toast-success' : 'toast-error';
        const ariaLive = isSuccess ? 'polite' : 'assertive';

        const toast = `
        <div class="toast ${toastType}" aria-live="${ariaLive}" style="min-width: 250px; margin-bottom: 10px;">
            <button type="button" class="toast-close-button" role="button" onclick="this.parentElement.remove()">×</button>
            <div class="toast-message" style="color: white;">${message}</div>
        </div>
    `;

        // Append the toast to the container
        $('#toast-container').append(toast);

        // Automatically fade out and remove this specific toast after 3 seconds
        setTimeout(() => {
            $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }


function handleObservationStatusChange(status) {
    const $button = status === 'Published' ? $('#publishObservation') : $('#draftObservation');
    const originalText = $button.html();
    $button.html('Processing...').prop('disabled', true);

    const obsId = $('#observation_id').val();
    if (!obsId) {
        showToast('error', 'Please create the observation first');
        $button.html(originalText).prop('disabled', false);
        return;
    }

    $.ajax({
        url: "{{ route('observation.status.update') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            observationId: obsId,
            status: status
        },
        success: function(res) {
            if (res.status === 'success') {
                showToast('success', `Observation marked as ${status}`);
                $button.html('Saved!');
                setTimeout(() => {
                    window.location.href = "{{ route('observation.index') }}";
                }, 1500);
            } else {
                showToast('error', res.message || 'Something went wrong');
                $button.html(originalText).prop('disabled', false);
            }
        },
        error: function(xhr) {
            $button.html(originalText).prop('disabled', false);
            if (xhr.status === 422) {
                Object.values(xhr.responseJSON.errors).forEach(err => showToast('error', err[0]));
            } else showToast('error', 'Server error occurred');
        }
    });
}

// Bind events
$('#publishObservation').on('click', function() {
    handleObservationStatusChange('Published');
});

$('#draftObservation').on('click', function() {
    handleObservationStatusChange('Draft');
});
</script>


<script>

function showToast(type, message) {
        const isSuccess = type === 'success';
        const toastType = isSuccess ? 'toast-success' : 'toast-error';
        const ariaLive = isSuccess ? 'polite' : 'assertive';

        const toast = `
        <div class="toast ${toastType}" aria-live="${ariaLive}" style="min-width: 250px; margin-bottom: 10px;">
            <button type="button" class="toast-close-button" role="button" onclick="this.parentElement.remove()">×</button>
            <div class="toast-message" style="color: white;">${message}</div>
        </div>
    `;

        // Append the toast to the container
        $('#toast-container').append(toast);

        // Automatically fade out and remove this specific toast after 3 seconds
        setTimeout(() => {
            $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }



let selectedObservationIds = [];


document.getElementById('btnLinkObservation').addEventListener('click', function () {
    $('#observationModal').modal('show');
    fetchObservations('');
});

document.getElementById('searchObservation').addEventListener('keyup', function () {
    let query = this.value;
    fetchObservations(query);
});

function fetchObservations(query) {
    const obsId = $('#observation_id').val();

    fetch(`/observation/observationslink?search=${encodeURIComponent(query)}&obsId=${obsId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('observationList');
            container.innerHTML = '';

            const observations = data.observations;
            const linkedIds = data.linked_ids.map(id => id.toString());

            // Merge initially fetched linked IDs into selectedObservationIds
            linkedIds.forEach(id => {
                if (!selectedObservationIds.includes(id)) selectedObservationIds.push(id);
            });

            if (observations.length === 0) {
                container.innerHTML = '<p class="text-center col-12">No observations found.</p>';
                return;
            }

            observations.forEach(obs => {
                const imageUrl = obs.media?.[0]?.mediaUrl
                    ? `/${obs.media[0].mediaUrl}`
                    : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg';

                const title = obs.obestitle ?? 'Untitled';
                const createdBy = obs.user?.name ?? 'Unknown';
                const isChecked = selectedObservationIds.includes(obs.id.toString()) ? 'checked' : '';

                const card = `
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm obs-card">
                            <img src="${imageUrl}" class="card-img-top obs-img" alt="${title}">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input obs-checkbox" type="checkbox" value="${obs.id}" id="obs${obs.id}" ${isChecked}>
                                    <label class="form-check-label" for="obs${obs.id}">${title}</label>
                                </div>
                                <p class="card-text"><small class="text-muted">Created by: ${createdBy}</small></p>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });

            // Rebind checkbox events
            document.querySelectorAll('.obs-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {
                    const id = this.value;
                    if (this.checked) {
                        if (!selectedObservationIds.includes(id)) selectedObservationIds.push(id);
                    } else {
                        selectedObservationIds = selectedObservationIds.filter(item => item !== id);
                    }
                });
            });
        });
}


document.getElementById('submitSelectedObs').addEventListener('click', function () {
    const obsId = $('#observation_id').val();
    let $button = $(this);
    let originalText = $button.html();

    if (!obsId) {
        showToast('error', 'Please create the observation first');
        $button.html(originalText).prop('disabled', false);
        return;
    }

    if (selectedObservationIds.length === 0) {
        showToast('error', 'Please select at least one observation.');
        return;
    }

    $button.html('Saving...').prop('disabled', true);

    fetch('/observation/submit-selectedoblink', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ observation_ids: selectedObservationIds, obsId: obsId })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw { status: response.status, data };

        showToast('success', 'Observations linked successfully!');
        $button.html('Saved!');
        setTimeout(() => {
            window.location.href = `/observation/addnew/${data.id}/link`;
        }, 100);
    })
    .catch(err => {
        $button.html(originalText).prop('disabled', false);

        if (err.status === 422 && err.data.errors) {
            Object.values(err.data.errors).forEach(error => showToast('error', error[0]));
        } else {
            showToast('error', 'Server error occurred');
        }
    });
});




let selectedReflectionIds = [];

// Existing observation code remains the same...

// Reflection functionality
document.getElementById('btnLinkReflection').addEventListener('click', function () {
    $('#reflectionModal').modal('show');
    fetchReflections('');
});

document.getElementById('searchReflection').addEventListener('keyup', function () {
    let query = this.value;
    fetchReflections(query);
});

function fetchReflections(query) {
    const obsId = $('#observation_id').val();

    fetch(`/observation/reflectionslink?search=${encodeURIComponent(query)}&obsId=${obsId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('reflectionList');
            container.innerHTML = '';

            const reflections = data.reflections;
            const linkedIds = data.linked_ids.map(id => id.toString());

            // Merge initially fetched linked IDs into selectedReflectionIds
            linkedIds.forEach(id => {
                if (!selectedReflectionIds.includes(id)) selectedReflectionIds.push(id);
            });

            if (reflections.length === 0) {
                container.innerHTML = '<p class="text-center col-12">No reflections found.</p>';
                return;
            }

            reflections.forEach(ref => {
                const imageUrl = ref.media?.[0]?.mediaUrl
                    ? `/${ref.media[0].mediaUrl}`
                    : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg';

                const title = ref.title ?? 'Untitled';
                const createdBy = ref.creator?.name ?? 'Unknown';
                const isChecked = selectedReflectionIds.includes(ref.id.toString()) ? 'checked' : '';

                const card = `
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm obs-card">
                            <img src="${imageUrl}" class="card-img-top obs-img" alt="${title}">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input ref-checkbox" type="checkbox" value="${ref.id}" id="ref${ref.id}" ${isChecked}>
                                    <label class="form-check-label" for="ref${ref.id}">${title}</label>
                                </div>
                                <p class="card-text"><small class="text-muted">Created by: ${createdBy}</small></p>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });

            // Rebind checkbox events
            document.querySelectorAll('.ref-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {
                    const id = this.value;
                    if (this.checked) {
                        if (!selectedReflectionIds.includes(id)) selectedReflectionIds.push(id);
                    } else {
                        selectedReflectionIds = selectedReflectionIds.filter(item => item !== id);
                    }
                });
            });
        });
}

document.getElementById('submitSelectedRef').addEventListener('click', function () {
    const obsId = $('#observation_id').val();
    let $button = $(this);
    let originalText = $button.html();

    if (!obsId) {
        showToast('error', 'Please create the observation first');
        $button.html(originalText).prop('disabled', false);
        return;
    }

    if (selectedReflectionIds.length === 0) {
        showToast('error', 'Please select at least one reflection.');
        return;
    }

    $button.html('Saving...').prop('disabled', true);

    fetch('/observation/submit-selectedreflink', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ reflection_ids: selectedReflectionIds, obsId: obsId })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw { status: response.status, data };

        showToast('success', 'Reflections linked successfully!');
        $button.html('Saved!');
        setTimeout(() => {
            window.location.href = `/observation/addnew/${data.id}/link`;
        }, 100);
    })
    .catch(err => {
        $button.html(originalText).prop('disabled', false);

        if (err.status === 422 && err.data.errors) {
            Object.values(err.data.errors).forEach(error => showToast('error', error[0]));
        } else {
            showToast('error', 'Server error occurred');
        }
    });
});



let selectedProgramPlanIds = [];

// Month names mapping for search
const monthNames = {
    1: 'January', 2: 'February', 3: 'March', 4: 'April',
    5: 'May', 6: 'June', 7: 'July', 8: 'August',
    9: 'September', 10: 'October', 11: 'November', 12: 'December'
};

// Existing observation and reflection code remains the same...

// Program Plan functionality
document.getElementById('btnLinkProgramPlan').addEventListener('click', function () {
    $('#programPlanModal').modal('show');
    fetchProgramPlans('');
});

document.getElementById('searchProgramPlan').addEventListener('keyup', function () {
    let query = this.value;
    fetchProgramPlans(query);
});

function fetchProgramPlans(query) {
    const obsId = $('#observation_id').val();

    fetch(`/observation/programplanslink?search=${encodeURIComponent(query)}&obsId=${obsId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('programPlanList');
            container.innerHTML = '';

            const programPlans = data.program_plans;
            const linkedIds = data.linked_ids.map(id => id.toString());

            // Merge initially fetched linked IDs into selectedProgramPlanIds
            linkedIds.forEach(id => {
                if (!selectedProgramPlanIds.includes(id)) selectedProgramPlanIds.push(id);
            });

            if (programPlans.length === 0) {
                container.innerHTML = '<p class="text-center col-12">No program plans found.</p>';
                return;
            }

            programPlans.forEach(pp => {
                const monthName = monthNames[pp.months] || 'Unknown Month';
                const title = `${monthName} ${pp.years}`;
                const roomName = pp.room?.name ?? 'Unknown Room';
                const createdBy = pp.creator?.name ?? 'Unknown';
                const isChecked = selectedProgramPlanIds.includes(pp.id.toString()) ? 'checked' : '';

                const card = `
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm obs-card">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input pp-checkbox" type="checkbox" value="${pp.id}" id="pp${pp.id}" ${isChecked}>
                                    <label class="form-check-label" for="pp${pp.id}"><strong>${title}</strong></label>
                                </div>
                                <p class="card-text"><strong>Room:</strong> ${roomName}</p>
                                <p class="card-text"><small class="text-muted">Created by: ${createdBy}</small></p>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });

            // Rebind checkbox events
            document.querySelectorAll('.pp-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {
                    const id = this.value;
                    if (this.checked) {
                        if (!selectedProgramPlanIds.includes(id)) selectedProgramPlanIds.push(id);
                    } else {
                        selectedProgramPlanIds = selectedProgramPlanIds.filter(item => item !== id);
                    }
                });
            });
        });
}

document.getElementById('submitSelectedPP').addEventListener('click', function () {
    const obsId = $('#observation_id').val();
    let $button = $(this);
    let originalText = $button.html();

    if (!obsId) {
        showToast('error', 'Please create the observation first');
        $button.html(originalText).prop('disabled', false);
        return;
    }

    if (selectedProgramPlanIds.length === 0) {
        showToast('error', 'Please select at least one program plan.');
        return;
    }

    $button.html('Saving...').prop('disabled', true);

    fetch('/observation/submit-selectedpplink', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ program_plan_ids: selectedProgramPlanIds, obsId: obsId })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw { status: response.status, data };

        showToast('success', 'Program Plans linked successfully!');
        $button.html('Saved!');
        setTimeout(() => {
            window.location.href = `/observation/addnew/${data.id}/link`;
        }, 100);
    })
    .catch(err => {
        $button.html(originalText).prop('disabled', false);

        if (err.status === 422 && err.data.errors) {
            Object.values(err.data.errors).forEach(error => showToast('error', error[0]));
        } else {
            showToast('error', 'Server error occurred');
        }
    });
});


</script>


<script>
// Initialize triangle states on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all triangles based on checked radio buttons
    const radios = document.querySelectorAll('.assessment-radio');
    radios.forEach(radio => {
        if (radio.checked) {
            updateTriangle(radio.dataset.subId, radio.dataset.level);
        }
    });

    // Add event listeners to all radio buttons
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                updateTriangle(this.dataset.subId, this.dataset.level);
            }
        });
    });
});

function updateTriangle(subId, level) {
    const triangle = document.getElementById(`triangle-${subId}`);
    if (!triangle) return;

    // Remove all existing level classes
    triangle.classList.remove('level-1', 'level-2', 'level-3');

    // Add animation class
    triangle.classList.add('animate');

    // Add new level class
    if (level) {
        triangle.classList.add(`level-${level}`);
    }

    // Remove animation class after animation completes
    setTimeout(() => {
        triangle.classList.remove('animate');
    }, 600);
}

function clearAssessment(subId) {
    // Clear radio buttons
    const radios = document.getElementsByName(`subactivity[${subId}]`);
    radios.forEach(radio => radio.checked = false);

    // Clear triangle visualization
    updateTriangle(subId, null);

    // Add a subtle feedback animation
    const triangle = document.getElementById(`triangle-${subId}`);
    if (triangle) {
        triangle.style.transform = 'scale(0.9)';
        setTimeout(() => {
            triangle.style.transform = 'scale(1)';
        }, 150);
    }
}

// Add smooth transitions when hovering over options
document.querySelectorAll('.assessment-label').forEach(label => {
    label.addEventListener('mouseenter', function() {
        const radio = this.previousElementSibling;
        const subId = radio.dataset.subId;
        const level = radio.dataset.level;
        const triangle = document.getElementById(`triangle-${subId}`);

        // Only show hover effect if no option is currently selected
        const allRadios = document.getElementsByName(`subactivity[${subId}]`);
        const hasSelection = Array.from(allRadios).some(r => r.checked);

        if (triangle && !hasSelection) {
            triangle.style.opacity = '0.6';
            triangle.classList.add(`level-${level}`);
        }
    });

    label.addEventListener('mouseleave', function() {
        const radio = this.previousElementSibling;
        const subId = radio.dataset.subId;
        const triangle = document.getElementById(`triangle-${subId}`);

        // Only clear hover effect if no option is currently selected
        const allRadios = document.getElementsByName(`subactivity[${subId}]`);
        const hasSelection = Array.from(allRadios).some(r => r.checked);

        if (triangle && !hasSelection) {
            triangle.style.opacity = '1';
            triangle.classList.remove('level-1', 'level-2', 'level-3');
        }
    });
});
</script>


<script>


function showToast(type, message) {
        const isSuccess = type === 'success';
        const toastType = isSuccess ? 'toast-success' : 'toast-error';
        const ariaLive = isSuccess ? 'polite' : 'assertive';

        const toast = `
        <div class="toast ${toastType}" aria-live="${ariaLive}" style="min-width: 250px; margin-bottom: 10px;">
            <button type="button" class="toast-close-button" role="button" onclick="this.parentElement.remove()">×</button>
            <div class="toast-message" style="color: white;">${message}</div>
        </div>
    `;

        // Append the toast to the container
        $('#toast-container').append(toast);

        // Automatically fade out and remove this specific toast after 3 seconds
        setTimeout(() => {
            $(`#toast-container .toast:contains('${message}')`).fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }




    document.addEventListener("DOMContentLoaded", function() {
    const display = document.getElementById('createdAtDisplay');
    const input = document.getElementById('editCreatedAt');

    // Initialize Flatpickr on the input
    const fp = flatpickr(input, {
        dateFormat: "d M Y",
        defaultDate: input.value,
        onChange: function(selectedDates, dateStr, instance) {
            // Send AJAX only if a date is selected
            if(selectedDates.length) {
                const formatted = selectedDates[0].toISOString().split('T')[0];
                fetch('{{ route("observation.changeCreatedAt") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        id: {{ $observation->id ?? 'null' }},
                        created_at: formatted
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Update display with new formatted date
                        display.innerHTML = '<i class="far fa-calendar-alt mr-1"></i>' + dateStr;
                        showToast('success', 'Date Changed Successfully');
                    } else {
                        showToast('error', data.message ?? 'Could not update');
                    }
                    input.style.display = "none";
                    display.style.display = "inline-block";
                })
                .catch(err => {
                    showToast('error', 'Error: ' + err.message);
                    input.style.display = "none";
                    display.style.display = "inline-block";
                });
            }
        },
        allowInput: true,
        clickOpens: true
    });

    // Show flatpickr input when span clicked
    display.addEventListener('click', function() {
        display.style.display = "none";
        input.style.display = "inline-block";
        fp.open();
    });

    // Hide input on blur (after a short delay to allow date selection)
    input.addEventListener('blur', function(){
        setTimeout(function(){
            input.style.display = "none";
            display.style.display = "inline-block";
        }, 200);
    });
});


</script>
<script>
   // Disable Bootstrap's collapse and use custom implementation
$(document).off('click.bs.collapse.data-api');

$(document).on('click', '[data-toggle="collapse"]', function(e) {
    e.preventDefault();
    
    const target = $(this).attr('data-target');
    const $target = $(target);
    const $button = $(this);
    const parent = $button.attr('data-parent');
    
    // Clean up all collapsing states
    $('.collapsing').removeClass('collapsing').removeAttr('style');
    
    if (parent) {
        // Close all other items in accordion
        $(parent).find('.collapse.show').removeClass('show').removeAttr('style');
        $(parent).find('[data-toggle="collapse"]').addClass('collapsed').attr('aria-expanded', 'false');
    }
    
    // Toggle current item
    if ($target.hasClass('show')) {
        $target.removeClass('show').removeAttr('style');
        $button.addClass('collapsed').attr('aria-expanded', 'false');
    } else {
        $target.addClass('show').removeAttr('style');
        $button.removeClass('collapsed').attr('aria-expanded', 'true');
    }
});
</script>



@include('layout.footer')
@stop




