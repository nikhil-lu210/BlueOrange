/**
 * Dashboard Calendar JavaScript
 * Handles the initialization and functionality of the dashboard calendar
 */
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('dashboard-calendar');

    if (!calendarEl) return;

    // Function to convert day names to day numbers (0=Sunday, 1=Monday, etc.)
    function dayNameToNumber(dayName) {
        const days = {
            'Sunday': 0,
            'Monday': 1,
            'Tuesday': 2,
            'Wednesday': 3,
            'Thursday': 4,
            'Friday': 5,
            'Saturday': 6
        };
        return days[dayName];
    }

    // Function to get business days (non-weekend days)
    function getBusinessDays(weekendDays) {
        // Create an array of all days [0,1,2,3,4,5,6]
        const allDays = [0, 1, 2, 3, 4, 5, 6];
        // Filter out weekend days to get business days
        return allDays.filter(day => !weekendDays.includes(day));
    }

    // Initialize calendar with default settings
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
        firstDay: 6, // Start week on Saturday (0=Sunday, 1=Monday, ..., 6=Saturday)
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5], // Default Monday - Friday (will be updated after fetching weekend days)
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
            url: dashboardCalendarConfig.eventsUrl,
            method: 'GET',
            failure: function() {
                alert('There was an error while fetching events!');
            }
        },
        eventClick: function(info) {
            // Don't show modal for weekend events
            if (info.event.extendedProps.type !== 'weekend') {
                showEventDetails(info.event);
            }
        },
        datesSet: function(info) {
            // Update the calendar title when the view changes
            updateCalendarTitle(info);
        }
    });

    calendar.render();

    // Fetch weekend days from the server and update the calendar
    fetch(dashboardCalendarConfig.weekendsUrl)
        .then(response => response.json())
        .then(data => {
            // Convert day names to day numbers
            const weekendDays = data.map(day => dayNameToNumber(day));

            // Update calendar business hours with non-weekend days
            const businessDays = getBusinessDays(weekendDays);
            calendar.setOption('businessHours', {
                daysOfWeek: businessDays,
                startTime: '09:00',
                endTime: '18:00',
            });
        })
        .catch(error => console.error('Error fetching weekend days:', error));

    // Force calendar to refetch events after initial load and apply initial filters
    setTimeout(() => {
        calendar.refetchEvents();
        // Apply initial filters after events are loaded
        setTimeout(() => {
            applyFilters();
        }, 500);
    }, 1000);

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
            link.href = dashboardCalendarConfig.taskUrl + '?taskid=' + event.extendedProps.taskid;
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
            // Add user name if it's not the current user's leave
            const userId = dashboardCalendarConfig.currentUserId;
            const eventUserId = event.extendedProps.user_id;
            const userName = event.extendedProps.user_name;

            if (eventUserId && eventUserId !== userId && userName) {
                detailsHtml += `<p><strong>Employee:</strong> ${userName}</p>`;
            }

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
