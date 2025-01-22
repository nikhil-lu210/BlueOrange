@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('QR Code Attendance'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('QR Code Attendance') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
    <li class="breadcrumb-item active">{{ __('QR Code Attendance') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">QR Code Attendance</h5>
        
                <div class="card-header-elements ms-auto">
                    <button id="scanQrBtn" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-qrcode ti-xs me-1"></span>
                        Scan QR Code
                    </button>
                    <button id="scanQrBtnOvertime" class="btn btn-sm btn-warning">
                        <span class="tf-icon ti ti-qrcode ti-xs me-1"></span>
                        Overtime
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <!-- Div for displaying the camera feed -->
                        <div id="qr-reader" style="width: 100%; height: 100%; display: none;"></div>

                        <!-- Div to display scanning results -->
                        <div id="qr-reader-results"></div>
                    </div>
                </div>
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
                                        <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div class="avatar me-2">
                                                    @if ($attendance->user->hasMedia('avatar'))
                                                        <img src="{{ $attendance->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $attendance->user->name }} Avatar" class="rounded-circle">
                                                    @else
                                                        <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="{{ $attendance->user->name }} No Avatar" class="rounded-circle">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="javascript:void(0);" target="_blank" class="emp_name text-truncate text-bold">{{ $attendance->user->name }}</a>
                                                <small class="emp_post text-truncate text-muted">{{ $attendance->user->roles[0]->name }}</small>
                                            </div>
                                        </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            $('#scanQrBtn').click(function () {
                const scannerId = '{{ $scanner->userid }}';
                
                // Show the QR code reader div
                $('#qr-reader').show();
        
                const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                    // Handle success when QR code is scanned
                    $('#qr-reader-results').html(`Scanned result: ${decodedText}`);
        
                    // Assuming decodedText is the userID, redirect to the attendance route
                    let attendanceUrl = `/attendance/qrcode/scan/${scannerId}/${decodedText}`;
                    window.location.href = attendanceUrl;
        
                    // Stop the scanning once the code is found
                    html5QrCode.stop().then(() => {
                        console.log("QR Code scanning stopped.");
                    }).catch((err) => {
                        console.error("Error stopping scanning: ", err);
                    });
                };
        
                const qrCodeErrorCallback = (errorMessage) => {
                    // Optionally handle errors (e.g., no QR code found)
                    console.warn(`QR Code scan error: ${errorMessage}`);
                };
        
                const html5QrCode = new Html5Qrcode("qr-reader");
        
                // Start the QR code scanner
                html5QrCode.start(
                    { facingMode: "environment" }, // Use the back camera
                    {
                        fps: 10,    // Frames per second
                        qrbox: { width: 250, height: 250 } // Scanning box size
                    },
                    qrCodeSuccessCallback,
                    qrCodeErrorCallback
                ).catch((err) => {
                    console.error("Error starting QR code scanner: ", err);
                });
            });
        });
    </script>
    
    <script>
        $(document).ready(function() {
            $('#scanQrBtnOvertime').click(function () {
                const scannerID = '{{ $scanner->userid }}';
                
                // Show the QR code reader div
                $('#qr-reader').show();
        
                const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                    // Handle success when QR code is scanned
                    $('#qr-reader-results').html(`Scanned result: ${decodedText}`);
        
                    // Assuming decodedText is the userID, redirect to the attendance route
                    let attendanceUrl = `/attendance/qrcode/scan/${scannerID}/${decodedText}/Overtime`;
                    window.location.href = attendanceUrl;
        
                    // Stop the scanning once the code is found
                    html5QrCode.stop().then(() => {
                        console.log("QR Code scanning stopped.");
                    }).catch((err) => {
                        console.error("Error stopping scanning: ", err);
                    });
                };
        
                const qrCodeErrorCallback = (errorMessage) => {
                    // Optionally handle errors (e.g., no QR code found)
                    console.warn(`QR Code scan error: ${errorMessage}`);
                };
        
                const html5QrCode = new Html5Qrcode("qr-reader");
        
                // Start the QR code scanner
                html5QrCode.start(
                    { facingMode: "environment" }, // Use the back camera
                    {
                        fps: 10,    // Frames per second
                        qrbox: { width: 250, height: 250 } // Scanning box size
                    },
                    qrCodeSuccessCallback,
                    qrCodeErrorCallback
                ).catch((err) => {
                    console.error("Error starting QR code scanner: ", err);
                });
            });
        });
    </script>
@endsection