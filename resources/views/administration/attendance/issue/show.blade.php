@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', ___('Attendance Issue Details'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    {{-- Bootstrap Datepicker --}}
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
    .btn-block {
        width: 100%;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ ___('Attendance Issue Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ ___('Attendance') }}</li>
    <li class="breadcrumb-item">{{ ___('Attendance Issue') }}</li>
    <li class="breadcrumb-item">
        @can ('Update Attenance Issue')
            <a href="{{ route('administration.attendance.issue.index') }}">{{ ___('All Issues') }}</a>
        @else
            <a href="{{ route('administration.attendance.issue.my') }}">{{ ___('My Issues') }}</a>
        @endcan
    </li>
    <li class="breadcrumb-item active">{{ ___('Attendance Issue Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-grow-1 mt-4">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-0">{{ ___($issue->title) }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Issue Created By" data-bs-placement="bottom">
                                    <i class="ti ti-crown"></i>
                                    {{ $issue->user->alias_name }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Issue Creation Date & Time">
                                    <i class="ti ti-calendar"></i>
                                    {{ show_date_time($issue->created_at) }}
                                </li>
                            </ul>
                        </div>
                        @if ($issue->status === 'Pending')
                            @can ('Announcement Update')
                                <div class="card-header-elements ms-auto">
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectAttendanceIssueModal">
                                        <span class="tf-icon ti ti-ban ti-xs me-1"></span>
                                        {{ ___('Reject') }}
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveAttendanceIssueModal">
                                        <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                        {{ ___('Approve') }}
                                    </button>
                                </div>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Warning for existing Regular attendance --}}
    @if($existingRegularAttendance && $issue->status === 'Pending')
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <h5 class="alert-heading">
                    <i class="ti ti-alert-triangle me-2"></i>
                    {{ ___('Existing Regular Attendance Detected') }}
                </h5>
                <p class="mb-2">
                    <strong>{{ ___('Warning') }}:</strong> {{ ___('A Regular attendance record already exists for') }} {{ $issue->user->alias_name }} {{ ___('on') }} {{ show_date($issue->clock_in_date) }}.
                </p>
                <p class="mb-2">
                    <strong>{{ ___('Existing Record') }}:</strong>
                    {{ ___('Clock-in:') }} {{ show_time($existingRegularAttendance->clock_in) }} {{ ___('|') }}
                    {{ ___('Clock-out:') }} {{ $existingRegularAttendance->clock_out ? show_time($existingRegularAttendance->clock_out) : ___('Not clocked out') }}
                </p>
                <hr>
                <p class="mb-0">
                    <strong>{{ ___('Recommendation') }}:</strong> {{ ___('If you approve this Regular attendance issue, it will fail because a Regular attendance already exists.') }}
                    {{ ___('Consider asking the employee to request an update to the existing attendance record instead.') }}
                </p>
            </div>
        </div>
    @endif

    {{-- Attendance Issue Details --}}
    <div class="col-md-7">
        @include('administration.attendance.issue.partials._issue_details')
    </div>

    @if ($issue->attendance)
        <div class="col-md-5">
            @include('administration.attendance.issue.partials._attendance_details')
        </div>
    @endif
</div>


{{-- Approve Issue Modal --}}
@include('administration.attendance.issue.modals.approve_modal')
{{-- reject Issue Modal --}}
@include('administration.attendance.issue.modals.reject_modal')
@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });

            $('.date-time-picker').flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i'
            });
        });
    </script>
@endsection
