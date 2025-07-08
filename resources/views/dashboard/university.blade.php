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
        margin-left: 110px;
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
                    <a href="#" class="card shadow-sm">
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


@include('layout.footer')
@stop
