<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support — Client Dashboard</title>
    {{-- Tab icon — the app tile, same mark on every surface. --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo/clean/appicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/clean/appicon.png') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #fdf6f0;
            --surface: #ffffff;
            --surface2: #fef9f5;
            --border: #f0e6da;
            --text: #2d1f14;
            --text-muted: #9c7c62;
            --accent-boy: #4f8ef7;
            --accent-girl: #f76fa1;
            --accent: #f76fa1;
            --accent-soft: #fff0f6;
            --gold: #e8a820;
            --gold-soft: #fffbf0;
            --sidebar-w: 270px;
            --radius: 16px;
            --shadow: 0 2px 24px rgba(200, 140, 100, 0.10);
        }

        [data-theme="boy"] {
            --accent: var(--accent-boy);
            --accent-soft: rgba(79, 142, 247, 0.1);
        }

        [data-theme="girl"] {
            --accent: var(--accent-girl);
            --accent-soft: rgba(247, 111, 161, 0.1);
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
            display: flex;
            overflow-x: hidden;
        }

        /* ─── CONFETTI bg ─── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(247, 111, 161, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(232, 168, 32, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(79, 142, 247, 0.1) 0%, transparent 50%);
            background-size: 300px 300px, 400px 400px, 200px 200px;
            background-position: 0% 0%, 100% 100%, 50% 50%;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-10px) rotate(1deg);
            }

            66% {
                transform: translateY(5px) rotate(-1deg);
            }
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1.5px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            box-shadow: var(--shadow);
        }

        .sidebar-brand {
            padding: 2rem 1.5rem 1rem;
            border-bottom: 1.5px solid var(--border);
            text-align: center;
        }

        .sidebar-brand .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-brand .logo span {
            font-size: 2rem;
        }

        .sidebar-brand p {
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Navigation */
        .dashboard-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 8px;
            margin: 1rem 0.75rem 0.5rem 0.75rem;
            background: var(--accent-soft);
            border: 1.5px solid var(--accent-soft);
        }

        .dashboard-nav-item:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        .dashboard-nav-item svg {
            color: var(--accent);
            flex-shrink: 0;
        }

        .dashboard-nav-item:hover svg {
            color: white;
        }

        .nav-steps h4 {
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 1.5rem 0 0.75rem 0;
            padding: 0 1.5rem;
        }

        /* ─── MAIN ─── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 2.5rem 2.5rem 4rem;
            position: relative;
            z-index: 1;
        }

        /* Top bar */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5rem;
        }

        .topbar-left h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-style: italic;
            color: var(--text);
            letter-spacing: -0.03em;
        }

        .topbar-left p {
            color: var(--text-muted);
            font-size: 0.87rem;
            margin-top: 0.2rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text);
            transition: all 0.2s;
            box-shadow: var(--shadow);
        }

        .user-dropdown-btn:hover {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-girl), var(--gold));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .user-name {
            font-weight: 500;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dropdown-arrow {
            color: var(--text-muted);
            transition: transform 0.2s;
        }

        .user-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .user-dropdown.open .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text);
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.2s;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            font-family: inherit;
        }

        .dropdown-item:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .dropdown-item svg {
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .dropdown-item:hover svg {
            color: var(--accent);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 0.25rem 0;
        }

        .logout-item {
            color: #ef4444;
        }

        .logout-item:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .logout-item svg {
            color: #ef4444;
        }

        .logout-item:hover svg {
            color: #dc2626;
        }

        /* Profile Card */
        .profile-card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }

        .profile-header {
            padding: 2rem;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface2);
            text-align: center;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--gold));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin: 0 auto 1rem;
            box-shadow: var(--shadow);
        }

        .profile-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-style: italic;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .profile-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .profile-content {
            padding: 2rem;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .info-card {
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
        }

        .info-card h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card h4 svg {
            color: var(--accent);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .info-value {
            font-size: 0.9rem;
            color: var(--text);
            font-weight: 600;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* The hamburger. Visibility is CSS-driven — it is hidden by default
           and only the narrow breakpoint reveals it — so it can never flash
           as an unstyled button before any script runs. */
        #menuToggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            padding: 0;
            background: var(--accent-soft);
            border: 1.5px solid var(--accent);
            border-radius: 10px;
            color: var(--accent);
            font-size: 1.15rem;
            line-height: 1;
            cursor: pointer;
            flex-shrink: 0;
            transition: transform .18s ease, background .18s ease;
        }

        #menuToggle:hover {
            background: var(--accent);
            color: #fff;
        }

        #menuToggle:active {
            transform: scale(.94);
        }

        /* Mobile */
        @media (max-width: 768px) {
            #menuToggle {
                display: flex !important;
            }

            .topbar {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .topbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 260px;
                box-shadow: 4px 0 32px rgba(200, 140, 100, 0.15);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar::after {
                content: '';
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                left: 260px;
                z-index: -1;
            }

            .sidebar.open::after {
                opacity: 1;
                pointer-events: auto;
            }

            .main {
                margin-left: 0;
            }

            .profile-content {
                padding: 1.5rem;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ─── Contact page ─── */
        .contact-hero {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.6rem;
        }

        .contact-hero .badge {
            width: 62px;
            height: 62px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: var(--accent-soft);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-hero h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: .45rem;
        }

        .contact-hero p {
            color: var(--text-muted);
            font-size: .9rem;
            max-width: 480px;
            margin: 0 auto;
            line-height: 1.65;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.1rem;
            margin-bottom: 1.6rem;
        }

        .contact-card {
            display: flex;
            align-items: center;
            gap: .95rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem 1.2rem;
            text-decoration: none;
            color: var(--text);
            box-shadow: var(--shadow);
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .contact-card:hover {
            transform: translateY(-3px);
            border-color: var(--brand, var(--accent));
            box-shadow: 0 8px 28px rgba(200, 140, 100, .18);
        }

        .contact-card__ico {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            background: var(--brand, var(--accent));
        }

        .contact-card__body {
            min-width: 0;
        }

        .contact-card__body strong {
            display: block;
            font-size: .95rem;
            margin-bottom: .15rem;
        }

        .contact-card__value {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .84rem;
            font-weight: 600;
            color: var(--text);
            word-break: break-all;
            font-variant-numeric: tabular-nums;
        }

        .contact-card__note {
            display: block;
            font-size: .76rem;
            color: var(--text-muted);
            margin-top: .1rem;
        }

        .contact-card__go {
            margin-left: auto;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .contact-empty {
            background: var(--surface);
            border: 1.5px dashed var(--border);
            border-radius: 14px;
            padding: 2.2rem 1.4rem;
            text-align: center;
            color: var(--text-muted);
            font-size: .9rem;
        }

        .panel-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            margin-bottom: 1.6rem;
        }

        .panel-card h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            margin-bottom: .3rem;
        }

        .panel-card > p.hint {
            color: var(--text-muted);
            font-size: .85rem;
            margin-bottom: 1.1rem;
        }









        .mini-copy {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 7px;
            font-family: inherit;
            font-size: .7rem;
            font-weight: 600;
            padding: .2rem .5rem;
            cursor: pointer;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .mini-copy:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .faq-item {
            border-top: 1.5px solid var(--border);
            padding: .95rem 0;
        }

        .faq-item:first-of-type {
            border-top: none;
            padding-top: 0;
        }

        .faq-item strong {
            display: block;
            font-size: .9rem;
            margin-bottom: .3rem;
        }

        .faq-item p {
            font-size: .84rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }
    
        /* The wordmark replaces the emoji lockup — height-locked so the
           sidebar keeps its spacing whatever the PNG measures. */
        .brand-logo {
            height: 40px;
            width: auto;
            display: block;
        }
    </style>
</head>

<body data-theme="{{ session('theme', 'girl') }}">
    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo"><img src="{{ asset('images/logo/clean/primarylogo.png') }}" alt="Giftloft" class="brand-logo"></div>
            <p>Creator Dashboard</p>
        </div>

        <nav class="nav-steps">
            <div class="dashboard-nav-item" onclick="window.location.href='{{ route('client.cards') }}'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Back to Dashboard
            </div>
        </nav>
    </aside>

    <!-- ─── MAIN ─── -->
    <main class="main">
        <div class="topbar">
            <div class="topbar-left">
                <h2>Contact Support</h2>
                <p>We are one message away — pick whichever suits you</p>
            </div>
            <div class="topbar-right">
                <button id="menuToggle" onclick="toggleSidebar()">☰</button>

                <div class="user-dropdown">
                    <button class="user-dropdown-btn" onclick="toggleUserDropdown()">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdown">
                        <a href="{{ route('client.profile') }}" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            My Profile
                        </a>
                        <a href="{{ route('client.settings') }}" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            Settings
                        </a>
                        <a href="{{ route('client.contact') }}" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"></path>
                            </svg>
                            Contact Us
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('client.logout') }}" style="margin:0">
                            @csrf
                            <button class="dropdown-item logout-item" type="submit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16,17 21,12 16,7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-hero">
            <div class="badge">
                @include('partials.channel-icon', ['channel' => 'phone', 'size' => 28])
            </div>
            <h3>How can we help?</h3>
            <p>Payment stuck, plan not activated, or a card not behaving? Message us on any channel below and a
                real person will get back to you.</p>
        </div>

        {{-- ── The channels the Super Admin published ── --}}
        @if ($supportContacts->isEmpty())
            <div class="contact-empty">
                No support channels have been published yet. Please check back shortly.
            </div>
        @else
            <div class="contact-grid">
                @foreach ($supportContacts as $contact)
                    <a class="contact-card" href="{{ $contact->url() }}" target="_blank" rel="noopener"
                        style="--brand: {{ $contact->brandColour() }}">
                        <span class="contact-card__ico">
                            @include('partials.channel-icon', ['channel' => $contact->channel, 'size' => 22])
                        </span>
                        <span class="contact-card__body">
                            <strong>{{ $contact->label }}</strong>
                            {{-- The number/handle itself, always — a client chasing a
                                 payment needs to be able to read and copy it. --}}
                            <span class="contact-card__value">
                            {{ $contact->value }}
                            <button type="button" class="mini-copy"
                                onclick="event.preventDefault();event.stopPropagation();copyValue(this,@json($contact->value))">Copy</button>
                        </span>
                            @if ($contact->note)
                                <span class="contact-card__note">{{ $contact->note }}</span>
                            @endif
                        </span>
                        <span class="contact-card__go">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 17 17 7M9 7h8v8"></path>
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="panel-card">
            <h4>Before you message us</h4>
            <p class="hint">These cover most of what people write in about.</p>

            <div class="faq-item">
                <strong>I paid but my plan is still locked.</strong>
                <p>Approvals are manual. Once you submit your payment screenshot, we verify the transfer and switch
                    your plan on — message us here if it has been a while and nothing has changed.</p>
            </div>
            <div class="faq-item">
                <strong>My transfer failed but the money was deducted.</strong>
                <p>Send us the transaction ID and a screenshot on WhatsApp. We will confirm against our account and
                    either activate your plan or help you get it reversed.</p>
            </div>
            <div class="faq-item">
                <strong>I sent to the wrong account.</strong>
                <p>Message us straight away with the screenshot. The accounts listed above are the only ones we use.</p>
            </div>
            <div class="faq-item">
                <strong>My card link or QR is not opening.</strong>
                <p>Send us the link. Cards can be switched off from your dashboard, so it may just need turning back on.</p>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : 'auto';
        }

        // Tapping the page behind an open sidebar should close it.
        document.addEventListener('click', function (e) {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.getElementById('menuToggle');
            if (sidebar && sidebar.classList.contains('open') &&
                !sidebar.contains(e.target) &&
                !menuToggle.contains(e.target) &&
                window.innerWidth <= 768) {
                sidebar.classList.remove('open');
                document.body.style.overflow = 'auto';
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                document.querySelector('.sidebar').classList.remove('open');
                document.body.style.overflow = 'auto';
            }
        });

        function toggleUserDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }

        document.addEventListener('click', (e) => {
            const dd = document.querySelector('.user-dropdown');
            if (dd && !dd.contains(e.target)) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        });

        function copyValue(button, value) {
            navigator.clipboard.writeText(value).then(() => {
                const original = button.textContent;
                button.textContent = 'Copied';
                setTimeout(() => button.textContent = original, 1400);
            });
        }
    </script>
</body>

</html>
