@extends('administration.settings.user.show')

@section('profile_content')

<!-- User Profile Content -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Team Leader(s)</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateTeamLeaderModal" title="Add/Update Team Leader">
                        @if ($user->employee_team_leaders->count() > 0) 
                            <span class="tf-icon ti ti-edit-circle ti-xs me-1"></span>
                            Update
                        @else 
                            <span class="tf-icon ti ti-user-plus ti-xs me-1"></span>
                            Add
                        @endif
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Team Leader</th>
                            <th>Assigned At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->employee_team_leaders as $key => $leader) 
                            <tr>
                                <th>{{ serial($user->employee_team_leaders, $key) }}</th>
                                <td>
                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $leader]) }}" target="_blank" class="text-{{ $leader->pivot->is_active == true ? 'success' : 'danger' }} text-bold">{{ $leader->name }}</a>
                                </td>
                                <td>
                                    <span>{{ show_date($leader->pivot->created_at) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>


    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Interactions With User(s)</h5>
        
                <div class="card-header-elements ms-auto">
                    @if ($user->user_interactions->count() > 0) 
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger waves-effect" data-bs-toggle="modal" data-bs-target="#removeUserModal" title="Remove User">
                            <i class="ti ti-user-minus me-1"></i> 
                            Remove
                        </a>
                    @endif
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#addNewUsersModal" title="Add User(s)">
                        <i class="ti ti-users-plus me-1"></i> 
                        Add
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->user_interactions as $key => $employee) 
                            <tr>
                                <th>{{ serial($employee->user_interactions, $key) }}</th>
                                <th>
                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $employee]) }}" target="_blank" class="text-primary text-bold">{{ $employee->name }}</a>
                                </th>
                                <td>{{ $employee->roles->first()->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
<!--/ User Profile Content -->



{{-- Update Team Leader Modal --}}
@include('administration.settings.user.includes.modals.update_team_leader')

{{-- Add Users Modal --}}
@include('administration.settings.user.includes.modals.add_users')

{{-- remove Users Modal --}}
@include('administration.settings.user.includes.modals.remove_users')


@endsection