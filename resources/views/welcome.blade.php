<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="">
    <head>
        {{-- Meta Starts --}}
        @include('layouts.administration.partials.metas')
        {{-- Meta Ends --}}

        <title>{{ config('app.name') }} || Welcome</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset(config('app.favicon')) }}" />

        <!-- Start css -->
        @include('layouts.administration.partials.stylesheet')
        <!-- End css -->

        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
        <style>
            .clock-container {
                text-align: center;
                padding: 1rem;
            }
            .clock {
                font-size: 4rem;
                font-weight: bold;
                color: #696cff;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            }
            .date {
                font-size: 1.5rem;
                color: #697a8d;
                margin-bottom: 0.5rem
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="misc-wrapper">
                <div class="clock-container">
                    <div class="clock" id="clock"></div>
                    <div class="date" id="date"></div>
                </div>
                @auth
                    <a href="{{ route('administration.dashboard.index') }}" class="btn btn-primary mb-3">Go To Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary mb-3 text-uppercase text-bold">{{ config('app.name') }} LOGIN</a>
                @endauth
                <div class="mt-4">
                    <img src="{{ asset('assets/img/illustrations/page-misc-under-maintenance.png') }}" alt="clock-illustration" width="500" class="img-fluid" />
                </div>
            </div>
        </div>

        <script>
            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const timeString = `${hours}:${minutes}:${seconds}`;

                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const dateString = now.toLocaleDateString('en-US', options);

                document.getElementById('clock').textContent = timeString;
                document.getElementById('date').textContent = dateString;
            }

            // Update the clock immediately and then every second
            updateClock();
            setInterval(updateClock, 1000);
        </script>

        @include('sweetalert::alert')
    </body>
</html>
