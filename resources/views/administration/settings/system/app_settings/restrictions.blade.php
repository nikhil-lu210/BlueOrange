@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Restrictions'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Restrictions') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('System Settings') }}</li>
    <li class="breadcrumb-item">{{ __('App Settings') }}</li>
    <li class="breadcrumb-item active">{{ __('Restrictions') }}</li>
@endsection


@section('content')

<!-- Start row -->
@canany (['App Setting Create', 'App Setting Update'])
    <form action="{{ route('administration.settings.system.app_setting.restriction.update.device') }}" method="POST" id="form-restrict">
        @csrf
        @method('PUT')
        <div class="row justify-content-center mt-5">
            @foreach ($devices as $key => $device)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 card-border-shadow-primary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                                <h5 class="mb-0 me-2">{{ __('Restrict ' . ucfirst($device)) }}</h5>
                            </div>
                            <div class="card-icon">
                                <label class="switch switch-square" style="margin-right: 2rem;">
                                    <input type="checkbox" name="{{ $key }}" value="1" class="switch-input" @checked($restrictions[$key] == true) onchange="event.preventDefault(); document.getElementById('form-restrict').submit();">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"><i class="ti ti-check"></i></span>
                                        <span class="switch-off"><i class="ti ti-x"></i></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    <div class="row">
        <div class="col-md-6">
            @include('administration.settings.system.app_settings.includes._ip_range')
        </div>
        
        <div class="col-md-6">
            @include('administration.settings.system.app_settings.includes._user_access')
        </div>
    </div>
@endcanany
<!-- End row -->


{{-- Page Modal --}}
@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection