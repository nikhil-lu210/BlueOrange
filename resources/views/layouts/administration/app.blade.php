<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Meta Starts --}}
        @include('layouts.administration.partials.metas')
        {{-- Meta Ends --}}
        
        {{--  Page Title  --}}
        <title> {{ config('app.name') }} | {{ __('Role_Name') }} | @yield('page_title') </title>
        <!-- Fevicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

        <!-- Start css -->
        @include('layouts.administration.partials.stylesheet')
        <!-- End css -->
    </head>

    
    <body class="vertical-layout">
        <!-- Start Containerbar -->
        <div id="containerbar">
            <!-- Start Leftbar -->
            @include('layouts.administration.partials.sidenav')
            <!-- End Leftbar -->
            
            <!-- Start Rightbar -->
            <div class="rightbar">
                <!-- Start Topbar Mobile -->
                @include('layouts.administration.partials.topnav_mobile')
                <!-- Start Topbar -->
                @include('layouts.administration.partials.topnav')
                <!-- End Topbar -->

                <!-- Start Breadcrumbbar -->
                @include('layouts.administration.partials.breadcrumb')
                <!-- End Breadcrumbbar -->

                <!-- Start Contentbar -->
                <div class="contentbar">
                    <!-- Start row -->
                        @yield('content')
                    <!-- End row -->
                </div>
                <!-- End Contentbar -->

                <!-- Start Footerbar -->
                @include('layouts.administration.partials.footer')
                <!-- End Footerbar -->
            </div>
            <!-- End Rightbar -->
        </div>
        <!-- End Containerbar -->



        <!-- Start js -->
        @include('layouts.administration.partials.scripts')
        <!-- End js -->

        @include('sweetalert::alert')
    </body>
</html>
