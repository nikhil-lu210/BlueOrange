@extends('layouts.email.app')

@section('email_title')
    <span style="text-align: center;">{{ $certificate->type }} Certificate</span>
@endsection

@section('content')
<!-- Start Content -->
<div>
    Dear <b>{{ $user->name }}</b>,
    <br><br>
    
    We hope this email finds you well. Please find your <strong>{{ $certificate->type }}</strong> certificate below.
    <br><br>
    
    <!-- Certificate Card -->
    <div style="
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 30px;
        margin: 20px 0;
        background-color: #f8fafc;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    ">
        <!-- Certificate Header -->
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="
                color: #2d3748;
                font-size: 28px;
                font-weight: bold;
                margin: 0;
                text-decoration: underline;
            ">{{ $certificate->type }}</h2>
            @if($certificate->type !== 'Appointment Letter')
                <h3 style="
                    color: #4a5568;
                    font-size: 20px;
                    font-weight: normal;
                    margin: 10px 0 0 0;
                    text-decoration: underline;
                ">To Whom It May Concern</h3>
            @endif
        </div>
        
        <!-- Certificate Content -->
        <div style="
            color: #2d3748;
            line-height: 1.8;
            font-size: 16px;
            text-align: justify;
        ">
            @if($certificate->type === 'Appointment Letter')
                <p>Dear <strong>{{ $certificate->user->name }}</strong>,</p>
                <p>We are pleased to inform you that you have been selected for the position of <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong> at <strong>{{ config('certificate.company.name') }}</strong>.</p>
                <p>Your appointment will be effective from <strong>{{ $certificate->formatted_joining_date }}</strong>. Your monthly salary will be <strong>BDT {{ $certificate->formatted_salary }}</strong>.</p>
                <p>We look forward to your valuable contribution to our organization.</p>
                <p>Welcome to the team!</p>
            @elseif($certificate->type === 'Employment Certificate')
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
            @elseif($certificate->type === 'Experience Letter')
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
            @elseif($certificate->type === 'Release Letter by Employee')
                <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'son' : 'daughter' }} of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed with <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>
                <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and submitted {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} resignation application on <strong>{{ $certificate->formatted_resign_application_date }}</strong>.</p>
                <p>After due consideration, {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} resignation was accepted and approved by the management on <strong>{{ $certificate->formatted_resignation_approval_date }}</strong>. {{ $certificate->user->employee->gender == 'Male' ? 'His' : 'Her' }} last working day with the company was <strong>{{ $certificate->formatted_resignation_date }}</strong>.</p>
                <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} is hereby officially released from all contractual obligations and employment responsibilities with <strong>{{ config('certificate.company.name') }}</strong> effective <strong>{{ $certificate->formatted_release_date }}</strong>.</p>
                <p>We acknowledge {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} service to our organization and wish {{ $certificate->user->employee->gender == 'Male' ? 'him' : 'her' }} success and prosperity in {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} future career endeavors.</p>
            @elseif($certificate->type === 'Release Letter by Employer')
                <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'son' : 'daughter' }} of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed with <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>
                <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} employment has been terminated by the company effective <strong>{{ $certificate->formatted_release_date }}</strong>.</p>
                <p><strong>Reason for Release:</strong> {{ $certificate->release_reason }}</p>
                <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} is hereby officially released from all contractual obligations and employment responsibilities with <strong>{{ config('certificate.company.name') }}</strong> effective from the above-mentioned date.</p>
            @elseif($certificate->type === 'NOC/No Objection Letter')
                <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, {{ $certificate->user->employee->gender == 'Male' ? 'son' : 'daughter' }} of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, is currently employed with <strong>{{ config('certificate.company.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>
                <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} has applied for permission to visit <strong>{{ $certificate->country_name }}</strong> for <strong>{{ $certificate->visiting_purpose }}</strong> purposes.</p>
                <p>We have no objection to {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} travel from <strong>{{ $certificate->formatted_leave_starts_from }}</strong>@if($certificate->leave_ends_on) to <strong>{{ $certificate->formatted_leave_ends_on }}</strong>@endif.</p>
                <p>{{ $certificate->user->employee->gender == 'Male' ? 'He' : 'She' }} will resume {{ $certificate->user->employee->gender == 'Male' ? 'his' : 'her' }} duties upon return.</p>
            @endif
            
            <p style="margin-top: 30px;">Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
        </div>
        
        <!-- Certificate Footer -->
        <div style="
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        ">
            <p style="
                color: #4a5568;
                font-size: 14px;
                margin: 0;
                font-style: italic;
            ">
                Certificate Reference: <strong>{{ $certificate->reference_no }}</strong>
            </p>
        </div>
    </div>
    
    <!-- Instructions -->
    <div style="
        background-color: #edf2f7;
        border-left: 4px solid #4299e1;
        padding: 15px 20px;
        margin: 20px 0;
        border-radius: 0 4px 4px 0;
    ">
        <p style="
            color: #2d3748;
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
        ">
            <strong>📋 Important Note:</strong> This is a digital copy of your certificate. For a printed copy with official seal and signature, please contact the administration office or visit the management department.
        </p>
    </div>
    
    <br>
    If you have any questions or need assistance, please don't hesitate to contact our administration office.
    <br><br>
    
    Best Regards,
    <br>
    <strong>{{ config('app.name') }}</strong>
    <br>
    Administration Department
</div>
<!-- End Content -->
@endsection
