<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" id="removeUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Remove Interacted User</h3>
                    <p class="text-muted">Remove Interacted User With {{ $user->name }}</p>
                </div>
                <!-- form -->
                <form method="post" action="{{ route('administration.settings.user.user_interaction.remove_user', ['user' => $user]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="user" class="form-label">Select User <strong class="text-danger">*</strong></label>
                        <select name="user" id="user" class="select2 form-select @error('user') is-invalid @enderror" data-allow-clear="true" required>
                            <option value="" selected>Select User</option>
                            @foreach ($user->user_interactions as $user)
                                <option value="{{ $user->id }}" {{ $user->id == old('user') ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-danger me-sm-3 me-1">
                            <i class="ti ti-user-minus"></i>
                            Remove User
                        </button>
                    </div>
                </form>
                <!--/ form -->
            </div>
        </div>
    </div>
</div>
<!--/ Modal -->