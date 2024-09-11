<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="">
    <head>
        {{-- Meta Starts --}}
        @include('layouts.administration.partials.metas')
        {{-- Meta Ends --}}
        
        <title>{{ config('app.name') }} || Not Authorized</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('Logo/logo_white_01.png') }}" />

        <!-- Start css -->
        @include('layouts.administration.partials.stylesheet')
        <!-- End css -->

        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
    </head>

    <body>
        <div class="container">
            <div class="misc-wrapper">
                <h2 class="mb-1 mx-2">Under Maintenance!</h2>
                <p class="mb-4 mx-2">Sorry for the inconvenience but we're performing some maintenance at the moment</p>
                @auth
                    <a href="{{ route('administration.dashboard.index') }}" class="btn btn-primary mb-4">Go To Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary mb-4">Login</a>
                @endauth
                <div class="mt-4">
                    <img src="{{ asset('assets/img/illustrations/page-misc-under-maintenance.png') }}" alt="page-misc-under-maintenance"
                        width="550" class="img-fluid" />
                </div>
            </div>
        </div>


        @include('sweetalert::alert')
    </body>
</html>
