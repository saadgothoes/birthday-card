{{--
    Anniversary · Gift 1 — "Keepsake" memory page.

    One shared design, re-skinned four ways. The wrapper views
    (anniversary-page-3-variant-{V}-gift-1-page-{P}.blade.php) each @include
    this with a `giftTheme` (1-4) that selects the palette below. `variant`
    only decides which wrapper is hit — the gift itself looks the same across
    the four gift-screen variants, exactly like the boy/girl gift files.

    Request params (same names the public-story controller already feeds
    gift 1 / gift 2 with):
      photo1, photo2, photo3   memory photos (fall back to drawn scenes)
      name_first, name_second  the couple
      cal_month, cal_day       the date the medallion shows
      years                    "n years" line
      message, signed          the letter body + signature
--}}
@php
    $giftTheme = (int) ($giftTheme ?? request('theme', 1));

    $themes = [
        1 => ['name' => 'Taupe & Charcoal', 'bg1' => '#c7bca6', 'bg2' => '#8f7f65',
              'paper' => '#f7f2ea', 'paper2' => '#ece3d1', 'ink' => '#1c1a17',
              'accent' => '#141312', 'accent2' => '#5a5147', 'heart' => '#7a6a55',
              'tape' => 'rgba(20,19,18,.12)', 'scene' => '#8f7f65'],
        2 => ['name' => 'Maroon & Gold', 'bg1' => '#a35a56', 'bg2' => '#5c1420',
              'paper' => '#f6ecd6', 'paper2' => '#ecdcb8', 'ink' => '#3a1016',
              'accent' => '#a3792f', 'accent2' => '#c9a75c', 'heart' => '#8b1e28',
              'tape' => 'rgba(163,121,47,.22)', 'scene' => '#7a3d3c'],
        3 => ['name' => 'Ivory & Peach Gold', 'bg1' => '#d8b98c', 'bg2' => '#9c7c52',
              'paper' => '#faf5ea', 'paper2' => '#efe3cb', 'ink' => '#33281a',
              'accent' => '#e0a865', 'accent2' => '#b98544', 'heart' => '#b5673c',
              'tape' => 'rgba(224,168,101,.24)', 'scene' => '#b08f62'],
        4 => ['name' => 'Bright Red & White', 'bg1' => '#e3bcab', 'bg2' => '#c4917c',
              'paper' => '#fdf6f2', 'paper2' => '#f3ded4', 'ink' => '#5c1712',
              'accent' => '#e8281a', 'accent2' => '#ff6a5c', 'heart' => '#e8281a',
              'tape' => 'rgba(232,40,26,.16)', 'scene' => '#cf9880'],
    ];
    $t = $themes[$giftTheme] ?? $themes[1];

    $captions = ['The day we met', 'Somewhere in between', 'And ever since'];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $t['name'] }} — Anniversary Keepsake</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&display=swap"
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
        --tape: {{ $t['tape'] }};
        --scene: {{ $t['scene'] }};
    }

    * {
        box-sizing: border-box;
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
        align-items: center;
        justify-content: center;
        padding: 34px 16px;
        background: linear-gradient(160deg, var(--bg-1) 0%, var(--bg-2) 100%);
        position: relative;
        overflow-x: hidden;
    }

    .glow {
        position: fixed;
        border-radius: 50%;
        filter: blur(70px);
        opacity: 0.4;
        pointer-events: none;
        z-index: 0;
    }

    .glow.g1 {
        width: 360px;
        height: 360px;
        top: -110px;
        left: -110px;
        background: var(--accent-2);
    }

    .glow.g2 {
        width: 400px;
        height: 400px;
        bottom: -140px;
        right: -120px;
        background: var(--accent);
        opacity: 0.22;
    }

    .keepsake {
        position: relative;
        z-index: 1;
        width: 480px;
        max-width: 94vw;
        background: var(--paper);
        border-radius: 22px;
        padding: 26px 22px 28px;
        box-shadow: 0 36px 80px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.12);
    }

    .keepsake::before {
        content: '';
        position: absolute;
        inset: 10px;
        border: 1px solid var(--accent);
        border-radius: 15px;
        opacity: 0.35;
        pointer-events: none;
    }

    .kicker {
        text-align: center;
        color: var(--accent-2);
        letter-spacing: 0.32em;
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 700;
    }

    .couple {
        text-align: center;
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 40px;
        line-height: 1.05;
        color: var(--ink);
        margin: 4px 0 2px;
    }

    .couple .amp {
        color: var(--heart);
        padding: 0 6px;
    }

    .rule {
        width: 66px;
        height: 2px;
        margin: 10px auto 18px;
        background: var(--accent);
        opacity: 0.6;
    }

    /* ---------- polaroid strip ---------- */
    .strip {
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 6px 0 14px;
    }

    .polaroid {
        width: 33%;
        background: #fff;
        padding: 8px 8px 26px;
        border-radius: 3px;
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.28);
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .polaroid:nth-child(1) {
        transform: rotate(-6deg);
    }

    .polaroid:nth-child(2) {
        transform: translateY(-6px) rotate(1deg);
        z-index: 2;
    }

    .polaroid:nth-child(3) {
        transform: rotate(6deg);
    }

    .polaroid:hover,
    .polaroid:focus-visible {
        transform: translateY(-6px) rotate(0deg) scale(1.03);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.34);
        z-index: 3;
    }

    .polaroid::after {
        content: '';
        position: absolute;
        top: -9px;
        left: 50%;
        transform: translateX(-50%) rotate(-3deg);
        width: 54px;
        height: 18px;
        background: var(--tape);
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .polaroid .shot {
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: var(--paper-2);
        display: block;
    }

    .polaroid .shot img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .polaroid .shot svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    .polaroid .cap {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 7px;
        text-align: center;
        font-family: 'Dancing Script', cursive;
        font-size: 12.5px;
        color: #5b5147;
    }

    /* ---------- date medallion ---------- */
    .medallion {
        width: 138px;
        height: 138px;
        margin: 6px auto 16px;
        border-radius: 50%;
        background: radial-gradient(circle at 34% 30%, var(--accent-2), var(--accent) 78%);
        color: var(--paper);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.3), inset 0 0 0 4px rgba(255, 255, 255, 0.16);
    }

    .medallion .m-top {
        font-size: 9.5px;
        letter-spacing: 0.24em;
        text-transform: uppercase;
        opacity: 0.85;
    }

    .medallion .m-month {
        font-size: 13px;
        letter-spacing: 0.04em;
        margin-top: 3px;
    }

    .medallion .m-day {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 40px;
        line-height: 1;
    }

    .medallion .m-years {
        font-size: 9.5px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        opacity: 0.85;
        margin-top: 2px;
    }

    /* ---------- letter ---------- */
    .letter {
        background: var(--paper-2);
        border-radius: 12px;
        padding: 18px 20px 16px;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .letter:hover,
    .letter:focus-visible {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.22);
    }

    .letter::before {
        content: '\201C';
        position: absolute;
        top: -4px;
        left: 12px;
        font-family: 'Dancing Script', cursive;
        font-size: 48px;
        color: var(--accent);
        opacity: 0.5;
    }

    .letter p {
        margin: 0;
        padding-left: 16px;
        font-style: italic;
        font-size: 15.5px;
        line-height: 1.65;
        color: var(--ink);
    }

    .letter .signed {
        display: block;
        margin-top: 10px;
        padding-left: 16px;
        font-family: 'Dancing Script', cursive;
        font-style: normal;
        font-size: 19px;
        color: var(--heart);
    }

    .foot {
        text-align: center;
        margin-top: 16px;
        font-size: 12px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--accent-2);
        opacity: 0.85;
    }

    @media (max-width: 480px) {
        .keepsake {
            padding: 20px 14px 22px;
        }

        .couple {
            font-size: 32px;
        }

        .strip {
            gap: 4px;
        }

        .polaroid {
            padding: 6px 6px 22px;
        }

        .polaroid .cap {
            font-size: 11px;
        }

        .medallion {
            width: 120px;
            height: 120px;
        }

        .medallion .m-day {
            font-size: 34px;
        }

        .letter p {
            font-size: 14.5px;
        }
    }

    /* ---------- peek (click to zoom) ---------- */
    .peekbox {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.35s ease;
        padding: 9vw;
    }

    .peekbox.open {
        opacity: 1;
        pointer-events: auto;
    }

    .peek-inner {
        transform: scale(0.86);
        transition: transform 0.35s ease;
    }

    .peekbox.open .peek-inner {
        transform: scale(1);
    }

    .peek-inner .peek-clone {
        transform: none !important;
        margin: 0 !important;
        width: min(82vw, 380px) !important;
        pointer-events: none;
    }

    .peek-inner .peek-clone::after {
        display: none;
    }

    .peekbox-close {
        position: absolute;
        top: 5vh;
        right: 6vw;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.25s ease, transform 0.2s ease;
    }

    .peekbox-close:hover {
        background: rgba(255, 255, 255, 0.22);
    }

    .peekbox-close:active {
        transform: scale(0.9);
    }
    </style>
