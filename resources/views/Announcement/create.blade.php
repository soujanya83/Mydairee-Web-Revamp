@extends('layout.master')
@section('title', 'Create Events')
@section('parentPageTitle', 'Dashboard')

@section('page-styles') {{-- ✅ Injects styles into layout --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<style>
    /* Limit modal height and allow scroll */
    #selectChildrenModal .modal-body {
        max-height: 80vh;
        /* limit vertical height */
        overflow-y: auto;
        /* enable vertical scroll */
        overflow-x: hidden;
        /* prevent horizontal scroll */
        padding-right: 10px;
        /* optional */
        width: 100%;
        /* full width */
        box-sizing: border-box;
        /* include padding in width */
    }



    .is-invalid {
        border-color: #dc3545 !important;
    }

    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
    }

    .toast {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .toast-success {
        background-color: #28a745;
        /* Green for success */
    }

    .toast-error {
        background-color: #dc3545;
        /* Red for error */
    }

    .toast-close-button {
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
        color: white;
        margin-left: 10px;
    }

    .toast-message {
        flex: 1;

    }

    .media-upload-box {
        border: 2px dashed #007bff;
        background-color: #f8f9fa;
        position: relative;
        cursor: pointer;
        transition: 0.3s ease-in-out;
    }

    .media-upload-box:hover {
        background-color: #e9f0ff;
    }

    .list-table td,
    .list-table tr {
        border: none !important;
    }

    .media-thumb {
        height: 150px;
        object-fit: cover;
        width: 100%;
    }

    .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        padding: 2px 5px;
        font-size: 12px;
    }

    #mediaPreview .btn {
        margin-right: 5px;
        margin-top: 5px;
    }

    .media-thumb {
        max-height: 200px;
        object-fit: cover;
        width: 100%;
        border: 1px solid #ddd;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>

<style>
    .list-thumbnail.xsmall {
        width: 40px;
    }

    .list-table td {
        vertical-align: middle !important;
    }

    .select-all-box {
        padding-left: 12px;
    }

    .select-all-box>label {
        margin-left: 22px;
        font-size: 15px;
    }
</style>
<style>
    .ck-editor__editable_inline {
        min-height: 300px;
        /* This is like setting more "rows" */
    }

    /* Active Save button */
    .btn-save {
        background-color: #0a89a2ff;
        /* Bootstrap info color */
        color: #fff;
        border: 2px solid #0dcaf0;
        /* font-size: 1.1rem;           larger text */
        border-radius: 0.5rem;
        /* rounded corners */
        transition: all 0.3s ease;
        /* smooth hover */
        padding-inline: 5px;
    }

    .btn-save:hover {
        background-color: transparent;
        color: #0995b1ff;
        border: 2px solid #0dcaf0;
    }

    /* Disabled Save button */
    .btn-save-disabled {
        background-color: #6c757d;
        /* muted gray */
        color: #fff;
        border: 2px solid #6c757d;
        font-size: 1.1rem;
        border-radius: 0.5rem;
        cursor: not-allowed;
        opacity: 0.8;
    }
</style>

<style>
    .announcement-card {
        display: none;
    }

    .events-card {
        display: none;
    }

    .public-holiday-card {
        display: none;
    }

    .add-holiday {
        display: block;
    }

    .add-holiday-csv {
        display: none;
    }
</style>
@endsection

@section('content')

@php
$role = Auth::user()->userType;
$edit = $add = 0;

if ($role === 'Superadmin') {
$edit = $add = 1;
} elseif ($role === 'Staff') {
if (isset($permissions->addAnnouncement) && $permissions->addAnnouncement == 1) {
$add = 1;
}
if (isset($permissions->updateAnnouncement) && $permissions->updateAnnouncement == 1) {
$edit = 1;
}
}
@endphp

<hr>
<main data-centerid="{{ $centerid }}">
    <div class="container-fluid">
        <!-- <div class="row">
            <div class="col-12">
                <h1>Manage Announcements</h1>
                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                        <li class="breadcrumb-item">
    <a href="{{ route('announcements.list') }}">Announcements List</a>
</li>

                        <li class="breadcrumb-item active" aria-current="page">Manage Announcement</li>
                    </ol>
                </nav>
                <div class="separator mb-5"></div>
            </div>
        </div> -->

        <div class="row no-gutters">
            <div class="col-md-12 ">

                <div class="col-md-12 mb-3 mb-4 card pt-2 pb-3">
                    <label for="eventType" class="form-label fw-bold">📅 Type</label>
                    <div class="input-group">
                        <select name="eventType" id="eventType" class="form-control">
                            <option value="">-- Select Type --</option>
                            <option value="announcement" {{ isset($announcement->eventType) && $announcement->eventType == 'annoucement' ? 'selected' : '' }}>Annoucement</option>
                            <option value="events" {{ isset($announcement->eventType) && $announcement->eventType == 'events' ? 'selected' : '' }}>Events</option>
                            <option value="public_holiday" {{ isset($announcement->eventType) && $announcement->eventType == 'public_holiday' ? 'selected' : '' }}>Public Holiday</option>
                        </select>

                        <span class="input-group-text">
                            <i class="simple-icon-calendar"></i>
                        </span>
                    </div>
                </div>

                <!-- annoucement card -->
                <div class="card announcement-card">
                    <div class="card-body">
                        <!-- <div class="mb-5"> -->
                        <!-- <h5 class="card-title">Enter Details</h5> -->
                        <!-- </div> -->

                        <form action="{{ route('announcements.store') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @if ($announcement)
                            <input type="hidden" name="annId" value="{{ $announcement->id }}">
                            @endif
                            <input type="hidden" name="centerid" value="{{ $centerid }}">
                            <input type="hidden" name="type" value="announcement">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" name="title" id="title" required
                                            value="{{ old('title', $announcement->title ?? '') }}">
                                    </div>

                                    <div class="form-group row align-items-center">


                                        <!-- Date Field -->
                                        <div class="col-md-6 mb-3 date">
                                            <label for="eventDate" class="form-label fw-bold">📅 Date</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control calendar"
                                                    name="eventDate"
                                                    value="{{ isset($announcement->eventDate) 
            ? \Carbon\Carbon::parse($announcement->eventDate)->format('d-m-Y') 
            : (isset($selectedDate) 
                ? \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') 
                : \Carbon\Carbon::now()->format('d-m-Y')) }}"
                                                    data-date-format="dd-mm-yyyy"
                                                    placeholder="Select date">

                                                <span class="input-group-text">
                                                    <i class="simple-icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Access Field -->
                                        <div class="col-md-6 mb-3 audience">
                                            <label for="audience" class="form-label fw-bold">👥 Access</label>
                                            <div class="input-group">
                                                <select class="form-select form-control" name="audience" required>
                                                    <option value="all" {{ isset($announcement) && $announcement->audience == 'all' ? 'selected' : '' }}>All</option>
                                                    <option value="parents" {{ isset($announcement) && $announcement->audience == 'parents' ? 'selected' : '' }}>Parents</option>
                                                    <option value="staff" {{ isset($announcement) && $announcement->audience == 'staff' ? 'selected' : '' }}>Staff</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group media-div">
                                        <h4>Media Upload Section</h4>
                                        <div class="media-upload-box p-4 border rounded bg-light text-center">
                                            <label for="mediaInput" class="btn btn-outline-info">
                                                Select Image (png,jpeg,jpg) or pdf
                                            </label>
                                            <input type="file" id="mediaInput" name="media[]" class="d-none" multiple accept="image/*,application/pdf">

                                            <small class="form-text text-muted mt-2">Only image and pdf allowed and upto 2MB file </small>
                                        </div>

                                        <div id="mediaPreview" class="row mt-4"></div>
                                    </div>

                                    <div class="form-group select-children">
                                        <button type="button" class="btn btn-info mb-1" data-toggle="modal"
                                            data-backdrop="static" data-target="#selectChildrenModal">+ Add
                                            Children</button>
                                    </div>
                                    <div class="children-tags">
                                        @forelse ($announcement->children ?? [] as $child)
                                        <a href="#!" class="rem" data-role="remove" data-child="{{ $child->id }}">
                                            <input type="hidden" name="childId[]" value="{{ $child->id }}">
                                            <span class="badge badge-pill badge-outline-info mb-1">{{ $child->name }}
                                                X</span>
                                        </a>
                                        @empty
                                        <p>No children selected</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-md-6 description">
                                    <div class="form-group">
                                        <label for="text">Description</label>
                                        <textarea name="text" id="about"
                                            class="form-control">{{ old('text', $announcement->text ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-right">
                                    @php
                                    $canSave = !empty($permissions->addAnnouncement) || auth()->user()->userType === "Superadmin" || auth()->user()->admin == 1;
                                    @endphp

                                    @if ($canSave)
                                    <button type="submit" class="btn btn-save my-2 btn-md-xl px-4 py-2">
                                        Save
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-save-disabled my-2 px-4 py-2"
                                        data-toggle="tooltip" data-placement="top"
                                        title="You need permission to save!">
                                        Save
                                    </button>
                                    @endif
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <!-- annoucement card ends  -->

                <!-- events card -->
                <div class="card events-card">
                    <div class="card-body">
                        <!-- <div class="mb-5"> -->
                        <!-- <h5 class="card-title">Enter Details</h5> -->
                        <!-- </div> -->

                        <form action="{{ route('announcements.store') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @if ($announcement)
                            <input type="hidden" name="annId" value="{{ $announcement->id }}">
                            @endif
                            <input type="hidden" name="centerid" value="{{ $centerid }}">
                            <input type="hidden" name="type" value="events">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" name="title" id="title" required
                                            value="{{ old('title', $announcement->title ?? '') }}">
                                    </div>

                                    <div class="form-group row align-items-center">


                                        <!-- Date Field -->
                                        <div class="col-md-6 mb-3 date">
                                            <label for="eventDate" class="form-label fw-bold">📅 Date</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control calendar"
                                                    name="eventDate"
                                                    value="{{ isset($announcement->eventDate) 
            ? \Carbon\Carbon::parse($announcement->eventDate)->format('d-m-Y') 
            : (isset($selectedDate) 
                ? \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') 
                : \Carbon\Carbon::now()->format('d-m-Y')) }}"
                                                    data-date-format="dd-mm-yyyy"
                                                    placeholder="Select date">

                                                <span class="input-group-text">
                                                    <i class="simple-icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Access Field -->
                                        <div class="col-md-6 mb-3 audience">
                                            <label for="audience" class="form-label fw-bold">👥 Access</label>
                                            <div class="input-group">
                                                <select class="form-select form-control" name="audience" required>
                                                    <option value="all" {{ isset($announcement) && $announcement->audience == 'all' ? 'selected' : '' }}>All</option>
                                                    <option value="parents" {{ isset($announcement) && $announcement->audience == 'parents' ? 'selected' : '' }}>Parents</option>
                                                    <option value="staff" {{ isset($announcement) && $announcement->audience == 'staff' ? 'selected' : '' }}>Staff</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <div class="col-md-12 mb-3 audience mx-0">
                                            <label for="color" class="form-label fw-bold">🎨 Choose Color</label>
                                            <div class="form-group">
                                                <select name="color" id="color" class="form-select form-control" style="font-weight: bold;">
                                                    <option value="#0d6efd" style="background-color:#0d6efd; color: white;"
                                                        {{ (isset($announcement) && $announcement->color == '#0d6efd') ? 'selected' : '' }}>
                                                        Blue
                                                    </option>
                                                    <option value="#198754" style="background-color:#198754; color: white;"
                                                        {{ (isset($announcement) && $announcement->color == '#198754') ? 'selected' : '' }}>
                                                        Green
                                                    </option>
                                                    <option value="#20c997" style="background-color:#20c997; color: white;"
                                                        {{ (isset($announcement) && $announcement->color == '#20c997') ? 'selected' : '' }}>
                                                        Teal
                                                    </option>
                                                    <option value="#0dcaf0" style="background-color:#0dcaf0; color: black;"
                                                        {{ (isset($announcement) && $announcement->color == '#0dcaf0') ? 'selected' : '' }}>
                                                        Cyan
                                                    </option>
                                                    <option value="#6610f2" style="background-color:#6610f2; color: white;"
                                                        {{ (isset($announcement) && $announcement->color == '#6610f2') ? 'selected' : '' }}>
                                                        Indigo
                                                    </option>
                                                    <option value="#6f42c1" style="background-color:#6f42c1; color: white;"
                                                        {{ (isset($announcement) && $announcement->color == '#6f42c1') ? 'selected' : '' }}>
                                                        Purple
                                                    </option>
                                                    <option value="#d63384" style="background-color:#d63384; color: white;"
                                                        {{ (isset($announcement) && $announcement->color == '#d63384') ? 'selected' : '' }}>
                                                        Pink
                                                    </option>
                                                    <option value="#ffc107" style="background-color:#ffc107; color: black;"
                                                        {{ (isset($announcement) && $announcement->color == '#ffc107') ? 'selected' : '' }}>
                                                        Yellow
                                                    </option>
                                                    <option value="#fd7e14" style="background-color:#fd7e14; color: white;"
                                                        {{ (isset($announcement) && $announcement->color == '#fd7e14') ? 'selected' : '' }}>
                                                        Orange
                                                    </option>
                                                    <option value="#343a40" style="background-color:#343a40; color: white;"
                                                        {{ (isset($announcement) && $announcement->color == '#343a40') ? 'selected' : '' }}>
                                                        Dark Gray
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="form-group media-div">
                                        <h4>📂 Event Media Upload</h4>
                                        <div class="media-upload-box p-4 border rounded bg-light text-center">
                                            <label for="mediaInputEvent" class="btn btn-outline-info">
                                                Select Image (png, jpeg, jpg) or PDF
                                            </label>
                                            <input type="file" id="mediaInputEvent" name="media[]"
                                                accept="image/*,application/pdf" class="d-none">

                                            <small class="form-text text-muted mt-2">
                                                Only 1 file allowed (Image/PDF), max size 2MB
                                            </small>
                                        </div>

                                        <!-- Preview Area -->
                                        <div id="mediaPreviewEvent" class="row mt-4"></div>
                                    </div>

                                    <div class="form-group select-children">
                                        <button type="button" class="btn btn-info mb-1" data-toggle="modal"
                                            data-backdrop="static" data-target="#selectChildrenModal">+ Add
                                            Children</button>
                                    </div>
                                    <div class="children-tags">
                                        @forelse ($announcement->children ?? [] as $child)
                                        <a href="#!" class="rem" data-role="remove" data-child="{{ $child->id }}">
                                            <input type="hidden" name="childId[]" value="{{ $child->id }}">
                                            <span class="badge badge-pill badge-outline-info mb-1">{{ $child->name }}
                                                X</span>
                                        </a>
                                        @empty
                                        <p>No children selected</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-md-6 description">
                                    <div class="form-group">
                                        <label for="text">Description</label>
                                        <textarea name="text" id="eventabout"
                                            class="form-control">{{ old('text', $announcement->text ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-right">
                                    @php
                                    $canSave = !empty($permissions->addAnnouncement) || auth()->user()->userType === "Superadmin" || auth()->user()->admin == 1;
                                    @endphp

                                    @if ($canSave)
                                    <button type="submit" class="btn btn-save my-2 btn-md-xl px-4 py-2">
                                        Save
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-save-disabled my-2 px-4 py-2"
                                        data-toggle="tooltip" data-placement="top"
                                        title="You need permission to save!">
                                        Save
                                    </button>
                                    @endif
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <!-- events card ends  -->

                <!-- public holiday -->
                <div class="card public-holiday-card">
                    <div class="card-body">

                        <form action="{{ route('settings.holiday.store') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @if ($announcement)
                            <input type="hidden" name="annId" value="{{ $announcement->id }}">
                            @endif
                            <input type="hidden" name="centerid" value="{{ $centerid }}">

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group row align-items-center">


                                        <!-- Date Field -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">📂 Upload Type</label>
                                            <div class="form-check">
                                                <input class="form-check-input-csv" type="radio" name="uploadType" id="notCsvOption" value="not_csv" checked>
                                                <label class="form-check-label" for="notCsvOption">
                                                    Manual
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input-csv" type="radio" name="uploadType" id="csvOption" value="csv">
                                                <label class="form-check-label" for="csvOption">
                                                    CSV
                                                </label>
                                            </div>

                                        </div>


                                    </div>




                                    <div class="form-group add-holiday-csv">
                                        <div class="d-flex gap-2">
                                            <h4>📂 Upload CSV/Excel</h4>
                                            <a href="{{ asset('uploads/holiday excel.xlsx') }}" download class="btn">
                                                <i class="fas fa-download"></i> Sample
                                            </a>
                                        </div>

                                        <div class="media-upload-box p-4 border rounded bg-light text-center">
                                            <label for="csvExcelInput" class="btn btn-outline-info">
                                                Select CSV or Excel File
                                            </label>
                                            <input type="file" id="csvExcelInput" name="csvExcel"
                                                accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                                class="form-control d-none">

                                            <small class="form-text text-muted mt-2">
                                                Only 1 file allowed (CSV/XLS/XLSX), max size 2MB
                                            </small>
                                        </div>

                                        <div id="csvExcelPreview" class="row mt-4"></div>
                                    </div>



                                    <div class="form-group add-holiday row">
                                        <div class="col-md-6 mb-3 date">
                                            <label for="eventDate" class="form-label fw-bold">📅 Date</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control calendar"
                                                    name="date"
                                                    value="{{ isset($announcement->eventDate) 
            ? \Carbon\Carbon::parse($announcement->eventDate)->format('d-m-Y') 
            : (isset($selectedDate) 
                ? \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') 
                : \Carbon\Carbon::now()->format('d-m-Y')) }}"
                                                    data-date-format="dd-mm-yyyy"
                                                    placeholder="Select date">


                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="d-block fw-bold">Status</label>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    name="status"
                                                    id="statusActive"
                                                    value="1"
                                                    {{ old('status', $announcement->status ?? '') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="statusActive">Active</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input"
                                                    type="radio"
                                                    name="status"
                                                    id="statusInactive"
                                                    value="0"
                                                    {{ old('status', $announcement->status ?? '') == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="statusInactive">Inactive</label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group add-holiday row">
                                        <div class="col-md-6 mb-3">
                                            <label for="text">Occcasion</label>
                                            <input type="text"
                                                name="occasion"
                                                id=""
                                                class="form-control"
                                                value="{{ old('text', $announcement->text ?? '') }}">

                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="text">State</label>
                                            <input type="text"
                                                name="state"
                                                id=""
                                                class="form-control"
                                                value="{{ old('text', $announcement->text ?? '') }}">

                                        </div>

                                    </div>





                                </div>




                            </div>

                            <div class="row">
                                <div class="col-12 text-right">
                                    @php
                                    $canSave = !empty($permissions->addAnnouncement) || auth()->user()->userType === "Superadmin" || auth()->user()->admin == 1;
                                    @endphp

                                    @if ($canSave)
                                    <button type="submit" class="btn btn-save my-2 btn-md-xl px-4 py-2">
                                        Save
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-save-disabled my-2 px-4 py-2"
                                        data-toggle="tooltip" data-placement="top"
                                        title="You need permission to save!">
                                        Save
                                    </button>
                                    @endif
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <!-- public holiday ends -->

            </div>
        </div>
    </div>
</main>


<div class="modal modal-right" id="selectChildrenModal" tabindex="-1" role="dialog"
    aria-labelledby="selectChildrenModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Select Children</h5>
                <button type="button" class="close select-children" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group filter-box">
                    <input type="text" class="form-control" id="filter-child"
                        placeholder="Enter child name or age to search">
                </div>

                <ul class="nav nav-tabs separator-tabs ml-0 mb-5" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab"
                            aria-controls="first" aria-selected="true">Children</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="second-tab" data-toggle="tab" href="#second" role="tab"
                            aria-controls="second" aria-selected="false">Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="third-tab" data-toggle="tab" href="#third" role="tab"
                            aria-controls="third" aria-selected="false">Rooms</a>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- Children Tab --}}
                    <div class="tab-pane show  active" id="first" role="tabpanel" aria-labelledby="first-tab">
                        <div class="select-all-box" id="select-all-box">
                            <input type="checkbox" id="select-all-child">
                            <label for="select-all-child" id="select-all-child-label">Select All</label>
                        </div>
                        <table class="list-table table table-condensed">
                            @foreach ($Childrens as $child)
                            <tr>
                                <td>
                                    <input type="checkbox" class="common-child child-tab unique-tag" name="child[]"
                                        id="child_{{ $child->childid }}" value="{{ $child->childid }}"
                                        data-name="{{ $child->name . ' - ' . $child->age }}" {{ $child->checked }}>
                                </td>
                                <td>
                                    <label for="child_{{ $child->childid }}">
                                        <img src="{{ public_path($child->imageUrl) }}"
                                            class="img-thumbnail border-0 rounded-circle list-thumbnail align-self-center xsmall">
                                        {{ $child->name . ' - ' . $child->age }}
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>

                    {{-- Groups Tab --}}
                    <div class="tab-pane show" id="second" role="tabpanel" aria-labelledby="second-tab">
                        @foreach ($Groups as $group)
                        <div class="select-all-box">
                            <input type="checkbox" id="select-group-child-{{ $group->groupid }}"
                                class="select-group-child" data-groupid="{{ $group->groupid }}">
                            <label for="select-group-child-{{ $group->groupid }}">{{ $group->name }}</label>
                        </div>
                        <table class="list-table table table-condensed">
                            @foreach ($group->Childrens as $child)
                            <tr>
                                <td>
                                    <input type="checkbox" class="common-child child-group" name="child[]"
                                        data-groupid="{{ $group->groupid }}" id="child_{{ $child->childid }}"
                                        value="{{ $child->childid }}" {{ $child->checked }}>
                                </td>
                                <td>
                                    <label for="child_{{ $child->childid }}">
                                        <img src="{{ public_path($child->imageUrl) }}"
                                            class="img-thumbnail border-0 rounded-circle list-thumbnail align-self-center xsmall">
                                        {{ $child->name . ' - ' . $child->age }}
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @endforeach
                    </div>

                    {{-- Rooms Tab --}}
                    <div class="tab-pane show" id="third" role="tabpanel" aria-labelledby="third-tab">
                        @foreach ($Rooms as $room)
                        <div class="select-all-box">
                            <input type="checkbox" class="select-room-child" id="select-room-child-{{ $room->roomid }}"
                                data-roomid="{{ $room->roomid }}">
                            <label for="select-room-child-{{ $room->roomid }}">{{ $room->name }}</label>
                        </div>
                        <table class="list-table table table-condensed">

                            @foreach ($room->Childrens as $child)
                            <tr>
                                <td>
                                    <input type="checkbox" class="common-child child-room" name="child[]"
                                        data-roomid="{{ $room->roomid }}" id="child_{{ $child->childid }}"
                                        value="{{ $child->childid }}" {{ $child->checked }}>
                                </td>
                                <td>
                                    <label for="child_{{ $child->childid }}">
                                        <img src="{{ public_path($child->imageUrl) }}"
                                            class="img-thumbnail border-0 rounded-circle list-thumbnail align-self-center xsmall">
                                        {{ $child->name . ' - ' . $child->age }}
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @endforeach
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="insert-childtags" data-dismiss="modal">Submit</button>
            </div>

        </div>
    </div>
</div>

<div aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 1rem; right: 1rem; z-index: 1080;">
    <div class="toast-container">

        {{-- Validation Errors --}}
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="toast bg-danger text-white mb-2" role="alert" aria-live="assertive" aria-atomic="true"
            data-delay="3000" data-autohide="true">

            <div class="toast-body">
                {{ $error }}
            </div>
        </div>
        @endforeach
        @endif

        {{-- Custom Flash Message
        @if (session('status') && session('message'))
        <div class="toast {{ session('status') === 'success' ? 'bg-success' : 'bg-danger' }} text-white mb-2"
        data-delay="5000">
        <div class="toast-header {{ session('status') === 'success' ? 'bg-success' : 'bg-danger' }} text-white">
            <strong class="mr-auto">{{ ucfirst(session('status')) }}</strong>
            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            {{ session('message') }}
        </div>
    </div>
    @endif --}}



</div>
</div>




@endsection

@push('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('status') == 'success' && session('msg'))
        Swal.fire({
            title: 'Success!',
            text: @json(session('msg')),
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        @endif

        @if(session('status') == 'error' && session('msg'))
        Swal.fire({
            title: 'Error!',
            text: @json(session('msg')),
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Close'
        });
        @endif
    });
</script>
<script>
    const colorInput = document.getElementById('color');
    const colorWarning = document.getElementById('colorWarning');

    function hexToHSL(hex) {
        let r = 0,
            g = 0,
            b = 0;
        if (hex.length == 4) {
            r = "0x" + hex[1] + hex[1];
            g = "0x" + hex[2] + hex[2];
            b = "0x" + hex[3] + hex[3];
        } else if (hex.length == 7) {
            r = "0x" + hex[1] + hex[2];
            g = "0x" + hex[3] + hex[4];
            b = "0x" + hex[5] + hex[6];
        }
        r /= 255;
        g /= 255;
        b /= 255;

        let max = Math.max(r, g, b),
            min = Math.min(r, g, b);
        let h, s, l = (max + min) / 2;

        if (max == min) {
            h = s = 0;
        } else {
            let d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r:
                    h = (g - b) / d + (g < b ? 6 : 0);
                    break;
                case g:
                    h = (b - r) / d + 2;
                    break;
                case b:
                    h = (r - g) / d + 4;
                    break;
            }
            h /= 6;
        }
        return Math.round(h * 360); // only hue needed
    }

    colorInput.addEventListener('input', function() {
        const picked = this.value.toLowerCase();
        const hue = hexToHSL(picked);

        // ❌ Block hue near red (350–360 OR 0–10)
        if (hue >= 350 || hue <= 10) {
            colorWarning.classList.remove('d-none');
            this.value = '#0d6efd'; // reset to default blue
        } else {
            colorWarning.classList.add('d-none');
        }
    });
</script>

<script>
    let selectedFiles = [];

    // document.getElementById('mediaInput').addEventListener('change', function (event) {
    //     const previewContainer = document.getElementById('mediaPreview');
    //     const newFiles = Array.from(event.target.files);
    //     const totalFiles = selectedFiles.length + newFiles.length;

    //     if (totalFiles > 1) {
    //         alert("You can upload a maximum of 1 files.");
    //         this.value = '';
    //         return;
    //     }

    //     newFiles.forEach((file, index) => {
    //         const reader = new FileReader();
    //         const fileIndex = selectedFiles.length;

    //         reader.onload = function (e) {
    //             const col = document.createElement('div');
    //             col.className = 'col-md-3 position-relative mb-3';

    //             let mediaContent = '';

    //             if (file.type.startsWith('image/')) {
    //                 mediaContent = `<img src="${e.target.result}" class="media-thumb rounded">`;
    //             } else if (file.type.startsWith('video/')) {
    //                 mediaContent = `<video src="${e.target.result}" class="media-thumb rounded" controls></video>`;
    //             }

    //             col.innerHTML = `
    //                 <div class="position-relative">
    //                     ${mediaContent}
    //                     <button type="button" class="btn btn-danger btn-sm remove-btn" data-index="${fileIndex}">✕</button>
    //                 </div>
    //             `;

    //             previewContainer.appendChild(col);
    //         };

    //         reader.readAsDataURL(file);
    //         selectedFiles.push(file);
    //     });

    //     updateFileInput();
    // });

    document.getElementById('mediaInput').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('mediaPreview');
        const newFiles = Array.from(event.target.files);

        // Ensure selectedFiles is defined globally
        window.selectedFiles = window.selectedFiles || [];

        const totalFiles = selectedFiles.length + newFiles.length;

        if (totalFiles > 1) {
            alert("You can upload a maximum of 1 file.");
            this.value = '';
            return;
        }

        newFiles.forEach((file, index) => {
            const reader = new FileReader();
            const fileIndex = selectedFiles.length;

            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 position-relative mb-3';

                let mediaContent = '';

                if (file.type.startsWith('image/')) {
                    mediaContent = `<img src="${e.target.result}" class="media-thumb rounded w-100" alt="Image">`;
                } else if (file.type === 'application/pdf') {
                    mediaContent = `<embed src="${e.target.result}" type="application/pdf" class="media-thumb rounded w-100" height="200px"/>`;
                } else if (file.type.startsWith('video/')) {
                    mediaContent = `<video src="${e.target.result}" class="media-thumb rounded w-100" controls></video>`;
                } else {
                    mediaContent = `<div class="alert alert-warning">Unsupported file type</div>`;
                }

                col.innerHTML = `
                <div class="position-relative">
                    ${mediaContent}
                    <button type="button" class="btn btn-danger btn-sm remove-btn position-absolute top-0 end-0 m-1" data-index="${fileIndex}">✕</button>
                </div>
            `;

                previewContainer.appendChild(col);
            };

            reader.readAsDataURL(file);
            selectedFiles.push(file);
        });

        updateFileInput();
    });


    // Remove handler
    document.getElementById('mediaPreview').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn')) {
            const index = parseInt(e.target.getAttribute('data-index'));
            selectedFiles.splice(index, 1);
            updateFileInput();
            renderPreview();
        }
    });


    // Re-render preview
    function renderPreview() {
        const preview = document.getElementById('mediaPreview');
        preview.innerHTML = ''; // clear previous

        selectedFiles.forEach((file, i) => {
            const div = document.createElement('div');
            div.innerHTML = `
            <span>${file.name}</span>
            <button type="button" class="remove-btn" data-index="${i}">Remove</button>
        `;
            preview.appendChild(div);
        });
    }

    function updateFileInput() {
        const input = document.getElementById('mediaInput');
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }
</script>

