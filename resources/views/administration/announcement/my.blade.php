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
    <b class="text-uppercase">{{ __('All Announcements') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Announcement') }}</li>
    <li class="breadcrumb-item active">{{ __('All Announcements') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Announcements</h5>

                <div class="card-header-elements ms-auto">
                    @can ('Announcement Create')
                        <a href="{{ route('administration.announcement.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Announcement
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
                                    <td>
                                        <b>{{ $announcement->title }}</b>
                                        <br>
                                        @if (!is_null($announcement->recipients))
                                            <small class="text-primary text-bold cursor-pointer text-left" title="
                                                @foreach ($announcement->recipients as $recipient)
                                                    <small>{{ show_user_data($recipient, 'name') }}</small>
                                                    <br>
                                                @endforeach
                                            ">
                                                {{ count($announcement->recipients) }} Recipients
                                            </small>
                                        @else
                                            <small class="text-muted">All Recipients</small>
                                        @endif
                                    </td>
                                    <td>
                                        {!! show_user_name_and_avatar($announcement->announcer, role: null) !!}
                                    </td>
                                    <td class="text-center">
                                        @can ('Announcement Delete')
                                            <a href="{{ route('administration.announcement.destroy', ['announcement' => $announcement]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Announcement?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        @can ('Announcement Update')
                                            <a href="{{ route('administration.announcement.edit', ['announcement' => $announcement]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit Announcement?">
                                                <i class="text-white ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can ('Announcement Read')
                                            <a href="{{ route('administration.announcement.show', ['announcement' => $announcement]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
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
