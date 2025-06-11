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
        @can ('Penalty Create')
            <a href="{{ route('administration.penalty.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>{{ __('Add Penalty') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered data-table" id="penaltiesTable">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Penalty Time') }}</th>
                        <th>{{ __('Attendance Date') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penalties as $penalty)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($penalty->user->media->isNotEmpty())
                                        <img src="{{ $penalty->user->media->first()->getUrl('thumb_color') }}"
                                             alt="{{ $penalty->user->name }}"
                                             class="rounded-circle me-2"
                                             width="32" height="32">
                                    @else
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($penalty->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="fw-medium">{{ $penalty->user->name }}</span>
                                        <small class="text-muted d-block">{{ $penalty->user->employee->alias_name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-warning">{{ $penalty->type }}</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $penalty->total_time_formatted }}</span>
                            </td>
                            <td>{{ $penalty->attendance->clock_in_date ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($penalty->creator->media->isNotEmpty())
                                        <img src="{{ $penalty->creator->media->first()->getUrl('thumb_color') }}"
                                             alt="{{ $penalty->creator->name }}"
                                             class="rounded-circle me-2"
                                             width="24" height="24">
                                    @endif
                                    <span>{{ $penalty->creator->name }}</span>
                                </div>
                            </td>
                            <td>{{ $penalty->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can ('Penalty Read')
                                            <a class="dropdown-item" href="{{ route('administration.penalty.show', $penalty) }}">
                                                <i class="ti ti-eye me-1"></i> {{ __('View') }}
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('No penalties found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ Basic Bootstrap Table -->

@endsection

@section('vendor_js')
    {{--  External JS  --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
@endsection

@section('custom_js')
    {{--  External JS  --}}
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
