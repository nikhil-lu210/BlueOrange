<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->type }} - {{ $certificate->user->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            background: #fff;
        }

        .certificate-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            width: 100%;
            min-height: 100vh;
        }

        .certificate-container::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 80%;
            height: 80%;
            background: url('{{ asset(config('app.logo')) }}') no-repeat center center;
            background-size: contain;
            opacity: 0.1;
            transform: translate(-50%, -50%);
            z-index: 0;
        }

        .certificate-content {
            position: relative;
            z-index: 1;
        }

        h1, h3 {
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        h1 u, h3 u {
            text-decoration: underline;
        }

        p {
            margin-bottom: 10px;
            line-height: 1.6;
            text-align: justify;
        }

        .letter-content {
            margin-top: 30px;
        }

        .signature {
            margin-top: 60px;
        }

        .signature p {
            margin-bottom: 5px;
        }

        ul {
            margin-left: 20px;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }

            .certificate-container {
                margin: 0 !important;
                padding: 20mm !important;
                box-shadow: none !important;
                page-break-inside: avoid;
                min-height: auto;
            }

            @page {
                size: A4;
                margin: 0;
            }

            .print-controls {
                display: none !important;
            }
        }

        /* Print Controls */
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .print-controls button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .print-controls button:hover {
            background: #0056b3;
        }

        .print-controls .close-btn {
            background: #6c757d;
        }

        .print-controls .close-btn:hover {
            background: #545b62;
        }
    </style>
</head>

