@extends('layout.master')
@section('title', 'Recipes List')

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

    #roomDropdown+.dropdown-menu {
        margin-top: -7px !important;
    }
</style>


@section('content')

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

<form method="GET" action="{{ route('childrens_list') }}" class="d-flex justify-content-end align-items-center" style="margin-top: -49px;
    margin-right: 30px;"
    id="roomFilterForm">
    <div class="dropdown">
        <button class="btn btn-outline-info dropdown-toggle" type="button" id="roomDropdown" data-bs-toggle="dropdown"
            aria-expanded="false">
            {{ $selectedRoom ? $rooms->firstWhere('id', $selectedRoom)->name : '-- All Rooms --' }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="roomDropdown"
            style="max-height: 300px; overflow-y: auto;">
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
        <input type="hidden" name="roomId" id="roomInput" value="{{ $selectedRoom }}">
    </div>
</form>

<hr>
<div class="row mb-5" >




    @foreach($chilData as $child)
    <div class="col-md-3 mb-2">
        <div class="card shadow rounded-lg">
            @php

            @endphp

            <img src="{{ $child->imageUrl ? asset($child->imageUrl) : 'http://www.mydiaree.com.au/assets/img/MYDIAREE-new-logo.png' }}"
                class="card-img-top" style="height: 200px; object-fit: cover;border-radius: 8px;padding: 5px;"
                alt="{{ $child->name }}">

            <div class="card-body">
                <h5 class="card-title">{{ $child->childname }} {{ $child->lastname }}</h5>

                <div class="mb-2">
                    <span class="badge bg-info text-white">Date of Birth:
                        {{ optional($child->dob ? \Carbon\Carbon::parse($child->dob) : null)->format('d / M / Y') ??
                        'N/A' }}
                    </span>
                    <span class="badge bg-light text-dark" style="margin-left:26px">
                        @if(strtolower($child->gender) == 'male')
                        <i class="fas fa-mars"></i> Male
                        @else
                        <i class="fas fa-venus"></i> Female
                        @endif
                    </span>
                </div>

                <p class="mb-1"><i class="fas fa-id-card me-1"></i> ID: {{ $child->childId }}</p>
                <p class="mb-1"><i class="fas fa-door-open me-1"></i> Room: {{ $child->roomname ?? 'N/A' }}</p>
                <p class="mb-3"><i class="fas fa-calendar-check me-1"></i>
                    Joined: {{ optional($child->startDate ? \Carbon\Carbon::parse($child->startDate) : null)->format('M
                    d, Y') ?? 'N/A' }}
                </p>

                <div class="d-flex justify-content-end" style="margin-top:-43px">
                    <form action="{{ route('children.destroy', $child->childId) }}" method="POST"
                        onsubmit="return confirm('Are you sure?')" class="me-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    </form> &nbsp;&nbsp;
                    <a href="{{ route('children.edit', $child->childId) }}" class="btn btn-primary btn-sm"
                        style="height: 26px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    function selectRoom(id, name) {
    document.getElementById('roomInput').value = id;
    document.getElementById('roomDropdown').textContent = name;
    document.getElementById('roomFilterForm').submit();
}
</script>
@include('layout.footer')
@stop
