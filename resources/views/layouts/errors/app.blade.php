<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="">
    <head>
        {{-- Meta Starts --}}
        @include('layouts.administration.partials.metas')
        {{-- Meta Ends --}}
        
        <title>{{ config('app.name') }} || @yield('page_title', 'Error')</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset(config('app.favicon')) }}" />

        <!-- Start css -->
        @include('layouts.administration.partials.stylesheet')
        <!-- End css -->
        
        <!-- Error Page Specific Styles -->
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: "Public Sans", sans-serif;
                background-color: #f8f9fa;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }

            .error-code {
                font-size: clamp(50px, 15vw, 300px); 
                font-weight: bold;
                background: linear-gradient(118.28deg, #7367F0 27.35%, #9D94F4 85.96%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                text-align: center;
                line-height: 1;
            }
            
            .error-title {
                margin-top: -20px;
                color: #333333;
                font-size: 64px;
                font-weight: 500;
                text-align: center;
            }

            .error-message {
                color: #5d596c;
                font-size: 21px;
                font-weight: 500;
                text-align: center;
                line-height: 1.6;
            }

            .btn-error {
                background: linear-gradient(118.28deg, #7367F0 27.35%, #9D94F4 85.96%);
                border-radius: 6px;
                padding: 14px 30px;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 8px rgba(115, 103, 240, 0.2);
                text-decoration: none;
                display: inline-block;
                border: none;
            }

            .btn-error:hover {
                text-decoration: none;
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(115, 103, 240, 0.3);
            }

            .btn-error-text {
                color: #ffffff;
                font-size: 16px;
                font-weight: 500;
                text-align: center;
            }

            /* Responsive */
            @media (max-width: 900px) {
                .error-title {
                    font-size: 48px;
                }
                .error-message {
                    font-size: 18px;
                }
            }

            @media (max-width: 600px) {
                .error-title {
                    font-size: 36px;
                }
                .error-message {
                    font-size: 16px;
                }
            }
        </style>
        
        @yield('additional_styles')
    </head>

    <body>
        <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card shadow-lg border-0 rounded-3">
                            <div class="card-body p-4 p-md-5">
                                <div class="row align-items-center g-4">
                                    @yield('error_content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start js -->
        @include('layouts.administration.partials.scripts')
        <!-- End js -->
        
        @yield('additional_scripts')
    </body>
</html>
