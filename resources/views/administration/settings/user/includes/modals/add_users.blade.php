<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="addNewUsersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Add Users</h3>
                    <p class="text-muted">Add Users For Interactions With {{ $user->alias_name }}</p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.settings.user.user_interaction.add_users', ['user' => $user]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="users" class="form-label">Select Users <strong class="text-danger">*</strong></label>
                        <select name="users[]" id="users" class="select2 form-select @error('users') is-invalid @enderror" data-allow-clear="true" multiple required>
                            <option value="selectAllValues">Select All</option>
                            @foreach ($users as $userData)
                                <option value="{{ $userData->id }}" {{ in_array($userData->id, old('users', [])) ? 'selected' : '' }}>
                                    {{ $userData->employee->alias_name }} ({{ $userData->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('users')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-plus"></i>
                            Add Users
                        </button>
                    </div>
                </form>
                <!--/ Holiday Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->
