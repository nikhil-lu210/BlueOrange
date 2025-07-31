@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <h1><u>No Objection Certificate</u></h1>
        <h3><u>To Whom It May Concern</u></h3>

        <div class="letter-content">
            <p>This letter is to confirm that Mr./Mrs./Miss <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, is employed at <strong>{{ config('certificate.company.name') }}</strong> since <strong>{{ $certificate->formatted_joining_date }}</strong> on a fulltime basis as <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

            <p>Mr./Mrs./Miss <strong>{{ $certificate->user->name }}</strong> has expressed interest in visiting <strong>{{ $certificate->country_name }}</strong> for <strong>{{ $certificate->visiting_purpose }}</strong> and for this reason she/he has booked holiday from <strong>{{ $certificate->formatted_leave_starts_from }}</strong>@if($certificate->leave_ends_on) until <strong>{{ $certificate->formatted_leave_ends_on }}</strong>@endif. Our organization has no objection regarding his/her visit. She/He will report to the office after @if($certificate->leave_ends_on) <strong>{{ $certificate->formatted_leave_ends_on }}</strong> @else returning from the leave @endif.</p>

            <p>Please feel free to contact us for any further information.</p>
        </div>
    </div>
@endsection
