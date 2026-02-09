<div id="left-sidebar" class="sidebar"
    style="background-color: #ffffff;background-image: url('{{ asset('assets/img/doodleold.jpg') }}')">
    <style>
        .dropdown-menu.account.show {
            top: 100% !important;
            left: 0px !important;
        }

        .dropdown-arrow {
            transition: transform 0.3s ease;
        }

        .open>a .dropdown-arrow,
        .active>a .dropdown-arrow {
            transform: rotate(90deg);
        }

        .open>ul {
            display: block;
        }

        #left-sidebar-nav .metismenu>li>a {
            padding: 10px 15px;
            border-radius: 9px;
            transition: all 0.2s ease;
        }

        #left-sidebar-nav .metismenu>li>a:hover {
            background-color: #ffffff;
            color: #000000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        #left-sidebar-nav .metismenu>li>a:hover i {
            color: #000000 !important;
        }

        #left-sidebar-nav .metismenu ul li a {
            margin-left: 9px;
            border-radius: 20px;
            transition: all 0.2s ease;
            margin-top:1px;
        }

        #left-sidebar-nav .metismenu ul li a:hover {
            background-color: #ffffff !important;
            color: #000000 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        #left-sidebar-nav .metismenu ul li a:hover i {
            color: #000000 !important;
        }

        
        #left-sidebar-nav .metismenu>li.active>a,
        #left-sidebar-nav .metismenu>li.open>a {
            background-color: #ffffff !important;
            color: #000000 !important;
            border-radius: 9px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }

        #left-sidebar-nav .metismenu>li.active>a i,
        #left-sidebar-nav .metismenu>li.open>a i {
            color: #000000 !important;
        }

        #left-sidebar-nav .metismenu ul a::before {
            content: '' !important;
        }
    </style>




    <div class="sidebar-scroll">



        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane active" id="menu">
                <nav id="left-sidebar-nav" class="sidebar-nav" style="margin-bottom: 60px;">
                    <ul id="main-menu" class="metismenu">
                        <li class="{{ Request::is('/dashboard') ? 'active' : null }}">
                            <a href="/dashboard" data-toggle="tooltip" data-placement="right"><i class="icon-home"
                                    style="font-size: 25px;"></i>&nbsp;<span style="font-size: 18px; margin-left:-1px">Dashboard</span>
                            </a>

                        </li>

                        @php
                        $isDiaryActive = Route::is('dailyDiary.list') || Route::is('headChecks') ||
                        Route::is('sleepcheck.list') || Route::is('Accidents.list');
                        @endphp

                        <li class="{{ $isDiaryActive ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="d-flex justify-content-between align-items-center"
                                data-toggle="tooltip" data-placement="right">
                                <div>
                                    <i class="fa fa-calendar" style="font-size: 25px;"></i>
                                    <span style="font-size: 18px; margin-left:2px">Daily Journal</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                                @if(
                                in_array(auth()->user()->userType, ['Superadmin', 'Parent']) ||
                                (auth()->user()->userType == 'Staff' && !empty($permissions['viewDailyDiary']) &&
                                $permissions['viewDailyDiary'])
                                )
                                <li class="{{ Route::is('dailyDiary.list') ? 'active' : '' }}">
                                    <a href="{{ route('dailyDiary.list') }}" data-toggle="tooltip"
                                        data-placement="right">Daily Diary</a>
                                </li>
                                @endif

                                @if(auth()->user()->userType != 'Parent')

                                <li class="{{ Route::is('headChecks') ? 'active' : '' }}">
                                    <a href="{{ route('headChecks') }}" data-toggle="tooltip" data-placement="right">Head Checks</a>
                                </li>

                                @endif

                                <li class="{{ Route::is('sleepcheck.list') ? 'active' : '' }}">
                                    <a href="{{ route('sleepcheck.list') }}" data-toggle="tooltip"
                                        data-placement="right">Sleep Check List</a>
                                </li>
                                <li class="{{ Route::is('Accidents.list') ? 'active' : '' }}">
                                    <a href="{{ route('Accidents.list') }}" data-toggle="tooltip"
                                        data-placement="right">Accidents</a>
                                </li>
                            </ul>
                        </li>

                        @if( in_array(auth()->user()->userType, ['Superadmin', 'Parent']) ||
                        (auth()->user()->userType == 'Staff' && !empty($permissions['viewProgramPlan']) &&
                        $permissions['viewProgramPlan']))

                        <li class="{{ Request::is('programPlanList*') ? 'active' : '' }}">
                            <a href="/programPlanList" data-toggle="tooltip" data-placement="right">
                                <i class="far fa-clipboard" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:5px">
                                    Program Plan</span>
                            </a>
                        </li>
                        @endif


                        @if( in_array(auth()->user()->userType, ['Superadmin', 'Parent']) ||
                        (auth()->user()->userType == 'Staff' && !empty($permissions['viewAllReflection']) &&
                        $permissions['viewAllReflection']))

                        <li class="{{ Request::is('reflection*') ? 'active' : null }}">
                            <a href="{{route('reflection.index')}}" data-toggle="tooltip" data-placement="right"><i
                                    class="fa-solid fa-window-restore" style="font-size: 25px;"></i> <span
                                    style="font-size: 18px; margin-left:0px">
                                    Daily Reflections</span></a>
                        </li>
                        @endif

                        @if( in_array(auth()->user()->userType, ['Superadmin', 'Parent']) ||
                        (auth()->user()->userType == 'Staff' && !empty($permissions['viewAllObservation']) &&
                        $permissions['viewAllObservation']))

                        <li class="{{ Request::is('observation*') ? 'active' : null }}">
                            <a href="{{route('observation.index')}}" data-toggle="tooltip" data-placement="right">
                                <i class="icon-equalizer" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:4px">Observation</span></a>
                        </li>
                        @endif

                        {{--  <li class="{{ Request::is('ptm*') ? 'active' : null }}">
                            <a href="{{route('ptm.index')}}" data-toggle="tooltip" data-placement="right">
                                <i class="fas fa-chalkboard-teacher" style="font-size: 21px; "></i><span
                                    style="font-size: 18px; margin-left:4px;">PTM</span></a>
                        </li>  --}}

