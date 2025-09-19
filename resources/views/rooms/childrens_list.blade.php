@extends('layout.master')
@section('title', 'Children List')

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
    <div style="margin-right:485px">
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
            <option value="Active">Active</option>
            <option value="Inactive">IN-Active</option>
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


<div class="row mb-5" id="childrenWrapper">

    @foreach($chilData as $child)
    <div class="col-md-3 mb-2 child-card" data-name="{{ strtolower($child->childname . ' ' . $child->lastname) }}"
        data-gender="{{ strtolower($child->gender) }}">

        {{-- 👇 Yahan aapka card code as it is rahega --}}
        <div class="card shadow rounded-lg position-relative">
            <img src="{{ $child->imageUrl
                    ? asset($child->imageUrl)
                    : ($child->gender == 'Male'
                        ? asset('assets/img/default-boyimage.jpg')
                        : asset('assets/img/default-girlimage.jpg')) }}" class="card-img-top"
                style="height: 200px; object-fit: cover; border-radius: 8px; padding: 5px;" alt="{{ $child->name }}">

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
                        data-bs-target="#statusModal{{ $child->childId }}" style="height: 23px;"
                        title="View Status History">
                        <i class="fas fa-eye"></i>
                    </button>

                </div>

                <p class="mb-1"><i class="fas fa-id-card me-1"></i> ID: {{ $child->childId }}</p>
                <p class="mb-1"><i class="fas fa-door-open me-1"></i> Room: {{ $child->roomname ?? 'N/A' }}</p>
                <p class="mb-3"><i class="fas fa-calendar-check me-1"></i>
                    Joined: {{ optional($child->startDate ? \Carbon\Carbon::parse($child->startDate) :
                    null)->format('d
                    M Y') ?? 'N/A' }}
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
                        <button class="btn btn-outline-danger btn-sm" title="Child Delete"><i
                                class="fas fa-trash-alt"></i></button>
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
    document.addEventListener("DOMContentLoaded", function () {
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
    var status     = $('#statusFilter').val().toLowerCase();
    var birthMonth = $('#birthmonthFilter').val().toLowerCase().slice(0,3); // trim to 3 letters
    var gender     = $('#genderFilter').val().toLowerCase();

    // Iterate over each child card
    $('.row.mb-5 > .col-md-3').each(function() {
        var card        = $(this);
        var childName   = card.find('.card-title').text().toLowerCase();
        var childStatus = card.find('form button[type="submit"]').text().toLowerCase();
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
        if (name && !childName.includes(name)) show = false;
        if (status && !childStatus.includes(status)) show = false;
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
