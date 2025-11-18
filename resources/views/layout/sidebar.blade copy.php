<div id="left-sidebar" class="sidebar"
    style="background-color: #ffffff;background-image: url('{{ asset('assets/img/doodleold.jpg') }}')">
    <style>
        .dropdown-menu.account.show {
            top: 100% !important;
            left: 0px !important;
        }
    </style>
    <style>
        /* Default: right arrow */
        .dropdown-arrow {
            transition: transform 0.3s ease;
        }

        /* When parent li has .open or .active class, rotate arrow down */
        .open>a .dropdown-arrow,
        .active>a .dropdown-arrow {
            transform: rotate(90deg);
        }

        /* Optional: style submenu if needed */
        .open>ul {
            display: block;
        }
    </style>



    <div class="sidebar-scroll" style="    margin-top: 54px;">



        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane active" id="menu">
                <nav id="left-sidebar-nav" class="sidebar-nav" style="margin-bottom: 60px;">
                    <ul id="main-menu" class="metismenu">
                        <li class="{{ Request::is('/dashboard') ? 'active' : null }}">
                            <a href="/dashboard" data-toggle="tooltip" data-placement="right"><i class="icon-home" style="font-size: 25px;"></i>&nbsp;<span
                                    style="font-size: 18px;">Dashboard</span>
                            </a>

                        </li>

                        @php
                        $isDiaryActive = Route::is('dailyDiary.list') || Route::is('headChecks') ||
                        Route::is('sleepcheck.list') || Route::is('Accidents.list');
                        @endphp

                        <li class="{{ $isDiaryActive ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="d-flex justify-content-between align-items-center" data-toggle="tooltip" data-placement="right" >
                                <div>
                                    <i class="fa fa-calendar" style="font-size: 25px;"></i>
                                    <span style="font-size: 18px;">Daily Journal</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                            @if(
    in_array(auth()->user()->userType, ['Superadmin', 'Parent']) ||
    (auth()->user()->userType == 'Staff' && !empty($permissions['viewDailyDiary']) && $permissions['viewDailyDiary'])
)
                                <li class="{{ Route::is('dailyDiary.list') ? 'active' : '' }}">
                                    <a href="{{ route('dailyDiary.list') }}" data-toggle="tooltip" data-placement="right" > &nbsp;Daily Diary</a>
                                </li>
                                @endif

                                @if(auth()->user()->userType != 'Parent')

                                <li class="{{ Route::is('headChecks') ? 'active' : '' }}">
                                    <a href="{{ route('headChecks') }}" data-toggle="tooltip" data-placement="right" >   &nbsp;Head Checks</a>
                                </li>

                                @endif

                                <li class="{{ Route::is('sleepcheck.list') ? 'active' : '' }}">
                                    <a href="{{ route('sleepcheck.list') }}" data-toggle="tooltip" data-placement="right" >  &nbsp;Sleep Check List</a>
                                </li>
                                <li class="{{ Route::is('Accidents.list') ? 'active' : '' }}">
                                    <a href="{{ route('Accidents.list') }}" data-toggle="tooltip" data-placement="right">  &nbsp;Accidents</a>
                                </li>
                            </ul>
                        </li>
                        
                        @if(  in_array(auth()->user()->userType, ['Superadmin', 'Parent']) ||
    (auth()->user()->userType == 'Staff' && !empty($permissions['viewProgramPlan']) && $permissions['viewProgramPlan']))

                        <li class="{{ Request::is('programPlanList*') ? 'active' : '' }}">
                            <a href="/programPlanList" data-toggle="tooltip" data-placement="right">
                                <i class="far fa-clipboard" style="font-size: 25px;"></i><span style="font-size: 18px;">
                                    &nbsp;Program
                                    Plan</span>
                            </a>
                        </li>
                        @endif
                        @if(  in_array(auth()->user()->userType, ['Superadmin', 'Parent']) ||
    (auth()->user()->userType == 'Staff' && !empty($permissions['viewAllReflection']) && $permissions['viewAllReflection']))

                        <li class="{{ Request::is('reflection*') ? 'active' : null }}">
                            <a href="{{route('reflection.index')}}" data-toggle="tooltip" data-placement="right"><i class="fa-solid fa-window-restore"
                                    style="font-size: 25px;"></i> <span style="font-size: 18px;"> Daily
                                    Reflections</span></a>
                        </li>
                        @endif

                        @if(  in_array(auth()->user()->userType, ['Superadmin', 'Parent']) ||
    (auth()->user()->userType == 'Staff' && !empty($permissions['viewAllObservation']) && $permissions['viewAllObservation']))

                        <li class="{{ Request::is('observation*') ? 'active' : null }}">
                            <a href="{{route('observation.index')}}" data-toggle="tooltip" data-placement="right">
                                <i class="icon-equalizer" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:3px">Observation</span></a>
                        </li>
                        @endif

                        <li class="{{ Request::is('ptm*') ? 'active' : null }}">
                            <a href="{{route('ptm.index')}}" data-toggle="tooltip" data-placement="right">
                              <i class="icon-users" style="font-size: 25px; "></i><span
                                    style="font-size: 18px; margin-left:3px">PTM</span></a>
                        </li>
                        <li class="{{ Request::is('snapshot*') ? 'active' : null }}">
                            <a href="{{route('snapshot.index')}}" data-toggle="tooltip" data-placement="right">
                                <i class="icon-camera" style="font-size: 25px;"></i>
                                <span style="font-size: 18px; margin-left:3px">Snapshots</span>
                            </a>
                        </li>




                        @if(  in_array(auth()->user()->userType, ['Superadmin']) ||
    (auth()->user()->userType == 'Staff' && !empty($permissions['viewAllAnnouncement']) && $permissions['viewAllAnnouncement']) || auth()->user()->admin == 1)

                        <li class="{{ Request::segment(1) === 'announcements' ? 'active open' : '' }}">
                            <a href="{{ route('announcements.list') }}" data-toggle="tooltip" data-placement="right"> <i class="fa fa-bullhorn"
                                    style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:-1px">&nbsp; Events</span></a>

                        </li>
                        @endif


                        @if(  in_array(auth()->user()->userType, ['Superadmin']) ||
    (auth()->user()->userType == 'Staff' && !empty($permissions['viewRoom']) && $permissions['viewRoom']))

                        <li class="{{ Request::is('room*') ? 'active' : null }}">
                            <a href="{{ route('rooms_list') }}" data-toggle="tooltip" data-placement="right"><i class="fa-solid fa-users-viewfinder"
                                    style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:1px">Rooms</span></a>

                        </li>
                        @endif
 @if(  in_array(auth()->user()->userType, ['Superadmin']) ||
    (auth()->user()->userType == 'Staff'))
                         <li class="{{ Request::is('child*') ? 'active' : null }}">
                            <a href="{{ route('childrens_list') }}" data-toggle="tooltip" data-placement="right"><i class="fa-solid fa-children fa-2x mb-2"
                                    style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:1px">Children</span></a>

                        </li>
                        @endif

