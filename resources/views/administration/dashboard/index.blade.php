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
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Dashboard') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
@endsection

@section('breadcrumb_action')
    @if (auth()->user()->tl_employees && auth()->user()->tl_employees->count() > 0)
        <button class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#recognitionModal">
            <i class="ti ti-badge me-1"></i>
            {{ __('Submit Recognition') }}
        </button>
    @endif
@endsection



@section('content')
{{-- <!-- Start row --> --}}

{{-- Birdthday Wish --}}
@include('administration.dashboard.partials._birthday_wish')


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

@if (auth()->user()->tl_employees && auth()->user()->tl_employees->count() > 0)
    @include('administration.dashboard.modals.employee_recognition_modal')
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

@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).on('shown.bs.modal', function (e) {
            $(e.target).find('.select2').select2({
                dropdownParent: $(e.target),
                width: '100%'
            });
        });
    </script>

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
        function createConfetti() {
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
            setInterval(createConfetti, 200);
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
                        width: '100%' // Optional: ensures it fits the container
                    });

                    // Initialize institute Select2 with tagging
                    $('#institute_id').select2({
                        dropdownParent: $('#employeeInfoUpdateModal'),
                        tags: true,
                        tokenSeparators: [], // Remove space and comma separators
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
                            // Only insert if user explicitly selects the tag
                            data.push(tag);
                        },
                        width: '100%'
                    });

                    // Initialize education level Select2 with tagging
                    $('#education_level_id').select2({
                        dropdownParent: $('#employeeInfoUpdateModal'),
                        tags: true,
                        tokenSeparators: [], // Remove space and comma separators
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
                            // Only insert if user explicitly selects the tag
                            data.push(tag);
                        },
                        width: '100%'
                    });
                });
            });
        </script>
    @endif
@endsection
