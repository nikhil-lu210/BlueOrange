@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Barcode Attendance'))

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
    <b class="text-uppercase">{{ __('Barcode Attendance') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
    <li class="breadcrumb-item active">{{ __('Barcode Attendance') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Barcode Attendance</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.attendance.barcode.scanner') }}" class="btn btn-sm btn-dark" title="Reload Page?">
                        <span class="tf-icon ti ti-reload ti-xs me-1"></span>
                        Reload
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.attendance.barcode.scan', ['scanner_id' => $scanner_id]) }}" method="POST" autocomplete="off" id="barcodeScannerForm">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-basic form-check-primary">
                                        <label class="form-check-label custom-option-content" for="typeRegular">
                                            <input name="type" class="form-check-input" type="radio" value="Regular" id="typeRegular" checked />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0 text-primary text-bold">{{ __('Regular') }}</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic form-check-warning">
                                        <label class="form-check-label custom-option-content" for="typeOvertime">
                                            <input name="type" class="form-check-input" type="radio" value="Overtime" id="typeOvertime" />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0 text-warning text-bold">{{ __('Overtime') }}</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label text-bold text-dark">{{ __('User ID') }} <strong class="text-danger">*</strong></label>
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
                    <span>Attendances of </span>
                    <span class="text-bold">{{ date('d M Y') }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Name</th>
                                <th>Clocked IN</th>
                                <th>Clock Out</th>
                                <th>Total</th>
                                <th>Scanned By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $key => $attendance) 
                                <tr>
                                    <th>#{{ serial($attendances, $key) }}</th>
                                    <td>
                                        {!! show_user_name_and_avatar($attendance->user, role: null) !!}
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            @php
                                                if (get_time_only($attendance->clock_in) > $attendance->employee_shift->start_time){
                                                    $clockInColor = 'text-danger';
                                                } else {
                                                    $clockInColor = 'text-success';
                                                }
                                            @endphp
                                            <span class="text-bold {{ $clockInColor }}">{{ show_time($attendance->clock_in) }}</span>
                                            <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="left" title="Shift Start Time">{{ show_time($attendance->employee_shift->start_time) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            @isset($attendance->clock_out)
                                                @php
                                                    if (get_time_only($attendance->clock_out) < $attendance->employee_shift->end_time){
                                                        $clockOutColor = 'text-danger';
                                                    } else {
                                                        $clockOutColor = 'text-success';
                                                    }
                                                @endphp
                                                <span class="text-bold {{ $clockOutColor }}">{{ show_time($attendance->clock_out) }}</span>
                                            @else
                                                <b class="text-success text-uppercase">Running</b>
                                            @endisset
                                            <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="right" title="Shift End Time">{{ show_time($attendance->employee_shift->end_time) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            @isset($attendance->total_time)
                                                @php
                                                    $totalWorkingHour = get_total_hour($attendance->employee_shift->start_time, $attendance->employee_shift->end_time);
                                                @endphp
                                                <b>
                                                    {!! total_time_with_min_hour($attendance->total_time, $totalWorkingHour) !!}
                                                </b>
                                            @else
                                                <b class="text-success text-uppercase">Running</b>
                                            @endisset
                                            @if ($attendance->type == 'Regular') 
                                                @php
                                                    $totalTimeDifferent = total_time_difference($attendance->employee_shift->start_time, $attendance->employee_shift->end_time);
                                                @endphp
                                                <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Shift's Total Working Time">{{ $totalTimeDifferent }}</small>
                                            @else
                                                <small class="text-bold text-warning">{{ $attendance->type }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark text-truncate">
                                            <b>Clock-In:</b> 
                                            <span>{{ optional($attendance->clockin_scanner)->name }}</span>
                                        </span>
                                        <br>
                                        <span class="text-dark text-truncate">
                                            <b>Clock-Out:</b> 
                                            <span>{{ optional($attendance->clockout_scanner)->name }}</span>
                                        </span>
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
        $(document).ready(function() {
            // When any radio button for clock-in type is selected
            $('input[name="type"]').on('change', function() {
                // Clear the User ID field and focus on it
                $('#userid').val('').focus();
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
