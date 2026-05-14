<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login — BirthdayCard</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --bg: #f5f7fa;
        --surface: #ffffff;
        --border: #e2e8f0;
        --text: #1e293b;
        --text-muted: #64748b;
        --accent: #6366f1;
        /* Indigo - Neutral/Uni */
        --accent-soft: #eff6ff;
        --radius: 16px;
        --shadow: 0 4px 32px rgba(99, 102, 241, 0.08);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
        padding: 1.5rem;
    }

    /* ─── DECORATIVE BACKGROUND ─── */
    body::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.06) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(14, 165, 233, 0.06) 0%, transparent 40%);
        pointer-events: none;
        z-index: 0;
    }

    /* Floaties */
    .floater {
        position: absolute;
        font-size: 1.5rem;
        opacity: 0.2;
        pointer-events: none;
        z-index: 0;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0);
        }

        50% {
            transform: translateY(-20px) rotate(10deg);
        }
    }

    .login-card {
        background: var(--surface);
        padding: 3rem 2.5rem;
        border-radius: 24px;
        width: 100%;
        max-width: 440px;
        box-shadow: var(--shadow);
        position: relative;
        z-index: 1;
        border: 1.5px solid var(--border);
        text-align: center;
    }

    .logo-area {
        margin-bottom: 2rem;
    }

    .logo-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        display: inline-block;
    }

    .logo-text {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-style: italic;
        color: var(--text);
        letter-spacing: -0.02em;
    }

    .logo-text span {
        color: var(--accent);
        font-style: normal;
    }

    h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        color: var(--text);
        margin-bottom: 0.5rem;
    }

    p.sub {
        color: var(--text-muted);
        margin-bottom: 2.2rem;
        font-size: 0.9rem;
    }

    .form-group {
        text-align: left;
        margin-bottom: 1.25rem;
    }

    label {
        display: block;
        color: var(--text-muted);
        margin-bottom: 0.55rem;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    input {
        width: 100%;
        padding: 0.85rem 1.1rem;
        background: #f8fafc;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        color: var(--text);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        outline: none;
        transition: all 0.2s ease;
    }

    input:focus {
        border-color: var(--accent);
        background: white;
        box-shadow: 0 0 0 4px var(--accent-soft);
    }

    .error-msg {
        background: #fff1f2;
        color: #e11d48;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        border: 1px solid #ffe4e6;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-align: left;
    }

    button {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--accent), #818cf8);
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        cursor: pointer;
        font-weight: 700;
        margin-top: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.2);
        font-family: 'DM Sans', sans-serif;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(99, 102, 241, 0.3);
    }

    button:active {
        transform: translateY(0);
    }

    .footer-links {
        margin-top: 2rem;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .footer-links a {
        color: var(--text-muted);
        font-size: 0.82rem;
        text-decoration: none;
        transition: color 0.2s;
    }

    .footer-links a:hover {
        color: var(--accent);
    }

    .admin-btn {
        display: inline-block;
        margin-top: 1rem;
        font-size: 0.75rem;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    /* Mobile Adjustments */
    @media (max-width: 480px) {
        .login-card {
            padding: 2.5rem 1.5rem;
        }

        .logo-text {
            font-size: 1.6rem;
        }
    }
    </style>
</head>

<body>
    <!-- Unicode-based Floating elements (Modern & Neutral) -->
    <span class="floater" style="top: 10%; left: 8%;">🎈</span>
    <span class="floater" style="bottom: 15%; right: 10%; animation-delay: 2s;">🎂</span>
    <span class="floater" style="top: 15%; right: 12%; animation-delay: 1s;">✨</span>
    <span class="floater" style="bottom: 12%; left: 14%; animation-delay: 3s;">🎁</span>
    <span class="floater" style="top: 50%; left: 5%; animation-delay: 4s;">🎉</span>

    <div class="login-card">


        <h2>Welcome Back!</h2>
        <p class="sub">Log in to create your magic</p>

        @if($errors->any())
        <div class="error-msg">
            <span>⚠️</span> {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('client.login.post') }}">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required
                    autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 0.5rem;">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <div style="text-align: right; margin-bottom: 2rem;">
                <a href="#"
                    style="font-size: 0.75rem; color: var(--accent); text-decoration: none; font-weight: 600;">Forgot
                    Password?</a>
            </div>

            <button type="submit">Sign In to Dashboard →</button>
        </form>


    </div>
</body>

</html>