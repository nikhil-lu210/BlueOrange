<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex">
            <small class="card-text text-uppercase">Penalties</small>
            <div class="ms-auto" style="margin-top: -5px;">
                @if ($attendance->penalties->count() > 0 && $attendance->total_penalty_time_formatted)
                    <small class="badge bg-danger" title="Total Penalty Time">
                        {{ $attendance->total_penalty_time_formatted }}
                    </small>
                @endif
            </div>
        </div>
        <ul class="timeline mb-0 pb-1 mt-4">
            @forelse ($attendance->penalties as $key => $penalty)
                <li class="timeline-item ps-4 {{ $loop->last ? 'border-transparent' : 'border-left-dashed pb-1' }}">
                    <span class="timeline-indicator-advanced timeline-indicator-danger">
                        <i class="ti ti-alert-triangle"></i>
                    </span>
                    <div class="timeline-event px-0 pb-0">
                        <div class="timeline-header">
                            <small class="text-capitalize fw-bold" title="Click To See Details">
                                <a href="{{ route('administration.penalty.show', ['penalty' => $penalty]) }}" target="_blank" class="text-danger">{{ $penalty->type }}</a>
                            </small>
                        </div>
                        <small class="text-muted mb-0">
                            {{ show_date_time($penalty->created_at) }}
                        </small>
                        <h6 class="mb-1 mt-1">
                            <span class="badge bg-danger">{{ $penalty->total_time_formatted }}</span>
                        </h6>
                    </div>
                </li>
            @empty
                <div class="text-center text-bold text-muted fs-2">No Penalties</div>
            @endforelse
        </ul>
    </div>
</div>
