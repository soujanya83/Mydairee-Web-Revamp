@extends('layout.master')
@section('title', 'Dashboard')
{{-- @section('parentPageTitle', 'Dashboard') --}}
<!-- FullCalendar CSS -->

@section('content')
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    <!-- Use a single, up-to-date Font Awesome stylesheet -->
    <!-- Font Awesome already included earlier in the document -->
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --sd-bg: #f3f4f6;
        --sd-surface: #ffffff;
        --sd-border: #e5e7eb;
        --sd-text: #111827;
        --sd-muted: #6b7280;
        --sd-radius-lg: 14px;
        --sd-radius-md: 10px;
        --sd-shadow-soft: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    /* Theme-specific colors - dynamically applies based on user's theme selection */
    .theme-purple {
        --sd-accent: #a27ce6;
        --sd-accent-soft: #f3e8ff;
    }
    
    .theme-blue {
        --sd-accent: #3eacff;
        --sd-accent-soft: #dbeafe;
    }
    
    .theme-cyan {
        --sd-accent: #49c5b6;
        --sd-accent-soft: #ccfbf1;
    }
    
    .theme-green {
        --sd-accent: #50d38a;
        --sd-accent-soft: #d1fae5;
    }
    
    .theme-orange {
        --sd-accent: #ffce4b;
        --sd-accent-soft: #fef3c7;
    }
    
    .theme-blush {
        --sd-accent: #e47297;
        --sd-accent-soft: #fce7f3;
    }

    /* Base */
    body {
        background: var(--sd-bg);
        color: var(--sd-text);
    }

    /* Wrapper */
    .staff-dashboard {
        max-width: 1250px;
        margin: 20px auto 40px;
        padding: 0 16px;
    }

    /* Header */
    .sd-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 22px;
    }

    .sd-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
    }

    .sd-subtitle {
        margin: 4px 0 0;
        color: var(--sd-muted);
        font-size: 0.9rem;
    }

    /* Stats row */
    .sd-stats-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0,1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .sd-stat-card {
        background: var(--sd-surface);
        border-radius: var(--sd-radius-lg);
        padding: 14px 14px 12px;
        border: 1px solid var(--sd-border);
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 6px;
        box-shadow: var(--sd-shadow-soft);
        transition: background-color 0.15s ease, transform 0.12s ease, box-shadow 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .sd-stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--sd-muted);
    }

    .sd-stat-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--sd-text);
    }

    .sd-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        background: var(--sd-accent-soft, #dbeafe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--sd-accent, #2563eb);
    }

    /* Main grid */
    .sd-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
        gap: 20px;
        align-items: flex-start;
    }

    .sd-right-column {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Panels */
    .sd-panel {
        background: var(--sd-surface);
        border-radius: var(--sd-radius-lg);
        border: 1px solid var(--sd-border);
        box-shadow: var(--sd-shadow-soft);
        display: flex;
        flex-direction: column;
    }

    .sd-panel-header {
        padding: 12px 16px 8px;
        border-bottom: 1px solid var(--sd-border);
    }

    .sd-panel-header h2 {
        margin: 0;
        font-size: 0.96rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--sd-text);
    }

    .sd-panel-header h2 i {
        color: var(--sd-accent, #2563eb);
    }

    .sd-panel-body {
        padding: 14px 16px 16px;
    }

    /* Calendar */
    #calendar {
        background: transparent;
        border-radius: var(--sd-radius-md);
    }

    .fc .fc-button {
        background-color: var(--sd-accent, #2563eb);
        border-color: var(--sd-accent, #2563eb);
        color: #fff;
        border-radius: 6px;
        font-size: 0.8rem;
        padding: 4px 10px;
    }

    .fc .fc-button:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .fc .fc-button:disabled {
        background-color: #e5e7eb;
        border-color: #e5e7eb;
        color: var(--sd-muted);
    }

    .fc-daygrid-event {
        border-radius: 4px;
        padding: 2px 4px;
        font-size: 0.75rem;
        border: none;
        background-color: var(--sd-accent, #2563eb);
        color: #fff;
        cursor: pointer !important;
    }

    .fc-event-main {
        background-color: var(--sd-accent, #2563eb) !important;
    }

    /* Merged event (custom) */
    .fc-event.merged-event {
        background: #e5e7eb;
        color: #374151;
        border: 1px dashed #9ca3af !important;
        box-shadow: none !important;
    }

    /* Legend */
    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 14px;
        padding-top: 10px;
        border-top: 1px solid var(--sd-border);
        font-size: 0.78rem;
        color: var(--sd-muted);
        width: 100%;
        justify-content: space-between;
    }

    .calendar-legend span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 999px;
        border: none;
        color: #fff;
        font-weight: 700;
    }

    /* Legend colors aligned with badge colors */
    .calendar-legend span:nth-child(1) { background: #93a5f6; }
    .calendar-legend span:nth-child(2) { background: #86e191; }
    .calendar-legend span:nth-child(3) { background: rgb(229 119 235); }
    .calendar-legend span:nth-child(4) { background: #e97d4f; }
    .calendar-legend span:nth-child(5) { background: #e09e23; }

    /* Quick access grid */
    .sd-quick-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0,1fr));
        gap: 10px;
    }

    .sd-quick-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 8px;
        border-radius: var(--sd-radius-md);
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--sd-text);
        text-decoration: none;
        transition: background-color 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        text-align: center;
    }

    .sd-quick-link i {
        font-size: 1.2rem;
        color: var(--sd-accent, #2563eb);
    }

    .sd-quick-link:hover {
        background: var(--sd-accent, #2563eb);
        color: #fff;
        border-color: var(--sd-accent, #2563eb);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(37,99,235,0.25);
    }

    .sd-quick-link:hover i {
        color: #fff;
    }

    /* Hover effect for stat cards to match Quick Access behavior */
    .sd-stat-card:hover {
        background: var(--sd-accent, #2563eb);
        color: #fff;
        border-color: var(--sd-accent, #2563eb);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(37,99,235,0.18);
    }

    .sd-stat-card:hover .sd-stat-label,
    .sd-stat-card:hover .sd-stat-value {
        color: #fff;
    }

    .sd-stat-card .sd-stat-icon {
        transition: background-color 0.15s ease, color 0.15s ease, transform 0.12s ease;
    }

    .sd-stat-card:hover .sd-stat-icon {
        background: rgba(255,255,255,0.16);
        color: #fff;
    }

    .sd-stat-card .sd-stat-icon i {
        color: inherit;
    }

    .sd-quick-link-wide {
        grid-column: 1 / -1;
    }

    /* UV widget */
    #sunsmart {
        border-radius: var(--sd-radius-md);
        border: 1px solid var(--sd-border);
        background: #f9fafb;
    }

    /* Modal basics (if you still use birthday/announcement/holiday modals) */
    .modal-content {
        border-radius: var(--sd-radius-lg);
        border: 1px solid var(--sd-border);
        box-shadow: 0 24px 60px rgba(15,23,42,0.45);
    }

    .modal-header {
        border-bottom: 1px solid var(--sd-border);
        background: #f9fafb;
    }

    .modal-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--sd-text) !important; /* ensure readable title color even when HTML has text-white */
    }

    .close {
        font-size: 1.4rem;
        font-weight: 400;
    }

    /* Calendar Modal Base */
    .calendar-modal {
        position: relative;
        overflow: hidden;
    }

    .calendar-modal .modal-header {
        border: none;
        padding: 20px 24px;
        position: relative;
        overflow: hidden;
    }

    .calendar-modal .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        z-index: 1;
    }

    .calendar-modal .modal-title {
        font-weight: 600;
        font-size: 1.2rem;
        position: relative;
        z-index: 2;
        margin: 0;
    }

    .calendar-modal .close {
        font-size: 1.5rem;
        font-weight: 300;
        opacity: 0.8;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        padding: 0 6px;
        border-radius: 4px;
    }

    .calendar-modal .close:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.3);
    }

    /* Header color variants */
    .modal-header.birthday-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%) !important;
    }

    .modal-header.birthday-header .modal-title,
    .modal-header.birthday-header .close {
        color: #fff;
    }

    .modal-header.birthday-header .close {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-header.birthday-header .close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .modal-header.announcement-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .modal-header.announcement-header .modal-title,
    .modal-header.announcement-header .close {
        color: #fff;
    }

    .modal-header.announcement-header .close {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-header.announcement-header .close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .modal-header.holiday-header {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8a80 100%) !important;
    }

    .modal-header.holiday-header .modal-title,
    .modal-header.holiday-header .close {
        color: #fff;
    }

    .modal-header.holiday-header .close {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-header.holiday-header .close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .modal-header.ptm-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    }

    .modal-header.ptm-header .modal-title,
    .modal-header.ptm-header .close {
        color: #fff;
    }

    .modal-header.ptm-header .close {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-header.ptm-header .close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Distinctive Modal Wrappers with Glow Effects */
    .birthday-modal-wrapper,
    .ptm-modal-wrapper,
    .announcement-modal-wrapper,
    .holiday-modal-wrapper,
    .event-modal-wrapper {
        position: relative;
    }

    .birthday-modal-wrapper::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #ff9a9e 100%);
        border-radius: 18px;
        z-index: -1;
        opacity: 0.4;
        filter: blur(8px);
    }

    .announcement-modal-wrapper::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
        border-radius: 18px;
        z-index: -1;
        opacity: 0.4;
        filter: blur(8px);
    }

    .holiday-modal-wrapper::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8a80 50%, #ff6b6b 100%);
        border-radius: 18px;
        z-index: -1;
        opacity: 0.4;
        filter: blur(8px);
    }

    .event-modal-wrapper::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #86e191 0%, #51cf66 50%, #86e191 100%);
        border-radius: 18px;
        z-index: -1;
        opacity: 0.4;
        filter: blur(8px);
    }

    .ptm-modal-header-decoration {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 16px;
        position: relative;
    }

    .ptm-modal-header-decoration i {
        font-size: 1.1rem;
        animation: ptmIconPulse 2s ease-in-out infinite;
    }

    @keyframes ptmIconPulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.15);
        }
    }

    /* Content item styling for all modals */
    .birthday-content-item,
    .ptm-content-item,
    .announcement-content-item,
    .holiday-content-item,
    .event-content-item {
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .birthday-content-item {
        background: linear-gradient(135deg, rgba(255, 154, 158, 0.08) 0%, rgba(254, 207, 239, 0.08) 100%);
        border-left-color: #ff9a9e;
    }

    .ptm-content-item {
        background: linear-gradient(135deg, rgba(79, 172, 254, 0.08) 0%, rgba(0, 242, 254, 0.08) 100%);
        border-left-color: #4facfe;
    }

    .announcement-content-item {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
        border-left-color: #667eea;
    }

    .holiday-content-item {
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.08) 0%, rgba(255, 138, 128, 0.08) 100%);
        border-left-color: #ff6b6b;
    }

    .event-content-item {
        background: linear-gradient(135deg, rgba(134, 225, 145, 0.08) 0%, rgba(81, 207, 102, 0.08) 100%);
        border-left-color: #86e191;
    }

    .ptm-content-item:hover,
    .birthday-content-item:hover,
    .announcement-content-item:hover,
    .holiday-content-item:hover,
    .event-content-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .birthday-content-item:hover {
        background: linear-gradient(135deg, rgba(255, 154, 158, 0.12) 0%, rgba(254, 207, 239, 0.12) 100%);
        box-shadow: 0 4px 12px rgba(255, 154, 158, 0.15);
    }

    .ptm-content-item:hover {
        background: linear-gradient(135deg, rgba(79, 172, 254, 0.12) 0%, rgba(0, 242, 254, 0.12) 100%);
        box-shadow: 0 4px 12px rgba(79, 172, 254, 0.15);
    }

    .announcement-content-item:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.12) 0%, rgba(118, 75, 162, 0.12) 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .holiday-content-item:hover {
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.12) 0%, rgba(255, 138, 128, 0.12) 100%);
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.15);
    }

    .event-content-item:hover {
        background: linear-gradient(135deg, rgba(134, 225, 145, 0.12) 0%, rgba(81, 207, 102, 0.12) 100%);
        box-shadow: 0 4px 12px rgba(134, 225, 145, 0.15);
    }

    .content-label {
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline;
        margin-bottom: 0;
        margin-right: 6px;
        margin-top: 0;
    }

    .birthday-label {
        color: #ff9a9e;
    }

    .ptm-label {
        color: #4facfe;
    }

    .announcement-label {
        color: #667eea;
    }

    .holiday-label {
        color: #ff6b6b;
    }

    .event-label {
        color: #86e191;
    }

    .content-value {
        color: #2d3748;
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 8px;
        word-break: break-word;
        word-wrap: break-word;
        display: inline;
    }

    .ptm-value {
        color: #2d3748;
    }

    .birthday-event,
    .annoucement-event,
    .fc-daygrid-event {
        cursor: pointer !important;
    }

    .fa-solid, .fas {
    font-weight: 900;
    margin-right: 8px;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .sd-stats-row {
            grid-template-columns: repeat(2, minmax(0,1fr));
        }
        .sd-main-grid {
            grid-template-columns: minmax(0,1fr);
        }
    }

    @media (max-width: 640px) {
        .sd-stats-row {
            grid-template-columns: minmax(0,1fr);
        }
        .sd-quick-grid {
            grid-template-columns: repeat(2, minmax(0,1fr));
        }
    }
