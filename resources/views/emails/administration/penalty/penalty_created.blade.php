@extends('layouts.email.app')

@section('email_title')
    @if($user->id === $penalty->user_id)
        <span style="text-align: center;">Penalty Assigned by <b>{{ $penalty->creator->alias_name }}</b></span>
    @else
        <span style="text-align: center;">Team Member Penalty: <b>{{ $penalty->user->alias_name }}</b> by <b>{{ $penalty->creator->alias_name }}</b></span>
    @endif
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br><br>
    @if($user->id === $penalty->user_id)
        A penalty has been assigned to you by {{ $penalty->creator->alias_name }}.
    @else
        Your team member <strong>{{ $penalty->user->alias_name }}</strong> has received a penalty assigned by {{ $penalty->creator->alias_name }}.
    @endif
    <br><br>

    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
        <h3 style="color: #dc3545; margin-top: 0;">Penalty Details</h3>
        <p><strong>Type:</strong> {{ $penalty->type }}</p>
        <p><strong>Penalty Time:</strong> {{ $penalty->total_time_formatted }}</p>
        <p><strong>Date:</strong> {{ show_date_time($penalty->created_at) }}</p>
    </div>

    <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ffc107;">
        <h4 style="color: #856404; margin-top: 0;">Related Attendance</h4>
        <p><strong>Date:</strong> {{ show_date($penalty->attendance->clock_in_date) }}</p>
        <p><strong>Type:</strong> {{ $penalty->attendance->type }}</p>
        <p><strong>Clock In:</strong> {{ show_time($penalty->attendance->clock_in) }}</p>
        <p><strong>Clock Out:</strong> {{ $penalty->attendance->clock_out ? show_time($penalty->attendance->clock_out) : 'Ongoing' }}</p>
    </div>

    @if($penalty->reason)
    <div style="background-color: #f1f3f4; padding: 15px; border-radius: 8px; margin: 15px 0;">
        <h4 style="color: #495057; margin-top: 0;">Reason</h4>
        <p>{!! $penalty->reason !!}</p>
    </div>
    @endif

    <br>
    You can view the full penalty details by clicking <a href="{{ route('administration.penalty.show', $penalty) }}"><strong>here</strong></a>.
    <br><br>

    @if($user->id === $penalty->user_id)
        If you have any questions or concerns about this penalty, please contact your supervisor or HR department.
        <br><br>
    @endif

    Best regards,<br>
    {{ config('app.name') }} Team
</div>
<!-- End Content -->
@endsection
