<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <title>Our Memory Board</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,500&family=Dancing+Script:wght@500;600;700&display=swap"
        rel="stylesheet">

    <style>
    /* =========================================================
       🎀  THEME FILE — yeh sirf ek jagah hai jahan sab colors
       define hain. Yahan se hi PORE CARD ka har bg/text/shadow
       color control hota hai. Sirf yeh block change karo,
       poora design naya theme le lega. Neeche kahin bhi
       hardcoded hex color use nahi hua — sab var() se aa raha hai.
       ========================================================= */
    :root {
        /* Background */
        --bg-dark-1: #1E3045;
        --bg-dark-2: #2D4663;
        --bg-dark-3: #101B28;

        /* Paper */
        --paper-light: #F9FCFF;
        --paper-mid: #E6F3FF;
        --paper-white: #FFFFFF;

        /* Accent */
        --accent-1: #72C7FF;
        --accent-2: #4EA7E8;
        --accent-3: #B8D8FF;
        --accent-gold: #EAF7FF;

        /* Text */
        --ink: #2E5677;
        --ink-soft: #5A7C9C;
        --text-on-accent: #FFFFFF;

        /* RGB */
        --bgdark1-rgb: 30, 48, 69;
        --bgdark2-rgb: 45, 70, 99;
        --paper-rgb: 249, 252, 255;
        --accent1-rgb: 114, 199, 255;
        --accent2-rgb: 78, 167, 232;
        --accent3-rgb: 184, 216, 255;
        --shadow-rgb: 0, 0, 0;

        --font-display: 'Playfair Display', serif;
        --font-body: 'Cormorant Garamond', serif;
        --font-script: 'Dancing Script', cursive;

        --ease: cubic-bezier(.22, 1, .36, 1);
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100vh;
        min-height: 100dvh;
        background: var(--bg-dark-1);
        font-family: var(--font-body);
        -webkit-tap-highlight-color: transparent;
        overflow-x: hidden;
    }

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation-duration: .01ms !important;
            transition-duration: .01ms !important;
        }
    }

    /* =========================================================
       BACKDROP — girly gradient, heart pattern, kiss marks,
       glows, floating sparkle particles
       ========================================================= */

    .backdrop {
        position: fixed;
        inset: 0;
        z-index: 0;
        background:
            radial-gradient(120% 90% at 50% 0%, var(--bg-dark-2) 0%, var(--bg-dark-1) 55%, var(--bg-dark-3) 100%);
        overflow: hidden;
    }

    /* subtle repeating heart texture, drawn once as an inline SVG data-uri */
    .backdrop::before {
        content: "";
        position: absolute;
        inset: 0;
        opacity: .5;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='44' height='44'%3E%3Cpath d='M22 36S9 27.5 9 18.7A7.3 7.3 0 0 1 22 13.8a7.3 7.3 0 0 1 13 4.9C35 27.5 22 36 22 36z' fill='%23F472A0' fill-opacity='0.07'/%3E%3C/svg%3E");
        background-size: 44px 44px;
    }

    .glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(70px);
        pointer-events: none;
    }

    .glow.g1 {
        width: 50vh;
        height: 50vh;
        top: -12%;
        left: -14%;
        background: radial-gradient(circle, rgba(var(--accent1-rgb), .32), transparent 70%);
        animation: driftA 20s ease-in-out infinite;
    }

    .glow.g2 {
        width: 44vh;
        height: 44vh;
        bottom: -10%;
        right: -12%;
        background: radial-gradient(circle, rgba(var(--accent3-rgb), .24), transparent 70%);
        animation: driftB 24s ease-in-out infinite;
    }

    @keyframes driftA {

        0%,
        100% {
            transform: translate(0, 0)
        }

        50% {
            transform: translate(5%, 7%)
        }
    }

    @keyframes driftB {

        0%,
        100% {
            transform: translate(0, 0)
        }

        50% {
            transform: translate(-5%, -6%)
        }
    }

    /* lipstick kiss marks — two overlapping ellipses forming a lip shape */
    .lipstick {
        position: absolute;
        opacity: .18;
        mix-blend-mode: screen;
        pointer-events: none;
    }

    .lipstick svg {
        display: block;
    }

    .lipstick.l1 {
        top: 8%;
        right: 9%;
        width: 70px;
        transform: rotate(18deg);
    }

    .lipstick.l2 {
        bottom: 14%;
        left: 6%;
        width: 54px;
        transform: rotate(-24deg) scaleX(-1);
    }

    .particles {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .particles span {
        position: absolute;
        border-radius: 50%;
        background: var(--accent-gold);
        opacity: 0;
        animation: floatUp linear infinite;
    }

    @keyframes floatUp {
        0% {
            opacity: 0;
            transform: translateY(0) translateX(0);
        }

        12% {
            opacity: .6;
        }

        88% {
            opacity: .32;
        }

        100% {
            opacity: 0;
            transform: translateY(-92vh) translateX(18px);
        }
    }

    /* =========================================================
       STAGE — centers the card, mobile-first; desktop just scales
       ========================================================= */

    .stage {
        position: relative;
        z-index: 1;
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6vh 5vw;
    }

    /* =========================================================
       SCRAPBOOK CARD
       ========================================================= */

    .scrapbook-card {
        position: relative;
        width: min(92vw, 400px);
        background:
            repeating-linear-gradient(180deg, rgba(var(--accent1-rgb), .03) 0px, rgba(var(--accent1-rgb), .03) 1px, transparent 1px, transparent 3px),
            linear-gradient(160deg, var(--paper-light), var(--paper-mid) 150%);
        border-radius: 18px;
        padding: 9% 7% 8%;
        box-shadow:
            0 30px 70px rgba(var(--shadow-rgb), .55),
            0 8px 18px rgba(var(--shadow-rgb), .35),
            inset 0 0 0 1px rgba(var(--accent1-rgb), .28);
        opacity: 0;
        transform: translateY(26px) scale(.96);
        animation: cardIn 1s var(--ease) .15s forwards;
    }

    @keyframes cardIn {
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* soft rotation for a handmade, imperfect feel */
    .scrapbook-card {
        transform: translateY(26px) scale(.96) rotate(0deg);
    }

    /* washi-tape accents pinning the "pages" down */
    .tape {
        position: absolute;
        width: 64px;
        height: 22px;
        background: rgba(var(--accent1-rgb), .55);
        box-shadow: 0 2px 6px rgba(var(--shadow-rgb), .2);
        opacity: .85;
    }

    .tape::after {
        content: "";
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(45deg, rgba(var(--paper-rgb), .3) 0 2px, transparent 2px 6px);
    }

    .tape-tl {
        top: -10px;
        left: 8%;
        transform: rotate(-8deg);
        border-radius: 2px;
    }

    .tape-tr {
        top: -10px;
        right: 8%;
        transform: rotate(7deg);
        border-radius: 2px;
    }

    /* ---------- Hanging birthday tag ---------- */
    .hanger {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 7%;
    }

    .string {
        width: 1px;
        height: 16px;
        background: rgba(var(--bgdark1-rgb), .35);
    }

    .tag {
        margin-top: -2px;
        padding: 7px 18px;
        background: linear-gradient(155deg, var(--accent-1), var(--accent-2));
        color: var(--text-on-accent);
        font-family: var(--font-display);
        font-weight: 700;
        font-size: clamp(11px, 3vw, 13px);
        letter-spacing: 2.5px;
        border-radius: 3px;
        box-shadow: 0 6px 14px rgba(var(--shadow-rgb), .25);
        transform: rotate(-2deg);
        position: relative;
    }

    .tag::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 50%;
        width: 6px;
        height: 6px;
        background: var(--bg-dark-1);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 -2px 0 rgba(var(--paper-rgb), .15) inset;
    }

    /* ---------- Polaroid photos ---------- */
    .polaroid-row {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 4%;
        margin-bottom: 9%;
        padding: 0 2%;
    }

    .polaroid {
        margin: 0;
        width: 32%;
        background: var(--paper-white);
        padding: 6% 6% 16%;
        box-shadow: 0 10px 20px rgba(var(--shadow-rgb), .30);
        border-radius: 2px;
        cursor: pointer;
        transition: transform .35s var(--ease), box-shadow .35s var(--ease);
    }

    .polaroid:hover,
    .polaroid:focus-visible {
        transform: translateY(-6px) scale(1.05) rotate(0deg) !important;
        box-shadow: 0 18px 30px rgba(var(--shadow-rgb), .4);
        z-index: 5;
    }

    .polaroid img {
        display: block;
        width: 100%;
        aspect-ratio: 1/1.05;
        object-fit: cover;
        border-radius: 1px;
    }

    .polaroid figcaption {
        display: block;
        text-align: center;
        margin-top: 8%;
        font-family: var(--font-script);
        font-size: clamp(12px, 3.4vw, 15px);
        color: var(--ink);
    }

    .polaroid.p1 {
        transform: rotate(-7deg);
        margin-top: 2%;
    }

    .polaroid.p2 {
        transform: rotate(3deg);
        margin-top: -2%;
    }

    .polaroid.p3 {
        transform: rotate(8deg);
        margin-top: 3%;
    }

    /* ---------- Calendar + Note row ---------- */
    .mini-row {
        display: flex;
        gap: 5%;
        margin-bottom: 8%;
    }

    .mini-calendar,
    .mini-note {
        flex: 1;
        min-width: 0;
        background: rgba(var(--paper-rgb), .65);
        border: 1px solid rgba(var(--accent1-rgb), .35);
        border-radius: 10px;
        padding: 7% 6%;
        box-shadow: 0 6px 14px rgba(var(--shadow-rgb), .10);
    }

    .mini-calendar {
        text-align: center;
    }

    .cal-head {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: clamp(10px, 2.8vw, 12px);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--ink);
        margin-bottom: 6px;
    }

    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
    }

    .cal-grid span {
        display: flex;
        align-items: center;
        justify-content: center;
        aspect-ratio: 1/1;
        font-size: clamp(7px, 2vw, 9px);
        color: rgba(var(--bgdark1-rgb), .55);
        font-family: var(--font-body);
    }

    .cal-grid span.hl {
        background: var(--accent-1);
        color: var(--text-on-accent);
        border-radius: 50%;
        font-weight: 700;
        position: relative;
        animation: pulseDate 2.6s ease-in-out infinite;
    }

    @keyframes pulseDate {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(var(--accent1-rgb), .5);
        }

        50% {
            box-shadow: 0 0 0 4px rgba(var(--accent1-rgb), 0);
        }
    }

    .mini-note {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transform: rotate(1.5deg);
    }

    .mini-note::before {
        content: "";
        position: absolute;
        top: -8px;
        left: 50%;
        width: 34px;
        height: 14px;
        transform: translateX(-50%) rotate(-2deg);
        background: rgba(var(--accent1-rgb), .45);
    }

    .mini-note p {
        margin: 0;
        font-family: var(--font-script);
        font-size: clamp(13px, 3.6vw, 16px);
        line-height: 1.35;
        color: var(--ink);
        text-align: center;
    }

    /* ---------- Quote ---------- */
    .quote {
        text-align: center;
        font-family: var(--font-display);
        font-style: italic;
        font-size: clamp(14px, 4vw, 17px);
        color: var(--ink-soft);
        opacity: .9;
        margin: 0 0 8%;
        padding: 0 4%;
    }

    /* ---------- Next button ---------- */
    .next-wrap {
        display: flex;
        justify-content: flex-end;
    }

    .next-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border: none;
        border-radius: 30px;
        background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
        color: var(--text-on-accent);
        font-family: var(--font-body);
        font-weight: 600;
        font-size: clamp(13px, 3.4vw, 15px);
        letter-spacing: .5px;
        cursor: pointer;
        box-shadow: 0 10px 22px rgba(var(--accent1-rgb), .35);
        transition: transform .3s var(--ease), box-shadow .3s var(--ease);
    }

    .next-btn span {
        display: inline-block;
        transition: transform .3s var(--ease);
    }

    .next-btn:hover,
    .next-btn:focus-visible {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(var(--accent1-rgb), .45);
    }

    .next-btn:hover span {
        transform: translateX(4px);
    }

    .next-btn:active {
        transform: translateY(0) scale(.97);
    }

    /* =========================================================
       CARD PEEK — click the calendar or note to zoom it in over a
       blurred backdrop, same as the polaroid lightbox does for
       photos. Hover stays a small lift only; nothing zooms or
       blurs until the item is actually clicked.
       ========================================================= */

    .mini-calendar,
    .mini-note {
        cursor: pointer;
        transition: transform .3s var(--ease), box-shadow .3s var(--ease);
    }

    .mini-calendar:hover,
    .mini-calendar:focus-visible {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 12px 22px rgba(var(--shadow-rgb), .18);
    }

    .mini-note:hover,
    .mini-note:focus-visible {
        transform: rotate(1.5deg) translateY(-4px) scale(1.03);
        box-shadow: 0 12px 22px rgba(var(--shadow-rgb), .18);
    }

    .peekbox {
        position: fixed;
        inset: 0;
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--bgdark1-rgb), .88);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        opacity: 0;
        pointer-events: none;
        transition: opacity .4s var(--ease);
        padding: 8vw;
    }

    .peekbox.open {
        opacity: 1;
        pointer-events: auto;
    }

    .peek-inner {
        transform: scale(.85);
        transition: transform .4s var(--ease);
    }

    .peekbox.open .peek-inner {
        transform: scale(1);
    }

    .peek-inner .peek-clone {
        transform: none !important;
        margin: 0 !important;
        width: min(80vw, 380px) !important;
        pointer-events: none;
    }

    .peek-inner .mini-calendar.peek-clone .cal-head {
        font-size: 17px;
    }

    .peek-inner .mini-calendar.peek-clone .cal-grid span {
        font-size: 15px;
    }

    .peek-inner .mini-note.peek-clone p {
        font-size: 24px;
    }

    /* =========================================================
       LIGHTBOX — photo zoom
       ========================================================= */

    .lightbox {
        position: fixed;
        inset: 0;
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--bgdark1-rgb), .88);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        opacity: 0;
        pointer-events: none;
        transition: opacity .4s var(--ease);
        padding: 8vw;
    }

    .lightbox.open {
        opacity: 1;
        pointer-events: auto;
    }

    .lightbox img {
        max-width: min(86vw, 420px);
        max-height: 78vh;
        border: 10px solid var(--paper-light);
        border-radius: 2px;
        box-shadow: 0 30px 60px rgba(var(--shadow-rgb), .5);
        transform: scale(.85);
        transition: transform .4s var(--ease);
    }

    .lightbox.open img {
        transform: scale(1);
    }

    .lightbox-close {
        position: absolute;
        top: 5vh;
        right: 6vw;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid rgba(var(--paper-rgb), .4);
        background: rgba(var(--paper-rgb), .1);
        color: var(--paper-light);
        font-size: 16px;
        cursor: pointer;
        transition: background .25s ease, transform .2s ease;
    }

    .lightbox-close:hover {
        background: rgba(var(--paper-rgb), .22);
    }

    .lightbox-close:active {
        transform: scale(.9);
    }

    /* =========================================================
       RESPONSIVE — desktop scales the same layout, stays centered
       ========================================================= */

    @media (min-width:600px) {
        .scrapbook-card {
            width: 440px;
            padding: 8% 6% 7%;
        }
    }

    @media (min-width:900px) {
        .scrapbook-card {
            width: 480px;
        }

        .stage {
            padding: 8vh 5vw;
        }
    }

    @media (min-width:1024px) {
        .scrapbook-card {
            width: 580px;
            padding: 6% 6% 5%;
        }

        .tag {
            font-size: 14px;
            padding: 9px 22px;
        }

        .polaroid figcaption {
            font-size: 17px;
        }

        .cal-head {
            font-size: 13px;
        }

        .cal-grid span {
            font-size: 11px;
        }

        .mini-note p {
            font-size: 18px;
        }

        .quote {
            font-size: 19px;
        }

        .next-btn {
            font-size: 16px;
            padding: 12px 26px;
        }
    }

    @media (max-width:340px) {
        .scrapbook-card {
            padding: 8% 6% 7%;
        }

        .mini-row {
            gap: 4%;
        }
    }
    </style>
