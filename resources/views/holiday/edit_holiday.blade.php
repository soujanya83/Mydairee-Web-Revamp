@extends('layout.master')
@section('title', 'Public Holiday List')
@section('parentPageTitle', 'Setting')

@section('page-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<style>
.datepicker .prev, 
.datepicker .next {
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
}

    /*  */
    #holidayEditModal .modal-body {
        max-height: 70vh;
        /* or any value */
        overflow-y: auto;
    }

    /* Limit modal height and allow scroll */
    #selectChildrenModal .modal-body {
        max-height: 50vh;
        /* limit vertical height */
        overflow-y: scroll;
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
    .ck-editor__editable_inline {
        min-height: 300px;
        /* This is like setting more "rows" */
    }

    /* Slide-out style for child modal */
    .modal.right .modal-dialog {
        position: fixed;
        margin: auto;
        width: 80%;
        height: 100%;
        right: 0;
        top: 0;
        transform: translate3d(100%, 0, 0);
        transition: all 0.3s ease-out;
    }

    .modal.right.show .modal-dialog {
        transform: translate3d(0, 0, 0);
    }

    #selectChildrenModal .modal-body {
        max-height: 70vh;
        /* adjust as needed */
        overflow-y: auto;
    }
</style>


@endsection

@section('content')

<div class="d-flex justify-content-end" style="margin-top: -52px;margin-right:50px">
    <a href="{{ route('announcements.create') }}" class="btn btn-outline-info">
        Add New Holiday
    </a>


</div>
<hr>

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
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="modal-body " style="padding: 20px;">
                <div class="mb-3">
                    <label for="eventType" style="font-weight: bold;">Select Type</label>
                    <select class="form-control" name="type" id="eventType" required>
                        <option value="">-- Select --</option>
                        <option value="events">Event</option>
                        <option value="announcement">Announcement</option>
                        <option value="public_holiday" selected>Public Holiday</option>
                    </select>
                </div>

                <form action="{{ route('settings.holiday.update', $holidayData->id) }}"
                    method="post"
                    class="update-holiday"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="holidayid" value="{{ $holidayData->id }}">
                    <input type="hidden" name="_method" id="putmethod" value="PUT">

                    <div class="edit-holidays">

                        <!-- <div class="mb-3">
                            <label for="editDate" style="font-weight: bold;">Date</label>
                            <input type="date" class="form-control calendar" name="date" id="editDate" value="{{ $holidayData->Holiday_date }}" required>
                        </div> -->
<!--  -->

<div class=" mb-3 date">
    <label for="eventDate" class="form-label fw-bold">📅 Date</label>
    <div class="input-group">
        <input type="text" id="editDate"
            class="form-control calendar"
            name="date"
            value="{{ isset($holidayData->Holiday_date) 
    ? \Carbon\Carbon::parse($holidayData->Holiday_date)->format('d-m-Y') 
    : '' }}"

            data-date-format="dd-mm-yyyy"
            placeholder="Select date" required>
            

        <span class="input-group-text">
            <i class="simple-icon-calendar"></i>
        </span>
    </div>
</div>


                        <!--  -->

                        <div class="mb-3">
                            <label for="editOccasion" style="font-weight: bold;">Occasion</label>
                            <input type="text" class="form-control" name="occasion" id="editOccasion" value="{{ $holidayData->occasion }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="editState" style="font-weight: bold;">State</label>
                            <input type="text" class="form-control" name="state" id="editState" value="{{ $holidayData->state }}" required>
                        </div>

                        <div class="mb-3">
                            <label style="font-weight: bold;">Status</label><br>
                            <label><input type="radio" name="status" value="1" id="editStatusActive"> Active</label>
                            <label style="margin-left: 20px;"><input type="radio" name="status" value="0" id="editStatusInactive"> Inactive</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" id="holidaySaveBtn">Save </button>
                        <a href="{{ url()->previous() }}" class="btn btn-info">
    Cancel
</a>

                    </div>
                </form>
            </div>


        </div>
    </div>
</div>



<!-- add children -->
<div class="modal modal-right" id="selectChildrenModal" tabindex="-1" role="dialog"
    aria-labelledby="selectChildrenModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
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



