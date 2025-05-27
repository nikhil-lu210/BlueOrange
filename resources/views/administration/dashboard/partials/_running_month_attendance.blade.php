<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-border-shadow-primary">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Attendances Of <b class="text-bold text-primary">{{ date('F Y') }}</b></h5>

                <div class="card-header-elements ms-auto" style="margin-top: -5px;">
                    <small class="badge bg-primary cursor-pointer" title="Total Working Hour (Regular)" data-bs-placement="top" >
                        {{ total_time($totalRegularWorkingHour) }}
                    </small>

                    @isset ($totalOvertimeWorkingHour)
                        <small class="badge bg-warning cursor-pointer" title="Total Working Hour (Overtime)" data-bs-placement="bottom" >
                            {{ total_time($totalOvertimeWorkingHour) }}
                        </small>
                    @endisset
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-left">Date</th>
                                <th class="text-center">Clock In</th>
                                <th class="text-center">Clock Out</th>
                                <th class="text-center">Total Worked</th>
                                <th class="text-center">Total Break</th>
                                <th class="text-center">Over Break</th>
                                <th class="text-right">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $key => $attendance)
                                <tr>
                                    <td class="text-left">
                                        <span class="fw-medium">{{ show_date_month_day($attendance->clock_in_date) }}</span>
                                    </td>
                                    <td class="text-center">{{ show_time($attendance->clock_in) }}</td>
                                    <td class="text-center">
                                        @isset ($attendance->clock_out)
                                            <span>{{ show_time($attendance->clock_out) }}</span>
                                        @else
                                            <span class="text-bold text-success">Running</span>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @isset ($attendance->total_time)
                                            <span>{{ total_time($attendance->total_time) }}</span>
                                        @else
                                            <span class="text-bold text-success">Running</span>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @isset ($attendance->total_break_time)
                                            <span>{{ total_time($attendance->total_break_time) }}</span>
                                        @else
                                            <span class="text-bold text-success" title="No Break Taken" data-bs-placement="right">
                                                <i class="ti ti-clock-play"></i>
                                            </span>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @isset ($attendance->total_over_break)
                                            <span class="text-danger">{{ total_time($attendance->total_over_break) }}</span>
                                        @else
                                            <span class="text-bold text-success" title="No Over Break" data-bs-placement="right">
                                                <i class="ti ti-mood-check"></i>
                                            </span>
                                        @endisset
                                    </td>
                                    <td class="text-right">
                                        @if ($attendance->type == 'Regular')
                                            <span class="badge bg-label-primary me-1">Regular</span>
                                        @else
                                            <span class="badge bg-label-warning me-1">Overtime</span>
                                        @endif
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
