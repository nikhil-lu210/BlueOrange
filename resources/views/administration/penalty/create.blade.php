@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create Penalty'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
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
                <form action="{{ route('administration.penalty.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
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
                        <div class="col-md-5 mb-3">
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
                        <div class="mb-3 col-md-3">
                            <label for="total_time" class="form-label required">{{ __('Penalty Time (Minutes)') }}</label>
                            <input type="number" class="form-control" id="total_time" name="total_time" value="{{ old('total_time') }}" min="1" max="480" step="1" required>
                            <div class="form-text">{{ __('Enter penalty time in minutes (1-480)') }}</div>
                            @error('total_time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4" id="fileInputContainer">
                            <label for="files[]" class="form-label">{{ __('Prescription/Proof Files') }}</label>
                            <input type="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Prescription/Proof Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ __('Penalty Reason') }} <strong class="text-danger">*</strong></label>
                        <div name="reason" id="full-editor">{!! old('reason') !!}</div>
                        <textarea class="d-none" name="reason" id="reason-input" placeholder="{{ __('Provide detailed reason for the penalty...') }}">{{ old('reason') }}</textarea>
                        @error('reason')
                            <b class="text-danger">{{ $message }}</b>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-device-floppy me-1"></i>{{ __('Submit Penalty') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Handle employee selection change
            $('#user_id').on('change', function() {
                const userId = $(this).val();
                const attendanceSelect = $('#attendance_id');

                if (userId) {
                    // Enable attendance dropdown and show loading
                    attendanceSelect.prop('disabled', false);
                    attendanceSelect.html('<option value="">Loading...</option>');

                    // Fetch attendances for selected user
                    $.get('{{ route('administration.penalty.attendances.get') }}', { user_id: userId })
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
        });
    </script>



    <script>
        $(document).ready(function () {
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                ["link"],
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ];

            var fullEditor = new Quill("#full-editor", {
                bounds: "#full-editor",
                placeholder: "Ex: The employee did not follow the dress code.",
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

            $('#postForm').on('submit', function() {
                $('#reason-input').val(fullEditor.root.innerHTML);
            });
        });
    </script>
@endsection
