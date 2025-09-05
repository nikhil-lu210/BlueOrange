@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Edit Work Schedule'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .work-item-row {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
    }
    .work-type-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .work-type-client { background-color: #28a745; color: white; }
    .work-type-internal { background-color: #007bff; color: white; }
    .work-type-bench { background-color: #ffc107; color: #212529; }
    .shift-info {
        background-color: #e3f2fd;
        border: 1px solid #2196f3;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Edit Work Schedule') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Work Schedule') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.work_schedule.index') }}">{{ __('All Work Schedules') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Schedule Details') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.work_schedule.show', $workSchedule) }}" class="text-bold">{{ get_employee_name($workSchedule->user) }} - {{ $workSchedule->weekday }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Edit Work Schedule') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Edit Work Schedule</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.work_schedule.show', $workSchedule) }}" class="btn btn-sm btn-dark">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back To Details
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="workScheduleForm" action="{{ route('administration.work_schedule.update', $workSchedule) }}" method="post" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <!-- Employee and Schedule Info (Read-only) -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Employee</label>
                            <input type="text" class="form-control" value="{{ get_employee_name($workSchedule->user) }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Weekday</label>
                            <input type="text" class="form-control" value="{{ $workSchedule->weekday }}" readonly>
                        </div>
                    </div>

                    <!-- Shift Information -->
                    <div class="shift-info">
                        <h6 class="mb-2"><i class="ti ti-clock me-2"></i>Employee Shift Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Start Time:</strong> {{ $workSchedule->employeeShift->start_time }}
                            </div>
                            <div class="col-md-4">
                                <strong>End Time:</strong> {{ $workSchedule->employeeShift->end_time }}
                            </div>
                            <div class="col-md-4">
                                <strong>Total Time:</strong> {{ $workSchedule->employeeShift->total_time }}
                            </div>
                        </div>
                    </div>

                    <!-- Work Items Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-3">Work Items <strong class="text-danger">*</strong></h6>
                            <div id="work-items-container">
                                @foreach ($workSchedule->workScheduleItems as $index => $item)
                                    <div class="work-item-row" data-index="{{ $index }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Start Time</label>
                                                <input type="time" name="work_items[{{ $index }}][start_time]" class="form-control work-start-time" value="{{ $item->start_time }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">End Time</label>
                                                <input type="time" name="work_items[{{ $index }}][end_time]" class="form-control work-end-time" value="{{ $item->end_time }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Work Type</label>
                                                <select name="work_items[{{ $index }}][work_type]" class="form-select work-type" required>
                                                    <option value="">Select Type</option>
                                                    @foreach ($workTypes as $type)
                                                        <option value="{{ $type }}" {{ $item->work_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Work Title</label>
                                                <input type="text" name="work_items[{{ $index }}][work_title]" class="form-control" value="{{ $item->work_title }}" placeholder="Enter work title" required>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12 text-end">
                                                <button type="button" class="btn btn-sm btn-danger remove-work-item" {{ $workSchedule->workScheduleItems->count() <= 1 ? 'style=display:none' : '' }}>
                                                    <i class="ti ti-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-outline-primary" id="add-work-item">
                                    <i class="ti ti-plus"></i> Add Work Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Validation Summary -->
                    <div id="validation-summary" class="alert alert-warning" style="display: none;">
                        <h6><i class="ti ti-alert-triangle me-2"></i>Validation Summary</h6>
                        <ul id="validation-errors" class="mb-0"></ul>
                    </div>

                    <div class="mt-4 float-end">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Update Schedule
                        </button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
</div>
<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            let workItemIndex = {{ $workSchedule->workScheduleItems->count() - 1 }};
            const shiftStartTime = '{{ $workSchedule->employeeShift->start_time }}';
            const shiftEndTime = '{{ $workSchedule->employeeShift->end_time }}';

            // Set time constraints
            $('.work-start-time').attr('min', shiftStartTime);
            $('.work-end-time').attr('max', shiftEndTime);

            // Add work item
            $('#add-work-item').on('click', function() {
                workItemIndex++;
                const workItemHtml = `
                    <div class="work-item-row" data-index="${workItemIndex}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="work_items[${workItemIndex}][start_time]" class="form-control work-start-time" min="${shiftStartTime}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="work_items[${workItemIndex}][end_time]" class="form-control work-end-time" max="${shiftEndTime}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Work Type</label>
                                <select name="work_items[${workItemIndex}][work_type]" class="form-select work-type" required>
                                    <option value="">Select Type</option>
                                    @foreach ($workTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Work Title</label>
                                <input type="text" name="work_items[${workItemIndex}][work_title]" class="form-control" placeholder="Enter work title" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-sm btn-danger remove-work-item">
                                    <i class="ti ti-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#work-items-container').append(workItemHtml);
                updateRemoveButtons();
            });

            // Remove work item
            $(document).on('click', '.remove-work-item', function() {
                $(this).closest('.work-item-row').remove();
                updateRemoveButtons();
            });

            // Update remove buttons visibility
            function updateRemoveButtons() {
                const workItems = $('.work-item-row');
                if (workItems.length > 1) {
                    $('.remove-work-item').show();
                } else {
                    $('.remove-work-item').hide();
                }
            }

            // Form validation
            $('#workScheduleForm').on('submit', function(e) {
                const errors = [];

                // Check work items
                const workItems = $('.work-item-row');
                if (workItems.length === 0) {
                    errors.push('Please add at least one work item');
                }

                // Validate each work item
                workItems.each(function(index) {
                    const startTime = $(this).find('.work-start-time').val();
                    const endTime = $(this).find('.work-end-time').val();
                    const workType = $(this).find('.work-type').val();
                    const workTitle = $(this).find('input[name*="work_title"]').val();

                    if (!startTime || !endTime || !workType || !workTitle) {
                        errors.push(`Work item ${index + 1} is incomplete`);
                    }

                    if (startTime && endTime && startTime >= endTime) {
                        errors.push(`Work item ${index + 1}: End time must be after start time`);
                    }
                });

                if (errors.length > 0) {
                    e.preventDefault();
                    $('#validation-errors').empty();
                    errors.forEach(function(error) {
                        $('#validation-errors').append('<li>' + error + '</li>');
                    });
                    $('#validation-summary').show();
                } else {
                    $('#validation-summary').hide();
                }
            });

            // Initialize
            updateRemoveButtons();
        });
    </script>
@endsection
