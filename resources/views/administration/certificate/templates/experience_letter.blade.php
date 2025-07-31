@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <div style="text-align: right; margin-bottom: 20px; font-size: 14px; color: #666;">
            <strong>Reference No: {{ $certificate->formatted_reference_no ?? 'CERT-' . ($certificate->reference_no ?? 'XXXXXXXXXX') }}</strong>
        </div>

        <h1><u>Experience Letter</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed at <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

            <p>He/She joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and resigned on <strong>{{ $certificate->formatted_resignation_date }}</strong>. During his/her tenure with us, he/she demonstrated excellent professional skills and maintained good conduct.</p>

            @if($certificate->leave_starts_from)
            <p>He/She has been granted leave starting from <strong>{{ $certificate->formatted_leave_starts_from }}</strong>@if($certificate->leave_ends_on) until <strong>{{ $certificate->formatted_leave_ends_on }}</strong>@endif.</p>
            @endif

            <p>We found him/her to be hardworking, sincere, and dedicated to his/her responsibilities. He/She has been a valuable asset to our organization.</p>

            <p>We wish him/her all the best for his/her future endeavors.</p>

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
