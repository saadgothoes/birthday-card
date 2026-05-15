<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — Client Dashboard</title>
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
    </style>
</head>

<body data-theme="{{ session('theme', 'girl') }}">
    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo"><span>🎂</span> BirthdayCard</div>
            <p>Creator Dashboard</p>
        </div>

        <nav class="nav-steps">
            <div class="dashboard-nav-item" onclick="window.location.href='{{ route('client.dashboard') }}'">
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
                <h2>My Profile</h2>
                <p>View and manage your account information</p>
            </div>
            <div class="topbar-right">
                <button id="menuToggle" onclick="toggleSidebar()">☰</button>

                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <button class="user-dropdown-btn" onclick="toggleUserDropdown()">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdown">
                        <a href="{{ route('client.profile') }}" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            My Profile
                        </a>
                        <a href="{{ route('client.settings') }}" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('client.logout') }}" style="margin:0">
                            @csrf
                            <button class="dropdown-item logout-item" type="submit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <h3>{{ Auth::user()->name }}</h3>
                <p>{{ Auth::user()->email }}</p>
            </div>

            <div class="profile-content">
                <div class="profile-grid">
                    <div class="info-card">
                        <h4>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Account Information
                        </h4>
                        <div class="info-row">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email Address</span>
                            <span class="info-value">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Account Status</span>
                            <span class="status-badge status-{{ Auth::user()->status }}">{{ Auth::user()->status }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Role</span>
                            <span class="info-value">{{ Auth::user()->role }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Member Since</span>
                            <span class="info-value">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="info-card">
                        <h4>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            Account Settings
                        </h4>
                        <div class="info-row">
                            <span class="info-label">Password Changed</span>
                            <span class="info-value">{{ Auth::user()->password_changed ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value">{{ Auth::user()->updated_at->format('M d, Y') }}</span>
                        </div>
                        @if(Auth::user()->client_fields)
                        <div class="info-row">
                            <span class="info-label">Phone</span>
                            <span class="info-value">{{ Auth::user()->client_fields['phone'] ?? 'Not set' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Company</span>
                            <span class="info-value">{{ Auth::user()->client_fields['company'] ?? 'Not set' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Set theme based on user preference
        function setTheme(theme) {
            const accent = theme === 'boy' ? '#4f8ef7' : '#f76fa1';
            const accentSoft = theme === 'boy' ? '#e8f1ff' : '#fff0f6';
            document.documentElement.style.setProperty('--accent', accent);
            document.documentElement.style.setProperty('--accent-soft', accentSoft);
            document.body.setAttribute('data-theme', theme);
            sessionStorage.setItem('theme', theme);
        }

        // Load saved theme
        const savedTheme = sessionStorage.getItem('theme') || 'girl';
        setTheme(savedTheme);

        // User dropdown toggle
        function toggleUserDropdown() {
            const dropdown = document.querySelector('.user-dropdown');
            dropdown.classList.toggle('open');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.user-dropdown');
            const dropdownBtn = document.querySelector('.user-dropdown-btn');
            if (dropdown && dropdown.classList.contains('open') &&
                !dropdown.contains(e.target) &&
                !dropdownBtn.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        // Mobile menu toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
            if (sidebar.classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
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

        // Show menu toggle on mobile
        function updateMenuButton() {
            const menuToggle = document.getElementById('menuToggle');
            if (window.innerWidth <= 768) {
                menuToggle.style.display = 'flex';
            } else {
                menuToggle.style.display = 'none';
                document.querySelector('.sidebar').classList.remove('open');
                document.body.style.overflow = 'auto';
            }
        }

        window.addEventListener('resize', updateMenuButton);
        updateMenuButton();
    </script>
</body>

</html>