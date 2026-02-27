@extends('layout.master')
@section('title', 'Store PTM')
@section('parentPageTitle', 'PTM')

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
        /* Theme labels inside .row to use accent color in theme mode */
        body[class^="theme-"] .row label {
            color: var(--sd-accent, #5f77ff) !important;
        }

        #selected_date::placeholder {
            color: #e29c33 !important;
            opacity: 1;
            /* ensures exact color */
        }


            /* Ensure 'Select Rooms' button always default (not themed) */
            body[class^="theme-"] .btn.btn-outline-success[data-target="#roomsModal"] {
                background: transparent !important;
                color: #28a745 !important;
                border-color: #28a745 !important;
                box-shadow: none !important;
            }
            body[class^="theme-"] .btn.btn-outline-success[data-target="#roomsModal"]:hover,
            body[class^="theme-"] .btn.btn-outline-success[data-target="#roomsModal"]:focus {
                background: #28a745 !important;
                color: #fff !important;
                border-color: #28a745 !important;
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

        /* Small margin for date-slot badges */
        #dateSlotPreview .badge {
            margin: 2px 2px;
        }

        /* Dynamic UI for selectors */
        .modal.modern-modal .modal-content {
            border: none;
            border-radius: 14px;
            background: linear-gradient(180deg, #f7f9fc 0%, #ffffff 35%, #f6f8fb 100%);
            box-shadow: 0 18px 45px rgba(24, 36, 65, 0.16);
        }

        .modal.modern-modal .modal-header {
            border: none;
            background: linear-gradient(90deg, #2f7cf6, #6bc2ff);
            color: #fff;
            padding: 14px 18px;
        }

        .modal.modern-modal .modal-header h5 {
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .modal.modern-modal .modal-body {
            background: transparent;
            padding: 16px 18px 6px;
        }

        .selector-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .selector-card {
            background: linear-gradient(180deg, #f9fbff 0%, #f4f7fb 100%);
            border: 1px solid #e4eaf5;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.18s ease;
            box-shadow: 0 6px 16px rgba(31, 49, 78, 0.08);
            cursor: pointer;
        }

        .selector-card:hover {
            border-color: #5a9bff;
            box-shadow: 0 10px 22px rgba(47, 124, 246, 0.14);
            transform: translateY(-1px);
        }

        .selector-card.selected {
            border-color: #2f7cf6;
            background: linear-gradient(180deg, #f0f6ff 0%, #e7f1ff 100%);
            box-shadow: 0 12px 26px rgba(47, 124, 246, 0.2);
        }

        .selector-card .form-check-label {
            margin-left: 10px;
            font-weight: 600;
            color: #122033;
            width: 100%;
        }

        .selector-card .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0;
        }

        .selector-card .avatar-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2f7cf6, #56c4ff);
            box-shadow: 0 0 0 6px rgba(47, 124, 246, 0.08);
        }

        /* Slot modal tweaks */
        .slot-card .form-check-label {
            margin-left: 12px;
        }

        .select-all-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(90deg, #eef3ff, #e3edff);
            border: 1px solid #d6e3ff;
            border-radius: 999px;
            padding: 6px 12px;
            box-shadow: 0 6px 16px rgba(47, 124, 246, 0.12);
        }

        .select-all-toggle .toggle-checkbox {
            display: none;
        }

        .select-all-toggle .toggle-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            position: relative;
            padding-left: 34px;
            font-weight: 600;
            color: #18406f;
        }

        .select-all-toggle .toggle-label::before {
            content: '';
            position: absolute;
            left: 0;
            width: 28px;
            height: 16px;
            border-radius: 999px;
            background: #cfd9eb;
            transition: all 0.18s ease;
        }

        .select-all-toggle .toggle-label::after {
            content: '';
            position: absolute;
            left: 2px;
            top: 2px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            transition: all 0.18s ease;
        }

        .select-all-toggle .toggle-checkbox:checked + .toggle-label::before {
            background: linear-gradient(135deg, #2f7cf6, #5ec4ff);
        }

        .select-all-toggle .toggle-checkbox:checked + .toggle-label::after {
            transform: translateX(12px);
        }

        .modal .modal-header .search-pill,
        .modal .modal-header input.search-pill {
            border-radius: 999px;
            border: 1px solid #d6deeb;
            background-color: #f9fbff;
            padding: 8px 14px 8px 14px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .modal .modal-header .search-pill:focus,
        .modal .modal-header input.search-pill:focus {
            border-color: #4b8df8;
            box-shadow: 0 0 0 3px rgba(75, 141, 248, 0.15);
        }

        .select-section .btn {
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        /* Remove all custom .btn-outline-success styling so it always uses Bootstrap default */

        /* CTA buttons for modal footers */
        .btn-cta-primary {
            background: linear-gradient(90deg, #2f7cf6, #5ec4ff);
            border: none;
            color: #fff;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
            box-shadow: 0 10px 22px rgba(47, 124, 246, 0.2);
            transition: all 0.18s ease;
        }

        .btn-cta-primary:hover {
            box-shadow: 0 12px 26px rgba(47, 124, 246, 0.26);
            transform: translateY(-1px);
        }

        .btn-ghost-cancel {
            background: #f7f9fc;
            border: 1px solid #dfe7f3;
            color: #1f2d3d;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 600;
            box-shadow: 0 6px 14px rgba(24, 36, 65, 0.08);
            transition: all 0.18s ease;
        }

        .btn-ghost-cancel:hover {
            background: #eef3fb;
            border-color: #cfd9eb;
            transform: translateY(-1px);
        }

        /* Themed modals: Rooms (green), Children (blue), Staff (brown), Slots (purple) */
        /* Header */
        .modal.modern-modal.theme-rooms .modal-header { background: linear-gradient(90deg, #16a34a, #22c55e); }
        .modal.modern-modal.theme-children .modal-header { background: linear-gradient(90deg, #0ea5e9, #2563eb); }
        .modal.modern-modal.theme-staff .modal-header { background: linear-gradient(90deg, #7e2c16, #975543); }
        .modal.modern-modal.theme-slots .modal-header { background: linear-gradient(90deg, #62b7e9, #62b7e9); }

        /* Selector card accents */
        .modal.modern-modal.theme-rooms .selector-card { border-color: #d1fae5; background: linear-gradient(180deg, #f0fdf4, #ecfdf5); }
        .modal.modern-modal.theme-rooms .selector-card.selected { border-color: #22c55e; background: linear-gradient(180deg, #dcfce7, #f0fdf4); box-shadow: 0 12px 26px rgba(34,197,94,.25); }
        .modal.modern-modal.theme-rooms .selector-card:hover { border-color: #22c55e; }
        .modal.modern-modal.theme-rooms .avatar-dot { background: linear-gradient(135deg, #16a34a, #22c55e); box-shadow: 0 0 0 6px rgba(34,197,94,.10); }

        .modal.modern-modal.theme-children .selector-card { border-color: #dbeafe; background: linear-gradient(180deg, #eff6ff, #e0f2fe); }
        .modal.modern-modal.theme-children .selector-card.selected { border-color: #2563eb; background: linear-gradient(180deg, #dbeafe, #e0f2fe); box-shadow: 0 12px 26px rgba(37,99,235,.25); }
        .modal.modern-modal.theme-children .selector-card:hover { border-color: #2563eb; }
        .modal.modern-modal.theme-children .avatar-dot { background: linear-gradient(135deg, #0ea5e9, #2563eb); box-shadow: 0 0 0 6px rgba(14,165,233,.10); }

        .modal.modern-modal.theme-staff .selector-card { border-color: #f1d7cf; background: linear-gradient(180deg, #fff7f5, #fdf4f1); }
        .modal.modern-modal.theme-staff .selector-card.selected { border-color: #975543; background: linear-gradient(180deg, #fde8e2, #fff7f5); box-shadow: 0 12px 26px rgba(151,85,67,.25); }
        .modal.modern-modal.theme-staff .selector-card:hover { border-color: #975543; }
        .modal.modern-modal.theme-staff .avatar-dot { background: linear-gradient(135deg, #7e2c16, #975543); box-shadow: 0 0 0 6px rgba(151,85,67,.10); }

        .modal.modern-modal.theme-slots .selector-card { border-color: #eadcff; background: linear-gradient(180deg, #faf5ff, #f3e8ff); }
        .modal.modern-modal.theme-slots .selector-card.selected { border-color: #62b7e9; background: linear-gradient(180deg, #ede9fe, #faf5ff); box-shadow: 0 12px 26px rgba(124,58,237,.25); }
        .modal.modern-modal.theme-slots .selector-card:hover { border-color: #62b7e9; }
        .modal.modern-modal.theme-slots .avatar-dot { background: linear-gradient(135deg, #62b7e9, #62b7e9); box-shadow: 0 0 0 6px rgba(124,58,237,.10); }

        /* Select-all toggles */
        .modal.modern-modal.theme-rooms .select-all-toggle { background: linear-gradient(90deg, #ecfdf5, #dcfce7); border-color: #bbf7d0; }
        .modal.modern-modal.theme-rooms .select-all-toggle .toggle-label { color: #14532d; }
        .modal.modern-modal.theme-rooms .select-all-toggle .toggle-checkbox:checked + .toggle-label::before { background: linear-gradient(135deg, #16a34a, #22c55e); }

        .modal.modern-modal.theme-children .select-all-toggle { background: linear-gradient(90deg, #e0f2fe, #dbeafe); border-color: #bfdbfe; }
        .modal.modern-modal.theme-children .select-all-toggle .toggle-label { color: #1e3a8a; }
        .modal.modern-modal.theme-children .select-all-toggle .toggle-checkbox:checked + .toggle-label::before { background: linear-gradient(135deg, #0ea5e9, #2563eb); }

        .modal.modern-modal.theme-staff .select-all-toggle { background: linear-gradient(90deg, #fff1eb, #fde7e1); border-color: #f1d7cf; }
        .modal.modern-modal.theme-staff .select-all-toggle .toggle-label { color: #5b2b1e; }
        .modal.modern-modal.theme-staff .select-all-toggle .toggle-checkbox:checked + .toggle-label::before { background: linear-gradient(135deg, #7e2c16, #975543); }

        .modal.modern-modal.theme-slots .select-all-toggle { background: linear-gradient(90deg, #f3e8ff, #ede9fe); border-color: #e9d5ff; }
        .modal.modern-modal.theme-slots .select-all-toggle .toggle-label { color: #62b7e9; }
        .modal.modern-modal.theme-slots .select-all-toggle .toggle-checkbox:checked + .toggle-label::before { background: linear-gradient(135deg, #62b7e9, #62b7e9); }

        /* Footer buttons */
        .modal.modern-modal.theme-rooms .btn-cta-primary { background: linear-gradient(90deg, #16a34a, #22c55e); box-shadow: 0 10px 22px rgba(34,197,94,.25); }
        .modal.modern-modal.theme-children .btn-cta-primary { background: linear-gradient(90deg, #0ea5e9, #2563eb); box-shadow: 0 10px 22px rgba(37,99,235,.25); }
        .modal.modern-modal.theme-staff .btn-cta-primary { background: linear-gradient(90deg, #7e2c16, #975543); box-shadow: 0 10px 22px rgba(151,85,67,.25); }
        .modal.modern-modal.theme-slots .btn-cta-primary { background: linear-gradient(90deg, #62b7e9, #62b7e9); box-shadow: 0 10px 22px rgba(124,58,237,.25); }

        .modal.modern-modal.theme-rooms .btn-ghost-cancel { border-color: #bbf7d0; }
        .modal.modern-modal.theme-children .btn-ghost-cancel { border-color: #bfdbfe; }
        .modal.modern-modal.theme-staff .btn-ghost-cancel { border-color: #f1d7cf; }
        .modal.modern-modal.theme-slots .btn-ghost-cancel { border-color: #e9d5ff; }

        /* Checkbox accent colors per theme */
        .modal.modern-modal.theme-rooms .form-check-input:checked { background-color: #22c55e; border-color: #22c55e; }
        .modal.modern-modal.theme-children .form-check-input:checked { background-color: #2563eb; border-color: #2563eb; }
        .modal.modern-modal.theme-staff .form-check-input:checked { background-color: #975543; border-color: #975543; }
        .modal.modern-modal.theme-slots .form-check-input:checked { background-color: #a855f7; border-color: #62b7e9; }
        /* Ensure native checkmark accent matches the modal theme */
        .modal.modern-modal.theme-rooms .form-check-input { accent-color: #22c55e; }
        .modal.modern-modal.theme-children .form-check-input { accent-color: #2563eb; }
        .modal.modern-modal.theme-staff .form-check-input { accent-color: #975543; }
        .modal.modern-modal.theme-slots .form-check-input { accent-color: #62b7e9; }

        /* Count badges themed per modal */
        .count-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
            white-space: nowrap;
        }
        .modal.modern-modal.theme-rooms .count-badge { background: linear-gradient(90deg, #dcfce7, #bbf7d0); border: 1px solid #86efac; color: #14532d; }
        .modal.modern-modal.theme-children .count-badge { background: linear-gradient(90deg, #dbeafe, #bfdbfe); border: 1px solid #93c5fd; color: #1e3a8a; }
        .modal.modern-modal.theme-staff .count-badge { background: linear-gradient(90deg, #fde8e2, #f1d7cf); border: 1px solid #deb8aa; color: #5b2b1e; }
        .modal.modern-modal.theme-slots .count-badge { background: linear-gradient(90deg, #ede9fe, #e9d5ff); border: 1px solid #d8b4fe; color: #62b7e9; }

        /* Empty state block for filtered lists */
        .empty-state {
            text-align: center;
            padding: 20px 12px;
            background: #f9fafb;
            border: 1px dashed #d6dde9;
            border-radius: 12px;
            color: #4b5563;
            font-weight: 600;
        }
        .empty-state small {
            display: block;
            color: #6b7280;
            font-weight: 500;
            margin-top: 4px;
        }
    </style>
      <style>
        .publish-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 10, 20, 0.55);
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease-in-out;
        }
        .publish-overlay.show {
            opacity: 1;
            pointer-events: all;
        }
        .publish-progress {
            text-align: center;
            color: #fff;
            font-weight: 700;
        }
        .publish-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(#28c76f 0deg, #ffffff33 0deg);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 10px;
        }
        .publish-percent { font-size: 1.1rem; }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
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
    <!-- AJAX inline errors (hidden by default) -->
    <div id="ajaxErrors" class="alert alert-danger alert-dismissible d-none shadow-sm mb-4" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul id="ajaxErrorsList" class="mb-0 pl-3"></ul>
    </div>
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
                                <div id="dateInlineError" class="alert alert-danger mt-2 d-none" style="padding: 8px 12px; font-size: 13px; animation: shake 0.5s;">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span id="dateInlineErrorText"></span>
                                </div>

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
    <div class="modal modern-modal theme-rooms" id="roomsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Rooms</h5>
                    <input type="text" id="roomSearch" class="form-control ml-3 search-pill" placeholder="Search rooms..."
                        style="max-width: 250px;">
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:550px; overflow-y:auto;">
                    <div id="roomsList" class="selector-grid"></div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <span id="roomsCount" class="count-badge">0 selected</span>
                    <div class="d-flex align-items-center">
                        <div class="select-all-toggle mb-0 mr-3">
                            <input class="toggle-checkbox" type="checkbox" id="selectAllRooms">
                            <label class="toggle-label mb-0" for="selectAllRooms">Select All</label>
                        </div>
                        <button type="button" id="confirmRooms" class="btn btn-cta-primary">
                            <i class="fas fa-check mr-1"></i> Confirm
                        </button>
                        <button type="button" class="btn btn-ghost-cancel ml-2" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <!-- Staff Modal -->
    <div class="modal modern-modal theme-staff" id="staffModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Staff</h5>
                    <div class="d-flex align-items-center">
                        <input type="text" id="staffSearch" class="form-control search-pill" placeholder="Search staff..."
                            style="max-width: 250px;">
                        <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:550px; overflow-y:auto;">
                    <div id="staffInlineError" class="text-danger small mb-2 d-none"></div>
                    <div id="staffList" class="selector-grid"></div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <span id="staffCount" class="count-badge">0 selected</span>
                    <div class="d-flex align-items-center">
                        <div class="select-all-toggle mb-0 mr-3">
                            <input class="toggle-checkbox" type="checkbox" id="selectAllStaff">
                            <label class="toggle-label mb-0" for="selectAllStaff">Select All</label>
                        </div>
                        <button type="button" id="confirmStaff" class="btn btn-cta-primary">
                            <i class="fas fa-check mr-1"></i> Confirm
                        </button>
                        <button type="button" class="btn btn-ghost-cancel ml-2" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Children Modal -->
    <div class="modal modern-modal theme-children" id="childrenModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Children</h5>
                    <div class="d-flex align-items-center">
                        <input type="text" id="childSearch" class="form-control search-pill" placeholder="Search children..."
                            style="max-width: 250px;">
                        <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:550px; overflow-y:auto;">
                    <div id="childrenInlineError" class="text-danger small mb-2 d-none"></div>
                    <div id="childrenList" class="selector-grid"></div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <span id="childrenCount" class="count-badge">0 selected</span>
                    <div class="d-flex align-items-center">
                        <div class="select-all-toggle mb-0 mr-3">
                            <input class="toggle-checkbox" type="checkbox" id="selectAllChildren">
                            <label class="toggle-label mb-0" for="selectAllChildren">Select All</label>
                        </div>
                        <button type="button" id="confirmChildren" class="btn btn-cta-primary">
                            <i class="fas fa-check mr-1"></i> Confirm
                        </button>
                        <button type="button" class="btn btn-ghost-cancel ml-2" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </div>
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
                    <p> Are you sure you want to <strong>Publish</strong> this PTM?
                        Once published, it will be visible to all linked users. And it cannot be reverted back for edit.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmPublishBtn" class="btn btn-success">Yes, Publish</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Slot Selection Modal -->
    <div class="modal modern-modal theme-slots" id="slotModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Select Slots for <span id="currentDateLabel"></span></h5>
                    <div class="d-flex align-items-center">
                        <input type="text" id="slotSearch" class="form-control search-pill" placeholder="Search Slot..."
                            style="max-width: 250px;">
                        <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body" style="max-height:550px; overflow-y:auto;">
                    <div id="slotInlineError" class="text-danger small mb-2 d-none"></div>
                    
                    <div id="slotLoading" style="display:none; min-height:120px; align-items:center; justify-content:center; text-align:center;">
                        <div style="width:36px; height:36px; border:4px solid #eef2ff; border-top:4px solid #4a6cf7; border-radius:50%; margin:0 auto; animation: slotSpin 0.8s linear infinite;"></div>
                        
                    </div>
                    <div id="slotList" class="selector-grid"></div>
                    <div style="margin-top:12px;padding:8px 12px;background:#f6f5ff;border:1px solid #e5ddff;border-radius:12px;">
                        <!-- Info message -->
                        <div style="font-size:11px;color:#6b5b95;font-weight:500;margin-bottom:8px;padding-bottom:8px;border-bottom:1px solid #e5ddff;">
                            <i class="fas fa-info-circle" style="color:#a78bda;margin-right:4px;"></i>
                            Slots can be created between <strong>7:00 AM - 7:00 PM</strong>
                        </div>
                        <!-- Time range inputs -->
                        <div class="d-flex align-items-center" style="gap:10px; flex-wrap:nowrap;">
                            <!-- FROM Section -->
                            <div style="display:flex; gap:6px; align-items:center;">
                                <div style="text-align:center;">
                                    <label style="font-size:11px;font-weight:600;color:#4c1d95;display:block;margin-bottom:2px;">Hour</label>
                                    <input type="number" id="slotHour" class="form-control" min="1" max="12" placeholder="7" style="width:60px;padding:4px 6px;font-size:13px;">
                                </div>
                                <div style="text-align:center;">
                                    <label style="font-size:11px;font-weight:600;color:#4c1d95;display:block;margin-bottom:2px;">Min</label>
                                    <select id="slotMinutes" class="form-control" style="width:60px;padding:4px 6px;font-size:13px;">
                                        <option value="00">00</option>
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                    </select>
                                </div>
                                <div style="text-align:center;">
                                    <label style="font-size:11px;font-weight:600;color:#4c1d95;display:block;margin-bottom:2px;">Period</label>
                                    <div class="d-flex" style="gap:3px;">
                                        <button type="button" class="slot-ampm-btn" data-period="AM" style="padding:4px 8px;border:1px solid #d8b4fe;background:#fff;color:#4c1d95;border-radius:4px;font-weight:600;cursor:pointer;font-size:11px;width:40px;">AM</button>
                                        <button type="button" class="slot-ampm-btn" data-period="PM" style="padding:4px 8px;border:1px solid #d8b4fe;background:#fff;color:#4c1d95;border-radius:4px;font-weight:600;cursor:pointer;font-size:11px;width:40px;">PM</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Separator -->
                            <div style="font-size:16px;font-weight:700;color:#62b7e9;">-</div>

                            <!-- TO Section -->
                            <div style="display:flex; gap:6px; align-items:center;">
                                <div style="text-align:center;">
                                    <label style="font-size:11px;font-weight:600;color:#4c1d95;display:block;margin-bottom:2px;">Hour</label>
                                    <input type="number" id="slotHourEnd" class="form-control" min="1" max="12" placeholder="10" style="width:60px;padding:4px 6px;font-size:13px;">
                                </div>
                                <div style="text-align:center;">
                                    <label style="font-size:11px;font-weight:600;color:#4c1d95;display:block;margin-bottom:2px;">Min</label>
                                    <select id="slotMinutesEnd" class="form-control" style="width:60px;padding:4px 6px;font-size:13px;">
                                        <option value="00">00</option>
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="45">45</option>
                                    </select>
                                </div>
                                <div style="text-align:center;">
                                    <label style="font-size:11px;font-weight:600;color:#4c1d95;display:block;margin-bottom:2px;">Period</label>
                                    <div class="d-flex" style="gap:3px;">
                                        <button type="button" class="slot-ampm-btn-end" data-period="AM" style="padding:4px 8px;border:1px solid #d8b4fe;background:#fff;color:#4c1d95;border-radius:4px;font-weight:600;cursor:pointer;font-size:11px;width:40px;">AM</button>
                                        <button type="button" class="slot-ampm-btn-end" data-period="PM" style="padding:4px 8px;border:1px solid #d8b4fe;background:#fff;color:#4c1d95;border-radius:4px;font-weight:600;cursor:pointer;font-size:11px;width:40px;">PM</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Button -->
                            <button type="button" id="addCustomSlot" class="btn btn-cta-primary btn-sm" style="padding:7px 14px;margin-left:auto;white-space:nowrap;font-size:12px;">Add slots</button>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <span id="slotsCount" class="count-badge">0 selected</span>
                    <div class="d-flex align-items-center">
                        <div class="select-all-toggle mb-0 mr-3">
                            <input class="toggle-checkbox" type="checkbox" id="selectAllSlots">
                            <label class="toggle-label mb-0" for="selectAllSlots">Select All</label>
                        </div>
                        <button type="button" id="confirmslot" class="btn btn-cta-primary">
                            <i class="fas fa-check mr-1"></i> Confirm
                        </button>
                        <button type="button" class="btn btn-ghost-cancel ml-2" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    
    <!-- Publish Progress Overlay -->
    <div class="publish-overlay" id="publishOverlay" aria-hidden="true">
        <div class="publish-progress">
            <div class="publish-circle" id="publishCircle">
                <div class="publish-percent" id="publishPercent">0%</div>
            </div>
            <div>Publishing... Please wait</div>
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
        // Spinner keyframes for slot loader
        const styleEl = document.createElement('style');
        styleEl.innerHTML = '@keyframes slotSpin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}';
        document.head.appendChild(styleEl);

        // Helper: clear inline validation UI
        function clearValidationErrors() {
            const container = document.getElementById('ajaxErrors');
            const list = document.getElementById('ajaxErrorsList');
            if (container) container.classList.add('d-none');
            if (list) list.innerHTML = '';

            // remove is-invalid classes and inline feedback
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback.ajax').forEach(el => el.remove());
        }

        // Helper: show field-level and form-level validation errors
        function showValidationErrors(errors) {
            const list = document.getElementById('ajaxErrorsList');
            const container = document.getElementById('ajaxErrors');
            if (!list || !container) return;

            container.classList.remove('d-none');
            list.innerHTML = '';

            // errors is an object: { field: [msg, ...], ... }
            Object.keys(errors).forEach(field => {
                const messages = errors[field];
                messages.forEach(msg => {
                    const li = document.createElement('li');
                    li.textContent = msg;
                    list.appendChild(li);
                });

                // Try to mark corresponding form control(s)
                // Handle array names too (e.g., name[])
                let selector = `[name="${field}"]`;
                let els = document.querySelectorAll(selector);
                if (els.length === 0) {
                    // fallback: try name with []
                    selector = `[name="${field}[]"]`;
                    els = document.querySelectorAll(selector);
                }

                els.forEach(el => {
                    el.classList.add('is-invalid');
                    // add small.invalid-feedback if not present
                    if (!el.nextElementSibling || !el.nextElementSibling.classList || !el.nextElementSibling.classList.contains('invalid-feedback')) {
                        const fb = document.createElement('div');
                        fb.className = 'invalid-feedback ajax';
                        fb.textContent = Array.isArray(messages) ? messages[0] : messages;
                        // if input is hidden, append feedback after its nearest visible parent
                        if (el.type === 'hidden') {
                            el.parentNode.insertBefore(fb, el.nextSibling);
                        } else {
                            el.parentNode.insertBefore(fb, el.nextSibling);
                        }
                    }
                });
            });
        }

        function showGenericError(message){
            const list = document.getElementById('ajaxErrorsList');
            const container = document.getElementById('ajaxErrors');
            if (!list || !container) return alert(message);
            container.classList.remove('d-none');
            list.innerHTML = '';
            const li = document.createElement('li');
            li.textContent = message;
            list.appendChild(li);
        }

        // ✅ Initialize dateWiseSlots with existing data for edit mode (before DOM ready)
        @if (isset($dateSlotMap) && !empty($dateSlotMap))
            dateWiseSlots = @json($dateSlotMap);
        @endif

        $(document).ready(function() {

            // Sync visual state of selector cards with their checkboxes
            window.syncCardSelection = function(selector) {
                $(selector).each(function() {
                    const card = $(this).closest('.selector-card');
                    if (!card.length) return;
                    card.toggleClass('selected', $(this).is(':checked'));
                });
            }

            // Allow clicking anywhere on selector cards to toggle the checkbox
            $(document).on('click', '.selector-card', function(e) {
                // Skip if actual checkbox or label was clicked
                if ($(e.target).is('input, label')) return;
                const cb = $(this).find('input[type="checkbox"]');
                cb.prop('checked', !cb.prop('checked')).trigger('change');
            });

            // Count update helpers
            window.updateRoomsCount = function() {
                $('#roomsCount').text($('.room-checkbox:checked').length + ' selected');
            }
            window.updateChildrenCount = function() {
                $('#childrenCount').text($('.child-checkbox:checked').length + ' selected');
            }
            window.updateStaffCount = function() {
                $('#staffCount').text($('.staff-checkbox:checked').length + ' selected');
            }
            window.updateSlotsCount = function() {
                $('#slotsCount').text($('.slot-checkbox:checked').length + ' selected');
            }

            // Empty state helper for filtered lists
            window.ensureEmptyState = function(listSelector, emptyId, message, subtext) {
                const $list = $(listSelector);
                if (!$list.length) return null;
                let $empty = $('#' + emptyId);
                if (!$empty.length) {
                    const sub = subtext ? `<small>${subtext}</small>` : '';
                    $list.after(`<div id="${emptyId}" class="empty-state" style="display:none;">${message}${sub}</div>`);
                    $empty = $('#' + emptyId);
                }
                return $empty;
            }

            window.updateEmptyState = function(listSelector, emptyId, message, subtext) {
                const $empty = window.ensureEmptyState(listSelector, emptyId, message, subtext);
                if (!$empty) return;
                const visible = $(listSelector).children(':visible').length;
                $empty.toggle(visible === 0);
            }

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
                            html += `<div class="selector-card room-item">
                        <span class="avatar-dot"></span>
                        <input class="form-check-input room-checkbox" type="checkbox" value="${room.id}" id="room-${room.id}" ${checked}>
                        <label class="form-check-label" for="room-${room.id}">${room.name}</label>
                    </div>`;
                        });
                        $('#roomsList').html(html);
                        syncCardSelection('.room-checkbox');
                        updateRoomsCount();
                        window.updateEmptyState('#roomsList', 'roomsEmpty', 'No rooms found', 'Try adjusting your search.');
                    }
                });
            });

            $('#roomSearch').on('keyup', function() {
                const val = $(this).val().toLowerCase();
                $('.room-item').each(function() {
                    const name = $(this).find('label').text().toLowerCase();
                    $(this).toggle(name.includes(val));
                });
                window.updateEmptyState('#roomsList', 'roomsEmpty', 'No rooms found', 'Try adjusting your search.');
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
                syncCardSelection('.room-checkbox');
                updateRoomsCount();
            });

            // When rooms are loaded, reset Select All checkbox if not all selected
            $('#roomsModal').on('shown.bs.modal', function() {
                const total = $('.room-checkbox').length;
                const checked = $('.room-checkbox:checked').length;
                $('#selectAllRooms').prop('checked', total > 0 && total === checked);
                syncCardSelection('.room-checkbox');
            });

            // Update "Select All" checkbox if user manually unchecks one
            $(document).on('change', '.room-checkbox', function() {
                const total = $('.room-checkbox').length;
                const checked = $('.room-checkbox:checked').length;
                $('#selectAllRooms').prop('checked', total > 0 && total === checked);
                syncCardSelection('.room-checkbox');
                updateRoomsCount();
            });


            // Children
            let selectedChildren = new Set($('#selected_children').val().split(',').filter(id => id));
            $('#childrenModal').on('show.bs.modal', function() {
                let selectedrooms = $('#selected_rooms').val();
                $('#childrenInlineError').addClass('d-none').text('');
                
                if (!selectedrooms) {
                    $('#childrenInlineError').removeClass('d-none').text('Please select at least one room first.');
                    $('#childrenList').empty();
                    $('#childrenCount').text('0 selected');
                    return;
                }

                $.get('{{ route('ptm.get.children') }}', {
                    rooms: selectedrooms
                }, function(response) {
                    if (response.success) {
                        $('#childrenInlineError').addClass('d-none').text('');
                        let html = '';
                        response.children.sort((a, b) => a.name.localeCompare(b.name));
                        response.children.forEach(child => {

                            const checked = selectedChildren.has(child.id.toString()) ?
                                'checked' : '';
                            html += `<div class="selector-card child-item">
                        <span class="avatar-dot"></span>
                        <input class="form-check-input child-checkbox" type="checkbox" value="${child.id}" id="child-${child.id}" ${checked}>
                        <label class="form-check-label" for="child-${child.id}">${child.name} ${child.lastname}</label>
                    </div>`;
                        });
                        $('#childrenList').html(html);
                        syncCardSelection('.child-checkbox');
                        updateChildrenCount();
                        window.updateEmptyState('#childrenList', 'childrenEmpty', 'No records found', 'Try a different name or room.');
                    }
                });
            });
            $('#childSearch').on('keyup', function() {
                const search = $(this).val().toLowerCase();
                $('.child-item').each(function() {
                    const name = $(this).find('.form-check-label').text().toLowerCase();
                    $(this).toggle(name.includes(search));
                });
                window.updateEmptyState('#childrenList', 'childrenEmpty', 'No records found', 'Try a different name or room.');
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
                syncCardSelection('.child-checkbox');
                updateChildrenCount();
            });
            // When children are loaded via AJAX, reset Select All checkbox
            $('#childrenModal').on('shown.bs.modal', function() {
                const total = $('.child-checkbox').length;
                const checked = $('.child-checkbox:checked').length;
                $('#selectAllChildren').prop('checked', total > 0 && total === checked);
                syncCardSelection('.child-checkbox');
            });
            // Update "Select All" checkbox if user manually toggles one
            $(document).on('change', '.child-checkbox', function() {
                const total = $('.child-checkbox').length;
                const checked = $('.child-checkbox:checked').length;
                $('#selectAllChildren').prop('checked', total > 0 && total === checked);
                syncCardSelection('.child-checkbox');
                updateChildrenCount();
            });


            // Staff
            let selectedStaff = new Set($('#selected_staff').val().split(',').filter(id => id));
            $('#staffModal').on('show.bs.modal', function() {
                const selectedRooms = $('#selected_rooms').val(); // get room IDs
                $('#staffInlineError').addClass('d-none').text('');
                
                if (!selectedRooms) {
                    $('#staffInlineError').removeClass('d-none').text('Please select at least one room first.');
                    $('#staffList').empty();
                    $('#staffCount').text('0 selected');
                    return;
                }

                $.get('{{ route('ptm.get-staff') }}', {
                    rooms: selectedRooms
                }, function(response) {
                    if (response.success) {
                        $('#staffInlineError').addClass('d-none').text('');
                        let html = '';
                        response.staff.sort((a, b) => a.name.localeCompare(b.name));
                        response.staff.forEach(staff => {

                            const checked = selectedStaff.has(staff.id.toString()) ?
                                'checked' : '';
                            html += `<div class="selector-card staff-item">
                    <span class="avatar-dot"></span>
                    <input class="form-check-input staff-checkbox" type="checkbox" value="${staff.id}" id="staff-${staff.id}" ${checked}>
                    <label class="form-check-label" for="staff-${staff.id}">${staff.name}</label>
                </div>`;
                        });
                        $('#staffList').html(html);
                        syncCardSelection('.staff-checkbox');
                        updateStaffCount();
                        window.updateEmptyState('#staffList', 'staffEmpty', 'No staff found', 'Try adjusting the search or rooms.');
                    }
                });
            });

            $('#staffModal').on('shown.bs.modal', function() {
                const total = $('.staff-checkbox').length;
                const checked = $('.staff-checkbox:checked').length;
                $('#selectAllStaff').prop('checked', total > 0 && total === checked);
                syncCardSelection('.staff-checkbox');
            });
            // Staff Search
            $('#staffSearch').on('keyup', function() {
                const search = $(this).val().toLowerCase();
                $('.staff-item').each(function() {
                    const name = $(this).find('.form-check-label').text().toLowerCase();
                    $(this).toggle(name.includes(search));
                });
                window.updateEmptyState('#staffList', 'staffEmpty', 'No staff found', 'Try adjusting the search or rooms.');
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
                syncCardSelection('.staff-checkbox');
                updateStaffCount();
            });

            $(document).on('change', '.staff-checkbox', function() {
                const total = $('.staff-checkbox').length;
                const checked = $('.staff-checkbox:checked').length;
                $('#selectAllStaff').prop('checked', total > 0 && total === checked);
                syncCardSelection('.staff-checkbox');
                updateStaffCount();
            });


            // Handle Publish confirmation
            $('#publishBtn').on('click', function(e) {
                e.preventDefault(); // stop immediate form submission
                $('#publishConfirmModal').modal('show'); // show confirmation modal
            });
            $('#confirmPublishBtn').on('click', function() {
                               $('<input>').attr({
                    type: 'hidden',
                    name: 'action',
                    value: 'Published'
                }).appendTo('#ptmForm');

                // Show overlay and start progress
                showPublishOverlay();

                // Close the confirmation modal immediately and remove backdrop
                $('#publishConfirmModal').modal('hide');
                $('.modal-backdrop').remove();

                // Prepare form data
                const form = document.getElementById('ptmForm');
                // Use getAttribute to avoid collision when a form control is named "action"
                const url = form.getAttribute('action') || form.action;
                const fd = new FormData(form);

   
                clearValidationErrors();

                
                fetch(url, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                }).then(async (resp) => {
                    if (resp.status >= 400 && resp.status !== 422) {
                        hidePublishOverlayImmediate();
                        showGenericError('Publish failed: server returned ' + resp.status + '.');
                        return;
                    }


                    if (resp.status === 422) {
                        let json = null;
                        try { json = await resp.json(); } catch (e) { json = null; }
                        hidePublishOverlayImmediate();
                        if (json && json.errors) {
                            showValidationErrors(json.errors);
                        } else {
                            showGenericError('Validation failed. Please check the form.');
                        }
                        return;
                    }

                    // Try JSON first for redirect info
                    let json = null;
                    try { json = await resp.json(); } catch (e) { /* not JSON */ }


                    clearValidationErrors();
                    finalizePublishOverlay();


                    const fallback = "{{ route('ptm.index') }}";
                    if (json && json.redirect) {
                        window.location = json.redirect;
                    } else if (resp.redirected) {
                        // If fetch followed redirect, go to final URL
                        window.location = resp.url;
                    } else {
                        // Fallback
                        window.location = fallback;
                    }
                }).catch(err => {
                    // Hide overlay and alert
                    alert('Publish failed. Please try again.');
                    hidePublishOverlayImmediate();
                });
            });


        });


        document.addEventListener('DOMContentLoaded', function() {

            const input = document.getElementById('selected_date');
            const hiddenInput = document.getElementById('selected_dates');
            const previewDiv = document.getElementById('selectedDatePreview');
            let pendingDate = null;

            let preselectedDates = @json($convertedDates); // Y-m-d format only
            let preselectedDisplayDates = @json($displayDates ?? []); // d-m-Y format for hidden input

            // If editing and there are already saved dates, populate the hidden input
            if (preselectedDisplayDates && preselectedDisplayDates.length > 0) {
                hiddenInput.value = preselectedDisplayDates.join(',');
            }

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
                        `<span class="badge mr-1" style="background:#62b7e9;color:#fff;border:1px solid #c084fc;">${s}</span>`
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

            // Remove a date selection if no slots are confirmed
            function removeDateSelection(dateYMD) {
                if (!calendar) return;
                calendar.selectedDates = calendar.selectedDates.filter(d => calendar.formatDate(d, "Y-m-d") !== dateYMD);
                calendar.setDate(calendar.selectedDates, false, "Y-m-d");
                const formatted = calendar.selectedDates.map(d => calendar.formatDate(d, "d-m-Y"));
                hiddenInput.value = formatted.join(',');
                if (dateWiseSlots[dateYMD]) delete dateWiseSlots[dateYMD];
                updateDateSlotPreview();
            }

            // Fetch available slots for a date and auto-open modal
            function fetchSlotsForDate(date) {
                pendingDate = date;
                let selectedRooms = $('#selected_rooms').val();

                if (!selectedRooms) {
                    // Close the calendar
                    if (calendar) calendar.close();
                    
                    // Show interactive error
                    $('#dateInlineErrorText').text('Please select rooms first.');
                    $('#dateInlineError').removeClass('d-none');
                    
                    // Auto-hide after 4 seconds
                    setTimeout(function() {
                        $('#dateInlineError').addClass('d-none');
                    }, 4000);
                    
                    removeDateSelection(date);
                    return;
                }

                // Clear any previous errors
                $('#dateInlineError').addClass('d-none');
                $('#dateInlineErrorText').text('');
                $('#slotInlineError').addClass('d-none').text('');

                // Show modal immediately with loader, then fetch
                $("#currentDateLabel").text(date.split("-").reverse().join("-"));
                $("#slotList").empty();
                $("#slotLoading").css('display','flex');
                $("#slotModal").modal("show");

                $.get('{{ route('ptm.get-slots') }}', {
                    date: date,
                    rooms: selectedRooms
                }, function(res) {
                    // Hide loader once response arrives
                    $("#slotLoading").hide();

                    if (!res.success || !res.slot || res.slot.length === 0) {
                        $('#slotInlineError').removeClass('d-none').text('No slots available for this date.');
                        $("#slotList").empty();
                        removeDateSelection(date);
                        return;
                    }

                    // Filter out empty slots
                    let validSlots = res.slot.filter(slot => slot.time && slot.time.trim() !== '');
                    

                    if (validSlots.length === 0) {
                        $('#slotInlineError').removeClass('d-none').text('No valid slots found for this date. Please create slots first.');
                        $("#slotList").empty();
                        removeDateSelection(date);
                        return;
                    }

                    // Clear error if slots loaded successfully
                    $('#slotInlineError').addClass('d-none').text('');

                    let html = "";
                    let dateDMY = date.split("-").reverse().join("-"); // Y-m-d → d-m-Y

                    validSlots.forEach((slot, i) => {
                        html += `
                            <div class="selector-card slot-card slot-item">
                                <span class="avatar-dot"></span>
                                <input class="form-check-input slot-checkbox"
                                       type="checkbox"
                                       value="${slot.time}"
                                       id="slot-${date}-${i}">
                                <label class="form-check-label" for="slot-${date}-${i}">
                                    ${slot.time}
                                </label>
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

                    // Sync card selection states and update UI
                    window.syncCardSelection('.slot-checkbox');
                    window.updateSelectAllCheckbox();
                    window.updateEmptyState('#slotList', 'slotsEmpty', 'No slots found', 'Try another Slot or Create one.');

                    // Modal already open; ensure loader hidden
                    $("#slotLoading").hide();

                    // Confirm slots
                    $("#confirmslot").off("click").on("click", function() {

                        let selected = [];
                        $(".slot-checkbox:checked").each(function() {
                            selected.push($(this).val());
                        });

                        if (selected.length === 0) {
                            $('#slotInlineError').removeClass('d-none').text('Please select at least one slot.');
                            return;
                        }

                        $('#slotInlineError').addClass('d-none').text('');
                        dateWiseSlots[date] = selected;
                        updateDateSlotPreview();
                        $("#slotModal").modal("hide");
                        pendingDate = null;
                    });
                }).fail(function() {
                    $("#slotLoading").hide();
                    $('#slotInlineError').removeClass('d-none').text('Failed to load slots. Please try again.');
                    $("#slotList").empty();
                    removeDateSelection(date);
                });
            }

            // Slot search functionality
            $('#slotSearch').on('keyup', function() {
                const search = $(this).val().toLowerCase();
                $('.slot-item').each(function() {
                    const name = $(this).find('.form-check-label').text().toLowerCase();
                    $(this).toggle(name.includes(search));
                });
                window.updateEmptyState('#slotList', 'slotsEmpty', 'No slots found', 'Try another Slot or Create One.');
            });

            // Add custom slot(s) in range
            $('#addCustomSlot').on('click', function() {
                const hourStart = parseInt($('#slotHour').val(), 10);
                const minutesStart = $('#slotMinutes').val();
                const periodStart = $('.slot-ampm-btn.active').data('period') || 'AM';

                const hourEnd = parseInt($('#slotHourEnd').val(), 10);
                const minutesEnd = $('#slotMinutesEnd').val();
                const periodEnd = $('.slot-ampm-btn-end.active').data('period') || 'AM';

                // Validate inputs
                if (isNaN(hourStart) || hourStart < 1 || hourStart > 12) {
                    alert('Please enter a valid start hour (1-12)');
                    return;
                }
                if (isNaN(hourEnd) || hourEnd < 1 || hourEnd > 12) {
                    alert('Please enter a valid end hour (1-12)');
                    return;
                }

                // Convert to 24-hour format
                let h24Start = hourStart;
                if (periodStart === 'PM' && hourStart !== 12) h24Start += 12;
                else if (periodStart === 'AM' && hourStart === 12) h24Start = 0;

                let h24End = hourEnd;
                if (periodEnd === 'PM' && hourEnd !== 12) h24End += 12;
                else if (periodEnd === 'AM' && hourEnd === 12) h24End = 0;

                // Restrict to 7 AM - 7 PM (7:00 - 19:00)
                if (h24Start < 7 || h24End > 19 || (h24End === 19 && parseInt(minutesEnd) > 0)) {
                    alert('Slots can only be created between 7:00 AM and 7:00 PM');
                    return;
                }

                // Validate end time is after start time
                if (h24End < h24Start || (h24End === h24Start && parseInt(minutesEnd) < parseInt(minutesStart))) {
                    alert('End time must be after start time');
                    return;
                }

                // Format times for display (12-hour format with AM/PM and zero-padded)
                const h12Start = hourStart;
                const h12End = hourEnd;
                const value = `${String(h12Start).padStart(2, '0')}:${minutesStart} ${periodStart} - ${String(h12End).padStart(2, '0')}:${minutesEnd} ${periodEnd}`;

                // Prevent duplicates
                const exists = $(`.slot-checkbox[value="${value}"]`).length > 0;
                if (exists) {
                    // Ensure it is selected if already exists
                    $(`.slot-checkbox[value="${value}"]`).prop('checked', true).trigger('change');
                    $('#slotHour').val('');
                    $('#slotMinutes').val('00');
                    $('#slotHourEnd').val('');
                    $('#slotMinutesEnd').val('00');
                    return;
                }

                // Create single slot card for the range
                const newId = `slot-custom-${Date.now()}`;
                const card = `
                    <div class="selector-card slot-card slot-item">
                        <span class="avatar-dot"></span>
                        <input class="form-check-input slot-checkbox" type="checkbox" value="${value}" id="${newId}" checked>
                        <label class="form-check-label" for="${newId}">${value}</label>
                    </div>`;

                $('#slotList').prepend(card);

                // Update UI
                window.syncCardSelection('.slot-checkbox');
                window.updateSelectAllCheckbox();
                window.updateSlotsCount();
                window.updateEmptyState('#slotList', 'slotsEmpty', 'No slots found', 'Try another Slot or Create One.');

                // Reset inputs
                $('#slotHour').val('');
                $('#slotMinutes').val('00');
                $('#slotHourEnd').val('');
                $('#slotMinutesEnd').val('00');
                $('.slot-ampm-btn').removeClass('active').css('background', '#fff').css('color', '#4c1d95');
                $('.slot-ampm-btn-end').removeClass('active').css('background', '#fff').css('color', '#4c1d95');
                $('.slot-ampm-btn[data-period="AM"]').addClass('active').css('background', '#a855f7').css('color', '#fff');
                $('.slot-ampm-btn-end[data-period="AM"]').addClass('active').css('background', '#a855f7').css('color', '#fff');
                $('#slotHour').focus();
            });
            $(document).on('click', '.slot-ampm-btn', function() {
                $('.slot-ampm-btn').removeClass('active').css('background', '#fff').css('color', '#4c1d95');
                $(this).addClass('active').css('background', '#a855f7').css('color', '#fff');
            });

            // Handle end AM/PM button clicks
            $(document).on('click', '.slot-ampm-btn-end', function() {
                $('.slot-ampm-btn-end').removeClass('active').css('background', '#fff').css('color', '#4c1d95');
                $(this).addClass('active').css('background', '#a855f7').css('color', '#fff');
            });

            // Set default AM on load for both start and end
            $('.slot-ampm-btn[data-period="AM"]').addClass('active').css('background', '#a855f7').css('color', '#fff');
            $('.slot-ampm-btn-end[data-period="AM"]').addClass('active').css('background', '#a855f7').css('color', '#fff');

            // Auto-move to minutes when hour is entered
            $('#slotHour').on('keyup', function(e) {
                const val = $(this).val();
                if (val && (e.key === 'Tab' || e.key === 'Enter' || val.length >= 2)) {
                    $('#slotMinutes').focus();
                }
            });

            // Auto-move to end hour when start time minutes are selected
            $('#slotMinutes').on('change', function() {
                $('#slotHourEnd').focus();
            });

            // Auto-move to end minutes when end hour is entered
            $('#slotHourEnd').on('keyup', function(e) {
                const val = $(this).val();
                if (val && (e.key === 'Tab' || e.key === 'Enter' || val.length >= 2)) {
                    $('#slotMinutesEnd').focus();
                }
            });

            // Select All Slots checkbox
            $('#selectAllSlots').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.slot-checkbox:visible').prop('checked', isChecked);
                window.syncCardSelection('.slot-checkbox');
                window.updateSlotsCount();
            });

            // Update Select All checkbox when individual checkboxes change
            $(document).on('change', '.slot-checkbox', function() {
                window.updateSelectAllCheckbox();
                window.syncCardSelection('.slot-checkbox');
                window.updateSlotsCount();
            });

            window.updateSelectAllCheckbox = function() {
                const total = $('.slot-checkbox:visible').length;
                const checked = $('.slot-checkbox:visible:checked').length;
                $('#selectAllSlots').prop('checked', total > 0 && total === checked);
                window.syncCardSelection('.slot-checkbox');
                window.updateSlotsCount();
            }

            // If modal closes without confirming slots, remove the pending date selection
            $('#slotModal').on('hidden.bs.modal', function() {
                if (pendingDate) {
                    const hasSlots = Array.isArray(dateWiseSlots[pendingDate]) && dateWiseSlots[pendingDate].length > 0;
                    if (!hasSlots) {
                        removeDateSelection(pendingDate);
                    }
                    pendingDate = null;
                }
                $("#slotLoading").hide();
                $("#slotList").empty();
            });

            // ✅ Render existing date-slot badges on page load for edit mode
            if (Object.keys(dateWiseSlots).length > 0) {
                updateDateSlotPreview();
            }

            // Track previously selected dates to detect add/remove actions
            let prevSelectedYMD = new Set(preselectedDates || []);

            let calendar = flatpickr(input, {
                mode: "multiple",
                dateFormat: "d-m-Y",
                minDate: "today",

                // Highlight saved dates immediately on Edit page
                onReady: function(selectedDates, dateStr, instance) {
                    if (preselectedDates.length > 0) {
                        instance.setDate(preselectedDates, false, "Y-m-d");
                    }
                    input.value = ""; // always empty

                    // Initialize prev set from actual selectedDates rendered by flatpickr
                    prevSelectedYMD = new Set(
                        instance.selectedDates.map(d => instance.formatDate(d, "Y-m-d"))
                    );
                },

                // Keep input empty always
                onOpen: function() {
                    input.value = "";
                },

                onChange: function(selectedDates, dateStr, instance) {
                    // Always keep input visually empty
                    input.value = "";

                    // Current selection set in Y-m-d
                    const curSelectedYMD = new Set(
                        selectedDates.map(d => instance.formatDate(d, "Y-m-d"))
                    );

                    // Compute added and removed dates
                    const added = [...curSelectedYMD].filter(d => !prevSelectedYMD.has(d));
                    const removed = [...prevSelectedYMD].filter(d => !curSelectedYMD.has(d));

                    // Update hidden input with current selected dates in d-m-Y format
                    const formattedDMY = selectedDates.map(d => instance.formatDate(d, "d-m-Y"));
                    hiddenInput.value = formattedDMY.join(',');

                    // Handle removed dates: delete their slots and refresh preview
                    if (removed.length > 0) {
                        removed.forEach(d => {
                            if (dateWiseSlots[d]) delete dateWiseSlots[d];
                        });
                        updateDateSlotPreview();
                    }

                    // Handle newly added date: open slot picker for the first added date
                    if (added.length > 0) {
                        fetchSlotsForDate(added[0]);
                    }

                    // Persist this selection as previous for next change
                    prevSelectedYMD = curSelectedYMD;
                }
            });
        });

        
    </script>
    <script>
        
        function showPublishOverlay() {
            const overlay = document.getElementById('publishOverlay');
            const percentText = document.getElementById('publishPercent');
            const circle = document.getElementById('publishCircle');

            overlay.classList.add('show');

            let pct = 10; 
            percentText.textContent = pct + '%';
            circle.style.background = `conic-gradient(#28c76f ${pct * 3.6}deg, #ffffff33 0deg)`;

            void overlay.offsetWidth;


            const tick = setInterval(() => {
                pct += 6; 
                if (pct >= 98) pct = 98; 
                percentText.textContent = pct + '%';
                circle.style.background = `conic-gradient(#28c76f ${pct * 3.6}deg, #ffffff33 0deg)`;
            }, 1000);

            window.addEventListener('beforeunload', function cleanup() {
                clearInterval(tick);
                window.removeEventListener('beforeunload', cleanup);
            });
        }

        function finalizePublishOverlay() {
            const percentText = document.getElementById('publishPercent');
            const circle = document.getElementById('publishCircle');
            percentText.textContent = '100%';
            circle.style.background = `conic-gradient(#28c76f 360deg, #ffffff33 0deg)`;
        }

        function hidePublishOverlayImmediate() {
            const overlay = document.getElementById('publishOverlay');
            overlay.classList.remove('show');
        }
    </script>
@stop
