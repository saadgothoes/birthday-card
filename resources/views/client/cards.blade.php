<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — BirthdayCard</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #f6f4fb;
            --surface: #ffffff;
            --surface2: #faf9ff;
            --border: #e9e3f8;
            --border2: #d9cdf7;
            --text: #140f1f;
            --text-muted: #6b6478;
            --text-dim: #a49db3;
            --accent: #8B5CF6;
            --accent2: #a78bfa;
            --accent-soft: #f3edfe;
            --green: #10b981;
            --green-soft: #ecfdf5;
            --amber: #f59e0b;
            --amber-soft: #fffbeb;
            --red: #ef4444;
            --red-soft: #fef2f2;
            --radius: 18px;
            --shadow: 0 1px 3px rgba(90, 60, 160, .05), 0 8px 32px rgba(90, 60, 160, .06);
            --sidebar-w: 260px;
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
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
        }

        /* ── Sidebar ───────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--surface);
            border-right: 1.5px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 1.6rem 0;
            z-index: 40;
        }

        .sb-brand {
            padding: 0 1.4rem 1.4rem;
            border-bottom: 1.5px solid var(--border);
            margin-bottom: 1.2rem;
        }

        .sb-brand .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .sb-brand .logo span {
            color: var(--accent);
        }

        .sb-brand p {
            font-size: .75rem;
            color: var(--text-muted);
            margin-top: .15rem;
        }

        .sb-nav {
            padding: 0 .9rem;
            flex: 1;
            overflow-y: auto;
        }

        .sb-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .07em;
            font-weight: 700;
            color: var(--text-dim);
            padding: 0 .5rem;
            margin-bottom: .5rem;
        }

        .sb-item {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .7rem .8rem;
            border-radius: 11px;
            font-size: .87rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: .25rem;
            transition: background .18s ease, color .18s ease;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            text-align: left;
        }

        .sb-item:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .sb-item.active {
            background: var(--accent);
            color: #fff;
        }

        .sb-item .ico {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .sb-item .tally {
            margin-left: auto;
            font-size: .72rem;
            font-weight: 700;
            background: var(--accent-soft);
            color: var(--accent);
            padding: .1rem .45rem;
            border-radius: 999px;
        }

        .sb-item.active .tally {
            background: rgba(255, 255, 255, .25);
            color: #fff;
        }

        .sb-foot {
            padding: 1rem 1.4rem 0;
            border-top: 1.5px solid var(--border);
            margin-top: 1rem;
        }

        .sb-user {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-bottom: .8rem;
        }

        .sb-av {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: .85rem;
            flex-shrink: 0;
        }

        .sb-user-meta {
            min-width: 0;
        }

        .sb-user-meta strong {
            display: block;
            font-size: .82rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sb-user-meta span {
            display: block;
            font-size: .7rem;
            color: var(--text-muted);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ── Main ──────────────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            padding: 2rem 2.2rem 4rem;
            max-width: 1240px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }

        .topbar h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .topbar p {
            font-size: .88rem;
            color: var(--text-muted);
            margin-top: .2rem;
        }

        .menu-toggle {
            display: none;
            border: 1.5px solid var(--border);
            background: var(--surface);
            border-radius: 10px;
            padding: .5rem .7rem;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            border: none;
            cursor: pointer;
            font-size: .86rem;
            font-weight: 600;
            padding: .7rem 1.25rem;
            border-radius: 11px;
            background: var(--accent);
            color: #fff;
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(139, 92, 246, .3);
        }

        .btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-ghost {
            background: var(--surface);
            color: var(--text);
            border: 1.5px solid var(--border);
        }

        .btn-ghost:hover {
            box-shadow: none;
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-sm {
            padding: .45rem .85rem;
            font-size: .78rem;
        }

        .btn-danger {
            background: transparent;
            color: var(--red);
            border: 1.5px solid transparent;
        }

        .btn-danger:hover {
            background: var(--red-soft);
            transform: none;
            box-shadow: none;
        }

        /* ── Stat cards ────────────────────────────────────── */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 1rem;
            margin-bottom: 1.6rem;
        }

        .stat {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.35rem;
            box-shadow: var(--shadow);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .8rem;
        }

        .stat-ico {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            font-size: 1.05rem;
            background: var(--accent-soft);
        }

        .stat-ico.g {
            background: var(--green-soft);
        }

        .stat-ico.a {
            background: var(--amber-soft);
        }

        .stat-num {
            font-size: 1.7rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-num.sm {
            font-size: 1.05rem;
        }

        .stat-lbl {
            font-size: .78rem;
            color: var(--text-muted);
            margin-top: .35rem;
        }

        .meter {
            height: 5px;
            border-radius: 999px;
            background: #efeaf9;
            overflow: hidden;
            margin-top: .7rem;
        }

        .meter i {
            display: block;
            height: 100%;
            background: var(--accent);
            border-radius: 999px;
        }

        .meter i.full {
            background: var(--amber);
        }

        /* ── Panels ────────────────────────────────────────── */
        .cols {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.3rem;
            align-items: start;
        }

        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1.4rem 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.3rem;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            margin-bottom: 1.1rem;
        }

        .panel-head h3 {
            font-size: 1rem;
            font-weight: 700;
        }

        .panel-head .count {
            font-size: .76rem;
            color: var(--text-muted);
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: .18rem .55rem;
            border-radius: 999px;
        }

        /* ── Subscription ──────────────────────────────────── */
        .sub-state {
            display: flex;
            align-items: center;
            gap: .7rem;
            margin-bottom: 1rem;
        }

        .badge {
            display: inline-block;
            padding: .28rem .7rem;
            border-radius: 999px;
            font-size: .73rem;
            font-weight: 700;
        }

        .badge.active {
            background: var(--green-soft);
            color: var(--green);
        }

        .badge.pending {
            background: var(--amber-soft);
            color: var(--amber);
        }

        .badge.none {
            background: #f1f0f5;
            color: var(--text-muted);
        }

        .sub-row {
            display: flex;
            justify-content: space-between;
            font-size: .84rem;
            padding: .5rem 0;
            border-bottom: 1px solid var(--border);
        }

        .sub-row:last-of-type {
            border-bottom: none;
        }

        .sub-row span {
            color: var(--text-muted);
        }

        .sub-row b {
            font-weight: 600;
        }

        .sub-note {
            font-size: .78rem;
            color: var(--text-muted);
            line-height: 1.55;
            margin-top: .8rem;
            padding-top: .8rem;
            border-top: 1px solid var(--border);
        }

        /* ── Activity ──────────────────────────────────────── */
        .act {
            display: flex;
            gap: .7rem;
            padding: .65rem 0;
            border-bottom: 1px solid var(--border);
            font-size: .84rem;
        }

        .act:last-child {
            border-bottom: none;
        }

        .act-ico {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--surface2);
            border: 1px solid var(--border);
            display: grid;
            place-items: center;
            font-size: .8rem;
        }

        .act-body {
            min-width: 0;
        }

        .act-body strong {
            font-weight: 600;
        }

        .act-body .when {
            display: block;
            font-size: .73rem;
            color: var(--text-dim);
            margin-top: .1rem;
        }

        /* ── Card grid ─────────────────────────────────────── */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(228px, 1fr));
            gap: 1rem;
        }

        .tile {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 15px;
            padding: 1.1rem;
            display: flex;
            flex-direction: column;
            gap: .65rem;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .tile:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: var(--border2);
        }

        .tile-top {
            display: flex;
            align-items: center;
            gap: .65rem;
            min-width: 0;
        }

        .tile-thumb {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            background: var(--accent-soft);
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .tile-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tile-name {
            font-weight: 700;
            font-size: .92rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tile-meta {
            font-size: .73rem;
            color: var(--text-muted);
        }

        .tile-foot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .4rem;
            margin-top: .15rem;
        }

        .tile-actions {
            display: flex;
            align-items: center;
            gap: .25rem;
        }

        .pill {
            font-size: .66rem;
            font-weight: 700;
            padding: .2rem .5rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .pill.draft {
            background: var(--amber-soft);
            color: var(--amber);
        }

        .pill.done {
            background: var(--green-soft);
            color: var(--green);
        }

        .tile-new {
            border: 2px dashed var(--border2);
            background: var(--surface2);
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 172px;
            cursor: pointer;
            gap: .35rem;
        }

        .tile-new:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
            transform: translateY(-2px);
        }

        .tile-new .plus {
            font-size: 1.8rem;
            color: var(--accent);
            line-height: 1;
        }

        .tile-new strong {
            font-size: .93rem;
        }

        .tile-new small {
            font-size: .75rem;
            color: var(--text-muted);
        }

        .tile-new[disabled] {
            opacity: .55;
            cursor: not-allowed;
        }

        .empty {
            border: 1.5px dashed var(--border);
            border-radius: 14px;
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: .86rem;
            background: var(--surface2);
        }

        /* ── Flash ─────────────────────────────────────────── */
        .flash {
            padding: .85rem 1.1rem;
            border-radius: 12px;
            font-size: .86rem;
            font-weight: 500;
            margin-bottom: 1.3rem;
        }

        .flash.success {
            background: var(--green-soft);
            color: #047857;
        }

        .flash.error {
            background: var(--red-soft);
            color: #b91c1c;
        }

        /* ── Modal ─────────────────────────────────────────── */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(20, 15, 31, .55);
            display: none;
            place-items: center;
            padding: 1.5rem;
            z-index: 60;
        }

        .modal.open {
            display: grid;
        }

        .modal-box {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.8rem;
            width: min(440px, 100%);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-box h3 {
            font-size: 1.12rem;
            margin-bottom: .3rem;
        }

        .modal-box p.sub {
            font-size: .84rem;
            color: var(--text-muted);
            margin-bottom: 1.2rem;
        }

        .modal-box input[type=text] {
            width: 100%;
            font-family: inherit;
            font-size: .9rem;
            padding: .72rem .9rem;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            outline: none;
        }

        .modal-box input[type=text]:focus {
            border-color: var(--accent);
        }

        .plan-opt {
            display: flex;
            align-items: center;
            gap: .8rem;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: .85rem 1rem;
            margin-bottom: .6rem;
            cursor: pointer;
            transition: border-color .18s ease, background .18s ease;
        }

        .plan-opt:hover {
            border-color: var(--border2);
        }

        .plan-opt.selected {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .plan-opt input {
            accent-color: var(--accent);
        }

        .plan-opt .amount {
            font-weight: 700;
        }

        .plan-opt .cards {
            font-size: .78rem;
            color: var(--text-muted);
        }

        .modal-actions {
            display: flex;
            gap: .6rem;
            justify-content: flex-end;
            margin-top: 1.3rem;
        }

        /* ── Responsive ────────────────────────────────────── */
        @media (max-width: 1080px) {
            .cols {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 860px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
                box-shadow: 0 0 40px rgba(20, 15, 31, .18);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                padding: 1.4rem 1.1rem 3rem;
            }

            .menu-toggle {
                display: block;
            }

            .backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(20, 15, 31, .4);
                z-index: 35;
            }

            .backdrop.open {
                display: block;
            }
        }
    </style>
</head>

<body>

    <div class="backdrop" id="backdrop" onclick="toggleSidebar()"></div>

    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar" id="sidebar">
        <div class="sb-brand">
            <div class="logo">🎂 Birthday<span>Card</span></div>
            <p>Creator Dashboard</p>
        </div>

        <nav class="sb-nav">
            <div class="sb-label">Menu</div>

            {{-- Main Dashboard sits at the very top, so it is always the way back. --}}
            <a href="{{ route('client.cards') }}" class="sb-item active">
                <span class="ico">🏠</span> Main Dashboard
            </a>
            <button class="sb-item" onclick="jumpTo('recentSection')">
                <span class="ico">🕘</span> Recent
                <span class="tally">{{ $recent->count() }}</span>
            </button>
            <button class="sb-item" onclick="jumpTo('draftsSection')">
                <span class="ico">📝</span> Drafts
                <span class="tally">{{ $drafts->count() }}</span>
            </button>
            <button class="sb-item" onclick="jumpTo('completedSection')">
                <span class="ico">✅</span> Completed
                <span class="tally">{{ $completed->count() }}</span>
            </button>

            <div class="sb-label" style="margin-top:1.2rem">Account</div>
            <a href="{{ route('client.profile') }}" class="sb-item"><span class="ico">👤</span> My Profile</a>
            <a href="{{ route('client.settings') }}" class="sb-item"><span class="ico">⚙️</span> Settings</a>
        </nav>

        <div class="sb-foot">
            <div class="sb-user">
                <div class="sb-av">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="sb-user-meta">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>{{ Auth::user()->email }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">
                    Log out
                </button>
            </form>
        </div>
    </aside>

    <!-- ─── MAIN ─── -->
    <main class="main">

        <div class="topbar">
            <div style="display:flex;align-items:center;gap:.8rem;">
                <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                <div>
                    <h1>Welcome back, {{ explode(' ', Auth::user()->name)[0] }}</h1>
                    <p>Here is everything you have built so far.</p>
                </div>
            </div>

            @if ($cardsRemaining > 0)
                <form method="POST" action="{{ route('client.cards.store') }}">
                    @csrf
                    <button type="submit" class="btn">+ New Card</button>
                </form>
            @else
                <button class="btn" disabled title="Card limit reached">+ New Card</button>
            @endif
        </div>

        @if (session('success'))
            <div class="flash success">✅ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash error">⚠️ {{ session('error') }}</div>
        @endif

        {{-- ── At-a-glance numbers ── --}}
        <div class="stats">
            <div class="stat">
                <div class="stat-top">
                    <div class="stat-ico">🎂</div>
                </div>
                <div class="stat-num">{{ $cardsUsed }}<span
                        style="font-size:.95rem;color:var(--text-dim);font-weight:600;"> / {{ $cardLimit }}</span>
                </div>
                <div class="stat-lbl">Cards created</div>
                @php $pct = $cardLimit > 0 ? min(100, round($cardsUsed / $cardLimit * 100)) : 0; @endphp
                <div class="meter"><i class="{{ $pct >= 100 ? 'full' : '' }}" style="width: {{ $pct }}%"></i></div>
            </div>

            <div class="stat">
                <div class="stat-top">
                    <div class="stat-ico a">📝</div>
                </div>
                <div class="stat-num">{{ $drafts->count() }}</div>
                <div class="stat-lbl">Drafts in progress</div>
            </div>

            <div class="stat">
                <div class="stat-top">
                    <div class="stat-ico g">✅</div>
                </div>
                <div class="stat-num">{{ $completed->count() }}</div>
                <div class="stat-lbl">Completed &amp; shareable</div>
            </div>

            <div class="stat">
                <div class="stat-top">
                    <div class="stat-ico">🎟️</div>
                </div>
                <div class="stat-num sm">{{ Auth::user()->planLabel() }}</div>
                <div class="stat-lbl">{{ $cardsRemaining }} card{{ $cardsRemaining === 1 ? '' : 's' }} remaining</div>
            </div>
        </div>

        <div class="cols">
            <div>
                {{-- ── Recent ── --}}
                <div class="panel" id="recentSection">
                    <div class="panel-head">
                        <h3>Recent</h3>
                        <span class="count">{{ $recent->count() }} shown</span>
                    </div>

                    <div class="grid">
                        @if ($cardsRemaining > 0)
                            <form method="POST" action="{{ route('client.cards.store') }}" style="display:contents;">
                                @csrf
                                <button type="submit" class="tile tile-new">
                                    <span class="plus">+</span>
                                    <strong>New Card</strong>
                                    <small>Start from scratch</small>
                                </button>
                            </form>
                        @else
                            <button class="tile tile-new" disabled title="Card limit reached">
                                <span class="plus">+</span>
                                <strong>New Card</strong>
                                <small>Limit reached — upgrade your plan</small>
                            </button>
                        @endif

                        @foreach ($recent as $card)
                            @include('client.partials.card-tile', ['card' => $card])
                        @endforeach
                    </div>
                </div>

                {{-- ── Drafts ── --}}
                <div class="panel" id="draftsSection">
                    <div class="panel-head">
                        <h3>Drafts</h3>
                        <span class="count">{{ $drafts->count() }}</span>
                    </div>

                    @if ($drafts->isEmpty())
                        <div class="empty">No drafts yet — a card stays here until you generate its QR code.</div>
                    @else
                        <div class="grid">
                            @foreach ($drafts as $card)
                                @include('client.partials.card-tile', ['card' => $card])
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── Completed ── --}}
                <div class="panel" id="completedSection">
                    <div class="panel-head">
                        <h3>Completed</h3>
                        <span class="count">{{ $completed->count() }}</span>
                    </div>

                    @if ($completed->isEmpty())
                        <div class="empty">Cards appear here once their QR code has been generated.</div>
                    @else
                        <div class="grid">
                            @foreach ($completed as $card)
                                @include('client.partials.card-tile', ['card' => $card])
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Right rail ── --}}
            <div>
                <div class="panel">
                    <div class="panel-head">
                        <h3>Subscription</h3>
                    </div>

                    <div class="sub-state">
                        @if (Auth::user()->hasActiveSubscription())
                            <span class="badge active">Active</span>
                        @elseif ($pendingRequest)
                            <span class="badge pending">Pending approval</span>
                        @else
                            <span class="badge none">Not subscribed</span>
                        @endif
                    </div>

                    <div class="sub-row"><span>Plan</span><b>{{ Auth::user()->planLabel() }}</b></div>
                    <div class="sub-row"><span>Allowed cards</span><b>{{ $cardLimit }}</b></div>
                    <div class="sub-row"><span>Created cards</span><b>{{ $cardsUsed }}</b></div>
                    <div class="sub-row"><span>Remaining</span><b>{{ $cardsRemaining }}</b></div>
                    @if (Auth::user()->subscription_activated_at)
                        <div class="sub-row"><span>Active since</span>
                            <b>{{ Auth::user()->subscription_activated_at->format('d M Y') }}</b>
                        </div>
                    @endif

                    @if (Auth::user()->hasActiveSubscription())
                        <p class="sub-note">✅ You can generate QR codes for your cards.</p>
                    @elseif ($pendingRequest)
                        <p class="sub-note">
                            ⏳ Requested
                            <strong>{{ \App\Support\SubscriptionPlans::label($pendingRequest->plan_amount) }}</strong>
                            on {{ $pendingRequest->created_at->format('d M Y') }}. QR generation unlocks as soon as
                            the admin approves it.
                        </p>
                    @else
                        <p class="sub-note">🔒 A QR code can only be generated on an active subscription.</p>
                        <button class="btn" style="width:100%;justify-content:center;margin-top:.9rem;"
                            onclick="openPlanModal()">Request Subscription</button>
                    @endif
                </div>

                <div class="panel">
                    <div class="panel-head">
                        <h3>Recent Activity</h3>
                    </div>

                    @if ($activity->isEmpty())
                        <div class="empty" style="padding:1.3rem;">Nothing yet — create your first card.</div>
                    @else
                        @foreach ($activity as $event)
                            <div class="act">
                                <div class="act-ico">{{ $event['icon'] }}</div>
                                <div class="act-body">
                                    {{ $event['text'] }} <strong>{{ $event['card']->displayTitle() }}</strong>
                                    <span class="when">{{ $event['at']?->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </main>

    {{-- ── Subscription request modal ── --}}
    <div class="modal" id="planModal">
        <div class="modal-box">
            <h3>Request a Subscription</h3>
            <p class="sub">Pick a plan and send the request to the admin. Payment is not collected here yet —
                your subscription activates once the admin approves it.</p>

            <form method="POST" action="{{ route('client.subscription.request') }}">
                @csrf
                @foreach ($plans as $i => $plan)
                    <label class="plan-opt {{ $i === 0 ? 'selected' : '' }}" onclick="pickPlan(this)">
                        <input type="radio" name="plan_amount" value="{{ $plan['amount'] }}"
                            {{ $i === 0 ? 'checked' : '' }} required>
                        <span>
                            <span class="amount">Rs {{ number_format($plan['amount']) }}</span><br>
                            <span class="cards">{{ $plan['cards'] }}
                                {{ $plan['cards'] === 1 ? 'card' : 'cards' }}</span>
                        </span>
                    </label>
                @endforeach

                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" onclick="closePlanModal()">Cancel</button>
                    <button type="submit" class="btn">Send Request</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Rename modal ── --}}
    <div class="modal" id="renameModal">
        <div class="modal-box">
            <h3>Rename Card</h3>
            <p class="sub">Give this card a label you will recognise on your dashboard.</p>

            <form method="POST" id="renameForm">
                @csrf
                @method('PATCH')
                <input type="text" name="title" id="renameInput" maxlength="80" required
                    placeholder="e.g. Ayesha's 21st Birthday">
                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" onclick="closeRenameModal()">Cancel</button>
                    <button type="submit" class="btn">Save Name</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('backdrop').classList.toggle('open');
        }

        function jumpTo(id) {
            document.getElementById(id).scrollIntoView({ behavior: 'smooth', block: 'start' });
            if (window.innerWidth <= 860) toggleSidebar();
        }

        function openPlanModal() {
            document.getElementById('planModal').classList.add('open');
        }

        function closePlanModal() {
            document.getElementById('planModal').classList.remove('open');
        }

        function pickPlan(label) {
            document.querySelectorAll('.plan-opt').forEach(el => el.classList.remove('selected'));
            label.classList.add('selected');
        }

        function openRenameModal(action, current) {
            const form = document.getElementById('renameForm');
            form.action = action;
            const input = document.getElementById('renameInput');
            input.value = current;
            document.getElementById('renameModal').classList.add('open');
            input.focus();
            input.select();
        }

        function closeRenameModal() {
            document.getElementById('renameModal').classList.remove('open');
        }

        document.querySelectorAll('.modal').forEach(m => {
            m.addEventListener('click', function (e) {
                if (e.target === this) this.classList.remove('open');
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.open').forEach(m => m.classList.remove('open'));
            }
        });
    </script>
</body>

</html>
