@extends('layout.master')
@section('title', 'Childs List')

@section('parentPageTitle', '')
<style>
    .card-img-top {
        width: 100%;
        height: 180px;
        object-fit: cover;
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

    /* filter  */
        #FilterbyTitle{
        display: none;
    }
     #FilterbyCreatedBy{
        display: none;
    }
     #StatusFilter{
        display: none;
    }
     #StatusFilter_label{
        display: none;
    }
     #Filterbydate_from_label{
        display: none;
    }
     #Filterbydate_from{
        display: none;
    }
     #Filterbydate_to_label{
        display: none;
    }
       #Filterbydate_to{
        display: none;
    }
          #genderFilter{
        display: none;
    }
           #genderFilter_label{
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
    /* Make all inputs uniform */
    .uniform-input {
        width: 180px;    /* same width for all */
        height: 36px;    /* same height */
        font-size: 0.875rem;
        margin-inline: 0.5rem;
    }

    /* Make sure labels don't misalign the row */
    .top-right-button-container label {
        line-height: 1;
    }
</style>

@section('content')

  <div class="col-12 d-flex align-items-center flex-nowrap gap-3 top-right-button-container mb-4 justify-content-end"
     >  <!-- ✅ scroll if overflow -->

    @if(Auth::user()->userType != "Parent")
        <!-- Filter Icon -->
        <i class="fas fa-filter text-info" style="font-size: 1.2rem;"></i>

        <!-- Filter Dropdown -->
        <select name="filter" onchange="showfilter(this.value)" 
                class="border-info uniform-input">
            <option value="">Choose</option>
            <option value="title">Title</option>
            <option value="status">Status</option>
            <option value="date">Date</option>
            <option value="gender">Gender</option>
        </select>

        <!-- Title Filter -->
        <input type="text" name="filterbyTitle" id="FilterbyTitle"
               class="uniform-input"
               placeholder="Filter by name"
               onkeyup="filterProgramPlan()">

        <!-- From Date -->
        <div class="d-flex align-items-center gap-2">
            <label for="Filterbydate_from" id="Filterbydate_from_label" class="text-info small m-0">From</label>
            <input type="date" id="Filterbydate_from" name="date_from"
                   class="form-control border-info form-control-sm uniform-input"
                   value="{{ request('date_from') }}"
                   onchange="filterProgramPlan()">
        </div>

        <!-- To Date -->
        <div class="d-flex align-items-center gap-2">
            <label for="Filterbydate_to" id="Filterbydate_to_label" class="text-info small m-0">To</label>
            <input type="date" id="Filterbydate_to" name="date_to"
                   class="form-control border-info form-control-sm uniform-input"
                   value="{{ request('date_to') }}"
                   onchange="filterProgramPlan()">
        </div>

        <!-- Status -->
        <div class="d-flex align-items-center gap-2">
            <label for="statusFilter" id="StatusFilter_label" class="text-info small m-0">Status</label>
            <select id="statusFilter" name="status"
                    class="form-control form-control-sm border-info uniform-input"
                    onchange="filterProgramPlan()">
                <option value="">All</option>
                <option value="Active">Active</option>
                <option value="In-active" >IN-Active</option>
            </select>
        </div>

               <div class="d-flex align-items-center gap-2">
            <label for="genderFilter" id="genderFilter_label" class="text-info small m-0">Gender</label>
            <select id="genderFilter" name="gender"
                    class="form-control form-control-sm border-info uniform-input"
                    onchange="filterProgramPlan()">
                <option value="">All</option>
                <option value="Male">Male</option>
                <option value="Female" >Female</option>
            </select>
        </div>
    @endif

    <!-- Room Dropdown -->
    <form method="GET" action="{{ route('childrens_list') }}" id="roomFilterForm" class="d-inline-block m-0">
        <div class="dropdown d-inline-block">
            <button class="btn btn-outline-info dropdown-toggle" type="button" id="roomDropdown">
                {{ $selectedRoom && $rooms->firstWhere('id', $selectedRoom) ? $rooms->firstWhere('id', $selectedRoom)->name : '-- All Rooms --' }}
            </button>

            <ul class="dropdown-menu"
                style="max-height:300px; overflow-y:auto; z-index:999; display:none;">
                <li><a class="dropdown-item" href="#" onclick="selectRoom('', '-- All Rooms --'); return false;">-- All Rooms --</a></li>
                @foreach($rooms as $room)
                <li><a class="dropdown-item" href="#" onclick="selectRoom('{{ $room->id }}', '{{ $room->name }}'); return false;">{{ $room->name }}</a></li>
                @endforeach
            </ul>

            <input type="hidden" name="roomId" id="roomInput" value="{{ $selectedRoom ?? '' }}">
        </div>
    </form>
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

