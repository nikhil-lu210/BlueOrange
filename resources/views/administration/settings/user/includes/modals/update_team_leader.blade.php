<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" id="updateTeamLeaderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Add/Update Team Leader</h3>
                    <p class="text-muted">Update Team Leader For {{ $user->alias_name }}</p>
                </div>
                <!-- form -->
                <form method="post" action="{{ route('administration.settings.user.user_interaction.update_team_leader', ['user' => $user]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="team_leader_id" class="form-label">Select Team Leader <strong class="text-danger">*</strong></label>
                        <select name="team_leader_id" id="team_leader_id" class="select2 form-select @error('team_leader_id') is-invalid @enderror" data-allow-clear="true" required>
                            <option value="" selected disabled>Select Team Leader</option>
                            @foreach ($teamLeaders as $teamLeader)
                                <option value="{{ $teamLeader->id }}" {{ $teamLeader->id == old('team_leader_id') ? 'selected' : '' }}>
                                    {{ $teamLeader->employee->alias_name }} ({{ $teamLeader->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('team_leader_id')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Update Team Leader
                        </button>
                    </div>
                </form>
                <!--/ form -->
            </div>
        </div>
    </div>
</div>
<!--/ Modal -->
