<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="">
    <head>
        {{-- Meta Starts --}}
        @include('layouts.administration.partials.metas')
        {{-- Meta Ends --}}
        
        <title>{{ config('app.name') }} || IP Unauthorized</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset(config('app.favicon')) }}" />

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
                <h4 class="mb-0 mx-2 text-danger text-bold">Unauthorized IP Address!</h4>
                <h2 class="mb-1 mx-2">Oops! 😖 You are trying to access the site by unauthorized IP.</h2>
                <p class="mb-4 mx-2">{{ __('The site is IP restricted. You cannot access the site by this IP. Please use Office\'s Computer / Tablets.') }}</p>
                <a href="{{ url()->previous() }}" class="btn btn-primary mb-4">
                    <i class="ti ti-arrow-left" style="font-size: 20px; margin-top: -2px; padding-right: 4px;"></i>
                    Back to Previous Page
                </a>
                <div class="mt-0">
                    <img src="{{ asset('assets/img/illustrations/page-misc-you-are-not-authorized.png') }}" alt="page-misc-not-authorized" width="150" class="img-fluid" />
                </div>
            </div>
        </div>
        <!-- /Not Authorized -->
    </body>
</html>
