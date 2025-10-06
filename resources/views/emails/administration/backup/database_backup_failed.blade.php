@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">Database Backup Failed - {{ $failureDate }}</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello,
    <br><br>

    The automated database backup process encountered an error and could not complete successfully.
    <br><br>

    <!-- Error Information Card -->
    <div style="
        border: 2px solid #dc3545;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        background-color: #f8d7da;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    ">
        <h3 style="margin-top: 0; color: #dc3545; text-align: center;">❌ Backup Failure Details</h3>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #f5c6cb;"><strong>Failure Time:</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f5c6cb;">{{ $failureDate }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #f5c6cb;"><strong>Backup Type:</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #f5c6cb;">Daily Database Backup</td>
            </tr>
            <tr>
                <td style="padding: 8px 0;"><strong>Status:</strong></td>
                <td style="padding: 8px 0; color: #dc3545; font-weight: bold;">FAILED</td>
            </tr>
        </table>
    </div>

    <!-- Error Message -->
    <div style="
        background-color: #fff5f5;
        border: 1px solid #fed7d7;
        color: #c53030;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
        font-family: monospace;
        font-size: 14px;
        white-space: pre-wrap;
        word-break: break-word;
    ">
        {{ $errorMessage }}
    </div>

    <!-- Action Required -->
    <div style="
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
    ">
        <strong>🔧 Action Required:</strong>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>Check database connectivity and credentials</li>
            <li>Verify mysqldump is installed and accessible</li>
            <li>Ensure sufficient disk space in backup directory</li>
            <li>Check file permissions for backup directory</li>
            <li>Review application logs for additional details</li>
            <li>Consider running manual backup to test the process</li>
        </ul>
    </div>

    <br>

    Best Regards,
    <br>
    {{ config('app.name') }} Backup System
</div>
<!-- End Content -->
@endsection
