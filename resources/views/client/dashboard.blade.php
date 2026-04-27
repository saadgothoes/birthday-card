<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #0f172a;
        color: #f1f5f9;
    }

    .navbar {
        background: #1e293b;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #334155;
    }

    .navbar h1 {
        font-size: 1.2rem;
        color: #10b981;
    }

    .logout-btn {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .container {
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .welcome {
        background: #1e293b;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #10b981;
    }

    .welcome h2 {
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }

    .welcome p {
        color: #94a3b8;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .info-card {
        background: #1e293b;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #334155;
    }

    .info-card p {
        color: #64748b;
        font-size: 0.8rem;
        margin-bottom: 0.3rem;
    }

    .info-card h3 {
        font-size: 1rem;
        color: #f1f5f9;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <h1>👤 Client Portal</h1>
        <div style="display:flex; align-items:center; gap:1rem;">
            <span style="color:#94a3b8;">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button class="logout-btn" type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="welcome">
            <h2>Welcome, {{ Auth::user()->name }}! 👋</h2>
            <p>You are logged in to your client portal.</p>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <p>Email</p>
                <h3>{{ Auth::user()->email }}</h3>
            </div>
            <div class="info-card">
                <p>Phone</p>
                <h3>{{ Auth::user()->phone ?? '—' }}</h3>
            </div>
            <div class="info-card">
                <p>City</p>
                <h3>{{ Auth::user()->city ?? '—' }}</h3>
            </div>
            <div class="info-card">
                <p>Age</p>
                <h3>{{ Auth::user()->age ?? '—' }}</h3>
            </div>
        </div>
    </div>
</body>

</html>