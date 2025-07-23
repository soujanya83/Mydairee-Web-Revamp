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
                    @php
                    $notifications = auth()->user()->unreadNotifications;
                    @endphp
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"
                            title="Notifications">
                            <i class="fa fa-bell" style="font-size: 22px;color:rgb(180, 155, 10)"></i>
                            <span style="
                                    display: inline-block; min-width: 20px;height: 20px; padding: 0 6px;font-size: 12px; color: white;
                                    text-align: center;background-color: rgb(180, 155, 10);border-radius: 50%;`line-height: 20px;
                                    margin-left: 0px;
                                ">
                                {{ $notifications->count() }}
                            </span>

                        </a>


                        <ul class="dropdown-menu notifications" style="background-color: aliceblue">
                            <li class="mb-2"><strong>You have {{ $notifications->count() }} new Notifications</strong>
                                <a href="{{ route('notifications.markAllRead') }}"
                                    class="d-flex justify-content-between align-items-center"
                                    style="margin-left:78%;color: rgb(67, 133, 204);margin-top:-36px">Mark all
                                    read</a>
                            </li>


                            @forelse ($notifications as $notification)
                            <li>
                                <a href="{{ $notification->data['url'] ?? '#' }}">
                                    <div class="media">
                                        <div class="media-left">
                                            <i class="{{ $notification->data['icon'] ?? 'fa fa-bell' }} fa-2x"
                                                style="color:green"></i>
                                        </div>
                                        <div class="media-body">
                                            <p class="text">{{ $notification->data['message'] }}</p>
                                            <span class="timestamp">{{ $notification->created_at->diffForHumans()
                                                }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @empty
                            <li>
                                <p>No notifications</p>
                            </li>
                            @endforelse

                            <li class="footer"><a href="{{ route('notifications.all') }}" class="more" style="margin-left:67%;">See all
                                    notifications</a></li>
                        </ul>

                    </li>

                    <li>
                        <a href="{{route('logout')}}" class="icon-menu"><i class="icon-login" title="Logout"
                                style="font-size:20px"></i></a>
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
