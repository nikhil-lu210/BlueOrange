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
                                <h5 class="text-success mb-0">{{ auth()->user()->available_leaves()->earned_leave }}</h5>
                                <small class="mb-0 text-muted">Available <b class="text-dark">Earned Leave ({{ date('Y') }})</b></small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                                <i class="ti ti-calendar-pause ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-warning mb-0">{{ auth()->user()->available_leaves()->sick_leave }}</h5>
                                <small class="mb-0 text-muted">Available <b class="text-dark">Sick Leave ({{ date('Y') }})</b></small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-primary p-2 rounded">
                                <i class="ti ti-calendar-pause ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-primary mb-0">{{ auth()->user()->available_leaves()->casual_leave }}</h5>
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
                [{ script: "super" }, { script: "sub" }],
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
            // Counter for dynamic rows
            let rowCount = $('.leave-day-row').length; // Start from the current number of rows

            // Add Day button click event
            $('#addLeaveDay').click(function () {
                // Clone the last leave day row
                var newRow = $('.leave-day-row:last').clone();

                // Clear the input values in the cloned row
                newRow.find('input[type="text"], input[type="number"]').val('');

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
                }
            });

            // Initial reindexing to ensure first row is included
            $('.leave-day-row').each(function (index) {
                $(this).find('input[name^="leave_days[date]"]').attr('name', `leave_days[date][${index}]`);
                $(this).find('input[name^="total_leave[hour]"]').attr('name', `total_leave[hour][${index}]`);
                $(this).find('input[name^="total_leave[min]"]').attr('name', `total_leave[min][${index}]`);
                $(this).find('input[name^="total_leave[sec]"]').attr('name', `total_leave[sec][${index}]`);
            });
        });
    </script>
@endsection
