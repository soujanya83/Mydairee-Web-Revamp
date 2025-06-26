@extends('layout.master')
@section('title', 'Daily Diary')
@section('parentPageTitle', 'Dashboard')

@section('page-styles')
   <style>
        .drop-down{
            border: 1px solid #008ecc!important;
            border-bottom-left-radius: 50px!important;
            border-bottom-right-radius: 50px!important;
            border-top-left-radius: 50px!important;
            border-top-right-radius: 50px!important;
            background-color: transparent!important;
            color: #008ecc!important;
            text-transform: uppercase!important;
            font-weight: bold!important;
            display: block!important;
            line-height: 19.2px!important;
            font-size: 12.8px!important;
            letter-spacing: 0.8px!important;
            vertical-align: middle!important;
            padding: 12px 41.6px 9.6px 41.6px!important;
            height: 42.78px!important;
            text-align: center!important;
            -webkit-transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,-webkit-box-shadow 0.15s ease-in-out;
            transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,-webkit-box-shadow 0.15s ease-in-out;
            transition-property: color, background-color, border-color, box-shadow, -webkit-box-shadow;
            transition-duration: 0.15s, 0.15s, 0.15s, 0.15s, 0.15s;
            transition-timing-function: ease-in-out, ease-in-out, ease-in-out, ease-in-out, ease-in-out;
            transition-delay: 0s, 0s, 0s, 0s, 0s;
            transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;
            transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out,-webkit-box-shadow 0.15s ease-in-out;
        }

        .drop-down:hover{
            color: #ffffff!important;
            background-color: #008ecc!important;
        }
        .custom-cal{
            position: absolute;
            vertical-align: middle;
            top: 8px;
            right: 10px;
            border: none;
            color: #0085bf;
            background: transparent;
            pointer-events: none;
        }
        .custom-cal:hover{
            color: #ffffff;
            background-color: transparent;
        }
        .input-group-text{
            color: #008ecc!important;
            background-color: transparent!important;
        }
        .btn-lg{
            height: 42.78px!important;
        }
        .form-number{
            border: 1px solid #d7d7d7;
            outline: none;
            height: 35px;
        }
        .dailyDiaryTable  td {
            text-align: center!important;
        }
        .dailyDiaryTable tr.records > td:first-child {
            text-align: left!important;
            align-items: center;
            font-size: 15px;
            font-weight: 600;
        }

        .theme-link {
            color: #007bff!important;
        }

        .theme-link:hover {
            color: #000000!important;
        }

        .dailyDiaryTable  th {
            text-align: center!important;
        }
        .dailyDiaryTable tr> th:first-child {
            text-align: left!important;
            align-items: center;
        }
        .common-dd-tbl td, .common-dd-tbl th{
            align-items: center!important;
            text-align: center!important;
        }
        .x-small{
            height: 40px!important;
            width: 40px!important;
        }
        td{
            vertical-align: middle!important;
        }
        .table-header {
            position: sticky;
            top:0;
        }

        @media (max-width: 575px) {
        .top-right-button-container {
          flex-wrap: wrap;
          
       }
}

    </style>
@endsection

