@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title', __('Dashboard'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    {{-- FullCalendar --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

    <link rel="stylesheet" href="{{ asset('assets/css/custom_css/dashboard/index.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Dashboard') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
@endsection

@section('content')
    {{-- <!-- Start row --> --}}

    {{-- Birdthday Wish --}}
    @include('administration.dashboard.partials._birthday_wish')

    @if ($canRecognize)
        @if ($autoShowRecognitionModal)
            @include('administration.dashboard.partials._recognition_urgent_prompt')
        @else
            @include('administration.dashboard.partials._recognition_default_prompt')
        @endif
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
        @include('administration.dashboard.modals.employee_recognition_modal')
    @endif

    {{-- Recognition Congratulation Modal --}}
    @if (auth()->user()->hasPermissionTo('Recognition Read'))
        @include('administration.dashboard.modals.recognize_congrats_modal_multi_users')
    @endif
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

    <!-- Dashboard Calendar JS -->
    <script src="{{ asset('assets/js/custom_js/calendar/dashboard_calendar.js') }}"></script>

    <!-- Lucide Icons for Recognition Notification -->
    @if ($canRecognize || auth()->user()->hasPermissionTo('Recognition Read'))
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    @endif

    <!-- Optimized Dashboard JavaScript -->
    <script src="{{ asset('assets/js/custom_js/dashboard/core.js') }}"></script>
    @if ($canRecognize || auth()->user()->hasPermissionTo('Recognition Read'))
        <script src="{{ asset('assets/js/custom_js/dashboard/recognition.js') }}"></script>
    @endif
@endsection

@section('custom_script')
    <script>
        // Dashboard Calendar Configuration
        window.dashboardCalendarConfig = {
            eventsUrl: '{{ route("administration.dashboard.calendar.events") }}',
            weekendsUrl: '{{ route("administration.dashboard.calendar.weekends") }}',
            taskUrl: '{{ route("administration.task.index") }}',
            currentUserId: {{ Auth::id() }}
        };

        // Dashboard Data Configuration
        window.dashboardData = {
            showEmployeeInfoUpdateModal: {{ $showEmployeeInfoUpdateModal ? 'true' : 'false' }},
            @if ($canRecognize || auth()->user()->hasPermissionTo('Recognition Read'))
            hasRecognitionData: {{ !empty($recognitionData) ? 'true' : 'false' }},
            @endif
            canRecognize: {{ $canRecognize ? 'true' : 'false' }},
            canReadRecognition: {{ auth()->user()->hasPermissionTo('Recognition Read') ? 'true' : 'false' }}
        };
    </script>
@endsection