@if(  in_array(auth()->user()->userType, ['Superadmin']) ||
    (auth()->user()->userType == 'Staff' && !empty($permissions['viewQip']) && $permissions['viewQip']))
                        <li class="{{ Request::is('qip*') ? 'active' : null }}">
                            <a href="{{ route('qip.index') }}" data-toggle="tooltip" data-placement="right"><i class="fa-solid fa-clipboard"
                                    style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:12px">QIP</span></a>

                        </li>
                        @endif


                        @if( in_array(auth()->user()->userType, ['Superadmin']))
                        <li class="{{ Request::is('enrolment*') ? 'active' : null }}">
                            <a href="{{ route('enrolment.dashboard') }}" data-toggle="tooltip" data-placement="right"><i class="fa-solid fa-file-lines" style="font-size: 25px;"></i>
                            <span
                                    style="font-size: 18px; margin-left:12px">Form</span></a>

                        </li>
                        @endif


                        <li class="{{ Request::is('learningandprogress*') ? 'active' : null }}">
                            <a href="{{ route('learningandprogress.index') }}" data-toggle="tooltip" data-placement="right"><i class="fa-solid fa-chart-simple"
                                    style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:12px">Lesson Plan</span></a>

                        </li>


                        @php
                        $isHealthyActive = Route::is('healthy_menu') || Route::is('healthy_recipe') ||
                        Route::is('recipes.Ingredients');
                        @endphp


                        <li class="{{ $isHealthyActive ? 'active open' : '' }}">
                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-utensils" style="font-size: 25px;"></i> <span
                                        style="font-size: 18px;margin-left:8px">Healthy Eating</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                                <li class="{{ Route::is('healthy_menu') ? 'active' : '' }}">
                                    <a href="{{ route('healthy_menu') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp;Menu</a>
                                </li>
                                @if(auth()->user()->userType != 'Parent')
                                <li class="{{ Route::is('healthy_recipe') ? 'active' : '' }}">
                                    <a href="{{ route('healthy_recipe') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp;Recipe</a>
                                </li>
                                <li class="{{ Route::is('recipes.Ingredients') ? 'active' : '' }}">
                                    <a href="{{ route('recipes.Ingredients') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp;Ingredients</a>
                                </li>
                                @endif
                            </ul>
                        </li>



                    @if(auth()->user()->userType != 'Parent')

                        <li class="{{ Request::segment(1) === 'ServiceDetails' ? 'active' : '' }}">
                            <a href="/ServiceDetails" data-toggle="tooltip" data-placement="right">
                                <i class="fa fa-info-circle" style="font-size: 25px;"></i>
                                <span style="font-size: 18px;margin-left:6px">Service Details</span>
                            </a>
                        </li>
                    @endif

                        @if(auth()->user()->userType == 'Superadmin' || auth()->user()->admin == 1)
                        <li class="{{ Request::segment(1) === 'settings' ? 'active open' : null }}">
                            <a href="#settings" data-toggle="tooltip" data-placement="right" class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="icon-settings" style="font-size: 25px;"></i>
                                    <span style="font-size: 18px;margin-left:8px">Settings</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                                @php
                                $userId=Auth::user()->id;
                                @endphp
                                @if($userId == 1)
                                <li class="{{ Request::segment(2) === 'superadmin_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.superadmin_settings') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp; &nbsp;Super-Admin Settings</a>
                                </li>
                                @endif
                                <li class="{{ Request::segment(2) === 'ip-list' ? 'active' : null }}">
                                    <a href="{{ route('settings.wifi_add_page') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp; &nbsp;IP Manage</a>
                                </li>
                                @php
                                $userType=Auth::user()->userType; @endphp


                                @if((!empty($permissions['viewCenters']) && $permissions['viewCenters']))

                                <li class="{{ Request::segment(2) === 'center_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.center_settings') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp; &nbsp;Center Settings</a>
                                </li>
                                @endif

                                <li class="{{ Request::segment(2) === 'staff_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.staff_settings') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp; &nbsp;Staffs Settings</a>
                                </li>

                                 <li class="{{ Request::segment(2) === 'manage_permissions' ? 'active' : null }}">
                                    <a href="{{ route('settings.manage_permissions') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp; &nbsp;Manage Permissions</a>
                                </li>

                                @if(!empty($permissions['viewParent']) && $permissions['viewParent'])

                                <li class="{{ Request::segment(2) === 'parent_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.parent_settings') }}" data-toggle="tooltip" data-placement="right"> &nbsp; &nbsp; &nbsp;Parents Settings</a>
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
