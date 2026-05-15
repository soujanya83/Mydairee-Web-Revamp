@extends('layout.master')
@section('title', 'Children List')

@section('parentPageTitle', '')
<style>
    .card-img-top {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .child-details-modal .modal-dialog {
        max-width: 920px;
        width: calc(100% - 1rem);
    }

    .child-details-modal .modal-content {
        max-width: 100%;
    }

    .child-details-modal .modal-body {
        overflow-x: hidden;
    }

    .child-details-modal table {
        table-layout: fixed;
        width: 100%;
    }

    .child-details-modal th,
    .child-details-modal td {
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
        vertical-align: top;
    }

    .child-details-modal .parent-card,
    .child-details-modal .sibling-card {
        min-width: 0;
    }

    .child-details-modal .text-break {
        overflow-wrap: anywhere;
    }
</style>
<style>
    /* Theme accent for filter icon and filter dropdown/input */
    .theme-purple .fas.fa-filter,
    .theme-blue .fas.fa-filter,
    .theme-cyan .fas.fa-filter,
    .theme-green .fas.fa-filter,
    .theme-orange .fas.fa-filter,
    .theme-blush .fas.fa-filter {
        color: var(--sd-accent) !important;
    }
    .theme-purple select.uniform-input,
    .theme-blue select.uniform-input,
    .theme-cyan select.uniform-input,
    .theme-green select.uniform-input,
    .theme-orange select.uniform-input,
    .theme-blush select.uniform-input,
    .theme-purple input.uniform-input,
    .theme-blue input.uniform-input,
    .theme-cyan input.uniform-input,
    .theme-green input.uniform-input,
    .theme-orange input.uniform-input,
    .theme-blush input.uniform-input {
        border-color: var(--sd-accent) !important;
        color: var(--sd-accent) !important;
    }
    .theme-purple select.uniform-input:focus,
    .theme-blue select.uniform-input:focus,
    .theme-cyan select.uniform-input:focus,
    .theme-green select.uniform-input:focus,
    .theme-orange select.uniform-input:focus,
    .theme-blush select.uniform-input:focus,
    .theme-purple input.uniform-input:focus,
    .theme-blue input.uniform-input:focus,
    .theme-cyan input.uniform-input:focus,
    .theme-green input.uniform-input:focus,
    .theme-orange input.uniform-input:focus,
    .theme-blush input.uniform-input:focus {
        box-shadow: 0 0 0 2px var(--sd-accent-soft) !important;
        border-color: var(--sd-accent) !important;
    }
</style>

<!-- Theme accent color overrides for specific elements when a theme is active -->
<style>
    /* Theme accent for Add New Child and Manage Educators buttons */
    .theme-purple .btn-outline-info,
    .theme-blue .btn-outline-info,
    .theme-cyan .btn-outline-info,
    .theme-green .btn-outline-info,
    .theme-orange .btn-outline-info,
    .theme-blush .btn-outline-info {
        border-color: var(--sd-accent) !important;
        color: var(--sd-accent) !important;
    }
    .theme-purple .btn-outline-info:hover,
    .theme-blue .btn-outline-info:hover,
    .theme-cyan .btn-outline-info:hover,
    .theme-green .btn-outline-info:hover,
    .theme-orange .btn-outline-info:hover,
    .theme-blush .btn-outline-info:hover {
        background: var(--sd-accent) !important;
        color: #fff !important;
    }
    /* Card name (child name) in card-title */
    .theme-purple .row .card .card-title,
    .theme-blue .row .card .card-title,
    .theme-cyan .row .card .card-title,
    .theme-green .row .card .card-title,
    .theme-orange .row .card .card-title,
    .theme-blush .row .card .card-title {
        color: var(--sd-accent) !important;
    }
    /* Tab switch background for active tab */
    .theme-purple .nav-tabs .nav-link.active,
    .theme-blue .nav-tabs .nav-link.active,
    .theme-cyan .nav-tabs .nav-link.active,
    .theme-green .nav-tabs .nav-link.active,
    .theme-orange .nav-tabs .nav-link.active,
    .theme-blush .nav-tabs .nav-link.active {
        background-color: var(--sd-accent) !important;
        color: #fff !important;
        border-color: var(--sd-accent) !important;
    }
    .theme-purple .nav-tabs .nav-link,
    .theme-blue .nav-tabs .nav-link,
    .theme-cyan .nav-tabs .nav-link,
    .theme-green .nav-tabs .nav-link,
    .theme-orange .nav-tabs .nav-link,
    .theme-blush .nav-tabs .nav-link {
        border-color: var(--sd-accent) !important;
    }
</style>
<style>
    .card-header {
        position: relative;
        z-index: 1;
    }

    .dropdown-menu {
        z-index: 9999;
    }

    html,
    body {
        overflow-x: hidden;
        /* Disable horizontal scroll */
    }

    #roomDropdown+.dropdown-menu {
        margin-top: -7px !important;
    }

    /* Filter inputs hidden by default */
    #FilterbyName {
        display: none;
    }

    #FilterbyCreatedBy {
        display: none;
    }

    #statusFilter {
        display: none;
    }

    #StatusFilter_label {
        display: none;
    }

    #birthFilter_label {
        display: none;
    }

    #birthmonthFilter {
        display: none;
    }

    #genderFilter {
        display: none;
    }

    #genderFilter_label {
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

