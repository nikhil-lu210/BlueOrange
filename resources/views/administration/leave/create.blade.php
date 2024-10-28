@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Apply For Leave'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
    
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
                                            <input name="type" class="form-check-input" type="radio" value="Earned" id="typeEarned" required />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Earned</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="typeSick">
                                            <input name="type" class="form-check-input" type="radio" value="Sick" id="typeSick" required />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Sick</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="typeCasual">
                                            <input name="type" class="form-check-input" type="radio" value="Casual" id="typeCasual"  required />
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
                            <div class="card mb-4 border-1">
                                <div class="card-header header-elements">
                                    <h5 class="mb-0">Leave Date(s) and Hour(s)</h5>
                            
                                    <div class="card-header-elements ms-auto">
                                        <button type="button" class="btn btn-sm btn-dark" id="addLeaveDay">
                                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                                            Add Day
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <!-- This Row Will Be Duplicated on Add Day Button Click -->
                                    <div class="row leave-day-row">
                                        <div class="mb-3 col-md-5">
                                            <label class="form-label">Date <strong class="text-danger">*</strong></label>
                                            <input type="text" name="leave_days[date][]" value="{{ old('deadline') }}" class="form-control date-picker" placeholder="YYYY-MM-DD" tabindex="-1" required />
                                            @error('deadline.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                
                                        <div class="mb-3 col-md-7">
                                            <label for="total_leave" class="form-label">Total Leave <strong class="text-danger">*</strong></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-label-primary">Hour:</span>
                                                <input type="number" min="0" max="240" step="1" name="total_leave[hour][]" value="{{ old('total_leave_hour') }}" class="form-control @error('total_leave_hour.*') is-invalid @enderror" placeholder="HH" aria-label="HH" required>
                                                <span class="input-group-text bg-label-primary">Min:</span>
                                                <input type="number" min="0" max="59" step="1" name="total_leave[min][]" value="{{ old('total_leave_min') }}" class="form-control @error('total_leave_min.*') is-invalid @enderror" placeholder="MM" aria-label="MM" required>
                                                <span class="input-group-text bg-label-primary">Sec:</span>
                                                <input type="number" min="0" max="59" step="1" name="total_leave[sec][]" value="{{ old('total_leave_sec') }}" class="form-control @error('total_leave_sec.*') is-invalid @enderror" placeholder="SS" aria-label="SS" required>
                                            </div>
                                            @error('total_leave.*')
                                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <!-- Hide remove button initially -->
                                            <button type="button" class="btn btn-danger btn-xs remove-leave-day text-right float-right" style="display: none !important;">
                                                <i class="fa fa-times" style="margin-right: 5px;"></i>
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>                                
                            </div>                            
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
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/form-layouts.js')}}"></script>
    <!-- Vendors JS -->
    <script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
    
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
        $(document).ready(function() {
            // Add Day button click event
            $('#addLeaveDay').click(function() {
                // Clone the leave day row
                var newRow = $('.leave-day-row:first').clone();

                // Clear the input values in the cloned row
                newRow.find('input').val('');

                // Show the remove button for all rows except the first one
                newRow.find('.remove-leave-day').show();

                // Append the cloned row to the parent container
                $('.leave-day-row:first').parent().append(newRow);

                // Reinitialize date-picker on cloned inputs
                newRow.find('.date-picker').removeClass('hasDatepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                    autoclose: true,
                    orientation: 'auto right'
                });
            });

            // Event delegation for remove button click
            $(document).on('click', '.remove-leave-day', function() {
                // Check if it's not the remove button of the first row
                if ($(this).closest('.row').index() !== 0) {
                    // Remove the parent row
                    $(this).closest('.leave-day-row').remove();
                }
            });
        });
    </script>
@endsection
