@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <div style="text-align: right; margin-bottom: 20px; font-size: 14px; color: #666;">
            <strong>Reference No: {{ $certificate->formatted_reference_no ?? 'CERT-' . ($certificate->reference_no ?? 'XXXXXXXXXX') }}</strong>
        </div>

        <h1><u>Employment Certificate</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed at <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

            <p>He/She joined on <strong>{{ $certificate->formatted_joining_date }}</strong> and is currently working with us. During the tenure, his/her performance and conduct have been satisfactory.</p>

            @if($certificate->salary)
            <p>His/Her current salary is <strong>BDT {{ $certificate->formatted_salary }}</strong> per month.</p>
            @endif

            <p>This certificate is issued upon request for whatever purpose it may serve best.</p>

            <p>Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
        </div>

        <div class="signature">
            <p>Best Regards,</p>
            <br><br>
            <p style="font-weight: 100;">_____________________________</p>
            <p><strong>MD. Abdul Razzak Chowdhury</strong></p>
            <p>General Manager</p>
            <p>{{ config('certificate.company.name') }}</p>
        </div>
    </div>
@endsection
