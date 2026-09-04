{{--
    Anniversary · Gift 3 — "Pop-up Book".

    One shared design, re-skinned four ways. Wrapper views
    (anniversary-page-3-variant-{V}-gift-3-page-{P}.blade.php) each @include this
    with a `giftTheme` (1-4) that picks the palette below. Like gift 1 / gift 2
    the gift is identical across the four gift-screen variants — only `giftPage`
    (the theme) changes the look.

    A closed leather book sits centre-stage (initials + wax-seal heart). Tap it
    (or "Open the book") and the FRONT COVER swings open slowly on a LEFT hinge.
    Inside are three spreads — "How it began", "The years between", "Still us" —
    each turned with a slow page-flip; on every spread a paper-craft scene stands
    up in staggered layers (photo cut-outs, a date medallion, a "n years" ring,
    a heart). Tap a standing photo to zoom it. On the last spread "Close the
    book" swings the BACK COVER shut from the RIGHT hinge onto "The End", and
    "Read again" restarts.

    Request params (same names gift 1 / gift 2 use):
      photo1, photo2, photo3   the standing cut-outs (fall back to drawn scenes)
      name_first, name_second  the couple (header + cover initials + The End)
      cal_month, cal_day       spread-1 date tag
      years                    spread-2 ring "n years"
      message, signed          spread-3 letter body + signature
      line1, line2             one-liners under spreads 1 & 2
      open=1                   start already opened on spread 1 (for wiring/preview)
--}}
@php
    $giftTheme = (int) ($giftTheme ?? request('theme', 1));

    $themes = [
        1 => ['name' => 'Taupe & Charcoal', 'bg1' => '#c7bca6', 'bg2' => '#8f7f65',
              'paper' => '#f7f2ea', 'paper2' => '#ece3d1', 'ink' => '#1c1a17',
              'accent' => '#141312', 'accent2' => '#5a5147', 'heart' => '#7a6a55',
              'cover1' => '#5a5147', 'cover2' => '#2f2b26', 'scene' => '#8f7f65'],
        2 => ['name' => 'Maroon & Gold', 'bg1' => '#a35a56', 'bg2' => '#5c1420',
              'paper' => '#f6ecd6', 'paper2' => '#ecdcb8', 'ink' => '#3a1016',
              'accent' => '#a3792f', 'accent2' => '#c9a75c', 'heart' => '#8b1e28',
              'cover1' => '#7a1f28', 'cover2' => '#4a0f16', 'scene' => '#7a3d3c'],
        3 => ['name' => 'Ivory & Peach Gold', 'bg1' => '#d8b98c', 'bg2' => '#9c7c52',
              'paper' => '#faf5ea', 'paper2' => '#efe3cb', 'ink' => '#33281a',
              'accent' => '#e0a865', 'accent2' => '#b98544', 'heart' => '#b5673c',
              'cover1' => '#c08a4e', 'cover2' => '#7f5a30', 'scene' => '#b08f62'],
        4 => ['name' => 'Bright Red & White', 'bg1' => '#e3bcab', 'bg2' => '#c4917c',
              'paper' => '#fdf6f2', 'paper2' => '#f3ded4', 'ink' => '#5c1712',
              'accent' => '#e8281a', 'accent2' => '#ff6a5c', 'heart' => '#e8281a',
              'cover1' => '#e8281a', 'cover2' => '#a3140b', 'scene' => '#cf9880'],
    ];
    $t = $themes[$giftTheme] ?? $themes[1];

    $nameFirst  = request('name_first', 'Ayesha');
    $nameSecond = request('name_second', 'Bilal');
    $startOpen  = request('open') === '1' ? true : (in_array(request('open'), ['s1', 's2', 's3', 'end'], true) ? request('open') : false);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $t['name'] }} — A Pop-up Book</title>
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
        --cover-1: {{ $t['cover1'] }};
        --cover-2: {{ $t['cover2'] }};
        --scene: {{ $t['scene'] }};
        --ease-turn: cubic-bezier(0.62, 0.02, 0.16, 1);
        --ease-pop: cubic-bezier(0.34, 1.42, 0.52, 1);
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
        gap: 20px;
        padding: 30px 16px;
        background: linear-gradient(160deg, var(--bg-1) 0%, var(--bg-2) 100%);
        color: var(--ink);
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

    /* ---------- 3D stage ---------- */
    .stage {
        position: relative;
        z-index: 1;
        width: 328px;
        max-width: 90vw;
        height: 452px;
        perspective: 1300px;
        perspective-origin: 50% 32%;
        transition: perspective-origin 0.3s ease;
    }

    .book {
        position: absolute;
        inset: 0;
        transform-style: preserve-3d;
        transform: rotateX(8deg) rotateZ(-0.4deg);
        transition: transform 1s var(--ease-turn);
    }

    .book.reading {
        transform: rotateX(4deg);
    }

    /* ---------- the readable page (base) ---------- */
    .base {
        position: absolute;
        inset: 0;
        border-radius: 14px;
        background: radial-gradient(120% 90% at 50% 0%, var(--paper) 0%, var(--paper-2) 100%);
        box-shadow:
            2px 0 0 #efe7d5, 5px 0 0 #e4dac2, 8px 0 0 #d8ccae,
            0 24px 50px rgba(0, 0, 0, 0.34), inset 0 0 0 1px rgba(255, 255, 255, 0.4);
        overflow: hidden;
        z-index: 10;
    }

    .base::before {
        content: '';
        position: absolute;
        inset: 8px;
        border: 1px solid var(--accent);
        opacity: 0.24;
        border-radius: 9px;
        pointer-events: none;
    }

    /* spine shading on the left edge */
    .base::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 30px;
        background: linear-gradient(90deg, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0));
        pointer-events: none;
        z-index: 6;
    }

    /* ---------- spreads ---------- */
    .spread {
        position: absolute;
        inset: 18px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.35s ease;
        transform-style: preserve-3d;
    }

    .spread.active {
        opacity: 1;
        pointer-events: auto;
    }

    .s-kicker {
        text-align: center;
        color: var(--accent-2);
        letter-spacing: 0.3em;
        text-transform: uppercase;
        font-size: 10px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .ribbon {
        position: relative;
        width: 176px;
        text-align: center;
        padding: 6px 0;
        background: var(--heart);
        color: var(--paper);
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 9px 16px rgba(0, 0, 0, 0.26);
        z-index: 2;
    }

    .ribbon::before,
    .ribbon::after {
        content: '';
        position: absolute;
        top: 0;
        border: 14px solid var(--heart);
        filter: brightness(0.68);
    }

    .ribbon::before {
        left: -16px;
        border-left-color: transparent;
    }

    .ribbon::after {
        right: -16px;
        border-right-color: transparent;
    }

    .popwrap {
        position: relative;
        width: 100%;
        height: 258px;
        flex: none;
        transform-style: preserve-3d;
    }

    /* standing pieces: folded flat until the spread is "revealed" */
    .pop {
        position: absolute;
        transform-origin: 50% 100%;
        transition: transform 0.9s var(--ease-pop);
    }

    .pop-photo {
        width: 148px;
        height: 174px;
        background: #fff;
        padding: 8px 8px 26px;
        border-radius: 2px;
        box-shadow: 0 20px 28px rgba(0, 0, 0, 0.34);
        left: 50%;
        margin-left: -74px;
        top: 26px;
        cursor: zoom-in;
        transform: rotateX(-90deg) rotate(-4deg);
    }

    .spread.revealed .pop-photo {
        transform: rotateX(0deg) rotate(-4deg) translateZ(58px);
        transition-delay: 0.16s;
    }

    .pop-photo .shot {
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: var(--paper-2);
        display: block;
    }

    .pop-photo .shot img,
    .pop-photo .shot svg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .pop-photo .cap {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 6px;
        text-align: center;
        font-family: 'Dancing Script', cursive;
        font-size: 12px;
        color: #5b5147;
    }

    .pop-medallion {
        width: 128px;
        height: 128px;
        border-radius: 50%;
        background: radial-gradient(circle at 34% 30%, var(--accent-2), var(--accent) 78%);
        color: var(--paper);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        box-shadow: 0 16px 28px rgba(0, 0, 0, 0.34), inset 0 0 0 3px rgba(255, 255, 255, 0.18);
        left: 50%;
        margin-left: -64px;
        top: 40px;
        transform: rotateX(-90deg);
    }

    .spread.revealed .pop-medallion {
        transform: rotateX(0deg) translateZ(76px);
        transition-delay: 0.34s;
    }

    .pop-medallion .m-top {
        font-size: 8px;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        opacity: 0.85;
    }

    .pop-medallion .m-month {
        font-size: 11px;
        margin-top: 2px;
    }

    .pop-medallion .m-day,
    .pop-medallion .m-years {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 34px;
        line-height: 1;
    }

    .pop-medallion .m-unit {
        font-size: 10px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        opacity: 0.85;
        margin-top: 3px;
    }

    .pop-tag {
        left: 50%;
        margin-left: -70px;
        top: 214px;
        width: 140px;
        text-align: center;
        padding: 5px 0;
        font-size: 10px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--paper);
        background: var(--accent);
        transform: rotateX(-90deg);
        box-shadow: 0 8px 14px rgba(0, 0, 0, 0.22);
    }

    .spread.revealed .pop-tag {
        transform: rotateX(0deg) translateZ(20px);
        transition-delay: 0.46s;
    }

    /* spread 2 — photo left, ring right */
    .s2 .pop-photo {
        left: 4px;
        margin-left: 0;
        top: 20px;
        transform: rotateX(-90deg) rotate(-5deg);
    }

    .s2 .spread.revealed .pop-photo,
    .s2.revealed .pop-photo {
        transform: rotateX(0deg) rotate(-5deg) translateZ(50px);
    }

    .s2 .pop-medallion {
        left: auto;
        right: 12px;
        margin-left: 0;
        top: 96px;
    }

    /* spread 3 — heart / photo centred, letter below */
    .s3 .popwrap {
        flex: none;
        height: 168px;
    }

    .s3 .pop-photo {
        top: 6px;
    }

    .pop-heart {
        left: 50%;
        margin-left: -66px;
        top: 8px;
        width: 132px;
        height: 148px;
        background: #fff;
        padding: 8px 8px 26px;
        border-radius: 2px;
        box-shadow: 0 20px 28px rgba(0, 0, 0, 0.34);
        transform: rotateX(-90deg) rotate(3deg);
    }

    .spread.revealed .pop-heart {
        transform: rotateX(0deg) rotate(3deg) translateZ(56px);
        transition-delay: 0.16s;
    }

    .pop-heart .shot {
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: var(--paper-2);
        display: block;
    }

    .pop-heart .shot svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    .s-caption {
        text-align: center;
        font-style: italic;
        font-size: 13.5px;
        line-height: 1.55;
        color: var(--ink);
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.5s ease 0.34s, transform 0.5s ease 0.34s;
    }

    .spread.revealed .s-caption {
        opacity: 1;
        transform: none;
    }

    .s3 .letter {
        margin-top: 4px;
        max-height: 168px;
        overflow: auto;
        text-align: center;
        padding: 0 4px;
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.55s ease 0.4s, transform 0.55s ease 0.4s;
    }

    .spread.revealed .letter {
        opacity: 1;
        transform: none;
    }

    .s3 .letter p {
        margin: 0;
        font-style: italic;
        font-size: 13.5px;
        line-height: 1.6;
        color: var(--ink);
    }

    .s3 .letter .signed {
        display: block;
        margin-top: 7px;
        font-family: 'Dancing Script', cursive;
        font-style: normal;
        font-size: 17px;
        color: var(--heart);
    }

    /* contact shadow the standing pieces cast on the page */
    .spread::after {
        content: '';
        position: absolute;
        left: 14%;
        right: 14%;
        top: 232px;
        height: 30px;
        background: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.22), rgba(0, 0, 0, 0) 70%);
        opacity: 0;
        transition: opacity 0.5s ease 0.3s;
    }

    .spread.revealed::after {
        opacity: 1;
    }

    /* ---------- the turning page ---------- */
    .turner {
        position: absolute;
        inset: 0;
        transform-origin: left center;
        transform: rotateY(0deg) translateZ(0);
        transform-style: preserve-3d;
        transition: transform 0.5s var(--ease-turn);
        z-index: 40;
        opacity: 0;
        pointer-events: none;
    }

    .turner.run {
        opacity: 1;
    }

    /* step 1 — the page lifts off the book */
    .turner.lift {
        transition-duration: 0.36s;
        transform: rotateY(-13deg) translateZ(54px);
    }

    /* step 2 — it sweeps across to the left */
    .turner.flip {
        transition-duration: 0.86s;
        transform: rotateY(-178deg) translateZ(0);
    }

    .t-face {
        position: absolute;
        inset: 0;
        border-radius: 14px;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        overflow: hidden;
    }

    .t-front {
        background: linear-gradient(105deg, var(--paper) 0%, var(--paper-2) 58%, #d8ccae 100%);
        box-shadow: 14px 0 40px rgba(0, 0, 0, 0.34);
    }

    /* leading-edge curl shadow */
    .t-front::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 40px;
        background: linear-gradient(90deg, rgba(0, 0, 0, 0.22), rgba(0, 0, 0, 0));
    }

    .t-front::after {
        content: '\2735';
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-2);
        font-size: 22px;
        opacity: 0.45;
    }

    .t-back {
        background: linear-gradient(255deg, var(--paper-2) 0%, #cabfa6 55%, #b6a888 100%);
        box-shadow: -14px 0 40px rgba(0, 0, 0, 0.34);
        transform: rotateY(180deg);
    }

    .t-back::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        width: 46px;
        background: linear-gradient(270deg, rgba(0, 0, 0, 0.22), rgba(0, 0, 0, 0));
    }

    /* ---------- covers ---------- */
    .cover-face {
        position: absolute;
        inset: 0;
        border-radius: 15px;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        text-align: center;
        padding: 28px;
    }

    .cover-leather {
        background: linear-gradient(150deg, var(--cover-1) 0%, var(--cover-2) 100%);
        color: var(--paper);
        box-shadow: 0 26px 52px rgba(0, 0, 0, 0.36), inset 0 0 0 1px rgba(255, 255, 255, 0.12);
    }

    .cover-leather::before {
        content: '';
        position: absolute;
        inset: 14px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 9px;
    }

    .cover-inner {
        background: var(--paper-2);
        box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.06);
    }

    .ex-libris {
        font-size: 10px;
        letter-spacing: 0.24em;
        text-transform: uppercase;
        color: var(--accent-2);
        opacity: 0.75;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ex-libris span {
        font-family: 'Dancing Script', cursive;
        font-size: 22px;
        letter-spacing: 0;
        text-transform: none;
        color: var(--heart);
        opacity: 1;
    }

    .cover-inner::before {
        content: '';
        position: absolute;
        inset: 14px;
        border: 1px dashed var(--accent);
        opacity: 0.3;
        border-radius: 9px;
    }

    .c-monogram {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 46px;
        line-height: 1;
    }

    .c-monogram .amp {
        opacity: 0.7;
        padding: 0 4px;
    }

    .c-sub {
        font-size: 11px;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        opacity: 0.85;
    }

    .c-seal {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: radial-gradient(circle at 36% 32%, var(--heart), rgba(0, 0, 0, 0.35) 130%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--paper);
        font-size: 18px;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
    }

    .c-hint {
        display: none;
        position: absolute;
        bottom: 24px;
        left: 0;
        right: 0;
        font-size: 10px;
        letter-spacing: 0.24em;
        text-transform: uppercase;
        opacity: 0.7;
        animation: breathe 2.4s ease-in-out infinite;
    }

    @keyframes breathe {

        0%,
        100% {
            opacity: 0.35;
        }

        50% {
            opacity: 0.85;
        }
    }

    /* front cover — LEFT hinge, opens */
    .cover-front {
        position: absolute;
        inset: 0;
        border-radius: 15px;
        transform-origin: left center;
        transform: rotateY(0deg) translateZ(0);
        transform-style: preserve-3d;
        transition: transform 0.5s var(--ease-turn);
        z-index: 60;
    }

    /* step 1 — cover lifts at the fore-edge */
    .cover-front.lift {
        transition-duration: 0.36s;
        transform: rotateY(-24deg) translateZ(40px);
    }

    /* step 2 — swings fully open to the left */
    .cover-front.open {
        transition-duration: 0.92s;
        transform: rotateY(-174deg) translateZ(0);
    }

    /* end — quietly returns so only "The End" shows */
    .cover-front.reclose {
        transition-duration: 0.9s;
        transform: rotateY(0deg) translateZ(0);
    }

    .cover-front.idle {
        animation: hover 3.6s ease-in-out infinite;
    }

    .cover-front.idle .c-hint {
        display: block;
    }

    @keyframes hover {

        0%,
        100% {
            transform: rotateY(0deg) translateY(0);
        }

        50% {
            transform: rotateY(0deg) translateY(-6px);
        }
    }

    .cover-front .cover-inner {
        transform: rotateY(180deg);
    }

    /* back cover — RIGHT hinge, closes at the end */
    .cover-back {
        position: absolute;
        inset: 0;
        border-radius: 15px;
        transform-origin: right center;
        transform: rotateY(176deg);
        transform-style: preserve-3d;
        transition: transform 1.15s var(--ease-turn), opacity 0.2s ease;
        z-index: 70;
        pointer-events: none;
        opacity: 0;
    }

    .cover-back.close {
        transform: rotateY(0deg);
        pointer-events: auto;
        opacity: 1;
    }

    .cover-back .cover-inner {
        transform: rotateY(180deg);
    }

    .c-end {
        font-family: 'Dancing Script', cursive;
        font-weight: 700;
        font-size: 40px;
        line-height: 1;
    }

    /* ---------- controls ---------- */
    .controls {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        min-height: 58px;
    }

    .dots {
        display: flex;
        gap: 8px;
    }

    .dots[hidden] {
        display: none;
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
        opacity: 0.7;
    }

    .dots span.active {
        opacity: 1;
        transform: scale(1.5);
    }

    .nav {
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
        transition: background 0.25s ease, opacity 0.25s ease;
    }

    .nav:hover {
        background: rgba(255, 255, 255, 0.14);
    }

    .nav:disabled {
        opacity: 0.5;
        cursor: default;
    }

    /* ---------- peek (zoom a photo) ---------- */
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
        padding: 10vw;
    }

    .peekbox.open {
        opacity: 1;
        pointer-events: auto;
    }

    .peek-inner {
        transform: scale(0.86);
        transition: transform 0.35s ease;
        width: min(78vw, 300px);
        background: #fff;
        padding: 12px 12px 34px;
        border-radius: 3px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
    }

    .peekbox.open .peek-inner {
        transform: scale(1);
    }

    .peek-inner .shot {
        display: block;
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: var(--paper-2);
    }

    .peek-inner .shot img,
    .peek-inner .shot svg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
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
    }

    @media (max-width: 380px) {
        .stage {
            transform: scale(0.82);
            margin: -44px 0;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .book,
        .cover-front,
        .cover-back,
        .turner.flip,
        .pop,
        .s-caption,
        .s3 .letter {
            transition-duration: 0.001s !important;
            animation: none !important;
        }
    }
    </style>
</head>

<body>

    <div class="glow g1"></div>
    <div class="glow g2"></div>

    <div class="head">
        <div class="kicker">Our Anniversary</div>
        <div class="couple">{{ $nameFirst }} &amp; {{ $nameSecond }}</div>
    </div>

    <div class="stage" id="stage">
        <div class="book" id="book">

            <div class="base" id="base">

                {{-- ---------- spread 1 ---------- --}}
                <div class="spread s1" data-spread="0">
                    <div class="s-kicker">Chapter one</div>
                    <div class="ribbon">How it began</div>
                    <div class="popwrap">
                        <div class="pop pop-photo" data-peek tabindex="0">
                            <div class="shot">
                                @if(request('photo1'))
                                <img src="{{ request('photo1') }}" alt="">
                                @else
                                @include('birthday.partials._anniversary_gift3_scene', ['kind' => 'meet'])
                                @endif
                            </div>
                            <span class="cap">the beginning</span>
                        </div>
                        <div class="pop pop-tag">Since {{ request('cal_month', 'September') }} {{ request('cal_day', '14') }}</div>
                    </div>
                    <div class="s-caption">{{ request('line1', 'Where every good thing started.') }}</div>
                </div>

                {{-- ---------- spread 2 ---------- --}}
                <div class="spread s2" data-spread="1">
                    <div class="s-kicker">Chapter two</div>
                    <div class="ribbon">The years between</div>
                    <div class="popwrap">
                        <div class="pop pop-photo" data-peek tabindex="0">
                            <div class="shot">
                                @if(request('photo2'))
                                <img src="{{ request('photo2') }}" alt="">
                                @else
                                @include('birthday.partials._anniversary_gift3_scene', ['kind' => 'trip'])
                                @endif
                            </div>
                            <span class="cap">us, in motion</span>
                        </div>
                        <div class="pop pop-medallion">
                            <span class="m-top">Together</span>
                            <span class="m-years">{{ request('years', '5') }}</span>
                            <span class="m-unit">years</span>
                        </div>
                    </div>
                    <div class="s-caption">{{ request('line2', 'Every one of them, my favourite.') }}</div>
                </div>

                {{-- ---------- spread 3 ---------- --}}
                <div class="spread s3" data-spread="2">
                    <div class="s-kicker">Chapter three</div>
                    <div class="ribbon">Still us</div>
                    <div class="popwrap">
                        @if(request('photo3'))
                        <div class="pop pop-photo" data-peek tabindex="0" style="margin-left:-66px;width:132px;height:150px;">
                            <div class="shot"><img src="{{ request('photo3') }}" alt=""></div>
                            <span class="cap">now &amp; on</span>
                        </div>
                        @else
                        <div class="pop pop-heart">
                            <div class="shot">
                                @include('birthday.partials._anniversary_gift3_scene', ['kind' => 'letter'])
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="letter">
                        <p>{{ request('message', "Every year with you has been the one I would choose again. Here's to every road still ahead — walked side by side.") }}</p>
                        <span class="signed">{{ request('signed', '— always yours') }}</span>
                    </div>
                </div>

            </div>

            <div class="turner" id="turner">
                <div class="t-face t-front"></div>
                <div class="t-face t-back"></div>
            </div>

            <div class="cover-front" id="coverFront">
                <div class="cover-face cover-leather">
                    <div class="c-seal">&#10084;</div>
                    <div class="c-monogram">
                        {{ mb_substr($nameFirst, 0, 1) }}<span class="amp">&amp;</span>{{ mb_substr($nameSecond, 0, 1) }}
                    </div>
                    <div class="c-sub">Our story</div>
                    <div class="c-hint">Tap to open</div>
                </div>
                <div class="cover-face cover-inner">
                    <div class="ex-libris">This book belongs to<span>{{ $nameFirst }} &amp; {{ $nameSecond }}</span></div>
                </div>
            </div>

            <div class="cover-back" id="coverBack">
                <div class="cover-face cover-leather">
                    <div class="c-end">The End</div>
                    <div class="c-sub">{{ $nameFirst }} &amp; {{ $nameSecond }}</div>
                </div>
                <div class="cover-face cover-inner"></div>
            </div>

        </div>
    </div>

    <div class="controls">
        <div class="dots" id="dots" hidden><span></span><span></span><span></span></div>
        <button class="nav" id="nav" type="button">Open the book</button>
    </div>

    <div class="peekbox" id="peekbox">
        <button class="peekbox-close" id="peekboxClose" aria-label="Close">&#10005;</button>
        <div class="peek-inner" id="peekInner"></div>
    </div>

    <script>
    (function() {
        'use strict';

        var stage = document.getElementById('stage');
        var book = document.getElementById('book');
        var base = document.getElementById('base');
        var turner = document.getElementById('turner');
        var coverFront = document.getElementById('coverFront');
        var nav = document.getElementById('nav');
        var dots = document.getElementById('dots');
        var spreads = Array.prototype.slice.call(base.querySelectorAll('.spread'));

        var coverBack = document.getElementById('coverBack');
        var state = 'cover';
        var busy = false;

        // motion timings (ms) — kept slow + deliberate so it reads as a real book
        var T = { coverLift: 360, coverSwing: 920, turnLift: 340, turnSwing: 860, close: 1150 };

        function idxOf(st) {
            return { s1: 0, s2: 1, s3: 2 }[st];
        }

        function paintDots(active) {
            Array.prototype.forEach.call(dots.children, function(d, i) {
                d.className = i < active ? 'done' : (i === active ? 'active' : '');
            });
        }

        // stand the pop-ups up on a spread, once its page has settled
        function revealSpread(i) {
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    if (spreads[i]) spreads[i].classList.add('revealed');
                });
            });
        }

        // no cover classes here — those are driven step-by-step by the animators
        function render() {
            var ai = idxOf(state);
            book.classList.toggle('reading', ai != null);
            book.classList.toggle('ended', state === 'end');

            spreads.forEach(function(s, i) {
                s.classList.toggle('active', i === ai);
                if (i !== ai) s.classList.remove('revealed');
            });

            var isSpread = ai != null;
            dots.hidden = !isSpread;
            if (isSpread) paintDots(ai);

            nav.textContent =
                state === 'cover' ? 'Open the book' :
                state === 's3' ? 'Close the book' :
                state === 'end' ? 'Read again' : 'Turn the page';
        }

        function snapTurner() {
            // drop the turning page back to its start with no visible rewind
            turner.classList.remove('run', 'lift', 'flip');
            turner.style.transition = 'none';
            void turner.offsetWidth;
            turner.style.transition = '';
        }

        function openCover() {
            busy = true;
            coverFront.classList.remove('idle');
            state = 's1';
            render();                                  // spread 1 active, pop-ups still flat
            // step 1: the cover lifts at the fore-edge …
            requestAnimationFrame(function() {
                coverFront.classList.add('lift');
            });
            // step 2: … then swings the rest of the way open
            setTimeout(function() {
                coverFront.classList.remove('lift');
                coverFront.classList.add('open');
            }, T.coverLift);
            // done — raise the pop-ups, release the lock
            setTimeout(function() {
                revealSpread(0);
                busy = false;
            }, T.coverLift + T.coverSwing);
        }

        function turnPage(fromIdx) {                    // 0 => s1→s2, 1 => s2→s3
            busy = true;
            snapTurner();
            turner.classList.add('run');               // opaque, flat over the current page
            void turner.offsetWidth;

            state = 's' + (fromIdx + 2);
            render();                                   // next spread active underneath, flat

            requestAnimationFrame(function() {
                turner.classList.add('lift');           // page peels up
                setTimeout(function() {
                    turner.classList.remove('lift');
                    turner.classList.add('flip');       // sweeps across to the left
                }, T.turnLift);
            });

            setTimeout(function() {
                snapTurner();
                revealSpread(fromIdx + 1);
                busy = false;
            }, T.turnLift + T.turnSwing + 40);
        }

        function closeBook() {
            busy = true;
            state = 'end';
            spreads.forEach(function(s) { s.classList.remove('revealed'); });
            render();

            // tuck the open front cover shut instantly — no leftward sweep, so the
            // only motion the eye follows is the back cover coming in from the RIGHT
            coverFront.style.transition = 'none';
            coverFront.classList.remove('open', 'lift');
            coverFront.classList.add('reclose');
            void coverFront.offsetWidth;
            coverFront.style.transition = '';

            requestAnimationFrame(function() {
                coverBack.classList.add('close');      // swings shut from the RIGHT
            });
            setTimeout(function() { busy = false; }, T.close + 60);
        }

        function reset() {
            busy = true;
            state = 'cover';
            coverBack.classList.remove('close');
            coverFront.classList.remove('open', 'lift', 'reclose');
            spreads.forEach(function(s) { s.classList.remove('active', 'revealed'); });
            snapTurner();
            render();
            setTimeout(function() {
                coverFront.classList.add('idle');
                busy = false;
            }, T.coverSwing);
        }

        function advance() {
            if (busy) return;
            if (state === 'cover') return openCover();
            if (state === 's1') return turnPage(0);
            if (state === 's2') return turnPage(1);
            if (state === 's3') return closeBook();
            if (state === 'end') return reset();
        }

        nav.addEventListener('click', advance);

        // tap the closed cover to open; tap a spread (not a photo) to advance
        coverFront.addEventListener('click', function() {
            if (state === 'cover') advance();
        });
        base.addEventListener('click', function(e) {
            if (state[0] === 's' && !e.target.closest('[data-peek]')) advance();
        });
        document.getElementById('coverBack').addEventListener('click', function() {
            if (state === 'end') advance();
        });

        // desktop parallax — a little extra depth
        if (window.matchMedia('(pointer:fine)').matches) {
            stage.addEventListener('pointermove', function(e) {
                var r = stage.getBoundingClientRect();
                var dx = (e.clientX - (r.left + r.width / 2)) / r.width;
                var dy = (e.clientY - (r.top + r.height / 2)) / r.height;
                stage.style.perspectiveOrigin =
                    (50 + dx * 26).toFixed(1) + '% ' + (32 + dy * 18).toFixed(1) + '%';
            });
            stage.addEventListener('pointerleave', function() {
                stage.style.perspectiveOrigin = '50% 32%';
            });
        }

        // ---- zoom a standing photo ----
        var peekbox = document.getElementById('peekbox');
        var peekInner = document.getElementById('peekInner');
        var peekboxClose = document.getElementById('peekboxClose');

        function openPeek(el) {
            var shot = el.querySelector('.shot');
            if (!shot) return;
            peekInner.innerHTML = '<div class="shot">' + shot.innerHTML + '</div>';
            peekbox.classList.add('open');
        }

        function closePeek() {
            peekbox.classList.remove('open');
        }

        base.querySelectorAll('[data-peek]').forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.stopPropagation();
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

        render();

        @if($startOpen)
        // deep-link / preview: jump straight to a given step, already settled
        (function() {
            var want = @json(is_string($startOpen) ? $startOpen : 's1');
            coverFront.classList.remove('idle');
            if (want === 'end') {
                state = 'end';
                render();
                coverFront.style.transition = 'none';
                coverFront.classList.add('reclose');
                coverBack.style.transition = 'none';
                coverBack.classList.add('close');
                void book.offsetWidth;
                coverFront.style.transition = '';
                coverBack.style.transition = '';
            } else {
                state = ['s1', 's2', 's3'].indexOf(want) >= 0 ? want : 's1';
                render();
                coverFront.classList.add('open');
                revealSpread(idxOf(state));
            }
        })();
        @else
        coverFront.classList.add('idle');
        @endif
    })();
    </script>
</body>

</html>
