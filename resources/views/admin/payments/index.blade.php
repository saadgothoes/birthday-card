<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments — Admin</title>
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
            --amber: #f59e0b;
            --amber-s: #fffbeb;
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
        }

        .nav-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.8rem;
            padding: 0 0.6rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.85rem 0.6rem;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text);
            margin-bottom: 0.2rem;
            transition: all 0.2s;
            position: relative;
        }

        .nav-item:hover {
            background: var(--accent-g);
            color: var(--accent);
        }

        .nav-item.active {
            background: var(--accent);
            color: white;
        }

        .nav-item.active:hover {
            background: var(--accent2);
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-user {
            margin-top: auto;
            padding: 1.2rem 0.8rem;
            border-top: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .user-av {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .user-meta strong {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .user-meta span {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .logout-form button {
            background: none;
            border: none;
            color: var(--text-dim);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.2rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .logout-form button:hover {
            background: var(--red-s);
            color: var(--red);
        }

        /* ─── MAIN ─── */
        .main {
            flex: 1;
            margin-left: var(--sidebar);
        }

        .topbar {
            padding: 1.6rem 2rem;
            border-bottom: 1.5px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .topbar p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: var(--accent-g);
            border: 1.5px solid rgba(91, 94, 244, 0.2);
            color: var(--accent);
            padding: 0.25rem 0.7rem;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
        }

        /* ─── CONTENT ─── */
        .content {
            padding: 2rem;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-head h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .section-head p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

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
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--accent-gs);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #3b82f6;
        }

        .stat-trend {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            position: relative;
            z-index: 1;
        }

        .stat-trend.up {
            color: var(--green);
        }

        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text);
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            position: relative;
            z-index: 1;
        }

        .table-container {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table-header {
            padding: 1.5rem;
            border-bottom: 1.5px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            padding: 0.75rem 1rem;
            padding-left: 2.5rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            width: 250px;
            background: var(--surface);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-g);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dim);
            font-size: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1.5px solid var(--border);
        }

        tbody td {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .row-num {
            background: var(--accent-g);
            color: var(--accent);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
            text-align: center;
            min-width: 24px;
        }

        .name-cell {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .av {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .name-strong {
            font-weight: 600;
        }

        .email-cell {
            color: var(--text);
            font-weight: 500;
        }

        .phone-cell {
            color: var(--text-muted);
        }

        .city-pill {
            background: var(--accent-g);
            color: var(--accent);
            padding: 0.25rem 0.6rem;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .age-cell {
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.7rem;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .status-badge.active {
            background: var(--green-s);
            border: 1.5px solid rgba(16, 185, 129, 0.2);
            color: var(--green);
        }

        .status-badge.disabled {
            background: var(--red-s);
            border: 1.5px solid rgba(239, 68, 68, 0.2);
            color: var(--red);
        }

        .fee-cell {
            font-weight: 600;
            color: var(--green);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .ei {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .empty-state a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .count-pill {
            background: var(--surface2);
            border: 1.5px solid var(--border);
            color: var(--text);
            padding: 0.4rem 0.8rem;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .settings-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1.6rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .setting-item {
            margin-bottom: 1rem;
        }

        .setting-item label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .setting-item input {
            width: 100%;
            padding: 0.75rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            background: var(--surface);
            color: var(--text);
        }

        .setting-item input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-g);
        }

        .btn-save {
            background: var(--accent);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: var(--accent2);
        }

        .btn-edit-rate {
            background: var(--accent);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.2s;
        }

        .btn-edit-rate:hover {
            background: var(--accent2);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .btn-cancel {
            background: var(--red-s);
            color: var(--red);
            border: 1.5px solid rgba(239, 68, 68, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: var(--red);
            color: white;
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
            <a href="{{ route('admin.dashboard') }}" class="nav-item">
                <div class="nav-icon">🏠</div> Dashboard
            </a>
            <a href="{{ route('admin.clients.index') }}" class="nav-item">
                <div class="nav-icon">👥</div> All Clients
            </a>
            <a href="{{ route('admin.clients.create') }}" class="nav-item">
                <div class="nav-icon">➕</div> Add Client
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-item active">
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
                <h1>Payments</h1>
                <p>View all client payments and revenue</p>
            </div>
            <div class="topbar-right">
                <div class="role-badge">{{ Auth::user()->role }}</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">💰</div>
                        <div class="stat-trend up">Total</div>
                    </div>
                    <div class="stat-num">{{ number_format($totalPayments, 2) }} PKR</div>
                    <div class="stat-label">Total Payments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">📅</div>
                        <div class="stat-trend up">Today</div>
                    </div>
                    <div class="stat-num">{{ number_format($clients->where('created_at', '>=', today())->sum('subscription_fee'), 2) }} PKR</div>
                    <div class="stat-label">Daily Income</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">📊</div>
                        <div class="stat-trend up">This Week</div>
                    </div>
                    <div class="stat-num">{{ number_format($clients->where('created_at', '>=', now()->startOfWeek())->sum('subscription_fee'), 2) }} PKR</div>
                    <div class="stat-label">Weekly Income</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon green">⚙️</div>
                        <div class="stat-trend neu">Current</div>
                    </div>
                    <div class="stat-num">{{ number_format(Auth::user()->default_subscription_fee, 2) }} PKR</div>
                    <div class="stat-label">Default Rate</div>
                    <button class="btn-edit-rate" onclick="toggleSettings()">Edit</button>
                </div>
            </div>

            <!-- Settings -->
            <div class="section-head">
                <div>
                    <h3>Payment Settings</h3>
                    <p>Configure default subscription fee for new clients</p>
                </div>
            </div>
            <div class="settings-card" id="settingsCard" style="display: none;">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    <div class="setting-item">
                        <label for="default_subscription_fee">Default Subscription Fee (PKR)</label>
                        <input type="number" id="default_subscription_fee" name="default_subscription_fee" value="{{ Auth::user()->default_subscription_fee }}" step="0.01" min="0" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Save Changes</button>
                        <button type="button" class="btn-cancel" onclick="toggleSettings()">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">Client Payments</div>
                    <div class="count-pill">{{ count($clients) }} clients</div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Subscription Fee</th>
                            <th>Start Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $i => $client)
                        <tr>
                            <td><span class="row-num">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="name-cell">
                                    <div class="av">{{ strtoupper(substr($client->name,0,1)) }}</div>
                                    <span class="name-strong">{{ $client->name }}</span>
                                </div>
                            </td>
                            <td><span class="email-cell">{{ $client->email }}</span></td>
                            <td><span class="fee-cell">{{ number_format($client->subscription_fee, 2) }} PKR</span></td>
                            <td>{{ $client->subscription_start_date ? $client->subscription_start_date->format('M d, Y') : 'N/A' }}</td>
                            <td><span class="status-badge {{ $client->status == 'active' ? 'active' : 'disabled' }}">{{ $client->status }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="ei">💰</div>
                                    <h3>No payments yet</h3>
                                    <p>Payments will appear here once clients are added</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </main>

    <script>
        function toggleSettings() {
            const settingsCard = document.getElementById('settingsCard');
            if (settingsCard.style.display === 'none' || settingsCard.style.display === '') {
                settingsCard.style.display = 'block';
            } else {
                settingsCard.style.display = 'none';
            }
        }
    </script>

</body>

</html>