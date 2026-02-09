<style>
/* THEME SYSTEM: Theme icons, selected date/slot cards */
body[class^="theme-"] .section-title .fa-calendar,
body[class^="theme-"] .section-title .fa-comment-alt {
    color: var(--sd-accent, #5f77ff) !important;
}
body[class^="theme-"] .date-card.selected {
    border-color: var(--sd-accent, #5f77ff) !important;
    background: rgba(95, 119, 255, 0.08) !important;
    box-shadow: 0 5px 15px rgba(95, 119, 255, 0.15);
}
body[class^="theme-"] .slot-item.selected {
    border-color: var(--sd-accent, #5f77ff) !important;
    background: rgba(95, 119, 255, 0.08) !important;
    box-shadow: 0 5px 15px rgba(95, 119, 255, 0.15);
}
body[class^="theme-"] .slot-item.selected .slot-icon {
    color: var(--sd-accent, #5f77ff) !important;
}
body[class^="theme-"] .date-card:hover {
    border-color: var(--sd-accent, #5f77ff) !important;
    background: rgba(95, 119, 255, 0.08) !important;
    box-shadow: 0 5px 15px rgba(95, 119, 255, 0.15);
}
body[class^="theme-"] .slot-item:hover {
    border-color: var(--sd-accent, #5f77ff) !important;
    background: rgba(95, 119, 255, 0.08) !important;
    box-shadow: 0 5px 15px rgba(95, 119, 255, 0.15);
}
body[class^="theme-"] .slot-item:hover .slot-icon {
    color: var(--sd-accent, #5f77ff) !important;
}
</style>
<style>
/* THEME SYSTEM: Theme reschedule header, selected-info border, and submit button */
body[class^="theme-"] .reschedule-header {
    background: var(--sd-accent, #5f77ff) !important;
    color: #fff !important;
}
body[class^="theme-"] .selected-info {
    border-left: 4px solid var(--sd-accent, #5f77ff) !important;
}
body[class^="theme-"] .btn-primary-gradient {
    background: var(--sd-accent, #28c76f) !important;
    border: none !important;
    color: #fff !important;
}
body[class^="theme-"] .btn-primary-gradient:hover:not(:disabled) {
    background: #0056b3 !important;
}
</style>
@extends('layout.master')

@section('title', 'Reschedule PTM')

@section('content')

    <style>
        .submit-overlay {
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
            transition: opacity 0.35s ease-in-out;
        }

        .submit-overlay.show {
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
            background: conic-gradient(#5f77ff 0deg, #ffffff33 0deg);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .progress-percent {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }

        .progress-check {
            font-size: 1.6rem;
            color: #fff;
            font-weight: 700;
        }

        .progress-circle.success {
            background: conic-gradient(#28c76f 360deg, #ffffff33 0deg) !important;
            box-shadow: 0 8px 24px rgba(40,199,111,0.25);
        }
        .progress-circle.success .progress-percent { display: none; }
        .progress-circle.success .progress-check { display: block; }

        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background: #f7f9fc;
        }

        /* PAGE WRAPPER */
        .reschedule-container {
            max-width: 720px; 
            margin: 0 auto;
            padding: 20px 10px;
            margin-top: -30px;
        }

        /* HEADER */
        .reschedule-header {
            background: linear-gradient(135deg, #5f77ff, #7b4bff);
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(95, 119, 255, 0.25);
        }

        .reschedule-header h3 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 600;
        }

        .reschedule-header p {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 0.95rem;
        }

        .reschedule-card {
            
            border-radius: 14px;
            box-shadow: 0 5px 16px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding-bottom: 10px;
        }

        .card-section {
            padding: 18px 20px;
            border-bottom: 1px solid #f1f1f1;
        }

        .section-title {
            font-size: 0.95rem;
            color: #444;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            font-size: 1.05rem;
            color: #5f77ff;
        }

        /* DATE AND SLOT GRID */
        .ptm-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 15px;
            margin-top: 10px;
        }

        /* DATE CARDS */
        .calendar-container {
            background: #f4f6ff;
            border-radius: 12px;
            padding: 12px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(105px, 1fr));
            gap: 10px;
        }

        .date-card {
            background: white;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 12px 10px;
            text-align: center;
            transition: 0.25s;
            cursor: pointer;
        }

        .date-card:hover {
            border-color: #5f77ff;
            background: #f2f4ff;
            transform: translateY(-3px);
        }

        .date-card.selected {
            border-color: #5f77ff;
            background: #eef1ff;
            box-shadow: 0 5px 15px rgba(95, 119, 255, 0.2);
        }

        .date-card .day-name {
            font-size: 0.75rem;
            color: #555;
            margin-bottom: 3px;
        }

        .date-card .date-number {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .date-card .month-year {
            font-size: 0.75rem;
            color: #666;
        }

        /* SLOT PANEL - STYLED LIKE DATE CARDS */
        .slots-panel {
            background: #f4f6ff;
            border-radius: 12px;
            padding: 12px;
        }

        .slots-panel-title {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(105px, 1fr));
            gap: 10px;
        }

        .slot-item {
            background: white;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
          
            text-align: center;
            cursor: pointer;
            transition: 0.25s;
        }

        .slot-item:hover {
            border-color: #5f77ff;
            background: #f2f4ff;
            transform: translateY(-3px);
        }

        .slot-item.selected {
            border-color: #5f77ff;
            background: #eef1ff;
            box-shadow: 0 5px 15px rgba(95, 119, 255, 0.2);
        }

        .slot-item .slot-icon {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: #555;
        }

        .slot-item.selected .slot-icon {
            color: #5f77ff;
        }

        .slot-item .slot-time {
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
        }

        .slots-container {
            display: none;
        }

        .slots-container.show {
            display: block;
        }

        /* REASON TEXTAREA */
        .elegant-textarea {
            background-color: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            resize: vertical;
            transition: all 0.25s ease;
            padding: 12px 14px;
            font-size: 0.9rem;
            color: #333;
            width: 100%;
        }

        .elegant-textarea:hover {
            border-color: #5f77ff;
            background-color: #fafbff;
        }

        .elegant-textarea:focus {
            border-color: #5f77ff;
            box-shadow: 0 0 0 3px rgba(95, 119, 255, 0.15);
            background-color: white;
            outline: none;
        }

        /* SUBMIT BUTTONS */
        .submit-container {
            padding: 20px;
            text-align: center;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #28c76f, #20dda8);
            border: none;
            border-radius: 35px;
            padding: 11px 35px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            transition: 0.25s;
            box-shadow: 0 5px 15px rgba(40, 199, 111, 0.3);
        }

        .btn-primary-gradient:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(40, 199, 111, 0.4);
        }

        .btn-primary-gradient:disabled {
            background: #c1c1c1;
            box-shadow: none;
            cursor: not-allowed;
        }

        .btn-outline-secondary {
            color: #555;
            border: 2px solid #ddd;
            background-color: white;
            border-radius: 35px;
            padding: 10px 34px;
            font-size: 1rem;
            font-weight: 600;
            transition: 0.25s;
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #aaa;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .selected-info {
            padding: 8px 10px;
            background: #eef3ff;
            border-left: 4px solid #5f77ff;
            border-radius: 7px;
            font-size: 0.85rem;
            display: none;
            margin-bottom: 10px;
        }

        .selected-info.show {
            display: block;
        }
    </style>

    <div class="reschedule-container" id="maincard">
        
        <div class="reschedule-header">
            <h3><i class="fa fa-calendar-check mr-2"></i>Reschedule PTM</h3>
            <p>Update schedule for <strong>{{ $child->name }}</strong></p>
        </div>

        <form id="rescheduleForm" action="{{ route('ptm.resupdate-fstaff', ['ptm' => $ptm->id, 'child' => $child->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="reschedule-card">

                {{-- Date & Slot Section --}}
                <div class="card-section">
                    <div class="section-title">
                        <i class="fa fa-calendar"></i> Select Date & Time Slot
                    </div>

                    <div class="selected-info" id="selectedDateInfo">
                        <strong>Date:</strong> <span id="selectedDateText"></span>
                    </div>
                    <div class="selected-info" id="selectedSlotInfo">
                        <strong>Slot:</strong> <span id="selectedSlotText"></span>
                    </div>

                    <div class="ptm-grid">
                        {{-- Date Cards --}}
                        <div class="calendar-container">
                            <div class="calendar-grid">
                                @foreach ($availableDates as $date)
                                    @php $dateObj = \Carbon\Carbon::parse($date->date); @endphp
                                    <div class="date-card {{ (old('ptmdateid', $currentDateId) == $date->id) ? 'selected' : '' }}" 
                                         data-date-id="{{ $date->id }}"
                                         onclick="selectDate(this, {{ $date->id }}, '{{ $dateObj->format('l, d M Y') }}')">
                                        <div class="day-name">
                                            <i class="fa fa-calendar-alt mr-1"></i> {{ $dateObj->format('D') }}
                                        </div>
                                        <div class="date-number">{{ $dateObj->format('d') }}</div>
                                        <div class="month-year">{{ $dateObj->format('M Y') }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Slot Cards --}}
                        <div class="slots-panel slots-container" id="slotsContainer">
                            <div class="slots-panel-title">
                                <i class="fa fa-clock mr-1"></i> Time Slots
                            </div>
                            <div class="slots-grid" id="slotsGrid"></div>
                        </div>
                    </div>

                    <input type="hidden" name="ptmdateid" id="ptmdateid" value="{{ old('ptmdateid', $currentDateId) }}" required>
                    <input type="hidden" name="ptmslotid" id="ptmslotid" value="{{ old('ptmslotid', $currentSlotId) }}" required>
                    <input type="hidden" name="childid" value="{{ $child->id ?? '' }}">
                </div>

                {{-- Reason Section --}}
                <div class="card-section">
                    <div class="section-title">
                        <i class="fa fa-comment-alt"></i> Reason (Optional)
                    </div>
                    <textarea name="reason" rows="3" class="elegant-textarea" placeholder="Enter reason for rescheduling..."></textarea>
                </div>

                {{-- Submit Buttons --}}
                <div class="submit-container">
                    <button type="submit" class="btn btn-primary-gradient">
                        <i class="fa fa-check-circle mr-1"></i> Confirm Reschedule
                    </button>
                    <a href="{{ route('ptm.index', $ptm->id) }}" class="btn btn-outline-secondary ml-2">
                        <i class="fa fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="submit-overlay" id="submitOverlay">
        <div class="progress-wrapper">
            <div class="progress-circle" id="progressCircle">
                <div class="progress-percent" id="progressPercent">0%</div>
                <div class="progress-check" id="progressCheck" style="display:none;">✓</div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Store all slots with their date associations
        const allSlots = @json($availableSlots->map(function($slot) {
            return [
                'id' => $slot->id,
                'slot' => $slot->slot,
                'ptmdate_id' => $slot->ptmdate_id ?? ($slot->ptmDate->id ?? null)
            ];
        }));

        let selectedDateId = {{ old('ptmdateid', $currentDateId ?? 'null') }};
        let selectedSlotId = {{ old('ptmslotid', $currentSlotId ?? 'null') }};
        
        // Store original values to check if anything changed
        const originalDateId = {{ $currentDateId ?? 'null' }};
        const originalSlotId = {{ $currentSlotId ?? 'null' }};

        

        // Function to check if values have changed and enable/disable submit button
        function checkIfChanged() {
            const submitBtn = document.querySelector('button[type="submit"]');
            const hasChanged = (selectedDateId != originalDateId || selectedSlotId != originalSlotId);
            const hasValidSelection = selectedDateId && selectedSlotId; // Both must be selected
            
            if (submitBtn) {
                // Enable only if values changed AND both date and slot are selected
                submitBtn.disabled = !(hasChanged && hasValidSelection);
                
                if (!(hasChanged && hasValidSelection)) {
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                } else {
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                }
            }
        }

        function selectDate(element, dateId, dateText) {
            // Remove previous selection
            document.querySelectorAll('.date-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selection to clicked card
            element.classList.add('selected');
            selectedDateId = dateId;

            // Update hidden input
            document.getElementById('ptmdateid').value = dateId;

            // Show selected date info
            document.getElementById('selectedDateText').textContent = dateText;
            document.getElementById('selectedDateInfo').classList.add('show');

            // Filter and show slots for this date
            loadSlotsForDate(dateId);

            // Reset slot selection
            selectedSlotId = null;
            document.getElementById('ptmslotid').value = '';
            document.getElementById('selectedSlotInfo').classList.remove('show');

            
            
            // Check if values changed
            checkIfChanged();
        }

        function loadSlotsForDate(dateId) {
            const slotsContainer = document.getElementById('slotsContainer');
            const slotsGrid = document.getElementById('slotsGrid');

            // Filter slots for this date
            let dateSlots = allSlots.filter(slot => slot.ptmdate_id == dateId);

            // Helper to extract starting time in minutes for sorting (supports formats like "09:00 AM - 09:30 AM")
            const getStartMinutes = (slotStr) => {
                if (typeof slotStr !== 'string') return Number.MAX_SAFE_INTEGER;
                const match = slotStr.match(/(\d{1,2}:\d{2})\s*([AP]M)/i);
                if (!match) return Number.MAX_SAFE_INTEGER; // put unknown formats at end
                let [_, time, meridian] = match;
                let [h, m] = time.split(':').map(Number);
                meridian = meridian.toUpperCase();
                if (meridian === 'PM' && h !== 12) h += 12;
                if (meridian === 'AM' && h === 12) h = 0;
                return h * 60 + m;
            };

            // Sort by start time, then lexicographically as a stable fallback
            dateSlots.sort((a, b) => {
                const diff = getStartMinutes(a.slot) - getStartMinutes(b.slot);
                return diff !== 0 ? diff : a.slot.localeCompare(b.slot);
            });

            

            // Clear existing slots
            slotsGrid.innerHTML = '';

            if (dateSlots.length > 0) {
                // Create slot cards
                dateSlots.forEach(slot => {
                    const slotCard = document.createElement('div');
                    slotCard.className = 'slot-item';
                    slotCard.innerHTML = `
                        <div class="slot-icon"><i class="fa fa-clock"></i></div>
                        <div class="slot-time">${slot.slot}</div>
                    `;
                    slotCard.onclick = () => selectSlot(slotCard, slot.id, slot.slot);
                    slotsGrid.appendChild(slotCard);
                });

                // Show slots container
                slotsContainer.classList.add('show');
            } else {
                slotsGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">No slots available</p>';
                slotsContainer.classList.add('show');
            }
        }

        function selectSlot(element, slotId, slotText) {
            // Remove previous selection
            document.querySelectorAll('.slot-item').forEach(slot => {
                slot.classList.remove('selected');
            });

            // Add selection to clicked card
            element.classList.add('selected');
            selectedSlotId = slotId;

            // Update hidden input
            document.getElementById('ptmslotid').value = slotId;

            // Show selected slot info
            document.getElementById('selectedSlotText').textContent = slotText;
            document.getElementById('selectedSlotInfo').classList.add('show');

            
            
            // Check if values changed
            checkIfChanged();
        }

        // Initialize on page load
        $(document).ready(function() {
            // If there's a preselected date, load its slots
            if (selectedDateId) {
                const dateCard = document.querySelector(`.date-card[data-date-id="${selectedDateId}"]`);
                if (dateCard) {
                    const dateText = document.getElementById('selectedDateText');
                    if (dateText && !dateText.textContent) {
                        // Extract date text from card
                        const dayName = dateCard.querySelector('.day-name').textContent;
                        const dateNumber = dateCard.querySelector('.date-number').textContent;
                        const monthYear = dateCard.querySelector('.month-year').textContent;
                        document.getElementById('selectedDateText').textContent = `${dayName} ${dateNumber} ${monthYear}`;
                        document.getElementById('selectedDateInfo').classList.add('show');
                    }
                    loadSlotsForDate(selectedDateId);

                    // If there's a preselected slot, select it
                    setTimeout(() => {
                        if (selectedSlotId) {
                            const slotCard = document.querySelector(`.slot-item`);
                            const allSlotCards = document.querySelectorAll('.slot-item');
                            allSlotCards.forEach(card => {
                                const slotText = card.querySelector('.slot-time').textContent;
                                const matchingSlot = allSlots.find(s => s.id == selectedSlotId && s.slot === slotText);
                                if (matchingSlot) {
                                    card.classList.add('selected');
                                    document.getElementById('selectedSlotText').textContent = slotText;
                                    document.getElementById('selectedSlotInfo').classList.add('show');
                                }
                            });
                        }
                    });
                }
            }

            // Initial check on page load
            checkIfChanged();

            // Form submission loader (bulk-style)
            document.getElementById('rescheduleForm').addEventListener('submit', function () {
                const overlay = document.getElementById('submitOverlay');
                const percentText = document.getElementById('progressPercent');
                const circle = document.getElementById('progressCircle');

                overlay.classList.add('show');
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;

                let percent = 0;
                const interval = setInterval(() => {
                    percent += Math.floor(Math.random() * 3) + 1; // 1-3%
                    if (percent >= 100) percent = 100;
                    percentText.textContent = percent + '%';
                    circle.style.background = `conic-gradient(#5f77ff ${percent * 3.6}deg, #ffffff33 0deg)`;
                    if (percent === 100) clearInterval(interval);
                }, 200);
            });

            window.addEventListener('pageshow', function() {
                const overlay = document.getElementById('submitOverlay');
                if (overlay) overlay.classList.remove('show');
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = false;
            });
        });
    </script>

    @include('layout.footer')
@endsection
