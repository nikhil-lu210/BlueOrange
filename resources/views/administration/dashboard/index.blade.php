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

{{-- Recognition Notification --}}
@include('administration.dashboard.partials._recognition_notification')

@section('content')
    {{-- <!-- Start row --> --}}

    {{-- Birdthday Wish --}}
    @include('administration.dashboard.partials._birthday_wish')

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
        @include('administration.dashboard.modals.employee_recognition_modal')
        {{-- @include('administration.dashboard.modals.employee_recognition_modal_form') --}}
    @endif

    {{-- Recognition Congratulation Modal --}}
    @include('administration.dashboard.modals.recognize_congrats_modal_multi_users')
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
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <script src="{{ asset('assets/js/custom_js/dashboard/index.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External js  --}}

    <script>
        /* Dashboard Calendar Configuration
        Configuration object for the dashboard calendar */

        const dashboardCalendarConfig = {
            eventsUrl: '{{ route("administration.dashboard.calendar.events") }}',
            weekendsUrl: '{{ route("administration.dashboard.calendar.weekends") }}',
            taskUrl: '{{ route("administration.task.index") }}',
            currentUserId: {{ Auth::id() }}
        };

        @if ($showEmployeeInfoUpdateModal)

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

        @endif

        @if ($recognitionData)
            $(document).ready(function () {
                // Show the modal
                $('#recognizeCongratsModal').modal('show');
            });
        @endif
    </script>
@endsection
