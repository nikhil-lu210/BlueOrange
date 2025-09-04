@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Functionality Walkthrough'))

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
    <b class="text-uppercase">{{ __('All Functionality Walkthroughs') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Functionality Walkthrough') }}</li>
    <li class="breadcrumb-item active">{{ __('All Walkthroughs') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('administration.functionality_walkthrough.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-7">
                            <label for="creator_id" class="form-label">Select Creator</label>
                            <select name="creator_id" id="creator_id" class="select2 form-select @error('creator_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->creator_id) ? 'selected' : '' }}>Select Creator</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $creator)
                                            <option value="{{ $creator->id }}" {{ $creator->id == request()->creator_id ? 'selected' : '' }}>
                                                {{ get_employee_name($creator) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('creator_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-5">
                            <label class="form-label">Walkthroughs Of</label>
                            <input type="text" name="created_month_year" value="{{ request()->created_month_year ?? old('created_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('created_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->creator_id || request()->created_month_year)
                            <a href="{{ route('administration.functionality_walkthrough.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Walkthroughs
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Functionality Walkthroughs</h5>

                <div class="card-header-elements ms-auto">
                    @can ('Functionality Walkthrough Create')
                        <a href="{{ route('administration.functionality_walkthrough.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Walkthrough
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Created At</th>
                                <th>Walkthrough</th>
                                <th>Creator</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($walkthroughs as $key => $walkthrough)
                                <tr>
                                    <th>#{{ serial($walkthroughs, $key) }}</th>
                                    <td>
                                        <b>{{ show_date($walkthrough->created_at) }}</b>
                                        <br>
                                        <span>
                                            at
                                            <b>{{ show_time($walkthrough->created_at) }}</b>
                                        </span>
                                    </td>
                                    <td>
                                        <b>{{ $walkthrough->title }}</b>
                                        <br>
                                        @if ($walkthrough->assigned_roles->isNotEmpty())
                                            <small class="text-primary text-bold cursor-pointer text-left" title="
                                                @foreach ($walkthrough->assigned_roles->take(9) as $role)
                                                    <small>{{ $role->name }}</small>
                                                    <br>
                                                @endforeach
                                                @if ($walkthrough->assigned_roles->count() > 9)
                                                    {{ $walkthrough->assigned_roles->count() - 9 }} More
                                                @endif
                                            ">
                                                {{ $walkthrough->assigned_roles->count() }} Roles
                                            </small>
                                        @else
                                            <small class="text-muted">All Users</small>
                                        @endif
                                    </td>
                                    <td>
                                        {!! show_user_name_and_avatar($walkthrough->creator, name: null) !!}
                                    </td>
                                    <td class="text-center">
                                        @can ('Functionality Walkthrough Delete')
                                            <a href="{{ route('administration.functionality_walkthrough.destroy', ['functionalityWalkthrough' => $walkthrough]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Walkthrough?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        @can ('Functionality Walkthrough Update')
                                            <a href="{{ route('administration.functionality_walkthrough.edit', ['functionalityWalkthrough' => $walkthrough]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit Walkthrough?">
                                                <i class="text-white ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can ('Functionality Walkthrough Read')
                                            <a href="{{ route('administration.functionality_walkthrough.show', ['functionalityWalkthrough' => $walkthrough]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                                <i class="text-white ti ti-info-hexagon"></i>
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
        // Custom Script Here
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.month-year-picker').datepicker({
                format: 'MM yyyy',         // Display format to show full month name and year
                minViewMode: 'months',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
