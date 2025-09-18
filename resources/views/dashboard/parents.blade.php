@extends('layout.master')
@section('title', 'Dashboard')
{{-- @section('parentPageTitle', 'Dashboard') --}}
<!-- FullCalendar CSS -->

@section('content')
<!-- FullCalendar CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet"> -->


<!-- <style>
    .fc .fc-button {
        background-color: #17a2b8;
        /* Bootstrap primary */
        border-color: #17a2b8;
        color: #fff;
    }

    .fc .fc-button:hover {
        background-color: rgb(76, 170, 185);
        border-color: rgb(81, 161, 174);
    }

    .fc .fc-button:disabled {
        background-color: rgb(103, 100, 100);
        border-color: #eaeff4;
        color: white
    }
</style> -->

<!-- css starts -->
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    .fc .fc-button {
        background-color: #17a2b8;
        /* Bootstrap primary */
        border-color: #17a2b8;
        color: #fff;
    }

    .fc .fc-button:hover {
        background-color: rgb(76, 170, 185);
        border-color: rgb(81, 161, 174);
    }

    .fc .fc-button:disabled {
        background-color: rgb(103, 100, 100);
        border-color: #eaeff4;
        color: white
    }

    #birthdayModal:hover,
    #announcementModal:hover {
        cursor: pointer !important;
    }

    .birthday-event,
    .annoucement-event {
        cursor: pointer;
    }

    .block-header {
        margin-top: -5px
    }

    .fc-daygrid-event {
        cursor: pointer !important;
        /* Pointer for all events */
    }

    .fc-event.merged-event {
        background: whitesmoke;
        border: none !important;
        box-shadow: none !important;
    }

    /* calendar legend  */
    .calendar-legend {
        display: flex;
        justify-content: start;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 10px;
        padding: 10px;
        border-top: 1px solid #ddd;
        font-size: 0.9rem;
    }

    .calendar-legend span i {
        margin-right: 6px;
    }
