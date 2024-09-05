<!-- Login Logout History Details Modal -->
<div class="modal fade" data-bs-backdrop="static" id="showHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Login Logout History Details</h3>
                    <p class="text-muted">Login Logout Details of <span class="text-bold text-primary user_name"></span></p>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-info-circle text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Name:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="user_name"></span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-calendar-event text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Logged In:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="login_time"></span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-calendar-event text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Logged Out:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="logout_time"></span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-wave-saw-tool text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Login IP:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <code class="login_ip"></code>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-wave-saw-tool text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Logout IP:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <code class="logout_ip"></code>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-file-description text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">User Agent:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="user_agent"></span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Details Modal -->
