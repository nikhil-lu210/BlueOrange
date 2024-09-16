@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Dashboard'))

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
    <b class="text-uppercase">{{ __('Dashboard') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
@endsection



@section('content')
<!-- Start row -->
<div class="row">
    <!-- Statistics -->
    <div class="col-lg-8 mb-4 col-md-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Statistics</h5>
                <small class="text-muted">Updated 1 month ago</small>
            </div>
            <div class="card-body pt-2">
                <div class="row gy-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                <i class="ti ti-chart-pie-2 ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">230k</h5>
                                <small>Sales</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-info me-3 p-2">
                                <i class="ti ti-users ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">8.549k</h5>
                                <small>Customers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                <i class="ti ti-shopping-cart ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">1.423k</h5>
                                <small>Products</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                                <i class="ti ti-currency-dollar ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">$9745</h5>
                                <small>Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-lg-2 col-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="badge rounded-pill p-2 bg-label-danger mb-2">
                    <i class="ti ti-briefcase ti-sm"></i>
                </div>
                <h5 class="card-title mb-2">97.8k</h5>
                <small>Orders</small>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="col-lg-2 col-6 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="badge rounded-pill p-2 bg-label-success mb-2">
                    <i class="ti ti-message-dots ti-sm"></i>
                </div>
                <h5 class="card-title mb-2">3.4k</h5>
                <small>Review</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        
        <!-- Button to trigger QR code scanner -->
        <button id="scanQrBtn" class="btn btn-primary">Scan QR Code</button>

        <!-- Div for displaying the camera feed -->
        <div id="qr-reader" style="width: 300px; height: 300px; display: none;"></div>

        <!-- Div to display scanning results -->
        <div id="qr-reader-results"></div>
    </div>
</div>

<!-- End row -->
@endsection



@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
<!-- Add this script tag for html5-qrcode library before your script -->
{{-- <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>


<script>
    document.getElementById('scanQrBtn').addEventListener('click', function () {
        // Show the QR code reader div
        document.getElementById('qr-reader').style.display = 'block';

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            // Handle success when QR code is scanned
            document.getElementById('qr-reader-results').innerHTML = `Scanned result: ${decodedText}`;

            // Assuming decodedText is the userID, redirect to the attendance route
            let attendanceUrl = `/test/qr/${decodedText}`;
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
</script>

@endsection
