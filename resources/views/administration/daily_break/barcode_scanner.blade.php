@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Barcode Daily Break'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Barcode Daily Break') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Break') }}</li>
    <li class="breadcrumb-item active">{{ __('Barcode Daily Break') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Barcode Daily Break</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.daily_break.barcode.scanner') }}" class="btn btn-sm btn-dark" title="Reload Page?">
                        <span class="tf-icon ti ti-reload ti-xs me-1"></span>
                        Reload
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.daily_break.barcode.scan', ['scanner_id' => $scanner_id]) }}" method="POST" autocomplete="off" id="barcodeScannerForm">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check form-check-primary bg-label-primary custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="shortBreak">
                                            <input name="break_type" value="Short" class="form-check-input" type="radio" id="shortBreak" required/>
                                            <span class="custom-option-header">
                                                <span class="h6 mb-0 text-uppercase text-bold">Short Break</span>
                                                <span class="text-bold">15-20 Min</span>
                                            </span>
                                            <span class="custom-option-body">
                                                <small class="text-muted">You Can Take Maximum 2 Short Break.</small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check form-check-warning bg-label-warning custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="longBreak">
                                            <input name="break_type" value="Long" class="form-check-input" type="radio" id="longBreak" required/>
                                            <span class="custom-option-header">
                                                <span class="h6 mb-0 text-uppercase text-bold">Long Break</span>
                                                <span class="text-bold">30-45 Min</span>
                                            </span>
                                            <span class="custom-option-body">
                                                <small class="text-muted">You Can Take Maximum 1 Long Break.</small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 col-md-12" id="userIdInputDiv">
                            <label class="form-label">{{ __('User ID') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text" style="padding-right: 2px;">UID</span>
                                <input type="text" id="userid" name="userid" class="form-control @error('userid') is-invalid @enderror" placeholder="20010101" autofocus required/>
                            </div>
                            @error('userid')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
        </div>      
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span>Daily Breaks of </span>
                    <span class="text-bold">{{ date('d M Y') }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Break Started</th>
                                <th>Break Stopped</th>
                                <th class="text-center">Break Time</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($breaks as $key => $break) 
                                <tr>
                                    <th>#{{ serial($breaks, $key) }}</th>
                                    <td>
                                        {!! show_user_name_and_avatar($break->user, name:null, role: null) !!}
                                    </td>
                                    <td>
                                        <small class="badge bg-{{ $break->type === 'Short' ? 'primary' : 'warning' }}">{{ $break->type }} Break</small>
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            <span class="text-bold text-success">{{ show_time($break->break_in_at) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @isset ($break->break_out_at) 
                                            <span class="text-bold text-success">{{ show_time($break->break_out_at) }}</span>
                                        @else
                                            <span class="badge bg-label-danger text-bold" title="Break Running">{{ __('Running') }}</span>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @isset ($break->total_time) 
                                            <div class="d-grid">
                                                <span class="text-bold badge bg-label-warning mb-1">{{ total_time($break->total_time) }}</span>
                                                @isset ($break->over_break) 
                                                    <small class="text-bold badge bg-label-danger">
                                                        <span>Overbreak: {{ total_time($break->over_break) }}</span>
                                                    </small>
                                                @endisset
                                            </div>
                                        @else
                                            <span class="badge bg-label-danger text-bold" title="Break Running">{{ __('Running') }}</span>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('administration.daily_break.show', ['break' => $break]) }}" target="_blank" class="btn btn-sm btn-icon btn-primary item-edit" data-bs-toggle="tooltip" title="Show Details">
                                            <i class="ti ti-info-hexagon"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
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
        });
    </script>

    <script>
        $(document).ready(function () {
            let userIdInput = $("#userIdInputDiv"); // Target the input div

            // Initially hide the User ID input
            userIdInput.hide();

            $("input[name='break_type']").on("change", function () {
                userIdInput.show();  // Show input when checkbox is clicked
                $("#userid").val("").focus();  // Clear and autofocus the input field
            });
        });
    </script>

    {{-- <script>
        $(document).ready(function () {
            $("#barcodeScannerForm").on("keypress", function (event) {
                // Check if the Enter key was pressed
                if (event.which === 13) {
                    event.preventDefault(); // Prevent form submission
                }
            });
        });
    </script> --}}
@endsection