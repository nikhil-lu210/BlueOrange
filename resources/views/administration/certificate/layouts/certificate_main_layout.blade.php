<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employment Certificate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            background: #fff;
        }

        .certificate-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            padding: 20mm;
            background-color: #fff;
            width: 100%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 40mm); /* prevent pushing to 2nd page */
            overflow: hidden;
        }

        .certificate-container::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 80%;
            height: 80%;
            background: url('{{ asset(config('certificate.company.logo')) }}') no-repeat center center;
            background-size: contain;
            opacity: 0.05;
            transform: translate(-50%, -50%) rotate(-45deg);
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
            html, body {
                height: auto;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden;
            }

            .certificate-container {
                margin: 0 auto;
                padding: 20mm;
                box-shadow: none;
                page-break-inside: avoid !important;
                height: auto;
                overflow: hidden;
            }

            @page {
                size: Letter;
                margin: 10mm;
            }

            .print-button {
                display: none !important;
            }

            * {
                page-break-after: avoid !important;
                page-break-before: avoid !important;
                page-break-inside: avoid !important;
            }
        }
    </style>

    @yield('certificate_custom_css')
</head>

<body>
    <section class="certificate-container">
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; color: #666;">
            <strong style="margin-left: 30px;"> {{ $certificate->formatted_reference_no ?? 'CERT-' . ($certificate->reference_no ?? 'XXXXXXXXXX') }}</strong>
            <strong> {{ $certificate->formatted_issue_date }}</strong>
        </div>

        @yield('certificate_content')
    </section>
</body>
</html>
