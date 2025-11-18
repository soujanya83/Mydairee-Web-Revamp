@extends('layout.master')
@section('title', 'Daily Diary')
@section('parentPageTitle', '')


<style>
.custom-datepicker {
    width: 250px;
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 6px;
    border: 2px solid #007bff;
    background-color: white;
    color: #007bff;
    cursor: pointer;
}

.custom-datepicker:hover {
    background-color: #e6f0ff;
}

.custom-datepicker:focus {
    border-color: #0056b3;
    outline: none;
    box-shadow: 0 0 4px rgba(0, 123, 255, 0.6);
}
</style>




<style>
:root {
    --primary-color: #6c5ce7;
    --secondary-color: #a29bfe;
    --success-color: #00b894;
    --warning-color: #fdcb6e;
    --danger-color: #e17055;
    --info-color: #74b9ff;
    --light-bg: #f8f9fa;
    --card-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    --border-radius: 12px;
}

body {
    /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
    /* background-color: #49c5b6; */
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.main-container {
    padding: 20px 0;
}

.page-header {
    /* background: rgba(255, 255, 255, 0.95); */
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: var(--card-shadow);
}

.child-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    margin-bottom: 30px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.child-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.child-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 20px;
    position: relative;
    overflow: hidden;
}

.child-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 200%;
    background: rgba(255, 255, 255, 0.1);
    transform: rotate(45deg);
}

.child-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, 0.3);
    object-fit: cover;
    margin-right: 20px;
}

.child-info h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.child-info p {
    margin: 5px 0 0 0;
    opacity: 0.9;
}

.care-activities {
    padding: 25px;
}

.activity-section {
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
    border-radius: var(--border-radius);
    overflow: hidden;
}

