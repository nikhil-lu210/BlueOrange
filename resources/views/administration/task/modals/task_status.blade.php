<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="taskStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Update Status</h3>
                    <p class="text-muted">Update Task Status</p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.task.update.status', ['task' => $task]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 col-md-12">
                        <label for="status" class="form-label">Select Task Status <strong class="text-danger">*</strong></label>
                        <div class="row">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-basic form-check-info">
                                    <label class="form-check-label custom-option-content" for="statusActive">
                                        <input name="status" class="form-check-input" type="radio" value="Active" id="statusActive" {{ old('status', $task->status) == 'Active' ? 'checked' : '' }} />
                                        <span class="custom-option-header pb-0">
                                            <span class="h6 mb-0">Active</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-basic form-check-primary">
                                    <label class="form-check-label custom-option-content" for="statusRunning">
                                        <input name="status" class="form-check-input" type="radio" value="Running" id="statusRunning" {{ old('status', $task->status) == 'Running' ? 'checked' : '' }} />
                                        <span class="custom-option-header pb-0">
                                            <span class="h6 mb-0">Running</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-basic form-check-success">
                                    <label class="form-check-label custom-option-content" for="statusCompleted">
                                        <input name="status" class="form-check-input" type="radio" value="Completed" id="statusCompleted" {{ old('status', $task->status) == 'Completed' ? 'checked' : '' }} />
                                        <span class="custom-option-header pb-0">
                                            <span class="h6 mb-0">Completed</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-basic form-check-danger">
                                    <label class="form-check-label custom-option-content" for="statusCancelled">
                                        <input name="status" class="form-check-input" type="radio" value="Cancelled" id="statusCancelled" {{ old('status', $task->status) == 'Cancelled' ? 'checked' : '' }} />
                                        <span class="custom-option-header pb-0">
                                            <span class="h6 mb-0">Cancelled</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>                            
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Stop Task Now
                        </button>
                    </div>
                </form>
                <!--/ Holiday Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->