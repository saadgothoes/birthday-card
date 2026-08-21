<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BG Owner - Admin Dashboard</title>
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
            background: var(--accent);
            color: white;
        }

        .sidebar-user {
            padding: 1.2rem 0.8rem;
            border-top: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-av {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--accent-g);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--accent);
            font-size: 0.9rem;
        }

        .user-meta {
            flex: 1;
        }

        .user-meta strong {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.1rem;
        }

        .user-meta span {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .logout-form button {
            width: 32px;
            height: 32px;
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
            gap: 1rem;
        }

        /* BG Owner specific styles */
        .bg-owner-container {
            padding: 20px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            color: #fff;
            border-radius: var(--radius);
        }

        .pin-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .pin-modal-content {
            background: linear-gradient(135deg, #2d1b69 0%, #11998e 100%);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border: 2px solid #00d4ff;
            max-width: 400px;
            width: 90%;
        }

        .pin-header h2 {
            margin: 0 0 10px 0;
            color: #00d4ff;
            text-align: center;
        }

        .pin-header p {
            margin: 0 0 20px 0;
            text-align: center;
            opacity: 0.8;
        }

        .pin-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .pin-digit {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 20px;
            border: 2px solid #00d4ff;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .pin-digit:focus {
            outline: none;
            border-color: #ff6b6b;
            box-shadow: 0 0 10px rgba(255, 107, 107, 0.5);
        }

        .pin-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-verify,
        .btn-cancel {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-verify {
            background: linear-gradient(45deg, #00d4ff, #11998e);
            color: white;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.4);
        }

        .btn-cancel {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .pin-error {
            margin-top: 15px;
            color: #ff6b6b;
            text-align: center;
            font-weight: bold;
        }

        .main-content {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bg-owner-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .bg-owner-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #00d4ff, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .data-sections {
            display: grid;
            gap: 30px;
        }

        .data-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(0, 212, 255, 0.3);
        }

        .data-section h3 {
            margin: 0 0 20px 0;
            color: #00d4ff;
            border-bottom: 2px solid #00d4ff;
            padding-bottom: 10px;
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .data-list {
            display: grid;
            gap: 10px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #00d4ff;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .pin-inputs {
                gap: 5px;
            }

            .pin-digit {
                width: 35px;
                height: 35px;
                font-size: 18px;
            }

            .data-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    
        /* ── BG Owner: client content ─────────────────────────── */
        .bgo-totals {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: .9rem;
            margin-bottom: 1.5rem;
        }

        .bgo-total {
            display: flex;
            align-items: center;
            gap: .7rem;
            background: var(--surface, #fff);
            border: 1.5px solid var(--border, #e4e9f4);
            border-radius: 14px;
            padding: .95rem 1.1rem;
        }

        .bgo-total-ico {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: var(--accent-g, rgba(91, 94, 244, .1));
            font-size: 1rem;
            flex-shrink: 0;
        }

        .bgo-total-num {
            font-family: 'Poppins', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1;
        }

        .bgo-total-lbl {
            font-size: .74rem;
            color: var(--text-muted, #6b7a99);
            margin-top: .2rem;
        }

        .bgo-toolbar {
            display: flex;
            gap: .8rem;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1.2rem;
        }

        .bgo-search {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex: 1;
            min-width: 220px;
            background: var(--surface, #fff);
            border: 1.5px solid var(--border, #e4e9f4);
            border-radius: 11px;
            padding: .6rem .9rem;
        }

        .bgo-search input {
            border: none;
            outline: none;
            background: none;
            font-family: inherit;
            font-size: .86rem;
            width: 100%;
            color: inherit;
        }

        .bgo-filters {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
        }

        .bgo-chip {
            border: 1.5px solid var(--border, #e4e9f4);
            background: var(--surface, #fff);
            font-family: inherit;
            font-size: .78rem;
            font-weight: 600;
            padding: .5rem .9rem;
            border-radius: 999px;
            cursor: pointer;
            color: var(--text-muted, #6b7a99);
        }

        .bgo-chip.active {
            background: var(--accent, #5b5ef4);
            border-color: var(--accent, #5b5ef4);
            color: #fff;
        }

        .bgo-client {
            background: var(--surface, #fff);
            border: 1.5px solid var(--border, #e4e9f4);
            border-radius: 16px;
            margin-bottom: .9rem;
            overflow: hidden;
        }

        .bgo-client-head {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: 1rem 1.2rem;
            cursor: pointer;
            user-select: none;
        }

        .bgo-client-head:hover {
            background: var(--surface2, #f8faff);
        }

        .bgo-av {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--accent, #5b5ef4);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .bgo-client-id {
            min-width: 0;
            flex: 1;
        }

        .bgo-client-id strong {
            display: block;
            font-size: .92rem;
        }

        .bgo-client-id span {
            display: block;
            font-size: .76rem;
            color: var(--text-muted, #6b7a99);
        }

        .bgo-client-tallies {
            display: flex;
            gap: .7rem;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-muted, #6b7a99);
            flex-wrap: wrap;
        }

        .bgo-caret {
            transition: transform .2s ease;
            color: var(--text-muted, #6b7a99);
        }

        .bgo-client.open .bgo-caret {
            transform: rotate(180deg);
        }

        .bgo-client-body {
            display: none;
            padding: 0 1.2rem 1.2rem;
            border-top: 1.5px solid var(--border, #e4e9f4);
        }

        .bgo-client.open .bgo-client-body {
            display: block;
        }

        .bgo-kv-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: .3rem 1.2rem;
            padding: 1rem 0;
        }

        .bgo-kv {
            display: flex;
            justify-content: space-between;
            gap: .6rem;
            font-size: .8rem;
            padding: .35rem 0;
            border-bottom: 1px solid var(--border, #e4e9f4);
        }

        .bgo-kv span {
            color: var(--text-muted, #6b7a99);
        }

        .bgo-kv b {
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }

        .bgo-card {
            border: 1.5px solid var(--border, #e4e9f4);
            border-radius: 13px;
            padding: 1.1rem;
            margin-top: .9rem;
            background: var(--surface2, #f8faff);
        }

        .bgo-card-head {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
            margin-bottom: .8rem;
        }

        .bgo-card-head strong {
            font-size: .92rem;
        }

        .bgo-tag {
            font-size: .68rem;
            font-weight: 700;
            padding: .2rem .55rem;
            border-radius: 999px;
            background: #eef1f8;
            color: var(--text-muted, #6b7a99);
        }

        .bgo-tag.done {
            background: var(--green-s, #ecfdf5);
            color: var(--green, #10b981);
        }

        .bgo-tag.draft {
            background: var(--amber-s, #fffbeb);
            color: var(--amber, #f59e0b);
        }

        .bgo-tag.lock {
            background: var(--red-s, #fef2f2);
            color: var(--red, #ef4444);
        }

        .bgo-when {
            font-size: .72rem;
            color: var(--text-dim, #adb5cc);
            margin-left: auto;
        }

        .bgo-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--surface, #fff);
            border: 1.5px solid var(--border, #e4e9f4);
            border-radius: 10px;
            padding: .6rem .8rem;
            font-size: .8rem;
            margin-bottom: .9rem;
            flex-wrap: wrap;
        }

        .bgo-link a {
            color: var(--accent, #5b5ef4);
            text-decoration: none;
            word-break: break-all;
            font-weight: 600;
            flex: 1;
            min-width: 150px;
        }

        .bgo-link a:hover {
            text-decoration: underline;
        }

        .bgo-link.none {
            color: var(--text-dim, #adb5cc);
            font-style: italic;
        }

        .bgo-copy {
            border: 1.5px solid var(--border, #e4e9f4);
            background: var(--surface, #fff);
            font-family: inherit;
            font-size: .72rem;
            font-weight: 700;
            padding: .3rem .7rem;
            border-radius: 7px;
            cursor: pointer;
            color: var(--text-muted, #6b7a99);
        }

        .bgo-copy:hover {
            border-color: var(--accent, #5b5ef4);
            color: var(--accent, #5b5ef4);
        }

        .bgo-sub {
            font-size: .76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted, #6b7a99);
            margin: 1rem 0 .55rem;
        }

        .bgo-media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(128px, 1fr));
            gap: .6rem;
        }

        .bgo-media {
            display: block;
            border: 1.5px solid var(--border, #e4e9f4);
            border-radius: 10px;
            overflow: hidden;
            background: var(--surface, #fff);
            text-decoration: none;
        }

        .bgo-media img,
        .bgo-media video {
            display: block;
            width: 100%;
            height: 104px;
            object-fit: cover;
            background: #eef1f8;
        }

        .bgo-media span {
            display: block;
            font-size: .68rem;
            color: var(--text-muted, #6b7a99);
            padding: .35rem .5rem;
            text-align: center;
            word-break: break-word;
        }

        .bgo-media:hover {
            border-color: var(--accent, #5b5ef4);
        }

        .bgo-texts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: .5rem;
        }

        .bgo-text {
            background: var(--surface, #fff);
            border: 1.5px solid var(--border, #e4e9f4);
            border-radius: 9px;
            padding: .55rem .7rem;
        }

        .bgo-text span {
            display: block;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-dim, #adb5cc);
            margin-bottom: .2rem;
        }

        .bgo-text p {
            font-size: .8rem;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .bgo-empty {
            font-size: .8rem;
            color: var(--text-dim, #adb5cc);
            font-style: italic;
            padding: .5rem 0;
        }

    </style>
</head>

<body>
    <!-- Sidebar -->
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
            <a href="{{ route('admin.subscriptions.index') }}" class="nav-item">
                <div class="nav-icon">🎫</div> Subscriptions
            </a>
            <a href="{{ route('admin.links.index') }}" class="nav-item">
                <div class="nav-icon">🔗</div> Generated Links
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-item">
                <div class="nav-icon">💰</div> Payments
            </a>
            <a href="{{ route('admin.bg-owner') }}" class="nav-item active">
                <div class="nav-icon">🔒</div> BG Owner
            </a>
        </nav>
        <div class="sidebar-user">
            <div class="user-info">
                <div class="user-av">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-meta">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>{{ Auth::user()->role }}</span>
                </div>
            </div>
            <form class="logout-form" method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" title="Logout">↩</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <div class="topbar">
            <div>
                <h1>BG Owner Access</h1>
                <p>Secure access to all client data</p>
            </div>
        </div>

        <div class="bg-owner-container">
            <!-- PIN Verification Modal -->
            <div id="pinModal" class="pin-modal">
                <div class="pin-modal-content">
                    <div class="pin-header">
                        <h2>🔒 BG Owner Access</h2>
                        <p>Enter 6-digit PIN to access sensitive data</p>
                    </div>
                    <form id="pinForm" class="pin-form">
                        <div class="pin-inputs">
                            <input type="password" maxlength="1" class="pin-digit" id="pin1" required>
                            <input type="password" maxlength="1" class="pin-digit" id="pin2" required>
                            <input type="password" maxlength="1" class="pin-digit" id="pin3" required>
                            <input type="password" maxlength="1" class="pin-digit" id="pin4" required>
                            <input type="password" maxlength="1" class="pin-digit" id="pin5" required>
                            <input type="password" maxlength="1" class="pin-digit" id="pin6" required>
                        </div>
                        <div class="pin-actions">
                            <button type="submit" class="btn-verify">Verify PIN</button>
                            <button type="button" class="btn-cancel" onclick="closePinModal()">Cancel</button>
                        </div>
                    </form>
                    <div id="pinError" class="pin-error" style="display: none;"></div>
                </div>
            </div>

            <!-- Main Content (loaded only after the PIN is accepted) -->
            <div id="mainContent" class="main-content" style="display: none;">
                <div class="bg-owner-header">
                    <h1>🎯 BG Owner Dashboard</h1>
                    <p>Every client's uploads, content and generated links</p>
                </div>

                <!-- Totals across the whole system -->
                <div class="bgo-totals" id="bgoTotals"></div>

                <div class="bgo-toolbar">
                    <div class="bgo-search">
                        <span>🔍</span>
                        <input type="text" id="bgoSearch" placeholder="Search client, email, card, link…"
                            oninput="filterClients()">
                    </div>
                    <div class="bgo-filters">
                        <button class="bgo-chip active" data-filter="all" onclick="setFilter(this,'all')">All</button>
                        <button class="bgo-chip" data-filter="images" onclick="setFilter(this,'images')">With
                            images</button>
                        <button class="bgo-chip" data-filter="videos" onclick="setFilter(this,'videos')">With
                            videos</button>
                        <button class="bgo-chip" data-filter="links" onclick="setFilter(this,'links')">With
                            links</button>
                    </div>
                </div>

                <!-- One block per client -->
                <div id="bgoClients">
                    <div class="loading">Loading client data…</div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show PIN modal on page load
            showPinModal();

            // PIN input handling
            const pinDigits = document.querySelectorAll('.pin-digit');
            pinDigits.forEach((digit, index) => {
                digit.addEventListener('input', function() {
                    if (this.value.length === 1 && index < 5) {
                        pinDigits[index + 1].focus();
                    }
                });

                digit.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        pinDigits[index - 1].focus();
                    }
                });
            });

            // PIN form submission
            document.getElementById('pinForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const pin = Array.from(pinDigits).map(d => d.value).join('');
                if (pin.length !== 6) {
                    showPinError('Please enter all 6 digits');
                    return;
                }

                verifyPin(pin);
            });
        });

        function showPinModal() {
            document.getElementById('pinModal').style.display = 'flex';
        }

        function closePinModal() {
            document.getElementById('pinModal').style.display = 'none';
        }

        function showPinError(message) {
            const errorDiv = document.getElementById('pinError');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 3000);
        }

        function verifyPin(pin) {
            fetch('/admin/bg-owner/verify-pin', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        pin: pin
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closePinModal();
                        document.getElementById('mainContent').style.display = 'block';
                        loadClientData();
                    } else {
                        showPinError(data.message || 'Invalid PIN');
                        // Clear PIN inputs
                        document.querySelectorAll('.pin-digit').forEach(d => d.value = '');
                        document.getElementById('pin1').focus();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showPinError('An error occurred. Please try again.');
                });
        }

        // ── Client data ─────────────────────────────────────────
        // Fetched only after the PIN is accepted; the endpoint checks the same
        // session flag, so the content never reaches an unverified page.
        let BGO_DATA = null;
        let BGO_FILTER = 'all';

        function esc(v) {
            return String(v == null ? '' : v).replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));
        }

        function loadClientData() {
            const host = document.getElementById('bgoClients');
            host.innerHTML = '<div class="loading">Loading client data…</div>';

            fetch('{{ route('admin.bg-owner.data') }}', {
                    headers: { 'Accept': 'application/json' },
                })
                .then(res => {
                    if (!res.ok) throw new Error('Request failed');
                    return res.json();
                })
                .then(data => {
                    BGO_DATA = data;
                    renderTotals(data.totals);
                    renderClients(data.clients);
                })
                .catch(() => {
                    host.innerHTML = '<div class="loading">Could not load client data. Reload and re-enter the PIN.</div>';
                });
        }

        function renderTotals(t) {
            document.getElementById('bgoTotals').innerHTML = [
                ['👥', BGO_DATA.clients.length, 'Clients'],
                ['🎂', t.cards, 'Cards'],
                ['📸', t.images, 'Images'],
                ['🎥', t.videos, 'Videos'],
                ['🔗', t.links, 'Links'],
                ['💾', t.storage, 'Storage'],
            ].map(([ico, num, lbl]) =>
                '<div class="bgo-total"><div class="bgo-total-ico">' + ico + '</div>' +
                '<div><div class="bgo-total-num">' + esc(num) + '</div>' +
                '<div class="bgo-total-lbl">' + lbl + '</div></div></div>'
            ).join('');
        }

        function renderClients(clients) {
            const host = document.getElementById('bgoClients');

            if (!clients.length) {
                host.innerHTML = '<div class="loading">No clients yet.</div>';
                return;
            }

            host.innerHTML = clients.map(c => {
                const initial = (c.name || '?').charAt(0).toUpperCase();

                const meta = [
                    ['Email', c.email], ['Phone', c.phone || '—'], ['City', c.city || '—'],
                    ['Age', c.age || '—'], ['Plan', c.plan], ['Subscription', c.subscription],
                    ['Account', c.status], ['Joined', c.joined || '—'],
                    ['Live logins', c.devices],
                ].map(([k, v]) =>
                    '<div class="bgo-kv"><span>' + k + '</span><b>' + esc(v) + '</b></div>'
                ).join('');

                const cards = c.cards.length
                    ? c.cards.map(card => renderCard(card)).join('')
                    : '<div class="bgo-empty">This client has not created any cards.</div>';

                return '' +
                    '<div class="bgo-client" data-search="' + esc((c.name + ' ' + c.email + ' ' +
                        c.cards.map(x => x.title + ' ' + (x.link || '')).join(' ')).toLowerCase()) + '"' +
                    ' data-images="' + c.image_count + '" data-videos="' + c.video_count +
                    '" data-links="' + c.link_count + '">' +
                    '<div class="bgo-client-head" onclick="toggleClient(this)">' +
                        '<div class="bgo-av">' + esc(initial) + '</div>' +
                        '<div class="bgo-client-id">' +
                            '<strong>' + esc(c.name) + '</strong>' +
                            '<span>' + esc(c.email) + '</span>' +
                        '</div>' +
                        '<div class="bgo-client-tallies">' +
                            '<span title="Cards">🎂 ' + c.card_count + '</span>' +
                            '<span title="Images">📸 ' + c.image_count + '</span>' +
                            '<span title="Videos">🎥 ' + c.video_count + '</span>' +
                            '<span title="Generated links">🔗 ' + c.link_count + '</span>' +
                        '</div>' +
                        '<div class="bgo-caret">▾</div>' +
                    '</div>' +
                    '<div class="bgo-client-body">' +
                        '<div class="bgo-kv-grid">' + meta + '</div>' +
                        cards +
                    '</div>' +
                '</div>';
            }).join('');
        }

        function renderCard(card) {
            const link = card.link
                ? '<div class="bgo-link"><span>🔗</span>' +
                  '<a href="' + esc(card.link) + '" target="_blank" rel="noopener">' + esc(card.link) + '</a>' +
                  '<button class="bgo-copy" onclick="copyLink(this, \'' + esc(card.link) + '\')">Copy</button></div>'
                : '<div class="bgo-link none">No link generated yet</div>';

            const images = card.images.length
                ? '<div class="bgo-media-grid">' + card.images.map(img =>
                    '<a class="bgo-media" href="' + esc(img.url) + '" target="_blank" rel="noopener">' +
                    '<img src="' + esc(img.url) + '" alt="" loading="lazy">' +
                    '<span>' + esc(img.slot) + '</span></a>'
                ).join('') + '</div>'
                : '<div class="bgo-empty">No images uploaded.</div>';

            const videos = card.videos.length
                ? '<div class="bgo-media-grid">' + card.videos.map(v =>
                    '<div class="bgo-media video">' +
                    '<video src="' + esc(v.url) + '" controls preload="metadata"></video>' +
                    '<span>' + esc(v.slot) + '</span></div>'
                ).join('') + '</div>'
                : '<div class="bgo-empty">No videos uploaded.</div>';

            const texts = card.texts.length
                ? '<div class="bgo-texts">' + card.texts.map(t =>
                    '<div class="bgo-text"><span>' + esc(t.label) + '</span><p>' + esc(t.value) + '</p></div>'
                ).join('') + '</div>'
                : '<div class="bgo-empty">Nothing typed in yet.</div>';

            return '' +
                '<div class="bgo-card">' +
                    '<div class="bgo-card-head">' +
                        '<strong>' + esc(card.title) + '</strong>' +
                        '<span class="bgo-tag ' + (card.published ? 'done' : 'draft') + '">' +
                            (card.published ? 'Completed' : 'Draft · step ' + card.step + '/10') + '</span>' +
                        '<span class="bgo-tag plain">' + esc(card.theme || 'no theme') +
                            (card.variant ? ' · design ' + card.variant : '') + '</span>' +
                        '<span class="bgo-tag plain">💾 ' + esc(card.storage) + '</span>' +
                        (card.lock_code ? '<span class="bgo-tag lock">🔒 PIN ' + esc(card.lock_code) + '</span>' : '') +
                        '<span class="bgo-when">created ' + esc(card.created_at) +
                            ' · edited ' + esc(card.updated_at) + '</span>' +
                    '</div>' +
                    link +
                    '<div class="bgo-sub">📸 Images (' + card.images.length + ')</div>' + images +
                    '<div class="bgo-sub">🎥 Videos (' + card.videos.length + ')</div>' + videos +
                    '<div class="bgo-sub">📄 Content (' + card.texts.length + ' fields)</div>' + texts +
                '</div>';
        }

        function toggleClient(head) {
            head.parentElement.classList.toggle('open');
        }

        function copyLink(btn, url) {
            event.stopPropagation();
            navigator.clipboard.writeText(url).then(() => {
                const original = btn.textContent;
                btn.textContent = 'Copied ✓';
                setTimeout(() => btn.textContent = original, 1600);
            });
        }

        function setFilter(chip, filter) {
            BGO_FILTER = filter;
            document.querySelectorAll('.bgo-chip').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            filterClients();
        }

        function filterClients() {
            const q = document.getElementById('bgoSearch').value.toLowerCase().trim();

            document.querySelectorAll('.bgo-client').forEach(el => {
                const matchesText = !q || el.dataset.search.includes(q);
                const matchesFilter = BGO_FILTER === 'all'
                    || Number(el.dataset[BGO_FILTER] || 0) > 0;
                el.style.display = (matchesText && matchesFilter) ? '' : 'none';
            });
        }
    </script>
</body>

</html>