</style>
<style>
    /* body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        } */

    /* Enhanced Modal Styles */
    .modal-content {
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        animation: modalSlideIn 0.4s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        border: none;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        z-index: 1;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.3rem;
        position: relative;
        z-index: 2;
        margin: 0;
    }

    .modal-body {
        padding: 2rem;
        background: #ffffff;
    }

    .close {
        font-size: 1.5rem;
        font-weight: 300;
        opacity: 0.8;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .close:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Birthday Modal Specific Styles */
    .birthday-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #eb9dd2ff 50%, #fecfef 100%);
        animation: gradientShift 3s ease-in-out infinite alternate;
    }

    @keyframes gradientShift {
        0% {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
        }

        100% {
            background: linear-gradient(135deg, #f4d9b3ff 0%, #fcb69f 50%, #ff9a9e 100%);
        }
    }

    .birthday-gif {
        max-width: 200px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        margin-bottom: 1.5rem;
        animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }

    .birthday-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        margin-top: 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .birthday-message {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .birthday-wishes {
        font-size: 0.95rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    /* Announcement Modal Styles */
    .announcement-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
    }

    .announcement-icon {
        font-size: 1.2rem;
        margin-right: 0.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .announcement-content {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid #667eea;
        margin-top: 1rem;
    }

    /* Holiday Modal Styles */
    .holiday-header {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        position: relative;
    }

    .holiday-icon {
        font-size: 1.2rem;
        margin-right: 0.5rem;
        animation: swing 2s ease-in-out infinite;
    }

    @keyframes swing {

        0%,
        100% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(10deg);
        }

        75% {
            transform: rotate(-10deg);
        }
    }

    .holiday-content {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: #333;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1rem;
    }

    /* Button Styles */
    .btn-demo {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 500;
        margin: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-demo:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .demo-container {
        text-align: center;
        padding: 3rem 0;
    }

    .demo-title {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .demo-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    /* Sparkle Animation */
    .sparkle {
        display: inline-block;
        animation: sparkle 1.5s ease-in-out infinite;
    }

    @keyframes sparkle {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.7;
            transform: scale(1.2);
        }
    }
</style>

<style>
    .particle {
        position: fixed;
        width: 6px;
        height: 6px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        pointer-events: none;
        z-index: -1;
        animation: float 15s infinite linear;
    }

    @keyframes float {
        0% {
            transform: translateY(100vh) rotate(0deg);
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            transform: translateY(-10vh) rotate(360deg);
            opacity: 0;
        }
    }

    /* Enhanced Modal Styles */
    .modal-content {
        border: none;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        overflow: hidden;
        animation: modalSlideIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        position: relative;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-100px) scale(0.8);
            opacity: 0;
        }

        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    /* Confetti overlay */
    .confetti-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 10;
        overflow: hidden;
    }

    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #ff6b6b;
        animation: confetti-fall 3s infinite linear;
    }

    .confetti:nth-child(2n) {
        background: #4ecdc4;
    }

    .confetti:nth-child(3n) {
        background: #45b7d1;
    }

    .confetti:nth-child(4n) {
        background: #f9ca24;
    }

    .confetti:nth-child(5n) {
        background: #6c5ce7;
    }

    @keyframes confetti-fall {
        0% {
            transform: translateY(-100px) rotateZ(0deg);
            opacity: 1;
        }

        100% {
            transform: translateY(500px) rotateZ(720deg);
            opacity: 0;
        }
    }

    /* Header Styles */
    .birthday-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
        position: relative;
        padding: 2rem;
        overflow: hidden;
    }

    .birthday-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: headerGlow 4s ease-in-out infinite alternate;
    }

    @keyframes headerGlow {
        0% {
            transform: scale(1) rotate(0deg);
        }

        100% {
            transform: scale(1.1) rotate(180deg);
        }
    }

    .modal-title {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 1.8rem;
        position: relative;
        z-index: 2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Enhanced cake animation */
    .birthday-gif {
        max-width: 180px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        margin-bottom: 1.5rem;
        animation: cakeFloat 3s ease-in-out infinite;
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .birthday-gif:hover {
        transform: scale(1.1) rotate(5deg);
    }

    @keyframes cakeFloat {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        25% {
            transform: translateY(-8px) rotate(1deg);
        }

        50% {
            transform: translateY(0) rotate(0deg);
        }

        75% {
            transform: translateY(-4px) rotate(-1deg);
        }
    }

    /* Content styling */
    .modal-body {
        padding: 2.5rem;
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        position: relative;
    }

    .birthday-content {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        color: #333;
        padding: 2rem;
        border-radius: 20px;
        margin-top: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
    }

    .birthday-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% {
            left: -100%;
        }

        100% {
            left: 100%;
        }
    }

    .birthday-message {
        font-family: 'Dancing Script', cursive;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #e17055;
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .birthday-wishes {
        font-size: 1rem;
        line-height: 1.8;
        color: #555;
        position: relative;
        z-index: 1;
        text-align: center;
        font-weight: 400;
    }

    /* Enhanced sparkle effects */
    .sparkle {
        display: inline-block;
        font-size: 2rem;
        margin: 0 0.5rem;
        animation: sparkleRotate 2s ease-in-out infinite;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .sparkle:hover {
        transform: scale(1.5);
    }

    @keyframes sparkleRotate {

        0%,
        100% {
            transform: scale(1) rotate(0deg);
            filter: hue-rotate(0deg);
        }

        25% {
            transform: scale(1.2) rotate(90deg);
            filter: hue-rotate(90deg);
        }

        50% {
            transform: scale(1) rotate(180deg);
            filter: hue-rotate(180deg);
        }

        75% {
            transform: scale(1.2) rotate(270deg);
            filter: hue-rotate(270deg);
        }
    }

    /* Animated close button */
    .close {
        font-size: 2rem;
        font-weight: 300;
        opacity: 0.8;
        position: relative;
        z-index: 2;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .close:hover {
        opacity: 1;
        transform: scale(1.2) rotate(90deg);
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Age counter animation */
    .age-counter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        display: inline-block;
        margin: 1rem 0;
        animation: pulse 2s infinite;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    /* Celebration button */
    .celebrate-btn {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        margin-top: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(238, 90, 36, 0.3);
        position: relative;
        overflow: hidden;
    }

    .celebrate-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(238, 90, 36, 0.4);
        color: white;
    }

    .celebrate-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transition: all 0.6s ease;
        transform: translate(-50%, -50%);
    }

    .celebrate-btn:active::before {
        width: 300px;
        height: 300px;
    }

    /* Music note animation */
    .music-note {
        position: absolute;
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.7);
        animation: musicFloat 4s infinite ease-in-out;
    }

    @keyframes musicFloat {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
            opacity: 0;
        }

        25% {
            opacity: 1;
        }

        50% {
            transform: translateY(-30px) rotate(180deg);
            opacity: 1;
        }

        75% {
            opacity: 1;
        }
    }

    /* Demo button */
    .btn-demo {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-demo:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .demo-container {
        text-align: center;
        padding: 3rem 0;
    }

    .demo-title {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        font-family: 'Dancing Script', cursive;
    }
</style>

<!-- css ends  -->


{{-- <div class="row clearfix" style="margin-top:30px">
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
</div> --}}




<div class="row clearfix">
    <!-- Calendar Column -->
    <div class="col-md-12 col-lg-6">
        <div class="card">

            <div class="body">
                <div id="calendar"></div>
            </div>
              <div class="calendar-legend">
    <span><i class="fas fa-bullhorn" style="color:#c0bcbcff;"></i> Announcement</span>
    <span><i class="fas fa-calendar-alt" style="color:#c0bcbcff;"></i> Event</span>
    <span><i class="fas fa-birthday-cake" style="color:#c0bcbcff;"></i> Birthday</span>
    <span><i class="fas fa-umbrella-beach" style="color:#c0bcbcff;"></i> Holiday</span>
</div>
        </div>
    </div>

    <!-- Weather Widget Column -->
    <div class="col-md-12 col-lg-6">

        <!-- Shortcut Cards -->
        <div class="icon-cards-row mt-0">
            <div class="row mb-4">
                <!-- Rooms -->
                <div class="col-md-4 mb-4" style="display:none">
                    <a href="{{ route('rooms_list') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-people-roof fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Rooms</p>
                        </div>
                    </a>
                </div>

                <!-- Children -->
                <div class="col-md-4 mb-4" style="display:none">
                    <a href="{{ route('childrens_list') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-children fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Children</p>
                        </div>
                    </a>
                </div>

                <!-- Educators -->
                <div class="col-md-4 mb-4" style="display:none">
                    <a href="{{ route('settings.staff_settings') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-chalkboard-user fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Educators</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 mb-4">
                    <a href="{{ route('announcements.list') }}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-bullhorn fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Announcements</p>
                        </div>
                    </a>
                </div>
                <!-- Observations -->
                <div class="col-md-6 mb-4">
                    <a href="{{route('observation.index')}}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="icon-equalizer fa-2x mb-2"></i>
                            <p class="card-text mb-1 title">Observations</p>
                        </div>
                    </a>
                </div>

                <!-- Daily Reflections -->
                <div class="col-md-6 mb-4" style="margin-top:-25px">
                    <a href="{{route('reflection.index')}}" class="card shadow-sm">
                        <div class="card-body text-center" style="color:#0e0e0e">
                            <i class="fa-solid fa-notes-medical fa-2x mb-2"></i>
                            <p class="card-text mb-0 title">Daily Reflections</p>
                        </div>
                    </a>
                </div>

                <!-- Daily Diary -->
                <div class="col-md-6 mb-4" style="margin-top:-25px">
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


        <div class="body text-center" style="margin-top: -50px;    margin-bottom: 82px;">
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
<!-- <div class="modal fade" id="birthdayModal" tabindex="-1" aria-labelledby="birthdayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="birthdayModalLabel">Birthday Details</h5>
                <button type="button" class="btn btn-sm btn-light text-danger border-0" style="cursor: pointer;" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body" id="birthdayModalBody"> -->
<!-- Populated dynamically -->
<!-- </div>
        </div>
    </div>
</div> -->
<!-- annoucement modal -->
<!-- <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="announcementModalLabel">Announcement</h5>
                <button type="button" class="btn btn-sm btn-light text-danger border-0" style="cursor: pointer;" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body" id="announcementModalBody"> -->
<!-- Dynamic content -->
<!-- </div>
        </div>
    </div>
</div> -->



<!-- birthday,annoucement, event modal -->
<!-- Birthday Modal -->
<!-- 🎂 Birthday Modal -->
<div class="particles" id="particles"></div>
<div class="modal fade" id="birthdayModal" tabindex="-1" aria-labelledby="birthdayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <!-- Confetti overlay -->
            <div class="confetti-overlay" id="confettiContainer"></div>

            <div class="modal-header birthday-header text-white">
                <!-- Floating music notes -->
                <i class="fas fa-music music-note" style="top: 20%; left: 20%; animation-delay: 0s;"></i>
                <i class="fas fa-music music-note" style="top: 30%; right: 25%; animation-delay: 1s;"></i>
                <i class="fas fa-music music-note" style="top: 50%; left: 15%; animation-delay: 2s;"></i>

                <h5 class="modal-title d-flex align-items-center" id="birthdayModalLabel">
                    <i class="fas fa-birthday-cake sparkle" style="margin-right: 0.5rem;"></i>
                    Birthday Celebration
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center" id="birthdayModalBody">
                <!-- Birthday Cake GIF -->
                <img src="https://media.giphy.com/media/3o7abldj0b3rxrZUxW/giphy.gif"
                    alt="Birthday Cake Celebration"
                    class="birthday-gif img-fluid"
                    onclick="triggerCelebration()">

                <!-- Dynamic Birthday Content -->
                <div class="birthday-content">
                    <div class="birthday-message">
                        <i class="fas fa-heart" style="color: #ff6b6b; margin-right: 0.5rem;"></i>
                        Happy Birthday! 🎉
                    </div>

                    <div class="age-counter" id="ageCounter">
                        Another Year of Awesomeness! 🌟
                    </div>

                    <div class="birthday-wishes">
                        May your special day be filled with happiness, laughter, and wonderful memories.
                        Here's to another year of amazing adventures and beautiful moments! 🎂✨
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <span class="sparkle" onclick="createSparkleEffect(this)">🎈</span>
                        <span class="sparkle" onclick="createSparkleEffect(this)">🎁</span>
                        <span class="sparkle" onclick="createSparkleEffect(this)">🎊</span>
                        <span class="sparkle" onclick="createSparkleEffect(this)">🌟</span>
                    </div>

                    <button class="celebrate-btn" onclick="launchFireworks()">
                        🎆 Celebrate More! 🎆
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 📢 Announcement Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header announcement-header text-white">
                <h5 class="modal-title d-flex align-items-center" id="announcementModalLabel">
                    <i class="fas fa-bullhorn announcement-icon"></i>
                    Important Announcement
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="announcementModalBody">
                <div class="announcement-content">
                    <h6 style="color: #667eea; font-weight: 600; margin-bottom: 1rem;">
                        <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                        Latest Updates
                    </h6>
                    <p style="margin-bottom: 0.5rem;">We're excited to share some important news with you!</p>
                    <ul style="margin-bottom: 0;">
                        <li>New features have been added to enhance your experience</li>
                        <li>System maintenance scheduled for optimal performance</li>
                        <li>Thank you for your continued support and feedback</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🎌 Holiday Modal -->
<div class="modal fade" id="holidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header holiday-header text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas fa-calendar-day holiday-icon"></i>
                    Holiday Celebration
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="holidayModalBody">
                <div class="holiday-content">
                    <h6 style="font-weight: 600; margin-bottom: 1rem;">
                        <i class="fas fa-star" style="color: #ff6b6b; margin-right: 0.5rem;"></i>
                        Special Holiday Details
                    </h6>
                    <p style="margin-bottom: 1rem;">
                        Join us in celebrating this wonderful occasion! Here are the details for the upcoming holiday:
                    </p>
                    <div style="background: rgba(255, 255, 255, 0.5); padding: 1rem; border-radius: 8px;">
                        <strong>Date:</strong> Coming Soon<br>
                        <strong>Activities:</strong> Fun celebrations and special events<br>
                        <strong>Duration:</strong> All day festivities
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Parent Login Notice Modal -->
@if(session('show_parent_notice'))
<div class="modal fade" id="parentNoticeModal" tabindex="-1" role="dialog" aria-labelledby="parentNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="parentNoticeModalLabel">
                    <i class="fas fa-info-circle mr-2"></i>Welcome Parent!
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-friends fa-3x text-primary mb-3"></i>
                </div>
                <h6 class="text-center mb-3">Important Login Notice</h6>
                <p class="mb-2">Welcome to your parent dashboard! Here are some important points:</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>You can view your child's activities and progress</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Access reports and communication from teachers</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Update your profile and contact information</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Contact support if you need any assistance</li>
                </ul>
                <div class="alert alert-info mt-3">
                    <small><i class="fas fa-lightbulb mr-1"></i>This notice will only appear once upon your first login.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-thumbs-up mr-1"></i>Got it, Thanks!
                </button>
            </div>
        </div>
    </div>
</div>





<!-- birthday ,annoucement,event modal ends -->

<script>
    $(document).ready(function() {
        $('#parentNoticeModal').modal('show');
    });
</script>

{{-- Clear the session flag immediately after displaying --}}
@php
session()->forget('show_parent_notice');
@endphp
@endif






<!-- FullCalendar JS
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'title',
                right: 'prev,next today'
            },
            height: 500,
            themeSystem: 'standard',
        });

        calendar.render();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                        media: item.announcementMedia,
                        color: '#17a2b8' // Blue for announcements
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
</script> -->

