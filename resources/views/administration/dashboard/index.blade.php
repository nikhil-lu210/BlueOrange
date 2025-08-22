@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Dashboard'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    {{-- FullCalendar --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Employee Recognition Modal Styles - slider flow */
        
        .recognize_modal_form_body {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            min-height: 350px;
            position: relative;
        }
        .slide-container {
            position: relative;
            overflow: hidden;
            height: 350px;
        }
        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .slide.active { transform: translateX(0); }
        .slide.slide-left { transform: translateX(-100%); }
        .slide.slide-right { transform: translateX(100%); }
        .step-indicator {
            position: absolute;
            top: -45px;
            left: 15px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 14px;
        }
        .recognition-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }
        .recognize-btn, .recognize-form-next-btn, .recognize-form-submit-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }
        .recognize-btn:hover, .recognize-form-next-btn:hover, .recognize-form-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }
        .recognize-form-back-btn {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
            transition: all 0.3s ease;
        }
        .recognize-form-back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
        }
        .form-select:focus, .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }
        .arrow-bounce { animation: bounce 2s infinite; }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        .pulse-animation { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .points-dropdown {
            background: #8b5cf6;
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
        }
        .points-dropdown:focus {
            background: #7c3aed;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.4);
        }
        .team-badge {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            border-radius: 15px;
            width: 60px;
            height: 60px;
            position: relative;
        }
        .team-badge::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 50%;
        }
        .points-label { font-size: 12px; color: #6b7280; margin-bottom: 5px; }

        /* Custom CSS Here */
        @import url('https://fonts.googleapis.com/css2?family=Satisfy&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap');
        .birthday-wish > * {
            font-family: "Satisfy", cursive;
        }
        .birthday-wish .birthday-message {
            font-family: "Indie Flower", cursive;
            font-size: 24px;
        }

        button[disabled],
        button:disabled,
        button[disabled]:hover {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Table */
        .table-borderless th, .table-bordered th {
            font-weight: bold;
        }

        /* Fix for horizontal scrollbar */
        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }

        /* Ensure content doesn't overflow container */
        .card-body {
            overflow-x: hidden;
            word-wrap: break-word;
        }

        /* Fix for excessive vertical scrolling */
        .content-wrapper {
            min-height: calc(100vh - 150px);
            max-height: 100%;
            overflow-y: auto;
        }

        /* Fix for confetti animation */
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f0f0f0;
            animation: confetti 5s ease-in-out infinite;
            z-index: 10;
            top: 0;
            max-height: 100%;
        }

        /* Prevent overflow on mobile */
        @media (max-width: 768px) {
            .d-flex.justify-content-between.flex-wrap {
                flex-direction: column;
            }

            /* Adjust table for mobile */
            .table-responsive table {
                min-width: 600px;
            }
        }

        /* Fix for any potential overflow issues */
        .row {
            margin-right: 0;
            margin-left: 0;
            width: 100%;
        }

        .container-xxl {
            max-width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        /* Recognition Notification Styles */
        .recognition-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 450px;
            width: 100%;
            animation: slideInRight 0.5s ease-out;
        }

        .recognition-card {
            min-height: 320px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .recognition-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .icon-bg-white {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .bg-white-20 {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .text-white-80 {
            color: rgba(255, 255, 255, 0.8);
        }

        .text-white-90 {
            color: rgba(255, 255, 255, 0.9);
        }

        .text-white-70 {
            color: rgba(255, 255, 255, 0.7);
        }

        .category-badge {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(10px);
        }

        .progress-bar-recognition {
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-bar-recognition::after {
            content: '';
            display: block;
            height: 100%;
            width: 30%;
            background: linear-gradient(90deg, #fff, rgba(255, 255, 255, 0.7));
            animation: progressAnimation 3s ease-in-out infinite;
        }

        @keyframes progressAnimation {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(400%); }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification-closing {
            animation: slideOutRight 0.3s ease-in forwards;
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .recognition-notification {
                right: 10px;
                left: 10px;
                max-width: none;
            }
            
            .recognition-card {
                min-height: 280px;
            }
        }


    </style>

    <style>
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #4ecdc4;
            --text-color: #333;
            --background-color: #f7f7f7;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @keyframes confetti {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(500px) rotate(360deg); }
        }
        .birthday-card {
            /* background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); */
            /* box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); */
            border-radius: 20px;
            /* overflow: hidden; */
            width: 100%;
            margin: 0 auto;
            padding: 0rem;
            text-align: center;
            z-index: 1;
            position: relative;
        }

        .user-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            /* animation: float 3s ease-in-out infinite; */
            position: absolute;
            z-index: 99999999;
            left: -17%;
            top: 25%;
        }

        .birthday-wish {
            width: 100%;
            border-radius: 10px;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            /* animation: float 3s ease-in-out infinite; */
        }

        .message {
            color: white;
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }


    </style>

    <!-- Dashboard Calendar Styles -->
    <style>
        .fc-event{
            cursor: pointer;
        }
        /* Make weekend events non-interactive but keep their appearance */
        .fc-event.weekend-event {
            pointer-events: none;
            cursor: default;
        }
        /* Custom styles for weekend events */
        .fc-event.weekend-event .fc-event-title {
            text-align: center !important;
            width: 100% !important;
            display: block !important;
        }
    </style>

    {{-- Recogntion Card --}}
    <style>
        .recognition-card {
            margin: auto;
        }

        .congrats-card {
            position: relative;
        }

        .ribbon {
            color: white;
            padding: 5px 15px;
            font-weight: bold;
            font-size: 0.9rem;
            border-radius: 20px;
            display: inline-block;
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
    </style>

    {{-- Recognition Notification Styles --}}
    <style>
        :root {
            --recognition-from: #7267f0;
            --recognition-to: #746dc7;
            --success-color: hsl(142, 76%, 36%);
            --warning-color: hsl(48, 96%, 53%);
        }

        .bg-gradient-recognition {
            background: linear-gradient(135deg, var(--recognition-from), var(--recognition-to));
        }

        .bg-gradient-card {
            background: linear-gradient(135deg, hsl(0, 0%, 100%), hsl(220, 13%, 97%));
        }

        .shadow-notification {
            box-shadow: 0 15px 40px -10px rgba(0, 0, 0, 0.2);
        }

        .icon-bg-success {
            background-color: rgba(34, 197, 94, 0.1);
        }
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .progress-bar-recognition {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.6));
            height: 3px;
            border-radius: 2px;
            animation: progressBar 5s linear forwards;
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }

        .recognition-points {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .category-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .notification-body:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
    </style>
    
    <style>
        /* Flip Animation Styles */
        .flip-container {
            perspective: 1000px;
            width: 100%;
            height: 200px;
            margin-top: -10%;
            margin-bottom: 1rem;
            position: relative;
        }

        .flip-card {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.8s ease-in-out;
            animation: flipAnimation 0.8s ease-in-out 2s forwards;
        }

        @keyframes flipAnimation {
            from {
                transform: rotateY(0deg);
            }
            to {
                transform: rotateY(180deg);
            }
        }

        .flip-front, .flip-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .flip-front {
            transform: rotateY(0deg);
        }

        .flip-back {
            transform: rotateY(180deg);
        }

        .medal-gif {
            max-width: 250px;
            height: auto;
            object-fit: contain;
        }

        .profile-circle {
            width: 180px;
            height: 180px;
            background: linear-gradient(135deg, #b2acf6, #8e84f2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(142, 132, 242, 0.3);
        }

        .profile-icon {
            font-size: 80px;
            color: white;
            opacity: 0.9;
        }

        /* Confetti Animation */
        .confetti-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 10;
        }

        .confetti {
            position: absolute;
            width: 8px;
            height: 8px;
            opacity: 0;
        }

        .confetti.active {
            animation: confettiFall 3s linear forwards;
        }

        @keyframes confettiFall {
            0% {
                opacity: 1;
                transform: translateY(-20px) rotate(0deg);
            }
            100% {
                opacity: 0;
                transform: translateY(320px) rotate(360deg);
            }
        }

        .confetti:nth-child(odd) {
            animation-duration: 2.5s;
        }

        .confetti:nth-child(even) {
            animation-duration: 3.5s;
        }

        /* Award Details Styling */
        .award-details {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .award-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #edebff, #f8f7ff);
            border: 1px solid rgba(207, 205, 227, 0.5);
            border-radius: 8px;
            color: #9990f3;
            font-weight: 500;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .award-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.6),
                transparent
            );
            transition: left 0.6s ease;
        }

        .award-badge:hover::before {
            left: 100%;
        }

        .award-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(153, 144, 243, 0.3);
            border-color: rgba(142, 132, 242, 0.8);
        }

        .award-badge i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .award-badge:hover i {
            transform: scale(1.1);
        }

        .points-badge {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            border-color: #ffd700;
        }

        .points-badge:hover {
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
            border-color: #ffd700;
        }

        /* Quote Section Styling */
        .quote-section {
            background: rgba(248, 249, 250, 0.8);
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #8e84f2;
            margin: 1rem 0;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .quote-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(142, 132, 242, 0.03),
                rgba(178, 172, 246, 0.08)
            );
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .quote-section:hover::before {
            opacity: 1;
        }

        .quote-section:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(142, 132, 242, 0.15);
            border-left-color: #b2acf6;
            background: rgba(248, 249, 250, 0.95);
        }

        .quote-section:hover .quote-icon i {
            transform: scale(1.05);
            color: #b2acf6;
        }

        .quote-section:hover blockquote {
            color: #495057;
        }

        .quote-icon i {
            color: #8e84f2;
            transition: all 0.3s ease;
        }

        .quote-section blockquote {
            transition: color 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .role-title {
            background: linear-gradient(135deg, #8e84f2, #b2acf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .recognized-by {
            color: #9990f3;
            font-weight: 500;
            margin-bottom: 1rem;
        }


    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Dashboard') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
@endsection

{{-- Recognition Notification --}}
{{-- @include('administration.dashboard.partials._recognition_notification') --}}

@section('breadcrumb_action')
    <div class="d-flex gap-2">
        @if ($canRecognize)
            <button class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#recognizeCongratsModal">
                <i class="ti ti-badge me-1"></i>
                {{ __('Submit Recognition') }}
            </button>
        @endif
        
        {{-- <button class="btn btn-outline-info btn-md" onclick="testRecognitionNotification()">
            <i class="ti ti-bell me-1"></i>
            Test Notification
        </button> --}}
    </div>
@endsection



@section('content')
    {{-- <!-- Start row --> --}}

    {{-- Birdthday Wish --}}
    @include('administration.dashboard.partials._birthday_wish')

    @include('administration.dashboard.partials._recognition_congratulation')

    {{-- @include('administration.dashboard.partials._recognition_form') --}}

    @if ($autoShowRecognitionModal) 
        @include('administration.dashboard.partials._recognition_urgent_prompt')
    @endif

    @if (!$autoShowRecognitionModal) 
    @include('administration.dashboard.partials._recognition_default_prompt')
    @endif

    {{-- Upcoming Birthdays --}}
    @include('administration.dashboard.partials._upcoming_birthdays')

    {{-- Attendance Summary and Clockin-Clockout --}}
    @include('administration.dashboard.partials._attendance_summary')

    {{-- Currently Working || On Leave Today || Absent Today --}}
    <div class="row mb-4">
        @include('administration.dashboard.partials._currently_working')

        @include('administration.dashboard.partials._on_leave_today')

        @include('administration.dashboard.partials._absent_today')
    </div>

    {{-- Calendar --}}
    @include('administration.dashboard.partials._calendar')

    {{-- Attendances for running month --}}
    @include('administration.dashboard.partials._running_month_attendance')


    {{-- Employee Info Update Modal --}}
    @if ($showEmployeeInfoUpdateModal)
        @include('administration.dashboard.modals.employee_info_update_modal')
    @endif


    @if ($canRecognize)
        {{-- @include('administration.dashboard.modals.employee_recognition_modal') --}}
        @include('administration.dashboard.modals.employee_recognition_modal_form')
    @endif

    @include('administration.dashboard.modals.recognize_congrats_modal')
    {{-- <!-- End row --> --}}
@endsection



@section('script_links')
    {{--  External Javascript Links --}}

    <!-- Page JS -->
    <script src="{{ asset('assets/js/cards-actions.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <!-- Calendar Dependencies -->
    <script src='https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <!-- Dashboard Calendar Configuration -->
    <script>
        // Configuration object for the dashboard calendar
        const dashboardCalendarConfig = {
            eventsUrl: '{{ route("administration.dashboard.calendar.events") }}',
            weekendsUrl: '{{ route("administration.dashboard.calendar.weekends") }}',
            taskUrl: '{{ route("administration.task.index") }}',
            currentUserId: {{ Auth::id() }}
        };
    </script>

    <!-- Dashboard Calendar JS -->
    <script src="{{ asset('assets/js/custom_js/calendar/dashboard_calendar.js') }}"></script>

    <!-- Lucide Icons for Recognition Notification -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

@endsection

@section('custom_script')
    {{-- External Custom JS For Modal flip animation --}}
    <script>
        // Confetti colors
        const confettiColors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b', '#eb4d4b', '#6c5ce7', '#a29bfe', '#fd79a8', '#fdcb6e'];
        
        // Create confetti function
        function createConfetti() {
            const container = document.getElementById('confettiContainer');
            const containerRect = container.getBoundingClientRect();
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.classList.add('confetti');
                
                // Random position across the width
                confetti.style.left = Math.random() * 100 + '%';
                
                // Random color
                confetti.style.backgroundColor = confettiColors[Math.floor(Math.random() * confettiColors.length)];
                
                // Random delay for staggered effect
                confetti.style.animationDelay = Math.random() * 2 + 's';
                
                // Random size variation
                const size = Math.random() * 6 + 4;
                confetti.style.width = size + 'px';
                confetti.style.height = size + 'px';
                
                // Sometimes make rectangles instead of squares
                if (Math.random() > 0.5) {
                    confetti.style.height = size * 0.6 + 'px';
                }
                
                container.appendChild(confetti);
                
                // Activate confetti with slight delay
                setTimeout(() => {
                    confetti.classList.add('active');
                }, i * 50);
            }
            
            // Clean up confetti after animation
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        // Modal event handlers
        document.getElementById('recognizeCongratsModal').addEventListener('show.bs.modal', function() {
            const flipCard = this.querySelector('.flip-card');
            
            // Reset flip animation
            flipCard.style.animation = 'none';
            flipCard.offsetHeight; // Trigger reflow
            flipCard.style.animation = 'flipAnimation 0.5s ease-in-out 1s forwards';
            
            // Start confetti after a short delay
            setTimeout(() => {
                createConfetti();
            }, 300);
        });

        // Reset card position when modal is closed
        document.getElementById('recognizeCongratsModal').addEventListener('hidden.bs.modal', function() {
            const flipCard = this.querySelector('.flip-card');
            const confettiContainer = document.getElementById('confettiContainer');
            
            // Reset flip card
            flipCard.style.animation = 'none';
            flipCard.style.transform = 'rotateY(0deg)';
            
            // Clear any remaining confetti
            confettiContainer.innerHTML = '';
        });
    </script>

    {{--  External Custom Javascript  --}}
    <script>
        $(document).on('shown.bs.modal', function (e) {
            $(e.target).find('.select2').select2({
                dropdownParent: $(e.target),
                width: '100%'
            });
        });
    </script>

    {{-- @if($autoShowRecognitionModal)
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let recognitionModal = new bootstrap.Modal(document.getElementById('recognitionModal'));
                recognitionModal.show();
            });
        </script>
    @endif --}}

    <script>
        // ShowLiveTime
        $(document).ready(function() {
            // Function to update the clock
            function updateTime() {
                var currentTime = new Date();

                // Format hours, minutes, and seconds with leading zeros
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();
                var seconds = currentTime.getSeconds();

                // Convert to 12-hour format and determine AM/PM
                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'

                // Add leading zeros to minutes and seconds if needed
                minutes = minutes < 10 ? '0'+minutes : minutes;
                seconds = seconds < 10 ? '0'+seconds : seconds;

                // Create the time string in the format HH:MM:SS AM/PM
                var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

                // Update the content of the #liveTime element
                $('#liveTime').text(timeString);
            }

            // Call the updateTime function every second (1000 milliseconds)
            setInterval(updateTime, 1000);

            // Call the function initially to show time immediately when the page loads
            updateTime();
        });
    </script>

    <script>
        // LiveClockInTimeCount
        $(document).ready(function() {
            const liveWorkingTimeElement = $('#liveWorkingTime');

            if (liveWorkingTimeElement.length) {
                const clockInAt = parseInt(liveWorkingTimeElement.data('clock-in-at')) * 1000; // Convert to milliseconds

                // Function to calculate and display the elapsed time
                function updateliveWorkingTime() {
                    const now = new Date().getTime();
                    const elapsed = now - clockInAt;

                    // Calculate hours, minutes, and seconds
                    const hours = Math.floor(elapsed / (1000 * 60 * 60));
                    const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);

                    // Format the time as hh:mm:ss
                    const formattedTime =
                        String(hours).padStart(2, '0') + ':' +
                        String(minutes).padStart(2, '0') + ':' +
                        String(seconds).padStart(2, '0');

                    liveWorkingTimeElement.text(formattedTime);
                }

                // Update the time every second
                updateliveWorkingTime(); // Initial call
                setInterval(updateliveWorkingTime, 1000); // Update every second
            }
        });
    </script>

    <script>
        // Recognition Notification Functions
        function showRecognitionNotification() {
            const notification = document.getElementById('recognitionNotification');
            notification.classList.remove('d-none');
            
            // Auto close notification after 5 seconds
            setTimeout(() => {
                closeRecognitionNotification();
            }, 5000);
        }

        function closeRecognitionNotification() {
            const notification = document.getElementById('recognitionNotification');
            notification.classList.add('notification-closing');
            
            setTimeout(() => {
                notification.classList.add('d-none');
                notification.classList.remove('notification-closing');
            }, 300);
        }

        // Global function to show notification with custom data
        function showCustomRecognition(data) {
            const notification = document.getElementById('recognitionNotification');
            
            // Update notification content with real data
            notification.querySelector('.fw-bold').textContent = data.employeeName || 'Employee Name';
            notification.querySelector('.recognition-points span').textContent = `${data.points || 0} pts`;
            notification.querySelector('.fw-medium').nextSibling.textContent = data.recognizedBy || 'Manager';
            notification.querySelector('.category-badge').innerHTML = `
                <i data-lucide="${data.categoryIcon || 'award'}" style="width: 16px; height: 16px;" class="me-2"></i>
                ${data.category || 'Recognition'}
            `;
            notification.querySelector('.fst-italic').textContent = `"${data.message || 'Great work!'}"`;
            
            // Reinitialize icons and show
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            notification.classList.remove('d-none');
            
            // Auto close after 5 seconds
            setTimeout(() => {
                closeRecognitionNotification();
            }, 5000);
        }

        // Initialize Lucide icons when page loads
        $(document).ready(function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            // Don't show notification automatically - only show when called with real data
            // The notification will be hidden by default and only shown when showCustomRecognition() is called
            
            // Example usage:
            // showCustomRecognition({
            //     employeeName: 'John Doe',
            //     points: 500,
            //     recognizedBy: 'Jane Smith',
            //     categoryIcon: 'star',
            //     category: 'Excellence',
            //     message: 'Outstanding performance this month!'
            // });
        });

        // Test function for recognition notification
        function testRecognitionNotification() {
            showCustomRecognition({
                employeeName: 'John Doe',
                points: 500,
                recognizedBy: 'Jane Smith',
                categoryIcon: 'star',
                category: 'Excellence',
                message: 'Outstanding performance this month! Keep up the great work!'
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            let submitting = false;

            function handleSubmit(buttonClass, attendanceType) {
                $(buttonClass).click(function () {
                    if (submitting) return; // Prevent double click
                    submitting = true;

                    $('#attendanceType').val(attendanceType);
                    $(this).prop('disabled', true); // Disable button
                    $(this).closest('form').submit();
                });
            }

            handleSubmit('.submit-regular', 'Regular');
            handleSubmit('.submit-overtime', 'Overtime');
        });
    </script>

    <script>
        function createConfettiBirthday() {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
            confetti.style.opacity = Math.random();
            confetti.style.transform = `rotate(${Math.random() * 360}deg)`;

            // Limit confetti to the content area instead of body
            document.querySelector('.content-wrapper').appendChild(confetti);

            setTimeout(() => {
                confetti.remove();
            }, 5000);
        }

        // Only create confetti if it's a birthday
        if (document.querySelector('.birthday-wish-container')) {
            setInterval(createConfettiBirthday, 200);
        }
    </script>



    @if ($showEmployeeInfoUpdateModal)
        <script>
            $(document).ready(function () {
                // Show the modal
                $('#employeeInfoUpdateModal').modal('show');

                // Wait for the modal to be shown, then initialize Select2
                $('#employeeInfoUpdateModal').on('shown.bs.modal', function () {
                    // Initialize blood group Select2
                    $('#blood_group').select2({
                        dropdownParent: $('#employeeInfoUpdateModal'),
                        width: '100%'
                    });

                    // Initialize institute Select2 with tagging
                    $('#institute_id').select2({
                        dropdownParent: $('#employeeInfoUpdateModal'),
                        tags: true,
                        tokenSeparators: [],
                        createTag: function (params) {
                            var term = $.trim(params.term);
                            if (term === '') {
                                return null;
                            }
                            return {
                                id: 'new:' + term,
                                text: term + ' (New Institute)',
                                newTag: true
                            };
                        },
                        templateResult: function (data) {
                            var $result = $('<span></span>');
                            $result.text(data.text);
                            if (data.newTag) {
                                $result.append(' <em>(will be created)</em>');
                            }
                            return $result;
                        },
                        insertTag: function (data, tag) {
                            data.push(tag);
                        },
                        width: '100%'
                    });

                    // Initialize education level Select2 with tagging
                    $('#education_level_id').select2({
                        dropdownParent: $('#employeeInfoUpdateModal'),
                        tags: true,
                        tokenSeparators: [],
                        createTag: function (params) {
                            var term = $.trim(params.term);
                            if (term === '') {
                                return null;
                            }
                            return {
                                id: 'new:' + term,
                                text: term + ' (New Education Level)',
                                newTag: true
                            };
                        },
                        templateResult: function (data) {
                            var $result = $('<span></span>');
                            $result.text(data.text);
                            if (data.newTag) {
                                $result.append(' <em>(will be created)</em>');
                            }
                            return $result;
                        },
                        insertTag: function (data, tag) {
                            data.push(tag);
                        },
                        width: '100%'
                    });
                });
            });
        </script>
    @endif

    <script>
        // Employee Recognition - Slider Logic
        (function() {
            let currentStepNumber = 1;
            let selectedUser = '';
            let selectedCategory = '';

            function updateStepIndicator() {
                const el = document.getElementById('currentStep');
                if (el) el.textContent = currentStepNumber;
            }

            window.nextStep = function() {
                if (currentStepNumber < 4) {
                    const currentSlide = document.getElementById(`step${currentStepNumber}`);
                    const nextSlide = document.getElementById(`step${currentStepNumber + 1}`);
                    if (!currentSlide || !nextSlide) return;
                    currentSlide.classList.remove('active');
                    currentSlide.classList.add('slide-left');
                    nextSlide.classList.remove('slide-right');
                    nextSlide.classList.add('active');
                    currentStepNumber++;
                    updateStepIndicator();

                    if (currentStepNumber === 4 && selectedUser) {
                        const userSelect = document.getElementById('userSelect');
                        if (userSelect) {
                            const selectedUserText = userSelect.options[userSelect.selectedIndex].text.split(' - ')[0];
                            const title = document.getElementById('recognitionTitle');
                            if (title) title.textContent = `You are now recognizing ${selectedUserText}`;
                        }
                    }
                }
            };

            window.prevStep = function() {
                if (currentStepNumber > 1) {
                    const currentSlide = document.getElementById(`step${currentStepNumber}`);
                    const prevSlide = document.getElementById(`step${currentStepNumber - 1}`);
                    if (!currentSlide || !prevSlide) return;
                    currentSlide.classList.remove('active');
                    currentSlide.classList.add('slide-right');
                    prevSlide.classList.remove('slide-left');
                    prevSlide.classList.add('active');
                    currentStepNumber--;
                    updateStepIndicator();
                }
            };

            function enableDisableNext(selectId, btnId, setter) {
                const select = document.getElementById(selectId);
                const btn = document.getElementById(btnId);
                if (!select || !btn) return;
                select.addEventListener('change', function() {
                    if (this.value) {
                        setter(this.value);
                        btn.disabled = false;
                        btn.classList.remove('opacity-50');
                    } else {
                        setter('');
                        btn.disabled = true;
                        btn.classList.add('opacity-50');
                    }
                });
            }

            enableDisableNext('userSelect', 'userNextBtn', v => { selectedUser = v; });
            enableDisableNext('categorySelect', 'categoryNextBtn', v => { selectedCategory = v; });

            window.submitRecognition = function() {
                const userSelect = document.getElementById('userSelect');
                const categorySelect = document.getElementById('categorySelect');
                const pointsSelect = document.getElementById('pointsSelect');
                const messageText = (document.getElementById('messageText')?.value || '').trim();
                const selectedUserText = userSelect && userSelect.selectedIndex >= 0 ? userSelect.options[userSelect.selectedIndex].text : '';
                const selectedCategoryText = categorySelect && categorySelect.selectedIndex >= 0 ? categorySelect.options[categorySelect.selectedIndex].text : '';
                const selectedPoints = pointsSelect ? pointsSelect.value : '';

                const modalEl = document.getElementById('recognitionModal');
                if (modalEl && window.bootstrap) {
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                }

                setTimeout(() => {
                    alert(`🎉 Recognition Submitted Successfully!\n\n👤 Employee: ${selectedUserText}\n🏆 Category: ${selectedCategoryText}\n⭐ Points: ${selectedPoints}\n💬 Message: ${messageText || 'No message provided'}`);
                }, 300);

                resetModal();
            };

            function resetModal() {
                currentStepNumber = 1;
                updateStepIndicator();
                document.querySelectorAll('#recognitionModal .slide').forEach((slide, index) => {
                    slide.classList.remove('active', 'slide-left', 'slide-right');
                    if (index === 0) slide.classList.add('active');
                    else slide.classList.add('slide-right');
                });
                const userSelect = document.getElementById('userSelect');
                const categorySelect = document.getElementById('categorySelect');
                const messageText = document.getElementById('messageText');
                const pointsSelect = document.getElementById('pointsSelect');
                if (userSelect) userSelect.value = '';
                if (categorySelect) categorySelect.value = '';
                if (messageText) messageText.value = '';
                if (pointsSelect) pointsSelect.value = '1000';
                const userNextBtn = document.getElementById('userNextBtn');
                const categoryNextBtn = document.getElementById('categoryNextBtn');
                if (userNextBtn) { userNextBtn.disabled = true; userNextBtn.classList.add('opacity-50'); }
                if (categoryNextBtn) { categoryNextBtn.disabled = true; categoryNextBtn.classList.add('opacity-50'); }
                selectedUser = '';
                selectedCategory = '';
            }

            document.getElementById('recognitionModal')?.addEventListener('hidden.bs.modal', resetModal);

            // Initialize disabled button opacity on load
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('userNextBtn')?.classList.add('opacity-50');
                document.getElementById('categoryNextBtn')?.classList.add('opacity-50');
            });
        })();
    </script>
@endsection
