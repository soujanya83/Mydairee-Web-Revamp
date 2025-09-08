@extends('layout.master')
@section('title', 'Assigned Permissions - ' . ($username->name ?? 'User'))
@section('parentPageTitle', '')

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

    .user-info {
        background: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content:between ;
        gap: 20px;
        
    }

       .user-info1 {
        /* background: white; */
        padding: 20px;
        /* border-radius: var(--border-radius); */
        /* box-shadow: var(--box-shadow); */
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content:between ;
        gap: 20px;
        
    }

      .user-info1 h2{
  margin: 0;
        color: var(--dark);
        font-size: 1.4rem;
      }

    .user-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        font-weight: 600;
    }

    .user-details h2 {
        margin: 0;
        color: var(--dark);
        font-size: 1.4rem;
    }

    .user-details p {
        margin: 5px 0 0 0;
        color: var(--gray);
    }

       .user-details1 h2 {
        margin: 0;
        color: var(--dark);
        font-size: 1.4rem;
    }

    .user-details1 p {
        margin: 5px 0 0 0;
        color: var(--gray);
    }

    .user-details1 {
    float: right !important;
}

    .back-btn {
        background: #49c5b6;
        border: 1px solid #000;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: var(--border-radius);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: var(--transition);
        margin-bottom: 20px;
    }

    .back-btn:hover {
        background: black;
        color: white;
        text-decoration: none;
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
        color: white;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #49c5b6;
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

    .permission-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 5px;
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

    .permission-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .status-icon {
        font-size: 1.2rem;
    }

    .status-granted {
        color: var(--success);
    }

    .status-denied {
        color: var(--danger);
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
        pointer-events: none;
        /* Make read-only */
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
        background-color: #49c5b6;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .no-permissions {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray);
    }

    .no-permissions i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: var(--light-gray);
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

    .stat-icon.red {
        background: rgba(244, 67, 54, 0.1);
        color: var(--danger);
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

        .user-info {
            flex-direction: column;
            text-align: center;
        }

        .stats {
            flex-direction: column;
        }
    }

.submit-btn button {
    display: block;                 
    padding: 10px 36px;             /* comfy padding */
    border: none;                  
    border-radius: 8px;            
    background-color: #17a2b8;     
    color: #fff;                   
    font-size: 14px;               
    font-weight: 600;              
    cursor: pointer;               
    box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
    transition: all 0.3s ease;     
    white-space: nowrap;            /* prevent breaking */
}

/* Hover effect */
.submit-btn button:hover {
    background-color: #138496; 
      color: #fff;      
    transform: translateY(-2px);   
    box-shadow: 0 6px 10px rgba(0,0,0,0.15);
}

/* Click (active) state */
.submit-btn button:active {
    transform: translateY(0);      
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* 📱 Responsiveness */
@media (max-width: 768px) {
    .submit-btn button {
        font-size: 13px;
        padding: 8px 28px;   /* smaller padding for tablets */
    }
}

@media (max-width: 480px) {
    .submit-btn button {
        font-size: 12px;
        padding: 6px 20px;   /* compact for mobile */
        border-radius: 6px;
    }
}

.permission-count:hover{
    cursor:pointer;

}

</style>

<div class="row clearfix" style="margin-top:30px">
    <div class="col-lg-12">
        <div class="card">

            <body>
                <div class="container">
                    <!-- Back Button -->
                    <a href="{{ route('settings.manage_permissions') }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Manage Permissions 
                    </a>

                    <!-- User Information -->
               <div class="user-info d-flex justify-content-between align-items-center">
    
    <!-- Left side: Avatar + Name -->
    <div class="user-info1 d-flex align-items-center">
        <div class="user-avatar me-2">
            {{ substr($username->name ?? 'U', 0, 1) }}
        </div>
        <div class="user-details">
            <h2 class="mb-0">{{ $username->name ?? 'Unknown User' }} /   @if($username->admin == 1)
                Admin
            @else
                {{ $username->userType }}
            @endif</h2>
            <p class="mb-0">Assigned Permissions Overview</p>
        </div>
    </div>

    <!-- Right side: Role -->
  
</div>


                    @php
                    $totalPermissions = $permissionColumns->count();
                    $assignedPermissions = 0;
                    if ($userPermissions) {
                    foreach ($permissionColumns as $perm) {
                    if ($userPermissions->{$perm['name']}) {
                    $assignedPermissions++;
                    }
                    }
                    }
                    $unassignedPermissions = $totalPermissions - $assignedPermissions;
                    @endphp

                    <!-- Statistics -->
                    <div class="stats">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="stat-value">{{ $totalPermissions }}</div>
                            <div class="stat-label">Total Permissions</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="stat-value">{{ $assignedPermissions }}</div>
                            <div class="stat-label">Granted</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="stat-value">{{ $unassignedPermissions }}</div>
                            <div class="stat-label">Not Granted</div>
                        </div>
                    </div>

                    @if($userPermissions)
                    @php
                    function getPermissionIcon($label) {
                    $labelLower = strtolower($label);
                    if (str_contains($labelLower, 'add')) return 'fas fa-plus-circle';
                    if (str_contains($labelLower, 'approve')) return 'fas fa-check-circle';
                    if (str_contains($labelLower, 'delete')) return 'fas fa-trash-alt';
                    if (str_contains($labelLower, 'update') || str_contains($labelLower, 'edit')) return 'fas fa-edit';
                    if (str_contains($labelLower, 'view')) return 'fas fa-eye';
                    return 'fas fa-cog'; // default icon
                    }
                    @endphp

                    <form action="{{ route('settings.update-permission')}}" method="post" >
                        @csrf
   <input type="hidden" name="userid" value="{{ $userPermissions->userid }}">

                    <div class="permission-grid">
                        <!-- Observation Manage -->
                        @if($ObservationPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="icon-equalizer"></i>
                                    <h3>Observation Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $ObservationPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $ObservationPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($ObservationPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                        <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Reflection Manage -->
                        @if($ReflectionPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fa-solid fa-window-restore"></i>
                                    <h3>Reflection Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $ReflectionPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $ReflectionPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($ReflectionPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                  
                                                                        <input type="checkbox" 
                                    class="permission-toggle" 
                                    name="{{ $perm['name'] }}" 
                                    value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
                                    data-permission="{{ $perm['name'] }}" 
                                    {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- QIP Manage -->
                        @if($QipPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fa-solid fa-clipboard"></i>
                                    <h3>QIP Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $QipPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $QipPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($QipPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Room Manage -->
                        @if($RoomPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fa-solid fa-users-viewfinder"></i>
                                    <h3>Room Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $RoomPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $RoomPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($RoomPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Announcement Manage -->
                        @if($AnnouncementPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fa fa-bullhorn"></i>
                                    <h3>Announcement Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $AnnouncementPermissions->filter(fn($perm) => $userPermissions->{$perm['name']}
                                    ?? false)->count() }} / {{ $AnnouncementPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($AnnouncementPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Survey Manage -->
                        @if($SurveyPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-door-open"></i>
                                    <h3>Survey Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $SurveyPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $SurveyPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($SurveyPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                       <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Recipe Manage -->
                        @if($RecipePermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-utensils"></i>
                                    <h3>Healthy Eating (Recipes)</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $RecipePermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $RecipePermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($RecipePermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                        <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Menu Manage -->
                        @if($MenuPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-utensils"></i>
                                    <h3>Healthy Eating (Menu)</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $MenuPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $MenuPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($MenuPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Program Plan Manage -->
                        @if($ProgramPlanPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-calendar-alt"></i>
                                    <h3>Program Plan Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $ProgramPlanPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $ProgramPlanPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($ProgramPlanPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                    <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Daily Journal Manage -->
                        @if($DailyPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-calendar-day"></i>
                                    <h3>Daily Journal Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $DailyPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $DailyPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($DailyPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- User Manage -->
                        @if($UsersPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-users"></i>
                                    <h3>User Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $UsersPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $UsersPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($UsersPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                     <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Centers Manage -->
                        @if($CentersPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-location"></i>
                                    <h3>Centers Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $CentersPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $CentersPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($CentersPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Children Manage -->
                        @if($ChildPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-child"></i>
                                    <h3>Children Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $ChildPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $ChildPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($ChildPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                     <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Parent Manage -->
                        @if($ParentPlanPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-user-friends"></i>
                                    <h3>Parent Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $ParentPlanPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $ParentPlanPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($ParentPlanPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Progress Manage -->
                        @if($ProgressPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-chart-line"></i>
                                    <h3>Progress Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $ProgressPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $ProgressPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($ProgressPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                     <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Lesson Manage -->
                        @if($LessonPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-book-open"></i>
                                    <h3>Lesson Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $LessonPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $LessonPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($LessonPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Assessment Manage -->
                        @if($AssessmentPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-file-alt"></i>
                                    <h3>Assessment Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $AssessmentPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $AssessmentPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($AssessmentPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Accidents Manage -->
                        @if($AccidentsPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-ambulance"></i>
                                    <h3>Accidents Manage</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $AccidentsPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $AccidentsPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($AccidentsPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                      <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($SnapshotsPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="icon-camera"></i>
                                    <h3>Snapshots Permissions</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $SnapshotsPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $SnapshotsPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($SnapshotsPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                     <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif


                        <!-- Other Permissions Manage -->
                        @if($otherPermissions->count() > 0)
                        <div class="permission-card">
                            <div class="card-header">
                                <div class="header-content">
                                    <i class="fas fa-key"></i>
                                    <h3>Other Permissions</h3>
                                </div>
                                <div class="permission-count">
                                    <i class="fas fa-check"></i>
                                    {{ $otherPermissions->filter(fn($perm) => $userPermissions->{$perm['name']} ??
                                    false)->count() }} / {{ $otherPermissions->count() }}
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($otherPermissions as $perm)
                                <div class="permission-item">
                                    <label>
                                        <i class="{{ getPermissionIcon($perm['label']) }}"></i>
                                        {{ $perm['label'] }}
                                    </label>
                                    <label class="switch">
                                     <input type="checkbox" 
       class="permission-toggle" 
       name="{{ $perm['name'] }}" 
       value="{{ ($userPermissions->{$perm['name']} ?? 0) }}" 
       data-permission="{{ $perm['name'] }}" 
       {{ ($userPermissions->{$perm['name']} ?? false) ? 'checked' : '' }}>

                                        <span class="slider"></span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                                       <div class="submit-btn float-right" 
     style="position:relative; z-index:9999; bottom:70px;">
    <button type="submit" class="btn btn-light">Submit</button>
</div>
                </form>

                    @else
                    <div class="no-permissions">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>No Permissions Assigned</h3>
                        <p>This user has not been assigned any permissions yet.</p>
                        <a href="{{ route('settings.manage_permissions') }}" class="back-btn" style="margin-top: 20px;">
                            <i class="fas fa-plus"></i> Assign Permissions
                        </a>
                    </div>
                    @endif

                </div>
            </body>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Done 🎉',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}"
        });
    </script>
@endif
<script>
// $(document).ready(function(){
//     $('.permission-toggle').on('change', function() {
//         let permissionName = $(this).data('permission');
//         let isChecked = $(this).is(':checked') ? 1 : 0;
//         let userid = {{ $username->userid}};

//         console.log(permissionName);
//         console.log(isChecked);
// console.log(userid);
//         $.ajax({
//             url: '{{ route("settings.update-permission")}}',   // Your route here
//             method: 'POST',
//             data: {
//                 _token: '{{ csrf_token() }}',
//                 permission: permissionName,
//                 value: isChecked,
//                 userid:userid
//             },
//             success: function(response) {
//                 console.log('Permission updated:', response);
//             },
//             error: function(xhr) {
//                 console.error('Error updating permission:', xhr.responseText);
//                 alert('Failed to update permission. Please try again.');
//             }
//         });
//     });
// });
$(function() {
  // Delegated handler for individual checkboxes (works for dynamic content)
  $(document).on('change', '.permission-toggle', function() {
    console.log('permission changed:', this.dataset.permission, 'checked=', this.checked); // debug

    let $card = $(this).closest('.permission-card');
    let checkedCount = $card.find('.permission-toggle:checked').length;
    let totalCount   = $card.find('.permission-toggle').length;

    $card.find('.permission-count').html(`<i class="fas fa-check"></i> ${checkedCount} / ${totalCount}`);

    // keep the value attribute consistent (if you rely on it server-side)
    $(this).val(this.checked ? 1 : 0);

    // show the floating submit (show container, not only inner button)
    $(".submit-btn").fadeIn();
  });

  // Delegated handler for clicking the count (toggle all)
  $(document).on('click', '.permission-card .permission-count', function() {
    let $card = $(this).closest('.permission-card');
    let $boxes = $card.find('.permission-toggle');

    // determine new state (toggle)
    let allChecked = ($boxes.length === $card.find('.permission-toggle:checked').length);
    let newState = !allChecked;

    // set each box and trigger change so the individual handler runs
    $boxes.each(function() {
      $(this).prop('checked', newState);
      $(this).val(newState ? 1 : 0);
      $(this).trigger('change'); // important — fires the individual handler
    });

    // update the header counter (change handler already updated it, but keep safe)
    let checkedCount = $card.find('.permission-toggle:checked').length;
    let totalCount = $boxes.length;
    $(this).html(`<i class="fas fa-check"></i> ${checkedCount} / ${totalCount}`);

    $(".submit-btn").fadeIn();
  });
});


</script>

@include('layout.footer')
@endsection
