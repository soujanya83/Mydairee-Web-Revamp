@extends('layout.master')
@section('title', 'Childrens List')

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
</style>

@section('content')



<form method="GET" action="{{ route('childrens_list') }}" class="d-flex justify-content-end align-items-center"
    style="margin-top: -49px; margin-right: 100px;" id="roomFilterForm">
    <div class="me-3 mr-3">
        <input type="text" name="childName" id="childNameInput" class="form-control" placeholder="Search by child name"
            value="{{ request('childName') }}" style="width: 200px;border-color:#49c5b6">
    </div>
    <div class="dropdown" style="position: relative;">
        <button class="btn btn-outline-info dropdown-toggle" type="button" id="roomDropdown">
            {{ $selectedRoom && $rooms->firstWhere('id', $selectedRoom) ? $rooms->firstWhere('id', $selectedRoom)->name
            : '-- All Rooms --' }}
        </button>

        <ul class="dropdown-menu" style="
            max-height: 300px;
            overflow-y: auto;
            position: absolute;
            left: 0;
            right: auto;
            top: 100%;
            display: none;
            z-index: 999;
            white-space: nowrap;">
            <li>
                <a class="dropdown-item" href="#" onclick="selectRoom('', '-- All Rooms --'); return false;">
                    -- All Rooms --
                </a>
            </li>

            @foreach($rooms as $room)
            <li>
                <a class="dropdown-item" href="#"
                    onclick="selectRoom('{{ $room->id }}', '{{ $room->name }}'); return false;">
                    {{ $room->name }}
                </a>
            </li>
            @endforeach
        </ul>

        <input type="hidden" name="roomId" id="roomInput" value="{{ $selectedRoom ?? '' }}">
    </div>
</form>
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
                    class="btn btn-sm {{ $child->childstatus == 'Active' ? 'btn-outline-success' : 'btn-outline-danger' }}">
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
                        data-bs-target="#statusModal{{ $child->childId }}" style="height: 23px;">
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
                        style="height: 24px;">
                        <i class="fas fa-edit"></i>
                    </a>&nbsp;&nbsp;



                    <form action="{{ route('children.destroy', $child->childId) }}" method="POST"
                        onsubmit="return confirm('Are you sure?')" class="me-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
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