@section('content')
<div class="col-12 d-flex align-items-center flex-nowrap gap-3 top-right-button-container mb-3 justify-content-end">
 
    <!-- ✅ scroll if overflow -->
    <div style="margin-right:355px">
        {{-- A-Z / Z-A toggle button --}}
        <button id="sortBtn" class="btn btn-outline-info btn-sm" title="Sort A-Z / Z-A">
            <i class="fas fa-sort-alpha-down"></i> A → Z
        </button>
        &nbsp;&nbsp;
        {{-- Gender toggle button --}}
        <button id="genderBtn" class="btn btn-outline-info btn-sm" title="Filter Male/Female">
            <i class="fas fa-venus-mars"></i> All
        </button>
    </div>


    @if(Auth::user()->userType != "Parent")
    <!-- Filter Icon -->
    <i class="fas fa-filter text-info" style="font-size: 1.2rem;"></i>

    <!-- Filter Dropdown -->
    <select name="filter" onchange="showfilter(this.value)" class="border-info uniform-input">
        <option value="">Select</option>
        <option value="title">Name</option>
        <option value="status">Status</option>
        <option value="Birthmonth">Birth Month</option>
        {{-- <option value="gender">Gender</option> --}}
    </select>

    <!-- Title Filter -->
    <input type="text" name="filterbyName" id="FilterbyName" class="uniform-input" placeholder="Name"
        onkeyup="filterProgramPlan()">


    <!-- Status -->
    <div class="d-flex align-items-center gap-2">
        <label for="statusFilter" id="StatusFilter_label" class="text-info small m-0">Status</label>
        <select id="statusFilter" name="status" class="form-control form-control-sm border-info uniform-input"
            onchange="filterProgramPlan()">
            <option value="">All</option>
            <option value="Active" selected>Active</option>
            <option value="Inactive">Inactive</option>
        </select>
    </div>

    <div class="d-flex align-items-center gap-2">
        <label for="statusFilter" id="birthFilter_label" class="text-info small m-0">Birth Month</label>
        <select id="birthmonthFilter" name="status" class="form-control form-control-sm border-info uniform-input"
            onchange="filterProgramPlan()">
            <option value="">All</option>
            <option value="January">January</option>
            <option value="Febuary">Febuary</option>
            <option value="March">March</option>
            <option value="April">April</option>
            <option value="May">May</option>
            <option value="June">June</option>
            <option value="July">July</option>
            <option value="August">August</option>
            <option value="September">September</option>
            <option value="October">October</option>
            <option value="November">November</option>
            <option value="December">December</option>

        </select>
    </div>

    <div class="d-flex align-items-center gap-2">
        <label for="genderFilter" id="genderFilter_label" class="text-info small m-0">Gender</label>
        <select id="genderFilter" name="gender" class="form-control form-control-sm border-info uniform-input"
            onchange="filterProgramPlan()">
            <option value="">All</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    @endif

    <!-- Room Dropdown -->
    <form method="GET" action="{{ route('childrens_list') }}" id="roomFilterForm" class="d-inline-block m-0">
        <div class="dropdown d-inline-block">
            <button class="btn btn-outline-info dropdown-toggle" type="button" id="roomDropdown">
                {{ $selectedRoom && $rooms->firstWhere('id', $selectedRoom) ? $rooms->firstWhere('id',
                $selectedRoom)->name : '-- All Rooms --' }}
            </button>

            <ul class="dropdown-menu" style="max-height:300px; overflow-y:auto; z-index:999; display:none;">
                <li><a class="dropdown-item" href="#" onclick="selectRoom('', '-- All Rooms --'); return false;">-- All
                        Rooms --</a></li>
                @foreach($rooms as $room)
                <li><a class="dropdown-item" href="#"
                        onclick="selectRoom('{{ $room->id }}', '{{ $room->name }}'); return false;">{{ $room->name
                        }}</a></li>
                @endforeach
            </ul>

            <input type="hidden" name="roomId" id="roomInput" value="{{ $selectedRoom ?? '' }}">
        </div>
    </form>
        <!-- Add New Child Button -->
    @if((Auth::user()->userType === 'Superadmin') || (Auth::user()->userType ==='Staff' && !empty($permissions['addChildGroup']) && $permissions['addChildGroup'] ))
    <button class="btn btn-outline-info ml-2" id="addChildBtn" data-toggle="modal" data-target="#selectRoomModal">
        + Add New Child
    </button>
    @endif
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-5px">
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
<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-5px">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif


