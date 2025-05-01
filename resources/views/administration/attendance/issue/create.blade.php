@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create Attendance Issue'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

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
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Create Attendance Issue') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance Issues') }}</li>
    <li class="breadcrumb-item active">{{ __('Create Attendance Issue') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-7">
        <form action="{{ route('administration.attendance.issue.store') }}" method="POST" id="attendanceIssueForm" autocomplete="off">
            @csrf
            <div class="card mb-4">
                <div class="card-header header-elements">
                    <h5 class="mb-0">{{ __('Assign Attendance Issue') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="title" class="form-label">{{ __('Issue Title') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Forgot To Clockin" class="form-control @error('title') is-invalid @enderror" required/>
                            @error('title')
                                <b class="text-danger">
                                    <i class="feather icon-info mr-1"></i>
                                    {{ $message }}
                                </b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="attendance_issue_type" class="form-label">{{ __('Issue For') }} <strong class="text-danger">*</strong></label>
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="attendanceOld">
                                            <input name="attendance_issue_type" class="form-check-input" type="radio" value="Old" id="attendanceOld" required @checked(old('attendance_issue_type') == 'Old')/>
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">{{ __('Update Old Attendance') }}</span>
                                            </span>
                                            <span class="custom-option-body">
                                                <small>{{ __('If you have clocked-in already, but there is something wrong with that attendance.') }}</small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="attendanceNew">
                                            <input name="attendance_issue_type" class="form-check-input" type="radio" value="New" id="attendanceNew" required @checked(old('attendance_issue_type') == 'New')/>
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">{{ __('New Attendance') }}</span>
                                            </span>
                                            <span class="custom-option-body">
                                                <small>{{ __('If you have not clocked-in or, missed to clock-in today or, on any particular date.') }}</small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- Validation Error Message --}}
                            @error('attendance_issue_type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12" id="newClockInDate">
                            <label for="clock_in_date" class="form-label">{{ __('Select Clock-In Date') }} <strong class="text-danger">*</strong></label>
                            <select name="clock_in_date" id="clock_in_date" class="select2 form-select @error('clock_in_date') is-invalid @enderror" data-allow-clear="true" required>
                                <option value="" selected>{{ __('Select Clock-In Date') }}</option>
                                @foreach ($dates as $date)
                                    <option value="{{ $date }}" {{ old('clock_in_date') == $date ? 'selected' : '' }}>
                                        {{ show_date($date) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('clock_in_date')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12" id="oldClockInDate">
                            <label for="attendance_id" class="form-label">{{ __('Select Attendance') }} <strong class="text-danger">*</strong></label>
                            <select name="attendance_id" id="attendance_id" class="select2 form-select @error('attendance_id') is-invalid @enderror" data-allow-clear="true" required>
                                <option value="" selected>{{ __('Select Attendance') }}</option>
                                @foreach ($attendances as $attendance)
                                    <option value="{{ $attendance->id }}" {{ old('attendance_id') == $attendance->id ? 'selected' : '' }}>
                                        {{ show_date_time($attendance->clock_in) }} | {{ $attendance->type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('attendance_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="clock_in" class="form-label">{{ __('Clockin Time') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="clock_in" name="clock_in" value="{{ old('clock_in') }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('clock_in') is-invalid @enderror" required/>
                            @error('clock_in')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="clock_out" class="form-label">{{ __('Clockout Time') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="clock_out" name="clock_out" value="{{ old('clock_out') }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('clock_out') is-invalid @enderror" required/>
                            @error('clock_out')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="type" class="form-label">{{ __('Select Clockin Type') }} <strong class="text-danger">*</strong></label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="" selected disabled>{{ __('Select Type') }}</option>
                                <option value="Regular" {{ old('type') == 'Regular' ? 'selected' : '' }}>{{ __('Regular') }}</option>
                                <option value="Overtime" {{ old('type') == 'Overtime' ? 'selected' : '' }}>{{ __('Overtime') }}</option>
                            </select>
                            @error('type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">{{ __('Explain Issue Reason') }} <strong class="text-danger">*</strong></label>
                            <div name="reason" id="full-editor">{!! old('reason') !!}</div>
                            <textarea class="d-none" name="reason" id="reason-input">{{ old('reason') }}</textarea>
                            @error('reason')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-check ti-xs me-1"></span>
                            {{ __('Create Issue') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End row -->

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
        // Custom Script Here
        $(document).ready(function() {
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

        $(document).ready(function () {
            // Initially hide, disable both fields, and remove required attribute
            $('#newClockInDate, #oldClockInDate').hide().find('select').prop('required', false).prop('disabled', true);

            function toggleClockInDateFields() {
                let selectedType = $('input[name="attendance_issue_type"]:checked').val();

                if (selectedType === 'Old') {
                    $('#oldClockInDate').show().find('select').prop('required', true).prop('disabled', false);
                    $('#newClockInDate').hide().find('select').prop('required', false).prop('disabled', true);
                } else if (selectedType === 'New') {
                    $('#newClockInDate').show().find('select').prop('required', true).prop('disabled', false);
                    $('#oldClockInDate').hide().find('select').prop('required', false).prop('disabled', true);
                } else {
                    // If no selection, hide, disable both, and remove required
                    $('#newClockInDate, #oldClockInDate').hide().find('select').prop('required', false).prop('disabled', true);
                }
            }

            // Bind change event to radio buttons
            $('input[name="attendance_issue_type"]').on('change', toggleClockInDateFields);
        });
    </script>

    <script>
        $(document).ready(function () {
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                [{ script: "super" }, { script: "sub" }],
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ];

            var fullEditor = new Quill("#full-editor", {
                bounds: "#full-editor",
                placeholder: "Ex: Have forgot to bring my ID Card. So Please consider me.",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old reason if validation fails
            @if(old('reason'))
                fullEditor.root.innerHTML = {!! json_encode(old('reason')) !!};
            @endif

            $('#attendanceIssueForm').on('submit', function() {
                $('#reason-input').val(fullEditor.root.innerHTML);
            });
        });
    </script>
@endsection