<!-- new script for birthday and event and annoucement -->

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script>
    // Add some interactive effects
    $(document).ready(function() {
        // Add fade-in effect when modals are shown
        $('.modal').on('show.bs.modal', function(e) {
            $(this).find('.modal-content').addClass('animated');
        });

        // Add sparkle click effect
        $('.sparkle').on('click', function() {
            $(this).css('animation', 'none');
            setTimeout(() => {
                $(this).css('animation', 'sparkle 1.5s ease-in-out infinite');
            }, 100);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const centerId = @json(Session('user_center_id'));

        const formatYMD = (d) => {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`; // avoids timezone issues from toISOString()
        };

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'title',
                right: 'prev,next today'
            },
            height: 500,
            themeSystem: 'standard',

            dayCellDidMount: function(info) {
                const today = new Date();
                const startOfToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                const cellDate = new Date(info.date.getFullYear(), info.date.getMonth(), info.date.getDate());

                // ✅ Only today and future dates
                if (cellDate >= startOfToday) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = '';
                    btn.className = 'add-announcement-btn';
                    btn.setAttribute('data-toggle', 'tooltip');
                    btn.setAttribute('title', 'Add Announcement');
                    btn.style.cssText = `
          position:absolute;
          top:-8px;
          left:2px;
          font-size:16px;
          border:none;
          border-radius:50%;
          color:green;
          cursor:pointer;
        `;

                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation(); // prevent calendar’s own click handling
                        const selectedDate = formatYMD(info.date); // ← use the cell's date
                        const url = `/announcements/create?centerid=${centerId }&date=${encodeURIComponent(selectedDate)}`;
                        window.location.assign(url);
                    });

                    info.el.style.position = 'relative'; // ensure positioning
                    info.el.appendChild(btn);
                }
            }
        });

        calendar.render();
    });
</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'title',
                right: 'prev,next today'
            },
            height: 500,
            themeSystem: 'standard',

            eventContent: function(arg) {
                if (arg.event.classNames.includes('merged-event')) {
                    const {
                        announcements,
                        normalEvents,
                        birthdays,
                        holidays
                    } = arg.event.extendedProps;

                    // helper with icon + badge + type attribute
                    const makeIcon = (emoji, count, color, type) => {
                        if (count === 0) return '';
                        return `
                        <div class="fc-icon-wrapper" data-type="${type}" style="position:relative; display:inline-block; font-size:16px; margin:2px; cursor:pointer;color:#c0bcbcff;">
                            ${emoji}
                            ${count > 0 ? `<span style="
                                position:absolute; top:-8px; right:-10px;
                                background:${color}; color:white;
                                border-radius:50%; padding:2px 4px;
                                font-size:9px; font-weight:bold;
                            ">${count}</span>` : ''}
                        </div>
                    `;
                    };

                    return {
                        html: `
                        <div style="display:flex; flex-wrap:wrap; gap:6px; justify-content:center; max-width:100%;">
                            ${makeIcon('<i class="fas fa-bullhorn"></i>', announcements.length, '#93a5f6ff', 'announcement')}
                            ${makeIcon('<i class="fas fa-calendar-alt"></i>', normalEvents.length, '#86e191ff', 'event')}
                            ${makeIcon('<i class="fas fa-birthday-cake"></i>', birthdays.length, '#e966a5ff', 'birthday')}
                            ${makeIcon('<i class="fas fa-umbrella-beach"></i>', holidays.length, '#e97d4fff', 'holiday')}

                        </div>
                    `
                    };
                }
                return true;
            },

            eventDidMount: function(info) {
                if (info.event.classNames.includes('merged-event')) {
                    // Attach click handler for each icon separately
                    info.el.querySelectorAll('.fc-icon-wrapper').forEach(iconEl => {
                        iconEl.addEventListener('click', (e) => {
                            e.stopPropagation(); // prevent full event click
                            const type = iconEl.dataset.type;
                            const {
                                announcements,
                                normalEvents,
                                birthdays,
                                holidays
                            } = info.event.extendedProps;

                            if (type === 'birthday' && birthdays.length > 0) {
                                let html = birthdays.map(user => `
                                <div class="mb-3 border-bottom pb-2">
                                    <strong>Name:</strong> ${user.name} ${user.lastname || ''}<br>
                                    <strong>Gender:</strong> ${user.gender || 'N/A'}<br>
                                    <strong>DOB:</strong> ${user.dob}
                                </div>
                            `).join('');
                                document.getElementById('birthdayModalBody').innerHTML = html;
                                new bootstrap.Modal(document.getElementById('birthdayModal')).show();
                            }

                            if (type === 'holiday' && holidays.length > 0) {
                                const formatDate = (dateStr) => {
                                    const date = new Date(dateStr);
                                    return `${String(date.getDate()).padStart(2, '0')} ${date.toLocaleString('en-US', {month: 'long'})} ${date.getFullYear()}`;
                                };
                                let html = holidays.map(h => `
                                <div class="mb-3 border-bottom pb-2">
                                    <div><strong>Date:</strong> ${formatDate(h.date)}</div>
                                    <div><strong>State:</strong> ${h.state}</div>
                                    <div><strong>Occasion:</strong> ${h.occasion || 'Holiday'}</div>
                                </div>
                            `).join('');
                                document.getElementById('holidayModalBody').innerHTML = html;
                                new bootstrap.Modal(document.getElementById('holidayModal')).show();
                            }

                            if (type === 'announcement' && announcements.length > 0) {
                                let html = announcements.map(item => {
                                    const title = item.title || 'Untitled';
                                    const date = item.eventDate || '';
                                    const description = item.text || 'No description available';
                                    const rawMedia = item.announcementMedia || [];
                                    const eventColor = item.eventColor || '#1598b3ff';

                                    let mediaHtml = '';
                                    let media = [];
                                    try {
                                        media = typeof rawMedia === 'string' ? JSON.parse(rawMedia) : rawMedia;
                                    } catch {}
                                    if (Array.isArray(media)) {
                                        media.forEach(file => {
                                            const fileUrl = file;
                                            const ext = file.split('.').pop().toLowerCase();
                                            if (['jpg', 'jpeg', 'png'].includes(ext)) {
                                                mediaHtml += `<div><img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow show-poster"></div>`;
                                            } else if (ext === 'pdf') {
                                                mediaHtml += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2">
                        <i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                            }
                                        });
                                    }

                                    // update header + bg per announcement
                                    $('#announcementModalLabel').html('Announcement');
                                    $('#change-bg').css('background-color', eventColor);

                                    return `
            <div class="mb-3 border-bottom pb-2">
                <div><strong>Title:</strong> ${title}</div>
                <div><strong>Date:</strong> ${date}</div>
                <div><strong>Description:</strong><br>${description}</div>
                ${mediaHtml}
            </div>
        `;
                                }).join('');

                                document.getElementById('announcementModalBody').innerHTML = html;
                                new bootstrap.Modal(document.getElementById('announcementModal')).show();
                            }

                            if (type === 'event' && normalEvents.length > 0) {
                                let html = normalEvents.map(item => {
                                    const title = item.title || 'Untitled';
                                    const date = item.eventDate || '';
                                    const description = item.text || 'No description available';
                                    const rawMedia = item.announcementMedia || [];
                                    const eventColor = item.eventColor || '#0d6efd';

                                    let mediaHtml = '';
                                    let media = [];
                                    try {
                                        media = typeof rawMedia === 'string' ? JSON.parse(rawMedia) : rawMedia;
                                    } catch {}
                                    if (Array.isArray(media)) {
                                        media.forEach(file => {
                                            const fileUrl = file;
                                            const ext = file.split('.').pop().toLowerCase();
                                            if (['jpg', 'jpeg', 'png'].includes(ext)) {
                                                mediaHtml += `<div><img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow show-poster"></div>`;
                                            } else if (ext === 'pdf') {
                                                mediaHtml += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2">
                    <i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                            }
                                        });
                                    }

                                    // update modal header + bg
                                    $('#announcementModalLabel').html('Event');
                                    $('#change-bg').css('background-color', eventColor);

                                    // 🎨 Wrap each event in a card with colored shadow
                                    return `
        <div class="rounded mb-3" style="box-shadow: 0 0 12px ${eventColor}; border-left: 4px solid ${eventColor};">
            <div class="card-body">
                <div><strong>Title:</strong> ${title}</div>
                <div><strong>Date:</strong> ${date}</div>
                <div><strong>Description:</strong><br>${description}</div>
                ${mediaHtml}
            </div>
        </div>
    `;
                                }).join('');

                                document.getElementById('announcementModalBody').innerHTML = html;
                                new bootstrap.Modal(document.getElementById('announcementModal')).show();
                            }

                        });
                    });
                }
            }
        });

        calendar.render();

        // ------------------------------- Data Fetching -------------------------------
        function groupByDate(events, dateExtractor) {
            const grouped = {};
            events.forEach(item => {
                const date = dateExtractor(item);
                if (!grouped[date]) grouped[date] = [];
                grouped[date].push(item);
            });
            return grouped;
        }

        Promise.all([
            fetch('/announcements/events').then(r => r.json()),
            fetch('/users/birthday').then(r => r.json()),
            fetch('settings/holidays/events').then(r => r.json())
        ]).then(([annData, bdayData, holiData]) => {
            const groupedAll = {};

            if (annData.status && Array.isArray(annData.events)) {
                const byDate = groupByDate(annData.events, i => i.eventDate);
                for (const [date, items] of Object.entries(byDate)) {
                    if (!groupedAll[date]) groupedAll[date] = {
                        announcements: [],
                        normalEvents: [],
                        birthdays: [],
                        holidays: []
                    };
                    groupedAll[date].announcements.push(...items.filter(i => i.type === 'announcement'));
                    groupedAll[date].normalEvents.push(...items.filter(i => i.type === 'events'));
                }
            }

            if (bdayData.status && Array.isArray(bdayData.events)) {
                const byDate = groupByDate(bdayData.events, user => {
                    const dob = new Date(user.dob);
                    return `${new Date().getFullYear()}-${String(dob.getMonth() + 1).padStart(2, '0')}-${String(dob.getDate()).padStart(2, '0')}`;
                });
                for (const [date, users] of Object.entries(byDate)) {
                    if (!groupedAll[date]) groupedAll[date] = {
                        announcements: [],
                        normalEvents: [],
                        birthdays: [],
                        holidays: []
                    };
                    groupedAll[date].birthdays.push(...users);
                }
            }

            if (holiData.status && Array.isArray(holiData.events)) {
                const byDate = groupByDate(holiData.events, item => {
                    const d = new Date(item.date);
                    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
                });
                for (const [date, items] of Object.entries(byDate)) {
                    if (!groupedAll[date]) groupedAll[date] = {
                        announcements: [],
                        normalEvents: [],
                        birthdays: [],
                        holidays: []
                    };
                    groupedAll[date].holidays.push(...items);
                }
            }

            const finalEvents = Object.entries(groupedAll).map(([date, items]) => ({
                title: '',
                date,
                allDay: true,
                className: 'merged-event',
                extendedProps: items
            }));

            calendar.addEventSource(finalEvents);
        }).catch(err => console.error('Fetch error:', err));



        $(document).on('click', '.show-poster', function() {
            const url = $(this).attr('src'); // get clicked image src
            // Open in a new tab/page
            window.open(url, '_blank');
        });



    });
