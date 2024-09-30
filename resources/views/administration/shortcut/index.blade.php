@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Shortcut'))

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
    <b class="text-uppercase">{{ __('All Shortcuts') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Shortcut') }}</li>
    <li class="breadcrumb-item active">{{ __('All Shortcuts') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Shortcuts</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.shortcut.create') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        Create Shortcut
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>URL</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shortcuts as $key => $shortcut) 
                            <tr>
                                <th>#{{ serial($shortcuts, $key) }}</th>
                                <td>
                                    <i class="ti ti-{{ $shortcut->icon }}"></i>
                                    <br>
                                    <small>{{ $shortcut->icon }}</small>
                                </td>
                                <td>
                                    <b>{{ $shortcut->name }}</b>
                                </td>
                                <td><code>{{ $shortcut->url }}</code></td>
                                <td class="text-center">
                                    <a href="{{ route('administration.shortcut.destroy', ['shortcut' => $shortcut]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete shortcut?">
                                        <i class="text-white ti ti-trash"></i>
                                    </a>
                                    <a href="{{ route('administration.shortcut.edit', ['shortcut' => $shortcut]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit shortcut?">
                                        <i class="text-white ti ti-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
