@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Apply For Leave'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Apply For Leave') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Leave') }}</li>
    <li class="breadcrumb-item active">{{ __('Apply For Leave') }}</li>
@endsection

@section('content')
<!-- Start row -->
<div class="row justify-content-center">
    @if (auth()->user()->allowed_leave)
        <div class="col-md-12">
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-success p-2 rounded">
                                <i class="ti ti-calendar-pause ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-success mb-0">{{ show_leave(auth()->user()->allowed_leave->earned_leave) }}</h5>
                                <small class="mb-0 text-muted">Allowed <b class="text-dark">Earned Leave ({{ date('Y') }})</b></small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                                <i class="ti ti-calendar-pause ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-warning mb-0">{{ show_leave(auth()->user()->allowed_leave->sick_leave) }}</h5>
                                <small class="mb-0 text-muted">Allowed <b class="text-dark">Sick Leave ({{ date('Y') }})</b></small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-primary p-2 rounded">
                                <i class="ti ti-calendar-pause ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-primary mb-0">{{ show_leave(auth()->user()->allowed_leave->casual_leave) }}</h5>
                                <small class="mb-0 text-muted">Allowed <b class="text-dark">Casual Leave ({{ date('Y') }})</b></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (auth()->user()->available_leaves())
        <div class="col-md-12">
            <div class="card mb-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-success p-2 rounded">
                                <i class="ti ti-calendar-pause ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-success mb-0">{{ show_leave(auth()->user()->available_leaves()->earned_leave) }}</h5>
                                <small class="mb-0 text-muted">Available <b class="text-dark">Earned Leave ({{ date('Y') }})</b></small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                                <i class="ti ti-calendar-pause ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-warning mb-0">{{ show_leave(auth()->user()->available_leaves()->sick_leave) }}</h5>
                                <small class="mb-0 text-muted">Available <b class="text-dark">Sick Leave ({{ date('Y') }})</b></small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-primary p-2 rounded">
                                <i class="ti ti-calendar-pause ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-primary mb-0">{{ show_leave(auth()->user()->available_leaves()->casual_leave) }}</h5>
                                <small class="mb-0 text-muted">Available <b class="text-dark">Casual Leave ({{ date('Y') }})</b></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Apply For Leave</h5>

                <div class="card-header-elements ms-auto">
                    @can ('Leave History Update')
                        <a href="{{ route('administration.leave.history.index') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                            All Leaves
                        </a>
                    @else
                        <a href="{{ route('administration.leave.history.my') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                            All Leaves
                        </a>
                    @endcan
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="postForm" action="{{ route('administration.leave.history.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="type" class="form-label">{{ __('Expected Leave Type') }} <strong class="text-danger">*</strong></label>
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="typeEarned">
                                            <input name="type" class="form-check-input" type="radio" value="Earned" id="typeEarned" required
                                                {{ old('type') === 'Earned' ? 'checked' : '' }} />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Earned</span>
                                            </span>
                                        </label>
                                        @error('type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="typeSick">
                                            <input name="type" class="form-check-input" type="radio" value="Sick" id="typeSick" required
                                                {{ old('type') === 'Sick' ? 'checked' : '' }} />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Sick</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="typeCasual">
                                            <input name="type" class="form-check-input" type="radio" value="Casual" id="typeCasual" required
                                                {{ old('type') === 'Casual' ? 'checked' : '' }} />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Casual</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12" id="fileInputContainer">
                            <label for="files[]" class="form-label">{{ __('Prescription/Proof Files') }}</label>
                            <input type="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Prescription/Proof Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Leave Reason <strong class="text-danger">*</strong></label>
                            <div name="reason" id="full-editor">{!! old('reason') !!}</div>
                            <textarea class="d-none" name="reason" id="reason-input">{{ old('reason') }}</textarea>
                            @error('reason')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            @include('administration.leave.includes.leave_days_hours')
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.leave.history.create') }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-2"></i>
                            Apply Now
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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
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
                placeholder: "Ex: I Have My Wedding Anniversary. So I Need A Day Of Leave.",
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

            // Store editor reference globally for form validation
            window.leaveReasonEditor = fullEditor;

            $('#postForm').on('submit', function() {
                $('#reason-input').val(fullEditor.root.innerHTML);
            });
        });
    </script>

    <script>
    $(document).ready(function () {
        // Initially hide the file input
        $('#fileInputContainer').hide();

        // Listen for changes on the radio buttons
        $('input[name="type"]').change(function () {
            if ($(this).val() === 'Sick') {
                // Show and enable the file input
                $('#fileInputContainer').show();
                $('#files').prop('disabled', false);
            } else {
                // Hide and disable the file input
                $('#fileInputContainer').hide();
                $('#files').prop('disabled', true);
            }
        });
    });
    </script>

    <script>
        $(document).ready(function () {
            // Available leave balances in seconds
            let availableLeaves = {
                'Earned': {{ auth()->user()->available_leaves() ? auth()->user()->available_leaves()->earned_leave->total('seconds') : 0 }},
                'Casual': {{ auth()->user()->available_leaves() ? auth()->user()->available_leaves()->casual_leave->total('seconds') : 0 }},
                'Sick': {{ auth()->user()->available_leaves() ? auth()->user()->available_leaves()->sick_leave->total('seconds') : 0 }}
            };

            // Function to format seconds to readable time
            function formatTime(totalSeconds) {
                if (totalSeconds <= 0) return '0h 0m';
                let hours = Math.floor(totalSeconds / 3600);
                let minutes = Math.floor((totalSeconds % 3600) / 60);
                return hours + 'h ' + minutes + 'm';
            }

            // Function to validate leave balance
            function validateLeaveBalance() {
                let selectedType = $('input[name="type"]:checked').val();
                if (!selectedType) {
                    return { valid: true, message: '' };
                }

                let totalRequestedSeconds = 0;
                $('.leave-day-row').each(function() {
                    let hours = parseInt($(this).find('input[name^="total_leave[hour]"]').val()) || 0;
                    let minutes = parseInt($(this).find('input[name^="total_leave[min]"]').val()) || 0;
                    let seconds = parseInt($(this).find('input[name^="total_leave[sec]"]').val()) || 0;
                    totalRequestedSeconds += (hours * 3600) + (minutes * 60) + seconds;
                });

                let availableSeconds = availableLeaves[selectedType];

                if (totalRequestedSeconds > availableSeconds) {
                    return {
                        valid: false,
                        message: `Insufficient ${selectedType} leave balance!\nAvailable: ${formatTime(availableSeconds)}\nRequested: ${formatTime(totalRequestedSeconds)}`
                    };
                }

                if (totalRequestedSeconds === 0) {
                    return {
                        valid: false,
                        message: 'Please specify leave duration for at least one day.'
                    };
                }

                return { valid: true, message: '' };
            }

            // Function to update balance display
            function updateBalanceDisplay() {
                let selectedType = $('input[name="type"]:checked').val();
                if (!selectedType) return;

                let totalRequestedSeconds = 0;
                $('.leave-day-row').each(function() {
                    let hours = parseInt($(this).find('input[name^="total_leave[hour]"]').val()) || 0;
                    let minutes = parseInt($(this).find('input[name^="total_leave[min]"]').val()) || 0;
                    let seconds = parseInt($(this).find('input[name^="total_leave[sec]"]').val()) || 0;
                    totalRequestedSeconds += (hours * 3600) + (minutes * 60) + seconds;
                });

                let availableSeconds = availableLeaves[selectedType];
                let remainingSeconds = availableSeconds - totalRequestedSeconds;

                // Update the balance display dynamically
                let balanceElement = $('.balance-display-' + selectedType.toLowerCase());
                if (balanceElement.length === 0) {
                    // Create balance display if it doesn't exist
                    let balanceHtml = `<div class="alert alert-primary balance-display-${selectedType.toLowerCase()} mt-2">
                        <strong>Balance Check:</strong><br>
                        Available: <span class="available-balance">${formatTime(availableSeconds)}</span><br>
                        Requested: <span class="requested-balance">${formatTime(totalRequestedSeconds)}</span><br>
                        Remaining: <span class="remaining-balance ${remainingSeconds < 0 ? 'text-danger' : 'text-success'}">${formatTime(Math.max(0, remainingSeconds))}</span>
                    </div>`;
                    $('.form-check:has(input[value="' + selectedType + '"])').closest('.col-md').append(balanceHtml);
                } else {
                    balanceElement.find('.available-balance').text(formatTime(availableSeconds));
                    balanceElement.find('.requested-balance').text(formatTime(totalRequestedSeconds));
                    balanceElement.find('.remaining-balance')
                        .text(formatTime(Math.max(0, remainingSeconds)))
                        .removeClass('text-danger text-success')
                        .addClass(remainingSeconds < 0 ? 'text-danger' : 'text-success');
                }
            }

                        // Function to validate dates
            function validateDates() {
                let dates = [];
                let duplicateFound = false;
                let hasValidationError = false;

                $('.leave-day-row input[name^="leave_days[date]"]').each(function() {
                    let date = $(this).val();
                    if (date) {
                        if (dates.includes(date)) {
                            duplicateFound = true;
                            return false;
                        }
                        dates.push(date);

                        // Check if date is in the past
                        if (new Date(date) < new Date().setHours(0,0,0,0)) {
                            $(this).addClass('is-invalid');
                            if (!$(this).next('.invalid-feedback').length) {
                                $(this).after('<div class="invalid-feedback">Leave date cannot be in the past.</div>');
                            }
                            hasValidationError = true;
                        } else {
                            $(this).removeClass('is-invalid');
                            $(this).next('.invalid-feedback').remove();
                        }
                    }
                });

                if (duplicateFound) {
                    Swal.fire({
                        title: 'Duplicate Dates!',
                        text: 'Duplicate dates are not allowed in the same leave request.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                if (hasValidationError) {
                    return false;
                }

                return true;
            }

            // Note: Date conflict validation is handled server-side in LeaveHistoryStoreRequest
            // The form request will validate and show appropriate error messages

            // Counter for dynamic rows
            let rowCount = $('.leave-day-row').length; // Start from the current number of rows

            // Add Day button click event
            $('#addLeaveDay').click(function () {
                // Clone the last leave day row
                var newRow = $('.leave-day-row:last').clone();

                // Clear the input values in the cloned row
                newRow.find('input[type="text"], input[type="number"]').val('');

                // Set Min and Sec values to 0
                newRow.find('input[name^="total_leave[min]"]').val('0');
                newRow.find('input[name^="total_leave[sec]"]').val('0');

                // Update names for dynamic indexing
                newRow.find('input[name^="leave_days[date]"]').attr('name', `leave_days[date][${rowCount}]`);
                newRow.find('input[name^="total_leave[hour]"]').attr('name', `total_leave[hour][${rowCount}]`);
                newRow.find('input[name^="total_leave[min]"]').attr('name', `total_leave[min][${rowCount}]`);
                newRow.find('input[name^="total_leave[sec]"]').attr('name', `total_leave[sec][${rowCount}]`);

                // Show the remove button for all rows
                newRow.find('.remove-leave-day').show();

                // Append the cloned row to the parent container
                $('.leave-day-row:last').after(newRow);

                // Reinitialize date-picker on cloned inputs
                newRow.find('.date-picker').removeClass('hasDatepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                    autoclose: true,
                    orientation: 'auto right',
                });

                // Increment row count
                rowCount++;
            });

            // Event delegation for remove button click
            $(document).on('click', '.remove-leave-day', function () {
                // Check if it's not the only remaining row
                if ($('.leave-day-row').length > 1) {
                    // Remove the parent row
                    $(this).closest('.leave-day-row').remove();

                    // Reindex the rows
                    $('.leave-day-row').each(function (index) {
                        $(this).find('input[name^="leave_days[date]"]').attr('name', `leave_days[date][${index}]`);
                        $(this).find('input[name^="total_leave[hour]"]').attr('name', `total_leave[hour][${index}]`);
                        $(this).find('input[name^="total_leave[min]"]').attr('name', `total_leave[min][${index}]`);
                        $(this).find('input[name^="total_leave[sec]"]').attr('name', `total_leave[sec][${index}]`);
                    });

                    // Update row count
                    rowCount = $('.leave-day-row').length;

                    // Update balance display after removing row
                    updateBalanceDisplay();
                }
            });

            // Event handlers for real-time validation
            $('input[name="type"]').change(function() {
                // Clear previous balance displays
                $('.balance-display-earned, .balance-display-casual, .balance-display-sick').remove();
                updateBalanceDisplay();
            });

            // Update balance on time input changes
            $(document).on('input', 'input[name^="total_leave"]', function() {
                updateBalanceDisplay();
            });

            // Validate dates on change
            $(document).on('change', 'input[name^="leave_days[date]"]', function() {
                validateDates();
            });

            // Form submission validation
            $('#postForm').on('submit', function(e) {
                // Validate dates first
                if (!validateDates()) {
                    e.preventDefault();
                    return false;
                }

                // Validate leave balance
                let balanceCheck = validateLeaveBalance();
                if (!balanceCheck.valid) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Insufficient Leave Balance!',
                        text: balanceCheck.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Validate sick leave files
                let selectedType = $('input[name="type"]:checked').val();
                if (selectedType === 'Sick') {
                    let hasFiles = $('#files\\[\\]')[0].files.length > 0;
                    if (!hasFiles) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Medical Certificate Required!',
                            text: 'Sick leave requires prescription or medical certificate.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }
                }

                // Validate reason length
                let reasonContent = '';
                if (window.leaveReasonEditor) {
                    reasonContent = window.leaveReasonEditor.getText().trim();
                }

                if (reasonContent.length < 10) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Reason Required!',
                        text: 'Please provide a detailed reason for your leave (minimum 10 characters).',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // All validations passed, submit the form
                if (window.leaveReasonEditor) {
                    $('#reason-input').val(window.leaveReasonEditor.root.innerHTML);
                }
                return true;
            });

            // Initial reindexing to ensure first row is included
            $('.leave-day-row').each(function (index) {
                $(this).find('input[name^="leave_days[date]"]').attr('name', `leave_days[date][${index}]`);
                $(this).find('input[name^="total_leave[hour]"]').attr('name', `total_leave[hour][${index}]`);
                $(this).find('input[name^="total_leave[min]"]').attr('name', `total_leave[min][${index}]`);
                $(this).find('input[name^="total_leave[sec]"]').attr('name', `total_leave[sec][${index}]`);
            });

            // Show warning for users with zero balance
            @if(auth()->user()->available_leaves())
                @php
                    $earnedSeconds = auth()->user()->available_leaves()->earned_leave->total('seconds');
                    $casualSeconds = auth()->user()->available_leaves()->casual_leave->total('seconds');
                    $sickSeconds = auth()->user()->available_leaves()->sick_leave->total('seconds');
                @endphp

                @if($earnedSeconds == 0 && $casualSeconds == 0 && $sickSeconds == 0)
                    setTimeout(function() {
                        Swal.fire({
                            title: 'No Leave Balance!',
                            text: 'You have no available leave balance. Please contact HR to check your leave policy.',
                            icon: 'warning',
                            confirmButtonText: 'Contact HR'
                        });
                    }, 1000);
                @endif
            @endif
        });
    </script>
@endsection
