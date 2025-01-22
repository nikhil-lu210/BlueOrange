@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Income & Expense Statistics'))

@section('css_links')
    {{--  External CSS  --}}
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
    <b class="text-uppercase">{{ __('Income & Expense Statistics') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Accounts') }}</li>
    <li class="breadcrumb-item">{{ __('Income & Expenses') }}</li>
    <li class="breadcrumb-item active">{{ __('Statistics') }}</li>
@endsection



@section('content')
<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header header-elements">
                <div>
                    <h5 class="card-title mb-0">{{ __('Statistics') }}</h5>
                    <small class="text-muted">{{ __('Income & Expense Yearly Statistics Chart') }}</small>
                </div>
                <div class="card-header-elements ms-auto py-0">
                    <form method="GET" action="{{ route('administration.accounts.income_expense.statistics.index') }}" autocomplete="off">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                            <input type="number" minlength="4" maxlength="4" min="1900" max="{{ date('Y') }}" name="for_year" value="{{ request()->for_year ?? date('Y') }}" class="form-control text-bold text-dark year-picker" placeholder="yyyy" required style="padding-right: 15px;"/>
                            <button class="btn btn-primary waves-effect text-uppercase" type="submit">{{ __('Filter') }}</button>
                        </div>
                        @error('for_year')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </form>
                </div>
            </div>
            <div class="card-body border-top">
                <canvas id="lineChart" class="chartjs"></canvas>
            </div>
        </div>        
    </div>    
</div>

<!-- End row -->
@endsection



@section('script_links')
    {{--  External Javascript Links --}}
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.year-picker').datepicker({
                format: 'yyyy',            // Display only the year
                minViewMode: 'years',      // Only allow year selection
                viewMode: 'years',         // Ensure that the view is limited to years
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>


    <script>
        // Ensure the document is ready before executing the script
        $(document).ready(function() {
            const lineChart = $("#lineChart")[0];
            lineChart.height = 100;

            // Data from the controller
            const monthlyIncome = {!! json_encode(array_values($monthlyIncome)) !!};
            const monthlyExpenses = {!! json_encode(array_values($monthlyExpenses)) !!};

            // Labels for months
            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            if (lineChart) {
                new Chart(lineChart, {
                    type: "line",
                    data: {
                        labels: months,
                        datasets: [
                            {
                                label: "Income",
                                data: monthlyIncome,
                                borderColor: "#28c76f",
                                backgroundColor: "#28c76f",
                                fill: false,
                                tension: 0.3,
                            },
                            {
                                label: "Expenses",
                                data: monthlyExpenses,
                                borderColor: "#ea5455",
                                backgroundColor: "#ea5455",
                                fill: false,
                                tension: 0.3,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                ticks: { color: "#666" },
                                grid: { display: true },
                            },
                            y: {
                                ticks: { color: "#666" },
                                grid: { color: "#ddd" },
                                beginAtZero: true, // Ensures chart starts at 0
                            },
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: "top",
                                labels: { color: "#444" },
                            },
                        },
                    },
                });
            }
        });
    </script>
@endsection
