<!-- Employee Info Update Modal -->
<div class="modal fade" data-bs-backdrop="static" id="employeeInfoUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">{{ ___('Update Your Information') }}</h3>
                    <p class="text-muted">{{ ___('Please update your information for emergency purposes.') }}</p>
                </div>
                <!-- Employee Info Update form -->
                <form method="post" action="{{ route('administration.my.profile.update.information') }}" class="row g-3" autocomplete="off">
                    @csrf
                    @if (is_invalid_employee_value($user->employee->blood_group))
                        <div class="mb-3 col-md-12">
                            <label for="blood_group" class="form-label">{{ ___('Blood Group') }} <strong class="text-danger">*</strong></label>
                            <select name="blood_group" id="blood_group" class="form-select select2 w-100 @error('blood_group') is-invalid @enderror" data-style="btn-default" required>
                                <option value="">{{ ___('Select Blood Group') }}</option>
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
                            <label for="father_name" class="form-label">{{ ___('Father Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="father_name" name="father_name" value="{{ old('father_name') }}" placeholder="{{ ___('Father Name') }}" class="form-control @error('father_name') is-invalid @enderror" required/>
                            @error('father_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    @endif

                    @if (is_invalid_employee_value($user->employee->mother_name))
                        <div class="mb-3 col-md-12">
                            <label for="mother_name" class="form-label">{{ ___('Mother Name') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name') }}" placeholder="{{ ___('Mother Name') }}" class="form-control @error('mother_name') is-invalid @enderror" required/>
                            @error('mother_name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    @endif

                    @if (is_invalid_employee_value($user->employee->institute_id))
                        <div class="mb-3 col-md-12">
                            <label for="institute_id" class="form-label">{{ ___('Last / Current Educational Institute') }} <strong class="text-danger">*</strong></label>
                            <select name="institute_id" id="institute_id" class="form-select select2-tags @error('institute_id') is-invalid @enderror" data-allow-clear="true" data-tags="true" data-placeholder="Select or type to add new institute" required>
                                <option value="">{{ ___('Select Institute') }}</option>
                                @if(isset($institutes))
                                    @foreach ($institutes as $institute)
                                        <option value="{{ $institute->id }}" {{ old('institute_id', $user->employee->institute_id) == $institute->id ? 'selected' : '' }}>
                                            {{ $institute->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">{{ ___('You can type to add a new institute if not found in the list') }}</small>
                            @error('institute_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    @endif

                    @if (is_invalid_employee_value($user->employee->education_level_id))
                        <div class="mb-3 col-md-12">
                            <label for="education_level_id" class="form-label">{{ ___('Education Level') }} <strong class="text-danger">*</strong></label>
                            <select name="education_level_id" id="education_level_id" class="form-select select2-tags @error('education_level_id') is-invalid @enderror" data-allow-clear="true" data-tags="true" data-placeholder="Select or type to add new education level" required>
                                <option value="">{{ ___('Select Education Level') }}</option>
                                @if(isset($educationLevels))
                                    @foreach ($educationLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('education_level_id', $user->employee->education_level_id) == $level->id ? 'selected' : '' }}>
                                            {{ $level->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-muted">You can type to add a new education level if not found in the list</small>
                            @error('education_level_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    @endif

                    @if (is_invalid_employee_value($user->employee->passing_year))
                        <div class="mb-3 col-md-12">
                            <label for="passing_year" class="form-label">{{ ___('Passing Year / Exp. Year') }} <strong class="text-danger">*</strong></label>
                            <input type="number" id="passing_year" name="passing_year" value="{{ old('passing_year', $user->employee->passing_year) }}" placeholder="{{ ___('e.g., 2020') }}" min="1950" max="{{ date('Y') + 10 }}" class="form-control @error('passing_year') is-invalid @enderror" required/>
                            @error('passing_year')
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
