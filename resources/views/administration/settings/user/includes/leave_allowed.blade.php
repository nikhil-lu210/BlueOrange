@extends('administration.settings.user.show')

@section('profile_content')

<!-- User Profile Content -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <div class="title-and-info">
                    <h5 class="mb-0">Remaining Available Leave</h5>
                    <small>
                        <b class="text-dark">{{ date('F d, Y') }}</b> 
                        to 
                        <b class="text-dark">{{ $user->allowed_leave->implemented_to->format('F d'). ', ' . date('Y') }}</b>
                    </small>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr class="bg-label-primary">
                            <th class="text-bold">Leave Type</th>
                            <th class="text-bold">Available Leave</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Earned Leave</th>
                            <td>96h 30m 00s</td>
                        </tr>
                        <tr>
                            <th>Sick Leave</th>
                            <td>96h 30m 00s</td>
                        </tr>
                        <tr>
                            <th>Casual Leave</th>
                            <td>96h 30m 00s</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>        
    </div>


    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <div class="title-and-info">
                    <h5 class="mb-0">Allowed Leave</h5>
                    <small>
                        <b class="text-dark">{{ $user->allowed_leave->implemented_from->format('F d') }}</b> 
                        to 
                        <b class="text-dark">{{ $user->allowed_leave->implemented_to->format('F d') }}</b>
                    </small>
                </div>
        
                <div class="card-header-elements ms-auto">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#upgradeLeaveModal" title="Add User(s)">
                        <i class="ti ti-calendar-plus me-1"></i> 
                        Upgrade Leave
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr class="bg-label-success">
                            <th class="text-bold">Leave Type</th>
                            <th class="text-bold">Allowed Leave</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Earned Leave</th>
                            <td>{{ show_hr_min_sec($user->allowed_leave->earned_leave) }}</td>
                        </tr>
                        <tr>
                            <th>Sick Leave</th>
                            <td>{{ show_hr_min_sec($user->allowed_leave->sick_leave) }}</td>
                        </tr>
                        <tr>
                            <th>Casual Leave</th>
                            <td>{{ show_hr_min_sec($user->allowed_leave->casual_leave) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
<!--/ User Profile Content -->



{{-- Update Team Leader Modal --}}
@include('administration.settings.user.includes.modals.upgrade_leave')


@endsection