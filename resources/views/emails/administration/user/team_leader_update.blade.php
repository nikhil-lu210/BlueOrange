@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Team Leader Update</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    @if ($notifiableUser->id === $employee->id)
        <!-- Email content for the employee -->
        Dear {{ $employee->employee->alias_name }},
        <br>
        We would like to inform you that your Team Leader has been updated
        from <strong>{{ $oldTeamLeader ? $oldTeamLeader->employee->alias_name : 'None' }}</strong>
        to <strong>{{ $newTeamLeader->employee->alias_name }}</strong> by {{ $authUser->employee->alias_name }}.
        <br>
        Please reach out to your new Team Leader for any assistance or guidance you may need.

    @elseif ($oldTeamLeader && $notifiableUser->id === $oldTeamLeader->id)
        <!-- Email content for the old team leader -->
        Dear {{ $oldTeamLeader->employee->alias_name }},
        <br>
        This is to inform you that you are no longer assigned as the Team Leader for
        <strong>{{ $employee->employee->alias_name }}</strong>.
        <br>
        <strong>{{ $newTeamLeader->employee->alias_name }}</strong> has been assigned as the new Team Leader.
        <br>
        Thank you for your leadership and support during your time as Team Leader.

    @elseif ($notifiableUser->id === $newTeamLeader->id)
        <!-- Email content for the new team leader -->
        Dear {{ $newTeamLeader->employee->alias_name }},
        <br>
        Congratulations! You have been assigned as the Team Leader for
        <strong>{{ $employee->employee->alias_name }}</strong> by {{ $authUser->employee->alias_name }}.
        <br>
        Please ensure you provide the necessary guidance and support to your team member.
    @endif
    <br><br>

    Best Regards,
    <br>
    {{ config('app.name') }} Management
</div>
<!-- End Content -->
@endsection
