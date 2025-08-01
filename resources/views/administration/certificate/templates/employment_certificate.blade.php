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
                <strong>{{ $certificate->user->name }}</strong>, Employee ID <strong>{{ $certificate->user->userid }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'S/O' : 'D/O' }} <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, Date of Birth <strong>{{ (isset($certificate) ? show_date($certificate->user->employee->birth_date, 'F j, Y') : 'N/A') }}</strong>, is currently employed with <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong> since <strong>{{ $certificate->formatted_joining_date }}</strong> till to date under my supervision.
            </p>

            <p>
                @if($certificate->user->employee->gender == 'Male')
                    Mr.
                @elseif($certificate->user->employee->gender == 'Female')
                    Mrs./Miss.
                @else
                    Mr./Mrs./Miss.
                @endif
                <strong>{{ $certificate->user->name }}</strong> is a full-time <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong> and {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} current total salary is <strong>BDT {{ $certificate->formatted_salary }}</strong> including all allowances. {{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} has rendered {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} services with the highest degree of responsibility with a professional attitude and we wish {{ $certificate->user->employee->gender == 'Male' ? 'him' : 'her' }} all the best in {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} life.
            </p>

            <p>Please feel free to contact us for any further information.</p>
        </div>
    </div>
@endsection
