<div class="card mb-4 border-1">
    <div class="card-header header-elements">
        <h5 class="mb-0">Leave Date(s) and Hour(s)</h5>

        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-sm btn-dark" id="addLeaveDay">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                Add Day
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- This Row Will Be Duplicated on Add Day Button Click -->
        @foreach(old('leave_days.date', []) as $index => $date)
            <div class="row leave-day-row">
                <div class="mb-3 col-md-5">
                    <label class="form-label">Date <strong class="text-danger">*</strong></label>
                    <input type="text" name="leave_days[date][]"
                        value="{{ old('leave_days.date.' . $index, $date) }}"
                        class="form-control date-picker" placeholder="YYYY-MM-DD" required />
                    @error('leave_days.date.' . $index)
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 col-md-7">
                    <label for="total_leave" class="form-label">Total Leave <strong class="text-danger">*</strong></label>
                    <div class="input-group">
                        <span class="input-group-text bg-label-primary">Hour:</span>
                        <input type="number" min="0" max="240" step="1"
                            name="total_leave[hour][]"
                            value="{{ old('total_leave.hour.' . $index) }}"
                            class="form-control @error('total_leave.hour.' . $index) is-invalid @enderror"
                            placeholder="HH" required>
                        <span class="input-group-text bg-label-primary">Min:</span>
                        <input type="number" min="0" max="59" step="1"
                            name="total_leave[min][]"
                            value="{{ old('total_leave.min.' . $index) }}"
                            class="form-control @error('total_leave.min.' . $index) is-invalid @enderror"
                            placeholder="MM" required>
                        <span class="input-group-text bg-label-primary">Sec:</span>
                        <input type="number" min="0" max="59" step="1"
                            name="total_leave[sec][]"
                            value="{{ old('total_leave.sec.' . $index) }}"
                            class="form-control @error('total_leave.sec.' . $index) is-invalid @enderror"
                            placeholder="SS" required>
                    </div>
                    @error('total_leave.*')
                        <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                    @enderror
                </div>

                <div class="col-md-12">
                    <!-- Show remove button -->
                    <button type="button" class="btn btn-danger btn-xs remove-leave-day text-right float-right">
                        <i class="fa fa-times" style="margin-right: 5px;"></i>
                        Remove
                    </button>
                </div>
            </div>
        @endforeach

        <!-- For adding new leave days -->
        <div class="row leave-day-row">
            <div class="mb-3 col-md-5">
                <label class="form-label">Date <strong class="text-danger">*</strong></label>
                <input type="text" name="leave_days[date][]" class="form-control date-picker"
                    placeholder="YYYY-MM-DD" required />
                @error('leave_days.date.*')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3 col-md-7">
                <label for="total_leave" class="form-label">Total Leave <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <span class="input-group-text bg-label-primary">Hour:</span>
                    <input type="number" min="0" max="240" step="1" name="total_leave[hour][]"
                        class="form-control @error('total_leave.hour.*') is-invalid @enderror"
                        placeholder="HH" required>
                    <span class="input-group-text bg-label-primary">Min:</span>
                    <input type="number" min="0" max="59" step="1" value="0" name="total_leave[min][]"
                        class="form-control @error('total_leave.min.*') is-invalid @enderror"
                        placeholder="MM" required>
                    <span class="input-group-text bg-label-primary">Sec:</span>
                    <input type="number" min="0" max="59" step="1" value="0" name="total_leave[sec][]"
                        class="form-control @error('total_leave.sec.*') is-invalid @enderror"
                        placeholder="SS" required>
                </div>
                @error('total_leave.*')
                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                @enderror
            </div>

            <div class="col-md-12">
                <!-- Hide remove button initially -->
                <button type="button" class="btn btn-danger btn-xs remove-leave-day text-right float-right" style="display: none !important;">
                    <i class="fa fa-times" style="margin-right: 5px;"></i>
                    Remove
                </button>
            </div>
        </div>
    </div>
</div>
