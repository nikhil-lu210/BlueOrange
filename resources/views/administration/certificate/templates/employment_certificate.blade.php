@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <h1><u>Employment Certificate</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>This is to certify that
                @if($certificate->user->employee->gender == 'Male')
                    Mr.
                @elseif($certificate->user->employee->gender == 'Female')
                    Mrs./Miss.
                @else
                    Mr./Mrs./Miss.
                @endif
                <strong>{{ $certificate->user->name }}</strong>, Employee ID <strong>{{ $certificate->user->id }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'S/O' : 'D/O' }} <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, is currently employed with <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.
            </p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and is presently working with us. {{ $certificate->user->employee->gender == 'Male' ? 'His' : 'Her' }} employment status is permanent and {{ $certificate->user->employee->gender == 'Male' ? 'he' : 'she' }} is a regular employee of our company.</p>

            @if($certificate->salary)
            <p>{{ $certificate->user->employee->gender == 'Male' ? 'His' : 'Her' }} current gross salary is <strong>BDT {{ $certificate->formatted_salary }}</strong> per month.</p>
            @endif

            <p>During {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} tenure with us, {{ $certificate->user->employee->gender == 'Male' ? 'he' : 'she' }} has demonstrated good professional conduct and has been performing {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} duties satisfactorily.</p>

            <p>This certificate is issued upon {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} request for official purposes.</p>

            <p>Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
        </div>
    </div>
@endsection
