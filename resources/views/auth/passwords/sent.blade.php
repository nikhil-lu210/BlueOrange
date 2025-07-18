<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="">
    <head>
        <meta charset="utf-8" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>{{ config('app.name') }} || {{ __('Password Reset Link Sent') }}</title>

        <meta name="description" content="Blue Orange Web Application | Staff-India" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset(config('app.favicon')) }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />

        <!-- Page CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

        <!-- Helpers -->
        <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
        <script src="{{ asset('assets/js/config.js') }}"></script>
    </head>

    <body>
        <!-- Content -->
        <div class="authentication-wrapper authentication-cover authentication-bg">
            <div class="authentication-inner row">
                <!-- /Left Text -->
                <div class="d-none d-lg-flex col-lg-7 p-0">
                    <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                        <img
                            src="{{ asset('assets/img/illustrations/auth-forgot-password-illustration-light.png') }}"
                            alt="auth-password-sent-cover"
                            class="img-fluid my-5 auth-illustration"
                            data-app-light-img="illustrations/auth-forgot-password-illustration-light.png"
                            data-app-dark-img="auth-forgot-password-illustration-dark.png"
                        />

                        <img
                            src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
                            alt="auth-password-sent-cover"
                            class="platform-bg"
                            data-app-light-img="illustrations/bg-shape-image-light.png"
                            data-app-dark-img="illustrations/bg-shape-image-dark.png"
                        />
                    </div>
                </div>
                <!-- /Left Text -->

                <!-- Password Reset Link Sent -->
                <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                    <div class="w-px-400 mx-auto">
                        <!-- Logo -->
                        <div class="app-brand mb-4">
                            <a href="{{ route('login') }}" class="app-brand-link gap-2">
                                <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" width="30%">
                            </a>
                        </div>
                        <!-- /Logo -->

                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="ti ti-mail-check text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="mb-1 text-success">Password Reset Link Sent! ✉️</h3>
                            <p class="mb-4 text-muted">
                                We have sent a password reset link to your official email address.
                            </p>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ti ti-user-circle text-primary me-2" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <small class="text-muted">Alias Name</small>
                                        <h6 class="mb-0">{{ session('alias_name') }}</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-mail text-info me-2" style="font-size: 1.2rem;"></i>
                                    <div>
                                        <small class="text-muted d-block">Official Email</small>
                                        <span class="fw-medium">{{ session('official_email') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex align-items-start" role="alert">
                            <i class="ti ti-info-circle me-2 mt-1"></i>
                            <div>
                                <strong>Important:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Check your official email inbox and spam folder</li>
                                    <li>The reset link will expire in 60 minutes</li>
                                    <li>If you don't receive the email, contact your administrator</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary text-uppercase text-bold">
                                <span class="fw-bold">
                                    <i class="ti ti-arrow-left"></i>
                                    {{ __('Back To Login') }}
                                </span>
                            </a>
                            <a href="{{ route('password.request') }}" class="btn btn-outline-secondary text-uppercase text-bold">
                                <span class="fw-bold">
                                    <i class="ti ti-refresh"></i>
                                    {{ __('Send Another Link') }}
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Password Reset Link Sent -->
            </div>
        </div>

        <!-- / Content -->

        <!-- Core JS -->
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

        <!-- Main JS -->
        <script src="{{ asset('assets/js/main.js') }}"></script>

        @include('sweetalert::alert')
    </body>
</html>
