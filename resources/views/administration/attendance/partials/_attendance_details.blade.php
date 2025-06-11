<div class="card mb-4">
    <div class="card-body">
        <small class="card-text text-uppercase">Attendance Details</small>
        <dl class="row mt-3 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar-event text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Date:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-bold badge bg-label-dark">{{ show_date($attendance->clock_in_date) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-hash"></i>
                <span class="fw-medium mx-2 text-heading">Attendance Type:</span>
            </dt>
            <dd class="col-sm-8">
                @if ($attendance->type == 'Regular')
                    <span class="badge bg-primary">{{ __('Regular Attendance') }}</span>
                @else
                    <span class="badge bg-warning">{{ __('Overtime Attendance') }}</span>
                @endif
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
                @isset($attendance->total_adjusted_time)
                    @php
                        $totalWorkingHour = get_total_hour($attendance->employee_shift->start_time, $attendance->employee_shift->end_time);
                    @endphp
                    <b>
                        {!! total_time_with_min_hour($attendance->total_adjusted_time, $totalWorkingHour) !!}
                        @if ($attendance->type == 'Regular')
                            <small class="text-muted" title="Total Attendance Time">({{ total_time($attendance->total_time) }})</small>
                        @endif
                    </b>
                @else
                    <b class="text-success text-uppercase">Running</b>
                @endisset
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
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-world-latitude text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Latitude:</span>
            </dt>
            <dd class="col-sm-8">
                <span>{{ $attendance->latitude }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-world-longitude text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Longitude:</span>
            </dt>
            <dd class="col-sm-8">
                <span>{{ $attendance->longitude }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-world-check text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Country:</span>
            </dt>
            <dd class="col-sm-8">
                <span>{{ $attendance->country }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-location text-heading"></i>
                <span class="fw-medium mx-2 text-heading">City:</span>
            </dt>
            <dd class="col-sm-8">
                <span>{{ $attendance->city }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-zip text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Zip Code:</span>
            </dt>
            <dd class="col-sm-8">
                <span>{{ $attendance->zip_code }}</span>
            </dd>
        </dl>

        @canany (['Attendance Update', 'Attendance Delete'])
            <hr>
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-clock-check text-heading"></i>
                    <span class="fw-medium mx-2 text-heading">Clockin Medium:</span>
                </dt>
                <dd class="col-sm-8">
                    <b>{{ $attendance->clockin_medium }}</b>
                </dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-clock-x text-heading"></i>
                    <span class="fw-medium mx-2 text-heading">Clockout Medium:</span>
                </dt>
                <dd class="col-sm-8">
                    <b>{{ $attendance->clockout_medium }}</b>
                </dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-check text-heading"></i>
                    <span class="fw-medium mx-2 text-heading">Clockin Scanned By:</span>
                </dt>
                <dd class="col-sm-8">
                    <b>{{ optional($attendance->clockin_scanner)->name }}</b>
                </dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-x text-heading"></i>
                    <span class="fw-medium mx-2 text-heading">Clockout Scanned By:</span>
                </dt>
                <dd class="col-sm-8">
                    <b>{{ optional($attendance->clockout_scanner)->name }}</b>
                </dd>
            </dl>
        @endcanany
    </div>
</div>
