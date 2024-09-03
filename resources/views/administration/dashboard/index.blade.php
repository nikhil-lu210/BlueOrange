@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('dashboard.dashboard'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('dashboard.dashboard') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('dashboard.dashboard') }}</li>
@endsection



@section('content')
<!-- Start row -->
<div class="row">
    <!-- Statistics -->
    <div class="col-lg-8 mb-4 col-md-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Statistics</h5>
                <small class="text-muted">Updated 1 month ago</small>
            </div>
            <div class="card-body pt-2">
                <div class="row gy-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                <i class="ti ti-chart-pie-2 ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">230k</h5>
                                <small>Sales</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-info me-3 p-2">
                                <i class="ti ti-users ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">8.549k</h5>
                                <small>Customers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                <i class="ti ti-shopping-cart ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">1.423k</h5>
                                <small>Products</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                                <i class="ti ti-currency-dollar ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">$9745</h5>
                                <small>Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-lg-2 col-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="badge rounded-pill p-2 bg-label-danger mb-2">
                    <i class="ti ti-briefcase ti-sm"></i>
                </div>
                <h5 class="card-title mb-2">97.8k</h5>
                <small>Orders</small>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="col-lg-2 col-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="badge rounded-pill p-2 bg-label-success mb-2">
                    <i class="ti ti-message-dots ti-sm"></i>
                </div>
                <h5 class="card-title mb-2">3.4k</h5>
                <small>Review</small>
            </div>
        </div>
    </div>
</div>

<!-- End row -->
@endsection



@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
