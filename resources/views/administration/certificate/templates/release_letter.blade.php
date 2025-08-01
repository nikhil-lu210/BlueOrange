@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <h1><u>Release Letter by Employer</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'son' : 'daughter' }} of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed with <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} employment has been terminated by the company effective <strong>{{ $certificate->formatted_release_date }}</strong>.</p>

            <p><strong>Reason for Release:</strong> {{ $certificate->release_reason }}</p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} has completed the necessary handover procedures and cleared all dues and obligations with the company as per company policy. All company properties, documents, and materials have been returned.</p>

            <p>During {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} employment period with us, all assigned responsibilities have been completed as per the terms of employment.</p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} is hereby officially released from all contractual obligations and employment responsibilities with <strong>{{ config('certificate.company.name') }}</strong> effective from the above-mentioned date.</p>

            <p>This release letter serves as official documentation of the termination of employment relationship between <strong>{{ $certificate->user->name }}</strong> and <strong>{{ config('certificate.company.name') }}</strong>.</p>

            <p>This release letter is issued for official and legal purposes.</p>

            <p>Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
        </div>
    </div>
@endsection
