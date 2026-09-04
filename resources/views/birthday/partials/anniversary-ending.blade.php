{{--
    Anniversary · Ending page — "Blow out the candles".

    One shared design, re-skinned four ways. The wrapper views
    (anniversary-page-4{,-2,-3,-4}.blade.php) each @include this with an
    `endingTheme` (1-4) that selects the palette below — same variant convention
    as boy/girl (variant 1 never appends a suffix) and the same shared-partial
    shape as the anniversary gifts.

    Two lit taper candles on an engraved base. Tap anywhere (or the candles) and
    the flames blow out — a puff, a rising curl of smoke, the room dims — then a
    closing note fades up: the anniversary message, the years, a signature and
    "the end". "Relight" plays it again.

    Request params:
      name_first, name_second   the couple (Ayesha / Bilal)
      years                     the "n years" on the base + closing line (5)
      message, signed           the closing note + signature
      heading                   small kicker over the names ("Our Anniversary")
      wish_label                line above the candles ("Make a wish")
      tap_label                 hint under the candles
      end_label                 the closing tag ("the end")
      preview_stage=out         skip straight to the blown-out note (wiring / preview)
--}}
@php
    $endingTheme = (int) ($endingTheme ?? request('theme', 1));

    $themes = [
        1 => ['name' => 'Taupe & Charcoal',
              'bg1' => '#3b362d', 'bg2' => '#211f1a', 'bg3' => '#14130f',
              'wax1' => '#f7f2ea', 'wax2' => '#d8cdb4', 'ink' => '#f4efe4',
              'accent' => '#c9bfa8', 'accent2' => '#8f8674', 'heart' => '#c58f6f',
              'glow' => 'rgba(255,206,140,.55)'],
        2 => ['name' => 'Maroon & Gold',
              'bg1' => '#4d121a', 'bg2' => '#280910', 'bg3' => '#170509',
              'wax1' => '#f6ecd6', 'wax2' => '#e0cba0', 'ink' => '#f6ecd6',
              'accent' => '#c9a75c', 'accent2' => '#a3792f', 'heart' => '#e6a95c',
              'glow' => 'rgba(255,196,120,.6)'],
        3 => ['name' => 'Ivory & Peach Gold',
              'bg1' => '#5b4632', 'bg2' => '#332619', 'bg3' => '#1f1710',
              'wax1' => '#faf5ea', 'wax2' => '#e6d3b2', 'ink' => '#faf5ea',
              'accent' => '#e0a865', 'accent2' => '#b98544', 'heart' => '#eab97e',
              'glow' => 'rgba(255,214,150,.6)'],
        4 => ['name' => 'Bright Red & White',
              'bg1' => '#5c1712', 'bg2' => '#2c0a08', 'bg3' => '#170403',
              'wax1' => '#fdf6f2', 'wax2' => '#f0d8cc', 'ink' => '#fdf6f2',
              'accent' => '#ff9a89', 'accent2' => '#e8281a', 'heart' => '#ff8a72',
              'glow' => 'rgba(255,190,140,.62)'],
    ];
    $t = $themes[$endingTheme] ?? $themes[1];

    $nameFirst  = request('name_first', 'Ayesha');
    $nameSecond = request('name_second', 'Bilal');
    $years      = request('years', '5');
    $previewOut = request('preview_stage') === 'out';
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $t['name'] }} — Make a Wish</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Dancing+Script:wght@600;700&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --bg-1: {{ $t['bg1'] }};
        --bg-2: {{ $t['bg2'] }};
        --bg-3: {{ $t['bg3'] }};
        --wax-1: {{ $t['wax1'] }};
        --wax-2: {{ $t['wax2'] }};
        --ink: {{ $t['ink'] }};
        --accent: {{ $t['accent'] }};
        --accent-2: {{ $t['accent2'] }};
        --heart: {{ $t['heart'] }};
        --glow: {{ $t['glow'] }};
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
        color: var(--ink);
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 34px 18px;
        overflow: hidden;
        background:
            radial-gradient(120% 90% at 50% 12%, var(--bg-1) 0%, var(--bg-2) 55%, var(--bg-3) 100%);
        position: relative;
        cursor: pointer;
    }

    /* the room darkens once the candles are out */
    body::after {
        content: '';
        position: fixed;
        inset: 0;
        background: radial-gradient(80% 60% at 50% 42%, transparent 0%, rgba(0, 0, 0, 0.55) 100%);
        opacity: 0;
        transition: opacity 1.4s ease;
        pointer-events: none;
        z-index: 2;
    }

    body.blown::after {
        opacity: 1;
    }

    /* ---------- ambient ---------- */
    .ambient {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
        transition: opacity 1.4s ease;
    }

    body.blown .ambient {
        opacity: 0.4;
    }

    .spark {
        position: absolute;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--accent);
        box-shadow: 0 0 10px 2px var(--glow);
        animation: twinkle 3.4s ease-in-out infinite;
    }

    @keyframes twinkle {

        0%,
        100% {
            opacity: 0.12;
            transform: scale(0.7);
        }

        50% {
            opacity: 0.9;
            transform: scale(1.25);
        }
    }

    .float-heart {
        position: absolute;
        color: var(--heart);
        opacity: 0;
        animation: rise linear infinite;
        filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.3));
    }

    @keyframes rise {
        0% {
            transform: translateY(20px) scale(0.7) rotate(var(--rot, 0deg));
            opacity: 0;
        }

        12% {
            opacity: var(--peak, 0.5);
        }

        88% {
            opacity: var(--peak, 0.5);
        }

        100% {
            transform: translateY(-88vh) scale(1) rotate(var(--rot, 0deg));
            opacity: 0;
        }
    }

    /* ---------- scene ---------- */
    .scene {
        position: relative;
        z-index: 1;
        width: 380px;
        max-width: 92vw;
        text-align: center;
    }

    .kicker {
        font-size: 11px;
        letter-spacing: 0.34em;
        text-transform: uppercase;
        color: var(--accent);
        opacity: 0.9;
    }

    .couple {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: clamp(30px, 8vw, 42px);
        line-height: 1.04;
        margin: 4px 0 0;
        text-shadow: 0 3px 26px rgba(0, 0, 0, 0.4);
    }

    .couple span {
        color: var(--heart);
        padding: 0 6px;
    }

    /* ---------- candles ---------- */
    .cake {
        position: relative;
        width: 210px;
        height: 250px;
        margin: 30px auto 6px;
    }

    .candle {
        position: absolute;
        bottom: 46px;
        width: 26px;
        height: 118px;
        border-radius: 7px 7px 3px 3px;
        background: linear-gradient(100deg, var(--wax-2) 0%, var(--wax-1) 45%, var(--wax-2) 100%);
        box-shadow: inset -6px 0 10px rgba(0, 0, 0, 0.18), inset 5px 0 8px rgba(255, 255, 255, 0.4),
            0 10px 20px rgba(0, 0, 0, 0.3);
    }

    .candle.left {
        left: 62px;
        height: 128px;
    }

    .candle.right {
        right: 62px;
    }

    /* drip lip */
    .candle::before {
        content: '';
        position: absolute;
        top: -4px;
        left: 0;
        right: 0;
        height: 8px;
        border-radius: 50%;
        background: var(--wax-1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .wick {
        position: absolute;
        top: -12px;
        left: 50%;
        width: 2px;
        height: 12px;
        margin-left: -1px;
        background: #3a2a1c;
        border-radius: 1px;
    }

    .flame {
        position: absolute;
        top: -40px;
        left: 50%;
        width: 16px;
        height: 30px;
        margin-left: -8px;
        border-radius: 50% 50% 22% 22% / 62% 62% 40% 40%;
        background: linear-gradient(to top, #ff7a1a 0%, #ffb648 45%, #ffe6a8 78%, #fffdf3 100%);
        transform-origin: 50% 100%;
        animation: flicker 1.5s ease-in-out infinite alternate;
        box-shadow: 0 0 34px 12px var(--glow), 0 -6px 20px 6px var(--glow);
        z-index: 3;
    }

    .flame::before {
        /* hot blue base */
        content: '';
        position: absolute;
        left: 50%;
        bottom: -2px;
        width: 8px;
        height: 12px;
        margin-left: -4px;
        border-radius: 50%;
        background: radial-gradient(circle at 50% 70%, rgba(120, 170, 255, 0.9), transparent 70%);
    }

    @keyframes flicker {
        0% {
            transform: scaleY(1) scaleX(1) rotate(-2deg);
        }

        30% {
            transform: scaleY(1.08) scaleX(0.94) rotate(1.5deg);
        }

        60% {
            transform: scaleY(0.96) scaleX(1.04) rotate(-1deg);
        }

        100% {
            transform: scaleY(1.05) scaleX(0.97) rotate(2deg);
        }
    }

    .smoke {
        position: absolute;
        top: -46px;
        left: 50%;
        width: 8px;
        height: 46px;
        margin-left: -4px;
        border-radius: 50%;
        background: linear-gradient(to top, rgba(220, 220, 220, 0.55), rgba(220, 220, 220, 0));
        filter: blur(3px);
        opacity: 0;
        transform-origin: 50% 100%;
        z-index: 2;
    }

    /* base / holder */
    .holder {
        position: absolute;
        left: 50%;
        bottom: 20px;
        width: 190px;
        height: 34px;
        margin-left: -95px;
        border-radius: 10px / 40%;
        background: linear-gradient(180deg, var(--accent) 0%, var(--accent-2) 100%);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.4), inset 0 2px 4px rgba(255, 255, 255, 0.3);
    }

    .holder::before {
        content: '';
        position: absolute;
        left: 14px;
        right: 14px;
        top: 10px;
        height: 1px;
        background: rgba(0, 0, 0, 0.18);
    }

    .years-plate {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 25px;
        font-size: 10px;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: rgba(0, 0, 0, 0.55);
        z-index: 1;
    }

    .years-plate b {
        font-weight: 700;
        letter-spacing: 0.02em;
        margin-right: 0.55em;
    }

    /* glow pool on the base under the flames */
    .cake::after {
        content: '';
        position: absolute;
        left: 50%;
        top: 42px;
        width: 220px;
        height: 90px;
        margin-left: -110px;
        border-radius: 50%;
        background: radial-gradient(ellipse at center, var(--glow), transparent 68%);
        opacity: 0.9;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }

    /* ---------- blown-out state ---------- */
    .cake.out .flame {
        animation: none;
        transform: scale(0.12) translateY(8px) rotate(12deg);
        opacity: 0;
        box-shadow: none;
        transition: transform 0.32s ease, opacity 0.32s ease, box-shadow 0.32s ease;
    }

    .cake.out::after {
        opacity: 0;
    }

    .cake.out .smoke {
        animation: smoke 2.4s ease-out forwards;
    }

    .cake.out .candle.right .smoke {
        animation-delay: 0.12s;
    }

    @keyframes smoke {
        0% {
            opacity: 0;
            transform: translateY(0) scaleY(0.3) scaleX(1);
        }

        15% {
            opacity: 0.7;
        }

        60% {
            opacity: 0.45;
        }

        100% {
            opacity: 0;
            transform: translateY(-96px) scaleY(1.5) scaleX(3.4) rotate(8deg);
        }
    }

    /* ---------- hint ---------- */
    .hint {
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .hint.gone {
        opacity: 0;
        transform: translateY(-6px);
        pointer-events: none;
    }

    .wish {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 24px;
        color: var(--accent);
    }

    .tap {
        margin-top: 4px;
        font-size: 11px;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--ink);
        opacity: 0.55;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 0.35;
        }

        50% {
            opacity: 0.75;
        }
    }

    /* ---------- farewell ---------- */
    .farewell {
        position: relative;
        margin-top: 14px;
        opacity: 0;
        transform: translateY(14px);
        transition: opacity 1s ease, transform 1s ease;
        pointer-events: none;
    }

    .farewell.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .farewell .msg {
        margin: 0 auto;
        max-width: 320px;
        font-style: italic;
        font-size: 16px;
        line-height: 1.65;
    }

    .farewell .yrs {
        margin-top: 12px;
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 22px;
        color: var(--heart);
    }

    .farewell .sig {
        margin-top: 4px;
        font-family: 'Dancing Script', cursive;
        font-size: 19px;
        color: var(--accent);
    }

    .farewell .end {
        margin-top: 18px;
        font-size: 10px;
        letter-spacing: 0.4em;
        text-transform: uppercase;
        color: var(--ink);
        opacity: 0.5;
    }

    .relight {
        margin-top: 20px;
        background: transparent;
        color: var(--ink);
        border: 1px solid rgba(255, 255, 255, 0.35);
        padding: 9px 26px;
        border-radius: 40px;
        font-family: 'Cormorant Garamond', serif;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.26em;
        text-transform: uppercase;
        cursor: pointer;
        opacity: 0.8;
        transition: background 0.25s ease, opacity 0.25s ease;
    }

    .relight:hover {
        background: rgba(255, 255, 255, 0.12);
        opacity: 1;
    }

    @media (max-width: 380px) {
        .cake {
            transform: scale(0.9);
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .flame,
        .tap,
        .spark,
        .float-heart {
            animation-duration: 0.001s !important;
        }
    }
    </style>
</head>

<body>

    <div class="ambient" id="ambient"></div>

    <div class="scene" id="scene">
        <div class="kicker">{{ request('heading', 'Our Anniversary') }}</div>
        <div class="couple">{{ $nameFirst }} <span>&amp;</span> {{ $nameSecond }}</div>

        <div class="cake" id="cake">
            <div class="candle left">
                <span class="wick"></span>
                <span class="flame"></span>
                <span class="smoke"></span>
            </div>
            <div class="candle right">
                <span class="wick"></span>
                <span class="flame"></span>
                <span class="smoke"></span>
            </div>
            <div class="holder"></div>
            <div class="years-plate"><b>{{ $years }}</b>years</div>
        </div>

        <div class="hint" id="hint">
            <div class="wish">{{ request('wish_label', 'Make a wish') }}</div>
            <div class="tap">{{ request('tap_label', 'tap to blow them out') }}</div>
        </div>

        <div class="farewell" id="farewell">
            <p class="msg">{{ request('message', "Every year with you has been the one I would choose again. Here's to every road still ahead — walked side by side.") }}</p>
            <div class="yrs">{{ $years }} years — and every one after</div>
            <div class="sig">{{ request('signed', '— always yours') }}</div>
            <div class="end">{{ request('end_label', 'the end') }}</div>
            <button class="relight" id="relight" type="button">relight</button>
        </div>
    </div>

    <script>
    (function() {
        'use strict';

        var body = document.body;
        var cake = document.getElementById('cake');
        var hint = document.getElementById('hint');
        var farewell = document.getElementById('farewell');
        var relight = document.getElementById('relight');
        var ambient = document.getElementById('ambient');
        var blown = false;
        var timer = null;

        // ---- ambient: sparks + a few drifting hearts ----
        (function build() {
            var small = window.innerWidth < 640;
            var sparks = small ? 12 : 20;
            var hearts = small ? 7 : 12;
            var glyphs = ['♥', '❤', '♡'];

            for (var i = 0; i < sparks; i++) {
                var s = document.createElement('span');
                s.className = 'spark';
                s.style.left = (Math.random() * 98) + 'vw';
                s.style.top = (Math.random() * 96) + 'vh';
                s.style.animationDelay = (Math.random() * 3.4) + 's';
                s.style.animationDuration = (2.6 + Math.random() * 2.6) + 's';
                ambient.appendChild(s);
            }
            for (var j = 0; j < hearts; j++) {
                var h = document.createElement('span');
                h.className = 'float-heart';
                h.textContent = glyphs[j % glyphs.length];
                h.style.left = (Math.random() * 94 + 2) + 'vw';
                h.style.top = (Math.random() * 40 + 55) + 'vh';
                h.style.fontSize = (11 + Math.random() * 15) + 'px';
                h.style.setProperty('--rot', (Math.random() * 40 - 20) + 'deg');
                h.style.setProperty('--peak', (0.28 + Math.random() * 0.3).toFixed(2));
                h.style.animationDuration = (11 + Math.random() * 9) + 's';
                h.style.animationDelay = (Math.random() * 12) + 's';
                ambient.appendChild(h);
            }
        })();

        function blowOut() {
            if (blown) return;
            blown = true;
            cake.classList.add('out');
            body.classList.add('blown');
            hint.classList.add('gone');
            timer = setTimeout(function() {
                farewell.classList.add('show');
            }, 1500);
        }

        function relightAll() {
            clearTimeout(timer);
            blown = false;
            cake.classList.remove('out');
            body.classList.remove('blown');
            hint.classList.remove('gone');
            farewell.classList.remove('show');
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('#relight')) {
                relightAll();
                return;
            }
            if (!blown && !e.target.closest('.farewell')) {
                blowOut();
            }
        });

        relight.addEventListener('click', relightAll);

        @if($previewOut)
        cake.classList.add('out');
        body.classList.add('blown');
        hint.classList.add('gone');
        blown = true;
        farewell.classList.add('show');
        farewell.style.transition = 'none';
        @endif
    })();
    </script>
</body>

</html>
