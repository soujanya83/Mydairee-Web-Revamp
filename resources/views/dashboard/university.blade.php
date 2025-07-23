@extends('layout.master')
@section('title', 'Dashboard')
{{-- @section('parentPageTitle', 'Dashboard') --}}
<!-- FullCalendar CSS -->

@section('content')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<style>
    #calendar {
        min-height: 350px;
        padding: 10px;
        margin-top: -49px;
    }
</style>
<style>
    .fc .fc-button {
        background-color: #007bff;
        /* Bootstrap primary */
        border-color: #007bff;
        color: #fff;
    }

    .fc .fc-button:hover {
        background-color: #0056b3;
        border-color: #004999;
    }

    .fc .fc-button:disabled {
        background-color: #0e0e0e;
        border-color: #eaeff4;
        color: white
    }

    .fc-toolbar-chunk {
        margin-left: 90px;
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
            <div class="header">
                <h2>Calendar</h2>
            </div>
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
                <div class="col-md-4 mb-4">
                    <a href="{{ route('settings.staff_settings') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-chalkboard-user fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Educators</p>
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
        <div class="card" style="margin-top: -48px;    margin-bottom: 82px;">
            <div class="header" style="    margin-bottom: -28px;">
                <h2>Weather</h2>
            </div>
            <div class="body text-center">
                <object data="https://www.sunsmart.com.au/uvalert/default.asp?version=australia&locationid=161"
                    height="300" width="100%" id="sunsmart">
                    <embed src="https://www.sunsmart.com.au/uvalert/default.asp?version=australia&locationid=161"
                        height="300" width="100%">
                    </embed>
                    Error: Embedded data could not be displayed.
                </object>
            </div>
        </div>

    </div>

</div>


{{-- <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2>University Survey</h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);">Action</a></li>
                            <li><a href="javascript:void(0);">Another Action</a></li>
                            <li><a href="javascript:void(0);">Something else</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row text-center">
                    <div class="col-sm-3 col-6">
                        <h4 class="margin-0">$231</h4>
                        <p class="text-muted margin-0"> Today's</p>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4 class="margin-0">$1,254</h4>
                        <p class="text-muted margin-0">This Week's</p>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4 class="margin-0">$3,298</h4>
                        <p class="text-muted margin-0">This Month's</p>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4 class="margin-0">$9,208</h4>
                        <p class="text-muted margin-0">This Year's</p>
                    </div>
                </div>
                <div id="m_bar_chart" class="graph m-t-20"></div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>New Admission List</h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);">Action</a></li>
                            <li><a href="javascript:void(0);">Another Action</a></li>
                            <li><a href="javascript:void(0);">Something else</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Address</th>
                                <th>Number</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="list-name">OU 00456</span></td>
                                <td>Joseph</td>
                                <td>25</td>
                                <td>70 Bowman St. South Windsor, CT 06074</td>
                                <td>404-447-6013</td>
                                <td><span class="badge badge-primary">MCA</span></td>
                            </tr>
                            <tr>
                                <td><span class="list-name">KU 00789</span></td>
                                <td>Cameron</td>
                                <td>27</td>
                                <td>123 6th St. Melbourne, FL 32904</td>
                                <td>404-447-4569</td>
                                <td><span class="badge badge-warning">Medical</span></td>
                            </tr>
                            <tr>
                                <td><span class="list-name">KU 00987</span></td>
                                <td>Alex</td>
                                <td>23</td>
                                <td>123 6th St. Melbourne, FL 32904</td>
                                <td>404-447-7412</td>
                                <td><span class="badge badge-info">M.COM</span></td>
                            </tr>
                            <tr>
                                <td><span class="list-name">OU 00951</span></td>
                                <td>James</td>
                                <td>23</td>
                                <td>44 Shirley Ave. West Chicago, IL 60185</td>
                                <td>404-447-2589</td>
                                <td><span class="badge badge-default">MBA</span></td>
                            </tr>
                            <tr>
                                <td><span class="list-name">OU 00456</span></td>
                                <td>Joseph</td>
                                <td>25</td>
                                <td>70 Bowman St. South Windsor, CT 06074</td>
                                <td>404-447-6013</td>
                                <td><span class="badge badge-primary">MCA</span></td>
                            </tr>
                            <tr>
                                <td><span class="list-name">OU 00953</span></td>
                                <td>charlie</td>
                                <td>21</td>
                                <td>123 6th St. Melbourne, FL 32904</td>
                                <td>404-447-9632</td>
                                <td><span class="badge badge-success">BBA</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row clearfix">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2>Exam Toppers</h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>First Name</th>
                            <th>Charts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dean Otto</td>
                            <td>
                                <span class="sparkbar">5,8,6,3,-5,9,2</span>
                            </td>
                        </tr>
                        <tr>
                            <td>K. Thornton</td>
                            <td>
                                <span class="sparkbar">10,-8,-9,3,5,8,5</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Kane D.</td>
                            <td>
                                <span class="sparkbar">7,5,9,3,5,2,5</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Jack Bird</td>
                            <td>
                                <span class="sparkbar">10,8,1,-3,-3,-8,7</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Hughe L.</td>
                            <td>
                                <span class="sparkbar">2,8,9,8,5,1,5</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Jack Bird</td>
                            <td>
                                <span class="sparkbar">1,8,2,3,9,8,5</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Hughe L.</td>
                            <td>
                                <span class="sparkbar">10,8,-1,-3,2,8,-5</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2>Timeline</h2>
                <ul class="header-dropdown">
                    <li class="remove">
                        <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="new_timeline">
                    <div class="header">
                        <div class="color-overlay">
                            <div class="day-number">8</div>
                            <div class="date-right">
                                <div class="day-name">Monday</div>
                                <div class="month">February 2018</div>
                            </div>
                        </div>
                    </div>
                    <ul>
                        <li>
                            <div class="bullet pink"></div>
                            <div class="time">11am</div>
                            <div class="desc">
                                <h3>Attendance</h3>
                                <h4>Computer Class</h4>
                            </div>
                        </li>
                        <li>
                            <div class="bullet green"></div>
                            <div class="time">12pm</div>
                            <div class="desc">
                                <h3>Design Team</h3>
                                <h4>Hangouts</h4>
                                <ul class="list-unstyled team-info margin-0 p-t-5">
                                    <li><img src="http://via.placeholder.com/35x35" alt="Avatar"></li>
                                    <li><img src="http://via.placeholder.com/35x35" alt="Avatar"></li>
                                    <li><img src="http://via.placeholder.com/35x35" alt="Avatar"></li>
                                    <li><img src="http://via.placeholder.com/35x35" alt="Avatar"></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="bullet orange"></div>
                            <div class="time">1:30pm</div>
                            <div class="desc">
                                <h3>Lunch Break</h3>
                            </div>
                        </li>
                        <li>
                            <div class="bullet green"></div>
                            <div class="time">2pm</div>
                            <div class="desc">
                                <h3>Finish</h3>
                                <h4>Go to Home</h4>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2>Attendance</h2>
            </div>
            <div class="body">
                <ul class=" list-unstyled basic-list">
                    <li>Mark Otto <span class="badge badge-primary">21%</span></li>
                    <li>Jacob Thornton <span class="badge-purple badge">50%</span></li>
                    <li>Jacob Thornton<span class="badge-success badge">90%</span></li>
                    <li>M. Arthur <span class="badge-info badge">75%</span></li>
                    <li>Jacob Thornton <span class="badge-warning badge">60%</span></li>
                    <li>M. Arthur <span class="badge-success badge">91%</span></li>
                    <li>Jacob Thornton<span class="badge-success badge">90%</span></li>
                    <li>M. Arthur <span class="badge-info badge">75%</span></li>
                </ul>
            </div>
        </div>
    </div>
</div> --}}




