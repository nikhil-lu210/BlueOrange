@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <h1><u>Release Letter by Employee</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'son' : 'daughter' }} of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed with <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and submitted {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} resignation application on <strong>{{ $certificate->formatted_resign_application_date }}</strong>.</p>

            <p>After due consideration, {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} resignation was accepted and approved by the management on <strong>{{ $certificate->formatted_resignation_approval_date }}</strong>. {{ $certificate->user->employee->gender == 'Male' ? 'His' : 'Her' }} last working day with the company was <strong>{{ $certificate->formatted_resignation_date }}</strong>.</p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} has successfully completed all handover procedures and cleared all dues and obligations with the company. All company properties, documents, and materials have been returned in good condition.</p>

            <p>During {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} employment period with us, {{ $certificate->user->employee->gender == 'Male' ? 'he' : 'she' }} has maintained professional conduct and performed {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} duties satisfactorily. {{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} has been a dedicated employee and contributed positively to our organization.</p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} is hereby officially released from all contractual obligations and employment responsibilities with <strong>{{ config('certificate.company.name') }}</strong> effective <strong>{{ $certificate->formatted_release_date }}</strong>.</p>

            <p>We acknowledge {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} service to our organization and wish {{ $certificate->user->employee->gender == 'Male' ? 'him' : 'her' }} success and prosperity in {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} future career endeavors.</p>

            <p>This release letter is issued upon {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} request for official and legal purposes.</p>

            <p>Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
        </div>
    </div>
@endsection
