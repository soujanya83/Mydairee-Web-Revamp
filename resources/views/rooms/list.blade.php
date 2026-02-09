@extends('layout.master')

@section('title', 'Rooms List')

@section('parentPageTitle', '')



@section('content')





<!-- <div style="margin-top: -36px;">
    <h5>Rooms List</h5>


    <hr> -->



<style>
    .educator-checkbox-list {
        max-height: 150px;
        /* Limit height to make it scrollable if needed */
        overflow-y: auto;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 5px;
    }

    .checkbox-item {
        padding: 5px;
    }

    .checkbox-item input[type="checkbox"] {
        margin-right: 10px;
    }

    .checkbox-item label {
        cursor: pointer;
    }

    .checkbox-item input[type="checkbox"]:checked+label {
        background-color: #007bff;
        color: white;
        padding: 2px 5px;
        border-radius: 3px;
    }

    /* Theme-specific styles */
    .theme-purple .checkbox-item input[type="checkbox"]:checked+label,
    .theme-blue .checkbox-item input[type="checkbox"]:checked+label,
    .theme-cyan .checkbox-item input[type="checkbox"]:checked+label,
    .theme-green .checkbox-item input[type="checkbox"]:checked+label,
    .theme-orange .checkbox-item input[type="checkbox"]:checked+label,
    .theme-blush .checkbox-item input[type="checkbox"]:checked+label {
        background-color: var(--sd-accent) !important;
        color: #fff !important;
    }
    .theme-purple .fa-children,
    .theme-blue .fa-children,
    .theme-cyan .fa-children,
    .theme-green .fa-children,
    .theme-orange .fa-children,
    .theme-blush .fa-children,
    .theme-purple .fa-chalkboard-teacher,
    .theme-blue .fa-chalkboard-teacher,
    .theme-cyan .fa-chalkboard-teacher,
    .theme-green .fa-chalkboard-teacher,
    .theme-orange .fa-chalkboard-teacher,
    .theme-blush .fa-chalkboard-teacher {
        color: var(--sd-accent) !important;
    }
    .theme-purple .btn-outline-info,
    .theme-blue .btn-outline-info,
    .theme-cyan .btn-outline-info,
    .theme-green .btn-outline-info,
    .theme-orange .btn-outline-info,
    .theme-blush .btn-outline-info,
    .theme-purple .btn-outline-primary,
    .theme-blue .btn-outline-primary,
    .theme-cyan .btn-outline-primary,
    .theme-green .btn-outline-primary,
    .theme-orange .btn-outline-primary,
    .theme-blush .btn-outline-primary {
        border-color: var(--sd-accent) !important;
        color: var(--sd-accent) !important;
    }
    .theme-purple .btn-outline-info:hover,
    .theme-blue .btn-outline-info:hover,
    .theme-cyan .btn-outline-info:hover,
    .theme-green .btn-outline-info:hover,
    .theme-orange .btn-outline-info:hover,
    .theme-blush .btn-outline-info:hover,
    .theme-purple .btn-outline-primary:hover,
    .theme-blue .btn-outline-primary:hover,
    .theme-cyan .btn-outline-primary:hover,
    .theme-green .btn-outline-primary:hover,
    .theme-orange .btn-outline-primary:hover,
    .theme-blush .btn-outline-primary:hover {
        background: var(--sd-accent) !important;
        color: #fff !important;
    }
</style>


