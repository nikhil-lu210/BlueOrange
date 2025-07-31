@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <h1><u>Experience Letter</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'son' : 'daughter' }} of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed at <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and resigned on <strong>{{ $certificate->formatted_resignation_date }}</strong>. During {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} tenure with us, {{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} demonstrated excellent professional skills and maintained good conduct.</p>

            @if($certificate->leave_starts_from)
            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} has been granted leave starting from <strong>{{ $certificate->formatted_leave_starts_from }}</strong>@if($certificate->leave_ends_on) until <strong>{{ $certificate->formatted_leave_ends_on }}</strong>@endif.</p>
            @endif

            <p>We found him/her to be hardworking, sincere, and dedicated to {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} responsibilities. {{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} has been a valuable asset to our organization.</p>

            <p>We wish him/her all the best for {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} future endeavors.</p>

            <p>This certificate is issued upon request for whatever purpose it may serve best.</p>

            <p>Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
        </div>
    </div>
@endsection
