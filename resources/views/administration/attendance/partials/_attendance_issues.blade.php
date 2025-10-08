<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex">
            <small class="card-text text-uppercase">{{ ___('Attendance Issues') }}</small>
        </div>
        <ul class="timeline mb-0 pb-1 mt-4">
            @forelse ($attendance->issues as $key => $issue)
                @php
                    switch ($issue->status) {
                        case 'Approved':
                            $color = 'success';
                            break;

                        case 'Rejected':
                            $color = 'danger';
                            break;

                        default:
                            $color = 'primary';
                            break;
                    }
                @endphp
                <li class="timeline-item ps-4 {{ $loop->last ? 'border-transparent' : 'border-left-dashed pb-1' }}">
                    <span class="timeline-indicator-advanced timeline-indicator-{{ $color }}">
                        <i class="ti ti-bell-question"></i>
                    </span>
                    <div class="timeline-event px-0 pb-0">
                        <div class="timeline-header">
                            <small class="text-capitalize fw-bold" title="{{ ___('Click To See Details') }}">
                                <a href="{{ route('administration.attendance.issue.show', ['issue' => $issue]) }}" target="_blank" class="text-{{ $color }}">{{ $issue->title }}</a>
                            </small>
                        </div>
                        <small class="text-muted mb-0">
                            {{ show_date_time($issue->created_at) }}
                        </small>
                        <h6 class="mb-1 mt-1">
                            <span class="badge bg-{{ $color }}">{{ $issue->status }}</span>
                        </h6>
                    </div>
                </li>
            @empty
                <div class="text-center text-bold text-muted fs-2">{{ ___('No Issues') }}</div>
            @endforelse
        </ul>
    </div>
</div>
