<!-- Status Modal -->
<div class="modal fade" data-bs-backdrop="static" id="statusUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Update Status</h3>
                    <p class="text-muted">Update the IT Ticket <b class="text-primary">({{ $itTicket->title }})</b> Status</p>
                </div>
                <!-- Status form -->
                <form method="post" action="{{ route('administration.ticket.it_ticket.update.status', ['it_ticket' => $itTicket]) }}" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 col-md-12">
                        <label for="status" class="form-label">Status <strong class="text-danger">*</strong></label>
                        <div class="row mt-2">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-success">
                                    <label class="form-check-label custom-option-content" for="markAsSolved">
                                        <span class="custom-option-body">
                                            <i class="ti ti-check fs-1 text-success"></i>
                                            <span class="custom-option-title fs-4 text-bold">Ticket Solved</span>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Solved" id="markAsSolved" required/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-danger">
                                    <label class="form-check-label custom-option-content" for="markAsCanceled">
                                        <span class="custom-option-body">
                                            <i class="ti ti-ban fs-1 text-danger"></i>
                                            <span class="custom-option-title fs-4 text-bold">Ticket Canceled</span>
                                        </span>
                                        <input name="status" class="form-check-input" type="radio" value="Canceled" id="markAsCanceled" required/>
                                    </label>
                                </div>
                            </div>
                        </div>                            
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Note <strong class="text-danger">*</strong></label>
                        <textarea class="form-control" name="solver_note" rows="3" placeholder="Ex: The Ticket Has Been Solved.">{{ old('solver_note') }}</textarea>
                        @error('solver_note')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Update Ticket Status
                        </button>
                    </div>
                </form>
                <!--/ Status form -->
            </div>
        </div>
    </div>
</div>
<!--/ Status Modal -->