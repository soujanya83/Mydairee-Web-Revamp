@extends('layout.master')
@section('title', 'Program Plan')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<style>
    .swal2-confirm.published-btn {
    background: linear-gradient(135deg, var(--danger-color), var(--secondary-color)) !important;
    color: #fff !important;
    border: none;
}

.swal2-cancel.draft-btn {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    color: #fff !important;
    border: none;
}

    .pagination {
        font-size: 0.9rem;
        /* Slightly larger for better readability */
        justify-content: center;
        /* Ensure pagination is centered */
        margin-bottom: 80px;
    }

    .page-item .page-link {
        padding: 0.5rem 0.75rem;
        /* Bootstrap 4 default padding for better spacing */
        font-size: 0.9rem;
        /* Match pagination font size */
        line-height: 1.5;
        /* Improved line height for readability */
        border-radius: 0.25rem;
        /* Keep your custom border radius */
        color: #007bff;
        /* Bootstrap primary color for links */
        background-color: #fff;
        /* Ensure background matches Bootstrap */
        border: 1px solid #dee2e6;
        /* Bootstrap default border */
    }

    .page-item.active .page-link {
        background-color: #007bff;
        /* Bootstrap primary color for active state */
        border-color: #007bff;
        color: #fff;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        /* Bootstrap disabled color */
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }

    /* SVG icons for Previous/Next arrows */
    .page-item .page-link svg {
        width: 1em;
        /* Slightly larger for better visibility */
        height: 1em;
        vertical-align: middle;
    }
