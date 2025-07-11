<style>
    .navbar-fixed-top .navbar-brand img {
        width: 130px;
    }

    .top_counter {
        background-image: url('{{ asset('assets/img/doodle1.png') }}');
        background-size: cover;
        background-position: center;
    }

    .card {
        background-image: url('{{ asset('assets/img/doodle1.png') }}');
        background-size: cover;
        background-position: center;
    }

    /* .theme-cyan {
        background-image: url('{{ asset('assets/img/doodleold.jpg') }}');
        background-size: cover;
        background-position: center;
    } */
</style>
<nav class="navbar navbar-fixed-top" style="background-image: url('{{ asset('assets/img/doodleold.jpg') }}')">
    <div class="container-fluid">
        <div class="navbar-btn">
            <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
        </div>

        <div class="navbar-brand" style="margin-top: -12px;">
            <a href="{{route('dashboard.analytical')}}"><img src="{{ asset('assets/img/MYDIAREE-new-logo.png') }}"
                    alt="Lucid Logo" class="img-responsive logo"></a>
        </div>
        <a class="btn btn-xs btn-link btn-toggle-fullwidth">
            <i class="fa fa-bars" style="font-size: 22px"></i>
        </a>

        <div class="navbar-right">
            <form id="navbar-search" class="navbar-form search-form">
                <input value="" class="form-control" placeholder="Search here..." type="text">
                <button type="button" class="btn btn-default"><i class="icon-magnifier"></i></button>
            </form>

            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    {{-- <li class="d-none d-sm-inline-block d-md-none d-lg-inline-block">
                        <a href="{{route('file-manager.dashboard')}}" class="icon-menu"><i
                                class="fa fa-folder-open-o"></i></a>
                    </li>
                    <li class="d-none d-sm-inline-block d-md-none d-lg-inline-block">
                        <a href="{{route('app.calendar')}}" class="icon-menu"><i class="icon-calendar"></i></a>
                    </li>
                    <li class="d-none d-sm-inline-block">
                        <a href="{{route('app.chat')}}" class="icon-menu"><i class="icon-bubbles"></i></a>
                    </li>
                    <li class="d-none d-sm-inline-block">
                        <a href="{{route('app.inbox')}}" class="icon-menu"><i class="icon-envelope"></i><span
                                class="notification-dot"></span></a>
                    </li> --}}
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"
                            title="Notifications">
                            <i class="icon-bell"></i>
                            <span class="notification-dot"></span>
                        </a>
                        <ul class="dropdown-menu notifications">
                            <li class="header"><strong>You have 4 new Notifications</strong></li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="media">
                                        <div class="media-left">
                                            <i class="icon-info text-warning"></i>
                                        </div>
                                        <div class="media-body">
                                            <p class="text">Campaign <strong>Holiday Sale</strong> is nearly reach
                                                budget limit.</p>
                                            <span class="timestamp">10:00 AM Today</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="media">
                                        <div class="media-left">
                                            <i class="icon-like text-success"></i>
                                        </div>
                                        <div class="media-body">
                                            <p class="text">Your New Campaign <strong>Holiday Sale</strong> is approved.
                                            </p>
                                            <span class="timestamp">11:30 AM Today</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="media">
                                        <div class="media-left">
                                            <i class="icon-pie-chart text-info"></i>
                                        </div>
                                        <div class="media-body">
                                            <p class="text">Website visits from Twitter is 27% higher than last week.
                                            </p>
                                            <span class="timestamp">04:00 PM Today</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="media">
                                        <div class="media-left">
                                            <i class="icon-info text-danger"></i>
                                        </div>
                                        <div class="media-body">
                                            <p class="text">Error on website analytics configurations</p>
                                            <span class="timestamp">Yesterday</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="footer"><a href="javascript:void(0);" class="more">See all notifications</a></li>
                        </ul>
                    </li>
                    {{-- <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i
                                class="icon-equalizer"></i></a>
                        <ul class="dropdown-menu user-menu menu-icon">
                            <li class="menu-heading">ACCOUNT SETTINGS</li>
                            <li><a href="javascript:void(0);"><i class="icon-note"></i> <span>Basic</span></a></li>
                            <li><a href="javascript:void(0);"><i class="icon-equalizer"></i>
                                    <span>Preferences</span></a></li>
                            <li><a href="javascript:void(0);"><i class="icon-lock"></i> <span>Privacy</span></a></li>
                            <li><a href="javascript:void(0);"><i class="icon-bell"></i> <span>Notifications</span></a>
                            </li>
                            <li class="menu-heading">BILLING</li>
                            <li><a href="javascript:void(0);"><i class="icon-credit-card"></i> <span>Payments</span></a>
                            </li>
                            <li><a href="javascript:void(0);"><i class="icon-printer"></i> <span>Invoices</span></a>
                            </li>
                            <li><a href="javascript:void(0);"><i class="icon-refresh"></i> <span>Renewals</span></a>
                            </li>
                        </ul>
                    </li> --}}
                    <li>
                        <a href="{{route('logout')}}" class="icon-menu"><i class="icon-login" title="Logout"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
    $('.btn-toggle-fullwidth').on('click', function() {
                $(this).find('i').toggleClass('fa-arrow-left fa-arrow-right');
            });
</script>
