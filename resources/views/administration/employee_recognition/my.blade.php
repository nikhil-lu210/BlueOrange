@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('My Recognitions & Scores'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('My Recognitions & Scores') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognition') }}</li>
    <li class="breadcrumb-item active">{{ __('My Recognitions & Scores') }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <form action="{{ route('administration.employee_recognition.my') }}" method="GET" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" name="year" value="{{ request()->year ?? now()->year }}" class="form-control year-picker" placeholder="YYYY" tabindex="-1"/>
                                <button type="submit" name="filter_reports" value="true" class="btn btn-primary">
                                    <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                    {{ __('Filter Reports') }}
                                </button>
                                @error('year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('My Recognition Scores of') }} <b class="text-bold text-dark">{{ request()->year ?? now()->year }}</b></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr class="bg-label-primary">
                                <th class="text-center text-bold text-dark">{{ __('Month') }}</th>
                                <th class="text-center text-bold text-dark">{{ __('Behavior') }}</th>
                                <th class="text-center text-bold text-dark">{{ __('Appreciation') }}</th>
                                <th class="text-center text-bold text-dark">{{ __('Leadership') }}</th>
                                <th class="text-center text-bold text-dark">{{ __('Loyalty') }}</th>
                                <th class="text-center text-bold text-dark">{{ __('Dedication') }}</th>
                                <th class="text-center text-bold text-dark">{{ __('Total') }}</th>
                                <th class="text-center text-bold text-dark">{{ __('Badge') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recognitions as $eval)
                                @php
                                    $badgeCode = ers_badge_for_score($eval->total_score)['code'];
                                @endphp
                                <tr>
                                    <td class="text-center text-bold text-dark">{{ show_date($eval->month, 'M Y') }}</td>
                                    <td class="text-center">{{ $eval->behavior }}</td>
                                    <td class="text-center">{{ $eval->appreciation }}</td>
                                    <td class="text-center">{{ $eval->leadership }}</td>
                                    <td class="text-center">{{ $eval->loyalty }}</td>
                                    <td class="text-center">{{ $eval->dedication }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ ers_badge_class($badgeCode) }}">{{ $eval->total_score }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ ers_badge_class($badgeCode) }}">{{ show_badge($badgeCode) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">{{ __('No recognitions found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
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

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.year-picker').datepicker({
                format: 'yyyy',         // Display format to show full month name and year
                minViewMode: 'years',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
