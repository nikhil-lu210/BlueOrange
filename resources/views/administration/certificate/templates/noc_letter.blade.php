@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; color: #666;">
            <strong>Ref: {{ $certificate->formatted_reference_no ?? 'CERT-' . ($certificate->reference_no ?? 'XXXXXXXXXX') }}</strong>
            <strong>Date: {{ $certificate->formatted_issue_date }}</strong>
        </div>

        <h1><u>No Objection Certificate</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, is currently employed at <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

            <p>He/She joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and is a regular employee in good standing.</p>

            <p>We have <strong>NO OBJECTION</strong> to his/her visit to <strong>{{ $certificate->country_name }}</strong> for <strong>{{ $certificate->visiting_purpose }}</strong>.</p>

            @if($certificate->leave_starts_from)
            <p>He/She has been granted leave starting from <strong>{{ $certificate->formatted_leave_starts_from }}</strong>@if($certificate->leave_ends_on) until <strong>{{ $certificate->formatted_leave_ends_on }}</strong>@endif for this purpose.</p>
            @endif

            <p>We assure that he/she will return to resume his/her duties after the completion of his/her visit. The company will continue to employ him/her upon his/her return.</p>

            <p>This certificate is issued upon his/her request for visa and immigration purposes.</p>

            <p>We wish him/her a safe and successful journey.</p>
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