</script>

<script>
    // Create floating particles
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        const particleCount = 30;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            particlesContainer.appendChild(particle);
        }
    }

    // Create confetti effect
    function createConfetti() {
        const container = document.getElementById('confettiContainer');
        const confettiCount = 50;

        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.animationDelay = Math.random() * 3 + 's';
            confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
            container.appendChild(confetti);

            // Remove after animation
            setTimeout(() => {
                if (confetti.parentNode) {
                    confetti.parentNode.removeChild(confetti);
                }
            }, 5000);
        }
    }

    // Sparkle click effect
    function createSparkleEffect(element) {
        const sparkles = ['✨', '⭐', '🌟', '💫'];
        const sparkle = document.createElement('span');
        sparkle.innerHTML = sparkles[Math.floor(Math.random() * sparkles.length)];
        sparkle.style.position = 'absolute';
        sparkle.style.fontSize = '1.5rem';
        sparkle.style.pointerEvents = 'none';
        sparkle.style.left = Math.random() * 50 + 25 + '%';
        sparkle.style.top = Math.random() * 50 + 25 + '%';
        sparkle.style.animation = 'sparkleRotate 1s ease-out forwards';

        element.parentNode.appendChild(sparkle);

        setTimeout(() => {
            if (sparkle.parentNode) {
                sparkle.parentNode.removeChild(sparkle);
            }
        }, 1000);
    }

    // Celebration button effect
    function triggerCelebration() {
        createConfetti();
        // Add shake effect to modal
        $('.modal-content').addClass('animated');
        setTimeout(() => {
            $('.modal-content').removeClass('animated');
        }, 600);
    }

    // Fireworks effect
    function launchFireworks() {
        createConfetti();
        // Create multiple confetti bursts
        setTimeout(() => createConfetti(), 500);
        setTimeout(() => createConfetti(), 1000);

        // Change age counter text temporarily
        const counter = document.getElementById('ageCounter');
        const originalText = counter.innerHTML;
        counter.innerHTML = '🎉 CELEBRATING! 🎉';
        setTimeout(() => {
            counter.innerHTML = originalText;
        }, 3000);
    }

    // Initialize effects
    $(document).ready(function() {
        createParticles();

        // Trigger confetti when modal opens
        $('#birthdayModal').on('shown.bs.modal', function() {
            setTimeout(() => {
                createConfetti();
            }, 500);
        });

        // Clear confetti when modal closes
        $('#birthdayModal').on('hidden.bs.modal', function() {
            const container = document.getElementById('confettiContainer');
            container.innerHTML = '';
        });

        // Add hover sound effect simulation
        $('.sparkle').hover(
            function() {
                $(this).css('transform', 'scale(1.3)');
            },
            function() {
                $(this).css('transform', 'scale(1)');
            }
        );
    });
</script>
@include('layout.footer')
@stop