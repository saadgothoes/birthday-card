<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
        color: #6366f1;
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
        max-width: 1000px;
        margin: 0 auto;
    }

    .welcome {
        background: #1e293b;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
        border-left: 4px solid #6366f1;
    }

    .welcome h2 {
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }

    .welcome p {
        color: #94a3b8;
    }

    .badge {
        display: inline-block;
        background: #6366f1;
        color: white;
        padding: 0.2rem 0.75rem;
        border-radius: 99px;
        font-size: 0.78rem;
        margin-top: 0.5rem;
    }

    /* ─── Stats Cards ─── */
    .stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #1e293b;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #334155;
        text-align: center;
    }

    .stat-card .number {
        font-size: 2rem;
        font-weight: 700;
        color: #6366f1;
    }

    .stat-card .label {
        color: #64748b;
        font-size: 0.85rem;
        margin-top: 0.3rem;
    }

    /* ─── Action Cards ─── */
    .section-title {
        font-size: 1rem;
        color: #94a3b8;
        margin-bottom: 1rem;
    }

    .actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .action-card {
        background: #1e293b;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #334155;
        text-decoration: none;
        color: #f1f5f9;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: border-color 0.2s, transform 0.2s;
    }

    .action-card:hover {
        border-color: #6366f1;
        transform: translateY(-2px);
    }

    .action-card .icon {
        font-size: 2rem;
        background: #0f172a;
        width: 56px;
        height: 56px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .action-card h3 {
        font-size: 1rem;
        margin-bottom: 0.3rem;
    }

    .action-card p {
        color: #64748b;
        font-size: 0.82rem;
    }
    </style>
</head>

<body>
    <nav class="navbar">
        <h1>⚡ Admin Panel</h1>
        <div style="display:flex; align-items:center; gap:1rem;">
            <span style="color:#94a3b8; font-size:0.9rem;">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout-btn" type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">

        {{-- Welcome --}}
        <div class="welcome">
            <h2>Welcome back, {{ Auth::user()->name }}! 👋</h2>
            <p>Manage your clients and system settings from here.</p>
            <span class="badge">{{ Auth::user()->role }}</span>
        </div>

        {{-- Stats --}}
        <div class="stats">
            <div class="stat-card">
                <div class="number">{{ \App\Models\User::where('role', 'client')->count() }}</div>
                <div class="label">Total Clients</div>
            </div>
            <div class="stat-card">
                <div class="number">
                    {{ \App\Models\User::where('role', 'client')->whereDate('created_at', today())->count() }}
                </div>
                <div class="label">New Today</div>
            </div>
            <div class="stat-card">
                <div class="number">
                    {{ \App\Models\User::where('role', 'client')->whereMonth('created_at', now()->month)->count() }}
                </div>
                <div class="label">This Month</div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <p class="section-title">Quick Actions</p>
        <div class="actions">
            <a href="{{ route('admin.clients.index') }}" class="action-card">
                <div class="icon">👥</div>
                <div>
                    <h3>All Clients</h3>
                    <p>View and manage all registered clients</p>
                </div>
            </a>
            <a href="{{ route('admin.clients.create') }}" class="action-card">
                <div class="icon">➕</div>
                <div>
                    <h3>Add New Client</h3>
                    <p>Create client & send credentials via email</p>
                </div>
            </a>
        </div>

    </div>
</body>

</html>