<!-- Room Selection Modal -->
<div class="modal" id="selectRoomModal" tabindex="-1" role="dialog" aria-labelledby="selectRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectRoomModalLabel">Select Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><b>X</b></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="roomSelectModal">Room <span style="color:red">*</span></label>
                    <select class="form-control" id="roomSelectModal">
                        <option value="" selected disabled>Select a room</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted" style="color:#555!important;">
                        <span style="color:red">*</span> Select the room in which you want to add the child.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal" id="newChildModal" tabindex="-1" role="dialog" aria-labelledby="newChildModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newChildModalLabel">+Add New Child</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><b>X</b></span>
                    </button>
                </div>
            <form action="{{ route('add_children') }}" id="form-child" method="post" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" name="id" id="modalRoomId">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstname">First Name <span style="color:red">*</span></label>
                            <span class="text-danger error_firstname"></span>
                            <input type="text" name="firstname" id="firstname" placeholder="Enter first name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name <span style="color:red">*</span></label>
                            <span class="text-danger error_lastname"></span>
                            <input type="text" name="lastname" id="lastname" placeholder="Enter last name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dob">Date of Birth <span style="color:red">*</span></label>
                            <span class="text-danger error_dob"></span>
                            <input type="date" name="dob" id="dob" class="form-control date-input flatpickr-input" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="doj">Date of Join <span style="color:red">*</span></label>
                            <span class="text-danger error_doj"></span>
                            <input type="date" name="startDate" id="doj" class="form-control date-input flatpickr-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="uploadImg">Choose Image</label>
                            <input id="uploadImg" name="file" class="form-control" type="file" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="status">Status <span style="color:red">*</span></label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected>Select</option>
                                <option value="Active" selected>Active</option>
                                <option value="In Active">In Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="gender">Gender <span style="color:red">*</span></label>
                            <div class="d-flex">
                                <span class="radio-label">
                                    <input type="radio" name="gender" value="Male" id="genderMale" required> <label for="genderMale">Male</label>
                                </span>
                                <span class="radio-label">
                                    <input type="radio" name="gender" value="Female" id="genderFemale"> <label for="genderFemale">Female</label>
                                </span>
                                <span class="radio-label">
                                    <input type="radio" name="gender" value="Other" id="genderOther"> <label for="genderOther">Other</label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="daysAttending">Days Attending <span style="color:red">*</span></label>
                            <div class="flexCheck">
                                <input type="checkbox" name="mon" value="1" id="Monday" checked>
                                <label for="Monday"> Monday</label>
                                <input type="checkbox" name="tue" value="1" id="Tuesday" checked>
                                <label for="Tuesday"> Tuesday</label>
                                <input type="checkbox" name="wed" value="1" id="Wednesday" checked>
                                <label for="Wednesday"> Wednesday</label>
                                <input type="checkbox" name="thu" value="1" id="Thursday" checked>
                                <label for="Thursday"> Thursday</label>
                                <input type="checkbox" name="fri" value="1" id="Friday" checked>
                                <label for="Friday"> Friday</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-add-child">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts for modal logic -->
