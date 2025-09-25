@extends('layout.master')
@section('title', 'Re-Enrollment')
@section('parentPageTitle', 'Dashboard')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #3a7c8c;
            --secondary-color: #f8f9fa;
            --accent-color: #e9c46a;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar2 {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2c6371 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar2 .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }

        .sidebar2 .nav-link:hover,
        .sidebar2 .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .main-content {
            background-color: #ffffff;
            min-height: 100vh;
        }

        .stats-card {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            border: none;
            border-radius: 15px;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stats-card.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #fd7e14 100%);
        }

        .stats-card.danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #e91e63 100%);
        }

        .enrollment-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .enrollment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .enrollment-card .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2c6371 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            border: none;
        }

        .badge-days {
            background-color: var(--accent-color);
            color: #333;
            font-weight: 600;
            padding: 0.4em 0.8em;
            border-radius: 20px;
        }

        .badge-session {
            background-color: var(--primary-color);
            color: white;
        }

        .badge-kinder {
            background-color: var(--success-color);
            color: white;
        }

        .view-toggle {
            background-color: white;
            border-radius: 25px;
            padding: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .view-toggle .btn {
            border-radius: 20px;
            border: none;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .view-toggle .btn.active {
            background-color: var(--primary-color);
            color: white;
        }

        .table-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .table-card .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px 12px 0 0 !important;
        }

        .custom-table {
            border-radius: 0 0 12px 12px;
        }

        .custom-table thead th {
            background-color: var(--secondary-color);
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }

        .custom-table tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .custom-table tbody tr:hover {
            background-color: rgba(58, 124, 140, 0.05);
        }

        .action-buttons .btn {
            border-radius: 20px;
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
            margin: 0.1rem;
        }

        .search-filter-bar {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .sidebar2 {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar2.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .enrollment-card {
                margin-bottom: 1rem;
            }
        }
    </style>

<style>
.max-height-400 {
    max-height: 400px;
}

.parent-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-bottom: 0.5rem;
}

.parent-item:hover {
    background-color: #f8f9fa;
    border-color: var(--primary-color);
}

.parent-item.selected {
    background-color: rgba(58, 124, 140, 0.1);
    border-color: var(--primary-color);
}

.parent-avatar {
    width: 40px;
    height: 40px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.pagination {
    display: flex;
    justify-content: center;
    padding: 0;
    list-style: none;
}

.page-item {
    margin: 0 3px;
}

.page-item .page-link {
    color: #007bff;
    border-radius: 8px;
    padding: 6px 12px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

.page-item .page-link:hover {
    background-color: #e9ecef;
    color: #0056b3;
}



</style>

@section('content')

<body>
    <!-- sidebar2 -->
    <div class="container-fluid">
        <div class="row">


            <!-- Main Content -->
            <div class="col-md-12 col-lg-12 p-0">
                <div class="main-content p-4">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="mb-1">Re-Enrollment Dashboard 2026</h2>
                            <p class="text-muted">Manage and view all re-enrollment submissions</p>
                        </div>


                        <div class="view-toggle">

                        <a href="{{ route('re-enrollment.form') }}" class="btn btn-primary me-2">
                            <i class="fa-solid fa-file-lines me-1"></i> Form
                        </a>

                        <button class="btn btn-outline-secondary" id="sendEmail" data-bs-toggle="modal" data-bs-target="#parentSelectModal">
                                <i class="fa-solid fa-envelope me-1"></i> Send Email
                            </button>
                            <button class="btn active" id="tableViewBtn" onclick="toggleView('table')">
                                <i class="bi bi-table me-1"></i> Table
                            </button>
                            <button class="btn" id="cardsViewBtn" onclick="toggleView('cards')">
                                <i class="bi bi-grid me-1"></i> Cards
                            </button>
                        </div>

                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class=" stats-card" style="background-color:#989494;">
                                <div class="card-body text-center">
                                    <i class="bi bi-people-fill fs-2 mb-2"></i>
                                    <h3 class="mb-0">{{ $totalEnrollments ?? '0' }}</h3>
                                    <p class="mb-0">Total Submissions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card success">
                                <div class="card-body text-center">
                                    <i class="bi bi-check-circle-fill fs-2 mb-2"></i>
                                    <h3 class="mb-0">{{ $completedEnrollments ?? '0' }}</h3>
                                    <p class="mb-0">Processed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card warning">
                                <div class="card-body text-center">
                                    <i class="bi bi-clock-fill fs-2 mb-2"></i>
                                    <h3 class="mb-0">{{ $pendingEnrollments ?? '0' }}</h3>
                                    <p class="mb-0">Pending Review</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stats-card " style="background-color:#149898;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-week fs-2 mb-2"></i>
                                    <h3 class="mb-0">{{ $thisWeekEnrollments ?? '0' }}</h3>
                                    <p class="mb-0">This Week</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter Bar -->
                    <div class="search-filter-bar">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Search by child name or parent email" id="searchInput">
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <select class="form-select" id="sessionFilter">
                                    <option value="">All Sessions</option>
                                    <option value="9_hours">9 Hours</option>
                                    <option value="10_hours_8_6">10 Hours (8-6)</option>
                                    <option value="10_hours_8_30_6_30">10 Hours (8:30-6:30)</option>
                                    <option value="full_day">Full Day</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <select class="form-select" id="kinderFilter">
                                    <option value="">All Kinder</option>
                                    <option value="3_year_old">3 Year Old</option>
                                    <option value="4_year_old">4 Year Old</option>
                                    <option value="unfunded">Unfunded</option>
                                    <option value="not_attending">Not Attending</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
                            </div>
                            <div class="col-md-2 mb-3">
                                <!-- <button class="btn btn-outline-primary w-100" onclick="exportData()">
                                    <i class="bi bi-download me-1"></i> Export
                                </button> -->
                            </div>
                        </div>
                    </div>

                    <!-- Cards View -->
                    <div id="cardsView" style="display: none;">
                        <div class="row">
                            @forelse($reEnrolments ?? [] as $enrollment)
                            <div class="col-lg-6 col-xl-4">
                                <div class=" enrollment-card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <i class="bi bi-person-circle me-2"></i>
                                                {{ $enrollment->child_name }}
                                            </h6>
                                            <!-- <span class="badge bg-light text-dark">
                                                ID: #{{ $enrollment->id }}
                                            </span> -->
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">Date of Birth</small>
                                                <div class="fw-bold">{{ $enrollment->child_dob->format('d M Y') }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Submitted</small>
                                                <div class="fw-bold">{{ $enrollment->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">Parent Email</small>
                                            <div class="fw-bold">
                                                <i class="bi bi-envelope me-1"></i>
                                                {{ $enrollment->parent_email }}
                                            </div>
                                        </div>

                                        @if($enrollment->current_days)
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Current Days (2025)</small>
                                            @foreach($enrollment->current_days as $day)
                                                <span class="badge badge-days me-1">{{ ucfirst($day) }}</span>
                                            @endforeach
                                        </div>
                                        @endif

                                        @if($enrollment->requested_days)
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Requested Days (2026)</small>
                                            @foreach($enrollment->requested_days as $day)
                                                <span class="badge badge-days me-1">{{ ucfirst($day) }}</span>
                                            @endforeach
                                        </div>
                                        @endif

                                        @if($enrollment->session_option)
                                        <div class="mb-3">
                                            <span class="badge badge-session">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $enrollment->session_option_display }}
                                            </span>
                                        </div>
                                        @endif

                                        @if($enrollment->kinder_program !== 'not_attending')
                                        <div class="mb-3">
                                            <span class="badge badge-kinder">
                                                <i class="bi bi-mortarboard me-1"></i>
                                                {{ $enrollment->kinder_program_display }}
                                            </span>
                                        </div>
                                        @endif

                                        @if($enrollment->holiday_dates)
                                        <div class="mb-3">
                                            <small class="text-muted">Holiday Plans</small>
                                            <div class="text-truncate">{{ $enrollment->holiday_dates }}</div>
                                        </div>
                                        @endif

                                        @if($enrollment->finishing_child_name)
                                        <div class="alert alert-warning py-2 px-3 mb-3">
                                            <small>
                                                <i class="bi bi-info-circle me-1"></i>
                                                <strong>Finishing:</strong> {{ $enrollment->finishing_child_name }}
                                                @if($enrollment->last_day)
                                                    ({{ $enrollment->last_day->format('d M Y') }})
                                                @endif
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="action-buttons d-flex justify-content-between">
                                            <button class="btn btn-outline-primary btn-sm" onclick="viewDetails({{ $enrollment->id }})">
                                                <i class="bi bi-eye me-1"></i> View
                                            </button>
                                            <!-- <button class="btn btn-outline-success btn-sm" onclick="editEnrollment({{ $enrollment->id }})">
                                                <i class="bi bi-pencil me-1"></i> Edit
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="sendEmail('{{ $enrollment->parent_email }}')">
                                                <i class="bi bi-envelope me-1"></i> Email
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <h4 class="text-muted mt-3">No Re-enrollments Found</h4>
                                    <p class="text-muted">No re-enrollment submissions have been received yet.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <!-- Pagination for Cards -->
                      @if(isset($reEnrolments) && $reEnrolments->hasPages())
    <div class="d-flex justify-content-center mt-1 mb-5">
        {{ $reEnrolments->links('pagination::bootstrap-5') }}
    </div>
@endif

                    </div>

                    <style>
                        table.custom-table th,
table.custom-table td {
  text-align: center;
  vertical-align: middle; /* optional: centers vertically if rows are tall */
}

                        </style>
                    <!-- Table View -->
                    <div id="tableView">
                        <div class="card table-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-table me-2"></i>
                                    Re-Enrollment Submissions
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0" id="enrollmentTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Child Name</th>
                                                <th>Parent Email</th>
                                                <th>Current Days</th>
                                                <th>Requested Days</th>
                                                <th>Session</th>
                                                <th>Kinder</th>
                                                <th>Submitted</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($reEnrolments ?? [] as $enrollment)
                                            <tr>
                                                <td>
                                                <span class="fw-bold">#{{ $loop->iteration }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                            {{ strtoupper(substr($enrollment->child_name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $enrollment->child_name }}</div>
                                                            <small class="text-muted">{{ $enrollment->child_dob->format('d M Y') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="mailto:{{ $enrollment->parent_email }}" class="text-decoration-none">
                                                        {{ $enrollment->parent_email }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($enrollment->current_days)
                                                        @foreach($enrollment->current_days as $day)
                                                            <span class="badge bg-secondary me-1">{{ ucfirst(substr($day, 0, 3)) }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($enrollment->requested_days)
                                                        @foreach($enrollment->requested_days as $day)
                                                            <span class="badge bg-primary me-1">{{ ucfirst(substr($day, 0, 3)) }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($enrollment->session_option)
                                                        <span class="badge bg-info text-dark" style="border:0px;color:white !important;">
                                                            {{ str_replace('_', ' ', $enrollment->session_option) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($enrollment->kinder_program !== 'not_attending')
                                                        <span class="badge bg-success">
                                                            {{ str_replace('_', ' ', $enrollment->kinder_program) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">None</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="text-nowrap">
                                                        {{ $enrollment->created_at->format('d M Y') }}
                                                        <br>
                                                        <small class="text-muted">{{ $enrollment->created_at->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td>
    <div class="dropdown position-relative dropup">
        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,5">
            <i class="bi bi-three-dots"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" style="height:36px;" data-bs-display="static">
            <li>
                <a class="dropdown-item" href="#" onclick="viewDetails({{ $enrollment->id }})">
                    <i class="bi bi-eye me-2"></i>View Details
                </a>
            </li>
            <!--
            <li>
                <a class="dropdown-item" href="#" onclick="editEnrollment({{ $enrollment->id }})">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="mailto:{{ $enrollment->parent_email }}">
                    <i class="bi bi-envelope me-2"></i>Send Email
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger" href="#" onclick="deleteEnrollment({{ $enrollment->id }})">
                    <i class="bi bi-trash me-2"></i>Delete
                </a>
            </li>
            -->
        </ul>
    </div>
</td>


                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-5">
                                                    <i class="bi bi-inbox display-6 text-muted"></i>
                                                    <p class="text-muted mt-2">No re-enrollment submissions found</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary-color); color: white;">
                    <h5 class="modal-title">
                        <i class="bi bi-person-circle me-2"></i>
                        Re-Enrollment Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printDetails()">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Parent Selection Modal -->
<div class="modal fade" id="parentSelectModal" tabindex="-1" aria-labelledby="parentSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--primary-color); color: white;">
                <h5 class="modal-title" id="parentSelectModalLabel">
                    <i class="fa-solid fa-users me-2"></i>
                    Send Re-Enrollment Link to Parents
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Bar -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="parentSearch" placeholder="Search parents by name or email...">
                    </div>
                </div>

                <!-- Select All Checkbox -->
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllParents">
                        <label class="form-check-label fw-bold" for="selectAllParents">
                            Select All Parents
                        </label>
                    </div>
                    <hr>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading parents...</p>
                </div>

                <!-- Parents List -->
                <div id="parentsList" class="max-height-400 overflow-auto">
                    <!-- Dynamic content will be loaded here -->
                </div>

                <!-- Selected Count -->
                <div class="mt-3 p-2 bg-light rounded">
                    <small class="text-muted">
                        <i class="fa-solid fa-info-circle me-1"></i>
                        Selected: <span id="selectedCount" class="fw-bold text-primary">0</span> parents
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="sendEmailBtn" disabled>
                    <i class="fa-solid fa-paper-plane me-1"></i>
                    Send Emails (<span id="selectedCountBtn">0</span>)
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Add SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>






    <script>
        // Toggle between card and table view
        function toggleView(viewType) {
            const cardsView = document.getElementById('cardsView');
            const tableView = document.getElementById('tableView');
            const cardBtn = document.getElementById('cardView');
            const tableBtn = document.getElementById('tableView');

            if (viewType === 'cards') {
                cardsView.style.display = 'block';
                tableView.style.display = 'none';
                cardBtn.classList.add('active');
                tableBtn.classList.remove('active');
            } else {
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
                cardBtn.classList.remove('active');
                tableBtn.classList.add('active');

                // Initialize DataTable if not already initialized
                if (!$.fn.DataTable.isDataTable('#enrollmentTable')) {
                    $('#enrollmentTable').DataTable({
                        responsive: true,
                        pageLength: 25,
                        order: [[0, 'desc']],
                        columnDefs: [
                            { orderable: false, targets: 8 }
                        ]
                    });
                }
            }
        }

        // View enrollment details
        function viewDetails(enrollmentId) {
            // You would typically fetch this data via AJAX
            fetch(`/re-enrolments/${enrollmentId}/details`)
                .then(response => response.json())

                .then(data => {
                    document.getElementById('modalContent').innerHTML = generateDetailHTML(data);
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading enrollment details');
                });
        }

        // Generate detail HTML for modal
        function generateDetailHTML(enrollment) {
            return `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Child Information</h6>
                        <table class="table table-borderless">
                            <tr><td><strong>Name:</strong></td><td>${enrollment.child_name}</td></tr>
                            <tr><td><strong>Date of Birth:</strong></td><td>${enrollment.child_dob}</td></tr>
                            <tr><td><strong>Parent Email:</strong></td><td>${enrollment.parent_email}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Enrollment Details</h6>
                        <table class="table table-borderless">
                            <tr><td><strong>Session:</strong></td><td>${enrollment.session_option || 'Not specified'}</td></tr>
                            <tr><td><strong>Kinder:</strong></td><td>${enrollment.kinder_program || 'Not attending'}</td></tr>
                            <tr><td><strong>Submitted:</strong></td><td>${enrollment.created_at}</td></tr>
                        </table>
                    </div>
                </div>
                ${enrollment.current_days ? `
                    <h6 class="text-primary">Current Days (2025)</h6>
                    <p>${enrollment.current_days.join(', ')}</p>
                ` : ''}
                ${enrollment.requested_days ? `
                    <h6 class="text-primary">Requested Days (2026)</h6>
                    <p>${enrollment.requested_days.join(', ')}</p>
                ` : ''}
                ${enrollment.holiday_dates ? `
                    <h6 class="text-primary">Holiday Plans</h6>
                    <p>${enrollment.holiday_dates}</p>
                ` : ''}
                ${enrollment.finishing_child_name ? `
                    <div class="alert alert-warning">
                        <strong>Finishing Up:</strong> ${enrollment.finishing_child_name}
                        ${enrollment.last_day ? ` - Last day: ${enrollment.last_day}` : ''}
                    </div>
                ` : ''}
            `;
        }

        // Additional functions
        function editEnrollment(id) {
            window.location.href = `/admin/re-enrolments/${id}/edit`;
        }

        function sendEmail(email) {
            window.location.href = `mailto:${email}`;
        }

        function deleteEnrollment(id) {
            if (confirm('Are you sure you want to delete this enrollment?')) {
                // Implement delete functionality
                console.log('Delete enrollment:', id);
            }
        }

        function exportData() {
            window.location.href = '/admin/re-enrolments/export/csv';
        }

        function printDetails() {
    const modalContent = document.getElementById('modalContent').innerHTML;

    const printWindow = window.open('', '', 'width=900,height=700');

    printWindow.document.write(`
        <html>
            <head>
                <title>Re-Enrollment Details</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                        padding: 25px 40px;
                        background: #fff;
                        color: #000;
                        font-size: 14px;
                        line-height: 1.5;
                    }

                    /* Header */
                    .print-header {
                        text-align: center;
                        margin-bottom: 25px;
                    }
                    .print-header h2 {
                        color: #007bff;
                        margin: 0;
                    }
                    .print-header small {
                        color: #666;
                    }

                    /* Section titles */
                    .section-title {
                        color: #007bff;
                        font-weight: 600;
                        margin: 20px 0 8px 0;
                        font-size: 15px;
                        border-bottom: 1px solid #ccc;
                        padding-bottom: 4px;
                    }

                    /* Table styling */
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    table th, table td {
                        padding: 8px 10px;
                        border: 1px solid #ccc;
                        text-align: left;
                        font-size: 13px;
                    }
                    table th {
                        background: #f1f5f9;
                        font-weight: 600;
                        color: #333;
                    }

                    /* Alerts / Notes */
                    .alert {
                        margin-top: 15px;
                        padding: 8px 12px;
                        border: 1px solid #ddd;
                        background: #f9f9f9;
                        font-size: 13px;
                    }

                    /* Print adjustments */
                    @media print {
                        body {
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        table th {
                            background-color: #e9ecef !important;
                            -webkit-print-color-adjust: exact;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h2>Re-Enrollment Details</h2>
                    <small>Generated on ${new Date().toLocaleDateString()}</small>
                </div>
                ${modalContent}
            </body>
        </html>
    `);

    printWindow.document.close();

    printWindow.focus();

    // Trigger print after DOM is ready
    printWindow.onload = () => {
        printWindow.print();
        // Close only AFTER print finishes
        printWindow.onafterprint = () => {
            printWindow.close();
        };
    };
}




        // Search and filter functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            // Implement search functionality
            console.log('Search:', e.target.value);
        });

        // Mobile sidebar2 toggle
        function togglesidebar2() {
            document.querySelector('.sidebar2').classList.toggle('show');
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>


<!-- Add SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

<script>
let allParents = [];
let filteredParents = [];
let selectedParents = [];

// Load parents when modal opens
document.getElementById('parentSelectModal').addEventListener('show.bs.modal', function() {
    loadParents();
});

// Load parents from server
function loadParents() {
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('parentsList').innerHTML = '';

    fetch('/admin/get-parents', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingSpinner').style.display = 'none';

        if (data.success) {
            allParents = data.parents;
            filteredParents = [...allParents];
            renderParentsList();
        } else {
            showError('Failed to load parents');
        }
    })
    .catch(error => {
        document.getElementById('loadingSpinner').style.display = 'none';
        console.error('Error:', error);
        showError('Error loading parents');
    });
}

// Render parents list
function renderParentsList() {
    const parentsList = document.getElementById('parentsList');

    if (filteredParents.length === 0) {
        parentsList.innerHTML = `
            <div class="text-center py-4">
                <i class="fa-solid fa-users-slash fs-1 text-muted"></i>
                <p class="text-muted mt-2">No parents found</p>
            </div>
        `;
        return;
    }

    const parentsHTML = filteredParents.map(parent => `
        <div class="parent-item p-3" data-parent-id="${parent.id}">
            <div class="form-check d-flex align-items-center">
                <input class="form-check-input me-3" type="checkbox" value="${parent.id}"
                       id="parent_${parent.id}" onchange="toggleParentSelection(${parent.id})">
                <div class="parent-avatar me-3">
                    ${parent.name.charAt(0).toUpperCase()}
                </div>
                <div class="flex-grow-1">
                    <label class="form-check-label fw-bold" for="parent_${parent.id}">
                        ${parent.name}
                    </label>
                    <div class="text-muted small">
                        <i class="fa-solid fa-envelope me-1"></i>
                        ${parent.email}
                    </div>
                    ${parent.children_count ? `
                        <div class="text-muted small">
                            <i class="fa-solid fa-child me-1"></i>
                            ${parent.children_count} child(ren)
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `).join('');

    parentsList.innerHTML = parentsHTML;
    updateSelectedCount();
}

// Toggle parent selection
function toggleParentSelection(parentId) {
    const checkbox = document.getElementById(`parent_${parentId}`);
    const parentItem = document.querySelector(`[data-parent-id="${parentId}"]`);

    if (checkbox.checked) {
        if (!selectedParents.includes(parentId)) {
            selectedParents.push(parentId);
        }
        parentItem.classList.add('selected');
    } else {
        selectedParents = selectedParents.filter(id => id !== parentId);
        parentItem.classList.remove('selected');
    }

    updateSelectedCount();
    updateSelectAllState();
}

// Select/Deselect all parents
document.getElementById('selectAllParents').addEventListener('change', function() {
    const isChecked = this.checked;

    filteredParents.forEach(parent => {
        const checkbox = document.getElementById(`parent_${parent.id}`);
        const parentItem = document.querySelector(`[data-parent-id="${parent.id}"]`);

        if (checkbox) {
            checkbox.checked = isChecked;
            if (isChecked) {
                if (!selectedParents.includes(parent.id)) {
                    selectedParents.push(parent.id);
                }
                parentItem.classList.add('selected');
            } else {
                selectedParents = selectedParents.filter(id => id !== parent.id);
                parentItem.classList.remove('selected');
            }
        }
    });

    updateSelectedCount();
});

// Update select all checkbox state
function updateSelectAllState() {
    const selectAllCheckbox = document.getElementById('selectAllParents');
    const visibleParentIds = filteredParents.map(p => p.id);
    const selectedVisibleParents = selectedParents.filter(id => visibleParentIds.includes(id));

    if (selectedVisibleParents.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (selectedVisibleParents.length === visibleParentIds.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
    }
}

// Update selected count
function updateSelectedCount() {
    const count = selectedParents.length;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('selectedCountBtn').textContent = count;
    document.getElementById('sendEmailBtn').disabled = count === 0;

    if (count > 0) {
        document.getElementById('sendEmailBtn').classList.remove('btn-primary');
        document.getElementById('sendEmailBtn').classList.add('btn-success');
    } else {
        document.getElementById('sendEmailBtn').classList.remove('btn-success');
        document.getElementById('sendEmailBtn').classList.add('btn-primary');
    }
}

// Search functionality
document.getElementById('parentSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();

    if (searchTerm === '') {
        filteredParents = [...allParents];
    } else {
        filteredParents = allParents.filter(parent =>
            parent.name.toLowerCase().includes(searchTerm) ||
            parent.email.toLowerCase().includes(searchTerm)
        );
    }

    renderParentsList();
    updateSelectAllState();
});

// Send emails
document.getElementById('sendEmailBtn').addEventListener('click', function() {
    if (selectedParents.length === 0) {
        showWarning('Please select at least one parent');
        return;
    }

    // Confirm before sending
    Swal.fire({
        title: 'Send Re-Enrollment Links?',
        text: `Are you sure you want to send re-enrollment links to ${selectedParents.length} parent(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3a7c8c',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, Send ${selectedParents.length} Email(s)`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            sendEmails();
        }
    });
});

// Send emails function
function sendEmails() {
    // Show loading
    Swal.fire({
        title: 'Sending Emails...',
        text: 'Please wait while we send the re-enrollment links',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('/admin/send-reenrollment-emails', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            parent_ids: selectedParents
        })
    })
    .then(response => response.json())
    .then(data => {
        // Hide the modal first (simple approach)
        const modal = document.getElementById('parentSelectModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        }

        // Reset selections
        selectedParents = [];

        // Show success message
        Swal.fire({
            title: 'Success!',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Error sending emails. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}
// Reset modal state
function resetModal() {
    selectedParents = [];
    document.getElementById('parentSearch').value = '';
    document.getElementById('selectAllParents').checked = false;
    document.getElementById('selectAllParents').indeterminate = false;
    updateSelectedCount();
}

// SweetAlert helper functions
function showSuccess(message, sentCount, failedCount) {
    let htmlMessage = `
        <div class="text-start">
            <p><strong>${message}</strong></p>
            <div class="mt-3">
                <div class="d-flex justify-content-between">
                    <span><i class="fa-solid fa-check-circle text-success me-2"></i>Successfully sent:</span>
                    <span class="fw-bold text-success">${sentCount}</span>
                </div>
    `;

    if (failedCount > 0) {
        htmlMessage += `
                <div class="d-flex justify-content-between mt-1">
                    <span><i class="fa-solid fa-exclamation-triangle text-warning me-2"></i>Failed to send:</span>
                    <span class="fw-bold text-warning">${failedCount}</span>
                </div>
        `;
    }

    htmlMessage += `
            </div>
        </div>
    `;

    Swal.fire({
        title: 'Email Campaign Complete!',
        html: htmlMessage,
        icon: 'success',
        confirmButtonColor: '#3a7c8c',
        confirmButtonText: 'Great!'
    });
}

function showError(message) {
    Swal.fire({
        title: 'Error!',
        text: message,
        icon: 'error',
        confirmButtonColor: '#d33'
    });
}

function showWarning(message) {
    Swal.fire({
        title: 'Warning!',
        text: message,
        icon: 'warning',
        confirmButtonColor: '#ffc107'
    });
}
</script>


</body>
@include('layout.footer')
    @stop
