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
                                <th>IP Address / CIDR</th>
                                <th>Assigned By</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ipRanges as $key => $range) 
                                <tr>
                                    <th>#{{ $key+1 }}</th>
                                    <td class="text-dark text-bold">
                                        {{ $range['ip_address'] }}/<sub>{{ $range['range'] }}</sub>
                                    </td>
                                    <td>
                                        <b class="text-dark">{{ show_user_data($range['created_by'], 'name') }}</b>
                                        <br>
                                        <small class="text-muted">{{ date_time_ago($range['created_at']) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('administration.settings.system.app_setting.restriction.destroy.ip.range', ['id' => $range['id']]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete IP Range?">
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
                <form action="{{ route('administration.settings.system.app_setting.restriction.update.ip.range') }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label class="form-label">{{ __('IP Address') }} <b class="text-danger">*</b></label>
                            <input type="text" name="ip_address" value="{{ request()->ip_address ?? old('ip_address') }}" class="form-control" placeholder="192.168.0.1" required/>
                            @error('ip_address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{{ __('IP Range (CIDR)') }} <b class="text-danger">*</b></label>
                            <input type="number" name="range" value="{{ request()->range ?? old('range') }}" class="form-control" placeholder="18" min="0" max="32" required/>
                            @error('range')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                {{ __('Store IP Range') }}
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