</style>

<div class="staff-dashboard">

    <!-- Stats row -->
    <div class="sd-stats-row">
        <div class="sd-stat-card">
            <div class="sd-stat-label">Total Users</div>
            <div class="sd-stat-value">{{ $totalUsers }}</div>
            <div class="sd-stat-icon"><i class="fa fa-users"></i></div>
        </div>

        <div class="sd-stat-card">
            <div class="sd-stat-label">Admins</div>
            <div class="sd-stat-value">{{ $totalSuperadmin }}</div>
            <div class="sd-stat-icon"><i class="fa fa-user-shield"></i></div>
        </div>

        <div class="sd-stat-card">
            <div class="sd-stat-label">Parents</div>
            <div class="sd-stat-value">{{ $totalParent }}</div>
            <div class="sd-stat-icon"><i class="fa fa-user"></i></div>
        </div>

        <div class="sd-stat-card">
            <div class="sd-stat-label">Staff</div>
            <div class="sd-stat-value">{{ $totalStaff }}</div>
            <div class="sd-stat-icon"><i class="fa fa-chalkboard-teacher"></i></div>
        </div>
    </div>

    <!-- Additional stats (added so all cards show) -->
    <div class="sd-stats-row">
        <div class="sd-stat-card">
            <div class="sd-stat-label">Total Centers</div>
            <div class="sd-stat-value">{{ $totalCenters ?? $totalCenter ?? 0 }}</div>
            <div class="sd-stat-icon"><i class="fa fa-building"></i></div>
        </div>

        <div class="sd-stat-card">
            <div class="sd-stat-label">Rooms</div>
            <div class="sd-stat-value">{{ $totalRooms ?? 0 }}</div>
            <div class="sd-stat-icon"><i class="fa fa-door-open"></i></div>
        </div>

        <div class="sd-stat-card">
            <div class="sd-stat-label">Recipes</div>
            <div class="sd-stat-value">{{ $totalRecipes ?? 0 }}</div>
            <div class="sd-stat-icon"><i class="fa fa-utensils"></i></div>
        </div>

        <div class="sd-stat-card">
            <div class="sd-stat-label">Happy Clients</div>
            <div class="sd-stat-value">{{ $happyClients ?? 0 }}</div>
            <div class="sd-stat-icon"><i class="fa fa-smile"></i></div>
        </div>
    </div>

    <!-- Main 2-column layout -->
    <div class="sd-main-grid">
        <!-- Left: Calendar -->
        <div class="sd-panel sd-panel-calendar">
            
            <div class="sd-panel-body">
                <div id="calendar"></div>

                <div class="calendar-legend">
                    <span><i class="fas fa-bullhorn"></i> Announcement</span>
                    <span><i class="fas fa-calendar-alt"></i> Event</span>
                    <span><i class="fas fa-birthday-cake"></i> Birthday</span>
                    <span><i class="fas fa-umbrella-beach"></i> Holiday</span>
                    <span><i class="fas fa-chalkboard-teacher"></i> PTM</span>
                </div>
            </div>
        </div>

        <!-- Right: Quick links + UV widget -->
        <div class="sd-right-column">

            <div class="sd-panel">
                
                <div class="sd-panel-body">
                    <div class="sd-quick-grid">
                        <a href="{{ route('rooms_list') }}" class="sd-quick-link">
                            <i class="fa-solid fa-people-roof"></i>
                            <span>Rooms</span>
                        </a>

                        <a href="{{ route('childrens_list') }}" class="sd-quick-link">
                            <i class="fa-solid fa-children"></i>
                            <span>Children</span>
                        </a>

                        <a href="{{ route('announcements.list') }}" class="sd-quick-link">
                            <i class="fa-solid fa-bullhorn"></i>
                            <span>Announcements</span>
                        </a>

                        <a href="{{ route('observation.index') }}" class="sd-quick-link">
                            <i class="icon-equalizer"></i>
                            <span>Observations</span>
                        </a>

                        <a href="{{ route('reflection.index') }}" class="sd-quick-link">
                            <i class="fa-solid fa-notes-medical"></i>
                            <span>Reflections</span>
                        </a>

                        <a href="{{ route('dailyDiary.list') }}" class="sd-quick-link">
                            <i class="fa-solid fa-wallet"></i>
                            <span>Daily Diary</span>
                        </a>

                        <a href="{{ route('ptm.index') }}" class="sd-quick-link sd-quick-link-wide">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>PTM</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="sd-panel">
                
                <div class="sd-panel-body">
                    <object data="https://www.sunsmart.com.au/uvalert/default.asp?version=australia&locationid=161"
                            height="260" width="100%" id="sunsmart">
                        <embed src="https://www.sunsmart.com.au/uvalert/default.asp?version=australia&locationid=161"
                               height="260" width="100%">
                        </embed>
                        Error: Embedded data could not be displayed.
                    </object>
                </div>
            </div>

        </div>
    </div>

