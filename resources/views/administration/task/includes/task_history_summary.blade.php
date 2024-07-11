<div class="card card-action mb-4">
    <div class="card-header align-items-center">
        <h5 class="card-action-title mb-0">Task History Summary</h5>
        <div class="card-action-element">
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots-vertical text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('administration.task.history.show', ['task' => $task]) }}">
                            <i class="ti ti-history me-1 fs-5" style="margin-top: -2px;"></i>
                            History Details
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <ul class="timeline ms-1 mb-0">
            @foreach ($task->histories as $history)
                <li class="timeline-item timeline-item-transparent {{ $loop->last ? 'border-transparent' : '' }}">
                    <span class="timeline-indicator-advanced timeline-indicator-primary">
                      <i class="ti ti-hash rounded-circle scaleX-n1-rtl"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header">
                            <h6 class="mb-0">{{ $history->user->name }}</h6>
                            <small class="text-muted">{{ $history->progress }}%</small>
                        </div>
                        <small class="text-muted"><span class="text-dark">Total Worked: </span>{{ total_time($history->total_worked) }}</small>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>    
</div>