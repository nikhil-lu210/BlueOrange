@extends('layouts.errors.app')

@section('page_title', 'Maintenance Mode')

@section('error_content')
    <!-- Image Column -->
    <div class="col-lg-7 col-md-6 col-12 text-center">
        <img
            src="{{ asset($image) }}"
            alt="Maintenance Mode"
            class="img-fluid"
            style="border-radius: 20%;"
        />
    </div>

    <!-- Content Column -->
    <div class="col-lg-5 col-md-6 col-12">
        <div class="d-flex flex-column h-100 justify-content-center">
            <!-- Error Header -->
            <div class="text-center mb-4">
                <div class="error-code">{{ $statusCode }}</div>
                <div class="error-title">{{ $title }}</div>
            </div>
            
            <!-- Error Body -->
            <div class="text-center">
                <div class="error-message mb-4">
                    {!! $message !!}
                </div>
                
                <div class="d-flex justify-content-center">
                    <a href="javascript:void(0)" onclick="location.reload()" class="btn-error">
                        <div class="btn-error-text">
                            <i class="ti ti-refresh me-2"></i> Try Again
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
