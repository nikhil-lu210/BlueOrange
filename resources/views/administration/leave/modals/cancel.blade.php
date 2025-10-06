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
<div class="modal fade" data-bs-backdrop="static" id="cancelLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Cancel Leave</h3>
                    <p class="text-muted">Cancel the <b class="text-{{ $typeBg }}">{{ $leaveHistory->type }} Leave</b> request of <b class="text-primary">{{ $leaveHistory->user->alias_name }}</b></p>
                </div>
                <!-- Status form -->
                <form method="post" action="{{ route('administration.leave.history.cancel', ['leaveHistory' => $leaveHistory]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12 mb-3">
                        <label class="form-label">{{ __('Cancelation Reason') }} <strong class="text-danger">*</strong></label>
                        <textarea class="form-control" name="reviewer_note" rows="3" placeholder="Ex: You are not eligible for this leave.">{{ old('reviewer_note') }}</textarea>
                        @error('reviewer_note')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-danger me-sm-3 me-1" id="cancelSubmitBtn">
                            <i class="ti ti-ban me-1"></i>
                            <span class="btn-text">{{ __('Cancel Leave') }}</span>
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
