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
        <!-- Content -->

        <!-- Not Authorized -->
        <div class="container">
            <div class="misc-wrapper">
                <h4 class="mb-0 mx-2 text-primary text-bold">{{ $exception->getStatusCode() }} Error!</h4>
                <h2 class="mb-1 mx-2">You are not authorized!</h2>
                <p class="mb-4 mx-2">{{ $exception->getMessage() }}</p>
                <a href="{{ route('administration.dashboard.index') }}" class="btn btn-primary mb-4">
                    <i class="ti ti-home" style="font-size: 18px; margin-top: -3px; padding-right: 4px;"></i>
                    Back to Dashboard
                </a>
                <div class="mt-0">
                    <img src="{{ asset('assets/img/illustrations/page-misc-you-are-not-authorized.png') }}" alt="page-misc-not-authorized" width="110" class="img-fluid" />
                </div>
            </div>
        </div>
        <!-- /Not Authorized -->
    </body>
</html>
