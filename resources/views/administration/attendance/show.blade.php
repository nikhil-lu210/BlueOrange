@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Attendance Details'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Attendance Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.attendance.index') }}">{{ __('All Attendances') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Attendance Details') }}</li>
    <li class="breadcrumb-item active">{{ get_date_only($attendance->clock_in_date) }} ({{ $attendance->type }})</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"><strong>{{ $attendance->user->alias_name }}'s</strong> {{ $attendance->type }} Attendance Details of {{ show_date($attendance->clock_in_date) }}</h5>

                @canany(['Attendance Update', 'Attendance Delete'])
                    <div class="card-header-elements ms-auto">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#editAttendance" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                            Edit Attendance
                        </button>
                    </div>
                @endcanany
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-7">
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
                    </div>

                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <small class="card-text text-uppercase">Daily Break's Details</small>
                                    <div class="ms-auto" style="margin-top: -5px;">
                                        @isset ($attendance->total_break_time)
                                            <small class="badge bg-dark" title="Total Break Taken">
                                                {{ total_time($attendance->total_break_time) }}
                                            </small>
                                        @endisset
                                        @isset ($attendance->total_over_break)
                                            <small class="badge bg-danger" title="Total Over Break">
                                                {{ total_time($attendance->total_over_break) }}
                                            </small>
                                        @endisset
                                    </div>
                                </div>
                                <ul class="timeline mb-0 pb-1 mt-4">
                                    @forelse ($attendance->daily_breaks as $key => $break)
                                        <li class="timeline-item ps-4 {{ $loop->last ? 'border-transparent' : 'border-left-dashed pb-1' }}">
                                            <span class="timeline-indicator-advanced timeline-indicator-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">
                                                <i class="ti ti-{{ $break->break_out_at ? 'clock-stop' : 'clock-play' }}"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-uppercase fw-medium" title="Click To See Details">
                                                        <a href="{{ route('administration.daily_break.show', ['break' => $break]) }}" class="text-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">{{ $break->type }} Break</a>
                                                    </small>
                                                </div>
                                                <small class="text-muted mb-0">
                                                    {{ show_time($break->break_in_at) }}
                                                    @if (!is_null($break->break_out_at))
                                                        <span>to</span>
                                                        <span>{{ show_time($break->break_out_at) }}</span>
                                                    @else
                                                        -
                                                        <span class="text-danger">Break Running</span>
                                                    @endif
                                                </small>
                                                <h6 class="mb-1">
                                                    @if (is_null($break->total_time))
                                                        <span class="text-danger">Break Running</span>
                                                    @else
                                                        <span class="text-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">{{ total_time($break->total_time) }}</span>
                                                        @isset($break->over_break)
                                                            <small class="text-danger text-bold mt-1" title="Over Break">({{ total_time($break->over_break) }})</small>
                                                        @endisset
                                                    @endif
                                                </h6>
                                            </div>
                                        </li>
                                    @empty
                                        <div class="text-center text-bold text-muted fs-2">No Breaks</div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <small class="card-text text-uppercase">Attendance Issues</small>
                                </div>
                                <ul class="timeline mb-0 pb-1 mt-4">
                                    @forelse ($attendance->issues as $key => $issue)
                                        @php
                                            switch ($issue->status) {
                                                case 'Approved':
                                                    $color = 'success';
                                                    break;

                                                case 'Rejected':
                                                    $color = 'danger';
                                                    break;

                                                default:
                                                    $color = 'primary';
                                                    break;
                                            }
                                        @endphp
                                        <li class="timeline-item ps-4 {{ $loop->last ? 'border-transparent' : 'border-left-dashed pb-1' }}">
                                            <span class="timeline-indicator-advanced timeline-indicator-{{ $color }}">
                                                <i class="ti ti-bell-question"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-capitalize fw-bold" title="Click To See Details">
                                                        <a href="{{ route('administration.attendance.issue.show', ['issue' => $issue]) }}" target="_blank" class="text-{{ $color }}">{{ $issue->title }}</a>
                                                    </small>
                                                </div>
                                                <small class="text-muted mb-0">
                                                    {{ show_date_time($issue->created_at) }}
                                                </small>
                                                <h6 class="mb-1 mt-1">
                                                    <span class="badge bg-{{ $color }}">{{ $issue->status }}</span>
                                                </h6>
                                            </div>
                                        </li>
                                    @empty
                                        <div class="text-center text-bold text-muted fs-2">No Issues</div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

{{-- Modal for Attendance Edit --}}
@canany(['Attendance Update', 'Attendance Delete'])
<div class="modal fade" id="editAttendance" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('administration.attendance.update', ['attendance' => $attendance]) }}" method="post" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editAttendanceTitle">
                        <span class="ti ti-edit ti-sm me-1"></span>
                        Update Attendance
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="type" class="form-label">Select Clockin Type <strong class="text-danger">*</strong></label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="Regular" @selected($attendance->type == 'Regular' ?? old('type'))>Regular</option>
                                <option value="Overtime" @selected($attendance->type == 'Overtime' ?? old('type'))>Overtime</option>
                            </select>
                            @error('type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="clock_in" class="form-label">{{ __('Clock In') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="clock_in" name="clock_in" value="{{ $attendance->clock_in ?? old('clock_in') }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('clock_in') is-invalid @enderror" required/>
                            @error('clock_in')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="clock_out" class="form-label">{{ __('Clock Out') }}</label>
                            <input type="text" id="clock_out" name="clock_out" value="{{ $attendance->clock_out ?? '' }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('clock_out') is-invalid @enderror"/>
                            @error('clock_out')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i>
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check"></i>
                        Update Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcanany

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    {{-- <!-- Page JS --> --}}
    {{-- <script src="{{ asset('assets/js/forms-pickers.js') }}"></script> --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.date-time-picker').flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i'
            });
        });
    </script>
@endsection
