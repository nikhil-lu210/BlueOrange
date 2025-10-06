@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Recognition Analytics'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .stats-card {
            border-left: 4px solid #007bff;
        }
        .stats-card.success {
            border-left-color: #28a745;
        }
        .stats-card.warning {
            border-left-color: #ffc107;
        }
        .stats-card.info {
            border-left-color: #17a2b8;
        }
        .chart-container {
            position: relative;
            height: 400px;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Recognition Analytics') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognition') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.recognition.index') }}">{{ __('All Recognitions') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Analytics') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row">
    <!-- My Recognition Stats -->
    <div class="col-md-12 mb-4">
        <div class="card card-border-shadow-primary border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Recognition Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-label-primary border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-primary p-2 rounded">
                                        <i class="ti ti-award ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-primary mb-0">{{ $analytics['my_stats']['total_received'] }}</h5>
                                        <small class="mb-0 text-muted">Total Recognitions</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-label-success border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-success p-2 rounded">
                                        <i class="ti ti-chart-line ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-success mb-0">{{ number_format($analytics['my_stats']['average_score_received'], 1) }}</h5>
                                        <small class="mb-0 text-muted">Average Score</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-label-info border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-info p-2 rounded">
                                        <i class="ti ti-sum ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-info mb-0">{{ $analytics['my_stats']['total_score_received'] }}</h5>
                                        <small class="mb-0 text-muted">Total Score</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-label-warning border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-warning p-2 rounded">
                                        <i class="ti ti-trophy ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-warning mb-0">{{ $analytics['my_stats']['highest_score_received'] }}</h5>
                                        <small class="mb-0 text-muted">Highest Score</small>
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

<div class="row">
    <!-- Category Breakdown -->
    <div class="col-md-6 mb-4">
        <div class="card card-border-shadow-primary border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">Recognition by Category</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trends -->
    <div class="col-md-6 mb-4">
        <div class="card card-border-shadow-primary border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">Monthly Recognition Trends</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Recognitions -->
    <div class="col-md-6 mb-4">
        <div class="card card-border-shadow-primary border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">Recent Recognitions</h5>
            </div>
            <div class="card-body">
                @if($analytics['recent_recognitions']->count() > 0)
                    <div class="list-group">
                        @foreach($analytics['recent_recognitions'] as $recognition)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $recognition->category }}</h6>
                                    <small>{{ show_date($recognition->created_at) }}</small>
                                </div>
                                <p class="mb-1">{{ strip_tags($recognition->comment) }}</p>
                                <small>Score: {{ $recognition->total_mark }} | By: {{ $recognition->recognizer->alias_name }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted">No recent recognitions</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="col-md-6 mb-4">
        <div class="card card-border-shadow-primary border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">Top Performers</h5>
            </div>
            <div class="card-body">
                @if($analytics['top_performers']->count() > 0)
                    <div class="list-group">
                        @foreach($analytics['top_performers'] as $index => $performer)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                        <div>
                                            <h6 class="mb-0">{{ $performer->alias_name }}</h6>
                                            <small class="text-muted">{{ $performer->recognition_count }} recognitions</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">{{ $performer->total_score }}</div>
                                        <small class="text-muted">Total Score</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted">No performance data available</p>
                    </div>
                @endif
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
        $(document).ready(function() {
            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryData = @json($analytics['my_stats']['category_breakdown']);
            
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(categoryData),
                    datasets: [{
                        data: Object.values(categoryData).map(item => item.count),
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40',
                            '#FF6384'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Trends Chart
            const trendsCtx = document.getElementById('trendsChart').getContext('2d');
            const trendsData = @json($analytics['monthly_trends']);
            
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: trendsData.map(item => item.month),
                    datasets: [{
                        label: 'Recognition Count',
                        data: trendsData.map(item => item.count),
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Average Score',
                        data: trendsData.map(item => item.avg_score),
                        borderColor: '#FF6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    }
                }
            });
        });
    </script>
@endsection
