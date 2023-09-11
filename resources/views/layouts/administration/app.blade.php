<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="">
    <head>
        {{-- Meta Starts --}}
        @include('layouts.administration.partials.metas')
        {{-- Meta Ends --}}
        
        <title>{{ config('app.name') }} || @yield('page_title')</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('Logo/logo_white_01.png') }}" />

        <!-- Start css -->
        @include('layouts.administration.partials.stylesheet')
        <!-- End css -->
    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                <!-- Start Sidebar -->
                @include('layouts.administration.partials.sidenav')
                <!-- End Sidebar -->
                <!-- / Menu -->

                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Start Top Navbar -->
                    @include('layouts.administration.partials.topnav')
                    <!-- End Top Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->

                        <div class="container-xxl flex-grow-1 container-p-y">
                            <!-- Start Breadcrumbbar -->
                            @include('layouts.administration.partials.breadcrumb')
                            <!-- End Breadcrumbbar -->
                            
                            <!-- Start row -->
                            @yield('content')
                            <!-- End row -->
                        </div>
                        <!-- / Content -->

                        <!-- Start Footerbar -->
                        {{-- @include('layouts.administration.partials.footer') --}}
                        <!-- End Footerbar -->

                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Start js -->
        @include('layouts.administration.partials.scripts')
        <!-- End js -->

        {{-- Sweetalert --}}
        @include('sweetalert::alert')
    </body>
</html>