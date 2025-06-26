<div id="left-sidebar" class="sidebar"
    style="background-color: #ffffff;background-image: url('{{ asset('assets/img/doodleold.jpg') }}')">
<style>
.dropdown-menu.account.show {
    top: 100% !important;
    left: 0px !important;
}
</style>
   
    <div class="sidebar-scroll">
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
                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu">
                        <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                            <a href="/"><i class="icon-home"></i> <span>Dashboard</span></a>

                        </li>

                        <li class="{{ Request::is('observation*') ? 'active' : null }}">
                            <a href="{{route('observation.index')}}"><i class="fa-solid fa-gears"></i><span>Observation</span></a>
                        </li>

                        <li class="{{ Request::is('reflection*') ? 'active' : null }}">
                            <a href="{{route('reflection.index')}}"><i class="fa-solid fa-window-restore"></i><span>Daily Reflections</span></a>
                        </li>

                           <li class="{{ Request::segment(1) === 'programPlanList' ? 'active' : '' }}">
                            <a href="/programPlanList">
                                <i class="far fa-clipboard"></i> <span>Program Plan</span>
                            </a>
                        </li>

                        <li class="{{ Request::segment(1) === 'ServiceDetails' ? 'active' : '' }}">
                            <a href="/ServiceDetails">
                                <i class="far fa-clipboard"></i> <span>Service Details</span>
                            </a>
                        </li>


                      

                        <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                            <a href="{{ route('rooms_list') }}"><i class="icon-home"></i> <span>Rooms</span></a>
                                {{-- <li class="{{ Request::segment(2) === 'superadmin_settings' ? 'active' : null }}"><a href="{{route('settings.superadmin_settings')}}">Super-Admin Settings</a> </li>
                                <li class="{{ Request::segment(2) === 'center_settings' ? 'active' : null }}"><a href="{{route('settings.center_settings')}}">Center Settings </a></li>
                                <li class="{{ Request::segment(2) === 'staff_settings' ? 'active' : null }}"><a href="{{route('settings.staff_settings')}}">Staffs Settings </a></li> --}}

                        </li>

                           <li class="{{ Request::segment(1) === 'announcements' ? 'active' : null }}">
                            <a href="#settings" class="has-arrow"><i class="icon-settings"></i>
                                <span>Announcements</span></a>
                            <ul>
                                <li class="{{ Request::segment(2) === 'list' ? 'active' : null }}"><a
                                        href="{{route('announcements.list')}}">Announcements</a> </li>
                                <!-- <li class="{{ Request::segment(2) === 'survey' ? 'active' : null }}"><a
                                        href="{{route('survey.list')}}">Survey </a></li> -->
                            </ul>

                        </li>

                        <!-- daily Journel -->
                            <li class="{{ Request::segment(1) === 'announcements' ? 'active' : null }}">
                            <a href="#settings" class="has-arrow"><i class="icon-settings"></i>
                                <span>Daily Journel</span></a>
                            <ul>
                                <li class="{{ Request::segment(2) === 'DailyDiary' ? 'active' : null }}"><a
                                        href="{{route('dailyDiary.list')}}">Daily Diary</a> </li>
                                <li class="{{ Request::segment(2) === 'survey' ? 'active' : null }}"><a
                                        href="{{route('dailyDiary.list')}}">Head Checks</a></li>
                                         <li class="{{ Request::segment(2) === 'survey' ? 'active' : null }}"><a
                                        href="{{route('dailyDiary.list')}}">Sleep Check List</a></li>
                                         <li class="{{ Request::segment(2) === 'survey' ? 'active' : null }}"><a
                                        href="{{route('dailyDiary.list')}}">Accidents</a></li>
                            </ul>

                        </li>
                         <!-- Daily journel ends -->

                          
                      


                        <li class="{{ Request::segment(1) === 'settings' ? 'active' : null }}">
                            <a href="#settings" class="has-arrow"><i class="icon-settings"></i>
                                <span>Settings</span></a>
                            <ul>
                                <li class="{{ Request::segment(2) === 'superadmin_settings' ? 'active' : null }}"><a
                                        href="{{route('settings.superadmin_settings')}}">Super-Admin Settings</a> </li>
                                <li class="{{ Request::segment(2) === 'center_settings' ? 'active' : null }}"><a
                                        href="{{route('settings.center_settings')}}">Center Settings </a></li>
                                <li class="{{ Request::segment(2) === 'staff_settings' ? 'active' : null }}"><a
                                        href="{{route('settings.staff_settings')}}">Staffs Settings </a></li>
                                <li class="{{ Request::segment(2) === 'parent_settings' ? 'active' : null }}"><a
                                        href="{{route('settings.parent_settings')}}">Parents Settings </a></li>

                            </ul>

                        </li>

                       
                     <li class="{{ Request::segment(1) === 'healthy_eating' ? 'active' : null }}">
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

                    </li>
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
                {{-- <h6>General Settings</h6>
                <ul class="setting-list list-unstyled">
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox">
                            <span>Report Panel Usag</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox" checked>
                            <span>Email Redirect</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox" checked>
                            <span>Notifications</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox">
                            <span>Auto Updates</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox">
                            <span>Offline</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox">
                            <span>Location Permission</span>
                        </label>
                    </li>
                </ul> --}}
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
