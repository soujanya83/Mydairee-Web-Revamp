@extends('layout.master')
@section('title', 'Dashboard')
{{-- @section('parentPageTitle', 'Dashboard') --}}
<!-- FullCalendar CSS -->

@section('content')
    <!-- FullCalendar CSS -->
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet"> -->


    <style>
        :root {
            --dashboard-accent: #2563eb;
            --dashboard-accent-end: #7c3aed;
        }

        /* Theme-specific colors - dynamically applies based on user's theme selection */
        .theme-purple {
            --dashboard-accent: #a27ce6;
            --dashboard-accent-end: #9b6dd6;
        }
        
        .theme-blue {
            --dashboard-accent: #3eacff;
            --dashboard-accent-end: #2d9cef;
        }
        
        .theme-cyan {
            --dashboard-accent: #49c5b6;
            --dashboard-accent-end: #3ab5a6;
        }
        
        .theme-green {
            --dashboard-accent: #50d38a;
            --dashboard-accent-end: #41c37a;
        }
        
        .theme-orange {
            --dashboard-accent: #ffce4b;
            --dashboard-accent-end: #ffc13b;
        }
        
        .theme-blush {
            --dashboard-accent: #e47297;
            --dashboard-accent-end: #d46287;
        }

        #birthdayModal:hover,
        #announcementModal:hover {
            cursor: pointer !important;
        }

        .birthday-event,
        .annoucement-event {
            cursor: pointer;
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

        /* Calendar panel */
        .calendar-shell {
            background: radial-gradient(circle at 20% 20%, #f0f4ff 0%, #ffffff 45%, #f6f8fb 100%);
            border-radius: 18px;
            padding: 18px 18px 14px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.12);
            border: 1px solid #e7eaf3;
            position: relative;
            overflow: hidden;
            height: 90%;
        }

        .calendar-shell::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 0%, rgba(103, 178, 255, 0.12), transparent 40%),
                        radial-gradient(circle at 10% 30%, rgba(129, 140, 248, 0.1), transparent 35%);
            pointer-events: none;
        }

        .calendar-header {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 6px 4px 4px;
        }

        .calendar-header h4 {
            margin: 0;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.2px;
        }

        .calendar-header .eyebrow {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 11px;
            color: #6b7280;
            margin: 0 0 4px;
        }

        .calendar-header .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--dashboard-accent) 0%, var(--dashboard-accent-end) 100%);
            color: #fff;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.28);
        }

        .calendar-body {
            position: relative;
            z-index: 1;
            background: #fff;
            border-radius: 14px;
            padding: 14px;
           height: 87%;
            border: 1px solid #e5e7eb;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 10px 24px rgba(15, 23, 42, 0.08);
            clip-path: inset(0 0 0 0);
        }

        #calendar {
            min-height: 500px;
            /* Allow interactions */
            pointer-events: auto;
        }

        /* Prevent grid lines from bleeding - use clip instead of overflow */
        .calendar-body .fc-scrollgrid,
        .calendar-body .fc-scrollgrid-liquid {
            clip-path: inset(0);
            pointer-events: auto;
        }

        .calendar-body .fc-daygrid-day-frame,
        .calendar-body .fc-daygrid-day-container {
            clip-path: inset(0);
        }

        /* Keep event content interactive */
        .calendar-body .fc-daygrid-day-events {
            pointer-events: auto;
        }

        /* Ensure icon wrappers stay interactive */
        .calendar-body .fc-icon-wrapper {
            pointer-events: auto;
            cursor: pointer;
        }

        /* Calendar icon theme gradient */
        .fc-icon-wrapper > span:first-child {
            background: linear-gradient(135deg, var(--dashboard-accent) 0%, var(--dashboard-accent-end) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Calendar legend */
        .calendar-legend {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            gap: 4px;
            margin-top: 12px;
            padding: 8px 10px;
            background: rgba(15, 23, 42, 0.02);
            border: 1px dashed #d8dceb;
            border-radius: 12px;
            backdrop-filter: blur(4px);
            overflow: hidden;
        }

        .calendar-legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            border-radius: 9px;
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
            flex-shrink: 0;
            font-size: 12px;
        }

        .calendar-legend span i {
            width: 18px;
            height: 18px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.05);
            margin-right: 0;
            font-size: 11px;
        }

        /* Only change background color of FullCalendar toolbar buttons */
        .calendar-body .fc .fc-toolbar .fc-button.fc-today-button,
        .calendar-body .fc .fc-toolbar .fc-button.fc-prev-button,
        .calendar-body .fc .fc-toolbar .fc-button.fc-next-button {
            background: linear-gradient(135deg, var(--dashboard-accent) 0%, var(--dashboard-accent-end) 100%) !important;
            color: white !important;
            border: none !important;
        }

        /* Shortcut icon cards */
        .icon-cards-row .row {
            row-gap: 10px;
        }

        .icon-cards-row .card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .icon-cards-row .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.14);
            border-color: #d0d7e6;
        }

        .icon-cards-row .card .card-body {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
        }

        .icon-cards-row .card i {
            width: 48px;
            height: 48px;
            min-width: 48px;
            min-height: 48px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--dashboard-accent) 0%, var(--dashboard-accent-end) 100%);
            color: #fff;
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.3);
            font-size: 20px;
            line-height: 48px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .icon-cards-row .card:hover i {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.4);
        }

        .icon-cards-row .card .title {
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.2px;
        }

        .right-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 0;
        }

        /* Weather widget shell */
        .weather-shell {
            margin: -25px 0px 0px 0px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(255, 255, 255, 0.5) 100%);
            border: 2px solid var(--dashboard-accent);
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.1);
            padding: 10px;
            overflow: hidden;
            position: relative;
        }

        .weather-shell .weather-frame {
            width: 100%;
            height: 320px;
            border: none;
            border-radius: 12px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }
        
        .weather-shell object,
        .weather-shell embed {
            display: block;
        }
        
        .weather-shell::after {
            content: '';
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            height: 20px;
            background: linear-gradient(to bottom, transparent, rgba(236, 254, 255, 0.9));
            pointer-events: none;
            border-radius: 0 0 12px 12px;
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

        /* Announcement Icon Animations */
        @keyframes announcementPulse {
            0%, 100% {
                transform: scale(1);
                filter: drop-shadow(0 0 10px rgba(102, 126, 234, 0.3));
            }
            50% {
                transform: scale(1.15);
                filter: drop-shadow(0 0 20px rgba(102, 126, 234, 0.6));
            }
        }

        @keyframes soundWave {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.8;
            }
            100% {
                transform: translate(-50%, -50%) scale(2.5);
                opacity: 0;
            }
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

        /* Holiday Icon Animations */
        @keyframes holidaySparkle {
            0%, 100% {
                transform: rotate(0deg) scale(1);
                filter: drop-shadow(0 0 15px rgba(255, 107, 107, 0.4));
            }
            25% {
                transform: rotate(90deg) scale(1.1);
                filter: drop-shadow(0 0 25px rgba(255, 107, 107, 0.7));
            }
            50% {
                transform: rotate(180deg) scale(1);
                filter: drop-shadow(0 0 15px rgba(255, 107, 107, 0.4));
            }
            75% {
                transform: rotate(270deg) scale(1.1);
                filter: drop-shadow(0 0 25px rgba(255, 107, 107, 0.7));
            }
        }

        @keyframes sparkle1 {
            0%, 100% { opacity: 0; transform: translate(0, 0) scale(0); }
            50% { opacity: 1; transform: translate(-20px, -20px) scale(1.2); }
        }

        @keyframes sparkle2 {
            0%, 100% { opacity: 0; transform: translate(0, 0) scale(0); }
            50% { opacity: 1; transform: translate(20px, -15px) scale(1); }
        }

        @keyframes sparkle3 {
            0%, 100% { opacity: 0; transform: translate(0, 0) scale(0); }
            50% { opacity: 1; transform: translate(-15px, 20px) scale(1.1); }
        }

        @keyframes sparkle4 {
            0%, 100% { opacity: 0; transform: translate(0, 0) scale(0); }
            50% { opacity: 1; transform: translate(-20px, -10px) scale(0.9); }
        }

        /* Event Modal Styles */
        .event-header {
            background: linear-gradient(135deg, #86e191 0%, #51cf66 100%);
            color: white;
            border-bottom: none;
            padding: 1.5rem 2rem;
            overflow: hidden;
            position: relative;
        }

        .event-modal-wrapper {
            border: none;
            overflow: visible;
            position: relative;
        }

        .event-modal-wrapper::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(135deg, #86e191 0%, #51cf66 100%);
            border-radius: 1rem;
            opacity: 0.4;
            filter: blur(8px);
            z-index: -1;
        }

        /* Event Icon Animations */
        @keyframes eventBounce {
            0%, 100% {
                transform: translateY(0) scale(1);
                filter: drop-shadow(0 5px 15px rgba(134, 225, 145, 0.4));
            }
            50% {
                transform: translateY(-15px) scale(1.1);
                filter: drop-shadow(0 10px 25px rgba(134, 225, 145, 0.6));
            }
        }

        @keyframes confettiFall1 {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translate(-30px, 80px) rotate(360deg); opacity: 0; }
        }

        @keyframes confettiFall2 {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translate(25px, 75px) rotate(-360deg); opacity: 0; }
        }

        @keyframes confettiFall3 {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translate(-20px, 85px) rotate(180deg); opacity: 0; }
        }

        @keyframes confettiFall4 {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translate(35px, 70px) rotate(-270deg); opacity: 0; }
        }

        @keyframes confettiFall5 {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translate(-25px, 78px) rotate(270deg); opacity: 0; }
        }

        /* Content Item Slide Animation */
        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced Content Items with Hover Glow */
        .announcement-content-item,
        .holiday-content-item,
        .event-content-item {
            position: relative;
            animation: slideInUp 0.5s ease-out;
        }

        .announcement-content-item:hover {
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.3) !important;
        }

        .holiday-content-item:hover {
            box-shadow: 0 12px 35px rgba(255, 107, 107, 0.3) !important;
        }

        .event-content-item:hover {
            box-shadow: 0 12px 35px rgba(134, 225, 145, 0.3) !important;
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

    <!-- Top 3-column row: PTM | Observations | Reflections -->
    <style>
        .top-row { 
            display: flex; 
            gap: 28px; 
            margin-left: 20px ; 
            flex-wrap: wrap;
        }
        
        .top-card { 
            flex: 1; 
            min-width: 280px;
        }
        
        .card-link { 
            display: block; 
            text-decoration: none; 
            color: inherit;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-link:hover { 
            cursor: pointer; 
            transform: translateY(-8px) scale(1.02);
        }
        
        .top-card .card { 
            padding: 14px; 
            border-radius: 14px; 
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 8px 28px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.05);
            border: 1px solid rgba(255,255,255,0.8);
            overflow: visible;
            position: relative;
         
        }
        
        .card-link:hover .card {
            box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .top-card .card .head { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .top-card .card .icon { 
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--dashboard-accent) 0%, var(--dashboard-accent-end) 100%);
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        
        .card-link:hover .icon {
            transform: rotate(5deg) scale(1.1);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }
        
        .top-card .card .icon i { 
            font-size: 1.3rem; 
            color: white;
        }
        
        .top-card .card .title { 
            font-weight: 700; 
            font-size: 1.0rem;
            color: #2d3748;
            letter-spacing: -0.3px;
        }
        
        .top-card .card .title-link { 
            color: inherit; 
            text-decoration: none;
            background: linear-gradient(to right, var(--dashboard-accent), var(--dashboard-accent-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .top-card .card .title-link:hover { 
            text-decoration: underline;
            text-decoration-color: var(--dashboard-accent);
        }
        
        .rotator { 
            min-height: 110px; 
            position: relative; 
            overflow: visible; 
            display: block;
            background: rgba(249, 250, 251, 0.5);
            border-radius: 10px;
            padding: 10px;
        }
        
        .rotator .item { 
            position: absolute; 
            left: 10px; 
            right: 10px; 
            top: 10px; 
            opacity: 0; 
            transform: translateY(30px); 
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 8px; 
            text-decoration: none; 
            color: inherit; 
            display: block; 
            z-index: 1;
            background: white;
            border-radius: 10px;
            border: 1px solid rgba(102, 126, 234, 0.08);
        }
        
        .rotator .item:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .rotator .item.active { 
            opacity: 1; 
            transform: translateY(0); 
            z-index: 3;
        }
        
        .rotator .item.leaving { 
            opacity: 0; 
            transform: translateY(-30px); 
            z-index: 2;
        }
        
        .rotator .item .small { 
            color: #4a5568; 
            font-size: 0.875rem;
        }
        
        .rotator .item .value {
            color: var(--dashboard-accent);
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .rotator .item .new-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--dashboard-accent) 0%, var(--dashboard-accent-end) 100%);
            color: white;
            font-size: 9px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 8px;
            vertical-align: middle;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
            animation: subtle-pulse 2s ease-in-out infinite;
        }
        
        @keyframes subtle-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .obs-content, .ref-content { 
            display: flex; 
            gap: 10px; 
            align-items: center; 
            position: relative;
        }
        
        .obs-text, .ref-text { 
            flex: 1; 
            min-width: 0;
        }
        
        .thumb-wrap {
            position: relative;
            width: 56px;
            height: 56px;
            flex-shrink: 0;
        }

        .obs-thumb, .ref-thumb { 
            width: 56px; 
            height: 56px; 
            border-radius: 10px; 
            object-fit: cover; 
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer; 
            position: relative;
            border: 2px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .thumb-wrap:hover .obs-thumb,
        .thumb-wrap:hover .ref-thumb { 
            position: absolute;
            top: 50%; 
            left: 50%;
            transform: translate(-130%, -110%) scale(3.2); 
            z-index: 9999; 
            box-shadow: 0 18px 50px rgba(0,0,0,0.38);
            border-color: rgba(102, 126, 234, 0.6);
        }
        
        .obs-title, .ref-about { 
            color: #718096; 
            font-size: 0.875rem; 
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
            text-overflow: ellipsis; 
            line-height: 1.4; 
            margin-bottom: 4px;
        }
        
        .ptm-content { 
            display: flex; 
            flex-direction: column; 
            gap: 6px;
        }
        
        .ptm-title { 
            font-weight: 600; 
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
            text-overflow: ellipsis; 
            line-height: 1.4;
            color: #2d3748;
        }
        
        .ptm-objective { 
            color: #718096; 
            font-size: 0.875rem; 
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
            text-overflow: ellipsis; 
            line-height: 1.4; 
            margin-bottom: 4px;
        }
        
        .two-line-clamp { 
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
            text-overflow: ellipsis; 
            line-height: 1.4;
        }
        
        /* Color variations for different cards */
        #refCard .icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        #obsCard .icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        #ptmCard .icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
    </style>

    <style>
        /* Modern Calendar Modal Styling */
        .calendar-modal {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.15), 0 0 1px rgba(15, 23, 42, 0.1);
            overflow: hidden;
            background: #fff;
            animation: modalIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .calendar-modal .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 24px;
            border: none;
            flex-wrap: nowrap;
            position: relative;
            z-index: 1;
        }

        .calendar-modal .modal-title {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .calendar-modal .modal-title i {
            font-size: 1.4rem;
        }

        .calendar-modal .close {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.06);
            color: #1a202c;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            opacity: 1;
            font-size: 24px;
            line-height: 1;
            padding: 0;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .calendar-modal .close:hover,
        .calendar-modal .close:focus {
            background: rgba(15, 23, 42, 0.12);
            transform: rotate(90deg);
            outline: none;
        }

        .calendar-modal .modal-body {
            background: #ffffff;
            padding: 28px 24px;
            color: #2d3748;
            line-height: 1.6;
        }

        /* Header color variants */
        .modal-header.birthday-header {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%) !important;
        }

        .modal-header.birthday-header .modal-title,
        .modal-header.birthday-header .close {
            color: #fff;
        }

        .modal-header.birthday-header .close {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-header.birthday-header .close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-header.ptm-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        }

        .modal-header.ptm-header .modal-title,
        .modal-header.ptm-header .close {
            color: #fff;
        }

        .modal-header.ptm-header .close {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-header.ptm-header .close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-header.announcement-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .modal-header.announcement-header .modal-title,
        .modal-header.announcement-header .close {
            color: #fff;
        }

        .modal-header.announcement-header .close {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-header.announcement-header .close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-header.holiday-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8a80 100%) !important;
        }

        .modal-header.holiday-header .modal-title,
        .modal-header.holiday-header .close {
            color: #fff;
        }

        .modal-header.holiday-header .close {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-header.holiday-header .close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Distinctive PTM Modal Styling */
        .ptm-modal-wrapper {
            position: relative;
        }

        .ptm-modal-wrapper::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 50%, #4facfe 100%);
            border-radius: 18px;
            z-index: -1;
            opacity: 0.4;
            filter: blur(8px);
        }

        .ptm-modal-header-decoration {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 16px;
            position: relative;
        }

        .ptm-modal-header-decoration i {
            font-size: 1.1rem;
            animation: ptmIconPulse 2s ease-in-out infinite;
        }

        @keyframes ptmIconPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.15);
            }
        }

        .ptm-content-item {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.08) 0%, rgba(0, 242, 254, 0.08) 100%);
            border-left: 4px solid #4facfe;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .ptm-content-item:hover {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.12) 0%, rgba(0, 242, 254, 0.12) 100%);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.15);
        }

        .ptm-label {
            font-weight: 700;
            color: #4facfe;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline;
            margin-bottom: 0;
            margin-right: 6px;
            margin-top: 0;
        }

        .ptm-value {
            color: #2d3748;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 8px;
            word-break: break-word;
            word-wrap: break-word;
            display: inline;
        }
    </style>

    <div class="top-row">
      
        <div class="top-card" id="refCard">
            @php
                $refLatest = !empty($recentReflections) && $recentReflections->count() ? $recentReflections->first() : null;
            @endphp
            <div class="card card-link" data-detail-href="{{ $refLatest ? route('reflection.print', $refLatest->id) : route('reflection.index') }}" tabindex="0">
                <div class="head">
                    <div class="icon" title="Reflections"><i class="fa-solid fa-window-restore " aria-hidden="true"></i></div>
                    <div class="title"><a href="{{ route('reflection.index') }}" class="title-link">Reflections</a></div>
                </div>
                <div class="rotator" id="refRotator">
                    @if(!empty($recentReflections) && $recentReflections->count())
                        @foreach($recentReflections->take(3) as $r)
                            <a class="item item-link {{ $loop->first ? 'active' : '' }}" href="{{ route('reflection.print', $r->id) }}">
                                <div class="ref-content">
                                    <div class="ref-text">
                                        <div class="small two-line-clamp" style="font-weight:600; margin-bottom:2px;">
                                            {{ strip_tags($r->title ?? 'Reflection') }}
                                            @if($loop->first)
                                                <span class="new-badge">NEW</span>
                                            @endif
                                        </div>
                                        <div class="value" style="margin-top:4px;">{{ $r->created_at ? date('d M Y', strtotime($r->created_at)) : ('#'.$r->id) }}</div>
                                    </div>
                                    @php
                                        $firstMedia = $r->media->first();
                                    @endphp
                                    @if($firstMedia && $firstMedia->mediaUrl)
                                        <div class="thumb-wrap">
                                            <img src="{{ asset($firstMedia->mediaUrl) }}" alt="reflection" class="ref-thumb">
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="item active"><div class="small">Total Reflections</div><div class="value">{{ $reflectionsCount ?? 0 }}</div></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="top-card" id="obsCard">
            @php
                $obsLatest = !empty($recentObservations) && $recentObservations->count() ? $recentObservations->first() : null;
            @endphp
            <div class="card card-link" data-detail-href="{{ $obsLatest ? route('observation.view', $obsLatest->id) : route('observation.index') }}" tabindex="0">
                <div class="head">
                    <div class="icon" title="Observations"><i class="icon-equalizer " aria-hidden="true"></i></div>
                    <div class="title"><a href="{{ route('observation.index') }}" class="title-link">Observations</a></div>
                </div>
                <div class="rotator" id="obsRotator">
                    @if(!empty($recentObservations) && $recentObservations->count())
                        @foreach($recentObservations->take(3) as $o)
                            <a class="item item-link {{ $loop->first ? 'active' : '' }}" href="{{ route('observation.view', $o->id) }}">
                                <div class="obs-content">
                                    <div class="obs-text">
                                        <div class="small two-line-clamp" style="font-weight:600; margin-bottom:2px;">
                                            {{ strip_tags($o->obestitle ?? 'Observation') }}
                                            @if($loop->first)
                                                <span class="new-badge">NEW</span>
                                            @endif
                                        </div>
                                        <div class="value" style="margin-top:4px;">{{ $o->created_at ? date('d M Y', strtotime($o->created_at)) : '' }}</div>
                                    </div>
                                    @php
                                        $firstMedia = $o->media->first();
                                    @endphp
                                    @if($firstMedia && $firstMedia->mediaUrl)
                                        <div class="thumb-wrap">
                                            <img src="{{ asset($firstMedia->mediaUrl) }}" alt="observation" class="obs-thumb">
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="item active"><div class="small">Total Observations</div><div class="value">{{ $observationsCount ?? 0 }}</div></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="top-card" id="ptmCard">
            @php
                $ptmLatest = !empty($recentPtms) && $recentPtms->count() ? $recentPtms->first() : null;
            @endphp
            <div class="card card-link" data-detail-href="{{ $ptmLatest ? route('ptm.viewptm', $ptmLatest->id) : route('ptm.index') }}" tabindex="0">
                <div class="head">
                    <div class="icon" title="PTM"><i class="icon-users " aria-hidden="true"></i></div>
                    <div class="title"><a href="{{ route('ptm.index') }}" class="title-link">PTM's</a></div>
                </div>
                <div class="rotator" id="ptmRotator">
                    @if(!empty($recentPtms) && $recentPtms->count())
                        @foreach($recentPtms->take(3) as $p)
                            <a class="item item-link {{ $loop->first ? 'active' : '' }}" href="{{ route('ptm.viewptm', $p->id) }}">
                                <div class="ptm-content">
                                    <div class="ptm-title">
                                        {{ strip_tags($p->title ?? 'PTM') }}
                                        @if($loop->first)
                                            <span class="new-badge">NEW</span>
                                        @endif
                                    </div>
                                    <div class="small" style="color:#6b7280;">Slot: {{ strip_tags($p->slot ?? $p->final_slot ?? ($p->ptmSlots->first()->slot ?? '—')) }}</div>
                                    <div class="value" style="margin-top:2px;">{{ $p->ptm_date ? date('d M Y', strtotime($p->ptm_date)) : '' }}</div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="item active"><div class="small">Total PTMs</div><div class="value">{{ $ptmCount ?? $ptmEventsCount ?? 0 }}</div></div>
                    @endif
                </div>
            </div>
        </div>
    </div>


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
        <div class="col-md-12 col-lg-6 right-stack">
            <div class="calendar-shell">

                <div class="calendar-body">
                    <div id="calendar"></div>
                </div>

                <div class="calendar-legend" >
                    <span><i class="fas fa-bullhorn"  style="color:#93a5f6ff;"></i> Announcement</span>
                    <span><i class="fas fa-calendar-alt" style="color:#86e191ff;"></i> Event</span>
                    <span><i class="fas fa-birthday-cake" style="color:#e966a5ff;"></i> Birthday</span>
                    <span><i class="fas fa-umbrella-beach" style="color:#e97d4fff;"></i> Holiday</span>
                    <span><i class="fas fa-chalkboard-teacher" style="color:#c68df7ff;"></i> PTM </span>
                </div>
            </div>
        </div>

        <!-- Weather Widget Column -->
        <div class="col-md-12 col-lg-6">

            <!-- Shortcut Cards -->
            <div class="icon-cards-row" style="margin-top: 20px;">
                <!-- Row 1: Announcements & Observations -->
                <div class="row mb-2" style="margin-bottom:0px;">
                    <div class="col-md-5 mb-3" style="margin-left:60px;">
                        <a href="{{ route('announcements.list') }}" class="card shadow-sm">
                            <div class="card-body" style="color:#0e0e0e;" >
                                <i class="fa-solid fa-bullhorn" style="margin-left:2px;"></i>
                                <p class="card-text mb-0 title">Announcements</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-5 mb-3" style="margin-left:7px;">
                        <a href="{{ route('dailyDiary.list') }}" class="card shadow-sm">
                            <div class="card-body" style="color:#0e0e0e">
                                <i class="fa-solid fa-wallet" style="margin-left:15px;"></i>
                                <p class="card-text mb-0 title">Daily Diary</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Row 2: Reflections, Diary, PTM -->
                <div class="row mb-2" style="margin-top:-30px;">
                    <div class="col-md-4 mb-3" style="margin-top: 0;">
                        <a href="{{ route('reflection.index') }}" class="card shadow-sm">
                            <div class="card-body" style="color:#0e0e0e">
                                <i class="fa-solid fa-notes-medical"></i>
                                <p class="card-text mb-0 title" style="margin-left: -6px;">Reflections</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3" style="margin-top: 0px; "  >
                        <a href="{{ route('observation.index') }}" class="card shadow-sm">
                            <div class="card-body" style="color:#0e0e0e">
                                <i class="icon-equalizer" style="margin-left: -6px;"></i>
                                <p class="card-text mb-0 title" style="margin-left:-6px;">Observation</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3" style="margin-top: 0;">
                        <a href="{{ route('ptm.index') }}" class="card shadow-sm">
                            <div class="card-body" style="color:#0e0e0e">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <p class="card-text mb-0 title">PTM</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Weather Card -->


            <div class="weather-shell text-center">
                <object data="https://www.sunsmart.com.au/uvalert/default.asp?version=australia&locationid=161"
                    class="weather-frame" id="sunsmart" aria-label="SunSmart UV alert" scrolling="no">
                    <embed src="https://www.sunsmart.com.au/uvalert/default.asp?version=australia&locationid=161"
                        class="weather-frame" scrolling="no">
                    </embed>
                    <p class="text-muted mb-0" style="padding: 8px;">Embedded data could not be displayed.</p>
                </object>
            </div>


        </div>

    </div>






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
    <div class="modal " id="birthdayModal" tabindex="-1" aria-labelledby="birthdayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal shadow-lg border-0 rounded-3">
                <!-- Confetti overlay -->
                <div class="confetti-overlay" id="confettiContainer"></div>

                <div class="modal-header birthday-header">
                    <h5 class="modal-title" id="birthdayModalLabel">
                        <i class="fas fa-birthday-cake"></i>
                        Birthday Celebration
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="birthdayModalBody">
                    <!-- Dynamic content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
    <!-- PTM Modal -->
    <div class="modal fade" id="ptmModal" tabindex="-1" aria-labelledby="ptmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal ptm-modal-wrapper shadow-lg border-0 rounded-3">
                <div class="modal-header ptm-header">
                    <h5 class="modal-title" id="ptmModalLabel">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Parents Teachers Meeting
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="ptmModalBody">
                    <!-- Content will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- 📢 Announcement Modal -->
    <div class="modal " id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal announcement-modal-wrapper shadow-lg border-0 rounded-3">
                <div class="modal-header announcement-header">
                    <h5 class="modal-title" id="announcementModalLabel">
                        <i class="fas fa-bullhorn"></i>
                        Important Announcement
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="announcementModalBody">
                    <!-- Animated Megaphone Icon -->
                    <div class="announcement-icon-container" style="text-align: center; margin-bottom: 1.5rem; position: relative;">
                        <i class="fas fa-bullhorn" style="font-size: 4rem; color: #667eea; animation: announcementPulse 2s infinite;"></i>
                        <div class="sound-waves" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <div class="wave" style="width: 80px; height: 80px; border: 3px solid rgba(102, 126, 234, 0.3); border-radius: 50%; position: absolute; animation: soundWave 2s infinite;"></div>
                            <div class="wave" style="width: 80px; height: 80px; border: 3px solid rgba(102, 126, 234, 0.3); border-radius: 50%; position: absolute; animation: soundWave 2s 0.5s infinite;"></div>
                            <div class="wave" style="width: 80px; height: 80px; border: 3px solid rgba(102, 126, 234, 0.3); border-radius: 50%; position: absolute; animation: soundWave 2s 1s infinite;"></div>
                        </div>
                    </div>
                    <div class="announcement-content" id="announcementContentBody">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🎌 Holiday Modal -->
    <div class="modal " id="holidayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal holiday-modal-wrapper shadow-lg border-0 rounded-3">
                <div class="modal-header holiday-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-day"></i>
                        Holiday Celebration
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="holidayModalBody">
                    <!-- Animated Holiday Icon -->
                    <div class="holiday-icon-container" style="text-align: center; margin-bottom: 1.5rem; position: relative;">
                        <i class="fas fa-star" style="font-size: 4rem; color: #ff6b6b; animation: holidaySparkle 3s infinite;"></i>
                        <div class="sparkles" style="position: absolute; top: 50%; left: 50%; width: 100px; height: 100px; transform: translate(-50%, -50%); pointer-events: none;">
                            <i class="fas fa-sparkles" style="position: absolute; top: 0; left: 50%; font-size: 1.2rem; color: #ffd700; animation: sparkle1 2s infinite;"></i>
                            <i class="fas fa-sparkles" style="position: absolute; top: 50%; right: 0; font-size: 1rem; color: #ff6b6b; animation: sparkle2 2.5s infinite;"></i>
                            <i class="fas fa-sparkles" style="position: absolute; bottom: 0; left: 50%; font-size: 0.9rem; color: #ffd700; animation: sparkle3 3s infinite;"></i>
                            <i class="fas fa-sparkles" style="position: absolute; top: 50%; left: 0; font-size: 1.1rem; color: #ff8a80; animation: sparkle4 2.2s infinite;"></i>
                        </div>
                    </div>
                    <div class="holiday-content" id="holidayContentBody">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🎉 Event Modal -->
    <div class="modal " id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content calendar-modal event-modal-wrapper shadow-lg border-0 rounded-3">
                <div class="modal-header event-header">
                    <h5 class="modal-title" id="eventModalLabel">
                        <i class="fas fa-calendar-check"></i>
                        Special Event
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="eventModalBody">
                    <!-- Animated Event Icon -->
                    <div class="event-icon-container" style="text-align: center; margin-bottom: 1.5rem; position: relative;">
                        <i class="fas fa-calendar-star" style="font-size: 4rem; color: #86e191; animation: eventBounce 2s infinite;"></i>
                        <div class="confetti-burst" style="position: absolute; top: 50%; left: 50%; width: 120px; height: 120px; transform: translate(-50%, -50%); pointer-events: none;">
                            <div class="confetti-piece" style="position: absolute; width: 8px; height: 8px; background: #ff6b6b; border-radius: 50%; top: 20%; left: 50%; animation: confettiFall1 2.5s infinite;"></div>
                            <div class="confetti-piece" style="position: absolute; width: 6px; height: 6px; background: #ffd93d; border-radius: 50%; top: 30%; left: 60%; animation: confettiFall2 2.8s infinite;"></div>
                            <div class="confetti-piece" style="position: absolute; width: 7px; height: 7px; background: #6bcf7f; border-radius: 50%; top: 25%; left: 40%; animation: confettiFall3 3s infinite;"></div>
                            <div class="confetti-piece" style="position: absolute; width: 5px; height: 5px; background: #4facfe; border-radius: 50%; top: 35%; left: 70%; animation: confettiFall4 2.6s infinite;"></div>
                            <div class="confetti-piece" style="position: absolute; width: 6px; height: 6px; background: #ff8ff8; border-radius: 50%; top: 28%; left: 30%; animation: confettiFall5 3.2s infinite;"></div>
                        </div>
                    </div>
                    <div class="event-content" id="eventContentBody">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Parent Login Notice Modal -->
    @if (session('show_parent_notice'))
        <div class="modal fade" id="parentNoticeModal" tabindex="-1" role="dialog"
            aria-labelledby="parentNoticeModalLabel" aria-hidden="true">
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
                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>You can view your child's
                                activities and progress</li>
                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Access reports and
                                communication from teachers</li>
                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Update your profile and
                                contact information</li>
                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Contact support if you need
                                any assistance</li>
                        </ul>
                        <div class="alert alert-info mt-3">
                            <small><i class="fas fa-lightbulb mr-1"></i>This notice will only appear once upon your first
                                login.</small>
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


    <script>
        // Rotator: auto-scroll every 3 seconds (bottom to top)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.rotator').forEach(function(rot) {
                var items = Array.from(rot.querySelectorAll('.item'));
                if (!items || items.length <= 1) return;

                if (!items.some(function(i) { return i.classList.contains('active'); })) {
                    items[0].classList.add('active');
                }

                var currentIndex = items.findIndex(function(i) { return i.classList.contains('active'); });
                if (currentIndex < 0) currentIndex = 0;

                var intervalId = null;

                function tick() {
                    var oldIndex = currentIndex;
                    var nextIndex = (currentIndex + 1) % items.length;

                    items[oldIndex].classList.remove('active');
                    items[oldIndex].classList.add('leaving');

                    items[nextIndex].classList.remove('leaving');
                    items[nextIndex].classList.add('active');

                    setTimeout(function() {
                        items[oldIndex].classList.remove('leaving');
                    }, 500);

                    var card = rot.closest('.card-link');
                    if (card && items[nextIndex].href) {
                        card.setAttribute('data-detail-href', items[nextIndex].href);
                    }

                    currentIndex = nextIndex;
                }

                function start() {
                    if (intervalId) return;
                    intervalId = setInterval(tick, 3000);
                }

                function stop() {
                    if (intervalId) {
                        clearInterval(intervalId);
                        intervalId = null;
                    }
                }

                rot.addEventListener('mouseenter', stop);
                rot.addEventListener('mouseleave', start);

                start();
            });

            // Make the top-row cards and their headings navigate to their index pages
            document.querySelectorAll('.top-row .card-link').forEach(function(card) {
                card.addEventListener('click', function(evt) {
                    if (evt.target.closest('.title-link')) return;
                    var detailUrl = card.getAttribute('data-detail-href');
                    if (detailUrl) window.location.href = detailUrl;
                });

                card.addEventListener('keypress', function(evt) {
                    if (evt.key === 'Enter') {
                        var detailUrl = card.getAttribute('data-detail-href');
                        if (detailUrl) window.location.href = detailUrl;
                    }
                });
            });
        });
    </script>






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
                                    const description = info.event.extendedProps.description ||
                                        'No description available';

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
                                                html +=
                                                    `<img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow">`;
                                            } else if (ext === 'pdf') {
                                                html +=
                                                    `<a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2"><i class="fas fa-file-pdf"></i> Download PDF</a>`;
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
                                        const eventDate =
                                            `${new Date().getFullYear()}-${String(dob.getMonth() + 1).padStart(2, '0')}-${String(dob.getDate()).padStart(2, '0')}`;

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
                    const startOfToday = new Date(today.getFullYear(), today.getMonth(), today
                        .getDate());
                    const cellDate = new Date(info.date.getFullYear(), info.date.getMonth(), info.date
                        .getDate());

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
                            const url =
                                `/announcements/create?centerid=${centerId }&date=${encodeURIComponent(selectedDate)}`;
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
                            holidays,
                            ptms
                        } = arg.event.extendedProps;

                        // helper with icon + badge + type attribute
                        const makeIcon = (emoji, count, color, type) => {
                            if (count === 0) return '';
                            return `
                        <div class="fc-icon-wrapper" data-type="${type}" style="position:relative; display:inline-block; font-size:16px; margin:2px; cursor:pointer;">
                            <span>${emoji}</span>
                            ${count > 1 ? `<span style="
                                                position:absolute; top:-8px; right:-10px;
                                                background:${color}; color:white;
                                                border-radius:50%; padding:2px 4px;
                                                font-size:9px; font-weight:bold;
                                                z-index: 10;
                                            ">${count}</span>` : ''}
                        </div> `;
                        };

                        return {
                            html: `
                        <div style="display:flex; flex-wrap:wrap; gap:6px; justify-content:center; max-width:100%;">
                            ${makeIcon('<i class="fas fa-bullhorn"></i>', announcements.length, '#93a5f6ff', 'announcement')}
                            ${makeIcon('<i class="fas fa-calendar-alt"></i>', normalEvents.length, '#86e191ff', 'event')}
                            ${makeIcon('<i class="fas fa-birthday-cake"></i>', birthdays.length, '#e966a5ff', 'birthday')}
                            ${makeIcon('<i class="fas fa-umbrella-beach"></i>', holidays.length, '#e97d4fff', 'holiday')}
                            ${makeIcon('<i class="fas fa-chalkboard-teacher"></i>', ptms.length, '#c68df7ff', 'ptm')}


                        </div>`
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
                                    holidays,
                                    ptms
                                } = info.event.extendedProps;

                                if (type === 'birthday' && birthdays.length > 0) {
                                    const user = birthdays[0]; // Get first birthday person
                                    const fullName = `${user.name} ${user.lastname || ''}`.trim();
                                    const dob = new Date(user.dob);
                                    const today = new Date();
                                    const age = today.getFullYear() - dob.getFullYear();
                                    
                                    let html = `
                                        <!-- Birthday Cake GIF -->
                                        <div style="text-align: center; margin-bottom: 20px;">
                                            <img src="https://media.giphy.com/media/3o7abldj0b3rxrZUxW/giphy.gif" alt="Birthday Cake Celebration"
                                                class="birthday-gif img-fluid" onclick="triggerCelebration()" style="max-width: 200px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); animation: cakeFloat 2s ease-in-out infinite; cursor: pointer;">
                                        </div>

                                        <!-- Birthday Content Card -->
                                        <div class="birthday-content" style="position: relative; z-index: 1; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 20px; border-radius: 15px; margin-top: 15px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
                                            <div style="text-align: center;">
                                                <div class="birthday-message" style="font-size: 1.1rem; font-weight: 500; margin-bottom: 10px;">
                                                    <i class="fas fa-heart" style="margin-right: 0.5rem;"></i>
                                                    Happy Birthday ${fullName}! 🎉
                                                </div>

                                                <div class="age-counter" id="ageCounter" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 20px; border-radius: 25px; font-size: 1rem; font-weight: 600; display: inline-block; margin: 10px 0; animation: pulse 2s infinite; box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);">
                                                    Turning ${age} Years Old! 🌟
                                                </div>
                                            </div>

                                            <div class="birthday-wishes" style="font-size: 0.95rem; line-height: 1.6; margin-top: 15px; text-align: center;">
                                                May your special day be filled with happiness, laughter, and wonderful memories.
                                                Here's to another year of amazing adventures and beautiful moments! 🎂✨
                                            </div>

                                            <div style="margin-top: 15px; text-align: center;">
                                                <span class="sparkle" onclick="createSparkleEffect(this)" style="display: inline-block; font-size: 2rem; margin: 0 0.5rem; animation: sparkleRotate 2s ease-in-out infinite; cursor: pointer; transition: all 0.3s ease;">🎈</span>
                                                <span class="sparkle" onclick="createSparkleEffect(this)" style="display: inline-block; font-size: 2rem; margin: 0 0.5rem; animation: sparkleRotate 2s ease-in-out infinite; cursor: pointer; transition: all 0.3s ease;">🎁</span>
                                                <span class="sparkle" onclick="createSparkleEffect(this)" style="display: inline-block; font-size: 2rem; margin: 0 0.5rem; animation: sparkleRotate 2s ease-in-out infinite; cursor: pointer; transition: all 0.3s ease;">🎊</span>
                                                <span class="sparkle" onclick="createSparkleEffect(this)" style="display: inline-block; font-size: 2rem; margin: 0 0.5rem; animation: sparkleRotate 2s ease-in-out infinite; cursor: pointer; transition: all 0.3s ease;">🌟</span>
                                            </div>

                                            <button class="celebrate-btn" onclick="launchFireworks()" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); border: none; color: white; padding: 12px 30px; border-radius: 25px; font-weight: 600; margin-top: 15px; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 8px 25px rgba(238, 90, 36, 0.3); width: 100%; position: relative; overflow: hidden;">
                                                🎆 Celebrate More! 🎆
                                            </button>
                                        </div>
                                    `;
                                    
                                    document.getElementById('birthdayModalBody').innerHTML = html;
                                    new bootstrap.Modal(document.getElementById('birthdayModal')).show();
                                }

                                if (type === 'holiday' && holidays.length > 0) {
                                    const formatDate = (dateStr) => {
                                        const date = new Date(dateStr);
                                        return `${String(date.getDate()).padStart(2, '0')} ${date.toLocaleString('en-US', {month: 'long'})} ${date.getFullYear()}`;
                                    };
                                    let html = holidays.map(h => `
                                        <div class="holiday-content-item" style="position: relative; animation: slideInUp 0.5s ease-out;">
                                            <span class="content-label holiday-label">Date:</span><span class="content-value">${formatDate(h.date)}</span><br>
                                            <span class="content-label holiday-label">State:</span><span class="content-value">${h.state || 'N/A'}</span><br>
                                            <span class="content-label holiday-label">Occasion:</span><span class="content-value">${h.occasion || 'Holiday'}</span>
                                        </div>
                                    `).join('');
                                    document.getElementById('holidayContentBody')
                                        .innerHTML = html;
                                    new bootstrap.Modal(document.getElementById(
                                        'holidayModal')).show();
                                }

                                if (type === 'ptm' && ptms.length > 0) {
                                    const formatDate = (dateStr) => {
                                        const date = new Date(dateStr);
                                        return `${String(date.getDate()).padStart(2, '0')} ${date.toLocaleString('en-US', { month: 'long' })} ${date.getFullYear()}`;
                                    };

                                    // Blade provides base for the view/reschedule route:
                                     const ptmViewBase = "{{ route('ptm.viewptm', ['ptm' => ':id']) }}";

                                    let html = ``;

                                    html += ptms.map(p => {
                                        const viewUrl = ptmViewBase.replace(':id', p.id);
                                        return `
                                            <div class="ptm-content-item">
                                                <div>
                                                    <span class="ptm-label"><i class="fas fa-book" style="margin-right: 6px;"></i>Title:</span>
                                                    <span class="ptm-value">${p.title || 'N/A'}</span>
                                                </div>
                                                
                                                <div style="margin-top: 8px;">
                                                    <span class="ptm-label"><i class="fas fa-child" style="margin-right: 6px;"></i>Child:</span>
                                                    <span class="ptm-value">${p.childname || 'N/A'}</span>
                                                </div>
                                                
                                                <div style="margin-top: 8px;">
                                                    <span class="ptm-label"><i class="fas fa-calendar" style="margin-right: 6px;"></i>Date:</span>
                                                    <span class="ptm-value">${formatDate(p.date || p.ptmdate)}${p.slot ? ` (<span style="color: #667eea; font-weight: 600;">${p.slot}</span>)` : ''}</span>
                                                </div>
                                                
                                                <div style="margin-top: 8px;">
                                                    <span class="ptm-label"><i class="fas fa-lightbulb" style="margin-right: 6px;"></i>Objective:</span>
                                                    <span class="ptm-value">${p.objective || 'No objective specified'}</span>
                                                </div>
                                                
                                                <div style="text-align: center; margin-top: 12px;">
                                                    <a href="${viewUrl}" class="btn btn-sm ptm-reschedule-btn" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s ease; box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3); position: relative; overflow: hidden;">
                                                        <span style="position: relative; z-index: 2;">
                                                            <i class="fas fa-calendar-check" style="margin-right: 6px;"></i>
                                                            Reschedule PTM
                                                        </span>
                                                        <style>
                                                            .ptm-reschedule-btn:hover {
                                                                transform: translateY(-2px);
                                                                box-shadow: 0 12px 30px rgba(79, 172, 254, 0.4) !important;
                                                            }
                                                            .ptm-reschedule-btn:active {
                                                                transform: translateY(0);
                                                            }
                                                        </style>
                                                    </a>
                                                </div>
                                            </div>`;
                                    }).join('');

                                    document.getElementById('ptmModalBody').innerHTML =
                                        html;
                                    new bootstrap.Modal(document.getElementById(
                                        'ptmModal')).show();
                                }


                                if (type === 'announcement' && announcements.length >
                                    0) {
                                    let html = announcements.map(item => {
                                        const title = item.title || 'Untitled';
                                        const date = item.eventDate || '';
                                        const description = item.text ||
                                            'No description available';
                                        const rawMedia = item
                                            .announcementMedia || [];
                                        const eventColor = item.eventColor ||
                                            '#1598b3ff';

                                        let mediaHtml = '';
                                        let media = [];
                                        try {
                                            media = typeof rawMedia ===
                                                'string' ? JSON.parse(
                                                    rawMedia) : rawMedia;
                                        } catch {}
                                        if (Array.isArray(media)) {
                                            media.forEach(file => {
                                                const fileUrl = file;
                                                const ext = file.split(
                                                        '.').pop()
                                                    .toLowerCase();
                                                if (['jpg', 'jpeg',
                                                        'png'
                                                    ].includes(ext)) {
                                                    mediaHtml +=
                                                        `<div><img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow show-poster"></div>`;
                                                } else if (ext ===
                                                    'pdf') {
                                                    mediaHtml += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2">
                        <i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                                }
                                            });
                                        }

                                        // update header + bg per announcement
                                        $('#announcementModalLabel').html(
                                            'Announcement');
                                        $('#change-bg').css('background-color',
                                            eventColor);

                                        return `
                                            <div class="announcement-content-item" style="position: relative; animation: slideInUp 0.5s ease-out;">
                                                <span class="content-label announcement-label">Title:</span><span class="content-value">${title}</span><br>
                                                <span class="content-label announcement-label">Date:</span><span class="content-value">${date}</span><br>
                                                <span class="content-label announcement-label">Description:</span><span class="content-value">${description}</span>
                                                ${mediaHtml}
                                            </div>
                                        `;
                                    }).join('');

                                    document.getElementById('announcementContentBody')
                                        .innerHTML = html;
                                    new bootstrap.Modal(document.getElementById(
                                        'announcementModal')).show();
                                }

                                if (type === 'event' && normalEvents.length > 0) {
                                    let html = normalEvents.map(item => {
                                        const title = item.title || 'Untitled';
                                        const date = item.eventDate || '';
                                        const description = item.text ||
                                            'No description available';
                                        const rawMedia = item
                                            .announcementMedia || [];
                                        const eventColor = item.eventColor ||
                                            '#0d6efd';

                                        let mediaHtml = '';
                                        let media = [];
                                        try {
                                            media = typeof rawMedia ===
                                                'string' ? JSON.parse(
                                                    rawMedia) : rawMedia;
                                        } catch {}
                                        if (Array.isArray(media)) {
                                            media.forEach(file => {
                                                const fileUrl = file;
                                                const ext = file.split(
                                                        '.').pop()
                                                    .toLowerCase();
                                                if (['jpg', 'jpeg',
                                                        'png'
                                                    ].includes(ext)) {
                                                    mediaHtml +=
                                                        `<div><img src="${fileUrl}" style="max-width:200px;" class="img-fluid mb-2 shadow show-poster"></div>`;
                                                } else if (ext ===
                                                    'pdf') {
                                                    mediaHtml += `<div><a href="${fileUrl}" target="_blank" class="btn btn-outline-primary btn-sm mb-2">
                    <i class="fas fa-file-pdf"></i> Download PDF</a></div>`;
                                                }
                                            });
                                        }

                                        // update modal header + bg
                                        $('#announcementModalLabel').html(
                                            'Event');
                                        $('#change-bg').css('background-color',
                                            eventColor);

                                        // 🎨 Wrap each event in styled content item
                                        return `
                                            <div class="event-content-item" style="position: relative; animation: slideInUp 0.5s ease-out;">
                                                <span class="content-label event-label">Title:</span><span class="content-value">${title}</span><br>
                                                <span class="content-label event-label">Date:</span><span class="content-value">${date}</span><br>
                                                <span class="content-label event-label">Description:</span><span class="content-value">${description}</span>
                                                ${mediaHtml}
                                            </div>
                                        `;
                                    }).join('');

                                    document.getElementById('eventContentBody')
                                        .innerHTML = html;
                                    new bootstrap.Modal(document.getElementById(
                                        'eventModal')).show();
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
                    fetch('/settings/holidays/events').then(r => r.json()),
                    fetch('/ptm/events').then(r => r.json())
                ])
                .then(([annData, bdayData, holiData, ptmData]) => {
                    const groupedAll = {};

                    if (annData.status && Array.isArray(annData.events)) {
                        const byDate = groupByDate(annData.events, i => i.eventDate);
                        for (const [date, items] of Object.entries(byDate)) {
                            if (!groupedAll[date]) groupedAll[date] = {
                                announcements: [],
                                normalEvents: [],
                                birthdays: [],
                                holidays: [],
                                ptms: []
                            };
                            groupedAll[date].announcements.push(...items.filter(i => i.type ===
                                'announcement'));
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
                                holidays: [],
                                ptms: []
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
                                holidays: [],
                                ptms: []
                            };
                            groupedAll[date].holidays.push(...items);
                        }
                    }

                    if (ptmData.status && Array.isArray(ptmData.events)) {
                        const byDate = groupByDate(ptmData.events, item => {
                            const d = new Date(item.date || item.ptmdate);
                            return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
                        });
                        for (const [date, items] of Object.entries(byDate)) {
                            if (!groupedAll[date]) groupedAll[date] = {
                                announcements: [],
                                normalEvents: [],
                                birthdays: [],
                                holidays: [],
                                ptms: []
                            };
                            groupedAll[date].ptms.push(...items);
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
    @stop
   @include('layout.footer')
