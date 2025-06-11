<div class="modal fade" id="editAttendance" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('administration.attendance.update', ['attendance' => $attendance]) }}" method="post" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editAttendanceTitle">
                        <span class="ti ti-edit ti-sm me-1"></span>
                        Update Attendance
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="type" class="form-label">Select Clockin Type <strong class="text-danger">*</strong></label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="Regular" @selected($attendance->type == 'Regular' ?? old('type'))>Regular</option>
                                <option value="Overtime" @selected($attendance->type == 'Overtime' ?? old('type'))>Overtime</option>
                            </select>
                            @error('type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="clock_in" class="form-label">{{ __('Clock In') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="clock_in" name="clock_in" value="{{ $attendance->clock_in ?? old('clock_in') }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('clock_in') is-invalid @enderror" required/>
                            @error('clock_in')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="clock_out" class="form-label">{{ __('Clock Out') }}</label>
                            <input type="text" id="clock_out" name="clock_out" value="{{ $attendance->clock_out ?? '' }}" placeholder="YYYY-MM-DD HH:MM" class="form-control date-time-picker @error('clock_out') is-invalid @enderror"/>
                            @error('clock_out')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x"></i>
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check"></i>
                        Update Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
