<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Giftloft</title>
    {{-- Tab icon — the app tile, same mark on every surface. --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo/clean/appicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/clean/appicon.png') }}">
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
            width: calc(100% - var(--sidebar-w));
            max-width: none;
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
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            padding: 0;
            border: 1.5px solid var(--accent);
            background: var(--accent-soft);
            color: var(--accent);
            border-radius: 10px;
            font-size: 1.15rem;
            line-height: 1;
            cursor: pointer;
            flex-shrink: 0;
            transition: transform .18s ease, background .18s ease, color .18s ease;
        }

        .menu-toggle:hover {
            background: var(--accent);
            color: #fff;
        }

        .menu-toggle:active {
            transform: scale(.94);
        }

        /* ── Card tabs ── */
        .tabbar {
            display: flex;
            gap: .5rem;
            margin-bottom: 1.1rem;
            overflow-x: auto;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .tabbar::-webkit-scrollbar {
            display: none;
        }

        .tab {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            flex-shrink: 0;
            font-family: inherit;
            font-size: .86rem;
            font-weight: 600;
            color: var(--text-muted);
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 999px;
            padding: .55rem 1rem;
            cursor: pointer;
            transition: border-color .18s ease, color .18s ease, background .18s ease;
        }

        .tab:hover {
            border-color: var(--border2);
            color: var(--text);
        }

        .tab.active {
            background: var(--accent-soft);
            border-color: var(--accent);
            color: var(--accent);
        }

        .tab-count {
            font-size: .74rem;
            font-weight: 700;
            background: var(--border);
            color: var(--text-muted);
            border-radius: 999px;
            padding: .1rem .45rem;
            min-width: 20px;
            text-align: center;
        }

        .tab.active .tab-count {
            background: var(--accent);
            color: #fff;
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

        .section-note {
            color: var(--text-muted);
            font-size: .78rem;
            line-height: 1.45;
            margin: -.45rem 0 1rem;
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

        /* Status on its own line, buttons underneath. Side by side, a tile with
           three actions (Edit / rename / Disable) wrapped into a ragged three
           rows once the grid column dropped near its 228px minimum. */
        .tile-foot {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: .45rem;
            margin-top: .15rem;
        }

        .tile-foot .pill {
            align-self: flex-start;
        }

        .tile-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .3rem;
            justify-content: flex-start;
        }

        .tile-actions > a.btn-sm {
            flex: 1 1 auto;
            min-width: 0;
            justify-content: center;
        }

        .qr-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .35rem;
            padding-top: .15rem;
        }

        .qr-block img {
            width: 120px;
            height: 120px;
            display: block;
        }

        .qr-download {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            color: var(--accent);
            border: 1px solid var(--border2);
            border-radius: 8px;
            padding: .3rem .55rem;
            font-size: .7rem;
            font-weight: 600;
            text-decoration: none;
            background: var(--surface);
        }

        .qr-download:hover {
            background: var(--accent-soft);
        }

        .share-row {
            display: flex;
            align-items: center;
            gap: .4rem;
            min-width: 0;
        }

        .share-row .tile-meta {
            flex: 1;
            min-width: 0;
        }

        .copy-link {
            flex-shrink: 0;
            border: 1px solid var(--border2);
            border-radius: 8px;
            padding: .3rem .55rem;
            color: var(--accent);
            background: var(--surface);
            cursor: pointer;
            font: inherit;
            font-size: .7rem;
            font-weight: 600;
        }

        .copy-link:hover {
            background: var(--accent-soft);
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
            width: 100%;
            max-width: 100%;
            overflow-wrap: anywhere;
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

        /* ── Plan cards ──
           Proper package cards rather than a stack of radio rows: the admin's
           own plan name and blurb carry as much weight here as the price. */
        .plan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: .7rem;
            margin-bottom: .4rem;
        }

        .plan-opt {
            position: relative;
            display: flex;
            flex-direction: column;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 1.1rem .9rem .95rem;
            cursor: pointer;
            background: var(--surface);
            transition: border-color .18s ease, background .18s ease, box-shadow .18s ease,
                transform .18s ease;
        }

        .plan-opt:hover {
            border-color: var(--border2);
            transform: translateY(-2px);
        }

        .plan-opt.selected {
            border-color: var(--accent);
            background: var(--accent-soft);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, .13);
        }

        /* The radio still drives the form; the card is its label. */
        .plan-opt input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .plan-opt .tick {
            position: absolute;
            top: .6rem;
            right: .6rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1.5px solid var(--border2);
            display: grid;
            place-items: center;
            font-size: .62rem;
            color: transparent;
            transition: all .18s ease;
        }

        .plan-opt.selected .tick {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .plan-opt .plan-name {
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: .3rem;
        }

        .plan-opt .amount {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
            line-height: 1.1;
            color: var(--text);
        }

        .plan-opt .amount small {
            font-family: 'DM Sans', sans-serif;
            font-size: .72rem;
            font-weight: 600;
            color: var(--text-dim);
        }

        .plan-opt .cards {
            display: inline-block;
            font-size: .76rem;
            font-weight: 700;
            color: #047857;
            background: var(--green-soft);
            border-radius: 999px;
            padding: .18rem .5rem;
            margin-top: .45rem;
        }

        .plan-opt .plan-desc {
            font-size: .74rem;
            line-height: 1.45;
            color: var(--text-muted);
            margin-top: .5rem;
        }

        .plan-opt .per-card {
            font-size: .68rem;
            color: var(--text-dim);
            margin-top: auto;
            padding-top: .55rem;
        }

        .plan-opt .flag {
            position: absolute;
            top: -9px;
            left: .8rem;
            font-size: .6rem;
            font-weight: 800;
            letter-spacing: .06em;
            color: #fff;
            background: var(--accent);
            border-radius: 999px;
            padding: .16rem .45rem;
        }

        .modal-actions {
            display: flex;
            gap: .6rem;
            justify-content: flex-end;
            margin-top: 1.3rem;
        }

        /* ── Locked-card dialog ──────────────────────────── */
        .lock-ico {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 1.35rem;
            background: var(--amber-soft);
            margin-bottom: .9rem;
        }

        .lock-card {
            font-size: .82rem;
            font-weight: 600;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: .7rem .85rem;
            margin-bottom: 1rem;
            overflow-wrap: anywhere;
        }

        .lock-card span {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .15rem;
        }

        .lock-points {
            list-style: none;
            font-size: .83rem;
            color: var(--text-muted);
            line-height: 1.55;
            margin-bottom: .2rem;
        }

        .lock-points li {
            padding-left: 1.3rem;
            position: relative;
            margin-bottom: .35rem;
        }

        .lock-points li::before {
            content: '•';
            position: absolute;
            left: .35rem;
            color: var(--accent);
            font-weight: 700;
        }

        .lock-pending {
            font-size: .82rem;
            font-weight: 600;
            color: #b45309;
            background: var(--amber-soft);
            border-radius: 12px;
            padding: .7rem .85rem;
            margin-top: .4rem;
        }

        /* ── Delete dialog ───────────────────────────────── */
        .danger-ico {
            background: var(--red-soft);
        }

        .del-note {
            font-size: .8rem;
            color: var(--text-muted);
            background: var(--surface2);
            border-radius: 12px;
            padding: .7rem .85rem;
            line-height: 1.5;
        }

        /* The tile's 🗑 stays a quiet ghost button — all it does is open the
           dialog. The button that actually deletes is solid, so the two never
           read as the same weight of action. */
        .btn-danger-solid {
            background: var(--red);
            color: #fff;
            border: none;
        }

        .btn-danger-solid:hover {
            background: #dc2626;
            box-shadow: 0 8px 22px rgba(239, 68, 68, .3);
        }

        /* ── Payment step ────────────────────────────────── */
        .modal-box.wide {
            width: min(580px, 100%);
        }

        .pay-step[hidden] {
            display: none;
        }

        .pay-plan-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            background: var(--accent-soft);
            border: 1.5px solid var(--accent);
            border-radius: 12px;
            padding: .8rem 1rem;
            margin-bottom: 1.1rem;
        }

        .pay-plan-banner .amt {
            font-weight: 800;
            font-size: 1.1rem;
        }

        .pay-plan-banner .cds {
            font-size: .76rem;
            color: var(--text-muted);
        }

        .pay-plan-banner button {
            background: none;
            border: none;
            color: var(--accent);
            font-family: inherit;
            font-size: .78rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
        }

        .pay-section-label {
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted);
            margin: 1.1rem 0 .6rem;
        }

        .acct-opt {
            display: block;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: .85rem 1rem;
            margin-bottom: .6rem;
            cursor: pointer;
            transition: border-color .18s ease, background .18s ease;
        }

        .acct-opt:hover {
            border-color: var(--border2);
        }

        .acct-opt.selected {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .acct-opt input {
            display: none;
        }

        .acct-top {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .acct-ico {
            font-size: 1.1rem;
        }

        .acct-top strong {
            font-size: .92rem;
        }

        .acct-top .kind {
            font-size: .72rem;
            color: var(--text-muted);
            margin-left: auto;
        }

        .acct-rows {
            margin-top: .55rem;
            font-size: .82rem;
            display: flex;
            flex-direction: column;
            gap: .3rem;
        }

        .acct-rows div {
            display: flex;
            gap: .6rem;
            align-items: center;
        }

        .acct-rows .k {
            color: var(--text-muted);
            min-width: 74px;
        }

        .acct-rows .v {
            font-weight: 600;
            word-break: break-all;
            font-variant-numeric: tabular-nums;
        }

        .copy-mini {
            margin-left: auto;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 7px;
            font-family: inherit;
            font-size: .7rem;
            font-weight: 700;
            padding: .22rem .5rem;
            cursor: pointer;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .copy-mini:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .acct-note {
            margin-top: .5rem;
            font-size: .76rem;
            color: var(--text-muted);
        }

        .acct-qr {
            margin-top: .6rem;
            max-width: 140px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
        }

        .pay-field {
            margin-bottom: .8rem;
        }

        .pay-field label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            margin-bottom: .35rem;
        }

        .pay-field label .opt {
            font-weight: 400;
            color: var(--text-muted);
        }

        .pay-field input[type=text],
        .pay-field input[type=file],
        .pay-field textarea {
            width: 100%;
            font-family: inherit;
            font-size: .9rem;
            padding: .72rem .9rem;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            outline: none;
            background: var(--surface);
        }

        .pay-field textarea {
            resize: vertical;
            min-height: 70px;
        }

        .pay-field input:focus,
        .pay-field textarea:focus {
            border-color: var(--accent);
        }

        .shot-preview {
            margin-top: .6rem;
            max-width: 100%;
            max-height: 210px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            display: none;
        }

        .pay-errors {
            background: #fef2f2;
            border: 1.5px solid #ef4444;
            color: #b91c1c;
            border-radius: 10px;
            padding: .7rem .9rem;
            font-size: .82rem;
            margin-bottom: 1rem;
        }

        .pay-errors ul {
            margin: .3rem 0 0 1rem;
        }

        .no-accts {
            background: #fffbeb;
            border: 1.5px solid #f59e0b;
            color: #92400e;
            border-radius: 10px;
            padding: .8rem .9rem;
            font-size: .84rem;
        }

        /* ── Support panel ───────────────────────────────── */
        .support-box {
            margin-top: 1.2rem;
            border-top: 1.5px solid var(--border);
            padding-top: 1rem;
        }

        .support-box.bare {
            margin-top: 0;
            border-top: none;
            padding-top: 0;
        }

        .support-box h4 {
            font-size: .88rem;
            margin-bottom: .2rem;
        }

        .support-box p {
            font-size: .78rem;
            color: var(--text-muted);
            margin-bottom: .7rem;
        }

        .support-links {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .support-link {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            border: 1.5px solid var(--border);
            border-radius: 999px;
            padding: .45rem .85rem;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--text);
            transition: border-color .18s ease, background .18s ease;
        }

        .support-link:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .support-link__ico {
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .support-link span.val {
            font-weight: 400;
            color: var(--text-muted);
            font-size: .74rem;
        }

        /* Floating help button — reachable from anywhere on the hub, not just
           from inside the payment modal. */
        .help-fab {
            position: fixed;
            right: 1.4rem;
            bottom: 1.4rem;
            z-index: 55;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border: none;
            border-radius: 999px;
            padding: .8rem 1.2rem;
            font-family: inherit;
            font-size: .85rem;
            font-weight: 700;
            color: #fff;
            background: var(--accent);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
            cursor: pointer;
        }

        .help-fab:hover {
            filter: brightness(1.08);
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
                width: 100%;
                padding: 1.4rem 1.1rem 3rem;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .75rem;
            }

            .stat {
                padding: 1rem;
            }

            .stat-num {
                font-size: 1.45rem;
            }

            .stat-num.sm {
                font-size: .92rem;
            }

            .panel {
                padding: 1.1rem;
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

        /* ── Phones ──
           Below this the two-up stat grid and the fixed 228px tile columns stop
           fitting, and the payment modal has to stop behaving like a dialog. */
        @media (max-width: 560px) {
            .main {
                padding: 1.1rem .85rem 2.5rem;
            }

            .topbar {
                flex-direction: column;
                align-items: stretch;
                gap: .9rem;
            }

            .topbar h1 {
                font-size: 1.25rem;
            }

            .stats {
                grid-template-columns: 1fr 1fr;
                gap: .6rem;
            }

            .stat {
                padding: .85rem;
            }

            .stat-num {
                font-size: 1.25rem;
            }

            .stat-lbl {
                font-size: .72rem;
            }

            /* Two cards per row on phones. Everything inside a tile is scaled
               down to survive the ~155px column that leaves. */
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .6rem;
            }

            .tile {
                padding: .7rem;
                border-radius: 13px;
                gap: .45rem;
            }

            /* Thumb over name instead of beside it — side by side left the
               title about 90px, which truncated almost every card name. */
            .tile-top {
                flex-direction: column;
                align-items: flex-start;
                gap: .4rem;
            }

            .tile-thumb {
                width: 36px;
                height: 36px;
                border-radius: 9px;
                font-size: 1rem;
            }

            /* Only the text column stretches — matching every child also hit
               the thumb and blew it up to the full tile width. */
            .tile-top > div + div {
                width: 100%;
                min-width: 0;
            }

            .tile-name {
                font-size: .82rem;
            }

            .tile-meta {
                font-size: .67rem;
                line-height: 1.35;
            }

            .tile-foot {
                flex-direction: column;
                align-items: stretch;
                gap: .4rem;
            }

            .tile-actions {
                justify-content: flex-start;
                flex-wrap: nowrap;
                gap: .3rem;
            }

            /* Continue takes the slack, the two icon buttons stay square, so
               all three fit on one row instead of the delete wrapping under. */
            .tile-actions .btn-sm {
                padding: .3rem .42rem;
                font-size: .66rem;
            }

            .tile-actions > a.btn-sm {
                flex: 1 1 auto;
                min-width: 0;
                overflow: hidden;
                justify-content: center;
            }

            /* Rename and Delete are both bare buttons now that Delete opens a
               dialog instead of carrying its own form, so both need pinning. */
            .tile-actions form,
            .tile-actions>button.btn-sm {
                flex: 0 0 auto;
            }

            .tile-new {
                min-height: 132px;
            }

            .tile-new .plus {
                font-size: 1.5rem;
            }

            .tile-new strong {
                font-size: .82rem;
            }

            .tile-new small {
                font-size: .66rem;
            }

            /* The share link is far wider than the column: keep it to one
               ellipsised line so it cannot stretch the grid. */
            .share-row {
                flex-direction: column;
                align-items: stretch;
                gap: .3rem;
            }

            .share-row .tile-meta {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                word-break: normal !important;
            }

            .qr-block img {
                width: 96px;
                height: 96px;
            }

            .qr-download {
                font-size: .64rem;
                padding: .25rem .45rem;
            }

            .panel {
                padding: .95rem;
                border-radius: 14px;
            }

            .panel-head h3 {
                font-size: 1rem;
            }

            .tab {
                font-size: .76rem;
                padding: .45rem .7rem;
                gap: .3rem;
            }

            .tab-count {
                font-size: .68rem;
                padding: .05rem .35rem;
                min-width: 17px;
            }

            .tabbar {
                gap: .35rem;
            }

            /* The modal becomes a full-height sheet: a 580px dialog with its own
               scroll inside a 360px viewport was unusable. */
            .modal {
                padding: 0;
                place-items: end stretch;
            }

            .modal-box,
            .modal-box.wide {
                width: 100%;
                max-width: 100%;
                max-height: 92vh;
                border-radius: 18px 18px 0 0;
                padding: 1.3rem 1.1rem 1.6rem;
            }

            .modal-actions {
                flex-direction: column-reverse;
            }

            .modal-actions .btn,
            .modal-actions a.btn {
                width: 100%;
                justify-content: center;
            }

            .sub-plan-grid,
            .pay-plan-banner {
                flex-wrap: wrap;
            }

            .acct-rows .k {
                min-width: 62px;
            }

            .support-links {
                flex-direction: column;
                align-items: stretch;
            }

            .support-link {
                justify-content: flex-start;
            }

            /* The floating help button must not sit on top of the last tile's
               buttons on a short screen. */
            .help-fab {
                right: .9rem;
                bottom: .9rem;
                padding: .7rem 1rem;
                font-size: .8rem;
            }
        }

        /* Very narrow phones: keep the two-up rhythm, just tighten the tabs
           so all three fit without the bar having to scroll. */
        @media (max-width: 400px) {
            .tab {
                font-size: .72rem;
                padding: .42rem .55rem;
            }

            .stat-num {
                font-size: 1.15rem;
            }
        }
    
        /* The wordmark replaces the emoji lockup — height-locked so the
           sidebar keeps its spacing whatever the PNG measures. */
        .brand-logo {
            height: 40px;
            width: auto;
            display: block;
        }
    </style>
    @include('partials.no-input-zoom')

</head>

<body>

    <div class="backdrop" id="backdrop" onclick="toggleSidebar()"></div>

    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar" id="sidebar">
        <div class="sb-brand">
            <div class="logo"><img src="{{ asset('images/logo/clean/primarylogo.png') }}" alt="Giftloft" class="brand-logo"></div>
            <p>Creator Dashboard</p>
        </div>

        <nav class="sb-nav">
            <div class="sb-label">Menu</div>

            {{-- Main Dashboard sits at the very top, so it is always the way back. --}}
            <a href="{{ route('client.cards') }}" class="sb-item active">
                <span class="ico">🏠</span> Main Dashboard
            </a>
            <button class="sb-item" data-tab-link="recent" onclick="showTab('recent')">
                <span class="ico">🕘</span> Recent
                <span class="tally">{{ $recent->count() }}</span>
            </button>
            <button class="sb-item" data-tab-link="drafts" onclick="showTab('drafts')">
                <span class="ico">📝</span> Drafts
                <span class="tally">{{ $drafts->count() }}</span>
            </button>
            <button class="sb-item" data-tab-link="completed" onclick="showTab('completed')">
                <span class="ico">✅</span> Completed
                <span class="tally">{{ $completed->count() }}</span>
            </button>

            <div class="sb-label" style="margin-top:1.2rem">Account</div>
            <a href="{{ route('client.profile') }}" class="sb-item"><span class="ico">👤</span> My Profile</a>
            <a href="{{ route('client.settings') }}" class="sb-item"><span class="ico">⚙️</span> Settings</a>
            <a href="{{ route('client.contact') }}" class="sb-item">
                <span class="ico">@include('partials.channel-icon', ['channel' => 'phone', 'size' => 15])</span>
                Contact Us
            </a>
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
        @if (Auth::user()->needsTopUp())
            <div class="flash error">
                ⚠️ You have used all cards in your current subscription. Please subscribe to a new plan to create
                another card or make a new version. Your completed links remain available below.
            </div>
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
                {{-- ── Recent / Drafts / Completed ──
                     One at a time. Showing all three at once listed every card
                     twice: once under Recent and again under its own section. --}}
                <div class="tabbar" role="tablist">
                    <button class="tab" role="tab" data-tab-link="recent" onclick="showTab('recent')">
                        🕘 Recent <span class="tab-count">{{ $recent->count() }}</span>
                    </button>
                    <button class="tab" role="tab" data-tab-link="drafts" onclick="showTab('drafts')">
                        📝 Drafts <span class="tab-count">{{ $drafts->count() }}</span>
                    </button>
                    <button class="tab" role="tab" data-tab-link="completed" onclick="showTab('completed')">
                        ✅ Completed <span class="tab-count">{{ $completed->count() }}</span>
                    </button>
                </div>

                <div class="panel" id="recentSection" data-tab-panel="recent">
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
                <div class="panel" id="draftsSection" data-tab-panel="drafts" hidden>
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
                <div class="panel" id="completedSection" data-tab-panel="completed" hidden>
                    <div class="panel-head">
                        <h3>Completed</h3>
                        <span class="count">{{ $completed->count() }}</span>
                    </div>
                    <p class="section-note">Completed QR codes and links remain visible here, but each share link
                        automatically expires after 15 days.</p>

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
                        @if (Auth::user()->needsTopUp())
                            <span class="badge pending">Cards used — subscribe again</span>
                        @elseif (Auth::user()->hasActiveSubscription())
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

                    @if (Auth::user()->needsTopUp())
                        <p class="sub-note">⚠️ You have used all cards from this subscription. Request a new plan to
                            create another card or version. Your completed links remain available.</p>
                        <button class="btn" style="width:100%;justify-content:center;margin-top:.9rem;"
                            onclick="openPlanModal()">Subscribe Again</button>
                    @elseif (Auth::user()->hasActiveSubscription())
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

    {{-- ── Subscription request modal ──
         Two steps in one box: choose the plan, then pay into one of the
         admin's accounts and attach proof. The request is only filed once the
         proof is attached, so the admin always has something to verify. --}}
    <div class="modal" id="planModal">
        <div class="modal-box wide">

            {{-- Step 1 — pick a plan --}}
            <div class="pay-step" id="planStep">
                <h3>Choose a Plan</h3>
                <p class="sub">Pick the plan you want, then pay into one of our accounts on the next step.</p>

                @php
                    // Best value is derived from the plans themselves — the
                    // cheapest rupees-per-card — so it follows whatever the
                    // admin sets instead of being pinned to one package.
                    $bestValue = count($plans) > 1
                        ? collect($plans)->sortBy(fn ($p) => $p['amount'] / max(1, $p['cards']))->first()['amount']
                        : null;
                @endphp
                <div class="plan-grid">
                    @foreach ($plans as $i => $plan)
                        <label class="plan-opt {{ $i === 0 ? 'selected' : '' }}"
                            onclick="pickPlan(this, {{ $plan['amount'] }}, {{ $plan['cards'] }}, @js($plan['name'] ?? ''))">
                            <input type="radio" name="plan_pick" value="{{ $plan['amount'] }}"
                                {{ $i === 0 ? 'checked' : '' }}>

                            @if ($bestValue === $plan['amount'])
                                <span class="flag">BEST VALUE</span>
                            @endif
                            <span class="tick">✓</span>

                            @if (!empty($plan['name']))
                                <span class="plan-name">{{ $plan['name'] }}</span>
                            @endif

                            <span class="amount"><small>Rs</small> {{ number_format($plan['amount']) }}</span>
                            <span class="cards">{{ $plan['cards'] }}
                                {{ $plan['cards'] === 1 ? 'card' : 'cards' }}</span>

                            @if (!empty($plan['description']))
                                <span class="plan-desc">{{ $plan['description'] }}</span>
                            @endif

                            <span class="per-card">
                                Rs {{ number_format($plan['amount'] / max(1, $plan['cards'])) }} per card
                            </span>
                        </label>
                    @endforeach
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" onclick="closePlanModal()">Cancel</button>
                    <button type="button" class="btn" onclick="goToPayStep()">Continue to Payment →</button>
                </div>
            </div>

            {{-- Step 2 — pay, then send the proof --}}
            <div class="pay-step" id="payStep" hidden>
                <h3>Send Payment</h3>
                <p class="sub">Transfer the plan amount to any account below, then fill in the details and attach
                    your payment screenshot.</p>

                @if ($errors->any())
                    <div class="pay-errors">
                        <strong>Please fix the following:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="pay-plan-banner">
                    <div>
                        <div class="cds" id="payPlanName" hidden></div>
                        <div class="amt" id="payPlanAmount">Rs 0</div>
                        <div class="cds" id="payPlanCards">— cards</div>
                    </div>
                    <button type="button" onclick="backToPlanStep()">Change plan</button>
                </div>

                @if ($paymentMethods->isEmpty())
                    <div class="no-accts">
                        No payment accounts are set up right now. Please contact support below and we will take your
                        payment directly.
                    </div>
                @else
                    <form method="POST" action="{{ route('client.subscription.request') }}"
                        enctype="multipart/form-data" id="payForm">
                        @csrf
                        <input type="hidden" name="plan_amount" id="payPlanInput"
                            value="{{ old('plan_amount', $plans[0]['amount'] ?? '') }}">

                        <div class="pay-section-label">1 · Pay into one of these accounts</div>

                        @foreach ($paymentMethods as $i => $method)
                            @php $checked = (int) old('payment_method_id', $paymentMethods->first()->id) === $method->id; @endphp
                            <label class="acct-opt {{ $checked ? 'selected' : '' }}" onclick="pickAccount(this)">
                                <input type="radio" name="payment_method_id" value="{{ $method->id }}"
                                    {{ $checked ? 'checked' : '' }}>
                                <span class="acct-top">
                                    <span class="acct-ico">{{ $method->typeIcon() }}</span>
                                    <strong>{{ $method->label }}</strong>
                                    <span class="kind">{{ $method->typeLabel() }}</span>
                                </span>
                                <span class="acct-rows">
                                    <span style="display:flex;gap:.6rem;align-items:center;">
                                        <span class="k">Title</span>
                                        <span class="v">{{ $method->account_name }}</span>
                                    </span>
                                    <span style="display:flex;gap:.6rem;align-items:center;">
                                        <span class="k">{{ $method->isBank() ? 'IBAN / Acct' : 'Number' }}</span>
                                        <span class="v">{{ $method->account_number }}</span>
                                        <button type="button" class="copy-mini"
                                            onclick="event.preventDefault();event.stopPropagation();copyAccount(this,@json($method->account_number))">Copy</button>
                                    </span>
                                    @if ($method->bank_name)
                                        <span style="display:flex;gap:.6rem;align-items:center;">
                                            <span class="k">Bank</span>
                                            <span class="v">{{ $method->bank_name }}</span>
                                        </span>
                                    @endif
                                    @if ($method->branch_code)
                                        <span style="display:flex;gap:.6rem;align-items:center;">
                                            <span class="k">Branch</span>
                                            <span class="v">{{ $method->branch_code }}</span>
                                        </span>
                                    @endif
                                </span>
                                @if ($method->instructions)
                                    <span class="acct-note">{{ $method->instructions }}</span>
                                @endif
                                @if ($method->qr_image_path)
                                    <img class="acct-qr" src="{{ asset('storage/' . $method->qr_image_path) }}"
                                        alt="Payment QR for {{ $method->label }}">
                                @endif
                            </label>
                        @endforeach

                        <div class="pay-section-label">2 · Tell us where it came from</div>

                        <div class="pay-field">
                            <label for="senderName">Sender name</label>
                            <input type="text" name="sender_name" id="senderName" required maxlength="120"
                                placeholder="Name on the account you paid from"
                                value="{{ old('sender_name', Auth::user()->name) }}">
                        </div>

                        <div class="pay-field">
                            <label for="senderNumber">Sender number / account</label>
                            <input type="text" name="sender_number" id="senderNumber" required maxlength="60"
                                placeholder="e.g. 0300-1234567"
                                value="{{ old('sender_number', Auth::user()->phone) }}">
                        </div>

                        <div class="pay-field">
                            <label for="txnId">Transaction ID <span class="opt">(optional)</span></label>
                            <input type="text" name="transaction_id" id="txnId" maxlength="120"
                                placeholder="TID from your payment receipt" value="{{ old('transaction_id') }}">
                        </div>

                        <div class="pay-section-label">3 · Attach your payment screenshot</div>

                        <div class="pay-field">
                            <label for="shotInput">Payment screenshot</label>
                            <input type="file" name="payment_screenshot" id="shotInput" accept="image/*" required
                                onchange="previewShot(this)">
                            <img class="shot-preview" id="shotPreview" alt="Your payment screenshot">
                        </div>

                        <div class="pay-field">
                            <label for="clientNote">Anything else? <span class="opt">(optional)</span></label>
                            <textarea name="client_note" id="clientNote" maxlength="500"
                                placeholder="Add a note for the admin">{{ old('client_note') }}</textarea>
                        </div>

                        <div class="modal-actions">
                            <button type="button" class="btn btn-ghost" onclick="backToPlanStep()">← Back</button>
                            <button type="submit" class="btn" id="paySubmitBtn">Submit for Approval</button>
                        </div>
                    </form>
                @endif

                @include('client.partials.support-links', ['supportContacts' => $supportContacts])
            </div>
        </div>
    </div>

    {{-- ── Help modal ── --}}
    @if ($supportContacts->isNotEmpty())
        <button class="help-fab" onclick="openHelpModal()">💬 Need Help?</button>

        <div class="modal" id="helpModal">
            <div class="modal-box">
                <h3>Chat Support</h3>
                <p class="sub">Payment not going through, or something else stuck? Message us on any of these and
                    we will sort it out.</p>

                @include('client.partials.support-links', [
                    'supportContacts' => $supportContacts,
                    'bare' => true,
                ])

                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" onclick="closeHelpModal()">Close</button>
                    <a class="btn" href="{{ route('client.contact') }}">Open Contact Page →</a>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Locked card modal ──
         A finished card is reopened by cloning it into a new version, which
         costs a card slot. When there is none left, Edit lands here instead of
         on a 403 page, so the client can see why and request more cards
         without leaving the hub. --}}
    <div class="modal" id="limitModal">
        <div class="modal-box">
            <div class="lock-ico">🔒</div>
            <h3>No Card Slot Left</h3>
            <p class="sub">You have used all {{ $cardLimit }} card{{ $cardLimit === 1 ? '' : 's' }} on your
                {{ Auth::user()->planLabel() }} plan, so this finished card cannot be reopened right now.</p>

            <div class="lock-card" id="limitModalCard" hidden>
                <span>Card</span>
                <strong id="limitModalCardName"></strong>
            </div>

            <ul class="lock-points">
                <li>Editing a completed card saves it as a <strong>new version</strong>, and every version takes one
                    card slot.</li>
                <li>Your finished links and QR codes keep working — nothing you already shared is affected.</li>
                <li>Request a new plan to unlock more slots, then Edit will open as usual.</li>
            </ul>

            @if ($pendingRequest)
                <div class="lock-pending">⏳ Your plan request is with our team for review. We will unlock the extra
                    cards as soon as it is approved.</div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" onclick="closeLimitModal()">Close</button>
                    <a class="btn" href="{{ route('client.contact') }}">Contact Support →</a>
                </div>
            @else
                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" onclick="closeLimitModal()">Not Now</button>
                    <button type="button" class="btn" onclick="closeLimitModal(); openPlanModal();">Request More Cards
                        →</button>
                </div>
            @endif
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

    {{-- ── Delete card modal ──
         Deleting a draft also deletes the photos uploaded to it and there is no
         undo, so this question was worth asking — but it was being asked by the
         browser's own confirm(), which cannot be styled, opens under a
         "127.0.0.1:8000 says" header, and reads like a browser warning rather
         than part of the product. Same question, asked in the app's own voice,
         and with room to name the card and say what deleting gives back. --}}
    <div class="modal" id="deleteModal">
        <div class="modal-box">
            <div class="lock-ico danger-ico">🗑</div>
            <h3>Delete this card?</h3>
            <p class="sub">This removes the draft and every photo uploaded to it. It cannot be undone.</p>

            <div class="lock-card">
                <span>Card</span>
                <strong id="deleteModalCardName"></strong>
            </div>

            <p class="del-note">Deleting a draft gives its slot back, so you can start a new card in its place.</p>

            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" id="deleteCancel"
                        onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger-solid">Delete Card</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('backdrop').classList.toggle('open');
        }

        // ── Card tabs ──
        // Only one of Recent / Drafts / Completed is mounted at a time, so a
        // card is never listed twice on the same screen.
        function showTab(name) {
            document.querySelectorAll('[data-tab-panel]').forEach(panel => {
                panel.hidden = panel.dataset.tabPanel !== name;
            });
            document.querySelectorAll('[data-tab-link]').forEach(link => {
                link.classList.toggle('active', link.dataset.tabLink === name);
            });

            try {
                localStorage.setItem('cardsTab', name);
            } catch (e) {
                /* private mode — the tab just will not be remembered */
            }

            if (window.innerWidth <= 860 && document.getElementById('sidebar').classList.contains('open')) {
                toggleSidebar();
            }
        }

        (function () {
            let saved = 'recent';
            try {
                saved = localStorage.getItem('cardsTab') || 'recent';
            } catch (e) {
                /* ignore */
            }
            if (!document.querySelector('[data-tab-panel="' + saved + '"]')) saved = 'recent';
            showTab(saved);
        })();

        // ── Subscription: plan → payment → proof ──
        // The chosen plan is mirrored into the payment form's hidden input, so
        // the amount the client picked is the amount the request is filed for.
        let chosenPlan = {
            amount: {{ $plans[0]['amount'] ?? 0 }},
            cards: {{ $plans[0]['cards'] ?? 0 }},
            name: @js($plans[0]['name'] ?? ''),
        };

        function openPlanModal() {
            document.getElementById('planModal').classList.add('open');
            showPlanStep();
        }

        function closePlanModal() {
            document.getElementById('planModal').classList.remove('open');
        }

        function pickPlan(label, amount, cards, name) {
            document.querySelectorAll('.plan-opt').forEach(el => el.classList.remove('selected'));
            label.classList.add('selected');
            const radio = label.querySelector('input');
            if (radio) radio.checked = true;
            if (amount !== undefined) chosenPlan = { amount: amount, cards: cards, name: name || '' };
        }

        function showPlanStep() {
            document.getElementById('planStep').hidden = false;
            document.getElementById('payStep').hidden = true;
        }

        function backToPlanStep() {
            showPlanStep();
        }

        function goToPayStep() {
            const picked = document.querySelector('input[name="plan_pick"]:checked');
            if (picked) chosenPlan.amount = parseInt(picked.value, 10);

            document.getElementById('payPlanInput').value = chosenPlan.amount;
            document.getElementById('payPlanAmount').textContent =
                'Rs ' + chosenPlan.amount.toLocaleString();
            document.getElementById('payPlanCards').textContent =
                chosenPlan.cards + (chosenPlan.cards === 1 ? ' card' : ' cards');

            // The admin's own name for the plan, when they gave it one.
            const nameEl = document.getElementById('payPlanName');
            nameEl.textContent = chosenPlan.name || '';
            nameEl.hidden = !chosenPlan.name;

            document.getElementById('planStep').hidden = true;
            document.getElementById('payStep').hidden = false;
            document.querySelector('.modal-box.wide').scrollTop = 0;
        }

        function pickAccount(label) {
            document.querySelectorAll('.acct-opt').forEach(el => el.classList.remove('selected'));
            label.classList.add('selected');
            const radio = label.querySelector('input');
            if (radio) radio.checked = true;
        }

        function copyAccount(button, value) {
            navigator.clipboard.writeText(value).then(() => {
                const original = button.textContent;
                button.textContent = 'Copied';
                setTimeout(() => button.textContent = original, 1400);
            });
        }

        function previewShot(input) {
            const img = document.getElementById('shotPreview');
            const file = input.files && input.files[0];
            if (!file) {
                img.style.display = 'none';
                return;
            }
            img.src = URL.createObjectURL(file);
            img.style.display = 'block';
        }

        function openHelpModal() {
            document.getElementById('helpModal').classList.add('open');
        }

        function closeHelpModal() {
            document.getElementById('helpModal').classList.remove('open');
        }

        // A rejected submission comes back as a redirect, which would otherwise
        // drop the client back on the hub with no idea what went wrong — so
        // reopen the modal on the step that failed.
        @if ($errors->any())
            window.addEventListener('DOMContentLoaded', () => {
                const amount = {{ (int) old('plan_amount', $plans[0]['amount'] ?? 0) }};
                const plan = @json(collect($plans)->keyBy('amount'));
                if (plan[amount]) chosenPlan = {
                    amount: amount,
                    cards: plan[amount].cards,
                    name: plan[amount].name || '',
                };
                openPlanModal();
                goToPayStep();
            });
        @endif

        function openLimitModal(cardName) {
            const box = document.getElementById('limitModalCard');
            box.hidden = !cardName;
            if (cardName) document.getElementById('limitModalCardName').textContent = cardName;
            document.getElementById('limitModal').classList.add('open');
        }

        function closeLimitModal() {
            document.getElementById('limitModal').classList.remove('open');
        }

        // Someone who opened /cards/{id}/edit directly is bounced back here —
        // show them the same explanation rather than a blank hub.
        @if (session('card_limit_blocked'))
            window.addEventListener('DOMContentLoaded', () => {
                openLimitModal(@json(session('card_limit_blocked')));
            });
        @endif

        function openRenameModal(action, current) {
            const form = document.getElementById('renameForm');
            form.action = action;
            const input = document.getElementById('renameInput');
            input.value = current;
            document.getElementById('renameModal').classList.add('open');
            input.focus();
            input.select();
        }

        function copyCardLink(button, url) {
            navigator.clipboard.writeText(url).then(() => {
                const original = button.textContent;
                button.textContent = 'Copied';
                setTimeout(() => button.textContent = original, 1400);
            });
        }

        function closeRenameModal() {
            document.getElementById('renameModal').classList.remove('open');
        }

        function openDeleteModal(action, name) {
            document.getElementById('deleteForm').action = action;
            document.getElementById('deleteModalCardName').textContent = name;
            document.getElementById('deleteModal').classList.add('open');
            // Cancel takes the focus, not Delete. The default answer to a
            // dialog that destroys something is no, and Enter should not be
            // able to confirm it by accident.
            document.getElementById('deleteCancel').focus();
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
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
