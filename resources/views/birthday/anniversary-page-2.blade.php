<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anniversary Card - Engraved Letter - Page 2</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,400;1,500&family=Marcellus&family=EB+Garamond:ital,wght@0,400;1,400&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            font-family: 'EB Garamond', serif;
        }

        :root {
            --bg-top: #c7bca6;
            --bg-bottom: #8f7f65;
            --cream: #f2ece0;
            --ink: #141312;
            --ink-soft: #4a4136;
            --gold: #a6813f;
            --gold-light: #d8bd85;
        }

        body {
            background: radial-gradient(ellipse at 50% 20%, #d3c7ac 0%, var(--bg-top) 40%, var(--bg-bottom) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            min-height: 100vh;
        }

        .om-scene {
            width: 100%;
            max-width: 420px;
        }

        .om-eyebrow-outer {
            text-align: center;
            font-family: 'Marcellus', serif;
            font-size: clamp(10px, 2.3vw, 11px);
            letter-spacing: 5px;
            text-transform: uppercase;
            color: var(--ink-soft);
            opacity: 0;
            margin-bottom: clamp(16px, 4vw, 22px);
            animation: om-fadeUp 0.8s ease 1.6s forwards;
        }

        /* ---------- CARD ---------- */
        .om-card {
            position: relative;
            background: var(--cream);
            border-radius: 2px;
            padding: clamp(38px, 9vw, 54px) clamp(28px, 7vw, 40px) clamp(30px, 7vw, 40px);
            box-shadow: 0 40px 70px rgba(15, 10, 0, 0.32), 0 2px 0 rgba(255, 255, 255, 0.4) inset;
            text-align: center;
            overflow: hidden;
        }

        .om-card::after {
            /* paper grain */
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image: repeating-linear-gradient(0deg, rgba(20, 19, 18, 0.02) 0px, rgba(20, 19, 18, 0.02) 1px, transparent 1px, transparent 3px);
            mix-blend-mode: multiply;
        }

        /* foil shimmer layer, position driven by --sx/--sy */
        .om-shine {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(115deg, transparent 35%, rgba(216, 189, 133, 0.55) 50%, transparent 65%);
            background-size: 260% 260%;
            background-position: var(--sx, 30%) var(--sy, 30%);
            mix-blend-mode: overlay;
            transition: background-position 0.2s ease-out;
        }

        .om-card.om-ambient .om-shine {
            animation: om-ambientShine 6s ease-in-out infinite;
        }

        @keyframes om-ambientShine {

            0%,
            100% {
                background-position: 10% 10%;
            }

            50% {
                background-position: 90% 90%;
            }
        }

        /* engraved frame */
        .om-frame {
            position: absolute;
            inset: 10px;
            pointer-events: none;
        }

        .om-frame rect {
            fill: none;
            stroke: var(--gold);
        }

        .om-frame .om-frame-outer {
            stroke-width: 1;
            stroke-dasharray: 1400;
            stroke-dashoffset: 1400;
            animation: om-draw 1.6s ease forwards 0.2s;
            opacity: 0.7;
        }

        .om-frame .om-frame-inner {
            stroke-width: 0.6;
            stroke-dasharray: 1300;
            stroke-dashoffset: 1300;
            animation: om-draw 1.6s ease forwards 0.5s;
            opacity: 0.45;
        }

        @keyframes om-draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        /* monogram */
        .om-monogram {
            position: relative;
            width: clamp(52px, 13vw, 62px);
            height: clamp(52px, 13vw, 62px);
            margin: 0 auto clamp(16px, 4vw, 22px);
            opacity: 0;
            animation: om-fadeUp 0.7s ease 1.1s forwards;
        }

        .om-monogram svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .om-monogram circle {
            fill: none;
            stroke: var(--gold);
            stroke-width: 1;
            stroke-dasharray: 220;
            stroke-dashoffset: 220;
            animation: om-draw 1.1s ease forwards 0.9s;
        }

        .om-monogram-mark {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Marcellus', serif;
            font-size: clamp(20px, 5.5vw, 24px);
            color: var(--ink);
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.55), 0 -1px 0 rgba(20, 19, 18, 0.18);
        }

        .om-rule {
            width: clamp(36px, 9vw, 46px);
            height: 1px;
            background: rgba(166, 129, 63, 0.5);
            margin: 0 auto clamp(14px, 3.5vw, 18px);
            opacity: 0;
            animation: om-fadeUp 0.7s ease 1.3s forwards;
        }

        .om-eyebrow {
            font-family: 'Marcellus', serif;
            font-size: clamp(10px, 2.3vw, 11px);
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--ink-soft);
            opacity: 0;
            margin-bottom: clamp(12px, 3vw, 16px);
            animation: om-fadeUp 0.7s ease 1.45s forwards;
        }

        .om-heading {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-weight: 600;
            font-size: clamp(27px, 7.4vw, 36px);
            color: var(--ink);
            line-height: 1.22;
            letter-spacing: 0.2px;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5), 0 -1px 0 rgba(20, 19, 18, 0.12);
            opacity: 0;
            animation: om-fadeUp 0.8s ease 1.65s forwards;
        }

        .om-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin: clamp(16px, 4vw, 22px) 0;
            opacity: 0;
            animation: om-fadeUp 0.7s ease 1.85s forwards;
        }

        .om-divider-line {
            flex: 0 1 clamp(40px, 10vw, 60px);
            height: 1px;
            background: rgba(166, 129, 63, 0.4);
        }

        .om-divider-mark {
            width: 5px;
            height: 5px;
            background: var(--gold);
            transform: rotate(45deg);
        }

        .om-message {
            font-family: 'EB Garamond', serif;
            font-style: italic;
            font-size: clamp(15px, 4vw, 17px);
            color: var(--ink-soft);
            line-height: 1.85;
            white-space: pre-line;
            max-width: 300px;
            margin: 0 auto clamp(26px, 6vw, 34px);
            opacity: 0;
            animation: om-fadeUp 0.8s ease 2.05s forwards;
        }

        .om-close-rule {
            width: 100%;
            height: 1px;
            background: rgba(20, 19, 18, 0.12);
            margin-bottom: clamp(18px, 4.5vw, 24px);
            opacity: 0;
            animation: om-fadeUp 0.6s ease 2.25s forwards;
        }

        .om-continue {
            background: none;
            border: none;
            font-family: 'Marcellus', serif;
            font-size: clamp(11px, 2.3vw, 12px);
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--ink);
            cursor: pointer;
            padding: 4px 2px;
            position: relative;
            opacity: 0;
            animation: om-fadeUp 0.6s ease 2.4s forwards;
        }

        .om-continue::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 1px;
            background: var(--gold);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.35s ease;
        }

        .om-continue:hover::before {
            transform: scaleX(1);
        }

        .om-continue::after {
            content: ' \2192';
            opacity: 0.6;
        }

        .om-colophon {
            text-align: center;
            font-family: 'Marcellus', serif;
            font-size: clamp(9px, 2vw, 10px);
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--ink-soft);
            opacity: 0;
            margin-top: clamp(16px, 4vw, 22px);
            animation: om-fadeUp 0.8s ease 2.6s forwards;
        }

        @keyframes om-fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
            }

            .om-frame .om-frame-outer,
            .om-frame .om-frame-inner,
            .om-monogram circle {
                stroke-dashoffset: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="om-scene">
        <div class="om-eyebrow-outer">— hold to catch the light —</div>

        <div class="om-card" id="om-card">
            <div class="om-shine" id="om-shine"></div>

            <svg class="om-frame" viewBox="0 0 400 480" preserveAspectRatio="none">
                <rect class="om-frame-outer" x="1" y="1" width="398" height="478" rx="1"></rect>
                <rect class="om-frame-inner" x="7" y="7" width="386" height="466" rx="1"></rect>
            </svg>

            <div class="om-monogram">
                <svg viewBox="0 0 60 60"><circle cx="30" cy="30" r="28"></circle></svg>
                <div class="om-monogram-mark">&amp;</div>
            </div>
            <div class="om-rule"></div>

            <div class="om-eyebrow">an anniversary</div>
            <h1 class="om-heading" id="om-heading">{{ request('heading', 'Happy Anniversary, My Love') }}</h1>

            <div class="om-divider">
                <div class="om-divider-line"></div>
                <span class="om-divider-mark"></span>
                <div class="om-divider-line"></div>
            </div>

            <p class="om-message">{{ request('message', "Every year with you still feels like the first day.\nHere's to us, always.") }}</p>

            <div class="om-close-rule"></div>
            <button class="om-continue" id="om-continue">Next</button>

            <div class="om-colophon">presented with love</div>
        </div>
    </div>

    <script>
        const card = document.getElementById('om-card');
        const hasHover = window.matchMedia('(hover: hover)').matches;

        if (hasHover) {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                card.style.setProperty('--sx', x + '%');
                card.style.setProperty('--sy', y + '%');
            });
            card.addEventListener('mouseleave', () => {
                card.style.setProperty('--sx', '30%');
                card.style.setProperty('--sy', '30%');
            });
        } else {
            card.classList.add('om-ambient');
        }
    </script>
</body>

</html>
