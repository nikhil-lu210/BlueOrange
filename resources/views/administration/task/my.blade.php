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
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    
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
        <form action="{{ route('administration.task.my') }}" method="get">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-7">
                            <label for="creator_id" class="form-label">Select Task Creator</label>
                            <select name="creator_id" id="creator_id" class="select2 form-select @error('creator_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->creator_id) ? 'selected' : '' }}>Select Creator</option>
                                @foreach ($creators as $creator)
                                    <option value="{{ $creator->id }}" {{ $creator->id == request()->creator_id ? 'selected' : '' }}>
                                        {{ get_employee_name($creator) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('creator_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-5">
                            <label for="status" class="form-label">Select Task Status</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror"  data-style="btn-default">
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
                            <a href="{{ route('administration.task.my') }}" class="btn btn-danger confirm-warning">
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
                                        <b title="{{ $task->title }}">{{ show_content($task->title, 30) }}</b>
                                        <br>
                                        <small>Priority: <span class="text-muted">{{ $task->priority }}</span></small>
                                    </td>
                                    <td>
                                        <b class="text-dark">{{ $task->creator->first_name.' '.$task->creator->last_name }}</b>
                                        <br>
                                        @if ($task->users->count() > 0)
                                            <div class="d-flex align-items-center">
                                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 zindex-2 mt-1">
                                                    @foreach ($task->users->take(6) as $user)
                                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar avatar-sm pull-up">
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
                                        @else 
                                            <span class="badge bg-success">Ongoing Task</span>
                                        @endif
                                        <br>
                                        <small>Created: <span class="text-muted">{{ show_date($task->created_at) }}</span></small>
                                    </td>
                                    <td>{!! show_status($task->status) !!}</td>
                                    <td class="text-center">
                                        @if ($task->creator_id == auth()->user()->id) 
                                            @can ('Task Delete') 
                                                <a href="{{ route('administration.task.destroy', ['task' => $task]) }}" class="btn btn-icon btn-label-danger btn-sm waves-effect confirm-danger" data-bs-toggle="tooltip" title="Delete Task?">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            @endcan
                                            @can ('Task Update') 
                                                <a href="{{ route('administration.task.edit', ['task' => $task]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit Task?">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            @endcan
                                        @endif
                                        @can ('Task Read') 
                                            <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                                <i class="ti ti-info-hexagon"></i>
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
    <script src="{{asset('assets/js/form-layouts.js')}}"></script>

    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
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
