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
    <b class="text-uppercase">{{ __('All Categories') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Accounts') }}</li>
    <li class="breadcrumb-item">{{ __('Income & Expenses') }}</li>
    <li class="breadcrumb-item active">{{ __('Categories') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Categories</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignNewCategoryModal">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        Assign Category
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Total Income</th>
                                <th>Total Expense</th>
                                <th>Profit/Loss</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $key => $category) 
                                <tr>
                                    <th>{{ serial($categories, $key) }}</th>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        @php
                                            $status = $category->is_active == true ? 'Active' : 'Inactive';
                                            $background = $category->is_active == true ? 'bg-success' : 'bg-danger';
                                        @endphp
                                        <span class="badge {{ $background }}">{{ $status }}</span>
                                    </td>
                                    <td>
                                        <b class="text-success">{{ format_currency($category->total_income) }}</b>
                                        <br>
                                        <span class="text-muted">
                                            <span class="text-dark text-bold">Incomes: </span>
                                            {{ $category->incomes_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <b class="text-danger">{{ format_currency($category->total_expense) }}</b>
                                        <br>
                                        <span class="text-muted">
                                            <span class="text-dark text-bold">Expenses: </span>
                                            {{ $category->expenses_count }}
                                        </span>
                                    </td>                                    
                                    <td>
                                        @php
                                            $profitOrLoss = ($category->total_income - $category->total_expense) > 0 ? 'Profit' : 'Loss';
                                            $color = ($category->total_income - $category->total_expense) > 0 ? 'text-success' : 'text-danger';
                                        @endphp
                                        <b class="{{ $color }}" title="Total {{ $profitOrLoss }}">
                                            {{ format_currency($category->total_income - $category->total_expense) }}
                                        </b>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('administration.accounts.income_expense.category.show', ['category' => $category]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                            <i class="text-white ti ti-info-hexagon"></i>
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
<!-- End row -->


{{-- Page Modal --}}
@include('administration.accounts.income_expense.category.modals.category_create')

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