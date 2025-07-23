@extends('layout.master')
@section('title', 'Announcements')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<style>
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
        .hover-shadow-lg:hover {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
            transform: translateY(-2px);
        }
        
        .transition-all {
            transition: all 0.3s ease;
        }
        
        .card-title {
            line-height: 1.3;
            height: 2.6em;
            overflow: hidden;
        }

        .search-highlight {
            background-color: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
        }

        .filter-badge {
            background-color: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }
        
        @media (max-width: 768px) {
            .col-md-6 {
                margin-bottom: 1rem;
            }
            
            #searchFilters .row > div {
                margin-bottom: 0.5rem;
            }
        }

        .collapse.show {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }
            to {
                opacity: 1;
                max-height: 500px;
            }
        }
    </style>
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .btn {
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .badge {
        transition: all 0.2s ease;
    }

    img {
        transition: all 0.2s ease;
    }

    img:hover {
        transform: scale(1.1);
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
    }

    .border-start {
        border-left-width: 4px !important;
    }

    .text-sm small {
        font-size: 0.875rem;
    }

    /* Custom scrollbar for better mobile experience */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>


<style>
    main {
        padding-block: 4em;
        padding-inline: 2em;
    }

    @media screen and (max-width: 600px) {
        main {

            padding-inline: 0;
        }
    }
</style>
@endsection
@section('content')
@if (session('msg'))
<script>
    $(document).ready(function() {
            Swal.fire({
                title: 'Success!',
                text: "{{ session('msg') }}",
                icon: 'success'
            });
        });
</script>
@endif
<hr>
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

        @if(Auth::user()->userType != 'Parent')
        @if(!empty($permissions['addAnnouncement']) && $permissions['addAnnouncement'])

        <a href="{{ route('announcements.create', ['centerid' => $selectedCenter ?? $centers->first()->id]) }}"
            class="btn btn-outline-info btn-lg">ADD NEW</a>
        @endif
        @endif
    </div>

</div>

<main class="py-4">
    <div class="container-fluid px-3 px-md-4">
        @if($records->isEmpty())
        <!-- Empty State -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-bullhorn fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No Announcements Found</h4>
                        <p class="text-muted mb-4">You don't have any announcement data yet. Get started by creating
                            your first announcement.</p>
                        <a href="" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-home me-2"></i>Go Back Home
                        </a>
                    </div>
                </div>
            </div>
<<<<<<< HEAD
        @else
            <!-- Search & Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0">
                                    <i class="fas fa-bullhorn me-2"></i>Announcements List
                                </h5>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="badge bg-white text-primary fs-6">
                                        {{ $records->total() ?? count($records) }} Total
                                    </div>
                                    <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#searchFilters" aria-expanded="false">
                                        <i class="fas fa-filter me-1"></i>Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Search and Filters -->
                        <div class="collapse" id="searchFilters">
                            <div class="card-body bg-light">
                                <form method="GET" action="{{ request()->url() }}" id="searchForm">
                                    <div class="row g-3">
                                        <!-- Text Search -->
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-search me-1"></i>Search
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="search" 
                                                   value="{{ request('search') }}" 
                                                   placeholder="Search title, creator..."
                                                   id="searchInput">
                                        </div>

                                        <!-- Status Filter -->
                                        <div class="col-12 col-md-2">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-flag me-1"></i>Status
                                            </label>
                                            <select class="form-select" name="status" id="statusFilter">
                                                <option value="">All Status</option>
                                                <option value="Sent" {{ request('status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                                            </select>
                                        </div>

                                        <!-- Creator Filter -->
                                        <div class="col-12 col-md-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-user me-1"></i>Created By
                                            </label>
                                            <select class="form-select" name="creator" id="creatorFilter">
                                                <option value="">All Creators</option>
                                                @if(isset($creators))
                                                    @foreach($creators as $creator)
                                                        <option value="{{ $creator }}" {{ request('creator') == $creator ? 'selected' : '' }}>
                                                            {{ ucfirst($creator) }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <!-- Date Range -->
                                        <div class="col-12 col-md-3">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-calendar me-1"></i>Date Range
                                            </label>
                                            <div class="row g-1">
                                                <div class="col-6">
                                                    <input type="date" 
                                                           class="form-control form-control-sm" 
                                                           name="date_from" 
                                                           value="{{ request('date_from') }}"
                                                           placeholder="From">
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" 
                                                           class="form-control form-control-sm" 
                                                           name="date_to" 
                                                           value="{{ request('date_to') }}"
                                                           placeholder="To">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Filter Buttons -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" id="clearFilters">
                                                    <i class="fas fa-times me-1"></i>Clear
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-search me-1"></i>Apply Filters
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements Cards -->
            <div class="row g-3">
                @forelse($records as $announcement)
                    @php $media = json_decode($announcement->announcementMedia, true); @endphp
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                            <!-- Card Header with Status Badge -->
                            <div class="card-header bg-light border-0 pb-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="badge  text-dark small">
                                        <!-- {{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }} -->
                                          notification
                                    </span>
                                    <span class="text-white badge fs-6 {{ $announcement->status == 'Sent' ? 'bg-success' : ($announcement->status == 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        <i class="fas {{ $announcement->status == 'Sent' ? 'fa-check' : ($announcement->status == 'Pending' ? 'fa-clock' : 'fa-times') }} me-1"></i>
                                        {{ ucfirst($announcement->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Media Section -->
                            @if (!empty($media) && is_array($media))
                                <div class="position-relative">
                                    @php $firstMedia = $media[0]; $extension = strtolower(pathinfo($firstMedia, PATHINFO_EXTENSION)); $fileUrl = asset('assets/media/' . $firstMedia); @endphp
                                    
                                    @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ $fileUrl }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="Announcement Image">
                                    @elseif ($extension === 'pdf')
                                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 180px;">
                                            <a href="{{ $fileUrl }}" target="_blank" title="View PDF" class="text-decoration-none">
                                                <img src="{{ asset('svg/pdf-icon.svg') }}" style="width: 80px; height: 80px;" alt="PDF">
                                                <div class="text-center mt-2 text-muted">PDF Document</div>
                                            </a>
                                        </div>
                                    @endif

                                    @if(count($media) > 1)
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75">
                                                <i class="fas fa-images me-1"></i>{{ count($media) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height: 180px;">
                                    <div class="text-center">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <div>No Media</div>
                                    </div>
                                </div>
                            @endif

                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                <!-- Title -->
                                <h6 class="card-title fw-bold mb-3 text-truncate" title="{{ $announcement->title }}">
                                    {{ ucfirst($announcement->title) }}
                                </h6>

                                <!-- Created By -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        <small class="text-white fw-bold">{{ strtoupper(substr($announcement->createdBy, 0, 1)) }}</small>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Created by</small>
                                        <span class="small fw-semibold">{{ ucfirst($announcement->createdBy) }}</span>
                                    </div>
                                </div>

                                <!-- Event Date -->
                                <div class="mb-3">
                                    <small class="text-muted d-block">Event Date</small>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($announcement->eventDate)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($announcement->eventDate)->diffForHumans() }}</small>
                                </div>

                                <!-- Actions -->
                                <div class="mt-auto">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- View -->
                                        <a href="{{ route('announcements.view', $announcement->id) }}" class="  btn btn-outline-success btn-sm" title="">
                                            <i class="fas fa-eye me-1"></i>
                                        </a>

                                        <!-- Edit -->
                                        @if($permissions && $permissions->updateAnnouncement == 1)
                                            <a href="{{ route('announcements.create', $announcement->id) }}" class=" btn btn-outline-info btn-sm " title="">
                                                <i class="fas fa-edit me-1"></i>
                                            </a>
                                        @endif

                                        <!-- Delete -->
                                        @if($permissions && $permissions->deleteAnnouncement == 1)
                                            <form action="{{ route('announcements.delete') }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="announcementid" value="{{ $announcement->id }}">
                                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn" title="">
                                                    <i class="fas fa-trash-alt me-1"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <h5>No announcements found</h5>
                                <p class="mb-0">Create your first announcement to get started.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
        @endif

    <!-- Pagination -->
  
    </div>
     
   

      @if(!$records->isEmpty())
    <div class="col-12 d-flex justify-content-center mt-4">
        {{ $records->links('vendor.pagination.bootstrap-4') }}
    </div>
    @endif
    </main>



@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this announcement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if user confirms
                    button.closest('form').submit();
                }
            });
        });
    });
});
</script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const searchForm = document.getElementById('searchForm');

            // Real-time search (optional - can be enabled for instant filtering)
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    // Uncomment the line below for real-time search
                    // searchForm.submit();
                }, 500);
            });

            // Clear all filters
            clearFiltersBtn.addEventListener('click', function() {
                // Clear all form inputs
                document.querySelector('input[name="search"]').value = '';
                document.querySelector('select[name="status"]').selectedIndex = 0;
                document.querySelector('select[name="creator"]').selectedIndex = 0;
                document.querySelector('input[name="date_from"]').value = '';
                document.querySelector('input[name="date_to"]').value = '';
                
                // Submit form to clear filters
                searchForm.submit();
            });

            // Show active filters count
            function updateFilterCount() {
                const activeFilters = [];
                const searchValue = document.querySelector('input[name="search"]').value;
                const statusValue = document.querySelector('select[name="status"]').value;
                const creatorValue = document.querySelector('select[name="creator"]').value;
                const dateFromValue = document.querySelector('input[name="date_from"]').value;
                const dateToValue = document.querySelector('input[name="date_to"]').value;

                if (searchValue) activeFilters.push('search');
                if (statusValue) activeFilters.push('status');
                if (creatorValue) activeFilters.push('creator');
                if (dateFromValue || dateToValue) activeFilters.push('date');

                const filterButton = document.querySelector('[data-bs-target="#searchFilters"]');
                if (activeFilters.length > 0) {
                    filterButton.innerHTML = `<i class="fas fa-filter me-1"></i>Filters (${activeFilters.length})`;
                    filterButton.classList.add('btn-warning');
                    filterButton.classList.remove('btn-outline-light');
                } else {
                    filterButton.innerHTML = '<i class="fas fa-filter me-1"></i>Filters';
                    filterButton.classList.remove('btn-warning');
                    filterButton.classList.add('btn-outline-light');
                }
            }

            // Update filter count on page load
            updateFilterCount();

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K to focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
                
                // Escape to clear search
                if (e.key === 'Escape' && document.activeElement === searchInput) {
                    searchInput.value = '';
                }
            });

            // Auto-expand filters if any filter is active
            const hasActiveFilters = '{{ request()->hasAny(["search", "status", "creator", "date_from", "date_to"]) ? "true" : "false" }}' === 'true';
            if (hasActiveFilters) {
                const filtersCollapse = document.getElementById('searchFilters');
                const bsCollapse = new bootstrap.Collapse(filtersCollapse, {
                    show: true
                });
            }
        });
    </script>
@endpush
@include('layout.footer')
