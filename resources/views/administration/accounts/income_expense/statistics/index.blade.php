@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Income & Expense Statistics'))

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
                    <span class="badge bg-label-secondary" title="Statistics of {{ date('Y') }}">
                        <i class="ti ti-calendar ti-md text-dark"></i>
                        <b class="align-middle text-dark fs-5 pt-1">{{ date('Y') }}</b>
                    </span>
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        (function () {
            const lineChart = document.getElementById("lineChart");
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
                                tension: 0.4,
                            },
                            {
                                label: "Expenses",
                                data: monthlyExpenses,
                                borderColor: "#ea5455",
                                backgroundColor: "#ea5455",
                                fill: false,
                                tension: 0.4,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                ticks: { color: "#666" },
                                grid: { display: false },
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
        })();
    </script>
    
@endsection