@section('content')
<main data-centerid="{{ $centerid ?? $centerid }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Daily Diary</h1>

                <div class="text-zero top-right-button-container d-flex flex-row">
                    <div class="btn-group mr-1">
                        @php
                            $dupArr = [];
                            $centersList = session('centerIds', []);
                        @endphp

                        @if (empty($centersList))
                            <div class="btn btn-outline-primary btn-lg dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                EMPTY CENTER
                            </div>
                        @else
                            @if (request()->has('centerid'))
                                @foreach ($centersList as $center)
                                    @if (!in_array($center, $dupArr) && request('centerid') == $center->id)
                                        <div class="btn btn-outline-primary btn-lg dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ strtoupper($center->centerName) }}
                                        </div>
                                    @endif
                                    @php $dupArr[] = $center; @endphp
                                @endforeach
                            @else
                                <div class="btn btn-outline-primary btn-lg dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ strtoupper($centersList[0]->centerName) }}
                                </div>
                            @endif

                            <div class="dropdown-menu dropdown-menu-right">
                                @foreach ($centersList as $center)
                                    <a class="dropdown-item" href="{{ url()->current() }}?centerid={{ $center->id }}">
                                        {{ strtoupper($center->centerName) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="btn-group mr-1">
                        @if (empty($rooms))
                            <div class="btn btn-outline-primary btn-lg dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                NO ROOMS AVAILABLE
                            </div>
                        @else
                            @foreach ($rooms as $rObj)
                                @if (request()->has('roomid') && request('roomid') == $rObj->id || (!request()->has('roomid') && $rObj->id == $roomid))
                                    <div class="btn btn-outline-primary btn-lg dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ strtoupper($rObj->name) }}
                                    </div>
                                @endif
                            @endforeach

                            <div class="dropdown-menu dropdown-menu-right">
                                @foreach ($rooms as $rObj)
                                    <a class="dropdown-item" href="{{ url()->current() }}?centerid={{ $centerid }}&roomid={{ $rObj->id }}">
                                        {{ strtoupper($rObj->name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @php
                        $calDate = request()->has('date') && !empty(request('date'))
                            ? date('d-m-Y', strtotime(request('date')))
                            : (isset($date) ? date('d-m-Y', strtotime($date)) : date('d-m-Y'));
                    @endphp

                    <div class="form-group">
                        <div class="input-group date">
                            <input type="text" class="form-control drop-down" id="txtCalendar" name="start_date" value="{{ $calDate }}">
                            <span class="input-group-text input-group-append input-group-addon custom-cal">
                                <i class="simple-icon-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.university') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Daily Diary</li>
                    </ol>
                </nav>
                <div class="separator mb-5"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-5">
                    <div class="card-body" style="overflow: scroll;">
                        <h5 class="card-title">Add or View Information</h5>
                        <table class="dailyDiaryTable table table-bordered" width="100%">
                            <thead style="position: sticky; top: 60px; background: #FFFFFF; box-shadow: 0px 1px 1px #d7d7d7;">
                                <tr>
                                    <th class="child-name-cell-title table-header">
                                        @if (auth()->user()->UserType != 'Parent')
                                            <input type="checkbox" id="checkAllStudents">
                                        @endif
                                        <span>Child Name</span>
                                    </th>
                                    @if ($columns?->breakfast == 1)
                                        <th class="table-header">Breakfast</th>
                                    @endif
                                    @if ($columns?->morningtea == 1)
                                        <th class="table-header">Morning Tea</th>
                                    @endif
                                    @if ($columns?->lunch == 1)
                                        <th class="table-header">Lunch</th>
                                    @endif
                                    @if ($columns?->sleep == 1)
                                        <th class="table-header">Sleep</th>
                                    @endif
                                    @if ($columns?->afternoontea == 1)
                                        <th class="table-header">Afternoon Tea</th>
                                    @endif
                                    @if ($columns?->latesnacks == 1)
                                        <th class="table-header">Late Snacks</th>
                                    @endif
                                    @if ($columns?->sunscreen == 1)
                                        <th class="table-header">SunScreen</th>
                                    @endif
                                    @if ($columns?->toileting == 1)
                                        <th class="table-header">Toileting</th>
                                    @endif
                                    <th class="table-header">Bottle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($childs))
                                    <tr>
                                        <td colspan="9" class="text-center">No children are there in this room</td>
                                    </tr>
                                @else
                                    @foreach ($childs as $cobj)
                                       @php
    $childImage = empty($cobj->imageUrl)
        ? 'https://via.placeholder.com/50'
        : asset('assets/media/' . $cobj->imageUrl);
    $centerid = $centerid ?? 1;
@endphp

                                        <tr class="records">
                                            <td class="kids-cell d-flex flex-row justify-content-start">
                                                @if (auth()->user()->UserType != 'Parent')
                                                    <input type="checkbox" id="child-{{ $cobj->id }}" class="check-kids" value="{{ $cobj->id }}" name="kids[]">
                                                @endif
                                                <label for="child-{{ $cobj->id }}">
                                                    <img src="{{ $childImage }}" class="img-thumbnail border-0 mx-1 rounded-circle list-thumbnail x-small" alt="">
                                                </label>
                                                <a class="theme-link" href="{{ route('dailyDiary.viewChildDiary') }}?childid={{ $cobj->id }}&date={{ $date }}&centerid={{ $centerid }}&roomid={{ $roomid }}">
                                                    {{ $cobj->name }}
                                                </a>
                                            </td>
                                            @if ($columns?->breakfast == 1)
                                                <td>
                                                    @if (empty($cobj->breakfast?->startTime))
                                                        @if (auth()->user()->UserType != 'Parent')
                                                            <button class="btn btn-outline-primary btn-sm btn-add" data-toggle="modal" data-target="#foodModal" data-bgcolor="#FFECB3" data-title="Add Breakfast" data-type="BREAKFAST" data-childid="{{ $cobj->id }}">Add</button>
                                                        @else
                                                            <p>Not Updated</p>
                                                        @endif
                                                    @else
                                                        {{ $cobj->breakfast->startTime }}
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($columns?->morningtea == 1)
                                                <td>
                                                    @if (empty($cobj->morningtea?->startTime))
                                                        @if (auth()->user()->UserType != 'Parent')
                                                            <button class="btn btn-outline-primary btn-sm btn-add" data-toggle="modal" data-target="#foodModal" data-bgcolor="#C0CCD9" data-title="Add Morning Tea" data-type="morningtea" data-childid="{{ $cobj->id }}">Add</button>
                                                        @else
                                                            <p>Not Updated</p>
                                                        @endif
                                                    @else
                                                        {{ $cobj->morningtea->startTime }}
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($columns?->lunch == 1)
                                                <td>
                                                    @if (empty($cobj->lunch?->startTime))
                                                        @if (auth()->user()->UserType != 'Parent')
                                                            <button class="btn btn-outline-primary btn-sm btn-add" data-toggle="modal" data-target="#foodModal" data-bgcolor="#D0E2FD" data-title="Add Lunch" data-type="lunch" data-childid="{{ $cobj->id }}">Add</button>
                                                        @else
                                                            <p>Not Updated</p>
                                                        @endif
                                                    @else
                                                        {{ $cobj->lunch->startTime }}
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($columns?->sleep == 1)
                                                <td>
                                                    @if (empty($cobj->sleep[0]?->startTime))
                                                        @if (auth()->user()->UserType != 'Parent')
                                                            <button class="btn btn-outline-primary btn-sm btn-add btn-sleep" data-toggle="modal" data-target="#sleepModal" data-bgcolor="#F5E18F" data-title="Add Sleep" data-type="sleep" data-childid="{{ $cobj->id }}">Add</button>
                                                        @else
                                                            <p>Not Updated</p>
                                                        @endif
                                                    @else
                                                        {{ $cobj->sleep[0]->startTime }} to {{ $cobj->sleep[0]->endTime }}
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($columns?->afternoontea == 1)
                                                <td>
                                                    @if (empty($cobj->afternoontea?->startTime))
                                                        @if (auth()->user()->UserType != 'Parent')
                                                            <button class="btn btn-outline-primary btn-sm btn-add" data-toggle="modal" data-target="#foodModal" data-bgcolor="#F0CDFF" data-title="Add Afternoon Tea" data-type="afternoontea" data-childid="{{ $cobj->id }}">Add</button>
                                                        @else
                                                            <p>Not Updated</p>
                                                        @endif
                                                    @else
                                                        {{ $cobj->afternoontea->startTime }}
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($columns?->latesnacks == 1)
                                                <td>
                                                    @if (empty($cobj->snacks?->startTime))
                                                        @if (auth()->user()->UserType != 'Parent')
                                                            <button class="btn btn-outline-primary btn-sm btn-add" data-toggle="modal" data-target="#foodModal" data-bgcolor="#FEC093" data-title="Add Snacks" data-type="snacks" data-childid="{{ $cobj->id }}">Add</button>
                                                        @else
                                                            <p>Not Updated</p>
                                                        @endif
                                                    @else
                                                        {{ $cobj->snacks->startTime }}
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($columns?->sunscreen == 1)
                                                <td>
                                                    @if (empty($cobj->sunscreen[0]?->startTime))
                                                        @if (auth()->user()->UserType != 'Parent')
                                                            <button class="btn btn-outline-primary btn-sm btnSunscreen" data-toggle="modal" data-target="#sunscreenModal" data-bgcolor="#E07F7F" data-title="Add Sunscreen" data-type="sunscreen" data-childid="{{ $cobj->id }}">Add</button>
                                                        @else
                                                            <p>Not Updated</p>
                                                        @endif
                                                    @else
                                                        @php
                                                            $totalMinutes = 0;
                                                        @endphp
                                                        @if (isset($cobj->sunscreen) && is_array($cobj->sunscreen))
                                                            @foreach ($cobj->sunscreen as $toiletEntry)
                                                                {{ htmlspecialchars($toiletEntry->startTime) }}<br>
                                                                @php
                                                                    if (preg_match('/(\d+)h:(\d+)m/', $toiletEntry->startTime, $matches)) {
                                                                        $hours = (int)$matches[1];
                                                                        $minutes = (int)$matches[2];
                                                                        $totalMinutes += ($hours * 60) + $minutes;
                                                                    }
                                                                @endphp
                                                            @endforeach
                                                        @endif
                                                        {{-- @php
                                                            $totalHours = floor($totalMinutes / 60);
                                                            $remainingMinutes = $totalMinutes % 60;
                                                        @endphp
                                                        Total Time {{ $totalHours }}h:{{ str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT) }}m<br> --}}
                                                    @endif
                                                </td>
                                            @endif
                                            @if ($columns?->toileting == 1)
                                                <td>
                                                    @if (empty($cobj->toileting[0]?->startTime))
                                                        @if (auth()->user()->UserType != 'Parent')
                                                            <button class="btn btn-outline-primary btn-sm btnToileting" data-toggle="modal" data-target="#toiletingModal" data-bgcolor="#D1FFCD" data-title="Add Toileting Info" data-type="toileting" data-childid="{{ $cobj->id }}">Add</button>
                                                        @else
                                                            <p>Not Updated</p>
                                                        @endif
                                                    @else
                                                        @php
                                                            $totalMinutes = 0;
                                                        @endphp
                                                        @if (isset($cobj->toileting) && is_array($cobj->toileting))
                                                            @foreach ($cobj->toileting as $toiletEntry)
                                                                {{ htmlspecialchars($toiletEntry->startTime) }}<br>
                                                                @php
                                                                    if (preg_match('/(\d+)h:(\d+)m/', $toiletEntry->startTime, $matches)) {
                                                                        $hours = (int)$matches[1];
                                                                        $minutes = (int)$matches[2];
                                                                        $totalMinutes += ($hours * 60) + $minutes;
                                                                    }
                                                                @endphp
                                                            @endforeach
                                                        @endif
                                                        {{-- @php
                                                            $totalHours = floor($totalMinutes / 60);
                                                            $remainingMinutes = $totalMinutes % 60;
                                                        @endphp
                                                        Total Time {{ $totalHours }}h:{{ str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT) }}m<br> --}}
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if (empty($cobj->bottle[0]?->startTime))
                                                    @if (auth()->user()->UserType != 'Parent')
                                                        <button class="btn btn-outline-primary btn-sm open-bottle-modal"
                                                                data-bgcolor="#D1FFCD"
                                                                data-childid="{{ $cobj->id }}">
                                                            Add
                                                        </button>
                                                    @else
                                                        <p>Not Updated</p>
                                                    @endif
                                                @else
                                                    @if (auth()->user()->UserType == 'Parent')
                                                        @if (isset($cobj->bottle) && is_array($cobj->bottle))
                                                            @foreach ($cobj->bottle as $bottledata)
                                                                {{ date('h:i A', strtotime($bottledata->startTime)) }}<br>
                                                            @endforeach
                                                        @endif
                                                    @else
                                                        @if (isset($cobj->bottle) && is_array($cobj->bottle))
                                                            <div class="bottle-times" data-childid="{{ $cobj->id }}" data-date="{{ request()->has('date') ? request('date') : $date }}">
                                                                @foreach ($cobj->bottle as $bottledata)
                                                                    <span class="badge badge-info edit-bottle-time"
                                                                          style="cursor:pointer;"
                                                                          data-id="{{ $bottledata->id }}"
                                                                          data-time="{{ $bottledata->startTime }}">
                                                                        {{ date('h:i A', strtotime($bottledata->startTime)) }}
                                                                    </span><br>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <table class="common-dd-tbl table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    @if ($columns?->breakfast == 1)
                                        <th>Breakfast</th>
                                    @endif
                                    @if ($columns?->morningtea == 1)
                                        <th>Morning Tea</th>
                                    @endif
                                    @if ($columns?->lunch == 1)
                                        <th>Lunch</th>
                                    @endif
                                    @if ($columns?->sleep == 1)
                                        <th>Sleep</th>
                                    @endif
                                    @if ($columns?->afternoontea == 1)
                                        <th>Afternoon Tea</th>
                                    @endif
                                    @if ($columns?->latesnacks == 1)
                                        <th>Late Snacks</th>
                                    @endif
                                    @if ($columns?->sunscreen == 1)
                                        <th>SunScreen</th>
                                    @endif
                                    @if ($columns?->toileting == 1)
                                        <th>Toileting</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if ($columns?->breakfast == 1)
                                        <td>
                                            <button class="btn cmn-btn-add btn-outline-primary" data-toggle="modal" data-target="#foodModal" data-bgcolor="#FFECB3" data-title="Add Breakfast" data-type="BREAKFAST">Add</button>
                                        </td>
                                    @endif
                                    @if ($columns?->morningtea == 1)
                                        <td>
                                            <button class="btn cmn-btn-add btn-outline-primary" data-toggle="modal" data-target="#foodModal" data-bgcolor="#C0CCD9" data-title="Add Morning Tea" data-type="morningtea">Add</button>
                                        </td>
                                    @endif
                                    @if ($columns?->lunch == 1)
                                        <td>
                                            <button class="btn cmn-btn-add btn-outline-primary" data-toggle="modal" data-target="#foodModal" data-bgcolor="rgba(19, 109, 246, 0.2)" data-title="Add Lunch" data-type="lunch">Add</button>
                                        </td>
                                    @endif
                                    @if ($columns?->sleep == 1)
                                        <td>
                                            <button class="btn cmn-btn-add btn-outline-primary" data-toggle="modal" data-target="#sleepModal" data-bgcolor="rgba(239, 206, 74, 0.62)" data-title="Add Sleep" data-type="sleep">Add</button>
                                        </td>
                                    @endif
                                    @if ($columns?->afternoontea == 1)
                                        <td>
                                            <button class="btn cmn-btn-add btn-outline-primary" data-toggle="modal" data-target="#foodModal" data-bgcolor="#F0CDFF" data-title="Add Afternoon Tea" data-type="afternoontea">Add</button>
                                        </td>
                                    @endif
                                    @if ($columns?->latesnacks == 1)
                                        <td>
                                            <button class="btn cmn-btn-add btn-outline-primary" data-toggle="modal" data-target="#foodModal" data-bgcolor="#FEC093" data-title="Add Snacks" data-type="snacks">Add</button>
                                        </td>
                                    @endif
                                    @if ($columns?->sunscreen == 1)
                                        <td>
                                            <button class="btn cmn-btn-add btn-outline-primary" data-toggle="modal" data-target="#sunscreenModal" data-bgcolor="#E07F7F" data-title="Add Sunscreen" data-type="sunscreen">Add</button>
                                        </td>
                                    @endif
                                    @if ($columns?->toileting == 1)
                                        <td>
                                            <button class="btn cmn-btn-add btn-outline-primary" data-toggle="modal" data-target="#toiletingModal" data-bgcolor="#D1FFCD" data-title="Add Toileting" data-type="toileting">Add</button>
                                        </td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- Add Bottle Modal -->
<div class="modal fade" id="bottleModal" tabindex="-1" role="dialog" aria-labelledby="bottleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="bottleForm" action="{{ route('dailyDiary.storeBottle') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Bottle Time</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="childid" name="childid">
                    <input type="hidden" id="diarydate" name="diarydate" value="{{ request()->has('date') ? request('date') : ($date ?? now()->format('Y-m-d')) }}">

                    <div id="timeInputs">
                        <div class="form-group time-block">
                            <label>Time</label>
                            <div class="input-group">
                                <input type="time" name="startTime[]" class="form-control" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger btn-sm remove-time">&times;</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-secondary" id="addMoreTime">Add More Time</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Bottle Modal -->
<div class="modal fade" id="editBottleModal" tabindex="-1" role="dialog" aria-labelledby="editBottleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editBottleForm" action="{{ route('dailyDiary.storeBottle') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bottle Times</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="childid" id="edit_childid">
                    <input type="hidden" name="diarydate" id="edit_diarydate">

                    <div id="editTimeInputs">
                        <!-- Existing DB times (editable) -->
                    </div>

                    <button type="button" class="btn btn-sm btn-secondary" id="addMoreEditTime">Add More Time</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Food Modal -->
<div class="modal fade bs-example-modal-sm" id="foodModal" tabindex="-1" role="dialog" aria-labelledby="foodModalLabel">
    <div class="modal-dialog" role="document">
        <form id="addDailyFoodRecord" action="{{ route('dailyDiary.storeFood') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="foodModalLabel">Title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Time</label>
                        <br>
                        @php
                            $now = \Carbon\Carbon::now('Australia/Sydney');
                            $hour = $now->hour;
                            $mins = $now->minute;
                        @endphp
                        <input type="number" min="0" max="24" value="{{ $hour }}" name="hour" class="form-hour form-number"> H :
                        <input type="number" min="0" max="59" value="{{ $mins }}" name="mins" class="form-mins form-number"> M
                        &nbsp;<i class="fa-solid fa-clock"></i>&nbsp;
                        <input type="time" name="bfTime" id="bfTime" value="{{ sprintf('%02d:%02d', $hour, $mins) }}">
                    </div>
                    <div class="form-group common-item">
                        <label>Item</label>
                        <select name="item[]" id="item" class="form-control select2-single" multiple="multiple" data-width="100%">
                        </select>
                    </div>
                    <div class="form-group common-item">
                        <label>Calories</label>
                        <input type="text" name="calories" id="calories" class="form-control modal-form-control">
                    </div>
                    <div class="form-group common-item">
                        <label for="qty">Quantity</label>
                        <input type="text" id="qty" name="qty" class="form-control modal-form-control">
                    </div>
                    <div class="form-group">
                        <label for="comments">Comments</label>
                        <textarea name="comments" class="form-control modal-form-control" id="comments" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-small btn-default btn-small pull-right">SAVE</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sleep Modal -->
<div class="modal fade bs-example-modal-sm" id="sleepModal" tabindex="-1" role="dialog" aria-labelledby="sleepModalLabel">
    <div class="modal-dialog" role="document">
        <form id="addDailySleepRecord" action="{{ route('dailyDiary.storeSleep') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="sleepModalLabel">Add Sleep Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Time</label>
                        <br>
                        <input type="number" min="0" max="12" value="1" name="from_hour" class="form-hour from-hour form-number"> H :
                        <input type="number" min="0" max="59" value="00" name="from_mins" class="form-mins from-mins form-number"> M to
                        <input type="number" min="1" max="12" value="1" name="to_hour" class="form-hour to-hour form-number"> H :
                        <input type="number" min="0" max="59" value="00" name="to_mins" class="form-mins to-mins form-number"> M
                    </div>
                    <div class="form-group">
                        <label for="comments">Comments</label>
                        <textarea name="comments" class="form-control modal-form-control sl-comments" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm">SAVE</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Toileting Modal -->
<div class="modal fade bs-example-modal-sm" id="toiletingModal" tabindex="-1" role="dialog" aria-labelledby="toiletingModalLabel">
    <div class="modal-dialog" role="document">
        <form id="addDailyToiletingRecord" action="{{ route('dailyDiary.storeToileting') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="toiletingModalLabel">Add Toileting Info</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Time</label>
                        <br>
                        @php
                            $now = \Carbon\Carbon::now('Australia/Sydney');
                            $hour = $now->hour;
                            $mins = $now->minute;
                        @endphp
                        <input type="number" min="0" max="24" value="{{ $hour }}" name="hour" class="form-hour form-hour-toilet form-number"> H :
                        <input type="number" min="0" max="59" value="{{ $mins }}" name="mins" class="form-mins form-mins-toilet form-number"> M
                        &nbsp;<i class="fa-solid fa-clock"></i>&nbsp;
                        <input type="time" name="timePicker" id="timePicker" class="form-time" value="{{ sprintf('%02d:%02d', $hour, $mins) }}">
                    </div>
                    <div class="form-group">
                        <label for="nappy_status">Nappy Status</label><br>
                        <select class="form-control modal-form-control" name="nappy_status" id="nappy_status">
                            <option value="Dry">Dry</option>
                            <option value="Wet">Wet</option>
                            <option value="Soiled">Soiled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="signature">Signature <span style="color:red;">* Required</span></label>
                        <input type="text" class="form-control modal-form-control" name="signature" id="signature" required>
                    </div>
                    <div class="form-group">
                        <label for="comments">Comments <span style="color:red;">* Required</span></label>
                        <textarea name="comments" class="form-control modal-form-control tt-comments" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm">SAVE</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sunscreen Modal -->
<div class="modal fade bs-example-modal-sm" id="sunscreenModal" tabindex="-1" role="dialog" aria-labelledby="sunscreenModalLabel">
    <div class="modal-dialog" role="document">
        <form id="addDailySunscreenRecord" action="{{ route('dailyDiary.storeSunscreen') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="sunscreenModal">Add Sunscreen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Time</label>
                        <br>
                        @php
                            $now = \Carbon\Carbon::now('Australia/Sydney');
                            $hour = $now->hour;
                            $mins = $now->minute;
                        @endphp
                        <input type="number" min="0" max="24" value="{{ $hour }}" name="hour" class="form-hour form-hour-ss form-number"> H :
                        <input type="number" min="0" max="59" value="{{ $mins }}" name="mins" class="form-mins form-mins-ss form-number"> M
                        &nbsp;<i class="fa-solid fa-clock"></i>&nbsp;
                        <input type="time" name="timePicker" id="timePickerSs" class="form-time" value="{{ sprintf('%02d:%02d', $hour, $mins) }}">
                    </div>
                    <div class="form-group">
                        <label for="comments">Comments</label>
                        <textarea name="comments" class="form-control modal-form-control ss-comments" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm">SAVE</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const centerid = $('#center-data').data('centerid');
            const roomid = '{{ $roomid ?? 0 }}';
            const diarydate = '{{ request()->has("date") ? request("date") : ($date ?? \Carbon\Carbon::now()->format("Y-m-d")) }}';
            const userId = '{{ auth()->id() }}';

            // Sync time pickers for modals
            function syncTimePicker(modalId, hourClass, minsClass, timePickerId) {
                const hourInput = document.querySelector(`${modalId} .${hourClass}`);
                const minsInput = document.querySelector(`${modalId} .${minsClass}`);
                const timePicker = document.querySelector(`${modalId} #${timePickerId}`);

                if (!hourInput || !minsInput || !timePicker) {
                    console.error(`One or more elements not found in ${modalId}.`);
                    return;
                }

                timePicker.addEventListener('change', function() {
                    const [hour, mins] = this.value.split(':');
                    hourInput.value = hour;
                    minsInput.value = mins;
                });

                hourInput.addEventListener('change', function() {
                    timePicker.value = `${hourInput.value.padStart(2, '0')}:${minsInput.value.padStart(2, '0')}`;
                });

                minsInput.addEventListener('change', function() {
                    timePicker.value = `${hourInput.value.padStart(2, '0')}:${minsInput.value.padStart(2, '0')}`;
                });
            }

            syncTimePicker('#foodModal', 'form-hour', 'form-mins', 'bfTime');
            syncTimePicker('#toiletingModal', 'form-hour-toilet', 'form-mins-toilet', 'timePicker');
            syncTimePicker('#sunscreenModal', 'form-hour-ss', 'form-mins-ss', 'timePickerSs');

            // Food modal button click
            $(document).on('click', '.btn-add', function() {
                const title = $(this).data('title');
                const type = $(this).data('type');
                const childid = $(this).data('childid');
                const bgcolor = '#FFFFFF';

                $('#foodModal').find('input[name="childids[]"]').remove();
                $('#foodModal').find('input[name="type"]').remove();
                $('#foodModal .modal-body').append(`<input type="hidden" class="childid" name="childids[]" value="${childid}">`);
                $('#foodModal .modal-body').append(`<input type="hidden" class="type" name="type" value="${type}">`);
                $('#foodModal .modal-header').css({ background: bgcolor, color: '#000000' });
                $('#foodModal .modal-title').text(title);

                if (type === 'morningtea' || type === 'afternoontea') {
                    $('#foodModal .common-item').hide();
                } else {
                    $('#foodModal .common-item').show();
                    $('#item').select2({
                        ajax: {
                            url: '{{ route("dailyDiary.getItems") }}',
                            type: 'POST',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    searchTerm: params.term,
                                    type: type,
                                    centerid: centerid,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                };
                            },
                            processResults: function(response) {
                                return { results: response };
                            },
                            cache: true
                        },
                        dropdownParent: $('#foodModal .modal-content')
                    });
                }
            });

            // Sleep modal button click
            $(document).on('click', '.btn-sleep', function() {
                const title = $(this).data('title');
                const type = $(this).data('type');
                const childid = $(this).data('childid');
                const bgcolor = '#FFFFFF';

                $('#sleepModal').find('input[name="childids[]"]').remove();
                $('#sleepModal').find('input[name="type"]').remove();
                $('#sleepModal .modal-body').append(`<input type="hidden" class="childid" name="childids" value="${childid}">`);
                $('#sleepModal .modal-body').append(`<input type="hidden" class="type" name="type" value="${type}">`);
                $('#sleepModal .modal-header').css({ background: bgcolor, color: '#000000' });
                $('#sleepModal .modal-title').text(title);
            });

            // Toileting modal button click
            $(document).on('click', '.btnToileting', function() {
                const childid = $(this).data('childid');
                $('#toiletingModal').find('input[name="childids[]"]').remove();
                $('#toiletingModal .modal-body').append(`<input type="hidden" class="childid" name="childids[]" value="${childid}">`);
            });

            $('#toiletingModal').on('hidden.bs.modal', function() {
                $('#toiletingModal').find('input[name="childids[]"]').remove();
            });

            // Sunscreen modal button click
            $(document).on('click', '.btnSunscreen', function() {
                const childid = $(this).data('childid');
                $('#sunscreenModal').find('input[name="childids[]"]').remove();
                $('#sunscreenModal .modal-body').append(`<input type="hidden" class="childid" name="childids[]" value="${childid}">`);
            });

            $('#sunscreenModal').on('hidden.bs.modal', function() {
                $('#sunscreenModal').find('input[name="childids[]"]').remove();
            });

            // Common form submission handler
            function submitForm(formId, url, dataCallback) {
                $(document).on('submit', formId, function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const data = dataCallback(form);
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { ...data, _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Success', response.message, 'success').then(() => {
                                    form.closest('.modal').modal('hide');
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON?.errors || {};
                            let errorMsg = Object.values(errors).flat().join('<br>') || 'An error occurred';
                            Swal.fire('Error', errorMsg, 'error');
                        }
                    });
                });
            }

            // Food form submission
            submitForm('#addDailyFoodRecord', '{{ route("dailyDiary.addFoodRecord") }}', function(form) {
                const hour = form.find('.form-hour').val();
                const mins = form.find('.form-mins').val();
                const childids = form.find('input[name="childids[]"]').map(function() { return this.value; }).get();
                return {
                    startTime: `${hour}h:${mins}m`,
                    item: JSON.stringify(form.find('#item').val()),
                    qty: form.find('#qty').val(),
                    comments: form.find('#comments').val(),
                    calories: form.find('#calories').val(),
                    diarydate: diarydate,
                    childid: JSON.stringify(childids),
                    type: form.find('input[name="type"]').val()
                };
            });

            // Sleep form submission
            submitForm('#addDailySleepRecord', '{{ route("dailyDiary.addSleepRecord") }}', function(form) {
                const hour = form.find('.from-hour').val();
                const mins = form.find('.from-mins').val();
                const endhour = form.find('.to-hour').val();
                const endmins = form.find('.to-mins').val();
                const childids = form.find('input[name="childids"]').val();
                return {
                    userid: userId,
                    startTime: `${hour}h:${mins}m`,
                    endTime: `${endhour}h:${endmins}m`,
                    comments: form.find('.sl-comments').val(),
                    diarydate: diarydate,
                    childid: JSON.stringify([childids])
                };
            });

            // Toileting form submission
            submitForm('#addDailyToiletingRecord', '{{ route("dailyDiary.addToiletingRecord") }}', function(form) {
                const hour = form.find('.form-hour-toilet').val();
                const mins = form.find('.form-mins-toilet').val();
                const childids = form.find('input[name="childids[]"]').map(function() { return this.value; }).get();
                return {
                    userid: userId,
                    startTime: `${hour}h:${mins}m`,
                    nappy_status: form.find('#nappy_status').val(),
                    signature: form.find('#signature').val(),
                    comments: form.find('.tt-comments').val(),
                    diarydate: diarydate,
                    childid: JSON.stringify(childids)
                };
            });

            // Sunscreen form submission
            submitForm('#addDailySunscreenRecord', '{{ route("dailyDiary.addSunscreenRecord") }}', function(form) {
                const hour = form.find('.form-hour-ss').val();
                const mins = form.find('.form-mins-ss').val();
                const childids = form.find('input[name="childids[]"]').map(function() { return this.value; }).get();
                return {
                    startTime: `${hour}h:${mins}m`,
                    comments: form.find('.ss-comments').val(),
                    diarydate: diarydate,
                    childid: JSON.stringify(childids)
                };
            });

            // Bottle modal handling
            $(document).on('click', '.open-bottle-modal', function() {
                const childid = $(this).data('childid');
                $('#bottleModal #childid').val(childid);
                $('#bottleModal #diarydate').val(diarydate);
                $('#bottleModal').modal('show');
            });

            $('#addMoreTime').click(function() {
                $('#timeInputs').append(`
                    <div class="form-group time-block">
                        <div class="input-group">
                            <input type="time" name="startTime[]" class="form-control" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger btn-sm remove-time">×</button>
                            </div>
                        </div>
                    </div>
                `);
            });

            $('#timeInputs').on('click', '.remove-time', function() {
                $(this).closest('.time-block').remove();
            });

            $('#bottleForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("dailyDiary.addBottle") }}',
                    type: 'POST',
                    data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                    success: function(response) {
                        Swal.fire('Success', 'Bottle times added successfully', 'success').then(() => {
                            $('#bottleModal').modal('hide');
                            $('#bottleForm')[0].reset();
                            $('#timeInputs').html(`
                                <div class="form-group time-block">
                                    <div class="input-group">
                                        <input type="time" name="startTime[]" class="form-control" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger btn-sm remove-time">×</button>
                                        </div>
                                    </div>
                                </div>
                            `);
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        let errorMsg = Object.values(errors).flat().join('<br>') || 'An error occurred';
                        Swal.fire('Error', errorMsg, 'error');
                    }
                });
            });

            // Edit bottle modal handling
            $('.bottle-times').on('click', '.edit-bottle-time', function() {
                const parentDiv = $(this).closest('.bottle-times');
                const childid = parentDiv.data('childid');
                const diarydate = parentDiv.data('date');

                $('#edit_childid').val(childid);
                $('#edit_diarydate').val(diarydate);
                $('#editTimeInputs').empty();

                parentDiv.find('.edit-bottle-time').each(function() {
                    const time = $(this).data('time');
                    const id = $(this).data('id');
                    $('#editTimeInputs').append(`
                        <div class="form-group time-block">
                            <input type="hidden" name="existing_id[]" value="${id}">
                            <div class="input-group">
                                <input type="time" name="existing_time[]" class="form-control" value="${time}" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger btn-sm remove-existing" data-id="${id}">×</button>
                                </div>
                            </div>
                        </div>
                    `);
                });

                $('#editBottleModal').modal('show');
            });

            $('#addMoreEditTime').click(function() {
                $('#editTimeInputs').append(`
                    <div class="form-group time-block">
                        <div class="input-group">
                            <input type="time" name="new_time[]" class="form-control" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger btn-sm remove-new">×</button>
                            </div>
                        </div>
                    </div>
                `);
            });

            $('#editTimeInputs').on('click', '.remove-existing', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to delete this time?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("dailyDiary.deleteBottleTime") }}',
                            type: 'POST',
                            data: { id: id, _token: $('meta[name="csrf-token"]').attr('content') },
                            success: function() {
                                $(`[data-id="${id}"]`).closest('.time-block').remove();
                                Swal.fire('Deleted!', 'The time has been deleted.', 'success').then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
                            }
                        });
                    }
                });
            });

            $('#editTimeInputs').on('click', '.remove-new', function() {
                $(this).closest('.time-block').remove();
            });

            $('#editBottleForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("dailyDiary.updateBottleTimes") }}',
                    type: 'POST',
                    data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                    success: function(response) {
                        Swal.fire('Success', 'Bottle times updated successfully', 'success').then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        let errorMsg = Object.values(errors).flat().join('<br>') || 'An error occurred';
                        Swal.fire('Error', errorMsg, 'error');
                    }
                });
            });

            // Select all kids
            $('.common-dd-tbl').css('display', 'none');

            $('#checkAllStudents').on('click', function() {
                const isChecked = $(this).prop('checked');
                $('.check-kids').prop('checked', isChecked);
                $('.common-dd-tbl').css('display', isChecked ? 'table' : 'none');
            });

            $(document).on('click', '.check-kids', function() {
                const checkedCount = $('.check-kids:checked').length;
                const totalCount = $('.check-kids').length;
                $('.common-dd-tbl').css('display', checkedCount > 1 ? 'table' : 'none');
                $('#checkAllStudents').prop('checked', checkedCount === totalCount);
            });

            // Common add button for multiple kids
            $(document).on('click', '.cmn-btn-add', function() {
                const type = $(this).data('type');
                const title = $(this).data('title');
                const bgcolor = '#FFFFFF';

                let modalName, modalId;
                if (['BREAKFAST', 'morningtea', 'lunch', 'afternoontea', 'snacks'].includes(type)) {
                    modalName = '#addDailyFoodRecord';
                    modalId = '#foodModal';
                    if (type === 'morningtea' || type === 'afternoontea') {
                        $('#foodModal .common-item').hide();
                    } else {
                        $('#foodModal .common-item').show();
                        $('#item').select2({
                            ajax: {
                                url: '{{ route("dailyDiary.getItems") }}',
                                type: 'POST',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        searchTerm: params.term,
                                        type: type,
                                        centerid: centerid,
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    };
                                },
                                processResults: function(response) {
                                    return { results: response };
                                },
                                cache: true
                            },
                            dropdownParent: $(modalId + ' .modal-content')
                        });
                    }
                } else if (type === 'sunscreen') {
                    modalName = '#addDailySunscreenRecord';
                    modalId = '#sunscreenModal';
                } else if (type === 'sleep') {
                    modalName = '#addDailySleepRecord';
                    modalId = '#sleepModal';
                } else {
                    modalName = '#addDailyToiletingRecord';
                    modalId = '#toiletingModal';
                }

                $(modalId).find('input[name="childids[]"]').remove();
                $(modalId).find('input[name="type"]').remove();
                $(modalName).find('.modal-body').append(`<input type="hidden" class="type" name="type" value="${type}">`);
                $(modalId).find('.modal-header').css({ background: bgcolor, color: '#000000' });
                $(modalId).find('.modal-title').text(title);

                $('input[name="kids[]"]:checked').each(function() {
                    $(modalName).find('.modal-body').append(`<input type="hidden" class="childid" name="childids[]" value="${this.value}">`);
                });

                $(modalId).modal('show');
            });

            // Calendar change
            $('#txtCalendar').on('change', function() {
                const date = $(this).val();
                window.location.href = '{{ route("dailyDiary.list") }}?centerid=' + centerid + '&roomid=' + roomid + '&date=' + date;
            });
        });
    </script>
@endpush