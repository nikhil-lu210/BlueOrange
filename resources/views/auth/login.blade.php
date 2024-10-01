<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="">
    <head>
        <meta charset="utf-8" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>{{ config('app.name') }} || {{ __('LOGIN') }}</title>

        <meta name="description" content="Blue Orange Web Application | Staff-India" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

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
        <!-- Vendor -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />

        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

        <!-- Helpers -->
        <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
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
                            src="{{ asset('assets/img/illustrations/auth-login-illustration-light.png') }}"
                            alt="auth-login-cover"
                            class="img-fluid my-5 auth-illustration"
                            data-app-light-img="illustrations/auth-login-illustration-light.png"
                            data-app-dark-img="illustrations/auth-login-illustration-dark.png"
                        />

                        <img
                            src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
                            alt="auth-login-cover"
                            class="platform-bg"
                            data-app-light-img="illustrations/bg-shape-image-light.png"
                            data-app-dark-img="illustrations/bg-shape-image-dark.png"
                        />
                    </div>
                </div>
                <!-- /Left Text -->

                <!-- Login -->
                <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                    <div class="w-px-400 mx-auto">
                        <!-- Logo -->
                        <div class="app-brand mb-4">
                            <a href="{{ route('login') }}" class="app-brand-link gap-2">
                                <img src="{{ asset('Logo/logo_black_01.png') }}" alt="{{ config('app.name') }}" width="30%">
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h3 class="mb-3 text-left"><b>Sign-In</b> to {{ config('app.name') }}</h3>

                        <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}" autocomplete="off">
                            @csrf
                            <div class="mb-3">
                                <label for="userid" class="form-label">User ID <sup class="text-danger">*</sup></label>
                                <div class="input-group input-group-merge mt-4">
                                    <span class="input-group-text" style="padding-right: 2px; @error('userid') border-color: red; @enderror">UID</span>
                                    <input type="text" id="userid" name="userid" class="form-control @error('userid') is-invalid @enderror" value="{{ old('userid') }}" minlength="8" maxlength="8" placeholder="20110101" autocomplete="off" required/>
                                </div>
                                @error('userid')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" required autocomplete="off"  tabindex="0" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="********" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark d-grid w-100">Sign in</button>
                        </form>

                        {{-- <p class="text-center">
                            <span>New on our platform?</span>
                            <a href="{{ route('register') }}">
                                <span>Create an account</span>
                            </a>
                        </p> --}}
                    </div>
                </div>
                <!-- /Login -->
            </div>
        </div>

        <!-- / Content -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->

        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>

        <!-- Main JS -->
        <script src="{{ asset('assets/js/main.js') }}"></script>

        <!-- Page JS -->
        <script src="{{ asset('assets/js/pages-auth.js') }}"></script>

        @include('sweetalert::alert')
    </body>
</html>
