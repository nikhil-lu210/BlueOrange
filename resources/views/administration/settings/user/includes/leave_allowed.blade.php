@extends('administration.settings.user.show')

@section('css_links_user_show')
    {{--  External CSS  --}}

@endsection

@section('profile_content')

<!-- User Profile Content -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <div class="title-and-info">
                    <h5 class="mb-0">Remaining Available Leave</h5>
                    @isset ($user->allowed_leave)
                        <small>
                            <b class="text-dark">{{ date('F d, Y') }}</b>
                            to
                            <b class="text-dark">{{ $user->allowed_leave->implemented_to->format('F d'). ', ' . date('Y') }}</b>
                        </small>
                    @endisset
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
                    @if ($user->available_leaves())
                        <tbody>
                            <tr>
                                <th>Earned Leave</th>
                                <td>{{ $user->available_leaves()->earned_leave }}</td>
                            </tr>
                            <tr>
                                <th>Sick Leave</th>
                                <td>{{ $user->available_leaves()->sick_leave }}</td>
                            </tr>
                            <tr>
                                <th>Casual Leave</th>
                                <td>{{ $user->available_leaves()->casual_leave }}</td>
                            </tr>
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <div class="title-and-info">
                    <h5 class="mb-0">Allowed Leave</h5>
                    @isset ($user->allowed_leave)
                        <small>
                            <b class="text-dark">{{ $user->allowed_leave->implemented_from->format('F d') }}</b>
                            to
                            <b class="text-dark">{{ $user->allowed_leave->implemented_to->format('F d') }}</b>
                        </small>
                    @endisset
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
                    @isset ($user->allowed_leave)
                        <tbody>
                            <tr>
                                <th>Earned Leave</th>
                                <td>{{ $user->allowed_leave->earned_leave->forHumans() }}</td>
                            </tr>
                            <tr>
                                <th>Sick Leave</th>
                                <td>{{ $user->allowed_leave->sick_leave->forHumans() }}</td>
                            </tr>
                            <tr>
                                <th>Casual Leave</th>
                                <td>{{ $user->allowed_leave->casual_leave->forHumans() }}</td>
                            </tr>
                        </tbody>
                    @endisset
                </table>
            </div>
        </div>
    </div>
</div>
<!--/ User Profile Content -->



{{-- Update Team Leader Modal --}}
@include('administration.settings.user.includes.modals.upgrade_leave')


@endsection


@section('script_links_user_show')
    {{--  External Javascript Links --}}

@endsection

@section('custom_script_user_show')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Handle Edit Minute & Seconds click
            let editEnabled = false;
            $('#editMinutesSeconds').on('click', function(e) {
                e.preventDefault();

                if (!editEnabled) {
                    // Enable editing
                    $('.time-min, .time-sec').prop('readonly', false);
                    $(this).text('Lock Minute & Seconds').addClass('text-danger').removeClass('text-primary');
                    editEnabled = true;
                } else {
                    // Disable editing
                    $('.time-min, .time-sec').prop('readonly', true);
                    $(this).text('Edit Minute & Seconds').addClass('text-primary').removeClass('text-danger');
                    editEnabled = false;
                }
            });
        });
    </script>
@endsection