<body>
    <!-- Print Controls -->
    <div class="print-controls">
        <button onclick="window.print()">
            🖨️ Print Certificate
        </button>
        <button class="close-btn" onclick="window.close()">
            ✕ Close
        </button>
    </div>

    <!-- Certificate Content -->
    <div class="certificate-container">
        <div class="certificate-content">
            <div style="text-align: right; margin-bottom: 20px; font-size: 14px; color: #666;">
                <strong>Reference No: {{ $certificate->formatted_reference_no ?? 'CERT-' . ($certificate->reference_no ?? 'XXXXXXXXXX') }}</strong>
            </div>

            @if($certificate->type === 'Employment Certificate')
                <h1><u>Employment Certificate</u></h1>
                <h3><u>To Whom It May Concern</u></h3>

                <div class="letter-content">
                    <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed at <strong>{{ config('app.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

                    <p>He/She joined on <strong>{{ $certificate->formatted_joining_date }}</strong> and is currently working with us. During the tenure, his/her performance and conduct have been satisfactory.</p>

                    @if($certificate->salary)
                    <p>His/Her current salary is <strong>BDT {{ $certificate->formatted_salary }}</strong> per month.</p>
                    @endif

                    <p>This certificate is issued upon request for whatever purpose it may serve best.</p>

                    <p>Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
                </div>

            @elseif($certificate->type === 'Appointment Letter')
                <h1><u>Appointment Letter</u></h1>

                <div class="letter-content">
                    <p><strong>Date:</strong> {{ $certificate->formatted_issue_date }}</p>
                    <br>
                    <p><strong>To,</strong></p>
                    <p><strong>{{ $certificate->user->name }}</strong></p>
                    <p>Son/Daughter of {{ $certificate->user->employee->father_name ?? 'N/A' }}</p>
                    <br>

                    <p><strong>Subject: Appointment Letter</strong></p>
                    <br>

                    <p>Dear {{ $certificate->user->name }},</p>

                    <p>We are pleased to inform you that you have been selected for the position of <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong> at <strong>{{ config('app.name') }}</strong>.</p>

                    <p>Your appointment will be effective from <strong>{{ $certificate->formatted_joining_date }}</strong>. Your monthly salary will be <strong>BDT {{ $certificate->formatted_salary }}</strong>.</p>

                    <p>You are required to report to the office on your joining date with all necessary documents. Please bring the following documents:</p>
                    <ul>
                        <li>Original and photocopy of educational certificates</li>
                        <li>Experience certificates (if any)</li>
                        <li>National ID card photocopy</li>
                        <li>Recent passport size photographs</li>
                    </ul>

                    <p>We look forward to your valuable contribution to our organization.</p>

                    <p>Congratulations and welcome to {{ config('app.name') }}!</p>
                </div>

            @elseif($certificate->type === 'Experience Letter')
                <h1><u>Experience Letter</u></h1>
                <h3><u>To Whom It May Concern</u></h3>

                <div class="letter-content">
                    <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed at <strong>{{ config('app.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

                    <p>He/She joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and resigned on <strong>{{ $certificate->formatted_resignation_date }}</strong>. During his/her tenure with us, he/she demonstrated excellent professional skills and maintained good conduct.</p>

                    @if($certificate->leave_starts_from)
                    <p>He/She has been granted leave starting from <strong>{{ $certificate->formatted_leave_starts_from }}</strong>@if($certificate->leave_ends_on) until <strong>{{ $certificate->formatted_leave_ends_on }}</strong>@endif.</p>
                    @endif

                    <p>We found him/her to be hardworking, sincere, and dedicated to his/her responsibilities. He/She has been a valuable asset to our organization.</p>

                    <p>We wish him/her all the best for his/her future endeavors.</p>

                    <p>This certificate is issued upon request for whatever purpose it may serve best.</p>

                    <p>Issued on <strong>{{ $certificate->formatted_issue_date }}</strong>.</p>
                </div>

            @elseif($certificate->type === 'Release Letter')
                <h1><u>Release Letter</u></h1>
                <h3><u>To Whom It May Concern</u></h3>

                <div class="letter-content">
                    <p><strong>Date:</strong> {{ $certificate->formatted_issue_date }}</p>
                    <br>

                    <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, was employed at <strong>{{ config('app.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

                    <p>He/She joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and has been released from his/her duties effective <strong>{{ $certificate->formatted_release_date }}</strong>.</p>

                    <p><strong>Reason for Release:</strong> {{ $certificate->release_reason }}</p>

                    <p>During his/her tenure with us, he/she has completed all assigned responsibilities and has cleared all dues and obligations with the company.</p>

                    <p>We hereby release him/her from all contractual obligations with <strong>{{ config('app.name') }}</strong> and wish him/her success in his/her future endeavors.</p>

                    <p>This release letter is issued upon request and for official purposes.</p>
                </div>

            @elseif($certificate->type === 'NOC/No Objection Letter')
                <h1><u>No Objection Certificate</u></h1>
                <h3><u>To Whom It May Concern</u></h3>

                <div class="letter-content">
                    <p><strong>Date:</strong> {{ $certificate->formatted_issue_date }}</p>
                    <br>

                    <p>This is to certify that <strong>{{ $certificate->user->name }}</strong>, son/daughter of <strong>{{ $certificate->user->employee->father_name ?? 'N/A' }}</strong>, is currently employed at <strong>{{ config('app.name') }}</strong> as a <strong>{{ $certificate->user->roles->first()->name ?? 'Employee' }}</strong>.</p>

                    <p>He/She joined our organization on <strong>{{ $certificate->formatted_joining_date }}</strong> and is a regular employee in good standing.</p>

                    <p>We have <strong>NO OBJECTION</strong> to his/her visit to <strong>{{ $certificate->country_name }}</strong> for <strong>{{ $certificate->visiting_purpose }}</strong>.</p>

                    @if($certificate->leave_starts_from)
                    <p>He/She has been granted leave starting from <strong>{{ $certificate->formatted_leave_starts_from }}</strong>@if($certificate->leave_ends_on) until <strong>{{ $certificate->formatted_leave_ends_on }}</strong>@endif for this purpose.</p>
                    @endif

                    <p>We assure that he/she will return to resume his/her duties after the completion of his/her visit. The company will continue to employ him/her upon his/her return.</p>

                    <p>This certificate is issued upon his/her request for visa and immigration purposes.</p>

                    <p>We wish him/her a safe and successful journey.</p>
                </div>
            @endif

            <div class="signature">
                <p>{{ $certificate->type === 'Appointment Letter' || $certificate->type === 'Release Letter' || $certificate->type === 'NOC/No Objection Letter' ? 'Sincerely,' : 'Best Regards,' }}</p>
                <br><br>
                <p style="font-weight: 100;">_____________________________</p>
                <p><strong>MD. Abdul Razzak Chowdhury</strong></p>
                <p>General Manager</p>
                <p>{{ config('app.name') }}</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 1000);
        // };
    </script>
</body>
</html>
