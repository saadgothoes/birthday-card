<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans — Admin</title>
    {{-- Tab icon — the app tile, same mark on every surface. --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo/clean/appicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/clean/appicon.png') }}">
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

        /* ─── Payment methods page ─── */
        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.6rem;
            margin-bottom: 1.6rem;
        }

        .panel h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .panel p.sub {
            color: var(--text-muted);
            font-size: 0.84rem;
            margin-bottom: 1.2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .field.wide {
            grid-column: 1 / -1;
        }

        .field label {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .field input[type=text],
        .field input[type=number],
        .field input[type=file],
        .field select,
        .field textarea {
            font-family: 'Open Sans', sans-serif;
            font-size: 0.9rem;
            padding: 0.7rem 0.85rem;
            border: 1.5px solid var(--border2);
            border-radius: 10px;
            background: var(--surface2);
            color: var(--text);
            width: 100%;
        }

        .field textarea {
            resize: vertical;
            min-height: 80px;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--accent);
            background: #fff;
        }

        .field .hint {
            font-size: 0.74rem;
            color: var(--text-dim);
        }

        .check-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.86rem;
            color: var(--text-muted);
        }

        .btn {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.86rem;
            padding: 0.75rem 1.4rem;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff;
            cursor: pointer;
            box-shadow: 0 4px 14px var(--accent-g);
        }

        .btn:hover {
            filter: brightness(1.06);
        }

        .btn.ghost {
            background: var(--surface2);
            color: var(--text-muted);
            border: 1.5px solid var(--border2);
            box-shadow: none;
        }

        .btn.danger {
            background: var(--red);
            box-shadow: none;
        }

        .btn.sm {
            padding: 0.45rem 0.85rem;
            font-size: 0.76rem;
        }

        .form-actions {
            display: flex;
            gap: 0.7rem;
            justify-content: flex-end;
            margin-top: 1.2rem;
        }

        .method-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.1rem;
        }

        .method-card {
            border: 1.5px solid var(--border);
            border-radius: 14px;
            background: var(--surface2);
            padding: 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
        }

        .method-card.off {
            opacity: 0.6;
        }

        .method-head {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .method-ico {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--accent-g);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .method-head strong {
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            display: block;
        }

        .method-head span {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .method-rows {
            font-size: 0.84rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .method-rows div {
            display: flex;
            justify-content: space-between;
            gap: 0.8rem;
        }

        .method-rows .k {
            color: var(--text-muted);
        }

        .method-rows .v {
            font-weight: 600;
            word-break: break-all;
            text-align: right;
        }

        .state-pill {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            margin-left: auto;
            flex-shrink: 0;
        }

        .state-pill.on {
            background: var(--green-s);
            color: var(--green);
        }

        .state-pill.off {
            background: var(--red-s);
            color: var(--red);
        }

        .method-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: auto;
            padding-top: 0.5rem;
            border-top: 1px solid var(--border);
        }

        .method-qr {
            width: 100%;
            max-width: 150px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            align-self: center;
        }

        .none-note {
            color: var(--text-muted);
            font-size: 0.86rem;
            padding: 1.4rem;
            text-align: center;
            background: var(--surface2);
            border-radius: 12px;
            border: 1.5px dashed var(--border2);
        }

        details.editor {
            margin-top: 0.4rem;
        }

        details.editor > summary {
            cursor: pointer;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--accent);
            list-style: none;
        }

        details.editor > summary::-webkit-details-marker {
            display: none;
        }

        details.editor .form-grid {
            margin-top: 0.9rem;
            grid-template-columns: 1fr;
        }

        .alert-error {
            background: var(--red-s);
            color: var(--red);
            border: 1.5px solid var(--red);
        }

        .alert-error ul {
            margin: 0.4rem 0 0 1rem;
            font-size: 0.84rem;
        }
    </style>
</head>

<body>

    @include('admin.partials.sidebar')

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Plans</h1>
                <p>What clients are offered when they subscribe — the price, how many cards it buys, and whether it
                    is shown at all</p>
            </div>
            <div class="count-pill"><strong>{{ $plans->where('is_active', true)->count() }}</strong> active</div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-warning">
            Every account starts with <strong>{{ $freeCardLimit }}</strong> free
            card{{ $freeCardLimit === 1 ? '' : 's' }} so a new client can build one all the way to the QR step. A plan
            is what unlocks generating the share link — and a client who runs out can buy again, which
            <strong>adds</strong> to the cards they already have.
        </div>

        {{-- ── The catalogue ── --}}
        <div class="table-card">
            <div class="table-toolbar">
                <h3>Current plans</h3>
                <span class="muted">{{ $plans->count() }} total</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Cards</th>
                        <th>Sold</th>
                        <th>On this plan</th>
                        <th>Revenue</th>
                        <th>Shown to clients</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($plans as $plan)
                        @php
                            // A plan that has been bought is historical: its price is
                            // locked and it can only be hidden, never deleted.
                            $sold = (int) ($purchaseCounts[$plan->amount] ?? 0);
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $plan->name ?: 'Rs ' . number_format($plan->amount) }}</strong>
                                @if ($plan->description)
                                    <div class="muted">{{ $plan->description }}</div>
                                @endif
                            </td>
                            <td>Rs {{ number_format($plan->amount) }}</td>
                            <td>{{ $plan->cards }}</td>
                            <td>{{ $sold }}×</td>
                            <td>{{ $subscriberCounts[$plan->amount] ?? 0 }}</td>
                            <td>Rs {{ number_format($revenue[$plan->amount] ?? 0) }}</td>
                            <td>
                                <span class="status-badge {{ $plan->is_active ? 'active' : '' }}">
                                    {{ $plan->is_active ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:.4rem;flex-wrap:wrap;justify-content:flex-end;">
                                    <button type="button" class="btn ghost sm"
                                        onclick="togglePlanForm({{ $plan->id }})">Edit</button>

                                    <form method="POST" action="{{ route('admin.plans.toggle', $plan) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="btn-toggle {{ $plan->is_active ? 'disable' : 'enable' }}">
                                            {{ $plan->is_active ? 'Hide' : 'Show' }}
                                        </button>
                                    </form>

                                    @if ($sold === 0)
                                        <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}"
                                            onsubmit="return confirm('Delete this plan? Nobody has bought it, so nothing is lost.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn danger sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Inline editor, one row down so the table stays readable. --}}
                        <tr id="planForm{{ $plan->id }}" hidden>
                            <td colspan="8">
                                <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
                                    @csrf @method('PUT')
                                    <div class="form-grid">
                                        <div class="field">
                                            <label>Price (PKR)</label>
                                            @if ($sold > 0)
                                                <input type="number" value="{{ $plan->amount }}" disabled>
                                                <span class="hint">Locked — this plan has been purchased
                                                    {{ $sold }} time{{ $sold === 1 ? '' : 's' }}. Changing the price
                                                    would rewrite what those clients paid. Hide it and add a new plan
                                                    instead.</span>
                                            @else
                                                <input type="number" name="amount" value="{{ $plan->amount }}"
                                                    min="1" required>
                                                <span class="hint">Nobody has bought this yet, so it is safe to
                                                    reprice.</span>
                                            @endif
                                        </div>
                                        <div class="field">
                                            <label>Cards this plan buys</label>
                                            <input type="number" name="cards" value="{{ $plan->cards }}" min="1"
                                                max="500" required>
                                        </div>
                                        <div class="field">
                                            <label>Name <span class="muted">(optional)</span></label>
                                            <input type="text" name="name" value="{{ $plan->name }}" maxlength="60"
                                                placeholder="e.g. Starter">
                                        </div>
                                        <div class="field">
                                            <label>Order</label>
                                            <input type="number" name="sort_order" value="{{ $plan->sort_order }}"
                                                min="0" max="999">
                                        </div>
                                        <div class="field wide">
                                            <label>Description <span class="muted">(optional)</span></label>
                                            <input type="text" name="description" value="{{ $plan->description }}"
                                                maxlength="160" placeholder="One line shown under the plan">
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" name="is_active" value="1"
                                                    {{ $plan->is_active ? 'checked' : '' }}>
                                                Show this plan to clients
                                            </label>
                                        </div>
                                    </div>
                                    <div style="display:flex;gap:.5rem;margin-top:1rem;">
                                        <button type="submit" class="btn">Save plan</button>
                                        <button type="button" class="btn ghost"
                                            onclick="togglePlanForm({{ $plan->id }})">Cancel</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="muted" style="padding:1.5rem;text-align:center;">
                                    No plans yet — add one below and clients will see it on the plan screen.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Add a plan ── --}}
        <div class="panel">
            <h3>Add a plan</h3>
            <p class="sub">A plan's price is its identity — past purchases point at it by price — so each price can
                only be used once.</p>

            <form method="POST" action="{{ route('admin.plans.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label for="amount">Price (PKR)</label>
                        <input type="number" name="amount" id="amount" min="1" required
                            value="{{ old('amount') }}" placeholder="e.g. 799">
                    </div>
                    <div class="field">
                        <label for="cards">Cards this plan buys</label>
                        <input type="number" name="cards" id="cards" min="1" max="500" required
                            value="{{ old('cards') }}" placeholder="e.g. 10">
                    </div>
                    <div class="field">
                        <label for="name">Name <span class="muted">(optional)</span></label>
                        <input type="text" name="name" id="name" maxlength="60" value="{{ old('name') }}"
                            placeholder="e.g. Studio">
                    </div>
                    <div class="field wide">
                        <label for="description">Description <span class="muted">(optional)</span></label>
                        <input type="text" name="description" id="description" maxlength="160"
                            value="{{ old('description') }}" placeholder="One line shown under the plan">
                    </div>
                    <div class="field">
                        <label>
                            <input type="checkbox" name="is_active" value="1" checked>
                            Show this plan to clients straight away
                        </label>
                    </div>
                </div>
                <div style="margin-top:1rem;">
                    <button type="submit" class="btn">Add plan</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function togglePlanForm(id) {
            const row = document.getElementById('planForm' + id);
            row.hidden = !row.hidden;
        }
    </script>

</body>

</html>
