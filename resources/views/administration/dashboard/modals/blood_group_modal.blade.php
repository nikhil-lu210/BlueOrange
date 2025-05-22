<!-- Blood Group Update Modal -->
<div class="modal fade" data-bs-backdrop="static" id="bloodGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Update Blood Group</h3>
                    <p class="text-muted">Please update your blood group information for emergency purposes.</p>
                </div>
                <!-- Blood Group Update form -->
                <form method="post" action="{{ route('administration.my.profile.update.blood.group') }}" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="blood_group" class="form-label">Blood Group</label>
                        <select name="blood_group" id="blood_group" class="form-select select2 w-100" data-style="btn-default" required>
                            <option value="">Select Blood Group</option>

                            @foreach ($groupedBloodGroups as $groupLabel => $groupOptions)
                                <optgroup label="{{ $groupLabel }}">
                                    @foreach ($groupOptions as $bloodOption)
                                        <option value="{{ $bloodOption->value }}">
                                            {{ $bloodOption->value }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach

                            <option value="N/A">Don't Know (N/A)</option>
                        </select>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Save Changes</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
