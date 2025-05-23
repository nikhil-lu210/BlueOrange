<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header header-elements">
                <div class="d-flex align-items-center">
                    <div class="btn-group me-3" role="group">
                        <button type="button" id="calendarPrevBtn" class="btn btn-sm btn-outline-secondary">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                        <button type="button" id="calendarTodayBtn" class="btn btn-sm btn-outline-secondary">today</button>
                        <button type="button" id="calendarNextBtn" class="btn btn-sm btn-outline-secondary">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                    </div>
                    <h5 class="mb-0" id="calendarTitle">Calendar</h5>
                </div>
                <div class="card-header-elements ms-auto">
                    <div class="btn-group" role="group">
                        <button type="button" id="calendarMonthBtn" class="btn btn-sm btn-outline-secondary active">month</button>
                        <button type="button" id="calendarWeekBtn" class="btn btn-sm btn-outline-secondary">week</button>
                        <button type="button" id="calendarDayBtn" class="btn btn-sm btn-outline-secondary">day</button>
                        <button type="button" id="calendarListBtn" class="btn btn-sm btn-outline-secondary">list</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-check">
                                <input class="form-check-input calendar-filter" type="checkbox" id="filterTasks" value="task" checked>
                                <label class="form-check-label" for="filterTasks">
                                    <span class="badge bg-danger">Tasks</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input calendar-filter" type="checkbox" id="filterHolidays" value="holiday" checked>
                                <label class="form-check-label" for="filterHolidays">
                                    <span class="badge bg-info">Holidays</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input calendar-filter" type="checkbox" id="filterLeaves" value="leave" checked>
                                <label class="form-check-label" for="filterLeaves">
                                    <span class="badge bg-warning">Leaves</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input calendar-filter" type="checkbox" id="filterWeekends" value="weekend" checked>
                                <label class="form-check-label" for="filterWeekends">
                                    <span class="badge bg-secondary">Weekends</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="dashboard-calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsTitle">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="eventDetailsContent">
                    <!-- Event details will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="eventDetailsLink" class="btn btn-primary d-none">View Details</a>
            </div>
        </div>
    </div>
</div>

<!-- Include FullCalendar directly in this partial -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('dashboard-calendar');

    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: false, // We'll use our custom header
        height: 'auto',
        dayMaxEvents: 2,
        navLinks: true, // can click day/week names to navigate views
        selectable: true,
        nowIndicator: true, // Show a marker for current time in time views
        slotMinTime: '06:00:00', // Start time grid at 6 AM
        slotMaxTime: '22:00:00', // End time grid at 10 PM
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
            startTime: '09:00',
            endTime: '18:00',
        },
        titleFormat: {
            year: 'numeric',
            month: 'long'
        },
        views: {
            dayGridMonth: {
                titleFormat: { year: 'numeric', month: 'long' }
            },
            timeGridWeek: {
                titleFormat: { year: 'numeric', month: 'short', day: '2-digit' }
            },
            timeGridDay: {
                titleFormat: { year: 'numeric', month: 'long', day: '2-digit', weekday: 'long' }
            },
            listMonth: {
                titleFormat: { year: 'numeric', month: 'long' }
            }
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short'
        },
        events: {
            url: '{{ route("administration.dashboard.calendar.events") }}',
            method: 'GET',
            failure: function() {
                alert('There was an error while fetching events!');
            }
        },
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        datesSet: function(info) {
            // Update the calendar title when the view changes
            updateCalendarTitle(info);
        }
    });

    calendar.render();

    // Function to update calendar title
    function updateCalendarTitle(info) {
        const title = info.view.title;
        document.getElementById('calendarTitle').textContent = title;
    }

    // Custom header buttons - Navigation
    document.getElementById('calendarPrevBtn').addEventListener('click', function() {
        calendar.prev();
    });

    document.getElementById('calendarTodayBtn').addEventListener('click', function() {
        calendar.today();
    });

    document.getElementById('calendarNextBtn').addEventListener('click', function() {
        calendar.next();
    });

    // Custom header buttons - View types
    document.getElementById('calendarMonthBtn').addEventListener('click', function() {
        setActiveViewButton('calendarMonthBtn');
        calendar.changeView('dayGridMonth');
    });

    document.getElementById('calendarWeekBtn').addEventListener('click', function() {
        setActiveViewButton('calendarWeekBtn');
        calendar.changeView('timeGridWeek');
    });

    document.getElementById('calendarDayBtn').addEventListener('click', function() {
        setActiveViewButton('calendarDayBtn');
        calendar.changeView('timeGridDay');
    });

    document.getElementById('calendarListBtn').addEventListener('click', function() {
        setActiveViewButton('calendarListBtn');
        calendar.changeView('listMonth');
    });

    // Helper function to set active view button
    function setActiveViewButton(activeButtonId) {
        const viewButtons = [
            'calendarMonthBtn',
            'calendarWeekBtn',
            'calendarDayBtn',
            'calendarListBtn'
        ];

        viewButtons.forEach(buttonId => {
            const button = document.getElementById(buttonId);
            if (buttonId === activeButtonId) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    }

    // Event filtering
    const filterCheckboxes = document.querySelectorAll('.calendar-filter');
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            applyFilters();
        });
    });

    function applyFilters() {
        const selectedTypes = Array.from(filterCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        calendar.getEvents().forEach(event => {
            const eventType = event.extendedProps.type;
            if (selectedTypes.includes(eventType)) {
                event.setProp('display', 'auto');
            } else {
                event.setProp('display', 'none');
            }
        });
    }

    // Show event details in modal
    function showEventDetails(event) {
        const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
        const title = document.getElementById('eventDetailsTitle');
        const content = document.getElementById('eventDetailsContent');
        const link = document.getElementById('eventDetailsLink');

        title.textContent = event.title;

        let detailsHtml = `<p><strong>Date:</strong> ${moment(event.start).format('MMMM D, YYYY')}</p>`;

        const eventType = event.extendedProps.type;

        if (eventType === 'task') {
            detailsHtml += `
                <p><strong>Status:</strong> ${event.extendedProps.status}</p>
                <p><strong>Task ID:</strong> ${event.extendedProps.taskid}</p>
                <div class="mt-3">
                    <h6>Description:</h6>
                    <div class="p-2 border rounded">${event.extendedProps.description || 'No description available'}</div>
                </div>
            `;

            link.classList.remove('d-none');
            link.href = '{{ route("administration.task.index") }}?taskid=' + event.extendedProps.taskid;
            link.textContent = 'View Task';

        } else if (eventType === 'holiday') {
            detailsHtml += `
                <div class="mt-3">
                    <h6>Description:</h6>
                    <div class="p-2 border rounded">${event.extendedProps.description || 'No description available'}</div>
                </div>
            `;
            link.classList.add('d-none');

        } else if (eventType === 'leave') {
            detailsHtml += `
                <p><strong>Paid Leave:</strong> ${event.extendedProps.is_paid ? 'Yes' : 'No'}</p>
                <div class="mt-3">
                    <h6>Reason:</h6>
                    <div class="p-2 border rounded">${event.extendedProps.reason || 'No reason provided'}</div>
                </div>
            `;
            link.classList.add('d-none');

        } else if (eventType === 'weekend') {
            detailsHtml += `<p>Weekend day - No additional details available.</p>`;
            link.classList.add('d-none');
        }

        content.innerHTML = detailsHtml;
        modal.show();
    }
});
</script>
