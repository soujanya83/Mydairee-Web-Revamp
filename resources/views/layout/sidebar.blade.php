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
        <div class="user-account">

            @php
            $maleAvatars = ['avatar1.jpg', 'avatar5.jpg', 'avatar8.jpg', 'avatar9.jpg', 'avatar10.jpg'];
            $femaleAvatars = ['avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar6.jpg', 'avatar7.jpg'];
            $avatars = Auth::user()->gender === 'FEMALE' ? $femaleAvatars : $maleAvatars;
            $defaultAvatar = $avatars[array_rand($avatars)];
            @endphp
            <img src="{{ Auth::user()->imageUrl ? asset(Auth::user()->imageUrl) : asset('storage/assets/img/default.png') }}"
                class="rounded-circle user-photo" style="vertical-align: bottom; height: 45px;"
                alt="User Profile Picture">

            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>{{
                        Auth::user()->name }}</strong></a>
                <ul class="dropdown-menu dropdown-menu-right account ">
                    <li><a href="{{route('settings.profile')}}"><i class="icon-user"></i>My Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="{{route('logout')}}"><i class="icon-power"></i>Logout</a></li>
                </ul>
            </div>

        </div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#menu">Menu</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting"><i class="icon-settings"></i></a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane active" id="menu">
                <nav id="left-sidebar-nav" class="sidebar-nav" style="margin-bottom: 60px;">
                    <ul id="main-menu" class="metismenu">
                        <li class="{{ Request::is('/') ? 'active' : null }}">
                            <a href="/"><i class="icon-home" style="font-size: 25px;"></i>&nbsp;<span
                                    style="font-size: 18px;">Dashboard</span></a>

                        </li>

                        @php
                        $isDiaryActive = Route::is('dailyDiary.list') || Route::is('headChecks') ||
                        Route::is('sleepcheck.list') || Route::is('Accidents.list');
                        @endphp

                        <li class="{{ $isDiaryActive ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fa fa-calendar" style="font-size: 25px;"></i>
                                    <span style="font-size: 18px;">Daily Journal</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                                <li class="{{ Route::is('dailyDiary.list') ? 'active' : '' }}">
                                    <a href="{{ route('dailyDiary.list') }}">Daily Diary</a>
                                </li>
                                <li class="{{ Route::is('headChecks') ? 'active' : '' }}">
                                    <a href="{{ route('headChecks') }}">Head Checks</a>
                                </li>
                                <li class="{{ Route::is('sleepcheck.list') ? 'active' : '' }}">
                                    <a href="{{ route('sleepcheck.list') }}">Sleep Check List</a>
                                </li>
                                <li class="{{ Route::is('Accidents.list') ? 'active' : '' }}">
                                    <a href="{{ route('Accidents.list') }}">Accidents</a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ Request::is('programPlanList*') ? 'active' : '' }}">
                            <a href="/programPlanList">
                                <i class="far fa-clipboard" style="font-size: 25px;"></i><span style="font-size: 18px;">
                                    &nbsp;Program
                                    Plan</span>
                            </a>
                        </li>

                        <li class="{{ Request::is('reflection*') ? 'active' : null }}">
                            <a href="{{route('reflection.index')}}"><i class="fa-solid fa-window-restore"
                                    style="font-size: 25px;"></i> <span style="font-size: 18px;"> Daily
                                    Reflections</span></a>
                        </li>

                        <li class="{{ Request::is('observation*') ? 'active' : null }}">
                            <a href="{{route('observation.index')}}">
                                <i class="icon-equalizer" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:3px">Observation</span></a>
                        </li>




                        <li class="{{ Request::segment(1) === 'announcements' ? 'active open' : '' }}">
                            <a href="{{ route('announcements.list') }}">  <i class="fa fa-bullhorn" style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:-1px">Announcements</span></a>

                        </li>

                      

                        <li class="{{ Request::is('room*') ? 'active' : null }}">
                            <a href="{{ route('rooms_list') }}"><i class="fa-solid fa-users-viewfinder"
                                    style="font-size: 25px;"></i><span
                                    style="font-size: 18px; margin-left:-1px">Rooms</span></a>

                        </li>

                        @php
                        $isHealthyActive = Route::is('healthy_menu') || Route::is('healthy_recipe') ||
                        Route::is('recipes.Ingredients');
                        @endphp

                        <li class="{{ $isHealthyActive ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-utensils" style="font-size: 25px;"></i> <span
                                        style="font-size: 18px;">Healthy Eating</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                                <li class="{{ Route::is('healthy_menu') ? 'active' : '' }}">
                                    <a href="{{ route('healthy_menu') }}">Menu</a>
                                </li>
                                <li class="{{ Route::is('healthy_recipe') ? 'active' : '' }}">
                                    <a href="{{ route('healthy_recipe') }}">Recipe</a>
                                </li>
                                <li class="{{ Route::is('recipes.Ingredients') ? 'active' : '' }}">
                                    <a href="{{ route('recipes.Ingredients') }}">Ingredients</a>
                                </li>
                            </ul>
                        </li>





                        <li class="{{ Request::segment(1) === 'ServiceDetails' ? 'active' : '' }}">
                            <a href="/ServiceDetails">
                                <i class="fa fa-info-circle" style="font-size: 25px;"></i>
                                <span style="font-size: 18px;">Service Details</span>
                            </a>
                        </li>





                        <!-- daily Journel -->
                        <!-- <li class="{{ Request::segment(1) === 'dailydiary' ? 'active' : null }}">

                                <li class="{{ Request::segment(2) === 'DailyDiary' ? 'active' : null }}"><a
                                        href="{{route('dailyDiary.list')}}">Daily Diary</a> </li>
                                <li class="{{ Request::segment(2) === 'survey' ? 'active' : null }}"><a
                                        href="{{route('headChecks')}}">Head Checks</a></li>
                                         <li class="{{ Request::segment(2) === 'survey' ? 'active' : null }}"><a
                                        href="{{route('sleepcheck.list')}}">Sleep Check List</a></li>
                                         <li class="{{ Request::segment(2) === 'survey' ? 'active' : null }}"><a
                                        href="{{route('Accidents.list')}}">Accidents</a></li>
                            </ul>

                        </li> -->



                        <!-- Daily journel ends -->





                        <li class="{{ Request::segment(1) === 'settings' ? 'active open' : null }}">
                            <a href="#settings" class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="icon-settings" style="font-size: 25px;"></i>
                                    <span style="font-size: 18px;">Settings</span>
                                </div>
                                <i class="fa fa-chevron-right dropdown-arrow"></i>
                            </a>
                            <ul>
                                <li class="{{ Request::segment(2) === 'superadmin_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.superadmin_settings') }}">Super-Admin Settings</a>
                                </li>
                                <li class="{{ Request::segment(2) === 'center_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.center_settings') }}">Center Settings</a>
                                </li>
                                <li class="{{ Request::segment(2) === 'staff_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.staff_settings') }}">Staffs Settings</a>
                                </li>
                                <li class="{{ Request::segment(2) === 'parent_settings' ? 'active' : null }}">
                                    <a href="{{ route('settings.parent_settings') }}">Parents Settings</a>
                                </li>
                                <li class="{{ Request::segment(2) === 'manage_permissions' ? 'active' : null }}">
                                    <a href="{{ route('settings.manage_permissions') }}">Manage Permissions</a>
                                </li>
                            </ul>
                        </li>



                        <!-- <li class="{{ Request::segment(1) === 'healthy_eating' ? 'active' : null }}">
                            <a href="#healthy_eating" class="has-arrow">
                                <i class="fas fa-utensils"></i> <span>Healthy Eating</span>
                            </a>
                    <ul>
                        <li class="{{ Request::segment(2) === 'menu' ? 'active' : null }}"><a
                                href="{{route('healthy_menu')}}">Menu </a></li>
                        <li class="{{ Request::segment(3) === 'recipe' ? 'active' : null }}"><a
                                href="{{route('healthy_recipe')}}">Recipe </a></li>

                        <li class="{{ Request::segment(4) === 'ingredients' ? 'active' : null }}"><a
                                href="{{route('recipes.Ingredients')}}">Ingredients </a></li>
                    </ul>

                    </li> -->







                    </ul>

                </nav>
            </div>



            <div class="tab-pane p-l-15 p-r-15" id="Chat">
                <form>
                    <div class="input-group m-b-20">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-magnifier"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search...">
                    </div>
                </form>
                <ul class="right_chat list-unstyled">
                    <li class="online">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="{{ asset('assets/img/xs/avatar4.jpg') }}" alt="">
                                <div class="media-body">
                                    <span class="name">Chris Fox</span>
                                    <span class="message">Designer, Blogger</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="online">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="{{ asset('assets/img/xs/avatar5.jpg') }}" alt="">
                                <div class="media-body">
                                    <span class="name">Joge Lucky</span>
                                    <span class="message">Java Developer</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="{{ asset('assets/img/xs/avatar2.jpg') }}" alt="">
                                <div class="media-body">
                                    <span class="name">Isabella</span>
                                    <span class="message">CEO, Thememakker</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="{{ asset('assets/img/xs/avatar1.jpg') }}" alt="">
                                <div class="media-body">
                                    <span class="name">Folisise Chosielie</span>
                                    <span class="message">Art director, Movie Cut</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="online">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="{{ asset('assets/img/xs/avatar3.jpg') }}" alt="">
                                <div class="media-body">
                                    <span class="name">Alexander</span>
                                    <span class="message">Writter, Mag Editor</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-pane p-l-15 p-r-15" id="setting">
                <h6>Choose Skin</h6>
                <ul class="choose-skin list-unstyled">
                    <li data-theme="purple">
                        <div class="purple"></div>
                        <span>Purple</span>
                    </li>
                    <li data-theme="blue">
                        <div class="blue"></div>
                        <span>Blue</span>
                    </li>
                    <li data-theme="cyan" class="active">
                        <div class="cyan"></div>
                        <span>Cyan</span>
                    </li>
                    <li data-theme="green">
                        <div class="green"></div>
                        <span>Green</span>
                    </li>
                    <li data-theme="orange">
                        <div class="orange"></div>
                        <span>Orange</span>
                    </li>
                    <li data-theme="blush">
                        <div class="blush"></div>
                        <span>Blush</span>
                    </li>
                </ul>
                <hr>

            </div>
            <div class="tab-pane p-l-15 p-r-15" id="question">
                <form>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-magnifier"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search...">
                    </div>
                </form>
                <ul class="list-unstyled question">
                    <li class="menu-heading">HOW-TO</li>
                    <li><a href="javascript:void(0);">How to Create Campaign</a></li>
                    <li><a href="javascript:void(0);">Boost Your Sales</a></li>
                    <li><a href="javascript:void(0);">Website Analytics</a></li>
                    <li class="menu-heading">ACCOUNT</li>
                    <li><a href="javascript:void(0);">Cearet New Account</a></li>
                    <li><a href="javascript:void(0);">Change Password?</a></li>
                    <li><a href="javascript:void(0);">Privacy &amp; Policy</a></li>
                    <li class="menu-heading">BILLING</li>
                    <li><a href="javascript:void(0);">Payment info</a></li>
                    <li><a href="javascript:void(0);">Auto-Renewal</a></li>
                    <li class="menu-button m-t-30">
                        <a href="javascript:void(0);" class="btn btn-primary"><i class="icon-question"></i> Need
                            Help?</a>
                    </li>
                </ul>
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
