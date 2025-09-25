@extends('layout.master')
@section('title', 'Events')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<style>
    /* Published button: green success */
    .published-btn {
        background-color: #28a745 !important;
        /* green */
        color: white !important;
        border: none;
    }

    /* Draft button: red danger */
    .draft-btn {
        background-color: #ff9305ff !important;
        /* red */
        color: white !important;
        border: none;
    }


    #FilterbyTitle {
        display: none;
    }

    #FilterbyCreatedBy {
        display: none;
    }

    #StatusFilter {
        display: none;
    }

    #StatusFilter_label {
        display: none;
    }

    #Filterbydate_from_label {
        display: none;
    }

    #Filterbydate_from {
        display: none;
    }

    #Filterbydate_to_label {
        display: none;
    }

    #Filterbydate_to {
        display: none;
    }
</style>
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

        #searchFilters .row>div {
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
    /* Make all inputs uniform */
    .uniform-input {
        width: 180px;
        /* same width for all */
        height: 36px;
        /* same height */
        font-size: 0.875rem;
        margin-inline: 0.5rem;
    }

    /* Make sure labels don't misalign the row */
    .top-right-button-container label {
        line-height: 1;
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('status') == 'success' && session('msg'))
        Swal.fire({
            title: 'Success!',
            text: @json(session('msg')),
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        @endif

        @if(session('status') == 'error' && session('msg'))
        Swal.fire({
            title: 'Error!',
            text: @json(session('msg')),
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Close'
        });
        @endif
    });
</script>
@endif
<!-- <hr> -->
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

        @if(isset($permission) && $permission->add == 1 || Auth::user()->userType === 'Superadmin' || Auth::user()->admin === '1')
        <a href="{{ route('settings.public_holiday') }}" class="btn btn-outline-info btn-lg top-right-button">Public Holiday</a>
        @endif


        @if(Auth::user()->userType != 'Parent')
        @if(!empty($permissions['addAnnouncement']) && $permissions['addAnnouncement'] )

        <a href="{{ route('announcements.create', ['centerid' => $selectedCenter ?? $centers->first()->id]) }}"
            class="btn btn-outline-info btn-lg">ADD NEW</a>
        @endif
        @endif

        @if(Auth::user()->userType === 'Superadmin')
        <a href="{{ route('announcements.create', ['centerid' => $selectedCenter ?? $centers->first()->id]) }}"
            class="btn btn-outline-info btn-lg">ADD NEW</a>
        @endif
    </div>

</div>

<hr class="mt-3">