<!-- event media -->
<script>
    let eventFiles = []; // store uploaded files

    const mediaInputEvent = document.getElementById('mediaInputEvent');
    const mediaPreviewEvent = document.getElementById('mediaPreviewEvent');

    mediaInputEvent.addEventListener('change', function(e) {
        const newFiles = Array.from(e.target.files);

        // ✅ Restrict only 1 file
        if (newFiles.length > 1 || eventFiles.length >= 1) {
            this.value = '';
            return;
        }

        const file = newFiles[0];

        // ✅ Validate size
        if (file.size > 2 * 1024 * 1024) {
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(ev) {
            mediaPreviewEvent.innerHTML = ''; // reset preview
            const wrapper = document.createElement('div');
            wrapper.className = 'col-md-4 position-relative mb-3';

            let previewHtml = '';
            if (file.type.startsWith('image/')) {
                previewHtml = `<img src="${ev.target.result}" class="media-thumb rounded w-100" alt="Image">`;
            } else if (file.type === 'application/pdf') {
                previewHtml = `<embed src="${ev.target.result}" type="application/pdf" class="media-thumb rounded w-100" height="200px"/>`;
            } else {
                previewHtml = `<div class="alert alert-warning">Unsupported file type</div>`;
            }

            wrapper.innerHTML = `
                <div class="position-relative">
                    ${previewHtml}
                    <button type="button" 
                            class="btn btn-danger btn-sm remove-file position-absolute top-0 end-0 m-1">
                        ✕
                    </button>
                </div>
            `;

            mediaPreviewEvent.appendChild(wrapper);
        };

        reader.readAsDataURL(file);
        eventFiles = [file]; // keep only one
        syncEventInput();
    });

    // Remove handler
    mediaPreviewEvent.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-file')) {
            eventFiles = [];
            mediaInputEvent.value = '';
            mediaPreviewEvent.innerHTML = '';
        }
    });

    // Sync selected files back to input
    function syncEventInput() {
        const dt = new DataTransfer();
        eventFiles.forEach(file => dt.items.add(file));
        mediaInputEvent.files = dt.files;
    }
