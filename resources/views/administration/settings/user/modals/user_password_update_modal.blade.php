<div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('administration.settings.user.password.update', ['user' => $user]) }}" method="post" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePasswordModalTitle">Update Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-12 form-password-toggle">
                            <label class="form-label" for="user_password">{{ __('Password') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="password" minlength="8" id="user_password" name="user_password" value="{{ old('user_password') }}" class="form-control @error('user_password') is-invalid @enderror" placeholder="**********" required />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('user_password')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="col-md-12 form-password-toggle">
                            <label class="form-label" for="user_password_confirmation">{{ __('Password Confirmation') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="password" minlength="8" id="user_password_confirmation" name="user_password_confirmation" value="{{ old('user_password_confirmation') }}" class="form-control @error('user_password_confirmation') is-invalid @enderror" placeholder="**********" required/>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('user_password_confirmation')
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
                        <i class="ti ti-lock-check"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
