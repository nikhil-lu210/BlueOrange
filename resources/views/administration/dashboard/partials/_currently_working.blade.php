<div class="col-md-4">
    <div class="card card-action card-border-shadow-success mb-1">
        <div class="card-header collapsed">
            <div class="card-action-title">{{ ___('Currently Working') }}</div>
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
                    @forelse($currentlyWorkingUsers as $workingUser)
                        @php
                            // Get the current attendance (should always exist due to our service filtering)
                            $currentAttendance = $workingUser->attendances->first();
                            $clockInTime = $currentAttendance ? show_time($currentAttendance->clock_in) : '';
                            $tooltipText = ($workingUser->employee->alias_name ?? $workingUser->name) . ($clockInTime ? ' (' . $clockInTime . ')' : '');

                            // Type
                            $type = $currentAttendance->type ?? 'Regular';
                            $typeColor = $type === 'Regular' ? 'primary' : 'warning';
                        @endphp
                        <div class="avatar me-2 mb-2 border border-3 rounded border-{{ $typeColor }}" title="{{ $tooltipText }}">
                            @if($workingUser->getFirstMediaUrl('avatar'))
                                <img src="{{ $workingUser->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $workingUser->name }}" class="rounded" />
                            @else
                                <span class="avatar-initial rounded bg-label-primary">{{ substr($workingUser->name, 0, 1) }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center w-100 py-3">
                            <p class="mb-0 text-muted">{{ ___('No users currently working')}}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
