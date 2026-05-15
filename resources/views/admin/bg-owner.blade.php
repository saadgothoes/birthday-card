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
            <a href="{{ route('admin.clients.create') }}" class="nav-item">
                <div class="nav-icon">➕</div> Add Client
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

            <!-- Main Content (Hidden until PIN verified) -->
            <div id="mainContent" class="main-content" style="display: none;">
                <div class="bg-owner-header">
                    <h1>🎯 BG Owner Dashboard</h1>
                    <p>Access to all client uploaded data and sensitive information</p>
                </div>

                <div class="data-sections">
                    <!-- Images Section -->
                    <div class="data-section">
                        <h3>📸 Client Images</h3>
                        <div class="data-grid" id="imagesGrid">
                            <!-- Images will be loaded here -->
                            <div class="loading">Loading images...</div>
                        </div>
                    </div>

                    <!-- Videos Section -->
                    <div class="data-section">
                        <h3>🎥 Client Videos</h3>
                        <div class="data-grid" id="videosGrid">
                            <!-- Videos will be loaded here -->
                            <div class="loading">Loading videos...</div>
                        </div>
                    </div>

                    <!-- Other Data Section -->
                    <div class="data-section">
                        <h3>📄 Client Data</h3>
                        <div class="data-list" id="dataList">
                            <!-- Data will be loaded here -->
                            <div class="loading">Loading data...</div>
                        </div>
                    </div>
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

        function loadClientData() {
            // This is a placeholder - you'll need to implement actual data loading
            // For now, just show some sample content
            document.getElementById('imagesGrid').innerHTML = '<div class="loading">No images found</div>';
            document.getElementById('videosGrid').innerHTML = '<div class="loading">No videos found</div>';
            document.getElementById('dataList').innerHTML = '<div class="loading">No data found</div>';
        }
    </script>
</body>

</html>