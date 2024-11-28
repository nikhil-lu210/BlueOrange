@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Income & Expense Categories'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    
    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />

    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Show Category') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Accounts') }}</li>
    <li class="breadcrumb-item">{{ __('Income & Expenses') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.accounts.income_expense.category.index') }}">{{ __('Categories') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Incomes of {{ $category->name }}</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateCategoryModal">
                        <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                        Update Category
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <small class="card-text text-uppercase">Category Info</small>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-hash"></i>
                                        <span class="fw-medium mx-2 text-heading">Category Name:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark text-bold">{{ $category->name }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-2">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-chart-candle"></i>
                                        <span class="fw-medium mx-2 text-heading">Status:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        @php
                                            $status = $category->is_active == true ? 'Active' : 'Inactive';
                                            $background = $category->is_active == true ? 'bg-success' : 'bg-danger';
                                        @endphp
                                        <span class="badge {{ $background }}">{{ $status }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-2">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-calendar"></i>
                                        <span class="fw-medium mx-2 text-heading">Created At:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ show_date_time($category->created_at) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-2">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-info-circle"></i>
                                        <span class="fw-medium mx-2 text-heading">Description:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{!! $category->description !!}</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-success p-2 rounded">
                                        <i class="ti ti-trending-up ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-success mb-0">{{ format_currency($category->total_income) }}</h5>
                                        <small class="mb-0 text-muted">
                                            <b class="text-dark">Incomes: </b>
                                            {{ $category->incomes_count }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-danger p-2 rounded">
                                        <i class="ti ti-trending-down ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-danger mb-0">{{ format_currency($category->total_expense) }}</h5>
                                        <small class="mb-0 text-muted">
                                            <b class="text-dark">Expenses: </b>
                                            {{ $category->expenses_count }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Start row -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Incomes of {{ $category->name }}</h5>
        
                <div class="card-header-elements ms-auto">
                    <h5 class="text-bold text-success m-0" title="Total Income">{{ format_currency($category->total_income) }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Source</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($category->incomes as $key => $income) 
                                <tr>
                                    <th>#{{ serial($category->incomes, $key) }}</th>
                                    <td>
                                        <b>{{ $income->source }}</b>
                                        <br>
                                        <small class="text-muted">{{ $income->date->format('d M, Y') }}</small>
                                    </td>
                                    <td>
                                        <b>{{ format_currency($income->total, 'BDT') }}</b>
                                        <br>
                                        <small class="text-muted text-capitalize">{{ spell_number($income->total) }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Expenses of {{ $category->name }}</h5>
        
                <div class="card-header-elements ms-auto">
                    <h5 class="text-bold text-danger m-0" title="Total Expense">{{ format_currency($category->total_expense) }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Source</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($category->expenses as $key => $expense) 
                                <tr>
                                    <th>#{{ serial($category->expenses, $key) }}</th>
                                    <td>
                                        <b>{{ $expense->title }}</b>
                                        <br>
                                        <small class="text-muted">{{ $expense->date->format('d M, Y') }}</small>
                                    </td>
                                    <td>
                                        <b>{{ format_currency($expense->total, 'BDT') }}</b>
                                        <br>
                                        <small class="text-muted text-capitalize">{{ spell_number($expense->total) }}</small>
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
<!-- End row -->


{{-- Page Modal --}}
@include('administration.accounts.income_expense.category.modals.category_update')

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    {{-- <!-- Datatable js --> --}}
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>

    {{-- Bootstrap Select --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });

            $('.month-year-picker').datepicker({
                format: 'MM yyyy',         // Display format to show full month name and year
                minViewMode: 'months',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });

            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });
        });
    </script>
@endsection