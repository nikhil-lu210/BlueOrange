<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ $certificate->type }} - {{ $certificate->user->name }}</title>
        <style>
            /* Print Styles */
            @media print {
                .print-controls {
                    display: none !important;
                }
                /* Ensure no extra content creates additional pages */
                * {
                    page-break-after: avoid !important;
                    page-break-before: avoid !important;
                    page-break-inside: avoid !important;
                }
                .certificate-content {
                    margin-top: 20mm;
                }
            }

            /* Print Controls */
            .print-controls {
                position: fixed;
                bottom: 50%;
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
        @php
            // Get the template name for this certificate type
            $templateName = certificate_get_template_path($certificate->type);

            // Set a flag to indicate this is for printing
            $isPrint = true;
        @endphp

        @include($templateName, ['certificate' => $certificate, 'isPrint' => $isPrint])
    </body>
</html>
