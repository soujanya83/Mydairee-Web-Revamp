@extends('layout.master')
@section('title', 'Permissions Assign')
@section('parentPageTitle', '')
{{--
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    :root {
        --primary: #4361ee;
        --primary-dark: #3a56d4;
        --secondary: #7209b7;
        --accent: #4cc9f0;
        --light: #f8f9fa;
        --dark: #212529;
        --success: #4caf50;
        --warning: #ff9800;
        --danger: #f44336;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    body {
        background-color: #f5f7fb;
        color: var(--dark);
        line-height: 1.6;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 20px;
        border-radius: var(--border-radius);
        margin-bottom: 30px;
        box-shadow: var(--box-shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo i {
        font-size: 2.2rem;
        color: var(--accent);
    }

    .logo h1 {
        font-size: 1.8rem;
        font-weight: 600;
    }

    .controls {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: none;
        border-radius: var(--border-radius);
        font-size: 1rem;
        box-shadow: var(--box-shadow);
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .role-selector {
        width: 250px;
    }

    .role-selector select {
        width: 100%;
        padding: 12px 20px;
        border: none;
        border-radius: var(--border-radius);
        font-size: 1rem;
        background-color: white;
        box-shadow: var(--box-shadow);
        cursor: pointer;
    }

    .permission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .permission-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
        position: relative;
    }

    .permission-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        /* background: linear-gradient(to right, var(#49c5b6), var(#49c5b6)); */
        color: white;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #49c5b6
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header i {
        font-size: 1.4rem;
    }

    .card-header h3 {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .select-all-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .select-all-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .select-all-btn.active {
        background: var(--success);
    }

    .card-body {
        padding: 20px;
    }

    .permission-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .permission-item:last-child {
        border-bottom: none;
    }

    .permission-item label {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        color: var(--dark);
    }

    .permission-item i {
        color: #49c5b6;
        font-size: 1.1rem;
        min-width: 20px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: var(--transition);
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: var(--transition);
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #49c5b6
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--light-gray);
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: var(--border-radius);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-primary {
        background: linear-gradient(to right, var(--primary), var(--primary-dark));
        color: white;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        transform: translateX(120%);
        transition: transform 0.4s ease;
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 1000;
    }

    .notification.show {
        transform: translateX(0);
    }

    .notification i {
        font-size: 1.8rem;
        color: var(--success);
    }

    .role-info {
        background: white;
        padding: 15px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .role-info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .role-info-label {
        font-size: 0.9rem;
        color: var(--gray);
    }

    .role-info-value {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .stats {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-icon.blue {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }

    .stat-icon.green {
        background: rgba(76, 175, 80, 0.1);
        color: var(--success);
    }

    .stat-icon.purple {
        background: rgba(114, 9, 183, 0.1);
        color: var(--secondary);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }

    .stat-label {
        color: var(--gray);
        text-align: center;
    }

    @media (max-width: 768px) {
        .permission-grid {
            grid-template-columns: 1fr;
        }

        .controls {
            flex-direction: column;
        }

        .role-selector {
            width: 100%;
        }

        header {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .stats {
            flex-direction: column;
        }

        .actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>


@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" style="margin-top:-22px">
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
<div class="alert alert-success alert-dismissible fade show mt-3" role="alert" style="margin-top:-22px">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="row clearfix" style="margin-top:30px">


    <div class="col-lg-12">
        <div class="card">


            <body>

                <div class="container">


                    {{-- <div class="stats">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-value">18</div>
                            <div class="stat-label">Active Roles</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="stat-value">142</div>
                            <div class="stat-label">Total Permissions</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon purple">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="stat-value">96%</div>
                            <div class="stat-label">Permission Coverage</div>
                        </div>
                    </div> --}}



                    {{-- <div class="controls"> --}}
                        <form action="{{ route('settings.assign_permissions') }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center gap-10 mb-5">
                                <div class="col-md-8"> <select name="user_ids[]" id="user_ids" class="form-control"
                                        multiple required style="flex: 1;">
                                        @foreach($users as $user)
                                        <option value="{{ $user->userid }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select></div>

                                <button type="button" class="select-all-btn btn-outline mb-0" data-category=""
                                    style="    background: #fff;">
                                    <i class="fas fa-check-circle"></i> Select All Permissions
                                </button>

                                <a class="select-all-btn btn-outline mb-0" style="background: #fff;margin-left: 20px;"
                                    href="{{ route('settings.assigned_permissions') }}">
                                    <i class="fa fa-users"></i>&nbsp; Assigned Users List
                                </a>
                            </div>




                            @php
                            function getPermissionIcon($label) {
                            $labelLower = strtolower($label);
                            if (str_contains($labelLower, 'add')) return 'fas fa-plus-circle';
                            if (str_contains($labelLower, 'approve')) return 'fas fa-check-circle';
                            if (str_contains($labelLower, 'delete')) return 'fas fa-trash-alt';
                            if (str_contains($labelLower, 'update') || str_contains($labelLower, 'edit')) return 'fas
                            fa-edit';
                            if (str_contains($labelLower, 'view')) return 'fas fa-eye';
                            return 'fas fa-cog'; // default icon
                            }
                            @endphp


                            <div class="permission-grid">
                                <!-- Observation Management -->
                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="icon-equalizer"></i>
                                            <h3>Observation Management</h3>
                                        </div>
                                        <button type="button" class="select-all-btn" data-category="observation">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        @foreach($ObservationPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    data-category="observation" name="permissions[{{ $perm['name'] }}]"
                                                    {{ !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Reflection Management -->
                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fa-solid fa-window-restore"></i>
                                            <h3>Reflection Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="reflection">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($ReflectionPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="reflection"
                                                    {{ !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- QIP Management -->
                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fa-solid fa-clipboard"></i>
                                            <h3>QIP Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="qip">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($QipPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" name="permissions[{{ $perm['name'] }}]"
                                                    class="permission-check" data-category="qip" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Room Management -->
                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fa-solid fa-users-viewfinder"></i>
                                            <h3>Room Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="room">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($RoomPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="room" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fa fa-bullhorn"></i>
                                            <h3>Announcement Manage</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="announcement">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($AnnouncementPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="announcement"
                                                    {{ !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-door-open"></i>
                                            <h3>Survey Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="survey">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($SurveyPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="survey" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-utensils"></i>
                                            {{-- <i class="fas fa-utensils" style="font-size: 25px;"></i> --}}
                                            <h3>Healthy Eating(Recipes)</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="recipes">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($RecipePermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="recipes" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-utensils"></i>

                                            <h3>Healthy Eating(Menu)</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="menu">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($MenuPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="menu" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>


                                <!-- Program Plan -->
                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-calendar-alt"></i>
                                            <h3>Program Plan Manage</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="program">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($ProgramPlanPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="program" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Daily Operations -->
                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-calendar-day"></i>
                                            <h3>Daily Journal Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="daily">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($DailyPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="daily" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- User Management -->
                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-users"></i>
                                            <h3>User Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="user">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($UsersPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="user" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-location"></i>
                                            <h3>Centers Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="centers">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($CentersPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="centers" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-child"></i>
                                            <h3>Childrens Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="childrens">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($ChildPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="childrens" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-user-friends"></i>
                                            <h3>Parent Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="parent">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($ParentPlanPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="parent" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-chart-line"></i>
                                            <h3>Progress Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="progress">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($ProgressPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="progress" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-book-open"></i>
                                            <h3>Lesson Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="lesson">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($LessonPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="lesson" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-file-alt"></i>
                                            <h3>Assessment Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="assessment">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($AssessmentPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="assessment"
                                                    {{ !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-ambulance"></i>
                                            <h3>Accidents Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="accidents">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($AccidentsPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="accidents" {{
                                                    !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' :
                                                '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Permissions Module -->
                                <div class="permission-card">
                                    <div class="card-header">
                                        <div class="header-content">
                                            <i class="fas fa-key"></i>
                                            <h3>Permissions Management</h3>
                                        </div>
                                        <button class="select-all-btn" type="button" data-category="permissions">
                                            <i class="fas fa-check-circle"></i> All
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @foreach($otherPermissions as $perm)
                                        <div class="permission-item">
                                            <label>
                                                <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                                {{ $perm['label'] }}
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="permission-check"
                                                    name="permissions[{{ $perm['name'] }}]" data-category="permissions"
                                                    {{ !empty($userPermissions) && $userPermissions->{$perm['name']} ?
                                                'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                            <div class="actions">
                                <button type="submit" class="btn btn-info mb-3">
                                    Assign Permissions
                                </button>
                            </div>
                        </form>

                    </div>
            </body>
        </div>


    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
    $('#user_ids').select2({
        placeholder: "Select users",
        width: '100%'
    });

    const selectAllBtns = document.querySelectorAll('.select-all-btn');
    const permissionChecks = document.querySelectorAll('.permission-check');

    // Master "Select All Permissions" button functionality
    const masterBtn = document.querySelector('.select-all-btn[data-category=""]');
    masterBtn.addEventListener('click', function () {
        const allChecked = Array.from(permissionChecks).every(cb => cb.checked);
        const newState = !allChecked;
        permissionChecks.forEach(checkbox => {
            checkbox.checked = newState;
        });
        selectAllBtns.forEach(btn => {
            btn.classList.toggle('active', newState);
        });
    });

    // Card-specific "All" button functionality
    selectAllBtns.forEach(btn => {
        if (btn.getAttribute('data-category')) { // Skip master button
            btn.addEventListener('click', function () {
                const category = this.getAttribute('data-category');
                const checkboxes = document.querySelectorAll(`.permission-check[data-category="${category}"]`);
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const newState = !allChecked;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = newState;
                });
                this.classList.toggle('active', newState);
                updateMasterButtonState();
            });
        }
    });

    // Update "All" button state when individual checkboxes change
    permissionChecks.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const category = this.getAttribute('data-category');
            const checkboxes = document.querySelectorAll(`.permission-check[data-category="${category}"]`);
            const selectAllBtn = document.querySelector(`.select-all-btn[data-category="${category}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            selectAllBtn.classList.toggle('active', allChecked);
            updateMasterButtonState();
        });
    });

    // Update master button state based on all checkboxes
    function updateMasterButtonState() {
        const allChecked = Array.from(permissionChecks).every(cb => cb.checked);
        const noneChecked = Array.from(permissionChecks).every(cb => !cb.checked);
        masterBtn.classList.toggle('active', allChecked);
    }

    // Initialize button states
    updateMasterButtonState();
});
</script>


@include('layout.footer')

@endsection
