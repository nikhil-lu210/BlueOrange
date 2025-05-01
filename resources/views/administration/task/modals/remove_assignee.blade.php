<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="removeTaskUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Remove Task Assignee</h3>
                    <p class="text-muted">Remove Task Assignee From This Task</p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.task.remove.user', ['task' => $task]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="user" class="form-label">Select User <strong class="text-danger">*</strong></label>
                        <select name="user" id="user" class="select2 form-select @error('user') is-invalid @enderror" data-allow-clear="true" required>
                            <option value="" selected>Select User</option>
                            @foreach ($task->users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == old('user') ? 'selected' : '' }}>
                                    {{ $user->alias_name }}
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
                            <i class="ti ti-x"></i>
                            Remove Assignee
                        </button>
                    </div>
                </form>
                <!--/ Holiday Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->