<main class="py-4">
    @if(Auth::user()->userType != "Parent")
    <div class="col-12 d-flex  align-items-end flex-wrap gap-2 top-right-button-container mb-4">

        <!-- Filter Icon -->
        <i class="fas fa-filter text-info" style="font-size: 1.2rem; position:relative; top:-8px;"></i>

        <select name="filter" id="" onchange="showfilter(this.value)" class="form-control form-control-sm border-info uniform-input">
            <option value="">Choose</option>
            <option value="title">Title</option>
            <!-- <option value="createdby">Created by</option> -->
            <option value="status">Status</option>
            <option value="date">Date</option>
        </select>

        <!-- Title Filter -->
        <input
            type="text"
            name="filterbyTitle"
            class="form-control border-info form-control-sm uniform-input"
            id="FilterbyTitle"
            placeholder="Filter by Title"
            onkeyup="filterProgramPlan()">

        <!-- Created By Filter -->
        <!-- <input
        type="text"
        name="filterbyCreatedBy"
        class="form-control border-info form-control-sm uniform-input"
        id="FilterbyCreatedBy"
        placeholder="Filter by Created by"
        onkeyup="filterProgramPlan()"> -->

        <!-- From Date -->
        <div class="d-flex flex-column Filterbydate_from">
            <label for="Filterbydate_from " id="Filterbydate_fr om_label" class="text-info small m b-1 Filterbydate_from_label">From Date</label>
            <input type="date"
                class="form-control border-info form-control-sm uniform-input"
                id="Filterbydate_from"
                name="date_from"
                value="{{ request('date_from') }}"
                onchange="filterProgramPlan()">
        </div>

        <!-- To Date -->
        <div class="d-flex flex-column Filterbydate_to">
            <label for="Filterbydate_to" id="Filterbydate_to_label" class="text-info small mb-1 Filterbydate_to_label">To Date</label>
            <input type="date"
                class="form-control border-info form-control-sm uniform-input"
                id="Filterbydate_to"
                name="date_to"
                value="{{ request('date_to') }}"
                onchange="filterProgramPlan()">
        </div>

        <!-- Status Filter -->
        <div class="d-flex flex-column statusFilter">
            <label for="statusFilter" id="statusFilter_label" class="text-info small mb-1 statusFilter_label">Status</label>
            <select class="form-control form-control-sm border-info uniform-input" name="status" id="statusFilter" onchange="filterProgramPlan()">
                <option value="">All </option>
                <option value="Sent" {{ request('status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
    </div>
    @endif


    <div class="container-fluid px-3 px-md-4">
        @if($records->isEmpty())
        <!-- Empty State -->
        <div class="row justify-content-center">

            <div class="col-12 col-md-12 col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-bullhorn fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No Event Found</h4>
                        <p class="text-muted mb-4">You don't have any Event data yet. Get started by creating
                            your first Event.</p>
                        <a href="list" class="btn btn-info btn-lg px-4">
                            <i class="fas fa-home me-2"></i>Go Back Home
                        </a>
                    </div>
                </div>
            </div>

            @else
            <!-- Search & Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white border-0" style="background-color:#17a2b8
;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="mb-0">
                                    <i class="fas fa-bullhorn me-2 mx-1"></i>Events
                                </h5>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="badge bg-white  fs-6" style="color:#17a2b8
;">
                                        {{ $records->total() ?? count($records) }} Total
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements Cards -->
            <div class="annoucement-list">


                <div class="row g-3">
                    @forelse($records as $announcement)
                    @php $media = json_decode($announcement->announcementMedia, true); @endphp
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                            <!-- Card Header with Status Badge -->
                            <div class="card-header bg-light border-0 pb-2">
                                <div class="d-flex justify-content-between align-items-start">


    @if(isset($announcement->type) && $announcement->type == "events")
        <span class="badge small text-white bg-info">
            {{ $announcement->type }}
        </span>
    @endif

      @if(isset($announcement->type) && $announcement->type == "announcement")
        <span class="badge small text-white bg-secondary">
            {{ $announcement->type }}
        </span>
    @endif



                                    <span class="text-white badge fs-6
          {{ $announcement->status == 'Sent' ? 'bg-success' : ($announcement->status == 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}"
                                        onclick="updateStatus('{{ $announcement->status }}', {{ $announcement->id }})">

                                        <i class="fas {{ $announcement->status == 'Sent' ? 'fa-check' : ($announcement->status == 'Pending' ? 'fa-clock' : 'fa-times') }} me-1"></i>
                                        {{ ucfirst($announcement->status == 'Sent' ? 'Published' : 'Draft') }}
                                    </span>

                                </div>
                            </div>

                            <!-- Media Section -->
                            @if (!empty($media) && is_array($media))
                            <div class="position-relative">
                                @php $firstMedia = $media[0]; $extension = strtolower(pathinfo($firstMedia, PATHINFO_EXTENSION)); $fileUrl = asset($firstMedia); @endphp

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
                                    <div class=" rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;background-color:#17a2b8
;">
                                        <small class="text-white fw-bold">{{ strtoupper(substr($announcement->createdBy, 0, 1)) }}</small>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Created by</small>
                                        <span class="small fw-semibold">{{ ucfirst($announcement->createdBy) }}</span>
                                    </div>
                                </div>
                                @php
                                $eventDate = \Carbon\Carbon::parse($announcement->eventDate);
                                $today = \Carbon\Carbon::today();

                                $diffDays = $today->diffInDays($eventDate, false); // false = signed difference

                                if ($diffDays > 0) {
                                $eventDateHuman = "Event in {$diffDays} day" . ($diffDays > 1 ? 's' : '');
                                } elseif ($diffDays === 0) {
                                $eventDateHuman = "Event is today";
                                } else {
                                $eventDateHuman = "Event passed " . abs($diffDays) . " day" . (abs($diffDays) > 1 ? 's' : '') . " ago";
                                }
                                @endphp
                                <!-- Event Date -->
                                <div class="mb-3">
                                    <small class="text-muted d-block">Event Date</small>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($announcement->eventDate)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $eventDateHuman }}</small>
                                    <small class="text-muted d-block">Created At</small>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($announcement->createdAt)->format('d M Y') }}</div>
                                </div>

                                <!-- Actions -->
                                <!-- Actions -->
                                <div class="mt-auto d-flex justify-content-start flex-wrap align-items-stretch">
                                    <!-- View -->
                                    <a href="{{ route('announcements.view', $announcement->id) }}"
                                        class="btn btn-outline-success btn-sm mr-2 mb-2 d-flex align-items-center justify-content-center"
                                        style="min-width: 38px; height: 38px;"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Edit -->
                                    @if($permissions && $permissions->updateAnnouncement == 1 || Auth::user()->userType == "Superadmin" || Auth::user()->admin == 1)
                                    <a href="{{ route('announcements.create', $announcement->id) }}"
                                        class="btn btn-outline-info btn-sm mr-2 mb-2 d-flex align-items-center justify-content-center"
                                        style="min-width: 38px; height: 38px;"
                                        title="Edit">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    @endif

                                    <!-- Delete -->
                                    @if($permissions && $permissions->deleteAnnouncement == 1 || Auth::user()->userType == "Superadmin" || Auth::user()->admin == 1)
                                    <form action="{{ route('announcements.delete') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="announcementid" value="{{ $announcement->id }}">
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm mr-2 mb-2 d-flex align-items-center justify-content-center delete-btn"
                                            style="min-width: 38px; height: 38px;"
                                            title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
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
                @if(!$records->isEmpty())
                <div class="col-12 d-flex justify-content-center mt-4">
                    {{ $records->links('vendor.pagination.bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>

    </div>


</main>




@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function updateStatus(currentStatus, id) {
        // alert();
        Swal.fire({
            title: "Change Annoucement Status",
            text: "Select the new status for the Annoucement:",
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
                    url: '/update-annoucement-status',
                    dataType: 'json',
                    type: 'post',
                    data: {
                        status: newStatus,
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: "Updating...",
                            text: "Please wait",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();


                            }

                        });
                    },
                    success: function(response) {
                        Swal.close();

                        if (response.status === true) {
                            Swal.fire({
                                title: "Updated!",
                                text: "Annoucment status updated to " + newStatus + ".",
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
                    error: function(xhr, error, status) {
                        Swal.close();
                        Swal.fire("Error!", "Something went wrong. Please try again.", "error");
                    }
                });
            }
        });
    }
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

    function filterProgramPlan() {
        // alert();
        var Title = $('#FilterbyTitle').val();
        // alert(Title);
        var CreatedBy = $('#FilterbyCreatedBy').val();
        var date_from = $('#Filterbydate_from').val();
        var date_to = $('#Filterbydate_to').val();
        var statusFilter = $('#statusFilter').val();

        console.log('data:', Title, CreatedBy, date_from, date_to, statusFilter);

        $.ajax({
            url: "{{ route('announcements.Filterlist') }}",
            type: "GET",
            dataType: "json",
            data: {
                title: Title,
                created_by: CreatedBy,
                date_from: date_from,
                date_to: date_to,
                status: statusFilter
            },
            beforeSend: function() {
                $('.annoucement-list').html('<div class="text-center py-5">Loading...</div>');
            },
            success: function(res) {
                if (res.status && res.records.length > 0) {
                    let html = '';

                    $.each(res.records, function(i, announcement) {
                        // --- MEDIA SECTION ---
                        let mediaHtml = '';
                        let mediaArr = [];

                        // Parse media JSON safely
                        try {
                            if (typeof announcement.announcementMedia === 'string') {
                                mediaArr = JSON.parse(announcement.announcementMedia);
                            } else if (Array.isArray(announcement.announcementMedia)) {
                                mediaArr = announcement.announcementMedia;
                            }
                        } catch (e) {
                            console.error('Invalid media JSON:', announcement.announcementMedia);
                        }

                        if (mediaArr.length > 0) {
                            let firstMedia = mediaArr[0];
                            let extension = firstMedia.split('.').pop().toLowerCase();

                            // Build file URL (assuming files stored in public/assets/media)
                            let fileUrl = '/assets/media/' + firstMedia;

                            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                                mediaHtml = `<img src="${fileUrl}" class="card-img-top" style="height:180px;object-fit:cover;" alt="Announcement Image">`;
                            } else if (extension === 'pdf') {
                                mediaHtml = `
                                <div class="d-flex align-items-center justify-content-center bg-light" style="height:180px;">
                                    <a href="${fileUrl}" target="_blank" class="text-decoration-none">
                                        <img src="/svg/pdf-icon.svg" style="width:80px;height:80px;" alt="PDF">
                                        <div class="text-center mt-2 text-muted">PDF Document</div>
                                    </a>
                                </div>`;
                            } else {
                                mediaHtml = `
                                <div class="d-flex align-items-center justify-content-center bg-light" style="height:180px;">
                                    <a href="${fileUrl}" target="_blank" class="text-decoration-none text-muted">Download File</a>
                                </div>`;
                            }

                            mediaHtml = `<div class="position-relative">${mediaHtml}</div>`;
                        } else {
                            mediaHtml = `
                            <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height:180px;">
                                <div class="text-center">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <div>No Media</div>
                                </div>
                            </div>`;
                        }

                        // --- STATUS BADGE ---
                        let statusBadgeClass =
                            announcement.status === 'Sent' ? 'bg-success' :
                            announcement.status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger';

                        let statusIcon =
                            announcement.status === 'Sent' ? 'fa-check' :
                            announcement.status === 'Pending' ? 'fa-clock' : 'fa-times';

                        let eventDate = new Date(announcement.eventDate);
                        let createdAt = new Date(announcement.createdAt);
                        let formattedDate = createdAt.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                        let today = new Date();
                        let diffTime = eventDate - today;
                        let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        let eventDateHuman = '';
                        if (diffDays > 0) {
                            eventDateHuman = `Event in ${diffDays} day${diffDays > 1 ? 's' : ''}`;
                        } else if (diffDays === 0) {
                            eventDateHuman = `Event is today`;
                        } else {
                            eventDateHuman = `Event passed ${Math.abs(diffDays)} day${Math.abs(diffDays) > 1 ? 's' : ''} ago`;
                        }

                        // --- CARD HTML ---
                        html += `
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                                <div class="card-header bg-light border-0 pb-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span class="badge text-dark small">notification</span>
                                        <span class="text-white badge fs-6 ${statusBadgeClass}">
                                            <i class="fas ${statusIcon} me-1"></i>
                                            ${announcement.status.charAt(0).toUpperCase() + announcement.status.slice(1)}
                                        </span>
                                    </div>
                                </div>

                                ${mediaHtml}

                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title fw-bold mb-3 text-truncate" title="${announcement.title}">
                                        ${announcement.title.charAt(0).toUpperCase() + announcement.title.slice(1)}
                                    </h6>

                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                             style="width:30px;height:30px;background-color:#17a2b8;">
                                            <small class="text-white fw-bold">${announcement.creatorName.charAt(0).toUpperCase()}</small>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Created by</small>
                                            <span class="small fw-semibold">${announcement.creator.name}</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Event Date</small>
                                        <div class="fw-semibold">${announcement.eventDate}</div>
                                        <small class=" text-muted">${eventDateHuman}</small>
                                                       <small class="text-muted d-block">Created At</small>
                                    <div class="fw-semibold">${formattedDate}</div>
                                    </div>

                               <div class="mt-auto d-flex justify-content-start flex-wrap align-items-stretch">
    <!-- View button always visible -->
    <a href="view/${announcement.id}"
       class="btn btn-outline-success btn-sm mr-2 mb-2 d-flex align-items-center justify-content-center"
       style="min-width:38px;height:38px;" title="View">
        <i class="fas fa-eye"></i>
    </a>

    <!-- Edit button only if key exists and is true -->
    ${res.permission && res.permission.addAnnouncement ? `
    <a href="create/${announcement.id}"
       class="btn btn-outline-info btn-sm mr-2 mb-2 d-flex align-items-center justify-content-center"
       style="min-width:38px;height:38px;" title="Edit">
        <i class="fas fa-pen-to-square"></i>
    </a>` : ''}

    <!-- Delete button only if key exists and is true -->
    ${res.permission && res.permission.deleteAnnouncement ? `
    <form action="delete" method="POST" class="d-inline delete-form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="announcementid " value="${announcement.id}">
        <button type="button" class="btn btn-outline-danger btn-sm mr-2 mb-2  d-flex align-items-center justify-content-center delete-btn"
                style="min-width:38px;height:38px;" title="Delete">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>` : ''}
</div>

                                </div>
                             </div>
                        </div>`;
                    });

                     $('.annoucement-list').html(`<div class="row g-3">${html}</div>`);

                } else {
                    $('.annoucement-list').html(`
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <h5>No announcements found</h5>
                                <p class="mb-0">Create your first announcement to get started.</p>
                            </div>
                        </div>
                    </div>
                 `);
                }
            },
            error: function() {
                $('.annoucement-list').html('<div class="text-center py-5 text-danger">Error loading announcements</div>');
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
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

    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();

        const button = this;

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
                button.closest('form').submit();
            }
        });
    });


    function showfilter(val) {
        // Hide all filters first
        $('#FilterbyTitle, #FilterbyCreatedBy, #StatusFilter_label, #statusFilter, #Filterbydate_to_label, #Filterbydate_to, #Filterbydate_from_label, #Filterbydate_from').hide();

        // Clear values of all fields
        $('#FilterbyTitle, #FilterbyCreatedBy, #statusFilter, #Filterbydate_to, #Filterbydate_from')
            .val('')
            .prop('checked', false)
            .trigger('change');

        // filterProgramPlan();

        if (val === 'createdby') {
            $('#FilterbyCreatedBy').show();
        } else if (val === 'status') {
            $('#statusFilter_label').show();
            $('#statusFilter').show();
        } else if (val === 'title') {
            $('#FilterbyTitle').show();
        } else if (val === 'date') {
            $('#Filterbydate_to_label').show();
            $('#Filterbydate_to').show();
            $('#Filterbydate_from_label').show();
            $('#Filterbydate_from').show();
        } else {
            window.location.reload();
        }
    }
</script>
@endpush
@stop
