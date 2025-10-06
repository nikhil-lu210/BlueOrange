<div class="row mb-4">
    <div class="col-md-12">
        <div class="card card-action card-border-shadow-primary mb-1">
            <div class="card-header collapsed">
                <h5 class="card-action-title mb-0">{{ config('app.name') }} {{ ___('Calendar') }}</h5>
                <div class="card-action-element">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="javascript:void(0);" class="card-collapsible">
                                <i class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="collapse">
                <div class="card-header header-elements">
                    <div class="d-flex align-items-center">
                        <div class="btn-group me-3" role="group">
                            <button type="button" id="calendarPrevBtn" class="btn btn-sm btn-dark">
                                <i class="ti ti-chevron-left"></i>
                            </button>
                            <button type="button" id="calendarTodayBtn" class="btn btn-sm btn-dark text-uppercase">today</button>
                            <button type="button" id="calendarNextBtn" class="btn btn-sm btn-dark">
                                <i class="ti ti-chevron-right"></i>
                            </button>
                        </div>
                        <h5 class="mb-0" id="calendarTitle">Calendar</h5>
                    </div>
                    <div class="card-header-elements ms-auto">
                        <div class="btn-group" role="group">
                            <button type="button" id="calendarMonthBtn" class="btn btn-sm btn-label-dark text-capitalize active">month</button>
                            <button type="button" id="calendarWeekBtn" class="btn btn-sm btn-label-dark text-capitalize">week</button>
                            <button type="button" id="calendarDayBtn" class="btn btn-sm btn-label-dark text-capitalize">day</button>
                            <button type="button" id="calendarListBtn" class="btn btn-sm btn-label-dark text-capitalize">list</button>
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
                                        <span class="badge bg-warning">Tasks</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input calendar-filter" type="checkbox" id="filterHolidays" value="holiday" checked>
                                    <label class="form-check-label" for="filterHolidays">
                                        <span class="badge bg-primary">Holidays</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input calendar-filter" type="checkbox" id="filterLeaves" value="leave" checked>
                                    <label class="form-check-label" for="filterLeaves">
                                        <span class="badge bg-danger">Leaves</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input calendar-filter" type="checkbox" id="filterWeekends" value="weekend" checked>
                                    <label class="form-check-label" for="filterWeekends">
                                        <span class="badge bg-dark">Weekends</span>
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
</div>

<!-- Event Details Modal -->
<div class="modal fade" data-bs-backdrop="static" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsTitle">{{ ___('Event Details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="eventDetailsContent">
                    <!-- Event details will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">{{ ___('Close') }}</button>
                <a href="#" id="eventDetailsLink" class="btn btn-primary d-none">{{ ___('View Details') }}</a>
            </div>
        </div>
    </div>
</div>

<!-- Calendar is initialized from dashboard_calendar.js -->
