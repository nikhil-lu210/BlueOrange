<div class="modal fade" data-bs-backdrop="static" id="rejectAttendanceIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Reject Attendance Issue</h3>
                    <p class="text-muted">Reject the Attendance Issue of <b class="text-primary">{{ $issue->user->alias_name }}</b></p>
                </div>
                <!-- Status form -->
                <form method="post" action="{{ route('administration.attendance.issue.update', ['issue' => $issue, 'status' => 'Rejected']) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ 'Rejected' }}" required>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Rejection Reason <strong class="text-danger">*</strong></label>
                        <textarea class="form-control" name="note" rows="3" placeholder="Ex: You are trying to fraud with the timing." required>{{ old('note') }}</textarea>
                        @error('note')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-danger me-sm-3 me-1">
                            <i class="ti ti-ban me-1"></i>
                            {{ __('Reject Issue') }}
                        </button>
                    </div>
                </form>
                <!--/ Status form -->
            </div>
        </div>
    </div>
</div>
