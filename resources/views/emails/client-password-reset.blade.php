<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f6f9;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #f76fa1;
            margin-bottom: 10px;
        }

        .logo span {
            color: #333;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .content {
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            background: #f76fa1;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }

        .button:hover {
            background: #e83e7a;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎂 <span>BirthdayCard</span></div>
            <h1>Reset Your Password</h1>
        </div>

        <div class="content">
            <p>Hello {{ $user->name }},</p>

            <p>You have requested to reset your password for your Birthday Card account. Click the button below to create a new password:</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>

            <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
            <p style="word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;">
                {{ $resetUrl }}
            </p>

            <div class="warning">
                <strong>Security Notice:</strong> This link will expire in 60 minutes for your security. If you didn't request this password reset, please ignore this email.
            </div>

            <p>If you have any questions or need help, please contact our support team.</p>

            <p>Best regards,<br>The Birthday Card Team</p>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $user->email }} because a password reset was requested for your Birthday Card account.</p>
            <p>If you no longer wish to receive these emails, please contact support.</p>
        </div>
    </div>
</body>

</html>