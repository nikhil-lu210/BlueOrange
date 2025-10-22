@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Suggestion'))

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
        .filter-card {
            z-index: 999 !important;
        }
        .marks {
            width: 60px;
            height: 60px;
            display: grid;
            grid-template-areas:
                "got divider"
                "divider total";
            place-items: center;
            position: relative;
            background: #f5f4ff;
            border: 1px solid #b7b4f2;
            border-radius: 6px;
            font-family: system-ui, sans-serif;
        }
        .marks .mark-got {
            grid-area: got;
            font-size: 18px;
            font-weight: 700;
            color: #2c2c54;
            justify-self: start;
            align-self: start;
            margin: -10px;
        }
        .marks .total-mark {
            grid-area: total;
            font-size: 16px;
            font-weight: 500;
            color: #555;
            justify-self: end;
            align-self: end;
            margin: 6px;
        }
        .marks::before {
            content: "";
            position: absolute;
            width: 120%;
            height: 1px;
            background: #b7b4f2;
            transform: rotate(-45deg);
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('All Suggestions') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Suggestion') }}</li>
    <li class="breadcrumb-item active">{{ __('All Suggestions') }}</li>
@endsection

@section('content')

<!-- Start row -->
@include('administration.suggestion.partials._filter_all_suggestion')

<div class="row">
    <div class="col-md-12">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Suggestions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Module</th>
                                <th>Title</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suggestions as $key => $suggestion)
                                <tr>
                                    <th>#{{ serial($suggestions, $key) }}</th>
                                    <td>
                                        {!! show_user_name_and_avatar($suggestion->user, name: null) !!}
                                    </td>
                                    <td>
                                        <span class="badge {{ $suggestion->type_badge_color }}">
                                            {{ $suggestion->type_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $suggestion->module_badge_color }}">
                                            {{ $suggestion->module_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $suggestion->title }}</small>
                                    </td>
                                    <td class="text-center">
                                        @can ('Suggestion Read')
                                            <a href="{{ route('administration.suggestion.show', $suggestion) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="View Details">
                                                <i class="text-white ti ti-info-hexagon"></i>
                                            </a>
                                        @endcan
                                        @can ('Suggestion Delete')
                                            <a href="{{ route('administration.suggestion.destroy', $suggestion) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Suggestion">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
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

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) {
                    $(this).selectpicker();
                }
            });

            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
