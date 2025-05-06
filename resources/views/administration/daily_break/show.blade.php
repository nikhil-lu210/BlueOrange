@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Daily Break Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Daily Break Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Break') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.daily_break.my') }}">{{ __('My Daily Breaks') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Daily Break Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"><b>{{ $break->user->alias_name }}'s</b> {{ $break->type }} Break Details of {{ show_date($break->attendance->clock_in_date) }}</h5>

                @canany(['Daily Break Update', 'Daily Break Delete'])
                    <div class="card-header-elements ms-auto">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#editDailyBreak" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                            Edit Break
                        </button>
                    </div>
                @endcanany
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <small class="card-text text-uppercase">Break Details</small>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-hash"></i>
                                        <span class="fw-medium mx-2 text-heading">Break Type:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        @if ($break->type == 'Short')
                                            <span class="badge bg-primary">{{ __('Short Break') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('Long Break') }}</span>
                                        @endif
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-clock-play"></i>
                                        <span class="fw-medium mx-2 text-heading">Break Started:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-primary">{{ show_time($break->break_in_at) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-clock-stop"></i>
                                        <span class="fw-medium mx-2 text-heading">Break Started:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        @if (!is_null($break->break_out_at))
                                            <span class="text-primary">{{ show_time($break->break_out_at) }}</span>
                                        @else
                                            <span class="text-success">Break Running</span>
                                        @endif
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-clock-plus"></i>
                                        <span class="fw-medium mx-2 text-heading">Total Break:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        @if (!is_null($break->total_time))
                                            <span class="text-warning">{{ total_time($break->total_time) }}</span>
                                        @else
                                            <span class="text-success">Break Running</span>
                                        @endif
                                    </dd>
                                </dl>
                                @isset ($break->over_break)
                                    <dl class="row mb-1">
                                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                            <i class="ti ti-clock-plus"></i>
                                            <span class="fw-medium mx-2 text-heading">Over Break:</span>
                                        </dt>
                                        <dd class="col-sm-8">
                                            <span class="text-danger">{{ total_time($break->over_break) }}</span>
                                        </dd>
                                    </dl>
                                @endisset
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-access-point text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Break Start IP:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $break->break_in_ip }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-access-point text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Break Stop IP:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $break->break_out_ip }}</span>
                                    </dd>
                                </dl>
                                @isset ($break->note)
                                    <dl class="row mb-1">
                                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                            <i class="ti ti-notes text-heading"></i>
                                            <span class="fw-medium mx-2 text-heading">Note:</span>
                                        </dt>
                                        <dd class="col-sm-8">
                                            <span>{{ $break->note }}</span>
                                        </dd>
                                    </dl>
                                @endisset
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <small class="card-text text-uppercase">Attendance Details</small>
                                @php
                                    $attendance = $break->attendance;
                                @endphp
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-calendar-event text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Date:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <a href="{{ route('administration.attendance.show', ['attendance' => $attendance]) }}" target="_blank" title="Show Attenance Details">
                                            <span class="text-bold badge bg-label-dark">{{ show_date($attendance->clock_in_date) }}</span>
                                        </a>
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
                                        <i class="ti ti-access-point text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Clock-In IP:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span>{{ $attendance->ip_address }}</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

{{-- Modal for Daily Break Edit --}}
@canany(['Daily Break Update', 'Daily Break Delete'])
<div class="modal fade" id="editDailyBreak" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('administration.daily_break.update', ['break' => $break]) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editDailyBreakTitle">
                        <span class="ti ti-edit ti-sm me-1"></span>
                        Update Daily Break
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="break_in_at" class="form-label">{{ __('Break Start') }} <b class="text-danger">*</b></label>
                            <input type="text" id="break_in_at" name="break_in_at" value="{{ $break->break_in_at ? get_time_only($break->break_in_at) : old('break_in_at') }}" placeholder="HH:MM:SS" class="form-control time-picker @error('break_in_at') is-invalid @enderror" required/>
                            @error('break_in_at')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="break_out_at" class="form-label">{{ __('Break Stop') }} <b class="text-danger">*</b></label>
                            <input type="text" id="break_out_at" name="break_out_at" value="{{ $break->break_out_at ? get_time_only($break->break_out_at) : old('break_out_at') }}" placeholder="HH:MM:SS" class="form-control time-picker @error('break_out_at') is-invalid @enderror" required/>
                            @error('break_out_at')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="type" class="form-label">{{ __('Break Type') }} <b class="text-danger">*</b></label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="Short" {{ $break->type == 'Short' ? 'selected' : '' }}>{{ __('Short Break') }}</option>
                                <option value="Long" {{ $break->type == 'Long' ? 'selected' : '' }}>{{ __('Long Break') }}</option>
                            </select>
                            @error('announcer_id')
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
                        Update Break
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
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
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

            $('.time-picker').flatpickr({
                enableTime: true,
                noCalendar: true,
            });
        });
    </script>
@endsection
