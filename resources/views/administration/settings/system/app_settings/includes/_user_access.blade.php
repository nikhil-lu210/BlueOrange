<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Unrestricted User(s)</h5> 
                <div class="card-header-elements ms-auto">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignUserModal">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        Assign User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>User</th>
                                <th>Assigned By</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unrestrictedUsers as $key => $user) 
                                <tr>
                                    <th>#{{ $key+1 }}</th>
                                    <td>
                                        <b class="text-dark">{{ show_user_data($user['user_id'], 'name') }}</b>
                                        <br>
                                        <small class="text-muted">{{ show_employee_data($user['user_id'], 'alias_name') }}</small>
                                    </td>
                                    <td>
                                        <b class="text-dark">{{ show_user_data($user['assigned_by'], 'name') }}</b>
                                        <br>
                                        <small class="text-muted">{{ date_time_ago($user['created_at']) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('administration.settings.system.app_setting.restriction.destroy.user', ['id' => $user['id']]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Remove Unrestricted User?">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
</div>


<!-- Create IP Range Modal -->
<div class="modal fade" data-bs-backdrop="static" id="assignUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Assign Unrestricted User</h3>
                    <p class="text-muted">Assign A New Unrestricted User</p>
                </div>
                <!-- Create IP Range form -->
                <form action="{{ route('administration.settings.system.app_setting.restriction.update.user') }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="user_id" class="form-label">Select User <strong class="text-danger">*</strong></label>
                            <select name="user_id" id="addUser" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true" required>
                                <option value="" selected disabled>Select User</option>
                                @foreach ($roleUsers as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('user_id', [])) ? 'selected' : '' }}>
                                                {{ get_employee_name($user) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                {{ __('Assign User') }}
                            </button>
                        </div>
                    </div>
                </form>
                <!--/ Create IP Range form -->
            </div>
        </div>
    </div>
</div>
<!--/ Create IP Range Modal -->