</script>

<!-- event media ends -->

<!-- upload csv/excel -->
<script>
    let csvExcelFiles = []; // 👈 different variable name

    document.addEventListener("DOMContentLoaded", function() {
        const csvExcelInput = document.getElementById('csvExcelInput');
        const csvExcelPreview = document.getElementById('csvExcelPreview');

        csvExcelInput.addEventListener('change', function(event) {
            const newFiles = Array.from(event.target.files);

            // Debug
            console.log("CSV/Excel files selected:", newFiles);

            // Replace old file (only 1 allowed)
            csvExcelFiles = [];

            csvExcelPreview.innerHTML = ''; // clear preview

            newFiles.forEach(file => {
                if (file.size > 2 * 1024 * 1024) {
                    alert("❌ File too large. Max size is 2MB.");
                    return;
                }

                let icon = '📄';
                if (file.name.toLowerCase().endsWith('.csv')) icon = '🧾';
                else if (file.name.toLowerCase().endsWith('.xls') || file.name.toLowerCase().endsWith('.xlsx')) icon = '📊';

                const col = document.createElement('div');
                col.className = 'col-md-6 position-relative mb-3';
                col.innerHTML = `
                    <div class="border p-3 rounded bg-white">
                        <span>${icon} ${file.name}</span>
                        <button type="button"
                                class="btn btn-danger btn-sm remove-csv-excel float-end"
                                data-index="0">Remove</button>
                    </div>
                `;

                csvExcelPreview.appendChild(col);
                csvExcelFiles.push(file);
            });

            updateCsvExcelInput();
        });

        // Remove handler
        csvExcelPreview.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-csv-excel')) {
                csvExcelFiles = []; // empty files
                updateCsvExcelInput();
                csvExcelPreview.innerHTML = ''; // clear preview
            }
        });

        function updateCsvExcelInput() {
            const dataTransfer = new DataTransfer();
            csvExcelFiles.forEach(file => dataTransfer.items.add(file));
            csvExcelInput.files = dataTransfer.files;
        }
    });
