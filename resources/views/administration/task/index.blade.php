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
    .more-user-avatar {
        background-color: #dddddd;
        border-radius: 50px;
        text-align: center;
        padding-top: 5px;
        border: 1px solid #ffffff;
    }
    .more-user-avatar small {
        font-size: 12px;
        color: #333333;
        font-weight: bold;
    }
    
    /* Fix table responsiveness and dropdown visibility */
    .table-responsive-md,
    .table-responsive-sm {
        overflow: initial !important;
    }
    
    .card-body {
        overflow: auto;
    }
    
    /* Style dropdown menu */
    .dropdown-menu {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
        z-index: 1000 !important;
        min-width: 120px !important;
        margin-top: 0.125rem !important;
        background-color: #fff !important;
        box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.45) !important;
        background-clip: padding-box !important;
        border-radius: 0.375rem !important;
        transform: none !important;
    }
    
    /* Style dropdown toggle button */
    .dropdown-toggle {
        padding: 0.5rem !important;
        border-radius: 0.375rem;
        transition: background-color 0.2s ease-in-out;
    }
    
    .dropdown-toggle:hover {
        background-color: rgba(67, 89, 113, 0.04) !important;
    }
    
    /* Ensure action column doesn't wrap */
    .table td:last-child {
        white-space: nowrap;
        width: 1%;
        position: relative;
    }
    
    /* Fix dropdown item styling */
    .dropdown-item {
        padding: 0.532rem 1.25rem;
        clear: both;
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
    }
    
    .dropdown-item:hover {
        background-color: rgba(67, 89, 113, 0.04);
    }
    
    /* Ensure table doesn't overflow container */
    .table {
        margin-bottom: 0;
        white-space: nowrap;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Tasks') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Task') }}</li>
    <li class="breadcrumb-item active">{{ __('All Tasks') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.task.index') }}" method="get">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="creator_id" class="form-label">Select Task Creator</label>
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
                        <div class="mb-3 col-md-4">
                            <label for="user_id" class="form-label">Select Task Assignee</label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->user_id) ? 'selected' : '' }}>Select Assignee</option>
                                @foreach ($assignees as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == request()->user_id ? 'selected' : '' }}>
                                        {{ get_employee_name($user) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="status" class="form-label">Select Task Status</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror" data-style="btn-default">
                                <option value="" {{ is_null(request()->status) ? 'selected' : '' }}>Select Status</option>
                                <option value="Active" {{ request()->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Running" {{ request()->status == 'Running' ? 'selected' : '' }}>Running</option>
                                <option value="Completed" {{ request()->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ request()->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->creator_id || request()->user_id || request()->status)
                            <a href="{{ route('administration.task.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Tasks
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
                <h5 class="mb-0">All Tasks</h5>

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
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Title</th>
                                <th>Assigner & Assignees</th>
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
                                        <b class="text-dark text-capitalize" title="{{ $task->title }}">{{ show_content($task->title, 30) }}</b>
                                        <br>
                                        <div class="li-wrapper d-flex justify-content-start align-items-center li-task-status-priority">
                                            <div class="list-content text-center">
                                                <small class="badge bg-{{ getColor($task->priority) }} task-priority" title="Task Priority">{{ $task->priority }}</small>
                                                @if ($task->parent_task)
                                                    <small class="badge bg-dark mb-1">{{ __('Sub Task') }}</small>
                                                @else
                                                    @if ($task->sub_tasks->count() > 0)
                                                        <small class="badge bg-dark mb-1" title="Total Sub-Tasks">{{ $task->sub_tasks->count() }}</small>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <b class="text-dark">{{ $task->creator->alias_name }}</b>
                                        <br>
                                        @if ($task->users->count() > 0)
                                            <div class="d-flex align-items-center">
                                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 zindex-2 mt-1">
                                                    @foreach ($task->users->take(6) as $user)
                                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->alias_name }}" class="avatar avatar-sm pull-up">
                                                            @if ($user->hasMedia('avatar'))
                                                                <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
                                                            @else
                                                                <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="rounded-circle">
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                    @if ($task->users->count() > 6)
                                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $task->users->count() - 6 }} More" class="avatar avatar-sm pull-up more-user-avatar">
                                                            <small>{{ $task->users->count() - 6 }}+</small>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!is_null($task->deadline))
                                            <b>{{ show_date($task->deadline) }}</b>
                                            @php
                                                $deadlineStatus = task_deadline_status($task->deadline, $task->created_at);
                                            @endphp
                                            <br>
                                            <span class="badge {{ $deadlineStatus['badge_class'] }} fs-tiny fw-bold">{{ $deadlineStatus['text'] }}</span>
                                        @else
                                            <span class="badge bg-success">Ongoing Task</span>
                                        @endif
                                        <br>
                                        <small>Created: <span class="text-muted">{{ show_date($task->created_at) }}</span></small>
                                    </td>
                                    <td>{!! show_status($task->status) !!}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if ($task->creator_id == auth()->user()->id)
                                                    @can ('Task Update')
                                                        <a class="dropdown-item waves-effect" href="{{ route('administration.task.edit', ['task' => $task]) }}" title="Edit Task?">
                                                            <i class="ti ti-pencil me-1"></i> Edit
                                                        </a>
                                                    @endcan
                                                    @can ('Task Delete')
                                                        <a class="dropdown-item waves-effect confirm-danger" href="{{ route('administration.task.destroy', ['task' => $task]) }}" title="Delete Task?">
                                                            <i class="ti ti-trash me-1"></i> Delete
                                                        </a>
                                                    @endcan
                                                @endif
                                                @can ('Task Read')
                                                    <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="dropdown-item waves-effect" title="Show Details">
                                                        <i class="ti ti-info-hexagon"></i> View
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
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
        });
    </script>
@endsection
