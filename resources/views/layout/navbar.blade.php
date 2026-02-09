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
    /* Theme accent for search button and magnifier */
    .navbar-form.search-form .btn.btn-default {
        border-color: var(--sd-accent, #6c757d);
        color: var(--sd-accent, #6c757d);
    }
    .navbar-form.search-form .btn.btn-default i.icon-magnifier {
        color: var(--sd-accent, #6c757d) !important;
    }
    .navbar-form.search-form .btn.btn-default:hover,
    .navbar-form.search-form .btn.btn-default:focus {
        background: var(--sd-accent, #0dcaf0);
        color: #fff;
        border-color: var(--sd-accent, #0dcaf0);
    }

    .notification-bell {
        background-color: var(--sd-accent, #0dcaf0);
        border-radius: 6px;
        /* square look with slight rounding */
        color: white;
        width: 30px;
        height: 30px;
        display: flex;
        /* flex centers the child */
        align-items: center;
        /* vertical center */
        justify-content: center;
        /* horizontal center */
        position: relative;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        padding: 0;
        margin-bottom: 15px;
        ;
    }

    .notification-bell i {
        font-size: 12px;
        /* fits nicely inside 30×30 */
        color: white !important;
        display: flex;
        /* ensure centering */
        align-items: center;
        justify-content: center;
        position: relative;
        /* make top/bottom work */
        top: -8px;
        /* shift up (adjust as needed) */
        border: 1px solid transparent;
        /* optional, just for demo */
    }

    /* Badge */
    .notification-bell .notification-count {
        position: absolute;
        top: -5px;
        right: -8px;
        background: #f0456dff;
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
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        background-color: var(--sd-accent, #0dcaf0) !important;
        filter: brightness(0.85);
    }



    .btn-toggle-fullwidth i {
        font-family: "Font Awesome 5 Free";
        /* depends on your FA version */
        font-weight: 900;
        /* needed for solid icons in FA5+ */
        content: "\f0c9" !important;
        /* Unicode for fa-bars */
        font-size: 22px !important;
    }

    .btn-toggle-fullwidth i.fa-bars {
        font-size: 22px !important;
    }
</style>
<style>
    /* Hover/interactive effects for search and notification to imitate sidebar highlight */
    .navbar-form.search-form {
        transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease, border-radius .18s ease;
        border-radius: 8px;
        padding: 6px;
        background: transparent;
    }

    /* stronger rounded look on hover */
    .navbar-form.search-form:hover,
    .navbar-form.search-form.navbar-interactive {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        background: rgba(255,255,255,0.94);
        border-radius: 12px; /* increased radius on hover */
    }

    .notification-bell {
        transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease, border-radius .18s ease;
        border-radius: 6px; /* starting radius */
    }

    .notification-bell:hover,
    .notification-bell.navbar-interactive {
        transform: translateY(-2px) scale(1.06);
        box-shadow: 0 12px 32px rgba(0,0,0,0.14);
        background-color: var(--sd-accent);
        filter: brightness(0.92);
        border-radius: 10px; /* slightly rounder on hover */
    }

    /* When hovered, give the left sidebar a subtle highlight so it feels connected, rounded and slightly slides inward */
    #left-sidebar.sidebar-highlight {
        box-shadow: 0 28px 80px rgba(15, 23, 42, 0.14);
        transform: translateX(6px); /* slight slide/widen illusion */
        transition: box-shadow .18s ease, transform .18s ease, border-radius .18s ease;
        border-radius: 12px; /* visible rounded corners */
        overflow: hidden; /* ensures children follow rounded corners */
    }

    /* small tweak for notification dropdown animation */
    .notifications { transition: transform .18s ease, box-shadow .18s ease, border-radius .18s ease; }
    .notifications.navbar-interactive { transform: translateY(6px); box-shadow: 0 8px 30px rgba(0,0,0,0.08); border-radius: 10px; }

        /* Profile link hover (user area) */
        #userDropdown {
            transition: transform .18s ease, box-shadow .18s ease, border-radius .18s ease;
            border-radius: 8px;
            padding: 4px 6px;
        }
        #userDropdown.navbar-interactive,
        #userDropdown:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 28px rgba(0,0,0,0.12);
            border-radius: 12px;
            background: rgba(255,255,255,0.96);
        }
        /* Profile dropdown item hover (options inside the dropdown) */
        #userDropdownMenu .dropdown-item {
            transition: transform .14s ease, box-shadow .14s ease, background-color .14s ease, border-radius .14s ease;
            border-radius: 8px;
            padding: 8px 12px;
            margin: 4px;
        }
        #userDropdownMenu .dropdown-item:hover,
        #userDropdownMenu .dropdown-item.navbar-interactive {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 8px 22px rgba(0,0,0,0.10);
            background: rgba(255,255,255,0.98);
            border-radius: 10px;
        }
        /* Theme accent for icons inside user dropdown options */
        #userDropdownMenu .dropdown-item i {
            color: var(--sd-accent) !important;
        }
</style>
<style>
    .theme-swatch {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
        vertical-align: middle;
    }

    .purple {
        background-color: #6f42c1;
    }

    .blue {
        background-color: #007bff;
    }

    .cyan {
        background-color: #17a2b8;
    }

    .green {
        background-color: #28a745;
    }

    .orange {
        background-color: #fd7e14;
    }

    .blush {
        background-color: #e83e8c;
    }

    .choose-skin li {
        padding: 10px;
        cursor: pointer;
    }

    .choose-skin li:hover {
        background-color: #f1f1f1;
    }

    .choose-skin li.active {
        background-color: #e9ecef;
        font-weight: bold;
    }
</style>

<nav class="navbar navbar-fixed-top" style="background-image: url('{{ asset('assets/img/doodleold.jpg') }}')">
    <div class="container-fluid">
        <div class="navbar-btn">
            <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
        </div>

        <div class="navbar-brand" style="margin-top: -12px;">
            <a href="/dashboard"><img src="{{ asset('assets/img/MYDIAREE-new-logo.png') }}" alt="Lucid Logo"
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


                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu notification-bell"
                            data-toggle="dropdown" title="Notifications">
                            <i class="fa fa-bell"></i>
                            <span class="notification-count">{{ $notifications->count() }}</span>
                        </a>


                        <ul class="dropdown-menu notifications" style="background-color: aliceblue; min-height: 170px; max-height: 400px; overflow: auto; width: 400px;">
                            <li class="mb-2"><strong>You have {{ $notifications->count() }} new Notifications</strong>
                                <a href="{{ route('notifications.markAllRead') }}"
                                    class="d-flex justify-content-between align-items-center"
                                    style="margin-left:71%;color: rgb(67, 133, 204);margin-top:-36px">Mark as all read
                                </a>
                            </li>


                            @forelse($notifications as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}" class="notification-item"
                                data-id="{{ $notification->id }}" 
                                onclick="markAsRead(event, '{{ $notification->id }}')">
                                <div class="media">

                                    <div class="media-left">
                                        <i class="{{ $notification->data['icon'] ?? 'fa fa-bell' }} fa-2x"
                                            style="color: var(--sd-accent, #0dcaf0); font-size:25px;"> </i>
                                    </div>

                                    <div class="media-body">
                                        <h6> &nbsp; {{ $notification->data['title'] ?? 'Notification' }}</h6>
                                        {{--  <p class="text">{{ strip_tags($notification->data['objective']) }}</p>  --}}

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
                                    style="margin-left:63%; margin-top:-5%;" >See all
                                    notifications</a></li>
                        </ul>

                    </li>

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
                                &nbsp; <i class="fa fa-user me-2 text-primary"></i>&nbsp; My Profile
                            </a>
                            <a href="#" class="dropdown-item" id="themeTrigger" onclick="openThemeModal(event)">
                                <i class="fa fa-paint-brush me-2 text-info"></i>&nbsp; &nbsp;Theme Color
                            </a>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                &nbsp;<i class="fa fa-power-off me-2"></i>&nbsp; &nbsp;Logout
                            </a>
                        </div>
                    </li>

                    <!-- Modal for Theme Selection -->
                    <div id="themeModal" class="modal"
                        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
                        <div class="modal-content"
                            style="background-color: #fff; margin-top: 50px; margin-left: auto; margin-right: auto; padding: 20px; width: 300px; border-radius: 5px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                            <h4 style="text-align: center; margin-bottom: 20px;">Select Theme Color</h4>
                            <div class="tab-pane p-l-15 p-r-15" id="setting">

                                <ul class="choose-skin list-unstyled">
    <li data-theme="purple" class="{{ Auth::user()->theme === 'purple' ? 'active' : '' }}">
        <div class="purple"></div>
        <span>Purple</span>
    </li>
    <li data-theme="blue" class="{{ Auth::user()->theme === 'blue' ? 'active' : '' }}">
        <div class="blue"></div>
        <span>Blue</span>
    </li>
    <li data-theme="cyan" class="{{ Auth::user()->theme === 'cyan' ? 'active' : '' }}">
        <div class="cyan"></div>
        <span>Cyan</span>
    </li>
    <li data-theme="green" class="{{ Auth::user()->theme === 'green' ? 'active' : '' }}">
        <div class="green"></div>
        <span>Green</span>
    </li>
    <li data-theme="orange" class="{{ Auth::user()->theme === 'orange' ? 'active' : '' }}">
        <div class="orange"></div>
        <span>Orange</span>
    </li>
    <li data-theme="blush" class="{{ Auth::user()->theme === 'blush' ? 'active' : '' }}">
        <div class="blush"></div>
        <span>Blush</span>
    </li>
    <li data-theme="none" class="{{ Auth::user()->theme === 'none' ? 'active' : '' }}">
        <div class="none"></div>
        <span>No Theme</span>
    </li>
</ul>

                                <hr>
                            </div>
                            <button onclick="closeThemeModal()"
                                style="display: block; margin: 0px auto 0; padding: 10px 20px; background-color: #dc3545; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Close</button>
                        </div>
                    </div>



                </ul>
            </div>




        </div>
    </div>
</nav>


<script>
    $('.btn-toggle-fullwidth').on('click', function(e) {
        e.preventDefault();

        // Toggle sidebar collapse/expand
        $('#left-sidebar').toggleClass('minified');

        // Force the icon inside button to always stay fa-bars
        $(this).find('i')
            .removeClass()
            .addClass('fa fa-bars')
            .css('font-size', '22px !important');

        // Prevent layout-fullwidth class from being added
        $('body').removeClass('layout-fullwidth');
    });


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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const userDropdown = document.getElementById('userDropdown');
    const dropdownMenu = document.getElementById('userDropdownMenu');

    // Toggle dropdown when clicking the profile link
    userDropdown.addEventListener('click', function(e) {
        e.preventDefault();
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Open theme modal
    function openThemeModal(event) {
        event.preventDefault();
        document.getElementById('themeModal').style.display = 'block';
        dropdownMenu.style.display = 'none'; // Close dropdown when opening modal
    }

    // Close theme modal
    function closeThemeModal() {
        document.getElementById('themeModal').style.display = 'none';
    }

    // Close dropdown and modal when clicking outside
    document.addEventListener('click', function(e) {
        if (!userDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
        if (!document.getElementById('themeModal').contains(e.target) && !document.getElementById('themeTrigger').contains(e.target)) {
            closeThemeModal();
        }
    });

    document.querySelectorAll('.choose-skin li').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const theme = this.getAttribute('data-theme'); // e.g. "blue"

        // 🔹 Save to database
        fetch('{{ route("update.theme") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ theme })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // 🔹 Instantly apply new theme to body
                if (theme === 'none') {
                    // Remove all theme classes to show original design
                    document.body.className = document.body.className.replace(/theme-\S+/g, '').trim();
                } else {
                    document.body.className = 'theme-' + theme;
                }
                closeThemeModal();
            }
        });
    });
});

