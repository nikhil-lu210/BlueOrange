<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="showHolidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Holiday Details</h3>
                    <p class="text-muted">Details of {{ 'holiday_name_here' }}</p>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-info-circle text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Title:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span>{{ 'Holiday_Title_Here' }}</span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-calendar-event text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Date:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span>{{ 'Holiday_Date_Here' }}</span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-file-description text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Description:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span>{{ 'Holiday_Description_Here' }}</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->