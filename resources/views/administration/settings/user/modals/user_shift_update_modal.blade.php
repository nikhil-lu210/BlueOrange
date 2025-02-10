<div class="modal fade" id="updateShift" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ route('administration.settings.user.shift.update', ['shift' => $user->current_shift, 'user' => $user]) }}" method="post" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="updateShiftTitle">Update Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">{{ __('Shift Start Time') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="start_time" name="start_time" value="{{ optional($user->current_shift)->start_time ?? old('start_time') }}" placeholder="HH:MM" class="form-control time-picker @error('start_time') is-invalid @enderror" required/>
                            @error('start_time')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label">{{ __('Shift End Time') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="end_time" name="end_time" value="{{ optional($user->current_shift)->end_time ?? old('end_time') }}" placeholder="HH:MM" class="form-control time-picker @error('end_time') is-invalid @enderror" required/>
                            @error('end_time')
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
                        Update Shift
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>