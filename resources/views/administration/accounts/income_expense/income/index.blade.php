@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('All Incomes'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    
    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Incomes') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Accounts') }}</li>
    <li class="breadcrumb-item">{{ __('Income & Expenses') }}</li>
    <li class="breadcrumb-item">{{ __('Income') }}</li>
    <li class="breadcrumb-item active">{{ __('All Incomes') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('administration.accounts.income_expense.income.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label for="category_id" class="form-label">{{ __('Select Category') }}</label>
                            <select name="category_id" id="category_id" class="select2 form-select @error('category_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->category_id) ? 'selected' : '' }}>{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == request()->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{{ __('Incomes Of') }}</label>
                            <input type="text" name="for_month" value="{{ request()->for_month ?? old('for_month') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('for_month')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12 text-end">
                        @if (request()->category_id || request()->for_month) 
                            <a href="{{ route('administration.accounts.income_expense.income.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                {{ __('Reset Filters') }}
                            </a>
                        @endif
                        <button type="submit" name="filter_incomes" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ __('Filter Incomes') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>        
    </div>
</div>


@include('administration.accounts.income_expense.income.partials._income_stats')


<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    All Incomes
                    @isset ($incomes) 
                        <sup class="text-bold" title="Total Income">({{ format_currency( $total['income'], 'BDT') }})</sup>
                    @endisset
                </h5>
        
                <div class="card-header-elements ms-auto">
                    @can(['Income Create'])
                        @if ($incomes->count() > 0)
                            <a href="{{ route('administration.accounts.income_expense.income.export', [
                                'category_id' => request('category_id'),
                                'for_month' => request('for_month'),
                                'filter_incomes' => request('filter_incomes')
                            ]) }}" target="_blank" class="btn btn-sm btn-dark">
                                <span class="tf-icon ti ti-download me-1"></span>
                                {{ __('Download') }}
                            </a>
                        @endif
                    @endcan
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
                                <th>Added By</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incomes as $key => $income) 
                                <tr>
                                    <th>#{{ serial($incomes, $key) }}</th>
                                    <td>
                                        <b>{{ $income->source }}</b>
                                        <br>
                                        <small class="text-muted">{{ $income->category->name }}</small>
                                    </td>
                                    <td>
                                        <b>{{ format_currency($income->total, 'BDT') }}</b>
                                        <br>
                                        <small class="text-muted text-capitalize">{{ spell_number($income->total) }}</small>
                                    </td>
                                    <td>
                                        {!! show_user_name_and_avatar($income->creator, name: null) !!}
                                    </td>
                                    <td>
                                        <b title="Income Date">{{ show_date($income->date) }}</b>
                                        <br>
                                        <small class="text-muted" title="Entry Date ({{ show_date_time($income->created_at) }})">{{ date_time_ago($income->created_at) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @can ('Income Delete') 
                                            <a href="{{ route('administration.accounts.income_expense.income.destroy', ['income' => $income]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Income?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        @can ('Income Read') 
                                            <a href="{{ route('administration.accounts.income_expense.income.show', ['income' => $income]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                                <i class="text-white ti ti-info-hexagon"></i>
                                            </a>
                                        @endcan
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

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script> --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.month-year-picker').datepicker({
                format: 'MM yyyy',         // Display format to show full month name and year
                minViewMode: 'months',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