{{--                          
                        @if(auth()->user()->userType === 'Parent' || auth()->user()->userType === 'Superadmin' || (!empty($permissions['viewMessages']) && $permissions['viewMessages']))
                        <li class="{{ Request::is('messaging*') ? 'active' : '' }}">
                            <a href="/messaging" data-toggle="tooltip" data-placement="right">
                                <i class="fa fa-comments" style="font-size: 25px;"></i>
                                <span style="font-size: 18px; margin-left:-3px">Messages
                                    <span id="sidebar-messages-badge" class="badge bg-danger text-white"
                                        style="display:none;  font-size:.75rem;"></span>
                                </span>
                            </a>
                        </li>
                        @endif  --}}

                        <li class="{{ Request::is('snapshot*') ? 'active' : null }}">
                            <a href="{{route('snapshot.index')}}" data-toggle="tooltip" data-placement="right">
                                <i class="icon-camera" style="font-size: 25px;"></i>
                                <span style="font-size: 18px; margin-left:3px">Snapshots</span>
                            </a>
                        </li>




                        @if( in_array(auth()->user()->userType, ['Superadmin']) ||
                        (auth()->user()->userType == 'Staff' && !empty($permissions['viewAllAnnouncement']) &&
                        $permissions['viewAllAnnouncement']) || auth()->user()->admin == 1)

                        <li class="{{ Request::segment(1) === 'announcements' ? 'active open' : '' }}">
                            <a href="{{ route('announcements.list') }}" data-toggle="tooltip" data-placement="right"> <i
                                    class="fa fa-bullhorn" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:7px">Events</span></a>

                        </li>
                        @endif


                        @if( in_array(auth()->user()->userType, ['Superadmin']) ||
                        (auth()->user()->userType == 'Staff' && !empty($permissions['viewRoom']) &&
                        $permissions['viewRoom']))

                        <li class="{{ Request::is('room*') ? 'active' : null }}">
                            <a href="{{ route('rooms_list') }}" data-toggle="tooltip" data-placement="right"><i
                                    class="fa-solid fa-users-viewfinder" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:1px">Rooms</span></a>

                        </li>
                        @endif
                        @if( in_array(auth()->user()->userType, ['Superadmin']) ||
                        (auth()->user()->userType == 'Staff'))
                        <li class="{{ Request::is('child*') ? 'active' : null }}">
                            <a href="{{ route('childrens_list') }}" data-toggle="tooltip" data-placement="right"><i
                                    class="fa-solid fa-children fa-2x mb-2" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:1px">Children</span></a>

                        </li>
                        @endif

                        @if( in_array(auth()->user()->userType, ['Superadmin']) ||
                        (auth()->user()->userType == 'Staff' && !empty($permissions['viewQip']) &&
                        $permissions['viewQip']))
                        <li class="{{ Request::is('qip*') ? 'active' : null }}">
                            <a href="{{ route('qip.index') }}" data-toggle="tooltip" data-placement="right"><i
                                    class="fa-solid fa-clipboard" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:15px">QIP</span></a>

                        </li>
                        @endif


                        @if( in_array(auth()->user()->userType, ['Superadmin']))
                        <li class="{{ Request::is('enrolment*') ? 'active' : null }}">
                            <a href="{{ route('enrolment.dashboard') }}" data-toggle="tooltip" data-placement="right"><i
                                    class="fa-solid fa-file-lines" style="font-size: 25px;"></i>
                                <span style="font-size: 18px; margin-left:14px">Form</span></a>

                        </li>
                        @endif


                        <li class="{{ Request::is('learningandprogress*') ? 'active' : null }}">
                            <a href="{{ route('learningandprogress.index') }}" data-toggle="tooltip"
                                data-placement="right"><i class="fa-solid fa-chart-simple"
                                    style="font-size: 25px;"></i><span style="font-size: 18px; margin-left:13px">Lesson
                                    Plan</span></a>

                        </li>


                        @php
                        $isHealthyActive = Route::is('healthy_menu') || Route::is('healthy_recipe') ||
                        Route::is('recipes.Ingredients');
                        @endphp


                        <li class="{{ $isHealthyActive ? 'active open' : '' }}">
                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="right"
                                class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-utensils" style="font-size: 25px;"></i> <span
                                        style="font-size: 18px;margin-left:10px">Healthy Eating</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                                <li class="{{ Route::is('healthy_menu') ? 'active' : '' }}">
                                    <a href="{{ route('healthy_menu') }}" data-toggle="tooltip" data-placement="right">
                                        &nbsp; &nbsp;Menu</a>
                                </li>
                                @if(auth()->user()->userType != 'Parent')
                                <li class="{{ Route::is('healthy_recipe') ? 'active' : '' }}">
                                    <a href="{{ route('healthy_recipe') }}" data-toggle="tooltip"
                                        data-placement="right"> &nbsp; &nbsp;Recipe</a>
                                </li>
                                <li class="{{ Route::is('recipes.Ingredients') ? 'active' : '' }}">
                                    <a href="{{ route('recipes.Ingredients') }}" data-toggle="tooltip"
                                        data-placement="right"> &nbsp; &nbsp;Ingredients</a>
                                </li>
                                @endif
                            </ul>
                        </li>



                        @if(auth()->user()->userType != 'Parent')

                        <li class="{{ Request::segment(1) === 'ServiceDetails' ? 'active' : '' }}">
                            <a href="/ServiceDetails" data-toggle="tooltip" data-placement="ight">
                                <i class="fa fa-info-circle" style="font-size: 25px;"></i>
                                <span style="font-size: 18px;margin-left:8px">Service Details</span>
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->userType == 'Superadmin' || auth()->user()->admin == 1)
                        <li class="{{ Request::segment(1) === 'settings' ? 'active open' : null }}">
                            <a href="#settings" data-toggle="tooltip" data-placement="right"
                                class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="icon-settings" style="font-size: 25px;"></i>
                                    <span style="font-size: 18px;margin-left:9px">Settings</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                                @php
                                $userId=Auth::user()->id;
                                @endphp
                                @if($userId == 1)
                                <li class="{{ Request::segment(2) === 'superadmin_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.superadmin_settings') }}" data-toggle="tooltip"
                                        data-placement="right"> &nbsp; &nbsp; &nbsp;Super-Admin Settings</a>
                                </li>
                                @endif
                                <li class="{{ Request::segment(2) === 'ip-list' ? 'active' : null }}">
                                    <a href="{{ route('settings.wifi_add_page') }}" data-toggle="tooltip"
                                        data-placement="right"> &nbsp; &nbsp; &nbsp;IP Manage</a>
                                </li>
                                @php
                                $userType=Auth::user()->userType; @endphp


                                @if((!empty($permissions['viewCenters']) && $permissions['viewCenters']))

                                <li class="{{ Request::segment(2) === 'center_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.center_settings') }}" data-toggle="tooltip"
                                        data-placement="right"> &nbsp; &nbsp; &nbsp;Center Settings</a>
                                </li>
                                @endif

                                <li class="{{ Request::segment(2) === 'staff_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.staff_settings') }}" data-toggle="tooltip"
                                        data-placement="right"> &nbsp; &nbsp; &nbsp;Staffs Settings</a>
                                </li>

                                <li class="{{ Request::segment(2) === 'manage_permissions' ? 'active' : null }}">
                                    <a href="{{ route('settings.manage_permissions') }}" data-toggle="tooltip"
                                        data-placement="right"> &nbsp; &nbsp; &nbsp;Manage Permissions</a>
                                </li>

                                @if(!empty($permissions['viewParent']) && $permissions['viewParent'])

                                <li class="{{ Request::segment(2) === 'parent_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.parent_settings') }}" data-toggle="tooltip"
                                        data-placement="right"> &nbsp; &nbsp; &nbsp;Parents Settings</a>
                                </li>
                                @endif


                                <li class="{{ Request::segment(2) === 'add-public-holiday' ? 'active' : null }}">

                                    <!-- <li class="{{ Request::segment(2) === 'add-public-holiday' ? 'active' : null }}">

                                    <a href="{{ route('settings.public_holiday') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp; &nbsp;Public Holiday</a>
                                </li> -->




                            </ul>
                        </li>
                        @endif

                    </ul>

                </nav>
            </div>


        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.has-arrow').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const parent = this.closest('li');
            parent.classList.toggle('open');
        });
    });
</script>



<script>
    $('.btn-toggle-fullwidth').on('click', function() {
        $('#left-sidebar').toggleClass('minified');
        $(this).find('i').toggleClass('fa-arrow-left fa-arrow-right');
    });
</script>

<script>
    // poll unread count for messages and update sidebar badge
    async function updateUnreadBadge() {
        try {
            const res = await fetch('/messaging/unread-count');
            const data = await res.json();
            if (data && data.success) {
                const count = data.unread || 0;
                const el = document.getElementById('sidebar-messages-badge');
                if (!el) return;
                if (count > 0) {
                    el.style.display = 'inline-block';
                    el.innerText = count;
                } else {
                    el.style.display = 'none';
                    el.innerText = '';
                }
            }
        } catch (e) {
            // ignore
        }
    }
    document.addEventListener('DOMContentLoaded', function(){
        updateUnreadBadge();
        setInterval(updateUnreadBadge, 5000);
    });
</script>