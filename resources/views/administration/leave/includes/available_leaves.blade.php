@php
    // Handle both original way (with $leaveHistory) and new way (with $summary)
    if (isset($summary)) {
        $user = $user ?? $leaveHistory->user;
        $leaveData = $summary;
    } else {
        $user = $leaveHistory->user;
        $leaveData = $user->available_leaves();
    }
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
                    <td>
                        @if (isset($summary))
                            {{ $summary['earned_leave']['available'] }}
                        @else
                            {{ show_leave($leaveData->earned_leave) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Sick Leave</th>
                    <td>
                        @if (isset($summary))
                            {{ $summary['sick_leave']['available'] }}
                        @else
                            {{ show_leave($leaveData->sick_leave) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Casual Leave</th>
                    <td>
                        @if (isset($summary))
                            {{ $summary['casual_leave']['available'] }}
                        @else
                            {{ show_leave($leaveData->casual_leave) }}
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
