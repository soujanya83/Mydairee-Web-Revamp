
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


    .user-account .dropdown-menu {
        animation: fadeInDown 0.3s ease-in-out;
    }

    .dropdown-menu {
        animation: fadeInDown 0.3s ease;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-menu {
        border-radius: 8px;
        font-size: 14px;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-menu .text-muted {
        line-height: 1.4;
    }
</style>
<style>
.notification-bell {
  background-color: #0dcaf0;
  border-radius: 6px;          /* square look with slight rounding */
  color: white;
  width: 30px;
  height: 30px;
  display: flex;               /* flex centers the child */
  align-items: center;         /* vertical center */
  justify-content: center;     /* horizontal center */
  position: relative;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  padding: 0;
  margin-bottom: 15px;;
}

.notification-bell i {
    font-size: 12px;             /* fits nicely inside 30×30 */
    color: white !important;
    display: flex;               /* ensure centering */
    align-items: center;
    justify-content: center;
    position: relative;          /* make top/bottom work */
    top: -8px;                   /* shift up (adjust as needed) */
    border: 1px solid transparent; /* optional, just for demo */
}

/* Badge */
.notification-bell .notification-count {
  position: absolute;
  top: -5px;
  right: -8px;
  background: #0dcaf0;
  color: white;
  font-size: 10px;
  font-weight: bold;
  border-radius: 50%;
  padding: 3px 3px;
  line-height: 1;
  min-width: 14px;
  text-align: center;
}

/* Hover */
.notification-bell:hover {
  transform: scale(1.1);
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}


</style>
<nav class="navbar navbar-fixed-top" style="background-image: url('{{ asset('assets/img/doodleold.jpg') }}')">
    <div class="container-fluid">
        <div class="navbar-btn">
            <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
        </div>

        <div class="navbar-brand" style="margin-top: -12px;">
            <a href="/"><img src="{{ asset('assets/img/MYDIAREE-new-logo.png') }}" alt="Lucid Logo"
                    class="img-responsive logo"></a>
        </div>
        <a class="btn btn-xs btn-link btn-toggle-fullwidth">
            <i class="fa fa-bars" style="font-size: 22px"></i>
        </a>


        <div class="navbar-right">
            <form id="navbar-search" class="navbar-form search-form mt-3">
                <input value="" class="form-control" placeholder="Search here..." type="text" style="width: 360px;">
                <button type="button" class="btn btn-default"><i class="icon-magnifier"></i></button>
            </form>




            <div id="navbar-menu">
                <ul class="nav navbar-nav">

                    {{-- Notifications --}}
                    @php
                    $notifications = auth()->user()->unreadNotifications;
                    @endphp
                    <li class="dropdown" style="margin-right: 35px;margin-top: 0px;">
                        {{-- <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"
                            title="Notifications">
                            <i class="fa fa-bell" style="font-size: 22px;color:rgb(73 201 185)"></i>
                            <span style="
                                    display: inline-block; min-width: 20px;height: 20px; padding: 0 6px;font-size: 12px; color: white;
                                    text-align: center;background-color: rgb(73 201 185);border-radius: 50%;`line-height: 20px;
                                    margin-left: 0px;
                                ">
                                {{ $notifications->count() }}
                            </span>

                        </a> --}}

                    <a href="javascript:void(0);" class="dropdown-toggle icon-menu notification-bell" data-toggle="dropdown" title="Notifications">
    <i class="fa fa-bell"></i>
    <span class="notification-count">{{ $notifications->count() }}</span>
</a>


                        <ul class="dropdown-menu notifications" style="background-color: aliceblue">
                            <li class="mb-2"><strong>You have {{ $notifications->count() }} new Notifications</strong>
                                <a href="{{ route('notifications.markAllRead') }}"
                                    class="d-flex justify-content-between align-items-center"
                                    style="margin-left:78%;color: rgb(67, 133, 204);margin-top:-36px">Mark as all read
                                </a>
                            </li>


                            @forelse($notifications as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}" class="notification-item"
                                data-id="{{ $notification->id }}"
                                onclick="markAsRead(event, '{{ $notification->id }}')">
                                <div class="media">

                                    <div class="media-left">
                                        <i class="{{ $notification->data['icon'] ?? 'fa fa-bell' }} fa-2x"
                                            style="color:green;font-size:25px "> </i>
                                    </div>

                                    <div class="media-body">
                                        <h6> &nbsp; {{ $notification->data['title'] ?? 'Notification' }}</h6>
                                        {{-- <p class="text">{{ strip_tags($notification->data['message']) }}</p> --}}

                                        <span class="float-end text-muted">
                                            &nbsp; {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans()
                                            }}
                                        </span>


                                        @if(!$notification->read_at)
                                        <div class="badge rounded-pill bg-light-danger unread-badge" style="color:red">
                                            Unread</div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <hr>
                            @empty
                            <div class="text-center text-muted p-2">No notifications</div>
                            @endforelse

                            <li class="footer"><a href="{{ route('notifications.all') }}" class="more"
                                    style="margin-left:67%;">See all
                                    notifications</a></li>
                        </ul>

                    </li>

                    {{-- Profile Dropdown --}}


                   <li class="nav-item dropdown mt-2" style="margin-right: 63px; position: relative;">
    <a href="#" class="nav-link d-flex align-items-center p-0" id="userDropdown"
        style="color:black; cursor: pointer;">
        <img src="{{ Auth::user()->imageUrl ? asset(Auth::user()->imageUrl) : asset('storage/assets/img/default.png') }}"
            class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
        <span class="font-weight-bold" style="font-size: 15px;">
            &nbsp;&nbsp;{{ Str::limit(Auth::user()->name, 20) }}
        </span>
    </a>

    <div class="dropdown-menu dropdown-menu-end shadow-sm" id="userDropdownMenu"
        style="min-width: 230px; display: none; position: absolute; top: 100%; right: 0;">
        <div class="px-3 py-2">
            <div class="fw-bold text-truncate" style="font-size: 15px;">
               &nbsp;&nbsp;<i class="fa fa-user me-2"></i> &nbsp;{{ Auth::user()->name }}
            </div>
        </div>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="{{ route('settings.profile') }}">
            <i class="fa fa-user me-2 text-primary"></i>&nbsp; My Profile
        </a>

        <a class="dropdown-item text-danger" href="{{ route('logout') }}">
            <i class="fa fa-power-off me-2"></i>&nbsp; Logout
        </a>
    </div>
</li>

<script>
    const userDropdown = document.getElementById('userDropdown');
    const dropdownMenu = document.getElementById('userDropdownMenu');

    userDropdown.addEventListener('click', function (e) {
        e.preventDefault();
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Optional: close dropdown if clicking outside
    document.addEventListener('click', function (e) {
        if (!userDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });
</script>



                </ul>
            </div>




        </div>
    </div>
</nav>

<script>
    $('.btn-toggle-fullwidth').on('click', function(e) {
        e.preventDefault();

        // Toggle the minified class on sidebar instead of layout-fullwidth on body
        $('#left-sidebar').toggleClass('minified');

        // Toggle the icon
        $(this).find('i').toggleClass('fa-arrow-left fa-arrow-left');

        // Prevent the default layout-fullwidth class from being added to body
        $('body').removeClass('layout-fullwidth');
    });
</script>
<script>
    function markAsRead(event, notificationId) {
        event.preventDefault();

        const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
        const unreadBadge = notificationItem.querySelector(".unread-badge");
        const notificationCountElement = document.getElementById("notification-count");
        const notifBadge = document.getElementById("notif-badge");

        // Remove Unread badge visually
        if (unreadBadge) unreadBadge.remove();

        // Decrease count in notification bell
        if (notificationCountElement && notifBadge) {
            let count = parseInt(notificationCountElement.innerText);
            if (count > 1) {
                notificationCountElement.innerText = count - 1;
                notifBadge.innerText = count - 1;
            } else {
                notificationCountElement.remove();
                notifBadge.remove();
            }
        }

        // Send request to backend to mark as read
        fetch(`/notifications/read/${notificationId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        }).then(response => response.json()).then(data => {
            if (data.success) {
                console.log("Notification marked as read");
            }
        });

        // Redirect after slight delay
        setTimeout(() => {
            window.location.href = notificationItem.getAttribute("href");
        }, 300);
    }
</script>
