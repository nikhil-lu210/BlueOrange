@extends('administration.certificate.layouts.certificate_main_layout')

@section('certificate_content')
    <div class="certificate-content">
        <h1><u>Experience Letter</u></h1>
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
                <strong>{{ $certificate->user->name }}</strong>, Employee ID <strong>{{ $certificate->user->id }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'S/O' : 'D/O' }} <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, Date of Birth <strong>{{ $certificate->user->employee->birth_date ? \Carbon\Carbon::parse($certificate->user->employee->birth_date)->format('F j, Y') : 'N/A' }}</strong>, has worked as {{ $certificate->user->employee->gender == 'Male' ? 'an' : 'a' }} <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong> in our organization from <strong>{{ $certificate->formatted_joining_date }}</strong> to <strong>{{ $certificate->formatted_resignation_date }}</strong> under my supervision.
            </p>

            <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} has rendered {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} services with the highest degree of responsibility with a professional attitude and we wish {{ $certificate->user->employee->gender == 'Male' ? 'him' : 'her' }} all the best in {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} life.</p>

            <p>Please feel free to contact us for any further information.</p>

            <p>Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
        </div>
    </div>
@endsection
