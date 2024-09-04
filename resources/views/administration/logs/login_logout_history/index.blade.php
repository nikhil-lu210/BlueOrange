@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Login & Logout Histories'))

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
    <b class="text-uppercase">{{ __('All Login & Logout Histories') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('System Logs') }}</li>
    <li class="breadcrumb-item active">{{ __('All Login & Logout Histories') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Login & Logout Histories of <b class="text-primary">{{ date('M Y') }}</b></h5>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Employee</th>
                            <th>Clockin</th>
                            <th>Clockout</th>
                            <th>IP Address</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $key => $history) 
                            <tr>
                                <th>#{{ serial($histories, $key) }}</th>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center user-name">
                                        <div class="avatar-wrapper">
                                            <div class="avatar me-2">
                                                @if ($history->user->hasMedia('avatar'))
                                                    <img src="{{ $history->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $history->user->name }} Avatar" class="rounded-circle">
                                                @else
                                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="{{ $history->user->name }} No Avatar" class="rounded-circle">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('administration.settings.user.show.profile', ['user' => $history->user]) }}" target="_blank" class="emp_name text-truncate">{{ $history->user->name }}</a>
                                            <small class="emp_post text-truncate text-muted">{{ $history->user->roles[0]->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-grid">
                                        <small>
                                            <span class="text-bold">Date:</span>
                                            {{ show_date($history->login_time) }}
                                        </small>
                                        <small>
                                            <span class="text-bold">Time:</span>
                                            {{ show_time($history->login_time) }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if (!is_null($history->logout_time)) 
                                        <div class="d-grid">
                                            <small>
                                                <span class="text-bold">Date:</span>
                                                {{ show_date($history->logout_time) }}
                                            </small>
                                            <small>
                                                <span class="text-bold">Time:</span>
                                                {{ show_time($history->logout_time) }}
                                            </small>
                                        </div>
                                    @else 
                                        <span class="badge bg-warning">No History</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-grid">
                                        <small>
                                            <span class="text-bold">Login:</span>
                                            <code>{{ $history->login_ip }}</code>
                                        </small>
                                        @if (!is_null($history->logout_ip)) 
                                            <small>
                                                <span class="text-bold">Logout:</span>
                                                <code>{{ $history->logout_ip }}</code>
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete History?">
                                        <i class="text-white ti ti-trash"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                        <i class="ti ti-info-hexagon"></i>
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
<!-- End row -->

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
