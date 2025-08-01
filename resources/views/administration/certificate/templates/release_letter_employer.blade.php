@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <h1><u>Release Letter by Employer</u></h1>
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
            <p>This is to inform you that you are released from the services of the company with effect from the close of office hours on <strong>{{ $certificate->formatted_release_date }}</strong> due to <strong>{{ $certificate->release_reason }}</strong>.</p>

            <p>We also certify that your full and final settlement of account with the organization (if any) will be cleared as per the company payment schedule.</p>

            <p>Thanks for your service for the company from <strong>{{ $certificate->formatted_joining_date }}</strong>. We truly value the contributions you've made during your time with us. We intend to reach out to you again in the future if a suitable contract becomes available that will match your skill set.</p>

            <p>We wish you all the best in your future endeavors.</p>
        </div>
    </div>
@endsection
