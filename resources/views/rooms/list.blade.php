@extends('layout.master')
<title>@yield('title','Rooms List')</title>

{{-- @section('parentPageTitle', '') --}}



@section('content')

<div style="margin-top: -36px;">
    <h5>Rooms List</h5>

    <form method="GET" action="{{ route('rooms_list') }}" class="d-flex justify-content-end mb-3"
        style="margin-right:30px;margin-top: -36px;">
        <div class="btn-group">
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                {{ $centers->firstWhere('id', $centerId)->centerName ?? 'Select Center' }}
            </button>
            <div class="dropdown-menu">
                @foreach ($centers as $center)
                <button type="submit" name="centerId" value="{{ $center->id }}" class="dropdown-item">
                    {{ $center->centerName }}
                </button>
                @endforeach
            </div>
        </div>
    </form>
    <hr>


    <div class="row clearfix" style="margin-bottom: 43px;">
        @foreach($getrooms as $room)
        <div class="col-lg-4 col-md-6 mb-1">
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

@include('layout.footer')
@stop

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
