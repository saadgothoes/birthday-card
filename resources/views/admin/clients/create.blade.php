<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Client — Admin</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Open+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --bg: #f4f6fb;
        --surface: #ffffff;
        --surface2: #f8faff;
        --border: #e4e9f4;
        --border2: #d0d8ee;
        --text: #111827;
        --text-muted: #6b7a99;
        --text-dim: #adb5cc;
        --accent: #5b5ef4;
        --accent2: #818cf8;
        --accent-g: rgba(91, 94, 244, 0.10);
        --accent-gs: rgba(91, 94, 244, 0.05);
        --green: #10b981;
        --green-s: #ecfdf5;
        --red: #ef4444;
        --red-s: #fef2f2;
        --radius: 16px;
        --sidebar: 260px;
        --shadow: 0 1px 4px rgba(100, 116, 180, 0.08), 0 4px 24px rgba(100, 116, 180, 0.06);
        --shadow-lg: 0 8px 40px rgba(91, 94, 244, 0.12);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        display: flex;
    }

    /* ─── SIDEBAR (shared) ─── */
    .sidebar {
        width: var(--sidebar);
        background: var(--surface);
        border-right: 1.5px solid var(--border);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 100;
        box-shadow: 4px 0 24px rgba(100, 116, 180, 0.06);
    }

    .sidebar-logo {
        padding: 1.6rem 1.4rem;
        border-bottom: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .logo-mark {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        box-shadow: 0 4px 12px var(--accent-g);
        flex-shrink: 0;
    }

    .logo-text {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 1.05rem;
        color: var(--text);
        letter-spacing: -0.02em;
    }

    .logo-text span {
        color: var(--accent);
    }

    .sidebar-nav {
        padding: 1.2rem 0.8rem;
        flex: 1;
    }

    .nav-label {
        font-size: 0.67rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-dim);
        padding: 0 0.8rem;
        margin-bottom: 0.5rem;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.72rem 0.85rem;
        border-radius: 11px;
        text-decoration: none;
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.15rem;
        transition: all 0.18s;
    }

    .nav-item:hover {
        background: var(--surface2);
        color: var(--text);
    }

    .nav-item.active {
        background: var(--accent-g);
        color: var(--accent);
        font-weight: 600;
    }

    .nav-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        background: var(--surface2);
        flex-shrink: 0;
    }

    .nav-item.active .nav-icon {
        background: var(--accent-g);
    }

    .sidebar-user {
        padding: 1rem 1.2rem;
        border-top: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-av {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 800;
        color: white;
        flex-shrink: 0;
    }

    .user-meta {
        flex: 1;
        min-width: 0;
    }

    .user-meta strong {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-meta span {
        font-size: 0.72rem;
        color: var(--text-muted);
    }

    .logout-form button {
        background: none;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        width: 32px;
        height: 32px;
        cursor: pointer;
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s;
    }

    .logout-form button:hover {
        background: var(--red-s);
        color: var(--red);
        border-color: #fca5a5;
    }

    /* ─── MAIN ─── */
    .main {
        margin-left: var(--sidebar);
        flex: 1;
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
    }

    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .topbar h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .topbar p {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-top: 0.2rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: var(--surface);
        border: 1.5px solid var(--border);
        color: var(--text-muted);
        text-decoration: none;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.18s;
        font-family: 'Open Sans', sans-serif;
    }

    .back-btn:hover {
        color: var(--text);
        border-color: var(--border2);
        background: var(--surface2);
    }

    /* ─── FORM LAYOUT ─── */
    .form-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 1.5rem;
        align-items: start;
    }

    /* Form Card */
    .form-card {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .form-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .form-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--accent-g);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .form-card-title h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--text);
    }

    .form-card-title p {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
    }

    .form-card-body {
        padding: 2rem;
    }

    .alert-success {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        background: var(--green-s);
        border: 1.5px solid rgba(16, 185, 129, 0.25);
        color: #059669;
        padding: 0.85rem 1.1rem;
        border-radius: 11px;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.3rem;
    }

    .form-group label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.78rem 1rem;
        background: var(--surface2);
        border: 1.5px solid var(--border);
        border-radius: 11px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        color: var(--text);
        outline: none;
        transition: all 0.2s;
    }

    .form-group input::placeholder {
        color: var(--text-dim);
    }

    .form-group input:focus {
        border-color: var(--accent);
        background: white;
        box-shadow: 0 0 0 3px var(--accent-gs);
    }

    .form-group input.error-input {
        border-color: var(--red);
        background: var(--red-s);
    }

    .err {
        font-size: 0.78rem;
        color: var(--red);
        margin-top: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .err::before {
        content: '⚠';
        font-size: 0.7rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .submit-btn {
        width: 100%;
        padding: 0.9rem;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        color: white;
        border: none;
        border-radius: 12px;
        font-family: 'Open Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        box-shadow: 0 4px 20px var(--accent-g);
        margin-top: 0.5rem;
        letter-spacing: 0.01em;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(91, 94, 244, 0.3);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    /* Right panel — Info */
    .info-panel {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .info-card {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius);
        padding: 1.4rem;
        box-shadow: var(--shadow);
    }

    .info-card-head {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1rem;
    }

    .info-card-head .ic {
        font-size: 1.1rem;
    }

    .info-card-head h4 {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text);
    }

    .info-steps {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-step {
        display: flex;
        align-items: flex-start;
        gap: 0.7rem;
    }

    .info-step-num {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--accent-g);
        border: 1.5px solid rgba(91, 94, 244, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.68rem;
        font-weight: 800;
        color: var(--accent);
        flex-shrink: 0;
        margin-top: 0.1rem;
        font-family: 'Poppins', sans-serif;
    }

    .info-step-text {
        font-size: 0.82rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .info-step-text strong {
        color: var(--text);
    }

    .tips-list {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        font-size: 0.82rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .tip-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent2);
        margin-top: 0.45rem;
        flex-shrink: 0;
    }

    .divider {
        height: 1px;
        background: var(--border);
        margin: 0.75rem 0;
    }

    .highlight-box {
        background: linear-gradient(135deg, var(--accent-g), rgba(129, 140, 248, 0.08));
        border: 1.5px solid rgba(91, 94, 244, 0.2);
        border-radius: 12px;
        padding: 1rem 1.1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .highlight-box .hi {
        font-size: 1.2rem;
    }

    .highlight-box p {
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .highlight-box p strong {
        color: var(--accent);
    }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-mark">⚡</div>
            <div class="logo-text">Admin<span>Panel</span></div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item">
                <div class="nav-icon">🏠</div> Dashboard
            </a>
            <a href="{{ route('admin.clients.index') }}" class="nav-item">
                <div class="nav-icon">👥</div> All Clients
            </a>
            <a href="{{ route('admin.clients.create') }}" class="nav-item active">
                <div class="nav-icon">➕</div> Add Client
            </a>
        </nav>
        <div class="sidebar-user">
            <div class="user-av">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-meta">
                <strong>{{ Auth::user()->name }}</strong>
                <span>{{ Auth::user()->role }}</span>
            </div>
            <form class="logout-form" method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" title="Logout">↩</button>
            </form>
        </div>
    </aside>

    <main class="main">

        <div class="topbar">
            <div>
                <h1>Add New Client</h1>
                <p>Create an account and send credentials automatically</p>
            </div>
            <a href="{{ route('admin.clients.index') }}" class="back-btn">← Back to Clients</a>
        </div>

        <div class="form-layout">

            <!-- ─── FORM ─── -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon">👤</div>
                    <div class="form-card-title">
                        <h3>Client Information</h3>
                        <p>Fill in the details below to create a new client account</p>
                    </div>
                </div>
                <div class="form-card-body">

                    @if(session('success'))
                    <div class="alert-success">✓ {{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.clients.store') }}">
                        @csrf

                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Aisha Khan"
                                required class="{{ $errors->has('name') ? 'error-input' : '' }}">
                            @error('name')<div class="err">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="e.g. aisha@example.com" required
                                class="{{ $errors->has('email') ? 'error-input' : '' }}">
                            @error('email')<div class="err">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+92 300 0000000"
                                    required class="{{ $errors->has('phone') ? 'error-input' : '' }}">
                                @error('phone')<div class="err">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" name="age" value="{{ old('age') }}" placeholder="e.g. 25" min="1"
                                    max="120" required class="{{ $errors->has('age') ? 'error-input' : '' }}">
                                @error('age')<div class="err">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Lahore" required
                                class="{{ $errors->has('city') ? 'error-input' : '' }}">
                            @error('city')<div class="err">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="submit-btn">
                            <span>✅</span> Create Client & Send Email
                        </button>
                    </form>
                </div>
            </div>

            <!-- ─── INFO PANEL ─── -->
            <div class="info-panel">

                <div class="info-card">
                    <div class="info-card-head">
                        <span class="ic">⚡</span>
                        <h4>What happens next?</h4>
                    </div>
                    <div class="info-steps">
                        <div class="info-step">
                            <div class="info-step-num">1</div>
                            <div class="info-step-text">Account is created with a <strong>secure auto-generated
                                    password</strong></div>
                        </div>
                        <div class="info-step">
                            <div class="info-step-num">2</div>
                            <div class="info-step-text">Client receives an <strong>email with login credentials</strong>
                                instantly</div>
                        </div>
                        <div class="info-step">
                            <div class="info-step-num">3</div>
                            <div class="info-step-text">They can log in to the <strong>Client Portal</strong> and start
                                using it</div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-head">
                        <span class="ic">💡</span>
                        <h4>Tips</h4>
                    </div>
                    <div class="tips-list">
                        <div class="tip-item">
                            <div class="tip-dot"></div>Use a real email — credentials are sent automatically
                        </div>
                        <div class="tip-item">
                            <div class="tip-dot"></div>Double-check phone format before submitting
                        </div>
                        <div class="tip-item">
                            <div class="tip-dot"></div>Client role is assigned automatically
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="highlight-box">
                        <div class="hi">🔐</div>
                        <p>Passwords are <strong>hashed & secure</strong>. The client receives only a one-time
                            plain-text copy via email.</p>
                    </div>
                </div>

            </div>
        </div>

    </main>
</body>

</html>