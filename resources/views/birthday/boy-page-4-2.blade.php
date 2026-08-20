@php
    // The letter is the one slot whose length the client controls freely, and
    // the paper is a fixed sheet. Its text and its auto-fit size are worked out
    // here, above the markup, so both the sheet and the script below read the
    // same value.
    $endingLetterDefault = "Happy birthday, my love.\n\nWe reached the last page, but this isn't really the end \u{2014} it's just where the words stop and the feeling keeps going.\n\nThank you for being exactly who you are \u{2014} for every laugh, every little moment, every reason you give me to smile.\n\nHere's to you, and to every birthday still ahead of us.\n\nI love you, always.";
    $endingLetter = request('letter', $endingLetterDefault);

    // A hard line break costs a whole line on this sheet, so it weighs about
    // as much as a line's worth of characters.
    $endingLetterWeight = max(
        mb_strlen($endingLetter),
        (substr_count($endingLetter, "\n") + 1) * 26
    );
    $endingLetterSize = $endingLetterWeight > 300 ? 'xs' : ($endingLetterWeight > 200 ? 'sm' : '');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>One Last Thing — A Letter For You</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Inter:wght@400;500;600&family=Dancing+Script:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
    /* ==================================================
   THEME VARIABLES — change these to re-skin everything
   THEME: Boy — Graphite Ice (Dark Variant 4)
   ================================================== */
    :root {
        --bg: #0D1117;
        /* Graphite Black */
        --primary: #4FA8D8;
        /* Ice Blue */
        --secondary: #274B67;
        /* Steel Navy */
        --accent: #BFD9EC;
        /* Pale Ice Blue */
        --text: #bfd9ec;
        --paper: #161B22;
        --paper-line: rgba(191, 217, 236, .16);
        --shadow: 0 20px 45px rgba(0, 0, 0, 0.55);
        --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.4);
        --radius: 26px;
        --transition: 550ms cubic-bezier(.22, 1, .36, 1);
        --font-heading: 'Playfair Display', serif;
        --font-body: 'Inter', sans-serif;
        --font-hand: 'Dancing Script', cursive;
        --stage-w: 420px;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        height: 100%;
        overscroll-behavior: none;
    }

    body {
        font-family: var(--font-body);
        color: var(--text);
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(120% 120% at 20% 0%, var(--bg) 0%, var(--bg) 100%);
        overflow: hidden;
        position: relative;
        -webkit-tap-highlight-color: transparent;
        padding: 20px;
    }

    button {
        font-family: inherit;
        cursor: pointer;
    }

    /* ==================================================
   LUXURY BACKGROUND — blur blobs, dust, glow lights
   ================================================== */
    .bg-blur {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: .35;
        animation: drift 18s ease-in-out infinite;
    }

    .blob1 {
        width: 60vw;
        height: 60vw;
        max-width: 520px;
        max-height: 520px;
        background: radial-gradient(circle, var(--primary), transparent 70%);
        top: -15%;
        left: -15%;
    }

    .blob2 {
        width: 55vw;
        height: 55vw;
        max-width: 480px;
        max-height: 480px;
        background: radial-gradient(circle, var(--secondary), transparent 70%);
        bottom: -18%;
        right: -12%;
        animation-delay: -6s;
    }

    .blob3 {
        width: 40vw;
        height: 40vw;
        max-width: 360px;
        max-height: 360px;
        background: radial-gradient(circle, var(--accent), transparent 70%);
        top: 40%;
        left: 55%;
        animation-delay: -11s;
        opacity: .35;
    }

    @keyframes drift {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        33% {
            transform: translate(4%, 6%) scale(1.08);
        }

        66% {
            transform: translate(-5%, -3%) scale(0.95);
        }
    }

    .dust {
        position: absolute;
        border-radius: 50%;
        background: var(--accent);
        opacity: 0;
        animation: dust-float linear infinite;
    }

    @keyframes dust-float {
        0% {
            transform: translateY(0) translateX(0);
            opacity: 0;
        }

        10% {
            opacity: .5;
        }

        90% {
            opacity: .35;
        }

        100% {
            transform: translateY(-110vh) translateX(20px);
            opacity: 0;
        }
    }

    .glow {
        position: absolute;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--primary);
        box-shadow: 0 0 12px 3px var(--primary);
        animation: twinkle 3.2s ease-in-out infinite;
    }

    @keyframes twinkle {

        0%,
        100% {
            opacity: .15;
            transform: scale(.8);
        }

        50% {
            opacity: 1;
            transform: scale(1.3);
        }
    }

    /* scattered "I love you" / "I miss you" whisper-text across the bg */
    .love-word {
        position: absolute;
        font-family: var(--font-hand);
        color: var(--secondary);
        white-space: nowrap;
        opacity: 0;
        animation: word-breathe ease-in-out infinite;
        pointer-events: none;
        user-select: none;
    }

    @keyframes word-breathe {

        0%,
        100% {
            opacity: 0;
            transform: translateY(6px) scale(.94) rotate(var(--rot, 0deg));
        }

        50% {
            opacity: var(--peak, .22);
            transform: translateY(-4px) scale(1) rotate(var(--rot, 0deg));
        }
    }

    .love-emoji {
        position: absolute;
        opacity: 0;
        animation: emoji-breathe ease-in-out infinite;
        pointer-events: none;
        user-select: none;
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .25));
    }

    @keyframes emoji-breathe {

        0%,
        100% {
            opacity: 0;
            transform: translateY(8px) scale(.85) rotate(var(--rot, 0deg));
        }

        50% {
            opacity: var(--peak, .5);
            transform: translateY(-6px) scale(1) rotate(var(--rot, 0deg));
        }
    }

    /* ==================================================
   STAGE — centered 9:16-ish envelope stage
   ================================================== */
    .stage {
        position: relative;
        z-index: 1;
        width: min(92vw, var(--stage-w));
        max-width: var(--stage-w);
    }

    /* ==================================================
   ENVELOPE COVER
   ================================================== */
    .envelope-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity var(--transition), transform var(--transition), visibility var(--transition);
    }

    .envelope-wrap.hidden {
        opacity: 0;
        transform: scale(.92);
        visibility: hidden;
        pointer-events: none;
        position: absolute;
        inset: 0;
    }

    .envelope {
        position: relative;
        width: 100%;
        aspect-ratio: 4/3;
        cursor: pointer;
        animation: env-float 4.5s ease-in-out infinite;
    }

    @keyframes env-float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .envelope-body {
        position: absolute;
        inset: 0;
        border-radius: 14px;
        background: linear-gradient(160deg, var(--paper) 0%, #0f141a 100%);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .envelope-body::before {
        /* bottom triangle fold shading */
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 62%;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, .05) 100%);
    }

    .envelope-flap {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 58%;
        background: linear-gradient(155deg, var(--secondary) 0%, var(--primary) 100%);
        clip-path: polygon(0 0, 100% 0, 50% 100%);
        box-shadow: 0 6px 14px rgba(0, 0, 0, .18);
    }

    .envelope-seal {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: radial-gradient(circle at 35% 30%, #6FA8C9, #16283A);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
        z-index: 3;
    }

    .envelope-seal svg {
        width: 24px;
        height: 24px;
        stroke: #EAF3FA;
    }

    .envelope-caption {
        text-align: center;
        margin-top: 26px;
    }

    .envelope-caption h1 {
        font-family: var(--font-heading);
        font-size: clamp(22px, 6vw, 28px);
        font-weight: 600;
        color: var(--accent);
        margin: 0 0 8px;
    }

    .envelope-caption p {
        font-size: 13px;
        font-style: italic;
        color: var(--primary);
        font-family: var(--font-heading);
        margin: 0 0 22px;
    }

    .envelope-tap {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--accent);
        opacity: .8;
        animation: pulse-tap 1.8s ease-in-out infinite;
    }

    @keyframes pulse-tap {

        0%,
        100% {
            opacity: .45;
        }

        50% {
            opacity: .9;
        }
    }

    .envelope-tap svg {
        width: 14px;
        height: 14px;
        stroke: var(--primary);
    }

    /* ==================================================
   THE LETTER (unfold + handwriting)
   ================================================== */
    .letter-wrap {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity var(--transition);
    }

    .letter-wrap.open {
        opacity: 1;
        visibility: visible;
    }

    .letter-paper {
        width: 100%;
        max-width: 400px;
        max-height: 86vh;
        background: var(--paper);
        border-radius: 8px;
        box-shadow: var(--shadow);
        padding: 38px 28px 32px;
        transform-origin: top center;
        transform: scaleY(.06);
        transition: transform 950ms cubic-bezier(.65, 0, .35, 1);
        overflow-y: auto;
        position: relative;
        scrollbar-width: none;
    }

    .letter-paper::-webkit-scrollbar {
        display: none;
    }

    .letter-wrap.open .letter-paper {
        transform: scaleY(1);
    }

    .letter-paper::before {
        content: '';
        position: absolute;
        inset: 12px;
        border: 1px solid var(--paper-line);
        pointer-events: none;
    }

    .letter-heading {
        text-align: center;
        margin-bottom: 22px;
    }

    .letter-heading span {
        font-family: var(--font-heading);
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--accent);
        opacity: .7;
    }

    .letter-body {
        font-family: var(--font-hand);
        font-size: clamp(20px, 6vw, 25px);
        line-height: 1.75;
        color: var(--accent);
        white-space: pre-wrap;
        min-height: 220px;
    }

    /* Auto-fit: the sheet is a fixed height, so a longer letter steps down a
   size rather than running off the bottom of it. Paired with the length and
   line caps in the dashboard. */
    .letter-body.sm {
        font-size: clamp(17px, 5vw, 21px);
        line-height: 1.6;
    }

    .letter-body.xs {
        font-size: clamp(15px, 4.2vw, 18px);
        line-height: 1.5;
    }

    .pen-caret {
        display: inline-block;
        width: 2px;
        height: 1em;
        background: var(--accent);
        vertical-align: -3px;
        animation: caret-blink 900ms steps(1) infinite;
    }

    @keyframes caret-blink {

        0%,
        49% {
            opacity: 1;
        }

        50%,
        100% {
            opacity: 0;
        }
    }

    .letter-signoff {
        margin-top: 22px;
        text-align: right;
        font-family: var(--font-hand);
        font-size: 24px;
        color: var(--accent);
        opacity: 0;
        transition: opacity 600ms ease;
    }

    .letter-signoff.show {
        opacity: 1;
    }

    .letter-stamp {
        position: absolute;
        top: 18px;
        right: 18px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(145deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 500ms ease, transform 500ms ease;
        transform: scale(.5) rotate(-20deg);
    }

    .letter-stamp.show {
        opacity: 1;
        transform: scale(1) rotate(0deg);
    }

    .letter-stamp svg {
        width: 15px;
        height: 15px;
        stroke: #fff;
    }

    .the-end {
        text-align: center;
        margin-top: 28px;
        opacity: 0;
        transition: opacity 700ms ease;
    }

    .the-end.show {
        opacity: 1;
    }

    .the-end span {
        font-family: var(--font-heading);
        font-style: italic;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--accent);
        opacity: .55;
    }

    /* ==================================================
   RIPPLE micro-interaction
   ================================================== */
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .55);
        transform: scale(0);
        animation: ripple-anim 650ms ease-out forwards;
        pointer-events: none;
    }

    @keyframes ripple-anim {
        to {
            transform: scale(3.2);
            opacity: 0;
        }
    }

    /* ==================================================
   RESPONSIVE
   ================================================== */
    @media (min-width: 641px) {
        :root {
            --stage-w: 440px;
        }
    }

    @media (min-width: 1024px) {
        body {
            padding: 40px;
        }
    }
    </style>
