@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Notification'))

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
    <b class="text-uppercase">{{ __('All Notifications') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Notification') }}</li>
    <li class="breadcrumb-item active">{{ __('All Notifications') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Notifications</h5>
        
                @if (Auth::user()->notifications->count() > 0) 
                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('administration.notification.destroy.all') }}" class="btn btn-sm btn-danger confirm-danger">
                            <span class="tf-icon ti ti-trash ti-xs me-1"></span>
                            Delete All
                        </a>
                        <a href="{{ route('administration.notification.mark_all_as_read') }}" class="btn btn-sm btn-primary confirm-success">
                            <span class="tf-icon ti ti-check ti-xs me-1"></span>
                            Mark All As Read
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Notification</th>
                            <th>Notified At</th>
                            <th>Read At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $key => $notification) 
                            <tr>
                                <th>#{{ serial($notifications, $key) }}</th>
                                <td>{{ $notification->data['message'] }}</td>
                                <td>{{ date_time_ago($notification->created_at) }}</td>
                                <td>
                                    @if (!is_null($notification->read_at)) 
                                        {{ date_time_ago($notification->read_at) }}
                                    @else 
                                        <span class="badge bg-label-dark">Unread</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('administration.notification.destroy', ['notification_id' => $notification->id]) }}" class="btn btn-sm btn-icon item-edit confirm-danger" data-bs-toggle="tooltip" title="Delete Notification?">
                                        <i class="text-danger ti ti-trash"></i>
                                    </a>
                                    <a href="{{ route('administration.notification.mark_as_read', ['notification_id' => $notification->id]) }}" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" title="Show Notification">
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
