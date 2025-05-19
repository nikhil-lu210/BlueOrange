<div class="card mb-4">
    <div class="card-header header-elements pt-3 pb-3">
        <h5 class="mb-0">{{ __('Sub Tasks') }}</h5>
        <div class="card-header-elements ms-auto">
            <button id="toggleView" class="btn btn-icon btn-outline-dark" title="Switch View">
                <span class="tf-icon ti ti-layout-2"></span>
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="demo-inline-spacing mt-1">
                    <div id="taskContainer" class="list-group list-view">
                        @forelse ($task->sub_tasks as $key => $task)
                            <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="list-group-item d-flex justify-content-between btn-outline-{{ getColor($task->status) }} bg-label-{{ getColor($task->status) }} mb-3" style="border-radius: 5px;">
                                <div class="li-wrapper d-flex justify-content-start align-items-center" title="{{ $task->title }}">
                                    <div class="list-content">
                                        <h6 class="mb-1 text-dark text-bold">{{ show_content($task->title, 30) }}</h6>
                                        <small class="text-muted">Task ID: <b>{{ $task->taskid }}</b></small>
                                    </div>
                                </div>
                                <div class="li-wrapper d-flex justify-content-start align-items-center" title="Task Deadline">
                                    <div class="list-content">
                                        @if (!is_null($task->deadline))
                                            <b class="text-dark">{{ show_date($task->deadline) }}</b>
                                        @else
                                            <span class="badge bg-success">Ongoing Task</span>
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
                            </a>
                        @empty
                            <h4 class="text-center text-muted mt-3">{{ __('No Tasks Available') }}</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


