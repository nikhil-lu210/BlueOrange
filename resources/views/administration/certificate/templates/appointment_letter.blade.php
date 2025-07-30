<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Letter</title>
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
            margin: 20mm auto;
            padding: 30px;
            background-color: #fff;
            width: 100%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
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

        /* Print Styles */
        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
            }

            .certificate-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
                page-break-inside: avoid;
            }

            @page {
                size: A4;
                margin: 20mm;
                margin-top: 50mm;
            }

            .print-button {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <section class="certificate-container">
        <div class="certificate-content">
            <div style="text-align: right; margin-bottom: 20px; font-size: 14px; color: #666;">
                <strong>Reference No: {{ $certificate->formatted_reference_no ?? 'CERT-' . ($certificate->reference_no ?? 'XXXXXXXXXX') }}</strong>
            </div>

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

            <div class="signature">
                <p>Sincerely,</p>
                <br><br>
                <p style="font-weight: 100;">_____________________________</p>
                <p><strong>MD. Abdul Razzak Chowdhury</strong></p>
                <p>General Manager</p>
                <p>{{ config('app.name') }}</p>
            </div>
        </div>
    </section>

    @if(!isset($isPrint))
    <div class="print-button" style="text-align: center; margin-top: 20px;">
        <button onclick="printCertificate()" class="btn btn-primary">
            <i class="ti ti-printer me-1"></i>Print Certificate
        </button>
    </div>

    <script>
        function printCertificate() {
            var container = document.querySelector(".certificate-container");
            var containerHtml = container.outerHTML;

            var printWindow = window.open("", "_blank");

            printWindow.document.open();
            printWindow.document.write('<html><head><title>Print Certificate</title><style>');

            printWindow.document.write(`
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap');
                body {
                    font-family: 'Montserrat', sans-serif;
                    margin: 0;
                }
                .certificate-container {
                    position: relative;
                    max-width: 800px;
                    margin: 20mm auto;
                    padding: 30px;
                    background-color: #fff;
                    width: 100%;
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
                .signature {
                    margin-top: 60px;
                }
                .signature p {
                    margin-bottom: 5px;
                }
                ul {
                    margin-left: 20px;
                }
                @media print {
                    body {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    .certificate-container {
                        margin: 0;
                        padding: 0;
                        box-shadow: none;
                        page-break-inside: avoid;
                    }
                    @page {
                        size: A4;
                        margin: 20mm;
                        margin-top: 50mm;
                    }
                }
            `);

            printWindow.document.write('</style></head><body>');
            printWindow.document.write(containerHtml);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            setTimeout(function () {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
    </script>
    @endif
</body>
</html>
