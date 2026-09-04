{{--
    Anniversary · Gift 2 — "Scratch to reveal" memory cards.

    One shared design, re-skinned four ways. Wrapper views
    (anniversary-page-3-variant-{V}-gift-2-page-{P}.blade.php) each @include this
    with a `giftTheme` (1-4) that picks the palette below. Like the boy/girl gift
    files, the gift is identical across the four gift-screen variants — only
    `giftPage` (the theme) changes the look.

    A stack of covered cards. Each is a foil panel the recipient scratches away
    with a finger to uncover a photo + a line about that memory. Scratch past
    ~55% and the rest clears on its own; "Next" moves to the following card. The
    last card is the anniversary message.

    Request params:
      name_first, name_second   the couple (header)
      message, signed           the final card
      memories                  JSON: [{date,title,text,photo}] — overrides below
      mem1_date / mem1_title / mem1_text / mem1_photo  … up to mem6_*  (flat form)
--}}
@php
    $giftTheme = (int) ($giftTheme ?? request('theme', 1));

    $themes = [
        1 => ['name' => 'Silver Taupe',
              'bg1' => '#c7bca6', 'bg2' => '#8f7f65',
              'paper' => '#f7f2ea', 'paper2' => '#ece3d1', 'ink' => '#1c1a17',
              'accent' => '#141312', 'accent2' => '#5a5147', 'heart' => '#7a6a55',
              'foilA' => '#cfc7b6', 'foilB' => '#efe9dc', 'foilC' => '#a29a89',
              'foilInk' => '#4a4437'],
        2 => ['name' => 'Maroon & Gold',
              'bg1' => '#a35a56', 'bg2' => '#5c1420',
              'paper' => '#f6ecd6', 'paper2' => '#ecdcb8', 'ink' => '#3a1016',
              'accent' => '#8b1e28', 'accent2' => '#a3792f', 'heart' => '#8b1e28',
              'foilA' => '#b8892f', 'foilB' => '#ecce85', 'foilC' => '#8f6420',
              'foilInk' => '#3f2c0b'],
        3 => ['name' => 'Ivory & Peach Gold',
              'bg1' => '#d8b98c', 'bg2' => '#9c7c52',
              'paper' => '#faf5ea', 'paper2' => '#efe3cb', 'ink' => '#33281a',
              'accent' => '#b5673c', 'accent2' => '#e0a865', 'heart' => '#b5673c',
              'foilA' => '#e0a865', 'foilB' => '#f4d8ac', 'foilC' => '#c2853f',
              'foilInk' => '#5a3a1c'],
        4 => ['name' => 'Bright Red & White',
              'bg1' => '#e3bcab', 'bg2' => '#c4917c',
              'paper' => '#fdf6f2', 'paper2' => '#f3ded4', 'ink' => '#5c1712',
              'accent' => '#e8281a', 'accent2' => '#ff6a5c', 'heart' => '#e8281a',
              'foilA' => '#e8281a', 'foilB' => '#ff8478', 'foilC' => '#b81c11',
              'foilInk' => '#ffece9'],
    ];
    $t = $themes[$giftTheme] ?? $themes[1];

    $memories = [];
    if ($raw = request('memories')) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $memories = $decoded;
        }
    }
    if (! $memories) {
        for ($i = 1; $i <= 6; $i++) {
            if (request("mem{$i}_title") || request("mem{$i}_text")) {
                $memories[] = [
                    'date' => request("mem{$i}_date"),
                    'title' => request("mem{$i}_title"),
                    'text' => request("mem{$i}_text"),
                    'photo' => request("mem{$i}_photo"),
                ];
            }
        }
    }
    if (! $memories) {
        $memories = [
            ['date' => 'August 2019', 'title' => 'The first hello', 'text' => 'A crowded room, and somehow only one conversation that mattered.'],
            ['date' => 'December 2019', 'title' => 'The first trip', 'text' => 'We got lost twice and did not mind once.'],
            ['date' => 'June 2021', 'title' => 'Our first home', 'text' => 'Two boxes, one lamp, and a floor picnic that lasted till morning.'],
            ['date' => 'September 2022', 'title' => 'The proposal', 'text' => 'You said the speech was too long. You still said yes.'],
        ];
    }
    $memories = array_slice(array_values($memories), 0, 6);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $t['name'] }} — Scratch The Memories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Dancing+Script:wght@600;700&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --bg-1: {{ $t['bg1'] }};
        --bg-2: {{ $t['bg2'] }};
        --paper: {{ $t['paper'] }};
        --paper-2: {{ $t['paper2'] }};
        --ink: {{ $t['ink'] }};
        --accent: {{ $t['accent'] }};
        --accent-2: {{ $t['accent2'] }};
        --heart: {{ $t['heart'] }};
    }

    * {
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        min-height: 100%;
    }

    body {
        font-family: 'Cormorant Garamond', serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 18px;
        padding: 30px 16px;
        background: linear-gradient(160deg, var(--bg-1) 0%, var(--bg-2) 100%);
        color: var(--ink);
        overflow-x: hidden;
    }

    .glow {
        position: fixed;
        border-radius: 50%;
        filter: blur(70px);
        opacity: 0.35;
        pointer-events: none;
        z-index: 0;
    }

    .glow.g1 {
        width: 340px;
        height: 340px;
        top: -100px;
        left: -100px;
        background: var(--accent-2);
    }

    .glow.g2 {
        width: 380px;
        height: 380px;
        bottom: -130px;
        right: -110px;
        background: var(--accent);
        opacity: 0.2;
    }

    .head {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .kicker {
        font-size: 11px;
        letter-spacing: 0.34em;
        text-transform: uppercase;
        color: var(--paper);
        opacity: 0.85;
    }

    .couple {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: clamp(28px, 6vw, 38px);
        line-height: 1.05;
        color: var(--paper);
        margin-top: 2px;
        text-shadow: 0 2px 18px rgba(0, 0, 0, 0.35);
    }

    /* ---------- card ---------- */
    .card-wrap {
        position: relative;
        z-index: 1;
        width: 340px;
        max-width: 90vw;
    }

    .card-wrap::before,
    .card-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        background: var(--paper);
        box-shadow: 0 20px 44px rgba(0, 0, 0, 0.4);
    }

    .card-wrap::before {
        transform: rotate(-3deg) translateY(6px) scale(0.97);
        opacity: 0.55;
    }

    .card-wrap::after {
        transform: rotate(2deg) translateY(3px) scale(0.985);
        opacity: 0.8;
    }

    .card {
        position: relative;
        z-index: 2;
        width: 100%;
        border-radius: 20px;
        background: var(--paper);
        padding: 14px 14px 20px;
        box-shadow: 0 26px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.14);
        overflow: hidden;
        min-height: 380px;
    }

    .card::after {
        content: '';
        position: absolute;
        inset: 8px;
        border: 1px solid var(--accent);
        border-radius: 13px;
        opacity: 0.28;
        pointer-events: none;
    }

    .c-photo {
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 12px;
        overflow: hidden;
        display: block;
        background: var(--paper-2);
    }

    .c-photo img,
    .c-photo svg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .c-body {
        padding: 14px 8px 2px;
        text-align: center;
    }

    .c-date {
        font-size: 11.5px;
        letter-spacing: 0.24em;
        text-transform: uppercase;
        color: var(--accent-2);
    }

    .c-title {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 30px;
        line-height: 1.12;
        color: var(--ink);
        margin: 3px 0 9px;
    }

    .c-text {
        margin: 0;
        font-style: italic;
        font-size: 15.5px;
        line-height: 1.62;
        color: var(--ink);
        opacity: 0.92;
    }

    .c-signed {
        display: block;
        margin-top: 12px;
        font-family: 'Dancing Script', cursive;
        font-style: normal;
        font-size: 19px;
        color: var(--heart);
    }

    /* ---------- foil overlay ---------- */
    .foil {
        position: absolute;
        inset: 0;
        z-index: 3;
        border-radius: 20px;
        touch-action: none;
        cursor: grab;
        transition: opacity 0.6s ease;
    }

    .foil:active {
        cursor: grabbing;
    }

    .foil.cleared {
        opacity: 0;
        pointer-events: none;
    }

    .scratch-hint {
        position: absolute;
        inset: 0;
        z-index: 4;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        pointer-events: none;
        color: {{ $t['foilInk'] }};
        font-size: 13px;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        transition: opacity 0.4s ease;
    }

    .scratch-hint .coin {
        font-size: 34px;
        animation: nudge 1.6s ease-in-out infinite;
    }

    @keyframes nudge {

        0%,
        100% {
            transform: translate(-8px, 6px) rotate(-8deg);
        }

        50% {
            transform: translate(10px, -6px) rotate(8deg);
        }
    }

    .scratch-hint.hide {
        opacity: 0;
    }

    /* ---------- progress + next ---------- */
    .controls {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
    }

    .dots {
        display: flex;
        gap: 8px;
    }

    .dots span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--paper);
        opacity: 0.4;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .dots span.done {
        opacity: 0.75;
    }

    .dots span.active {
        opacity: 1;
        transform: scale(1.5);
    }

    .next {
        background: transparent;
        color: var(--paper);
        border: 1px solid rgba(255, 255, 255, 0.55);
        padding: 11px 34px;
        border-radius: 40px;
        font-family: 'Cormorant Garamond', serif;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        cursor: pointer;
        opacity: 0;
        transform: translateY(6px);
        pointer-events: none;
        transition: opacity 0.4s ease, transform 0.4s ease, background 0.25s ease;
    }

    .next.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .next:hover {
        background: rgba(255, 255, 255, 0.14);
    }

    .next::after {
        content: ' \2192';
        opacity: 0.6;
    }

    .next.final::after {
        content: '';
    }

    @media (max-width: 480px) {
        .card {
            min-height: 340px;
        }

        .c-title {
            font-size: 26px;
        }
    }
    </style>
