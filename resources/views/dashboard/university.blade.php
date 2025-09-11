@extends('layout.master')
@section('title', 'Dashboard')
{{-- @section('parentPageTitle', 'Dashboard') --}}
<!-- FullCalendar CSS -->

@section('content')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">


<style>
    .fc .fc-button {
        background-color: #17a2b8;
        /* Bootstrap primary */
        border-color: #17a2b8;
        color: #fff;
    }

    .fc .fc-button:hover {
        background-color: rgb(76, 170, 185);
        border-color: rgb(81, 161, 174);
    }

    .fc .fc-button:disabled {
        background-color: rgb(103, 100, 100);
        border-color: #eaeff4;
        color: white
    }

    #birthdayModal:hover,
    #announcementModal:hover {
        cursor: pointer !important;
    }

    .birthday-event,
    .annoucement-event {
        cursor: pointer;
    }

    .block-header {
        margin-top: -5px
    }

    .fc-daygrid-event {
        cursor: pointer !important;
        /* Pointer for all events */
    }

    .fc-event.merged-event {
        background: whitesmoke;
        border: none !important;
        box-shadow: none !important;
    }
</style>


<div class="row clearfix" style="margin-top:30px">
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter">
            <div class="body">
                <div class="icon text-info"><i class="fa fa-users"></i> </div>
                <div class="content">
                    <div class="text">Total Users</div>
                    <h5 class="number">{{ $totalUsers }}</h5>
                </div>
                <hr>

                <div class="icon text-danger"><i class="fa fa-users"></i> </div>
                <div class="content">
                    <div class="text">Total Admin</div>
                    <h5 class="number">{{ $totalSuperadmin }}</h5>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter">
            <div class="body">
                <div class="icon text-success"><i class="fa fa-users"></i> </div>
                <div class="content">
                    <div class="text">Total Parents</div>
                    <h5 class="number">{{ $totalParent }}</h5>
                </div>
                <hr>
                <div class="icon text-warning"><i class="fa fa-users"></i> </div>
                <div class="content">
                    <div class="text">Total Staff</div>
                    <h5 class="number">{{ $totalStaff }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter">
            <div class="body">
                <div class="icon text-success"><i class="fa  fa-map-pin"></i> </div>
                <div class="content">
                    <div class="text">Total Centers</div>
                    <h5 class="number">{{ $totalCenter }}</h5>
                </div>
                <hr>
                <div class="icon text-danger"><i class="fa fa-university"></i> </div>
                <div class="content">
                    <div class="text">Total Rooms</div>
                    <h5 class="number">{{ $totalRooms }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter">
            <div class="body">
                <div class="icon"><i class="fa fa-utensils"></i> </div>
                <div class="content">
                    <div class="text">Total Recipes</div>
                    <h5 class="number">{{ $totalRecipes }}</h5>
                </div>
                <hr>
                <div class="icon text-success"><i class="fa fa-smile"></i> </div>
                <div class="content">
                    <div class="text">Happy Clients</div>
                    <h5 class="number">111</h5>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="row clearfix">
    <!-- Calendar Column -->
    <div class="col-md-12 col-lg-6">
        <div class="card">

            <div class="body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Weather Widget Column -->
    <div class="col-md-12 col-lg-6">

        <!-- Shortcut Cards -->
        <div class="icon-cards-row mt-0">
            <div class="row mb-4">
                <!-- Rooms -->
                <div class="col-md-4 mb-4">
                    <a href="{{ route('rooms_list') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-people-roof fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Rooms</p>
                        </div>
                    </a>
                </div>

                <!-- Children -->
                <div class="col-md-4 mb-4">
                    <a href="{{ route('childrens_list') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-children fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Children</p>
                        </div>
                    </a>
                </div>

                <!-- Educators -->
                <div class="col-md-4 mb-4" style="display: none">
                    <a href="{{ route('settings.staff_settings') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-chalkboard-user fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Educators</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 mb-4">
                    <a href="{{ route('announcements.list') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-bullhorn fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Announcements</p>
                        </div>
                    </a>
                </div>
                <!-- Observations -->
                <div class="col-md-4 mb-4" style="margin-top: -26px;">
                    <a href="{{route('observation.index')}}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="icon-equalizer fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Observations</p>
                        </div>
                    </a>
                </div>

                <!-- Daily Reflections -->
                <div class="col-md-4 mb-4" style="margin-top: -26px;">
                    <a href="{{route('reflection.index')}}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-notes-medical fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Daily Reflections</p>
                        </div>
                    </a>
                </div>

                <!-- Daily Diary -->
                <div class="col-md-4 mb-4" style="margin-top: -26px;">
                    <a href="{{ route('dailyDiary.list') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-wallet fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Daily Diary</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Weather Card -->


        <div class="body text-center" style="margin-top: -50px;    margin-bottom: 82px;">
            <object data="https://www.sunsmart.com.au/uvalert/default.asp?version=australia&locationid=161" height="300"
                width="100%" id="sunsmart">
                <embed src="https://www.sunsmart.com.au/uvalert/default.asp?version=australia&locationid=161"
                    height="300" width="100%">
                </embed>
                Error: Embedded data could not be displayed.
            </object>
        </div>


    </div>

</div>

<!-- Birthday Modal -->
<div class="modal" id="birthdayModal" tabindex="-1" aria-labelledby="birthdayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="birthdayModalLabel">Birthday Details</h5>
                <button type="button" class="btn btn-sm btn-light text-danger border-0" style="cursor: pointer;"
                    data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body" id="birthdayModalBody">
                <!-- Populated dynamically -->
            </div>
        </div>
    </div>
</div>
<!-- annoucement modal -->
<div class="modal" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow custom-shadow">
            <div class="modal-header text-white" id="change-bg">
                <h5 class="modal-title" id="announcementModalLabel">Announcement</h5>
                <button type="button" class="btn btn-sm btn-light text-danger border-0" style="cursor: pointer;"
                    data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body" id="announcementModalBody">
                <!-- Dynamic content -->
            </div>
        </div>
    </div>
</div>

<!-- Holiday Modal -->
<div class="modal fade" id="holidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: red">
                <h5 class="modal-title">Holiday Details</h5>
                <button type="button" class="btn btn-sm btn-light text-danger border-0" style="cursor: pointer;"
                    data-dismiss="modal" aria-label="Close">X</button>

            </div>
            <div class="modal-body" id="holidayModalBody"></div>
        </div>
    </div>
</div>




<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
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
                const startOfToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                const cellDate = new Date(info.date.getFullYear(), info.date.getMonth(), info.date.getDate());

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
                        const url = `/announcements/create?centerid=${centerId }&date=${encodeURIComponent(selectedDate)}`;
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


<!-- <script>
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

            eventClick: function(info) {
                const {
                    announcements = [], normalEvents = [], birthdays = [], holidays = []
                } = info.event.extendedProps;

                // ------------------------------
                // 🎂 Birthday Modal
                // ------------------------------
                if (birthdays.length > 0) {
                    let html = '';
                    birthdays.forEach(user => {
                        html += `
                        <div class="mb-3 border-bottom pb-2">
                            <strong>Name:</strong> ${user.name} ${user.lastname || ''}<br>
                            <strong>Gender:</strong> ${user.gender || 'N/A'}<br>
                            <strong>DOB:</strong> ${user.dob}
                        </div>`;
                    });

                    document.getElementById('birthdayModalBody').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('birthdayModal')).show();
                    return;
                }

                // ------------------------------
                // 🏖️ Holiday Modal
                // ------------------------------
                if (holidays.length > 0) {
                    const formatDate = (dateStr) => {
                        const date = new Date(dateStr);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = date.toLocaleString('en-US', {
                            month: 'long'
                        });
                        const year = date.getFullYear();
                        return `${day} ${month} ${year}`;
                    };

                    let html = '';
                    holidays.forEach(h => {
                        html += `
                        <div class="mb-3 border-bottom pb-2">
                            <div><strong>Date:</strong> ${formatDate(h.date)}</div>
                            <div><strong>State:</strong> ${h.state}</div>
                            <div><strong>Occasion:</strong> ${h.occasion || 'Holiday'}</div>
                        </div>`;
                    });

                    document.getElementById('holidayModalBody').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('holidayModal')).show();
                    return;
                }

                // ------------------------------
                // 📢 Announcement / 📅 Event Modal
                // ------------------------------
                const combined = [...announcements, ...normalEvents];
                if (combined.length > 0) {
                    let html = '';
                    combined.forEach(item => {
                        const title = item.title || 'Untitled';
                        const date = item.eventDate || '';
                        const description = item.text || 'No description available';
                        const rawMedia = item.announcementMedia || [];
                        const type = item.type || 'Announcement';
                        const eventColor = item.eventColor || '#1598b3ff';

                        html += `
                        <div class="mb-3 border-bottom pb-2">
                            <div><strong>Title:</strong> ${title}</div>
                            <div><strong>Date:</strong> ${date}</div>
                            <div><strong>Description:</strong><br>${description}</div>`;

                        // Media display
                        let media = [];
                        try {
                            media = typeof rawMedia === 'string' ? JSON.parse(rawMedia) : rawMedia;
                        } catch (e) {
                            console.error('Invalid JSON media format', e);
                        }

                        if (Array.isArray(media)) {
                            media.forEach(file => {
                                const fileUrl = `/assets/media/${file}`;
                                const ext = file.split('.').pop().toLowerCase();

                                if (['jpg', 'jpeg', 'png'].includes(ext)) {
                                    html += `<div><img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow"></div>`;
                                } else if (ext === 'pdf') {
                                    html += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2"><i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                }
                            });
                        }

                        html += `</div>`;

                        // Update modal header + bg
                        $('#announcementModalLabel').html(type.charAt(0).toUpperCase() + type.slice(1));
                        $('#change-bg').css('background-color', eventColor);
                    });

                    document.getElementById('announcementModalBody').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('announcementModal')).show();
                }
            }
        });

        calendar.render();



        // -------------------------------
        // 1. Fetch announcements
        // -------------------------------
        // Utility: group array items by date key
        function groupByDate(events, dateExtractor) {
            const grouped = {};
            events.forEach(item => {
                const date = dateExtractor(item);
                if (!grouped[date]) grouped[date] = [];
                grouped[date].push(item);
            });
            return grouped;
        }

        // -----------------------------------
        // Fetch + Merge All Event Types
        // -----------------------------------
        Promise.all([
            fetch('/announcements/events').then(r => r.json()),
            fetch('/users/birthday').then(r => r.json()),
            fetch('settings/holidays/events').then(r => r.json())
        ]).then(([annData, bdayData, holiData]) => {
            const groupedAll = {};

            // 🔹 Announcements & Events
            if (annData.status && Array.isArray(annData.events)) {
                const byDate = groupByDate(annData.events, i => i.eventDate);
                for (const [date, items] of Object.entries(byDate)) {
                    if (!groupedAll[date]) groupedAll[date] = {
                        announcements: [],
                        normalEvents: [],
                        birthdays: [],
                        holidays: []
                    };
                    groupedAll[date].announcements.push(...items.filter(i => i.type === 'announcement'));
                    groupedAll[date].normalEvents.push(...items.filter(i => i.type === 'events'));
                }
            }

            // 🔹 Birthdays
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
                        holidays: []
                    };
                    groupedAll[date].birthdays.push(...users);
                }
            }

            // 🔹 Holidays
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
                        holidays: []
                    };
                    groupedAll[date].holidays.push(...items);
                }
            }

            // 🔹 Build one compact event per date
            const finalEvents = Object.entries(groupedAll).map(([date, items]) => ({
                title: '', // icons handled in eventContent
                date,
                allDay: true,
                className: 'merged-event',
                extendedProps: items
            }));

            calendar.addEventSource(finalEvents);

            // -----------------------------------
            // Custom renderer: icons + counts
            // -----------------------------------
            calendar.setOption('eventContent', function(arg) {
                if (arg.event.classNames.includes('merged-event')) {
                    const {
                        announcements,
                        normalEvents,
                        birthdays,
                        holidays
                    } = arg.event.extendedProps;

                    // Helper for building icons with badge
                    const makeIcon = (emoji, count, color) => {
                        if (count === 0) return '';
                        return `
                <div style="position:relative; display:inline-block; font-size:18px; margin:2px;">
                    ${emoji}
                    ${count > 1 ? `<span style="
                        position:absolute; top:-8px; right:-10px;
                        background:${color}; color:white;
                        border-radius:50%; padding:2px 5px;
                        font-size:11px; font-weight:bold;
                    ">${count}</span>` : ''}
                </div>
            `;
                    };

                    // ✅ Flexbox with wrapping enabled
                    let html = `
            <div style="
                display:flex; 
                flex-wrap:wrap;   /* allow multiple rows */
                gap:6px; 
                justify-content:center;
                align-items:center;
                max-width:100%;
            ">
        `;
                    html += makeIcon('📢', announcements.length, 'blue'); // announcements
                    html += makeIcon('📅', normalEvents.length, 'green'); // events
                    html += makeIcon('🎂', birthdays.length, '#b1e415ff'); // birthdays
                    html += makeIcon('🏖️', holidays.length, 'red'); // holidays
                    html += `</div>`;

                    return {
                        html
                    };
                }
                return true;
            });


            calendar.render();
        }).catch(err => console.error('Fetch error:', err));


    });
</script> -->

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
                        holidays
                    } = arg.event.extendedProps;

                    // helper with icon + badge + type attribute
                    const makeIcon = (emoji, count, color, type) => {
                        if (count === 0) return '';
                        return `
                        <div class="fc-icon-wrapper" data-type="${type}" style="position:relative; display:inline-block; font-size:16px; margin:2px; cursor:pointer;">
                            ${emoji}
                            ${count > 1 ? `<span style="
                                position:absolute; top:-8px; right:-10px;
                                background:${color}; color:white;
                                border-radius:50%; padding:2px 5px;
                                font-size:11px; font-weight:bold;
                            ">${count}</span>` : ''}
                        </div>
                    `;
                    };

                    return {
                        html: `
                        <div style="display:flex; flex-wrap:wrap; gap:6px; justify-content:center; max-width:100%;">
                            ${makeIcon('📢', announcements.length, 'blue', 'announcement')}
                            ${makeIcon('📅', normalEvents.length, 'green', 'event')}
                            ${makeIcon('🎂', birthdays.length, '#b1e415ff', 'birthday')}
                            ${makeIcon('🏖️', holidays.length, 'red', 'holiday')}
                        </div>
                    `
                    };
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
                                holidays
                            } = info.event.extendedProps;

                            if (type === 'birthday' && birthdays.length > 0) {
                                let html = birthdays.map(user => `
                                <div class="mb-3 border-bottom pb-2">
                                    <strong>Name:</strong> ${user.name} ${user.lastname || ''}<br>
                                    <strong>Gender:</strong> ${user.gender || 'N/A'}<br>
                                    <strong>DOB:</strong> ${user.dob}
                                </div>
                            `).join('');
                                document.getElementById('birthdayModalBody').innerHTML = html;
                                new bootstrap.Modal(document.getElementById('birthdayModal')).show();
                            }

                            if (type === 'holiday' && holidays.length > 0) {
                                const formatDate = (dateStr) => {
                                    const date = new Date(dateStr);
                                    return `${String(date.getDate()).padStart(2, '0')} ${date.toLocaleString('en-US', {month: 'long'})} ${date.getFullYear()}`;
                                };
                                let html = holidays.map(h => `
                                <div class="mb-3 border-bottom pb-2">
                                    <div><strong>Date:</strong> ${formatDate(h.date)}</div>
                                    <div><strong>State:</strong> ${h.state}</div>
                                    <div><strong>Occasion:</strong> ${h.occasion || 'Holiday'}</div>
                                </div>
                            `).join('');
                                document.getElementById('holidayModalBody').innerHTML = html;
                                new bootstrap.Modal(document.getElementById('holidayModal')).show();
                            }

                            if (type === 'announcement' && announcements.length > 0) {
                                let html = announcements.map(item => {
                                    const title = item.title || 'Untitled';
                                    const date = item.eventDate || '';
                                    const description = item.text || 'No description available';
                                    const rawMedia = item.announcementMedia || [];
                                    const eventColor = item.eventColor || '#1598b3ff';

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
                                                mediaHtml += `<div><img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow show-poster"></div>`;
                                            } else if (ext === 'pdf') {
                                                mediaHtml += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2">
                        <i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                            }
                                        });
                                    }

                                    // update header + bg per announcement
                                    $('#announcementModalLabel').html('Announcement');
                                    $('#change-bg').css('background-color', eventColor);

                                    return `
            <div class="mb-3 border-bottom pb-2">
                <div><strong>Title:</strong> ${title}</div>
                <div><strong>Date:</strong> ${date}</div>
                <div><strong>Description:</strong><br>${description}</div>
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
                                    const date = item.eventDate || '';
                                    const description = item.text || 'No description available';
                                    const rawMedia = item.announcementMedia || [];
                                    const eventColor = item.eventColor || '#0d6efd';

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
                                                mediaHtml += `<div><img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow show-poster"></div>`;
                                            } else if (ext === 'pdf') {
                                                mediaHtml += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2">
                    <i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                            }
                                        });
                                    }

                                    // update modal header + bg
                                    $('#announcementModalLabel').html('Event');
                                    $('#change-bg').css('background-color', eventColor);

                                    // 🎨 Wrap each event in a card with colored shadow
                                    return `
        <div class="rounded mb-3" style="box-shadow: 0 0 12px ${eventColor}; border-left: 4px solid ${eventColor};">
            <div class="card-body">
                <div><strong>Title:</strong> ${title}</div>
                <div><strong>Date:</strong> ${date}</div>
                <div><strong>Description:</strong><br>${description}</div>
                ${mediaHtml}
            </div>
        </div>
    `;
                                }).join('');

                                document.getElementById('announcementModalBody').innerHTML = html;
                                new bootstrap.Modal(document.getElementById('announcementModal')).show();
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
            fetch('settings/holidays/events').then(r => r.json())
        ]).then(([annData, bdayData, holiData]) => {
            const groupedAll = {};

            if (annData.status && Array.isArray(annData.events)) {
                const byDate = groupByDate(annData.events, i => i.eventDate);
                for (const [date, items] of Object.entries(byDate)) {
                    if (!groupedAll[date]) groupedAll[date] = {
                        announcements: [],
                        normalEvents: [],
                        birthdays: [],
                        holidays: []
                    };
                    groupedAll[date].announcements.push(...items.filter(i => i.type === 'announcement'));
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
                        holidays: []
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
                        holidays: []
                    };
                    groupedAll[date].holidays.push(...items);
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

@include('layout.footer')
@stop