<script>
    function formatDate(dateString) {
        let date = new Date(dateString);
        if (isNaN(date)) return dateString; // fallback if invalid
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }

    $(document).ready(function() {
        $('#eventType').on('change', function() {
            const eventType = $(this).val();
            const edit_holidays = $('.edit-holidays');
            edit_holidays.html('');

            let html = '';

            // ✅ Prefilled values from backend
            let eventtitle = @json($holidayData -> occasion ?? '');
            let formatdate = @json($holidayData -> Holiday_date ?? '');
            let state = @json($holidayData -> state ?? '');
            let status = @json($holidayData -> status ?? 0);

            let date = formatDate(formatdate);

            $('.update-holiday').attr("action", '');
            $('.update-holiday').attr("method", "");

            if ($('#putmethod').length) {
                $('#putmethod').remove(); // removes only if exists
            }

            if (eventType === 'events') {


                // Both Events & Announcements use the same structure
                let url = "{{ route('announcements.store') }}";
                $('.update-holiday').attr("method", "post");
                $('.update-holiday').attr("action", url);

                html = `
                <input type="hidden" name="type" value="${eventType}">
            

             



                <div class=" mb-3 date">
    <label for="eventDate" class="form-label fw-bold">📅 Date</label>
    <div class="input-group">
        <input type="text" id="editDate"
            class="form-control calendar"
            name="date"
            value="${date}"
            data-date-format="dd-mm-yyyy"
            placeholder="Select date" required>
            

        <span class="input-group-text">
            <i class="simple-icon-calendar"></i>
        </span>
    </div>
</div>

                <div class="mb-3">
                    <label for="eventTitle" style="font-weight: bold;">Title</label>
                    <input type="text" class="form-control" name="title" id="eventTitle" value="${eventtitle}" required>
                </div>

                <div class="mb-3">
                    <label for="eventDescription" style="font-weight: bold;">Description</label>
                    <input type="text" class="form-control" name="text" id="eventDescription" >
                </div>

                <div class="mb-3">
                    <label for="audience" class="form-label fw-bold">👥 Access</label>
                    <div>
                        <select class="form-select form-control" name="audience" required>
                            <option value="all" {{ isset($announcement) && $announcement->audience == 'all' ? 'selected' : '' }}>All</option>
                            <option value="parents" {{ isset($announcement) && $announcement->audience == 'parents' ? 'selected' : '' }}>Parents</option>
                            <option value="staff" {{ isset($announcement) && $announcement->audience == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
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
                    <h4>Media Upload Section</h4>
                    <div class="media-upload-box p-4 border rounded bg-light text-center">
                        <label for="mediaInputUpload1" class="btn btn-outline-info">
                            Select Image (png, jpeg, jpg) or PDF
                        </label>
                        <input type="file" id="mediaInputUpload1" name="media[]" class="d-none" accept="image/*,application/pdf">
                        <small class="form-text text-muted mt-2">Only image and PDF allowed, up to 2MB</small>
                    </div>
                    <div id="mediaPreviewUpload1" class="row mt-4"></div>
                </div>

                <div class="form-group select-children">
                    <button type="button" class="btn btn-info mb-1" data-toggle="modal"
                        data-backdrop="static" data-target="#selectChildrenModal">+ Add Children</button>
                </div>

                <div class="children-tags">
                    @forelse ($announcement->children ?? [] as $child)
                        <a href="#!" class="rem" data-role="remove" data-child="{{ $child->id }}">
                            <input type="hidden" name="childId[]" value="{{ $child->id }}">
                            <span class="badge badge-pill badge-outline-info mb-1">{{ $child->name }} ✖</span>
                        </a>
                    @empty
                        <p>No children selected</p>
                    @endforelse
                </div>
            `;


            } else if (eventType === 'announcement') {
                $('.update-holiday').attr("method", "post");
                let url = "{{ route('announcements.store') }}";

                $('.update-holiday').attr("action", url);
                html = `
                <input type="hidden" name="type" value="${eventType}">

                     <div class=" mb-3 date">
    <label for="eventDate" class="form-label fw-bold">📅 Date</label>
    <div class="input-group">
        <input type="text" id="editDate"
            class="form-control calendar"
            name="date"
            value="${date}"
            data-date-format="dd-mm-yyyy"
            placeholder="Select date" required>
            

        <span class="input-group-text">
            <i class="simple-icon-calendar"></i>
        </span>
    </div>
</div>

                <div class="mb-3">
                    <label for="eventTitle" style="font-weight: bold;">Title</label>
                    <input type="text" class="form-control" name="title" id="eventTitle" value="${eventtitle}" required>
                </div>

                <div class="mb-3">
                    <label for="eventDescription" style="font-weight: bold;">Description</label>
                    <input type="text" class="form-control" name="text" id="eventDescription" >
                </div>

                <div class="mb-3">
                    <label for="audience" class="form-label fw-bold">👥 Access</label>
                    <div>
                        <select class="form-select form-control" name="audience" required>
                            <option value="all" {{ isset($announcement) && $announcement->audience == 'all' ? 'selected' : '' }}>All</option>
                            <option value="parents" {{ isset($announcement) && $announcement->audience == 'parents' ? 'selected' : '' }}>Parents</option>
                            <option value="staff" {{ isset($announcement) && $announcement->audience == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                </div>

                <div class="form-group media-div">
                    <h4>Media Upload Section</h4>
                    <div class="media-upload-box p-4 border rounded bg-light text-center">
                        <label for="mediaInputUpload1" class="btn btn-outline-info">
                            Select Image (png, jpeg, jpg) or PDF
                        </label>
                        <input type="file" id="mediaInputUpload1" name="media[]" class="d-none" accept="image/*,application/pdf">
                        <small class="form-text text-muted mt-2">Only image and PDF allowed, up to 2MB</small>
                    </div>
                    <div id="mediaPreviewUpload1" class="row mt-4"></div>
                </div>

                <div class="form-group select-children">
                    <button type="button" class="btn btn-info mb-1" data-toggle="modal"
                        data-backdrop="static" data-target="#selectChildrenModal">+ Add Children</button>
                </div>

                <div class="children-tags">
                    @forelse ($announcement->children ?? [] as $child)
                        <a href="#!" class="rem" data-role="remove" data-child="{{ $child->id }}">
                            <input type="hidden" name="childId[]" value="{{ $child->id }}">
                            <span class="badge badge-pill badge-outline-info mb-1">{{ $child->name }} ✖</span>
                        </a>
                    @empty
                        <p>No children selected</p>
                    @endforelse
                </div>
            `;

            } else if (eventType === 'public_holiday') {
                // ✅ Simpler form for public holidays
                let updateId = @json($holidayData -> id ?? null);

                if (updateId) {
                    let updateUrl = `{{ route('settings.holiday.update', ':id') }}`.replace(':id', updateId);

                    $('.update-holiday').attr("action", updateUrl);
                    // keep POST, spoof PUT
                }


                html = `
            <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="type" value="public_holiday">

             


        <div class=" mb-3 date">
    <label for="eventDate" class="form-label fw-bold">📅 Date</label>
    <div class="input-group">
        <input type="text" id="holidayDate"
            class="form-control calendar"
            name="date"
            value="${date}"
            data-date-format="dd-mm-yyyy"
            placeholder="Select date" required>
            

        <span class="input-group-text">
            <i class="simple-icon-calendar"></i>
        </span>
    </div>
</div>

                

                <div class="mb-3">
                    <label for="holidayOccasion" style="font-weight: bold;">Occasion</label>
                    <input type="text" class="form-control" name="occasion" id="holidayOccasion" value="${eventtitle}" required>
                </div>

                <div class="mb-3">
                    <label for="holidayState" style="font-weight: bold;">State</label>
                    <input type="text" class="form-control" name="state" id="holidayState" value="${state}" required>
                </div>

                <div class="mb-3">
                    <label style="font-weight: bold;">Status</label><br>
                    <label><input type="radio" name="status" value="1" ${status == 1 ? 'checked' : ''}> Active</label>
                    <label style="margin-left: 20px;"><input type="radio" name="status" value="0" ${status == 0 ? 'checked' : ''}> Inactive</label>
                </div>
            `;
            }

            edit_holidays.html(html);
            initDatePickers();
        });


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


<!-- image preview  -->
<script>
    let mediaFilesUpload1 = []; // store uploaded files

    // Handle file selection (delegated because input is dynamic)
    $(document).on("change", "#mediaInputUpload1", function(e) {
        const mediaInputUpload1 = this;
        const mediaPreviewUpload1 = document.getElementById("mediaPreviewUpload1");

        const newFiles = Array.from(e.target.files);

        // Restrict only 1 file
        if (newFiles.length > 1 || mediaFilesUpload1.length >= 1) {
            this.value = "";
            return;
        }

        const file = newFiles[0];

        // Validate size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            this.value = "";
            alert("File must be under 2MB.");
            return;
        }

        mediaPreviewUpload1.innerHTML = ""; // reset preview
        const wrapper = document.createElement("div");
        wrapper.className = "col-md-4 position-relative mb-3";

        let previewHtml = "";
        const fileURL = URL.createObjectURL(file);

        if (file.type.startsWith("image/")) {
            previewHtml = `<img src="${fileURL}" class="media-thumb rounded w-100" alt="Image">`;
        } else if (file.type === "application/pdf") {
            previewHtml = `<embed src="${fileURL}" type="application/pdf" class="media-thumb rounded w-100" height="200px"/>`;
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

        mediaPreviewUpload1.appendChild(wrapper);

        mediaFilesUpload1 = [file]; // keep only one
        syncMediaInputUpload1(mediaInputUpload1);
    });

    // Remove handler
    $(document).on("click", ".remove-file", function() {
        mediaFilesUpload1 = [];
        $("#mediaInputUpload1").val("");
        $("#mediaPreviewUpload1").html("");
    });

    // Sync selected files back to input
    function syncMediaInputUpload1(inputEl) {
        const dt = new DataTransfer();
        mediaFilesUpload1.forEach((file) => dt.items.add(file));
        inputEl.files = dt.files;
    }


    // When child modal closes, restore parent modal state
    // when clicking "Add Children"


    // When child modal closes, restore parent modal scroll
    $('#selectChildrenModal').on('hidden.bs.modal', function() {
        if ($('#holidayEditModal').hasClass('show')) {
            $('body').addClass('modal-open'); // restore scroll lock
        }
    });

//       $('.calendar').datepicker({
//     format: 'dd-mm-yyyy',
//     todayHighlight: true,
//     autoclose: true
// });

function initDatePickers() {
    $('.calendar').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
}

$(document).ready(function () {
    $('.calendar').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        clearBtn: true,
        templates: {
            leftArrow: '<i class="fa fa-chevron-left"></i>',
            rightArrow: '<i class="fa fa-chevron-right"></i>'
        }
    }).on('hide', function (e) {
        if (!$(this).val()) {
            $(this).val('');
        }
    });
});



</script>





@stop