<!-- Status Modal -->
<div class="modal fade" data-bs-backdrop="static" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">{{ __('Update Status') }}</h3>
                    <p class="text-muted">
                        Update Status Of <b class="text-dark">{{ $user->name }} ({{ $user->employee->alias_name }})</b>
                    </p>
                </div>
                <!-- Status form -->
                <form method="post" action="{{ route('administration.settings.user.status.update', ['user' => $user]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 col-md-12">
                        <label for="status" class="form-label">Select Status <strong class="text-danger">*</strong></label>
                        <div class="row mt-2">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-success">
                                    <label class="form-check-label custom-option-content" for="markAsActive">
                                        <span class="custom-option-body">
                                            <i class="ti ti-activity-heartbeat fs-1 text-success"></i>
                                            <span class="custom-option-title fs-4 text-bold">Active</span>
                                            <small>Mark Status As <b class="text-success">{{ __('Active') }}</b></small>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Active" id="markAsActive" required @checked($user->status === 'Active')/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-dark">
                                    <label class="form-check-label custom-option-content" for="markAsInactive">
                                        <span class="custom-option-body">
                                            <i class="ti ti-x fs-1 text-dark"></i>
                                            <span class="custom-option-title fs-4 text-bold">Inactive</span>
                                            <small>Mark Status As <b class="text-dark">{{ __('Inactive') }}</b></small>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Inactive" id="markAsInactive" required @checked($user->status === 'Inactive')/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-warning">
                                    <label class="form-check-label custom-option-content" for="markAsResigned">
                                        <span class="custom-option-body">
                                            <i class="ti ti-hand-stop fs-1 text-warning"></i>
                                            <span class="custom-option-title fs-4 text-bold">Resigned</span>
                                            <small>Mark Status As <b class="text-warning">{{ __('Resigned') }}</b></small>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Resigned" id="markAsResigned" required @checked($user->status === 'Resigned')/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-danger">
                                    <label class="form-check-label custom-option-content" for="markAsFired">
                                        <span class="custom-option-body">
                                            <i class="ti ti-ban fs-1 text-danger"></i>
                                            <span class="custom-option-title fs-4 text-bold">Fired</span>
                                            <small>Mark Status As <b class="text-danger">{{ __('Fired') }}</b></small>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Fired" id="markAsFired" required @checked($user->status === 'Fired')/>
                                    </label>
                                </div>
                            </div>
                        </div>                            
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Update Status
                        </button>
                    </div>
                </form>
                <!--/ Status form -->
            </div>
        </div>
    </div>
</div>
<!--/ Status Modal -->