  @extends('layout.master')
@section('title', 'Announcements')
@section('parentPageTitle', 'Dashboard')
@section('page-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
<div class="text-zero top-right-button-container d-flex justify-content-end" style="margin-right: 20px;margin-top: -60px;">

                <div class="text-zero top-right-button-container">

                    <div class="btn-group mr-1">
                        <div class="dropdown">
        <button class="btn btn-outline-info btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important;left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-info' : '' }}"
                 style="background-color:white;"  data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>

                    </div>

                      <div class="btn-group mr-1">
                       <div class="dropdown mr-2">
        @if(empty($rooms))
            <div class="btn btn-outline-info btn-lg dropdown-toggle">NO ROOMS AVAILABLE</div>
        @else
            <button class="btn btn-outline-info btn-lg dropdown-toggle" type="button" id="roomDropdown" data-toggle="dropdown">
                {{ strtoupper($rooms->firstWhere('id', request('roomid', $roomid))->name ?? 'Select Room') }}
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="roomDropdown">
                @foreach($rooms as $room)
                    <a class="dropdown-item" href="{{ url()->current() }}?centerid={{ $centerid }}&roomid={{ $room->id }}">
                        {{ strtoupper($room->name) }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

                    </div>

                    @if(isset($permission) && $permission->add == 1)
                        <!-- <a href="#" class="btn btn-primary btn-lg top-right-button" id="addnewbtn" data-toggle="modal" data-target="#templateModal">ADD NEW</a> -->
                    @endif

                    @if(Auth::user()->userType != 'Parent')
                 
                     
                        <a href="{{ route('Accidents.create', ['centerid' => $selectedCenter ?? $centers->first()->id,'roomid' => $roomid ?? $rooms->first()->id]) }}" class="btn btn-info btn-lg">ADD NEW ACCIDENT</a>
                    

                    @endif
                </div>

</div>

<main style="padding-block:5em;padding-inline:2em;">
    <div class="container-fluid">
        <div class="row">
              <div class="col-12 service-details-header">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
 <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4">
  <h2 class="mb-0">Daily Journel</h2>
  <p class="mb-0 text-muted mx-md-4">
    <a href="">Dashboard</a><span class="mx-2">|</span> <span>Accidents List</span>
  </p>
</div>



    </div>
    <hr class="mt-3">
  </div>   
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 mb-4 accidentListCont">
                <div class="card">
                    <div class="card-body">
                        <table class="table data-table data-tables-pagination">
                            <thead>
                                <tr>
                                    <th scope="col">S No</th>
                                    <th scope="col">Child Name</th>
                                    <th scope="col">Created By</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accidents as $index => $accident)
                                    <tr>
                                        <th scope="row"> {{ $loop->iteration + ($accidents->currentPage() - 1) * $accidents->perPage() }}</th>
                                        <td>
                                       <a  class="text-info" href="{{ route('Accidents.details') }}?id={{ $accident->id }}&centerid={{ $centerid }}&roomid={{ $roomid }}">
    {{ $accident->child_name }}
</a>

<a href="{{ route('Accidents.edit') }}?id={{ $accident->id }}&centerid={{ $centerid }}&roomid={{ $roomid }}"
   class="ml-2 text-info"
   data-toggle="tooltip"
   data-placement="top"
   title="Edit Record">
   <i class="fas fa-pencil-alt"></i>
</a>

                                        </td>
                                        <td>{{ $accident->username }}</td>
                                        <td>{{ \Carbon\Carbon::parse($accident->incident_date)->format('d.m.Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
              <div class="mt-3 float-center mx-auto">
        {{ $accidents->appends(request()->query())->links() }}
    </div>
        </div>
    </div>
</main>

    @endsection
    @push('scripts')
    	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        

   
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});


// rtooltip ends 
    $(document).ready(function () {
        $('#new-accident-btn').on('click', function (event) {
            var _centerid = $(this).data('centerid');
            var _roomid = $(this).data('roomid');
            var _url = '{{ url("accident/add") }}' + '?centerid=' + _centerid + '&roomid=' + _roomid;
            window.location.href = _url;
        });
    });

    $('#roomId').on('change', function (event) {
        var _centerid = $('#centerid').val();
        var _roomid = $('#roomId').val();
        var _url = '{{ url("accident") }}' + '?centerid=' + _centerid + '&roomid=' + _roomid;
        window.location.href = _url;
    });

    $("#centerid").on('change', function () {
        let _centerid = $(this).val();
        $.ajax({
            url: '{{ route("Accidents.getCenterRooms") }}',
            type: 'POST',
            data: {
                centerid: _centerid,
                _token: '{{ csrf_token() }}'
            },
        }).done(function (res) {
            if (res.Status === "SUCCESS") {
                $("#roomId").empty();
                $("#roomId").append(`<option value="">-- Select Room --</option>`);
                $.each(res.Rooms, function (index, val) {
                    $("#roomId").append(`<option value="${val.id}">${val.name}</option>`);
                });
            } else {
                console.log(res.Message);
                $("#roomId").empty();
                $("#roomId").append(`<option value="">No room found!</option>`);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX error:", textStatus);
        });
    });
</script>

    @endpush
    @include('layout.footer')
