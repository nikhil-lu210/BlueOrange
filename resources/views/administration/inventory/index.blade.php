@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('All Inventories'))

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
    <b class="text-uppercase">{{ __('All Inventories') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('All Inventories') }}</li>
@endsection

@section('content')

<!-- Basic Bootstrap Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('All Inventories') }}</h5>
        @canany(['Inventory Everything', 'Inventory Create'])
            <a href="{{ route('administration.inventory.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>{{ __('Store Inventory') }}
            </a>
        @endcanany
    </div>

    <div class="card-body">
        <div class="table-responsive-md table-responsive-sm w-100">
            <table class="table data-table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('Office Inventory Code (OIC)') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Category & Purpose') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $key => $inventory)
                        <tr>
                            <td>
                                <span class="fw-medium text-dark">{{ $inventory->oic }}</span>
                                <br>
                                <small class="text-muted">{{ $inventory->unique_number }}</small>
                            </td>
                            <td>
                                <b class="text-dark">{{ $inventory->name }}</b>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $inventory->category->name }}</span>
                                <br>
                                <small class="text-muted">{{ $inventory->usage_for }}</small>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $inventory->price }}</span>
                            </td>
                            <td>
                                <span class="{{ $inventory->status_badge }}">
                                    {{ $inventory->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                @canany(['Inventory Everything', 'Inventory Delete'])
                                    <a href="{{ route('administration.inventory.destroy', ['inventory' => $inventory]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete inventory?">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                @endcanany
                                @canany(['Inventory Everything', 'Inventory Read'])
                                    <a href="{{ route('administration.inventory.show', $inventory) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                        <i class="ti ti-info-hexagon"></i>
                                    </a>
                                @endcanany
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('No Inventories found') }}</td>
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
