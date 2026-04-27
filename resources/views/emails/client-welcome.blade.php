<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f1f5f9;
        margin: 0;
        padding: 30px;
    }

    .card {
        background: white;
        max-width: 500px;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .header {
        background: #6366f1;
        padding: 30px;
        text-align: center;
        color: white;
    }

    .header h1 {
        margin: 0;
        font-size: 1.5rem;
    }

    .body {
        padding: 30px;
    }

    .body p {
        color: #475569;
        line-height: 1.6;
    }

    .cred-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
    }

    .cred-box p {
        margin: 6px 0;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .cred-box span {
        font-weight: 700;
        color: #6366f1;
    }

    .btn {
        display: block;
        text-align: center;
        background: #6366f1;
        color: white;
        padding: 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 20px;
    }

    .footer {
        text-align: center;
        padding: 20px;
        color: #94a3b8;
        font-size: 0.8rem;
    }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <h1>⚡ Welcome to Admin Panel</h1>
        </div>
        <div class="body">
            <p>Hi <strong>{{ $clientName }}</strong>,</p>
            <p>Your account has been created. Here are your login credentials:</p>

            <div class="cred-box">
                <p>📧 Email: <span>{{ $clientEmail }}</span></p>
                <p>🔑 Password: <span>{{ $plainPassword }}</span></p>
            </div>

            <p>Please login and change your password after first login.</p>

            <a href="{{ url('/client/login') }}" class="btn">Login to Your Account →</a>
        </div>
        <div class="footer">
            If you did not expect this email, please ignore it.
        </div>
    </div>
</body>

</html>