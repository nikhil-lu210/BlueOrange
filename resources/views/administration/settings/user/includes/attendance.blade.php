@extends('administration.settings.user.show')

@section('profile_content')

<!-- User Profile Content -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Attendances</h5>

                <div class="card-header-elements ms-auto">
                    @if ($attendances->count() > 0)
                        <a href="{{ route('administration.attendance.export', [
                            'user_id' => $user->id,
                            'created_month_year' => date('F Y', strtotime('last month'))
                        ]) }}" target="_blank" class="btn btn-sm btn-dark me-3" title="Download {{ $user->alias_name }}'s Last Month's ({{ date('F Y', strtotime('last month')) }}) Attendances.">
                            <span class="tf-icon ti ti-download me-1"></span>
                            Download
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead class="bg-label-primary">
                            <tr>
                                <th>Sl.</th>
                                <th>Date</th>
                                <th>Clocked IN</th>
                                <th>Clock Out</th>
                                <th class="text-center">Breaks</th>
                                <th>Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $key => $attendance)
                                <tr>
                                    <th>#{{ serial($attendances, $key) }}</th>
                                    <td>
                                        <span class="text-truncate">{{ show_date($attendance->clock_in_date) }}</span>
                                        <br>
                                        <small class="text-bold badge bg-{{ $attendance->type === 'Regular' ? 'success' : 'warning' }}">{{ $attendance->type }}</small>
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            @php
                                                if (get_time_only($attendance->clock_in) > $attendance->employee_shift->start_time){
                                                    $clockInColor = 'text-danger';
                                                } else {
                                                    $clockInColor = 'text-success';
                                                }
                                            @endphp
                                            <span class="text-truncate text-bold {{ $clockInColor }}">{{ show_time($attendance->clock_in) }}</span>
                                            <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="left" title="Shift Start Time">{{ show_time($attendance->employee_shift->start_time) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            @isset($attendance->clock_out)
                                                @php
                                                    if (get_time_only($attendance->clock_out) < $attendance->employee_shift->end_time){
                                                        $clockOutColor = 'text-danger';
                                                    } else {
                                                        $clockOutColor = 'text-success';
                                                    }
                                                @endphp
                                                <span class="text-truncate text-bold {{ $clockOutColor }}">{{ show_time($attendance->clock_out) }}</span>
                                            @else
                                                <b class="text-success text-uppercase">Running</b>
                                            @endisset
                                            <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="right" title="Shift End Time">{{ show_time($attendance->employee_shift->end_time) }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center {{ $attendance->type == 'Overtime' ? 'not-allowed' : '' }}">
                                        @if ($attendance->type == 'Regular')
                                            <div class="d-grid">
                                                <b class="text-truncate">
                                                    <span class="text-warning" title="Total Break Time">
                                                        {{ total_time($attendance->total_break_time) }}
                                                    </span>
                                                    @isset ($attendance->total_over_break)
                                                        <br>
                                                        <small class="text-danger" title="Total Over Break">
                                                            ({{ total_time($attendance->total_over_break) }})
                                                        </small>
                                                    @endisset
                                                </b>
                                                <small class="text-truncate text-muted">
                                                    Breaks Taken: {{ $attendance->total_breaks_taken }}
                                                </small>
                                            </div>
                                        @else
                                            <b class="text-muted">No Break</b>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            @if ($attendance->type == 'Regular')
                                                @isset($attendance->total_adjusted_time)
                                                    @php
                                                        $totalWorkingHour = get_total_hour($attendance->employee_shift->start_time, $attendance->employee_shift->end_time);
                                                    @endphp
                                                    <b title="Adjusted Total Time">
                                                        {!! total_time_with_min_hour($attendance->total_adjusted_time, $totalWorkingHour) !!}
                                                    </b>
                                                @else
                                                    <b class="text-success text-uppercase">Running</b>
                                                @endisset
                                                <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Total Working Time">{{ $attendance->total_time }}</small>
                                            @else
                                                <b class="text-warning">
                                                    {{ total_time($attendance->total_time) }}
                                                </b>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('administration.attendance.show', ['attendance' => $attendance]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                            <i class="ti ti-info-hexagon"></i>
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
<!--/ User Profile Content -->
@endsection
