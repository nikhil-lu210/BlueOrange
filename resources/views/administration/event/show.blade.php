@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', $event->title)

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .event-header {
            /* background: linear-gradient(135deg, {{ $event->color }} 0%, {{ App\Helpers\ColorHelper::adjustBrightness($event->color, -20) }} 100%); */
            /* background: linear-gradient(135deg, #796ef0 0%, #4f44c9 100%); */
            background: white;
            color: #212529;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-bottom: 3px solid {{ $event->color }};
        }
        .event-info-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .event-info-header {
            background: #f8f9fa;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
        }
        .event-info-body {
            padding: 1.5rem;
        }
        .participant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .event-type-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ $event->title }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Event Management') }}</li>
    <li class="breadcrumb-item"><a href="{{ route('administration.event.index') }}">{{ __('All Events') }}</a></li>
    <li class="breadcrumb-item active">{{ $event->title }}</li>
@endsection

@section('content')

<!-- Event Header -->
<div class="event-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="mb-2">{{ $event->title }}</h1>
            <p class="mb-3">{{ $event->description }}</p>
            <div class="d-flex gap-3">
                <span class="badge event-type-badge bg-dark text-white">
                    {{ $event->event_type_label }}
                </span>
                @if($event->is_all_day)
                    <span class="badge bg-info">All Day Event</span>
                @endif
                @if($event->is_public)
                    <span class="badge bg-success">Public Event</span>
                @else
                    <span class="badge bg-warning">Private Event</span>
                @endif
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex flex-column gap-2">
                @can ('Event Update')
                    <a href="{{ route('administration.event.edit', ['event' => $event]) }}" 
                    class="btn btn-light">
                        <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                        Edit Event
                    </a>
                @endcan
                <a href="{{ route('administration.event.index') }}" 
                   class="btn btn-outline-dark">
                    <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                    Back to Events
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Event Details -->
    <div class="col-md-8">
        <!-- Date & Time Information -->
        <div class="event-info-card">
            <div class="event-info-header">
                <h5 class="mb-0">
                    <i class="ti ti-calendar me-2"></i>
                    Date & Time
                </h5>
            </div>
            <div class="event-info-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Start Date & Time</label>
                            <p class="mb-0">{{ $event->formatted_start_date_time }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">End Date & Time</label>
                            <p class="mb-0">{{ $event->formatted_end_date_time }}</p>
                        </div>
                    </div>
                </div>
                @if($event->location)
                <div class="mb-3">
                    <label class="form-label fw-bold">Location</label>
                    <p class="mb-0">
                        <i class="ti ti-map-pin me-2"></i>
                        {{ $event->location }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Event Description -->
        @if($event->description)
        <div class="event-info-card">
            <div class="event-info-header">
                <h5 class="mb-0">
                    <i class="ti ti-file-text me-2"></i>
                    Description
                </h5>
            </div>
            <div class="event-info-body">
                <p class="mb-0">{{ $event->description }}</p>
            </div>
        </div>
        @endif

        <!-- Participants -->
        <div class="event-info-card">
            <div class="event-info-header">
                <h5 class="mb-0">
                    <i class="ti ti-users me-2"></i>
                    Participants
                    <span class="badge bg-primary ms-2">
                        {{ $event->current_participants }}
                        @if($event->max_participants)
                            / {{ $event->max_participants }}
                        @endif
                    </span>
                </h5>
            </div>
            <div class="event-info-body">
                @if($event->participants->count() > 0)
                    <div class="row">
                        @foreach($event->participants as $participant)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($participant->user->getFirstMediaUrl('avatar'))
                                    <img src="{{ $participant->user->getFirstMediaUrl('avatar') }}" 
                                         alt="Avatar" class="participant-avatar me-3">
                                @else
                                    <div class="participant-avatar me-3 bg-secondary d-flex align-items-center justify-content-center">
                                        <i class="ti ti-user text-white"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ get_employee_name($participant->user) }}</h6>
                                    <small class="text-muted">
                                        @php
                                            $statusColors = [
                                                'invited' => 'bg-secondary',
                                                'accepted' => 'bg-success',
                                                'declined' => 'bg-danger',
                                                'maybe' => 'bg-warning',
                                                'attended' => 'bg-info',
                                                'no_show' => 'bg-dark'
                                            ];
                                        @endphp
                                        <span class="badge status-badge {{ $statusColors[strtolower($participant->status)] ?? 'bg-secondary' }}">
                                            {{ $participant->status_label }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No participants added yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Event Sidebar -->
    <div class="col-md-4">
        <!-- Event Status -->
        <div class="event-info-card">
            <div class="event-info-header">
                <h5 class="mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    Event Status
                </h5>
            </div>
            <div class="event-info-body">
                @php
                    $statusColors = [
                        'Draft' => 'bg-secondary',
                        'Published' => 'bg-success',
                        'Cancelled' => 'bg-danger',
                        'Completed' => 'bg-info'
                    ];
                @endphp
                <span class="badge status-badge {{ $statusColors[$event->status] ?? 'bg-secondary' }}">
                    {{ $event->status_label }}
                </span>
            </div>
        </div>

        <!-- Organizer Information -->
        <div class="event-info-card">
            <div class="event-info-header">
                <h5 class="mb-0">
                    <i class="ti ti-user me-2"></i>
                    Organizer
                </h5>
            </div>
            <div class="event-info-body">
                @if($event->organizer)
                    <div class="d-flex align-items-center">
                        @if($event->organizer->getFirstMediaUrl('avatar'))
                            <img src="{{ $event->organizer->getFirstMediaUrl('avatar') }}" 
                                 alt="Organizer Avatar" class="participant-avatar me-3">
                        @else
                            <div class="participant-avatar me-3 bg-primary d-flex align-items-center justify-content-center">
                                <i class="ti ti-user text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ get_employee_name($event->organizer) }}</h6>
                            <small class="text-muted">{{ $event->organizer->email }}</small>
                        </div>
                    </div>
                @else
                    <p class="text-muted mb-0">Organizer not found.</p>
                @endif
            </div>
        </div>

        <!-- Event Settings -->
        <div class="event-info-card">
            <div class="event-info-header">
                <h5 class="mb-0">
                    <i class="ti ti-settings me-2"></i>
                    Event Settings
                </h5>
            </div>
            <div class="event-info-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Event Color</label>
                    <div class="d-flex align-items-center">
                        <div class="color-preview me-2" style="background-color: {{ $event->color }}; width: 20px; height: 20px; border-radius: 4px;"></div>
                        <span>{{ $event->color }}</span>
                    </div>
                </div>
                
                @if($event->reminder_before)
                <div class="mb-3">
                    <label class="form-label fw-bold">Reminder</label>
                    <p class="mb-0">{{ $event->reminder_before }} {{ $event->reminder_unit_label }} before</p>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Created</label>
                    <p class="mb-0">{{ $event->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Last Updated</label>
                    <p class="mb-0">{{ $event->updated_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="event-info-card">
            <div class="event-info-header">
                <h5 class="mb-0">
                    <i class="ti ti-zap me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="event-info-body">
                <div class="d-grid gap-2">
                    @if($event->organizer_id === auth()->id())
                        @can ('Event Update')
                            <a href="{{ route('administration.event.edit', ['event' => $event]) }}" 
                            class="btn btn-warning">
                                <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                                Edit Event
                            </a>
                        @endcan
                        @can ('Event Delete')
                            <a href="{{ route('administration.event.destroy', ['event' => $event]) }}" 
                            class="btn btn-danger confirm-danger">
                                <span class="tf-icon ti ti-trash ti-xs me-1"></span>
                                Delete Event
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    <script>
        window.AppColorHelper = {
            adjustBrightness: function(hex, percent) {
                // Client-side mirror for any dynamic JS usage if needed later
                hex = hex.replace('#','');
                if (hex.length === 3) {
                    hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
                }
                var r = parseInt(hex.substr(0,2), 16),
                    g = parseInt(hex.substr(2,2), 16),
                    b = parseInt(hex.substr(4,2), 16);
                var factor = Math.max(-100, Math.min(100, percent)) / 100.0;
                r = Math.max(0, Math.min(255, Math.round(r + 255*factor)));
                g = Math.max(0, Math.min(255, Math.round(g + 255*factor)));
                b = Math.max(0, Math.min(255, Math.round(b + 255*factor)));
                return '#' + r.toString(16).padStart(2,'0') + g.toString(16).padStart(2,'0') + b.toString(16).padStart(2,'0');
            }
        };
    </script>
    {{--  External JS  --}}
@endsection

@section('custom_script')
    <script>
        // Add any custom JavaScript here
    </script>
@endsection
