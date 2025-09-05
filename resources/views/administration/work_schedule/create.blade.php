@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Assign Work Schedule'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
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
    <b class="text-uppercase">{{ __('Assign Work Schedule') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Work Schedule') }}</li>
    <li class="breadcrumb-item active">{{ __('Assign Work Schedule') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Assign Work Schedule</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.work_schedule.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Schedules
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="workScheduleForm" action="{{ route('administration.work_schedule.store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="user_id" class="form-label">{{ __('Select Employee') }} <strong class="text-danger">*</strong></label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Select Employee</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ get_employee_name($user) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-8">
                            <label class="form-label">{{ __('Select Weekdays') }} <strong class="text-danger">*</strong></label>
                            <div class="row">
                                @foreach ($availableWeekdays as $weekday)
                                    <div class="col-md-2 mt-2 mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input weekday-checkbox" type="checkbox" name="weekdays[]" value="{{ $weekday }}" id="weekday_{{ $loop->index }}" {{ in_array($weekday, old('weekdays', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="weekday_{{ $loop->index }}">
                                                {{ $weekday }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('weekdays')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Shift Information -->
                    <div id="shift-info" class="shift-info" style="display: none;">
                        <h6 class="mb-2"><i class="ti ti-clock me-2"></i>Employee Shift Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Start Time:</strong> <span id="shift-start-time">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>End Time:</strong> <span id="shift-end-time">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Time:</strong> <span id="shift-total-time">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="same_schedule_for_all" id="same_schedule_for_all" value="1" {{ (old('same_schedule_for_all') === '1' || (old('same_schedule_for_all') === null && !session()->hasOldInput())) ? 'checked' : '' }}>
                                <label class="form-check-label" for="same_schedule_for_all">
                                    <strong>Same Schedule for All Selected Weekdays?</strong>
                                </label>
                            </div>
                            <small class="text-muted">If checked, the same work schedule will be applied to all selected weekdays.</small>
                        </div>
                    </div>

                    <!-- Work Items Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-3">Work Items <strong class="text-danger">*</strong></h6>

                            <!-- Common Work Items (when same schedule for all is checked) -->
                            <div id="common-work-items-container">
                                @if(old('work_items'))
                                    @foreach(old('work_items') as $index => $item)
                                        <div class="work-item-row" data-index="{{ $index }}">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Start Time</label>
                                                    <input type="time" name="work_items[{{ $index }}][start_time]" class="form-control work-start-time" value="{{ old("work_items.{$index}.start_time") }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">End Time</label>
                                                    <input type="time" name="work_items[{{ $index }}][end_time]" class="form-control work-end-time" value="{{ old("work_items.{$index}.end_time") }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Work Type</label>
                                                    <select name="work_items[{{ $index }}][work_type]" class="form-select work-type" required>
                                                        <option value="">Select Type</option>
                                                        @foreach ($workTypes as $type)
                                                            <option value="{{ $type }}" {{ old("work_items.{$index}.work_type") == $type ? 'selected' : '' }}>{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Work Title</label>
                                                    <input type="text" name="work_items[{{ $index }}][work_title]" class="form-control" placeholder="Enter work title" value="{{ old("work_items.{$index}.work_title") }}" required>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-12 text-end">
                                                    <button type="button" class="btn btn-sm btn-danger remove-work-item" {{ $index == 0 ? 'style=display:none;' : '' }}>
                                                        <i class="ti ti-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="work-item-row" data-index="0">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Start Time</label>
                                                <input type="time" name="work_items[0][start_time]" class="form-control work-start-time" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">End Time</label>
                                                <input type="time" name="work_items[0][end_time]" class="form-control work-end-time" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Work Type</label>
                                                <select name="work_items[0][work_type]" class="form-select work-type" required>
                                                    <option value="">Select Type</option>
                                                    @foreach ($workTypes as $type)
                                                        <option value="{{ $type }}">{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Work Title</label>
                                                <input type="text" name="work_items[0][work_title]" class="form-control" placeholder="Enter work title" required>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12 text-end">
                                                <button type="button" class="btn btn-sm btn-danger remove-work-item" style="display: none;">
                                                    <i class="ti ti-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Individual Work Items for each weekday (when same schedule for all is unchecked) -->
                            <div id="individual-work-items-container" style="display: none;">
                                @foreach ($availableWeekdays as $weekday)
                                    <div class="weekday-work-items" data-weekday="{{ $weekday }}" style="display: none;">
                                        <h6 class="mb-3 text-primary">
                                            <i class="ti ti-calendar me-2"></i>Work Items for {{ $weekday }}
                                        </h6>
                                        <div class="work-items-for-weekday" data-weekday="{{ $weekday }}">
                                            @if(old("weekday_work_items.{$weekday}"))
                                                @foreach(old("weekday_work_items.{$weekday}") as $index => $item)
                                                    <div class="work-item-row" data-index="{{ $index }}">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="time" name="weekday_work_items[{{ $weekday }}][{{ $index }}][start_time]" class="form-control work-start-time" value="{{ old("weekday_work_items.{$weekday}.{$index}.start_time") }}" required>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">End Time</label>
                                                                <input type="time" name="weekday_work_items[{{ $weekday }}][{{ $index }}][end_time]" class="form-control work-end-time" value="{{ old("weekday_work_items.{$weekday}.{$index}.end_time") }}" required>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Work Type</label>
                                                                <select name="weekday_work_items[{{ $weekday }}][{{ $index }}][work_type]" class="form-select work-type" required>
                                                                    <option value="">Select Type</option>
                                                                    @foreach ($workTypes as $type)
                                                                        <option value="{{ $type }}" {{ old("weekday_work_items.{$weekday}.{$index}.work_type") == $type ? 'selected' : '' }}>{{ $type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Work Title</label>
                                                                <input type="text" name="weekday_work_items[{{ $weekday }}][{{ $index }}][work_title]" class="form-control" placeholder="Enter work title" value="{{ old("weekday_work_items.{$weekday}.{$index}.work_title") }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-12 text-end">
                                                                <button type="button" class="btn btn-sm btn-danger remove-work-item" {{ $index == 0 ? 'style=display:none;' : '' }}>
                                                                    <i class="ti ti-trash"></i> Remove
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="work-item-row" data-index="0">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Start Time</label>
                                                            <input type="time" name="weekday_work_items[{{ $weekday }}][0][start_time]" class="form-control work-start-time" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">End Time</label>
                                                            <input type="time" name="weekday_work_items[{{ $weekday }}][0][end_time]" class="form-control work-end-time" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Work Type</label>
                                                            <select name="weekday_work_items[{{ $weekday }}][0][work_type]" class="form-select work-type" required>
                                                                <option value="">Select Type</option>
                                                                @foreach ($workTypes as $type)
                                                                    <option value="{{ $type }}">{{ $type }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Work Title</label>
                                                            <input type="text" name="weekday_work_items[{{ $weekday }}][0][work_title]" class="form-control" placeholder="Enter work title" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-12 text-end">
                                                            <button type="button" class="btn btn-sm btn-danger remove-work-item" style="display: none;">
                                                                <i class="ti ti-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="button" class="btn btn-outline-primary add-work-item-for-weekday" data-weekday="{{ $weekday }}">
                                                <i class="ti ti-plus"></i> Add Work Item for {{ $weekday }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-3" id="add-common-work-item">
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
                        <a href="{{ route('administration.work_schedule.create') }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">Assign Schedule</button>
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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            let workItemIndex = 0;
            let weekdayWorkItemIndexes = {};
            let currentShiftInfo = null;

            // Initialize weekday work item indexes
            @foreach ($availableWeekdays as $weekday)
                weekdayWorkItemIndexes['{{ $weekday }}'] = 0;
            @endforeach

            // Load shift information when user is selected
            $('#user_id').on('change', function() {
                const userId = $(this).val();
                if (userId) {
                    loadUserShift(userId);
                } else {
                    $('#shift-info').hide();
                    currentShiftInfo = null;
                }
            });

            // Load user shift information
            function loadUserShift(userId) {
                $.ajax({
                    url: '{{ route("administration.work_schedule.get-user-shift") }}',
                    method: 'GET',
                    data: { user_id: userId },
                    success: function(response) {
                        if (response.success) {
                            currentShiftInfo = response.shift;
                            $('#shift-start-time').text(response.shift.start_time);
                            $('#shift-end-time').text(response.shift.end_time);
                            $('#shift-total-time').text(response.shift.total_time);
                            $('#shift-info').show();

                            // Set time constraints
                            $('.work-start-time').attr('min', response.shift.start_time);
                            $('.work-end-time').attr('max', response.shift.end_time);
                        } else {
                            alert(response.message);
                            $('#shift-info').hide();
                            currentShiftInfo = null;
                        }
                    },
                    error: function() {
                        alert('Error loading shift information');
                        $('#shift-info').hide();
                        currentShiftInfo = null;
                    }
                });
            }

            // Handle "Same Schedule for All Selected Weekdays?" checkbox
            $('#same_schedule_for_all').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#common-work-items-container').show();
                    $('#individual-work-items-container').hide();
                    $('#add-common-work-item').show();

                    // Add required to common work items and remove from individual
                    $('#common-work-items-container input, #common-work-items-container select').attr('required', 'required');
                    $('#individual-work-items-container input, #individual-work-items-container select').removeAttr('required');
                } else {
                    $('#common-work-items-container').hide();
                    $('#individual-work-items-container').show();
                    $('#add-common-work-item').hide();

                    // Remove required from common work items and add to individual
                    $('#common-work-items-container input, #common-work-items-container select').removeAttr('required');
                    $('#individual-work-items-container input, #individual-work-items-container select').attr('required', 'required');

                    updateWeekdayWorkItemsVisibility();
                }
            });

            // Handle weekday checkbox changes
            $('.weekday-checkbox').on('change', function() {
                if (!$('#same_schedule_for_all').is(':checked')) {
                    updateWeekdayWorkItemsVisibility();
                }
            });

            // Update visibility of weekday work items based on selected weekdays
            function updateWeekdayWorkItemsVisibility() {
                $('.weekday-work-items').each(function() {
                    const weekday = $(this).data('weekday');
                    const isChecked = $(`.weekday-checkbox[value="${weekday}"]`).is(':checked');

                    if (isChecked) {
                        $(this).show();
                        // Add required to visible weekday work items
                        $(this).find('input, select').attr('required', 'required');
                    } else {
                        $(this).hide();
                        // Remove required from hidden weekday work items
                        $(this).find('input, select').removeAttr('required');
                    }
                });
            }

            // Add common work item
            $('#add-work-item').on('click', function() {
                workItemIndex++;
                const workItemHtml = `
                    <div class="work-item-row" data-index="${workItemIndex}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="work_items[${workItemIndex}][start_time]" class="form-control work-start-time" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="work_items[${workItemIndex}][end_time]" class="form-control work-end-time" required>
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
                $('#common-work-items-container').append(workItemHtml);

                // Set time constraints for new item
                if (currentShiftInfo) {
                    $(`input[name="work_items[${workItemIndex}][start_time]"]`).attr('min', currentShiftInfo.start_time);
                    $(`input[name="work_items[${workItemIndex}][end_time]"]`).attr('max', currentShiftInfo.end_time);
                }

                // Set required attribute for new item
                $(`input[name="work_items[${workItemIndex}][start_time]"], input[name="work_items[${workItemIndex}][end_time]"], select[name="work_items[${workItemIndex}][work_type]"], input[name="work_items[${workItemIndex}][work_title]"]`).attr('required', 'required');

                updateRemoveButtons('#common-work-items-container');
            });

            // Add work item for specific weekday
            $(document).on('click', '.add-work-item-for-weekday', function() {
                const weekday = $(this).data('weekday');
                weekdayWorkItemIndexes[weekday]++;
                const index = weekdayWorkItemIndexes[weekday];

                const workItemHtml = `
                    <div class="work-item-row" data-index="${index}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="weekday_work_items[${weekday}][${index}][start_time]" class="form-control work-start-time" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="weekday_work_items[${weekday}][${index}][end_time]" class="form-control work-end-time" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Work Type</label>
                                <select name="weekday_work_items[${weekday}][${index}][work_type]" class="form-select work-type" required>
                                    <option value="">Select Type</option>
                                    @foreach ($workTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Work Title</label>
                                <input type="text" name="weekday_work_items[${weekday}][${index}][work_title]" class="form-control" placeholder="Enter work title" required>
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
                $(`.work-items-for-weekday[data-weekday="${weekday}"]`).append(workItemHtml);

                // Set time constraints for new item
                if (currentShiftInfo) {
                    $(`input[name="weekday_work_items[${weekday}][${index}][start_time]"]`).attr('min', currentShiftInfo.start_time);
                    $(`input[name="weekday_work_items[${weekday}][${index}][end_time]"]`).attr('max', currentShiftInfo.end_time);
                }

                // Set required attribute for new item
                $(`input[name="weekday_work_items[${weekday}][${index}][start_time]"], input[name="weekday_work_items[${weekday}][${index}][end_time]"], select[name="weekday_work_items[${weekday}][${index}][work_type]"], input[name="weekday_work_items[${weekday}][${index}][work_title]"]`).attr('required', 'required');

                updateRemoveButtons(`.work-items-for-weekday[data-weekday="${weekday}"]`);
            });

            // Remove work item
            $(document).on('click', '.remove-work-item', function() {
                $(this).closest('.work-item-row').remove();
                updateRemoveButtons($(this).closest('.work-items-for-weekday, #common-work-items-container'));
            });

            // Update remove buttons visibility
            function updateRemoveButtons(container) {
                const workItems = $(container).find('.work-item-row');
                if (workItems.length > 1) {
                    $(container).find('.remove-work-item').show();
                } else {
                    $(container).find('.remove-work-item').hide();
                }
            }

            // Form validation
            $('#workScheduleForm').on('submit', function(e) {
                const errors = [];

                // Check if user is selected
                if (!$('#user_id').val()) {
                    errors.push('Please select an employee');
                }

                // Check if weekdays are selected
                const selectedWeekdays = $('.weekday-checkbox:checked');
                if (selectedWeekdays.length === 0) {
                    errors.push('Please select at least one weekday');
                }

                // Check work items based on mode
                if ($('#same_schedule_for_all').is(':checked')) {
                    // Common work items mode
                    const workItems = $('#common-work-items-container .work-item-row');
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
                } else {
                    // Individual weekday work items mode
                    selectedWeekdays.each(function() {
                        const weekday = $(this).val();
                        const workItems = $(`.work-items-for-weekday[data-weekday="${weekday}"] .work-item-row`);

                        if (workItems.length === 0) {
                            errors.push(`Please add at least one work item for ${weekday}`);
                        }

                        // Validate each work item for this weekday
                        workItems.each(function(index) {
                            const startTime = $(this).find('.work-start-time').val();
                            const endTime = $(this).find('.work-end-time').val();
                            const workType = $(this).find('.work-type').val();
                            const workTitle = $(this).find('input[name*="work_title"]').val();

                            if (!startTime || !endTime || !workType || !workTitle) {
                                errors.push(`${weekday} - Work item ${index + 1} is incomplete`);
                            }

                            if (startTime && endTime && startTime >= endTime) {
                                errors.push(`${weekday} - Work item ${index + 1}: End time must be after start time`);
                            }
                        });
                    });
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    $('#validation-errors').empty();
                    errors.forEach(function(error) {
                        $('#validation-errors').append('<li>' + error + '</li>');
                    });
                    $('#validation-summary').show();
                    return false;
                } else {
                    $('#validation-summary').hide();

                    // Remove required attribute from hidden fields to prevent browser validation errors
                    if ($('#same_schedule_for_all').is(':checked')) {
                        // Remove required from individual weekday fields
                        $('#individual-work-items-container input, #individual-work-items-container select').removeAttr('required');
                    } else {
                        // Remove required from common work items fields
                        $('#common-work-items-container input, #common-work-items-container select').removeAttr('required');
                    }
                }
            });

            // Initialize
            updateRemoveButtons('#common-work-items-container');
            @foreach ($availableWeekdays as $weekday)
                updateRemoveButtons(`.work-items-for-weekday[data-weekday="{{ $weekday }}"]`);
            @endforeach

            // Initialize form state based on checkbox and old input values
            if ($('#same_schedule_for_all').is(':checked')) {
                $('#common-work-items-container').show();
                $('#individual-work-items-container').hide();
                $('#add-common-work-item').show();
                $('#common-work-items-container input, #common-work-items-container select').attr('required', 'required');
                $('#individual-work-items-container input, #individual-work-items-container select').removeAttr('required');
            } else {
                $('#common-work-items-container').hide();
                $('#individual-work-items-container').show();
                $('#add-common-work-item').hide();
                $('#common-work-items-container input, #common-work-items-container select').removeAttr('required');
                $('#individual-work-items-container input, #individual-work-items-container select').attr('required', 'required');
                updateWeekdayWorkItemsVisibility();
            }

            // Initialize work item indexes based on existing items
            workItemIndex = $('#common-work-items-container .work-item-row').length - 1;
            @foreach ($availableWeekdays as $weekday)
                weekdayWorkItemIndexes['{{ $weekday }}'] = $(`.work-items-for-weekday[data-weekday="{{ $weekday }}"] .work-item-row`).length - 1;
            @endforeach
        });
    </script>
@endsection
