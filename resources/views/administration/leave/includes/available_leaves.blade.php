@php
    $user = $leaveHistory->user;
@endphp
<div class="card mb-4">
    <div class="card-body">
        <small class="card-text text-uppercase">Leave Availables</small>
        <table class="table table-bordered table-responsive mt-3 mb-1" style="width: 100%;">
            <thead>
                <tr class="bg-label-success">
                    <th class="text-bold">Leave Type</th>
                    <th class="text-bold">Available Leave</th>
                </tr>
            </thead>
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
        </table>
    </div>
</div>