</head>

<body>

    <!-- Background ambience -->
    <div class="bg-blur" aria-hidden="true">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
        <div class="blob blob3"></div>
        <div id="wordLayer"></div>
        <div id="emojiLayer"></div>
        <div id="dustLayer"></div>
        <div id="glowLayer"></div>
    </div>

    <!-- ============ STAGE ============ -->
    <div class="stage" id="stage">

        <!-- ============ ENVELOPE COVER ============ -->
        <div class="envelope-wrap" id="envelopeWrap">
            <div>
                <div class="envelope" id="envelope">
                    <div class="envelope-body">
                        <div class="envelope-flap"></div>
                        <div class="envelope-seal">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M12 21s-7-4.6-9.6-9.1C.6 8.4 2.4 5 6 5c2 0 3.5 1.1 4.3 2.4a1 1 0 0 0 1.4 0C12.5 6.1 14 5 16 5c3.6 0 5.4 3.4 3.6 6.9C19 16.4 12 21 12 21Z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="envelope-caption">
                    <h1>{{ request('title', 'One Last Thing') }}</h1>
                    <p>{{ request('subtitle', 'Before you go, read this.') }}</p>
                    <div class="envelope-tap">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M9 11.5V6a2 2 0 1 1 4 0v5"></path>
                            <path d="M13 6a2 2 0 1 1 4 0v6"></path>
                            <path
                                d="M17 8.5a2 2 0 1 1 4 0V14a7 7 0 0 1-7 7h-1a7 7 0 0 1-6-3.4L4.7 13a1.8 1.8 0 0 1 2.9-2.1L9 13">
                            </path>
                        </svg>
                        {{ request('tap_label', 'Tap to Open') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- ============ THE LETTER ============ -->
        <div class="letter-wrap" id="letterWrap">
            <div class="letter-paper">
                <div class="letter-stamp" id="letterStamp">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M20.8 4.6c1.6 1.6 1.6 4.2 0 5.8L12 19.2l-8.8-8.8c-1.6-1.6-1.6-4.2 0-5.8 1.6-1.6 4.2-1.6 5.8 0L12 7.2l3-2.6c1.6-1.6 4.2-1.6 5.8 0Z">
                        </path>
                    </svg>
                </div>
                <div class="letter-heading"><span>{{ request('letter_heading', 'A Letter For You') }}</span></div>
                <div class="letter-body {{ $endingLetterSize }}" id="letterBody"><span class="pen-caret"></span></div>
                <div class="letter-signoff" id="letterSignoff">{{ request('signoff', '— always yours') }}</div>
                <div class="the-end" id="theEnd"><span>{{ request('end_label', 'The End') }}</span></div>
            </div>
        </div>
    </div>

    <script>
    /* ==================================================
   REUSABLE LETTER TEXT — replace this to customize
   ================================================== */
    const LETTER_TEXT = @json($endingLetter);

    /* ==================================================
       BACKGROUND LOVE WORDS + EMOJIS
       ================================================== */
    (function buildLoveBackground() {
        const wordLayer = document.getElementById('wordLayer');
        const emojiLayer = document.getElementById('emojiLayer');
        const words = ['I love you', 'I miss you', 'i love you', 'i miss u', 'love you', 'miss you', 'always you'];
        const emojis = ['💗', '💕', '💖', '💘', '🥰', '😽', '💝', '🌸', '✨', '💌', '😘', '🩷'];

        const wordCount = window.innerWidth < 640 ? 22 : 36;
        const emojiCount = window.innerWidth < 640 ? 18 : 30;

        for (let i = 0; i < wordCount; i++) {
            const w = document.createElement('span');
            w.className = 'love-word';
            w.textContent = words[Math.floor(Math.random() * words.length)];
            w.style.left = Math.random() * 96 + 'vw';
            w.style.top = Math.random() * 96 + 'vh';
            w.style.fontSize = (13 + Math.random() * 14) + 'px';
            w.style.setProperty('--rot', (Math.random() * 30 - 15) + 'deg');
            w.style.setProperty('--peak', (.14 + Math.random() * .18).toFixed(2));
            w.style.animationDuration = (5 + Math.random() * 5) + 's';
            w.style.animationDelay = (Math.random() * 6) + 's';
            wordLayer.appendChild(w);
        }

        for (let i = 0; i < emojiCount; i++) {
            const e = document.createElement('span');
            e.className = 'love-emoji';
            e.textContent = emojis[Math.floor(Math.random() * emojis.length)];
            e.style.left = Math.random() * 96 + 'vw';
            e.style.top = Math.random() * 96 + 'vh';
            e.style.fontSize = (12 + Math.random() * 16) + 'px';
            e.style.setProperty('--rot', (Math.random() * 40 - 20) + 'deg');
            e.style.setProperty('--peak', (.35 + Math.random() * .35).toFixed(2));
            e.style.animationDuration = (4 + Math.random() * 5) + 's';
            e.style.animationDelay = (Math.random() * 6) + 's';
            emojiLayer.appendChild(e);
        }
    })();

    /* ==================================================
       BACKGROUND PARTICLES
       ================================================== */
    (function buildParticles() {
        const dustLayer = document.getElementById('dustLayer');
        const glowLayer = document.getElementById('glowLayer');
        const dustCount = window.innerWidth < 640 ? 16 : 26;
        const glowCount = window.innerWidth < 640 ? 8 : 14;

        for (let i = 0; i < dustCount; i++) {
            const d = document.createElement('div');
            d.className = 'dust';
            const size = 2 + Math.random() * 3;
            d.style.width = size + 'px';
            d.style.height = size + 'px';
            d.style.left = Math.random() * 100 + 'vw';
            d.style.bottom = -10 + 'px';
            d.style.animationDuration = (14 + Math.random() * 12) + 's';
            d.style.animationDelay = (Math.random() * 14) + 's';
            dustLayer.appendChild(d);
        }

        for (let i = 0; i < glowCount; i++) {
            const g = document.createElement('div');
            g.className = 'glow';
            g.style.left = Math.random() * 100 + 'vw';
            g.style.top = Math.random() * 100 + 'vh';
            g.style.animationDelay = (Math.random() * 3) + 's';
            g.style.animationDuration = (2.6 + Math.random() * 2.4) + 's';
            glowLayer.appendChild(g);
        }
    })();

    /* ==================================================
       ENVELOPE TAP -> OPEN LETTER -> HANDWRITING
       ================================================== */
    const envelopeWrap = document.getElementById('envelopeWrap');
    const envelope = document.getElementById('envelope');
    const letterWrap = document.getElementById('letterWrap');
    const letterBody = document.getElementById('letterBody');
    const letterSignoff = document.getElementById('letterSignoff');
    const letterStamp = document.getElementById('letterStamp');
    const theEnd = document.getElementById('theEnd');

    const paper = letterBody.closest('.letter-paper');

    function typewriteHandwriting(text) {
        letterBody.innerHTML = '';
        const caret = document.createElement('span');
        caret.className = 'pen-caret';

        let i = 0;
        const textNode = document.createTextNode('');
        letterBody.appendChild(textNode);
        letterBody.appendChild(caret);

        function tick() {
            if (i >= text.length) {
                caret.remove();
                letterSignoff.classList.add('show');
                setTimeout(() => theEnd.classList.add('show'), 500);
                return;
            }
            textNode.textContent += text[i];
            i++;
            // The sheet scrolls, so a letter longer than the paper would
            // otherwise be written below the fold.
            if (paper && paper.scrollHeight > paper.clientHeight) {
                paper.scrollTop = paper.scrollHeight;
            }
            const ch = text[i - 1];
            let delay = 34 + Math.random() * 26;
            if (ch === ',') delay += 140;
            if (ch === '.') delay += 260;
            if (ch === '\n') delay += 420;
            setTimeout(tick, delay);
        }
        tick();
    }

    function openLetter() {
        envelopeWrap.classList.add('hidden');
        letterWrap.classList.add('open');
        setTimeout(() => letterStamp.classList.add('show'), 700);
        setTimeout(() => typewriteHandwriting(LETTER_TEXT), 900);
    }

    envelope.addEventListener('click', openLetter);
    envelope.addEventListener('keyup', e => {
        if (e.key === 'Enter' || e.key === ' ') openLetter();
    });

    /* ==================================================
       DASHBOARD PREVIEW
       ?preview_stage=letter skips the envelope and prints the letter in full,
       so the client can see the text they are typing without waiting out the
       handwriting animation.
       ================================================== */
    if (@json(request('preview_stage')) === 'letter') {
        envelopeWrap.classList.add('hidden');
        letterWrap.classList.add('open');
        letterStamp.classList.add('show');
        letterBody.textContent = LETTER_TEXT;
        letterSignoff.classList.add('show');
        theEnd.classList.add('show');
    }

    /* ripple micro-interaction */
    document.addEventListener('pointerdown', (e) => {
        const target = e.target.closest('.envelope');
        if (!target) return;
        const rect = target.getBoundingClientRect();
        const r = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        r.className = 'ripple';
        r.style.width = r.style.height = size + 'px';
        r.style.left = (e.clientX - rect.left - size / 2) + 'px';
        r.style.top = (e.clientY - rect.top - size / 2) + 'px';
        target.style.position = target.style.position || 'relative';
        target.style.overflow = target.style.overflow || 'hidden';
        target.appendChild(r);
        setTimeout(() => r.remove(), 650);
    });
    </script>

</body>

</html>