@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('My Functionality Walkthroughs'))

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
    <b class="text-uppercase">{{ __('My Functionality Walkthroughs') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Functionality Walkthrough') }}</li>
    <li class="breadcrumb-item active">{{ __('My Walkthroughs') }}</li>
@endsection


@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Functionality Walkthroughs</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.functionality_walkthrough.index') }}" class="btn btn-sm btn-outline-primary">
                        <span class="tf-icon ti ti-list ti-xs me-1"></span>
                        All Walkthroughs
                    </a>
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