</head>

<body>

    <div class="glow g1"></div>
    <div class="glow g2"></div>

    <div class="head">
        <div class="kicker">Our Anniversary</div>
        <div class="couple">{{ request('name_first', 'Ayesha') }} &amp; {{ request('name_second', 'Bilal') }}</div>
    </div>

    <div class="card-wrap">
        <div class="card" id="card">
            <div class="c-photo" id="cPhoto"></div>
            <div class="c-body">
                <div class="c-date" id="cDate"></div>
                <div class="c-title" id="cTitle"></div>
                <p class="c-text" id="cText"></p>
                <span class="c-signed" id="cSigned" hidden></span>
            </div>
        </div>
        <canvas class="foil" id="foil"></canvas>
        <div class="scratch-hint" id="hint">
            <span class="coin">&#128070;</span>
            <span>Scratch to reveal</span>
        </div>
    </div>

    <div class="controls">
        <div class="dots" id="dots"></div>
        <button class="next" id="next" type="button">Next</button>
    </div>

    <script>
    (function() {
        'use strict';

        var MEMORIES = @json($memories, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        var FINAL = {
            date: 'Happy Anniversary',
            title: @json(request('name_first', 'Ayesha') . ' & ' . request('name_second', 'Bilal')),
            text: @json(request('message', "Every year with you has been the one I would choose again. Here's to every road still ahead — walked side by side.")),
            signed: @json(request('signed', '— always yours')),
            isFinal: true
        };
        var STEPS = MEMORIES.concat([FINAL]);

        var FOIL = {
            a: @json($t['foilA']),
            b: @json($t['foilB']),
            c: @json($t['foilC']),
            ink: @json($t['foilInk'])
        };

        // ---- drawn scenes, so a card is never a bare page even with no photo ----
        var SC = {
            sky: @json($t['paper2']),
            fig: @json($t['accent2']),
            deep: @json($t['accent']),
            heart: @json($t['heart']),
            paper: @json($t['paper'])
        };

        function sceneSvg(kind) {
            var s = SC;
            var head = '<svg viewBox="0 0 200 150" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">';
            var bg = '<rect width="200" height="150" fill="' + s.sky + '"/>';
            var couple =
                '<g fill="' + s.fig + '">' +
                '<circle cx="86" cy="83" r="12"/><path d="M68 104 q18 -18 36 0 l4 40 h-44 z"/>' +
                '<circle cx="116" cy="78" r="12"/><path d="M100 100 q16 -18 34 0 l3 44 h-40 z" opacity="0.92"/>' +
                '</g>';
            if (kind === 'meet') {
                return head + bg +
                    '<circle cx="150" cy="34" r="16" fill="' + s.deep + '" opacity="0.3"/>' +
                    '<g stroke="' + s.deep + '" stroke-width="2" opacity="0.55">' +
                    '<path d="M40 30 l6 12 M52 24 l0 14 M30 46 l12 4"/></g>' +
                    couple +
                    '<path d="M101 58 q6 -9 12 0 q6 9 -12 19 q-18 -10 -12 -19 q6 -9 12 0z" fill="' + s.heart + '"/>' +
                    '</svg>';
            }
            if (kind === 'trip') {
                return head + bg +
                    '<path d="M0 150 L52 74 L96 150 Z" fill="' + s.fig + '" opacity="0.55"/>' +
                    '<path d="M70 150 L128 60 L200 150 Z" fill="' + s.deep + '" opacity="0.4"/>' +
                    '<circle cx="164" cy="34" r="13" fill="' + s.deep + '" opacity="0.35"/>' +
                    couple + '</svg>';
            }
            if (kind === 'home') {
                return head + bg +
                    '<path d="M56 150 V70 L100 40 L144 70 V150 Z" fill="' + s.fig + '" opacity="0.5"/>' +
                    '<path d="M48 74 L100 36 L152 74" fill="none" stroke="' + s.deep + '" stroke-width="3"/>' +
                    '<rect x="90" y="104" width="20" height="46" fill="' + s.deep + '" opacity="0.55"/>' +
                    '<circle cx="100" cy="88" r="7" fill="' + s.heart + '"/>' +
                    '</svg>';
            }
            if (kind === 'ring') {
                return head + bg +
                    couple +
                    '<g transform="translate(150,44)">' +
                    '<circle r="14" fill="none" stroke="' + s.deep + '" stroke-width="4"/>' +
                    '<path d="M0 -14 l6 -10 h-12 z" fill="' + s.heart + '"/></g>' +
                    '</svg>';
            }
            // letter — for the final card
            return head + bg +
                '<rect x="42" y="40" width="116" height="78" rx="4" fill="' + s.paper + '" stroke="' + s.deep + '" stroke-width="2"/>' +
                '<path d="M42 44 L100 86 L158 44" fill="none" stroke="' + s.deep + '" stroke-width="2"/>' +
                '<path d="M92 92 q8 -12 16 0 q8 12 -16 22 q-24 -10 -16 -22 q8 -12 16 0z" fill="' + s.heart + '"/>' +
                '</svg>';
        }

        var SCENE_ORDER = ['meet', 'trip', 'home', 'ring'];

        var card = document.getElementById('card');
        var foil = document.getElementById('foil');
        var fx = foil.getContext('2d');
        var hint = document.getElementById('hint');
        var nextBtn = document.getElementById('next');
        var dotsWrap = document.getElementById('dots');

        var cPhoto = document.getElementById('cPhoto');
        var cDate = document.getElementById('cDate');
        var cTitle = document.getElementById('cTitle');
        var cText = document.getElementById('cText');
        var cSigned = document.getElementById('cSigned');

        var step = 0;
        var cleared = false;
        var drawing = false;
        var lastPt = null;

        // ---- progress dots ----
        STEPS.forEach(function() {
            dotsWrap.appendChild(document.createElement('span'));
        });
        function paintDots() {
            Array.prototype.forEach.call(dotsWrap.children, function(d, i) {
                d.className = i < step ? 'done' : (i === step ? 'active' : '');
            });
        }

        // ---- fill the foil ----
        function sizeFoil() {
            var r = card.getBoundingClientRect();
            var dpr = Math.min(window.devicePixelRatio || 1, 2);
            foil.width = r.width * dpr;
            foil.height = r.height * dpr;
            foil.style.width = r.width + 'px';
            foil.style.height = r.height + 'px';
            fx.setTransform(dpr, 0, 0, dpr, 0, 0);
            paintFoil(r.width, r.height);
        }

        function paintFoil(w, h) {
            fx.globalCompositeOperation = 'source-over';
            var g = fx.createLinearGradient(0, 0, w, h);
            g.addColorStop(0, FOIL.a);
            g.addColorStop(0.45, FOIL.b);
            g.addColorStop(0.55, FOIL.a);
            g.addColorStop(1, FOIL.c);
            fx.fillStyle = g;
            fx.fillRect(0, 0, w, h);

            // brushed-metal streaks
            fx.globalAlpha = 0.10;
            fx.strokeStyle = '#ffffff';
            fx.lineWidth = 1;
            for (var i = -h; i < w; i += 7) {
                fx.beginPath();
                fx.moveTo(i, 0);
                fx.lineTo(i + h, h);
                fx.stroke();
            }
            fx.globalAlpha = 1;

            // sheen band
            var sh = fx.createLinearGradient(0, 0, w, h);
            sh.addColorStop(0.30, 'rgba(255,255,255,0)');
            sh.addColorStop(0.48, 'rgba(255,255,255,0.35)');
            sh.addColorStop(0.66, 'rgba(255,255,255,0)');
            fx.fillStyle = sh;
            fx.fillRect(0, 0, w, h);
        }

        // ---- scratching ----
        function ptFromEvent(e) {
            var r = foil.getBoundingClientRect();
            var p = e.touches ? e.touches[0] : e;
            return { x: p.clientX - r.left, y: p.clientY - r.top };
        }

        function scratch(a, b) {
            fx.globalCompositeOperation = 'destination-out';
            fx.lineWidth = 42;
            fx.lineCap = 'round';
            fx.lineJoin = 'round';
            fx.beginPath();
            fx.moveTo(a.x, a.y);
            fx.lineTo(b.x, b.y);
            fx.stroke();
            fx.beginPath();
            fx.arc(b.x, b.y, 21, 0, Math.PI * 2);
            fx.fill();
        }

        var checkTick = 0;
        function maybeReveal() {
            if (cleared) return;
            if (++checkTick % 6 !== 0) return;
            var w = foil.width, h = foil.height;
            var img = fx.getImageData(0, 0, w, h).data;
            var clearPx = 0, total = 0;
            for (var i = 3; i < img.length; i += 40) {
                total++;
                if (img[i] < 24) clearPx++;
            }
            if (clearPx / total > 0.55) revealCard();
        }

        function revealCard() {
            if (cleared) return;
            cleared = true;
            foil.classList.add('cleared');
            hint.classList.add('hide');
            nextBtn.classList.add('show');
        }

        function onDown(e) {
            if (cleared) return;
            drawing = true;
            lastPt = ptFromEvent(e);
            hint.classList.add('hide');
            e.preventDefault();
        }
        function onMove(e) {
            if (!drawing || cleared) return;
            var p = ptFromEvent(e);
            scratch(lastPt, p);
            lastPt = p;
            maybeReveal();
            e.preventDefault();
        }
        function onUp() {
            drawing = false;
            lastPt = null;
        }

        foil.addEventListener('pointerdown', onDown);
        window.addEventListener('pointermove', onMove, { passive: false });
        window.addEventListener('pointerup', onUp);
        foil.addEventListener('touchstart', onDown, { passive: false });
        window.addEventListener('touchmove', onMove, { passive: false });
        window.addEventListener('touchend', onUp);

        // ---- load a step ----
        function loadStep(i) {
            step = i;
            cleared = false;
            checkTick = 0;
            var m = STEPS[i];

            if (m.photo) {
                var im = document.createElement('img');
                im.alt = '';
                im.src = m.photo;
                cPhoto.innerHTML = '';
                cPhoto.appendChild(im);
            } else {
                var kind = m.isFinal ? 'letter' : SCENE_ORDER[i % SCENE_ORDER.length];
                cPhoto.innerHTML = sceneSvg(kind);
            }
            cDate.textContent = m.date || '';
            cTitle.textContent = m.title || 'A memory';
            cText.textContent = m.text || '';
            if (m.signed) {
                cSigned.textContent = m.signed;
                cSigned.hidden = false;
            } else {
                cSigned.hidden = true;
            }

            foil.classList.remove('cleared');
            hint.classList.remove('hide');
            nextBtn.classList.remove('show');
            nextBtn.textContent = i >= STEPS.length - 1 ? 'Read again' : 'Next';
            nextBtn.classList.toggle('final', i >= STEPS.length - 1);

            paintDots();
            requestAnimationFrame(sizeFoil);
        }

        nextBtn.addEventListener('click', function() {
            if (step >= STEPS.length - 1) {
                loadStep(0);
            } else {
                loadStep(step + 1);
            }
        });

        window.addEventListener('resize', function() {
            if (!cleared) sizeFoil();
        });

        loadStep(0);
    })();
    </script>
</body>

</html>