<!-- Modal -->
<!-- Birthday Modal -->
<div class="modal fade" id="birthdayModal" tabindex="-1" aria-labelledby="birthdayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="birthdayModalLabel">Birthday Details</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="birthdayModalBody">
        <!-- Populated dynamically -->
      </div>
    </div>
  </div>
</div>
<!-- annoucement modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="announcementModalLabel">Announcement</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="announcementModalBody">
        <!-- Dynamic content -->
      </div>
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
        themeSystem: 'standard', // default; can also try 'bootstrap'
    });

    calendar.render();
});

</script>
<!-- <script>
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
        events: [] // We'll populate this later
    });

    // Fetch announcements and populate calendar
    fetch('/announcements/events')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Fetched announcements:', data);

            if (data.status === true && Array.isArray(data.events)) {
                const events = data.events.map(item => ({
                    title: item.title || 'No Title',
                    date: item.eventDate, // format: 'YYYY-MM-DD'
                    description: item.text || '', // Optional
                }));

                // Add events to calendar
                calendar.addEventSource(events);
            } else {
                console.warn('Unexpected response format:', data);
            }

            calendar.render();
        })
        .catch(error => {
            console.error('Error fetching announcements:', error);
            calendar.render(); // Render calendar anyway
        });


 // Fetch users details and populate calendar
    fetch('/users/birthday')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Fetched announcements:', data);

            if (data.status === true && Array.isArray(data.events)) {
                const events = data.events.map(item => ({
                    title: item.name || 'No Title',
                    // date: item.eventDate, // format: 'YYYY-MM-DD'
                    // description: item.text || '', // Optional
                }));

                // Add events to calendar
                calendar.addEventSource(events);
            } else {
                console.warn('Unexpected response format:', data);
            }

            calendar.render();
        })
        .catch(error => {
            console.error('Error fetching announcements:', error);
            calendar.render(); // Render calendar anyway
        });


});


</script> -->
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
            } else {
                // Announcement modal
       const title = info.event.title || 'Announcement';
        const date = info.event.startStr || '';
        const description = info.event.extendedProps.description || 'No description available';
//           const media = info.event.extendedProps.media;
//           const mediaCount = media.length;
//           if (mediaCount ) {
//             console.log(`Total media files: ${media}`);
//     console.log(`Total media files: ${mediaCount}`);
// }
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
                    color: '#007bff' // Blue for announcements
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
                    color: '#dc3545', // Red
                    users
                }));

                calendar.addEventSource(birthdayEvents);
            }

            calendar.render(); // Final render after all events loaded
        })
        .catch(err => {
            console.error('Birthday fetch error:', err);
            calendar.render();
        });
});
</script>



@include('layout.footer')
@stop
