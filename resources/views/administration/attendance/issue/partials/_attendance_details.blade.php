<div class="card mb-4">
    <div class="card-body">
        <small class="card-text text-uppercase">Attendance Details</small>
        @php
            $attendance = $issue->attendance;
        @endphp
        <dl class="row mt-3 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar-event text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Date:</span>
            </dt>
            <dd class="col-sm-8">
                <a href="{{ route('administration.attendance.show', ['attendance' => $attendance]) }}" target="_blank" class="text-bold badge bg-label-primary" title="Click here to view attendance details">{{ show_date($attendance->clock_in_date) }}</a>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-check text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Working Shift:</span>
            </dt>
            <dd class="col-sm-8">
                <span>{{ show_time($attendance->employee_shift->start_time) }}</span>
                <i class="ti ti-minus text-bold"></i>
                <span>{{ show_time($attendance->employee_shift->end_time) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-hourglass-filled text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Total Working Hour:</span>
            </dt>
            <dd class="col-sm-8">
                <span>{{ total_time_difference($attendance->employee_shift->start_time, $attendance->employee_shift->end_time) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-plus text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Clock In:</span>
            </dt>
            <dd class="col-sm-8">
                @php
                    if (get_time_only($attendance->clock_in) <= $attendance->employee_shift->start_time){
                        $clockInColor = 'text-success';
                    } else {
                        $clockInColor = 'text-danger';
                    }
                @endphp
                <span class="{{ $clockInColor }}">{{ show_time($attendance->clock_in) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-minus text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Clock Out:</span>
            </dt>
            <dd class="col-sm-8">
                <span>
                    @isset($attendance->clock_out)
                        @if (show_date($attendance->clock_in) != show_date($attendance->clock_out))
                            <span class="text-warning text-bold">
                                {{ show_date_time($attendance->clock_out) }}
                            </span>
                        @else
                            @php
                                if (get_time_only($attendance->clock_out) < $attendance->employee_shift->end_time){
                                    $clockOutColor = 'text-danger';
                                } else {
                                    $clockOutColor = 'text-success';
                                }
                            @endphp
                            <span class="{{ $clockOutColor }}">{{ show_time($attendance->clock_out) }}</span>
                        @endif
                    @else
                        <b class="text-success text-uppercase">Running</b>
                    @endisset
                </span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-hourglass text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Total Worked:</span>
            </dt>
            <dd class="col-sm-8">
                @isset($attendance->total_time)
                    @php
                        $totalWorkingHour = get_total_hour($attendance->employee_shift->start_time, $attendance->employee_shift->end_time);
                    @endphp
                    <b>
                        {!! total_time_with_min_hour($attendance->total_time, $totalWorkingHour) !!}
                    </b>
                @else
                    <b class="text-success text-uppercase">Running</b>
                @endisset
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-hash text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Attendance Type:</span>
            </dt>
            <dd class="col-sm-8">
                <small class="text-bold badge bg-{{ $attendance->type === 'Regular' ? 'success' : 'warning' }}">
                    {{ $attendance->type }}
                </small>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-access-point text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Clock-In IP:</span>
            </dt>
            <dd class="col-sm-8">
                <span>{{ $attendance->ip_address }}</span>
            </dd>
        </dl>
        <hr>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar-check text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Created At:</span>
            </dt>
            <dd class="col-sm-8">
                <small class="text-dark">{{ show_date_time($attendance->created_at) }}</small>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar-x text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Updated At:</span>
            </dt>
            <dd class="col-sm-8">
                <small class="text-dark">{{ show_date_time($attendance->updated_at) }}</small>
            </dd>
        </dl>
    </div>
</div>