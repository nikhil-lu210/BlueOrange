@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('All Tests'))

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
    <b class="text-uppercase">{{ __('All Tests') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Quiz') }}</li>
    <li class="breadcrumb-item">{{ __('Quiz Tests') }}</li>
    <li class="breadcrumb-item active">{{ __('All Tests') }}</li>
@endsection

@section('content')

<!-- Basic Bootstrap Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('All Tests') }}</h5>
        @canany(['Quiz Everything', 'Quiz Create'])
            <a href="{{ route('administration.quiz.test.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>{{ __('Create Test') }}
            </a>
        @endcanany
    </div>

    <div class="card-body">
        <div class="table-responsive-md table-responsive-sm w-100">
            <table class="table data-table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Candidate Name & Email') }}</th>
                        <th>{{ __('Creator') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th class="text-center">{{ __('Total Questions') }}</th>
                        <th class="text-center">{{ __('Result') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $test)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $test->candidate_name }}<br>
                                <small class="text-muted">{{ $test->candidate_email }}</small>
                            </td>
                            <td>
                                {!! show_user_name_and_avatar($test->creator, name: null) !!}
                            </td>
                            <td>
                                {{ date_time_ago($test->created_at) }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-dark text-bold">{{ $test->questions()->count() }}</span>
                            </td>
                            <td class="text-center">
                                @if (!is_null($test->total_score))
                                    <span class="badge bg-label-dark text-bold">{{ $test->total_score }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @hasanyrole(['Super Admin', 'Developer'])
                                    <a href="{{ route('administration.quiz.test.destroy', ['test' => $test]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Test?">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                @endhasanyrole
                                @canany(['Quiz Everything', 'Quiz Read'])
                                    <a href="{{ route('administration.quiz.test.show', $test) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                        <i class="ti ti-info-hexagon"></i>
                                    </a>
                                @endcanany
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('No tests found') }}</td>
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
