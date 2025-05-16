@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Employee Shift Update</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    @php
        $oldStartTime = date('h:i A', strtotime($oldShift->start_time));
        $oldEndTime = date('h:i A', strtotime($oldShift->end_time));
        $newStartTime = date('h:i A', strtotime($newShift->start_time));
        $newEndTime = date('h:i A', strtotime($newShift->end_time));

        // Get the active team leader ID if it exists
        $activeTeamLeader = $user->active_team_leader;
        $activeTeamLeaderId = $activeTeamLeader ? $activeTeamLeader->id : null;
    @endphp

    @if ($notifiableUser->id === $user->id)
        <!-- Email content for the employee -->
        Dear {{ $user->employee->alias_name }},
        <br>
        We would like to inform you that your work shift has been updated by {{ $authUser->employee->alias_name }}.
        <br>
        <strong>Previous Shift:</strong> {{ $oldStartTime }} - {{ $oldEndTime }}
        <br>
        <strong>New Shift:</strong> {{ $newStartTime }} - {{ $newEndTime }}
        <br>
        This change is effective from {{ date('d M, Y', strtotime($newShift->implemented_from)) }}.
        <br>
        Please adjust your schedule accordingly. If you have any questions or concerns regarding this change, please contact your team leader or HR department.

    @elseif ($activeTeamLeaderId && $notifiableUser->id === $activeTeamLeaderId)
        <!-- Email content for the team leader -->
        Dear {{ $notifiableUser->employee->alias_name }},
        <br>
        This is to inform you that the work shift of your team member <strong>{{ $user->employee->alias_name }}</strong> has been updated by {{ $authUser->employee->alias_name }}.
        <br>
        <strong>Previous Shift:</strong> {{ $oldStartTime }} - {{ $oldEndTime }}
        <br>
        <strong>New Shift:</strong> {{ $newStartTime }} - {{ $newEndTime }}
        <br>
        This change is effective from {{ date('d M, Y', strtotime($newShift->implemented_from)) }}.
        <br>
        Please make any necessary adjustments to your team's schedule and ensure proper coverage during all shifts.

    @else
        <!-- Email content for users with permissions -->
        Dear {{ $notifiableUser->employee->alias_name }},
        <br>
        This is to inform you that the work shift of <strong>{{ $user->employee->alias_name }}</strong> has been updated by {{ $authUser->employee->alias_name }}.
        <br>
        <strong>Previous Shift:</strong> {{ $oldStartTime }} - {{ $oldEndTime }}
        <br>
        <strong>New Shift:</strong> {{ $newStartTime }} - {{ $newEndTime }}
        <br>
        This change is effective from {{ date('d M, Y', strtotime($newShift->implemented_from)) }}.
    @endif
    <br><br>

    Best Regards,
    <br>
    {{ config('app.name') }} Management
</div>
<!-- End Content -->
@endsection
