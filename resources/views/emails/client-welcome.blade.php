<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Using Web safe fonts that mimic the Dashboard aesthetic -->
    <style>
    body {
        margin: 0;
        padding: 0;
        background-color: #f8fafc;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .email-container {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
    }

    .brand-header {
        background-color: #ffffff;
        padding: 40px 20px;
        text-align: center;
        border-bottom: 1px solid #f1f5f9;
    }

    .logo-circle {
        width: 80px;
        height: 80px;
        background: #6366f1;
        /* Dashboard Indigo */
        border-radius: 50%;
        display: inline-block;
        line-height: 80px;
        font-size: 40px;
        margin-bottom: 15px;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
    }

    .brand-name {
        font-size: 26px;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.5px;
    }

    .brand-name span {
        color: #6366f1;
    }

    .hero-section {
        padding: 40px;
        text-align: center;
    }

    .hero-section h1 {
        font-size: 24px;
        color: #0f172a;
        margin-bottom: 16px;
        font-weight: 700;
    }

    .hero-section p {
        font-size: 16px;
        color: #64748b;
        line-height: 1.6;
        margin: 0;
    }

    .credentials-card {
        margin: 30px 40px;
        background: #fdfcff;
        border: 1px solid #eef2ff;
        border-radius: 20px;
        padding: 30px;
        text-align: left;
    }

    .credential-item {
        margin-bottom: 20px;
    }

    .credential-label {
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .credential-box {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        padding: 12px 16px;
        border-radius: 12px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 16px;
        color: #1e293b;
        font-weight: 600;
    }

    .action-area {
        padding: 0 40px 40px;
        text-align: center;
    }

    .dashboard-btn {
        display: inline-block;
        background-color: #6366f1;
        color: #ffffff !important;
        padding: 18px 40px;
        border-radius: 16px;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        transition: transform 0.2s;
    }

    .security-badge {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        padding: 6px 14px;
        border-radius: 20px;
        margin-top: 25px;
        font-weight: 500;
    }

    .footer {
        padding: 30px;
        text-align: center;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        color: #94a3b8;
        font-size: 13px;
    }

    .footer a {
        color: #6366f1;
        text-decoration: none;
    }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Logo Header -->
        <div class="brand-header">
            <div class="logo-circle">🎂</div>
            <div class="brand-name">Birthday<span>Card</span></div>
        </div>

        <!-- Welcome Text -->
        <div class="hero-section">
            <h1>Account Successfully Created!</h1>
            <p>Welcome to the family, {{ $clientName }}! We've set up your workspace. You can now start creating
                personalized birthday magic for your users.</p>
        </div>

        <!-- Credentials -->
        <div class="credentials-card">
            <div class="credential-item">
                <div class="credential-label">Access Email</div>
                <div class="credential-box">{{ $clientEmail }}</div>
            </div>
            <div class="credential-item" style="margin-bottom: 0;">
                <div class="credential-label">Temporary Password</div>
                <div class="credential-box">{{ $plainPassword }}</div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="action-area">
            <a href="{{ url('/client/login') }}" class="dashboard-btn">Go to Client Dashboard &rarr;</a>
            <br>
            <div class="security-badge">
                🔒 Security Note: Please update your password after logging in.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} BirthdayCard. All rights reserved.<br>
            Need help? <a href="mailto:support@birthdaycard.com">Contact Support</a>
        </div>
    </div>
</body>

</html>

</html>