<hr>

<div class="row mb-5">
    @foreach($chilData as $child)
    <div class="col-md-3 mb-2">
        <div class="card shadow rounded-lg position-relative">

            {{-- Image --}}
            <img src="{{ $child->imageUrl ? asset($child->imageUrl) : 'http://www.mydiaree.com.au/assets/img/MYDIAREE-new-logo.png' }}"
                class="card-img-top" style="height: 200px; object-fit: cover;border-radius: 8px;padding: 5px;"
                alt="{{ $child->name }}">

            {{-- Status button (overlay top-right) --}}
            <form action="{{ route('children.toggleStatus', $child->childId) }}" method="POST" class="position-absolute"
                style="top: 10px; right: 10px;">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="btn btn-sm {{ $child->childstatus == 'Active' ? 'btn-success' : 'btn-danger' }}">
                    {{ $child->childstatus == 'Active' ? 'Active' : 'Inactive' }}
                </button>

            </form>

            {{-- Card body --}}
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

                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#statusModal{{ $child->childId }}" style="height: 23px;" title="View Status History">
                        <i class="fas fa-eye"></i>
                    </button>

                </div>

                <p class="mb-1"><i class="fas fa-id-card me-1"></i> ID: {{ $child->childId }}</p>
                <p class="mb-1"><i class="fas fa-door-open me-1"></i> Room: {{ $child->roomname ?? 'N/A' }}</p>
                <p class="mb-3"><i class="fas fa-calendar-check me-1"></i>
                    Joined: {{ optional($child->startDate ? \Carbon\Carbon::parse($child->startDate) : null)->format('d M Y') ?? 'N/A' }}
                </p>

                <div class="d-flex justify-content-end" style="margin-top:-43px">

                    <a href="{{ route('children.edit', $child->childId) }}" class="btn btn-outline-primary btn-sm"
                        style="height: 24px;" title="Child Edit">
                        <i class="fas fa-edit"></i>
                    </a>&nbsp;&nbsp;



                    <form action="{{ route('children.destroy', $child->childId) }}" method="POST"
                        onsubmit="return confirm('Are you sure?')" class="me-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm" title="Child Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Status History Modal -->
    <div class="modal" id="statusModal{{ $child->childId }}" tabindex="-1"
        aria-labelledby="statusModalLabel{{ $child->childId }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content card">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel{{ $child->childId }}">
                        Status History - {{ $child->childname }} {{ $child->lastname }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
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



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showfilter(val) {
    // Hide all filters first
    $('#FilterbyTitle, #FilterbyCreatedBy, #StatusFilter_label, #statusFilter, #Filterbydate_to_label, #Filterbydate_to, #Filterbydate_from_label, #Filterbydate_from, #genderFilter_label, #genderFilter').hide();

    // Clear values of all fields
    $('#FilterbyTitle, #FilterbyCreatedBy, #statusFilter, #Filterbydate_to, #Filterbydate_from, #genderFilter')
        .val('')
        .prop('checked', false)
        .trigger('change');

        filterProgramPlan();

    // Show relevant fields based on selected filter
    if (val === 'createdby') {
        $('#FilterbyCreatedBy').show();
    } 
    else if (val === 'status') {
        $('#StatusFilter_label').show();
        $('#statusFilter').show();
    } 
    else if (val === 'title') {
        $('#FilterbyTitle').show();
    } 
    else if (val === 'date') {
        $('#Filterbydate_from_label, #Filterbydate_from, #Filterbydate_to_label, #Filterbydate_to').show();
    } 
    else if (val === 'gender') {
        $('#genderFilter_label, #genderFilter').show();
    } 
    else {
        // Reset view if "Choose" or invalid option
        window.location.reload();
    }
}



function filterProgramPlan() {
    var Title        = $('#FilterbyTitle').val();
    var CreatedBy    = $('#FilterbyCreatedBy').val();
    var date_from    = $('#Filterbydate_from').val();
    var date_to      = $('#Filterbydate_to').val();
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
        beforeSend: function () {
            $('.annoucement-list').html('<div class="text-center py-5">Loading...</div>');
        },
        success: function (res) {
            if (res.status && res.records.length > 0) {
                let html = '';

                $.each(res.records, function (i, announcement) {
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
                                        <small class="text-muted">${eventDateHuman}</small>
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
        <input type="hidden" name="announcementid" value="${announcement.id}">
        <button type="button" class="btn btn-outline-danger btn-sm mr-2 mb-2 d-flex align-items-center justify-content-center delete-btn" 
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
        error: function () {
            $('.annoucement-list').html('<div class="text-center py-5 text-danger">Error loading announcements</div>');
        }
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
