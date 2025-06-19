@extends('layout.master')
@section('title', 'Rooms')
@section('parentPageTitle', '')


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
</style>

@section('content')

<<<<<<< HEAD
<div style="">
=======
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
>>>>>>> origin/main
    <h5>Rooms List</h5>





    <div class="d-flex justify-content-end mb-3" style="gap: 10px;">

        <input type="text" id="roomSearch" class="form-control w-25" placeholder="Search room name...">

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

        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#roomModal"
            style="height: 36px;">
            Create Room
        </button>
    </div>



    <hr>


    {{--
    <div class="row clearfix" style="margin-bottom: 43px;">
        @foreach($getrooms as $room)
        <div class="col-lg-4 col-md-6 mb-1 room-card" data-room-name="{{ strtolower($room->name) }}">
            <a href="{{ route('room.children', $room->roomid) }}" style="text-decoration: none; color: inherit;">
                <div class="card shadow-sm border-0 rounded p-3 hover-shadow">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0">
                            {{ $room->name }}
                            <small class="text-muted" style="font-size: 0.8rem;">({{ $room->status }})</small>
                        </h5>
                        <i class="fa fa-ellipsis-v text-muted"></i>
                    </div>

                    <div class="mb-2">
                        <i class="fa fa-child text-warning me-2"></i>
                        {{ count($room->children) }} Children
                    </div>

                    <div class="mb-2">
                        <i class="fa fa-chalkboard-teacher text-primary me-2"></i>
                        Educators:
                        @foreach($room->educators as $educator)
                        <img src="{{ isset($educator->person_sign) && $educator->person_sign ? asset('storage/' . $educator->person_sign) : asset('assets/img/default-avatar.png') }}"
                            class="rounded-circle border"
                            style="width: 35px; height: 35px; object-fit: cover; margin-right: 4px;"
                            title="{{ ucfirst($educator->person_name ?? '') }}">

                        @endforeach
                    </div>

                    <div class="mb-1">
                        <i class="fa fa-user text-secondary me-2"></i>
                        <span class="text-muted">Lead:</span> {{ Auth::user()->username ?? 'Not Assigned' }}
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div> --}}

    <form method="POST" action="{{ route('rooms.bulk_delete') }}" id="deleteRoomsForm">
        @csrf
        @method('DELETE')

        <div class="d-flex justify-content-end mb-3">

            <button type="submit" class="btn btn-outline-danger"
                onclick="return confirm('Are you sure to delete selected rooms?')">
                <i class="fa fa-trash"></i> Delete Selected
            </button>
        </div>

        <div class="row clearfix" style="margin-bottom: 43px;">
            @foreach($getrooms as $room)
            <div class="col-lg-4 col-md-6 mb-1 room-card" data-room-name="{{ strtolower($room->name) }}">
                <div class="card shadow-sm border-0 rounded p-3 hover-shadow position-relative"
                    style="    height: 165px;">

                    {{-- Checkbox --}}
                    <input type="checkbox" name="selected_rooms[]" value="{{ $room->roomid }}"
                        class="form-check-input position-absolute"
                        style="top: 18px; left: 26px; z-index: 1;width: 15px; height: 15px;">

                    {{-- Card content (still clickable, but not interfering with checkbox) --}}
                    <a href="{{ route('room.children', $room->roomid) }}"
                        style="text-decoration: none; color: inherit; margin-left: 20px;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-0">
                                &nbsp;&nbsp; {{ $room->name }}
                                <small class="text-muted" style="font-size: 0.8rem;">({{ $room->status }})</small>
                            </h5>
                            <i class="fa fa-ellipsis-v text-muted"></i>
                        </div>

                        <div class="mb-2">
                            <i class="fa fa-child text-warning me-2"></i>
                            {{ count($room->children) }} Children
                        </div>

                        <div class="mb-2">
                            <i class="fa fa-chalkboard-teacher text-primary me-2"></i>
                            Educators:
                            @foreach($room->educators as $educator)
                            <img src="{{ isset($educator->person_sign) && $educator->person_sign ? asset('storage/' . $educator->person_sign) : asset('assets/img/default-avatar.png') }}"
                                class="rounded-circle border"
                                style="width: 35px; height: 35px; object-fit: cover; margin-right: 4px;"
                                title="{{ ucfirst($educator->person_name ?? '') }}">
                            @endforeach
                        </div>

                        <div class="mb-1">
                            <i class="fa fa-user text-secondary me-2"></i>
                            <span class="text-muted">Lead:</span> {{ Auth::user()->username ?? 'Not Assigned' }}
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </form>



</div>

<!-- Room Creation Modal -->
<div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomModalLabel">New Room</h5>
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
                        <div class="form-group col-md-6" style="height: 35px;">
                            <label for="txtRoomColor">Color</label>
                            <input type="color" name="room_color" id="txtRoomColor" value="#009DFF" class="form-control"
                                style="height: 35px;" required>
                        </div>
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

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" id="save-room-btn">Submit</button>

                    </div>
                </form>
            </div>


        </div>
    </div>
</div>


{{-- <div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="filtersModalRight"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomModalLabel">Add Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="" id="form-room" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="dcenterid" value="">
                            <div class="form-group">
                                <label for="txtRoomName">Name</label>
                                <input type="text" name="room_name" id="txtRoomName" placeholder="e.g Adventures"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="txtRoomCapacity">Capacity</label>
                                <input type="text" name="room_capacity" id="txtRoomCapacity" placeholder="e.g 20"
                                    class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="txtFromAge">From Age</label>
                                        <input type="text" name="ageFrom" id="txtFromAge" min="0" placeholder="e.g 0"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="txtToAge">To Age</label>
                                        <input type="text" name="ageTo" id="txtToAge" min="0" placeholder="e.g 5"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="txtRoomStatus">Status</label>
                                        <select name="room_status" id="txtRoomStatus" class="form-control">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="txtRoomColor">Color</label>
                                        <input type="color" name="room_color" id="txtRoomColor" value="#009DFF"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtRoomEducators">Educators</label>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-room-btn" data-dismiss="modal">Save
                    changes</button>
            </div>
        </div>
    </div>
</div> --}}

<script>
    $(document).on('click', '.edit-room', function() {
		$('#form-room').append(`<input type='hidden' class='hidden-room-id' name='id' value='` + $(this).data('roomid') + `'>`);
		$('#roomModalLabel').text('Edit Room');
		$('#save-room-btn').prop('id', 'edit-room-btn');
	});

	$(document).on('click', '.add-room', function() {
		$("#form-room").find('.hidden-room-id').remove();
		$('#roomModalLabel').text('New Room');
		$('#edit-room-btn').prop('id', 'save-room-btn');
		$('#form-room').trigger("reset");
		$("#txtRoomEducators").val([]).change();
	});
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
</script>

@include('layout.footer')
@stop
