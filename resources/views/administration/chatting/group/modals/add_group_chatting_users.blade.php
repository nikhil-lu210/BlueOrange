<!-- Add Users Modal -->
<div class="modal fade" data-bs-backdrop="static" id="addGroupChattingUsersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Add New Users</h3>
                    <p class="text-muted">Add New Users To This Chatting Group</p>
                </div>
                <!-- Add Users form -->
                <form method="post" action="{{ route('administration.chatting.group.store.users', ['group' => $group]) }}" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="users" class="form-label">Select Users <strong class="text-danger">*</strong></label>
                        <select name="users[]" id="addUsers" class="select2 form-select @error('users') is-invalid @enderror" data-allow-clear="true" multiple required>
                            @foreach ($addUsersRoles as $role)
                                <optgroup label="{{ $role->name }}">
                                    @foreach ($role->users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                            {{ get_employee_name($user) }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('users')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Add Users
                        </button>
                    </div>
                </form>
                <!--/ Add Users form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add Users Modal -->