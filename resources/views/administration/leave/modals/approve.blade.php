@php
    switch ($leaveHistory->type) {
        case 'Earned':
            $typeBg = 'success';
            break;

        case 'Sick':
            $typeBg = 'warning';
            break;

        default:
            $typeBg = 'danger';
            break;
    }
@endphp
<!-- Status Modal -->
<div class="modal fade" data-bs-backdrop="static" id="approveLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Approve Leave</h3>
                    <p class="text-muted">Approve the <b class="text-{{ $typeBg }}">{{ $leaveHistory->type }} Leave</b> request of <b class="text-primary">{{ $leaveHistory->user->alias_name }}</b></p>
                </div>
                <!-- Status form -->
                <form method="post" action="{{ route('administration.leave.history.approve', ['leaveHistory' => $leaveHistory]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 col-md-12">
                        <label for="is_paid_leave" class="form-label">Approve As? <strong class="text-danger">*</strong></label>
                        <div class="row mt-2">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-success">
                                    <label class="form-check-label custom-option-content" for="markAsPaid">
                                        <span class="custom-option-body">
                                            <i class="ti ti-currency-dollar fs-1 text-success"></i>
                                            <span class="custom-option-title fs-4 text-bold">Paid Leave</span>
                                            <small>Mark The <b class="text-{{ $typeBg }}">{{ $leaveHistory->type }}</b> Leave as <b class="text-success">Paid </b>Leave</small>
                                        </span>
                                        <input name="is_paid_leave" class="form-check-input" type="radio" value="Paid" id="markAsPaid" required @checked($leaveHistory->type === 'Earned')/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check custom-option custom-option-icon form-check-danger">
                                    <label class="form-check-label custom-option-content" for="markAsUnpaid">
                                        <span class="custom-option-body">
                                            <i class="ti ti-currency-dollar-off fs-1 text-danger"></i>
                                            <span class="custom-option-title fs-4 text-bold">Unpaid Leave</span>
                                            <small>Mark The <b class="text-{{ $typeBg }}">{{ $leaveHistory->type }}</b> Leave as <b class="text-danger">Unpaid </b>Leave</small>
                                        </span>
                                        <input name="is_paid_leave" class="form-check-input" type="radio" value="Unpaid" id="markAsUnpaid" required @checked($leaveHistory->type !== 'Earned') @disabled($leaveHistory->type === 'Earned')/>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Approve Leave
                        </button>
                    </div>
                </form>
                <!--/ Status form -->
            </div>
        </div>
    </div>
</div>
<!--/ Status Modal -->