</div>

    <!-- 🎂 Birthday Modal -->
    <div class="particles" id="particles"></div>
    <div class="modal fade" id="birthdayModal" tabindex="-1" aria-labelledby="birthdayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal birthday-modal-wrapper shadow-lg border-0 rounded-3">
                <!-- Confetti overlay -->
                <div class="confetti-overlay" id="confettiContainer"></div>

                <div class="modal-header birthday-header">
                    <h5 class="modal-title" id="birthdayModalLabel">
                        <i class="fas fa-birthday-cake"></i>
                        Birthday Celebration
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="birthdayModalBody">
                    <!-- Dynamic content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- 📢 Announcement Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal announcement-modal-wrapper shadow-lg border-0 rounded-3">
                <div class="modal-header announcement-header">
                    <h5 class="modal-title d-flex align-items-center" id="announcementModalLabel">
                        <i class="fas fa-bullhorn announcement-icon"></i>
                        Important Announcement
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="announcementModalBody">
                    <div class="announcement-content">
                        <h6 style="color: #667eea; font-weight: 600; margin-bottom: 1rem;">
                            <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                            Latest Updates
                        </h6>
                        <p style="margin-bottom: 0.5rem;">We're excited to share some important news with you!</p>
                        <ul style="margin-bottom: 0;">
                            <li>New features have been added to enhance your experience</li>
                            <li>System maintenance scheduled for optimal performance</li>
                            <li>Thank you for your continued support and feedback</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🎌 Holiday Modal -->
    <div class="modal fade" id="holidayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal holiday-modal-wrapper shadow-lg border-0 rounded-3">
                <div class="modal-header holiday-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="fas fa-calendar-day holiday-icon me-2"></i>
                          Holiday Celebration
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="holidayModalBody">
                    <div class="holiday-content">
                        <h6 style="font-weight: 600; margin-bottom: 1rem;">
                            <i class="fas fa-star" style="color: #ff6b6b; margin-right: 0.5rem;"></i>
                            Special Holiday Details
                        </h6>
                        <p style="margin-bottom: 1rem;">
                            Join us in celebrating this wonderful occasion! Here are the details for the upcoming holiday:
                        </p>
                        <div style="background: rgba(255, 255, 255, 0.5); padding: 1rem; border-radius: 8px;">
                            <strong>Date:</strong> Coming Soon<br>
                            <strong>Activities:</strong> Fun celebrations and special events<br>
                            <strong>Duration:</strong> All day festivities
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🧑‍🏫 PTM Modal -->
    <div class="modal fade" id="ptmModal" tabindex="-1" aria-labelledby="ptmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal ptm-modal-wrapper shadow-lg border-0 rounded-3">
                <div class="modal-header ptm-header">
                    <h5 class="modal-title" id="ptmModalLabel">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Parent Teacher Meeting
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="ptmModalBody"></div>
            </div>
        </div>
    </div>


    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script>
        // Add some interactive effects
        $(document).ready(function() {
            // Add fade-in effect when modals are shown
            $('.modal').on('show.bs.modal', function(e) {
                $(this).find('.modal-content').addClass('animated');
            });

            // Add sparkle click effect
            $('.sparkle').on('click', function() {
                $(this).css('animation', 'none');
                setTimeout(() => {
                    $(this).css('animation', 'sparkle 1.5s ease-in-out infinite');
                }, 100);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const centerId = @json(Session('user_center_id'));

            const formatYMD = (d) => {
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${y}-${m}-${day}`; // avoids timezone issues from toISOString()
            };

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'title',
                    right: 'prev,next today'
                },
                height: 500,
                themeSystem: 'standard',

                dayCellDidMount: function(info) {
                    const today = new Date();
                    const startOfToday = new Date(today.getFullYear(), today.getMonth(), today
                        .getDate());
                    const cellDate = new Date(info.date.getFullYear(), info.date.getMonth(), info.date
                        .getDate());

                    // ✅ Only today and future dates
                    if (cellDate >= startOfToday) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = '+';
                        btn.className = 'add-announcement-btn';
                        btn.setAttribute('data-toggle', 'tooltip');
                        btn.setAttribute('title', 'Add Announcement');
                        btn.style.cssText = `
          position:absolute;
          top:-8px;
          left:2px;
          font-size:16px;
          border:none;
          border-radius:50%;
          color:green;
          cursor:pointer;
        `;

                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation(); // prevent calendar’s own click handling
                            const selectedDate = formatYMD(info.date); // ← use the cell's date
                            const url =
                                `/announcements/create?centerid=${centerId }&date=${encodeURIComponent(selectedDate)}`;
                            window.location.assign(url);
                        });

                        info.el.style.position = 'relative'; // ensure positioning
                        info.el.appendChild(btn);
                    }
                }
            });

            calendar.render();
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'title',
                    right: 'prev,next today'
                },
                height: 500,
                themeSystem: 'standard',

                eventContent: function(arg) {
                    if (arg.event.classNames.includes('merged-event')) {
                        const {
                            announcements,
                            normalEvents,
                            birthdays,
                            holidays,
                            ptms
                        } = arg.event.extendedProps;

                        // create icon element with badge
                        const makeIconEl = (iconClasses, count, color, type) => {
                            if (!count || count === 0) return null;
                            const wrapper = document.createElement('div');
                            wrapper.className = 'fc-icon-wrapper';
                            wrapper.dataset.type = type;
                            wrapper.style.position = 'relative';
                            wrapper.style.display = 'inline-block';
                            wrapper.style.fontSize = '16px';
                            wrapper.style.margin = '2px';
                            wrapper.style.cursor = 'pointer';
                            wrapper.style.color = '#ffffff';

                            const icon = document.createElement('i');
                            icon.className = iconClasses; 
                            wrapper.appendChild(icon);

                            if (count > 0) {
                                const badge = document.createElement('span');
                                badge.textContent = String(count);
                                badge.style.position = 'absolute';
                                badge.style.top = '-8px';
                                badge.style.right = '-10px';
                                badge.style.background = color;
                                badge.style.color = 'white';
                                badge.style.borderRadius = '50%';
                                badge.style.padding = '2px 6px';
                                badge.style.fontSize = '9px';
                                badge.style.fontWeight = '700';
                                wrapper.appendChild(badge);
                            }

                            return wrapper;
                        };

                        const container = document.createElement('div');
                        container.style.display = 'flex';
                        container.style.flexWrap = 'wrap';
                        container.style.gap = '6px';
                        container.style.justifyContent = 'center';
                        container.style.maxWidth = '100%';

                        const items = [
                            makeIconEl('fa-solid fa-bullhorn', announcements.length, '#93a5f6ff', 'announcement'),
                            makeIconEl('fa-solid fa-calendar-days', normalEvents.length, '#86e191ff', 'event'),
                            makeIconEl('fa-solid fa-birthday-cake', birthdays.length, 'rgb(229 119 235)', 'birthday'),
                            makeIconEl('fa-solid fa-umbrella-beach', holidays.length, '#e97d4fff', 'holiday'),
                            makeIconEl('fa-solid fa-chalkboard-teacher', ptms.length, '#e09e23', 'ptm')
                        ];

                        items.forEach(it => {
                            if (it) container.appendChild(it);
                        });

                        return { domNodes: [container] };
                    }
                    return true;
                },

                eventDidMount: function(info) {
                    if (info.event.classNames.includes('merged-event')) {
                        // Attach click handler for each icon separately
                        info.el.querySelectorAll('.fc-icon-wrapper').forEach(iconEl => {
                            iconEl.addEventListener('click', (e) => {
                                e.stopPropagation(); // prevent full event click
                                const type = iconEl.dataset.type;
                                const {
                                    announcements,
                                    normalEvents,
                                    birthdays,
                                    holidays,
                                    ptms
                                } = info.event.extendedProps;

                                if (type === 'birthday' && birthdays.length > 0) {
                                    const user = birthdays[0];
                                    const fullName = `${user.name} ${user.lastname || ''}`.trim();
                                    const dob = new Date(user.dob);
                                    const today = new Date();
                                    let age = today.getFullYear() - dob.getFullYear();
                                    
                                    // Check if birthday hasn't occurred yet this year
                                    if (today.getMonth() < dob.getMonth() || 
                                        (today.getMonth() === dob.getMonth() && today.getDate() < dob.getDate())) {
                                        age--;
                                    }
                                    
                                    const dobFormatted = `${String(dob.getDate()).padStart(2, '0')} ${dob.toLocaleString('en-US', {month: 'long'})} ${dob.getFullYear()}`;
                                    const gender = user.gender || 'Not specified';
                                    
                                    let html = `
                                        <div class="birthday-content-item">
                                            <p class="content-label birthday-label">👤 Name:</p>
                                            <p class="content-value">${fullName}</p>
                                            <br>
                                            <p class="content-label birthday-label">🎂 Age:</p>
                                            <p class="content-value">${age} Years Old</p>
                                            <br>
                                            <p class="content-label birthday-label">📅 Date of Birth:</p>
                                            <p class="content-value">${dobFormatted}</p>
                                            <br>
                                            <p class="content-label birthday-label">👶 Gender:</p>
                                            <p class="content-value">${gender}</p>
                                        </div>
                                        
                                    `;
                                    
                                    document.getElementById('birthdayModalBody').innerHTML = html;
                                    new bootstrap.Modal(document.getElementById('birthdayModal')).show();
                                    setTimeout(() => {
                                        createConfetti();
                                    }, 500);
                                }

                                if (type === 'holiday' && holidays.length > 0) {
                                    const formatDate = (dateStr) => {
                                        const date = new Date(dateStr);
                                        return `${String(date.getDate()).padStart(2, '0')} ${date.toLocaleString('en-US', {month: 'long'})} ${date.getFullYear()}`;
                                    };
                                    let html = holidays.map(h => `
                                        <div class="holiday-content-item">
                                            <p class="content-label holiday-label">Date:</p>
                                            <p class="content-value">${formatDate(h.date)}</p>
                                            <br>
                                            <p class="content-label holiday-label">State:</p>
                                            <p class="content-value">${h.state || 'N/A'}</p>
                                            <br>
                                            <p class="content-label holiday-label">Occasion:</p>
                                            <p class="content-value">${h.occasion || 'Holiday'}</p>
                                        </div>
                                    `).join('');
                                    document.getElementById('holidayModalBody').innerHTML = html;
                                    new bootstrap.Modal(document.getElementById('holidayModal')).show();
                                }



                                if (type === 'announcement' && announcements.length > 0) {
                                    let html = announcements.map(item => {
                                        const title = item.title || 'Untitled';
                                        const date = item.eventDate || 'N/A';
                                        const description = item.text || 'No description available';
                                        const rawMedia = item.announcementMedia || [];

                                        let mediaHtml = '';
                                        let media = [];
                                        try {
                                            media = typeof rawMedia === 'string' ? JSON.parse(rawMedia) : rawMedia;
                                        } catch {}
                                        if (Array.isArray(media)) {
                                            media.forEach(file => {
                                                const fileUrl = file;
                                                const ext = file.split('.').pop().toLowerCase();
                                                if (['jpg', 'jpeg', 'png'].includes(ext)) {
                                                    mediaHtml += `<div><img src="${fileUrl}" style="max-width:200px; border-radius: 8px;" class="img-fluid mb-2 shadow show-poster"></div>`;
                                                } else if (ext === 'pdf') {
                                                    mediaHtml += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2"><i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                                }
                                            });
                                        }

                                        return `
                                            <div class="announcement-content-item">
                                                <p class="content-label announcement-label">Title:</p>
                                                <p class="content-value">${title}</p>
                                                <br>
                                                <p class="content-label announcement-label">Date:</p>
                                                <p class="content-value">${date}</p>
                                                <br>
                                                <p class="content-label announcement-label">Description:</p>
                                                <p class="content-value">${description}</p>
                                                ${mediaHtml}
                                            </div>
                                        `;
                                    }).join('');

                                    document.getElementById('announcementModalBody').innerHTML = html;
                                    new bootstrap.Modal(document.getElementById('announcementModal')).show();
                                }

                                if (type === 'event' && normalEvents.length > 0) {
                                    let html = normalEvents.map(item => {
                                        const title = item.title || 'Untitled';
                                        const date = item.eventDate || 'N/A';
                                        const description = item.text || 'No description available';
                                        const rawMedia = item.announcementMedia || [];

                                        let mediaHtml = '';
                                        let media = [];
                                        try {
                                            media = typeof rawMedia === 'string' ? JSON.parse(rawMedia) : rawMedia;
                                        } catch {}
                                        if (Array.isArray(media)) {
                                            media.forEach(file => {
                                                const fileUrl = file;
                                                const ext = file.split('.').pop().toLowerCase();
                                                if (['jpg', 'jpeg', 'png'].includes(ext)) {
                                                    mediaHtml += `<div><img src="${fileUrl}" style="max-width:200px; border-radius: 8px;" class="img-fluid mb-2 shadow show-poster"></div>`;
                                                } else if (ext === 'pdf') {
                                                    mediaHtml += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2"><i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                                }
                                            });
                                        }

                                        return `
                                            <div class="event-content-item">
                                                <p class="content-label event-label">Title:</p>
                                                <p class="content-value">${title}</p>
                                                <br>
                                                <p class="content-label event-label">Date:</p>
                                                <p class="content-value">${date}</p>
                                                <br>
                                                <p class="content-label event-label">Description:</p>
                                                <p class="content-value">${description}</p>
                                                ${mediaHtml}
                                            </div>
                                        `;
                                    }).join('');

                                    document.getElementById('announcementModalBody').innerHTML = html;
                                    new bootstrap.Modal(document.getElementById('announcementModal')).show();
                                }

                                if (type === 'ptm' && ptms.length > 0) {
                                    const formatDate = (dateStr) => {
                                        const date = new Date(dateStr);
                                        const day = String(date.getDate()).padStart(2, '0');
                                        const month = date.toLocaleString('en-US', { month: 'short' });
                                        const year = date.getFullYear();
                                        return `${day} ${month} ${year}`;
                                    };

                                    let html = ptms.map(p => {
                                        const title = p.title || 'Parent Teacher Meeting';
                                        const date = p.date || p.ptmdate;
                                        const slot = p.slot || p.ptmslot || '';
                                        const objective = p.objective || 'N/A';
                                        const dateLine = date ? `${formatDate(date)}${slot ? ` (${slot})` : ''}` : 'N/A';
                                        return `
                <div class="ptm-content-item">
                    <span class="ptm-label">Title : </span> <span class="ptm-value"> ${title}</span><br>
                    <span class="ptm-label">Scheduled : </span><span class="ptm-value"> ${dateLine}</span><br>
                    <span class="ptm-label">Objective : </span><span class="ptm-value"> ${objective}</span>
                </div>
            `;
                                    }).join('');

                                    document.getElementById('ptmModalBody').innerHTML = html;
                                    new bootstrap.Modal(document.getElementById('ptmModal')).show();
                                }

                            });
                        });
                    }
                }
            });




            calendar.render();

            // ------------------------------- Data Fetching -------------------------------
            function groupByDate(events, dateExtractor) {
                const grouped = {};
                events.forEach(item => {
                    const date = dateExtractor(item);
                    if (!grouped[date]) grouped[date] = [];
                    grouped[date].push(item);
                });
                return grouped;
            }

            Promise.all([
                fetch('/announcements/events').then(r => r.json()),
                fetch('/users/birthday').then(r => r.json()),
                fetch('settings/holidays/events').then(r => r.json()),
                fetch('/ptm/events').then(r => r.json())
            ]).then(([annData, bdayData, holiData, ptmData]) => {
                const groupedAll = {};

                if (annData.status && Array.isArray(annData.events)) {
                    const byDate = groupByDate(annData.events, i => i.eventDate);
                    for (const [date, items] of Object.entries(byDate)) {
                        if (!groupedAll[date]) groupedAll[date] = {
                            announcements: [],
                            normalEvents: [],
                            birthdays: [],
                            holidays: [],
                            ptms: []
                        };
                        groupedAll[date].announcements.push(...items.filter(i => i.type ===
                            'announcement'));
                        groupedAll[date].normalEvents.push(...items.filter(i => i.type === 'events'));
                    }
                }

                if (bdayData.status && Array.isArray(bdayData.events)) {
                    const byDate = groupByDate(bdayData.events, user => {
                        const dob = new Date(user.dob);
                        return `${new Date().getFullYear()}-${String(dob.getMonth() + 1).padStart(2, '0')}-${String(dob.getDate()).padStart(2, '0')}`;
                    });
                    for (const [date, users] of Object.entries(byDate)) {
                        if (!groupedAll[date]) groupedAll[date] = {
                            announcements: [],
                            normalEvents: [],
                            birthdays: [],
                            holidays: [],
                            ptms: []
                        };
                        groupedAll[date].birthdays.push(...users);
                    }
                }

                if (holiData.status && Array.isArray(holiData.events)) {
                    const byDate = groupByDate(holiData.events, item => {
                        const d = new Date(item.date);
                        return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
                    });
                    for (const [date, items] of Object.entries(byDate)) {
                        if (!groupedAll[date]) groupedAll[date] = {
                            announcements: [],
                            normalEvents: [],
                            birthdays: [],
                            holidays: [],
                            ptms: []
                        };
                        groupedAll[date].holidays.push(...items);
                    }
                }
                
                if (ptmData.status && Array.isArray(ptmData.events)) {
                    const byDate = groupByDate(ptmData.events, i => i.ptmdate);
                    for (const [date, items] of Object.entries(byDate)) {
                        if (!groupedAll[date]) groupedAll[date] = {
                            announcements: [],
                            normalEvents: [],
                            birthdays: [],
                            holidays: [],
                            ptms: []
                        };
                        groupedAll[date].ptms.push(...items);
                    }
                }

                const finalEvents = Object.entries(groupedAll).map(([date, items]) => ({
                    title: '',
                    date,
                    allDay: true,
                    className: 'merged-event',
                    extendedProps: items
                }));

                calendar.addEventSource(finalEvents);
            }).catch(err => console.error('Fetch error:', err));



            $(document).on('click', '.show-poster', function() {
                const url = $(this).attr('src'); // get clicked image src
                // Open in a new tab/page
                window.open(url, '_blank');
            });



        });
    </script>
    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Create confetti effect
        function createConfetti() {
            const container = document.getElementById('confettiContainer');
            const confettiCount = 50;
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                container.appendChild(confetti);
                // Remove after animation
                setTimeout(() => {
                    if (confetti.parentNode) {
                        confetti.parentNode.removeChild(confetti);
                    }
                }, 5000);
            }
        }

        // Sparkle click effect
        function createSparkleEffect(element) {
            const sparkles = ['✨', '⭐', '🌟', '💫'];
            const sparkle = document.createElement('span');
            sparkle.innerHTML = sparkles[Math.floor(Math.random() * sparkles.length)];
            sparkle.style.position = 'absolute';
            sparkle.style.fontSize = '1.5rem';
            sparkle.style.pointerEvents = 'none';
            sparkle.style.left = Math.random() * 50 + 25 + '%';
            sparkle.style.top = Math.random() * 50 + 25 + '%';
            sparkle.style.animation = 'sparkleRotate 1s ease-out forwards';

            element.parentNode.appendChild(sparkle);

            setTimeout(() => {
                if (sparkle.parentNode) {
                    sparkle.parentNode.removeChild(sparkle);
                }
            }, 1000);
        }

        // Celebration button effect
        function triggerCelebration() {
            createConfetti();
            // Add shake effect to modal
            $('.modal-content').addClass('animated');
            setTimeout(() => {
                $('.modal-content').removeClass('animated');
            }, 600);
        }

        // Fireworks effect
        function launchFireworks() {
            createConfetti();
            // Create multiple confetti bursts
            setTimeout(() => createConfetti(), 500);
            setTimeout(() => createConfetti(), 1000);
            // Change age counter text temporarily
            const counter = document.getElementById('ageCounter');
            const originalText = counter.innerHTML;
            counter.innerHTML = '🎉 CELEBRATING! 🎉';
            setTimeout(() => {
                counter.innerHTML = originalText;
            }, 3000);
        }

        // Initialize effects
        $(document).ready(function() {
            createParticles();

            // Trigger confetti when modal opens
            $('#birthdayModal').on('shown.bs.modal', function() {
                setTimeout(() => {
                    createConfetti();
                }, 500);
            });

            // Clear confetti when modal closes
            $('#birthdayModal').on('hidden.bs.modal', function() {
                const container = document.getElementById('confettiContainer');
                container.innerHTML = '';
            });

            // Add hover sound effect simulation
            $('.sparkle').hover(
                function() {
                    $(this).css('transform', 'scale(1.3)');
                },
                function() {
                    $(this).css('transform', 'scale(1)');
                }
            );
        });
    </script>

    @include('layout.footer')
@stop
