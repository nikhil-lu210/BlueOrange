@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('My Events'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- FullCalendar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .event-type-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .calendar-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .fc-event {
            cursor: pointer;
        }
        .fc-event:hover {
            opacity: 0.8;
        }
        .event-card {
            transition: transform 0.2s ease-in-out;
        }
        .event-card:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('My Events') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Event Management') }}</li>
    <li class="breadcrumb-item active">{{ __('My Events') }}</li>
@endsection

@section('content')

<!-- Calendar View -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-calendar me-2"></i>
                    My Events Calendar
                </h5>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Events Grid -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>
                    My Events List
                </h5>
            </div>
            <div class="card-body">
                @if($events->count() > 0)
                    <div class="row">
                        @foreach($events as $event)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card event-card h-100">
                                <div class="card-header mb-2" style="background-color: white; border-bottom: 3px solid {{ $event->color }}; border-radius: 8px 8px 0 0;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-dark">{{ $event->title }}</h6>
                                        <span class="badge event-type-badge bg-dark text-white">
                                            {{ $event->event_type_label }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($event->description)
                                        <p class="card-text text-muted small mb-3">
                                            {{ Str::limit($event->description, 100) }}
                                        </p>
                                    @endif
                                    
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Start</small>
                                                <strong>{{ $event->start_date->format('M d, Y') }}</strong>
                                                @if($event->start_time && !$event->is_all_day)
                                                    <br><small>{{ $event->start_time->format('g:i A') }}</small>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">End</small>
                                                <strong>{{ $event->end_date->format('M d, Y') }}</strong>
                                                @if($event->end_time && !$event->is_all_day)
                                                    <br><small>{{ $event->end_time->format('g:i A') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($event->location)
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Location</small>
                                            <strong>{{ $event->location }}</strong>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Status</small>
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-secondary',
                                                'published' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                'completed' => 'bg-info'
                                            ];
                                        @endphp
                                        <span class="badge status-badge {{ $statusColors[$event->status] ?? 'bg-secondary' }}">
                                            {{ $event->status_label }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Participants</small>
                                        <strong>{{ $event->current_participants }}</strong>
                                        @if($event->max_participants)
                                            / {{ $event->max_participants }}
                                        @endif
                                    </div>

                                    @if($event->is_all_day)
                                        <div class="mb-3">
                                            <span class="badge bg-info">All Day Event</span>
                                        </div>
                                    @endif

                                    @if($event->is_public)
                                        <div class="mb-3">
                                            <span class="badge bg-success">Public Event</span>
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <span class="badge bg-warning">Private Event</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('administration.event.show', ['event' => $event]) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="ti ti-eye"></i> View
                                        </a>
                                        @if($event->organizer_id === auth()->id())
                                            <a href="{{ route('administration.event.edit', ['event' => $event]) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="ti ti-edit"></i> Edit
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-calendar-off text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No Events Found</h4>
                        <p class="text-muted">You don't have any events yet. Create your first event to get started!</p>
                        <a href="{{ route('administration.event.create') }}" class="btn btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Event
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External JS  --}}
    <!-- DataTables js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>

    {{-- FullCalendar JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            // Initialize FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: '{{ route("administration.event.calendar") }}',
                editable: false, // Users can't edit events from this view
                eventClick: function(info) {
                    // Navigate to event details
                    window.location.href = info.event.url;
                },
                eventDidMount: function(info) {
                    // Only show events that belong to the current user
                    // This will be filtered on the server side
                }
            });
            calendar.render();
        });
    </script>
@endsection
