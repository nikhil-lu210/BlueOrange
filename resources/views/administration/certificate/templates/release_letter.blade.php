@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <div style="text-align: right; margin-bottom: 20px; font-size: 14px; color: #666;">
            <strong>Reference No: {{ $certificate->formatted_reference_no ?? 'CERT-' . ($certificate->reference_no ?? 'XXXXXXXXXX') }}</strong>
        </div>

        <h1><u>Release Letter</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p><strong>Date:</strong> {{ $certificate->formatted_issue_date }}</p>
            <br>

            <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed at <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

            <p>He/She joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and has been released from his/her duties effective <strong>{{ $certificate->formatted_release_date }}</strong>.</p>

            <p><strong>Reason for Release:</strong> {{ $certificate->release_reason }}</p>

            <p>During his/her tenure with us, he/she has completed all assigned responsibilities and has cleared all dues and obligations with the company.</p>

            <p>We hereby release him/her from all contractual obligations with <strong>{{ config('certificate.company.name') }}</strong> and wish him/her success in his/her future endeavors.</p>

            <p>This release letter is issued upon request and for official purposes.</p>
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
