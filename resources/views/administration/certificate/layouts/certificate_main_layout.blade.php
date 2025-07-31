<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employment Certificate</title>
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
            margin: 10mm auto;
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

    @yield('certificate_custom_css')
</head>

<body>
    <section class="certificate-container">
        @yield('certificate_content')
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
                        background: url('{{ asset(config('certificate.company.logo')) }}') no-repeat center center;
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

        @yield('certificate_custom_js')
    @endif
</body>
</html>
