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


@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -60px;">





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
            <a href="javascript:void(0);"
                onclick="window.location.href='{{ route('dailyDiary.list', ['room_id' => $rooms->id, 'center_id' => session('user_center_id')]) }}'"
                class="dropdown-item center-option {{ $selectedroom->id == $rooms->id ? 'active font-weight-bold text-primary' : '' }}"
                style="background-color:white;">
                {{ $rooms->name }}
            </a>
            @endforeach
        </div>
    </div>
    &nbsp;&nbsp;&nbsp;&nbsp;


    <form method="GET" action="{{ route('dailyDiary.list') }}" id="dateRoomForm">
        <input type="hidden" name="room_id" value="{{ $selectedroom->id }}">
        <input type="hidden" name="center_id" value="{{ session('user_center_id') }}">

        <div class="form-group">
            <!-- <label for="datePicker" class="font-weight-bold">Select Date:</label> -->
            <input type="date" class="form-control custom-datepicker btn-outline-primary btn-lg" id="datePicker"
                name="selected_date" value="{{ $selectedDate->format('Y-m-d') }}" onclick="this.showPicker()"
                onchange="document.getElementById('dateRoomForm').submit();">
        </div>
    </form>


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
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addEntryModal">
    <i class="fas fa-plus mr-2"></i>Add Entry
</button>
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
                                    <span class="badge badge-info badge-status ml-2">Not Update</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse show" id="Breakfast-{{ $childId }}">
                                <div class="activity-content">
                                    <div class="activity-entry activity-breakfast">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                              
                                                <span class="entry-value">{{ $breakfast->startTime ?? 'Not-Update' }}</span>
                                          
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Item:</span>
                                                <span class="entry-value">{{ $breakfast->item ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $breakfast->comments ?? 'Not-Update' }}</span>
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
                                    <span class="badge badge-info badge-status ml-2">Not Update</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Morning-{{ $childId }}">
                                <div class="activity-content">
                                    <div class="activity-entry activity-morning-tea">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $morning_tea->startTime ?? 'Not-Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $morning_tea->comments ?? 'Not-Update' }}</span>
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
                                    <div class="activity-entry activity-lunch">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $lunch->startTime ?? 'Not-Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Item:</span>
                                                <span class="entry-value">{{ $lunch->item ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $lunch->comments ?? 'Not-Update' }}</span>
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
                                    @if($sleep)
                                    <span class="badge badge-info badge-status ml-2">2 Entries</span>
                                    @else
                                    <span class="badge badge-danger badge-status ml-2">0 Entries</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Sleep-{{ $childId }}">
                                <div class="activity-content">
                                    <div class="activity-entry activity-sleep">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Sleep Time:</span>
                                                <span class="entry-value">{{ $sleep->startTime ?? 'Not-Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Wake Time:</span>
                                                <span class="entry-value">{{ $sleep->endTime ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $sleep->comments ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                  
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
                                    <div class="activity-entry activity-afternoon-tea">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $afternoon_tea->startTime ?? 'Not-Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $afternoon_tea->comments ?? 'Not-Update' }}</span>
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
                                    <div class="activity-entry activity-snacks">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $snacks->startTime ?? 'Not-Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Item:</span>
                                                <span class="entry-value">{{ $snacks->item ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $snacks->comments ?? 'Not-Update' }}</span>
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
                                    @if($sunscreen)
                                    <span class="badge badge-info badge-status ml-2">2 Applications</span>
                                    @else
                                    <span class="badge badge-danger badge-status ml-2">0 Applications</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Sunscreen-{{ $childId }}">
                                <div class="activity-content">
                                    <div class="activity-entry activity-sunscreen">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $sunscreen->startTime ?? 'Not-Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $sunscreen->comments ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="activity-entry activity-sunscreen">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">2:45 PM</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">Reapplied after nap for afternoon outdoor time</span>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <!-- Toileting -->
                        <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Toileting-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-baby activity-icon"></i>
                                    Toileting
                                    @if($toileting)
                                    <span class="badge badge-info badge-status ml-2">4 Changes</span>
                                    @else
                                    <span class="badge badge-warning badge-status ml-2">Not Update</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Toileting-{{ $childId }}">
                                <div class="activity-content">
                                    <div class="activity-entry activity-toileting">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $toileting->startTime ?? 'Not-Update' }}</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Status:</span>
                                                <span class="badge badge-warning">{{ $toileting->status ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">{{ $toileting->comments ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="activity-entry activity-toileting">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">11:20 AM</span>
                                            </div>
                                            <div class="entry-item">
                                                <span class="entry-label">Status:</span>
                                                <span class="badge badge-success">Clean</span>
                                            </div>
                                        </div>
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Comments:</span>
                                                <span class="entry-value">Routine check</span>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>



                         <!-- Bottel -->
                         <div class="activity-section">
                            <div class="activity-header" data-toggle="collapse" data-target="#Bottel-{{ $childId }}">
                                <h5>
                                    <i class="fas fa-bottel-water activity-icon"></i>
                                    Bottle
                                    @if($bottle)
                                    <span class="badge badge-primary badge-status ml-2">Completed</span>
                                    @else
                                    <span class="badge badge-secondary badge-status ml-2">Pending</span>
                                    @endif
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </h5>
                            </div>
                            <div class="collapse" id="Bottel-{{ $childId }}">
                                <div class="activity-content">
                                    <div class="activity-entry activity-bottle">
                                        <div class="entry-row">
                                            <div class="entry-item">
                                                <span class="entry-label">Time:</span>
                                                <span class="entry-value">{{ $bottle->startTime ?? 'Not-Update' }}</span>
                                            </div>
                                        </div>
                                    </div>
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
<div class="modal fade" id="addEntryModal" tabindex="-1" role="dialog" aria-labelledby="addEntryModalLabel" aria-hidden="true">
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
                                <i class="fas fa-bottle-water"></i>
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
            activityData.comments = $('#sunscreen-comments').val();
            break;
        case 'toileting':
            activityData.time = $('#toileting-time').val();
            activityData.status = $('#nappy-status').val();
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



@include('layout.footer')
@stop