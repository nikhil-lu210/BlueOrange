<div class="col-md-4">
    <div class="card card-action card-border-shadow-warning mb-1">
        <div class="card-header collapsed">
            <div class="card-action-title">{{ __('On Leave Today') }}</div>
            <div class="card-action-element">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="javascript:void(0);" class="card-collapsible">
                            <i class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="collapse">
            <div class="card-body pt-0">
                <div class="d-flex align-items-center flex-wrap">
                    @forelse($onLeaveUsers as $leaveUser)
                        <div class="avatar me-2 mb-2 avatar-away" title="{{ $leaveUser->employee->alias_name ?? $leaveUser->name }}">
                            @if($leaveUser->getFirstMediaUrl('avatar'))
                                <img src="{{ $leaveUser->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $leaveUser->name }}" class="rounded-circle" />
                            @else
                                <span class="avatar-initial rounded-circle bg-label-warning">{{ substr($leaveUser->name, 0, 1) }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center w-100 py-3">
                            <p class="mb-0 text-muted">No users on leave today</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
