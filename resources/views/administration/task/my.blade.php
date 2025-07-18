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
        .list-group-item + .list-group-item {
            border-top-width: 1px;
        }

        .grid-view {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Responsive Grid */
            gap: 20px; /* Spacing between items */
        }
        .grid-view .list-group-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 15px;
            border-radius: 10px;
            transition: transform 0.2s ease-in-out;
        }
        .grid-view .list-group-item:hover {
            transform: translateY(-5px); /* Subtle hover effect */
        }
        .grid-view .list-content {
            width: 100%;
        }
        .grid-view .li-wrapper.li-task-status-priority {
            position: absolute;
            right: 0;
            top: 0;
        }
        .grid-view .task-status {
            position: absolute;
            right: 10px;
            top: 40px;
        }
        .grid-view .task-priority {
            position: absolute;
            right: 10px;
            top: 10px;
        }
        .grid-view .li-wrapper {
            margin-bottom: 15px;
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
                <h5 class="mb-0">My Tasks <sup class="text-muted">(Assigned to me / Created by me)</sup></h5>
                <div class="card-header-elements ms-auto">
                    @can ('Task Create')
                        <a href="{{ route('administration.task.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Task
                        </a>
                    @endcan
                    <button id="toggleView" class="btn btn-icon btn-outline-dark" title="Switch View">
                        <span class="tf-icon ti ti-layout-2"></span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="vehicles-overview-progress progress rounded-0 mb-2" style="height: 40px">
                    @if ($statusPercentages['active'] > 0)
                        <div class="progress-bar fw-medium text-start bg-label-info px-3 rounded-0 text-center" role="progressbar"
                            style="width: {{ $statusPercentages['active'] }}%" aria-valuenow="{{ $statusPercentages['active'] }}"
                            aria-valuemin="0" aria-valuemax="100" title="Active Tasks">
                            <span class="percentage-value">{{ $statusPercentages['active'] }}%</span>
                        </div>
                    @endif

                    @if ($statusPercentages['running'] > 0)
                        <div class="progress-bar fw-medium text-start bg-label-primary px-3 rounded-0 text-center" role="progressbar"
                            style="width: {{ $statusPercentages['running'] }}%" aria-valuenow="{{ $statusPercentages['running'] }}"
                            aria-valuemin="0" aria-valuemax="100" title="Running Tasks">
                            <span class="percentage-value">{{ $statusPercentages['running'] }}%</span>
                        </div>
                    @endif

                    @if ($statusPercentages['completed'] > 0)
                        <div class="progress-bar fw-medium text-start bg-label-success px-3 rounded-0 text-center" role="progressbar"
                            style="width: {{ $statusPercentages['completed'] }}%" aria-valuenow="{{ $statusPercentages['completed'] }}"
                            aria-valuemin="0" aria-valuemax="100" title="Completed Tasks">
                            <span class="percentage-value">{{ $statusPercentages['completed'] }}%</span>
                        </div>
                    @endif

                    @if ($statusPercentages['canceled'] > 0)
                        <div class="progress-bar fw-medium text-start bg-label-danger px-3 rounded-0 text-center" role="progressbar"
                            style="width: {{ $statusPercentages['canceled'] }}%" aria-valuenow="{{ $statusPercentages['canceled'] }}"
                            aria-valuemin="0" aria-valuemax="100" title="Canceled Tasks">
                            <span class="percentage-value">{{ $statusPercentages['canceled'] }}%</span>
                        </div>
                    @endif
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="demo-inline-spacing mt-1">

                            <div id="taskContainer" class="list-group list-view"> {{-- grid-view --}}
                                @forelse ($tasks as $key => $task)
                                    <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="list-group-item d-flex justify-content-between btn-outline-{{ getColor($task->status) }} bg-label-{{ getColor($task->status) }} mb-3" style="border-radius: 5px;">
                                        <div class="li-wrapper d-flex justify-content-start align-items-center" title="{{ $task->title }}">
                                            <div class="list-content">
                                                <h6 class="mb-1 text-dark text-bold text-capitalize">{{ show_content($task->title, 30) }}</h6>
                                                <small class="text-muted">Task ID: <b>{{ $task->taskid }}</b></small>
                                            </div>
                                        </div>
                                        <div class="li-wrapper d-flex justify-content-start align-items-center">
                                            <div class="list-content">
                                                @if (!is_null($task->deadline))
                                                    <b class="text-dark" title="Task Deadline">{{ show_date($task->deadline) }}</b>
                                                    @php
                                                        $deadlineStatus = task_deadline_status($task->deadline, $task->created_at);
                                                    @endphp
                                                    <br>
                                                    <span class="badge {{ $deadlineStatus['badge_class'] }} fs-tiny fw-bold" title="Deadline Status">{{ $deadlineStatus['text'] }}</span>
                                                @else
                                                    <span class="badge bg-success" title="Task Deadline">Ongoing Task</span>
                                                @endif
                                                @if ($task->parent_task)
                                                    <small class="badge bg-dark mb-1">{{ __('Sub Task') }}</small>
                                                @else
                                                    @if ($task->sub_tasks->count() > 0)
                                                        <small class="badge bg-dark mb-1" title="Total Sub-Tasks">{{ $task->sub_tasks->count() }}</small>
                                                    @endif
                                                @endif
                                                <br>
                                                <small class="text-dark">Created: <span class="text-muted">{{ show_date($task->created_at) }}</span></small>
                                            </div>
                                        </div>
                                        <div class="li-wrapper d-flex justify-content-start align-items-center li-task-status-priority">
                                            <div class="list-content text-center">
                                                <small class="badge bg-{{ getColor($task->status) }} mb-1 task-status" title="Task Status">{{ $task->status }}</small>
                                                <br>
                                                <small class="badge bg-{{ getColor($task->priority) }} task-priority" title="Task Priority">{{ $task->priority }}</small>
                                            </div>
                                        </div>
                                        <div class="li-wrapper d-flex justify-content-start align-items-center">
                                            <div class="list-content">
                                                <span class="text-dark text-bold" title="Task Creator">
                                                    {{ $task->creator->alias_name }}
                                                </span>
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
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <h4 class="text-center text-muted mt-3">{{ __('No Tasks Available') }}</h4>
                                @endforelse

                                {{-- {{ $tasks->links() }} --}}
                            </div>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    {!! pagination($tasks, 'center', 'primary') !!}
                    {{-- Pagination Ends --}}
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

    <script>
        $(document).ready(function () {
            var $container = $('#taskContainer');
            var $button = $('#toggleView');

            // Check localStorage for stored view preference, or default to 'list-view'
            var viewMode = localStorage.getItem('taskViewMode');
            if (!viewMode) {
                viewMode = 'list-view'; // Default to list-view if nothing is stored
            }

            // Apply the view mode from localStorage or default
            $container.removeClass('grid-view list-view').addClass(viewMode);
            updateButtonText(viewMode);

            // Toggle View on Button Click
            $button.on('click', function () {
                var newViewMode = $container.hasClass('grid-view') ? 'list-view' : 'grid-view';

                // Update the class for task container
                $container.removeClass('grid-view list-view').addClass(newViewMode);

                // Store the updated view mode in localStorage
                localStorage.setItem('taskViewMode', newViewMode);

                // Update button text/icon based on the new view mode
                updateButtonText(newViewMode);
            });

            // Function to update button text/icon based on the view mode
            function updateButtonText(mode) {
                if (mode === 'grid-view') {
                    $button.html('<span class="tf-icon ti ti-list ti-xs me-1" title="Switch to List View"></span>');
                } else {
                    $button.html('<span class="tf-icon ti ti-layout-2 ti-xs me-1" title="Switch to Grid View"></span>');
                }
            }
        });
    </script>
@endsection
