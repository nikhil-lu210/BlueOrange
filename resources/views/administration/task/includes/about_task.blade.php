<div class="card mb-4">
    <div class="card-body">
        <small class="card-text text-uppercase">About Task</small>
        <ul class="list-unstyled mb-0 mt-3">
            <li class="d-flex align-items-center mb-3">
                <i class="ti ti-hash text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Task-ID:</span> 
                <span class="text-bold text-primary">{{ $task->taskid }}</span>
            </li>
            <li class="d-flex align-items-center mb-3">
                <i class="ti ti-user-edit text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Creator:</span> 
                <span>{{ $task->creator->name }}</span>
            </li>
            <li class="d-flex align-items-center mb-3">
                <i class="ti ti-clock-up text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Created At:</span> 
                <span class="text-capitalize">{{ date_time_ago($task->created_at) }}</span>
            </li>
            <li class="d-flex align-items-center mb-3">
                <i class="ti ti-hourglass-off text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Deadline:</span> 
                <span class="text-capitalize">{{ date_time_ago($task->deadline) }}</span>
            </li>
            <li class="d-flex align-items-center mb-3">
                <i class="ti ti-check text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Status:</span> 
                <span>{!! show_status($task->status) !!}</span>
            </li>
            <li class="d-flex align-items-center mb-3">
                <i class="ti ti-checks text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Priority:</span> 
                <span class="text-bold">{{ $task->priority }}</span>
            </li>
        </ul>
    </div>
</div>