<!-- Status Modal -->
<div class="modal fade" data-bs-backdrop="static" id="inventoryStatusUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">{{ __('Update Status') }}</h3>
                    <p class="text-muted">
                        Update Status Of <b class="text-dark">{{ $inventory->name }})</b>
                    </p>
                </div>
                <!-- Status form -->
                <form method="post" action="{{ route('administration.inventory.status.update', ['inventory' => $inventory]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 col-md-12">
                        <label for="status" class="form-label">Select Status <strong class="text-danger">*</strong></label>
                        <div class="row mt-2">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-success">
                                    <label class="form-check-label custom-option-content" for="markAsAvailable">
                                        <span class="custom-option-body">
                                            <i class="ti ti-check fs-1 text-success"></i>
                                            <span class="custom-option-title fs-4 text-bold">Available</span>
                                            <small>Mark Status As <b class="text-success">{{ __('Available') }}</b></small>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Available" id="markAsAvailable" required @checked($inventory->status === 'Available')/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-primary">
                                    <label class="form-check-label custom-option-content" for="markAsInUse">
                                        <span class="custom-option-body">
                                            <i class="ti ti-activity-heartbeat fs-1 text-primary"></i>
                                            <span class="custom-option-title fs-4 text-bold">In Use</span>
                                            <small>Mark Status As <b class="text-primary">{{ __('In Use') }}</b></small>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="In Use" id="markAsInUse" required @checked($inventory->status === 'In Use')/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-warning">
                                    <label class="form-check-label custom-option-content" for="markAsOutOfService">
                                        <span class="custom-option-body">
                                            <i class="ti ti-hand-stop fs-1 text-warning"></i>
                                            <span class="custom-option-title fs-4 text-bold">Out of Service</span>
                                            <small>Mark Status As <b class="text-warning">{{ __('Out of Service') }}</b></small>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Out of Service" id="markAsOutOfService" required @checked($inventory->status === 'Out of Service')/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-danger">
                                    <label class="form-check-label custom-option-content" for="markAsDamaged">
                                        <span class="custom-option-body">
                                            <i class="ti ti-ban fs-1 text-danger"></i>
                                            <span class="custom-option-title fs-4 text-bold">Damaged</span>
                                            <small>Mark Status As <b class="text-danger">{{ __('Damaged') }}</b></small>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Damaged" id="markAsDamaged" required @checked($inventory->status === 'Damaged')/>
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
