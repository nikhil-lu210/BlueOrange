@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Work Schedule Report'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Gantt Chart Styles */
        .gantt-chart {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            overflow-x: auto;
        }

        .gantt-header {
            display: flex;
            background-color: #e9ecef;
            border-radius: 4px;
            margin-bottom: 10px;
            min-width: 800px;
        }

        .gantt-employee-column {
            flex: 0 0 200px;
            min-width: 200px;
            padding: 15px;
            border-right: 1px solid #dee2e6;
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }

        .gantt-time-column {
            flex: 1;
            min-width: 35px;
            padding: 10px 5px;
            text-align: center;
            border-right: 1px solid #dee2e6;
            font-size: 0.8rem;
            font-weight: 500;
            color: #495057;
            background-color: #f8f9fa;
        }

        .gantt-row {
            display: flex;
            border-bottom: 1px solid #e9ecef;
            min-height: 50px;
            align-items: center;
            min-width: 800px;
        }

        .gantt-employee-cell {
            flex: 0 0 200px;
            min-width: 200px;
            padding: 15px;
            border-right: 1px solid #dee2e6;
            background-color: white;
            font-weight: 500;
            color: #212529;
        }

        .gantt-time-cell {
            flex: 1;
            min-width: 35px;
            height: 50px;
            border-right: 1px solid #e9ecef;
            position: relative;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s;
            cursor: pointer;
            font-size: 0.75rem;
        }

        .gantt-time-cell:hover {
            opacity: 0.8;
        }

        /* Clean colored cells without text */
        .gantt-time-cell.bg-warning {
            opacity: 0.9;
        }

        .gantt-time-cell.bg-success,
        .gantt-time-cell.bg-primary,
        .gantt-time-cell.bg-secondary {
            opacity: 0.9;
        }

        /* Work Type Colors - Using Bootstrap classes */
        /* Bootstrap classes: bg-success, bg-warning, bg-primary, bg-secondary */

        /* Legend */
        .legend {
            display: flex;
            gap: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        /* Weekday Navigation */
        .weekday-nav {
            margin-bottom: 20px;
        }

        .weekday-nav .nav-link {
            color: #6c757d;
            border: 1px solid #dee2e6;
            margin-right: 5px;
            border-radius: 4px;
        }

        .weekday-nav .nav-link.active {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .weekday-nav .nav-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Work Schedule Report') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Work Schedule') }}</li>
    <li class="breadcrumb-item active">{{ __('Work Schedule Report') }}</li>
@endsection

@section('content')

