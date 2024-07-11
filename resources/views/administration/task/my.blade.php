@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Task'))

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
    <b class="text-uppercase">{{ __('My Tasks') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Task') }}</li>
    <li class="breadcrumb-item active">{{ __('My Tasks') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Tasks</h5>
        
                <div class="card-header-elements ms-auto">
                    @can ('Task Create') 
                        <a href="{{ route('administration.task.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Task
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Title</th>
                            <th>Creator</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $key => $task) 
                            <tr>
                                <th>#{{ serial($tasks, $key) }}</th>
                                <td>
                                    <b title="{{ $task->title }}">{{ show_content($task->title, 30) }}</b>
                                    <br>
                                    <small>Priority: <span class="text-muted">{{ $task->priority }}</span></small>
                                </td>
                                <td>{{ $task->creator->first_name.' '.$task->creator->last_name }}</td>
                                <td>
                                    <b>{{ show_date($task->deadline) }}</b>
                                    <br>
                                    <small>Created: <span class="text-muted">{{ show_date($task->created_at) }}</span></small>
                                </td>
                                <td>{!! show_status($task->status) !!}</td>
                                <td class="text-center">
                                    @can ('Task Delete') 
                                        <a href="{{ route('administration.task.destroy', ['task' => $task]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Task?">
                                            <i class="text-white ti ti-trash"></i>
                                        </a>
                                    @endcan
                                    @can ('Task Update') 
                                        <a href="{{ route('administration.task.edit', ['task' => $task]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit Task?">
                                            <i class="text-white ti ti-pencil"></i>
                                        </a>
                                    @endcan
                                    @can ('Task Read') 
                                        <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
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
