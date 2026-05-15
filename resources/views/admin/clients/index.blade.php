<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients — Admin</title>
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
        }

        .topbar {
            display: flex;
            align-items: flex-end;
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

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent);
            color: white;
            text-decoration: none;
            padding: 0.65rem 1.4rem;
            border-radius: 11px;
            font-size: 0.87rem;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 4px 16px var(--accent-g);
            font-family: 'DM Sans', sans-serif;
        }

        .btn-add:hover {
            background: #4a4dd4;
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(91, 94, 244, 0.3);
        }

        /* Alerts */
        .alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.2rem;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            border: 1.5px solid;
            animation: fadeUp 0.3s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(-6px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .alert-success {
            background: var(--green-s);
            border-color: rgba(16, 185, 129, 0.25);
            color: #059669;
        }

        .alert-warning {
            background: var(--amber-s);
            border-color: rgba(245, 158, 11, 0.25);
            color: #d97706;
        }

        /* Stats row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .mini-stat {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 1.2rem 1.5rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mini-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .mini-stat-icon.blue {
            background: var(--accent-g);
        }

        .mini-stat-icon.green {
            background: var(--green-s);
        }

        .mini-stat-icon.gold {
            background: var(--amber-s);
        }

        .mini-stat-body .num {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }

        .mini-stat-body .lbl {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        /* Table Card */
        .table-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table-toolbar {
            padding: 1.1rem 1.5rem;
            border-bottom: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            max-width: 300px;
        }

        .search-wrap input {
            width: 100%;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 9px;
            padding: 0.6rem 1rem 0.6rem 2.4rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            color: var(--text);
            outline: none;
            transition: all 0.2s;
        }

        .search-wrap input::placeholder {
            color: var(--text-dim);
        }

        .search-wrap input:focus {
            border-color: var(--accent);
            background: white;
            box-shadow: 0 0 0 3px var(--accent-gs);
        }

        .search-ico {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.8rem;
            color: var(--text-dim);
            pointer-events: none;
        }

        .count-pill {
            margin-left: auto;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 0.35rem 0.85rem;
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .count-pill strong {
            color: var(--text);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: var(--surface2);
            padding: 0.85rem 1.2rem;
            text-align: left;
            font-size: 0.71rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1.5px solid var(--border);
        }

        thead th:first-child {
            padding-left: 1.5rem;
        }

        thead th:last-child {
            padding-right: 1.5rem;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: var(--surface2);
        }

        tbody td {
            padding: 1rem 1.2rem;
            font-size: 0.875rem;
            vertical-align: middle;
        }

        tbody td:first-child {
            padding-left: 1.5rem;
        }

        tbody td:last-child {
            padding-right: 1.5rem;
        }

        .row-num {
            font-family: 'Poppins', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-dim);
        }

        .name-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .av {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            font-weight: 800;
            color: white;
            flex-shrink: 0;
        }

        .name-strong {
            font-weight: 500;
        }

        .email-cell {
            color: var(--text-muted);
            font-size: 0.83rem;
        }

        .phone-cell {
            font-size: 0.83rem;
            font-variant-numeric: tabular-nums;
        }

        .city-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 100px;
            padding: 0.22rem 0.7rem;
            font-size: 0.77rem;
            color: var(--text-muted);
        }

        .age-cell {
            font-size: 0.83rem;
            color: var(--text-muted);
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

        .btn-toggle {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-toggle.disable {
            background: var(--red);
            color: white;
        }

        .btn-toggle.disable:hover {
            background: #dc2626;
        }

        .btn-toggle.enable {
            background: var(--green);
            color: white;
        }

        .btn-toggle.enable:hover {
            background: #059669;
        }

        .badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state .ei {
            font-size: 3rem;
            opacity: 0.35;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 0.3rem;
        }

        .empty-state p {
            font-size: 0.83rem;
            color: var(--text-dim);
            margin-bottom: 1.5rem;
        }

        .empty-state a {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent);
            color: white;
            text-decoration: none;
            padding: 0.65rem 1.4rem;
            border-radius: 11px;
            font-size: 0.87rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .empty-state a:hover {
            background: #4a4dd4;
            transform: translateY(-1px);
        }

        tbody tr.hidden {
            display: none;
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
            <a href="{{ route('admin.clients.index') }}" class="nav-item active">
                <div class="nav-icon">👥</div> All Clients
            </a>
            <a href="{{ route('admin.clients.create') }}" class="nav-item">
                <div class="nav-icon">➕</div> Add Client
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-item">
                <div class="nav-icon">💰</div> Payments
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
                <h1>Clients</h1>
                <p>All registered client accounts</p>
            </div>
            <a href="{{ route('admin.clients.create') }}" class="btn-add">+ Add Client</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning">⚠ {{ session('warning') }}</div>
        @endif

        <div class="stats-row">
            <div class="mini-stat">
                <div class="mini-stat-icon blue">👥</div>
                <div class="mini-stat-body">
                    <div class="num">{{ \App\Models\User::where('role','client')->count() }}</div>
                    <div class="lbl">Total Clients</div>
                </div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-icon green">✨</div>
                <div class="mini-stat-body">
                    <div class="num">
                        {{ \App\Models\User::where('role','client')->whereDate('created_at',today())->count() }}
                    </div>
                    <div class="lbl">Added Today</div>
                </div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-icon gold">📅</div>
                <div class="mini-stat-body">
                    <div class="num">
                        {{ \App\Models\User::where('role','client')->whereMonth('created_at',now()->month)->count() }}
                    </div>
                    <div class="lbl">This Month</div>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-wrap">
                    <span class="search-ico">🔍</span>
                    <input type="text" id="searchInput" placeholder="Search name, email, city…" oninput="filterTable()">
                </div>
                <div class="count-pill"><strong id="shownCount">{{ count($clients) }}</strong> clients</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Age</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
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
                        <td><span class="phone-cell">{{ $client->phone ?? '—' }}</span></td>
                        <td>@if($client->city)<span class="city-pill">📍 {{ $client->city }}</span>@else<span
                                style="color:var(--text-dim)">—</span>@endif</td>
                        <td><span class="age-cell">{{ $client->age ?? '—' }}</span></td>
                        <td><span class="status-badge {{ $client->status == 'active' ? 'active' : 'disabled' }}">{{ $client->status }}</span></td>
                        <td>
                            <form action="{{ route('admin.clients.toggle-status', $client->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-toggle {{ $client->status == 'active' ? 'disable' : 'enable' }}">
                                    {{ $client->status == 'active' ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="ei">👤</div>
                                <h3>No clients yet</h3>
                                <p>Get started by adding your first client</p>
                                <a href="{{ route('admin.clients.create') }}">+ Add First Client</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');
            let n = 0;
            rows.forEach(r => {
                const show = r.textContent.toLowerCase().includes(q);
                r.classList.toggle('hidden', !show);
                if (show) n++;
            });
            document.getElementById('shownCount').textContent = n;
        }
    </script>
</body>

</html>