</script>
<!-- upload csv/excel ends -->

<script>
    $(document).ready(function() {
        let toast = $('.toast');
        toast.toast('show');

        // Hide after 5 seconds
        setTimeout(function() {
            toast.toast('hide');
        }, 5000);
    });
</script>




<script>
    $(document).ready(function() {
        $('#filter-child').on('keyup', function() {
            const searchValue = $(this).val().toLowerCase();

            $('.tab-pane').each(function() {
                let tabHasVisibleChildren = false;

                // Loop each select-all box and its table
                $(this).find('.select-all-box').each(function() {
                    const $selectAllBox = $(this);
                    const $table = $selectAllBox.next('table');
                    let visibleRows = 0;

                    // Filter rows based on name or age
                    $table.find('tr').each(function() {
                        const label = $(this).find('label').text().toLowerCase();
                        const isMatch = label.includes(searchValue);
                        $(this).toggle(isMatch);
                        if (isMatch) visibleRows++;
                    });

                    // Show or hide select-all and table
                    if (visibleRows > 0) {
                        $selectAllBox.show();
                        $table.show();
                        tabHasVisibleChildren = true;
                    } else {
                        $selectAllBox.hide();
                        $table.hide();
                    }
                });

                // Hide the entire tab-pane if no visible children in any section
                $(this).toggle(tabHasVisibleChildren);
            });
        });
    });
