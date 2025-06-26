@extends('layout.master')
@section('title', 'Head Checks')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('content')

<div class="d-flex justify-content-end align-items-center" style="margin-right: 20px; margin-top: -60px; gap: 10px; flex-wrap: wrap;">

    {{-- Center Dropdown --}}
    <div class="dropdown mr-2">
        <button class="btn btn-outline-primary btn-lg dropdown-toggle"
                type="button" id="centerDropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            {{ $centers->firstWhere('id', session('user_center_id'))?->centerName ?? 'Select Center' }}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="centerDropdown" style="top:3% !important; left:13px !important;">
            @foreach($centers as $center)
                <a href="javascript:void(0);"
                   class="dropdown-item center-option {{ session('user_center_id') == $center->id ? 'active font-weight-bold text-primary' : '' }}"
                   style="background-color:white;" data-id="{{ $center->id }}">
                    {{ $center->centerName }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Room Dropdown --}}
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

    {{-- Date Picker --}}
    @php
        $calDate = request('date')
            ? \Carbon\Carbon::parse(request('date'))->format('d-m-Y')
            : \Carbon\Carbon::parse($date ?? now())->format('d-m-Y');
    @endphp
    <div class="form-group mb-0">
        <div class="input-group date">
            <input type="text" class="form-control drop-down" id="txtCalendar" name="start_date" value="{{ $calDate }}">
            <span class="input-group-text input-group-append custom-cal">
                <i class="simple-icon-calendar"></i>
            </span>
        </div>
    </div>

</div>


<main class="default-transition" style="padding-block:5em;padding-inline:2em;">
    <div class="default-transition">
        <div class="container-fluid">
            <div class="row">
                    <!-- <div class="col-12 service-details-header">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
 <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end gap-4">
  <h2 class="mb-0">Daily Journel</h2>
  <p class="mb-0 text-muted mx-md-4">
    <a href="">Dashboard</a><span class="mx-2">|</span> <span>Head Checks</span>
  </p>
</div>

    </div>
    <hr class="mt-3">
  </div>    -->

                {{-- Head Check Form --}}
                <div class="col-12">
                    <form action="{{ route('headchecks.store') }}" method="POST" id="headCheckForm">
                        @csrf
                        <input type="hidden" name="roomid" value="{{ request('roomid', $roomid) }}">
                        <input type="hidden" name="centerid" value="{{ request('centerid', $centerid) }}">
                        <input type="hidden" name="diarydate" value="{{ $calDate }}">

                        <div class="card p-4">
                            <div id="form-fields">
                                @php $i = 1; @endphp
                                @forelse($headChecks as $key => $hc)
                                    @php
                                        [$hour, $mins] = $hc->time 
                                            ? explode(':', str_replace(['h','m'], '', $hc->time)) 
                                            : [now()->format('G'), now()->format('i')];
                                    @endphp

                                    <div class="form-row row InnerHeadCheck w-100">
                                        <div class="form-group col-md-3">
                                            <label>Time</label><br>
                                            <input type="hidden" name="headcheck" id="headcheckid" value="{{$hc->id}}">
                                            <input type="number" name="hour[]" min="0" max="24" class="form-hour form-number w-40" value="{{ $hour }}"> H :
                                            <input type="number" name="mins[]" min="0" max="59" class="form-mins form-number w-40" value="{{ $mins }}"> M
                                            <i class="fa-solid fa-clock"></i>
                                            <input type="time" name="timePicker[]" class="form-time" value="{{ sprintf('%02d:%02d', $hour, $mins) }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Head Count</label>
                                            <input type="number" class="form-control" name="headCount[]" value="{{ $hc->headcount }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Signature</label>
                                            <input type="text" class="form-control" name="signature[]" value="{{ $hc->signature }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Comments</label>
                                            <input type="text" class="form-control" name="comments[]" value="{{ $hc->comments }}">
                                        </div>

                                        @if($i != 1 && $date == now()->format('Y-m-d'))
                                            <div class="form-group col-md-1 mt-4">
                                                <a href="#!" class="btn btn-outline-danger minus-btn">Remove</a>
                                            </div>
                                        @endif
                                    </div>
                                    @php $i++; @endphp
                                @empty
                                    {{-- Empty form if no records 
                                     @include('headchecks.partials.empty-row')
                                   --}}
                                @endforelse
                              
                            </div>

                            {{-- Action Buttons --}}
                            @if(($date ?? now()->format('Y-m-d')) == now()->format('Y-m-d'))
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-outline-primary add-btn">+ New</button>
                                    <button type="submit" class="btn btn-outline-success" id="save_headcheck">Save</button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
    flatpickr("#txtCalendar", {
        dateFormat: "d-m-Y",
        defaultDate: "{{ $calDate }}",
        maxDate: "today"
    });
</script>


