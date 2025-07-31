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
        @include('administration.certificate.templates.' . $certificate->getTemplateName(), ['isPrint' => false])
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
