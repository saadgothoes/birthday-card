<div class="birthday-card">
    <h1>Girl - Page 3 - Variant 1 - Gift 2 - Page 2</h1>
</div>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <title>A Gift For You — Luxury Surprise Experience</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,500&family=Dancing+Script:wght@500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* =====================================================================================
   1. THEME — :root
   Every color, gradient, shadow, glow, radius, transition and font in this file reads
   from these variables. Re-skin the whole experience by editing only this block.
===================================================================================== */
        :root {
            --bg-dark: #182B40;
            --bg-light: #284A69;
            --card: #356283;

            --primary: #73C7FF;
            --secondary: #D9F0FF;
            --accent: #9EDCFF;
            --gold: #EAF8FF;

            --text-light: #FCFEFF;
            --text-dark: #28435E;
            --paper: #F9FDFF;

            --bg-dark-rgb: 24, 43, 64;
            --bg-light-rgb: 40, 74, 105;
            --card-rgb: 53, 98, 131;
            --primary-rgb: 115, 199, 255;
            --secondary-rgb: 217, 240, 255;
            --accent-rgb: 158, 220, 255;
            --gold-rgb: 234, 248, 255;
            --text-light-rgb: 252, 254, 255;
            --shadow-rgb: 0, 0, 0;

            --shadow: 0 30px 60px rgba(var(--shadow-rgb), .55), 0 10px 24px rgba(var(--shadow-rgb), .35);
            --glow: 0 0 40px rgba(var(--accent-rgb), .45);
            --border: 1px solid rgba(var(--gold-rgb), .45);

            --radius: 18px;
            --radius-sm: 8px;

            --transition: .45s cubic-bezier(.22, 1, .36, 1);
            --transition-slow: .9s cubic-bezier(.22, 1, .36, 1);
            --transition-bounce: .7s cubic-bezier(.34, 1.56, .64, 1);

            --font-heading: 'Playfair Display', serif;
            --font-body: 'Cormorant Garamond', serif;
            --font-hand: 'Dancing Script', cursive;
        }

        /* =====================================================================================
   2. RESET & BASE
===================================================================================== */
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-dark);
            font-family: var(--font-body);
            overflow: hidden;
            /* no scrolling anywhere, per spec */
            color: var(--text-light);
            -webkit-font-smoothing: antialiased;
        }

        button {
            font-family: inherit;
            border: none;
            background: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        p {
            margin: 0;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }

        /* =====================================================================================
   3. APP FRAME — locked 9:16, mobile-first. Desktop only scales this, never redesigns it.
===================================================================================== */
        .stage-outer {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(60% 50% at 50% 42%, rgba(var(--accent-rgb), .10), transparent 70%),
                radial-gradient(120% 90% at 50% 100%, var(--bg-light) 0%, var(--bg-dark) 55%);
            overflow: hidden;
        }

        /* Desktop/web surround — only kicks in once the frame stops filling the viewport */
        @media (min-width:640px) {
            .stage-outer {
                padding: 4vh 4vw;
                background:
                    radial-gradient(55% 55% at 50% 40%, rgba(var(--accent-rgb), .14), transparent 68%),
                    radial-gradient(90% 70% at 15% 10%, rgba(var(--gold-rgb), .10), transparent 60%),
                    radial-gradient(90% 70% at 85% 95%, rgba(var(--primary-rgb), .16), transparent 62%),
                    linear-gradient(160deg, var(--bg-light) 0%, var(--bg-dark) 70%);
            }

            .stage-outer::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    repeating-linear-gradient(115deg, rgba(var(--text-light-rgb), .015) 0 2px, transparent 2px 6px);
                pointer-events: none;
            }

            .stage-outer::after {
                content: "";
                position: absolute;
                left: 50%;
                top: 50%;
                width: min(120vh, 140vw);
                aspect-ratio: 1;
                transform: translate(-50%, -50%);
                border-radius: 50%;
                background: radial-gradient(circle, rgba(var(--accent-rgb), .16) 0%, transparent 62%);
                filter: blur(10px);
                pointer-events: none;
                animation: spotlightPulse 6s ease-in-out infinite;
            }

        }

        @keyframes spotlightPulse {

            0%,
            100% {
                opacity: .75;
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.05);
            }
        }

        .app-frame {
            position: relative;
            width: 100%;
            height: 100%;
            max-width: calc(100dvh * 9 / 16);
            max-height: 100dvh;
            aspect-ratio: 9/16;
            overflow: hidden;
            background:
                radial-gradient(120% 65% at 50% 0%, var(--bg-light) 0%, var(--bg-dark) 60%);
            box-shadow: var(--shadow);
        }

        @media (min-width:640px) {
            .app-frame {
                border-radius: 28px;
                max-height: calc(100dvh - 8vh);
                max-width: calc((100dvh - 8vh) * 9 / 16);
                box-shadow:
                    var(--shadow),
                    0 0 0 1px rgba(var(--gold-rgb), .18),
                    0 0 90px rgba(var(--accent-rgb), .16);
            }
        }

        /* =====================================================================================
   4. AMBIENT BACKGROUND — glow blobs, tiny lights, floating dust particles
===================================================================================== */
        .bg-layer {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .bg-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
        }

        .bg-glow.g1 {
            width: 60%;
            aspect-ratio: 1;
            left: -18%;
            top: -6%;
            background: radial-gradient(circle, rgba(var(--accent-rgb), .30), transparent 70%);
            animation: driftGlow 9s ease-in-out infinite;
        }

        .bg-glow.g2 {
            width: 55%;
            aspect-ratio: 1;
            right: -16%;
            bottom: 4%;
            background: radial-gradient(circle, rgba(var(--gold-rgb), .22), transparent 70%);
            animation: driftGlow 11s ease-in-out infinite 1.4s;
        }

        @keyframes driftGlow {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
                opacity: .8;
            }

            50% {
                transform: translate(4%, 5%) scale(1.08);
                opacity: 1;
            }
        }

        .bg-lights span {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 8px 2px rgba(var(--accent-rgb), .8);
            animation: twinkle 3s ease-in-out infinite;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: .25;
            }

            50% {
                opacity: 1;
            }
        }

        .bg-particles span {
            position: absolute;
            border-radius: 50%;
            background: var(--secondary);
            opacity: 0;
            animation: dustFloat linear infinite;
        }

        @keyframes dustFloat {
            0% {
                opacity: 0;
                transform: translateY(0) translateX(0);
            }

            12% {
                opacity: .5;
            }

            88% {
                opacity: .2;
            }

            100% {
                opacity: 0;
                transform: translateY(-90%) translateX(10px);
            }
        }

        /* =====================================================================================
   5. GIFT SCENE — the box + ribbon (first thing the user sees)
===================================================================================== */
        .gift-scene {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6%;
            transition: opacity var(--transition-slow), transform var(--transition-slow);
        }

        .gift-scene.is-hidden {
            opacity: 0;
            transform: scale(.9);
            pointer-events: none;
        }

        .gift-box-wrap {
            position: relative;
            width: 56%;
            perspective: 900px;
            animation: boxBreathe 4.5s ease-in-out infinite;
        }

        @keyframes boxBreathe {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-6px) scale(1.015);
            }
        }

        .gift-box {
            position: relative;
            width: 100%;
            aspect-ratio: 1/.82;
            transform-style: preserve-3d;
        }

        /* Box body */
        .box-body {
            position: absolute;
            inset: 0;
            top: 22%;
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        .box-front {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(160deg, var(--primary), var(--card) 60%, var(--primary));
            box-shadow: inset 0 0 0 1px rgba(var(--gold-rgb), .3), inset 0 -14px 30px rgba(var(--shadow-rgb), .3);
        }

        .box-front::after {
            /* subtle premium paper texture via repeating gradient */
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(115deg, rgba(var(--text-light-rgb), .03) 0 2px, transparent 2px 5px);
        }

        .box-inner-glow {
            position: absolute;
            inset: 8% 8% 0 8%;
            border-radius: 6px 6px 0 0;
            background: radial-gradient(120% 100% at 50% 120%, rgba(var(--accent-rgb), .9), transparent 70%);
            opacity: 0;
            transition: opacity .6s ease;
        }

        .gift-box.is-glowing .box-inner-glow {
            opacity: .55;
        }

        /* Edge glow escaping before the lid opens */
        .box-edge-glow {
            position: absolute;
            inset: 20% -4% 0 -4%;
            border-radius: 14px;
            box-shadow: 0 0 0 0 rgba(var(--accent-rgb), 0);
            transition: box-shadow .6s ease;
            pointer-events: none;
        }

        .gift-box.is-glowing .box-edge-glow {
            animation: edgePulse 1.1s ease-in-out infinite;
        }

        @keyframes edgePulse {

            0%,
            100% {
                box-shadow: 0 0 18px 2px rgba(var(--accent-rgb), .35);
            }

            50% {
                box-shadow: 0 0 34px 8px rgba(var(--accent-rgb), .65);
            }
        }

        /* Lid */
        .box-lid {
            position: absolute;
            top: 14%;
            left: -2%;
            right: -2%;
            height: 26%;
            transform-origin: top center;
            transform: rotateX(0deg);
            transition: transform 1.1s cubic-bezier(.34, 1.4, .4, 1);
            z-index: 3;
        }

        .gift-box.is-open .box-lid {
            transform: rotateX(-118deg);
        }

        .lid-top {
            position: absolute;
            inset: 0;
            border-radius: var(--radius-sm);
            background: linear-gradient(160deg, var(--secondary), var(--primary));
            box-shadow: 0 10px 20px rgba(var(--shadow-rgb), .35), inset 0 0 0 1px rgba(var(--gold-rgb), .4);
        }

        .lid-edge {
            position: absolute;
            left: 6%;
            right: 6%;
            bottom: -6%;
            height: 14%;
            background: var(--primary);
            border-radius: 0 0 4px 4px;
            box-shadow: inset 0 4px 8px rgba(var(--shadow-rgb), .3);
        }

        /* Shake states */
        .gift-box.shake-1 {
            animation: shakeSm .4s ease;
        }

        .gift-box.shake-2 {
            animation: shakeMd .45s ease;
        }

        @keyframes shakeSm {

            0%,
            100% {
                transform: translateX(0) rotate(0);
            }

            25% {
                transform: translateX(-4px) rotate(-1deg);
            }

            75% {
                transform: translateX(4px) rotate(1deg);
            }
        }

        @keyframes shakeMd {

            0%,
            100% {
                transform: translateX(0) rotate(0);
            }

            20% {
                transform: translateX(-8px) rotate(-2deg);
            }

            60% {
                transform: translateX(8px) rotate(2deg);
            }

            85% {
                transform: translateX(-4px) rotate(-1deg);
            }
        }

        /* =====================================================================================
   6. RIBBON — untie / drop / fade
===================================================================================== */
        .ribbon-wrap {
            position: absolute;
            inset: 0;
            z-index: 4;
            cursor: pointer;
            transition: transform var(--transition-slow), opacity var(--transition-slow);
            transform-origin: 60% 40%;
        }

        .ribbon-vertical {
            position: absolute;
            left: 47%;
            top: 8%;
            width: 6%;
            height: 74%;
            background: linear-gradient(180deg, var(--gold), #a9822a);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .25);
        }

        .ribbon-horizontal {
            position: absolute;
            left: 8%;
            right: 8%;
            top: 34%;
            height: 8%;
            background: linear-gradient(90deg, var(--gold), #a9822a);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .25);
            transform-origin: right center;
            transition: transform 1.1s var(--transition), opacity .8s ease;
        }

        .ribbon-bow {
            position: absolute;
            left: 50%;
            top: 22%;
            width: 26%;
            aspect-ratio: 1.6/1;
            transform: translateX(-50%);
            transition: transform 1s cubic-bezier(.36, 0, .66, -0.3), opacity .7s ease;
        }

        .bow-wing {
            position: absolute;
            top: 0;
            width: 48%;
            height: 100%;
            background: linear-gradient(160deg, var(--gold), #a9822a);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .25);
        }

        .bow-wing.left {
            left: 0;
            border-radius: 80% 10% 60% 40%;
            transform: rotate(-14deg);
        }

        .bow-wing.right {
            right: 0;
            border-radius: 10% 80% 40% 60%;
            transform: rotate(14deg);
        }

        .bow-knot {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 16%;
            aspect-ratio: 1;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle at 35% 30%, #f3d783, var(--gold));
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(var(--shadow-rgb), .35);
        }

        /* Untie sequence, triggered by JS adding .is-untied to ribbon-wrap */
        .ribbon-wrap.is-untied .ribbon-bow {
            transform: translateX(-50%) rotate(35deg) scale(.7);
            opacity: 0;
        }

        .ribbon-wrap.is-untied .ribbon-horizontal {
            transform: rotate(70deg) translateX(30%);
            opacity: 0;
        }

        .ribbon-wrap.is-untied {
            transform: translateY(60%) rotate(18deg);
            opacity: 0;
        }

        .box-sparkles {
            position: absolute;
            inset: 0;
            z-index: 5;
            pointer-events: none;
        }

        .box-sparkles span {
            position: absolute;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 8px 2px rgba(var(--accent-rgb), .9);
            opacity: 0;
            animation: sparklePop 900ms ease-out forwards;
        }

        @keyframes sparklePop {
            0% {
                opacity: 1;
                transform: translate(0, 0) scale(0.4);
            }

            100% {
                opacity: 0;
                transform: translate(var(--dx, 10px), var(--dy, -30px)) scale(1);
            }
        }

        /* =====================================================================================
   7. HELPER TEXT
===================================================================================== */
        .helper-text {
            position: relative;
            z-index: 6;
            text-align: center;
            font-family: var(--font-heading);
            font-size: clamp(13px, 3.4vw, 15px);
            color: rgba(var(--text-light-rgb), .8);
            letter-spacing: .3px;
            transition: opacity .5s ease;
        }

        .helper-text .helper-sub {
            display: block;
            margin-top: 4px;
            font-family: var(--font-body);
            font-style: italic;
            font-size: clamp(12px, 3vw, 13.5px);
            color: var(--accent);
        }

        .helper-text.is-fading {
            opacity: 0;
        }

        .gift2-help-button {
            position: absolute;
            top: 5%;
            right: 5%;
            z-index: 20;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(var(--gold-rgb), .55);
            border-radius: 50%;
            background: rgba(var(--bg-dark-rgb), .55);
            color: var(--gold);
            font-family: var(--font-heading);
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(var(--shadow-rgb), .25);
        }

        .gift2-help-button:hover,
        .gift2-help-button:focus-visible {
            background: var(--primary);
            color: var(--text-light);
            outline: none;
        }

        .gift2-help-dialog {
            position: absolute;
            top: 11%;
            left: 8%;
            right: 8%;
            z-index: 30;
            display: none;
            padding: 1rem 1.1rem;
            border: 1px solid rgba(var(--gold-rgb), .55);
            border-radius: var(--radius-sm);
            background: rgba(var(--bg-dark-rgb), .94);
            color: var(--text-light);
            text-align: left;
            box-shadow: var(--shadow);
        }

        .gift2-help-dialog.is-visible {
            display: block;
        }

        .gift2-help-dialog strong {
            display: block;
            margin-bottom: .35rem;
            color: var(--gold);
            font-family: var(--font-heading);
            font-size: 1rem;
        }

        .gift2-help-dialog p {
            font-size: .95rem;
            line-height: 1.35;
        }

        .stage-helper {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 5%;
            z-index: 12;
            opacity: 0;
            transition: opacity .5s ease;
        }

        .stage-helper.is-visible {
            opacity: 1;
        }

        /* =====================================================================================
   8. REVEAL STAGE — photos / envelope / letter / secret card appear here, one at a time
===================================================================================== */
        .reveal-stage {
            position: absolute;
            inset: 0;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity var(--transition-slow);
        }

        .reveal-stage.is-active {
            opacity: 1;
            pointer-events: auto;
        }

        .stage-dim {
            position: absolute;
            inset: 0;
            background: rgba(var(--bg-dark-rgb), .7);
            backdrop-filter: blur(4px);
            opacity: 0;
            pointer-events: none;
            transition: opacity .4s ease;
            z-index: 1;
        }

        .stage-dim.is-visible {
            opacity: 1;
            pointer-events: auto;
        }

        /* ---------------- Polaroid photos ---------------- */
        .polaroid {
            position: absolute;
            width: 62%;
            max-width: 280px;
            background: var(--paper);
            padding: 6% 6% 16%;
            border-radius: 3px;
            box-shadow: 0 20px 40px rgba(var(--shadow-rgb), .4);
            z-index: 2;
            opacity: 0;
            transform: translateY(40%) scale(.7) rotate(0deg);
            transition: transform .9s cubic-bezier(.22, 1.4, .36, 1), opacity .6s ease, left .6s var(--transition), z-index 0s;
            cursor: pointer;
        }

        .polaroid img {
            width: 100%;
            aspect-ratio: 1/1.05;
            object-fit: cover;
            border-radius: 1px;
        }

        .polaroid-cap {
            text-align: center;
            margin-top: 8%;
            font-family: var(--font-hand);
            font-size: clamp(14px, 4vw, 17px);
            color: var(--text-dark);
        }

        .polaroid.enter {
            opacity: 1;
            transform: translateY(0) scale(1) rotate(var(--tilt, -6deg));
        }

        .polaroid.zoomed {
            transform: translateY(0) scale(1.28) rotate(0deg) !important;
            z-index: 20;
            box-shadow: 0 30px 70px rgba(var(--shadow-rgb), .55);
        }

        .polaroid.aside {
            opacity: 0;
            transform: translateY(-6%) translateX(-140%) scale(.8) rotate(-18deg);
        }

        /* ---------------- Envelope ---------------- */
        .envelope {
            position: absolute;
            width: 64%;
            max-width: 300px;
            aspect-ratio: 8/5.2;
            z-index: 2;
            opacity: 0;
            pointer-events: none;
            transform: translateY(50%) scale(.7) rotate(0deg);
            transition: transform 1s cubic-bezier(.22, 1.4, .36, 1), opacity .6s ease;
            perspective: 800px;
            cursor: pointer;
        }

        .envelope.enter {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1) rotate(-4deg);
        }

        .envelope.opened {
            transform: translateY(-6%) scale(1) rotate(0deg);
        }

        .envelope-back {
            position: absolute;
            inset: 0;
            border-radius: 8px;
            background: linear-gradient(155deg, var(--secondary), var(--primary));
            box-shadow: 0 20px 40px rgba(var(--shadow-rgb), .4);
        }

        .envelope-flap {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 54%;
            background: linear-gradient(160deg, var(--primary), var(--card));
            clip-path: polygon(0 0, 100% 0, 50% 100%);
            transform-origin: top center;
            transition: transform 1s cubic-bezier(.4, 0, .2, 1);
            border-radius: 8px 8px 0 0;
            z-index: 3;
        }

        .envelope.opened .envelope-flap {
            transform: rotateX(165deg);
        }

        .envelope-seal {
            position: absolute;
            top: 38%;
            left: 50%;
            width: 15%;
            aspect-ratio: 1;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #c9532e, #8a2f18);
            box-shadow: 0 6px 14px rgba(var(--shadow-rgb), .4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-family: var(--font-hand);
            font-size: clamp(14px, 4vw, 18px);
            z-index: 4;
            transition: transform .5s cubic-bezier(.4, 0, .2, 1), opacity .4s ease .1s;
        }

        .envelope.breaking .envelope-seal {
            transform: translate(-50%, -50%) scale(1.4) rotate(25deg);
            opacity: 0;
        }

        .envelope.opened .envelope-seal {
            opacity: 0;
            pointer-events: none;
        }

        /* ---------------- Letter ---------------- */
        .letter {
            position: absolute;
            width: 76%;
            max-width: 320px;
            height: 64%;
            z-index: 4;
            opacity: 0;
            pointer-events: none;
            transform: translateY(20%) scale(.9);
            transition: transform var(--transition-slow), opacity .7s ease;
        }

        .letter.enter {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .letter-paper {
            position: relative;
            width: 100%;
            height: 100%;
            background: var(--paper);
            border-radius: 6px;
            box-shadow: 0 24px 50px rgba(var(--shadow-rgb), .45);
            padding: 9% 8%;
            overflow-y: auto;
            color: var(--text-dark);
            font-family: var(--font-hand);
            font-size: clamp(15px, 4vw, 18px);
            line-height: 1.55;
        }

        .hand-line {
            min-height: 1.55em;
        }

        .hand-line.spacer {
            height: .7em;
        }

        .ink-char {
            display: inline-block;
            opacity: 0;
            transform: scale(1.5) rotate(var(--jr, 0deg)) translateY(var(--jy, 0));
            transition: opacity .18s ease-out, transform .18s ease-out;
        }

        .ink-char.is-inked {
            opacity: 1;
            transform: scale(1) rotate(0) translateY(0);
        }

        .pen-tip {
            position: absolute;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--primary);
            box-shadow: 0 0 6px 2px rgba(var(--primary-rgb), .5);
            transform: translate(-2px, -2px);
            opacity: 0;
            transition: opacity .2s ease, left .05s linear, top .05s linear;
            pointer-events: none;
        }

        .pen-tip.is-writing {
            opacity: .85;
        }

        /* ---------------- Secret hint + card ---------------- */
        .secret-hint {
            position: absolute;
            bottom: 10%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
            transition: opacity .6s ease, transform .6s ease;
            z-index: 5;
            cursor: pointer;
        }

        .secret-hint.enter {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .hint-glow {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(var(--accent-rgb), .9), transparent 70%);
            animation: hintPulse 1.6s ease-in-out infinite;
        }

        @keyframes hintPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: .7;
            }

            50% {
                transform: scale(1.3);
                opacity: 1;
            }
        }

        .secret-hint p {
            font-family: var(--font-heading);
            font-size: 13px;
            color: var(--accent);
            letter-spacing: .3px;
        }

        .secret-card {
            position: absolute;
            width: 56%;
            max-width: 250px;
            aspect-ratio: 5/7;
            z-index: 6;
            perspective: 1000px;
            opacity: 0;
            pointer-events: none;
            transform: translateY(30%) scale(.6);
            transition: transform var(--transition-slow), opacity .6s ease;
        }

        .secret-card.enter {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 1s cubic-bezier(.4, 0, .2, 1);
            cursor: pointer;
        }

        .secret-card.flipped .card-inner {
            transform: rotateY(180deg);
        }

        .card-face {
            position: absolute;
            inset: 0;
            border-radius: var(--radius-sm);
            backface-visibility: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10%;
            box-shadow: 0 20px 40px rgba(var(--shadow-rgb), .4);
        }

        .card-front {
            background: linear-gradient(160deg, var(--secondary), var(--primary));
            color: var(--text-light);
        }

        .card-mono {
            font-size: 34px;
            color: var(--accent);
        }

        .card-back {
            background: var(--paper);
            color: var(--text-dark);
            transform: rotateY(180deg);
        }

        .card-back p {
            font-family: var(--font-heading);
            font-size: clamp(13px, 3.8vw, 15px);
            line-height: 1.5;
        }

        .card-back-emph {
            font-family: var(--font-hand) !important;
            font-size: clamp(24px, 7vw, 30px) !important;
            color: var(--primary);
            margin-top: 10px !important;
        }

        .card-heart {
            margin-top: 12px;
            font-size: 20px;
            color: #b5432f;
        }

        /* =====================================================================================
   9. ENDING — confetti, floating hearts, replay
===================================================================================== */
        .confetti-layer,
        .hearts-layer {
            position: absolute;
            inset: 0;
            z-index: 15;
            pointer-events: none;
            overflow: hidden;
        }

        .confetti-piece {
            position: absolute;
            top: -5%;
            opacity: 0;
            animation: confettiFall linear forwards;
        }

        @keyframes confettiFall {
            0% {
                opacity: 1;
                transform: translateY(0) rotate(0deg);
            }

            100% {
                opacity: 0;
                transform: translateY(115vh) rotate(540deg);
            }
        }

        .heart-piece {
            position: absolute;
            bottom: -5%;
            color: var(--accent);
            opacity: 0;
            animation: heartRise linear forwards;
        }

        @keyframes heartRise {
            0% {
                opacity: 0;
                transform: translateY(0) scale(.6);
            }

            15% {
                opacity: .9;
            }

            100% {
                opacity: 0;
                transform: translateY(-90vh) scale(1.1);
            }
        }

        .replay-btn {
            position: absolute;
            left: 50%;
            bottom: 6%;
            transform: translateX(-50%) translateY(20px);
            z-index: 16;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 26px;
            border-radius: 32px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: var(--text-light);
            font-weight: 600;
            font-size: 14px;
            letter-spacing: .4px;
            box-shadow: 0 14px 28px rgba(var(--shadow-rgb), .4);
            cursor: pointer;
            opacity: 0;
            pointer-events: none;
            transition: opacity .6s ease, transform .6s ease, box-shadow .3s ease;
        }

        .replay-btn.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }

        .replay-btn:hover {
            box-shadow: 0 18px 34px rgba(var(--shadow-rgb), .5);
        }

        .replay-btn:active {
            transform: translateX(-50%) scale(.96);
        }

        /* =====================================================================================
   10. RESPONSIVE — the frame itself already handles scaling; these just fine-tune density
===================================================================================== */
        @media (max-height:560px) {
            .gift-box-wrap {
                width: 48%;
            }

            .polaroid {
                width: 54%;
            }
        }
    </style>
</head>

<body>

    <div class="stage-outer">
        <div class="app-frame" id="appFrame">

            <button class="gift2-help-button" id="gift2HelpButton" type="button" aria-label="How to open this gift" aria-expanded="false">ⓘ</button>
            <div class="gift2-help-dialog" id="gift2HelpDialog" role="dialog" aria-label="How to open this gift"><strong>How to open this gift</strong>
                <p>Tap the ribbon once. Then tap the gift box three times to open it.</p>
            </div>

            <!-- ============== AMBIENT BACKGROUND ============== -->
            <div class="bg-layer">
                <div class="bg-glow g1"></div>
                <div class="bg-glow g2"></div>
                <div class="bg-lights" id="bgLights"></div>
                <div class="bg-particles" id="bgParticles"></div>
            </div>

            <!-- ============== GIFT SCENE (box + ribbon) ============== -->
            <div class="gift-scene" id="giftScene">

                <div class="gift-box-wrap" id="boxWrap">
                    <div class="gift-box" id="giftBox">
                        <div class="box-lid" id="boxLid">
                            <div class="lid-top"></div>
                            <div class="lid-edge"></div>
                        </div>
                        <div class="box-body">
                            <div class="box-front"></div>
                            <div class="box-inner-glow"></div>
                        </div>
                        <div class="box-edge-glow"></div>
                    </div>

                    <div class="ribbon-wrap" id="ribbonWrap" role="button" tabindex="0" aria-label="Untie the ribbon">
                        <div class="ribbon-vertical"></div>
                        <div class="ribbon-horizontal"></div>
                        <div class="ribbon-bow">
                            <span class="bow-wing left"></span>
                            <span class="bow-wing right"></span>
                            <span class="bow-knot"></span>
                        </div>
                    </div>

                    <div class="box-sparkles" id="boxSparkles"></div>
                </div>

                <p class="helper-text" id="helperText">
                    {{ request('box_title', 'A little surprise is waiting…') }}
                    <span class="helper-sub" id="helperSub">{{ request('box_hint', 'Tap the ribbon') }}</span>
                </p>
            </div>

            <!-- ============== REVEAL STAGE (photos / envelope / letter / card) ============== -->
            <div class="reveal-stage" id="revealStage">
                <div class="stage-dim" id="stageDim"></div>

                <div class="polaroid" id="photo0" style="--tilt:-7deg"
                    data-src="{{ request('photo1', 'https://picsum.photos/seed/giftphoto1/500/560') }}" tabindex="0" role="button"
                    aria-label="View memory one">
                    <img src="{{ request('photo1', 'https://picsum.photos/seed/giftphoto1/500/560') }}" alt="A cherished memory">
                    <p class="polaroid-cap">{{ request('cap1', 'memory one') }}</p>
                </div>
                <div class="polaroid" id="photo1" style="--tilt:5deg"
                    data-src="{{ request('photo2', 'https://picsum.photos/seed/giftphoto2/500/560') }}" tabindex="0" role="button"
                    aria-label="View memory two">
                    <img src="{{ request('photo2', 'https://picsum.photos/seed/giftphoto2/500/560') }}" alt="A cherished memory">
                    <p class="polaroid-cap">{{ request('cap2', 'memory two') }}</p>
                </div>
                <div class="polaroid" id="photo2" style="--tilt:-4deg"
                    data-src="{{ request('photo3', 'https://picsum.photos/seed/giftphoto3/500/560') }}" tabindex="0" role="button"
                    aria-label="View memory three">
                    <img src="{{ request('photo3', 'https://picsum.photos/seed/giftphoto3/500/560') }}" alt="A cherished memory">
                    <p class="polaroid-cap">{{ request('cap3', 'memory three') }}</p>
                </div>

                <div class="envelope" id="envelope" tabindex="0" role="button" aria-label="Open the envelope">
                    <div class="envelope-back"></div>
                    <div class="envelope-flap" id="envelopeFlap"></div>
                    <div class="envelope-seal" id="envelopeSeal">♡</div>
                </div>

                <div class="letter" id="letter">
                    <div class="letter-paper" id="letterPaper"></div>
                </div>

                <div class="secret-hint" id="secretHint" tabindex="0" role="button"
                    aria-label="Reveal the last surprise">
                    <div class="hint-glow"></div>
                    <p>One last surprise…</p>
                </div>

                <div class="secret-card" id="secretCard" tabindex="0" role="button" aria-label="Flip the card">
                    <div class="card-inner" id="cardInner">
                        <div class="card-face card-front">
                            <span class="card-mono">✦</span>
                        </div>
                        <div class="card-face card-back">
                            <p>The best gift was never inside this box…</p>
                            <p class="card-back-emph">It was You</p>
                            <span class="card-heart">❤</span>
                        </div>
                    </div>
                </div>
            </div>

            <p class="helper-text stage-helper" id="stageHelperText"></p>

            <div class="confetti-layer" id="confettiLayer"></div>
            <div class="hearts-layer" id="heartsLayer"></div>

            <button class="replay-btn" id="replayBtn">↺ Replay</button>

        </div>
    </div>

    <script>
        (function() {
            'use strict';

            /* ===================================================================================
               UTILITIES
            =================================================================================== */
            const $ = (id) => document.getElementById(id);
            const rand = (min, max) => Math.random() * (max - min) + min;

            const gift2HelpButton = $('gift2HelpButton');
            const gift2HelpDialog = $('gift2HelpDialog');
            gift2HelpButton.addEventListener('click', () => {
                const visible = gift2HelpDialog.classList.toggle('is-visible');
                gift2HelpButton.setAttribute('aria-expanded', String(visible));
            });

            function createEl(tag, className, parent) {
                const el = document.createElement(tag);
                if (className) el.className = className;
                if (parent) parent.appendChild(el);
                return el;
            }

            /* ===================================================================================
               AMBIENT BACKGROUND — tiny lights + floating dust particles
            =================================================================================== */
            const bgLights = $('bgLights');
            for (let i = 0; i < 10; i++) {
                const s = createEl('span', '', bgLights);
                s.style.left = rand(5, 95) + '%';
                s.style.top = rand(5, 95) + '%';
                s.style.animationDelay = rand(0, 3) + 's';
                s.style.animationDuration = rand(2.4, 4) + 's';
            }

            const bgParticles = $('bgParticles');
            for (let i = 0; i < 26; i++) {
                const s = createEl('span', '', bgParticles);
                const size = rand(2, 4.5);
                s.style.width = size + 'px';
                s.style.height = size + 'px';
                s.style.left = rand(0, 100) + '%';
                s.style.bottom = '-10px';
                s.style.animationDuration = rand(9, 18) + 's';
                s.style.animationDelay = rand(0, 16) + 's';
            }

            /* ===================================================================================
               STATE MACHINE — top-level progression through the experience
            =================================================================================== */
            const State = {
                RIBBON: 'ribbon',
                SHAKE: 'shake',
                OPENING: 'opening',
                PHOTOS: 'photos',
                ENVELOPE: 'envelope',
                LETTER: 'letter',
                SECRET: 'secret',
                END: 'end'
            };
            let current = State.RIBBON;
            let boxTapCount = 0;
            let photoIndex = 0;
            const TOTAL_PHOTOS = 3;

            const giftScene = $('giftScene');
            const revealStage = $('revealStage');
            const stageDim = $('stageDim');
            const helperText = $('helperText');
            const helperSub = $('helperSub');
            const stageHelper = $('stageHelperText');
            const ribbonWrap = $('ribbonWrap');
            const giftBox = $('giftBox');
            const boxSparkles = $('boxSparkles');
            const replayBtn = $('replayBtn');

            function setHelper(main, sub) {
                helperText.classList.add('is-fading');
                setTimeout(() => {
                    helperText.childNodes[0].nodeValue = main;
                    helperSub.textContent = sub || '';
                    helperText.classList.remove('is-fading');
                }, 250);
            }

            function setStageHelper(text) {
                stageHelper.textContent = text;
                stageHelper.classList.toggle('is-visible', !!text);
            }

            /* ===================================================================================
               SPARKLE BURST — small reusable particle burst used across multiple steps
            =================================================================================== */
            function sparkleBurst(container, count) {
                for (let i = 0; i < count; i++) {
                    const s = createEl('span', '', container);
                    s.style.left = rand(20, 80) + '%';
                    s.style.top = rand(20, 80) + '%';
                    s.style.setProperty('--dx', rand(-40, 40) + 'px');
                    s.style.setProperty('--dy', rand(-60, -10) + 'px');
                    s.style.animationDelay = rand(0, .3) + 's';
                    setTimeout(() => s.remove(), 1300);
                }
            }

            /* ===================================================================================
               STEP 1 — RIBBON UNTIE
            =================================================================================== */
            function untieRibbon() {
                if (current !== State.RIBBON) return;
                ribbonWrap.classList.add('is-untied');
                sparkleBurst(boxSparkles, 10);
                setHelper('Beautifully wrapped, isn\u2019t it?', 'Now tap the gift…');
                current = State.SHAKE;
            }
            ribbonWrap.addEventListener('click', untieRibbon);
            ribbonWrap.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    untieRibbon();
                }
            });

            /* ===================================================================================
               STEP 2 — BOX SHAKE (3 taps) → STEP 3 — BOX OPENING
            =================================================================================== */
            function handleBoxTap() {
                if (current !== State.SHAKE) return;
                boxTapCount++;

                if (boxTapCount === 1) {
                    giftBox.classList.remove('shake-1');
                    void giftBox.offsetWidth;
                    giftBox.classList.add('shake-1');
                    setHelper('Something is stirring inside…', 'Tap again');
                } else if (boxTapCount === 2) {
                    giftBox.classList.remove('shake-2');
                    void giftBox.offsetWidth;
                    giftBox.classList.add('shake-2');
                    setHelper('It\u2019s almost ready…', 'One more tap');
                } else if (boxTapCount >= 3) {
                    giftBox.classList.add('is-glowing');
                    sparkleBurst(boxSparkles, 16);
                    setHelper('Here it comes…', '');
                    current = State.OPENING;
                    setTimeout(openBox, 1000);
                }
            }
            giftBox.addEventListener('click', handleBoxTap);

            function openBox() {
                giftBox.classList.add('is-open');
                sparkleBurst(boxSparkles, 22);
                setTimeout(() => {
                    giftScene.classList.add('is-hidden');
                    revealStage.classList.add('is-active');
                    startPhotoSequence();
                }, 900);
            }

            /* ===================================================================================
               STEP 4 — MEMORY REVEAL: photos, one at a time
            =================================================================================== */
            const photos = [$('photo0'), $('photo1'), $('photo2')];
            let zoomedPhoto = null;

            function centerPhoto(el) {
                el.style.position = 'absolute';
                el.style.left = '50%';
                el.style.top = '50%';
                el.style.translate = '-50% -50%';
            }

            photos.forEach((p) => centerPhoto(p));

            function startPhotoSequence() {
                current = State.PHOTOS;
                photoIndex = 0;
                setStageHelper('Tap the photo to look closer');
                showPhoto(photoIndex);
            }

            function showPhoto(i) {
                const p = photos[i];
                requestAnimationFrame(() => p.classList.add('enter'));
            }

            function onPhotoTap(e) {
                const el = e.currentTarget;
                if (current !== State.PHOTOS) return;

                if (!el.classList.contains('enter')) return;

                if (zoomedPhoto === el) {
                    // second tap: close zoom, then move photo aside and advance
                    el.classList.remove('zoomed');
                    stageDim.classList.remove('is-visible');
                    zoomedPhoto = null;
                    setTimeout(() => {
                        el.classList.add('aside');
                        photoIndex++;
                        if (photoIndex < TOTAL_PHOTOS) {
                            setStageHelper('Tap the photo to look closer');
                            showPhoto(photoIndex);
                        } else {
                            setStageHelper('');
                            current = State.ENVELOPE;
                            setTimeout(startEnvelope, 500);
                        }
                    }, 350);
                } else {
                    // first tap: zoom in
                    el.classList.add('zoomed');
                    stageDim.classList.add('is-visible');
                    zoomedPhoto = el;
                }
            }
            photos.forEach((p) => {
                p.addEventListener('click', onPhotoTap);
                p.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        onPhotoTap(e);
                    }
                });
            });
            stageDim.addEventListener('click', () => {
                if (zoomedPhoto) {
                    onPhotoTap({
                        currentTarget: zoomedPhoto
                    });
                }
            });

            /* ===================================================================================
               STEP 5 — LOVE LETTER ENVELOPE
            =================================================================================== */
            const envelope = $('envelope');
            const letter = $('letter');
            const letterPaper = $('letterPaper');

            function startEnvelope() {
                envelope.classList.add('enter');
                setStageHelper('Tap the envelope');
            }

            function onEnvelopeTap() {
                if (current !== State.ENVELOPE) return;
                current = State.LETTER;
                setStageHelper('');
                envelope.classList.add('breaking');
                setTimeout(() => {
                    envelope.classList.add('opened');
                    setTimeout(() => {
                        letter.classList.add('enter');
                        setTimeout(playHandwriting, 500);
                    }, 700);
                }, 450);
            }
            envelope.addEventListener('click', onEnvelopeTap);
            envelope.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    onEnvelopeTap();
                }
            });

            /* ===================================================================================
               HANDWRITING ENGINE — pen-style character reveal, not a typewriter
            =================================================================================== */
            @php
            $letterLines = request('message') ?
                preg_split('/\r\n|\r|\n/', request('message')) : ['Dear Love,', '', 'Every day with you makes my life more beautiful.', '', 'Thank you for every smile,', 'every memory,', 'every moment.', '', 'You are my favorite chapter.', '', "\u{2764}"];
            @endphp
            const LETTER_TEXT = @json($letterLines);

            const penTip = createEl('span', 'pen-tip', letterPaper);

            function playHandwriting() {
                letterPaper.innerHTML = '';
                letterPaper.appendChild(penTip);
                penTip.classList.add('is-writing');

                const lines = LETTER_TEXT.map((lineText) => {
                    if (lineText === '') {
                        const spacer = createEl('div', 'hand-line spacer', letterPaper);
                        return {
                            el: spacer,
                            chars: []
                        };
                    }
                    const lineEl = createEl('div', 'hand-line', letterPaper);
                    return {
                        el: lineEl,
                        text: lineText
                    };
                });

                let li = 0,
                    ci = 0;

                function writeNext() {
                    if (li >= lines.length) {
                        penTip.classList.remove('is-writing');
                        setTimeout(() => {
                            current = State.SECRET;
                            startSecretHint();
                        }, 700);
                        return;
                    }
                    const line = lines[li];
                    if (!line.text) {
                        li++;
                        ci = 0;
                        writeNext();
                        return;
                    }

                    if (ci >= line.text.length) {
                        li++;
                        ci = 0;
                        writeNext();
                        return;
                    }

                    const ch = line.text[ci];
                    const span = createEl('span', 'ink-char', line.el);
                    span.textContent = ch === ' ' ? '\u00A0' : ch;
                    span.style.setProperty('--jr', rand(-6, 6) + 'deg');
                    span.style.setProperty('--jy', rand(-1, 1) + 'px');

                    requestAnimationFrame(() => {
                        span.classList.add('is-inked');
                        const r = span.getBoundingClientRect();
                        const pr = letterPaper.getBoundingClientRect();
                        penTip.style.left = (r.right - pr.left + letterPaper.scrollLeft) + 'px';
                        penTip.style.top = (r.top - pr.top + letterPaper.scrollTop + r.height / 2) + 'px';
                    });

                    ci++;
                    let delay = 45 + rand(0, 35);
                    if (ch === ',' || ch === '.') delay += 260;
                    if (ch === ' ') delay += 40;
                    setTimeout(writeNext, delay);
                }
                writeNext();
            }

            /* ===================================================================================
               STEP 6 — SECRET FINAL CARD
            =================================================================================== */
            const secretHint = $('secretHint');
            const secretCard = $('secretCard');
            const cardInner = $('cardInner');

            function startSecretHint() {
                secretHint.classList.add('enter');
            }

            function onSecretHintTap() {
                if (current !== State.SECRET) return;
                secretHint.classList.remove('enter');
                secretCard.classList.add('enter');
            }
            secretHint.addEventListener('click', onSecretHintTap);
            secretHint.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    onSecretHintTap();
                }
            });

            let cardFlipped = false;

            function onCardTap() {
                if (cardFlipped) return;
                cardFlipped = true;
                secretCard.classList.add('flipped');
            }
            secretCard.addEventListener('click', onCardTap);
            secretCard.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    onCardTap();
                }
            });

            cardInner.addEventListener('transitionend', (e) => {
                if (e.propertyName === 'transform' && cardFlipped) {
                    playEnding();
                }
            });

            /* ===================================================================================
               ENDING — confetti, floating hearts, replay button
            =================================================================================== */
            const confettiLayer = $('confettiLayer');
            const heartsLayer = $('heartsLayer');
            const confettiColors = ['var(--gold)', 'var(--secondary)', 'var(--accent)', 'var(--primary)'];

            function playEnding() {
                current = State.END;

                for (let i = 0; i < 40; i++) {
                    const c = createEl('span', 'confetti-piece', confettiLayer);
                    const w = rand(5, 9);
                    c.style.width = w + 'px';
                    c.style.height = (w * rand(.4, 1)) + 'px';
                    c.style.left = rand(0, 100) + '%';
                    c.style.background = confettiColors[Math.floor(rand(0, confettiColors.length))];
                    c.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
                    c.style.animationDuration = rand(2.4, 4) + 's';
                    c.style.animationDelay = rand(0, .6) + 's';
                    setTimeout(() => c.remove(), 5200);
                }

                for (let i = 0; i < 14; i++) {
                    const h = createEl('span', 'heart-piece', heartsLayer);
                    h.textContent = '\u2764';
                    h.style.left = rand(10, 90) + '%';
                    h.style.fontSize = rand(10, 18) + 'px';
                    h.style.animationDuration = rand(4, 7) + 's';
                    h.style.animationDelay = rand(0, 2) + 's';
                    setTimeout(() => h.remove(), 9000);
                }

                setTimeout(() => replayBtn.classList.add('is-visible'), 900);
            }

            /* ===================================================================================
               REPLAY — smooth reset back to the very beginning
            =================================================================================== */
            function resetExperience() {
                current = State.RIBBON;
                boxTapCount = 0;
                photoIndex = 0;
                zoomedPhoto = null;
                cardFlipped = false;

                ribbonWrap.classList.remove('is-untied');
                giftBox.classList.remove('shake-1', 'shake-2', 'is-glowing', 'is-open');
                giftScene.classList.remove('is-hidden');
                revealStage.classList.remove('is-active');
                stageDim.classList.remove('is-visible');

                photos.forEach((p) => p.classList.remove('enter', 'zoomed', 'aside'));

                envelope.classList.remove('enter', 'breaking', 'opened');
                letter.classList.remove('enter');
                letterPaper.innerHTML = '';
                letterPaper.appendChild(penTip);

                secretHint.classList.remove('enter');
                secretCard.classList.remove('enter', 'flipped');

                replayBtn.classList.remove('is-visible');
                setStageHelper('');
                setHelper('A little surprise is waiting…', 'Tap the ribbon');

                boxSparkles.innerHTML = '';
                confettiLayer.innerHTML = '';
                heartsLayer.innerHTML = '';
            }
            replayBtn.addEventListener('click', resetExperience);

            /* ===================================================================================
               DASHBOARD PREVIEW
               The scene opens on a wrapped box and only reveals the photos and the
               letter as the recipient taps through it. That is no use while the
               client is choosing which photo goes where, so ?preview_stage lets the
               dashboard jump straight to the beat being edited.
            =================================================================================== */
            (function previewStage() {
                const stage = @json(request('preview_stage'));
                if (stage !== 'photos' && stage !== 'letter') return;

                ribbonWrap.classList.add('is-untied');
                giftBox.classList.add('is-open');
                giftScene.classList.add('is-hidden');
                revealStage.classList.add('is-active');
                setStageHelper('');

                // All three polaroids at once, spread across the stage. The live
                // scene shows them one at a time; the client needs to see the set.
                const spread = ['26%', '50%', '74%'];
                photos.forEach((p, i) => {
                    p.style.left = spread[i];
                    p.style.top = '36%';
                    p.style.scale = '0.62';
                    p.classList.add('enter');
                });

                if (stage !== 'letter') return;

                current = State.LETTER;
                envelope.classList.add('enter', 'breaking', 'opened');
                letter.classList.add('enter');
                letterPaper.innerHTML = '';
                LETTER_TEXT.forEach((lineText) => {
                    const lineEl = createEl('div', lineText === '' ? 'hand-line spacer' : 'hand-line', letterPaper);
                    if (lineText === '') return;
                    for (const ch of lineText) {
                        const span = createEl('span', 'ink-char is-inked', lineEl);
                        span.textContent = ch === ' ' ? '\u00A0' : ch;
                    }
                });
            })();

        })();
    </script>
</body>

</html>