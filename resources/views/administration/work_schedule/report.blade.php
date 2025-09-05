@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Work Schedule Report'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .gantt-chart {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }
    .gantt-header {
        display: flex;
        background-color: #e9ecef;
        border-radius: 4px;
        margin-bottom: 10px;
        overflow-x: auto;
    }
    .gantt-employee-column {
        min-width: 200px;
        padding: 10px;
        border-right: 1px solid #dee2e6;
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .gantt-time-column {
        min-width: 60px;
        padding: 10px 5px;
        text-align: center;
        border-right: 1px solid #dee2e6;
        font-size: 0.8rem;
    }
    .gantt-row {
        display: flex;
        border-bottom: 1px solid #e9ecef;
        min-height: 50px;
        align-items: center;
    }
    .gantt-employee-cell {
        min-width: 200px;
        padding: 10px;
        border-right: 1px solid #dee2e6;
        background-color: white;
    }
    .gantt-time-cell {
        min-width: 60px;
        height: 50px;
        border-right: 1px solid #e9ecef;
        position: relative;
        background-color: white;
    }
    .gantt-bar {
        position: absolute;
        top: 10px;
        height: 30px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.7rem;
        font-weight: bold;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .gantt-bar:hover {
        opacity: 0.8;
    }
    .work-type-client { background-color: #28a745; }
    .work-type-internal { background-color: #007bff; }
    .work-type-bench { background-color: #ffc107; color: #212529; }
    .legend {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 20px;
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

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="weekday_filter" class="form-label">Filter by Weekday</label>
                        <select name="weekday_filter" id="weekday_filter" class="form-select">
                            <option value="">All Weekdays</option>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $weekday)
                                <option value="{{ $weekday }}" {{ request()->weekday_filter == $weekday ? 'selected' : '' }}>{{ $weekday }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="user_filter" class="form-label">Filter by Employee</label>
                        <select name="user_filter" id="user_filter" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ request()->user_filter == $user->id ? 'selected' : '' }}>{{ get_employee_name($user) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-12 text-end">
                    <button type="button" id="apply-filters" class="btn btn-primary">
                        <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Work Schedule Report - Gantt Chart View</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.work_schedule.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back to Schedules
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(count($reportData) > 0)
                    <!-- Legend -->
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-color work-type-client"></div>
                            <span>Client Work</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color work-type-internal"></div>
                            <span>Internal Work</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color work-type-bench"></div>
                            <span>Bench Work</span>
                        </div>
                    </div>

                    <!-- Gantt Chart -->
                    <div class="gantt-chart">
                        <div class="gantt-header">
                            <div class="gantt-employee-column">Employee / Weekday</div>
                            @for($hour = 0; $hour < 24; $hour++)
                                <div class="gantt-time-column">{{ sprintf('%02d:00', $hour) }}</div>
                            @endfor
                        </div>

                        @foreach($reportData as $userData)
                            @foreach($userData['schedules'] as $schedule)
                                <div class="gantt-row">
                                    <div class="gantt-employee-cell">
                                        <strong>{{ $userData['user_name'] }}</strong><br>
                                        <small class="text-muted">{{ $schedule['weekday'] }}</small>
                                    </div>
                                    @for($hour = 0; $hour < 24; $hour++)
                                        <div class="gantt-time-cell" data-hour="{{ $hour }}">
                                            @php
                                                $startHour = (int) substr($schedule['start_time'], 0, 2);
                                                $startMinute = (int) substr($schedule['start_time'], 3, 2);
                                                $endHour = (int) substr($schedule['end_time'], 0, 2);
                                                $endMinute = (int) substr($schedule['end_time'], 3, 2);

                                                $scheduleStartMinutes = $startHour * 60 + $startMinute;
                                                $scheduleEndMinutes = $endHour * 60 + $endMinute;
                                                $hourStartMinutes = $hour * 60;
                                                $hourEndMinutes = ($hour + 1) * 60;

                                                // Check if this schedule overlaps with this hour
                                                $overlapStart = max($scheduleStartMinutes, $hourStartMinutes);
                                                $overlapEnd = min($scheduleEndMinutes, $hourEndMinutes);

                                                if ($overlapStart < $overlapEnd) {
                                                    $overlapDuration = $overlapEnd - $overlapStart;
                                                    $leftPercent = (($overlapStart - $hourStartMinutes) / 60) * 100;
                                                    $widthPercent = ($overlapDuration / 60) * 100;
                                                }
                                            @endphp

                                            @if(isset($overlapStart) && $overlapStart < $overlapEnd)
                                                <div class="gantt-bar work-type-{{ strtolower($schedule['work_type']) }}"
                                                     style="left: {{ $leftPercent }}%; width: {{ $widthPercent }}%;"
                                                     data-bs-toggle="tooltip"
                                                     title="{{ $schedule['work_title'] }} ({{ $schedule['start_time'] }} - {{ $schedule['end_time'] }})">
                                                    {{ $schedule['work_type'] }}
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-chart-bar me-2" style="font-size: 3rem; color: #6c757d;"></i>
                        <h5 class="text-muted">No work schedules found for the selected date range</h5>
                        <p class="text-muted">Try adjusting the date range or create some work schedules first.</p>
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Apply filters
            $('#apply-filters').on('click', function() {
                const weekdayFilter = $('#weekday_filter').val();
                const userFilter = $('#user_filter').val();

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
