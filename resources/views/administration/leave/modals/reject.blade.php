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
<div class="modal fade" data-bs-backdrop="static" id="rejectLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Reject Leave</h3>
                    <p class="text-muted">Reject the <b class="text-{{ $typeBg }}">{{ $leaveHistory->type }} Leave</b> request of <b class="text-primary">{{ $leaveHistory->user->alias_name }}</b></p>
                </div>
                <!-- Status form -->
                <form method="post" action="{{ route('administration.leave.history.reject', ['leaveHistory' => $leaveHistory]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off" id="rejectLeaveForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 col-12">
                        <label class="form-label">Rejection Reason <strong class="text-danger">*</strong></label>
                        <div name="reviewer_note" id="leaveRejectNoteEditor">{!! old('reviewer_note') !!}</div>
                        <textarea class="d-none" name="reviewer_note" id="leaveRejectNoteInput">{{ old('reviewer_note') }}</textarea>
                        @error('reviewer_note')
                            <b class="text-danger">{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-danger me-sm-3 me-1" id="rejectSubmitBtn">
                            <i class="ti ti-check"></i>
                            <span class="btn-text">Reject Leave</span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
                <!--/ Status form -->
            </div>
        </div>
    </div>
</div>
<!--/ Status Modal -->
