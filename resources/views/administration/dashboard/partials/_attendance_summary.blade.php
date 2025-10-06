<div class="row">
    <div class="col-md-12">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-body row">
                <div class="col-md-8 card-separator">
                    <div class="d-flex justify-content-between flex-wrap gap-3 me-3">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="bg-label-success p-2 rounded">
                                <i class="ti ti-briefcase ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-success mb-0" title="{{ __('Total Worked') }}" data-bs-placement="left">{{ format_number($totalWorkedDays) }} <small>Days</small></h5>
                                <small class="mb-0" title="{{ __('Total Days in '). config('app.name') }}" data-bs-placement="left">{{ total_day($user->employee->joining_date) }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-primary p-2 rounded">
                                <i class="ti ti-hourglass-high ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-primary mb-0">{{ total_time($totalRegularWork) }}</h5>
                                <small class="mb-0 text-muted">Total Worked (Regular)</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                                <i class="ti ti-hourglass-low ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-warning mb-0">{{ total_time($totalOvertimeWork) }}</h5>
                                <small class="mb-0 text-muted">Total Worked (Overtime)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex justify-content-between flex-wrap gap-3 px-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-2">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-calendar-time ti-md"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0 text-nowrap live-time text-bold" id="liveTime"></h5>
                                <small>{{ date('jS M, Y (l)') }}</small>
                            </div>
                        </div>
                        @if (auth()->user()->hasAllPermissions(['Attendance Create', 'Attendance Read']))
                            @isset($activeAttendance->clock_in)
                                <div class="d-flex align-items-center">
                                    <form action="{{ route('administration.attendance.clockout') }}" method="post" class="mb-0 confirm-form-danger">
                                        @csrf
                                        <div class="avatar flex-shrink-0 me-2">
                                            <button type="submit" name="attendance" value="clock_out"
                                                    class="avatar-initial rounded bg-danger border-0"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-container="body"
                                                    title="Clock Out">
                                                <i class="ti ti-clock-off ti-md"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <div>
                                        <h5 class="mb-0 text-nowrap live-working-time text-{{ $activeAttendance->type == 'Regular' ? 'primary' : 'warning' }}"
                                            id="liveWorkingTime"
                                            data-clock-in-at="{{ $activeAttendance->clock_in->timestamp }}">
                                        </h5>
                                        <small class="bg-label-{{ $activeAttendance->type == 'Regular' ? 'primary' : 'warning' }} p-1 px-2 rounded-2 text-bold">{{ $activeAttendance->type }}</small>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('administration.attendance.clockin') }}" method="post" class="mb-0">
                                    @csrf
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="avatar flex-shrink-0 me-2">
                                            <button type="button" class="avatar-initial rounded bg-primary border-0 submit-regular"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-container="body"
                                                    title="Regular Clock In">
                                                <i class="ti ti-clock-check ti-md"></i>
                                            </button>
                                        </div>
                                        <div class="avatar flex-shrink-0 me-2">
                                            <button type="button" class="avatar-initial rounded bg-warning border-0 submit-overtime"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-container="body"
                                                    title="Overtime Clock In">
                                                <i class="ti ti-clock-check ti-md"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Hidden input to store attendance type -->
                                    <input type="hidden" name="attendance" id="attendanceType">
                                </form>
                            @endisset
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
