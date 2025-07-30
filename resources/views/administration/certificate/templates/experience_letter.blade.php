<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Experience Letter</title>
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

            <div class="signature">
                <p>Best Regards,</p>
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
