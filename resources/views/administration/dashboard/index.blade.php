@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Dashboard'))

@section('css_links')
    {{--  External CSS  --}}
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

        /* Table */
        .table-borderless th, .table-bordered th {
            font-weight: bold;
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
            100% { transform: translateY(100vh) rotate(360deg); }
        }
        .birthday-card {
            /* background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); */
            /* box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); */
            border-radius: 20px;
            overflow: hidden;
            width: 100%;
            margin: 0 auto;
            padding: 0rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .user-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: float 3s ease-in-out infinite;
        }

        .birthday-wish {
            width: 100%;
            border-radius: 10px;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: float 3s ease-in-out infinite;
        }

        .message {
            color: white;
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f0f0f0;
            animation: confetti 5s ease-in-out infinite;
        }
    </style>
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


{{-- Attendance Summary and Clockin-Clockout --}}
@include('administration.dashboard.partials._attendance_summary')

{{-- Currently Working || On Leave Today || Absent Today --}}
<div class="row mb-4">
    @include('administration.dashboard.partials._currently_working')

    @include('administration.dashboard.partials._absent_today')

    @include('administration.dashboard.partials._on_leave_today')
</div>


{{-- Attendances for running month --}}
@include('administration.dashboard.partials._running_month_attendance')

{{-- <!-- End row --> --}}
@endsection



@section('script_links')
    {{--  External Javascript Links --}}

    <!-- Page JS -->
    <script src="{{ asset('assets/js/cards-actions.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
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
        $(document).ready(function() {
            // $('form').on('submit', function(e) {
            //     e.preventDefault(); // Prevent default submission
            // });

            $('.submit-regular').click(function() {
                $('#attendanceType').val('Regular');
                $(this).closest('form').submit();
            });

            $('.submit-overtime').click(function() {
                $('#attendanceType').val('Overtime');
                $(this).closest('form').submit();
            });
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

            document.body.appendChild(confetti);

            setTimeout(() => {
                confetti.remove();
            }, 5000);
        }

        setInterval(createConfetti, 200);
    </script>
@endsection
