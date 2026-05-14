<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card Creator — Client Dashboard</title>
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

    /* Theme toggle in sidebar */
    .theme-switcher {
        padding: 1rem 1.2rem;
        border-bottom: 1.5px solid var(--border);
    }

    .theme-switcher label {
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.6rem;
    }

    .theme-btns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }

    .theme-btn {
        padding: 0.55rem 0;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        background: var(--surface2);
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.2s;
        color: var(--text-muted);
    }

    .theme-btn.active-boy {
        background: #e8f1ff;
        border-color: var(--accent-boy);
        color: var(--accent-boy);
    }

    .theme-btn.active-girl {
        background: var(--accent-soft);
        border-color: var(--accent-girl);
        color: var(--accent-girl);
    }

    /* Nav Steps */
    .nav-steps {
        padding: 1rem 0.8rem;
        flex: 1;
        overflow-y: auto;
    }

    .nav-steps h4 {
        font-size: 0.68rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        padding: 0 0.8rem;
        margin-bottom: 0.5rem;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 0.85rem;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 0.2rem;
        position: relative;
    }

    .step-item:hover {
        background: var(--surface2);
    }

    .step-item.active {
        background: var(--accent-soft);
    }

    .step-item.active .step-num {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .step-item.active .step-label {
        color: var(--accent);
        font-weight: 600;
    }

    .step-item.done .step-num {
        background: #d1fae5;
        color: #059669;
        border-color: #a7f3d0;
        font-size: 0.85rem;
    }

    .step-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-muted);
        background: var(--surface2);
        flex-shrink: 0;
        transition: all 0.2s;
    }

    .step-label {
        font-size: 0.87rem;
        color: var(--text);
        line-height: 1.3;
        transition: color 0.2s;
    }

    .step-sub {
        font-size: 0.72rem;
        color: var(--text-muted);
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

    .logout-btn {
        background: none;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 0.35rem 0.55rem;
        cursor: pointer;
        font-size: 0.8rem;
        color: var(--text-muted);
        transition: all 0.2s;
    }

    .logout-btn:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fca5a5;
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

    .progress-pill {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: 100px;
        padding: 0.5rem 1.2rem;
        font-size: 0.82rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow);
    }

    .progress-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1)
        }

        50% {
            opacity: 0.6;
            transform: scale(1.3)
        }
    }

    /* ─── STEP PANELS ─── */
    .step-panel {
        display: none;
        animation: fadeIn 0.35s ease;
    }

    .step-panel.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    /* Card base */
    .card {
        background: var(--surface);
        border-radius: var(--radius);
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        background: var(--accent-soft);
    }

    .card-title h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text);
    }

    .card-title p {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 0.1rem;
    }

    .card-body {
        padding: 2rem;
    }

    /* Form elements */
    .form-group {
        margin-bottom: 1.4rem;
    }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        background: var(--surface2);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        color: var(--text);
        transition: all 0.2s;
        outline: none;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: var(--accent);
        background: white;
        box-shadow: 0 0 0 3px rgba(247, 111, 161, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    /* Step 1 — Theme selection */
    .theme-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-top: 0.5rem;
    }

    .theme-choice {
        border: 2px solid var(--border);
        border-radius: var(--radius);
        padding: 2rem 1.5rem;
        cursor: pointer;
        transition: all 0.25s;
        text-align: center;
        position: relative;
        overflow: hidden;
        background: var(--surface2);
    }

    .theme-choice::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.25s;
    }

    .theme-choice.boy-theme::before {
        background: linear-gradient(135deg, #e8f1ff, #dbeafe);
    }

    .theme-choice.girl-theme::before {
        background: linear-gradient(135deg, #fff0f6, #fce7f3);
    }

    .theme-choice:hover::before,
    .theme-choice.selected::before {
        opacity: 1;
    }

    .theme-choice:hover,
    .theme-choice.selected {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .theme-choice.selected.boy-theme {
        border-color: var(--accent-boy);
    }

    .theme-choice.selected.girl-theme {
        border-color: var(--accent-girl);
    }

    .theme-choice .check {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        opacity: 0;
        transition: all 0.2s;
    }

    .theme-choice.selected .check {
        opacity: 1;
    }

    .theme-choice.boy-theme .check {
        background: var(--accent-boy);
        color: white;
    }

    .theme-choice.girl-theme .check {
        background: var(--accent-girl);
        color: white;
    }

    .theme-emoji {
        font-size: 3rem;
        position: relative;
        z-index: 1;
        display: block;
        margin-bottom: 0.8rem;
    }

    .theme-name {
        font-size: 1.1rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .theme-choice.boy-theme .theme-name {
        color: var(--accent-boy);
    }

    .theme-choice.girl-theme .theme-name {
        color: var(--accent-girl);
    }

    .theme-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
        position: relative;
        z-index: 1;
    }

    /* Step 2 — Lock Code */
    .lock-display {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        border-radius: 14px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .lock-display::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 0%, rgba(247, 111, 161, 0.2), transparent 60%);
    }

    .lock-icon {
        font-size: 2.5rem;
        position: relative;
        z-index: 1;
    }

    .lock-code {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        letter-spacing: 0.4em;
        color: white;
        position: relative;
        z-index: 1;
        margin: 0.5rem 0;
        text-shadow: 0 0 20px rgba(247, 111, 161, 0.5);
    }

    .lock-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.5);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        position: relative;
        z-index: 1;
    }

    .dob-hint {
        background: var(--gold-soft);
        border: 1.5px solid #fcd77a;
        border-radius: 10px;
        padding: 0.9rem 1.2rem;
        font-size: 0.83rem;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    /* Step 3 — Welcome screen preview */
    .welcome-preview {
        border-radius: 14px;
        overflow: hidden;
        border: 1.5px solid var(--border);
        margin-bottom: 1.5rem;
    }

    .preview-header {
        background: linear-gradient(135deg, var(--accent-girl), #ff9dcd);
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
    }

    .preview-avatar-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border: 3px solid rgba(255, 255, 255, 0.5);
    }

    .preview-header h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: white;
        font-style: italic;
    }

    .preview-header p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.85rem;
        margin-top: 0.3rem;
    }

    .preview-body {
        background: white;
        padding: 1.5rem;
        text-align: center;
    }

    .preview-message {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.7;
        font-style: italic;
    }

    /* Step 4 — Gift sections */
    .gift-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .gift-tab {
        padding: 0.55rem 1.2rem;
        border-radius: 100px;
        border: 1.5px solid var(--border);
        background: var(--surface2);
        cursor: pointer;
        font-size: 0.83rem;
        font-weight: 500;
        color: var(--text-muted);
        transition: all 0.2s;
    }

    .gift-tab.active {
        background: var(--accent-soft);
        border-color: var(--accent);
        color: var(--accent);
        font-weight: 600;
    }

    .gift-panel {
        display: none;
    }

    .gift-panel.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .image-upload-zone {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--surface2);
    }

    .image-upload-zone:hover {
        border-color: var(--accent);
        background: var(--accent-soft);
    }

    .upload-icon {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .upload-text {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .upload-text strong {
        color: var(--accent);
    }

    .image-slots {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.8rem;
        margin-top: 1rem;
    }

    .image-slot {
        aspect-ratio: 1;
        border: 2px dashed var(--border);
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1.5rem;
        color: var(--text-muted);
        background: var(--surface2);
    }

    .image-slot:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: var(--accent-soft);
    }

    .image-slot span {
        font-size: 0.7rem;
        margin-top: 0.3rem;
        font-family: 'DM Sans', sans-serif;
    }

    /* Book preview */
    .book-preview {
        background: #fdf4e7;
        border-radius: 14px;
        border: 1.5px solid #e8d5b0;
        overflow: hidden;
        margin-top: 1rem;
    }

    .book-header {
        background: linear-gradient(135deg, #8b5a2b, #6b3f1f);
        padding: 1.5rem;
        text-align: center;
    }

    .book-title {
        font-family: 'Playfair Display', serif;
        color: #f5d89e;
        font-size: 1.1rem;
        font-style: italic;
    }

    .book-content {
        padding: 1.5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .book-text-side {
        font-size: 0.82rem;
        line-height: 1.8;
        color: #5c3d20;
        font-style: italic;
    }

    .book-img-side {
        border-radius: 8px;
        border: 3px solid #e8d5b0;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5e6cc;
        font-size: 2rem;
        color: #c4a06a;
    }

    /* Step 5 — Generate */
    .generate-section {
        text-align: center;
        padding: 2rem 0;
    }

    .generate-btn {
        background: linear-gradient(135deg, var(--accent-girl), #ff9dcd);
        color: white;
        border: none;
        border-radius: 100px;
        padding: 1rem 3rem;
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-style: italic;
        cursor: pointer;
        box-shadow: 0 8px 32px rgba(247, 111, 161, 0.3);
        transition: all 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .generate-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(247, 111, 161, 0.4);
    }

    .url-box {
        background: var(--surface2);
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .url-text {
        font-family: 'DM Sans', monospace;
        font-size: 0.85rem;
        color: var(--accent);
        flex: 1;
        word-break: break-all;
    }

    .copy-btn {
        background: var(--accent-soft);
        border: 1.5px solid var(--accent);
        color: var(--accent);
        border-radius: 8px;
        padding: 0.45rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .copy-btn:hover {
        background: var(--accent);
        color: white;
    }

    .qr-placeholder {
        width: 140px;
        height: 140px;
        border-radius: 14px;
        background: var(--surface2);
        border: 2px dashed var(--border);
        margin: 1.5rem auto 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--text-muted);
    }

    .qr-placeholder span {
        font-size: 2.5rem;
    }

    .qr-placeholder p {
        font-size: 0.75rem;
    }

    /* Nav buttons */
    .step-nav {
        display: flex;
        gap: 0.75rem;
        margin-top: 2rem;
        justify-content: flex-end;
    }

    .btn-prev {
        background: var(--surface);
        border: 1.5px solid var(--border);
        color: var(--text-muted);
        border-radius: 10px;
        padding: 0.7rem 1.5rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-prev:hover {
        background: var(--surface2);
    }

    .btn-next {
        background: var(--accent);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 0.7rem 1.8rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 16px rgba(247, 111, 161, 0.3);
    }

    .btn-next:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(247, 111, 161, 0.4);
    }

    /* Gift cards summary */
    .gift-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .gift-summary-card {
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 1.5rem;
        text-align: center;
        background: var(--surface2);
    }

    .gift-summary-card .gi {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .gift-summary-card h4 {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text);
    }

    .gift-summary-card p {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }

    /* Mobile menu toggle */
    #menuToggle {
        display: none;
        background: var(--accent-soft);
        border: 1.5px solid var(--accent);
        border-radius: 8px;
        padding: 0.5rem 0.8rem;
        cursor: pointer;
        font-size: 1.2rem;
        color: var(--accent);
        margin-left: auto;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    #menuToggle:active {
        transform: scale(0.95);
    }

    @media (max-width: 768px) {
        #menuToggle {
            display: flex !important;
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .main {
            padding: 2rem 1.8rem;
        }

        .topbar {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .progress-pill {
            align-self: flex-start;
        }
    }

    @media (max-width: 768px) {
        :root {
            --sidebar-w: 260px;
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
            padding: 1.5rem 1rem;
        }

        .topbar {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .topbar-left h2 {
            font-size: 1.4rem;
        }

        .topbar-left p {
            font-size: 0.8rem;
        }

        .progress-pill {
            align-self: flex-start;
            font-size: 0.75rem;
        }

        .card {
            border-radius: 12px;
        }

        .card-header {
            flex-wrap: wrap;
            padding: 1.2rem 1.5rem;
            gap: 0.75rem;
        }

        .card-icon {
            width: 36px;
            height: 36px;
            font-size: 1.1rem;
        }

        .card-title h3 {
            font-size: 1rem;
        }

        .card-title p {
            font-size: 0.75rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .theme-cards {
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .theme-choice {
            padding: 1.5rem 1rem;
        }

        .theme-emoji {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .theme-name {
            font-size: 1rem;
        }

        .theme-desc {
            font-size: 0.75rem;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-size: 0.75rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 0.65rem 0.85rem;
            font-size: 0.85rem;
        }

        .form-group textarea {
            min-height: 80px;
        }

        .image-upload-zone {
            padding: 1.5rem;
            border-radius: 10px;
        }

        .upload-icon {
            font-size: 1.5rem;
        }

        .upload-text {
            font-size: 0.8rem;
        }

        .image-slots {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
            margin-top: 0.8rem;
        }

        .image-slot {
            border-radius: 8px;
            font-size: 1.2rem;
        }

        .image-slot span {
            font-size: 0.65rem;
        }

        .gift-tabs {
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .gift-tab {
            padding: 0.45rem 0.9rem;
            font-size: 0.75rem;
            border-radius: 100px;
        }

        .lock-display {
            padding: 1.5rem;
        }

        .lock-code {
            font-size: 2.2rem;
            letter-spacing: 0.3em;
        }

        .lock-icon {
            font-size: 2rem;
        }

        .dob-hint {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
        }

        .welcome-preview {
            border-radius: 12px;
        }

        .preview-header {
            padding: 1.8rem 1.5rem;
        }

        .preview-avatar-wrap {
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
            margin-bottom: 0.8rem;
        }

        .preview-header h3 {
            font-size: 1.2rem;
        }

        .preview-header p {
            font-size: 0.8rem;
        }

        .preview-body {
            padding: 1.2rem;
        }

        .preview-message {
            font-size: 0.85rem;
        }

        .book-preview {
            border-radius: 12px;
            margin-top: 1rem;
        }

        .book-header {
            padding: 1rem;
        }

        .book-title {
            font-size: 0.95rem;
        }

        .book-content {
            grid-template-columns: 1fr;
            gap: 0.8rem;
            padding: 1rem;
        }

        .book-text-side {
            font-size: 0.78rem;
            line-height: 1.6;
        }

        .book-img-side {
            min-height: 100px;
            font-size: 1.8rem;
        }

        .gift-summary-grid {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        .gift-summary-card {
            padding: 1.2rem;
        }

        .gift-summary-card .gi {
            font-size: 1.8rem;
        }

        .gift-summary-card h4 {
            font-size: 0.8rem;
        }

        .gift-summary-card p {
            font-size: 0.7rem;
        }

        .generate-btn {
            padding: 0.85rem 2rem;
            font-size: 1rem;
            gap: 0.5rem;
        }

        .url-box {
            flex-direction: column;
            gap: 0.8rem;
        }

        .url-text {
            font-size: 0.8rem;
        }

        .copy-btn {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            width: 100%;
        }

        .qr-placeholder {
            width: 120px;
            height: 120px;
            margin: 1rem auto;
        }

        .qr-placeholder span {
            font-size: 2rem;
        }

        .qr-placeholder p {
            font-size: 0.7rem;
        }

        .step-nav {
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .btn-prev,
        .btn-next {
            padding: 0.6rem 1.2rem;
            font-size: 0.85rem;
            flex: 1;
            min-width: 120px;
        }

        .step-item {
            padding: 0.65rem 0.7rem;
            gap: 0.6rem;
        }

        .step-num {
            width: 24px;
            height: 24px;
            font-size: 0.7rem;
        }

        .step-label {
            font-size: 0.8rem;
        }

        .step-sub {
            font-size: 0.65rem;
        }

        .theme-btn {
            padding: 0.5rem;
            font-size: 0.75rem;
        }

        .sidebar-brand {
            padding: 1.5rem 1.2rem 0.9rem;
        }

        .sidebar-brand .logo {
            font-size: 1.1rem;
        }

        .sidebar-brand .logo span {
            font-size: 1.2rem;
        }

        .sidebar-brand p {
            font-size: 0.65rem;
        }

        .theme-switcher {
            padding: 0.8rem 1rem;
        }

        .theme-switcher label {
            font-size: 0.65rem;
        }

        .nav-steps h4 {
            font-size: 0.65rem;
        }

        .sidebar-user {
            padding: 0.8rem 1rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }

        .user-info strong {
            font-size: 0.75rem;
        }

        .user-info span {
            font-size: 0.65rem;
        }

        .logout-btn {
            padding: 0.3rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        :root {
            --sidebar-w: 240px;
        }

        .main {
            padding: 1rem 0.75rem;
        }

        .topbar-left h2 {
            font-size: 1.2rem;
        }

        .topbar-left p {
            font-size: 0.75rem;
        }

        .progress-pill {
            font-size: 0.7rem;
            padding: 0.4rem 0.9rem;
        }

        .card-header {
            padding: 1rem 1.2rem;
        }

        .card-body {
            padding: 1.2rem;
        }

        .card-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }

        .card-title h3 {
            font-size: 0.95rem;
        }

        .card-title p {
            font-size: 0.7rem;
        }

        .theme-cards {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        .theme-emoji {
            font-size: 2rem;
        }

        .theme-name {
            font-size: 0.95rem;
        }

        .theme-desc {
            font-size: 0.7rem;
        }

        .form-group label {
            font-size: 0.7rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 0.6rem 0.75rem;
            font-size: 0.8rem;
        }

        .lock-code {
            font-size: 1.8rem;
            letter-spacing: 0.25em;
        }

        .preview-header {
            padding: 1.5rem 1rem;
        }

        .preview-avatar-wrap {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .preview-header h3 {
            font-size: 1.1rem;
        }

        .preview-message {
            font-size: 0.8rem;
        }

        .gift-summary-card {
            padding: 1rem;
        }

        .gift-summary-card .gi {
            font-size: 1.5rem;
        }

        .generate-btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
        }

        .step-nav {
            flex-direction: column;
        }

        .btn-prev,
        .btn-next {
            width: 100%;
        }

        .image-slots {
            grid-template-columns: repeat(2, 1fr);
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

        <div class="theme-switcher">
            <label>Card Theme</label>
            <div class="theme-btns">
                <button class="theme-btn" id="sidebarBoy" onclick="setGlobalTheme('boy')">💙 Boy</button>
                <button class="theme-btn active-girl" id="sidebarGirl" onclick="setGlobalTheme('girl')">💗 Girl</button>
            </div>
        </div>

        <nav class="nav-steps">
            <h4>Setup Steps</h4>
            <div class="step-item active" onclick="goToStep(1)">
                <div class="step-num" id="sn1">1</div>
                <div>
                    <div class="step-label">Choose Theme</div>
                    <div class="step-sub">Boy or Girl style</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(2)">
                <div class="step-num" id="sn2">2</div>
                <div>
                    <div class="step-label">Set Lock Code</div>
                    <div class="step-sub">DOB-based secret code</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(3)">
                <div class="step-num" id="sn3">3</div>
                <div>
                    <div class="step-label">Welcome Screen</div>
                    <div class="step-sub">Name, image & message</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(4)">
                <div class="step-num" id="sn4">4</div>
                <div>
                    <div class="step-label">Gift Section</div>
                    <div class="step-sub">3 gifts to configure</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(5)">
                <div class="step-num" id="sn5">5</div>
                <div>
                    <div class="step-label">Generate & Share</div>
                    <div class="step-sub">URL & QR code</div>
                </div>
            </div>
        </nav>

        <div class="sidebar-user">
            <div class="user-avatar">A</div>
            <div class="user-info">
                <strong>{{ Auth::user()->name }}</strong>
                <span>{{ Auth::user()->email }}</span>
            </div>
            <form method="POST" action="{{ route('client.logout') }}" style="margin:0">
                @csrf
                <button class="logout-btn" type="submit">↩</button>
            </form>
        </div>
    </aside>

    <!-- ─── MAIN ─── -->
    <main class="main">
        <div class="topbar">
            <div class="topbar-left">
                <h2>Client Dashboard</h2>
                <p>Create & share a beautiful birthday card experience</p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center; width: 100%; justify-content: flex-end;">
                <div class="progress-pill">
                    <div class="dot"></div>
                    <span id="progressText">Step 1 of 5</span>
                </div>
                <button id="menuToggle" onclick="toggleSidebar()">☰</button>
            </div>
        </div>

        <!-- ── STEP 1: Theme ── -->
        <div class="step-panel active" id="panel1">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🎨</div>
                    <div class="card-title">
                        <h3>Select Card Theme</h3>
                        <p>Choose a style that matches your birthday person</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="theme-cards">
                        <div class="theme-choice boy-theme" id="boyCard" onclick="selectTheme('boy')">
                            <div class="check">✓</div>
                            <span class="theme-emoji">💙</span>
                            <div class="theme-name">Boy Theme</div>
                            <div class="theme-desc">Cool blues, stars & adventure vibes</div>
                        </div>
                        <div class="theme-choice girl-theme selected" id="girlCard" onclick="selectTheme('girl')">
                            <div class="check">✓</div>
                            <span class="theme-emoji">🌸</span>
                            <div class="theme-name">Girl Theme</div>
                            <div class="theme-desc">Soft pinks, florals & dreamy magic</div>
                        </div>
                    </div>
                    <div class="step-nav">
                        <button class="btn-next" onclick="nextStep()">Continue →</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── STEP 2: Lock Code ── -->
        <div class="step-panel" id="panel2">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🔐</div>
                    <div class="card-title">
                        <h3>Set Lock Code</h3>
                        <p>Only the birthday person can unlock the card using their DOB</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="lock-display">
                        <div class="lock-icon">🔒</div>
                        <div class="lock-code" id="lockCodeDisplay">••••••••</div>
                        <div class="lock-label">Generated from Date of Birth</div>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" id="dobInput" onchange="generateLockCode(this.value)" />
                    </div>

                    <div class="dob-hint">
                        💡 The lock code will be automatically generated from the birthday person's date of birth
                        (DDMMYYYY format). They'll need to enter it to open the card.
                    </div>

                    <div class="form-group" style="margin-top:1.2rem;">
                        <label>Custom Hint Message (optional)</label>
                        <input type="text" placeholder="e.g. Enter your special date to unlock 🎂" />
                    </div>

                    <div class="step-nav">
                        <button class="btn-prev" onclick="prevStep()">← Back</button>
                        <button class="btn-next" onclick="nextStep()">Continue →</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── STEP 3: Welcome Screen ── -->
        <div class="step-panel" id="panel3">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🌟</div>
                    <div class="card-title">
                        <h3>Customize Welcome Screen</h3>
                        <p>This is the first thing they see after unlocking</p>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
                        <div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Recipient's Name</label>
                                    <input type="text" placeholder="e.g. Aisha" id="recipientName"
                                        oninput="updatePreview()" />
                                </div>
                                <div class="form-group">
                                    <label>Your Name</label>
                                    <input type="text" placeholder="e.g. Bilal" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Welcome Message</label>
                                <textarea placeholder="Write a heartfelt welcome message…" id="welcomeMsg"
                                    oninput="updatePreview()">You are someone so special, and today is all about you. Open each gift with love. 🎀</textarea>
                            </div>
                            <div class="form-group">
                                <label>Profile Image</label>
                                <div class="image-upload-zone">
                                    <span class="upload-icon">📸</span>
                                    <div class="upload-text">Drop image here or <strong>click to browse</strong></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div
                                style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:0.8rem; font-weight:600;">
                                Live Preview</div>
                            <div class="welcome-preview">
                                <div class="preview-header">
                                    <div class="preview-avatar-wrap">🌸</div>
                                    <h3 id="previewName">Happy Birthday!</h3>
                                    <p id="previewSub">Your special day has arrived</p>
                                </div>
                                <div class="preview-body">
                                    <div class="preview-message" id="previewMsg">You are someone so special, and today
                                        is all about you. Open each gift with love. 🎀</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step-nav">
                        <button class="btn-prev" onclick="prevStep()">← Back</button>
                        <button class="btn-next" onclick="nextStep()">Continue →</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── STEP 4: Gift Sections ── -->
        <div class="step-panel" id="panel4">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🎁</div>
                    <div class="card-title">
                        <h3>Configure Gift Sections</h3>
                        <p>3 unique gifts — each with its own style and surprise</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="gift-tabs">
                        <button class="gift-tab active" onclick="switchGift(1,this)">🖼️ Gift 1 · Gallery</button>
                        <button class="gift-tab" onclick="switchGift(2,this)">💌 Gift 2 · Love Letter</button>
                        <button class="gift-tab" onclick="switchGift(3,this)">📖 Gift 3 · Story Book</button>
                    </div>

                    <!-- Gift 1 -->
                    <div class="gift-panel active" id="gift1">
                        <div class="form-group">
                            <label>Gift Title</label>
                            <input type="text" placeholder="e.g. Our Memories Together 📸"
                                value="Our Memories Together 📸" />
                        </div>
                        <div class="form-group">
                            <label>Image Gallery (upload photos — no text, pure visuals)</label>
                            <div class="image-upload-zone">
                                <span class="upload-icon">🖼️</span>
                                <div class="upload-text">Drop multiple photos or <strong>click to browse</strong></div>
                            </div>
                            <div class="image-slots">
                                <div class="image-slot">+<span>Photo 1</span></div>
                                <div class="image-slot">+<span>Photo 2</span></div>
                                <div class="image-slot">+<span>Photo 3</span></div>
                                <div class="image-slot">+<span>Photo 4</span></div>
                                <div class="image-slot">+<span>Photo 5</span></div>
                                <div class="image-slot">+<span>Photo 6</span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gallery Layout</label>
                            <select>
                                <option>Grid (3 columns)</option>
                                <option>Masonry</option>
                                <option>Slideshow / Carousel</option>
                                <option>Polaroid style</option>
                            </select>
                        </div>
                    </div>

                    <!-- Gift 2 -->
                    <div class="gift-panel" id="gift2">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Gift Title</label>
                                <input type="text" placeholder="e.g. A Letter From My Heart 💌"
                                    value="A Letter From My Heart 💌" />
                            </div>
                            <div class="form-group">
                                <label>Envelope Color</label>
                                <select>
                                    <option>Pink & Gold ✨</option>
                                    <option>Red & White ❤️</option>
                                    <option>Blue & Silver 💙</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Love Letter Content</label>
                            <textarea placeholder="Pour your heart out here…" style="min-height:150px;">Dear Aisha,

Every day with you feels like a blessing. On your birthday, I want you to know that you light up every room you walk into, and my life has been so much brighter since you've been in it.

With all my love 💕</textarea>
                        </div>
                        <div class="form-group">
                            <label>Accompanying Image</label>
                            <div class="image-upload-zone">
                                <span class="upload-icon">📷</span>
                                <div class="upload-text">Upload 1 meaningful photo to go with the letter</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Text Position</label>
                            <select>
                                <option>Letter left, Image right</option>
                                <option>Image left, Letter right</option>
                                <option>Letter above, Image below</option>
                            </select>
                        </div>
                    </div>

                    <!-- Gift 3 -->
                    <div class="gift-panel" id="gift3">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Book Title</label>
                                <input type="text" placeholder="e.g. Our Story 📖" value="The Story of Us 📖" />
                            </div>
                            <div class="form-group">
                                <label>Book Cover Style</label>
                                <select>
                                    <option>Classic Leather</option>
                                    <option>Floral Vintage</option>
                                    <option>Modern Minimal</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Story / Letter (shown on left page)</label>
                            <textarea
                                style="min-height:130px;">Chapter 1: The Beginning

It all started the day I first saw your smile. I knew right then that something magical was about to unfold — a story worth telling for the rest of my life…</textarea>
                        </div>
                        <div class="form-group">
                            <label>Book Image (shown on right page)</label>
                            <div class="image-upload-zone">
                                <span class="upload-icon">📸</span>
                                <div class="upload-text">Upload your favourite together photo</div>
                            </div>
                        </div>
                        <div class="book-preview">
                            <div class="book-header">
                                <div class="book-title">✦ The Story of Us ✦</div>
                            </div>
                            <div class="book-content">
                                <div class="book-text-side">
                                    Chapter 1: The Beginning<br><br>
                                    It all started the day I first saw your smile. I knew right then that something
                                    magical was about to unfold…
                                </div>
                                <div class="book-img-side">📷</div>
                            </div>
                        </div>
                    </div>

                    <div class="step-nav">
                        <button class="btn-prev" onclick="prevStep()">← Back</button>
                        <button class="btn-next" onclick="nextStep()">Continue →</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── STEP 5: Generate ── -->
        <div class="step-panel" id="panel5">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">✨</div>
                    <div class="card-title">
                        <h3>Generate & Share Your Card</h3>
                        <p>Everything looks great! Create the link and QR code to share</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="gift-summary-grid">
                        <div class="gift-summary-card">
                            <div class="gi">🖼️</div>
                            <h4>Gift 1</h4>
                            <p>Image Gallery</p>
                        </div>
                        <div class="gift-summary-card">
                            <div class="gi">💌</div>
                            <h4>Gift 2</h4>
                            <p>Love Letter + Image</p>
                        </div>
                        <div class="gift-summary-card">
                            <div class="gi">📖</div>
                            <h4>Gift 3</h4>
                            <p>Story Book UI</p>
                        </div>
                    </div>

                    <div class="generate-section">
                        <button class="generate-btn" onclick="generateCard()">
                            🎂 Generate Birthday Card
                        </button>
                        <div class="url-box" id="urlBox" style="display:none;">
                            <div class="url-text" id="urlDisplay">https://birthdaycard.app/c/aisha-2024-x7k9p</div>
                            <button class="copy-btn" onclick="copyUrl()">Copy Link</button>
                        </div>
                        <div class="qr-placeholder" id="qrBox" style="display:none;">
                            <span>▦</span>
                            <p>QR Code Generated</p>
                        </div>
                        <p id="shareNote"
                            style="margin-top:1rem; font-size:0.82rem; color:var(--text-muted); display:none;">
                            🔒 Only someone who knows the lock code (DOB) can open this card
                        </p>
                    </div>

                    <div class="step-nav">
                        <button class="btn-prev" onclick="prevStep()">← Back</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    let currentStep = 1;
    const totalSteps = 5;
    let selectedTheme = 'girl';

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

    function goToStep(n) {
        document.getElementById('panel' + currentStep).classList.remove('active');
        document.querySelectorAll('.step-item')[currentStep - 1].classList.remove('active');
        if (currentStep < n) {
            document.querySelectorAll('.step-item')[currentStep - 1].classList.add('done');
            document.getElementById('sn' + currentStep).textContent = '✓';
        }
        currentStep = n;
        document.getElementById('panel' + currentStep).classList.add('active');
        document.querySelectorAll('.step-item')[currentStep - 1].classList.add('active');
        document.getElementById('progressText').textContent = 'Step ' + currentStep + ' of ' + totalSteps;

        // Close sidebar on mobile when step changes
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    }

    function nextStep() {
        if (currentStep < totalSteps) goToStep(currentStep + 1);
    }

    function prevStep() {
        if (currentStep > 1) goToStep(currentStep - 1);
    }

    function selectTheme(t) {
        selectedTheme = t;
        document.getElementById('boyCard').classList.toggle('selected', t === 'boy');
        document.getElementById('girlCard').classList.toggle('selected', t === 'girl');
        setGlobalTheme(t);
    }

    function setGlobalTheme(t) {
        selectedTheme = t;
        const accent = t === 'boy' ? '#4f8ef7' : '#f76fa1';
        const accentSoft = t === 'boy' ? '#e8f1ff' : '#fff0f6';
        document.documentElement.style.setProperty('--accent', accent);
        document.documentElement.style.setProperty('--accent-soft', accentSoft);
        document.getElementById('sidebarBoy').className = 'theme-btn' + (t === 'boy' ? ' active-boy' : '');
        document.getElementById('sidebarGirl').className = 'theme-btn' + (t === 'girl' ? ' active-girl' : '');
        document.getElementById('boyCard').classList.toggle('selected', t === 'boy');
        document.getElementById('girlCard').classList.toggle('selected', t === 'girl');
        document.querySelector('.preview-header').style.background =
            t === 'boy' ?
            'linear-gradient(135deg, #4f8ef7, #90b9ff)' :
            'linear-gradient(135deg, #f76fa1, #ff9dcd)';
        document.querySelector('.generate-btn').style.background =
            t === 'boy' ?
            'linear-gradient(135deg, #4f8ef7, #90b9ff)' :
            'linear-gradient(135deg, #f76fa1, #ff9dcd)';
    }

    function generateLockCode(dob) {
        if (!dob) return;
        const d = new Date(dob);
        const day = String(d.getDate()).padStart(2, '0');
        const mon = String(d.getMonth() + 1).padStart(2, '0');
        const yr = d.getFullYear();
        document.getElementById('lockCodeDisplay').textContent = day + mon + yr;
    }

    function updatePreview() {
        const name = document.getElementById('recipientName').value || 'Birthday Star';
        const msg = document.getElementById('welcomeMsg').value;
        document.getElementById('previewName').textContent = 'Happy Birthday, ' + name + '! 🎉';
        document.getElementById('previewMsg').textContent = msg;
    }

    function switchGift(n, el) {
        document.querySelectorAll('.gift-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.gift-panel').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('gift' + n).classList.add('active');
    }

    function generateCard() {
        const slug = 'aisha-' + Date.now().toString(36);
        document.getElementById('urlDisplay').textContent = 'https://birthdaycard.app/c/' + slug;
        document.getElementById('urlBox').style.display = 'flex';
        document.getElementById('qrBox').style.display = 'flex';
        document.getElementById('shareNote').style.display = 'block';
    }

    function copyUrl() {
        const text = document.getElementById('urlDisplay').textContent;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.querySelector('.copy-btn');
            btn.textContent = 'Copied! ✓';
            setTimeout(() => btn.textContent = 'Copy Link', 2000);
        });
    }

    // Prevent scrolling when sidebar is open on mobile
    function preventScroll(e) {
        e.preventDefault();
    }

    window.addEventListener('load', function() {
        updateMenuButton();
    });
    </script>
</body>

</html>