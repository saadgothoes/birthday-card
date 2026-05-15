<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap"
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
            --accent-gs: rgba(91, 94, 244, 0.06);
            --green: #10b981;
            --green-s: #ecfdf5;
            --red: #ef4444;
            --red-s: #fef2f2;
            --gold: #f59e0b;
            --gold-s: #fffbeb;
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

        /* ─── SIDEBAR ─── */
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

        .nav-item .nav-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            background: var(--surface2);
            flex-shrink: 0;
            transition: background 0.18s;
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
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5rem;
        }

        .topbar h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text);
        }

        .topbar p {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 0.2rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--accent-g);
            border: 1.5px solid rgba(91, 94, 244, 0.2);
            color: var(--accent);
            padding: 0.4rem 1rem;
            border-radius: 100px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-family: 'Poppins', sans-serif;
        }

        .role-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
        }

        /* ─── WELCOME BANNER ─── */
        .welcome-banner {
            background: linear-gradient(135deg, var(--accent) 0%, #7c7eff 50%, #a78bfa 100%);
            border-radius: var(--radius);
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -40px;
            right: 100px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
        }

        .welcome-text {
            position: relative;
            z-index: 1;
        }

        .welcome-text h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.02em;
            margin-bottom: 0.4rem;
        }

        .welcome-text p {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.875rem;
        }

        .welcome-emoji {
            font-size: 3.5rem;
            position: relative;
            z-index: 1;
        }

        /* ─── STATS ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            transition: transform 0.18s, box-shadow 0.18s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .stat-icon.blue {
            background: var(--accent-g);
        }

        .stat-icon.green {
            background: var(--green-s);
        }

        .stat-icon.gold {
            background: var(--gold-s);
        }

        .stat-trend {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 100px;
        }

        .stat-trend.up {
            background: var(--green-s);
            color: var(--green);
        }

        .stat-trend.neu {
            background: var(--surface2);
            color: var(--text-muted);
        }

        .stat-num {
            font-family: 'Poppins', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.04em;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ─── SECTION TITLE ─── */
        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.2rem;
        }

        .section-head h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
        }

        .section-head p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        /* ─── ACTION CARDS ─── */
        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .action-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1.6rem;
            text-decoration: none;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 1.1rem;
            box-shadow: var(--shadow);
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .action-card::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--accent-gs);
            transition: transform 0.3s;
        }

        .action-card:hover {
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .action-card:hover::after {
            transform: scale(2.5);
        }

        .action-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            transition: background 0.2s, border-color 0.2s;
        }

        .action-card:hover .action-icon {
            background: var(--accent-g);
            border-color: rgba(91, 94, 244, 0.25);
        }

        .action-text {
            position: relative;
            z-index: 1;
        }

        .action-text h3 {
            font-size: 0.98rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .action-text p {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .action-arrow {
            margin-left: auto;
            color: var(--text-dim);
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
            transition: transform 0.2s, color 0.2s;
        }

        .action-card:hover .action-arrow {
            transform: translateX(3px);
            color: var(--accent);
        }
    </style>
</head>

<body>

    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-mark">⚡</div>
            <div class="logo-text">Admin<span>Panel</span></div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu</div>
            <a href="#" class="nav-item active">
                <div class="nav-icon">🏠</div> Dashboard
            </a>
            <a href="{{ route('admin.clients.index') }}" class="nav-item">
                <div class="nav-icon">👥</div> All Clients
            </a>
            <a href="{{ route('admin.clients.create') }}" class="nav-item">
                <div class="nav-icon">➕</div> Add Client
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-item">
                <div class="nav-icon">💰</div> Payments
            </a>
            <a href="{{ route('admin.bg-owner') }}" class="nav-item">
                <div class="nav-icon">🔒</div> BG Owner
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

    <!-- ─── MAIN ─── -->
    <main class="main">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Welcome back — here's what's happening today</p>
            </div>
            <div class="topbar-right">
                <div class="role-badge">{{ Auth::user()->role }}</div>
            </div>
        </div>

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h2>Good to see you, {{ Auth::user()->name }}! 👋</h2>
                <p>Manage your clients and system settings from here.</p>
            </div>
            <div class="welcome-emoji">🚀</div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon blue">👥</div>
                    <div class="stat-trend up">↑ Active</div>
                </div>
                <div class="stat-num">{{ \App\Models\User::where('role', 'client')->count() }}</div>
                <div class="stat-label">Total Clients</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green">✨</div>
                    <div class="stat-trend up">↑ Today</div>
                </div>
                <div class="stat-num">
                    {{ \App\Models\User::where('role', 'client')->whereDate('created_at', today())->count() }}
                </div>
                <div class="stat-label">New Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon gold">📅</div>
                    <div class="stat-trend neu">This Month</div>
                </div>
                <div class="stat-num">
                    {{ \App\Models\User::where('role', 'client')->whereMonth('created_at', now()->month)->count() }}
                </div>
                <div class="stat-label">Monthly Signups</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon blue">💰</div>
                    <div class="stat-trend up">Total</div>
                </div>
                <div class="stat-num">
                    {{ number_format(\App\Models\User::where('role', 'client')->sum('subscription_fee'), 2) }} PKR
                </div>
                <div class="stat-label">Total Payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green">📈</div>
                    <div class="stat-trend up">Today</div>
                </div>
                <div class="stat-num">
                    {{ number_format(\App\Models\User::where('role', 'client')->whereDate('created_at', today())->sum('subscription_fee'), 2) }}
                    PKR
                </div>
                <div class="stat-label">Daily Income</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon gold">📊</div>
                    <div class="stat-trend up">This Week</div>
                </div>
                <div class="stat-num">
                    {{ number_format(\App\Models\User::where('role', 'client')->where('created_at', '>=', now()->startOfWeek())->sum('subscription_fee'), 2) }}
                    PKR
                </div>
                <div class="stat-label">Weekly Income</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-head">
            <div>
                <h3>Quick Actions</h3>
                <p>Common tasks at a glance</p>
            </div>
        </div>
        <div class="actions-grid">
            <a href="{{ route('admin.clients.index') }}" class="action-card">
                <div class="action-icon">👥</div>
                <div class="action-text">
                    <h3>All Clients</h3>
                    <p>View, search and manage all registered clients</p>
                </div>
                <div class="action-arrow">→</div>
            </a>
            <a href="{{ route('admin.clients.create') }}" class="action-card">
                <div class="action-icon">➕</div>
                <div class="action-text">
                    <h3>Add New Client</h3>
                    <p>Create a client account & send credentials via email</p>
                </div>
                <div class="action-arrow">→</div>
            </a>
        </div>

    </main>
</body>

</html>