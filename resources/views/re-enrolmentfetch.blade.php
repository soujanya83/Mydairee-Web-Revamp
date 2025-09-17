@extends('layout.master')
@section('title', 'Re-Enrollment')
@section('parentPageTitle', 'Dashboard')
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
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
        
        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2c6371 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .main-content {
            background-color: #ffffff;
            min-height: 100vh;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                z-index: 1000;
                transition: left 0.3s ease;
            }
            
            .sidebar.show {
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
@section('content')

<body>
    <!-- Sidebar -->
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
                            <button class="btn active" id="cardView" onclick="toggleView('cards')">
                                <i class="bi bi-grid-3x2-gap me-1"></i> Cards
                            </button>
                            <!-- <button class="btn" id="tableView" onclick="toggleView('table')">
                                <i class="bi bi-table me-1"></i> Table
                            </button> -->
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class=" stats-card info">
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
                            <div class="card stats-card danger">
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
                    <div id="cardsView">
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
                                            <button class="btn btn-outline-success btn-sm" onclick="editEnrollment({{ $enrollment->id }})">
                                                <i class="bi bi-pencil me-1"></i> Edit
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="sendEmail('{{ $enrollment->parent_email }}')">
                                                <i class="bi bi-envelope me-1"></i> Email
                                            </button>
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
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reEnrolments->links() }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Table View -->
                    <div id="tableView" style="display: none;">
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
                                                    <span class="fw-bold">#{{ $enrollment->id }}</span>
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
                                                        <span class="badge bg-info text-dark">
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
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#" onclick="viewDetails({{ $enrollment->id }})">
                                                                <i class="bi bi-eye me-2"></i>View Details
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="editEnrollment({{ $enrollment->id }})">
                                                                <i class="bi bi-pencil me-2"></i>Edit
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="mailto:{{ $enrollment->parent_email }}">
                                                                <i class="bi bi-envelope me-2"></i>Send Email
                                                            </a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteEnrollment({{ $enrollment->id }})">
                                                                <i class="bi bi-trash me-2"></i>Delete
                                                            </a></li>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printDetails()">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

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
            fetch(`/admin/re-enrolments/${enrollmentId}`)
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
            window.print();
        }
        
        // Search and filter functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            // Implement search functionality
            console.log('Search:', e.target.value);
        });
        
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
@include('layout.footer')
    @stop
