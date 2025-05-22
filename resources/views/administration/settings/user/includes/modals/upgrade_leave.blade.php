<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="upgradeLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Upgrade Leave</h3>
                    <p class="text-muted">Upgrade allowed leave for <b class="text-primary">{{ $user->name }}</b></p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.settings.user.leave_allowed.store', ['user' => $user]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="earned_leave" class="form-label">Earned Leave <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <input type="number" min="0" max="240" name="earned_leave_hour" value="{{ old('earned_leave_hour') }}" @error('earned_leave') is-invalid @enderror class="form-control" placeholder="HH" aria-label="HH" required>
                            <input type="number" min="0" max="59" name="earned_leave_min" value="{{ old('earned_leave_min', 0) }}" @error('earned_leave') is-invalid @enderror class="form-control" placeholder="MM" aria-label="MM" required>
                            <input type="number" min="0" max="59" name="earned_leave_sec" value="{{ old('earned_leave_sec', 0) }}" @error('earned_leave') is-invalid @enderror class="form-control" placeholder="SS" aria-label="SS" required>
                        </div>
                        @error('earned_leave')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="sick_leave" class="form-label">Sick Leave <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <input type="number" min="0" max="240" name="sick_leave_hour" value="{{ old('sick_leave_hour') }}" @error('sick_leave') is-invalid @enderror class="form-control" placeholder="HH" aria-label="HH" required>
                            <input type="number" min="0" max="59" name="sick_leave_min" value="{{ old('sick_leave_min', 0) }}" @error('sick_leave') is-invalid @enderror class="form-control" placeholder="MM" aria-label="MM" required>
                            <input type="number" min="0" max="59" name="sick_leave_sec" value="{{ old('sick_leave_sec', 0) }}" @error('sick_leave') is-invalid @enderror class="form-control" placeholder="SS" aria-label="SS" required>
                        </div>
                        @error('sick_leave')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="casual_leave" class="form-label">Casual Leave <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <input type="number" min="0" max="240" name="casual_leave_hour" value="{{ old('casual_leave_hour') }}" @error('casual_leave') is-invalid @enderror class="form-control" placeholder="HH" aria-label="HH" required>
                            <input type="number" min="0" max="59" name="casual_leave_min" value="{{ old('casual_leave_min', 0) }}" @error('casual_leave') is-invalid @enderror class="form-control" placeholder="MM" aria-label="MM" required>
                            <input type="number" min="0" max="59" name="casual_leave_sec" value="{{ old('casual_leave_sec', 0) }}" @error('casual_leave') is-invalid @enderror class="form-control" placeholder="SS" aria-label="SS" required>
                        </div>
                        @error('casual_leave')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="implemented_from" class="form-label">Implemented From <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <input type="number" min="1" max="12" maxlength="2" name="implemented_from_month" value="{{ old('implemented_from_month', 1) }}" @error('implemented_from') is-invalid @enderror class="form-control" placeholder="MM" aria-label="MM" required>
                            <input type="number" min="1" max="31" maxlength="2" name="implemented_from_date" value="{{ old('implemented_from_date', 1) }}" @error('implemented_from') is-invalid @enderror class="form-control" placeholder="DD" aria-label="DD" required>
                        </div>
                        @error('implemented_from')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="implemented_to" class="form-label">Implemented To <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <input type="number" min="1" max="12" maxlength="2" name="implemented_to_month" value="{{ old('implemented_to_month', 12) }}" @error('implemented_to') is-invalid @enderror class="form-control" placeholder="MM" aria-label="MM" required>
                            <input type="number" min="1" max="31" maxlength="2" name="implemented_to_date" value="{{ old('implemented_to_date', 31) }}" @error('implemented_to') is-invalid @enderror class="form-control" placeholder="DD" aria-label="DD" required>
                        </div>
                        @error('implemented_to')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Upgrade Now
                        </button>
                    </div>
                </form>
                <!--/ Holiday Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->
