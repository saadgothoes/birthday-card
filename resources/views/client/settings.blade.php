<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — Client Dashboard</title>
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
                radial-gradient(circle at 12% 18%, rgba(247, 111, 161, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 88% 70%, rgba(79, 142, 247, 0.07) 0%, transparent 40%),
                radial-gradient(circle at 55% 95%, rgba(232, 168, 32, 0.07) 0%, transparent 35%);
            pointer-events: none;
            z-index: 0;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1.5px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            box-shadow: 4px 0 32px rgba(200, 140, 100, 0.07);
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 2rem 1.6rem 1.2rem;
            border-bottom: 1.5px solid var(--border);
        }

        .sidebar-brand .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            font-style: italic;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.02em;
        }

        .sidebar-brand .logo span {
            font-style: normal;
            font-size: 1.5rem;
        }

        .sidebar-brand p {
            color: var(--text-muted);
            font-size: 0.72rem;
            margin-top: 0.3rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Sidebar bottom user */
        .sidebar-user {
            padding: 1rem 1.2rem;
            border-top: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-girl), var(--gold));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-info strong {
            display: block;
            font-size: 0.83rem;
            color: var(--text);
        }

        .user-info span {
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        .user-menu {
            position: relative;
        }

        .user-menu-btn {
            background: none;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 0.35rem 0.55rem;
            cursor: pointer;
            font-size: 0.8rem;
            color: var(--text-muted);
            transition: all 0.2s;
        }

        .user-menu-btn:hover {
            background: var(--surface2);
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

        /* Settings Card */
        .settings-card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }

        .settings-header {
            padding: 1.5rem 2rem;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface2);
        }

        .settings-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-style: italic;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .settings-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .settings-form {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.95rem;
            background: var(--surface2);
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: #e83e7a;
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Success message */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
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

            .settings-form {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
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
                <h2>Settings</h2>
                <p>Manage your account preferences</p>
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

        <div class="settings-card">
            <div class="settings-header">
                <h3>Change Password</h3>
                <p>Update your password for better security</p>
            </div>

            <div class="settings-form">
                @if(session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    ❌ Please fix the errors below
                </div>
                @endif

                <form method="POST" action="{{ route('client.settings.password') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input @error('current_password') error @enderror" required>
                        @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" required>
                        @error('password')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        🔒 Update Password
                    </button>
                </form>
            </div>
        </div>
        </div>

        <script>
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

            // Set localStorage when password is changed
            @if(session('success'))
            localStorage.setItem('can_reset_password', 'true');
            @endif
        </script>
</body>

</html>