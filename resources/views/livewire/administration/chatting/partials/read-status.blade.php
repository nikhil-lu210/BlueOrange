@if (!is_null($message->seen_at))
    <i class="ti ti-checks ti-xs me-1 text-success" data-bs-toggle="tooltip" title="Seen At: {{ show_time($message->seen_at) }}"></i>
@else
    <i class="ti ti-check ti-xs me-1"></i>
@endif
