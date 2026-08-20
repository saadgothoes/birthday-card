@php
    /* ==================================================
       GIRL ENDING PAGE — "The Bloom"
       THEME: Girl — Rose Gold Noir (Dark)

       A different shape from the boy ending, which is an envelope that
       unfolds into a letter. Here the story closes on a flower: a closed bud
       sits on a soft field, and tapping it makes the petals unfurl one after
       another to reveal a round keepsake card written out by hand, with petals
       drifting past the whole time.

       Every text slot is a query parameter whose default is this page's own
       wording, so the template still renders on its own — the same convention
       the rest of the card designs use.
       ================================================== */
    $endingLetterDefault = "Every petal here is a day I got to spend with you.\n\nThank you for all of them.\n\nHappy birthday, my love.";
    $endingLetter = request('letter', $endingLetterDefault);

    // The card is a fixed circle, so a longer note steps down a size rather
    // than spilling out of it. A hard break costs about a line of text.
    $endingLetterWeight = max(
        mb_strlen($endingLetter),
        (substr_count($endingLetter, "\n") + 1) * 26
    );
    $endingLetterSize = $endingLetterWeight > 140 ? 'xs' : ($endingLetterWeight > 72 ? 'sm' : '');
@endphp
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Last Bloom — A Note For You</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Dancing+Script:wght@500;600&family=Jost:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --bg: #1C1116;
        /* Blush Cream */
        --bg-deep: #2E1922;
        --primary: #E7A9AE;
        /* Blush Pink */
        --secondary: #C58490;
        /* Deep Rose */
        --accent: #F6DCE1;
        /* Wine */
        --ink: #F6DCE1;
        --card: #2A1922;
        --card-line: rgba(246, 220, 225, .18);
        --petal-a: #C58490;
        --petal-b: #A2606F;
        --petal-c: #83495A;
        --leaf: #6E7F63;
        --stem: #556349;
        --glow: rgba(231, 169, 174, .4);
        --shadow: 0 22px 50px rgba(0, 0, 0, .55);
        --shadow-soft: 0 10px 28px rgba(0, 0, 0, .4);
        --font-display: 'Cormorant Garamond', Georgia, serif;
        --font-hand: 'Dancing Script', cursive;
        --font-ui: 'Jost', system-ui, sans-serif;
        --transition: 620ms cubic-bezier(.22, 1, .36, 1);
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        height: 100%;
    }

    body {
        background:
            radial-gradient(120% 80% at 50% 8%, var(--bg) 0%, var(--bg-deep) 100%);
        color: var(--ink);
        font-family: var(--font-ui);
        overflow: hidden;
        -webkit-font-smoothing: antialiased;
    }

    /* ==================================================
       AMBIENCE — drifting petals and soft light
       ================================================== */
    .field {
        position: fixed;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
    }

    .haze {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: .5;
        pointer-events: none;
    }

    .haze.one {
        width: 46vmin;
        height: 46vmin;
        left: -8vmin;
        top: 6vmin;
        background: var(--petal-a);
    }

    .haze.two {
        width: 40vmin;
        height: 40vmin;
        right: -10vmin;
        bottom: 4vmin;
        background: var(--petal-c);
        opacity: .38;
    }

    .haze.three {
        width: 34vmin;
        height: 34vmin;
        left: 40%;
        bottom: -12vmin;
        background: #8A6242;
        opacity: .28;
    }

    .drift {
        position: absolute;
        top: -8vh;
        width: 12px;
        height: 12px;
        border-radius: 60% 0 60% 0;
        background: linear-gradient(150deg, var(--petal-a), var(--petal-c));
        opacity: 0;
        animation: fall linear infinite;
        pointer-events: none;
    }

    @keyframes fall {
        0% {
            transform: translateY(-10vh) rotate(0deg) scale(var(--s, 1));
            opacity: 0;
        }

        12% {
            opacity: var(--peak, .7);
        }

        88% {
            opacity: var(--peak, .7);
        }

        100% {
            transform: translateY(112vh) translateX(var(--drift-x, 40px)) rotate(540deg) scale(var(--s, 1));
            opacity: 0;
        }
    }

    .sparkle {
        position: absolute;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #33202A;
        box-shadow: 0 0 8px 2px var(--glow);
        opacity: 0;
        animation: twinkle ease-in-out infinite;
    }

    @keyframes twinkle {

        0%,
        100% {
            opacity: 0;
            transform: scale(.4);
        }

        50% {
            opacity: .9;
            transform: scale(1);
        }
    }

    /* ==================================================
       STAGE
       ================================================== */
    .stage {
        position: relative;
        z-index: 1;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        /* The bud's wrapper scales to 1.35 as it fades out, which reaches past
           the viewport on every side; clipping here keeps that out of the
           page's scrollable area. */
        overflow: hidden;
    }

    /* ==================================================
       THE BUD — closed flower, tap to bloom
       ================================================== */
    .bloom-wrap {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: opacity var(--transition), transform var(--transition);
    }

    .bloom-wrap.gone {
        opacity: 0;
        transform: scale(1.35);
        pointer-events: none;
    }

    .flower {
        position: relative;
        width: min(58vw, 240px);
        height: min(58vw, 240px);
        cursor: pointer;
        outline: none;
        animation: sway 5.5s ease-in-out infinite;
    }

    @keyframes sway {

        0%,
        100% {
            transform: rotate(-2.5deg);
        }

        50% {
            transform: rotate(2.5deg);
        }
    }

    .petal {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 40%;
        height: 50%;
        margin-left: -20%;
        /* The petal hangs from the flower's centre upward, so its own bottom
           edge — the transform origin — sits exactly on that centre and the
           rotation fans the petals around it rather than below it. */
        margin-top: -50%;
        border-radius: 50% 50% 50% 50% / 62% 62% 38% 38%;
        background: linear-gradient(170deg, var(--petal-a), var(--petal-c));
        box-shadow: inset 0 -8px 18px rgba(122, 50, 71, .16);
        transform-origin: 50% 100%;
        /* closed: every petal drawn in tight around the core */
        transform: rotate(var(--a)) scale(.34);
        transition: transform 900ms cubic-bezier(.34, 1.4, .5, 1);
    }

    .petal.back {
        background: linear-gradient(170deg, var(--petal-b), var(--secondary));
        opacity: .95;
    }

    .flower.open .petal {
        transform: rotate(var(--a)) scale(1);
    }

    .flower.open .petal.back {
        transform: rotate(var(--a)) scale(1.12);
    }

    .bud-core {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 24%;
        height: 24%;
        margin: -12% 0 0 -12%;
        border-radius: 50%;
        background: radial-gradient(circle at 34% 30%, #F3D9A8, #C99A5C 70%, #A87C43);
        box-shadow: 0 0 0 6px rgba(255, 255, 255, .5), 0 6px 16px rgba(122, 50, 71, .2);
        z-index: 3;
        transition: transform 700ms cubic-bezier(.34, 1.4, .5, 1);
    }

    .flower.open .bud-core {
        transform: scale(1.18);
    }

    .leaf {
        position: absolute;
        left: 50%;
        bottom: -6%;
        width: 26%;
        height: 16%;
        background: linear-gradient(120deg, var(--leaf), var(--stem));
        border-radius: 0 100% 0 100%;
        transform-origin: 0 50%;
        z-index: 1;
    }

    .leaf.l {
        transform: translateX(-100%) rotate(18deg) scaleX(-1);
    }

    .leaf.r {
        transform: rotate(18deg);
    }

    .bloom-caption {
        text-align: center;
        margin-top: 26px;
    }

    .bloom-caption h1 {
        font-family: var(--font-display);
        font-size: clamp(24px, 6.4vw, 32px);
        font-weight: 600;
        font-style: italic;
        color: var(--accent);
        margin: 0 0 8px;
    }

    .bloom-caption p {
        font-family: var(--font-display);
        font-size: 14px;
        font-style: italic;
        color: var(--secondary);
        margin: 0 0 20px;
    }

    .bloom-tap {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        letter-spacing: 2.4px;
        text-transform: uppercase;
        color: var(--secondary);
        animation: pulse-tap 1.9s ease-in-out infinite;
    }

    .bloom-tap svg {
        width: 14px;
        height: 14px;
        stroke: var(--primary);
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    @keyframes pulse-tap {

        0%,
        100% {
            opacity: .45;
        }

        50% {
            opacity: 1;
        }
    }

    /* ==================================================
       THE KEEPSAKE — a round card inside the open flower
       ================================================== */
    .keepsake-wrap {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity var(--transition);
        padding: 20px;
    }

    .keepsake-wrap.open {
        opacity: 1;
        visibility: visible;
    }

    .keepsake {
        position: relative;
        /* The card's padding has to be a fraction of the card, not of the page.
           Percentage padding resolves against the containing block's width, so
           on a wide screen `15%` was 210px a side and left the note no room at
           all — it collapsed to one character per line. Tying both to the same
           length keeps the proportions and fixes that. */
        --keep-size: min(86vw, 380px);
        width: var(--keep-size);
        max-height: 88vh;
        aspect-ratio: 1;
        border-radius: 50%;
        background:
            radial-gradient(circle at 50% 12%, #33202A 0%, var(--card) 58%, #241419 100%);
        box-shadow: var(--shadow);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: calc(var(--keep-size) * 0.13) calc(var(--keep-size) * 0.15);
        transform: scale(.2) rotate(-16deg);
        transition: transform 820ms cubic-bezier(.22, 1.2, .36, 1);
        overflow: hidden;
    }

    .keepsake-wrap.open .keepsake {
        transform: scale(1) rotate(0deg);
    }

    .keepsake::before {
        content: '';
        position: absolute;
        inset: 12px;
        border-radius: 50%;
        border: 1px dashed var(--card-line);
        pointer-events: none;
    }

    .keepsake-title {
        /* Same reason as the note: these are centred flex children, so without
           a width they shrink-wrap and a long line pushes past the card. */
        width: 100%;
        overflow-wrap: break-word;
        font-family: var(--font-ui);
        font-size: 10px;
        letter-spacing: 3.4px;
        text-transform: uppercase;
        color: var(--secondary);
        opacity: .8;
        margin-bottom: 12px;
    }

    .keepsake-note {
        font-family: var(--font-hand);
        font-size: clamp(19px, 5.4vw, 25px);
        line-height: 1.6;
        color: var(--accent);
        white-space: pre-wrap;
        /* The card is a centred column, so without an explicit width the note
           shrink-wraps to its content — and `anywhere` wrapping then collapsed
           it to one character per line. Filling the padded box fixes both that
           and the sideways scroll a long note used to cause. */
        width: 100%;
        max-width: 100%;
        min-width: 0;
        overflow-wrap: break-word;
    }

    /* Auto-fit: the card is a fixed circle, so a longer note steps down. */
    .keepsake-note.sm {
        font-size: clamp(17px, 4.6vw, 21px);
        line-height: 1.5;
    }

    .keepsake-note.xs {
        font-size: clamp(14px, 3.9vw, 18px);
        line-height: 1.42;
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

    .keepsake-signoff {
        width: 100%;
        overflow-wrap: break-word;
        margin-top: 14px;
        font-family: var(--font-hand);
        font-size: 20px;
        color: var(--secondary);
        opacity: 0;
        transition: opacity 600ms ease;
    }

    .keepsake-signoff.show {
        opacity: 1;
    }

    .keepsake-end {
        width: 100%;
        overflow-wrap: break-word;
        margin-top: 10px;
        font-family: var(--font-display);
        font-style: italic;
        font-size: 10px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--accent);
        opacity: 0;
        transition: opacity 700ms ease;
    }

    .keepsake-end.show {
        opacity: .6;
    }

    .keepsake-heart {
        position: absolute;
        top: 9%;
        left: 50%;
        transform: translateX(-50%);
        font-size: 15px;
        color: var(--primary);
        opacity: 0;
        transition: opacity 500ms ease;
    }

    .keepsake-heart.show {
        opacity: 1;
        animation: beat 1.5s ease-in-out infinite;
    }

    @keyframes beat {

        0%,
        100% {
            transform: translateX(-50%) scale(1);
        }

        25% {
            transform: translateX(-50%) scale(1.22);
        }

        40% {
            transform: translateX(-50%) scale(1);
        }
    }

    @media (max-width: 380px) {
        .keepsake {
            padding: calc(var(--keep-size) * 0.12) calc(var(--keep-size) * 0.13);
        }

        .bloom-caption {
            margin-top: 18px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .flower,
        .drift,
        .sparkle,
        .keepsake-heart.show {
            animation: none !important;
        }
    }
    </style>
</head>

<body>
    <!-- ============ AMBIENCE ============ -->
    <div class="field" aria-hidden="true">
        <div class="haze one"></div>
        <div class="haze two"></div>
        <div class="haze three"></div>
        <div id="petalLayer"></div>
        <div id="sparkleLayer"></div>
    </div>

    <div class="stage">
        <!-- ============ THE CLOSED BUD ============ -->
        <div class="bloom-wrap" id="bloomWrap">
            <div class="flower" id="flower" tabindex="0" role="button" aria-label="Open the flower">
                <div class="leaf l"></div>
                <div class="leaf r"></div>
                <div class="petal back" style="--a:0deg"></div>
                <div class="petal back" style="--a:72deg"></div>
                <div class="petal back" style="--a:144deg"></div>
                <div class="petal back" style="--a:216deg"></div>
                <div class="petal back" style="--a:288deg"></div>
                <div class="petal" style="--a:36deg"></div>
                <div class="petal" style="--a:108deg"></div>
                <div class="petal" style="--a:180deg"></div>
                <div class="petal" style="--a:252deg"></div>
                <div class="petal" style="--a:324deg"></div>
                <div class="bud-core"></div>
            </div>

            <div class="bloom-caption">
                <h1>{{ request('title', 'One Last Bloom') }}</h1>
                <p>{{ request('subtitle', 'Something kept for the very end.') }}</p>
                <div class="bloom-tap">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 11.5V6a2 2 0 1 1 4 0v5"></path>
                        <path d="M13 6a2 2 0 1 1 4 0v6"></path>
                        <path
                            d="M17 8.5a2 2 0 1 1 4 0V14a7 7 0 0 1-7 7h-1a7 7 0 0 1-6-3.4L4.7 13a1.8 1.8 0 0 1 2.9-2.1L9 13">
                        </path>
                    </svg>
                    {{ request('tap_label', 'Tap to Bloom') }}
                </div>
            </div>
        </div>

        <!-- ============ THE KEEPSAKE CARD ============ -->
        <div class="keepsake-wrap" id="keepsakeWrap">
            <div class="keepsake">
                <div class="keepsake-heart" id="keepsakeHeart">❤</div>
                <div class="keepsake-title">{{ request('letter_heading', 'A Note For You') }}</div>
                <div class="keepsake-note {{ $endingLetterSize }}" id="keepsakeNote"><span class="pen-caret"></span>
                </div>
                <div class="keepsake-signoff" id="keepsakeSignoff">{{ request('signoff', '— always, me') }}</div>
                <div class="keepsake-end" id="keepsakeEnd">{{ request('end_label', 'The End') }}</div>
            </div>
        </div>
    </div>

    <script>
    const NOTE_TEXT = @json($endingLetter);

    /* ==================================================
       DRIFTING PETALS + SPARKLES
       ================================================== */
    (function buildField() {
        const petalLayer = document.getElementById('petalLayer');
        const sparkleLayer = document.getElementById('sparkleLayer');
        const small = window.innerWidth < 640;

        for (let i = 0; i < (small ? 14 : 24); i++) {
            const p = document.createElement('span');
            p.className = 'drift';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.setProperty('--s', (0.6 + Math.random() * 0.9).toFixed(2));
            p.style.setProperty('--peak', (0.4 + Math.random() * 0.45).toFixed(2));
            p.style.setProperty('--drift-x', (Math.random() * 160 - 80) + 'px');
            p.style.animationDuration = (11 + Math.random() * 12) + 's';
            p.style.animationDelay = (Math.random() * 14) + 's';
            petalLayer.appendChild(p);
        }

        for (let i = 0; i < (small ? 10 : 18); i++) {
            const s = document.createElement('span');
            s.className = 'sparkle';
            s.style.left = Math.random() * 100 + 'vw';
            s.style.top = Math.random() * 100 + 'vh';
            s.style.animationDuration = (2.4 + Math.random() * 2.6) + 's';
            s.style.animationDelay = (Math.random() * 4) + 's';
            sparkleLayer.appendChild(s);
        }
    })();

    /* ==================================================
       BLOOM -> KEEPSAKE -> HANDWRITING
       ================================================== */
    const flower = document.getElementById('flower');
    const bloomWrap = document.getElementById('bloomWrap');
    const keepsakeWrap = document.getElementById('keepsakeWrap');
    const keepsakeNote = document.getElementById('keepsakeNote');
    const keepsakeSignoff = document.getElementById('keepsakeSignoff');
    const keepsakeEnd = document.getElementById('keepsakeEnd');
    const keepsakeHeart = document.getElementById('keepsakeHeart');
    let opened = false;

    function writeNote(text) {
        keepsakeNote.innerHTML = '';
        const caret = document.createElement('span');
        caret.className = 'pen-caret';
        const node = document.createTextNode('');
        keepsakeNote.appendChild(node);
        keepsakeNote.appendChild(caret);

        let i = 0;

        function tick() {
            if (i >= text.length) {
                caret.remove();
                keepsakeSignoff.classList.add('show');
                setTimeout(() => keepsakeEnd.classList.add('show'), 480);
                return;
            }
            node.textContent += text[i];
            i++;
            const ch = text[i - 1];
            let delay = 32 + Math.random() * 24;
            if (ch === ',') delay += 130;
            if (ch === '.') delay += 240;
            if (ch === '\n') delay += 380;
            setTimeout(tick, delay);
        }
        tick();
    }

    function bloom() {
        if (opened) return;
        opened = true;

        flower.classList.add('open');
        // The petals take ~0.9s to unfurl; the card rises out of the middle of
        // that, so the two movements read as one.
        setTimeout(() => {
            bloomWrap.classList.add('gone');
            keepsakeWrap.classList.add('open');
        }, 620);
        setTimeout(() => keepsakeHeart.classList.add('show'), 1200);
        setTimeout(() => writeNote(NOTE_TEXT), 1400);
    }

    flower.addEventListener('click', bloom);
    flower.addEventListener('keyup', e => {
        if (e.key === 'Enter' || e.key === ' ') bloom();
    });

    /* ==================================================
       DASHBOARD PREVIEW
       ?preview_stage=note opens the card straight away and prints the note in
       full, so the client can see what they are typing without waiting out the
       bloom and the handwriting.
       ================================================== */
    if (@json(request('preview_stage')) === 'note') {
        opened = true;
        flower.classList.add('open');
        bloomWrap.classList.add('gone');
        keepsakeWrap.classList.add('open');
        keepsakeHeart.classList.add('show');
        keepsakeNote.textContent = NOTE_TEXT;
        keepsakeSignoff.classList.add('show');
        keepsakeEnd.classList.add('show');
    }
    </script>
</body>

</html>
