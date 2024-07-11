<div class="card card-action mb-4">
    <div class="card-header align-items-center pb-3 pt-3">
        <h5 class="card-action-title mb-0">{{ $task->title }}</h5>
        <div class="card-action-element">
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots-vertical text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item text-dark" href="{{ route('administration.task.history.show', ['task' => $task]) }}">
                            <i class="ti ti-history me-1 fs-5" style="margin-top: -5px;"></i>
                            Task History
                        </a>
                    </li>
                    @if (auth()->user()->id == $task->creator->id)
                        <li>
                            <a class="dropdown-item text-primary" href="{{ route('administration.task.edit', ['task' => $task]) }}">
                                <i class="ti ti-edit me-1 fs-5" style="margin-top: -5px;"></i>
                                Edit Task
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item text-danger confirm-danger" href="{{ route('administration.task.destroy', ['task' => $task]) }}">
                                <i class="ti ti-trash me-1 fs-5" style="margin-top: -5px;"></i>
                                Delete Task
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body border-top pt-3 pb-3">
        <div class="task-details">
            {!! $task->description !!}
        </div>
    </div>
</div>