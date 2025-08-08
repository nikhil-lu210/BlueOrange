@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Task'))

@section('css_links')
    {{--  External CSS  --}}
    
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/custom_css/task/sprint.css') }}">
    
    @endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Tasks Board') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Task') }}</li>
    <li class="breadcrumb-item active">{{ __('All Tasks Board') }}</li>
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
        <div class="board">
            <div class="column" id="todo">
                <div class="column-header">
                    <div class="left">
                        <h5>To Do</h5>
                        <span class="task-count" id="todo-count">0</span>
                    </div>
                    <div class="right">
                        <button id="addTaskBtn" class="btn btn-light add-task-btn" data-column-header="todo">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>

                    
                </div>
                <div class="task-list" data-column="todo">
                    <!-- Tasks will be added here dynamically -->
                </div>
            </div>

            <div class="column" id="not-start">
                <div class="column-header">
                    <div class="left">
                        <h5>Not Start</h5>
                        <span class="task-count" id="not-start-count">0</span>
                    </div>
                    <div class="right">
                        <button id="addTaskBtn" class="btn btn-light add-task-btn" data-column-header="not-start">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>
                    
                </div>
                <div class="task-list" data-column="not-start">
                    <!-- Tasks will be added here dynamically -->
                </div>
            </div>

            <div class="column" id="in-progress">
                <div class="column-header">
                    <div class="left">
                        <h5>In Progress</h5>
                        <span class="task-count" id="in-progress-count">0</span>
                    </div>
                    
                    <div class="right">
                        <button id="addTaskBtn" class="btn btn-light add-task-btn" data-column-header="in-progress">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>
                </div>
                
                
                <div class="task-list" data-column="in-progress">
                    <!-- Tasks will be added here dynamically -->
                </div>
            </div>

            <div class="column" id="done">
                <div class="column-header">
                    <div class="left">
                        <h5>Done</h5>
                        <span class="task-count" id="done-count">0</span>
                    </div>
                    <div class="right">
                        <button id="addTaskBtn" class="btn btn-light add-task-btn" data-column-header="done">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>
                    
                    
                </div>
                <div class="task-list" data-column="done">
                    <!-- Tasks will be added here dynamically -->
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div class="modal" id="taskModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Task</h2>
            <form id="taskForm">
                <div class="form-group">
                    <label for="taskTitle">Title</label>
                    <input type="text" id="taskTitle" required>
                </div>
                <div class="form-group">
                    <label for="taskDescription">Description</label>
                    <textarea id="taskDescription" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="taskPriority">Priority</label>
                    <select id="taskPriority">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="assignTo">Assign To</label>
                    <select id="assignTo">
                        <option value="shams">Scott</option>
                        <option value="nigel">Nigel</option>
                        <option value="max">Max</option>
                        <option value="henry">Henry</option>
                        <option value="jack">Jack</option>
                        <option value="sarah">Sarah</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dueDate">Due Date</label>
                    <input type="date" id="due" name="due">
                </div>
                <button type="submit" class="btn btn-primary">Add Task</button>
            </form>
        </div>
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/task/sprint.js') }}"></script>
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