</script>
<script>
    // Attach hover handlers to create a sidebar-like visual effect when hovering search or notification
    (function() {
        try {
            const leftSidebar = document.getElementById('left-sidebar');
            const searchForm = document.getElementById('navbar-search');
            const notifBell = document.querySelector('.notification-bell');
            const notifDropdown = document.querySelector('.notifications');
            const profileLink = document.getElementById('userDropdown');

            function addInteractive(elem) {
                if (!elem) return;
                elem.addEventListener('mouseenter', function() {
                    elem.classList.add('navbar-interactive');
                    if (leftSidebar) leftSidebar.classList.add('sidebar-highlight');
                    if (notifDropdown) notifDropdown.classList.add('navbar-interactive');
                });
                elem.addEventListener('mouseleave', function() {
                    elem.classList.remove('navbar-interactive');
                    if (leftSidebar) leftSidebar.classList.remove('sidebar-highlight');
                    if (notifDropdown) notifDropdown.classList.remove('navbar-interactive');
                });
            }

            addInteractive(searchForm);
            addInteractive(notifBell);
            // also add hover effect for user profile area
            addInteractive(profileLink);
            // add hover handlers for each option in the profile dropdown menu
            const userMenu = document.getElementById('userDropdownMenu');
            if (userMenu) {
                const menuItems = userMenu.querySelectorAll('.dropdown-item');
                menuItems.forEach(item => {
                    item.addEventListener('mouseenter', function() {
                        this.classList.add('navbar-interactive');
                        if (leftSidebar) leftSidebar.classList.add('sidebar-highlight');
                    });
                    item.addEventListener('mouseleave', function() {
                        this.classList.remove('navbar-interactive');
                        if (leftSidebar) leftSidebar.classList.remove('sidebar-highlight');
                    });
                });
            }
        } catch (e) {
            // fail silently to avoid breaking the page if elements are not present
            console.warn('Navbar interactive hover script failed', e);
        }
    })();
</script>