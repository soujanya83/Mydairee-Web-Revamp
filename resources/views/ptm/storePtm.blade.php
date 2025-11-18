@extends('layout.master')
@section('title', 'Store PTM')
@section('parentPageTitle', 'ptm')

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/summernote/dist/summernote.css') }}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
@section('content')
    <style>
        #selected_date {
            border: 1.5px solid #e29c33 !important;
            background: transparent;
        }

        #selected_date::placeholder {
            color: #e29c33 !important;
            opacity: 1;
            /* ensures exact color */
        }

        #selected_date:focus {
            border-color: #e29c33 !important;
            box-shadow: 0 0 6px rgba(226, 156, 51, 0.4) !important;
        }

        /* Page background */
        body {
            background: linear-gradient(180deg, #f3f7fb 0%, #ffffff 100%);
            color: #243447;
            font-smooth: always;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(34, 60, 80, 0.06);
            overflow: hidden;
        }

        .card .body {
            padding: 20px;
        }

        /* Inputs & editors */
        .form-control {
            border-radius: 10px;
            border: 1px solid #e7eef6;
            transition: box-shadow .15s ease, border-color .15s ease;
        }

        .form-control:focus {
            border-color: #6aa8ff;
            box-shadow: 0 8px 24px rgba(60, 120, 220, 0.07);
            outline: none;
        }

        .ck-editor__editable_inline {
            min-height: 150px;
            border-radius: 8px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(90deg, #4b8df8 0%, #2bb7f4 100%);
            border: none;
            color: #fff;
            box-shadow: 0 8px 20px rgba(43, 122, 246, 0.12);
        }

        .btn-success {
            background: linear-gradient(90deg, #28b785 0%, #2ec27e 100%);
            border: none;
            color: #fff;
        }


        .btn-outline-primary,
        .btn-outline-success,
        .btn-outline-brown {
            border-radius: 8px;
        }

        .btn-outline-brown {
            color: #975543;
            border: 1px solid #975543;
            background-color: transparent;
        }

        .btn-outline-brown:hover {
            color: #fff;
            background-color: #975543;
            border-color: #975543;
        }

        .btn-outline-brown:focus,
        .btn-outline-brown.focus,
        .btn-outline-brown:active:focus {
            outline: none !important;
            box-shadow: 0 0 6px rgba(151, 85, 67, 0.4) !important;
            /* brown glow */
            border-color: #975543 !important;
        }

        /* Badges */
        .badge {
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 600;
        }

        /* Modals */
        .modal-content {
            border-radius: 12px;
            border: none;
        }

        /* Toasts */
        #toast-container {
            right: 22px;
            bottom: 22px;
        }

        .toast {
            border-radius: 8px;
            padding: 10px 14px;
            box-shadow: 0 8px 24px rgba(33, 40, 50, 0.07);
        }

        .toast-success {
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
        }

        .toast-error {
            background: linear-gradient(90deg, #e5534b 0%, #d32f2f 100%);
        }

        /* Small responsive tweaks */
        @media (max-width: 768px) {
            .card .body {
                padding: 14px;
            }

            .modal-dialog {
                max-width: 95%;
            }
        }

        #selectedRoomsPreview .badge {
            background: linear-gradient(to right, #4caf50, #81c784);
        }

        #selectedChildrenPreview .badge {
            background: linear-gradient(to right, #00bcd4, #2196f3);
        }

        #selectedStaffPreview .badge {
            /*background: linear-gradient(to right, #dad866, #918f2d);*/
            background: linear-gradient(to right, #975543, #7e2c16);
        }

        #selectedChildrenPreview .badge,
        #selectedRoomsPreview .badge,
        #selectedStaffPreview .badge {
            font-size: 13px;
            padding: 6px 10px;
            border-radius: 8px;
            /* background: linear-gradient(to right, #4caf50, #81c784);*/
            color: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 4px;
        }

        .badge {
            padding: 4px 8px;
            text-transform: uppercase;
            line-height: 12px;
            border: 1px solid;
            font-weight: 400;
        }

        .select-section .btn {
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-success {
            color: #28a745;
            background-color: transparent;
            background-image: none;
            border-color: #28a745;
        }
    </style>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <h6 class="mb-2"><i class="fas fa-times-circle mr-2"></i> Please fix the following errors:</h6>
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="body">
                    <form id="ptmForm" method="POST" enctype="multipart/form-data" action="{{ route('ptm.store') }}">
                        @csrf
                        <div class="row">

                            {{-- Rooms --}}
                            <div class="col-md-6 select-section">
                                <label class="font-weight-bold">Rooms</label><br>
                                <button type="button" class="btn btn-outline-success" data-toggle="modal"
                                    data-target="#roomsModal">Select Rooms</button>
                                @php
                                    $selectedRooms = old(
                                        'selected_rooms',
                                        isset($ptm) ? $ptm->room->pluck('id')->implode(',') : '',
                                    );
                                    $selectedRoomsArray = $selectedRooms ? explode(',', $selectedRooms) : [];
                                @endphp
                                <input type="hidden" name="selected_rooms" id="selected_rooms"
                                    value="{{ $selectedRooms }}">
                                <div id="selectedRoomsPreview" class="mt-3">
                                    @if (isset($rooms) && !empty($selectedRoomsArray))
                                        @foreach ($selectedRoomsArray as $roomId)
                                            @php $room = $rooms->firstWhere('id', $roomId); @endphp
                                            @if ($room)
                                                <span class="badge badge-success mr-1">{{ $room->name }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- Children --}}
                            <div class="col-md-6">
                                <label class="font-weight-bold">Children</label><br>
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                    data-target="#childrenModal">Select Children</button>
                                @php
                                    $selectedChildren = old(
                                        'selected_children',
                                        isset($ptm) ? $ptm->children->pluck('id')->implode(',') : '',
                                    );
                                    $selectedChildrenArray = $selectedChildren ? explode(',', $selectedChildren) : [];
                                @endphp
                                <input type="hidden" name="selected_children" id="selected_children"
                                    value="{{ $selectedChildren }}">
                                <div id="selectedChildrenPreview" class="mt-3">
                                    @if (isset($childrens) && !empty($selectedChildrenArray))
                                        @foreach ($selectedChildrenArray as $childId)
                                            @php $child = $childrens->firstWhere('id', $childId); @endphp
                                            @if ($child)
                                                <span class="badge badge-info mr-1">{{ $child->name }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- Educators --}}
                            <div class="col-md-6 mt-4">
                                <label class="font-weight-bold">Tag Educators</label><br>
                                <button type="button" class="btn btn-outline-brown" data-toggle="modal"
                                    data-target="#staffModal">Select Educators</button>
                                @php
                                    $selectedStaff = old(
                                        'selected_staff',
                                        isset($ptm) ? $ptm->staff->pluck('id')->implode(',') : '',
                                    );
                                    $selectedStaffArray = $selectedStaff ? explode(',', $selectedStaff) : [];
                                @endphp
                                <input type="hidden" name="selected_staff" id="selected_staff"
                                    value="{{ $selectedStaff }}">
                                <div id="selectedStaffPreview" class="mt-3">
                                    @if (isset($educators) && !empty($selectedStaffArray))
                                        @foreach ($selectedStaffArray as $staffId)
                                            @php $staff = $educators->firstWhere('id', $staffId); @endphp
                                            @if ($staff)
                                                <span class="badge badge-danger mr-1">{{ $staff->name }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- Date --}}
                            @php
                                $convertedDates = [];
                                $displayDates = [];

                                if (!empty($selectedDates)) {
                                    foreach ($selectedDates as $d) {
                                        $d = trim($d);

                                        // CASE 1: DB format = Y-m-d  ✔ (most important)
                                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
                                            $convertedDates[] = $d; // this goes to Flatpickr
                                            $displayDates[] = \Carbon\Carbon::parse($d)->format('d-m-Y'); // this goes to badges
                                        }

                                        // CASE 2: When old form input returns d-m-Y (only during create fail/save)
                                        elseif (preg_match('/^\d{2}-\d{2}-\d{4}$/', $d)) {
                                            $convertedDates[] = \Carbon\Carbon::createFromFormat('d-m-Y', $d)->format(
                                                'Y-m-d',
                                            );
                                            $displayDates[] = $d;
                                        }
                                    }
                                }
                            @endphp
                            <div class="col-md-6 mt-4">
                                <label class="font-weight-bold">Date</label><br>

                                <input type="text" class="form-control" id="selected_date" {{--  value="{{ implode(',', $displayDates) }}"  --}}
                                    placeholder="Choose Expected Meeting date">

                                <input type="hidden" name="selected_dates" id="selected_dates">
                                {{--  value="{{ implode(',', $displayDates) }}" --}}

                                <div id="selectedDatePreview" class="mt-3">
                                    @foreach ($displayDates as $d)
                                        <span class="badge badge-danger mr-1"
                                            style="background:#e29c33;color:#ffffff;">{{ $d }}</span>
                                    @endforeach
                                    <div id="dateSlotPreview" class="mt-3"></div>

                                    <input type="hidden" id="date_slot_map" name="date_slot_map">

                                </div>
                            </div>

                            {{-- Hidden ID --}}
                            <input type="hidden" name="id" value="{{ isset($ptm) ? $ptm->id : '' }}">

                            {{-- Title --}}
                            <div class="col-md-6 mt-4">
                                <label for="editor6" class="font-weight-bold">Title</label>
                                <textarea id="editor6" name="title" class="form-control ckeditor" placeholder="Enter PTM Title...">{!! isset($ptm) ? $ptm->title : '' !!}</textarea>
                            </div>

                            {{-- Objective --}}
                            <div class="col-md-6 mt-4">
                                <label for="editor1" class="font-weight-bold">Objective</label>
                                <textarea id="editor1" name="objective" class="form-control ckeditor" placeholder="Enter PTM Objective...">{!! isset($ptm) ? $ptm->objective : '' !!}</textarea>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12 mt-4 d-flex justify-content-end" style="gap: 10px;">
                                <button type="submit" name="action" value="draft" class="btn btn-primary">Save as
                                    Draft</button>
                                <button type="button" id="publishBtn" class="btn btn-success">Publish</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Rooms Modal -->
    <div class="modal" id="roomsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Rooms</h5>
                    <input type="text" id="roomSearch" class="form-control ml-3" placeholder="Search rooms..."
                        style="max-width: 250px;">
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:550px; overflow-y:auto;">
                    <div id="roomsList" class="row"></div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-end align-items-center">
                    <div class="form-check mb-0 mr-3 d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" id="selectAllRooms">
                        <label class="form-check-label ml-2 mb-0" for="selectAllRooms">Select All</label>
                    </div>
                    <button type="button" id="confirmRooms" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Confirm
                    </button>
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                </div>


            </div>
        </div>
    </div>


    <!-- Staff Modal -->
    <div class="modal" id="staffModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Staff</h5>
                    <div class="d-flex align-items-center">
                        <input type="text" id="staffSearch" class="form-control" placeholder="Search staff..."
                            style="max-width: 250px;">
                        <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:550px; overflow-y:auto;">
                    <div id="staffList" class="row"></div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-end align-items-center">
                    <div class="form-check mb-0 mr-3 d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" id="selectAllStaff">
                        <label class="form-check-label ml-2 mb-0" for="selectAllStaff">Select All</label>
                    </div>
                    <button type="button" id="confirmStaff" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Confirm
                    </button>
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>

                </div>

            </div>
        </div>
    </div>


    <!-- Children Modal -->
    <div class="modal" id="childrenModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Children</h5>
                    <div class="d-flex align-items-center">
                        <input type="text" id="childSearch" class="form-control" placeholder="Search children..."
                            style="max-width: 250px;">
                        <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:550px; overflow-y:auto;">
                    <div id="childrenList" class="row"></div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-end align-items-center">
                    <div class="form-check mb-0 mr-3 d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" id="selectAllChildren">
                        <label class="form-check-label ml-2 mb-0" for="selectAllChildren">Select All</label>
                    </div>
                    <button type="button" id="confirmChildren" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Confirm
                    </button>
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>

                </div>

            </div>
        </div>
    </div>


    <!-- Publish Confirmation Modal -->
    <div class="modal fade" id="publishConfirmModal" tabindex="-1" role="dialog"
        aria-labelledby="publishConfirmLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="publishConfirmLabel">Confirm Publish</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to <strong>publish</strong> this PTM?
                        Once published, it will be visible to all linked users. Abd it cannot be reverted back for edit.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmPublishBtn" class="btn btn-success">Yes, Publish</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Slot Selection Modal -->
    <div class="modal" id="slotModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Slots for <span id="currentDateLabel"></span></h5>
                    <div class="d-flex align-items-center">
                        <input type="text" id="slotSearch" class="form-control" placeholder="Search Slot..."
                            style="max-width: 250px;">
                        <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:550px; overflow-y:auto;">
                    <div id="slotList" class="row"></div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-end align-items-center">
                    <div class="form-check mb-0 mr-3 d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" id="selectAllSlots">
                        <label class="form-check-label ml-2 mb-0" for="selectAllSlots">Select All</label>
                    </div>

                    <button type="button" id="confirmslot" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Confirm
                    </button>
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>

    @include('layout.footer')
    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Global variable for date-wise slots mapping
        let dateWiseSlots = {};

        // ✅ Initialize dateWiseSlots with existing data for edit mode (before DOM ready)
        @if (isset($dateSlotMap) && !empty($dateSlotMap))
            dateWiseSlots = @json($dateSlotMap);
            console.log('Edit mode: Pre-loaded dateWiseSlots', dateWiseSlots);
        @endif

        $(document).ready(function() {

            // Rooms
            let selectedRooms = new Set($('#selected_rooms').val().split(',').filter(id => id));
            $('#roomsModal').on('show.bs.modal', function() {
                $.get('{{ route('ptm.get.rooms') }}', function(res) {
                    if (res.success) {
                        let html = '';
                        res.rooms.sort((a, b) => a.name.localeCompare(b.name));
                        res.rooms.forEach(room => {
                            const checked = selectedRooms.has(room.id.toString()) ?
                                'checked' : '';
                            html += `<div class="col-md-4 mb-2 room-item">
                        <div class="form-check">
                            <input class="form-check-input room-checkbox" type="checkbox" value="${room.id}" id="room-${room.id}" ${checked}>
                            <label class="form-check-label" for="room-${room.id}">${room.name}</label>
                        </div>
                    </div>`;
                        });
                        $('#roomsList').html(html);
                    }
                });
            });

            $('#roomSearch').on('keyup', function() {
                const val = $(this).val().toLowerCase();
                $('.room-item').each(function() {
                    const name = $(this).find('label').text().toLowerCase();
                    $(this).toggle(name.includes(val));
                });
            });

            $('#confirmRooms').on('click', function() {
                selectedRooms = new Set();
                let nameHtml = '';
                $('.room-checkbox:checked').each(function() {
                    selectedRooms.add($(this).val());
                    nameHtml +=
                        `<span class="badge badge-success mr-1">${$(this).next('label').text()}</span>`;
                });
                $('#selected_rooms').val([...selectedRooms].join(','));
                $('#selectedRoomsPreview').html(nameHtml);
                $('#roomsModal').modal('hide');
                $('.modal-backdrop').remove();
            });

            // Handle Select All checkbox
            $(document).on('change', '#selectAllRooms', function() {
                const isChecked = $(this).is(':checked');
                $('.room-checkbox').prop('checked', isChecked);
            });

            // When rooms are loaded, reset Select All checkbox if not all selected
            $('#roomsModal').on('shown.bs.modal', function() {
                const total = $('.room-checkbox').length;
                const checked = $('.room-checkbox:checked').length;
                $('#selectAllRooms').prop('checked', total > 0 && total === checked);
            });

            // Update "Select All" checkbox if user manually unchecks one
            $(document).on('change', '.room-checkbox', function() {
                const total = $('.room-checkbox').length;
                const checked = $('.room-checkbox:checked').length;
                $('#selectAllRooms').prop('checked', total > 0 && total === checked);
            });


            // Children
            let selectedChildren = new Set($('#selected_children').val().split(',').filter(id => id));
            $('#childrenModal').on('show.bs.modal', function() {
                let selectedrooms = $('#selected_rooms').val();
                $.get('{{ route('ptm.get.children') }}', {
                    rooms: selectedrooms
                }, function(response) {
                    if (response.success) {
                        let html = '';
                        response.children.sort((a, b) => a.name.localeCompare(b.name));
                        response.children.forEach(child => {

                            const checked = selectedChildren.has(child.id.toString()) ?
                                'checked' : '';
                            html += `<div class="col-md-4 mb-2 child-item">
                        <div class="form-check">
                            <input class="form-check-input child-checkbox" type="checkbox" value="${child.id}" id="child-${child.id}" ${checked}>
                            <label class="form-check-label" for="child-${child.id}">${child.name} ${child.lastname}</label>
                        </div>
                    </div>`;
                        });
                        $('#childrenList').html(html);
                    }
                });
            });
            $('#childSearch').on('keyup', function() {
                const search = $(this).val().toLowerCase();
                $('.child-item').each(function() {
                    const name = $(this).find('.form-check-label').text().toLowerCase();
                    $(this).toggle(name.includes(search));
                });
            });
            $('#confirmChildren').on('click', function() {
                selectedChildren = new Set();
                let nameHtml = '';
                $('.child-checkbox:checked').each(function() {
                    selectedChildren.add($(this).val());
                    nameHtml +=
                        `<span class="badge badge-info mr-1">${$(this).next('label').text()}</span>`;
                });
                $('#selected_children').val([...selectedChildren].join(','));
                $('#selectedChildrenPreview').html(nameHtml);
                $('#childrenModal').modal('hide');
                $('.modal-backdrop').remove();
            });

            // Handle Select All for children
            $(document).on('change', '#selectAllChildren', function() {
                const isChecked = $(this).is(':checked');
                $('.child-checkbox').prop('checked', isChecked);
            });
            // When children are loaded via AJAX, reset Select All checkbox
            $('#childrenModal').on('shown.bs.modal', function() {
                const total = $('.child-checkbox').length;
                const checked = $('.child-checkbox:checked').length;
                $('#selectAllChildren').prop('checked', total > 0 && total === checked);
            });
            // Update "Select All" checkbox if user manually toggles one
            $(document).on('change', '.child-checkbox', function() {
                const total = $('.child-checkbox').length;
                const checked = $('.child-checkbox:checked').length;
                $('#selectAllChildren').prop('checked', total > 0 && total === checked);
            });


            // Staff
            let selectedStaff = new Set($('#selected_staff').val().split(',').filter(id => id));
            $('#staffModal').on('show.bs.modal', function() {
                const selectedRooms = $('#selected_rooms').val(); // get room IDs
                if (!selectedRooms) {
                    alert('Please select at least one room first.');
                    $('#staffModal').modal('hide');
                    return;
                }

                $.get('{{ route('ptm.get-staff') }}', {
                    rooms: selectedRooms
                }, function(response) {
                    if (response.success) {
                        let html = '';
                        response.staff.sort((a, b) => a.name.localeCompare(b.name));
                        response.staff.forEach(staff => {

                            const checked = selectedStaff.has(staff.id.toString()) ?
                                'checked' : '';
                            html += `<div class="col-md-4 mb-2 staff-item">
                    <div class="form-check">
                        <input class="form-check-input staff-checkbox" type="checkbox" value="${staff.id}" id="staff-${staff.id}" ${checked}>
                        <label class="form-check-label" for="staff-${staff.id}">${staff.name}</label>
                    </div>
                </div>`;
                        });
                        $('#staffList').html(html);
                    }
                });
            });
            // Staff Search
            $('#staffSearch').on('keyup', function() {
                const search = $(this).val().toLowerCase();
                $('.staff-item').each(function() {
                    const name = $(this).find('.form-check-label').text().toLowerCase();
                    $(this).toggle(name.includes(search));
                });
            });
            $('#confirmStaff').on('click', function() {
                selectedStaff = new Set();
                let nameHtml = '';
                $('.staff-checkbox:checked').each(function() {
                    selectedStaff.add($(this).val());
                    nameHtml +=
                        `<span class="badge badge-danger mr-1">${$(this).next('label').text()}</span>`;
                });
                $('#selected_staff').val([...selectedStaff].join(','));
                $('#selectedStaffPreview').html(nameHtml);
                $('#staffModal').modal('hide');
                $('.modal-backdrop').remove();
            });
            // Staff Select All
            $('#selectAllStaff').on('change', function() {
                const checked = $(this).is(':checked');
                $('#staffList .staff-checkbox').prop('checked', checked);
            });


            // Handle Publish confirmation
            $('#publishBtn').on('click', function(e) {
                e.preventDefault(); // stop immediate form submission
                $('#publishConfirmModal').modal('show'); // show confirmation modal
            });
            $('#confirmPublishBtn').on('click', function() {
                // DEBUG: Check slot data before submission
                console.log('=== PUBLISH BUTTON CLICKED ===');
                console.log('dateWiseSlots object:', dateWiseSlots);
                console.log('date_slot_map value:', $('#date_slot_map').val());
                console.log('selected_dates value:', $('#selected_dates').val());

                // When user confirms, set action value to "Published" and submit form
                $('<input>').attr({
                    type: 'hidden',
                    name: 'action',
                    value: 'Published'
                }).appendTo('#ptmForm');

                $('#ptmForm').submit(); // submit the form
            });


        });


        document.addEventListener('DOMContentLoaded', function() {

            const input = document.getElementById('selected_date');
            const hiddenInput = document.getElementById('selected_dates');
            const previewDiv = document.getElementById('selectedDatePreview');

            let preselectedDates = @json($convertedDates); // Y-m-d format only

            // Render date-slot badges dynamically (only dates WITH slots)
            function updateDateSlotPreview() {
                let html = "";

                // Only keep dates that have at least one slot
                const datesWithSlots = Object.keys(dateWiseSlots)
                    .filter(d => Array.isArray(dateWiseSlots[d]) && dateWiseSlots[d].length > 0)
                    .sort();

                // If we're showing date-slot badges, remove any old date-only badges
                if (datesWithSlots.length > 0) {
                    // Remove only the top-level date-only badges rendered by Blade
                    // (slot badges live inside #dateSlotPreview and won't be affected)
                    $("#selectedDatePreview > span.badge, #selectedDatePreview > span.badge-danger").remove();
                }

                datesWithSlots.forEach(date => {
                    let showDate = date.split("-").reverse().join("-"); // Y-m-d → d-m-Y
                    let slots = dateWiseSlots[date];

                    let slotBadges = slots.map(s =>
                        `<span class="badge badge-primary mr-1">${s}</span>`
                    ).join("");

                    html += `
                        <div class="mb-2">
                            <span class="badge" style="background:#e29c33;color:#fff;">${showDate}</span>
                            ${slotBadges}
                        </div>
                    `;
                });

                $("#dateSlotPreview").html(html);
                $("#date_slot_map").val(JSON.stringify(dateWiseSlots)); // Update hidden input
            }

            // Fetch available slots for a date and auto-open modal
            function fetchSlotsForDate(date) {
                console.log('fetchSlotsForDate called with date:', date);

                let selectedRooms = $('#selected_rooms').val();
                console.log('Selected rooms:', selectedRooms);

                if (!selectedRooms) {
                    alert("Please select rooms first!");
                    return;
                }

                $.get('{{ route('ptm.get-slots') }}', {
                    date: date,
                    rooms: selectedRooms
                }, function(res) {
                    console.log('AJAX response:', res);

                    if (!res.success || !res.slot || res.slot.length === 0) {
                        alert("No slots available for this date.");
                        return;
                    }

                    // Filter out empty slots
                    let validSlots = res.slot.filter(slot => slot.time && slot.time.trim() !== '');
                    console.log('Valid slots:', validSlots);

                    if (validSlots.length === 0) {
                        alert("No valid slots found for this date. Please create slots first.");
                        return;
                    }

                    let html = "";
                    let dateDMY = date.split("-").reverse().join("-"); // Y-m-d → d-m-Y

                    validSlots.forEach((slot, i) => {
                        html += `
                            <div class="col-md-4 mb-2 slot-item">
                                <div class="form-check">
                                    <input class="form-check-input slot-checkbox"
                                           type="checkbox"
                                           value="${slot.time}"
                                           id="slot-${date}-${i}">
                                    <label class="form-check-label" for="slot-${date}-${i}">
                                        ${slot.time}
                                    </label>
                                </div>
                            </div>`;
                    });

                    $("#slotList").html(html);
                    $("#currentDateLabel").text(dateDMY);

                    // Restore already selected slots for that date
                    if (dateWiseSlots[date]) {
                        dateWiseSlots[date].forEach(s => {
                            $(`.slot-checkbox[value="${s}"]`).prop("checked", true);
                        });
                    }

                    // Update Select All checkbox state
                    updateSelectAllCheckbox();

                    // Auto-open slot modal
                    $("#slotModal").modal("show");

                    // Confirm slots
                    $("#confirmslot").off("click").on("click", function() {

                        let selected = [];
                        $(".slot-checkbox:checked").each(function() {
                            selected.push($(this).val());
                        });

                        if (selected.length === 0) {
                            alert("Please select at least one slot!");
                            return;
                        }

                        dateWiseSlots[date] = selected;

                        console.log('=== SLOTS CONFIRMED ===');
                        console.log('Date:', date);
                        console.log('Selected slots:', selected);
                        console.log('Updated dateWiseSlots:', dateWiseSlots);

                        updateDateSlotPreview();

                        console.log('After updateDateSlotPreview - date_slot_map value:', $(
                            '#date_slot_map').val());

                        $("#slotModal").modal("hide");
                    });
                });
            }

            // Slot search functionality
            $('#slotSearch').on('keyup', function() {
                const search = $(this).val().toLowerCase();
                $('.slot-item').each(function() {
                    const name = $(this).find('.form-check-label').text().toLowerCase();
                    $(this).toggle(name.includes(search));
                });
            });

            // Select All Slots checkbox
            $('#selectAllSlots').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.slot-checkbox:visible').prop('checked', isChecked);
            });

            // Update Select All checkbox when individual checkboxes change
            $(document).on('change', '.slot-checkbox', function() {
                updateSelectAllCheckbox();
            });

            function updateSelectAllCheckbox() {
                const total = $('.slot-checkbox:visible').length;
                const checked = $('.slot-checkbox:visible:checked').length;
                $('#selectAllSlots').prop('checked', total > 0 && total === checked);
            }

            // ✅ Render existing date-slot badges on page load for edit mode
            console.log('Checking dateWiseSlots for preview:', dateWiseSlots);
            console.log('Keys count:', Object.keys(dateWiseSlots).length);
            if (Object.keys(dateWiseSlots).length > 0) {
                console.log('Calling updateDateSlotPreview...');
                updateDateSlotPreview();
            } else {
                console.log('dateWiseSlots is empty, skipping preview');
            }

            const calendar = flatpickr(input, {
                mode: "multiple",
                dateFormat: "d-m-Y",
                minDate: "today",

                // Highlight saved dates immediately on Edit page
                onReady: function(selectedDates, dateStr, instance) {
                    if (preselectedDates.length > 0) {
                        instance.setDate(preselectedDates, false, "Y-m-d");
                    }
                    input.value = ""; // always empty
                },

                // Keep input empty always
                onOpen: function() {
                    input.value = "";
                },

                onChange: function(selectedDates, dateStr, instance) {
                    console.log('Flatpickr onChange triggered', selectedDates);

                    input.value = "";

                    // Latest clicked date
                    let clickedDate = selectedDates[selectedDates.length - 1];
                    console.log('Clicked date:', clickedDate);

                    let dateYMD = instance.formatDate(clickedDate, "Y-m-d");
                    let dateDMY = instance.formatDate(clickedDate, "d-m-Y");
                    console.log('Date formatted - YMD:', dateYMD, 'DMY:', dateDMY);

                    // Build selected date list (badges)
                    const formatted = selectedDates.map(d =>
                        instance.formatDate(d, "d-m-Y")
                    );

                    hiddenInput.value = formatted.join(',');

                    // Auto open slot selection for this date
                    console.log('About to call fetchSlotsForDate');
                    fetchSlotsForDate(dateYMD);
                }
            });
        });

        // Form submission debug
        $('#ptmForm').on('submit', function(e) {
            console.log('=== FORM SUBMISSION DEBUG ===');
            console.log('dateWiseSlots object:', dateWiseSlots);
            console.log('date_slot_map hidden input value:', $('#date_slot_map').val());
            console.log('selected_dates value:', $('#selected_dates').val());

            let mapValue = $('#date_slot_map').val();
            if (mapValue) {
                try {
                    let parsed = JSON.parse(mapValue);
                    console.log('Parsed date_slot_map:', parsed);
                    console.log('Number of dates with slots:', Object.keys(parsed).length);
                } catch (e) {
                    console.error('Error parsing date_slot_map:', e);
                }
            } else {
                console.warn('⚠️ date_slot_map is EMPTY!');
            }
        });
    </script>
@stop
