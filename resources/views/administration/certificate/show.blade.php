@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Certificate Details'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />

    <style>
        .certificate-actions {
            position: sticky;
            top: 20px;
            z-index: 100;
        }

        .certificate-display {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
    </style>
@endsection

@section('custom_css')
    {{--  External CSS  --}}
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Certificate Details') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.certificate.index') }}">{{ __('All Certificates') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Certificate Details') }}</li>
@endsection

@section('content')

<div class="row">
    <!-- Certificate Actions -->
    <div class="col-md-3">
        <div class="card certificate-actions">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Certificate Actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('administration.certificate.print', $certificate) }}"
                       class="btn btn-primary" target="_blank">
                        <i class="ti ti-printer me-1"></i>{{ __('Print Certificate') }}
                    </a>

                    @canany(['Certificate Everything', 'Certificate Delete'])
                        <a href="{{ route('administration.certificate.destroy', $certificate) }}"
                           class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this certificate?')">
                            <i class="ti ti-trash me-1"></i>{{ __('Delete Certificate') }}
                        </a>
                    @endcanany

                    <a href="{{ route('administration.certificate.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>{{ __('Back to List') }}
                    </a>
                </div>

                <!-- Certificate Info -->
                <div class="mt-4">
                    <h6>{{ __('Certificate Information') }}</h6>
                    <hr>
                    <p>
                        <strong>{{ __('Reference No') }}:</strong>
                        <br>
                        <span class="text-bold text-primary">
                            {{ $certificate->formatted_reference_no ?? 'CERT-' . $certificate->reference_no }}
                        </span>
                    </p>
                    <p><strong>{{ __('Type') }}:</strong><br>{{ $certificate->type }}</p>
                    <p><strong>{{ __('Employee') }}:</strong><br><a href="{{ route('administration.settings.user.show.profile', ['user' => $certificate->user]) }}" target="_blank" class="text-primary">{{ $certificate->user->name }}</a></p>
                    <p><strong>{{ __('Issue Date') }}:</strong><br>{{ $certificate->formatted_issue_date }}</p>
                    <p><strong>{{ __('Created By') }}:</strong><br>{{ $certificate->creator->name }}</p>
                    <p><strong>{{ __('Created At') }}:</strong><br>{{ $certificate->created_at->format('M j, Y g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificate Display -->
    <div class="col-md-9">
        <div class="certificate-display">
            @include('administration.certificate.templates.' . $certificate->getTemplateName(), ['isPrint' => false])
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <!-- DataTables JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Any custom JavaScript for the certificate show page
    </script>
@endsection
