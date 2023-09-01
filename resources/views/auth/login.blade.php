<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description" content="Orbiter is a bootstrap minimal & clean admin template" />
        <meta name="keywords" content="admin, admin panel, admin template, admin dashboard, responsive, bootstrap 4, ui kits, ecommerce, web app, crm, cms, html, sass support, scss" />
        <meta name="author" content="Themesbox" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
        <title>{{ __('Project_Name') }} || {{ __('LOGIN') }}</title>
        <!-- Fevicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
        <!-- Start css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />

        {{-- custom css --}}
        <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css" />
        <!-- End css -->
    </head>
    <body class="vertical-layout">
        <!-- Start Containerbar -->
        <div id="containerbar" class="containerbar authenticate-bg">
            <!-- Start Container -->
            <div class="container">
                <div class="auth-box login-box">
                    <!-- Start row -->
                    <div class="row no-gutters align-items-center justify-content-center">
                        <!-- Start col -->
                        <div class="col-md-6 col-lg-5">
                            <!-- Start Auth Box -->
                            <div class="auth-box-right">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="form-head">
                                                <a href="/" class="logo">
                                                    <img src="{{ asset('assets/images/logo.svg') }}" class="img-fluid" alt="logo" />
                                                </a>
                                            </div>

                                            <h4 class="text-primary my-4"><b>{{ __('LOGIN') }}</b></h4>

                                            <div class="form-group">
                                                <input type="email" value="{{ old('email') }}" name="email" required autocomplete="off" autofocus tabindex="0" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Login Email') }}">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                                
                                            <div class="form-group">
                                                <input type="password" value="{{ old('password') }}" name="password" required autocomplete="off"  tabindex="0" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-row mb-3">
                                                <div class="col-6">
                                                    <div class="custom-control custom-checkbox text-left">
                                                        <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                                                        <label class="custom-control-label font-14" for="rememberme">{{ __('Remember Me') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="forgot-psw">
                                                        @if (Route::has('password.request'))
                                                            <a id="forgot-psw" href="{{ route('password.request') }}" class="float-right font-14">
                                                                <b>{{ __('Forgot Password?') }}</b>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-lg btn-block font-18"><b>{{ __('LOGIN') }}</b></button>
                                        </form>
                                        <p class="mb-0 mt-3">{{ __('Don\'t have a account?') }}
                                            <a href="{{ route('register') }}">
                                                <b>{{ __('Register') }}</b>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- End Auth Box -->
                        </div>
                        <!-- End col -->
                    </div>
                    <!-- End row -->
                </div>
            </div>
            <!-- End Container -->
        </div>
        <!-- End Containerbar -->
        <!-- Start js -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        
        {{-- custom js --}}
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <!-- End js -->
    </body>
</html>
