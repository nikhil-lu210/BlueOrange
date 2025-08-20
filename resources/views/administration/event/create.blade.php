@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create Event'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />


@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .color-picker-container { display: flex; align-items: center; gap: 10px; }
        .color-preview { width: 36px; height: 36px; border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 1px 2px rgba(0,0,0,.05); }

        /* Subtle card polish */
        .card { border-radius: 12px; }
        .card-header { background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%); border-bottom: 1px solid #eef0f2; }

        /* Better spacing on form groups */
        .form-label { font-weight: 600; }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create New Event') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Event Management') }}</li>
    <li class="breadcrumb-item"><a href="{{ route('administration.event.index') }}">{{ __('All Events') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create Event') }}</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-plus me-2"></i>
                    Event Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.event.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Event Title -->
                        <div class="mb-3 col-md-12">
                            <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   placeholder="Enter event title" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Event Description -->
                        <div class="mb-3 col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Enter event description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Event Type and Status -->
                        <div class="mb-3 col-md-6">
                            <label for="event_type" class="form-label">Event Type <span class="text-danger">*</span></label>
                            <select name="event_type" id="event_type" class="select2 form-select @error('event_type') is-invalid @enderror" data-allow-clear="true" data-placeholder="Select Event Type" required>
                                <option value="">Select Event Type</option>
                                <option value="meeting" {{ old('event_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                <option value="training" {{ old('event_type') == 'training' ? 'selected' : '' }}>Training</option>
                                <option value="celebration" {{ old('event_type') == 'celebration' ? 'selected' : '' }}>Celebration</option>
                                <option value="conference" {{ old('event_type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                <option value="workshop" {{ old('event_type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('event_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="select2 form-select @error('status') is-invalid @enderror" data-allow-clear="true" data-placeholder="Select Status" required>
                                <option value="">Select Status</option>
                                <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Published" {{ old('status') == 'Published' ? 'selected' : '' }}>Published</option>
                                <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start and End Dates -->
                        <div class="mb-3 col-md-6">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" 
                                   class="form-control @error('start_date') is-invalid @enderror" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" 
                                   class="form-control @error('end_date') is-invalid @enderror" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start and End Times -->
                        <div class="mb-3 col-md-6">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" 
                                   class="form-control @error('start_time') is-invalid @enderror">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" 
                                   class="form-control @error('end_time') is-invalid @enderror">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location and Color -->
                        <div class="mb-3 col-md-6">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   placeholder="Enter event location">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="color" class="form-label">Event Color</label>
                            <div class="color-picker-container">
                                <input type="color" name="color" id="color" value="{{ old('color', '#3788d8') }}"
                                       class="form-control @error('color') is-invalid @enderror">
                                <div class="color-preview" id="color-preview" style="background-color: {{ old('color', '#3788d8') }}"></div>
                            </div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- All Day and Public -->
                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="is_all_day" id="is_all_day" value="1" 
                                       class="form-check-input" {{ old('is_all_day') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_all_day">
                                    All Day Event
                                </label>
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="is_public" id="is_public" value="1" 
                                       class="form-check-input" {{ old('is_public', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    Public Event
                                </label>
                            </div>
                        </div>

                        <!-- Max Participants and Reminder -->
                        <div class="mb-3 col-md-6">
                            <label for="max_participants" class="form-label">Maximum Participants</label>
                            <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants') }}" 
                                   class="form-control @error('max_participants') is-invalid @enderror" 
                                   placeholder="Enter max participants" min="1">
                            @error('max_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="reminder_before" class="form-label">Reminder</label>
                            <div class="row">
                                <div class="col-8">
                                    <input type="number" name="reminder_before" id="reminder_before" value="{{ old('reminder_before') }}" 
                                           class="form-control @error('reminder_before') is-invalid @enderror" 
                                           placeholder="Enter reminder time" min="1">
                                </div>
                                <div class="col-4">
                                    <select name="reminder_unit" id="reminder_unit" class="form-select">
                                        <option value="minutes" {{ old('reminder_unit') == 'minutes' ? 'selected' : '' }}>Minutes</option>
                                        <option value="hours" {{ old('reminder_unit') == 'hours' ? 'selected' : '' }}>Hours</option>
                                        <option value="days" {{ old('reminder_unit') == 'days' ? 'selected' : '' }}>Days</option>
                                    </select>
                                </div>
                            </div>
                            @error('reminder_before')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Participants -->
                        <div class="mb-3 col-md-12">
                            <label for="participants" class="form-label">Select Participants</label>
                            <select name="participants[]" id="participants" class="select2 form-select" multiple data-allow-clear="true" data-placeholder="Select participants...">
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('participants', [])) ? 'selected' : '' }}>
                                                {{ get_employee_name($user) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Leave empty if no additional participants needed</small>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('administration.event.index') }}" class="btn btn-secondary me-2">
                            <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-device-floppy ti-xs me-1"></span>
                            Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External JS  --}}
    {{-- Select 2 --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>


@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            // Initialize Select2 (robust)
            $('.select2').each(function () {
                var $this = $(this);
                if ($this.hasClass('select2-hidden-accessible')) {
                    $this.select2('destroy');
                }
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: $this.data('placeholder') || 'Select value',
                    allowClear: !!$this.data('allow-clear'),
                    dropdownParent: $this.parent(),
                    width: '100%',
                    closeOnSelect: !$this.prop('multiple')
                });
            });

            // For participants, keep dropdown open for multi-select
            var $participants = $('#participants');
            if ($participants.length) {
                $participants.select2('destroy');
                $participants.wrap('<div class="position-relative"></div>').select2({
                    placeholder: $participants.data('placeholder') || 'Select participants...',
                    allowClear: true,
                    dropdownParent: $participants.parent(),
                    width: '100%',
                    closeOnSelect: false
                });
            }

            // Handle all day event checkbox
            $('#is_all_day').change(function() {
                if ($(this).is(':checked')) {
                    $('#start_time, #end_time').prop('disabled', true).val('');
                } else {
                    $('#start_time, #end_time').prop('disabled', false);
                }
            });

            // Handle color picker
            $('#color').on('input', function() {
                var color = $(this).val();
                $('#color-preview').css('background-color', color);
            });

            // Set minimum end date based on start date
            $('#start_date').change(function() {
                var startDate = $(this).val();
                if (startDate) {
                    $('#end_date').attr('min', startDate);
                    if ($('#end_date').val() && $('#end_date').val() < startDate) {
                        $('#end_date').val(startDate);
                    }
                }
            });

            // Set minimum end time based on start time
            $('#start_time').change(function() {
                var startTime = $(this).val();
                if (startTime) {
                    $('#end_time').attr('min', startTime);
                }
            });

            // Trigger change event on page load
            $('#is_all_day').trigger('change');
        });
    </script>
@endsection
