@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Employee Lifecycle Management'))

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
		.nav-pills .nav-link {
			transition: all 0.25s ease;
			border-radius: 0.5rem;
			background-color: rgba(0, 0, 0, 0.03);
		}

		.nav-pills .nav-link:hover {
			background-color: rgba(13, 110, 253, 0.08);
			color: #0d6efd !important;
		}

		.nav-pills .nav-link.active {
			background: linear-gradient(90deg, #0d6efd 0%, #3d8bfd 100%);
			color: #ffffff !important;
			box-shadow: 0 0.25rem 0.75rem rgba(13, 110, 253, 0.25);
		}

		.card {
			transition: transform 0.2s ease-in-out;
		}

		.card:hover {
			transform: translateY(-2px);
		}

		.progress {
			border-radius: 10px;
			overflow: hidden;
		}

		.progress-bar {
			border-radius: 10px;
		}

		.btn {
			transition: all 0.3s ease;
		}

		.btn:hover {
			transform: translateY(-1px);
		}

		.list-group-item:hover {
			background-color: rgba(0, 0, 0, 0.02);
		}

		/* Navigation icons */
		.nav-pills .nav-link i { font-size: 1rem; opacity: 0.9; }

		/* Statistics cards */
		.stat-card { position: relative; overflow: hidden; background-color: #ffffff; border: 1px solid rgba(0,0,0,0.08); }
		.stat-card .card-body { position: relative; z-index: 1; }
		.stat-card::before { content: ""; position: absolute; inset: 0; background: var(--stat-gradient, linear-gradient(135deg, #5b86e5 0%, #36d1dc 100%)); transform: scale(0); transform-origin: top right; transition: transform 0.35s ease; z-index: 0; }
		.stat-card:hover::before { transform: scale(1); }
		.stat-icon { width: 54px; height: 54px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; transition: background-color 0.35s ease; }
		.stat-title { letter-spacing: 0.03em; transition: color 0.35s ease, opacity 0.35s ease; }
		.stat-value { transition: color 0.35s ease; }
		.stat-icon i { transition: color 0.35s ease; }
		.stat-primary { --stat-color: #0d6efd; --stat-gradient: linear-gradient(135deg, #3d8bfd 0%, #00c6ff 100%); }
		.stat-success { --stat-color: #28a745; --stat-gradient: linear-gradient(135deg, #28a745 0%, #6fdd8b 100%); }
		.stat-warning { --stat-color: #ff9f43; --stat-gradient: linear-gradient(135deg, #f6c343 0%, #ff9f43 100%); }
		.stat-info { --stat-color: #17a2b8; --stat-gradient: linear-gradient(135deg, #17a2b8 0%, #5bc0de 100%); }
		.stat-primary .stat-icon { background-color: rgba(13,110,253,0.12); }
		.stat-success .stat-icon { background-color: rgba(40,167,69,0.12); }
		.stat-warning .stat-icon { background-color: rgba(255,159,67,0.15); }
		.stat-info .stat-icon { background-color: rgba(23,162,184,0.12); }
		.stat-primary .stat-title, .stat-primary .stat-icon i { color: #0d6efd; }
		.stat-success .stat-title, .stat-success .stat-icon i { color: #28a745; }
		.stat-warning .stat-title, .stat-warning .stat-icon i { color: #ff9f43; }
		.stat-info .stat-title, .stat-info .stat-icon i { color: #17a2b8; }
		.stat-card:hover .stat-title, .stat-card:hover .stat-value, .stat-card:hover .stat-icon i { color: #ffffff !important; }
		.stat-card:hover .stat-icon { background-color: rgba(255,255,255,0.25); }

		/* Activity list */
		.activity-list { display: flex; flex-direction: column; gap: 0.75rem; }
		.activity-item { background-color: #ffffff; border: 1px solid rgba(0,0,0,0.06); border-radius: 0.65rem; padding: 0.85rem 1rem; box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.04); transition: transform 0.15s ease, box-shadow 0.15s ease; }
		.activity-item:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.06); }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Overview') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Employee Lifecycle Management') }}</li>
    <li class="breadcrumb-item active">{{ __('All Cycle Lifecycle Management') }}</li>
@endsection

@section('content')

<!-- Events Table -->
<div class="row">
    <div class="container-fluid">
        <!-- Header Section -->
        @include('administration.lifecycle.partials.header')

        @include('administration.lifecycle.partials.nav')

        <!-- Statistics Cards (white default, hover gradient) -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card stat-primary shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 stat-icon me-2">
                                <i class="ti ti-user-plus fs-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-medium stat-title text-uppercase mb-1">New Hires</div>
                                <div class="h2 mb-0 stat-value">{{ $stats['new_hires'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card stat-success shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 stat-icon me-2">
                                <i class="ti ti-users-group fs-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-medium stat-title text-uppercase mb-1">Active Employees</div>
                                <div class="h2 mb-0 stat-value">{{ $stats['active_employees'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card stat-warning shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 stat-icon me-2">
                                <i class="ti ti-door-exit fs-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-medium stat-title text-uppercase mb-1">Departures</div>
                                <div class="h2 mb-0 stat-value">{{ $stats['departures'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card stat-info shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 stat-icon me-2">
                                <i class="ti ti-arrow-left-right fs-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-medium stat-title text-uppercase mb-1">Transfers</div>
                                <div class="h2 mb-0 stat-value">{{ $stats['transfers'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity (clean list, badge + progress tweak) -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 fw-medium">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            @foreach($recentActivity as $activity)
                            <div class="activity-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-{{ $activity['color_class'] }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                            <span class="fw-medium text-{{ $activity['color_class'] }} small">{{ $activity['employee_initials'] }}</span>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">{{ $activity['employee_name'] }}</h6>
                                            <div class="text-muted small">{{ $activity['position'] }} • {{ $activity['department'] }}</div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $activity['color_class'] }} text-white rounded-pill small">{{ $activity['activity'] }}</span>
                                        <div class="small text-muted mt-1">{{ $activity['timestamp']->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $activity['color_class'] }} text-dark" role="progressbar" style="width: {{ $activity['progress'] }}%" aria-valuenow="{{ $activity['progress'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 fw-medium">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <button class="btn btn-primary btn-lg d-flex align-items-center justify-content-start">
                                <i class="ti ti-person-plus me-3 fs-1"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Start New Onboarding</div>
                                    <div class="small opacity-75">Add a new employee</div>
                                </div>
                            </button>

                            <button class="btn btn-success btn-lg d-flex align-items-center justify-content-start">
                                <i class="ti ti-calendar-plus me-3 fs-1"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Schedule Exit Interview</div>
                                    <div class="small opacity-75">Plan employee departure</div>
                                </div>
                            </button>

                            <button class="btn btn-info btn-lg d-flex align-items-center justify-content-start">
                                <i class="ti ti-arrow-left-right me-3 fs-1"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Process Transfer</div>
                                    <div class="small opacity-75">Move employee between roles</div>
                                </div>
                            </button>

                            <button class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-start">
                                <i class="ti ti-file-earmark-text me-3 fs-1"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Generate Reports</div>
                                    <div class="small opacity-75">View analytics and insights</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External JS  --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
    
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

        });
    </script>
@endsection
