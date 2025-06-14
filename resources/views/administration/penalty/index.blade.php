@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('All Penalties'))

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
    <b class="text-uppercase">{{ __('All Penalties') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('All Penalties') }}</li>
@endsection

@section('content')

<!-- Basic Bootstrap Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('All Penalties') }}</h5>
        @canany(['Penalty Everything', 'Penalty Create'])
            <a href="{{ route('administration.penalty.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>{{ __('Add Penalty') }}
            </a>
        @endcanany
    </div>

    <div class="card-body">
        <div class="table-responsive-md table-responsive-sm w-100">
            <table class="table data-table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Penalty Time') }}</th>
                        <th>{{ __('Attendance Date') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penalties as $penalty)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {!! show_user_name_and_avatar($penalty->user, role: null) !!}
                            </td>
                            <td>
                                <span class="text-bold text-danger">{{ $penalty->type }}</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $penalty->total_time_formatted }}</span>
                            </td>
                            <td>
                                <a href="{{ route('administration.attendance.show', ['attendance' => $penalty->attendance]) }}" target="_blank">
                                    {{ show_date($penalty->attendance->clock_in) }}
                                </a>
                            </td>
                            <td class="text-center">
                                @canany(['Penalty Everything', 'Penalty Delete'])
                                    <a href="{{ route('administration.penalty.destroy', ['penalty' => $penalty]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Penalty?">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                @endcanany
                                @canany(['Penalty Everything', 'Penalty Read'])
                                    <a href="{{ route('administration.penalty.show', $penalty) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                        <i class="ti ti-info-hexagon"></i>
                                    </a>
                                @endcanany
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('No penalties found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ Basic Bootstrap Table -->

@endsection

@section('script_links')
    {{--  External JS  --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External JS  --}}
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