</script>





<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#about'), {
                toolbar: [
                    'undo', 'redo', '|',
                    'bold', 'italic', 'strikethrough', '|',
                    'numberedList', 'bulletedList', '|',
                    'link'
                ]
            })
            .then(editor => {
                console.log('CKEditor 5 initialized');
            })
            .catch(error => {
                console.error('CKEditor 5 initialization failed:', error);
            });
    });

    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#aboutevent'), {
                toolbar: [
                    'undo', 'redo', '|',
                    'bold', 'italic', 'strikethrough', '|',
                    'numberedList', 'bulletedList', '|',
                    'link'
                ]
            })
            .then(editor => {
                console.log('CKEditor 5 initialized');
            })
            .catch(error => {
                console.error('CKEditor 5 initialization failed:', error);
            });
    });


    $(document).off('click', '.nav-link').on('click', 'select-children', function(e) {
        e.preventDefault();
        $(this).modal('hide'); // Manually trigger Bootstrap tab
    });




    $(document).ready(function() {


        var date = new Date();
        date.setDate(date.getDate());

        $('.calendar').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate: date,
            templates: {
                leftArrow: '<i class="simple-icon-arrow-left"></i>',
                rightArrow: '<i class="simple-icon-arrow-right"></i>'
            }
        });





        $(document).on('click', "#select-all-child", function() {
            //check if this checkbox is checked or not
            if ($(this).is(':checked')) {
                // alert();
                //check all children
                var _childid = $('input.common-child');
                $(_childid).prop('checked', true);
                $(".select-group-child").prop('checked', true);
                $(".select-room-child").prop('checked', true);
            } else {
                //uncheck all children
                var _childid = $('input.common-child');
                $(_childid).prop('checked', false);
                $(".select-group-child").prop('checked', false);
                $(".select-room-child").prop('checked', false);
            }
        });

        var _totalchilds = '<?= count($Childrens); ?>';

        $(document).on('click', '.common-child', function() {
            var _value = $(this).val();
            if ($(this).is(':checked')) {
                $('input.common-child[value="' + _value + '"]').prop('checked', true);
                $('input.child-group[value="' + _value + '"]').trigger('change');
                $('input.child-room[value="' + _value + '"]').trigger('change');

            } else {
                $('input.common-child[value="' + _value + '"]').prop('checked', false);
                $('input.child-group[value="' + _value + '"]').trigger('change');
                $('input.child-room[value="' + _value + '"]').trigger('change');
            }

            var _totalChildChecked = $('.child-tab:checked').length;
            if (_totalChildChecked == _totalchilds) {
                $("#select-all-child").prop('checked', true);
            } else {
                $("#select-all-child").prop('checked', false);
            }
        });

        $(document).on("click", ".select-group-child", function() {
            var _groupid = $(this).data('groupid');
            var _selector = $('input.common-child[data-groupid="' + _groupid + '"]');

            if ($(this).is(':checked')) {
                // $(_selector).prop('checked', true);
                $.each(_selector, function(index, val) {
                    $(".common-child[value='" + $(this).val() + "']").prop('checked', true);
                });
            } else {
                // $(_selector).prop('checked', false);
                $.each(_selector, function(index, val) {
                    $(".common-child[value='" + $(this).val() + "']").prop('checked', false);
                });
            }

            var _totalChildChecked = $('.child-tab:checked').length;
            if (_totalChildChecked == _totalchilds) {
                $("#select-all-child").prop('checked', true);
            } else {
                $("#select-all-child").prop('checked', false);
            }
        });

        $(document).on("change", ".child-group", function() {
            var _groupid = $(this).data('groupid');
            var _selector = '#select-group-child-' + _groupid;
            var _totalGroupChilds = $('.child-group[data-groupid="' + _groupid + '"]').length;
            var _totalGroupChildsChecked = $('.child-group[data-groupid="' + _groupid + '"]:checked').length;
            if (_totalGroupChilds == _totalGroupChildsChecked) {
                $(_selector).prop('checked', true);
            } else {
                $(_selector).prop('checked', false);
            }
        });

        $(document).on("click", ".select-room-child", function() {
            var _roomid = $(this).data('roomid');
            var _selector = $('input.common-child[data-roomid="' + _roomid + '"]');

            if ($(this).is(':checked')) {
                $.each(_selector, function(index, val) {
                    $(".common-child[value='" + $(this).val() + "']").prop('checked', true);
                });
            } else {
                $.each(_selector, function(index, val) {
                    $(".common-child[value='" + $(this).val() + "']").prop('checked', false);
                });
            }

            var _totalChildChecked = $('.child-tab:checked').length;
            if (_totalChildChecked == _totalchilds) {
                $("#select-all-child").prop('checked', true);
            } else {
                $("#select-all-child").prop('checked', false);
            }
        });

        $(document).on("change", ".child-room", function() {
            var _roomid = $(this).data('roomid');
            var _selector = '#select-room-child-' + _roomid;
            var _totalRoomChilds = $('.child-room[data-roomid="' + _roomid + '"]').length;
            var _totalRoomChildsChecked = $('.child-room[data-roomid="' + _roomid + '"]:checked').length;
            if (_totalRoomChilds == _totalRoomChildsChecked) {
                $(_selector).prop('checked', true);
            } else {
                $(_selector).prop('checked', false);
            }
        });

        $(document).on("click", "#insert-childtags", function() {
            $('.children-tags').empty();
            $('.unique-tag:checked').each(function(index, val) {
                $('.children-tags').append(`
                        <a href="#!" class="rem" data-role="remove" data-child="` + $(this).val() + `">
                            <input type="hidden" name="childId[]" value="` + $(this).val() + `">
                            <span class="badge badge-pill badge-outline-primary mb-1">` + $(this).data('name') + ` X </span>
                        </a>
                    `);
            });
            $(".children-tags").show();
        });

        $(document).on('click', '.rem', function() {
            var _childid = $(this).data('child');
            $(".common-child[value='" + _childid + "']").trigger('click');
            $(this).remove();
        });
    });
