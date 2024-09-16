@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('QR Code Attendance'))

@section('css_links')
    {{--  External CSS  --}}
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
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
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
@endsection