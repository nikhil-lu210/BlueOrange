<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="">
    <head>
        {{-- Meta Starts --}}
        @include('layouts.administration.partials.metas')
        {{-- Meta Ends --}}
        
        <title>{{ config('app.name') }} || Not Found</title>
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
                <h4 class="mb-0 mx-2 text-primary text-bold">{{ $exception->getStatusCode() }} Error!</h4>
                <h2 class="mb-1 mx-2">Oops! 😖 The requested URL was not found on this server.</h2>
                @auth
                    @hasexactroles(['Developer'])
                        <p class="mb-4 mx-2">{{ $exception->getMessage() }}</p>
                    @endhasexactroles
                @endauth
                <a href="{{ url()->previous() }}" class="btn btn-primary mb-4">
                    <i class="ti ti-arrow-left" style="font-size: 20px; margin-top: -2px; padding-right: 4px;"></i>
                    Back to Previous Page
                </a>
                <div class="mt-0">
                    <img src="{{ asset('assets/img/illustrations/page-misc-error.png') }}" alt="page-misc-not-authorized" width="200" class="img-fluid" />
                </div>
            </div>
        </div>
        <!-- /Not Authorized -->
    </body>
</html>
