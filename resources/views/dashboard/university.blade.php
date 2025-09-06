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
                    <div class="text">Total SuperAdmin</div>
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
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
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
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: green">
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
    document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'title',
            right: 'prev,next today'
        },
        height: 500,
        themeSystem: 'standard',

        dayCellDidMount: function (info) {
            let today = new Date();
            let cellDate = new Date(info.date);

            // ✅ Only today and future dates
            if (cellDate >= new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
                let btn = document.createElement('button');
                btn.innerHTML = '+';
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
                btn.onclick = function (e) {
                    // e.stopPropagation();
      window.location.href = "/announcements/create?centerid={{ session('user_center_id') }}";

                };

                info.el.style.position = 'relative'; // ensure positioning
                info.el.appendChild(btn);
            }
        }
    });

    calendar.render();
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
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
            const isBirthday = info.event.title.includes('🎂');
            const users = info.event.extendedProps.users;
             const isHoliday  = info.event.classNames.includes('holiday-event');
            if (isBirthday && Array.isArray(users)) {
                // Birthday modal
                let html = '';
                users.forEach(user => {
                    html += `
                        <div class="mb-3 border-bottom pb-2">
                            <strong>Name:</strong> ${user.name}  ${user.lastname || ''} <br>

                            <strong>Gender:</strong> ${user.gender || 'N/A'}<br>
                            <strong>DOB:</strong> ${user.dob}
                        </div>`;
                });

                document.getElementById('birthdayModalBody').innerHTML = html;
                new bootstrap.Modal(document.getElementById('birthdayModal')).show();
            }  else if (isHoliday) {
        // 📅 Holiday Modal
                function formatDate(dateStr) {
                const date = new Date(dateStr);
                const day = String(date.getDate()).padStart(2, '0');
                const month = date.toLocaleString('en-US', { month: 'long' }); // Full month name
                const year = date.getFullYear();
                return `${day} ${month} ${year}`;
                }

            let html = `
                <div class="mb-2"><strong>Date:</strong> ${formatDate(info.event.startStr)}</div>
                <div class="mb-2"><strong>State:</strong> ${info.event.extendedProps.state}</div>
                <div class="mb-2"><strong>Occasion:</strong> ${info.event.title.replace('📅 ', '')}</div>
            `;

        document.getElementById('holidayModalBody').innerHTML = html;
        new bootstrap.Modal(document.getElementById('holidayModal')).show();
    }      else {
                // Announcement modal
       const title = info.event.title || 'Announcement';
        const date = info.event.startStr || '';
        const description = info.event.extendedProps.description || 'No description available';

   let media = [];
            const raw = info.event.extendedProps.media;

        let html = `
            <div class="mb-2"><strong>Title:</strong> ${title}</div>
            <div class="mb-2"><strong>Date:</strong> ${date}</div>
            <div class="mb-2"><strong>Description:</strong><br>${description}</div>
        `;

        // Handle media display


          try {
                media = typeof raw === 'string' ? JSON.parse(raw) : raw;
            } catch (e) {
                console.error('Invalid JSON media format', e);
            }

            if (Array.isArray(media)) {
                let html = '';
                media.forEach(file => {
                    const fileUrl = `/assets/media/${file}`;
                    const ext = file.split('.').pop().toLowerCase();

                    if (['jpg', 'jpeg', 'png'].includes(ext)) {
                        html += `<img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow">`;
                    } else if (ext === 'pdf') {
                        html += `<a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2"><i class="fas fa-file-pdf"></i> Download PDF</a>`;
                    }
                });

            }
                document.getElementById('announcementModalBody').innerHTML = html;
                new bootstrap.Modal(document.getElementById('announcementModal')).show();
            }
        }
    });

    // -------------------------------
    // 1. Fetch announcements
    // -------------------------------
    fetch('/announcements/events')
        .then(res => res.json())
        .then(data => {
            if (data.status && Array.isArray(data.events)) {
                const events = data.events.map(item => ({
                    title: item.title || 'No Title',
                    date: item.eventDate,
                    description: item.text || '',
                    media:item.announcementMedia,
                   color: '#17a2b8' ,// Blue for announcements
                    className: 'annoucement-event'
                }));
                calendar.addEventSource(events);
            }
        })
        .catch(err => console.error('Announcement fetch error:', err));

    // -------------------------------
    // 2. Fetch birthdays
    // -------------------------------
    fetch('/users/birthday')
        .then(res => res.json())
        .then(data => {
            if (data.status && Array.isArray(data.events)) {
                const groupedByDate = {};

                data.events.forEach(user => {
                    const dob = new Date(user.dob);
                    const eventDate = `${new Date().getFullYear()}-${String(dob.getMonth() + 1).padStart(2, '0')}-${String(dob.getDate()).padStart(2, '0')}`;

                    if (!groupedByDate[eventDate]) groupedByDate[eventDate] = [];
                    groupedByDate[eventDate].push(user);
                });

                const birthdayEvents = Object.entries(groupedByDate).map(([date, users]) => ({
                    title: '🎂 Birthday',
                    date,
                    allDay: true,
                    color: '#74a5c9', // Red
                    users,
                     className: 'birthday-event'
                }));

                calendar.addEventSource(birthdayEvents);
            }

            calendar.render(); // Final render after all events loaded
        })
        .catch(err => {
            console.error('Birthday fetch error:', err);
            calendar.render();
        });


        fetch('settings/holidays/events')
            .then(res => res.json())
            .then(data => {
                if (data.status && Array.isArray(data.events)) {
                    const holidays = data.events.map(item => ({
                        title: item.title,
                        date: item.date,
                        allDay: true,
                        color: 'green', // 🟢 Green for holidays
                        state: item.state,
                        status: item.status,
                        className: 'holiday-event'
                    }));
                    calendar.addEventSource(holidays);
                }
            })
            .catch(err => console.error('Holiday fetch error:', err));
        });
</script>


@include('layout.footer')
@stop
