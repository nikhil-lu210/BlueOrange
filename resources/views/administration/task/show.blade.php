@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Task Details'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />

    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Task Comments CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/custom_css/comment_reply.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .btn-block {
        width: 100%;
    }
    .img-thumbnail {
        padding: 3px;
        border: 3px solid var(--bs-border-color);
        border-radius: 5px;
    }
    .file-thumbnail-container {
        width: 150px;
        height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .file-thumbnail-container .file-name {
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    </style>
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
    <b class="text-uppercase">{{ __('Task Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Task') }}</li>
    @canany (['Task Create', 'Task Update', 'Task Delete'])
        <li class="breadcrumb-item">
            <a href="{{ route('administration.task.index') }}">{{ __('All Tasks') }}</a>
        </li>
    @else
        <li class="breadcrumb-item">
            <a href="{{ route('administration.task.my') }}">{{ __('My Tasks') }}</a>
        </li>
    @endcanany
    <li class="breadcrumb-item">{{ __('Task Details') }}</li>
    <li class="breadcrumb-item active">{{ $task->taskid }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card border-bottom-{{ getColor($task->priority) }} mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-grow-1 mt-4">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-0 text-capitalize">{{ $task->title }}</h4>
                            <small class="text-muted">{{ $task->taskid }}</small>
                            @if ($task->users)
                                <div class="d-flex align-items-center">
                                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 zindex-2 mt-1">
                                        @foreach ($task->users as $user)
                                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->alias_name }}" class="avatar avatar-sm pull-up">
                                                @if ($user->hasMedia('avatar'))
                                                    <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
                                                @else
                                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="rounded-circle">
                                                @endif
                                            </li>
                                        @endforeach
                                        <li class="m-2">
                                            <small class="text-muted">Total {{ count($task->users) }} Assignees</small>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="actions d-flex">
                            @can('Task Create')
                                @if (auth()->user()->id == $task->creator->id)
                                    <a href="{{ route('administration.task.create', ['parent_task_id' => $task->id]) }}" target="_blank" class="btn btn-label-dark btn-icon waves-effect me-1" title="{{ __('Create Sub-Task') }}">
                                        <span class="tf-icon ti ti-plus"></span>
                                    </a>

                                    <a href="{{ route('administration.task.history.show', ['task' => $task]) }}" target="_blank" class="btn btn-label-success btn-icon waves-effect me-1" title="{{ __('Task History') }}">
                                        <span class="tf-icon ti ti-history"></span>
                                    </a>

                                    <a href="javascript:void(0);" class="btn btn-label-primary btn-icon waves-effect me-1" data-bs-toggle="modal" data-bs-target="#taskStatusModal" title="{{ __('Update Task Status') }}">
                                        <i class="ti ti-check"></i>
                                    </a>

                                    <a href="{{ route('administration.task.edit', ['task' => $task]) }}" class="btn btn-label-info btn-icon waves-effect me-1" title="{{ __('Edit Task') }}">
                                        <span class="tf-icon ti ti-edit"></span>
                                    </a>

                                    <a href="{{ route('administration.task.destroy', ['task' => $task]) }}" class="btn btn-label-danger btn-icon waves-effect me-1 confirm-danger" title="{{ __('Delete Task') }}">
                                        <span class="tf-icon ti ti-trash"></span>
                                    </a>
                                @endif
                            @endcan

                            @php
                                $userPivot = $task->users->where('id', auth()->user()->id)->first()?->pivot;
                                $hasUnderstood = $userPivot?->has_understood;
                            @endphp
                            @can ('Task Read')
                                @if ($task->users->contains(auth()->user()->id) && ($task->status == 'Active' || $task->status == 'Running'))
                                    {{-- Show Start or Stop button only if user has understood --}}
                                    @if ($hasUnderstood == true)
                                        @if (!$isWorking)
                                            <form action="{{ route('administration.task.history.start', ['task' => $task]) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-success me-1" title="Start Working On This Task">
                                                    <i class="ti ti-clock-check me-1" style="margin-top: -3px;"></i>
                                                    Start
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-danger me-1" title="Stop Working On This Task" data-bs-toggle="modal" data-bs-target="#stopTaskModal">
                                                <i class="ti ti-clock-x me-1" style="margin-top: -3px;"></i>
                                                Stop
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        {{-- <!-- About Task --> --}}
        @include('administration.task.includes.about_task')

        {{-- Task Details --}}
        @include('administration.task.includes.task_details')

        {{-- <!-- Task Files --> --}}
        @include('administration.task.includes.task_files')

        {{-- <!-- Sub Tasks --> --}}
        @include('administration.task.includes.sub_tasks')

        {{-- Task Assignees --}}
        @include('administration.task.includes.task_assignees')

        {{-- Task History Summary --}}
        @if ($task->histories->count() > 0)
            @include('administration.task.includes.task_history_summary')
        @endif
    </div>

    <div class="col-md-5">
        @if ($task->users->contains(auth()->user()->id))
            <div class="card mb-4 {{ $hasUnderstood == true ? 'd-none' : '' }}">
                <div class="card-body">
                    {{-- Buttons for marking as understood / not understood --}}
                    @if (is_null($hasUnderstood))
                        <a href="{{ route('administration.task.history.understood', ['task' => $task, 'status' => 'true']) }}" class="btn btn-label-success btn-block btn-xl me-1 text-capitalize confirm-success" title="Mark as Understood">
                            <i class="ti ti-check me-1" style="margin-top: -3px;"></i>
                            Yes, I have Understood The Task
                        </a>

                        <a href="{{ route('administration.task.history.understood', ['task' => $task, 'status' => 'false']) }}" class="btn btn-label-danger btn-block btn-xl me-1 text-capitalize mt-3 confirm-danger" title="Mark as Not Understood">
                            <i class="ti ti-x me-1" style="margin-top: -3px;"></i>
                            No, I Did Not Understand The Task
                        </a>
                    @elseif ($hasUnderstood == false)
                        <a href="{{ route('administration.task.history.understood', ['task' => $task, 'status' => 'true']) }}" class="btn btn-label-success btn-block btn-xl me-1 text-capitalize confirm-success" title="Mark as Understood">
                            <i class="ti ti-check me-1" style="margin-top: -3px;"></i>
                            Yes, Now I have Understood The Task
                        </a>
                    @endif
                </div>
            </div>
        @endif

        {{-- Task Comments --}}
        @include('administration.task.includes.task_comments')
    </div>
    {{-- Task Details --}}
</div>
<!-- End row -->

{{-- Page Modal --}}
@if ($lastActiveTaskHistory)
    @include('administration.task.modals.task_stop')
@endif

@if ($task->creator_id == auth()->user()->id)
    @include('administration.task.modals.task_status')
@endif

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>

    {{-- Bootstrap Select --}}
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Task Comments JS --}}
    <script src="{{ asset('assets/js/custom_js/task/task_comments.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                ["link"],
                [{ header: "1" }, { header: "2" }, "blockquote"],
                [{ list: "ordered" }, { list: "bullet" }],
            ];

            // Task Comments are handled by external JS file

            var taskStopNoteEditor = new Quill("#taskStopNoteEditor", {
                bounds: "#taskStopNoteEditor",
                placeholder: "Ex: Completed the task.",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old note if validation fails
            @if(old('note'))
                taskStopNoteEditor.root.innerHTML = {!! json_encode(old('note')) !!};
            @endif

            $('#stopTaskForm').on('submit', function() {
                $('#note-input').val(taskStopNoteEditor.root.innerHTML);
            });

            // Lightbox configuration
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': "Image %1 of %2"
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
                    $button.html('<span class="tf-icon ti ti-list ti-xs" title="Switch to List View"></span>');
                } else {
                    $button.html('<span class="tf-icon ti ti-layout-2 ti-xs" title="Switch to Grid View"></span>');
                }
            }
        });
    </script>
@endsection