<div style="margin-top: -36px;">

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-22px">
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
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-22px">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <!-- <h5>Rooms List</h5> -->




    <div class="d-flex justify-content-end mb-3" style="gap: 10px;">

        <input type="text" id="roomSearch" class="form-control w-25" placeholder="Search room name...">

        <!-- <div class="dropdown">
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
        </div> -->

        <div class="text-zero top-right-button-container d-flex justify-content-end"
            style="margin-right: 20px;margin-top: 0px;">
            <div class="dropdown">
                <button class="btn btn-outline-primary btn-lg dropdown-toggle" type="button" id="centerDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown"
                    style="top:3% !important;left:13px !important;">
                    @foreach($centers as $center)
                    <a href="javascript:void(0);"
                        class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                        style="background-color:white;" data-id="{{ $center->id }}">
                        {{ $center->centerName }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @if(!empty($permissions['addRoom']) && $permissions['addRoom'])

        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#roomModal"
            style="height: 36px;">
            Create Room
        </button>
        @endif
    </div>
    <hr>
   
   
    <form method="POST" action="{{ route('rooms.bulk_delete') }}" id="deleteRoomsForm">
        @csrf
        @method('DELETE')
        @if(!empty($permissions['deleteRoom']) && $permissions['deleteRoom'])

        <div class="d-flex flex-row d-flex justify-content-end mb-3">
<div class="d-flex justify-content-end mb-3 mx-2">
    <button type="button" id="toggle-age-btn" class="btn btn-outline-danger">
        <i class="fa fa-toggle-on"></i> <span class="toggle-label">Years</span>
    </button>
</div>



        <div class="d-flex justify-content-end mb-3">

            <button type="submit" class="btn btn-outline-danger"
                onclick="return confirm('Are you sure to delete selected rooms?')">
                <i class="fa fa-trash"></i> Delete Selected
            </button>
        </div>
        </div>

        


        @endif

        <div class="row clearfix" style="margin-bottom: 43px;">
            @foreach($getrooms as $room)
            <div class="col-lg-4 col-md-4 mb-1 room-card" data-room-name="{{ strtolower($room->name) }}">
                <div class="card shadow-sm border-0 rounded p-3 hover-shadow position-relative"
                    style="    height: 165px;">

                    <input type="checkbox" name="selected_rooms[]" value="{{ $room->roomid }}"
                        class="form-check-input position-absolute"
                        style="top: 13px; left: 26px; z-index: 2;width: 15px; height: 15px;">

                    <div class="d-flex justify-content-between align-items-start mb-2"
                        style="margin-left: 20px; margin-top: -3px;">
                        <a href="{{ route('room.children', ['roomid' => $room->id]) }}"
                            style="text-decoration: none; color: inherit;">
                            <h5 class="mb-0">
                                {{ $room->name }}
                                <small class="text-muted" style="font-size: 0.8rem;">({{ $room->status }})</small>
                            </h5>
                        </a>

                        <!-- Trigger -->
                        @if(!empty($permissions['editRoom']) && $permissions['editRoom'])

                        <button type="button" class="btn btn-sm " onclick='openEditModal(@json($room))'
                            style="background-color: #f0ece4;">
                            <i class="fa fa-edit" class="d-flex justify-content-between align-items-start"></i>
                        </button>




                        @endif
                    </div>

                  <div class="mb-2 children-age" 
     data-age-from="{{ $room->ageFrom }}" 
     data-age-to="{{ $room->ageTo }}">
    <i class="fa fa-children me-2" style="color:blue"></i>
    <span class="age-text">
        Age Group (years): {{ $room->ageFrom }} to {{ $room->ageTo }}
    </span>
</div>

                    <div class="mb-2">
                        <i class="fa fa-children me-2" style="color:blue"></i>
                        Children: {{ count($room->children) }}
                    </div>

                    <div class="mb-2">
                        <i class="fa fa-chalkboard-teacher me-2" style="color:blue"></i>
                        Educators:

                        @php
                        // educators array me se sirf userid nikaal lo
                        $educatorIds = collect($room->educators)->pluck('userid')->toArray();

                        // fir DB query karo in ids ke against
                        $educatorsCenterdata = DB::table('usercenters')
                        ->join('users','users.id','=','usercenters.userid')
                        ->where('usercenters.centerid', $room->centerid) // ya $centerid
                        ->whereIn('users.id', $educatorIds)
                        ->select('users.*')
                        ->get();

                        $total = count($educatorsCenterdata);
                        @endphp


                        {{-- @foreach($educators->take(5) as $educator)
                        <img src="{{ isset($educator->imageUrl) && $educator->imageUrl ? asset($educator->imageUrl) : asset('storage/children/images/download.jpg') }}"
                            class="rounded-circle border"
                            style="width: 35px; height: 35px; object-fit: cover; margin-right: 4px;"
                            title="{{ ucfirst($educator->person_name ?? '') }}">
                        @endforeach --}}

                        @foreach($educatorsCenterdata->take(5) as $educator)
                        <img src="{{ $educator->imageUrl ? asset($educator->imageUrl) : asset('storage/children/images/download.jpg') }}"
                            class="rounded-circle border"
                            style="width: 35px; height: 35px; object-fit: cover; margin-right: 4px; cursor:pointer;"
                            title="{{ ucfirst($educator->name ?? '') }}"
                            onclick='showRoomEducators(@json($educatorsCenterdata))'>
                        @endforeach

                        @if($total > 5)
                        <span
                            class="rounded-circle border bg-light d-inline-flex align-items-center justify-content-center"
                            style="width: 35px; height: 35px; font-size: 14px; font-weight: bold; margin-right: 4px;">
                            +{{ $total - 5 }}
                        </span>
                        @endif

                    </div>


                    {{-- <div class="mb-1">
                        <i class="fa fa-user text-secondary me-2"></i>
                        <span class="text-muted">Lead:</span> &nbsp;{{ Auth::user()->username ?? 'Not Assigned' }}
                    </div> --}}
                </div>
            </div>
            @endforeach
        </div>
    </form>
</div>

<!-- Room Creation Modal -->
<div class="modal" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content card">
            <div class="modal-header">
                <h5 class="modal-title" id="roomModalLabel">Create Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('room_create') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="dcenterid" value="{{ session('user_center_id') }}">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="txtRoomName">Name</label>
                            <input type="text" name="room_name" id="txtRoomName" placeholder="e.g Adventures"
                                class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txtRoomCapacity">Capacity</label>
                            <input type="text" name="room_capacity" id="txtRoomCapacity" placeholder="e.g 20"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="txtFromAge">From Age</label>
                            <input type="text" name="ageFrom" id="txtFromAge" min="0" placeholder="e.g 0"
                                class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txtToAge">To Age</label>
                            <input type="text" name="ageTo" id="txtToAge" min="0" placeholder="e.g 5"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="txtRoomStatus">Status</label>
                            <select name="room_status" id="txtRoomStatus" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" style="height: 35px;display:none">
                            <label for="txtRoomColor">Color</label>
                            <input type="color" name="room_color" id="txtRoomColor" value="#009DFF" class="form-control"
                                style="height: 35px;" required>
                        </div>


                        <div class="form-group col-md-6">
                            <label for="txtRoomEducators">Educators</label>
                            <div class="educator-checkbox-list">
                                @foreach($roomStaffs as $data)
                                <div class="checkbox-item">
                                    <input type="checkbox" id="educator_{{ $data->staffid }}" name="educators[]"
                                        value="{{ $data->staffid }}">
                                    <label for="educator_{{ $data->staffid }}">{{ $data->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" id="save-room-btn">Submit</button>

                    </div>
                </form>
            </div>


        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal" id="editRoomModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" id="editRoomForm">
            @csrf
            <div class="modal-content card">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body row">
                    <input type="hidden" id="editRoomId" name="room_id">

                    <div class="form-group col-md-6">
                        <label for="editRoomName">Room Name</label>
                        <input type="text" name="room_name" id="editRoomName" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="editRoomCapacity">Room Capacity</label>
                        <input type="number" name="room_capacity" id="editRoomCapacity" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="editAgeFrom">Age From</label>
                        <input type="number" name="ageFrom" id="editAgeFrom" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="editAgeTo">Age To</label>
                        <input type="number" name="ageTo" id="editAgeTo" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="editRoomStatus">Room Status</label>
                        <select name="room_status" id="editRoomStatus" class="form-control" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6" style="display:none">
                        <label for="editRoomColor">Room Color</label>
                        <input type="color" name="room_color" id="editRoomColor" class="form-control form-control-color"
                            required style="height: 35px;">
                    </div>

                    <div class="form-group col-6">
                        <label>Educators</label>
                        <div class="border rounded p-2" style="height: 150px; overflow-y: auto;" id="editEducators">
                            @foreach($roomStaffs as $data)
                            <div class="form-check d-flex align-items-center mb-2">
                                <input type="checkbox" class="form-check-input edit-educator-checkbox"
                                    id="educator_{{ $data->id }}" name="educators[]" value="{{ $data->id }}">
                                <label class="form-check-label" for="educator_{{ $data->id }}">
                                    {{ $data->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>



                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Update Room</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Educator Details Modal -->
<!-- Room Educators Modal -->
<div class="modal" id="roomEducatorsModal" tabindex="-1" aria-labelledby="roomEducatorsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="roomEducatorsModalLabel">Room Educators</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row" id="roomEducatorsContainer">
                    <!-- Educators list will load here -->
                </div>
            </div>


        </div>
    </div>
</div>

<script>
    function showRoomEducators(educators) {
    let container = document.getElementById('roomEducatorsContainer');
    container.innerHTML = ''; // Purana clear

    educators.forEach(ed => {
        let img = ed.imageUrl
            ? "{{ asset('') }}" + ed.imageUrl
            : "{{ asset('storage/children/images/download.jpg') }}";

        let card = `
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 text-center p-3">
              <img src="${img}" class="rounded-circle mx-auto mb-2"
                   style="width:80px; height:80px; object-fit:cover;">
              <h6 class="fw-bold mb-1">${ed.name ?? 'N/A'}</h6>
              <p class="mb-1"><strong>Gender:</strong> ${ed.gender ?? '-'}</p>
              <p class="mb-1"><strong>Email:</strong> ${ed.email ?? '-'}</p>
            </div>
          </div>`;

        container.insertAdjacentHTML('beforeend', card);
    });

    var modal = new bootstrap.Modal(document.getElementById('roomEducatorsModal'));
    modal.show();
}
</script>



<!-- Script -->
<script>
    function openEditModal(room) {
    // Set form fields
    document.getElementById('editRoomId').value = room.roomid;
    document.getElementById('editRoomName').value = room.name;
    document.getElementById('editRoomCapacity').value = room.capacity;
    document.getElementById('editAgeFrom').value = room.ageFrom;
    document.getElementById('editAgeTo').value = room.ageTo;
    document.getElementById('editRoomStatus').value = room.status;
    document.getElementById('editRoomColor').value = room.color;

    // Reset all checkboxes first
    document.querySelectorAll('#editEducators input[type="checkbox"]').forEach(cb => cb.checked = false);

    // ✅ Check the educators of this room
    if (Array.isArray(room.assignedEducatorIds)) {
        room.assignedEducatorIds.forEach(id => {
            const checkbox = document.getElementById('educator_' + id);
            if (checkbox) checkbox.checked = true;
        });
    }

    // Set form action
    document.getElementById('editRoomForm').action = `/rooms/update/${room.roomid}`;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editRoomModal'));
    modal.show();
}
</script>


<script>
    document.getElementById('roomSearch').addEventListener('input', function () {
        let searchTerm = this.value.toLowerCase();
        let cards = document.querySelectorAll('.room-card');

        cards.forEach(card => {
            let roomName = card.getAttribute('data-room-name');
            if (roomName.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    children-months
       document.getElementById('roomSearch').addEventListener('input', function () {
        let searchTerm = this.value.toLowerCase();
        let cards = document.querySelectorAll('.room-card');

        cards.forEach(card => {
            let roomName = card.getAttribute('data-room-name');
            if (roomName.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });




</script>

<!-- <script>
$(document).ready(function () {
    let showInMonths = false;

    $('#toggle-age-btn').click(function () {
        // alert();
        showInMonths = !showInMonths;

        $('.children-age').each(function () {
           
            let ageFrom = parseFloat($(this).data('age-from'));
            let ageTo   = parseFloat($(this).data('age-to'));

            if (showInMonths) {
                // Convert years to months
                let fromMonths = Math.round(ageFrom * 12);
                let toMonths   = Math.round(ageTo * 12);
                $(this).find('.age-text').text(`Age Group (months): ${fromMonths} to ${toMonths}`);
            } else {
                // Back to years
                $(this).find('.age-text').text(`Age Group (years): ${ageFrom} to ${ageTo}`);
            }
        });

        // Toggle button text
        $(this).html(showInMonths 
            ? '<i class="fa fa-toggle-off"></i> Show in Years' 
            : '<i class="fa fa-toggle-on"></i> Show in Months'
        );
    });
});
</script> -->

<script>
$(document).ready(function () {
    let showInYears = true; // default

    $('#toggle-age-btn').on('click', function () {
        showInYears = !showInYears; // flip state

        $('.children-age').each(function () {
            let ageFrom = parseFloat($(this).data('age-from'));
            let ageTo   = parseFloat($(this).data('age-to'));

            if (showInYears) {
                // Show in years
                $(this).find('.age-text').text(`Age Group (years): ${ageFrom} to ${ageTo}`);
            } else {
                // Convert years → months
                let fromMonths = Math.round(ageFrom * 12);
                let toMonths   = Math.round(ageTo * 12);
                $(this).find('.age-text').text(`Age Group (months): ${fromMonths} to ${toMonths}`);
            }
        });

        // Update icon + label
        $(this).find('i').toggleClass('fa-toggle-on fa-toggle-off');
        $(this).find('.toggle-label').text(showInYears ? 'Years' : 'Months');
    });
});
</script>


@include('layout.footer')
@stop
