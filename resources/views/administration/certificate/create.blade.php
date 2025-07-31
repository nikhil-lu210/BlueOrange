@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Certificate'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Prevent horizontal scroll in certificate form */
        .certificate-form-container {
            overflow-x: hidden;
        }

        /* Certificate preview styling */
        .certificate-preview {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Ensure proper container width */
        .row {
            margin-left: 0;
            margin-right: 0;
        }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Create Certificate') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Certificate') }}</li>
    <li class="breadcrumb-item active">{{ __('Create Certificate') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-4">
        @include('administration.certificate.includes.generate_form', ['employees' => $employees])
    </div>

    {{-- It will visible only if $certificate is not null  --}}
    @if (isset($certificate) && $certificate)
        <div class="col-md-8">
            @include('administration.certificate.includes.generated_certificate')
        </div>
    @endif
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

    {{-- Certificate Form Handler --}}
    <script src="{{ asset('assets/js/custom_js/certificate/certificate-form.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Debug: Check if employees data is available
            console.log('Employee dropdown options count:', $('#user_id option').length);
            console.log('Employee dropdown HTML:', $('#user_id').html());

            // Wait for DOM to be fully loaded
            setTimeout(function() {
                // Destroy any existing Select2 instance first
                if ($('#user_id').hasClass('select2-hidden-accessible')) {
                    $('#user_id').select2('destroy');
                }

                // Initialize Select2 for employee dropdown
                $('#user_id').select2({
                    placeholder: 'Select Employee',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#user_id').parent()
                });

                console.log('Select2 initialized for #user_id');
                console.log('Select2 container:', $('.select2-container').length);
            }, 100);

            // Initialize Bootstrap Select for certificate type
            setTimeout(function() {
                $('.bootstrap-select').each(function() {
                    if (!$(this).data('bs.select')) {
                        $(this).selectpicker();
                    }
                });
                console.log('Bootstrap Select initialized');
            }, 150);

            // Certificate form is handled by certificate-form.js
            // Additional custom scripts can be added here if needed
        });
    </script>
@endsection
