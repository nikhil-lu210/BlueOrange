@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('My Certificates'))

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
    <b class="text-uppercase">{{ __('My Certificates') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('My Certificates') }}</li>
@endsection

@section('content')

<!-- Basic Bootstrap Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('My Certificates') }}</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive-md table-responsive-sm w-100">
            <table class="table data-table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Certificate Time') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $key => $certificate)
                        <tr>
                            <td>#{{ serial($certificates, $key) }}</td>
                            <td>
                                {!! show_user_name_and_avatar($certificate->user, role: null) !!}
                            </td>
                            <td>
                                <span class="text-bold text-danger">{{ $certificate->type }}</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ show_date($certificate->created_at) }}</span>
                            </td>
                            <td class="text-center">
                                @canany(['Certificate Everything', 'Certificate Delete'])
                                    <a href="{{ route('administration.certificate.destroy', ['certificate' => $certificate]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Certificate?">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                @endcanany
                                @canany(['Certificate Everything', 'Certificate Read'])
                                    <a href="{{ route('administration.certificate.show', $certificate) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                        <i class="ti ti-info-hexagon"></i>
                                    </a>
                                @endcanany
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('No penalties found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ Basic Bootstrap Table -->

@endsection

@section('script_links')
    {{--  External JS  --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External JS  --}}
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
