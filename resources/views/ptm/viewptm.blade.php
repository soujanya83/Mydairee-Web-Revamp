@extends('layout.master')

@section('title', 'View PTM')

@section('content')
<style>
    /* === Base Theme === */
    body {
        background: linear-gradient(135deg, #f7fafc, #eef3f9);
        font-family: 'Inter', 'Poppins', sans-serif;
        color: #333;
    }

    .ptm-wrapper {
        padding: 3rem 0;
        min-height: calc(100vh - 100px);
    }

    /* === Elegant Card === */
    .ptm-view-card {
        margin-top: -50px;
        border: 1px solid #e8ecf1;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        animation: fadeIn 0.5s ease-out;
    }

    .ptm-view-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    /* === Header === */
    .ptm-header {
        background: linear-gradient(90deg, var(--sd-accent, #4a6cf7), var(--sd-accent-soft, #7699f8));
        color: #fff;
        padding: 1.2rem 1.5rem;
        border-radius: 16px 16px 0 0;
    }

    .ptm-header h3 {
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
    }

    /* === Body === */
    .ptm-body {
        padding: 2rem 2rem 1rem 2rem;
    }

    .ptm-body h6 {
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .ptm-section {
        background: #fafbff;
        border: 1px solid #edf0f6;
        border-radius: 12px;
        padding: 1rem 1.2rem;
        transition: all 0.3s ease;
    }

    .ptm-section:hover {
        background: #fff;
        border-color: #dce4f0;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
    }

    .ptm-section p {
        margin: 0;
        color: #333;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* === Buttons === */
    .btn {
        border-radius: 10px;
        font-weight: 500;
        letter-spacing: 0.3px;
        transition: all 0.25s ease;
    }

    .btn-outline-primary {
        border: 1px solid var(--sd-accent, #4a6cf7);
        color: var(--sd-accent, #4a6cf7);
    }

    .btn-outline-primary:hover {
        background-color: var(--sd-accent, #4a6cf7);
        color: #fff;
        box-shadow: 0 4px 12px rgba(74, 108, 247, 0.2);
    }

    /* Themed hover: keep Back border/text black, no fill */
    body[class*="theme-"] .btn-outline-primary:hover {
        background-color: transparent !important;
        border-color: black !important;
        color: black !important;
        box-shadow: none !important;
    }

    .btn-primary {
        background: linear-gradient(90deg, var(--sd-accent, #4a6cf7), var(--sd-accent-soft, #7699f8));
        border: none;
        color: #fff;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, var(--sd-accent, #3857d6), var(--sd-accent-soft, #5b82f1));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(74, 108, 247, 0.3);
    }

    .btn-success {
        background-color: #33c58e;
        border: none;
    }

    .btn-success:hover {
        background-color: #2ca87a;
        transform: translateY(-2px);
    }

    /* === Modal Styling === */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: linear-gradient(90deg, var(--sd-accent, #4a6cf7), var(--sd-accent-soft, #7699f8));
        color: #fff;
        border-bottom: none;
        border-radius: 16px 16px 0 0;
        padding: 1rem 1.5rem;
    }

    .modal-body label {
        font-weight: 600;
        color: #555;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Dropdowns */
    select.form-control {
        border-radius: 12px;
        border: 1px solid #d7dce4;
        background-color: #f9faff;
        padding: 10px 14px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    select.form-control:hover {
        background-color: #fff;
        border-color: var(--sd-accent, #4a6cf7);
    }

    select.form-control:focus {
        border-color: var(--sd-accent, #4a6cf7);
        box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.15);
    }

    /* Elegant Textarea */
    textarea.form-control {
        border-radius: 12px;
        border: 1px solid #d7dce4;
        background-color: #f9faff;
        padding: 10px 14px;
        font-size: 0.95rem;
        resize: none;
        transition: all 0.3s ease;
    }

    textarea.form-control:hover {
        background-color: #fff;
    }

    textarea.form-control:focus {
        border-color: var(--sd-accent, #4a6cf7);
        box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.15);
    }

    /* === Footer === */
    .card-footer {
        border-top: 1px solid #e8ecf1;
        border-radius: 0 0 16px 16px;
        padding: 1.2rem 1.5rem;
    }

    /* === Loading Overlay === */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(10, 10, 20, 0.55);
        backdrop-filter: blur(4px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.35s ease-in-out;
    }

    .loading-overlay.show {
        display: flex !important;
        opacity: 1;
        pointer-events: all;
    }

    .progress-wrapper {
        text-align: center;
    }

    .progress-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: conic-gradient(var(--sd-accent, #5f77ff) 0deg, #ffffff33 0deg);
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
    }

    .progress-percent {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #4a6cf7;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* === Subtle Animation === */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .icon {
            color: var(--sd-accent, #4a6cf7);
        margin-right: 6px;
    }

    .badge {
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 500;
        padding: 0.35rem 0.6rem;
    }

    /* Theme conditional text colors */
    body[class*="theme-"] .ptm-section {
        color: black !important;
    }

    body[class*="theme-"] .btn-primary {
        color: black !important;
    }

    /* Show Details button becomes solid with theme */
    body[class*="theme-"] .show-details-btn {
        background: linear-gradient(90deg, var(--sd-accent), var(--sd-accent-soft)) !important;
        border: none !important;
        color: black !important;
    }
    
    /* Back button text black when themed */
    body[class*="theme-"] .btn-outline-primary {
          background: linear-gradient(90deg, var(--sd-accent), var(--sd-accent-soft)) !important;
        color: black !important;
    }
</style>

<div class="ptm-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class=" col-lg-10">

                <div class="card ptm-view-card">
                    <!-- Header -->
                    <div class="ptm-header d-flex justify-content-between align-items-center">
                        <h3><i class="fa fa-users " style="margin-right: 10px;"></i> {{ $ptm->title ?? 'Parent Teacher Meeting' }}</h3>
                    </div>
                    
                    <!-- Body -->
                    <div class="ptm-body">
                        <!-- Objective Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6><i class="fa fa-bullseye icon"></i> Objective</h6>
                                @if (auth()->user()->userType === 'Parent')
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                        data-target="#rescheduleModal">
                                        <i class="fa fa-sync-alt me-1"></i> Reschedule
                                    </button>
                                @else
                                    <a href="{{ route('ptm.details', $ptm->id) }}" 
                                        class="btn btn-outline-primary btn-sm show-details-btn">
                                        <i class="fa fa-eye me-1"></i> Show Details
                                    </a>
                                @endif
                            </div>
                            <div class="ptm-section">
                                <p>{{ $ptm->objective ?? 'No objective provided for this PTM.' }}</p>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="row g-4">
                            <div class="col-md-12">
                                <h6 class="mb-3"><i class="fa fa-calendar icon me-2"></i> Date & Time</h6>
                                @if(auth()->user()->userType === 'Parent')
                                    <div class="ptm-section" style="background: linear-gradient(135deg, var(--sd-accent, #667eea) 0%, var(--sd-accent-soft, #764ba2) 100%); border-radius: 15px; padding: 25px; color: white; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
                                        <div class="d-flex align-items-start justify-content-between mb-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fa fa-calendar-check me-2" style="font-size: 1.3rem;"></i>
                                                    <span style="font-size: 1.2rem; font-weight: 600; margin-left: 10px;">
                                                        @if ($finalDate)
                                                            {{ \Carbon\Carbon::parse($finalDate)->format('l, d M Y') }}
                                                        @else
                                                            <span style="opacity: 0.8;"><i class="fa fa-exclamation-circle me-2"></i>Date not set</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center" style="font-size: 1rem; opacity: 0.95;">
                                                    <i class="fa fa-clock me-2"></i>
                                                    <span style="margin-left: 10px;"><strong>Time Slot:</strong> {{ $finalSlot ?? 'Slot not set' }}</span>
                                                </div>
                                            </div>
                                            @if ($isRescheduled)
                                                <span class="badge" style="background-color: rgba(242, 246, 247, 0.25); backdrop-filter: blur(10px); font-size: 0.85rem; padding: 8px 15px; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.3);">
                                                    <i class="fa fa-sync-alt me-1"></i> Rescheduled
                                                </span>
                                            @endif
                                        </div>

                                        @if ($isRescheduled)
                                            <div style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border-radius: 10px; padding: 15px; border-left: 4px solid rgba(255, 255, 255, 0.5);">
                                                <div class="d-flex align-items-start">
                                                    <i class="fa fa-info-circle me-2 mt-1" style="opacity: 0.9;  margin-right: 10px;"></i>
                                                    <div style="font-size: 0.9rem; line-height: 1.6; ">
                                                        <div class="mb-1">
                                                            <strong>Original Schedule:</strong> 
                                                            {{ $originalDate ? \Carbon\Carbon::parse($originalDate)->format('l, d M Y') : 'N/A' }}
                                                            @if ($originalSlot)
                                                                at {{ $originalSlot }}
                                                            @endif
                                                        </div>
                                                        @if ($rescheduledBy)
                                                            <div style="opacity: 0.85;">
                                                                <i class="fa fa-user me-1" ></i>
                                                                <small>Rescheduled by: <strong>{{ $rescheduledBy }}</strong></small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="ptm-section" style="background: linear-gradient(135deg, var(--sd-accent, #1a73e8) 0%, var(--sd-accent-soft, #1557b0) 100%); border-radius: 15px; padding: 30px; color: white; box-shadow: 0 8px 20px rgba(26, 115, 232, 0.3); text-align: center;">
                                        <div class="mb-3">
                                            <i class="fa fa-calendar-alt" style="font-size: 2.5rem; opacity: 0.9;"></i>
                                        </div>
                                        <div style="font-size: 1.3rem; font-weight: 600; margin-bottom: 10px;">
                                            {{ $originalDate ? \Carbon\Carbon::parse($originalDate)->format('l, d M Y') : 'Date not available' }}
                                        </div>
                                        @if ($originalSlot)
                                            <div style="font-size: 1.1rem; opacity: 0.95; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                                <i class="fa fa-clock"></i>
                                                <span>{{ $originalSlot }}</span>
                                            </div>
                                        @endif
                                        <div class="mt-3" style="padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.2);">
                                            <small style="opacity: 0.85;">
                                                <i class="fa fa-info-circle me-1"></i>
                                                Default PTM Schedule
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card card-footer text-center">
                        <a href="javascript:void(0)" onclick="goBack()" class="btn btn-outline-primary me-3 px-4">
                            <i class="fa fa-arrow-left me-2"></i> Back
                        </a>
                        <a href="{{ route('ptm.index') }}" class="btn btn-primary px-4">
                            <i class="fa fa-list me-2"></i> My PTMs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #5f77ff, #7b4bff); color: white; border-radius: 16px 16px 0 0; border: none;">
                <h5 class="mb-0"><i class="fa fa-sync-alt me-2"></i>Reschedule PTM</h5>
                <button type="button" class="btn btn-sm " data-dismiss="modal" style="border-radius: 8px; padding: 6px 16px; font-size: 0.85rem; font-weight: 500; white-space: nowrap;">
                                <i class="fa fa-times me-1"></i> Cancel
                            </button>
            </div>
            <form id="reschedulePtmForm" action="{{ route('ptm.reschedule-ptm') }}" method="POST">
                @csrf
                <input type="hidden" name="ptmid" value="{{ $ptm->id ?? '' }}">
                <input type="hidden" name="userid" value="{{ Auth::id() }}">
                <input type="hidden" name="ptmdateid" id="hiddenPtmDateId">
                <input type="hidden" name="ptmslotid" id="hiddenPtmSlotId">

                <div class="modal-body" style="padding: 20px;">
                    
                    {{-- Selected Info --}}
                    <div class="d-flex justify-content-between align-items-stretch mb-3 gap-3">
                        <div class="flex-grow-1 d-flex flex-column gap-2">
                            <div class="selected-info-reschedule" id="selectedDateInfoReschedule" style="display: none; padding: 10px; background: #eef3ff; border-left: 4px solid #5f77ff; border-radius: 8px; font-size: 0.9rem; flex: 1;">
                                <strong>Date:</strong> <span id="selectedDateTextReschedule"></span>
                            </div>
                            <div class="selected-info-reschedule" id="selectedSlotInfoReschedule" style="display: none; padding: 10px; background: #eef3ff; border-left: 4px solid #5f77ff; border-radius: 8px; font-size: 0.9rem; flex: 1;">
                                <strong>Slot:</strong> <span id="selectedSlotTextReschedule"></span>
                            </div>
                        </div>
                        <div style="display: flex; min-width: 140px;">
                            <button type="submit" class="btn btn-sm" style="background: linear-gradient(135deg, #28c76f, #20dda8); color: white; border: none; border-radius: 8px; padding: 10px 16px; font-size: 0.9rem; font-weight: 600; white-space: nowrap; width: 100%; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-check me-2"></i> Reschedule
                            </button>
                        </div>
                    </div>

                    {{-- Date & Slot Grid --}}
                    <div class="ptm-grid-reschedule" style="display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 15px; margin-bottom: 15px;">
                        
                        {{-- Date Calendar --}}
                        <div class="calendar-container-reschedule" style="background: #f4f6ff; border-radius: 12px; padding: 12px;">
                            <div class="section-title-reschedule" style="font-size: 0.85rem; color: #666; font-weight: 600; margin-bottom: 10px;">
                                <i class="fa fa-calendar mr-1"></i> Select Date
                            </div>
                            <div class="calendar-grid-reschedule" id="calendarGridReschedule" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(105px, 1fr)); gap: 10px;">
                                <!-- Dates will be populated here -->
                            </div>
                        </div>

                        {{-- Slot Panel --}}
                        <div class="slots-panel-reschedule" style="background: #f4f6ff; border-radius: 12px; padding: 12px; display: none;" id="slotsPanelReschedule">
                            <div class="section-title-reschedule" style="font-size: 0.85rem; color: #666; margin-bottom: 10px; font-weight: 600;">
                                <i class="fa fa-clock mr-1"></i> Time Slots
                            </div>
                            <div class="slots-grid-reschedule" id="slotsGridReschedule" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(105px, 1fr)); gap: 10px;">
                                <!-- Slots will be populated here -->
                            </div>
                        </div>

                    </div>

                    {{-- Reason --}}
                    <div style="margin-top: 15px;">
                        <label style="font-size: 0.9rem; color: #555; font-weight: 600; margin-bottom: 8px; display: block;">
                            <i class="fa fa-comment-alt mr-1"></i> Reason (optional)
                        </label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"
                            placeholder="Briefly explain why you want to reschedule..."
                            style="border-radius: 10px; border: 2px solid #e1e1e1; padding: 12px; font-size: 0.9rem; resize: vertical; transition: 0.25s;"></textarea>
                    </div>
                </div>

            </form>
            
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="progress-wrapper">
        <div class="progress-circle">
            <span class="progress-percent">⏳</span>
        </div>
        <p style="color: #fff; margin-top: 20px; font-weight: 500;">Processing...</p>
    </div>
</div>

@include('layout.footer')
<script>
    function goBack() {
        if (window.history.length > 1) window.history.back();
        else window.location.href = "{{ route('ptm.index') }}";
    }

    $(function() {
        let ptm = @json($ptm);
        let finalDate = @json($finalDate ?? null);
        let finalSlot = @json($finalSlot ?? null);
        let allPtmData = null;
        let selectedDateId = null;
        let selectedSlotId = null;

        // Helper to extract starting time in minutes for sorting
        const getStartMinutes = (slotStr) => {
            if (typeof slotStr !== 'string') return Number.MAX_SAFE_INTEGER;
            const match = slotStr.match(/(\d{1,2}:\d{2})\s*([AP]M)/i);
            if (!match) return Number.MAX_SAFE_INTEGER;
            let [_, time, meridian] = match;
            let [h, m] = time.split(':').map(Number);
            meridian = meridian.toUpperCase();
            if (meridian === 'PM' && h !== 12) h += 12;
            if (meridian === 'AM' && h === 12) h = 0;
            return h * 60 + m;
        };

        $('#rescheduleModal').on('show.bs.modal', function() {
            $.get('{{ route('ptm.get-date-slots') }}', { ptmid: ptm.id }, function(res) {
                allPtmData = res.ptm;
                
                // Find the current date ID to pre-select it
                let currentDateId = null;
                const calendarGrid = $('#calendarGridReschedule');
                calendarGrid.empty();
                
                // Create date cards
                $.each(res?.ptm?.ptm_dates || [], (i, d) => {
                    const dateObj = new Date(d.date);
                    const dayName = dateObj.toLocaleDateString('en-US', { weekday: 'short' });
                    const dateNumber = dateObj.getDate();
                    const monthYear = dateObj.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                    const isSelected = (d.date === finalDate);
                    
                    if (isSelected) currentDateId = d.id;
                    
                    const dateCard = $(`
                        <div class="date-card-reschedule ${isSelected ? 'selected' : ''}" 
                             data-date-id="${d.id}"
                             style="background: ${isSelected ? '#eef1ff' : 'white'}; border-radius: 12px; border: 2px solid ${isSelected ? '#5f77ff' : '#e0e0e0'}; padding: 12px 10px; text-align: center; cursor: pointer; transition: 0.25s;">
                            <div style="font-size: 0.75rem; color: #555; margin-bottom: 3px;">
                                <i class="fa fa-calendar-alt mr-1"></i> ${dayName}
                            </div>
                            <div style="font-size: 1.4rem; font-weight: 700;">${dateNumber}</div>
                            <div style="font-size: 0.75rem; color: #666;">${monthYear}</div>
                        </div>
                    `);
                    
                    dateCard.on('click', function() {
                        selectDateReschedule($(this), d.id, `${dayName} ${dateNumber} ${monthYear}`);
                    });
                    
                    calendarGrid.append(dateCard);
                });
                
                // If we have a current date, filter and show slots for that date
                if (currentDateId) {
                    selectedDateId = currentDateId;
                    $('#hiddenPtmDateId').val(currentDateId);
                    $('#selectedDateTextReschedule').text($('.date-card-reschedule.selected').text().replace(/\s+/g, ' ').trim());
                    $('#selectedDateInfoReschedule').show();
                    loadSlotsForDateReschedule(currentDateId, finalSlot);
                }
            });
        });

        function selectDateReschedule(element, dateId, dateText) {
            // Remove previous selection
            $('.date-card-reschedule').css({
                'border-color': '#e0e0e0',
                'background': 'white',
                'box-shadow': 'none'
            }).removeClass('selected');
            
            // Add selection to clicked card
            element.css({
                'border-color': '#5f77ff',
                'background': '#eef1ff',
                'box-shadow': '0 5px 15px rgba(95, 119, 255, 0.2)'
            }).addClass('selected');
            
            selectedDateId = dateId;
            $('#hiddenPtmDateId').val(dateId);
            
            // Show selected date info
            $('#selectedDateTextReschedule').text(dateText);
            $('#selectedDateInfoReschedule').show();
            
            // Load slots for this date
            loadSlotsForDateReschedule(dateId);
            
            // Reset slot selection
            selectedSlotId = null;
            $('#hiddenPtmSlotId').val('');
            $('#selectedSlotInfoReschedule').hide();
        }

        function loadSlotsForDateReschedule(dateId, preSelectedSlot = null) {
            if (!allPtmData || !dateId) {
                $('#slotsPanelReschedule').hide();
                return;
            }

            // Filter slots by ptmdate_id
            let filteredSlots = allPtmData.ptm_slots.filter(slot => 
                slot.ptmdate_id == dateId
            );

            // Sort slots chronologically
            filteredSlots.sort((a, b) => {
                const diff = getStartMinutes(a.slot) - getStartMinutes(b.slot);
                return diff !== 0 ? diff : a.slot.localeCompare(b.slot);
            });

            const slotsGrid = $('#slotsGridReschedule');
            slotsGrid.empty();

            if (filteredSlots.length > 0) {
                // Create slot cards
                $.each(filteredSlots, (i, s) => {
                    const isSelected = (preSelectedSlot && s.slot === preSelectedSlot);
                    
                    if (isSelected) {
                        selectedSlotId = s.id;
                        $('#hiddenPtmSlotId').val(s.id);
                        $('#selectedSlotTextReschedule').text(s.slot);
                        $('#selectedSlotInfoReschedule').show();
                    }
                    
                    const slotCard = $(`
                        <div class="slot-item-reschedule ${isSelected ? 'selected' : ''}"
                             data-slot-id="${s.id}"
                             style="background: ${isSelected ? '#eef1ff' : 'white'}; border-radius: 12px; border: 2px solid ${isSelected ? '#5f77ff' : '#e0e0e0'}; padding: 12px 10px; text-align: center; cursor: pointer; transition: 0.25s;">
                            <div style="font-size: 1.2rem; margin-bottom: 5px; color: ${isSelected ? '#5f77ff' : '#555'};">
                                <i class="fa fa-clock"></i>
                            </div>
                            <div style="font-size: 0.85rem; font-weight: 600; color: #333;">${s.slot}</div>
                        </div>
                    `);
                    
                    slotCard.on('click', function() {
                        selectSlotReschedule($(this), s.id, s.slot);
                    });
                    
                    slotCard.on('mouseenter', function() {
                        if (!$(this).hasClass('selected')) {
                            $(this).css({
                                'border-color': '#5f77ff',
                                'background': '#f2f4ff',
                                'transform': 'translateY(-3px)'
                            });
                        }
                    }).on('mouseleave', function() {
                        if (!$(this).hasClass('selected')) {
                            $(this).css({
                                'border-color': '#e0e0e0',
                                'background': 'white',
                                'transform': 'translateY(0)'
                            });
                        }
                    });
                    
                    slotsGrid.append(slotCard);
                });
                
                // Show slots panel
                $('#slotsPanelReschedule').show();
            } else {
                slotsGrid.html('<p style="grid-column: 1/-1; text-align: center; color: #999;">No slots available</p>');
                $('#slotsPanelReschedule').show();
            }
        }

        function selectSlotReschedule(element, slotId, slotText) {
            // Remove previous selection
            $('.slot-item-reschedule').css({
                'border-color': '#e0e0e0',
                'background': 'white',
                'box-shadow': 'none'
            }).removeClass('selected').find('i').css('color', '#555');
            
            // Add selection to clicked card
            element.css({
                'border-color': '#5f77ff',
                'background': '#eef1ff',
                'box-shadow': '0 5px 15px rgba(95, 119, 255, 0.2)'
            }).addClass('selected').find('i').css('color', '#5f77ff');
            
            selectedSlotId = slotId;
            $('#hiddenPtmSlotId').val(slotId);
            
            // Show selected slot info
            $('#selectedSlotTextReschedule').text(slotText);
            $('#selectedSlotInfoReschedule').show();
        }

        $('#reschedulePtmForm').on('submit', function(e) {
            e.preventDefault();
            
            // Show loading overlay
            $('#loadingOverlay').addClass('show');
            
            // Disable form submission button
            $('#reschedulePtm').prop('disabled', true);
            
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#rescheduleModal').modal('hide');
                        $('#reschedulePtmForm')[0].reset();
                        
                        // Refresh the page after success
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.text-danger').remove();
                        $.each(errors, function(field, messages) {
                            $('[name="' + field + '"]').after(
                                '<span class="text-danger small">' + messages[0] + '</span>'
                            );
                        });
                    }
                    
                    // Show error message if request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to reschedule PTM. Please try again.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                complete: function() {
                    // Hide loading overlay
                    $('#loadingOverlay').removeClass('show');
                    // Re-enable form submission button
                    $('#reschedulePtm').prop('disabled', false);
                }
            });
        });
    });
</script>

@endsection
