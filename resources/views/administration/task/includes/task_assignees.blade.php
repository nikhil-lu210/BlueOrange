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
                            <a class="dropdown-item" href="javascript:void(0);">
                                <i class="ti ti-plus me-1 fs-5" style="margin-top: -2px;"></i>
                                Add Assignees
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item text-danger confirm-danger" href="javascript:void(0);">
                                <i class="ti ti-x me-1 fs-5" style="margin-top: -2px;"></i>
                                Remove Assignees
                            </a>
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
                                    <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle">
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