.activity-header {
    background: var(--light-bg);
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.activity-header:hover {
    background: #e9ecef;
}

.activity-header h5 {
    margin: 0;
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #495057;
}

.activity-icon {
    width: 24px;
    margin-right: 10px;
    color: var(--primary-color);
}

.activity-content {
    padding: 20px;
    background: white;
}

.activity-entry {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    border-left: 4px solid var(--primary-color);
}

.activity-entry:last-child {
    margin-bottom: 0;
}

.entry-row {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.entry-row:last-child {
    margin-bottom: 0;
}

.entry-item {
    margin-right: 20px;
    margin-bottom: 5px;
}

.entry-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.entry-value {
    color: #6c757d;
    margin-left: 5px;
}

.badge-status {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.collapse-icon {
    transition: transform 0.3s ease;
    margin-left: auto;
}

.collapsed .collapse-icon {
    transform: rotate(-90deg);
}

.stats-row {
    background: rgba(108, 92, 231, 0.1);
    border-radius: var(--border-radius);
    padding: 15px;
    margin-bottom: 20px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary-color);
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .child-header {
        text-align: center;
    }

    .child-avatar {
        margin: 0 0 15px 0;
    }

    .entry-row {
        flex-direction: column;
    }

    .entry-item {
        margin-right: 0;
    }
}

.activity-breakfast {
    border-left-color: #e17055;
}

.activity-morning-tea {
    border-left-color: #00b894;
}

.activity-lunch {
    border-left-color: #fdcb6e;
}

.activity-sleep {
    border-left-color: #6c5ce7;
}

.activity-afternoon-tea {
    border-left-color: #74b9ff;
}

.activity-snacks {
    border-left-color: #fd79a8;
}
.activity-bottle {
    border-left-color: #fd79a8;
}

.activity-sunscreen {
    border-left-color: #ffeaa7;
}

.activity-toileting {
    border-left-color: #81ecec;
}
</style>




<style>
/* Modal Styles */
.modal-xl {
    max-width: 1200px;
}

.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #6c5ce7, #a29bfe);
    color: white;
    border: none;
    padding: 20px 30px;
}

.modal-title {
    font-weight: 600;
    font-size: 1.25rem;
}

.modal-body {
    min-height: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

.close {
    color: white;
    opacity: 0.8;
    font-size: 1.5rem;
}

.close:hover {
    color: white;
    opacity: 1;
}

/* Sidebar Styles */
.modal-sidebar {
    background: #f8f9fa;
    border-right: 1px solid #e9ecef;
    min-height: 600px;
}

.sidebar-header {
    padding: 20px;
    background: #e9ecef;
    border-bottom: 1px solid #dee2e6;
}

.sidebar-header h6 {
    margin: 0;
    font-weight: 600;
    color: #495057;
}

.sidebar-nav {
    padding: 10px 0;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.nav-item:hover {
    background: #e9ecef;
    color: #495057;
    text-decoration: none;
    border-left-color: #6c5ce7;
}

.nav-item.active {
    background: #6c5ce7;
    color: white;
    border-left-color: #5a4fcf;
}

.nav-item i {
    width: 20px;
    margin-right: 12px;
    font-size: 1.1rem;
}

.nav-item span {
    font-weight: 500;
}

/* Main Content Styles */
.modal-main-content {
    background: white;
}

.content-header {
    padding: 20px 30px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.content-header h5 {
    margin: 0;
    font-weight: 600;
    color: #495057;
}

.form-container {
    padding: 30px;
}

.form-section {
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.form-label i {
    color: #6c5ce7;
}

/* Custom Form Controls */
.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
}

/* Date Picker Styles */
.date-picker-wrapper {
    position: relative;
}

.date-picker {
    background: linear-gradient(135deg, #fff, #f8f9fa);
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #495057;
    transition: all 0.3s ease;
}

.date-picker:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
    background: white;
}

/* Time Picker Styles */
.time-picker-wrapper {
    position: relative;
}

.time-picker {
    background: linear-gradient(135deg, #fff, #f8f9fa);
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #495057;
    transition: all 0.3s ease;
}

.time-picker:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
    background: white;
}

/* Children Selection Styles */
.children-selection {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.child-checkbox {
    position: relative;
}

.custom-checkbox {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.child-label {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    color: #495057;
}

.child-label:hover {
    border-color: #6c5ce7;
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.custom-checkbox:checked + .child-label {
    border-color: #6c5ce7;
    background: linear-gradient(135deg, #6c5ce7, #a29bfe);
    color: white;
    box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
}

.child-thumb {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 15px;
    border: 2px solid #e9ecef;
    object-fit: cover;
}

.custom-checkbox:checked + .child-label .child-thumb {
    border-color: white;
}

/* Custom Select Styles */
.custom-select {
    background: linear-gradient(135deg, #fff, #f8f9fa);
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #495057;
    transition: all 0.3s ease;
}

.custom-select:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
    background: white;
}

/* Activity Forms */
.activity-form {
    display: none;
}

.activity-form.active {
    display: block;
}

/* Multiple Entry Section */
.multiple-entry-section {
    background: #e8f4f8;
    border: 1px solid #bee5eb;
    border-radius: 8px;
    padding: 15px;
}

.form-check-label {
    font-weight: 500;
    color: #495057;
    cursor: pointer;
}

.form-check-input:checked ~ .form-check-label {
    color: #6c5ce7;
}

/* Modal Footer */
.modal-footer {
    border: none;
    padding: 20px 30px;
    background: #f8f9fa;
}

.btn {
    padding: 10px 25px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #6c5ce7, #a29bfe);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a4fcf, #9085e8);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
}

.btn-secondary {
    background: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
        margin: 10px auto;
    }
    
    .modal-body {
        min-height: 500px;
        max-height: 70vh;
    }
    
    .row.no-gutters {
        flex-direction: column;
    }
    
    .modal-sidebar {
        min-height: auto;
        border-right: none;
        border-bottom: 1px solid #e9ecef;
    }
    
    .sidebar-nav {
        display: flex;
        overflow-x: auto;
        padding: 10px;
    }
    
    .nav-item {
        flex-shrink: 0;
        min-width: 120px;
        text-align: center;
        margin-right: 5px;
        border-radius: 8px;
        border-left: none;
        border-bottom: 3px solid transparent;
    }
    
    .nav-item.active {
        border-bottom-color: #5a4fcf;
        border-left: none;
    }
    
    .children-selection {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .child-checkbox {
        flex: 1;
        min-width: 200px;
    }
    
    .form-container {
        padding: 20px;
    }
    
    .form-section {
        padding: 15px;
    }
}

/* Animation for form switching */
.activity-form {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
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
/* Default (desktop) */
.responsive-top-margin {
    margin-top: 0px;
    margin-right: 20px;
}

/* Scroll wrapper for small screens */
.scroll-on-small {
    overflow-x: hidden;
}

/* Tablet screens */
@media (max-width: 1024px) {
    .responsive-top-margin {
        margin-top: 80px;
    }
}

@media (max-width: 992px) {
    .responsive-top-margin {
        margin-top: 40px;
    }
}

@media (max-width: 914px) {
    .responsive-top-margin {
        margin-top: 90px;
    }
}

@media (max-width: 768px) {
    .responsive-top-margin {
        margin-top: 90px;
    }
}

/* Mobile screens */
@media (max-width: 576px) {
    .responsive-top-margin {
        margin-top: 80px;
    }
}

/* Enable horizontal scroll below 600px */
@media (max-width: 600px) {
    .scroll-on-small {
        overflow-x: auto;
        white-space: nowrap;
    }

    .scroll-on-small > * {
        display: inline-block;
        margin-right: 10px; /* Optional spacing */
    }
}


</style>


@section('content')


        <div class="text-zero top-right-button-container d-flex justify-content-end responsive-top-margin">

            <div class="dropdown">
                <button class="btn btn-outline-primary btn-lg dropdown-toggle" type="button" id="centerDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
                    style="top:3% !important;left:13px !important;">
                    @foreach($centers as $center)
                    <a href="javascript:void(0);"
                        class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                        style="background-color:white;" data-id="{{ $center->id }}">
                        {{ $center->centerName }}
                    </a>
                    @endforeach
                </div>
            </div>


            &nbsp;&nbsp;&nbsp;&nbsp;

            <div class="dropdown">
                <button class="btn btn-outline-primary btn-lg dropdown-toggle" type="button" id="centerDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $selectedroom->name ?? 'Select Room' }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
                    style="top:3% !important;left:13px !important;">
                    @foreach($room as $rooms)
                    <a href="#"
                    class="dropdown-item room-selector {{ optional($selectedroom)->id == $rooms->id ? 'active font-weight-bold text-primary' : '' }}"
                    data-url="{{ url('DailyDiary/list') }}?room_id={{ $rooms->id }}&center_id={{ session('user_center_id') }}"
                    style="background-color:white;">
                    {{ $rooms->name }}
                    </a>
                                @endforeach
                            </div>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;


                        @if(isset($selectedroom) && isset($selectedDate))
                        <form method="GET" action="{{ route('dailyDiary.list') }}" id="dateRoomForm">
                            <input type="hidden" name="room_id" value="{{ $selectedroom->id }}">
                            <input type="hidden" name="center_id" value="{{ session('user_center_id') }}">

                            <div class="form-group">
                                <input type="date"
                                    class="form-control custom-datepicker btn-outline-primary btn-lg"
                                    id="datePicker"
                                    name="selected_date"
                                    value="{{ $selectedDate ? $selectedDate->format('Y-m-d') : '' }}"
                                    onclick="this.showPicker()"
                                    onchange="document.getElementById('dateRoomForm').submit();">
                            </div>
                        </form>
                    @endif


                    </div>



        </div>







<div class="container-fluid main-container card">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-0"><i class="fas fa-baby mr-3"></i>Daily Childcare Tracking</h1>
                    <p class="mb-0 mt-2 text-muted">Monitor and track daily activities for all children</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group">
                    @if(auth()->user()->userType !== 'Parent')
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addEntryModal">
    <i class="fas fa-plus mr-2"></i>Add Entry
</button>
@endif
                        <!-- <button class="btn btn-outline-primary"><i class="fas fa-download mr-2"></i>Export</button> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Children Cards -->
        <div class="row">

            <!-- Child 1 -->

            @foreach($children as $entry)

            @php
           
                $child = $entry['child'];
                $image = $child->imageUrl ?? 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=150&h=150&fit=crop&crop=face';
                $childId = 'child-' . $child->id;
                $fullName = $child->name . ' ' . $child->lastname;

                $dob = $child->dob;
                $age = $dob ? round(\Carbon\Carbon::parse($dob)->floatDiffInYears(now())) : null;

                $bottle = $entry['bottle'];
                $sleep = $entry['sleep'];
                $lunch = $entry['lunch'];
                $toileting = $entry['toileting'];
                $sunscreen = $entry['sunscreen'];
                $snacks = $entry['snacks'];
                $afternoon_tea = $entry['afternoon_tea'];
                $morning_tea = $entry['morning_tea'];
                $breakfast = $entry['breakfast'];
            
            @endphp

            <div class="col-lg-6 col-xl-4">
                <div class="child-card">
                    <div class="child-header">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($image) }}" alt="{{ $fullName }}" class="child-avatar">
                            <div class="child-info">
                                <h3>{{ $fullName }}</h3>
                                <p><i class="fas fa-birthday-cake mr-2"></i>Age: {{ $age }} years</p>
                                <p><i class="fas fa-clock mr-2"></i>Today: {{ $selectedDate->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="care-activities">
                        <!-- Stats Row -->
                        <div class="stats-row">
                            <div class="row">
                                <div class="col-4 stat-item">
                                    <div class="stat-number">9</div>
                                    <div class="stat-label">Activities</div>
                                </div>
                                <div class="col-4 stat-item">
                                    <div class="stat-number">3</div>
                                    <div class="stat-label">Meals</div>
                                </div>
                                <div class="col-4 stat-item">
                                    <div class="stat-number">2</div>
                                    <div class="stat-label">Naps</div>
                                </div>
                            </div>
                        </div>

                        <!-- Breakfast -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Breakfast-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-coffee activity-icon"></i>
                                    Breakfast
                                    @if($breakfast)
                                    <span class="badge badge-success badge-status ml-2">Completed</span>
                                    @else
                                    <span class="badge badge-info badge-status ml-2">No Update</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse show" id="Breakfast-{{ $childId }}">
                                <div class="activity-content">
                                @if(auth()->user()->userType !== 'Parent')
                                <button 
                                        class="btn btn-outline-primary open-diary-modal" 
                                        style="float:right;"
                                        data-child-id="{{ $child }}"
                                        data-selected-date="{{ request('selected_date') ?? '' }}"
                                    >
                                        <i class="fa-solid fa-plus"></i>
                                    </button>  
                                    @endif     
                       <div class="activity-entry activity-breakfast">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                               
                                                <span class="entry-label">Time:</span>
                                              
                                                <span class="entry-value">{{ $breakfast->startTime ?? 'No Update' }}</span>
                                          
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Item:</span>
                                                <span class="entry-value">{{ $breakfast->item ?? 'No Update' }}</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $breakfast->comments ?? 'No Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Morning Tea -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Morning-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-mug-hot activity-icon"></i>
                                    Morning Tea
                                    @if($morning_tea)
                                    <span class="badge badge-success badge-status ml-2">Completed</span>
                                    @else
                                    <span class="badge badge-info badge-status ml-2">No Update</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Morning-{{ $childId }}">
                                <div class="activity-content">
                                @if(auth()->user()->userType !== 'Parent')
                                <button 
                                    class="btn btn-outline-warning open-morningtea-modal"
                                    style="float:right;"
                                    data-child-id="{{ $child }}"
                                    data-selected-date="{{ request('selected_date') ?? '' }}"
                                >
                                    <i class="fa fa-plus"></i>
                                </button>
                                @endif
                                    <div class="activity-entry activity-morning-tea">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $morning_tea->startTime ?? 'No Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $morning_tea->comments ?? 'No Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lunch -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Lunch-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-utensils activity-icon"></i>
                                    Lunch
                                    @if($lunch)
                                    <span class="badge badge-success badge-status ml-2">Completed</span>
                                    @else
                                    <span class="badge badge-warning badge-status ml-2">In Progress</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Lunch-{{ $childId }}">
                                <div class="activity-content">
                                @if(auth()->user()->userType !== 'Parent')
                                <button
                                    class="btn btn-outline-success open-lunch-modal"
                                    style="float:right;"
                                    data-child-id="{{ $child }}"
                                    data-selected-date="{{ request('selected_date') ?? '' }}"
                                >
                                    <i class="fa fa-plus"></i>
                                </button>
                                @endif
                                    <div class="activity-entry activity-lunch">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $lunch->startTime ?? 'No Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Item:</span>
                                                <span class="entry-value">{{ $lunch->item ?? 'No Update' }}</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $lunch->comments ?? 'No Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sleep -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Sleep-{{ $childId }}">
                            <h5>
                                <i class="fas fa-bed activity-icon"></i>
                                Sleep

                                @if($sleep->count())
                                    <span class="badge badge-info badge-status ml-2">{{ $sleep->count() }} Entries</span>
                                @else
                                    <span class="badge badge-danger badge-status ml-2">0 Entries</span>
                                @endif

                                <i class="fas fa-chevron-down collapse-icon"></i>
                            </h5>
                            </div>
                            <div class="collapse" id="Sleep-{{ $childId }}">
                                <div class="activity-content">
                                <div class="text-right">
                                @if(auth()->user()->userType !== 'Parent')
                                <button
                                    class="btn btn-outline-primary mb-2 open-sleep-modal"
                                    data-child-id="{{ $child }}"
                                    data-selected-date="{{ request('selected_date') ?? '' }}"
                                    data-mode="add"
                                >
                                    <i class="fa fa-plus"></i> Add Sleep
                                </button>
                                @endif
                                </div>
                                    @forelse ($sleep as $entry)
                                        <div class="activity-entry activity-sleep">
                                        @if(auth()->user()->userType !== 'Parent')
                                        <button
                                            class="btn btn-link p-0 open-sleep-modal"
                                            style="float:right;"
                                            data-child-id="{{ $child }}"
                                            data-selected-date="{{ request('selected_date') ?? '' }}"
                                            data-mode="edit"
                                            data-entry-id="{{ $entry->id }}"
                                        >
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        @endif
                                            <div class="entry-row">
                                                <div class="entry-item">
                                                    <span class="entry-label">Sleep Time:</span>
                                                    <span class="entry-value">{{ $entry->startTime ?? 'No Update' }}</span>
                                                </div>
                                                <div class="entry-item">
                                                    <span class="entry-label">Wake Time:</span>
                                                    <span class="entry-value">{{ $entry->endTime ?? 'No Update' }}</span>
                                                </div>
                                            </div>
                                            <div class="entry-row">
                                                <div class="entry-item">
                                                    <span class="entry-label">Comments:</span>
                                                    <span class="entry-value">{{ $entry->comments ?? 'No Update' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No sleep data available.</p>
                                    @endforelse
                                </div>
                            </div>

                        </div>

                        <!-- Afternoon Tea -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Afternoon-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-cookie-bite activity-icon"></i>
                                    Afternoon Tea
                                    @if($afternoon_tea)
                                    <span class="badge badge-primary badge-status ml-2">Completed</span>
                                    @else
                                    <span class="badge badge-secondary badge-status ml-2">Pending</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Afternoon-{{ $childId }}">
                                <div class="activity-content">
                                @if(auth()->user()->userType !== 'Parent')
                                <button
                                    class="btn btn-outline-info open-afternoontea-modal"
                                    style="float:right;"
                                    data-child-id="{{ $child }}"
                                    data-selected-date="{{ request('selected_date') ?? '' }}"
                                >
                                    <i class="fa fa-plus"></i>
                                </button>
                                @endif
                                    <div class="activity-entry activity-afternoon-tea">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $afternoon_tea->startTime ?? 'No Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $afternoon_tea->comments ?? 'No Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Late Snacks -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Snacks-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-apple-alt activity-icon"></i>
                                    Late Snacks
                                    @if($snacks)
                                    <span class="badge badge-primary badge-status ml-2">Completed</span>
                                    @else
                                    <span class="badge badge-secondary badge-status ml-2">Pending</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Snacks-{{ $childId }}">
                                <div class="activity-content">
                                @if(auth()->user()->userType !== 'Parent')
                                <button
                                    class="btn btn-outline-dark open-snacks-modal"
                                    style="float:right;"
                                    data-child-id="{{ $child }}"
                                    data-selected-date="{{ request('selected_date') ?? '' }}"
                                >
                                    <i class="fa fa-plus"></i>
                                </button>
                                @endif
                                    <div class="activity-entry activity-snacks">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $snacks->startTime ?? 'No Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Item:</span>
                                                <span class="entry-value">{{ $snacks->item ?? 'No Update' }}</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $snacks->comments ?? 'No Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sunscreen -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Sunscreen-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-sun activity-icon"></i>
                                    Sunscreen

                                    @if($sunscreen->count())
                                        <span class="badge badge-info badge-status ml-2">{{ $sunscreen->count() }} Applications</span>
                                    @else
                                        <span class="badge badge-danger badge-status ml-2">0 Applications</span>
                                    @endif

                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Sunscreen-{{ $childId }}">
                                <div class="activity-content">
                                    <div class="text-right">
                                    @if(auth()->user()->userType !== 'Parent')
                                <button
                                    class="btn btn-outline-warning mb-2 open-sunscreen-modal"
                                    data-child-id="{{ $child }}"
                                    data-selected-date="{{ request('selected_date') ?? '' }}"
                                    data-mode="add"
                                >
                                    <i class="fa fa-plus"></i> Add Sunscreen
                                </button>
                                @endif
                                </div>
                                    @forelse ($sunscreen as $entry)
                                        <div class="activity-entry activity-sunscreen">
                                        <div class="text-right">
                                        @if(auth()->user()->userType !== 'Parent')
                                            <button
                                                class="btn btn-link p-0 open-sunscreen-modal"
                                                data-child-id="{{ $child }}"
                                                data-selected-date="{{ request('selected_date') ?? '' }}"
                                                data-mode="edit"
                                                data-entry-id="{{ $entry->id }}"
                                            >
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            @endif
                                        </div>
                                            <div class="entry-row">
                                                <div class="entry-item">
                                                    <span class="entry-label">Time:</span>
                                                    <span class="entry-value">{{ $entry->startTime ?? 'No Update' }}</span>
                                                </div>
                                                <div class="entry-item">
                                                    <span class="entry-label">Comments:</span>
                                                    <span class="entry-value">{{ $entry->comments ?? 'No Update' }}</span>
                                                </div>
                                                <div class="entry-item">
                                                    <span class="entry-label">Signature:</span>
                                                    <span class="entry-value">{{ $entry->signature ?? 'No Update' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No sunscreen applications recorded.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Toileting -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Toileting-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-baby activity-icon"></i>
                                    Toileting

                                    @if($toileting->count())
                                        <span class="badge badge-info badge-status ml-2">{{ $toileting->count() }} Changes</span>
                                    @else
                                        <span class="badge badge-warning badge-status ml-2">No Update</span>
                                    @endif

                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Toileting-{{ $childId }}">
                                <div class="activity-content">
                                <div class="text-right">
                                @if(auth()->user()->userType !== 'Parent')
                                <button
                                    class="btn btn-outline-secondary mb-2 open-toileting-modal"
                                    data-child-id="{{ $child }}"
                                    data-selected-date="{{ request('selected_date') ?? '' }}"
                                    data-mode="add"
                                >
                                    <i class="fa fa-plus"></i> Add Toileting
                                </button>
                                @endif
                                </div>
                                    @forelse ($toileting as $entry)
                                        <div class="activity-entry activity-toileting">
                                        <div class="text-right">
                                        @if(auth()->user()->userType !== 'Parent')
                                            <button
                                                class="btn btn-link p-0 open-toileting-modal"
                                                data-child-id="{{ $child }}"
                                                data-selected-date="{{ request('selected_date') ?? '' }}"
                                                data-mode="edit"
                                                data-entry-id="{{ $entry->id }}"
                                            >
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            @endif
                                        </div>
                                            <div class="entry-row">
                                                <div class="entry-item">
                                                    <span class="entry-label">Time:</span>
                                                    <span class="entry-value">{{ $entry->startTime ?? 'No Update' }}</span>
                                                </div>
                                                <div class="entry-item">
                                                    <span class="entry-label">Status:</span>
                                                    <span class="badge badge-warning">{{ $entry->status ?? 'No Update' }}</span>
                                                </div>
                                            </div>
                                            <div class="entry-row">
                                                <div class="entry-item">
                                                    <span class="entry-label">Signature:</span>
                                                    <span class="entry-value">{{ $entry->signature ?? 'No Update' }}</span>
                                                </div>
                                            </div>
                                            <div class="entry-row">
                                                <div class="entry-item">
                                                    <span class="entry-label">Comments:</span>
                                                    <span class="entry-value">{{ $entry->comments ?? 'No Update' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No toileting changes recorded.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>




                         <!-- Bottel -->
                         <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Bottel-{{ $childId }}">
                                <h5>
                                    <i class="fa-solid fa-bottle-water activity-icon"></i>
                                    Bottle

                                    @if($bottle->count())
                                        <span class="badge badge-primary badge-status ml-2">{{ $bottle->count() }} Feedings</span>
                                    @else
                                        <span class="badge badge-secondary badge-status ml-2">Pending</span>
                                    @endif

                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Bottel-{{ $childId }}">
                                <div class="activity-content">
                                    <div class="text-right">
                                    @if(auth()->user()->userType !== 'Parent')
                                    <button
                                        class="btn btn-outline-info mb-2 open-bottle-modal"
                                        data-child-id="{{ $child }}"
                                        data-selected-date="{{ request('selected_date') ?? '' }}"
                                        data-mode="add"
                                    >
                                        <i class="fa fa-plus"></i> Add Bottle
                                    </button>
                                    @endif
                                    </div>
                                    @forelse ($bottle as $entry)
                                        <div class="activity-entry activity-bottle">
                                        <div class="text-right">
                                        @if(auth()->user()->userType !== 'Parent')
                                            <button
                                                class="btn btn-link p-0 open-bottle-modal"
                                                data-child-id="{{ $child }}"
                                                data-selected-date="{{ request('selected_date') ?? '' }}"
                                                data-mode="edit"
                                                data-entry-id="{{ $entry->id }}"
                                            >
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            @endif
                                        </div>
                                            <div class="entry-row">
                                                <div class="entry-item">
                                                    <span class="entry-label">Time:</span>
                                                    <span class="entry-value">{{ $entry->startTime ?? 'No Update' }}</span>
                                                </div>
                                                <div class="entry-item">
                                                    <span class="entry-label">Comments:</span>
                                                    <span class="entry-value">{{ $entry->comments ?? 'No Update' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No bottle feeding recorded.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>





                    </div>
                </div>
            </div>

            @endforeach

          

</div>




<div id="toast-container" class="toast-bottom-right"
        style="position: fixed; right: 20px; bottom: 20px; z-index: 9999;"></div>




<style>
.modal-fullwidth {
    max-width: 80%;
    margin: 1rem auto;
}
</style>

<!-- Add Entry Modal -->
<div class="modal" id="addEntryModal" tabindex="-1" role="dialog" aria-labelledby="addEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullwidth" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addEntryModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Add New Activity Entry
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters h-100">
                    <!-- Sidebar -->
                    <div class="col-md-3 modal-sidebar">
                        <div class="sidebar-header">
                            <h6><i class="fas fa-list mr-2"></i>Select Activity</h6>
                        </div>
                        <div class="sidebar-nav">
                            <a href="#" class="nav-item active" data-activity="breakfast">
                                <i class="fas fa-coffee"></i>
                                <span>Breakfast</span>
                            </a>
                            <a href="#" class="nav-item" data-activity="morning-tea">
                                <i class="fas fa-mug-hot"></i>
                                <span>Morning Tea</span>
                            </a>
                            <a href="#" class="nav-item" data-activity="lunch">
                                <i class="fas fa-utensils"></i>
                                <span>Lunch</span>
                            </a>
                            <a href="#" class="nav-item" data-activity="sleep">
                                <i class="fas fa-bed"></i>
                                <span>Sleep</span>
                            </a>
                            <a href="#" class="nav-item" data-activity="afternoon-tea">
                                <i class="fas fa-cookie-bite"></i>
                                <span>Afternoon Tea</span>
                            </a>
                            <a href="#" class="nav-item" data-activity="snacks">
                                <i class="fas fa-apple-alt"></i>
                                <span>Late Snacks</span>
                            </a>
                            <a href="#" class="nav-item" data-activity="sunscreen">
                                <i class="fas fa-sun"></i>
                                <span>Sunscreen</span>
                            </a>
                            <a href="#" class="nav-item" data-activity="toileting">
                                <i class="fas fa-baby"></i>
                                <span>Toileting</span>
                            </a>
                            <a href="#" class="nav-item" data-activity="bottle">
                                <i class="fa-solid fa-bottle-water"></i>
                                <span>Bottle</span>
                            </a>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="col-md-9 modal-main-content">
                        <div class="content-header">
                            <h5 id="activity-title">
                                <i class="fas fa-coffee mr-2"></i>Add Breakfast Entry
                            </h5>
                        </div>

                        <div class="form-container">
                            <form id="activityForm">
                                <!-- Common Fields -->
                                <div class="form-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-calendar-alt mr-2"></i>General Information
                                    </h6>
                                    
                                    <!-- Date Picker -->
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-calendar mr-2"></i>Date
                                        </label>
                                        <div class="date-picker-wrapper">
                                            <input type="date" class="form-control date-picker" id="activityDate" required>
                                        </div>
                                    </div>

                                    <!-- Children Selection -->
                                    <div class="form-group" style="overflow-y:auto;max-height:300px;">
                                        <label class="form-label">
                                            <i class="fas fa-users mr-2"></i>Select Children
                                        </label>
                                        <div class="mb-3">
                                            <input type="text" id="childSearchInput" class="form-control" placeholder="Search children...">
                                        </div>

                                        <div class="mb-2">
                                            <label>
                                                <input type="checkbox" id="selectAllChildren" class="mr-2"> Select All
                                            </label>
                                        </div>

                                        <div class="children-selection" id="childrenList">
                                            @foreach($children as $entry)
                                                @php
                                                $child = $entry['child'];
                                                $image = $child->imageUrl ?? 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=40&h=40&fit=crop&crop=face';
                                                $childId = 'child-' . $child->id;
                                                $fullName = $child->name . ' ' . $child->lastname;
                                                @endphp

                                                <div class="child-checkbox" data-name="{{ strtolower($fullName) }}">
                                                    <input type="checkbox" id="{{ $childId }}" value="{{ $child->id }}" class="custom-checkbox child-checkbox-input">
                                                    <label for="{{ $childId }}" class="child-label">
                                                        <img src="{{ asset($image) }}" alt="{{ $fullName }}" class="child-thumb">
                                                        <span>{{ $fullName }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Specific Fields -->
                                <div class="form-section activity-fields">
                                    <h6 class="section-title">
                                        <i class="fas fa-edit mr-2"></i>Activity Details
                                    </h6>

                                    <!-- Breakfast Fields -->
                                    <div id="breakfast-fields" class="activity-form active">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-clock mr-2"></i>Breakfast Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="breakfast-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-utensils mr-2"></i>Breakfast Item
                                                    </label>
                                                    <input type="text" class="form-control" id="breakfast-item" placeholder="e.g., Cereal with milk, banana" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="breakfast-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="breakfast">
                                                <i class="fas fa-save mr-2"></i>Save Breakfast Entry
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Morning Tea Fields -->
                                    <div id="morning-tea-fields" class="activity-form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-clock mr-2"></i>Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="morning-tea-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="morning-tea-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="morning-tea">
                                                <i class="fas fa-save mr-2"></i>Save Morning Tea Entry
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Lunch Fields -->
                                    <div id="lunch-fields" class="activity-form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-clock mr-2"></i>Lunch Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="lunch-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-utensils mr-2"></i>Lunch Item
                                                    </label>
                                                    <input type="text" class="form-control" id="lunch-item" placeholder="e.g., Sandwich, apple slices, yogurt" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="lunch-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="lunch">
                                                <i class="fas fa-save mr-2"></i>Save Lunch Entry
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Sleep Fields -->
                                    <div id="sleep-fields" class="activity-form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-moon mr-2"></i>Sleep Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="sleep-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-sun mr-2"></i>Wake Up Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="wake-time">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="sleep-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="sleep">
                                                <i class="fas fa-save mr-2"></i>Save Sleep Entry
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Afternoon Tea Fields -->
                                    <div id="afternoon-tea-fields" class="activity-form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-clock mr-2"></i>Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="afternoon-tea-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="afternoon-tea-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="afternoon-tea">
                                                <i class="fas fa-save mr-2"></i>Save Afternoon Tea Entry
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Late Snacks Fields -->
                                    <div id="snacks-fields" class="activity-form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-clock mr-2"></i>Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="snacks-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-apple-alt mr-2"></i>Snack Item
                                                    </label>
                                                    <input type="text" class="form-control" id="snacks-item" placeholder="e.g., Fruit pieces, crackers" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="snacks-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="snacks">
                                                <i class="fas fa-save mr-2"></i>Save Late Snacks Entry
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Sunscreen Fields -->
                                    <div id="sunscreen-fields" class="activity-form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-clock mr-2"></i>Application Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="sunscreen-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Signature
                                            </label>
                                            <input type="text" class="form-control" id="sunscreen-signature" rows="3" >
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="sunscreen-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="sunscreen">
                                                <i class="fas fa-save mr-2"></i>Save Sunscreen Entry
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Toileting Fields -->
                                    <div id="toileting-fields" class="activity-form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-clock mr-2"></i>Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="toileting-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-baby mr-2"></i>Nappy Status
                                                    </label>
                                                    <select class="form-control custom-select" id="nappy-status" required>
                                                        <option value="">Select Status</option>
                                                        <option value="clean">Clean</option>
                                                        <option value="wet">Wet</option>
                                                        <option value="soiled">Soiled</option>
                                                        <option value="successful">Successful (Toilet)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Signature
                                            </label>
                                            <input type="text" class="form-control" id="toileting-signature" rows="3" >
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="toileting-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="toileting">
                                                <i class="fas fa-save mr-2"></i>Save Toileting Entry
                                            </button>
                                        </div>
                                    </div>


                                    <div id="bottle-fields" class="activity-form">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="fas fa-clock mr-2"></i>Bottle Time
                                                    </label>
                                                    <div class="time-picker-wrapper">
                                                        <input type="time" class="form-control time-picker" id="bottle-time" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-comment mr-2"></i>Comments
                                            </label>
                                            <textarea class="form-control" id="bottle-comments" rows="3" placeholder="Any additional notes..."></textarea>
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-primary submit-activity" data-activity="bottle">
                                                <i class="fas fa-save mr-2"></i>Save Bottle Entry
                                            </button>
                                        </div>
                                    </div>





                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal" id="breakfastModal" tabindex="-1" role="dialog" aria-labelledby="breakfastModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="breakfastForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="breakfastModalLabel">Add/Edit Breakfast</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="child_id" id="modal_child_id">
          <input type="hidden" name="selected_date" id="modal_selected_date">

          <div class="form-group">
            <label>Time</label>
            <input type="time" name="startTime" class="form-control" id="modal_start_time">
          </div>
          <div class="form-group">
            <label>Item</label>
            <input type="text" name="item" class="form-control" id="modal_item">
          </div>
          <div class="form-group">
            <label>Comments</label>
            <textarea name="comments" class="form-control" id="modal_comments"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="saveBreakfastBtn">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>







<div class="modal" id="morningTeaModal" tabindex="-1" role="dialog" aria-labelledby="morningTeaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="morningTeaForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="morningTeaModalLabel">Add/Edit Morning Tea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="mt_modal_child_id">
                    <input type="hidden" name="selected_date" id="mt_modal_selected_date">
                    
                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="startTime" class="form-control" id="mt_modal_start_time">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="comments" class="form-control" id="mt_modal_comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>




<div class="modal" id="lunchModal" tabindex="-1" role="dialog" aria-labelledby="lunchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="lunchForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lunchModalLabel">Add/Edit Lunch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="lunch_modal_child_id">
                    <input type="hidden" name="selected_date" id="lunch_modal_selected_date">

                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="startTime" class="form-control" id="lunch_modal_start_time">
                    </div>
                    <div class="form-group">
                        <label>Item</label>
                        <input type="text" name="item" class="form-control" id="lunch_modal_item">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="comments" class="form-control" id="lunch_modal_comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class="modal" id="afternoonTeaModal" tabindex="-1" role="dialog" aria-labelledby="afternoonTeaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="afternoonTeaForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="afternoonTeaModalLabel">Add/Edit Afternoon Tea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="at_modal_child_id">
                    <input type="hidden" name="selected_date" id="at_modal_selected_date">

                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="startTime" class="form-control" id="at_modal_start_time">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="comments" class="form-control" id="at_modal_comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class="modal" id="snacksModal" tabindex="-1" role="dialog" aria-labelledby="snacksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="snacksForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="snacksModalLabel">Add/Edit Late Snack</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="snacks_modal_child_id">
                    <input type="hidden" name="selected_date" id="snacks_modal_selected_date">

                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="startTime" class="form-control" id="snacks_modal_start_time">
                    </div>
                    <div class="form-group">
                        <label>Item</label>
                        <input type="text" name="item" class="form-control" id="snacks_modal_item">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="comments" class="form-control" id="snacks_modal_comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>




<div class="modal" id="sleepModal" tabindex="-1" role="dialog" aria-labelledby="sleepModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="sleepForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sleepModalLabel">Add/Edit Sleep</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="sleep_modal_child_id">
                    <input type="hidden" name="selected_date" id="sleep_modal_selected_date">
                    <input type="hidden" name="sleep_entry_id" id="sleep_modal_entry_id"> <!-- this is only used in edit mode -->

                    <div class="form-group">
                        <label>Sleep Time</label>
                        <input type="time" name="startTime" class="form-control" id="sleep_modal_start_time">
                    </div>
                    <div class="form-group">
                        <label>Wake Time</label>
                        <input type="time" name="endTime" class="form-control" id="sleep_modal_end_time">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="comments" class="form-control" id="sleep_modal_comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class="modal" id="sunscreenModal" tabindex="-1" role="dialog" aria-labelledby="sunscreenModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="sunscreenForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sunscreenModalLabel">Add/Edit Sunscreen Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="sunscreen_modal_child_id">
                    <input type="hidden" name="selected_date" id="sunscreen_modal_selected_date">
                    <input type="hidden" name="sunscreen_entry_id" id="sunscreen_modal_entry_id">

                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="startTime" class="form-control" id="sunscreen_modal_start_time">
                    </div>
                    <div class="form-group">
                        <label>Signature</label>
                        <input type="text" name="signature" class="form-control" id="sunscreen_modal_signature">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="comments" class="form-control" id="sunscreen_modal_comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal" id="toiletingModal" tabindex="-1" role="dialog" aria-labelledby="toiletingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="toiletingForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toiletingModalLabel">Add/Edit Toileting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="toileting_modal_child_id">
                    <input type="hidden" name="selected_date" id="toileting_modal_selected_date">
                    <input type="hidden" name="toileting_entry_id" id="toileting_modal_entry_id">

                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="startTime" class="form-control" id="toileting_modal_start_time">
                    </div>
                    <div class="form-group">
                        <label>Nappy Status</label>
                        <select name="status" class="form-control" id="toileting_modal_status">
                            <option value="">Select Status</option>
                            <option value="clean">Clean</option>
                                                        <option value="wet">Wet</option>
                                                        <option value="soiled">Soiled</option>
                                                        <option value="successful">Successful (Toilet)</option>
                            <!-- Add or change status options as needed -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Signature</label>
                        <input type="text" name="signature" class="form-control" id="toileting_modal_signature">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="comments" class="form-control" id="toileting_modal_comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">Save</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class="modal" id="bottleModal" tabindex="-1" role="dialog" aria-labelledby="bottleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="bottleForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bottleModalLabel">Add/Edit Bottle Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="child_id" id="bottle_modal_child_id">
                    <input type="hidden" name="selected_date" id="bottle_modal_selected_date">
                    <input type="hidden" name="bottle_entry_id" id="bottle_modal_entry_id">

                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="startTime" class="form-control" id="bottle_modal_start_time">
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea name="comments" class="form-control" id="bottle_modal_comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Save</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>





<script>
$(document).ready(function() {
    // Handle activity switching in the sidebar
    $('.sidebar-nav a').click(function(e) {

        const href = $(this).attr('href');
      if (!href || href === '#') {
        e.preventDefault();
        $('.sidebar-nav a').removeClass('active');
        $(this).addClass('active');
        
        const activity = $(this).data('activity');
        $('.activity-form').removeClass('active');
        $(`#${activity}-fields`).addClass('active');
        
        // Update the activity title
        const activityName = $(this).find('span').text();
        $('#activity-title').html(`<i class="${$(this).find('i').attr('class')} mr-2"></i>Add ${activityName} Entry`);
      }
    });

    // Handle child search
    $('#childSearchInput').on('keyup', function() {
        const searchText = $(this).val().toLowerCase();
        $('.child-checkbox').each(function() {
            const childName = $(this).data('name');
            if (childName.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Handle select all children
    $('#selectAllChildren').change(function() {
        $('.child-checkbox-input').prop('checked', $(this).prop('checked'));
    });

    // Handle activity submission
    $('.submit-activity').click(function() {
    const $button = $(this);
    const originalText = $button.html();
    $button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...').prop('disabled', true);

    const activityType = $(this).data('activity');
    const date = $('#activityDate').val();
    const selectedChildren = $('.child-checkbox-input:checked').map(function() {
        return $(this).val();
    }).get();

    if (!date) {
        showToast('error', 'Please select a date');
        $button.html(originalText).prop('disabled', false);
        return;
    }

    if (selectedChildren.length === 0) {
        showToast('error', 'Please select at least one child');
        $button.html(originalText).prop('disabled', false);
        return;
    }

    // Prepare data object based on activity type
    let activityData = {
        date: date,
        child_ids: selectedChildren
    };

    // Add activity-specific fields
    switch (activityType) {
        case 'breakfast':
            activityData.time = $('#breakfast-time').val();
            activityData.item = $('#breakfast-item').val();
            activityData.comments = $('#breakfast-comments').val();
            break;
        case 'morning-tea':
            activityData.time = $('#morning-tea-time').val();
            activityData.comments = $('#morning-tea-comments').val();
            break;
        case 'lunch':
            activityData.time = $('#lunch-time').val();
            activityData.item = $('#lunch-item').val();
            activityData.comments = $('#lunch-comments').val();
            break;
        case 'sleep':
            activityData.sleep_time = $('#sleep-time').val();
            activityData.wake_time = $('#wake-time').val();
            activityData.comments = $('#sleep-comments').val();
            break;
        case 'afternoon-tea':
            activityData.time = $('#afternoon-tea-time').val();
            activityData.comments = $('#afternoon-tea-comments').val();
            break;
        case 'snacks':
            activityData.time = $('#snacks-time').val();
            activityData.item = $('#snacks-item').val();
            activityData.comments = $('#snacks-comments').val();
            break;
        case 'sunscreen':
            activityData.time = $('#sunscreen-time').val();
            activityData.signature = $('#sunscreen-signature').val();
            activityData.comments = $('#sunscreen-comments').val();
            break;
        case 'toileting':
            activityData.time = $('#toileting-time').val();
            activityData.status = $('#nappy-status').val();
            activityData.signature = $('#toileting-signature').val();
            activityData.comments = $('#toileting-comments').val();
            break;
        case 'bottle':
            activityData.time = $('#bottle-time').val();
            activityData.comments = $('#bottle-comments').val();
            break;
    }

    // Send data to server via AJAX
    $.ajax({
        url: `/activities/${activityType}`,
        type: 'POST',
        data: activityData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            if (res.status === 'success') {
                showToast('success', res.message);
                $button.html('<i class="fas fa-check mr-2"></i>Saved!');
                setTimeout(() => {
                    // Optionally refresh the page or close modal
                    $('#addEntryModal').modal('hide');
                    // Or refresh a specific part of the page if needed
                    window.location.reload();
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
            } else {
                showToast('error', xhr.responseJSON?.message || 'Server error occurred');
            }
        }
    });
});

    // Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    $('#activityDate').val(today);


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



});
</script>

<!-- <script>
    $(document).ready(function() {
    // Set current date as default
    var today = new Date().toISOString().split('T')[0];
    $('#activityDate').val(today);
    
    // Activity navigation
    $('.nav-item').click(function(e) {
        e.preventDefault();
        
        // Update active state
        $('.nav-item').removeClass('active');
        $(this).addClass('active');
        
        // Get activity type
        var activity = $(this).data('activity');
        
        // Hide all activity forms
        $('.activity-form').removeClass('active');
        
        // Show selected activity form
        $('#' + activity + '-fields').addClass('active');
        
        // Update title and icon
        var icon = $(this).find('i').attr('class');
        var title = $(this).find('span').text();
        $('#activity-title').html('<i class="' + icon + ' mr-2"></i>Add ' + title + ' Entry');
        
        // Show/hide multiple entry option for specific activities
        if (activity === 'sleep' || activity === 'sunscreen' || activity === 'toileting') {
            $('.multiple-entry-section').show();
        } else {
            $('.multiple-entry-section').hide();
        }
    });
});
    </script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('childSearchInput');
    const selectAll = document.getElementById('selectAllChildren');
    const checkboxes = document.querySelectorAll('.child-checkbox-input');
    const childBoxes = document.querySelectorAll('.child-checkbox');

    // Live Search
    searchInput.addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        childBoxes.forEach(function (childBox) {
            const name = childBox.getAttribute('data-name');
            childBox.style.display = name.includes(query) ? 'block' : 'none';
        });
    });

    // Select All
    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
});
</script> -->



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

function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    // fallback to today 'YYYY-MM-DD' if not present
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}


$(document).on('click', '.open-diary-modal', function() {
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    if (!selected_date) selected_date = getSelectedDate();

    // console.log(childId.id);

    // Reset form
    $('#breakfastForm')[0].reset();
    $('#modal_child_id').val(childId.id);
    $('#modal_selected_date').val(selected_date);

    // fetch data by AJAX
    $.ajax({
        url: '/daily-diary/breakfast2',  // Make appropriate API route
        method: 'GET',
        data: {
            child_id: childId,
            selected_date: selected_date,
        },
        success: function(res) {
            if (res.data) {
                // Fill modal with response data
                $('#modal_start_time').val(res.data.startTime || '');
                $('#modal_item').val(res.data.item || '');
                $('#modal_comments').val(res.data.comments || '');
            } else {
                // reset fields for add
                $('#modal_start_time').val('');
                $('#modal_item').val('');
                $('#modal_comments').val('');
            }
            $('#breakfastModal').modal('show');
        },
        error: function() {
            showToast('error', 'Error fetching data');
        }
    });
});

$('#breakfastForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#modal_child_id').val();
    const diaryDate = $('#modal_selected_date').val();

    $.ajax({
        url: '/daily-diary/breakfast2',
        method: 'POST',
        data: {
            child_id: childId,
            selected_date: diaryDate,
            startTime: $('#modal_start_time').val(),
            item: $('#modal_item').val(),
            comments: $('#modal_comments').val(),
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            if(res.success){
                $('#breakfastModal').modal('hide');
                showToast('success', res.message);
                window.location.reload(); // or update UI via JS
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
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

function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}


$(document).on('click', '.open-morningtea-modal', function() {
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    if (!selected_date) selected_date = getSelectedDate();

    // Reset form fields
    $('#morningTeaForm')[0].reset();
    $('#mt_modal_child_id').val(childId.id);
    $('#mt_modal_selected_date').val(selected_date);

    // Get the data (by child and date)
    $.ajax({
        url: '/daily-diary/morning-tea2',
        method: 'GET',
        data: {
            child_id: childId,
            selected_date: selected_date,
        },
        success: function(res) {
            if (res.data) {
                $('#mt_modal_start_time').val(res.data.startTime || '');
                $('#mt_modal_comments').val(res.data.comments || '');
            } else {
                $('#mt_modal_start_time').val('');
                $('#mt_modal_comments').val('');
            }
            $('#morningTeaModal').modal('show');
        },
        error: function() {
            showToast('error', 'Error fetching morning tea data');
        }
    });
});


$('#morningTeaForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#mt_modal_child_id').val();
    const diaryDate = $('#mt_modal_selected_date').val();

    $.ajax({
        url: '/daily-diary/morning-tea2',
        method: 'POST',
        data: {
            child_id: childId,
            selected_date: diaryDate,
            startTime: $('#mt_modal_start_time').val(),
            comments: $('#mt_modal_comments').val(),
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            if(res.success){
                $('#morningTeaModal').modal('hide');
                showToast('success', res.message);
                window.location.reload(); // Or refresh only the required section
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
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

    function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}

$(document).on('click', '.open-lunch-modal', function() {
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    if (!selected_date) selected_date = getSelectedDate();

    // Reset form fields
    $('#lunchForm')[0].reset();
    $('#lunch_modal_child_id').val(childId.id);
    $('#lunch_modal_selected_date').val(selected_date);

    // Fetch data from API
    $.ajax({
        url: '/daily-diary/lunch',
        method: 'GET',
        data: {
            child_id: childId,
            selected_date: selected_date,
        },
        success: function(res) {
            if (res.data) {
                $('#lunch_modal_start_time').val(res.data.startTime || '');
                $('#lunch_modal_item').val(res.data.item || '');
                $('#lunch_modal_comments').val(res.data.comments || '');
            } else {
                $('#lunch_modal_start_time').val('');
                $('#lunch_modal_item').val('');
                $('#lunch_modal_comments').val('');
            }
            $('#lunchModal').modal('show');
        },
        error: function() {
            showToast('error', 'Error fetching lunch data');
        }
    });
});


$('#lunchForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#lunch_modal_child_id').val();
    const diaryDate = $('#lunch_modal_selected_date').val();

    $.ajax({
        url: '/daily-diary/lunch',
        method: 'POST',
        data: {
            child_id: childId,
            selected_date: diaryDate,
            startTime: $('#lunch_modal_start_time').val(),
            item: $('#lunch_modal_item').val(),
            comments: $('#lunch_modal_comments').val(),
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            if(res.success){
                $('#lunchModal').modal('hide');
                showToast('success', res.message);
                window.location.reload();
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
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


       function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}


$(document).on('click', '.open-afternoontea-modal', function() {
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    if (!selected_date) selected_date = getSelectedDate();

    // Clear modal form
    $('#afternoonTeaForm')[0].reset();
    $('#at_modal_child_id').val(childId.id);
    $('#at_modal_selected_date').val(selected_date);

    // Fetch existing data, if any
    $.ajax({
        url: '/daily-diary/afternoon-tea',
        method: 'GET',
        data: {
            child_id: childId,
            selected_date: selected_date,
        },
        success: function(res) {
            if (res.data) {
                $('#at_modal_start_time').val(res.data.startTime || '');
                $('#at_modal_comments').val(res.data.comments || '');
            } else {
                $('#at_modal_start_time').val('');
                $('#at_modal_comments').val('');
            }
            $('#afternoonTeaModal').modal('show');
        },
        error: function() {
            showToast('error', 'Error fetching afternoon tea data');
        }
    });
});


$('#afternoonTeaForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#at_modal_child_id').val();
    const diaryDate = $('#at_modal_selected_date').val();

    $.ajax({
        url: '/daily-diary/afternoon-tea',
        method: 'POST',
        data: {
            child_id: childId,
            selected_date: diaryDate,
            startTime: $('#at_modal_start_time').val(),
            comments: $('#at_modal_comments').val(),
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            if(res.success){
                $('#afternoonTeaModal').modal('hide');
                showToast('success', res.message);
                window.location.reload();
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
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


       function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}


$(document).on('click', '.open-snacks-modal', function() {
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    if (!selected_date) selected_date = getSelectedDate();

    // Reset form fields
    $('#snacksForm')[0].reset();
    $('#snacks_modal_child_id').val(childId.id);
    $('#snacks_modal_selected_date').val(selected_date);

    // Fetch snack data via AJAX
    $.ajax({
        url: '/daily-diary/snacks',
        method: 'GET',
        data: {
            child_id: childId,
            selected_date: selected_date,
        },
        success: function(res) {
            if (res.data) {
                $('#snacks_modal_start_time').val(res.data.startTime || '');
                $('#snacks_modal_item').val(res.data.item || '');
                $('#snacks_modal_comments').val(res.data.comments || '');
            } else {
                $('#snacks_modal_start_time').val('');
                $('#snacks_modal_item').val('');
                $('#snacks_modal_comments').val('');
            }
            $('#snacksModal').modal('show');
        },
        error: function() {
            showToast('error', 'Error fetching snack data');
        }
    });
});


$('#snacksForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#snacks_modal_child_id').val();
    const diaryDate = $('#snacks_modal_selected_date').val();

    $.ajax({
        url: '/daily-diary/snacks',
        method: 'POST',
        data: {
            child_id: childId,
            selected_date: diaryDate,
            startTime: $('#snacks_modal_start_time').val(),
            item: $('#snacks_modal_item').val(),
            comments: $('#snacks_modal_comments').val(),
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            if(res.success){
                $('#snacksModal').modal('hide');
                showToast('success', res.message);
                window.location.reload();
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
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


       function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}


$(document).on('click', '.open-sleep-modal', function() {
    const mode = $(this).data('mode'); // 'add' or 'edit'
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    const entryId = $(this).data('entry-id') || '';

    if (!selected_date) selected_date = getSelectedDate();

    // Reset modal
    $('#sleepForm')[0].reset();
    $('#sleep_modal_child_id').val(childId.id);
    $('#sleep_modal_selected_date').val(selected_date);
    $('#sleep_modal_entry_id').val('');

    if (mode === 'edit' && entryId) {
        // Fetch the specific entry for editing
        $.ajax({
            url: '/daily-diary/sleep/' + entryId,
            method: 'GET',
            success: function(res) {
                if (res.data) {
                    $('#sleep_modal_start_time').val(res.data.startTime || '');
                    $('#sleep_modal_end_time').val(res.data.endTime || '');
                    $('#sleep_modal_comments').val(res.data.comments || '');
                    $('#sleep_modal_entry_id').val(res.data.id);
                }
                $('#sleepModal').modal('show');
            },
            error: function() {
                showToast('error', 'Error fetching sleep entry');
            }
        });
    } else {
        // Add mode: just open with blank fields
        $('#sleep_modal_entry_id').val('');
        $('#sleepModal').modal('show');
    }
});


$('#sleepForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#sleep_modal_child_id').val();
    const diaryDate = $('#sleep_modal_selected_date').val();
    const entryId = $('#sleep_modal_entry_id').val();
    const startTime = $('#sleep_modal_start_time').val();
    const endTime = $('#sleep_modal_end_time').val();
    const comments = $('#sleep_modal_comments').val();

    let url, method, data;
    if (entryId) {
        // Edit
        url = '/daily-diary/sleep/' + entryId;
        method = 'PUT';
        data = {
            child_id: childId,
            selected_date: diaryDate,
            startTime,
            endTime,
            comments,
            _token: '{{ csrf_token() }}'
        };
    } else {
        // Add
        url = '/daily-diary/sleep';
        method = 'POST';
        data = {
            child_id: childId,
            selected_date: diaryDate,
            startTime,
            endTime,
            comments,
            _token: '{{ csrf_token() }}'
        };
    }

    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(res) {
            if(res.success){
                $('#sleepModal').modal('hide');
                showToast('success', res.message);
                window.location.reload();
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
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


       function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}


$(document).on('click', '.open-sunscreen-modal', function() {
    const mode = $(this).data('mode'); // 'add' or 'edit'
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    const entryId = $(this).data('entry-id') || '';

    if (!selected_date) selected_date = getSelectedDate();

    // Reset form
    $('#sunscreenForm')[0].reset();
    $('#sunscreen_modal_child_id').val(childId.id);
    $('#sunscreen_modal_selected_date').val(selected_date);
    $('#sunscreen_modal_entry_id').val('');

    if (mode === 'edit' && entryId) {
        // Edit Mode: Fetch entry and fill modal
        $.ajax({
            url: '/daily-diary/sunscreen/' + entryId,
            method: 'GET',
            success: function(res) {
                if (res.data) {
                    $('#sunscreen_modal_start_time').val(res.data.startTime || '');
                    $('#sunscreen_modal_comments').val(res.data.comments || '');
                    $('#sunscreen_modal_signature').val(res.data.signature || '');
                    $('#sunscreen_modal_entry_id').val(res.data.id);
                }
                $('#sunscreenModal').modal('show');
            },
            error: function() {
                showToast('error', 'Error fetching sunscreen entry');
            }
        });
    } else {
        // Add Mode: Show empty modal
        $('#sunscreen_modal_entry_id').val('');
        $('#sunscreenModal').modal('show');
    }
});


$('#sunscreenForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#sunscreen_modal_child_id').val();
    const diaryDate = $('#sunscreen_modal_selected_date').val();
    const entryId = $('#sunscreen_modal_entry_id').val();
    const startTime = $('#sunscreen_modal_start_time').val();
    const comments = $('#sunscreen_modal_comments').val();
    const signature = $('#sunscreen_modal_signature').val();

    let url, method, data;
    if (entryId) {
        // Edit
        url = '/daily-diary/sunscreen/' + entryId;
        method = 'PUT';
        data = {
            child_id: childId,
            selected_date: diaryDate,
            startTime,
            comments,
            signature,
            _token: '{{ csrf_token() }}'
        };
    } else {
        // Add
        url = '/daily-diary/sunscreen';
        method = 'POST';
        data = {
            child_id: childId,
            selected_date: diaryDate,
            startTime,
            comments,
            signature,
            _token: '{{ csrf_token() }}'
        };
    }

    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(res) {
            if(res.success){
                $('#sunscreenModal').modal('hide');
                showToast('success', res.message);
                window.location.reload();
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
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


       function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}


$(document).on('click', '.open-toileting-modal', function() {
    const mode = $(this).data('mode'); // 'add' or 'edit'
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    const entryId = $(this).data('entry-id') || '';

    if (!selected_date) selected_date = getSelectedDate();

    // Reset form fields
    $('#toiletingForm')[0].reset();
    $('#toileting_modal_child_id').val(childId.id);
    $('#toileting_modal_selected_date').val(selected_date);
    $('#toileting_modal_entry_id').val('');

    if (mode === 'edit' && entryId) {
        // Fetch entry and fill modal for edit
        $.ajax({
            url: '/daily-diary/toileting/' + entryId,
            method: 'GET',
            success: function(res) {
                if (res.data) {
                    $('#toileting_modal_start_time').val(res.data.startTime || '');
                    $('#toileting_modal_status').val(res.data.status || '');
                    $('#toileting_modal_comments').val(res.data.comments || '');
                    $('#toileting_modal_signature').val(res.data.signature || '');
                    $('#toileting_modal_entry_id').val(res.data.id);
                }
                $('#toiletingModal').modal('show');
            },
            error: function() {
                showToast('error', 'Error fetching toileting entry');
            }
        });
    } else {
        // Add Mode: show empty
        $('#toileting_modal_entry_id').val('');
        $('#toiletingModal').modal('show');
    }
});


$('#toiletingForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#toileting_modal_child_id').val();
    const diaryDate = $('#toileting_modal_selected_date').val();
    const entryId = $('#toileting_modal_entry_id').val();
    const startTime = $('#toileting_modal_start_time').val();
    const status = $('#toileting_modal_status').val();
    const comments = $('#toileting_modal_comments').val();
    const signature = $('#toileting_modal_signature').val();

    let url, method, data;
    if (entryId) {
        url = '/daily-diary/toileting/' + entryId;
        method = 'PUT';
        data = {
            child_id: childId,
            selected_date: diaryDate,
            startTime,
            status,
            comments,
            signature,
            _token: '{{ csrf_token() }}'
        };
    } else {
        url = '/daily-diary/toileting';
        method = 'POST';
        data = {
            child_id: childId,
            selected_date: diaryDate,
            startTime,
            status,
            comments,
            signature,
            _token: '{{ csrf_token() }}'
        };
    }

    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(res) {
            if(res.success){
                $('#toiletingModal').modal('hide');
                showToast('success', res.message);
                window.location.reload();
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
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


       function getSelectedDate() {
    const urlParams = new URLSearchParams(window.location.search);
    let date = urlParams.get('selected_date');
    if (!date) {
        let d = new Date();
        let month = (d.getMonth() + 1).toString().padStart(2, '0');
        let day = d.getDate().toString().padStart(2, '0');
        date = d.getFullYear() + '-' + month + '-' + day;
    }
    return date;
}


$(document).on('click', '.open-bottle-modal', function() {
    const mode = $(this).data('mode');
    const childId = $(this).data('child-id');
    let selected_date = $(this).data('selected-date');
    const entryId = $(this).data('entry-id') || '';

    if (!selected_date) selected_date = getSelectedDate();

    // Reset
    $('#bottleForm')[0].reset();
    $('#bottle_modal_child_id').val(childId.id);
    $('#bottle_modal_selected_date').val(selected_date);
    $('#bottle_modal_entry_id').val('');

    if (mode === 'edit' && entryId) {
        $.ajax({
            url: '/daily-diary/bottle/' + entryId,
            method: 'GET',
            success: function(res) {
                if (res.data) {
                    $('#bottle_modal_start_time').val(res.data.startTime || '');
                    $('#bottle_modal_comments').val(res.data.comments || '');
                    $('#bottle_modal_entry_id').val(res.data.id);
                }
                $('#bottleModal').modal('show');
            },
            error: function() {
                showToast('error', 'Error fetching bottle entry');
            }
        });
    } else {
        $('#bottle_modal_entry_id').val('');
        $('#bottleModal').modal('show');
    }
});


$('#bottleForm').on('submit', function(e) {
    e.preventDefault();

    const childId = $('#bottle_modal_child_id').val();
    const diaryDate = $('#bottle_modal_selected_date').val();
    const entryId = $('#bottle_modal_entry_id').val();
    const startTime = $('#bottle_modal_start_time').val();
    const comments = $('#bottle_modal_comments').val();

    let url, method, data;
    if (entryId) {
        url = '/daily-diary/bottle/' + entryId;
        method = 'PUT';
        data = {
            child_id: childId,
            selected_date: diaryDate,
            startTime,
            comments,
            _token: '{{ csrf_token() }}'
        };
    } else {
        url = '/daily-diary/bottle';
        method = 'POST';
        data = {
            child_id: childId,
            selected_date: diaryDate,
            startTime,
            comments,
            _token: '{{ csrf_token() }}'
        };
    }

    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(res) {
            if(res.success){
                $('#bottleModal').modal('hide');
                showToast('success', res.message);
                window.location.reload();
            } else {
                showToast('error', res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON?.message || 'Something went wrong';
            showToast('error', msg);
        }
    });
}); 



</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.room-selector').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default anchor behavior
            e.stopPropagation(); // Stop Bootstrap dropdown from interfering

            const targetUrl = this.dataset.url;
            if (targetUrl) {
                window.location.href = targetUrl;
            }
        });
    });
});
</script>


@include('layout.footer')
@stop