</head>

<body>

    <div class="glow g1"></div>
    <div class="glow g2"></div>

    <div class="keepsake">
        <div class="kicker">Our Anniversary</div>
        <div class="couple">
            <span>{{ request('name_first', 'Ayesha') }}</span><span class="amp">&amp;</span><span>{{ request('name_second', 'Bilal') }}</span>
        </div>
        <div class="rule"></div>

        <div class="strip">
            @foreach ([1, 2, 3] as $i)
            <div class="polaroid" tabindex="0">
                <div class="shot">
                    @if(request('photo' . $i))
                    <img src="{{ request('photo' . $i) }}" alt="">
                    @else
                    <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                        <rect width="120" height="120" fill="var(--paper-2)" />
                        <rect y="74" width="120" height="46" fill="var(--scene)" opacity="0.55" />
                        <g transform="translate(60,66)" fill="var(--scene)">
                            <circle cx="-9" cy="-16" r="7" />
                            <path d="M-20 -2 Q-9 -12 2 -2 L5 34 L-24 34 Z" />
                            <circle cx="11" cy="-18" r="7" />
                            <path d="M0 -4 Q11 -14 22 -4 L26 34 L-2 34 Z" />
                        </g>
                        <path d="M60 20 q7 -9 14 0 q7 9 -14 20 q-21 -11 -14 -20 q7 -9 14 0 z"
                            fill="var(--heart)" opacity="0.8" />
                    </svg>
                    @endif
                </div>
                <span class="cap">{{ $captions[$i - 1] }}</span>
            </div>
            @endforeach
        </div>

        <div class="medallion">
            <span class="m-top">On this day</span>
            <span class="m-month">{{ request('cal_month', 'September') }}</span>
            <span class="m-day">{{ request('cal_day', '14') }}</span>
            <span class="m-years">{{ request('years', '5') }} years</span>
        </div>

        <div class="letter" tabindex="0">
            <p>{{ request('message', "Every year with you has been the one I would choose again. Here's to every road still ahead — walked side by side.") }}</p>
            <span class="signed">{{ request('signed', '— always yours') }}</span>
        </div>

        <div class="foot">Still us &mdash; still choosing this</div>
    </div>

    <div class="peekbox" id="peekbox">
        <button class="peekbox-close" id="peekboxClose" aria-label="Close">&#10005;</button>
        <div class="peek-inner" id="peekInner"></div>
    </div>

    <script>
    (function() {
        'use strict';

        var peekbox = document.getElementById('peekbox');
        var peekInner = document.getElementById('peekInner');
        var peekboxClose = document.getElementById('peekboxClose');
        var peekTargets = document.querySelectorAll('.polaroid, .letter');

        function openPeek(el) {
            var clone = el.cloneNode(true);
            clone.classList.add('peek-clone');
            peekInner.innerHTML = '';
            peekInner.appendChild(clone);
            peekbox.classList.add('open');
        }

        function closePeek() {
            peekbox.classList.remove('open');
        }

        peekTargets.forEach(function(el) {
            el.addEventListener('click', function() {
                openPeek(el);
            });
            el.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openPeek(el);
                }
            });
        });

        peekboxClose.addEventListener('click', closePeek);
        peekbox.addEventListener('click', function(e) {
            if (e.target === peekbox) closePeek();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePeek();
        });
    })();
    </script>
</body>

</html>
