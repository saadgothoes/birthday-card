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

        html {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            width: 100%;
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
            flex-wrap: wrap;
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

        /* User Dropdown */
        .user-dropdown {
            position: relative;
            margin-left: 1rem;
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

        /* Variant preview boxes (shown after theme is picked) */
        .variant-section {
            margin-top: 1.6rem;
            display: none;
        }

        .variant-section.visible {
            display: block;
        }

        .variant-section h4 {
            font-size: 0.78rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.8rem;
        }

        .variant-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .variant-choice {
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 0.6rem;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--surface2);
            position: relative;
        }

        .variant-choice:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .variant-choice.selected {
            border-color: var(--accent);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .variant-choice .variant-check {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.68rem;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 2;
        }

        .variant-choice.selected .variant-check {
            opacity: 1;
        }

        .variant-thumb {
            width: 100%;
            max-width: 100%;
            aspect-ratio: 16 / 10;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #fdf2f8 0%, #f8fafc 100%);
            border: 1px solid rgba(45, 31, 20, 0.08);
            contain: layout paint;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        /* Real page always rendered at a fixed desktop width (900px) inside the iframe,
       then scaled down via JS to fit whatever size the thumb container actually is —
       so the preview looks identical (the full desktop design) on mobile and web. */
        .variant-thumb iframe {
            position: absolute;
            inset: 0;
            width: 900px;
            height: 562px;
            border: 0;
            background: #fff;
            transform-origin: top left;
            pointer-events: none;
            display: block;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        /* The gift-box screen (page 3) switches to a pure-CSS mobile layout
       below 1024px — render its thumb wider than that breakpoint so it
       always shows the real desktop gift image, not the mobile fallback. */
        .gift-variant-thumb iframe {
            width: 1280px;
            height: 800px;
        }

        .variant-thumb iframe.loaded {
            opacity: 1;
        }

        .variant-choice:hover .variant-thumb {
            box-shadow: 0 0 0 3px var(--accent);
        }

        .variant-label {
            text-align: center;
            font-size: 0.82rem;
            font-weight: 600;
            margin-top: 0.6rem;
            color: var(--text-muted);
        }

        .variant-choice.selected .variant-label {
            color: var(--accent);
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

        /* Step 2 — PIN (DD-MM) input */
        .pin-dob-input {
            display: block;
            width: 160px;
            margin: 0.6rem auto;
            padding: 0.7rem 0;
            border-radius: 10px;
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            letter-spacing: 0.15em;
            text-align: center;
            outline: none;
            transition: all 0.2s;
            position: relative;
            z-index: 1;
        }

        .pin-dob-input::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .pin-dob-input:focus {
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.15);
        }

        .pin-recommend-btn {
            margin-top: 0.9rem;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pin-recommend-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .live-preview-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 0.8rem;
            font-weight: 600;
        }

        /* Step 2 layout — side-by-side on laptop/web (form left, live preview
       sticky on the right), stacked full-width on mobile/tablet. */
        .step2-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.6rem;
        }

        @media (min-width: 1000px) {
            .step2-grid {
                grid-template-columns: 1.1fr 0.9fr;
                align-items: start;
            }

            .step2-preview-col {
                position: sticky;
                top: 1.5rem;
            }
        }

        /* Step 2 — real page live preview (iframe of the actual public page).
       Same fixed-desktop-width + JS-scale technique as the Step 1 thumbs,
       so it always shows the full design shrunk to fit, on any screen size. */
        .live-page-preview {
            width: 100%;
            max-width: 100%;
            aspect-ratio: 16 / 10;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: #111;
            margin-bottom: 1.6rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            contain: layout paint;
        }

        .live-page-preview iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 900px;
            height: 562px;
            border: 0;
            transform-origin: top left;
            pointer-events: none;
        }

        /* PIN entry */
        .pin-set-label {
            font-size: 0.78rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
            font-weight: 600;
        }

        .pin-recommend-hint {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.9rem;
        }

        .pin-recommend-hint strong {
            color: var(--accent);
        }

        /* Mirror-shaped crop box — matches the real page's .arch-photo frame
       (viewBox 190x256, fully rounded top / straight bottom corners) so the
       client can drag/zoom their photo to exactly how it'll appear in the card. */
        .mirror-crop-wrap {
            margin-top: 1.2rem;
            text-align: center;
        }

        .mirror-crop-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.8rem;
        }

        .mirror-crop-box {
            width: 160px;
            aspect-ratio: 190 / 256;
            margin: 0 auto;
            border-radius: 80px 80px 6px 6px;
            border: 2.5px solid var(--accent);
            box-shadow: 0 0 0 6px rgba(247, 111, 161, 0.1);
            overflow: hidden;
            position: relative;
            background: #111;
            cursor: grab;
            touch-action: none;
            user-select: none;
        }

        .mirror-crop-box.dragging {
            cursor: grabbing;
        }

        .mirror-crop-box img {
            position: absolute;
            top: 50%;
            left: 50%;
            max-width: none;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .mirror-crop-zoom {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            margin-top: 0.9rem;
        }

        .mirror-crop-zoom input[type="range"] {
            width: 160px;
            accent-color: var(--accent);
        }

        /* Step 3 — Welcome screen preview */
        .welcome-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

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

        /* Gift 1 / Gift 2 photo slots — reuses .image-slot, adds a filled state
       that swaps the "+" placeholder for the uploaded photo thumbnail. */
        .image-slot {
            position: relative;
            overflow: hidden;
        }

        .image-slot .slot-preview {
            display: none;
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-slot.filled {
            border-style: solid;
        }

        .image-slot.filled .slot-preview {
            display: block;
        }

        .image-slot.filled .slot-plus {
            display: none;
        }

        .gift2-field-row {
            margin-top: 1.4rem;
        }

        .gift3-field-row {
            margin-top: 1.4rem;
        }

        /* Gift 3 is a portrait book sized off the viewport height, so a 900x562
       desktop-shaped iframe would leave it floating tiny in the middle.
       Render its thumbs and preview in a phone-shaped viewport instead. */
        .gift3-thumb {
            aspect-ratio: 7 / 11;
        }

        .gift3-thumb iframe,
        .gift3-preview iframe,
        .ending-preview iframe {
            width: 460px;
            height: 723px;
        }

        /* The ending page is a mobile-first, height-driven design — the same
       reason Gift 3's book gets a portrait frame. In a landscape preview the
       letter sheet reads nothing like it does on a phone. */
        .gift3-preview,
        .ending-preview {
            aspect-ratio: 7 / 11;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ── Gift 3 book-page sub-wizard ──────────────────────────
       The book has 10 pages; showing every page's fields at once would
       bury Step 7 under ~35 inputs. One book page is shown at a time. */
        .book-step-dots {
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
            margin-bottom: 1.1rem;
        }

        .book-step-dots span {
            width: 28px;
            height: 5px;
            border-radius: 100px;
            background: var(--border);
            cursor: pointer;
            transition: background 0.2s;
        }

        .book-step-dots span.done {
            background: var(--accent-soft);
        }

        .book-step-dots span.active {
            background: var(--accent);
        }

        .book-step-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1rem;
        }

        .book-step-head h4 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
        }

        .book-step-count {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent);
            background: var(--accent-soft);
            border-radius: 100px;
            padding: 0.3rem 0.8rem;
            white-space: nowrap;
        }

        .book-page-panel {
            display: none;
        }

        .book-page-panel.active {
            display: block;
        }

        .book-page-nav {
            display: flex;
            justify-content: space-between;
            gap: 0.8rem;
            margin-top: 1.3rem;
        }

        .book-photo-slots {
            grid-template-columns: repeat(auto-fill, minmax(0, 116px));
        }

        .checklist-row {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        /* Every card design is a fixed layout, so each field shows how much room
       it has left rather than silently truncating at the maxlength. */
        .field-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 0.8rem;
        }

        .char-count {
            font-size: 0.7rem;
            font-variant-numeric: tabular-nums;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .char-count.at-limit {
            color: #dc2626;
            font-weight: 600;
        }

        .field-hint {
            font-size: 0.74rem;
            color: var(--text-muted);
            margin: 0.4rem 0 0;
        }

        .dream-remove {
            flex: 0 0 auto;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--surface2);
            color: var(--text-muted);
            font-size: 0.8rem;
            line-height: 1;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dream-remove:hover {
            border-color: #dc2626;
            color: #dc2626;
        }

        .dream-add {
            margin-top: 0.7rem;
            padding: 0.45rem 0.9rem;
            border-radius: 100px;
            border: 1px dashed var(--border);
            background: transparent;
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dream-add:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .dream-row {
            margin-bottom: 0.6rem;
        }

        .checklist-row input[type="text"] {
            flex: 1;
            min-width: 0;
        }

        .checklist-row .done-toggle {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin: 0;
            font-size: 0.78rem;
            color: var(--text-muted);
            white-space: nowrap;
            cursor: pointer;
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
            grid-template-columns: repeat(4, 1fr);
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

            .topbar-right {
                width: 100%;
                justify-content: space-between;
                align-items: center;
                gap: 0.75rem;
            }

            .user-dropdown {
                margin-left: 0;
                width: 100%;
                max-width: 220px;
            }

            .user-dropdown-btn {
                width: 100%;
                justify-content: center;
            }

            .user-dropdown-menu {
                width: 100%;
                min-width: 0;
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

            .welcome-layout {
                grid-template-columns: 1fr;
                gap: 1.2rem;
            }

            .variant-grid {
                grid-template-columns: 1fr;
                gap: 0.9rem;
            }

            .pin-dob-input {
                width: 140px;
                font-size: 1.4rem;
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
                padding: 1rem 0.75rem 2rem;
            }

            .topbar {
                margin-bottom: 1rem;
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
                width: 100%;
                justify-content: center;
            }

            .topbar-right {
                flex-direction: column;
                align-items: stretch;
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

            .pin-dob-input {
                width: 120px;
                font-size: 1.25rem;
            }

            .pin-recommend-hint {
                font-size: 0.75rem;
            }

            .live-preview-label {
                font-size: 0.7rem;
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
                gap: 0.6rem;
            }

            .btn-prev,
            .btn-next {
                width: 100%;
                justify-content: center;
            }

            .image-slots {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Payment Popup */
        .payment-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .popup-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .popup-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .popup-content h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #d97706;
        }

        .popup-content p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .popup-close {
            background: #d97706;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .popup-close:hover {
            background: #b45309;
        }

        /* Password Alert */
        .password-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 400px;
            z-index: 1001;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .alert-content {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border-left: 4px solid #f59e0b;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .alert-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .alert-text h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 0.25rem;
        }

        .alert-text p {
            font-size: 0.85rem;
            color: #a16207;
            margin: 0;
        }

        .alert-actions {
            margin-top: 0.5rem;
        }

        .alert-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .alert-btn-primary {
            background: #f59e0b;
            color: white;
        }

        .alert-btn-primary:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        /* ── Step 8: Ending Page ─────────────────────────────── */
        .ending-preview-tabs {
            display: inline-flex;
            gap: 0.35rem;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 100px;
            padding: 0.25rem;
            margin-bottom: 0.9rem;
        }

        .ending-preview-tabs button {
            border: 0;
            background: transparent;
            color: var(--text-muted);
            border-radius: 100px;
            padding: 0.35rem 0.95rem;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .ending-preview-tabs button.active {
            background: var(--accent);
            color: #fff;
        }

        /* ── Step 9: QR Select ───────────────────────────────── */
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.2rem;
        }

        .qr-thumb {
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            background: var(--surface2);
            border: 1px solid rgba(45, 31, 20, 0.08);
        }

        .qr-thumb img {
            display: block;
            width: 100%;
            height: auto;
        }

        .qr-blurb {
            text-align: center;
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        .qr-result {
            display: none;
            margin-top: 1.6rem;
            text-align: center;
        }

        .qr-result.visible {
            display: block;
        }

        .qr-large {
            width: 260px;
            max-width: 100%;
            margin: 0 auto 1.2rem;
            display: block;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .qr-actions {
            display: flex;
            gap: 0.7rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 1.1rem;
        }

        .qr-actions .copy-btn {
            padding: 0.55rem 1.2rem;
        }

        /* A side whose designs are not wired up yet still shows what is coming,
       so the tab is never an empty room. */
        .theme-placeholder {
            display: none;
            margin-top: 1.6rem;
        }

        .theme-placeholder.visible {
            display: block;
        }

        .placeholder-note {
            background: var(--accent-soft);
            border: 1.5px dashed var(--accent);
            border-radius: 12px;
            padding: 0.9rem 1.2rem;
            font-size: 0.84rem;
            color: var(--text-muted);
            margin-bottom: 1.2rem;
        }

        .placeholder-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .placeholder-card {
            border: 2px dashed var(--border);
            border-radius: 14px;
            padding: 1.4rem 0.8rem;
            text-align: center;
            background: var(--surface2);
            opacity: 0.75;
        }

        .placeholder-card .pi {
            font-size: 1.6rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .placeholder-card h5 {
            font-size: 0.84rem;
            margin: 0 0 0.25rem;
            color: var(--text-muted);
        }

        .placeholder-card p {
            font-size: 0.72rem;
            margin: 0;
            color: var(--text-muted);
        }

        @media (max-width: 900px) {

            .qr-grid,
            .placeholder-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* A clip's slot mirrors the photo slots, showing the first frame once one
       is chosen so the client can see which clip they picked. */
        .video-slot {
            position: relative;
            width: 100%;
            max-width: 220px;
            aspect-ratio: 16 / 10;
            border: 2px dashed var(--border);
            border-radius: 12px;
            background: var(--surface2);
            cursor: pointer;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.2s, background 0.2s;
        }

        .video-slot:hover {
            border-color: var(--accent);
        }

        .video-slot video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .video-slot.filled video {
            display: block;
        }

        .video-slot.filled .slot-plus {
            display: none;
        }

        .video-slot.uploading::after {
            content: 'Uploading…';
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.45);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .field-error {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #dc2626;
        }

        .gift2-help-note {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            margin: 0 0 1rem;
            padding: 0.8rem 1rem;
            border: 1.5px solid var(--accent);
            border-radius: 10px;
            background: var(--accent-soft);
            color: var(--text-muted);
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .gift2-help-note strong {
            color: var(--accent);
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
                    <div class="step-label">Gift Box Screen</div>
                    <div class="step-sub">Choose the gift screen design</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(5)">
                <div class="step-num" id="sn5">5</div>
                <div>
                    <div class="step-label">Gift 1</div>
                    <div class="step-sub">Theme & photos</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(6)">
                <div class="step-num" id="sn6">6</div>
                <div>
                    <div class="step-label">Gift 2</div>
                    <div class="step-sub">Theme, photos, date & note</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(7)">
                <div class="step-num" id="sn7">7</div>
                <div>
                    <div class="step-label">Gift 3</div>
                    <div class="step-sub" id="gift3StepSub">Story book, page by page</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(8)">
                <div class="step-num" id="sn8">8</div>
                <div>
                    <div class="step-label">Ending Page</div>
                    <div class="step-sub" id="endingStepSub">The closing letter</div>
                </div>
            </div>
            <div class="step-item" onclick="goToStep(9)">
                <div class="step-num" id="sn9">9</div>
                <div>
                    <div class="step-label">QR Select</div>
                    <div class="step-sub">Link & QR code</div>
                </div>
            </div>
        </nav>

    </aside>

    <!-- ─── MAIN ─── -->
    <main class="main">
        <div class="topbar">
            <div class="topbar-left">
                <h2>Client Dashboard</h2>
                <p>Create & share a beautiful birthday card experience</p>
            </div>
            <div class="topbar-right">
                <div class="progress-pill">
                    <div class="dot"></div>
                    <span id="progressText">Step 1 of 5</span>
                </div>
                <button id="menuToggle" onclick="toggleSidebar()">☰</button>

                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <button class="user-dropdown-btn" onclick="toggleUserDropdown()">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
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
                                <path
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                </path>
                            </svg>
                            Settings
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

        @php
        $daysLeft = null;
        if (Auth::user()->subscription_start_date) {
        $startDate = \Illuminate\Support\Carbon::parse(Auth::user()->subscription_start_date);
        $daysLeft = now()->diffInDays($startDate->addDays(30));
        }
        $showPaymentPopup = $daysLeft !== null && $daysLeft <= 6 && Auth::user()->status === 'active';
            $showPasswordAlert = !Auth::user()->hasChangedPassword();
            @endphp

            @if($showPasswordAlert)
            <div class="password-alert" id="passwordAlert">
                <div class="alert-content">
                    <div class="alert-icon">🔒</div>
                    <div class="alert-text">
                        <h3>Update Your Password</h3>
                        <p>For security reasons, please update your password before continuing.</p>
                    </div>
                    <div class="alert-actions">
                        <a href="{{ route('client.settings') }}" class="alert-btn alert-btn-primary">Update Password</a>
                    </div>
                </div>
            </div>
            @endif

            @if($showPaymentPopup)
            <div class="payment-popup" id="paymentPopup">
                <div class="popup-content">
                    <div class="popup-icon">⚠️</div>
                    <h3>Payment Pending</h3>
                    <p>Your subscription will expire in {{ $daysLeft }} days. Please renew your payment to continue
                        using the service.</p>
                    <button class="popup-close" onclick="closePopup()">OK</button>
                </div>
            </div>
            @endif

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

                        <!-- Boy variants -->
                        <div class="variant-section" id="boyVariants">
                            <h4>Choose Boy Card Design</h4>
                            <div class="variant-grid">
                                <div class="variant-choice" id="boyVariant1" onclick="selectVariant(1)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe data-src="{{ route('boy.page.variant',['page'=>1,'variant'=>1]) }}" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Midnight Gold</div>
                                </div>
                                <div class="variant-choice" id="boyVariant2" onclick="selectVariant(2)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe data-src="{{ route('boy.page.variant',['page'=>1,'variant'=>2]) }}" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Light Blue Sky</div>
                                </div>
                            </div>
                        </div>

                        <!-- Girl variants -->
                        <div class="variant-section" id="girlVariants">
                            <h4>Choose Girl Card Design</h4>
                            <div class="variant-grid">
                                <div class="variant-choice" id="girlVariant1" onclick="selectVariant(1)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe data-src="{{ route('girl.page.variant',['page'=>1,'variant'=>1]) }}" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Blush Petal</div>
                                </div>
                                <div class="variant-choice" id="girlVariant2" onclick="selectVariant(2)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe data-src="{{ route('girl.page.variant',['page'=>1,'variant'=>2]) }}" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Rose Bloom</div>
                                </div>
                            </div>
                        </div>

                        <p class="step1-error" id="step1Error"
                            style="display:none; color:#dc2626; font-size:0.82rem; margin-top:1rem;">
                            Please select a card design to continue.</p>

                        <div class="step-nav">
                            <button class="btn-next" id="step1ContinueBtn" onclick="saveStep1AndContinue()">Continue
                                →</button>
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
                            <h3>Add Photo & Set PIN</h3>
                            <p>Position the birthday person's photo and choose a 4-digit PIN</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="step2-grid">
                            <div>
                                <div class="form-group">
                                    <label>Photo</label>
                                    <div class="image-upload-zone"
                                        onclick="document.getElementById('step2ImageInput').click()">
                                        <span class="upload-icon">📸</span>
                                        <div class="upload-text">Drop image here or <strong>click to browse</strong>
                                        </div>
                                    </div>
                                    <input type="file" id="step2ImageInput" accept="image/*" style="display:none"
                                        onchange="onStep2ImageSelected(event)">
                                </div>

                                <!-- Mirror-shaped crop box: drag the photo to position it inside the frame -->
                                <div class="mirror-crop-wrap" id="mirrorCropWrap" style="display:none;">
                                    <div class="mirror-crop-label">Drag the photo to position it inside the mirror
                                        frame</div>
                                    <div class="mirror-crop-box" id="mirrorCropBox">
                                        <img id="mirrorCropImg" draggable="false" alt="Crop preview">
                                    </div>
                                    <div class="mirror-crop-zoom">
                                        <span>🔍</span>
                                        <input type="range" id="mirrorZoomSlider" min="100" max="250" value="100"
                                            oninput="onMirrorZoomChange(this.value)">
                                    </div>
                                </div>

                                <div class="pin-set-label" style="margin-top:1.4rem;">Set PIN</div>
                                <div class="pin-recommend-hint">
                                    💡 <strong>Recommended:</strong> use the birthday person's date of birth as
                                    <strong>DD-MM</strong> — but you can set any 4 digits you like.
                                </div>

                                <div class="lock-display">
                                    <div class="lock-icon">🔒</div>
                                    <input type="text" class="pin-dob-input" id="pinDobInput" maxlength="5"
                                        inputmode="numeric" placeholder="DD-MM" oninput="onPinDobInput(this)">
                                    <div class="lock-label">4-Digit Unlock PIN (DD-MM)</div>
                                </div>

                                <p class="step2-error" id="step2Error"
                                    style="display:none; color:#dc2626; font-size:0.82rem; margin-top:0.6rem;"></p>

                                <div class="step-nav">
                                    <button class="btn-prev" onclick="prevStep()">← Back</button>
                                    <button class="btn-next" id="step2ContinueBtn"
                                        onclick="saveStep2AndContinue()">Continue →</button>
                                </div>
                            </div>

                            <div class="step2-preview-col">
                                <div class="live-preview-label">Live Preview — Your Selected Design</div>
                                <div class="live-page-preview">
                                    <iframe id="step2LivePreview" src="about:blank" tabindex="-1"></iframe>
                                </div>
                            </div>
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
                        <div class="welcome-layout">
                            <div>
                                @php $welcomeLimits = \App\Http\Controllers\Client\BirthdayCardController::WELCOME_LIMITS; @endphp
                                <div class="form-group">
                                    <div class="field-head">
                                        <label>Heading</label>
                                        <span class="char-count" data-for="step3Heading"></span>
                                    </div>
                                    <input type="text" placeholder="e.g. Happy Birthday My Love" id="step3Heading"
                                        maxlength="{{ $welcomeLimits['heading'] }}"
                                        oninput="updateStep3LivePreview()" />
                                </div>
                                <div class="form-group">
                                    <div class="field-head">
                                        <label>Message</label>
                                        <span class="char-count" data-for="step3Message"></span>
                                    </div>
                                    <textarea placeholder="Write a heartfelt message…" id="step3Message"
                                        maxlength="{{ $welcomeLimits['message'] }}" data-max-lines="4"
                                        oninput="updateStep3LivePreview()"></textarea>
                                    <p class="field-hint">The welcome screen sets this in a large display face —
                                        up to 4 short lines.</p>
                                </div>
                                <p class="step3-error" id="step3Error"
                                    style="display:none; color:#dc2626; font-size:0.82rem; margin-top:0.6rem;"></p>
                            </div>
                            <div class="step2-preview-col">
                                <div class="live-preview-label">Live Preview — Your Selected Design</div>
                                <div class="live-page-preview">
                                    <iframe id="step3LivePreview" src="about:blank" tabindex="-1"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="step-nav">
                            <button class="btn-prev" onclick="prevStep()">← Back</button>
                            <button class="btn-next" id="step3ContinueBtn" onclick="saveStep3AndContinue()">Continue
                                →</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 4: Gift Box Screen ── -->
            <div class="step-panel" id="panel4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">🎁</div>
                        <div class="card-title">
                            <h3>Choose Gift Box Screen Design</h3>
                            <p>This is the screen they'll tap gifts open from</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="variant-section visible" style="margin-top:0;">
                            <div class="variant-grid">
                                <div class="variant-choice" id="giftVariant1" onclick="selectGiftVariant(1)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb gift-variant-thumb">
                                        <iframe id="giftVariantFrame1" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Design 1</div>
                                </div>
                                <div class="variant-choice" id="giftVariant2" onclick="selectGiftVariant(2)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb gift-variant-thumb">
                                        <iframe id="giftVariantFrame2" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Design 2</div>
                                </div>
                            </div>
                            <p class="step4-error" id="step4Error"
                                style="display:none; color:#dc2626; font-size:0.82rem; margin-top:1rem;">
                                Please select a gift screen design to continue.</p>
                        </div>

                        <div class="step-nav">
                            <button class="btn-prev" onclick="prevStep()">← Back</button>
                            <button class="btn-next" id="step4ContinueBtn"
                                onclick="saveStep4AndContinue()">Continue →</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 5: Gift 1 ── -->
            <div class="step-panel" id="panel5">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">🖼️</div>
                        <div class="card-title">
                            <h3>Gift 1 — Memory Photos</h3>
                            <p>Pick a style, then add the photos</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="variant-section visible" style="margin-top:0;">
                            <h4>Choose a Theme</h4>
                            <div class="variant-grid" id="gift1ThemeGrid">
                                <div class="variant-choice" id="gift1Theme1" onclick="selectGiftTheme(1,1)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe id="gift1ThemeFrame1" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme 1</div>
                                </div>
                                <div class="variant-choice" id="gift1Theme2" onclick="selectGiftTheme(1,2)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe id="gift1ThemeFrame2" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme 2</div>
                                </div>
                                <div class="variant-choice" id="gift1Theme3" onclick="selectGiftTheme(1,3)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe id="gift1ThemeFrame3" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme 3</div>
                                </div>
                                <div class="variant-choice" id="gift1Theme4" onclick="selectGiftTheme(1,4)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe id="gift1ThemeFrame4" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme 4</div>
                                </div>
                            </div>
                            <p class="step5-error" id="step5Error"
                                style="display:none; color:#dc2626; font-size:0.82rem; margin-top:1rem;">
                                Please choose a theme to continue.</p>
                        </div>

                        <div class="form-group gift2-field-row">
                            <label>Photos (3)</label>
                            <div class="image-slots">
                                <div class="image-slot" id="gift1Slot0"
                                    onclick="document.getElementById('gift1PhotoInput0').click()">
                                    <img class="slot-preview" id="gift1PhotoPreview0" alt="">
                                    <span class="slot-plus">+<span>Photo 1</span></span>
                                </div>
                                <div class="image-slot" id="gift1Slot1"
                                    onclick="document.getElementById('gift1PhotoInput1').click()">
                                    <img class="slot-preview" id="gift1PhotoPreview1" alt="">
                                    <span class="slot-plus">+<span>Photo 2</span></span>
                                </div>
                                <div class="image-slot" id="gift1Slot2"
                                    onclick="document.getElementById('gift1PhotoInput2').click()">
                                    <img class="slot-preview" id="gift1PhotoPreview2" alt="">
                                    <span class="slot-plus">+<span>Photo 3</span></span>
                                </div>
                            </div>
                            <input type="file" id="gift1PhotoInput0" accept="image/*" style="display:none"
                                onchange="onGiftPhotoSelected('gift1',0,this)">
                            <input type="file" id="gift1PhotoInput1" accept="image/*" style="display:none"
                                onchange="onGiftPhotoSelected('gift1',1,this)">
                            <input type="file" id="gift1PhotoInput2" accept="image/*" style="display:none"
                                onchange="onGiftPhotoSelected('gift1',2,this)">
                        </div>

                        @php $gift1GirlLimits = \App\Http\Controllers\Client\BirthdayCardController::GIFT1_GIRL_LIMITS; @endphp
                        {{-- The girl Gift 1 design pairs the photo board with a mini calendar
                             and a handwritten note; the boy design has neither, so these two
                             only appear on the girl side (see updateGift1FieldsVisibility). --}}
                        <div class="form-group gift2-field-row gift1-girl-only" id="gift1CalRow">
                            <label>Special Date</label>
                            <input type="date" id="gift1CalDate" oninput="updateGift1LivePreview()">
                            <p class="field-hint">The calendar in this design marks this day, and
                                takes its month name and length from it.</p>
                        </div>

                        <div class="form-group gift2-field-row gift1-girl-only" id="gift1MessageRow">
                            <div class="field-head">
                                <label>Handwritten Note</label>
                                <span class="char-count" data-for="gift1Message"></span>
                            </div>
                            <textarea id="gift1Message" rows="3"
                                maxlength="{{ $gift1GirlLimits['message'] }}"
                                placeholder="e.g. Your laugh is my favorite sound."
                                oninput="updateGift1LivePreview()"></textarea>
                            <p class="field-hint">Sits in a small panel beside the calendar.</p>
                        </div>

                        <div class="gift2-field-row">
                            <div class="live-preview-label">Live Preview</div>
                            <div class="live-page-preview">
                                <iframe id="gift1LivePreview" src="about:blank" tabindex="-1"></iframe>
                            </div>
                        </div>

                        <div class="step-nav">
                            <button class="btn-prev" onclick="prevStep()">← Back</button>
                            <button class="btn-next" id="step5ContinueBtn"
                                onclick="saveStep5AndContinue()">Continue →</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 6: Gift 2 ── -->
            <div class="step-panel" id="panel6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">💌</div>
                        <div class="card-title">
                            <h3>Gift 2 — Photos, Date &amp; Note</h3>
                            <p>Pick a style, then personalize it</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="variant-section visible" style="margin-top:0;">
                            <h4>Choose a Theme</h4>
                            <div class="variant-grid" id="gift2ThemeGrid">
                                <div class="variant-choice" id="gift2Theme1" onclick="selectGiftTheme(2,1)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe id="gift2ThemeFrame1" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme 1</div>
                                </div>
                                <div class="variant-choice" id="gift2Theme2" onclick="selectGiftTheme(2,2)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe id="gift2ThemeFrame2" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme 2</div>
                                </div>
                                <div class="variant-choice" id="gift2Theme3" onclick="selectGiftTheme(2,3)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe id="gift2ThemeFrame3" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme 3</div>
                                </div>
                                <div class="variant-choice" id="gift2Theme4" onclick="selectGiftTheme(2,4)">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb">
                                        <iframe id="gift2ThemeFrame4" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme 4</div>
                                </div>
                            </div>
                            <p class="step6-error" id="step6Error"
                                style="display:none; color:#dc2626; font-size:0.82rem; margin-top:1rem;">
                                Please choose a theme to continue.</p>
                        </div>

                        <div id="gift2BoySide">
                            <div class="form-group gift2-field-row">
                                <label>Photos</label>
                                <div class="image-slots">
                                    <div class="image-slot" id="gift2Slot0"
                                        onclick="document.getElementById('gift2PhotoInput0').click()">
                                        <img class="slot-preview" id="gift2PhotoPreview0" alt="">
                                        <span class="slot-plus">+<span>Photo 1</span></span>
                                    </div>
                                    <div class="image-slot" id="gift2Slot1"
                                        onclick="document.getElementById('gift2PhotoInput1').click()">
                                        <img class="slot-preview" id="gift2PhotoPreview1" alt="">
                                        <span class="slot-plus">+<span>Photo 2</span></span>
                                    </div>
                                    <div class="image-slot" id="gift2Slot2"
                                        onclick="document.getElementById('gift2PhotoInput2').click()">
                                        <img class="slot-preview" id="gift2PhotoPreview2" alt="">
                                        <span class="slot-plus">+<span>Photo 3</span></span>
                                    </div>
                                    <div class="image-slot" id="gift2Slot3Wrap"
                                        onclick="document.getElementById('gift2PhotoInput3').click()">
                                        <img class="slot-preview" id="gift2PhotoPreview3" alt="">
                                        <span class="slot-plus">+<span>Photo 4</span></span>
                                    </div>
                                </div>
                                <input type="file" id="gift2PhotoInput0" accept="image/*" style="display:none"
                                    onchange="onGiftPhotoSelected('gift2',0,this)">
                                <input type="file" id="gift2PhotoInput1" accept="image/*" style="display:none"
                                    onchange="onGiftPhotoSelected('gift2',1,this)">
                                <input type="file" id="gift2PhotoInput2" accept="image/*" style="display:none"
                                    onchange="onGiftPhotoSelected('gift2',2,this)">
                                <input type="file" id="gift2PhotoInput3" accept="image/*" style="display:none"
                                    onchange="onGiftPhotoSelected('gift2',3,this)">
                            </div>

                            @php $gift2Limits = \App\Http\Controllers\Client\BirthdayCardController::GIFT2_LIMITS; @endphp
                            <div class="form-row gift2-field-row" id="gift2NamesRow">
                                <div class="form-group">
                                    <div class="field-head">
                                        <label>Name 1</label>
                                        <span class="char-count" data-for="gift2NameFirst"></span>
                                    </div>
                                    <input type="text" id="gift2NameFirst" placeholder="e.g. Emma"
                                        maxlength="{{ $gift2Limits['name_first'] }}"
                                        oninput="updateGift2LivePreview()">
                                </div>
                                <div class="form-group">
                                    <div class="field-head">
                                        <label>Name 2</label>
                                        <span class="char-count" data-for="gift2NameSecond"></span>
                                    </div>
                                    <input type="text" id="gift2NameSecond" placeholder="e.g. Lucas"
                                        maxlength="{{ $gift2Limits['name_second'] }}"
                                        oninput="updateGift2LivePreview()">
                                </div>
                            </div>

                            <div class="form-group gift2-field-row" id="gift2CalRow">
                                <label>Special Date</label>
                                <input type="date" id="gift2CalDate" oninput="updateGift2LivePreview()">
                            </div>

                            <div class="form-group gift2-field-row">
                                <div class="field-head">
                                    <label>Message</label>
                                    <span class="char-count" data-for="gift2Message"></span>
                                </div>
                                {{-- The boy design drops this into a fixed note panel; the girl one
                                 writes it out on a taller letter sheet. The limit follows the
                                 chosen theme (see setGift2MessageLimit). --}}
                                <textarea id="gift2Message" placeholder="Write a heartfelt note…"
                                    maxlength="{{ $gift2Limits['message_boy'] }}"
                                    oninput="onGift2MessageInput(this)"></textarea>
                            </div>

                            <div class="form-group gift2-field-row" id="gift2SignedGroup">
                                <div class="field-head">
                                    <label>Signed</label>
                                    <span class="char-count" data-for="gift2Signed"></span>
                                </div>
                                <input type="text" id="gift2Signed" placeholder="e.g. — always, E"
                                    maxlength="{{ $gift2Limits['signed'] }}"
                                    oninput="updateGift2LivePreview()">
                            </div>
                        </div>

                        <!-- ── Girl Gift 2: the scene, one beat at a time ── -->
                        @php
                        $gift2GirlLimits = \App\Http\Controllers\Client\BirthdayCardController::GIFT2_GIRL_LIMITS;

                        // The girl design is a scene, not a form: a wrapped box that opens
                        // onto three polaroids and then an envelope. Walking it one beat at
                        // a time keeps this step from turning into a wall of inputs, the
                        // same way Gift 3's book is walked a page at a time.
                        $gift2GirlSteps = [
                        [
                        'title' => 'The Gift Box',
                        'blurb' => 'What the recipient sees before anything opens',
                        'fields' => [
                        ['key' => 'box_title', 'label' => 'Line Above the Box',
                        'default' => 'A little surprise is waiting…'],
                        ['key' => 'box_hint', 'label' => 'Prompt Under It',
                        'default' => 'Tap the ribbon'],
                        ],
                        ],
                        [
                        'title' => 'Photo 1',
                        'blurb' => 'The first polaroid and its caption',
                        'fields' => [
                        ['type' => 'photo', 'index' => 0, 'label' => 'Photo 1'],
                        ['key' => 'cap1', 'label' => 'Caption', 'default' => 'memory one'],
                        ],
                        ],
                        [
                        'title' => 'Photo 2',
                        'blurb' => 'The second polaroid and its caption',
                        'fields' => [
                        ['type' => 'photo', 'index' => 1, 'label' => 'Photo 2'],
                        ['key' => 'cap2', 'label' => 'Caption', 'default' => 'memory two'],
                        ],
                        ],
                        [
                        'title' => 'Photo 3',
                        'blurb' => 'The third polaroid and its caption',
                        'fields' => [
                        ['type' => 'photo', 'index' => 2, 'label' => 'Photo 3'],
                        ['key' => 'cap3', 'label' => 'Caption', 'default' => 'memory three'],
                        ],
                        ],
                        [
                        'title' => 'The Letter',
                        'blurb' => 'Written out by hand when the envelope opens',
                        'fields' => [
                        ['type' => 'letter', 'label' => 'Letter'],
                        ],
                        ],
                        ];
                        $gift2GirlStepCount = count($gift2GirlSteps);
                        @endphp
                        <div id="gift2GirlSide">
                            <div class="gift2-help-note" role="note">
                                <span aria-hidden="true">ⓘ</span>
                                <span><strong>How will Gift 2 open?</strong> The recipient should tap the ribbon first, then tap the gift box three times. The <strong>ⓘ</strong> button in the preview repeats these steps.</span>
                            </div>
                            <div class="book-step-dots" id="gift2GirlDots">
                                @foreach ($gift2GirlSteps as $i => $beat)
                                <span onclick="goToGift2GirlStep({{ $i + 1 }})"
                                    title="{{ $beat['title'] }}"></span>
                                @endforeach
                            </div>

                            @foreach ($gift2GirlSteps as $i => $beat)
                            <div class="book-page-panel" id="gift2GirlStep{{ $i + 1 }}">
                                <div class="book-step-head">
                                    <h4>{{ $beat['title'] }}</h4>
                                    <span class="book-step-count">Step {{ $i + 1 }} of
                                        {{ $gift2GirlStepCount }}</span>
                                </div>
                                <p class="field-hint" style="margin:-0.4rem 0 1rem;">{{ $beat['blurb'] }}</p>

                                @foreach ($beat['fields'] as $field)
                                @php
                                $type = $field['type'] ?? 'text';
                                $key = $field['key'] ?? null;
                                $inputId = $key ? 'gift2_' . $key : null;
                                @endphp

                                @if ($type === 'photo')
                                <div class="form-group">
                                    <label>{{ $field['label'] }}</label>
                                    <div class="image-slots book-photo-slots">
                                        <div class="image-slot" id="gift2GirlSlot{{ $field['index'] }}"
                                            onclick="document.getElementById('gift2PhotoInput{{ $field['index'] }}').click()">
                                            <img class="slot-preview"
                                                id="gift2GirlPhotoPreview{{ $field['index'] }}" alt="">
                                            <span class="slot-plus">+<span>{{ $field['label'] }}</span></span>
                                        </div>
                                    </div>
                                </div>

                                @elseif ($type === 'letter')
                                <div class="form-group">
                                    <div class="field-head">
                                        <label>Letter</label>
                                        <span class="char-count" data-for="gift2GirlMessage"></span>
                                    </div>
                                    {{-- Kept in step with the boy design's own message box, so
                                         switching theme never loses what was written. --}}
                                    <textarea id="gift2GirlMessage" rows="7"
                                        maxlength="{{ $gift2Limits['message_girl'] }}"
                                        placeholder="Dear Love,&#10;&#10;Every day with you makes my life more beautiful."
                                        oninput="onGift2MessageInput(this)"></textarea>
                                    <p class="field-hint">A blank line starts a new line on the paper.</p>
                                </div>

                                @else
                                <div class="form-group">
                                    <div class="field-head">
                                        <label>{{ $field['label'] }}</label>
                                        <span class="char-count" data-for="{{ $inputId }}"></span>
                                    </div>
                                    <input type="text" id="{{ $inputId }}"
                                        maxlength="{{ $gift2GirlLimits[$key] }}"
                                        value="{{ $field['default'] }}"
                                        placeholder="{{ $field['default'] }}"
                                        oninput="updateGift2LivePreview()">
                                </div>
                                @endif

                                @endforeach
                            </div>
                            @endforeach

                            <div class="book-page-nav">
                                <button class="btn-prev" id="gift2GirlPrevBtn"
                                    onclick="goToGift2GirlStep(currentGift2GirlStep - 1)">← Previous</button>
                                <button class="btn-next" id="gift2GirlNextBtn"
                                    onclick="goToGift2GirlStep(currentGift2GirlStep + 1)">Next →</button>
                            </div>
                        </div>

                        <div class="gift2-field-row">
                            <div class="live-preview-label">Live Preview</div>
                            <div class="live-page-preview">
                                <iframe id="gift2LivePreview" src="about:blank" tabindex="-1"></iframe>
                            </div>
                        </div>

                        <div class="step-nav">
                            <button class="btn-prev" onclick="prevStep()">← Back</button>
                            <button class="btn-next" id="step6ContinueBtn"
                                onclick="saveStep6AndContinue()">Continue →</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 7: Gift 3 — the "Our Story" book ── -->
            @php
            // One entry per page of the book, in book order. The wizard
            // reveals exactly one of these at a time so the 10-page book
            // doesn't turn Step 7 into a wall of ~35 inputs.
            // Every default is the template's own original text, so a
            // client who changes nothing still gets a complete book.
            // (The closed cover is deliberately not editable.)
            //
            // Field lengths come from the controller, which derives each
            // one from the room that slot actually has in the design.
            $gift3Limits = \App\Http\Controllers\Client\BirthdayCardController::GIFT3_TEXT_LIMITS;
            $gift3MinDreams = \App\Http\Controllers\Client\BirthdayCardController::GIFT3_MIN_DREAMS;
            $gift3MaxDreams = \App\Http\Controllers\Client\BirthdayCardController::GIFT3_MAX_DREAMS;
            $gift3LetterLines = \App\Http\Controllers\Client\BirthdayCardController::GIFT3_LETTER_MAX_LINES;

            $gift3Pages = [
            [
            'title' => 'Title Page',
            'fields' => [
            ['key' => 'eyebrow', 'label' => 'Book Title', 'default' => 'Our Story'],
            ['key' => 'from_name', 'label' => 'Made By', 'default' => 'Emma'],
            ['key' => 'to_name', 'label' => 'For', 'default' => 'Lucas'],
            ],
            ],
            [
            'title' => 'The Big Photo',
            'fields' => [
            ['key' => 'photo1', 'type' => 'photo', 'index' => 0, 'label' => 'Photo'],
            ['key' => 'caption', 'label' => 'Caption', 'default' => 'The day everything changed.'],
            ],
            ],
            [
            'title' => 'A Memory',
            'fields' => [
            ['key' => 'photo2', 'type' => 'photo', 'index' => 1, 'label' => 'Photo'],
            [
            'key' => 'memory_text',
            'label' => 'Handwritten Note',
            'default' => '"I still remember that smile."',
            'hint' => 'Sits in a narrow column beside the photo, so it stays short.',
            ],
            ],
            ],
            [
            'title' => 'Polaroids',
            'fields' => [
            ['key' => 'polaroid_label', 'label' => 'Page Title', 'default' => 'A few of my favorites'],
            ['key' => 'photo3', 'type' => 'photo', 'index' => 2, 'label' => 'Polaroid 1'],
            ['key' => 'note1', 'label' => 'Polaroid 1 Caption', 'default' => 'us :)'],
            ['key' => 'photo4', 'type' => 'photo', 'index' => 3, 'label' => 'Polaroid 2'],
            ['key' => 'note2', 'label' => 'Polaroid 2 Caption', 'default' => 'that day'],
            ['key' => 'photo5', 'type' => 'photo', 'index' => 4, 'label' => 'Polaroid 3'],
            ['key' => 'note3', 'label' => 'Polaroid 3 Caption', 'default' => 'forever'],
            ],
            ],
            [
            'title' => 'The Letter',
            'fields' => [
            ['key' => 'letter_label', 'label' => 'Page Title', 'default' => 'A letter for you'],
            [
            'key' => 'letter',
            'type' => 'textarea',
            'label' => 'Letter',
            'default' => "Dear Love,\n\nThank you for making every ordinary day feel special.\n\nI love you.",
            'rows' => 6,
            'maxLines' => $gift3LetterLines,
            'hint' => 'Fits the letter paper — up to ' . $gift3LetterLines . ' lines. Longer notes are set in a smaller hand automatically.',
            ],
            ['key' => 'envelope_hint', 'label' => 'Envelope Hint', 'default' => 'Tap the envelope'],
            ],
            ],
            [
            'title' => 'Special Dates',
            'fields' => [
            ['key' => 'dates_label', 'label' => 'Page Title', 'default' => 'Special Dates'],
            ['key' => 'date1_name', 'label' => 'Date 1 Title', 'default' => 'First Meet'],
            ['key' => 'date1_value', 'type' => 'date', 'label' => 'Date 1'],
            ['key' => 'date2_name', 'label' => 'Date 2 Title', 'default' => 'First Call'],
            ['key' => 'date2_value', 'type' => 'date', 'label' => 'Date 2'],
            ['key' => 'date3_name', 'label' => 'Date 3 Title', 'default' => 'First Date'],
            ['key' => 'date3_value', 'type' => 'date', 'label' => 'Date 3'],
            ['key' => 'date4_name', 'label' => 'Date 4 Title', 'default' => 'Anniversary'],
            ['key' => 'date4_value', 'type' => 'date', 'label' => 'Date 4'],
            ],
            ],
            [
            'title' => 'Future Dreams',
            'fields' => [
            ['key' => 'dreams_label', 'label' => 'Page Title', 'default' => 'Future Dreams'],
            [
            'type' => 'dreams',
            'label' => 'Dreams',
            'defaults' => [
            ['text' => 'First Coffee', 'done' => true],
            ['text' => 'First Selfie', 'done' => true],
            ['text' => 'First Trip', 'done' => true],
            ['text' => 'Grow Old Together', 'done' => false],
            ],
            ],
            ],
            ],
            [
            'title' => 'Favourite Quote',
            'fields' => [
            [
            'key' => 'quote',
            'type' => 'textarea',
            'label' => 'Quote',
            'default' => 'Every love story is beautiful, but ours is my favorite.',
            'rows' => 3,
            'hint' => 'Written out by hand on the page, a letter at a time.',
            ],
            ],
            ],
            [
            'title' => 'The Secret Page',
            'fields' => [
            ['key' => 'secret_label', 'label' => 'Ribbon Hint', 'default' => 'Pull the ribbon down'],
            ['key' => 'secret_button', 'label' => 'Button Label', 'default' => 'Click to Open'],
            ['key' => 'secret_message', 'label' => 'Hidden Message', 'default' => 'You found the secret page ❤'],
            ],
            ],
            [
            'title' => 'The Last Page',
            'fields' => [
            ['key' => 'final_line1', 'label' => 'Closing Line 1', 'default' => "This isn't the end..."],
            ['key' => 'final_line2', 'label' => 'Closing Line 2', 'default' => "It's just the beginning."],
            ['key' => 'replay_label', 'label' => 'Replay Button', 'default' => 'Replay Story'],
            ['key' => 'close_label', 'label' => 'Close Button', 'default' => 'Close the Book'],
            ],
            ],
            ];
            $gift3PageCount = count($gift3Pages);
            @endphp
            <div class="step-panel" id="panel7">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📖</div>
                        <div class="card-title">
                            <h3 id="gift3Heading">Gift 3 — Our Story Book</h3>
                            <p id="gift3Subheading">Pick a style, then fill the book one page at a time</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="variant-section visible" style="margin-top:0;">
                            <h4>Choose a Theme</h4>
                            <div class="variant-grid" id="gift3ThemeGrid">
                                @for ($n = 1; $n <= 4; $n++)
                                    <div class="variant-choice" id="gift3Theme{{ $n }}"
                                    onclick="selectGiftTheme(3,{{ $n }})">
                                    <div class="variant-check">✓</div>
                                    <div class="variant-thumb gift3-thumb">
                                        <iframe id="gift3ThemeFrame{{ $n }}" tabindex="-1"></iframe>
                                    </div>
                                    <div class="variant-label">Theme {{ $n }}</div>
                            </div>
                            @endfor
                        </div>
                        <p class="step7-error" id="step7Error"
                            style="display:none; color:#dc2626; font-size:0.82rem; margin-top:1rem;">
                            Please choose a theme to continue.</p>
                    </div>

                    <div class="gift3-field-row" id="gift3BoySide">
                        <div class="book-step-dots" id="bookStepDots">
                            @foreach ($gift3Pages as $i => $bookPage)
                            <span onclick="goToBookPage({{ $i + 1 }})" title="{{ $bookPage['title'] }}"></span>
                            @endforeach
                        </div>

                        @foreach ($gift3Pages as $i => $bookPage)
                        <div class="book-page-panel" id="bookPage{{ $i + 1 }}">
                            <div class="book-step-head">
                                <h4>{{ $bookPage['title'] }}</h4>
                                <span class="book-step-count">Book page {{ $i + 1 }} of
                                    {{ $gift3PageCount }}</span>
                            </div>

                            @foreach ($bookPage['fields'] as $field)
                            @php
                            $type = $field['type'] ?? 'text';
                            $key = $field['key'] ?? null;
                            $limit = $key ? ($gift3Limits[$key] ?? null) : null;
                            $inputId = $key ? 'gift3_' . $key : null;
                            @endphp

                            @if ($type === 'photo')
                            <div class="form-group">
                                <label>{{ $field['label'] }} <span style="color:#dc2626">*</span></label>
                                <div class="image-slots book-photo-slots">
                                    <div class="image-slot" id="gift3Slot{{ $field['index'] }}"
                                        onclick="document.getElementById('gift3PhotoInput{{ $field['index'] }}').click()">
                                        <img class="slot-preview" id="gift3PhotoPreview{{ $field['index'] }}"
                                            alt="">
                                        <span class="slot-plus">+<span>{{ $field['label'] }}</span></span>
                                    </div>
                                </div>
                                <input type="file" id="gift3PhotoInput{{ $field['index'] }}" accept="image/*"
                                    style="display:none"
                                    onchange="onGiftPhotoSelected('gift3',{{ $field['index'] }},this)">
                            </div>

                            @elseif ($type === 'date')
                            <div class="form-group">
                                <label>{{ $field['label'] }}</label>
                                <input type="date" id="{{ $inputId }}" oninput="updateGift3LivePreview()">
                                <p class="field-hint">Optional — shown beside the title on the page.</p>
                            </div>

                            @elseif ($type === 'dreams')
                            <div class="form-group">
                                <label>{{ $field['label'] }}</label>
                                <div id="dreamRows">
                                    @foreach ($field['defaults'] as $d => $dream)
                                    @php $n = $d + 1; @endphp
                                    <div class="checklist-row dream-row" id="dreamRow{{ $n }}">
                                        <input type="text" id="gift3_dream{{ $n }}"
                                            maxlength="{{ $gift3Limits['dream' . $n] }}"
                                            value="{{ $dream['text'] }}" oninput="updateGift3LivePreview()">
                                        <label class="done-toggle">
                                            <input type="checkbox" id="gift3_dream{{ $n }}_done"
                                                @checked($dream['done']) onchange="updateGift3LivePreview(true)">
                                            Ticked
                                        </label>
                                        <button type="button" class="dream-remove"
                                            onclick="removeDream({{ $n }})" title="Remove this dream"
                                            aria-label="Remove dream {{ $n }}">✕</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="dream-add" id="dreamAddBtn"
                                    onclick="addDream()">+ Add a dream</button>
                                <p class="field-hint" id="dreamHint"></p>
                            </div>

                            @elseif ($type === 'textarea')
                            <div class="form-group">
                                <div class="field-head">
                                    <label>{{ $field['label'] }}</label>
                                    <span class="char-count" data-for="{{ $inputId }}"></span>
                                </div>
                                <textarea id="{{ $inputId }}" rows="{{ $field['rows'] ?? 5 }}"
                                    maxlength="{{ $limit }}"
                                    @isset($field['maxLines']) data-max-lines="{{ $field['maxLines'] }}" @endisset
                                    oninput="updateGift3LivePreview()">{{ $field['default'] }}</textarea>
                                @isset($field['hint'])
                                <p class="field-hint">{{ $field['hint'] }}</p>
                                @endisset
                            </div>

                            @else
                            <div class="form-group">
                                <div class="field-head">
                                    <label>{{ $field['label'] }}</label>
                                    <span class="char-count" data-for="{{ $inputId }}"></span>
                                </div>
                                <input type="text" id="{{ $inputId }}" maxlength="{{ $limit }}"
                                    value="{{ $field['default'] }}"
                                    placeholder="{{ $field['placeholder'] ?? $field['default'] }}"
                                    oninput="updateGift3LivePreview()">
                                @isset($field['hint'])
                                <p class="field-hint">{{ $field['hint'] }}</p>
                                @endisset
                            </div>
                            @endif

                            @endforeach
                        </div>
                        @endforeach

                        <div class="book-page-nav">
                            <button class="btn-prev" id="gift3PrevPageBtn"
                                onclick="goToBookPage(currentBookPage - 1)">← Previous page</button>
                            <button class="btn-next" id="gift3NextPageBtn"
                                onclick="goToBookPage(currentBookPage + 1)">Next page →</button>
                        </div>
                    </div>

                    <!-- ── Girl Gift 3: the camera roll, card by card ── -->
                    @php
                    $g3GirlLimits = \App\Http\Controllers\Client\BirthdayCardController::GIFT3_GIRL_TEXT_LIMITS;
                    $g3GirlLetterLines = \App\Http\Controllers\Client\BirthdayCardController::GIFT3_GIRL_LETTER_MAX_LINES;
                    $g3GirlVideoMaxKb = \App\Http\Controllers\Client\BirthdayCardController::GIFT3_GIRL_VIDEO_MAX_KB;

                    // The girl Gift 3 is a phone with a camera roll on it, scrolled
                    // through card by card — photos, a chat screen, two clips, a pinned
                    // note and a letter. Each card gets its own beat here, so the step
                    // stays a walk rather than a wall of thirty inputs.
                    //
                    // Photo slots follow GIFT3_GIRL_PHOTO_KEYS: 0-2 are the photo cards,
                    // 3-4 the two video posters.
                    $g3GirlBeats = [
                    [
                    'title' => 'The Cover',
                    'blurb' => 'What shows before the roll is opened',
                    'fields' => [
                    ['key' => 'cover_title', 'label' => 'Cover Title', 'default' => 'Our Camera Roll'],
                    ['key' => 'cover_sub', 'label' => 'Cover Line', 'default' => 'Every memory has a story.'],
                    ['key' => 'cover_tap', 'label' => 'Tap Hint', 'default' => 'Tap to Open'],
                    ['key' => 'gallery_title', 'label' => 'Gallery Heading', 'default' => 'Our Camera Roll'],
                    ],
                    ],
                    [
                    'title' => 'Image + Date',
                    'blurb' => 'The first card in the roll',
                    'fields' => [
                    ['type' => 'photo', 'index' => 0, 'label' => 'Photo'],
                    ['key' => 'p1_date', 'label' => 'Date', 'default' => 'March 14'],
                    ['key' => 'p1_place', 'label' => 'Place', 'default' => 'Rooftop Cafe'],
                    ['key' => 'p1_caption', 'label' => 'Caption', 'default' => 'The evening the sky turned gold.'],
                    ],
                    ],
                    [
                    'title' => 'The Video',
                    'blurb' => 'One clip, with the still that shows before it plays',
                    'fields' => [
                    ['type' => 'photo', 'index' => 1, 'label' => 'Cover Still'],
                    ['type' => 'video', 'index' => 0, 'label' => 'Video Clip'],
                    ['key' => 'v1_duration', 'label' => 'Length Badge', 'default' => '0:18'],
                    ['key' => 'v1_date', 'label' => 'Date', 'default' => 'April 2'],
                    ['key' => 'v1_place', 'label' => 'Place', 'default' => 'Home'],
                    ['key' => 'v1_caption', 'label' => 'Caption', 'default' => 'That laugh I never get tired of.'],
                    ],
                    ],
                    [
                    'title' => 'The Chat',
                    'blurb' => 'Upload a screenshot, or write the conversation out',
                    'fields' => [
                    ['type' => 'photo', 'index' => 2, 'label' => 'Chat Screenshot',
                    'hint' => 'Optional. With a screenshot the card shows it; without one the three lines below are drawn as the chat.'],
                    ['key' => 'chat_name', 'label' => 'Chat Name', 'default' => 'My Person'],
                    ['key' => 'chat1', 'label' => 'Their Message', 'default' => 'guess where I am rn 👀'],
                    ['key' => 'chat2', 'label' => 'Your Reply', 'default' => "no way. tell me you didn't"],
                    ['key' => 'chat3', 'label' => 'Their Message', 'default' => 'i did. saved us seats already 🎬'],
                    ['key' => 'chat_date', 'label' => 'Date', 'default' => 'April 9'],
                    ['key' => 'chat_caption', 'label' => 'Caption', 'default' => 'My favorite conversation.'],
                    ],
                    ],
                    [
                    'title' => 'Image',
                    'blurb' => 'The last photo card',
                    'fields' => [
                    ['type' => 'photo', 'index' => 3, 'label' => 'Photo'],
                    ['key' => 'p2_date', 'label' => 'Date', 'default' => 'May 21'],
                    ['key' => 'p2_place', 'label' => 'Place', 'default' => 'Coastline Drive'],
                    ['key' => 'p2_caption', 'label' => 'Caption', 'default' => 'Windows down, nowhere to be.'],
                    ],
                    ],
                    [
                    'title' => 'A Love Letter',
                    'blurb' => 'The last card — it opens full screen',
                    'fields' => [
                    ['key' => 'letter', 'type' => 'textarea', 'label' => 'Letter',
                    'default' => "Thank you for every smile.\n\nEvery photo here reminds me how lucky I am to have you.\n\nHere's to a thousand more memories, and to you — always, endlessly, you.\n\nHappy birthday. I love you.",
                    'rows' => 8, 'maxLines' => $g3GirlLetterLines,
                    'hint' => 'Written out by hand when the letter opens — up to ' . $g3GirlLetterLines . ' lines.'],
                    ['key' => 'signoff', 'label' => 'Sign-off', 'default' => '— with love'],
                    ],
                    ],
                    ];
                    $g3GirlBeatCount = count($g3GirlBeats);
                    @endphp
                    <div class="gift3-field-row" id="gift3GirlSide">
                        <div class="book-step-dots" id="gift3GirlDots">
                            @foreach ($g3GirlBeats as $i => $beat)
                            <span onclick="goToGift3GirlStep({{ $i + 1 }})" title="{{ $beat['title'] }}"></span>
                            @endforeach
                        </div>

                        @foreach ($g3GirlBeats as $i => $beat)
                        <div class="book-page-panel" id="gift3GirlStep{{ $i + 1 }}">
                            <div class="book-step-head">
                                <h4>{{ $beat['title'] }}</h4>
                                <span class="book-step-count">Card {{ $i + 1 }} of
                                    {{ $g3GirlBeatCount }}</span>
                            </div>
                            <p class="field-hint" style="margin:-0.4rem 0 1rem;">{{ $beat['blurb'] }}</p>

                            @foreach ($beat['fields'] as $field)
                            @php
                            $type = $field['type'] ?? 'text';
                            $key = $field['key'] ?? null;
                            $inputId = $key ? 'g3girl_' . $key : null;
                            @endphp

                            @if ($type === 'photo')
                            <div class="form-group">
                                <label>{{ $field['label'] }}</label>
                                <div class="image-slots book-photo-slots">
                                    <div class="image-slot" id="gift3GirlSlot{{ $field['index'] }}"
                                        onclick="document.getElementById('gift3PhotoInput{{ $field['index'] }}').click()">
                                        <img class="slot-preview"
                                            id="gift3GirlPhotoPreview{{ $field['index'] }}" alt="">
                                        <span class="slot-plus">+<span>{{ $field['label'] }}</span></span>
                                    </div>
                                </div>
                                @isset($field['hint'])
                                <p class="field-hint">{{ $field['hint'] }}</p>
                                @endisset
                            </div>

                            @elseif ($type === 'video')
                            <div class="form-group">
                                <label>{{ $field['label'] }}</label>
                                <div class="video-slot" id="gift3VideoSlot{{ $field['index'] }}"
                                    onclick="document.getElementById('gift3VideoInput{{ $field['index'] }}').click()">
                                    <video id="gift3VideoPreview{{ $field['index'] }}" muted playsinline></video>
                                    <span class="slot-plus">▶<span>Add a clip</span></span>
                                </div>
                                <input type="file" id="gift3VideoInput{{ $field['index'] }}"
                                    accept="video/mp4,video/webm,video/ogg,video/quicktime" style="display:none"
                                    onchange="onGiftVideoSelected({{ $field['index'] }},this)">
                                <p class="field-hint" id="gift3VideoHint">Plays when the card is tapped.
                                    Optional — without one the still is all the card shows. MP4, WebM, OGG
                                    or MOV, up to {{ round($g3GirlVideoMaxKb / 1024) }} MB.</p>
                                <p class="field-error" id="gift3VideoError" style="display:none;"></p>
                            </div>

                            @elseif ($type === 'textarea')
                            <div class="form-group">
                                <div class="field-head">
                                    <label>{{ $field['label'] }}</label>
                                    <span class="char-count" data-for="{{ $inputId }}"></span>
                                </div>
                                <textarea id="{{ $inputId }}" rows="{{ $field['rows'] ?? 4 }}"
                                    maxlength="{{ $g3GirlLimits[$key] }}"
                                    @isset($field['maxLines']) data-max-lines="{{ $field['maxLines'] }}" @endisset
                                    oninput="updateGift3LivePreview()">{{ $field['default'] }}</textarea>
                                @isset($field['hint'])
                                <p class="field-hint">{{ $field['hint'] }}</p>
                                @endisset
                            </div>

                            @else
                            <div class="form-group">
                                <div class="field-head">
                                    <label>{{ $field['label'] }}</label>
                                    <span class="char-count" data-for="{{ $inputId }}"></span>
                                </div>
                                <input type="text" id="{{ $inputId }}"
                                    maxlength="{{ $g3GirlLimits[$key] }}"
                                    value="{{ $field['default'] }}"
                                    placeholder="{{ $field['default'] }}"
                                    oninput="updateGift3LivePreview()">
                            </div>
                            @endif

                            @endforeach
                        </div>
                        @endforeach

                        <div class="book-page-nav">
                            <button class="btn-prev" id="gift3GirlPrevBtn"
                                onclick="goToGift3GirlStep(currentGift3GirlStep - 1)">← Previous card</button>
                            <button class="btn-next" id="gift3GirlNextBtn"
                                onclick="goToGift3GirlStep(currentGift3GirlStep + 1)">Next card →</button>
                        </div>
                    </div>

                    <div class="gift3-field-row">
                        <div class="live-preview-label">Live Preview</div>
                        <div class="live-page-preview gift3-preview">
                            <iframe id="gift3LivePreview" src="about:blank" tabindex="-1"></iframe>
                        </div>
                    </div>

                    <div class="step-nav">
                        <button class="btn-prev" onclick="prevStep()">← Back</button>
                        <button class="btn-next" id="step7ContinueBtn" style="display:none;"
                            onclick="saveStep7AndContinue()">Continue →</button>
                    </div>
                </div>
            </div>
            </div>

            <!-- ── STEP 8: Ending Page — the closing letter ── -->
            @php
            // The ending page is one screen, not a book, so its fields all
            // fit on a single panel. Defaults are the template's own text,
            // exactly as with the gift steps, so a client who changes
            // nothing still gets a finished page.
            $endingLimits = \App\Http\Controllers\Client\BirthdayCardController::ENDING_TEXT_LIMITS;
            $endingLetterLines = \App\Http\Controllers\Client\BirthdayCardController::ENDING_LETTER_MAX_LINES;
            $endingThemeMeta = \App\Http\Controllers\Client\BirthdayCardController::ENDING_THEMES;

            // The two endings are different designs, not one design in two
            // palettes: the boy's is an envelope that unfolds into a letter,
            // the girl's a flower that blooms into a round keepsake card. The
            // slots line up one for one, but their wording, their ceilings and
            // the half of the page a preview should show do not — so each side
            // brings its own, and the fields are relabelled when theme changes.
            $endingSideMeta = [
            'boy' => [
            'limits' => \App\Http\Controllers\Client\BirthdayCardController::ENDING_TEXT_LIMITS,
            'maxLines' => \App\Http\Controllers\Client\BirthdayCardController::ENDING_LETTER_MAX_LINES,
            'stage' => 'letter',
            'stageLabels' => ['Envelope', 'Letter'],
            'labels' => [
            'title' => 'Envelope Title',
            'subtitle' => 'Envelope Subtitle',
            'tap_label' => 'Tap Hint',
            'letter_heading' => 'Letter Heading',
            'letter' => 'The Letter',
            'signoff' => 'Sign-off',
            'end_label' => 'Closing Stamp',
            ],
            'defaults' => [
            'title' => 'One Last Thing',
            'subtitle' => 'Before you go, read this.',
            'tap_label' => 'Tap to Open',
            'letter_heading' => 'A Letter For You',
            'letter' => "Happy birthday, my love.\n\nWe reached the last page, but this isn't really the end — it's just where the words stop and the feeling keeps going.\n\nThank you for being exactly who you are — for every laugh, every little moment, every reason you give me to smile.\n\nHere's to you, and to every birthday still ahead of us.\n\nI love you, always.",
            'signoff' => '— always yours',
            'end_label' => 'The End',
            ],
            ],
            'girl' => [
            'limits' => \App\Http\Controllers\Client\BirthdayCardController::ENDING_GIRL_TEXT_LIMITS,
            'maxLines' => \App\Http\Controllers\Client\BirthdayCardController::ENDING_GIRL_LETTER_MAX_LINES,
            'stage' => 'note',
            'stageLabels' => ['Flower', 'Note'],
            'labels' => [
            'title' => 'Bloom Title',
            'subtitle' => 'Bloom Subtitle',
            'tap_label' => 'Tap Hint',
            'letter_heading' => 'Card Heading',
            'letter' => 'The Note',
            'signoff' => 'Sign-off',
            'end_label' => 'Closing Line',
            ],
            'defaults' => [
            'title' => 'One Last Bloom',
            'subtitle' => 'Something kept for the very end.',
            'tap_label' => 'Tap to Bloom',
            'letter_heading' => 'A Note For You',
            'letter' => "Every petal here is a day I got to spend with you.\n\nThank you for all of them.\n\nHappy birthday, my love.",
            'signoff' => '— always, me',
            'end_label' => 'The End',
            ],
            ],
            ];
            $endingDefaultLetter = "Happy birthday, my love.\n\nWe reached the last page, but this isn't really the end — it's just where the words stop and the feeling keeps going.\n\nThank you for being exactly who you are — for every laugh, every little moment, every reason you give me to smile.\n\nHere's to you, and to every birthday still ahead of us.\n\nI love you, always.";

            $endingFields = [
            ['key' => 'title', 'label' => 'Envelope Title', 'default' => 'One Last Thing'],
            ['key' => 'subtitle', 'label' => 'Envelope Subtitle', 'default' => 'Before you go, read this.'],
            ['key' => 'tap_label', 'label' => 'Tap Hint', 'default' => 'Tap to Open',
            'hint' => 'Uppercase, beside the tap icon — keep it short.'],
            ['key' => 'letter_heading', 'label' => 'Letter Heading', 'default' => 'A Letter For You'],
            ['key' => 'letter', 'type' => 'textarea', 'label' => 'The Letter',
            'default' => $endingDefaultLetter, 'rows' => 8, 'maxLines' => $endingLetterLines,
            'hint' => 'Written out by hand on the page, a letter at a time. Blank lines start a new paragraph.'],
            ['key' => 'signoff', 'label' => 'Sign-off', 'default' => '— always yours'],
            ['key' => 'end_label', 'label' => 'Closing Stamp', 'default' => 'The End'],
            ];
            @endphp
            <div class="step-panel" id="panel8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">💌</div>
                        <div class="card-title">
                            <h3>Ending Page</h3>
                            <p>The envelope that closes the story — pick a design and write the letter</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="endingBoySide">
                            <div class="variant-section visible" style="margin-top:0;">
                                <h4>Choose a Design</h4>
                                <div class="variant-grid" id="endingThemeGrid">
                                    @for ($n = 1; $n <= 4; $n++)
                                        <div class="variant-choice" id="endingTheme{{ $n }}"
                                        onclick="selectEndingTheme({{ $n }})">
                                        <div class="variant-check">✓</div>
                                        <div class="variant-thumb">
                                            <iframe id="endingThemeFrame{{ $n }}" tabindex="-1"></iframe>
                                        </div>
                                        <div class="variant-label" id="endingThemeName{{ $n }}">Design {{ $n }}</div>
                                        <div class="qr-blurb" id="endingThemeBlurb{{ $n }}"></div>
                                </div>
                                @endfor
                            </div>
                            <p class="step8-error" id="step8Error"
                                style="display:none; color:#dc2626; font-size:0.82rem; margin-top:1rem;">
                                Please choose a design to continue.</p>
                        </div>

                        <div class="gift3-field-row">
                            @foreach ($endingFields as $field)
                            @php
                            $type = $field['type'] ?? 'text';
                            $key = $field['key'];
                            $limit = $endingLimits[$key] ?? null;
                            $inputId = 'ending_' . $key;
                            @endphp
                            <div class="form-group">
                                <div class="field-head">
                                    <label>{{ $field['label'] }}</label>
                                    <span class="char-count" data-for="{{ $inputId }}"></span>
                                </div>
                                @if ($type === 'textarea')
                                <textarea id="{{ $inputId }}" rows="{{ $field['rows'] ?? 5 }}"
                                    maxlength="{{ $limit }}"
                                    @isset($field['maxLines']) data-max-lines="{{ $field['maxLines'] }}" @endisset
                                    oninput="updateEndingLivePreview()">{{ $field['default'] }}</textarea>
                                @else
                                <input type="text" id="{{ $inputId }}" maxlength="{{ $limit }}"
                                    value="{{ $field['default'] }}" placeholder="{{ $field['default'] }}"
                                    oninput="updateEndingLivePreview()">
                                @endif
                                @isset($field['hint'])
                                <p class="field-hint">{{ $field['hint'] }}</p>
                                @endisset
                            </div>
                            @endforeach
                        </div>

                        <div class="gift3-field-row">
                            <div class="live-preview-label">Live Preview</div>
                            {{-- The page opens on a closed envelope, so the letter — the field
                                     the client spends the most time on — is invisible by default.
                                     These two tabs pick which half of the page the preview shows. --}}
                            <div class="ending-preview-tabs" id="endingPreviewTabs">
                                <button type="button" class="active" id="endingStageCover"
                                    onclick="setEndingPreviewStage('cover')">Envelope</button>
                                <button type="button" id="endingStageLetter"
                                    onclick="setEndingPreviewStage('open')">Letter</button>
                            </div>
                            <div class="live-page-preview ending-preview">
                                <iframe id="endingLivePreview" src="about:blank" tabindex="-1"></iframe>
                            </div>
                        </div>
                    </div>

                    <div class="theme-placeholder" id="endingPlaceholder">
                        <p class="placeholder-note">
                            The girl ending designs are not wired up yet — they are coming in a
                            later update. The four designs below are the ones that will be
                            available, and everything else on this step already works the same way.
                        </p>
                        <div class="placeholder-grid" id="endingPlaceholderGrid"></div>
                    </div>

                    <div class="step-nav">
                        <button class="btn-prev" onclick="prevStep()">← Back</button>
                        <button class="btn-next" id="step8ContinueBtn"
                            onclick="saveStep8AndContinue()">Continue →</button>
                    </div>
                </div>
            </div>
            </div>

            <!-- ── STEP 9: QR Select — generate the link and the code ── -->
            @php
            $qrThemeMeta = \App\Http\Controllers\Client\BirthdayCardController::QR_THEMES;

            // Only the sides that are actually wired up are rendered, so the
            // page doesn't carry four unused QR images. When the girl designs
            // are switched on, their previews appear here automatically.
            $qrPreviewsBySide = [];
            foreach (array_keys($qrThemeMeta) as $side) {
            $sideThemes = \App\Http\Controllers\Client\BirthdayCardController::qrThemes($side);
            if (\App\Http\Controllers\Client\BirthdayCardController::themeSideIsAvailable($sideThemes)) {
            $qrPreviewsBySide[$side] = \App\Http\Controllers\Client\BirthdayCardController::qrPreviews(
            $side, $card->slug ?? null, 300
            );
            }
            }

            $shareUrl = \App\Http\Controllers\Client\BirthdayCardController::shareUrl($card->slug ?? null);
            $savedQrTheme = $card->qr_data['theme'] ?? null;
            $savedQrSvg = null;
            if ($savedQrTheme && $shareUrl) {
            $savedSide = \App\Http\Controllers\Client\BirthdayCardController::qrThemes($card->theme ?? 'boy');
            if (isset($savedSide[$savedQrTheme])) {
            $savedQrSvg = \App\Support\QrRenderer::svg($shareUrl, $savedSide[$savedQrTheme], 720);
            }
            }
            @endphp
            <div class="step-panel" id="panel9">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">▦</div>
                        <div class="card-title">
                            <h3>QR Select</h3>
                            <p>Pick a QR design, then generate the link and code to share</p>
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
                            <div class="gift-summary-card">
                                <div class="gi">✉️</div>
                                <h4>Ending</h4>
                                <p>Closing Letter</p>
                            </div>
                        </div>

                        <div id="qrBoySide">
                            <div class="variant-section visible">
                                <h4>Choose a QR Design</h4>
                                <div class="qr-grid" id="qrThemeGrid">
                                    @for ($n = 1; $n <= 4; $n++)
                                        <div class="variant-choice" id="qrTheme{{ $n }}"
                                        onclick="selectQrTheme({{ $n }})">
                                        <div class="variant-check">✓</div>
                                        <div class="qr-thumb">
                                            <img id="qrThemeImg{{ $n }}" alt="QR design {{ $n }}">
                                        </div>
                                        <div class="variant-label" id="qrThemeName{{ $n }}">Design {{ $n }}</div>
                                        <div class="qr-blurb" id="qrThemeBlurb{{ $n }}"></div>
                                </div>
                                @endfor
                            </div>
                            <p class="step9-error" id="step9Error"
                                style="display:none; color:#dc2626; font-size:0.82rem; margin-top:1rem;">
                                Please choose a QR design to continue.</p>
                        </div>

                        <div class="generate-section">
                            <button class="generate-btn" id="generateBtn" onclick="generateCard()">
                                🎂 Generate Link & QR
                            </button>

                            <div class="qr-result" id="qrResult">
                                <img class="qr-large" id="qrLarge" alt="QR code for your card">
                                <div class="url-box" id="urlBox">
                                    <div class="url-text" id="urlDisplay"></div>
                                    <button class="copy-btn" onclick="copyUrl()">Copy Link</button>
                                </div>
                                <div class="qr-actions">
                                    <button class="copy-btn" onclick="downloadQr('png')">Download PNG</button>
                                    <button class="copy-btn" onclick="downloadQr('svg')">Download SVG</button>
                                </div>
                                <p id="shareNote"
                                    style="margin-top:1.2rem; font-size:0.82rem; color:var(--text-muted);">
                                    🔒 Only someone who knows the lock code (DOB) can open this card
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="theme-placeholder" id="qrPlaceholder">
                        <p class="placeholder-note">
                            The girl QR designs are not wired up yet — they are coming in a later
                            update. The four designs below are the ones that will be available.
                        </p>
                        <div class="placeholder-grid" id="qrPlaceholderGrid"></div>
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
        const totalSteps = 9;
        let selectedTheme = 'girl';
        let selectedVariant = @json($card - > variant ?? null);
        const CARD_STEP1_URL = @json(route('client.card.step1'));
        const CARD_STEP2_URL = @json(route('client.card.step2'));
        const CARD_STEP3_URL = @json(route('client.card.step3'));
        const CARD_STEP4_URL = @json(route('client.card.step4'));
        const CARD_STEP5_URL = @json(route('client.card.step5'));
        const CARD_STEP6_URL = @json(route('client.card.step6'));
        const CARD_STEP7_URL = @json(route('client.card.step7'));
        const CARD_STEP8_URL = @json(route('client.card.step8'));
        const CARD_STEP9_URL = @json(route('client.card.step9'));
        const CSRF_TOKEN = @json(csrf_token());
        let step2ImageFile = null;
        // Resolves once the cropped photo has actually been produced.
        let step2CropPending = null;
        let step2ImageUrl = @json($card ? - > profile_image_path ? \Illuminate\ Support\ Facades\ Storage::url($card - > profile_image_path) : null);
        let selectedGiftVariant = @json($card - > gift_screen_variant ?? null);

        let selectedGift1Theme = @json($card - > gift1_data['theme'] ?? null);
        let selectedGift2Theme = @json($card - > gift2_data['theme'] ?? null);
        let selectedGift3Theme = @json($card - > gift3_data['theme'] ?? null);
        const giftPhotoFiles = {
            gift1: [null, null, null],
            gift2: [null, null, null, null],
            gift3: [null, null, null, null, null],
        };
        @php
        $gift1PhotoUrls = array_map(
            fn($p) => $p ? \Illuminate\ Support\ Facades\ Storage::url($p) : null,
            $card - > gift1_data['photos'] ?? [null, null, null]
        );
        $gift2PhotoUrls = array_map(
            fn($p) => $p ? \Illuminate\ Support\ Facades\ Storage::url($p) : null,
            $card - > gift2_data['photos'] ?? [null, null, null, null]
        );
        $gift3PhotoUrls = array_map(
            fn($p) => $p ? \Illuminate\ Support\ Facades\ Storage::url($p) : null,
            $card - > gift3_data['photos'] ?? [null, null, null, null, null]
        );
        @endphp
        const giftPhotoUrls = {
            gift1: @json($gift1PhotoUrls),
            gift2: @json($gift2PhotoUrls),
            gift3: @json($gift3PhotoUrls),
        };

        // Single source of truth for the Gift 3 field names — the save endpoint
        // validates against these same two lists, and the book templates read
        // them straight off the query string.
        const GIFT3_TEXT_KEYS = @json(\App\ Http\ Controllers\ Client\ BirthdayCardController::gift3TextKeys());
        const GIFT3_DATE_KEYS = @json(\App\ Http\ Controllers\ Client\ BirthdayCardController::GIFT3_DATE_KEYS);
        const GIFT3_FLAG_KEYS = @json(\App\ Http\ Controllers\ Client\ BirthdayCardController::GIFT3_FLAG_KEYS);
        const GIFT3_MIN_DREAMS = {
            {
                $gift3MinDreams
            }
        };
        const GIFT3_MAX_DREAMS = {
            {
                $gift3MaxDreams
            }
        };
        let dreamCount = GIFT3_MAX_DREAMS;
        const GIFT3_SAVED = @json($card - > gift3_data ?? null);
        const TOTAL_BOOK_PAGES = {
            {
                $gift3PageCount ?? 10
            }
        };
        let currentBookPage = 1;

        // The girl Gift 2 scene is walked one beat at a time — see the panel markup.
        const GIFT2_GIRL_KEYS = @json(array_keys(\App\ Http\ Controllers\ Client\ BirthdayCardController::GIFT2_GIRL_LIMITS));
        const TOTAL_GIFT2_GIRL_STEPS = {
            {
                $gift2GirlStepCount ?? 5
            }
        };
        let currentGift2GirlStep = 1;

        // The girl Gift 3 camera roll, likewise — one card at a time.
        const GIFT3_GIRL_KEYS = @json(\App\ Http\ Controllers\ Client\ BirthdayCardController::gift3GirlTextKeys());
        const GIFT3_GIRL_PHOTO_KEYS = @json(\App\ Http\ Controllers\ Client\ BirthdayCardController::GIFT3_GIRL_PHOTO_KEYS);
        const GIFT3_GIRL_VIDEO_KEYS = @json(\App\ Http\ Controllers\ Client\ BirthdayCardController::GIFT3_GIRL_VIDEO_KEYS);
        const TOTAL_GIFT3_GIRL_STEPS = {
            {
                $g3GirlBeatCount ?? 9
            }
        };
        let currentGift3GirlStep = 1;

        // Clips are uploads like the photos, just much larger ones.
        const GIFT3_VIDEO_MAX_KB = {
            {
                \
                App\ Http\ Controllers\ Client\ BirthdayCardController::GIFT3_GIRL_VIDEO_MAX_KB
            }
        };
        const CARD_GIFT3_VIDEO_URL = @json(route('client.card.gift3-video'));
        const giftVideoFiles = {
            gift3: [null]
        };
        @php
        $gift3VideoUrls = array_map(
            fn($v) => $v ? \Illuminate\ Support\ Facades\ Storage::url($v) : null,
            $card - > gift3_data['videos'] ?? [null]
        );
        @endphp
        const giftVideoUrls = {
            gift3: @json($gift3VideoUrls)
        };
        let gift3VideoUploading = false;

        function closePopup() {
            document.getElementById('paymentPopup').style.display = 'none';
        }

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

            // Whichever step just became visible may hold thumbs/previews that
            // were left at scale(0) from a previous step change (see the guard
            // in scaleVariantThumbs) — recompute now that they have real layout.
            requestAnimationFrame(scaleVariantThumbs);

            if (currentStep === 2) {
                updateStep2LivePreview();
            }

            if (currentStep === 3) {
                ensureStep3Defaults();
                updateStep3LivePreview(true);
            }

            if (currentStep === 4) {
                loadGiftVariantThumbs();
            }

            if (currentStep === 5) {
                loadGiftThemeThumbs(1);
                updateGift1FieldsVisibility();
                updateGift1LivePreview(true);
            }

            if (currentStep === 6) {
                loadGiftThemeThumbs(2);
                updateGift2FieldsVisibility();
                updateGift2LivePreview(true);
            }

            if (currentStep === 7) {
                loadGiftThemeThumbs(3);
                updateGift3FieldsVisibility();
            }

            if (currentStep === 8) {
                updateEndingAvailability();
                loadEndingThemeThumbs();
                updateEndingLivePreview(true);
            }

            if (currentStep === 9) {
                updateQrAvailability();
            }

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

        const PAGE1_ROUTES = {
            boy: {
                1: @json(route('boy.page.variant', ['page' => 1, 'variant' => 1])),
                2: @json(route('boy.page.variant', ['page' => 1, 'variant' => 2])),
            },
            girl: {
                1: @json(route('girl.page.variant', ['page' => 1, 'variant' => 1])),
                2: @json(route('girl.page.variant', ['page' => 1, 'variant' => 2])),
            },
        };

        // ── Step 3: welcome screen (heading + message) ──────────
        const PAGE2_ROUTES = {
            boy: {
                1: @json(route('boy.page.variant', ['page' => 2, 'variant' => 1])),
                2: @json(route('boy.page.variant', ['page' => 2, 'variant' => 2])),
            },
            girl: {
                1: @json(route('girl.page.variant', ['page' => 2, 'variant' => 1])),
                2: @json(route('girl.page.variant', ['page' => 2, 'variant' => 2])),
            },
        };

        const STEP3_DEFAULTS = {
            boy: {
                1: {
                    heading: 'Happy Birthday My Love',
                    message: 'You are my favourite person in this world.\nToday is all yours, my king. 🕯️'
                },
                2: {
                    heading: 'Happy Birthday King',
                    message: 'You are the main character today.\nEverything is built around your moment ✨'
                },
            },
            girl: {
                1: {
                    heading: 'Happy Birthday My Love',
                    message: 'You make every day feel like a celebration.\nToday is all about you, my love. 🌸'
                },
                2: {
                    heading: 'Happy Birthday Princess',
                    message: 'In this dark little universe,\nyou are the softest light in my life ✨'
                },
            },
        };

        // ── Step 4: gift-box screen design ───────────────────────
        const PAGE3_ROUTES = {
            boy: {
                1: @json(route('boy.page.variant', ['page' => 3, 'variant' => 1])),
                2: @json(route('boy.page.variant', ['page' => 3, 'variant' => 2])),
            },
            girl: {
                1: @json(route('girl.page.variant', ['page' => 3, 'variant' => 1])),
                2: @json(route('girl.page.variant', ['page' => 3, 'variant' => 2])),
            },
        };

        let giftVariantThumbsTheme = null;

        function selectTheme(t) {
            selectedTheme = t;
            selectedVariant = null;
            document.getElementById('boyCard').classList.toggle('selected', t === 'boy');
            document.getElementById('girlCard').classList.toggle('selected', t === 'girl');
            setGlobalTheme(t);

            document.getElementById('boyVariants').classList.toggle('visible', t === 'boy');
            document.getElementById('girlVariants').classList.toggle('visible', t === 'girl');
            document.querySelectorAll('.variant-choice').forEach(el => el.classList.remove('selected'));
            document.getElementById('step1Error').style.display = 'none';

            // Lazy-load only the thumbs for the chosen theme (first time), then
            // measure once layout settles. Loading all 4 real pages up front —
            // even while hidden — was causing a visible "blink" on web when the
            // section toggled to display:block, since browsers repaint iframes
            // that were still rendering in the background as soon as they're shown.
            loadVariantThumbs(t === 'boy' ? 'boyVariants' : 'girlVariants');
            requestAnimationFrame(scaleVariantThumbs);

            // Gifts 1 and 2 are shaped differently per side, and Steps 8 and 9 are
            // boy-only so far — switching theme has to re-shape all four.
            updateGift1FieldsVisibility();
            updateGift2FieldsVisibility();
            updateGift3FieldsVisibility();
            endingThumbsLoadedFor = null;
            updateEndingAvailability();
            updateQrAvailability();
        }

        function loadVariantThumbs(sectionId) {
            document.querySelectorAll('#' + sectionId + ' .variant-thumb iframe').forEach(iframe => {
                if (!iframe.src && iframe.dataset.src) {
                    iframe.addEventListener('load', () => iframe.classList.add('loaded'));
                    iframe.src = iframe.dataset.src;
                }
            });
        }

        function selectVariant(n) {
            selectedVariant = n;
            const prefix = selectedTheme === 'boy' ? 'boyVariant' : 'girlVariant';
            document.querySelectorAll('#' + (selectedTheme === 'boy' ? 'boyVariants' : 'girlVariants') + ' .variant-choice')
                .forEach(el => el.classList.remove('selected'));
            document.getElementById(prefix + n).classList.add('selected');
            document.getElementById('step1Error').style.display = 'none';
            updateStep2LivePreview();
        }

        // ── Scale any fixed-desktop-width iframe (900px) down to fit
        // its actual container size — used by Step 1 thumbs AND the
        // Step 2 live preview, so both always show the full design
        // shrunk to fit, on any screen size (mobile or web). ───────
        const THUMB_IFRAME_WIDTH = 900;

        function scaleVariantThumbs() {
            document.querySelectorAll('.variant-thumb, .live-page-preview').forEach(box => {
                const iframe = box.querySelector('iframe');
                if (!iframe) return;
                // Boxes on an inactive step (display:none) report 0 width. Skip
                // them instead of zeroing their scale — this ran on every step
                // change (via updateStep2LivePreview's own recalibration), so
                // switching to Step 2 was silently collapsing Step 1's already
                // -correct thumbs to scale(0), leaving them blank until a full
                // page refresh recomputed everything from scratch.
                if (box.clientWidth === 0) return;
                // Each iframe scales against its own CSS-defined width (usually
                // THUMB_IFRAME_WIDTH, but the gift-screen thumbs render wider —
                // see .gift-variant-thumb iframe — so they land past the real
                // page's own 1024px breakpoint and show its desktop image
                // instead of the mobile CSS fallback).
                const iframeWidth = iframe.offsetWidth || THUMB_IFRAME_WIDTH;
                const scale = box.clientWidth / iframeWidth;
                iframe.style.transform = 'scale(' + scale + ')';
            });
        }

        window.addEventListener('resize', scaleVariantThumbs);

        // ── Step 2: live preview of the selected real page ─────
        function updateStep2LivePreview() {
            const frame = document.getElementById('step2LivePreview');
            if (!frame || !selectedTheme || !selectedVariant) return;
            const base = PAGE1_ROUTES[selectedTheme][selectedVariant];
            frame.src = step2ImageUrl ? (base + '?photo=' + encodeURIComponent(step2ImageUrl)) : base;
            requestAnimationFrame(scaleVariantThumbs);
        }

        // ── Step 3: fill the heading/message inputs with the design's
        // default copy — but only if the client hasn't typed anything yet,
        // so we never clobber their own words or a saved draft. ─────
        function ensureStep3Defaults() {
            if (!selectedTheme || !selectedVariant) return;
            const d = STEP3_DEFAULTS[selectedTheme][selectedVariant];
            const headingEl = document.getElementById('step3Heading');
            const messageEl = document.getElementById('step3Message');
            if (!headingEl.value) headingEl.value = d.heading;
            if (!messageEl.value) messageEl.value = d.message;
        }

        // ── Step 3: live preview of the real welcome-screen page, updated
        // as the client types (debounced so every keystroke doesn't force
        // a full iframe reload). ─────────────────────────────────
        let step3DebounceTimer = null;

        function updateStep3LivePreview(immediate) {
            const frame = document.getElementById('step3LivePreview');
            if (!frame || !selectedTheme || !selectedVariant) return;
            clearTimeout(step3DebounceTimer);
            const render = () => {
                const base = PAGE2_ROUTES[selectedTheme][selectedVariant];
                const params = new URLSearchParams({
                    heading: document.getElementById('step3Heading').value,
                    message: document.getElementById('step3Message').value,
                });
                frame.src = base + '?' + params.toString();
                requestAnimationFrame(scaleVariantThumbs);
            };
            if (immediate) render();
            else step3DebounceTimer = setTimeout(render, 250);
        }

        function saveStep3AndContinue() {
            const btn = document.getElementById('step3ContinueBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            fetch(CARD_STEP3_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({
                        heading: document.getElementById('step3Heading').value,
                        message: document.getElementById('step3Message').value,
                    }),
                })
                .then(res => {
                    if (!res.ok) throw new Error('Save failed');
                    return res.json();
                })
                .then(() => {
                    nextStep();
                })
                .catch(() => {
                    const errorEl = document.getElementById('step3Error');
                    errorEl.textContent = 'Could not save your details. Please try again.';
                    errorEl.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        // ── Step 4: gift-box screen design picker (2 real-page thumbs,
        // same lazy-load + fade-in pattern as the Step 1 variant thumbs). ──
        function loadGiftVariantThumbs() {
            if (!selectedTheme) return;
            if (giftVariantThumbsTheme === selectedTheme) {
                requestAnimationFrame(scaleVariantThumbs);
                return;
            }
            giftVariantThumbsTheme = selectedTheme;
            [1, 2].forEach(n => {
                const iframe = document.getElementById('giftVariantFrame' + n);
                iframe.classList.remove('loaded');
                iframe.addEventListener('load', () => iframe.classList.add('loaded'), {
                    once: true
                });
                iframe.src = PAGE3_ROUTES[selectedTheme][n];
            });
            requestAnimationFrame(scaleVariantThumbs);
        }

        function selectGiftVariant(n) {
            selectedGiftVariant = n;
            document.querySelectorAll('#giftVariant1, #giftVariant2').forEach(el => el.classList.remove('selected'));
            document.getElementById('giftVariant' + n).classList.add('selected');
            document.getElementById('step4Error').style.display = 'none';
        }

        function saveStep4AndContinue() {
            if (!selectedGiftVariant) {
                document.getElementById('step4Error').style.display = 'block';
                return;
            }

            const btn = document.getElementById('step4ContinueBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            fetch(CARD_STEP4_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({
                        gift_screen_variant: selectedGiftVariant,
                    }),
                })
                .then(res => {
                    if (!res.ok) throw new Error('Save failed');
                    return res.json();
                })
                .then(() => {
                    nextStep();
                })
                .catch(() => {
                    const errorEl = document.getElementById('step4Error');
                    errorEl.textContent = 'Could not save your selection. Please try again.';
                    errorEl.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        // ── Step 5 / Step 6: Gift 1 & Gift 2 theme pickers ──────
        // Real published pages, same lazy-load-once + fade-in pattern as
        // Step 4's design thumbs. URL shape: /{theme}/page/3/{giftVariant}/gift/{giftNum}/{themePage}
        function giftPageUrl(giftNum, themePage) {
            if (!selectedTheme || !selectedGiftVariant) return null;
            return '/' + selectedTheme + '/page/3/' + selectedGiftVariant + '/gift/' + giftNum + '/' + themePage;
        }

        // Per-gift memo of the theme+gift-variant the thumbs were last built for,
        // so revisiting a step doesn't reload four iframes for nothing.
        const giftThemeLoadedKeys = {
            1: null,
            2: null,
            3: null
        };

        function loadGiftThemeThumbs(giftNum) {
            if (!selectedTheme || !selectedGiftVariant) return;
            const key = selectedTheme + '-' + selectedGiftVariant;
            if (giftThemeLoadedKeys[giftNum] === key) {
                requestAnimationFrame(scaleVariantThumbs);
                return;
            }
            giftThemeLoadedKeys[giftNum] = key;

            for (let n = 1; n <= 4; n++) {
                const iframe = document.getElementById('gift' + giftNum + 'ThemeFrame' + n);
                if (!iframe) continue;
                iframe.classList.remove('loaded');
                iframe.addEventListener('load', () => iframe.classList.add('loaded'), {
                    once: true
                });
                iframe.src = giftPageUrl(giftNum, n);
            }
            requestAnimationFrame(scaleVariantThumbs);
        }

        function selectGiftTheme(giftNum, n) {
            if (giftNum === 1) selectedGift1Theme = n;
            else if (giftNum === 2) selectedGift2Theme = n;
            else selectedGift3Theme = n;

            document.querySelectorAll('#gift' + giftNum + 'ThemeGrid .variant-choice').forEach(el => el.classList
                .remove('selected'));
            document.getElementById('gift' + giftNum + 'Theme' + n).classList.add('selected');
            // Gift 1 is step 5, Gift 2 step 6, Gift 3 step 7.
            document.getElementById('step' + (giftNum + 4) + 'Error').style.display = 'none';

            if (giftNum === 1) updateGift1LivePreview(true);
            else if (giftNum === 2) updateGift2LivePreview(true);
            else updateGift3LivePreview(true);
        }

        // ── Step 5 / Step 6: photo upload slots ─────────────────
        function onGiftPhotoSelected(giftKey, index, inputEl) {
            const file = inputEl.files[0];
            if (!file) return;

            giftPhotoFiles[giftKey][index] = file;
            if (giftPhotoUrls[giftKey][index] && giftPhotoUrls[giftKey][index].startsWith('blob:')) {
                URL.revokeObjectURL(giftPhotoUrls[giftKey][index]);
            }
            const url = URL.createObjectURL(file);
            giftPhotoUrls[giftKey][index] = url;

            const slot = document.getElementById(giftKey + 'Slot' + index) || document.getElementById(giftKey +
                'Slot' + index + 'Wrap');
            const img = document.getElementById(giftKey + 'PhotoPreview' + index);
            img.src = url;
            slot.classList.add('filled');

            // Gift 2's girl side shows the same photo in its own step panel, so
            // both slots have to follow the same file input.
            mirrorGiftPhotoSlot(giftKey, index, url);
            if (giftKey === 'gift3') mirrorGift3PhotoSlot(index, url);

            if (giftKey === 'gift1') updateGift1LivePreview();
            else if (giftKey === 'gift2') updateGift2LivePreview();
            else updateGift3LivePreview();
        }

        // The girl Gift 2 panels carry their own slot for each photo; keeping it in
        // step here means neither side has to know the other exists.
        function mirrorGiftPhotoSlot(giftKey, index, url) {
            const mirror = document.getElementById(giftKey + 'GirlSlot' + index);
            const mirrorImg = document.getElementById(giftKey + 'GirlPhotoPreview' + index);
            if (!mirror || !mirrorImg) return;
            mirrorImg.src = url;
            mirror.classList.add('filled');
        }

        // ── Girl calendars ──────────────────────────────────────────
        // The girl Gift 1 design prints a month name over a grid of that month's
        // days and marks one of them, all from a single date picker. The public
        // story derives the same three values in PHP.
        const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        function girlCalendarParams(dateValue) {
            if (!dateValue) return null;
            const date = new Date(dateValue + 'T00:00:00');
            if (isNaN(date)) return null;
            return {
                cal_month: MONTH_NAMES[date.getMonth()],
                cal_day: String(date.getDate()),
                // Day 0 of the next month is the last day of this one.
                cal_days: String(new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate()),
            };
        }

        // ── Step 5: Gift 1 live preview + save ──────────────────
        let gift1DebounceTimer = null;

        // Gift 1 is a photo board on the boy side and a photo board plus a calendar
        // and a note on the girl side — so the extra two fields follow the theme,
        // the same way Step 6's do.
        function updateGift1FieldsVisibility() {
            const isGirl = selectedTheme === 'girl';
            document.querySelectorAll('.gift1-girl-only').forEach(el => {
                el.style.display = isGirl ? 'block' : 'none';
            });
        }

        function updateGift1LivePreview(immediate) {
            const frame = document.getElementById('gift1LivePreview');
            if (!frame || !selectedGift1Theme) return;
            clearTimeout(gift1DebounceTimer);
            const render = () => {
                const base = giftPageUrl(1, selectedGift1Theme);
                if (!base) return;
                const params = new URLSearchParams();
                giftPhotoUrls.gift1.forEach((url, i) => {
                    if (url) params.set('photo' + (i + 1), url);
                });
                if (selectedTheme === 'girl') {
                    const calendar = girlCalendarParams(document.getElementById('gift1CalDate').value);
                    if (calendar) Object.entries(calendar).forEach(([k, v]) => params.set(k, v));
                    const note = document.getElementById('gift1Message').value;
                    if (note) params.set('message', note);
                }
                frame.src = base + (params.toString() ? '?' + params.toString() : '');
                requestAnimationFrame(scaleVariantThumbs);
            };
            if (immediate) render();
            else gift1DebounceTimer = setTimeout(render, 250);
        }

        function saveStep5AndContinue() {
            if (!selectedGift1Theme) {
                document.getElementById('step5Error').style.display = 'block';
                return;
            }

            const btn = document.getElementById('step5ContinueBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            const formData = new FormData();
            formData.append('theme', selectedGift1Theme);
            giftPhotoFiles.gift1.forEach((file, i) => {
                if (file) formData.append('photos[' + i + ']', file);
            });
            formData.append('cal_date', document.getElementById('gift1CalDate').value);
            formData.append('message', document.getElementById('gift1Message').value);

            fetch(CARD_STEP5_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: formData,
                })
                .then(res => {
                    if (!res.ok) throw new Error('Save failed');
                    return res.json();
                })
                .then(data => {
                    (data.photo_urls || []).forEach((url, i) => {
                        if (!url) return;
                        if (giftPhotoUrls.gift1[i] && giftPhotoUrls.gift1[i].startsWith('blob:')) {
                            URL.revokeObjectURL(giftPhotoUrls.gift1[i]);
                        }
                        giftPhotoUrls.gift1[i] = url;
                        giftPhotoFiles.gift1[i] = null;
                    });
                    nextStep();
                })
                .catch(() => {
                    const errorEl = document.getElementById('step5Error');
                    errorEl.textContent = 'Could not save. Please try again.';
                    errorEl.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        // ── Step 6: Gift 2 live preview + save ──────────────────
        // Boy designs show names/calendar/signature; girl designs (as built)
        // don't use those fields — hide them instead of sending unused data.
        // The two Gift 2 designs are not the same screen with different colours:
        // the boy one is a tiled memory page, the girl one a wrapped box that opens
        // onto polaroids and an envelope. So each side gets its own editor — the
        // boy's single form, the girl's five-beat walk — and only one is ever shown.
        function updateGift2FieldsVisibility() {
            const isBoy = selectedTheme === 'boy';
            document.getElementById('gift2NamesRow').style.display = isBoy ? 'flex' : 'none';
            document.getElementById('gift2CalRow').style.display = isBoy ? 'block' : 'none';
            document.getElementById('gift2SignedGroup').style.display = isBoy ? 'block' : 'none';
            document.getElementById('gift2Slot3Wrap').style.display = isBoy ? 'flex' : 'none';
            document.getElementById('gift2BoySide').style.display = isBoy ? 'block' : 'none';
            document.getElementById('gift2GirlSide').style.display = isBoy ? 'none' : 'block';
            setGift2MessageLimit(isBoy);

            if (isBoy) {
                // The boy form is all on one panel, so Continue is always there.
                document.getElementById('step6ContinueBtn').style.display = '';
            } else {
                goToGift2GirlStep(currentGift2GirlStep);
            }
        }

        // One beat of the girl scene at a time, with Continue held back until the
        // last one so the whole thing gets walked through — the same shape as the
        // Gift 3 book's page-by-page panels.
        function goToGift2GirlStep(n) {
            if (n < 1 || n > TOTAL_GIFT2_GIRL_STEPS) return;

            const previous = document.getElementById('gift2GirlStep' + currentGift2GirlStep);
            if (previous) previous.classList.remove('active');
            currentGift2GirlStep = n;
            document.getElementById('gift2GirlStep' + n).classList.add('active');

            document.querySelectorAll('#gift2GirlDots span').forEach((dot, i) => {
                dot.classList.toggle('active', i === n - 1);
                dot.classList.toggle('done', i < n - 1);
            });

            const onLast = n === TOTAL_GIFT2_GIRL_STEPS;
            document.getElementById('gift2GirlPrevBtn').style.visibility = n === 1 ? 'hidden' : 'visible';
            document.getElementById('gift2GirlNextBtn').style.display = onLast ? 'none' : '';
            document.getElementById('step6ContinueBtn').style.display = onLast ? '' : 'none';

            updateGift2LivePreview(true);
        }

        // Both designs write to the same saved note, so the two boxes stay in step
        // and switching theme never loses what was typed.
        function onGift2MessageInput(el) {
            const other = el.id === 'gift2Message' ?
                document.getElementById('gift2GirlMessage') :
                document.getElementById('gift2Message');
            if (other && other.value !== el.value) {
                other.value = el.value;
                other.dispatchEvent(new Event('input', {
                    bubbles: false
                }));
            }
            updateGift2LivePreview();
        }

        function gift2MessageValue() {
            const el = selectedTheme === 'girl' ?
                document.getElementById('gift2GirlMessage') :
                document.getElementById('gift2Message');
            return el ? el.value : '';
        }

        // The note sits in a small fixed panel on the boy designs and on a taller
        // letter sheet on the girl ones, so its ceiling follows the theme.
        const GIFT2_MESSAGE_LIMITS = {
            boy: {
                {
                    \
                    App\ Http\ Controllers\ Client\ BirthdayCardController::GIFT2_LIMITS['message_boy']
                }
            },
            girl: {
                {
                    \
                    App\ Http\ Controllers\ Client\ BirthdayCardController::GIFT2_LIMITS['message_girl']
                }
            },
        };

        function setGift2MessageLimit(isBoy) {
            // The boy design drops the note into a small fixed panel; the girl one
            // writes it out on a taller letter sheet, so the ceiling follows the
            // design. Both boxes carry it, since they mirror each other.
            const limit = isBoy ? GIFT2_MESSAGE_LIMITS.boy : GIFT2_MESSAGE_LIMITS.girl;
            ['gift2Message', 'gift2GirlMessage'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.setAttribute('maxlength', limit);
                if (el.value.length > limit) el.value = el.value.slice(0, limit);
                el.dispatchEvent(new Event('input', {
                    bubbles: false
                }));
            });
        }

        let gift2DebounceTimer = null;

        function updateGift2LivePreview(immediate) {
            const frame = document.getElementById('gift2LivePreview');
            if (!frame || !selectedGift2Theme) return;
            clearTimeout(gift2DebounceTimer);
            const render = () => {
                const base = giftPageUrl(2, selectedGift2Theme);
                if (!base) return;
                const params = new URLSearchParams();
                giftPhotoUrls.gift2.forEach((url, i) => {
                    if (url) params.set('photo' + (i + 1), url);
                });
                const nameFirst = document.getElementById('gift2NameFirst').value;
                const nameSecond = document.getElementById('gift2NameSecond').value;
                const dateVal = document.getElementById('gift2CalDate').value;
                const message = gift2MessageValue();
                const signed = document.getElementById('gift2Signed').value;
                if (nameFirst) params.set('name_first', nameFirst);
                if (nameSecond) params.set('name_second', nameSecond);
                if (dateVal) params.set('cal_day', String(new Date(dateVal + 'T00:00:00').getDate()));
                if (message) params.set('message', message);
                if (signed) params.set('signed', signed);
                if (selectedTheme === 'girl') {
                    GIFT2_GIRL_KEYS.forEach(key => {
                        const el = document.getElementById('gift2_' + key);
                        if (el && el.value !== '') params.set(key, el.value);
                    });
                    // The scene opens on a wrapped box and only reveals the rest as
                    // it is tapped through, so the preview is fast-forwarded to
                    // whichever beat is being edited: 1 the box, 2-4 the photos,
                    // 5 the letter.
                    if (currentGift2GirlStep >= 5) params.set('preview_stage', 'letter');
                    else if (currentGift2GirlStep >= 2) params.set('preview_stage', 'photos');
                }
                frame.src = base + (params.toString() ? '?' + params.toString() : '');
                requestAnimationFrame(scaleVariantThumbs);
            };
            if (immediate) render();
            else gift2DebounceTimer = setTimeout(render, 250);
        }

        function saveStep6AndContinue() {
            if (!selectedGift2Theme) {
                document.getElementById('step6Error').style.display = 'block';
                return;
            }

            const btn = document.getElementById('step6ContinueBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            const dateVal = document.getElementById('gift2CalDate').value;

            const formData = new FormData();
            formData.append('theme', selectedGift2Theme);
            giftPhotoFiles.gift2.forEach((file, i) => {
                if (file) formData.append('photos[' + i + ']', file);
            });
            formData.append('name_first', document.getElementById('gift2NameFirst').value);
            formData.append('name_second', document.getElementById('gift2NameSecond').value);
            if (dateVal) formData.append('cal_date', dateVal);
            formData.append('message', gift2MessageValue());
            GIFT2_GIRL_KEYS.forEach(key => {
                const el = document.getElementById('gift2_' + key);
                if (el) formData.append(key, el.value);
            });
            formData.append('signed', document.getElementById('gift2Signed').value);

            fetch(CARD_STEP6_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: formData,
                })
                .then(res => {
                    if (!res.ok) throw new Error('Save failed');
                    return res.json();
                })
                .then(data => {
                    (data.photo_urls || []).forEach((url, i) => {
                        if (!url) return;
                        if (giftPhotoUrls.gift2[i] && giftPhotoUrls.gift2[i].startsWith('blob:')) {
                            URL.revokeObjectURL(giftPhotoUrls.gift2[i]);
                        }
                        giftPhotoUrls.gift2[i] = url;
                        giftPhotoFiles.gift2[i] = null;
                    });
                    nextStep();
                })
                .catch(() => {
                    const errorEl = document.getElementById('step6Error');
                    errorEl.textContent = 'Could not save. Please try again.';
                    errorEl.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        // ── Step 7: Gift 3 — the "Our Story" book ───────────────
        // The book is 10 pages long, so its fields are walked one book page at a
        // time and the live preview jumps to whichever page is being edited
        // (?preview_page=N opens the book straight to that page).
        function goToBookPage(n) {
            if (n < 1 || n > TOTAL_BOOK_PAGES) return;

            const previous = document.getElementById('bookPage' + currentBookPage);
            if (previous) previous.classList.remove('active');
            currentBookPage = n;
            document.getElementById('bookPage' + currentBookPage).classList.add('active');

            document.querySelectorAll('#bookStepDots span').forEach((dot, i) => {
                dot.classList.toggle('active', i === n - 1);
                dot.classList.toggle('done', i < n - 1);
            });

            const onLastPage = n === TOTAL_BOOK_PAGES;
            document.getElementById('gift3PrevPageBtn').style.visibility = n === 1 ? 'hidden' : 'visible';
            document.getElementById('gift3NextPageBtn').style.display = onLastPage ? 'none' : '';
            // Continue only appears once the whole book has been walked through.
            document.getElementById('step7ContinueBtn').style.display = onLastPage ? '' : 'none';

            updateGift3LivePreview(true);
        }

        function gift3Params() {
            const params = new URLSearchParams();
            giftPhotoUrls.gift3.forEach((url, i) => {
                if (url) params.set('photo' + (i + 1), url);
            });
            GIFT3_TEXT_KEYS.forEach(key => {
                const el = document.getElementById('gift3_' + key);
                if (el && el.value !== '') params.set(key, el.value);
            });
            GIFT3_DATE_KEYS.forEach(key => {
                const el = document.getElementById('gift3_' + key);
                if (el && el.value !== '') params.set(key, el.value);
            });
            GIFT3_FLAG_KEYS.forEach(key => {
                const el = document.getElementById('gift3_' + key);
                if (el) params.set(key, el.checked ? '1' : '0');
            });
            params.set('dream_count', String(dreamCount));
            return params;
        }

        // ── Step 7: the Future Dreams list (3 or 4 items) ───────
        function renderDreams() {
            for (let d = 1; d <= GIFT3_MAX_DREAMS; d++) {
                const row = document.getElementById('dreamRow' + d);
                if (row) row.style.display = d <= dreamCount ? 'flex' : 'none';
            }
            // Removing is only offered above the minimum, so the client can never
            // empty the page out.
            const canRemove = dreamCount > GIFT3_MIN_DREAMS;
            document.querySelectorAll('.dream-remove').forEach(btn => {
                btn.style.display = canRemove ? '' : 'none';
            });
            const addBtn = document.getElementById('dreamAddBtn');
            if (addBtn) addBtn.style.display = dreamCount < GIFT3_MAX_DREAMS ? '' : 'none';
            const hint = document.getElementById('dreamHint');
            if (hint) {
                hint.textContent = dreamCount >= GIFT3_MAX_DREAMS ?
                    'The page holds ' + GIFT3_MAX_DREAMS + ' dreams at most.' :
                    'You can add one more — ' + GIFT3_MIN_DREAMS + ' is the minimum.';
            }
        }

        function addDream() {
            if (dreamCount >= GIFT3_MAX_DREAMS) return;
            dreamCount++;
            renderDreams();
            updateGift3LivePreview(true);
        }

        function removeDream(n) {
            if (dreamCount <= GIFT3_MIN_DREAMS) return;
            // Shift the rows below up, so removing the 2nd of 4 doesn't leave a
            // hole in the middle of the list.
            for (let i = n; i < dreamCount; i++) {
                const here = document.getElementById('gift3_dream' + i);
                const next = document.getElementById('gift3_dream' + (i + 1));
                here.value = next.value;
                document.getElementById('gift3_dream' + i + '_done').checked =
                    document.getElementById('gift3_dream' + (i + 1) + '_done').checked;
            }
            dreamCount--;
            renderDreams();
            updateGift3LivePreview(true);
        }

        // A 422 response's body is { message, errors: { field: [msg, ...] } } —
        // pull the first message out so the client sees why the save failed
        // instead of a generic "try again".
        function firstValidationError(body) {
            if (!body) return null;
            if (body.errors) {
                const firstKey = Object.keys(body.errors)[0];
                if (firstKey && body.errors[firstKey][0]) return body.errors[firstKey][0];
            }
            return body.message || null;
        }

        // ── Design-safe field limits ────────────────────────────
        // Each card design is a fixed layout, so every field carries the
        // maxlength its own slot can actually show. The counter makes that
        // budget visible instead of the field just stopping dead.
        function wireCharCounters(root) {
            (root || document).querySelectorAll('.char-count[data-for]').forEach(counter => {
                const el = document.getElementById(counter.getAttribute('data-for'));
                if (!el || el.dataset.counterWired) return;
                const max = parseInt(el.getAttribute('maxlength'), 10);
                if (!max) return;
                el.dataset.counterWired = '1';
                const update = () => {
                    counter.textContent = el.value.length + '/' + max;
                    counter.classList.toggle('at-limit', el.value.length >= max);
                };
                el.addEventListener('input', update);
                update();
            });
        }

        // The letter is the one field where characters alone don't bound the
        // height, so extra line breaks are trimmed as they're typed.
        function wireLineLimits(root) {
            (root || document).querySelectorAll('textarea[data-max-lines]').forEach(el => {
                if (el.dataset.lineLimitWired) return;
                el.dataset.lineLimitWired = '1';
                const max = parseInt(el.dataset.maxLines, 10);
                el.addEventListener('input', () => {
                    const lines = el.value.split('\n');
                    if (lines.length > max) {
                        el.value = lines.slice(0, max).join('\n');
                    }
                });
            });
        }

        // ── Step 7: the girl camera roll ────────────────────────
        // The clip is checked for size here and then sent on its own, so an
        // oversized file is refused with a plain message instead of the server
        // answering 413 to the whole step, and a good one never has to share a
        // request with four photos and thirty text fields.
        function onGiftVideoSelected(index, inputEl) {
            const file = inputEl.files[0];
            const errorEl = document.getElementById('gift3VideoError');
            errorEl.style.display = 'none';
            if (!file) return;

            const maxBytes = GIFT3_VIDEO_MAX_KB * 1024;
            if (file.size > maxBytes) {
                inputEl.value = '';
                errorEl.textContent = 'That clip is ' + (file.size / 1048576).toFixed(1) +
                    ' MB. The limit is ' + Math.round(GIFT3_VIDEO_MAX_KB / 1024) +
                    ' MB — please trim it or pick a smaller one.';
                errorEl.style.display = 'block';
                return;
            }

            const slot = document.getElementById('gift3VideoSlot' + index);
            const video = document.getElementById('gift3VideoPreview' + index);
            const localUrl = URL.createObjectURL(file);
            video.src = localUrl;
            slot.classList.add('filled', 'uploading');
            gift3VideoUploading = true;

            const body = new FormData();
            body.append('video', file);

            fetch(CARD_GIFT3_VIDEO_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: body,
                })
                .then(res => res.json().then(data => ({
                    ok: res.ok,
                    status: res.status,
                    data
                })))
                .then(({
                    ok,
                    status,
                    data
                }) => {
                    if (!ok) {
                        const message = status === 413 ?
                            'That clip is larger than the server will accept. The limit is ' +
                            Math.round(GIFT3_VIDEO_MAX_KB / 1024) + ' MB.' :
                            (data && data.errors && data.errors.video ? data.errors.video[0] :
                                'That clip could not be uploaded. Please try another one.');
                        throw new Error(message);
                    }
                    giftVideoUrls.gift3[index] = data.video_url;
                    giftVideoFiles.gift3[index] = null;
                    video.src = data.video_url;
                    updateGift3LivePreview(true);
                })
                .catch(err => {
                    slot.classList.remove('filled');
                    video.removeAttribute('src');
                    giftVideoUrls.gift3[index] = null;
                    errorEl.textContent = err.message || 'That clip could not be uploaded.';
                    errorEl.style.display = 'block';
                })
                .finally(() => {
                    slot.classList.remove('uploading');
                    gift3VideoUploading = false;
                    URL.revokeObjectURL(localUrl);
                    inputEl.value = '';
                });
        }

        function mirrorGift3PhotoSlot(index, url) {
            const slot = document.getElementById('gift3GirlSlot' + index);
            const img = document.getElementById('gift3GirlPhotoPreview' + index);
            if (!slot || !img) return;
            img.src = url;
            slot.classList.add('filled');
        }

        function goToGift3GirlStep(n) {
            if (n < 1 || n > TOTAL_GIFT3_GIRL_STEPS) return;

            const previous = document.getElementById('gift3GirlStep' + currentGift3GirlStep);
            if (previous) previous.classList.remove('active');
            currentGift3GirlStep = n;
            document.getElementById('gift3GirlStep' + n).classList.add('active');

            document.querySelectorAll('#gift3GirlDots span').forEach((dot, i) => {
                dot.classList.toggle('active', i === n - 1);
                dot.classList.toggle('done', i < n - 1);
            });

            const onLast = n === TOTAL_GIFT3_GIRL_STEPS;
            document.getElementById('gift3GirlPrevBtn').style.visibility = n === 1 ? 'hidden' : 'visible';
            document.getElementById('gift3GirlNextBtn').style.display = onLast ? 'none' : '';
            document.getElementById('step7ContinueBtn').style.display = onLast ? '' : 'none';

            updateGift3LivePreview(true);
        }

        // Whichever side is live gets its own editor; the other stays hidden.
        // The two Gift 3 designs are different objects — a story book and a phone
        // full of memories — so the step names itself after whichever is showing.
        function updateGift3FieldsVisibility() {
            const isGirl = selectedTheme === 'girl';
            document.getElementById('gift3BoySide').style.display = isGirl ? 'none' : '';
            document.getElementById('gift3GirlSide').style.display = isGirl ? '' : 'none';

            document.getElementById('gift3Heading').textContent =
                isGirl ? 'Gift 3 — Our Camera Roll' : 'Gift 3 — Our Story Book';
            document.getElementById('gift3Subheading').textContent = isGirl ?
                'Pick a style, then fill the roll one card at a time' :
                'Pick a style, then fill the book one page at a time';
            document.getElementById('gift3StepSub').textContent =
                isGirl ? 'Camera roll, card by card' : 'Story book, page by page';
            document.getElementById('endingStepSub').textContent =
                isGirl ? 'The closing bloom' : 'The closing letter';

            if (isGirl) {
                goToGift3GirlStep(currentGift3GirlStep);
            } else {
                goToBookPage(currentBookPage);
            }
        }

        function gift3GirlParams() {
            const params = new URLSearchParams();
            // Slots 0-2 are the photo cards, 3-4 the two video posters.
            GIFT3_GIRL_PHOTO_KEYS.forEach((key, i) => {
                if (giftPhotoUrls.gift3[i]) params.set(key, giftPhotoUrls.gift3[i]);
            });
            GIFT3_GIRL_VIDEO_KEYS.forEach((key, i) => {
                if (giftVideoUrls.gift3[i]) params.set(key, giftVideoUrls.gift3[i]);
            });
            GIFT3_GIRL_KEYS.forEach(key => {
                const el = document.getElementById('g3girl_' + key);
                if (el && el.value !== '') params.set(key, el.value);
            });
            // Beat 1 is the cover; beats 2-9 are cards 0-7 of the roll. The roll
            // opens on its cover and stages cards in one at a time, so the preview
            // is told which card the client is actually working on.
            if (currentGift3GirlStep > 1) params.set('preview_card', String(currentGift3GirlStep - 2));
            return params;
        }

        let gift3DebounceTimer = null;

        function updateGift3LivePreview(immediate) {
            const frame = document.getElementById('gift3LivePreview');
            if (!frame || !selectedGift3Theme) return;
            clearTimeout(gift3DebounceTimer);
            const render = () => {
                const base = giftPageUrl(3, selectedGift3Theme);
                if (!base) return;
                let params;
                if (selectedTheme === 'girl') {
                    params = gift3GirlParams();
                } else {
                    params = gift3Params();
                    params.set('preview_page', String(currentBookPage));
                }
                frame.src = base + '?' + params.toString();
                requestAnimationFrame(scaleVariantThumbs);
            };
            if (immediate) render();
            else gift3DebounceTimer = setTimeout(render, 250);
        }

        function saveStep7AndContinue() {
            const errorEl = document.getElementById('step7Error');

            if (!selectedGift3Theme) {
                errorEl.textContent = 'Please choose a theme to continue.';
                errorEl.style.display = 'block';
                document.getElementById('gift3ThemeGrid').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            // All five images are required on both sides — the boy book lays out a
            // slot for each, and the girl roll needs three photos plus a still for
            // each clip. An empty one falls back to a placeholder.
            // The girl roll needs four images; the chat screenshot is optional
            // because the design draws the conversation when there isn't one.
            const required = selectedTheme === 'girl' ? [0, 1, 3] : [0, 1, 2, 3, 4];
            const missing = [];
            required.forEach(i => {
                if (!giftPhotoUrls.gift3[i] && !giftPhotoFiles.gift3[i]) missing.push(i + 1);
            });
            if (missing.length) {
                errorEl.textContent = 'Please add every photo before continuing (missing: photo ' +
                    missing.join(', ') + ').';
                errorEl.style.display = 'block';
                errorEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            if (gift3VideoUploading) {
                errorEl.textContent = 'The clip is still uploading — one moment.';
                errorEl.style.display = 'block';
                return;
            }

            errorEl.style.display = 'none';

            const btn = document.getElementById('step7ContinueBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            const formData = new FormData();
            formData.append('theme', selectedGift3Theme);
            giftPhotoFiles.gift3.forEach((file, i) => {
                if (file) formData.append('photos[' + i + ']', file);
            });

            if (selectedTheme === 'girl') {
                // The clip is already stored — it went up on its own.
                GIFT3_GIRL_KEYS.forEach(key => {
                    const el = document.getElementById('g3girl_' + key);
                    if (el) formData.append(key, el.value);
                });
            } else {
                GIFT3_TEXT_KEYS.forEach(key => {
                    const el = document.getElementById('gift3_' + key);
                    if (el) formData.append(key, el.value);
                });
                GIFT3_DATE_KEYS.forEach(key => {
                    const el = document.getElementById('gift3_' + key);
                    if (el) formData.append(key, el.value);
                });
                GIFT3_FLAG_KEYS.forEach(key => {
                    const el = document.getElementById('gift3_' + key);
                    if (el) formData.append(key, el.checked ? '1' : '0');
                });
                formData.append('dream_count', String(dreamCount));
            }

            fetch(CARD_STEP7_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: formData,
                })
                .then(res => {
                    if (!res.ok) throw new Error('Save failed');
                    return res.json();
                })
                .then(data => {
                    (data.photo_urls || []).forEach((url, i) => {
                        if (!url) return;
                        if (giftPhotoUrls.gift3[i] && giftPhotoUrls.gift3[i].startsWith('blob:')) {
                            URL.revokeObjectURL(giftPhotoUrls.gift3[i]);
                        }
                        giftPhotoUrls.gift3[i] = url;
                        giftPhotoFiles.gift3[i] = null;
                    });
                    (data.video_urls || []).forEach((url, i) => {
                        if (!url) return;
                        if (giftVideoUrls.gift3[i] && giftVideoUrls.gift3[i].startsWith('blob:')) {
                            URL.revokeObjectURL(giftVideoUrls.gift3[i]);
                        }
                        giftVideoUrls.gift3[i] = url;
                        giftVideoFiles.gift3[i] = null;
                    });
                    nextStep();
                })
                .catch(() => {
                    errorEl.textContent = 'Could not save. Please try again.';
                    errorEl.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }


        // ── Step 8 / Step 9: ending page + QR ───────────────────
        // The design tables live in the controller, so the dashboard, the save
        // endpoints and the templates all read one list. Each side carries an
        // `available` flag: boy is wired up, girl is not yet, and turning girl on
        // is a matter of flipping that flag rather than rebuilding these steps.
        const ENDING_THEME_META = @json($endingThemeMeta);
        const QR_THEME_META = @json($qrThemeMeta);
        const ENDING_TEXT_KEYS = @json(\App\ Http\ Controllers\ Client\ BirthdayCardController::endingTextKeys());
        const ENDING_SAVED = @json($card - > ending_data ?? null);
        const QR_PREVIEWS = @json($qrPreviewsBySide);
        const SHARE_URL = @json($shareUrl);
        const QR_SAVED_THEME = @json($savedQrTheme);
        const QR_SAVED_SVG = @json($savedQrSvg);
        let selectedEndingTheme = @json($card - > ending_data['theme'] ?? null);
        let selectedQrTheme = @json($card - > qr_data['theme'] ?? null);
        let endingPreviewStage = 'cover';
        const ENDING_SIDE_META = @json($endingSideMeta);
        let qrSvg = QR_SAVED_SVG;

        function themeSideHasDesigns(meta) {
            return Object.values(meta[selectedTheme] || {}).some(design => design.available);
        }

        // ── Step 8: the ending page ─────────────────────────────
        function endingPageUrl(design) {
            if (!selectedTheme) return null;
            return '/' + selectedTheme + '/page/4/' + design;
        }

        let endingThumbsLoadedFor = null;

        function loadEndingThemeThumbs() {
            if (!selectedTheme || !themeSideHasDesigns(ENDING_THEME_META)) return;
            if (endingThumbsLoadedFor === selectedTheme) {
                requestAnimationFrame(scaleVariantThumbs);
                return;
            }
            endingThumbsLoadedFor = selectedTheme;

            for (let n = 1; n <= 4; n++) {
                const iframe = document.getElementById('endingThemeFrame' + n);
                if (!iframe) continue;
                iframe.classList.remove('loaded');
                iframe.addEventListener('load', () => iframe.classList.add('loaded'), {
                    once: true
                });
                iframe.src = endingPageUrl(n);
            }
            requestAnimationFrame(scaleVariantThumbs);
        }

        // Names come off the meta table rather than being written into the markup,
        // so each side labels its own designs.
        function renderThemeNames(meta, idPrefix) {
            const designs = meta[selectedTheme] || meta.boy;
            Object.entries(designs).forEach(([n, design]) => {
                const name = document.getElementById(idPrefix + 'Name' + n);
                const blurb = document.getElementById(idPrefix + 'Blurb' + n);
                if (name) name.textContent = design.name;
                if (blurb) blurb.textContent = design.blurb;
            });
        }

        function renderPlaceholderGrid(meta, gridId) {
            const grid = document.getElementById(gridId);
            if (!grid) return;
            grid.innerHTML = '';
            Object.entries(meta[selectedTheme] || {}).forEach(([n, design]) => {
                const card = document.createElement('div');
                card.className = 'placeholder-card';
                card.innerHTML = '<span class="pi">🔒</span><h5></h5><p></p>';
                card.querySelector('h5').textContent = design.name;
                card.querySelector('p').textContent = design.blurb;
                grid.appendChild(card);
            });
        }

        function updateEndingAvailability() {
            const available = themeSideHasDesigns(ENDING_THEME_META);
            document.getElementById('endingBoySide').style.display = available ? '' : 'none';
            document.getElementById('step8ContinueBtn').style.display = available ? '' : 'none';
            document.getElementById('endingPlaceholder').classList.toggle('visible', !available);
            if (available) {
                renderThemeNames(ENDING_THEME_META, 'endingTheme');
                applyEndingSide();
            } else {
                renderPlaceholderGrid(ENDING_THEME_META, 'endingPlaceholderGrid');
            }
        }

        // The fields are one set of inputs serving two different designs, so their
        // labels, ceilings and placeholder text are swapped rather than duplicated.
        // A value the client actually typed is left alone; one that is still the
        // other side's default is replaced with this side's.
        function applyEndingSide() {
            const meta = ENDING_SIDE_META[selectedTheme] || ENDING_SIDE_META.boy;
            const other = ENDING_SIDE_META[selectedTheme === 'girl' ? 'boy' : 'girl'];

            ENDING_TEXT_KEYS.forEach(key => {
                const el = document.getElementById('ending_' + key);
                if (!el) return;

                const limit = meta.limits[key];
                if (limit) el.setAttribute('maxlength', limit);

                const label = el.closest('.form-group').querySelector('label');
                if (label) label.textContent = meta.labels[key];

                const fresh = el.value === '' || el.value === other.defaults[key];
                if (fresh) el.value = meta.defaults[key];
                el.setAttribute('placeholder', meta.defaults[key]);

                if (el.dataset.maxLines) el.dataset.maxLines = meta.maxLines;
                if (el.value.length > limit) el.value = el.value.slice(0, limit);
                el.dispatchEvent(new Event('input', {
                    bubbles: false
                }));
            });

            document.getElementById('endingStageCover').textContent = meta.stageLabels[0];
            document.getElementById('endingStageLetter').textContent = meta.stageLabels[1];
        }

        function selectEndingTheme(n) {
            selectedEndingTheme = n;
            document.querySelectorAll('#endingThemeGrid .variant-choice').forEach(el => el.classList.remove(
                'selected'));
            document.getElementById('endingTheme' + n).classList.add('selected');
            document.getElementById('step8Error').style.display = 'none';
            updateEndingLivePreview(true);
        }

        function endingParams() {
            const params = new URLSearchParams();
            ENDING_TEXT_KEYS.forEach(key => {
                const el = document.getElementById('ending_' + key);
                if (el && el.value !== '') params.set(key, el.value);
            });
            return params;
        }

        function setEndingPreviewStage(stage) {
            endingPreviewStage = stage;
            document.getElementById('endingStageCover').classList.toggle('active', stage === 'cover');
            document.getElementById('endingStageLetter').classList.toggle('active', stage === 'open');
            updateEndingLivePreview(true);
        }

        let endingDebounceTimer = null;

        function updateEndingLivePreview(immediate) {
            const frame = document.getElementById('endingLivePreview');
            if (!frame || !selectedEndingTheme) return;
            clearTimeout(endingDebounceTimer);
            const render = () => {
                const base = endingPageUrl(selectedEndingTheme);
                if (!base) return;
                const params = endingParams();
                if (endingPreviewStage === 'open') {
                    const meta = ENDING_SIDE_META[selectedTheme] || ENDING_SIDE_META.boy;
                    params.set('preview_stage', meta.stage);
                }
                frame.src = base + (params.toString() ? '?' + params.toString() : '');
                requestAnimationFrame(scaleVariantThumbs);
            };
            if (immediate) render();
            else endingDebounceTimer = setTimeout(render, 250);
        }

        function saveStep8AndContinue() {
            const errorEl = document.getElementById('step8Error');

            if (!selectedEndingTheme) {
                errorEl.textContent = 'Please choose a design to continue.';
                errorEl.style.display = 'block';
                document.getElementById('endingThemeGrid').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }
            errorEl.style.display = 'none';

            const btn = document.getElementById('step8ContinueBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            const formData = new FormData();
            formData.append('theme', selectedEndingTheme);
            ENDING_TEXT_KEYS.forEach(key => {
                const el = document.getElementById('ending_' + key);
                if (el) formData.append(key, el.value);
            });

            fetch(CARD_STEP8_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: formData,
                })
                .then(res => {
                    if (res.ok) return res.json();
                    return res.json()
                        .then(body => {
                            throw new Error(firstValidationError(body) || 'Save failed');
                        })
                        .catch(err => {
                            throw err instanceof Error ? err : new Error('Save failed');
                        });
                })
                .then(() => nextStep())
                .catch(err => {
                    errorEl.textContent = (err && err.message) ? err.message :
                        'Could not save. Please try again.';
                    errorEl.style.display = 'block';
                    errorEl.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        // ── Step 9: QR select ───────────────────────────────────
        function updateQrAvailability() {
            const available = themeSideHasDesigns(QR_THEME_META) && !!QR_PREVIEWS[selectedTheme];
            document.getElementById('qrBoySide').style.display = available ? '' : 'none';
            document.getElementById('qrPlaceholder').classList.toggle('visible', !available);
            if (!available) {
                renderPlaceholderGrid(QR_THEME_META, 'qrPlaceholderGrid');
                return;
            }
            renderThemeNames(QR_THEME_META, 'qrTheme');
            const previews = QR_PREVIEWS[selectedTheme];
            Object.entries(previews).forEach(([n, dataUri]) => {
                const img = document.getElementById('qrThemeImg' + n);
                if (img) img.src = dataUri;
            });
        }

        function selectQrTheme(n) {
            selectedQrTheme = n;
            document.querySelectorAll('#qrThemeGrid .variant-choice').forEach(el => el.classList.remove(
                'selected'));
            document.getElementById('qrTheme' + n).classList.add('selected');
            document.getElementById('step9Error').style.display = 'none';
        }

        function svgDataUri(svg) {
            // btoa() only takes latin1, and the QR label can carry anything.
            return 'data:image/svg+xml;base64,' + btoa(String.fromCharCode(...new TextEncoder().encode(svg)));
        }

        function showQrResult(url, svg) {
            qrSvg = svg;
            document.getElementById('qrLarge').src = svgDataUri(svg);
            document.getElementById('urlDisplay').textContent = url;
            document.getElementById('qrResult').classList.add('visible');
        }

        function generateCard() {
            const errorEl = document.getElementById('step9Error');

            if (!selectedQrTheme) {
                errorEl.textContent = 'Please choose a QR design to continue.';
                errorEl.style.display = 'block';
                document.getElementById('qrThemeGrid').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }
            errorEl.style.display = 'none';

            const btn = document.getElementById('generateBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Generating…';

            const formData = new FormData();
            formData.append('theme', selectedQrTheme);

            fetch(CARD_STEP9_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: formData,
                })
                .then(res => {
                    if (!res.ok) throw new Error('Generate failed');
                    return res.json();
                })
                .then(data => {
                    showQrResult(data.share_url, data.qr_svg);
                    document.getElementById('qrResult').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                })
                .catch(() => {
                    errorEl.textContent = 'Could not generate the code. Please try again.';
                    errorEl.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        function copyUrl() {
            const text = document.getElementById('urlDisplay').textContent;
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.querySelector('#urlBox .copy-btn');
                btn.textContent = 'Copied! ✓';
                setTimeout(() => btn.textContent = 'Copy Link', 2000);
            });
        }

        function saveBlob(blob, filename) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        }

        // The code is generated as SVG (the PNG writer needs the GD extension,
        // which the server doesn't have), so PNG is rasterised here from the same
        // markup — no second trip and no second source of truth.
        function downloadQr(format) {
            if (!qrSvg) return;
            const name = 'birthday-card-qr';

            if (format === 'svg') {
                saveBlob(new Blob([qrSvg], {
                    type: 'image/svg+xml'
                }), name + '.svg');
                return;
            }

            const img = new Image();
            img.onload = () => {
                const scale = 2;
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth * scale;
                canvas.height = img.naturalHeight * scale;
                const ctx = canvas.getContext('2d');
                ctx.scale(scale, scale);
                ctx.drawImage(img, 0, 0);
                canvas.toBlob(blob => saveBlob(blob, name + '.png'), 'image/png');
            };
            img.src = svgDataUri(qrSvg);
        }

        // ── Step 2: PIN input (single "DD-MM" field) ────────────
        function onPinDobInput(el) {
            let digits = el.value.replace(/[^0-9]/g, '').slice(0, 4);
            el.value = digits.length > 2 ? digits.slice(0, 2) + '-' + digits.slice(2) : digits;
        }

        function getPinValue() {
            return document.getElementById('pinDobInput').value.replace(/[^0-9]/g, '');
        }

        function setPinValue(pin) {
            const digits = (pin || '').replace(/[^0-9]/g, '').slice(0, 4);
            document.getElementById('pinDobInput').value =
                digits.length > 2 ? digits.slice(0, 2) + '-' + digits.slice(2) : digits;
        }

        // ── Step 2: photo upload + mirror-shaped drag/zoom crop ─
        // The crop box matches the real page's arch photo frame ratio (190:256).
        // The client drags/zooms the image inside it; on every change we render
        // a cropped canvas snapshot as the actual upload file + live-preview photo,
        // so what they see in the mirror box is exactly what ends up on the card.
        const MIRROR_RATIO = 190 / 256;
        let mirrorImgNatural = {
            w: 0,
            h: 0
        };
        let mirrorState = {
            scale: 1,
            offsetX: 0,
            offsetY: 0,
            minScale: 1
        };
        let mirrorDrag = null;

        function onStep2ImageSelected(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('mirrorCropImg');
                img.onload = () => {
                    mirrorImgNatural = {
                        w: img.naturalWidth,
                        h: img.naturalHeight
                    };
                    initMirrorCrop();
                    document.getElementById('mirrorCropWrap').style.display = 'block';
                    renderMirrorCrop();
                    // Export straight away. The crop was only produced on zoom or
                    // drag-end, so a client who picked a photo, left it centred and
                    // pressed Continue sent no photo at all.
                    exportMirrorCrop();
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function initMirrorCrop() {
            // Base scale so the image fully covers the mirror box (like object-fit: cover)
            const boxRatio = MIRROR_RATIO;
            const imgRatio = mirrorImgNatural.w / mirrorImgNatural.h;
            let baseW, baseH;
            if (imgRatio > boxRatio) {
                baseH = 256;
                baseW = baseH * imgRatio;
            } else {
                baseW = 190;
                baseH = baseW / imgRatio;
            }
            mirrorState.baseW = baseW;
            mirrorState.baseH = baseH;
            mirrorState.minScale = 1;
            mirrorState.scale = 1;
            mirrorState.offsetX = 0;
            mirrorState.offsetY = 0;
            document.getElementById('mirrorZoomSlider').value = 100;
        }

        function renderMirrorCrop() {
            const img = document.getElementById('mirrorCropImg');
            const box = document.getElementById('mirrorCropBox');
            const boxPxW = box.clientWidth || 160;
            const scaleToBox = boxPxW / 190; // crop box is laid out at 190-unit width internally

            const w = mirrorState.baseW * mirrorState.scale * scaleToBox;
            const h = mirrorState.baseH * mirrorState.scale * scaleToBox;
            img.style.width = w + 'px';
            img.style.height = h + 'px';
            img.style.transform = 'translate(calc(-50% + ' + mirrorState.offsetX + 'px), calc(-50% + ' +
                mirrorState.offsetY + 'px))';
        }

        function onMirrorZoomChange(val) {
            mirrorState.scale = val / 100;
            clampMirrorOffset();
            renderMirrorCrop();
            exportMirrorCrop();
        }

        function clampMirrorOffset() {
            const box = document.getElementById('mirrorCropBox');
            const boxPxW = box.clientWidth || 160;
            const boxPxH = box.clientHeight || (boxPxW * 256 / 190);
            const scaleToBox = boxPxW / 190;
            const w = mirrorState.baseW * mirrorState.scale * scaleToBox;
            const h = mirrorState.baseH * mirrorState.scale * scaleToBox;
            const maxX = Math.max(0, (w - boxPxW) / 2);
            const maxY = Math.max(0, (h - boxPxH) / 2);
            mirrorState.offsetX = Math.min(maxX, Math.max(-maxX, mirrorState.offsetX));
            mirrorState.offsetY = Math.min(maxY, Math.max(-maxY, mirrorState.offsetY));
        }

        function setupMirrorDrag() {
            const box = document.getElementById('mirrorCropBox');

            const start = (x, y) => {
                mirrorDrag = {
                    x,
                    y,
                    startOffsetX: mirrorState.offsetX,
                    startOffsetY: mirrorState.offsetY
                };
                box.classList.add('dragging');
            };
            const move = (x, y) => {
                if (!mirrorDrag) return;
                mirrorState.offsetX = mirrorDrag.startOffsetX + (x - mirrorDrag.x);
                mirrorState.offsetY = mirrorDrag.startOffsetY + (y - mirrorDrag.y);
                clampMirrorOffset();
                renderMirrorCrop();
            };
            const end = () => {
                if (!mirrorDrag) return;
                mirrorDrag = null;
                box.classList.remove('dragging');
                exportMirrorCrop();
            };

            box.addEventListener('mousedown', e => start(e.clientX, e.clientY));
            window.addEventListener('mousemove', e => move(e.clientX, e.clientY));
            window.addEventListener('mouseup', end);

            box.addEventListener('touchstart', e => {
                const t = e.touches[0];
                start(t.clientX, t.clientY);
            }, {
                passive: true
            });
            box.addEventListener('touchmove', e => {
                const t = e.touches[0];
                move(t.clientX, t.clientY);
            }, {
                passive: true
            });
            box.addEventListener('touchend', end);
        }

        function exportMirrorCrop() {
            const img = document.getElementById('mirrorCropImg');
            const box = document.getElementById('mirrorCropBox');
            const boxPxW = box.clientWidth || 160;
            const boxPxH = box.clientHeight || (boxPxW * 256 / 190);
            const scaleToBox = boxPxW / 190;

            // Output at the template's native 190x256 resolution for crisp results
            const outW = 190,
                outH = 256;
            const canvas = document.createElement('canvas');
            canvas.width = outW;
            canvas.height = outH;
            const ctx = canvas.getContext('2d');

            const drawW = mirrorState.baseW * mirrorState.scale;
            const drawH = mirrorState.baseH * mirrorState.scale;
            const drawX = outW / 2 - drawW / 2 + (mirrorState.offsetX / scaleToBox);
            const drawY = outH / 2 - drawH / 2 + (mirrorState.offsetY / scaleToBox);

            ctx.drawImage(img, drawX, drawY, drawW, drawH);

            // toBlob is asynchronous, so the export is handed back as a promise:
            // clicking Continue straight after picking a photo used to submit
            // before the crop existed, and the picture was silently dropped.
            step2CropPending = new Promise(resolve => {
                canvas.toBlob(blob => {
                    if (!blob) {
                        resolve();
                        return;
                    }
                    step2ImageFile = new File([blob], 'photo.jpg', {
                        type: 'image/jpeg'
                    });
                    if (step2ImageUrl && step2ImageUrl.startsWith('blob:')) {
                        URL.revokeObjectURL(step2ImageUrl);
                    }
                    step2ImageUrl = URL.createObjectURL(blob);
                    updateStep2LivePreview();
                    resolve();
                }, 'image/jpeg', 0.92);
            });

            return step2CropPending;
        }

        async function saveStep2AndContinue() {
            // Wait for any crop still being encoded, so the photo always goes up.
            if (step2CropPending) {
                await step2CropPending;
            }

            const pin = getPinValue();
            const errorEl = document.getElementById('step2Error');
            errorEl.style.display = 'none';

            if (pin.length !== 4) {
                errorEl.textContent = 'Please enter a full 4-digit PIN.';
                errorEl.style.display = 'block';
                return;
            }

            const btn = document.getElementById('step2ContinueBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            const formData = new FormData();
            formData.append('theme', selectedTheme);
            formData.append('variant', selectedVariant);
            formData.append('lock_code', pin);
            if (step2ImageFile) formData.append('photo', step2ImageFile);

            fetch(CARD_STEP2_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: formData,
                })
                .then(res => {
                    if (!res.ok) throw new Error('Save failed');
                    return res.json();
                })
                .then(data => {
                    if (data.profile_image_url) {
                        if (step2ImageUrl && step2ImageUrl.startsWith('blob:')) {
                            URL.revokeObjectURL(step2ImageUrl);
                        }
                        step2ImageUrl = data.profile_image_url;
                        step2ImageFile = null;
                    }
                    nextStep();
                })
                .catch(() => {
                    errorEl.textContent = 'Could not save your details. Please try again.';
                    errorEl.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }

        function saveStep1AndContinue() {
            if (!selectedTheme || !selectedVariant) {
                document.getElementById('step1Error').style.display = 'block';
                return;
            }

            const btn = document.getElementById('step1ContinueBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            fetch(CARD_STEP1_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({
                        theme: selectedTheme,
                        variant: selectedVariant,
                    }),
                })
                .then(res => {
                    if (!res.ok) throw new Error('Save failed');
                    return res.json();
                })
                .then(() => {
                    nextStep();
                })
                .catch(() => {
                    document.getElementById('step1Error').textContent =
                        'Could not save your selection. Please try again.';
                    document.getElementById('step1Error').style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
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
            document.querySelector('.generate-btn').style.background =
                t === 'boy' ?
                'linear-gradient(135deg, #4f8ef7, #90b9ff)' :
                'linear-gradient(135deg, #f76fa1, #ff9dcd)';
        }

        // Prevent scrolling when sidebar is open on mobile
        function preventScroll(e) {
            e.preventDefault();
        }

        window.addEventListener('load', function() {
            updateMenuButton();
            scaleVariantThumbs();
            setupMirrorDrag();

            const savedTheme = @json($card - > theme ?? null);
            if (savedTheme) {
                selectTheme(savedTheme);
                if (selectedVariant) {
                    selectVariant(selectedVariant);
                }
            }

            const savedPin = @json($card - > lock_code ?? null);
            if (savedPin) {
                setPinValue(savedPin);
            }

            const savedHeading = @json($card - > heading ?? null);
            const savedMessage = @json($card - > welcome_message ?? null);
            if (savedHeading) document.getElementById('step3Heading').value = savedHeading;
            if (savedMessage) document.getElementById('step3Message').value = savedMessage;

            if (selectedGiftVariant) {
                document.getElementById('giftVariant' + selectedGiftVariant).classList.add('selected');
            }

            if (selectedGift1Theme) {
                document.getElementById('gift1Theme' + selectedGift1Theme).classList.add('selected');
            }
            giftPhotoUrls.gift1.forEach((url, i) => {
                if (!url) return;
                document.getElementById('gift1PhotoPreview' + i).src = url;
                document.getElementById('gift1Slot' + i).classList.add('filled');
            });
            const savedGift1 = @json($card - > gift1_data ?? null);
            if (savedGift1) {
                if (savedGift1.cal_date) document.getElementById('gift1CalDate').value = savedGift1.cal_date;
                if (savedGift1.message) document.getElementById('gift1Message').value = savedGift1.message;
            }
            updateGift1FieldsVisibility();

            if (selectedGift2Theme) {
                document.getElementById('gift2Theme' + selectedGift2Theme).classList.add('selected');
            }
            giftPhotoUrls.gift2.forEach((url, i) => {
                if (!url) return;
                document.getElementById('gift2PhotoPreview' + i).src = url;
                const slot = document.getElementById('gift2Slot' + i) || document.getElementById('gift2Slot' + i +
                    'Wrap');
                slot.classList.add('filled');
                mirrorGiftPhotoSlot('gift2', i, url);
            });
            const savedGift2 = @json($card - > gift2_data ?? null);
            if (savedGift2) {
                if (savedGift2.name_first) document.getElementById('gift2NameFirst').value = savedGift2.name_first;
                if (savedGift2.name_second) document.getElementById('gift2NameSecond').value = savedGift2
                    .name_second;
                if (savedGift2.cal_date) document.getElementById('gift2CalDate').value = savedGift2.cal_date;
                if (savedGift2.message) {
                    // Both message boxes mirror one saved value.
                    document.getElementById('gift2Message').value = savedGift2.message;
                    document.getElementById('gift2GirlMessage').value = savedGift2.message;
                }
                if (savedGift2.signed) document.getElementById('gift2Signed').value = savedGift2.signed;
                GIFT2_GIRL_KEYS.forEach(key => {
                    const el = document.getElementById('gift2_' + key);
                    if (el && savedGift2[key] !== null && savedGift2[key] !== undefined) {
                        el.value = savedGift2[key];
                    }
                });
            }
            goToGift2GirlStep(1);

            updateGift2FieldsVisibility();

            if (selectedGift3Theme) {
                document.getElementById('gift3Theme' + selectedGift3Theme).classList.add('selected');
            }
            giftPhotoUrls.gift3.forEach((url, i) => {
                if (!url) return;
                document.getElementById('gift3PhotoPreview' + i).src = url;
                document.getElementById('gift3Slot' + i).classList.add('filled');
                mirrorGift3PhotoSlot(i, url);
            });
            giftVideoUrls.gift3.forEach((url, i) => {
                if (!url) return;
                const preview = document.getElementById('gift3VideoPreview' + i);
                const slot = document.getElementById('gift3VideoSlot' + i);
                if (!preview || !slot) return;
                preview.src = url;
                slot.classList.add('filled');
            });
            // Fields render with the book's own default text; anything the client
            // already saved overrides it here.
            if (GIFT3_SAVED) {
                GIFT3_TEXT_KEYS.concat(GIFT3_DATE_KEYS).forEach(key => {
                    const el = document.getElementById('gift3_' + key);
                    if (el && GIFT3_SAVED[key] !== null && GIFT3_SAVED[key] !== undefined) {
                        el.value = GIFT3_SAVED[key];
                    }
                });
                GIFT3_FLAG_KEYS.forEach(key => {
                    const el = document.getElementById('gift3_' + key);
                    if (el && GIFT3_SAVED[key] !== null && GIFT3_SAVED[key] !== undefined) {
                        el.checked = !!GIFT3_SAVED[key];
                    }
                });
                if (GIFT3_SAVED.dream_count) {
                    dreamCount = Math.min(GIFT3_MAX_DREAMS,
                        Math.max(GIFT3_MIN_DREAMS, parseInt(GIFT3_SAVED.dream_count, 10) || GIFT3_MAX_DREAMS));
                }
                GIFT3_GIRL_KEYS.forEach(key => {
                    const el = document.getElementById('g3girl_' + key);
                    if (el && GIFT3_SAVED[key] !== null && GIFT3_SAVED[key] !== undefined) {
                        el.value = GIFT3_SAVED[key];
                    }
                });
            }
            renderDreams();
            updateGift3FieldsVisibility();

            // ── Step 8: the ending page ──
            updateEndingAvailability();
            if (selectedEndingTheme) {
                const chosen = document.getElementById('endingTheme' + selectedEndingTheme);
                if (chosen) chosen.classList.add('selected');
            }
            // The fields render with the template's own text; anything already
            // saved overrides it, before the counters are wired so they count the
            // restored value rather than the default.
            if (ENDING_SAVED) {
                ENDING_TEXT_KEYS.forEach(key => {
                    const el = document.getElementById('ending_' + key);
                    if (el && ENDING_SAVED[key] !== null && ENDING_SAVED[key] !== undefined) {
                        el.value = ENDING_SAVED[key];
                    }
                });
            }

            // ── Step 9: QR select ──
            updateQrAvailability();
            if (selectedQrTheme) {
                const chosen = document.getElementById('qrTheme' + selectedQrTheme);
                if (chosen) chosen.classList.add('selected');
            }
            // A card that was already generated comes back with its link and code
            // in place, so reopening the dashboard doesn't hide the share link
            // behind the Generate button again.
            if (QR_SAVED_SVG && SHARE_URL) {
                showQrResult(SHARE_URL, QR_SAVED_SVG);
            }

            wireCharCounters(document);
            wireLineLimits(document);
            goToBookPage(1);
        });
    </script>
</body>

</html>