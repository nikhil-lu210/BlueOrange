<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="upgradeLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Upgrade Leave</h3>
                    <p class="text-muted">Upgrade allowed leave for <b class="text-primary">{{ $user->alias_name }}</b></p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.settings.user.leave_allowed.store', ['user' => $user]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="col-12">
                        <a href="javascript:void(0);" class="text-primary fs-6" id="editMinutesSeconds" style="cursor: pointer;">Edit Minute & Seconds</a>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="earned_leave" class="form-label">Earned Leave <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <input type="number" min="0" max="240" name="earned_leave_hour" value="{{ old('earned_leave_hour') }}" @error('earned_leave') is-invalid @enderror class="form-control" placeholder="HH" aria-label="HH" required>
                            <input type="number" readonly min="0" max="59" name="earned_leave_min" value="{{ old('earned_leave_min', 0) }}" @error('earned_leave') is-invalid @enderror class="form-control time-min" placeholder="MM" aria-label="MM" required>
                            <input type="number" readonly min="0" max="59" name="earned_leave_sec" value="{{ old('earned_leave_sec', 0) }}" @error('earned_leave') is-invalid @enderror class="form-control time-sec" placeholder="SS" aria-label="SS" required>
                        </div>
                        @error('earned_leave')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="sick_leave" class="form-label">Sick Leave <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <input type="number" min="0" max="240" name="sick_leave_hour" value="{{ old('sick_leave_hour') }}" @error('sick_leave') is-invalid @enderror class="form-control" placeholder="HH" aria-label="HH" required>
                            <input type="number" readonly min="0" max="59" name="sick_leave_min" value="{{ old('sick_leave_min', 0) }}" @error('sick_leave') is-invalid @enderror class="form-control time-min" placeholder="MM" aria-label="MM" required>
                            <input type="number" readonly min="0" max="59" name="sick_leave_sec" value="{{ old('sick_leave_sec', 0) }}" @error('sick_leave') is-invalid @enderror class="form-control time-sec" placeholder="SS" aria-label="SS" required>
                        </div>
                        @error('sick_leave')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="casual_leave" class="form-label">Casual Leave <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <input type="number" min="0" max="240" name="casual_leave_hour" value="{{ old('casual_leave_hour') }}" @error('casual_leave') is-invalid @enderror class="form-control" placeholder="HH" aria-label="HH" required>
                            <input type="number" readonly min="0" max="59" name="casual_leave_min" value="{{ old('casual_leave_min', 0) }}" @error('casual_leave') is-invalid @enderror class="form-control time-min" placeholder="MM" aria-label="MM" required>
                            <input type="number" readonly min="0" max="59" name="casual_leave_sec" value="{{ old('casual_leave_sec', 0) }}" @error('casual_leave') is-invalid @enderror class="form-control time-sec" placeholder="SS" aria-label="SS" required>
                        </div>
                        @error('casual_leave')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="implemented_from" class="form-label">Implemented From <strong class="text-danger">*</strong></label>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <select name="implemented_from_month" class="form-select select2 implemented-month-select @error('implemented_from') is-invalid @enderror" data-style="btn-default" required>
                                    <option value="">Select Month</option>
                                    <option value="1" {{ old('implemented_from_month', 1) == 1 ? 'selected' : '' }}>January</option>
                                    <option value="2" {{ old('implemented_from_month') == 2 ? 'selected' : '' }}>February</option>
                                    <option value="3" {{ old('implemented_from_month') == 3 ? 'selected' : '' }}>March</option>
                                    <option value="4" {{ old('implemented_from_month') == 4 ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ old('implemented_from_month') == 5 ? 'selected' : '' }}>May</option>
                                    <option value="6" {{ old('implemented_from_month') == 6 ? 'selected' : '' }}>June</option>
                                    <option value="7" {{ old('implemented_from_month') == 7 ? 'selected' : '' }}>July</option>
                                    <option value="8" {{ old('implemented_from_month') == 8 ? 'selected' : '' }}>August</option>
                                    <option value="9" {{ old('implemented_from_month') == 9 ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ old('implemented_from_month') == 10 ? 'selected' : '' }}>October</option>
                                    <option value="11" {{ old('implemented_from_month') == 11 ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ old('implemented_from_month') == 12 ? 'selected' : '' }}>December</option>
                                </select>
                                <label for="implemented_from_month" class="text-muted">Month</label>
                            </div>
                            <div class="col-md-6">
                                <select name="implemented_from_date" class="form-select select2 implemented-date-select @error('implemented_from') is-invalid @enderror" data-style="btn-default" required>
                                    <option value="">Select Date</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" {{ old('implemented_from_date', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <label for="implemented_from_date" class="text-muted">Date</label>
                            </div>
                        </div>
                        @error('implemented_from')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="implemented_to" class="form-label">Implemented To <strong class="text-danger">*</strong></label>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <select name="implemented_to_month" class="form-select select2 implemented-month-select @error('implemented_to') is-invalid @enderror" data-style="btn-default" required>
                                    <option value="">Select Month</option>
                                    <option value="1" {{ old('implemented_to_month') == 1 ? 'selected' : '' }}>January</option>
                                    <option value="2" {{ old('implemented_to_month') == 2 ? 'selected' : '' }}>February</option>
                                    <option value="3" {{ old('implemented_to_month') == 3 ? 'selected' : '' }}>March</option>
                                    <option value="4" {{ old('implemented_to_month') == 4 ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ old('implemented_to_month') == 5 ? 'selected' : '' }}>May</option>
                                    <option value="6" {{ old('implemented_to_month') == 6 ? 'selected' : '' }}>June</option>
                                    <option value="7" {{ old('implemented_to_month') == 7 ? 'selected' : '' }}>July</option>
                                    <option value="8" {{ old('implemented_to_month') == 8 ? 'selected' : '' }}>August</option>
                                    <option value="9" {{ old('implemented_to_month') == 9 ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ old('implemented_to_month') == 10 ? 'selected' : '' }}>October</option>
                                    <option value="11" {{ old('implemented_to_month') == 11 ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ old('implemented_to_month', 12) == 12 ? 'selected' : '' }}>December</option>
                                </select>
                                <label for="implemented_to_month" class="text-muted">Month</label>
                            </div>
                            <div class="col-md-6">
                                <select name="implemented_to_date" class="form-select select2 implemented-date-select @error('implemented_to') is-invalid @enderror" data-style="btn-default" required>
                                    <option value="">Select Date</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" {{ old('implemented_to_date', 31) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <label for="implemented_to_date" class="text-muted">Date</label>
                            </div>
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
