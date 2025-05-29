<div class="modal fade" data-bs-backdrop="static" id="approveAttendanceIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Reject Attendance Issue</h3>
                    <p class="text-muted">Reject the Attendance Issue of <b class="text-primary">{{ $issue->user->alias_name }}</b></p>
                </div>
                {{-- {{ dd(get_date_only($issue->clock_in_date)) }} --}}
                <!-- Status form -->
                <form method="post" action="{{ route('administration.attendance.issue.update', ['issue' => $issue, 'status' => 'Approved']) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ 'Approved' }}" required>
                    <input type="hidden" name="user_id" value="{{ $issue->user->id }}" required>
                    <input type="hidden" name="clock_in_date" value="{{ get_date_only($issue->clock_in_date) }}" required>
                    <input type="hidden" name="attendance_id" value="{{ $issue->attendance->id ?? NULL }}" required>
                    <div class="mb-3 col-md-4">
                        <label for="clock_in" class="form-label">{{ __('Clockin Time') }} <strong class="text-danger">*</strong></label>
                        <input type="text" id="clock_in" name="clock_in" value="{{ old('clock_in', $issue->clock_in) }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('clock_in') is-invalid @enderror" required/>
                        @error('clock_in')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="clock_out" class="form-label">{{ __('Clockout Time') }} <strong class="text-danger">*</strong></label>
                        <input type="text" id="clock_out" name="clock_out" value="{{ old('clock_out', $issue->clock_out) }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('clock_out') is-invalid @enderror" required/>
                        @error('clock_out')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="type" class="form-label">{{ __('Select Clockin Type') }} <strong class="text-danger">*</strong></label>
                        <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default" required>
                            <option value="" selected disabled>{{ __('Select Type') }}</option>
                            <option value="Regular" {{ old('type', $issue->type) == 'Regular' ? 'selected' : '' }}>{{ __('Regular') }}</option>
                            <option value="Overtime" {{ old('type', $issue->type) == 'Overtime' ? 'selected' : '' }}>{{ __('Overtime') }}</option>
                        </select>
                        @error('type')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Note</label>
                        <textarea class="form-control" name="note" rows="3" placeholder="Ex: You are not supposed to do this mistake again.">{{ old('note') }}</textarea>
                        @error('note')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-success me-sm-3 me-1">
                            <i class="ti ti-check me-1"></i>
                            {{ __('Approve Issue') }}
                        </button>
                    </div>
                </form>
                <!--/ Status form -->
            </div>
        </div>
    </div>
</div>