</script>
<script>
    $(document).on("change", ".form-check-input-csv", function() {
        // Hide all first
        $(".add-holiday, .add-holiday-csv").hide();

        // Show based on selection
        let selected = $(this).val();
        if (selected === "csv") {
            $(".add-holiday-csv").show();
        } else if (selected === "not_csv") {
            $(".add-holiday").show();
        }
    });

    $(document).ready(function() {
        function toggleEventCards() {
            $(".events-card, .announcement-card, .public-holiday-card").hide();

            let selected = $("#eventType").val();
            if (selected === "events") {
                $(".events-card").show();
            } else if (selected === "announcement") {
                $(".announcement-card").show();
            } else if (selected === "public_holiday") {
                $(".public-holiday-card").show();
            }
        }

        // Trigger on change
        $(document).on("change", "#eventType", toggleEventCards);

        @if(!empty($announcement) && in_array($announcement -> type, ['announcement', 'events']))

        $("#eventType").val("{{ $announcement->type }}").trigger('change');

        @endif



        // Trigger if backend passed an error and type
        @if(Session('type'))
        $("#eventType").val("{{ Session('type') }}").trigger('change');
        @else
          $("#eventType").val("announcement").trigger('change');
        @endif
       
    });

    $('.calendar').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

</script>

@endpush

@include('layout.footer')