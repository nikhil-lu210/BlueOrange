<div class="col-md-4">
    <div class="card card-action card-border-shadow-danger mb-1">
        <div class="card-header">
            <div class="card-action-title">{{ __('Absent Today') }}</div>
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
        <div class="collapse show">
            <div class="card-body pt-0">
                <div class="d-flex align-items-center flex-wrap">
                    @forelse($absentUsers as $absentUser)
                        <div class="avatar me-2 mb-2 avatar-busy" title="{{ $absentUser->employee->alias_name ?? $absentUser->name }}">
                            @if($absentUser->getFirstMediaUrl('avatar'))
                                <img src="{{ $absentUser->getFirstMediaUrl('avatar') }}" alt="{{ $absentUser->name }}" class="rounded-circle" />
                            @else
                                <span class="avatar-initial rounded-circle bg-label-danger">{{ substr($absentUser->name, 0, 1) }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center w-100 py-3">
                            <p class="mb-0 text-muted">No absent users today</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
