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
            <form action="{{ route('administration.settings.system.app_setting.restriction.update.ip.range') }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-8">
                                <label class="form-label">{{ __('IP Address') }} <b class="text-danger">*</b></label>
                                <input type="text" name="ip_address" value="{{ request()->ip_address ?? old('ip_address') }}" class="form-control" placeholder="192.168.0.1" required/>
                                @error('ip_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">{{ __('IP Range (CIDR)') }} <b class="text-danger">*</b></label>
                                <input type="number" name="range" value="{{ request()->range ?? old('range') }}" class="form-control" placeholder="18" min="0" max="32" required/>
                                @error('range')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> 
                        
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                {{ __('Store IP Range') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header header-elements">
                            <h5 class="mb-0">All IP Range(s)</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-md table-responsive-sm w-100">
                                <table class="table data-table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>IP Address / CIDR</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ipRanges as $key => $range) 
                                            <tr>
                                                <th>#{{ $key+1 }}</th>
                                                <td class="text-dark text-bold">
                                                    {{ $range['ip_address'] }}/<sub>{{ $range['range'] }}</sub>
                                                </td>
                                                <td>{{ date_time_ago($range['created_at']) }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('administration.settings.system.app_setting.restriction.destroy.ip.range', ['id' => $range['id']]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete IP Range?">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>        
                </div>
            </div>
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