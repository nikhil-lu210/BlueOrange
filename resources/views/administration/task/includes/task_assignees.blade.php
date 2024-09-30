<div class="card card-action mb-4">
    <div class="card-header align-items-center">
        <h5 class="card-action-title mb-0">Task Assignees</h5>
        @if (auth()->user()->id == $task->creator->id) 
            <div class="card-action-element">
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-dots-vertical text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addTaskUsersModal">
                                <i class="ti ti-plus me-1 fs-5" style="margin-top: -2px;"></i>
                                Add Assignees
                            </button>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#removeTaskUserModal">
                                <i class="ti ti-x me-1 fs-5" style="margin-top: -2px;"></i>
                                Remove Assignees
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            @foreach ($task->users as $user) 
                <li class="mb-3">
                    <div class="d-flex align-items-start">
                        <div class="d-flex align-items-start">
                            <div class="avatar me-2">
                                @if ($user->hasMedia('avatar'))
                                    <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
                                @else
                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="rounded-circle">
                                @endif
                            </div>
                            <div class="me-2 ms-1">
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted fs-tiny">{{ show_date($user->pivot->created_at) }}</small>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <b class="text-muted">{{ $user->pivot->progress }}%</b>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>


{{-- Add Assignees Modal --}}
@include('administration.task.modals.add_assignees')

{{-- Remove Assignee Modal --}}
@include('administration.task.modals.remove_assignee')