@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Announcement'))

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
    <b class="text-uppercase">{{ __('My Announcements') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Announcement') }}</li>
    <li class="breadcrumb-item active">{{ __('My Announcements') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Announcements</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="#" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        Create Announcement
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Announced At</th>
                            <th>Announcement</th>
                            <th>Announcer</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($announcements as $key => $announcement) 
                            <tr>
                                <th>#{{ serial($announcements, $key) }}</th>
                                <td>{{ show_date($announcement->created_at) }}</td>
                                <td>{{ $announcement->description }}</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" title="Show Details">
                                        <i class="text-primary ti ti-info-hexagon"></i>
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
