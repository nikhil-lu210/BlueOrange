@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Event Management'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />

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
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('All Events') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Event Management') }}</li>
    <li class="breadcrumb-item active">{{ __('All Events') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <form action="{{ route('administration.event.index') }}" method="get" autocomplete="off">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="organizer_id" class="form-label">Select Organizer</label>
                            <select name="organizer_id" id="organizer_id" class="select2 form-select @error('organizer_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->organizer_id) ? 'selected' : '' }}>Select Organizer</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $organizer)
                                            <option value="{{ $organizer->id }}" {{ $organizer->id == request()->organizer_id ? 'selected' : '' }}>
                                                {{ get_employee_name($organizer) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('organizer_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-2">
                            <label for="event_type" class="form-label">Event Type</label>
                            <select name="event_type" id="event_type" class="select2 form-select @error('event_type') is-invalid @enderror">
                                <option value="">All Types</option>
                                <option value="meeting" {{ request()->event_type == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                <option value="training" {{ request()->event_type == 'training' ? 'selected' : '' }}>Training</option>
                                <option value="celebration" {{ request()->event_type == 'celebration' ? 'selected' : '' }}>Celebration</option>
                                <option value="conference" {{ request()->event_type == 'conference' ? 'selected' : '' }}>Conference</option>
                                <option value="workshop" {{ request()->event_type == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="other" {{ request()->event_type == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="select2 form-select @error('status') is-invalid @enderror">
                                <option value="">All Status</option>
                                <option value="draft" {{ request()->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request()->status == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="cancelled" {{ request()->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ request()->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-2">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" value="{{ request()->start_date ?? old('start_date') }}" class="form-control" />
                        </div>

                        <div class="mb-3 col-md-2">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" value="{{ request()->end_date ?? old('end_date') }}" class="form-control" />
                        </div>

                        <div class="mb-3 col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->organizer_id || request()->event_type || request()->status || request()->start_date || request()->end_date)
                            <a href="{{ route('administration.event.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        @can ('Event Create')
                            <a href="{{ route('administration.event.create') }}" class="btn btn-success">
                                <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                                Create Event
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Calendar View -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-calendar me-2"></i>
                    Event Calendar
                </h5>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Events Table -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>
                    Events List
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="events-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Event Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Location</th>
                                <th>Organizer</th>
                                <th>Status</th>
                                <th>Participants</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td>
                                    <strong>{{ $event->title }}</strong>
                                    @if($event->is_all_day)
                                        <span class="badge bg-info ms-1">All Day</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge event-type-badge" style="background-color: {{ $event->color }}">
                                        {{ $event->event_type_label }}
                                    </span>
                                </td>
                                <td>{{ $event->formatted_start_date_time }}</td>
                                <td>{{ $event->formatted_end_date_time }}</td>
                                <td>{{ $event->location ?? 'N/A' }}</td>
                                <td>
                                    @if($event->organizer)
                                        {{ get_employee_name($event->organizer) }}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    {{ $event->current_participants }}
                                    @if($event->max_participants)
                                        / {{ $event->max_participants }}
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can ('Event Read')
                                            <a href="{{ route('administration.event.show', ['event' => $event]) }}"
                                            class="btn btn-sm btn-info"
                                            title="View">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        @endcan
                                        @if($event->organizer_id === auth()->id())
                                            @can ('Event Update')
                                                <a href="{{ route('administration.event.edit', ['event' => $event]) }}"
                                                class="btn btn-sm btn-warning"
                                                title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endcan

                                            @can ('Event Delete')
                                                <a href="{{ route('administration.event.destroy', ['event' => $event]) }}"
                                                class="btn btn-sm btn-danger confirm-danger"
                                                title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External JS  --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>

    {{-- FullCalendar JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            // Initialize DataTable
            $('#events-table').DataTable({
                "pageLength": 25,
                "order": [[2, "asc"]],
                "responsive": true,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });

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
                editable: true,
                eventDisplay: 'block',
                displayEventTime: false,
                eventContent: function(arg) {
                    // Repeat the title on each segment/day cell
                    var titleEl = document.createElement('div');
                    titleEl.className = 'fc-event-title fc-sticky';
                    titleEl.textContent = arg.event.title || '';
                    return { domNodes: [titleEl] };
                },
                eventDrop: function(info) {
                    const ev = info.event;
                    console.log(ev.id);
                    const isAllDay = ev.allDay === true;
                    const start = ev.start;
                    let end = ev.end || start;

                    // Compute dates (adjust exclusive end for all-day)
                    const startDate = start.toISOString().slice(0, 10);
                    let endDate;
                    if (isAllDay) {
                        const tmp = new Date(end);
                        tmp.setDate(tmp.getDate() - 1);
                        endDate = tmp.toISOString().slice(0, 10);
                    } else {
                        endDate = end.toISOString().slice(0, 10);
                    }

                    $.ajax({
                        url: '{{ route("administration.event.updateDateTime", "") }}/' + ev.id,
                        method: 'POST',
                        data: {
                            start_date: startDate,
                            end_date: endDate,
                            start_time: isAllDay ? null : start.toTimeString().split(' ')[0],
                            end_time: isAllDay ? null : end.toTimeString().split(' ')[0],
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message || 'Event updated successfully',
                                    toast: true,
                                    position: 'top-end',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: response.message || 'Failed to update event',
                                    toast: true,
                                    position: 'top-end',
                                    timer: 2500,
                                    showConfirmButton: false,
                                    timerProgressBar: true
                                });
                                info.revert();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to update event',
                                text: (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : undefined,
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });
                            info.revert();
                        }
                    });
                },
                eventClick: function(info) {
                    // Navigate to event details
                    window.location.href = info.event.url;
                }
            });
            calendar.render();

            // Confirm delete using SweetAlert2
            $(document).on('click', '.confirm-delete', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                let title = $(this).closest('tr').find('td').eq(0).find('strong').text().trim();
                if (!title) title = 'this event';
                Swal.fire({
                    title: 'Delete Event?',
                    text: 'Are you sure you want to delete "' + title + '"? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });

            // Show toast notifications for flash messages
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: @json(session('success')),
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
            });
            @endif
            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: @json(session('error')),
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true
            });
            @endif
        });
    </script>
@endsection