<script>
$(document).ready(function() {

    function syncTimePicker(row) {
        const hourInput = row.querySelector('.form-hour');
        const minsInput = row.querySelector('.form-mins');
        const timePicker = row.querySelector('.form-time');

        if (!hourInput || !minsInput || !timePicker) {
            console.error('Missing inputs in row');
            return;
        }

        timePicker.addEventListener('change', function () {
            const [hour, mins] = this.value.split(':');
            hourInput.value = hour;
            minsInput.value = mins;
        });

        hourInput.addEventListener('change', function () {
            timePicker.value = `${hourInput.value.padStart(2, '0')}:${minsInput.value.padStart(2, '0')}`;
        });

        minsInput.addEventListener('change', function () {
            timePicker.value = `${hourInput.value.padStart(2, '0')}:${minsInput.value.padStart(2, '0')}`;
        });
    }

    document.querySelectorAll('.rowInnerHeadCheck, .InnerHeadCheck').forEach(row => {
        syncTimePicker(row);
    });

    $('.add-btn').on('click', function () {
        const currentTime = new Date().toLocaleTimeString('en-AU', { timeZone: 'Australia/Sydney', hour12: false, hour: '2-digit', minute: '2-digit' });
        const [hour, mins] = currentTime.split(':');

        const newRow = `
            <div class="row rowInnerHeadCheck form-row w-100">
                <div class="form-group col-md-3 col-sm-12">
                    <label>Time</label><br>
                    <input type="number" min="0" max="24" value="${hour}" name="hour[]" class="form-hour form-number w-40"> H :
                    <input type="number" min="00" max="59" value="${mins}" name="mins[]" class="form-mins form-number w-40"> M
                    &nbsp;<i class="fa-solid fa-clock"></i>&nbsp;<input type="time" name="timePicker[]" class="form-time" value="${currentTime}">
                </div>
                <div class="form-group col-md-3 col-sm-12">
                    <label>Head Count</label>
                    <input type="number" class="form-control" name="headCount[]">
                </div>
                <div class="form-group col-md-3 col-sm-12">
                    <label>Signature</label>
                    <input type="text" class="form-control" name="signature[]">
                </div>
                <div class="form-group commentGroup col-md-3 col-sm-12">
                    <label>Comments</label>
                    <input type="text" class="form-control commentField" name="comments[]">
                </div>
                <div class="btn-group" style="display:contents;">
                    <div class="form-group lastGroup col-md-1 col-sm-12" style="margin-top: 28px;">
                        <a href="#!" class="btn btn-outline-danger minus-btn btn-block" style="width: fit-content;">Remove</a>
                    </div>
                </div>
            </div>
        `;

        $('#form-fields').append(newRow);
        const addedRow = $('#form-fields .rowInnerHeadCheck').last()[0];
        syncTimePicker(addedRow);
    });

    $(document).on('click', '.minus-btn', function () {
      

          let row = $(this).closest('.rowInnerHeadCheck, .InnerHeadCheck');

    // This finds the input with class .headcheckid inside only that row
    let headCheckId = row.find('#headcheckid').val();
    // alert(headCheckId);

  if (headCheckId) {
    $.ajax({
        url: "{{ route('headcheck.delete') }}",
        type: "POST",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            headCheckId: headCheckId
        },
        success: function(response) {
            if (response.Status === 'SUCCESS') {
                // alert('Deleted successfully');
               window.location.href = "{{ route('headChecks') }}";

                // Optionally remove the row from DOM
                // $(this).closest('.rowInnerHeadCheck').remove();
            } else {
                alert(response.Message || 'Failed to delete');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
        }
    });
}else{
  $(this).closest('.rowInnerHeadCheck, .InnerHeadCheck').remove();
}
    });

    // Fetch rooms based on center ID
    $(document).on('change', '#centerId', function () {
        const centerId = $(this).val();

        $.ajax({
            url: "{{ route('headchecks.getCenterRooms') }}", // Update this route name
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'x-device-id': '', // Fill if needed
                'x-token': '609ca994bf421'
            },
            data: {
                userid: 3,
                centerId: centerId
            },
            success: function (res) {
                $("#roomid").html('<option>-- Select Room --</option>');
                if (res.Rooms) {
                    res.Rooms.forEach(function (room) {
                        $("#roomid").append('<option value="' + room.id + '">' + room.name + '</option>');
                    });
                }
            },
            error: function (err) {
                console.error("Failed to fetch rooms", err);
            }
        });
    });

    // On room change, submit the form
    $(document).on('change', '#roomid', function () {
        $('#headCheckForm').submit();
    });

    // On calendar change, redirect
    $(document).on('change', '#txtCalendar', function () {
        let date = $(this).val();
        // alert(date);
        let url = "{{ url('headChecks') }}?centerid={{ $centerid }}&roomid={{ $roomid }}&date=" + date;
        window.location.href = url;
    });

    // Save form
    $(document).on('click', '#save_headcheck', function () {
        $('#headCheckForm').submit();
    });
});

// $(document).on('click', '.minus-btn', function () {
//     alert();
//     // This finds the current row that contains the clicked button
//     let row = $(this).closest('.rowInnerHeadCheck, .InnerHeadCheck');

//     // This finds the input with class .headcheckid inside only that row
//     let headCheckId = row.find('.headcheckid').val();

//   if (headCheckId) {
//     $.ajax({
//         url: "{{ route('headcheck.delete') }}",
//         type: "POST",
//         dataType: "json",
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         data: {
//             headCheckId: headCheckId
//         },
//         success: function(response) {
//             if (response.Status === 'SUCCESS') {
//                 // alert('Deleted successfully');
//                 window.href.reload();
//                 // Optionally remove the row from DOM
//                 // $(this).closest('.rowInnerHeadCheck').remove();
//             } else {
//                 alert(response.Message || 'Failed to delete');
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error('AJAX error:', error);
//         }
//     });
// }


//     console.log('Selected ID:', headCheckId);
// });

</script>

@endpush
@include('layout.footer')