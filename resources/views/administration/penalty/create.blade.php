@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create Penalty'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .penalty-form .form-label {
            font-weight: 600;
        }
        .penalty-form .required::after {
            content: " *";
            color: red;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create Penalty') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.penalty.index') }}">{{ __('All Penalties') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Create Penalty') }}</li>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Create New Penalty') }}</h5>
            </div>
            <div class="card-body penalty-form">
                <form action="{{ route('administration.penalty.store') }}" method="POST" enctype="multipart/form-data" id="penaltyForm">
                    @csrf

                    <div class="row">
                        <!-- Employee Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label required">{{ __('Select Employee') }}</label>
                            <select class="form-select select2" id="user_id" name="user_id" required>
                                <option value="">{{ __('Choose Employee...') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->employee->alias_name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Attendance Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="attendance_id" class="form-label required">{{ __('Select Attendance') }}</label>
                            <select class="form-select select2" id="attendance_id" name="attendance_id" required disabled>
                                <option value="">{{ __('First select an employee...') }}</option>
                            </select>
                            @error('attendance_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Penalty Type -->
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label required">{{ __('Penalty Type') }}</label>
                            <select class="form-select select2" id="type" name="type" required>
                                <option value="">{{ __('Choose Penalty Type...') }}</option>
                                @foreach($penaltyTypes as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Penalty Time -->
                        <div class="col-md-6 mb-3">
                            <label for="total_time" class="form-label required">{{ __('Penalty Time (Minutes)') }}</label>
                            <input type="number" class="form-control" id="total_time" name="total_time"
                                   value="{{ old('total_time') }}" min="1" max="1440" required>
                            <div class="form-text">{{ __('Enter penalty time in minutes (1-1440)') }}</div>
                            @error('total_time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="mb-3">
                        <label for="reason" class="form-label required">{{ __('Reason for Penalty') }}</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4"
                                  placeholder="{{ __('Provide detailed reason for the penalty...') }}" required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="files" class="form-label">{{ __('Penalty Proof (Optional)') }}</label>
                        <div class="dropzone" id="penalty-dropzone">
                            <div class="dz-message">
                                <i class="ti ti-upload display-4"></i>
                                <h5>{{ __('Drop files here or click to upload') }}</h5>
                                <span>{{ __('Upload penalty proof documents (Max 5MB per file)') }}</span>
                            </div>
                        </div>
                        @error('files.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('administration.penalty.index') }}" class="btn btn-outline-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>{{ __('Create Penalty') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('vendor_js')
    {{--  External JS  --}}
    <!-- Select2 JS -->
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <!-- Dropzone JS -->
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
@endsection

@section('custom_js')
    {{--  External JS  --}}
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Handle employee selection change
            $('#user_id').on('change', function() {
                const userId = $(this).val();
                const attendanceSelect = $('#attendance_id');

                if (userId) {
                    // Enable attendance dropdown and show loading
                    attendanceSelect.prop('disabled', false);
                    attendanceSelect.html('<option value="">Loading...</option>');

                    // Fetch attendances for selected user
                    $.get('{{ route('administration.penalty.attendances') }}', { user_id: userId })
                        .done(function(data) {
                            let options = '<option value="">Choose Attendance...</option>';
                            data.forEach(function(attendance) {
                                options += `<option value="${attendance.id}">${attendance.text}</option>`;
                            });
                            attendanceSelect.html(options);
                        })
                        .fail(function() {
                            attendanceSelect.html('<option value="">Error loading attendances</option>');
                        });
                } else {
                    // Reset attendance dropdown
                    attendanceSelect.prop('disabled', true);
                    attendanceSelect.html('<option value="">First select an employee...</option>');
                }
            });

            // Initialize Dropzone
            Dropzone.autoDiscover = false;
            const penaltyDropzone = new Dropzone("#penalty-dropzone", {
                url: "{{ route('administration.penalty.store') }}",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 5,
                maxFilesize: 5,
                acceptedFiles: ".jpg,.jpeg,.png,.pdf,.doc,.docx",
                addRemoveLinks: true,
                paramName: "files",
                init: function() {
                    const submitButton = document.querySelector("#penaltyForm button[type=submit]");
                    const myDropzone = this;

                    submitButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (myDropzone.getQueuedFiles().length > 0) {
                            myDropzone.processQueue();
                        } else {
                            document.getElementById("penaltyForm").submit();
                        }
                    });

                    this.on("sendingmultiple", function(files, xhr, formData) {
                        // Add form data to the request
                        const form = document.getElementById("penaltyForm");
                        const formDataObj = new FormData(form);
                        for (let [key, value] of formDataObj.entries()) {
                            if (key !== 'files[]') {
                                formData.append(key, value);
                            }
                        }
                    });

                    this.on("successmultiple", function(files, response) {
                        window.location.href = "{{ route('administration.penalty.index') }}";
                    });

                    this.on("errormultiple", function(files, response) {
                        console.error("Upload failed:", response);
                    });
                }
            });
        });
    </script>
@endsection
