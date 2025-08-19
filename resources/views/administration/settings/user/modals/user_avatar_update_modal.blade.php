<div class="modal fade" data-bs-backdrop="static" id="updateAvatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-0 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">{{ __('Update Avatar') }}</h3>
                    <p class="text-muted">
                        Update Avatar Of <b class="text-dark">{{ $user->employee->alias_name }} ({{ $user->name }})</b>
                    </p>
                </div>
                <form method="post" action="{{ route('administration.settings.user.avatar.update', ['user' => $user]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <label for="avatar" class="form-label">{{ __('User Avatar') }} <strong class="text-danger">*</strong></label>
                                <input type="file" id="avatar" name="avatar" value="{{ old('avatar') }}" placeholder="{{ __('User Avatar') }}" class="form-control @error('avatar') is-invalid @enderror" required accept="image/jpeg,image/jpg,image/png" style="padding: 20px;"/>
                                @error('avatar')
                                    <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>

                            <div class="col-md-12 text-center mt-4">
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                    <i class="ti ti-check"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