</head>

<body>

    <!-- Ambient girly backdrop -->
    <div class="backdrop">
        <div class="glow g1"></div>
        <div class="glow g2"></div>

        <div class="lipstick l1">
            <svg viewBox="0 0 100 60" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 30 C10 10 35 8 50 22 C65 8 90 10 90 30 C90 45 65 40 50 50 C35 40 10 45 10 30 Z"
                    fill="#F472A0" />
            </svg>
        </div>
        <div class="lipstick l2">
            <svg viewBox="0 0 100 60" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 30 C10 10 35 8 50 22 C65 8 90 10 90 30 C90 45 65 40 50 50 C35 40 10 45 10 30 Z"
                    fill="#F472A0" />
            </svg>
        </div>

        <div class="particles" id="particles"></div>
    </div>

    <!-- Centered stage -->
    <main class="stage">
        <section class="scrapbook-card" id="card">

            <div class="tape tape-tl"></div>
            <div class="tape tape-tr"></div>

            <!-- Hanging birthday tag -->
            <div class="hanger">
                <span class="string"></span>
                <div class="tag">HAPPY BIRTHDAY</div>
            </div>

            <!-- Three polaroid photos -->
            <div class="polaroid-row">
                <figure class="polaroid p1" tabindex="0"
                    data-full="{{ request('photo1', 'https://picsum.photos/seed/lovenote1/700/820') }}">
                    <img src="{{ request('photo1', 'https://picsum.photos/seed/lovenote1/320/380') }}"
                        alt="A favorite memory together" loading="lazy">
                    <figcaption>that day</figcaption>
                </figure>
                <figure class="polaroid p2" tabindex="0"
                    data-full="{{ request('photo2', 'https://picsum.photos/seed/lovenote2/700/820') }}">
                    <img src="{{ request('photo2', 'https://picsum.photos/seed/lovenote2/320/380') }}"
                        alt="A favorite memory together" loading="lazy">
                    <figcaption>us</figcaption>
                </figure>
                <figure class="polaroid p3" tabindex="0"
                    data-full="{{ request('photo3', 'https://picsum.photos/seed/lovenote3/700/820') }}">
                    <img src="{{ request('photo3', 'https://picsum.photos/seed/lovenote3/320/380') }}"
                        alt="A favorite memory together" loading="lazy">
                    <figcaption>forever</figcaption>
                </figure>
            </div>

            <!-- Calendar + handwritten note -->
            <div class="mini-row">
                <div class="mini-calendar">
                    <div class="cal-head">{{ request('cal_month', 'August') }}</div>
                    <div class="cal-grid" id="calGrid">
                        <!-- filled by script -->
                    </div>
                </div>
                <div class="mini-note">
                    <p>{{ request('message', 'Your laugh is my favorite sound.') }}</p>
                </div>
            </div>

            <!-- Quote -->
            <p class="quote">"You make every moment beautiful."</p>

        </section>
    </main>

    <!-- Photo zoom lightbox -->
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" id="lightboxClose" aria-label="Close">✕</button>
        <img id="lightboxImg" src="" alt="Zoomed memory">
    </div>

    <!-- Card peek overlay (calendar & note) -->
    <div class="peekbox" id="peekbox">
        <button class="lightbox-close" id="peekboxClose" aria-label="Close">✕</button>
        <div class="peek-inner" id="peekInner"></div>
    </div>

    <script>
    (function() {
        'use strict';

        /* ---------------- Floating particles ---------------- */
        const particleLayer = document.getElementById('particles');
        const PARTICLE_COUNT = 24;
        for (let i = 0; i < PARTICLE_COUNT; i++) {
            const s = document.createElement('span');
            const size = 2 + Math.random() * 2;
            s.style.width = size + 'px';
            s.style.height = size + 'px';
            s.style.left = Math.random() * 100 + '%';
            s.style.bottom = '-10px';
            s.style.animationDuration = (9 + Math.random() * 10) + 's';
            s.style.animationDelay = (Math.random() * 14) + 's';
            particleLayer.appendChild(s);
        }

        /* ---------------- Mini calendar ---------------- */
        const calGrid = document.getElementById('calGrid');
        const DAYS_IN_MONTH = {{ (int) request('cal_days', 31) }};
        const HIGHLIGHT_DAY = {{ (int) request('cal_day', 14) }}; // change this to your special date
        for (let d = 1; d <= DAYS_IN_MONTH; d++) {
            const cell = document.createElement('span');
            cell.textContent = d;
            if (d === HIGHLIGHT_DAY) cell.classList.add('hl');
            calGrid.appendChild(cell);
        }

        /* ---------------- Polaroid lightbox zoom ---------------- */
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightboxImg');
        const lightboxClose = document.getElementById('lightboxClose');
        const polaroids = document.querySelectorAll('.polaroid');

        function openLightbox(src) {
            lightboxImg.src = src;
            lightbox.classList.add('open');
        }

        function closeLightbox() {
            lightbox.classList.remove('open');
        }

        polaroids.forEach((p) => {
            p.addEventListener('click', () => openLightbox(p.dataset.full));
            p.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openLightbox(p.dataset.full);
                }
            });
        });

        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });

        /* ---------------- Card peek (calendar & note) ---------------- */
        const peekbox = document.getElementById('peekbox');
        const peekInner = document.getElementById('peekInner');
        const peekboxClose = document.getElementById('peekboxClose');
        const peekTargets = document.querySelectorAll('.mini-calendar, .mini-note');

        function openPeek(el) {
            const clone = el.cloneNode(true);
            clone.classList.add('peek-clone');
            peekInner.innerHTML = '';
            peekInner.appendChild(clone);
            peekbox.classList.add('open');
        }

        function closePeek() {
            peekbox.classList.remove('open');
        }

        peekTargets.forEach((el) => {
            el.setAttribute('tabindex', '0');
            el.addEventListener('click', () => openPeek(el));
            el.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openPeek(el);
                }
            });
        });

        peekboxClose.addEventListener('click', closePeek);
        peekbox.addEventListener('click', (e) => {
            if (e.target === peekbox) closePeek();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeLightbox();
                closePeek();
            }
        });

    })();
    </script>
</body>

</html>
