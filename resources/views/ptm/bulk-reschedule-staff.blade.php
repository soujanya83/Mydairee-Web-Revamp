@extends('layout.master')

@section('title', 'Bulk Reschedule PTM')

@section('content')

<style>
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

.reschedule-card {
    background: white;
    border-radius: 14px;
    box-shadow: 0 5px 16px rgba(0,0,0,0.08);
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

/* CHILD BADGES */
.children-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 8px;
}

.child-badge {
    background: #f4f6ff;
    border: 1.8px solid #5f77ff;
    padding: 8px 10px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    transition: 0.25s;
}

.child-badge:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 12px rgba(95, 119, 255, 0.2);
}

.remove-btn {
    border: none;
    background: #ff4d5c;
    color: white;
    width: 22px;
    height: 22px;
    font-size: 0.7rem;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: 0.25s;
}

.remove-btn:hover {
    background: #d92f3e;
    transform: rotate(180deg);
}

/* TWO COLUMN LAYOUT */
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
    cursor: not-allowed;
    opacity: 0.5;
}

.date-card.available {
    opacity: 1;
    cursor: pointer;
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

/* SLOT PANEL */
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

/* TEXTAREA */
.reason-textarea {
    width: 100%;
    border-radius: 10px;
    border: 2px solid #e1e1e1;
    padding: 12px;
    font-size: 0.9rem;
    resize: vertical;
    transition: 0.25s;
}

.reason-textarea:focus {
    border-color: #5f77ff;
    box-shadow: 0 0 0 3px rgba(95,119,255,0.15);
}

/* SUBMIT BUTTON */
.submit-container {
    padding: 20px;
    text-align: center;
}

.submit-btn {
    background: linear-gradient(135deg, #28c76f, #20dda8);
    border: none;
    border-radius: 35px;
    padding: 11px 35px;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    transition: 0.25s;
}

.submit-btn:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(32,221,168,0.3);
}

.submit-btn:disabled {
    background: #c1c1c1;
    cursor: not-allowed;
}

/* SELECTED INFO */
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

/* ======== OVERLAY WITH FAKE % LOADER ========= */

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

.loader-text.success { color: #bfeee0; }

.loader-text {
    margin-top: 12px;
    font-size: 1rem;
    color: white;
    font-weight: 600;
    opacity: 0;
    animation: textFade 0.7s ease forwards 0.3s;
}

@keyframes textFade {
    to { opacity: 1; }
}
</style>

{{-- FLASH MESSAGES --}}
@if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0 pl-3">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="reschedule-container">

    <div class="reschedule-header">
        <h3>Bulk Reschedule PTM</h3>
        <p>Pick a new date and timing for selected children</p>
    </div>

    <form action="{{ route('ptm.resupdateFromStaffBulk', $ptm->id) }}" method="POST" id="rescheduleForm">
        @csrf

        <div class="reschedule-card">

            <!-- CHILDREN -->
            <div class="card-section">
                <div class="section-title"><i class="fa fa-users"></i> Selected Children (<span id="childCount">{{ count($children) }}</span>)</div>

                <div class="children-grid" id="childrenGrid">
                    @foreach ($children as $child)
                        <div class="child-badge">
                            <span>{{ $child->name }}</span>
                            <button type="button" class="remove-btn" onclick="removeChildBadge(this)">
                                <i class="fa fa-times"></i>
                            </button>
                            <input type="hidden" name="child_ids[]" value="{{ $child->id }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- DATE + SLOT -->
            <div class="card-section">
                <div class="section-title"><i class="fa fa-calendar"></i> Select Date & Slot</div>

                <div class="selected-info" id="selectedDateInfo"><strong>Date:</strong> <span id="selectedDateText"></span></div>
                <div class="selected-info" id="selectedSlotInfo"><strong>Slot:</strong> <span id="selectedSlotText"></span></div>

                <div class="ptm-grid">
                    <!-- DATES -->
                    <div class="calendar-container">
                        <div class="calendar-grid">
                            @foreach ($ptm->ptmDates as $d)
                                @php $c = \Carbon\Carbon::parse($d->date); @endphp
                                <div class="date-card available"
                                     onclick="selectDate(this, {{ $d->id }}, '{{ $c->format('l, d M Y') }}')">
                                    <div class="day-name"><i class="fa fa-calendar-alt"></i> {{ $c->format('D') }}</div>
                                    <div class="date-number">{{ $c->format('d') }}</div>
                                    <div class="month-year">{{ $c->format('M Y') }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- SLOTS -->
                    <div class="slots-panel" id="slotsPanel">
                        <div class="slots-panel-title"><i class="fa fa-clock"></i> Time Slots</div>
                        <div class="slots-grid" id="slotsGrid"></div>
                    </div>
                </div>

                <input type="hidden" name="ptmdateid" id="ptmdateid" required>
                <input type="hidden" name="ptmslotid" id="ptmslotid" required>

            </div>

            <!-- REASON -->
            <div class="card-section">
                <div class="section-title"><i class="fa fa-comment-alt"></i> Reason (Optional)</div>
                <textarea name="reason" class="reason-textarea" rows="3" placeholder="Why rescheduling?"></textarea>
            </div>

            <!-- SUBMIT -->
            <div class="submit-container">
                <button type="submit" id="submitBtn" class="submit-btn" disabled>
                    <i class="fa fa-check-circle mr-1"></i> Confirm Reschedule
                </button>
            </div>

        </div>

        <!-- OVERLAY WITH % LOADER -->
        <div class="submit-overlay" id="submitOverlay">
                <div class="progress-wrapper">
                    <div class="progress-circle" id="progressCircle">
                        <div class="progress-percent" id="progressPercent">0%</div>
                        <div class="progress-check" id="progressCheck" style="display:none;">✓</div>
                    </div>
                </div>
        </div>

    </form>
</div>

<script>
/* ------- Date Selection Logic ------- */
const allSlots = @json($ptm->ptmSlots->map(fn($s) => [
    'id' => $s->id,
    'slot' => $s->slot,
    'ptmdate_id' => $s->ptmdate_id
]));

let selectedDateId = null;
let selectedSlotId = null;

function selectDate(el, id, text) {
    document.querySelectorAll(".date-card").forEach(c => c.classList.remove("selected"));
    el.classList.add("selected");

    selectedDateId = id;
    document.getElementById("ptmdateid").value = id;

    document.getElementById("selectedDateText").textContent = text;
    document.getElementById("selectedDateInfo").classList.add("show");

    loadSlots(id);

    selectedSlotId = null;
    document.getElementById("ptmslotid").value = "";
    document.getElementById("selectedSlotInfo").classList.remove("show");

    updateSubmit();
}

function loadSlots(dateId) {
    const grid = document.getElementById("slotsGrid");
    grid.innerHTML = "";

    let slots = allSlots.filter(s => s.ptmdate_id == dateId);

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

    slots.sort((a,b) => getStartMinutes(a.slot) - getStartMinutes(b.slot));

    if (!slots.length) {
        grid.innerHTML = "<p class='text-muted' style='grid-column:1/-1;'>No slots available</p>";
        return;
    }

    slots.forEach(s => {
        const div = document.createElement("div");
        div.className = "slot-item";
        div.innerHTML = `
            <div class="slot-icon"><i class='fa fa-clock'></i></div>
            <div class="slot-time">${s.slot}</div>
        `;
        div.onclick = () => selectSlot(div, s.id, s.slot);
        grid.appendChild(div);
    });
}

function selectSlot(el, id, text) {
    document.querySelectorAll(".slot-item").forEach(s => s.classList.remove("selected"));
    el.classList.add("selected");

    selectedSlotId = id;
    document.getElementById("ptmslotid").value = id;

    document.getElementById("selectedSlotText").textContent = text;
    document.getElementById("selectedSlotInfo").classList.add("show");

    updateSubmit();
}

/* ------- Remove Child ------- */
function removeChildBadge(button) {
    const badge = button.closest('.child-badge');
    if (badge) badge.remove();

    document.getElementById('childCount').textContent =
        document.querySelectorAll('.child-badge').length;

    updateSubmit();
}

function updateSubmit() {
    const childCount = document.querySelectorAll('input[name="child_ids[]"]').length;

    document.getElementById("submitBtn").disabled =
        !(selectedDateId && selectedSlotId && childCount > 0);
}

/* ------- OVERLAY + FAKE % LOADER ------- */
document.getElementById("rescheduleForm").addEventListener("submit", function () {

    const overlay = document.getElementById("submitOverlay");
    const percentText = document.getElementById("progressPercent");
    const circle = document.getElementById("progressCircle");

    overlay.classList.add("show");

    let percent = 0;

    const interval = setInterval(() => {
        // smaller increments and slower tick for a gentler progress feel
        percent += Math.floor(Math.random() * 3) + 1;  // +1% to +3%

        if (percent >= 100) percent = 100;

        percentText.textContent = percent + "%";
        circle.style.background =
            `conic-gradient(#5f77ff ${percent * 3.6}deg, #ffffff33 0deg)`;

        if (percent === 100) clearInterval(interval);

    }, 200);

});
</script>

@stop
