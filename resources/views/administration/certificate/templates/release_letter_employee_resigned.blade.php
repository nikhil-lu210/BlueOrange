@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <h1><u>Release Letter by Employee</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>Dear
                @if($certificate->user->employee->gender == 'Male')
                    Mr.
                @elseif($certificate->user->employee->gender == 'Female')
                    Mrs./Miss.
                @else
                    Mr./Mrs./Miss.
                @endif
                <strong>{{ $certificate->user->name }}</strong>,
            </p>
            <p>This has reference to your letter of resignation dated <strong>{{ $certificate->formatted_resignation_approval_date }}</strong>, wherein you have requested to be relieved from the services of the company on <strong>{{ $certificate->formatted_resign_application_date }}</strong> due to <strong>{{ $certificate->release_reason }}</strong>.</p>

            <p>We wish to inform you that your resignation is hereby accepted and you are being relieved from the services of the company with effect from close of office hours on <strong>{{ $certificate->formatted_release_date }}</strong>.</p>

            <p>We also certify that your full and final settlement of account with the organization (if any) will be cleared soon as possible.</p>

            <p>Thanks for your service for the company from <strong>{{ $certificate->formatted_joining_date }}</strong> to <strong>{{ $certificate->formatted_release_date }}</strong>. We truly value the contributions you've made during your time with us.</p>
        </div>
    </div>
@endsection