<!-- Filter Section -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <select name="user_filter" id="user_filter" class="form-select select2" data-allow-clear="true">
                            <option value="">All Employees</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ request()->user_filter == $user->id ? 'selected' : '' }}>{{ get_employee_name($user) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="apply-filters" class="btn btn-primary btn-block">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                @if(count($reportData) > 0)
                    <!-- Weekday Navigation -->
                    <ul class="nav nav-pills weekday-nav d-flex justify-content-between" id="weekday-tabs" role="tablist">
                        @foreach($weekdays as $weekday)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ request()->weekday_filter == $weekday || (request()->weekday_filter == null && $weekday == date('l')) ? 'active' : '' }}"
                                        id="{{ strtolower($weekday) }}-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#{{ strtolower($weekday) }}"
                                        type="button"
                                        role="tab"
                                        aria-controls="{{ strtolower($weekday) }}"
                                        aria-selected="{{ request()->weekday_filter == $weekday || (request()->weekday_filter == null && $weekday == date('l')) ? 'true' : 'false' }}">
                                    {{ $weekday }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="weekday-tabContent">
                        @foreach($weekdays as $weekday)
                            <div class="tab-pane fade {{ request()->weekday_filter == $weekday || (request()->weekday_filter == null && $weekday == date('l')) ? 'show active' : '' }}"
                                 id="{{ strtolower($weekday) }}"
                                 role="tabpanel"
                                 aria-labelledby="{{ strtolower($weekday) }}-tab">

                                <!-- Legend and Employee Count -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="legend">
                                        <div class="legend-item">
                                            <div class="legend-color bg-success"></div>
                                            <span>Client Work</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color bg-primary"></div>
                                            <span>Internal Work</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color bg-warning"></div>
                                            <span>Bench Work</span>
                                        </div>
                                    </div>
                                    <div class="employee-count">
                                        @php
                                            $scheduledCount = count(collect($reportData)->map(function($userData) use ($weekday) {
                                                return collect($userData['schedules'])->where('weekday', $weekday)->first();
                                            })->filter());
                                            $totalActiveCount = count($users ?? []);
                                        @endphp
                                        <span class="badge bg-label-primary fs-6">
                                            Scheduled: {{ $scheduledCount }}/{{ $totalActiveCount }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Gantt Chart -->
                                <div class="gantt-chart">
                                    <div class="gantt-header">
                                        <div class="gantt-employee-column">Employee</div>
                                        @for($hour = 0; $hour <= 23; $hour++)
                                            <div class="gantt-time-column">
                                                @php
                                                    $displayTime = '';
                                                    if ($hour == 0) {
                                                        $displayTime = '12 AM';
                                                    } elseif ($hour < 12) {
                                                        $displayTime = $hour . ' AM';
                                                    } elseif ($hour == 12) {
                                                        $displayTime = '12 PM';
                                                    } else {
                                                        $displayTime = ($hour - 12) . ' PM';
                                                    }
                                                @endphp
                                                {{ $displayTime }}
                                            </div>
                                        @endfor
                                    </div>

                                    @php
                                        $currentWeekdayData = collect($reportData)->map(function($userData) use ($weekday) {
                                            return [
                                                'user_id' => $userData['user_id'],
                                                'user_name' => $userData['user_name'],
                                                'schedules' => collect($userData['schedules'])->filter(function($schedule) use ($weekday) {
                                                    return $schedule['weekday'] == $weekday;
                                                })->values()->toArray()
                                            ];
                                        })->filter(function($userData) {
                                            return count($userData['schedules']) > 0;
                                        });
                                    @endphp

                                    @foreach($currentWeekdayData as $userData)
                                        <div class="gantt-row">
                                            <div class="gantt-employee-cell">
                                                @php
                                                    $user = \App\Models\User::find($userData['user_id']);
                                                @endphp
                                                {!! show_user_name_and_avatar($user, name: false) !!}
                                            </div>
                                            @php
                                                $cellData = $workScheduleService->getGanttCellData($userData, $weekday);
                                            @endphp
                                            @foreach($cellData as $cell)
                                                <div class="gantt-time-cell {{ $cell['bgClass'] }}"
                                                     data-hour="{{ $cell['hour'] }}"
                                                     @if($cell['hasSchedule'])
                                                         data-bs-toggle="tooltip"
                                                         title="{{ $cell['title'] }}"
                                                     @endif>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-chart-bar me-2" style="font-size: 3rem; color: #6c757d;"></i>
                        <h5 class="text-muted">No work schedules found</h5>
                        <p class="text-muted">Try adjusting the filters or create some work schedules first.</p>
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
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Apply filters
            $('#apply-filters').on('click', function() {
                const userFilter = $('#user_filter').val();
                const activeTab = $('.nav-link.active').attr('id').replace('-tab', '');
                const weekdayFilter = activeTab.charAt(0).toUpperCase() + activeTab.slice(1);

                let url = '{{ route("administration.work_schedule.report") }}';
                const params = new URLSearchParams();

                if (weekdayFilter) {
                    params.append('weekday_filter', weekdayFilter);
                }

                if (userFilter) {
                    params.append('user_filter', userFilter);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                window.location.href = url;
            });

            // Handle tab changes
            $('.nav-link').on('click', function() {
                const userFilter = $('#user_filter').val();
                const weekdayFilter = $(this).attr('id').replace('-tab', '').charAt(0).toUpperCase() + $(this).attr('id').replace('-tab', '').slice(1);

                let url = '{{ route("administration.work_schedule.report") }}';
                const params = new URLSearchParams();

                if (weekdayFilter) {
                    params.append('weekday_filter', weekdayFilter);
                }

                if (userFilter) {
                    params.append('user_filter', userFilter);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                window.location.href = url;
            });
        });
    </script>
@endsection
