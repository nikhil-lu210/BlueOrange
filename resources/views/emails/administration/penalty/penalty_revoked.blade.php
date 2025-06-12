@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Penalty Revoked by <b>{{ $deleted_by->alias_name }}</b></span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello {{ $user->alias_name }},
    <br><br>
    
    <div style="background-color: #d4edda; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #28a745;">
        <h3 style="color: #155724; margin-top: 0;">🎉 Congratulations!</h3>
        <p style="color: #155724; margin-bottom: 0;">Your penalty has been revoked by <strong>{{ $deleted_by->alias_name }}</strong>.</p>
    </div>

    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
        <h3 style="color: #495057; margin-top: 0;">Revoked Penalty Details</h3>
        <p><strong>Type:</strong> {{ $penalty->type }}</p>
        <p><strong>Penalty Time:</strong> {{ $penalty->total_time_formatted }}</p>
        <p><strong>Originally Created:</strong> {{ show_date_time($penalty->created_at) }}</p>
        <p><strong>Revoked On:</strong> {{ show_date_time(now()) }}</p>
    </div>

    @if($penalty->reason)
    <div style="background-color: #f1f3f4; padding: 15px; border-radius: 8px; margin: 15px 0;">
        <h4 style="color: #495057; margin-top: 0;">Original Reason</h4>
        <p>{!! $penalty->reason !!}</p>
    </div>
    @endif

    <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ffc107;">
        <h4 style="color: #856404; margin-top: 0;">⚠️ Important Reminder</h4>
        <p style="color: #856404;">
            You should always be careful about this and not make this kind of mistake again. 
            Please ensure you follow all company policies and procedures to maintain a positive work environment.
        </p>
    </div>

    <br>
    This penalty has been completely removed from your record.
    <br><br>

    Best regards,<br>
    {{ config('app.name') }} Team
</div>
<!-- End Content -->
@endsection
