<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <small class="card-text text-uppercase">{{ ___('Daily Break\'s Details') }}</small>
            <div class="ms-auto" style="margin-top: -5px;">
                @isset ($attendance->total_break_time)
                    <small class="badge bg-dark" title="{{ ___('Total Break Taken') }}">
                        {{ total_time($attendance->total_break_time) }}
                    </small>
                @endisset
                @isset ($attendance->total_over_break)
                    <small class="badge bg-danger" title="{{ ___('Total Over Break') }}">
                        {{ total_time($attendance->total_over_break) }}
                    </small>
                @endisset
            </div>
        </div>
        <ul class="timeline mb-0 pb-1 mt-4">
            @forelse ($attendance->daily_breaks as $key => $break)
                <li class="timeline-item ps-4 {{ $loop->last ? 'border-transparent' : 'border-left-dashed pb-1' }}">
                    <span class="timeline-indicator-advanced timeline-indicator-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">
                        <i class="ti ti-{{ $break->break_out_at ? 'clock-stop' : 'clock-play' }}"></i>
                    </span>
                    <div class="timeline-event px-0 pb-0">
                        <div class="timeline-header">
                            <small class="text-uppercase fw-medium" title="{{ ___('Click To See Details') }}">
                                <a href="{{ route('administration.daily_break.show', ['break' => $break]) }}" class="text-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">{{ $break->type }} Break</a>
                            </small>
                        </div>
                        <small class="text-muted mb-0">
                            {{ show_time($break->break_in_at) }}
                            @if (!is_null($break->break_out_at))
                                <span>to</span>
                                <span>{{ show_time($break->break_out_at) }}</span>
                            @else
                                -
                                <span class="text-danger">{{ ___('Break Running') }}</span>
                            @endif
                        </small>
                        <h6 class="mb-1">
                            @if (is_null($break->total_time))
                                <span class="text-danger">{{ ___('Break Running') }}</span>
                            @else
                                <span class="text-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">{{ total_time($break->total_time) }}</span>
                                @isset($break->over_break)
                                    <small class="text-danger text-bold mt-1" title="{{ ___('Over Break') }}">({{ total_time($break->over_break) }})</small>
                                @endisset
                            @endif
                        </h6>
                    </div>
                </li>
            @empty
                <div class="text-center text-bold text-muted fs-2">{{ ___('No Breaks') }}</div>
            @endforelse
        </ul>
    </div>
</div>
