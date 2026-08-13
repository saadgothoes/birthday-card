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
                    <div class="step-label">Generate & Share</div>
                    <div class="step-sub">URL & QR code</div>
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
                                <div class="form-group">
                                    <label>Heading</label>
                                    <input type="text" placeholder="e.g. Happy Birthday My Love" id="step3Heading"
                                        oninput="updateStep3LivePreview()" />
                                </div>
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea placeholder="Write a heartfelt message…" id="step3Message"
                                        oninput="updateStep3LivePreview()"></textarea>
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

                        <div class="form-row gift2-field-row" id="gift2NamesRow">
                            <div class="form-group">
                                <label>Name 1</label>
                                <input type="text" id="gift2NameFirst" placeholder="e.g. Emma"
                                    oninput="updateGift2LivePreview()">
                            </div>
                            <div class="form-group">
                                <label>Name 2</label>
                                <input type="text" id="gift2NameSecond" placeholder="e.g. Lucas"
                                    oninput="updateGift2LivePreview()">
                            </div>
                        </div>

                        <div class="form-group gift2-field-row" id="gift2CalRow">
                            <label>Special Date</label>
                            <input type="date" id="gift2CalDate" oninput="updateGift2LivePreview()">
                        </div>

                        <div class="form-group gift2-field-row">
                            <label>Message</label>
                            <textarea id="gift2Message" placeholder="Write a heartfelt note…"
                                oninput="updateGift2LivePreview()"></textarea>
                        </div>

                        <div class="form-group gift2-field-row" id="gift2SignedGroup">
                            <label>Signed</label>
                            <input type="text" id="gift2Signed" placeholder="e.g. — always, E"
                                oninput="updateGift2LivePreview()">
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

            <!-- ── STEP 7: Generate ── -->
            <div class="step-panel" id="panel7">
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
    const totalSteps = 7;
    let selectedTheme = 'girl';
    let selectedVariant = @json($card->variant ?? null);
    const CARD_STEP1_URL = @json(route('client.card.step1'));
    const CARD_STEP2_URL = @json(route('client.card.step2'));
    const CARD_STEP3_URL = @json(route('client.card.step3'));
    const CARD_STEP4_URL = @json(route('client.card.step4'));
    const CARD_STEP5_URL = @json(route('client.card.step5'));
    const CARD_STEP6_URL = @json(route('client.card.step6'));
    const CSRF_TOKEN = @json(csrf_token());
    let step2ImageFile = null;
    let step2ImageUrl = @json($card?->profile_image_path ? \Illuminate\Support\Facades\Storage::url($card->profile_image_path) : null);
    let selectedGiftVariant = @json($card->gift_screen_variant ?? null);

    let selectedGift1Theme = @json($card->gift1_data['theme'] ?? null);
    let selectedGift2Theme = @json($card->gift2_data['theme'] ?? null);
    const giftPhotoFiles = {
        gift1: [null, null, null],
        gift2: [null, null, null, null],
    };
    @php
        $gift1PhotoUrls = array_map(
            fn ($p) => $p ? \Illuminate\Support\Facades\Storage::url($p) : null,
            $card->gift1_data['photos'] ?? [null, null, null]
        );
        $gift2PhotoUrls = array_map(
            fn ($p) => $p ? \Illuminate\Support\Facades\Storage::url($p) : null,
            $card->gift2_data['photos'] ?? [null, null, null, null]
        );
    @endphp
    const giftPhotoUrls = {
        gift1: @json($gift1PhotoUrls),
        gift2: @json($gift2PhotoUrls),
    };

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
            updateGift1LivePreview(true);
        }

        if (currentStep === 6) {
            loadGiftThemeThumbs(2);
            updateGift2FieldsVisibility();
            updateGift2LivePreview(true);
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

    let gift1ThemeLoadedKey = null;
    let gift2ThemeLoadedKey = null;

    function loadGiftThemeThumbs(giftNum) {
        if (!selectedTheme || !selectedGiftVariant) return;
        const key = selectedTheme + '-' + selectedGiftVariant;
        const isGift1 = giftNum === 1;
        if (isGift1 && gift1ThemeLoadedKey === key) {
            requestAnimationFrame(scaleVariantThumbs);
            return;
        }
        if (!isGift1 && gift2ThemeLoadedKey === key) {
            requestAnimationFrame(scaleVariantThumbs);
            return;
        }
        if (isGift1) gift1ThemeLoadedKey = key;
        else gift2ThemeLoadedKey = key;

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
        else selectedGift2Theme = n;

        document.querySelectorAll('#gift' + giftNum + 'ThemeGrid .variant-choice').forEach(el => el.classList
            .remove('selected'));
        document.getElementById('gift' + giftNum + 'Theme' + n).classList.add('selected');
        document.getElementById('step' + (giftNum === 1 ? 5 : 6) + 'Error').style.display = 'none';

        if (giftNum === 1) updateGift1LivePreview(true);
        else updateGift2LivePreview(true);
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

        if (giftKey === 'gift1') updateGift1LivePreview();
        else updateGift2LivePreview();
    }

    // ── Step 5: Gift 1 live preview + save ──────────────────
    let gift1DebounceTimer = null;

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
    function updateGift2FieldsVisibility() {
        const isBoy = selectedTheme === 'boy';
        document.getElementById('gift2NamesRow').style.display = isBoy ? 'flex' : 'none';
        document.getElementById('gift2CalRow').style.display = isBoy ? 'block' : 'none';
        document.getElementById('gift2SignedGroup').style.display = isBoy ? 'block' : 'none';
        document.getElementById('gift2Slot3Wrap').style.display = isBoy ? 'flex' : 'none';
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
            const message = document.getElementById('gift2Message').value;
            const signed = document.getElementById('gift2Signed').value;
            if (nameFirst) params.set('name_first', nameFirst);
            if (nameSecond) params.set('name_second', nameSecond);
            if (dateVal) params.set('cal_day', String(new Date(dateVal + 'T00:00:00').getDate()));
            if (message) params.set('message', message);
            if (signed) params.set('signed', signed);
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
        formData.append('message', document.getElementById('gift2Message').value);
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

        canvas.toBlob(blob => {
            if (!blob) return;
            step2ImageFile = new File([blob], 'photo.jpg', {
                type: 'image/jpeg'
            });
            if (step2ImageUrl && step2ImageUrl.startsWith('blob:')) {
                URL.revokeObjectURL(step2ImageUrl);
            }
            step2ImageUrl = URL.createObjectURL(blob);
            updateStep2LivePreview();
        }, 'image/jpeg', 0.92);
    }

    function saveStep2AndContinue() {
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

    function generateLockCode(dob) {
        if (!dob) return;
        const d = new Date(dob);
        const day = String(d.getDate()).padStart(2, '0');
        const mon = String(d.getMonth() + 1).padStart(2, '0');
        const yr = d.getFullYear();
        document.getElementById('lockCodeDisplay').textContent = day + mon + yr;
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
        scaleVariantThumbs();
        setupMirrorDrag();

        const savedTheme = @json($card->theme ?? null);
        if (savedTheme) {
            selectTheme(savedTheme);
            if (selectedVariant) {
                selectVariant(selectedVariant);
            }
        }

        const savedPin = @json($card->lock_code ?? null);
        if (savedPin) {
            setPinValue(savedPin);
        }

        const savedHeading = @json($card->heading ?? null);
        const savedMessage = @json($card->welcome_message ?? null);
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

        if (selectedGift2Theme) {
            document.getElementById('gift2Theme' + selectedGift2Theme).classList.add('selected');
        }
        giftPhotoUrls.gift2.forEach((url, i) => {
            if (!url) return;
            document.getElementById('gift2PhotoPreview' + i).src = url;
            const slot = document.getElementById('gift2Slot' + i) || document.getElementById('gift2Slot' + i +
                'Wrap');
            slot.classList.add('filled');
        });
        const savedGift2 = @json($card->gift2_data ?? null);
        if (savedGift2) {
            if (savedGift2.name_first) document.getElementById('gift2NameFirst').value = savedGift2.name_first;
            if (savedGift2.name_second) document.getElementById('gift2NameSecond').value = savedGift2
                .name_second;
            if (savedGift2.cal_date) document.getElementById('gift2CalDate').value = savedGift2.cal_date;
            if (savedGift2.message) document.getElementById('gift2Message').value = savedGift2.message;
            if (savedGift2.signed) document.getElementById('gift2Signed').value = savedGift2.signed;
        }

        updateGift2FieldsVisibility();
    });
    </script>
</body>

</html>