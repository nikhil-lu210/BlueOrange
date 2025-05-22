<!-- Employee Info Update Modal -->
<div class="modal fade" data-bs-backdrop="static" id="employeeInfoUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Update Your Information</h3>
                    <p class="text-muted">Please update your information for emergency purposes.</p>
                </div>
                <!-- Employee Info Update form -->
                <form method="post" action="{{ route('administration.my.profile.update.information') }}" class="row g-3" autocomplete="off">
                    @csrf
                    @if (is_invalid_employee_value($user->employee->blood_group))
                        <div class="mb-3 col-md-12">
                            <label for="blood_group" class="form-label">Blood Group <strong class="text-danger">*</strong></label>
                            <select name="blood_group" id="blood_group" class="form-select select2 w-100 @error('blood_group') is-invalid @enderror" data-style="btn-default" required>
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
                                <option value="Unknown">Don't Know (Unknown)</option>
                            </select>
                            @error('blood_group')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    @endif

                    @if (is_invalid_employee_value($user->employee->father_name))
                        <div class="mb-3 col-md-12">
                            <label for="father_name" class="form-label">{{ __('Father Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="father_name" name="father_name" value="{{ old('father_name') }}" placeholder="{{ __('Father Name') }}" class="form-control @error('father_name') is-invalid @enderror" required/>
                            @error('father_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    @endif

                    @if (is_invalid_employee_value($user->employee->mother_name))
                        <div class="mb-3 col-md-12">
                            <label for="mother_name" class="form-label">{{ __('Mother Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name') }}" placeholder="{{ __('Mother Name') }}" class="form-control @error('mother_name') is-invalid @enderror" required/>
                            @error('mother_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    @endif

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Save Changes</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
