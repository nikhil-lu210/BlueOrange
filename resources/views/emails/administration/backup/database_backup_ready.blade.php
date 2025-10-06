@extends('layouts.email.app')

@section('email_title')
    @php
        $dateStr = '';
        if (preg_match('/si_app_db_backup_(\d{8})\.sql/', $filename, $matches)) {
            $date = \Carbon\Carbon::createFromFormat('dmY', $matches[1]);
            $dateStr = $date->format('jS F, Y');
        }
    @endphp
    <span style="text-align: center;">{{ config('app.name') }} Database Backup of {{ $dateStr }} Has Been Ready</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Hello,
    <br><br>

    Your daily database backup has been successfully created and is ready for download.
    <br><br>

    <!-- Backup Information Card -->
    <div style="
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        background-color: #f8fafc;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    ">
        <h3 style="margin-top: 0; color: #28a745; text-align: center;">📊 Backup Details</h3>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>Backup Date:</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">{{ $backupDate }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>File Name:</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">{{ $filename }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;"><strong>File Size:</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">{{ $fileSize }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0;"><strong>Storage Location:</strong></td>
                <td style="padding: 8px 0;">storage/app/public/db_backup/</td>
            </tr>
        </table>
    </div>

    <!-- Download Section -->
    <div style="text-align: center; margin: 30px 0;">
        <div style="
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
        ">
            <h3 style="margin-top: 0; color: #1976d2;">📥 Download Your Backup</h3>
            <p>Click the button below to download your database backup file:</p>

            <a href="{{ $downloadUrl }}"
               style="background-color: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-size: 16px; margin: 10px;">
                Download Database Backup
            </a>
        </div>
    </div>

    <!-- Important Notes -->
    <div style="
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
    ">
        <strong>⚠️ Important Notes:</strong>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>This backup file contains sensitive data - please handle it securely</li>
            <li>The backup will be automatically deleted after 3 days</li>
            <li>Keep this file in a secure location</li>
            <li>Do not share this download link with unauthorized persons</li>
        </ul>
    </div>

    <br>

    Best Regards,
    <br>
    {{ config('app.name') }} Backup System
</div>
<!-- End Content -->
@endsection
