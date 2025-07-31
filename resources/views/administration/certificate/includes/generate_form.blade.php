<form action="{{ route('administration.certificate.generate') }}" method="GET" autocomplete="off">
    <div class="card mb-4">
        <div class="card-header header-elements">
            <h5 class="mb-0 text-bold">Generate Certificate</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label for="user_id" class="form-label">Select Employee <strong class="text-danger">*</strong></label>
                    <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true" required>
                        <option value="" selected>Select Employee</option>
                        @if(isset($employees) && $employees->count() > 0)
                            @foreach ($employees as $user)
                                <option value="{{ $user->id }}" {{ (isset($certificate) && $certificate->user->id == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }} @if($user->employee && $user->employee->alias_name)({{ $user->employee->alias_name }})@endif
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>No employees found</option>
                        @endif
                    </select>
                    @error('user_id')
                        <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                    @enderror
                </div>
                <div class="mb-3 col-md-12">
                    <label for="type" class="form-label">Select Certificate Type <strong class="text-danger">*</strong></label>
                    <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default" required>
                        <option value="" selected disabled>Select Certificate Type</option>
                        @foreach(certificate_get_types() as $typeKey)
                            <option value="{{ $typeKey }}" {{ (isset($certificate) && $certificate->type == $typeKey) ? 'selected' : '' }}>
                                {{ certificate_get_type_config($typeKey)['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                    @enderror
                </div>
                <div class="mb-3 col-md-12">
                    <label class="form-label">Issue Date <strong class="text-danger">*</strong></label>
                    <input type="text" name="issue_date" value="{{ (isset($certificate) ? show_date($certificate->issue_date, 'Y-m-d') : old('issue_date')) }}" class="form-control date-picker" placeholder="YYYY-MM-DD" required/>
                    @error('issue_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Joining Date will be visible and required only for Appointment Letter --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Joining Date <strong class="text-danger">*</strong></label>
                    <input type="text" name="joining_date" value="{{ (isset($certificate) ? show_date($certificate->joining_date, 'Y-m-d') : old('joining_date')) }}" class="form-control date-picker" placeholder="YYYY-MM-DD" required/>
                    @error('joining_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Salary will be visible and required only for Appointment Letter And Employment Certificate --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Salary <strong class="text-danger">*</strong></label>
                    <input type="number" name="salary" value="{{ (isset($certificate) ? $certificate->salary : old('salary')) }}" class="form-control" placeholder="25000" required/>
                    @error('salary')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Resignation Date will be visible and required only for Experience Letter --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Resignation Date <strong class="text-danger">*</strong></label>
                    <input type="text" name="resignation_date" value="{{ (isset($certificate) ? show_date($certificate->resignation_date, 'Y-m-d') : old('resignation_date')) }}" class="form-control date-picker" placeholder="YYYY-MM-DD" required/>
                    @error('resignation_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Release Date will be visible and required only for Release Letter --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Release Date <strong class="text-danger">*</strong></label>
                    <input type="text" name="release_date" value="{{ (isset($certificate) ? show_date($certificate->release_date, 'Y-m-d') : old('release_date')) }}" class="form-control date-picker" placeholder="YYYY-MM-DD" required/>
                    @error('release_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Release Reason will be visible and required only for Release Reason --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Release Reason <strong class="text-danger">*</strong></label>
                    <input type="text" name="release_reason" value="{{ (isset($certificate) ? $certificate->release_reason : old('release_reason')) }}" class="form-control" placeholder="Ex: Health Issue" required/>
                    @error('release_reason')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Country Name will be visible and required only for NOC/No Objection Letter --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Country Name <strong class="text-danger">*</strong></label>
                    <input type="text" name="country_name" value="{{ (isset($certificate) ? $certificate->country_name : old('country_name')) }}" class="form-control" placeholder="Ex: United State of America" required/>
                    @error('country_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Visiting Purpose will be visible and required only for NOC/No Objection Letter --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Visiting Purpose <strong class="text-danger">*</strong></label>
                    <input type="text" name="visiting_purpose" value="{{ (isset($certificate) ? $certificate->visiting_purpose : old('visiting_purpose')) }}" class="form-control" placeholder="Ex: United State of America" required/>
                    @error('visiting_purpose')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Leave Starts From will be visible and required only for NOC/No Objection Letter --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Leave Starts From <strong class="text-danger">*</strong></label>
                    <input type="text" name="leave_starts_from" value="{{ (isset($certificate) ? show_date($certificate->leave_starts_from, 'Y-m-d') : old('leave_starts_from')) }}" class="form-control date-picker" placeholder="YYYY-MM-DD" required/>
                    @error('leave_starts_from')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Leave Ends On will be visible but not required only for NOC/No Objection Letter --}}
                <div class="mb-3 col-md-12">
                    <label class="form-label">Leave Ends On</label>
                    <input type="text" name="leave_ends_on" value="{{ (isset($certificate) ? show_date($certificate->leave_ends_on, 'Y-m-d') : old('leave_ends_on')) }}" class="form-control date-picker" placeholder="YYYY-MM-DD"/>
                    @error('leave_ends_on')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-label-success btn-block">
                    <span class="tf-icon ti ti-progress-check ti-xs me-1"></span>
                    {{ __('Generate Certificate') }}
                </button>
            </div>
        </div>
    </div>
</form>
