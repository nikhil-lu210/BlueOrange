@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <div style="text-align: right; margin-bottom: 20px; font-size: 14px; color: #666;">
            <strong>Reference No: {{ $certificate->formatted_reference_no ?? 'CERT-' . ($certificate->reference_no ?? 'XXXXXXXXXX') }}</strong>
        </div>

        <h1><u>Appointment Letter</u></h1>

        <div class="letter-content">
            <p><strong>Date:</strong> {{ $certificate->formatted_issue_date }}</p>
            <br>
            <p><strong>To,</strong></p>
            <p><strong>{{ $certificate->user->name }}</strong></p>
            <p>Son/Daughter of {{ $certificate->user->employee->father_name ?? 'N/A' }}</p>
            <br>

            <p><strong>Subject: Appointment Letter</strong></p>
            <br>

            <p>Dear {{ $certificate->user->name }},</p>

            <p>We are pleased to inform you that you have been selected for the position of <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong> at <strong>{{ config('certificate.company.name') }}</strong>.</p>

            <p>Your appointment will be effective from <strong>{{ $certificate->formatted_joining_date }}</strong>. Your monthly salary will be <strong>BDT {{ $certificate->formatted_salary }}</strong>.</p>

            <p>You are required to report to the office on your joining date with all necessary documents. Please bring the following documents:</p>
            <ul>
                <li>Original and photocopy of educational certificates</li>
                <li>Experience certificates (if any)</li>
                <li>National ID card photocopy</li>
                <li>Recent passport size photographs</li>
            </ul>

            <p>We look forward to your valuable contribution to our organization.</p>

            <p>Congratulations and welcome to {{ config('certificate.company.name') }}!</p>
        </div>

        <div class="signature">
            <p>Sincerely,</p>
            <br><br>
            <p style="font-weight: 100;">_____________________________</p>
            <p><strong>MD. Abdul Razzak Chowdhury</strong></p>
            <p>General Manager</p>
            <p>{{ config('certificate.company.name') }}</p>
        </div>
    </div>
@endsection
