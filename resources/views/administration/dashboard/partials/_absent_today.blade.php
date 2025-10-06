<div class="col-md-4">
    <div class="card card-action card-border-shadow-danger mb-1">
        <div class="card-header collapsed">
            <div class="card-action-title">{{ ___('Absent Today') }}</div>
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
                    @forelse($absentUsers as $absentUser)
                        <div class="avatar me-2 mb-2 border border-3 rounded border-danger" title="{{ $absentUser->employee->alias_name ?? $absentUser->name }}">
                            @if($absentUser->getFirstMediaUrl('avatar'))
                                <img src="{{ $absentUser->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $absentUser->name }}" class="rounded" />
                            @else
                                <span class="avatar-initial rounded bg-label-danger">{{ substr($absentUser->name, 0, 1) }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center w-100 py-3">
                            <p class="mb-0 text-muted">{{ ___('No absent users today') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
