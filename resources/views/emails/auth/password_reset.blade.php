@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Password Reset Request</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello <b>{{ $user->employee->alias_name ?? $user->name }}</b>,
    <br><br>
    You are receiving this email because we received a password reset request for your account.
    <br>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $resetUrl }}"
           style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
            Reset Password
        </a>
    </div>

    <div style="margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #007bff;">
        <strong>Security Information:</strong>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>This password reset link will expire in 60 minutes</li>
            <li>If you did not request a password reset, no further action is required. Just permanently delete this email.</li>
            <li>For security reasons, please do not share this link with anyone</li>
        </ul>
    </div>

    If you're having trouble clicking the <b>"Reset Password"</b> button, copy and paste the URL below into your web browser:
    <br>
    <a href="{{ $resetUrl }}" style="color: #007bff; word-break: break-all;">{{ $resetUrl }}</a>
    <br><br>

    Best Regards,
    <br>
    {{ config('app.name') }} Team
</div>
<!-- End Content -->
@endsection