</style>
<style>
    :root {
        --primary-color: #667eea;
        --secondary-color: #764ba2;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
        --light-bg: #f8fafc;
        --dark-text: #1e293b;
        --muted-text: #64748b;
        --border-color: #e2e8f0;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        min-height: 100vh;
        color: var(--dark-text);
    }

    .main-container {
        padding: 2rem 1rem;
        min-height: 100vh;
    }

    .page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-custom {
        color: var(--muted-text);
        font-size: 0.95rem;
    }

    .breadcrumb-custom .separator {
        margin: 0 0.75rem;
        opacity: 0.5;
    }

    .program-plan-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .program-plan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem 2rem;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .card-header-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
        pointer-events: none;
    }

    .card-header-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-container {
        padding: 0;
        overflow: hidden;
    }

    .table-responsive {
        border-radius: 0;
        margin: 0;
    }

    .table {
        margin: 0;
        background: white;
    }

    .table thead th {
        background: var(--light-bg);
        color: var(--dark-text);
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
        border: none;
        border-bottom: 2px solid var(--border-color);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody tr {
        transition: all 0.2s ease;
        border: none;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        transform: scale(1.01);
    }

    .table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border: none;
        border-bottom: 1px solid var(--border-color);
    }

    .id-badge {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-block;
        min-width: 2.5rem;
        text-align: center;
    }

    .month-badge {
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }

    .info-text {
        color: var(--muted-text);
        font-size: 0.875rem;
    }

    .date-text {
        color: var(--dark-text);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    /* Fallback for browsers that don't support gap */
    .action-buttons>*+* {
        margin-left: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .btn-action:hover::before {
        left: 100%;
    }

    .btn-print {
        background: linear-gradient(135deg, var(--info-color), #2563eb);
        color: white;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }

    .btn-print:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--warning-color), #d97706);
        color: white;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: white;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        color: white;
    }

    .animated-icon {
        transition: transform 0.2s ease;
    }

    .btn-action:hover .animated-icon {
        transform: scale(1.1);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--muted-text);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 1.125rem;
        margin: 0;
    }

    .pagination-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-top: 2rem;
        box-shadow: var(--shadow);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .no-data-message {
        text-align: center;
        color: var(--muted-text);
        font-size: 1.125rem;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 0.75rem;
        margin-top: 2rem;
        box-shadow: var(--shadow);
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .main-container {
            padding: 1rem 0.5rem;
        }

        .page-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .table-responsive {
            border-radius: 0;
            margin: 0 -15px;
        }

        .table {
            font-size: 0.875rem;
        }

        .table thead {
            display: none;
        }

        .table,
        .table tbody,
        .table tr,
        .table td {
            display: block;
        }

        .table tbody tr {
            background: white;
            margin-bottom: 1rem;
            border-radius: 0.75rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
            padding: 0;
        }

        .table tbody tr:hover {
            transform: none;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            border: none;
            border-bottom: 1px solid var(--border-color);
            position: relative;
            padding-left: 35%;
            text-align: right;
        }

        .table tbody td:last-child {
            border-bottom: none;
        }

        .table tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            top: 0.75rem;
            font-weight: 600;
            color: var(--dark-text);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            width: 30%;
            text-align: left;
        }

        .action-buttons {
            justify-content: flex-end;
            gap: 0.375rem;
        }

        .btn-action {
            min-width: 2.25rem;
            height: 2.25rem;
            padding: 0.375rem;
        }

        .month-badge,
        .id-badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.625rem;
        }

        /* Fix for gap not supported in older browsers */
        .action-buttons>*+* {
            margin-left: 0.375rem;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.75rem;
        }

        .table tbody td {
            padding-left: 40%;
            font-size: 0.8rem;
        }

        .table tbody td::before {
            width: 35%;
            font-size: 0.7rem;
        }

        /* Fix for gap not supported in older browsers */
        .action-buttons>*+* {
            margin-left: 0.25rem;
        }
    }

    /* Bootstrap 4 Compatibility Fixes */
    .container-fluid {
        padding-right: 15px;
        padding-left: 15px;
    }

    .card {
        border: none;
        border-radius: 0.75rem;
    }

    .btn {
        border-radius: 0.375rem;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Custom Bootstrap 4 Pagination Styles */
    .pagination {
        display: flex;
        list-style: none;
        border-radius: 0.25rem;
        margin: 0;
    }

    .page-item {
        margin: 0 2px;
    }

    .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: 0;
        line-height: 1.25;
        color: var(--primary-color);
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        z-index: 2;
        color: white;
        text-decoration: none;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-color: var(--primary-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.2);
    }

    .page-item.active .page-link {
        z-index: 1;
        color: white;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-color: var(--primary-color);
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        cursor: auto;
        background-color: #fff;
        border-color: #dee2e6;
        opacity: 0.5;
    }

    /* Flex utilities for Bootstrap 4 */
    .d-flex {
        display: flex !important;
    }

    .justify-content-center {
        justify-content: center !important;
    }

    .justify-content-between {
        justify-content: space-between !important;
    }

    .align-items-center {
        align-items: center !important;
    }

    .flex-wrap {
        flex-wrap: wrap !important;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 1rem;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    .mt-3 {
        margin-top: 1rem !important;
    }

    .px-0 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    /* Print Styles */
    @media print {
        body {
            background: white !important;
        }

        .main-container {
            padding: 0;
        }

        .page-header,
        .pagination-container {
            background: white !important;
            box-shadow: none !important;
        }

        .program-plan-card {
            background: white !important;
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

        .action-buttons {
            display: none !important;
        }
    }
</style>
@endsection
@section('content')
<div class="text-zero top-right-button-container d-flex justify-content-end"
    style="margin-right: 20px;margin-top: -60px;">

    <div class="text-zero top-right-button-container">

        <div class="btn-group mr-1">
            <div class="dropdown">
                <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="centerDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
                    style="top:3% !important;left:13px !important;">
                    @foreach($centers as $center)
                    <a href="javascript:void(0);"
                        class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-info' : '' }}"
                        style="background-color:white;" data-id="{{ $center->id }}">
                        {{ $center->centerName }}
                    </a>
                    @endforeach
                </div>
            </div>

        </div>

        @if(isset($permission) && $permission->add == 1)
        <!-- <a href="#" class="btn btn-primary btn-lg top-right-button" id="addnewbtn" data-toggle="modal" data-target="#templateModal">ADD NEW</a> -->
        @endif

        @if($userType != 'Parent')
        @if(!empty($permissions['addProgramPlan']) && $permissions['addProgramPlan'])

        <a href="{{ route('create.programplan', ['centerid' => $centerId]) }}" class="btn btn-outline-info"
            style="margin-left:5px;">
            Add ProgramPlan
        </a>

        @endif
        @endif
    </div>

</div>

 <hr class="mt-3"> 
  <!-- filter  -->
             <div class="col-6 d-flex justify-content-end align-items-center top-right-button-container">
     <i class="fas fa-filter mx-2" style="color:#17a2b8;"></i>
    <input 
        type="text" 
        name="filterbyCentername" 
        class="form-control border-info" 
        id="FilterbyRoomName"
        placeholder="Filter by Room name" onkeyup="filterProgramPlan()">

          <input 
        type="text" 
        name="filterbyCentername" 
        class="form-control border-info mx-2" 
        id="FilterbyCreatedBy"
        placeholder="Filter by Created by" onkeyup="filterProgramPlan()">

          <input 
        type="text" 
        name="filterbyCentername" 
        class="form-control border-info mx-2" 
        id="FilterbyStatus"
        placeholder="Filter by Status" onkeyup="filterProgramPlan()">

<!-- <input list="monthsList" 
       id="FilterbyMonth" 
       class="form-control border-info mx-2" 
       placeholder="Month" 
       oninput="updateMonthDisplay(); filterProgramPlan()" 
       onfocus="this.showPicker && this.showPicker()">

<datalist id="monthsList">
    <option value="0">January</option>
    <option value="1">February</option>
    <option value="2">March</option>
    <option value="3">April</option>
    <option value="4">May</option>
    <option value="5">June</option>
    <option value="6">July</option>
    <option value="7">August</option>
    <option value="8">September</option>
    <option value="9">October</option>
    <option value="10">November</option>
    <option value="11">December</option>
</datalist> -->



</div>
             <!-- filter ends here  -->
<!-- resources/views/program_plan_list.blade.php -->

<div class="main-container">
    <!-- Page Header -->
    <!-- <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-clipboard-list"></i>
                Program Plan Management
            </h1>
            <div class="breadcrumb-custom">
                <i class="fas fa-home"></i>
                Dashboard
                <span class="separator">|</span>
                <span>Program Plan</span>
            </div>
        </div> -->

    <!-- Main Content -->
 <div class="container-fluid px-0">
    <div class="program-plan-container">
        <div class="card-header-custom mb-3">
            <h5 class="card-header-title">
                <i class="fas fa-table"></i> Program Plans
            </h5>
        </div>
<div class="program-plan">


        <div class="row">
            @forelse ($programPlans as $index => $plan)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm rounded-3">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between">
  <h5 class="card-title mb-2">
                                {{ $getMonthName($plan->months) }} {{ $plan->years ?? '' }}
                            </h5>
<p class="text-xs mb-2">
    @if($plan->status == 'Draft')
    <span class="badge text-light rounded-pill px-3 py-2 shadow-sm cursor-auto"
          style="transition: 0.2s; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));"
          onclick="updatestatus('{{ $plan->status ?? `` }}', '{{ $plan->id }}')"
          onmouseover="this.style.opacity='0.8';"
          onmouseout="this.style.opacity='1';">
        {{ ucfirst($plan->status ?? 'Draft') }}
    </span>
    @else
  <span class="badge text-light rounded-pill px-3 py-2 shadow-sm cursor-auto"
          style="transition: 0.2s; background: linear-gradient(135deg, var(--danger-color), var(--secondary-color));"
          onclick="updatestatus('{{ $plan->status ?? `` }}', '{{ $plan->id }}')"
          onmouseover="this.style.opacity='0.8';"
          onmouseout="this.style.opacity='1';">
        {{ ucfirst($plan->status ?? 'Draft') }}
    </span>
    @endif
</p>



                            </div>
                          

                            <ul class="list-unstyled mb-3">
                                <!-- <li><strong>S No:</strong> {{ ($programPlans->currentPage() - 1) * $programPlans->perPage() + $loop->iteration }}</li> -->
                                <li><strong>Room:</strong> {{ $plan->room->name ?? '' }}</li>
                                <li><strong>Created By:</strong> {{ $plan->creator->name ?? '' }}</li>
                                <!-- <li><strong>Created:</strong> {{ \Carbon\Carbon::parse($plan->created_at)->format('d M Y / H:i') }}</li> -->
                                <li><strong>Published on :</strong> {{ \Carbon\Carbon::parse($plan->updated_at)->format('d M Y') }}</li>

                            </ul>

                            <div class="mt-auto d-flex justify-content-start gap-2 flex-wrap">
                                <a href="{{ route('print.programplan', $plan->id) }}"
                                   class="btn btn-outline-primary btn-sm" title="Print">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->userType != 'Parent')
                                    @if(!empty($permissions['editProgramPlan']) && $permissions['editProgramPlan'])
                                        <a href="{{ route('create.programplan', ['centerId' => $centerId, 'planId' => $plan->id]) }}"
                                           class="btn btn-outline-info btn-sm" title="Edit">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>
                                    @endif

                                    @if(!empty($permissions['deleteProgramPlan']) && $permissions['deleteProgramPlan'])
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm delete-program"
                                                data-id="{{ $plan->id }}" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-clipboard-list me-1"></i> No program plans found.
                    </div>
                </div>
            @endforelse
        </div>

 

              @if(!$programPlans->isEmpty())
    <div class="col-12 d-flex justify-content-center mt-4 mb-5">
        {{ $programPlans->links('vendor.pagination.bootstrap-4') }}
    </div>
    @endif

</div>
    </div>
</div>

</div>

@endsection

@push('scripts')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.1.1/js/bootstrap.min.js"></script> -->
<script>
    // Add smooth animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Add click handlers for action buttons
            const printButtons = document.querySelectorAll('.btn-print');
            const editButtons = document.querySelectorAll('.btn-edit');
            // const deleteButtons = document.querySelectorAll('.btn-delete');

            printButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Add your print logic here
                    console.log('Print button clicked');
                    // Example: window.print();
                });
            });

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Add your edit logic here
                    console.log('Edit button clicked');
                });
            });

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Add your delete logic here
                    if (confirm('Are you sure you want to delete this program plan?')) {
                        console.log('Delete confirmed');
                        // Add delete logic here
                    }
                });
            });

            // Add loading animation for buttons
            const actionButtons = document.querySelectorAll('.btn-action');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    this.disabled = true;

                    // Simulate loading (remove this in production)
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    }, 1000);
                });
            });
        });

        // Responsive table enhancement
        function makeTableResponsive() {
            const table = document.querySelector('.table');
            const headers = table.querySelectorAll('thead th');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index].textContent);
                    }
                });
            });
        }

        // Initialize responsive table
        makeTableResponsive();

        // Re-initialize on window resize
        window.addEventListener('resize', makeTableResponsive);

  function filterProgramPlan() {
    // Show loading indicator
    $('.program-plan').html(`
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading program plans...</p>
        </div>
    `);

    // ✅ Get filter input values
    var room = $('#FilterbyRoomName').val() || '';
    var createdBy = $('#FilterbyCreatedBy').val() || '';
    var month = $('#FilterbyMonth').val() || '';
    var status = $('#FilterbyStatus').val() || '';

    // ✅ Pass centerId from blade if needed
    var centerId = "{{ $centerId ?? '' }}";

    console.log('Filters:', room, createdBy,month);

    $.ajax({
        url: 'LessonPlanList/filter-program-plans', // Your route
        type: 'GET',
        data: {
            room: room,
            created_by: createdBy,
            center_id: centerId,
            month:month ,// ✅ filter by center,
            status:status
        },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Response:', response);

            // ✅ Check status
            if (response.status === true) {
                $('.program-plan').empty();

                // ✅ Check if data exists
                if (Array.isArray(response.data) && response.data.length > 0) {
                    let html = '<div class="row">';

                    response.data.forEach(function(plan) {
                        // ✅ Safe fallback values
                        let monthName = plan.month_name || '';
                        let year = plan.years || '';
                        let roomName = plan.room_name || '';
                        let creator = plan.creator_name || '';
                        let createdAt = plan.created_at_formatted || '';
                        let updatedAt = plan.updated_at_formatted || '';
                        let canEdit = plan.can_edit || false;
                        let canDelete = plan.can_delete || false;
                        let status = plan.status || '';
                        let planid = plan.id;

                        html += `
                            <div class="col-md-6 col-lg-3 mb-4">
                                <div class="card h-100 shadow-sm rounded-3">
                                    <div class="card-body d-flex flex-column">
                                

                                    
     <div class="d-flex justify-content-between">
  <h5 class="">
                                ${monthName} ${year}
                            </h5>
<p class="text-xs mb-2">
 <span class="badge text-light rounded-pill px-3 py-2 shadow-sm cursor-pointer"
      style="transition: 0.2s; background: linear-gradient(135deg, ${status === 'Draft' ? 'var(--primary-color)' : 'var(--danger-color)'}, var(--secondary-color));"
      onclick="updatestatus('${status}', '${planid}')"
      onmouseover="this.style.opacity='0.8';"
      onmouseout="this.style.opacity='1';">
    ${status}
</span>


    
</p>



                            </div>




                                        
                                        <ul class="list-unstyled mb-3">
                                            <li><strong>Room:</strong> ${roomName}</li>
                                            <li><strong>Created By:</strong> ${creator}</li>
                                           <!--- <li><strong>Created:</strong> ${createdAt}</li> -----> 
                                            <li><strong>Published on :</strong> ${updatedAt}</li>
                                        </ul>
                                        
                                        <div class="mt-auto d-flex justify-content-start gap-2 flex-wrap">
                                            <a href="/print/programplan/${plan.id}" 
                                               class="btn btn-outline-primary btn-sm" title="Print">
                                                <i class="fas fa-print"></i>
                                            </a>

                                            ${canEdit ? `
                                                <a href="programPlan/create?centerId=${centerId}&planId=${plan.id}"
                                                   class="btn btn-outline-info btn-sm" title="Edit">
                                                    <i class="fas fa-pen-to-square"></i>
                                                </a>` : ''}

                                            ${canDelete ? `
                                                <button type="button"
                                                        class="btn btn-outline-danger btn-sm delete-program"
                                                        data-id="${plan.id}" title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    html += '</div>';
                    $('.program-plan').html(html);

                } else {
                    // ✅ No results
                    $('.program-plan').html(`
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-clipboard-list me-1"></i> No program plans found matching your criteria.
                                </div>
                            </div>
                        </div>
                    `);
                }
            } else {
                // ✅ API returned error
                $('.program-plan').html(`
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i> 
                                ${response.message || 'Error loading program plans. Please try again.'}
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('.program-plan').html(`
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i> 
                            Error loading program plans. Please try again.
                        </div>
                    </div>
                </div>
            `);
        }
    });
}

</script>

<script>
    $(document).ready(function() {
    // Delete program plan
    $(document).on('click', '.delete-program', function() {
        var programId = $(this).data('id');
        var row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6DAFE0',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                  const csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{route('LessonPlanList.deletedataofprogramplan') }}",
                    type: 'POST',
                    data: {
                        program_id: programId,

                    },
                     headers: {
            'X-CSRF-TOKEN': csrfToken
        },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                          Swal.fire(
    'Deleted!',
    response.message,
    'success'
).then(() => {
    location.reload();
});

                            // Remove the row from the table
                            row.fadeOut(400, function() {
                                $(this).remove();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Something went wrong with the server. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});


function updateMonthDisplay() {
    const monthInput = document.getElementById('FilterbyMonth');
    const value = monthInput.value.trim();

    const months = [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December'
    ];

    // If input is empty, just leave it empty (do nothing)
    if (value === '') {
        return;
    }

    // Only convert if it's a valid number
    if (!isNaN(value) && value >= 0 && value <= 11) {
        monthInput.value = months[parseInt(value)];
    }
}

function updatestatus(currentStatus, planid) {
    Swal.fire({
        title: "Change Plan Status",
        text: "Select the new status for the plan:",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Published",
        cancelButtonText: "Draft",
        reverseButtons: true,
            customClass: {
        confirmButton: 'published-btn',
        cancelButton: 'draft-btn'
    }
    }).then((result) => {
        let newStatus = null;

        if (result.isConfirmed) {
            newStatus = 'Published';
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            newStatus = 'Draft';
        }

        if (newStatus) {
            // Send AJAX with selected status
            $.ajax({
                url: '/update-program-plan-status',
                dataType: 'json',
                type: 'post',
                data: {
                    status: newStatus,
                    planid: planid
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    Swal.fire({
                        title: "Updating...",
                        text: "Please wait",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.close();

                    if (response.status === true) {
                        Swal.fire({
                            title: "Updated!",
                            text: "Program plan status updated to " + newStatus + ".",
                            icon: "success",
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error!", response.message || "Failed to update status.", "error");
                    }
                },
                error: function (xhr, error, status) {
                    Swal.close();
                    Swal.fire("Error!", "Something went wrong. Please try again.", "error");
                }
            });
        }
    });
}



</script>

@endpush

@include('layout.footer')
