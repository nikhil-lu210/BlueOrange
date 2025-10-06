/**
 * Dashboard Core JavaScript
 * Handles core dashboard functionality
 */

class DashboardCore {
    constructor() {
        this.config = this.getConfig();
        this.init();
    }

    getConfig() {
        return window.dashboardData || {};
    }

    init() {
        this.initLiveClock();
        this.initWorkingTimeCounter();
        this.initAttendanceHandlers();
        this.initModalHandlers();
        this.initTooltips();
    }

    // Live Clock
    initLiveClock() {
        const liveTimeElement = document.getElementById('liveTime');
        if (!liveTimeElement) return;

        const updateTime = () => {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour12: true,
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit'
            });
            liveTimeElement.textContent = timeString;
        };

        updateTime();
        setInterval(updateTime, 1000);
    }

    // Working Time Counter
    initWorkingTimeCounter() {
        const liveWorkingTimeElement = document.getElementById('liveWorkingTime');
        if (!liveWorkingTimeElement) return;

        const clockInAt = parseInt(liveWorkingTimeElement.dataset.clockInAt) * 1000;

        const updateWorkingTime = () => {
            const now = new Date().getTime();
            const elapsed = now - clockInAt;

            const hours = Math.floor(elapsed / (1000 * 60 * 60));
            const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);

            const formattedTime = [
                String(hours).padStart(2, '0'),
                String(minutes).padStart(2, '0'),
                String(seconds).padStart(2, '0')
            ].join(':');

            liveWorkingTimeElement.textContent = formattedTime;
        };

        updateWorkingTime();
        setInterval(updateWorkingTime, 1000);
    }

    // Attendance Form Handlers
    initAttendanceHandlers() {
        let submitting = false;

        const handleSubmit = (buttonClass, attendanceType) => {
            document.querySelectorAll(buttonClass).forEach(button => {
                button.addEventListener('click', function(e) {
                    if (submitting) {
                        e.preventDefault();
                        return;
                    }

                    submitting = true;
                    const form = this.closest('form');
                    const typeInput = form.querySelector('#attendanceType');

                    if (typeInput) {
                        typeInput.value = attendanceType;
                    }

                    this.disabled = true;
                    form.submit();
                });
            });
        };

        handleSubmit('.submit-regular', 'Regular');
        handleSubmit('.submit-overtime', 'Overtime');
    }

    // Modal Handlers
    initModalHandlers() {
        // Select2 initialization for modals (excluding employee info modal)
        document.addEventListener('shown.bs.modal', function(e) {
            const modal = e.target;
            
            // Skip employee info update modal to avoid conflicts with custom initialization
            if (modal.id === 'employeeInfoUpdateModal') {
                return;
            }
            
            modal.querySelectorAll('.select2').forEach(select => {
                if (!select.hasAttribute('data-select2-initialized')) {
                    $(select).select2({
                        dropdownParent: $(modal),
                        width: '100%'
                    });
                    select.setAttribute('data-select2-initialized', 'true');
                }
            });
        });

    }


    // Initialize Bootstrap tooltips
    initTooltips() {
        // Initialize all tooltips on the page
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.dashboardCore = new DashboardCore();
});
