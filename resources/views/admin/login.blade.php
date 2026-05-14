<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — BirthdayCard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --primary: #0f172a;
        --accent: #6366f1;
        --bg: #f8fafc;
        --card: #ffffff;
        --text: #1e293b;
        --text-muted: #64748b;
        --border: #e2e8f0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--bg);
        color: var(--text);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        overflow: hidden;
        position: relative;
    }

    /* Abstract background shapes */
    body::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
        top: -100px;
        right: -100px;
        border-radius: 50%;
        z-index: 0;
    }

    body::after {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
        bottom: -150px;
        left: -150px;
        border-radius: 50%;
        z-index: 0;
    }

    .card {
        background: var(--card);
        padding: 3rem 2.5rem;
        border-radius: 20px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border);
        position: relative;
        z-index: 1;
    }

    .logo-badge {
        width: 64px;
        height: 64px;
        background: var(--primary);
        color: white;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.2);
    }

    h2 {
        color: var(--primary);
        text-align: center;
        margin-bottom: 0.5rem;
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    p.sub {
        color: var(--text-muted);
        text-align: center;
        margin-bottom: 2.5rem;
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    label {
        display: block;
        color: var(--text);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        color: var(--text);
        font-size: 0.95rem;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }

    input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    button {
        width: 100%;
        padding: 1rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    button:hover {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
    }

    button:active {
        transform: translateY(0);
    }

    .error {
        background: #fef2f2;
        color: #991b1b;
        padding: 0.875rem 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        border: 1px solid #fee2e2;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 2rem;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: var(--accent);
    }
    </style>
</head>

<body>
    <div class="card">
        <div class="logo-badge">🛡️</div>
        <h2>Super Admin</h2>
        <p class="sub">Control center authentication</p>

        @if($errors->any())
        <div class="error">
            <span>⚠️</span> {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label>System Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@system.com" required
                    autofocus>
            </div>

            <div class="form-group">
                <label>Secure Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit">Authenticate &rarr;</button>
        </form>

        <a href="{{ route('client.login') }}" class="back-link">&larr; Return to Client Login</a>
    </div>
</body>

</html>