<script>

    // Automatically open child modal after room selection
    $('#roomSelectModal').on('change', function() {
        var roomId = $(this).val();
        if(roomId) {
            $('#modalRoomId').val(roomId);
            $('#selectRoomModal').modal('hide');
            setTimeout(function() {
                $('#newChildModal').modal('show');
            }, 400);
        }
    });

    // Reset modals on close
    $('#selectRoomModal').on('hidden.bs.modal', function () {
        $('#roomSelectModal').val('');
        $('#proceedToAddChild').prop('disabled', true);
    });
    $('#newChildModal').on('hidden.bs.modal', function () {
        $('#form-child')[0].reset();
        $('.error_firstname, .error_lastname, .error_dob, .error_doj').text('');
    });
</script>


<div class="row mb-5" id="childrenWrapper">

    @foreach($chilData as $child)
    <div class="col-md-3 mb-2 child-card" data-name="{{ strtolower($child->childname . ' ' . $child->lastname) }}"
        data-gender="{{ strtolower($child->gender) }}">

        
        <div class="card shadow rounded-lg position-relative">
            <img src="{{ $child->imageUrl
                    ? asset($child->imageUrl)
                    : ($child->gender == 'Male'
                        ? asset('assets/img/default-boyimage.jpg')
                        : asset('assets/img/default-girlimage.jpg')) }}" class="card-img-top"
                style="height: 200px; object-fit: cover; border-radius: 8px; padding: 5px;" alt="{{ $child->name }}">
            <form action="{{ route('children.toggleStatus', $child->childId) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px;">
                @csrf
                @method('PATCH')
                <button type="button"
                    class="btn btn-sm {{ $child->childstatus == 'Active' ? 'btn-success' : 'btn-danger' }} toggle-status-btn"
                    data-child-id="{{ $child->childId }}"
                    data-child-status="{{ $child->childstatus }}">
                    {{ $child->childstatus == 'Active' ? 'Active' : 'Inactive' }}
                </button>
            </form>
            <div class="card-body">
                <h5 class="card-title">{{ $child->childname }} {{ $child->lastname }}</h5>

                <div class="mb-2">
                    <span class="badge bg-outline-info" style="color:#000000;background-color:#fff">DOB:
                        {{ optional($child->dob ? \Carbon\Carbon::parse($child->dob) : null)->format('d M Y') ??
                        'N/A' }}
                    </span>
                    <span class="badge" style="margin-left:-2px;color:#000000;background-color:#fff">
                        @if(strtolower($child->gender) == 'male')
                        <i class="fas fa-mars"></i> Male
                        @else
                        <i class="fas fa-venus"></i> Female
                        @endif
                    </span>

                    

                </div>

                {{--  <p class="mb-1"><i class="fas fa-id-card me-1"></i> ID: {{ $child->childId }}</p>  --}}
                <p class="mb-1"><i class="fas fa-door-open me-1"></i> Room: {{ $child->roomname ?? 'N/A' }}</p>
                <p class="mb-3"><i class="fas fa-calendar-check me-1"></i>
                    Joined: {{ optional($child->startDate ? \Carbon\Carbon::parse($child->startDate) :
                    null)->format('d
                    M Y') ?? 'N/A' }}
                </p>

                <div class="d-flex justify-content-end" style="margin-top:-17px">
                    <button type="button" class="btn btn-outline-info btn-sm view-details-btn" style="height: 23px; margin-right:6px;"
                        data-child-id="{{ $child->childId }}" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                        <!-- View Details Modal -->
                        <div class="modal fade child-details-modal" id="detailsModal{{ $child->childId }}" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel{{ $child->childId }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content border-0 overflow-hidden">
                                    <div class="modal-header py-3">
                                        <h5 class="modal-title text-break" id="detailsModalLabel{{ $child->childId }}">
                                            Child Details{{ !empty($child->childname) || !empty($child->lastname) ? ' - ' . trim($child->childname . ' ' . $child->lastname) : '' }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-3 p-md-4">
                                        <div class="row g-3 align-items-start">
                                            <div class="col-lg-4">
                                                <div class="border rounded-4 p-3 bg-light text-center h-100 shadow-sm parent-card">
                                                    <img src="{{ $child->imageUrl ? asset($child->imageUrl) : (strtolower($child->gender) == 'male' ? asset('assets/img/default-boyimage.jpg') : asset('assets/img/default-girlimage.jpg')) }}" class="img-fluid rounded-circle mb-3" style="max-width: 180px; width: 100%; height: 180px; object-fit: cover;" alt="{{ $child->childname }}">
                                                    <h5 class="mb-1 text-break">{{ $child->childname }} {{ $child->lastname }}</h5>
                                                    @if(!empty($child->roomname))
                                                        <div class="text-muted small">{{ $child->roomname }}</div>
                                                    @endif
                                                    <div class="mt-2 d-flex justify-content-center flex-wrap gap-2">
                                                        @if(!empty($child->gender))
                                                            <span class="badge bg-info text-dark">{{ $child->gender }}</span>
                                                        @endif
                                                        @if(!empty($child->childstatus))
                                                            <span class="badge bg-secondary">{{ $child->childstatus }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="table-responsive">
                                                <table class="table table-bordered table-sm mb-0">
                                                    <tbody>
                                                        @if(!empty($child->childname) || !empty($child->lastname))
                                                            <tr><th>Full Name</th><td>{{ trim($child->childname . ' ' . $child->lastname) }}</td></tr>
                                                        @endif
                                                        @if(!empty($child->roomname))
                                                            <tr><th>Room</th><td>{{ $child->roomname }}</td></tr>
                                                        @endif
                                                        @if(!empty($child->dob))
                                                            <tr><th>Date of Birth</th><td>{{ \Carbon\Carbon::parse($child->dob)->format('d M Y') }}</td></tr>
                                                        @endif
                                                        @if(!empty($child->gender))
                                                            <tr><th>Gender</th><td>{{ $child->gender }}</td></tr>
                                                        @endif
                                                        @if(!empty($child->childstatus))
                                                            <tr><th>Status</th><td>{{ $child->childstatus }}</td></tr>
                                                        @endif
                                                        @if(!empty($child->startDate))
                                                            <tr><th>Joined</th><td>{{ \Carbon\Carbon::parse($child->startDate)->format('d M Y') }}</td></tr>
                                                        @endif
                                                        @if(!empty($child->address))
                                                            <tr><th>Address</th><td>{{ $child->address }}</td></tr>
                                                        @endif
                                                        @php
                                                            $parents = \App\Models\Childparent::where('childid', $child->childId)
                                                                ->join('users', 'users.id', '=', 'childparent.parentid')
                                                                ->select('users.id', 'users.title', 'users.name', 'users.email', 'users.contactNo', 'users.gender', 'users.imageUrl', 'childparent.relation')
                                                                ->get();
                                                        @endphp
                                                        @if($parents && $parents->isNotEmpty())
                                                            <tr><th>Parents</th><td>
                                                            @php
                                                                $parents = $parents;
                                                            @endphp
                                                                <div class="d-flex flex-column gap-3">
                                                                @foreach($parents as $parent)
                                                                    <div class="border rounded p-3 bg-white shadow-sm parent-card">
                                                                        <div class="d-flex align-items-center">
                                                                            <img src="{{ $parent->imageUrl ? asset($parent->imageUrl) : asset('assets/img/user.png') }}" alt="{{ $parent->name }}" class="rounded-circle me-3" style="width: 56px; height: 56px; object-fit: cover;">
                                                                            <div>
                                                                                <div class="fw-semibold">{{ trim(($parent->title ? $parent->title . ' ' : '') . $parent->name) }}</div>
                                                                                @if(!empty($parent->relation))
                                                                                    <div class="text-muted small">{{ $parent->relation }}</div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-3 small">
                                                                            @if(!empty($parent->email))
                                                                                <div class="col-md-12 mb-2"><strong>Email:</strong> {{ $parent->email }}</div>
                                                                            @endif
                                                                            <br>
                                                                            @if(!empty($parent->contactNo))
                                                                                <div class="col-md-6 mb-2"><strong>Contact:</strong> {{ $parent->contactNo }}</div>
                                                                            @endif
                                                                            
                                                                            @if(!empty($parent->gender))
                                                                                <div class="col-md-6 mb-2"><strong>Gender:</strong> {{ $parent->gender }}</div>
                                                                            @endif
                                                                            @if(!empty($parent->title))
                                                                                <div class="col-md-6 mb-2"><strong>Title:</strong> {{ $parent->title }}</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                </div>
                                                            </td></tr>
                                                        @endif
                                                        @php
                                                            $parentIds = \App\Models\Childparent::where('childid', $child->childId)->pluck('parentid');
                                                            $siblingIds = \App\Models\Childparent::whereIn('parentid', $parentIds)
                                                                ->where('childid', '!=', $child->childId)
                                                                ->pluck('childid')->unique();
                                                            $siblings = \App\Models\Child::whereIn('id', $siblingIds)->select('name as childname', 'lastname', 'gender', 'imageUrl')->get();
                                                        @endphp
                                                        @if($siblings && $siblings->isNotEmpty())
                                                            <tr><th>Siblings</th><td>
                                                                <div class="d-flex flex-column gap-2">
                                                                @foreach($siblings as $sibling)
                                                                    <div class="d-flex align-items-center border rounded p-2 bg-light sibling-card">
                                                                        <img src="{{ $sibling->imageUrl ? asset($sibling->imageUrl) : (strtolower($sibling->gender) == 'male' ? asset('assets/img/default-boyimage.jpg') : asset('assets/img/default-girlimage.jpg')) }}" alt="{{ $sibling->childname }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                                        <div>
                                                                            <div class="fw-semibold">{{ $sibling->childname }} {{ $sibling->lastname }}</div>
                                                                            @if(!empty($sibling->gender))
                                                                                <div class="text-muted small">{{ $sibling->gender }}</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                </div>
                                                            </td></tr>
                                                        @endif
                                                        @if(!empty($child->other_details))
                                                            <tr><th>Other Details</th><td>{{ $child->other_details }}</td></tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- status modal placeholder (moved to global) -->
                    @if((Auth::user()->userType === 'Superadmin') || (Auth::user()->userType ==='Staff' && !empty($permissions['updateChildGroup']) && $permissions['updateChildGroup'] ))
                    <a href="{{ route('children.edit', $child->childId) }}" class="btn btn-outline-primary btn-sm"
                        style="height: 24px;" title="Child Edit">
                        <i class="fas fa-edit"></i>
                    </a>&nbsp;&nbsp;
                    @endif



                    <form action="{{ route('children.destroy', $child->childId) }}" method="POST" class="me-2 delete-child-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm delete-child-btn" title="Child Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    <!-- SweetAlert2 -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Attach SweetAlert2 to all delete buttons
                            document.querySelectorAll('.delete-child-btn').forEach(function(btn) {
                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const form = btn.closest('form');
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "This child will be permanently deleted!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Yes, delete!',
                                        cancelButtonText: 'Cancel'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            form.submit();
                                        }
                                    });
                                });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>

    <!-- Status History Modal -->
    <div class="modal" id="statusModal{{ $child->childId }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $child->childId }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content card">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel{{ $child->childId }}">
                        Status History - {{ $child->childname }} {{ $child->lastname }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                    $statusHistory = \App\Models\ChildStatusHistory::where('child_id', $child->childId)
                    ->orderBy('date_time','desc')->get();
                    @endphp

                    @if($statusHistory->isEmpty())
                    <p class="text-muted">No status history found.</p>
                    @else
                    <table class="table table-bordered table-sm">
                        <thead class="table-light" style="background: url('{{ asset('img/download.jpg') }}') no-repeat center center;
            background-size: cover;">
                            <tr>
                                <th>#</th>
                                <th>Old Status</th>
                                <th>New Status</th>
                                <th>Changed By</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statusHistory as $index => $history)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td><span style="color:#fff"
                                        class="badge {{ $history->old_status == 'Active' ? 'bg-success' : 'bg-danger' }}">{{
                                        $history->old_status ?? '-' }}</span></td>
                                <td><span style="color:#fff"
                                        class="badge {{ $history->new_status == 'Active' ? 'bg-success' : 'bg-danger' }}">{{
                                        $history->new_status }}</span></td>
                                <td>{{ optional($history->user)->name ?? 'System' }}</td>
                                <td>{{ \Carbon\Carbon::parse($history->date_time)->format('d M Y h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @endforeach

</div>

    <!-- Global Status Toggle Confirmation Modal -->
    <div class="modal fade" id="statusConfirmModal" tabindex="-1" role="dialog" aria-labelledby="statusConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusConfirmModalLabel">Confirm Status Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to change the status of this child?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmStatusChangeBtn">Yes, Change</button>
                </div>
            </div>
        </div>
    </div>



<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
$(document).on('hidden.bs.modal', '.child-details-modal', function () {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').css('padding-right', '');
});

// Handle View Details button
$(document).on('click', '.view-details-btn', function() {
    var childId = $(this).data('child-id');
    $('#detailsModal' + childId).modal('show');
});

// Status toggle confirmation logic
let statusFormToSubmit = null;
$(document).on('click', '.toggle-status-btn', function(e) {
    e.preventDefault();
    statusFormToSubmit = $(this).closest('form');
    $('#statusConfirmModal').modal('show');
});

$('#confirmStatusChangeBtn').on('click', function() {
    if (statusFormToSubmit) {
        statusFormToSubmit.submit();
        statusFormToSubmit = null;
        $('#statusConfirmModal').modal('hide');
        // Optionally show a success toast/modal here
    }
});
    document.addEventListener("DOMContentLoaded", function () {
        filterProgramPlan();
    const sortBtn = document.getElementById("sortBtn");
    const genderBtn = document.getElementById("genderBtn");
    const wrapper = document.getElementById("childrenWrapper");

    let sortAsc = true;   // toggle A→Z / Z→A
    let genderFilter = "all"; // toggle male / female / all

    // 🔹 Sorting Function
    sortBtn.addEventListener("click", function () {
        const cards = Array.from(wrapper.querySelectorAll(".child-card"));

        cards.sort((a, b) => {
            let nameA = a.dataset.name;
            let nameB = b.dataset.name;

            return sortAsc
                ? nameA.localeCompare(nameB)   // A→Z
                : nameB.localeCompare(nameA);  // Z→A
        });

        // re-append sorted cards
        cards.forEach(card => wrapper.appendChild(card));

        // toggle state
        sortAsc = !sortAsc;
        sortBtn.innerHTML = sortAsc
            ? '<i class="fas fa-sort-alpha-down"></i> A → Z'
            : '<i class="fas fa-sort-alpha-up"></i> Z → A';
    });

    // 🔹 Gender Filter Function
    genderBtn.addEventListener("click", function () {
        const cards = wrapper.querySelectorAll(".child-card");

        if (genderFilter === "all") {
            genderFilter = "male";
            genderBtn.innerHTML = '<i class="fas fa-mars"></i> Male';
        } else if (genderFilter === "male") {
            genderFilter = "female";
            genderBtn.innerHTML = '<i class="fas fa-venus"></i> Female';
        } else {
            genderFilter = "all";
            genderBtn.innerHTML = '<i class="fas fa-venus-mars"></i> All';
        }

        cards.forEach(card => {
            const gender = card.dataset.gender;
            if (genderFilter === "all" || gender === genderFilter) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
});
</script>

<script>
    function showfilter(val) {
    // Hide all filters first
    $('#FilterbyName, #FilterbyCreatedBy, #StatusFilter_label, #statusFilter, #birthFilter_label, #birthmonthFilter, #genderFilter_label, #genderFilter')
        .hide()
        .val('') // clear values
        .prop('checked', false)
        .trigger('change');

    filterProgramPlan(); // apply filter after clearing

    // Show relevant fields based on selected filter
    switch (val.toLowerCase()) {
        case 'title':
            $('#FilterbyName').show();
            break;
        case 'status':
            $('#StatusFilter_label, #statusFilter').show();
            break;
        case 'birthmonth':
            $('#birthFilter_label, #birthmonthFilter').show();
            break;
        case 'gender':
            $('#genderFilter_label, #genderFilter').show();
            break;
        case 'createdby':
            $('#FilterbyCreatedBy').show();
            break;
        default:
            // If "Choose" or invalid option, hide all
            $('#FilterbyName, #FilterbyCreatedBy, #StatusFilter_label, #statusFilter, #birthFilter_label, #birthmonthFilter, #genderFilter_label, #genderFilter').hide();
            break;
    }
}




function filterProgramPlan() {
    // Get filter values
    var name       = $('#FilterbyName').val().toLowerCase();
    var status     = $('#statusFilter').val().toLowerCase().trim();
    var birthMonth = $('#birthmonthFilter').val().toLowerCase().slice(0,3); // trim to 3 letters
    var gender     = $('#genderFilter').val().toLowerCase();

    // Iterate over each child card
    $('.row.mb-5 > .col-md-3').each(function() {
        var card        = $(this);
        var childName   = card.find('.card-title').text().toLowerCase();
        var childStatus = card.find('form button').first().text().toLowerCase().trim();
        var childGender = card.find('.badge i').hasClass('fa-mars') ? 'male' : 'female';

        // Extract DOB from badge
        var dobText = card.find('.badge').first().text().trim(); // e.g. "DOB: 21 Apr 2023"
        var dobMonth = '';
        var dobMatch = dobText.match(/dob:\s*\d+\s+(\w+)/i);
        if (dobMatch) {
            dobMonth = dobMatch[1].toLowerCase().slice(0,3); // first 3 letters
        }

        // Check if card matches all active filters
        var show = true;

        // Debugging: log status values
        // console.log('Dropdown status:', status, '| Card status:', childStatus);

        if (name && !childName.includes(name)) show = false;
        if (status && status !== '' && childStatus !== status) show = false;
        if (birthMonth && dobMonth !== birthMonth) show = false;
        if (gender && childGender !== gender) show = false;

        // Toggle card visibility
        card.toggle(show);
    });
}






    document.addEventListener("DOMContentLoaded", function () {
    const dropdownToggle = document.getElementById("roomDropdown");
    const dropdownMenu = dropdownToggle.nextElementSibling;
    const childNameInput = document.getElementById("childNameInput");
    let debounceTimeout;

    // Room dropdown toggle
    dropdownToggle.addEventListener("click", function (event) {
        event.preventDefault();
        dropdownMenu.style.display =
            dropdownMenu.style.display === "block" ? "none" : "block";
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none";
        }
    });

    // Submit form on child name input (with debounce)
    childNameInput.addEventListener("keyup", function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            document.getElementById("roomFilterForm").submit();
        }, 500); // 500ms debounce
    });
    });

        function selectRoom(id, name) {
            document.getElementById('roomInput').value = id;
            document.getElementById('roomDropdown').textContent = name;
            document.getElementById('roomFilterForm').submit();
        }
</script>

@include('layout.footer')
@stop