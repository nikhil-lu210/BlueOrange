<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Functionality Walkthrough</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Functionality Walkthrough</h2>
    </div>

    <div class="content">
        <p>Hello {{ $user->name }},</p>

        <p>A new functionality walkthrough has been created and assigned to you:</p>

        <h3>{{ $walkthrough->title }}</h3>

        <p><strong>Created by:</strong> {{ $creator->name }}</p>
        <p><strong>Created on:</strong> {{ $walkthrough->created_at->format('F j, Y \a\t g:i A') }}</p>

        <p>This walkthrough contains step-by-step instructions to help you understand and use specific functionality in our system.</p>

        <a href="{{ route('administration.functionality_walkthrough.show', $walkthrough) }}" class="button">
            View Walkthrough
        </a>

        <p>Please review the walkthrough at your earliest convenience.</p>

        <p>Best regards,<br>
        {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
