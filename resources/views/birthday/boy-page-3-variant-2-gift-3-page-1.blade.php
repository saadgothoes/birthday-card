<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Our Story — A Book For You</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Dancing+Script:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
    /* =========================================================
   OUR STORY — A Premium Interactive Book Gift
   ========================================================= */

    :root {
        /* ============================================================
     THEME TOKENS — change values here to re-skin the whole book.
     Everything below reads from these, nothing else hardcodes color.
     ============================================================ */
        --cream: #F7F2EA;
        --sand: #E9D8C5;
        --tan: #D2B48C;
        --leather: #8B5E3C;
        --espresso: #3B2A20;
        --gold: #C9A24B;
        --gold-light: #E8CE8B;
        --paper-white: #FFFDF8;
        --ink: #2E2013;

        /* stage backdrop gradient (3 stops, dark → darker) */
        --bg-1: #4A3626;
        --bg-2: #2C1E15;
        --bg-3: #1C130D;

        /* cover leather gradient + spine */
        --leather-dark: #6B4429;
        --leather-deep: #4A2E1A;
        --leather-deepest: #3A2213;
        --spine-dark: #2A1C12;

        /* rgb triplets so rgba(var(--x-rgb), alpha) works anywhere */
        --shadow-rgb: 0, 0, 0;
        --gold-rgb: 201, 162, 75;
        --leather-rgb: 139, 94, 60;
        --espresso-rgb: 59, 42, 32;
        --white-rgb: 255, 255, 255;
        --cream-rgb: 247, 242, 234;
        --ink-rgb: 20, 12, 6;

        --font-display: 'Playfair Display', serif;
        --font-body: 'Cormorant Garamond', serif;
        --font-script: 'Dancing Script', cursive;

        --ease-book: cubic-bezier(0.65, 0, 0.35, 1);
        --flip-duration: 1.3s;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100vh;
        height: 100svh;
        height: 100dvh;
        overflow: hidden;
        background: var(--espresso);
        font-family: var(--font-body);
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
        overscroll-behavior: none;
    }

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* =========================================================
   STAGE / BACKGROUND
   ========================================================= */

    .stage {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(120% 90% at 50% 0%, var(--bg-1) 0%, var(--bg-2) 45%, var(--bg-3) 100%);
        overflow: hidden;
        padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
    }

    .glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.35;
        pointer-events: none;
    }

    .glow-a {
        width: 45vh;
        height: 45vh;
        top: -10%;
        left: -10%;
        background: radial-gradient(circle, var(--gold-light), transparent 70%);
        animation: drift-a 18s ease-in-out infinite;
    }

    .glow-b {
        width: 40vh;
        height: 40vh;
        bottom: -8%;
        right: -8%;
        background: radial-gradient(circle, var(--leather), transparent 70%);
        animation: drift-b 22s ease-in-out infinite;
    }

    @keyframes drift-a {

        0%,
        100% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(6%, 8%);
        }
    }

    @keyframes drift-b {

        0%,
        100% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(-6%, -6%);
        }
    }

    .dust {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .dust span {
        position: absolute;
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: var(--gold-light);
        opacity: 0;
        animation: dust-float linear infinite;
    }

    @keyframes dust-float {
        0% {
            opacity: 0;
            transform: translateY(0) translateX(0);
        }

        10% {
            opacity: 0.55;
        }

        90% {
            opacity: 0.35;
        }

        100% {
            opacity: 0;
            transform: translateY(-90vh) translateX(20px);
        }
    }

    /* =========================================================
   BOOK
   ========================================================= */

    .book-shell {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: clamp(14px, 3.2vh, 22px);
        height: 100%;
        width: 100%;
        perspective: 1900px;
    }

    .book {
        position: relative;
        width: min(88vw, 380px, calc(68dvh * 0.5625));
        aspect-ratio: 9 / 16;
        perspective: 2200px;
        flex: 0 0 auto;
        transform-style: preserve-3d;
        transform: rotateX(4deg) rotateY(-3deg);
        animation: book-settle 1.3s var(--ease-book) both;
    }

    @keyframes book-settle {
        0% {
            transform: rotateX(16deg) rotateY(-10deg) translateY(18px);
            opacity: 0;
        }

        100% {
            transform: rotateX(4deg) rotateY(-3deg) translateY(0);
            opacity: 1;
        }
    }

    .book-shadow {
        position: absolute;
        bottom: 3%;
        left: 50%;
        width: min(70vw, 300px);
        height: 22px;
        transform: translateX(-50%);
        background: radial-gradient(ellipse at center, rgba(var(--shadow-rgb), 0.55), transparent 72%);
        filter: blur(4px);
        z-index: 1;
        pointer-events: none;
    }

    .cover-spine {
        position: absolute;
        left: -2%;
        top: 1.5%;
        width: 4%;
        height: 97%;
        background: linear-gradient(90deg, var(--spine-dark), var(--leather) 60%, var(--spine-dark));
        border-radius: 6px 0 0 6px;
        box-shadow: inset -2px 0 6px rgba(var(--shadow-rgb), 0.5);
        z-index: 1;
    }

    /* ---------- Closed Cover ---------- */

    .cover {
        position: absolute;
        inset: 0;
        z-index: 20;
        transform-style: preserve-3d;
        transform-origin: left center;
        transform: rotateY(0deg);
        transition: transform 1.4s var(--ease-book);
        cursor: pointer;
    }

    .book.open .cover {
        transform: rotateY(-172deg);
        pointer-events: none;
    }

    .cover-face {
        position: absolute;
        inset: 0;
        border-radius: 10px;
        backface-visibility: hidden;
        box-shadow:
            0 25px 60px rgba(var(--shadow-rgb), 0.55),
            0 4px 10px rgba(var(--shadow-rgb), 0.4);
    }

    .cover-face.front {
        background:
            repeating-radial-gradient(circle at 30% 20%, rgba(var(--white-rgb), 0.025) 0px, rgba(var(--white-rgb), 0.025) 1px, transparent 1px, transparent 3px),
            linear-gradient(135deg, rgba(var(--white-rgb), 0.05), rgba(var(--shadow-rgb), 0.15)),
            radial-gradient(140% 100% at 30% 0%, var(--leather-dark), var(--leather-deep) 55%, var(--leather-deepest) 100%);
        border: 1px solid rgba(var(--gold-rgb), 0.35);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: 12% 8%;
        background-blend-mode: overlay, overlay, normal;
    }

    .cover-face.front::before {
        content: "";
        position: absolute;
        inset: 6%;
        border: 1px solid rgba(var(--gold-rgb), 0.4);
        border-radius: 4px;
        pointer-events: none;
    }

    .cover-face.back {
        background: linear-gradient(135deg, var(--sand), var(--tan));
        transform: rotateY(180deg);
    }

    .cover-frame {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        margin-top: 18%;
    }

    .cover-ornament {
        color: var(--gold);
        font-size: clamp(14px, 3.5vw, 18px);
        opacity: 0.85;
        letter-spacing: 4px;
    }

    .cover-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: clamp(28px, 8vw, 40px);
        color: var(--gold-light);
        text-shadow: 0 2px 10px rgba(var(--shadow-rgb), 0.5), 0 0 22px rgba(var(--gold-rgb), 0.25);
        letter-spacing: 1px;
        margin: 0;
        text-align: center;
    }

    .cover-sub {
        font-family: var(--font-script);
        font-size: clamp(18px, 5vw, 24px);
        color: var(--sand);
        margin: -6px 0 0;
        opacity: 0.9;
    }

    .tap-hint {
        font-family: var(--font-body);
        letter-spacing: 3px;
        text-transform: uppercase;
        font-size: clamp(11px, 2.6vw, 13px);
        color: var(--sand);
        opacity: 0.75;
        animation: pulse-hint 2.4s ease-in-out infinite;
        margin-bottom: 4%;
    }

    .tap-hint.small {
        margin-top: 12px;
        margin-bottom: 0;
    }

    @keyframes pulse-hint {

        0%,
        100% {
            opacity: 0.4;
            transform: translateY(0);
        }

        50% {
            opacity: 0.9;
            transform: translateY(-3px);
        }
    }

    /* =========================================================
   PAGES CONTAINER
   ========================================================= */

    .pages {
        position: absolute;
        inset: 0;
        border-radius: 10px;
        overflow: hidden;
        background: var(--cream);
        box-shadow:
            0 25px 55px rgba(var(--shadow-rgb), 0.5),
            inset 0 0 0 1px rgba(var(--leather-rgb), 0.15);
    }

    .pages::before {
        content: "";
        position: absolute;
        top: 2%;
        bottom: 2%;
        right: -1.5%;
        width: 6px;
        background: repeating-linear-gradient(180deg,
                var(--cream) 0px, var(--cream) 1px,
                var(--sand) 1px, var(--sand) 2px);
        box-shadow: 2px 0 4px rgba(var(--shadow-rgb), 0.25);
        border-radius: 0 3px 3px 0;
        z-index: 3;
        pointer-events: none;
    }

    .page {
        position: absolute;
        inset: 0;
        transform-style: preserve-3d;
        transform-origin: left center;
        transform: rotateY(0deg);
        opacity: 1;
        transition: transform var(--flip-duration) var(--ease-book);
        backface-visibility: hidden;
        pointer-events: none;
    }

    .page.current {
        pointer-events: auto;
        z-index: 10;
    }

    .page.gone-prev {
        transform: rotateY(-172deg) translateZ(-36px);
        z-index: 15;
    }

    .page.gone-next {
        transform: rotateY(172deg) translateZ(-36px);
        z-index: 15;
    }

    /* Dynamic lighting sweep synced with the flip — gives the same
   dramatic, realistic feel as the cover-opening animation */
    .page-shade {
        position: absolute;
        inset: 0;
        z-index: 6;
        pointer-events: none;
        opacity: 0;
        background: linear-gradient(90deg,
                rgba(var(--ink-rgb), 0.5) 0%,
                rgba(var(--ink-rgb), 0.15) 18%,
                transparent 45%);
        transition: opacity var(--flip-duration) var(--ease-book);
    }

    .page.gone-prev .page-shade,
    .page.gone-next .page-shade {
        opacity: 1;
    }

    .page.current .page-shade {
        opacity: 0;
    }

    .page-inner {
        box-shadow: 3px 0 10px rgba(var(--espresso-rgb), 0.10);
    }

    .page-inner {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 11% 8%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background:
            repeating-linear-gradient(180deg, rgba(var(--leather-rgb), 0.02) 0px, rgba(var(--leather-rgb), 0.02) 2px, transparent 2px, transparent 4px),
            linear-gradient(160deg, var(--cream), var(--sand) 130%);
    }

    .page-inner::after {
        content: "";
        position: absolute;
        inset: 0;
        box-shadow: inset 0 0 40px rgba(var(--leather-rgb), 0.12);
        pointer-events: none;
    }

    .page-label {
        font-family: var(--font-display);
        font-size: clamp(13px, 3.4vw, 16px);
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--leather);
        opacity: 0.75;
        margin: 0 0 6%;
    }

    /* ---------- Letter-paper treatment (used on 3 pages) ---------- */
    .page-inner.letter-style {
        background:
            repeating-linear-gradient(180deg,
                transparent 0px, transparent 27px,
                rgba(var(--leather-rgb), 0.16) 28px, transparent 29px),
            linear-gradient(170deg, var(--paper-white), var(--sand) 145%);
    }

    .letter-style .handwritten,
    .letter-style .letter-text {
        color: var(--ink);
    }

    /* ---------- Page 1: Title ---------- */

    .title-page {
        gap: 4%;
    }

    .heart-deco {
        font-size: clamp(20px, 5.5vw, 26px);
        color: var(--gold);
        opacity: 0.8;
        animation: heart-pulse 3s ease-in-out infinite;
    }

    .heart-deco.small {
        font-size: clamp(16px, 4vw, 20px);
    }

    @keyframes heart-pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.7;
        }

        50% {
            transform: scale(1.15);
            opacity: 1;
        }
    }

    .eyebrow {
        font-family: var(--font-display);
        letter-spacing: 4px;
        text-transform: uppercase;
        font-size: clamp(12px, 3vw, 14px);
        color: var(--leather);
        margin: 10px 0 0;
    }

    .title-rule {
        width: 46px;
        height: 1px;
        background: var(--gold);
        margin: 6px 0 4px;
        opacity: 0.7;
    }

    .made-by {
        font-family: var(--font-body);
        font-style: italic;
        font-size: clamp(14px, 3.6vw, 17px);
        color: var(--espresso);
        opacity: 0.65;
        margin: 8px 0 0;
    }

    .name-script {
        font-family: var(--font-script);
        font-size: clamp(30px, 9vw, 42px);
        color: var(--leather);
        margin: 0;
        text-shadow: 0 1px 0 rgba(var(--white-rgb), 0.4);
    }

    /* ---------- Page 2: Large photo ---------- */

    .photo-page {
        gap: 6%;
    }

    .photo-frame {
        padding: 10px;
        background: var(--cream);
        box-shadow: 0 10px 30px rgba(var(--espresso-rgb), 0.25), 0 0 0 1px rgba(var(--leather-rgb), 0.2);
        border-radius: 4px;
    }

    .photo-frame.large {
        width: 84%;
    }

    .photo-placeholder {
        width: 100%;
        aspect-ratio: 1 / 1;
        border-radius: 2px;
        background: linear-gradient(145deg, var(--tan), var(--leather));
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: transform 0.4s ease;
        cursor: pointer;
    }

    .photo-placeholder.small {}

    .photo-placeholder.tapped {
        transform: scale(1.06);
    }

    .photo-icon {
        font-size: clamp(28px, 8vw, 40px);
        color: rgba(var(--cream-rgb), 0.55);
    }

    .caption {
        font-family: var(--font-body);
        font-style: italic;
        font-size: clamp(16px, 4.2vw, 19px);
        color: var(--espresso);
        opacity: 0.85;
    }

    /* ---------- Page 3: Memory split ---------- */

    .memory-page {
        flex-direction: row;
        gap: 6%;
    }

    .memory-photo,
    .memory-text {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .memory-photo .photo-placeholder {
        width: 100%;
        aspect-ratio: 3/4;
    }

    .handwritten {
        font-family: var(--font-script);
        font-size: clamp(20px, 6vw, 26px);
        color: var(--leather);
        line-height: 1.3;
    }

    .handwritten.big {
        font-size: clamp(24px, 6.6vw, 30px);
    }

    .doodle {
        position: absolute;
        color: var(--gold);
        opacity: 0.55;
        font-size: clamp(16px, 4.5vw, 20px);
    }

    .doodle.flower.f1 {
        top: -6%;
        left: -4%;
    }

    .doodle.heart.h1 {
        bottom: -6%;
        right: -2%;
    }

    .doodle.flower.f2 {
        bottom: -14%;
        right: 6%;
    }

    /* ---------- Page 4: Polaroids ---------- */

    .polaroid-stack {
        position: relative;
        width: 100%;
        height: 70%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .polaroid {
        position: absolute;
        width: 58%;
        background: var(--paper-white);
        padding: 8% 8% 16%;
        box-shadow: 0 12px 24px rgba(var(--espresso-rgb), 0.35);
        border-radius: 2px;
        transition: transform 0.35s var(--ease-book), box-shadow 0.35s ease;
        cursor: pointer;
    }

    .polaroid-photo {
        width: 100%;
        aspect-ratio: 1/1;
        background: linear-gradient(145deg, var(--tan), var(--leather));
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .polaroid-note {
        display: block;
        margin-top: 8%;
        font-family: var(--font-script);
        font-size: clamp(14px, 3.8vw, 17px);
        color: var(--espresso);
        text-align: center;
    }

    .polaroid.p1 {
        transform: rotate(-9deg) translate(-30%, -6%);
        z-index: 1;
    }

    .polaroid.p2 {
        transform: rotate(3deg) translate(0%, 2%);
        z-index: 2;
    }

    .polaroid.p3 {
        transform: rotate(11deg) translate(30%, -4%);
        z-index: 1;
    }

    .polaroid.p1.active {
        transform: rotate(-9deg) translate(-30%, -6%) scale(1.12);
        z-index: 5;
        box-shadow: 0 20px 36px rgba(var(--espresso-rgb), 0.45);
    }

    .polaroid.p2.active {
        transform: rotate(3deg) translate(0%, 2%) scale(1.12);
        z-index: 5;
        box-shadow: 0 20px 36px rgba(var(--espresso-rgb), 0.45);
    }

    .polaroid.p3.active {
        transform: rotate(11deg) translate(30%, -4%) scale(1.12);
        z-index: 5;
        box-shadow: 0 20px 36px rgba(var(--espresso-rgb), 0.45);
    }

    /* ---------- Page 5: Envelope / Letter ---------- */

    .envelope-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8%;
    }

    .envelope {
        position: relative;
        width: clamp(170px, 62vw, 240px);
        aspect-ratio: 3/2;
        cursor: pointer;
    }

    .envelope-back {
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, var(--sand), var(--tan));
        border-radius: 4px;
        box-shadow: 0 14px 30px rgba(var(--espresso-rgb), 0.35);
    }

    .envelope-flap {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 55%;
        background: linear-gradient(160deg, var(--tan), var(--leather));
        clip-path: polygon(0 0, 100% 0, 50% 100%);
        transform-origin: top center;
        transition: transform 0.9s var(--ease-book);
        z-index: 3;
        border-radius: 4px 4px 0 0;
    }

    .envelope-seal {
        position: absolute;
        top: 38%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 15%;
        aspect-ratio: 1/1;
        background: var(--leather);
        color: var(--gold-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(11px, 3vw, 14px);
        z-index: 4;
        box-shadow: 0 3px 6px rgba(var(--shadow-rgb), 0.3);
        transition: opacity 0.3s ease;
    }

    .envelope-letter {
        position: absolute;
        left: 6%;
        right: 6%;
        bottom: 4%;
        height: 88%;
        background:
            repeating-linear-gradient(180deg,
                transparent 0px, transparent 22px,
                rgba(var(--leather-rgb), 0.16) 23px, transparent 24px),
            var(--paper-white);
        border-radius: 2px;
        box-shadow: 0 4px 10px rgba(var(--shadow-rgb), 0.15);
        padding: 10% 8%;
        z-index: 2;
        transform: translateY(0) scale(0.94);
        transition: transform 0.9s var(--ease-book) 0.15s;
    }

    .letter-text {
        font-family: var(--font-script);
        font-size: clamp(15px, 4vw, 18px);
        color: var(--ink);
        line-height: 1.6;
        opacity: 0;
        transition: opacity 0.8s ease 0.9s;
    }

    .envelope.open .envelope-flap {
        transform: rotateX(180deg);
    }

    .envelope.open .envelope-seal {
        opacity: 0;
    }

    .envelope.open .envelope-letter {
        transform: translateY(-46%) scale(1.06);
        z-index: 5;
    }

    .envelope.open .letter-text {
        opacity: 1;
    }

    /* ---------- Page 6: Dates ---------- */

    .dates-list {
        list-style: none;
        padding: 0;
        margin: 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 6%;
    }

    .dates-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 4% 6%;
        background: rgba(var(--white-rgb), 0.4);
        border: 1px solid rgba(var(--leather-rgb), 0.25);
        border-radius: 8px;
    }

    .date-heart {
        color: var(--leather);
        font-size: clamp(14px, 3.6vw, 17px);
    }

    .date-name {
        font-family: var(--font-display);
        font-size: clamp(15px, 4vw, 18px);
        color: var(--espresso);
    }

    /* Only rendered when the client fills a date in — without it the list
   looks exactly as it did before. */
    .date-value {
        margin-left: auto;
        font-family: var(--font-body);
        font-size: clamp(13px, 3.4vw, 15px);
        color: var(--leather);
        opacity: 0.85;
        white-space: nowrap;
    }

    /* ---------- Page 7: Checklist ---------- */

    .checklist {
        list-style: none;
        padding: 0;
        margin: 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 5%;
    }

    .checklist li {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: var(--font-body);
        font-size: clamp(15px, 4vw, 18px);
        color: var(--espresso);
        opacity: 0.55;
    }

    .checklist li.checked {
        opacity: 1;
    }

    .box {
        width: clamp(18px, 5vw, 22px);
        height: clamp(18px, 5vw, 22px);
        border: 2px solid var(--leather);
        border-radius: 4px;
        position: relative;
        flex-shrink: 0;
    }

    .checklist li.checked .box::after {
        content: "✓";
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--cream);
        background: var(--leather);
        border-radius: 3px;
        font-size: 13px;
        animation: check-pop 0.4s var(--ease-book);
    }

    @keyframes check-pop {
        0% {
            transform: scale(0);
        }

        70% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    /* ---------- Page 8: Quote ---------- */

    .quote-page {
        position: relative;
        gap: 4%;
    }

    .quote-mark {
        font-family: var(--font-display);
        font-size: clamp(50px, 14vw, 70px);
        color: var(--gold);
        opacity: 0.5;
        margin: 0;
        height: 0.5em;
        line-height: 1;
    }

    .doodle.quote-f1 {
        top: 8%;
        right: 8%;
    }

    .doodle.quote-h1 {
        bottom: 14%;
        left: 10%;
    }

    .floating-heart {
        position: absolute;
        color: var(--gold);
        opacity: 0.5;
        animation: float-up 4s ease-in-out infinite;
    }

    .fh1 {
        bottom: 20%;
        left: 20%;
        font-size: 16px;
        animation-delay: 0.5s;
    }

    .fh2 {
        bottom: 15%;
        right: 18%;
        font-size: 20px;
        animation-delay: 1.2s;
    }

    @keyframes float-up {

        0%,
        100% {
            transform: translateY(0);
            opacity: 0.35;
        }

        50% {
            transform: translateY(-14px);
            opacity: 0.75;
        }
    }

    /* ---------- Page 9: Secret bookmark ---------- */

    .secret-page {
        position: relative;
        overflow: hidden;
    }

    .bookmark-track {
        position: absolute;
        top: 0;
        right: 14%;
        width: 14%;
        height: 60%;
        display: flex;
        justify-content: center;
    }

    .bookmark {
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, var(--leather), var(--tan));
        clip-path: polygon(0 0, 100% 0, 100% 88%, 50% 100%, 0 88%);
        cursor: grab;
        touch-action: none;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding-top: 10%;
        box-shadow: 0 6px 14px rgba(var(--shadow-rgb), 0.25);
        transition: transform 0.05s linear;
    }

    .bookmark.dragging {
        cursor: grabbing;
    }

    .bookmark.released {
        transition: transform 0.6s var(--ease-book);
    }

    .bookmark-heart {
        color: var(--gold-light);
        font-size: clamp(12px, 3vw, 15px);
    }

    .secret-message {
        position: absolute;
        bottom: 8%;
        left: 8%;
        right: 8%;
        text-align: center;
        opacity: 0;
        transform: translateY(14px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .secret-message.show {
        opacity: 1;
        transform: translateY(0);
    }

    .secret-message p {
        font-family: var(--font-script);
        font-size: clamp(20px, 5.6vw, 25px);
        color: var(--leather);
    }

    /* ---------- Page 10: Final ---------- */

    .final-page {
        background: transparent;
    }

    .final-page::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(120% 100% at 50% 30%, var(--espresso), var(--bg-3));
    }

    .final-text {
        position: relative;
        font-family: var(--font-display);
        font-size: clamp(20px, 5.6vw, 26px);
        color: var(--gold-light);
        text-shadow: 0 0 18px rgba(var(--gold-rgb), 0.4);
        margin: 4px 0;
        z-index: 2;
    }

    .final-text.emphasis {
        font-family: var(--font-script);
        font-size: clamp(28px, 7.5vw, 34px);
        margin-top: 8px;
    }

    .replay-btn {
        position: relative;
        z-index: 2;
        margin-top: 10%;
        padding: 3.2% 9%;
        background: transparent;
        border: 1px solid var(--gold);
        color: var(--gold-light);
        font-family: var(--font-body);
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: clamp(12px, 3.2vw, 14px);
        border-radius: 30px;
        cursor: pointer;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .replay-btn:hover,
    .replay-btn:focus-visible {
        background: var(--gold);
        color: var(--espresso);
    }

    .final-page .floating-heart {
        color: var(--gold-light);
        opacity: 0.7;
    }

    .ff1 {
        top: 18%;
        left: 15%;
        font-size: 18px;
        animation-delay: 0s;
    }

    .ff2 {
        top: 30%;
        right: 12%;
        font-size: 14px;
        animation-delay: 1s;
    }

    .ff3 {
        bottom: 28%;
        left: 22%;
        font-size: 16px;
        animation-delay: 2s;
    }

    .confetti {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 1;
    }

    .confetti span {
        position: absolute;
        top: -5%;
        width: 6px;
        height: 10px;
        opacity: 0;
        border-radius: 1px;
    }

    @keyframes confetti-fall {
        0% {
            opacity: 0;
            transform: translateY(0) rotate(0deg);
        }

        8% {
            opacity: 0.9;
        }

        100% {
            opacity: 0;
            transform: translateY(115vh) rotate(400deg);
        }
    }

    /* =========================================================
   NAVIGATION
   ========================================================= */

    .nav {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        z-index: 30;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.6s ease 0.4s, transform 0.6s ease 0.4s;
        transform: translateY(6px);
        padding-bottom: env(safe-area-inset-bottom);
    }

    .nav.open {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0);
    }

    .nav-btn {
        width: clamp(40px, 11vw, 46px);
        height: clamp(40px, 11vw, 46px);
        border-radius: 50%;
        border: 1px solid rgba(var(--gold-rgb), 0.5);
        background: rgba(var(--espresso-rgb), 0.55);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        color: var(--gold-light);
        font-size: clamp(18px, 5vw, 22px);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.25s ease, transform 0.2s ease;
    }

    .nav-btn:hover,
    .nav-btn:focus-visible {
        background: rgba(var(--gold-rgb), 0.35);
    }

    .nav-btn:active {
        transform: scale(0.9);
    }

    .nav-btn:disabled {
        opacity: 0.3;
        cursor: default;
    }

    .page-dots {
        display: flex;
        gap: 6px;
        padding: 0 4px;
    }

    .page-dots span {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: rgba(var(--gold-rgb), 0.35);
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .page-dots span.active {
        background: var(--gold-light);
        transform: scale(1.4);
    }

    /* =========================================================
   RESPONSIVE
   ========================================================= */

    /* =========================================================
   RESPONSIVE FINE-TUNING
   ========================================================= */

    @media (max-height: 640px) {
        .book {
            width: min(88vw, 340px, calc(62dvh * 0.5625));
        }

        .book-shell {
            gap: 10px;
        }
    }

    @media (min-width: 640px) and (min-height: 700px) {
        .book {
            width: min(60vh, 380px);
        }
    }

    @media (max-width: 360px) {
        .page-inner {
            padding: 9% 6%;
        }

        .cover-face.front {
            padding: 10% 7%;
        }

        .polaroid {
            width: 60%;
        }
    }

    @media (max-width: 380px) and (max-height: 680px) {
        .nav-btn {
            width: 38px;
            height: 38px;
        }

        .book-shell {
            gap: 8px;
        }
    }
    </style>
</head>

<body>

    <!-- Ambient background -->
    <div class="stage">
        <div class="glow glow-a"></div>
        <div class="glow glow-b"></div>
        <div class="dust" id="dust"></div>

        <!-- Book + Nav shell -->
        <div class="book-shell">
            <div class="book" id="book">

                <!-- ===================== CLOSED COVER ===================== -->
                <div class="cover" id="cover">
                    <div class="cover-face front">
                        <div class="cover-frame">
                            <span class="cover-ornament top">✦</span>
                            <h1 class="cover-title">Our Story</h1>
                            <p class="cover-sub">Made with Love</p>
                            <span class="cover-ornament bottom">✦</span>
                        </div>
                        <p class="tap-hint" id="tapHint">Tap to Open</p>
                    </div>
                    <div class="cover-face back"></div>
                </div>
                <div class="cover-spine"></div>

                <!-- ===================== PAGES ===================== -->
                <div class="pages" id="pages">

                    <!-- PAGE 1 — Title -->
                    <section class="page" data-index="0">
                        <div class="page-inner title-page">
                            <span class="heart-deco">❦</span>
                            <p class="eyebrow">{{ request('eyebrow', 'Our Story') }}</p>
                            <div class="title-rule"></div>
                            <p class="made-by">{{ request('made_by_label', 'Made by') }}</p>
                            <h2 class="name-script" id="fromName">{{ request('from_name', 'Emma') }}</h2>
                            <p class="made-by">{{ request('for_label', 'For') }}</p>
                            <h2 class="name-script" id="toName">{{ request('to_name', 'Lucas') }}</h2>
                            <span class="heart-deco small">❤</span>
                        </div>
                    </section>

                    <!-- PAGE 2 — Big photo -->
                    <section class="page" data-index="1">
                        <div class="page-inner photo-page">
                            <div class="photo-frame large">
                                <div class="photo-placeholder" data-photo="1">
                                    @if(request('photo1'))
                                    <img src="{{ request('photo1') }}" alt=""
                                        style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                    <span class="photo-icon">✦</span>
                                    @endif
                                </div>
                            </div>
                            <p class="caption">{{ request('caption', 'The day everything changed.') }}</p>
                        </div>
                    </section>

                    <!-- PAGE 3 — Memory split -->
                    <section class="page" data-index="2">
                        <div class="page-inner memory-page letter-style">
                            <div class="memory-photo">
                                <div class="photo-placeholder small" data-photo="2">
                                    @if(request('photo2'))
                                    <img src="{{ request('photo2') }}" alt=""
                                        style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                    <span class="photo-icon">✦</span>
                                    @endif
                                </div>
                                <span class="doodle flower f1">✿</span>
                                <span class="doodle heart h1">♡</span>
                            </div>
                            <div class="memory-text">
                                <p class="handwritten">{{ request('memory_text', '"I still remember that smile."') }}
                                </p>
                                <span class="doodle flower f2">✿</span>
                            </div>
                        </div>
                    </section>

                    <!-- PAGE 4 — Polaroids -->
                    <section class="page" data-index="3">
                        <div class="page-inner polaroid-page">
                            <p class="page-label">{{ request('polaroid_label', 'A few of my favorites') }}</p>
                            @php
                                $polaroids = [
                                    ['n' => 3, 'cls' => 'p1', 'note' => 'us :)'],
                                    ['n' => 4, 'cls' => 'p2', 'note' => 'that day'],
                                    ['n' => 5, 'cls' => 'p3', 'note' => 'forever'],
                                ];
                            @endphp
                            <div class="polaroid-stack">
                                @foreach($polaroids as $i => $p)
                                <div class="polaroid {{ $p['cls'] }}" tabindex="0" data-photo="{{ $p['n'] }}">
                                    <div class="polaroid-photo">
                                        @if(request('photo' . $p['n']))
                                        <img src="{{ request('photo' . $p['n']) }}" alt=""
                                            style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                        <span class="photo-icon">✦</span>
                                        @endif
                                    </div>
                                    <span class="polaroid-note">{{ request('note' . ($i + 1), $p['note']) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <!-- PAGE 5 — Hidden letter -->
                    <section class="page" data-index="4">
                        <div class="page-inner letter-page">
                            <p class="page-label">{{ request('letter_label', 'A letter for you') }}</p>
                            <div class="envelope-wrap" id="envelopeWrap">
                                <div class="envelope" id="envelope">
                                    <div class="envelope-back"></div>
                                    <div class="envelope-letter" id="envelopeLetter">
                                        @php
                                            $letterText = request(
                                                'letter',
                                                "Dear Love,\n\nThank you for making every ordinary day feel special.\n\nI love you."
                                            );
                                        @endphp
                                        <p class="letter-text">{!! nl2br(e($letterText)) !!}</p>
                                    </div>
                                    <div class="envelope-flap"></div>
                                    <div class="envelope-seal">❤</div>
                                </div>
                                <p class="tap-hint small" id="envelopeHint">{{ request('envelope_hint', 'Tap the envelope') }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- PAGE 6 — Special dates -->
                    <section class="page" data-index="5">
                        <div class="page-inner dates-page">
                            <p class="page-label">{{ request('dates_label', 'Special Dates') }}</p>
                            @php
                                $specialDates = ['First Meet', 'First Call', 'First Date', 'Anniversary'];
                            @endphp
                            <ul class="dates-list">
                                @foreach($specialDates as $i => $dateName)
                                @php $n = $i + 1; @endphp
                                <li>
                                    <span class="date-heart">❤</span>
                                    <span class="date-name">{{ request('date' . $n . '_name', $dateName) }}</span>
                                    @if(request('date' . $n . '_value'))
                                    <span class="date-value">{{ request('date' . $n . '_value') }}</span>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>

                    <!-- PAGE 7 — Future dreams checklist -->
                    <section class="page" data-index="6">
                        <div class="page-inner checklist-page">
                            <p class="page-label">{{ request('dreams_label', 'Future Dreams') }}</p>
                            @php
                                $dreams = [
                                    ['text' => 'First Coffee', 'done' => true],
                                    ['text' => 'First Selfie', 'done' => true],
                                    ['text' => 'First Trip', 'done' => true],
                                    ['text' => 'Endless Laughs', 'done' => true],
                                    ['text' => 'Grow Old Together', 'done' => false],
                                ];
                            @endphp
                            <ul class="checklist" id="checklist">
                                @foreach($dreams as $i => $dream)
                                @php
                                    $n = $i + 1;
                                    $isDone = request()->has('dream' . $n . '_done')
                                        ? filter_var(request('dream' . $n . '_done'), FILTER_VALIDATE_BOOLEAN)
                                        : $dream['done'];
                                @endphp
                                <li @class(['checked' => $isDone])><span class="box"></span><span>{{ request('dream' . $n, $dream['text']) }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    </section>

                    <!-- PAGE 8 — Favorite quote -->
                    <section class="page" data-index="7">
                        <div class="page-inner quote-page letter-style">
                            <span class="doodle flower quote-f1">✿</span>
                            <p class="quote-mark">“</p>
                            <p class="handwritten big" id="quoteText">{{ request('quote', 'Every love story is beautiful, but ours is my favorite.') }}
                            </p>
                            <span class="doodle heart quote-h1">♡</span>
                            <span class="floating-heart fh1">♡</span>
                            <span class="floating-heart fh2">❤</span>
                        </div>
                    </section>

                    <!-- PAGE 9 — Secret bookmark -->
                    <section class="page" data-index="8">
                        <div class="page-inner secret-page">
                            <p class="page-label">{{ request('secret_label', 'Pull the ribbon down') }}</p>
                            <div class="bookmark-track">
                                <div class="bookmark" id="bookmark">
                                    <span class="bookmark-heart">❤</span>
                                </div>
                            </div>
                            <div class="secret-message" id="secretMessage">
                                <p>{{ request('secret_message', 'You found the secret page ❤') }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- PAGE 10 — Final page -->
                    <section class="page" data-index="9">
                        <div class="page-inner final-page">
                            <div class="confetti" id="confetti"></div>
                            <p class="final-text">{{ request('final_line1', "This isn't the end...") }}</p>
                            <p class="final-text emphasis">{{ request('final_line2', "It's just the beginning.") }}</p>
                            <span class="floating-heart ff1">❤</span>
                            <span class="floating-heart ff2">♡</span>
                            <span class="floating-heart ff3">❤</span>
                            <button class="replay-btn" id="replayBtn">{{ request('replay_label', 'Replay Story') }}</button>
                        </div>
                    </section>

                </div>

                <div class="book-shadow"></div>
            </div>

            <!-- Nav controls -->
            <div class="nav" id="nav">
                <button class="nav-btn prev" id="prevBtn" aria-label="Previous page">‹</button>
                <div class="page-dots" id="pageDots"></div>
                <button class="nav-btn next" id="nextBtn" aria-label="Next page">›</button>
            </div>

        </div>
    </div>

    <script>
    /* =========================================================
   OUR STORY — Interactions
   ========================================================= */

    (function() {
        'use strict';

        const book = document.getElementById('book');
        const cover = document.getElementById('cover');
        const nav = document.getElementById('nav');
        const pagesWrap = document.getElementById('pages');
        const pages = Array.from(document.querySelectorAll('.page'));
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const pageDotsWrap = document.getElementById('pageDots');
        const dustLayer = document.getElementById('dust');

        let currentIndex = 0;
        const lastIndex = pages.length - 1;

        /* ---------------- Add realistic flip-lighting overlay ---------------- */
        pages.forEach((p) => {
            const shade = document.createElement('div');
            shade.className = 'page-shade';
            p.appendChild(shade);
        });

        /* ---------------- Build page dots ---------------- */
        pages.forEach((_, i) => {
            const dot = document.createElement('span');
            if (i === 0) dot.classList.add('active');
            pageDotsWrap.appendChild(dot);
        });
        const dots = Array.from(pageDotsWrap.children);

        function updateDots() {
            dots.forEach((d, i) => d.classList.toggle('active', i === currentIndex));
        }

        function updateNavButtons() {
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex === lastIndex;
        }

        /* ---------------- Page turning ---------------- */
        function goTo(newIndex) {
            if (newIndex < 0 || newIndex > lastIndex || newIndex === currentIndex) return;
            const direction = newIndex > currentIndex ? 'next' : 'prev';
            const oldPage = pages[currentIndex];
            const newPage = pages[newIndex];

            oldPage.classList.remove('current');
            newPage.classList.remove('gone-prev', 'gone-next', 'current');

            if (direction === 'next') {
                oldPage.classList.add('gone-prev');
                newPage.style.zIndex = 10;
                oldPage.style.zIndex = 15;
            } else {
                oldPage.classList.add('gone-next');
                newPage.style.zIndex = 20;
                oldPage.style.zIndex = 15;
            }

            requestAnimationFrame(() => {
                newPage.classList.add('current');
            });

            currentIndex = newIndex;
            updateDots();
            updateNavButtons();

            if (currentIndex === lastIndex) {
                launchConfetti();
            }
        }

        prevBtn.addEventListener('click', () => goTo(currentIndex - 1));
        nextBtn.addEventListener('click', () => goTo(currentIndex + 1));

        /* ---------------- Swipe support ---------------- */
        let touchStartX = 0;
        let touchStartY = 0;

        pagesWrap.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }, {
            passive: true
        });

        pagesWrap.addEventListener('touchend', (e) => {
            const dx = e.changedTouches[0].clientX - touchStartX;
            const dy = e.changedTouches[0].clientY - touchStartY;
            if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
                if (dx < 0) goTo(currentIndex + 1);
                else goTo(currentIndex - 1);
            }
        }, {
            passive: true
        });

        /* ---------------- Cover open ---------------- */
        cover.addEventListener('click', () => {
            if (book.classList.contains('open')) return;
            book.classList.add('open');
            nav.classList.add('open');
        });

        /* ---------------- Dust particles ---------------- */
        function spawnDust() {
            const count = 22;
            for (let i = 0; i < count; i++) {
                const s = document.createElement('span');
                s.style.left = Math.random() * 100 + '%';
                s.style.bottom = '-10px';
                const duration = 8 + Math.random() * 10;
                const delay = Math.random() * 12;
                s.style.animationDuration = duration + 's';
                s.style.animationDelay = delay + 's';
                s.style.opacity = '0';
                dustLayer.appendChild(s);
            }
        }
        spawnDust();

        /* ---------------- Polaroids (Page 4) ---------------- */
        const polaroids = document.querySelectorAll('.polaroid');
        polaroids.forEach((p) => {
            p.addEventListener('click', () => {
                const wasActive = p.classList.contains('active');
                polaroids.forEach((o) => o.classList.remove('active'));
                if (!wasActive) p.classList.add('active');
            });
        });

        /* ---------------- Envelope (Page 5) ---------------- */
        const envelope = document.getElementById('envelope');
        const envelopeHint = document.getElementById('envelopeHint');
        envelope.addEventListener('click', () => {
            const isOpen = envelope.classList.toggle('open');
            envelopeHint.style.opacity = isOpen ? '0' : '0.75';
        });

        /* ---------------- Photo tap zoom ---------------- */
        document.querySelectorAll('.photo-placeholder').forEach((ph) => {
            ph.addEventListener('click', () => {
                ph.classList.toggle('tapped');
            });
        });

        /* ---------------- Bookmark drag (Page 9) ---------------- */
        const bookmark = document.getElementById('bookmark');
        const bookmarkTrack = bookmark.parentElement;
        const secretMessage = document.getElementById('secretMessage');
        let dragging = false;
        let startY = 0;
        let currentY = 0;
        let maxDrag = 0;

        function trackHeight() {
            return bookmarkTrack.clientHeight - bookmark.clientHeight;
        }

        function onDragStart(clientY) {
            dragging = true;
            startY = clientY - currentY;
            maxDrag = trackHeight();
            bookmark.classList.add('dragging');
            bookmark.classList.remove('released');
        }

        function onDragMove(clientY) {
            if (!dragging) return;
            let y = clientY - startY;
            y = Math.max(0, Math.min(maxDrag, y));
            currentY = y;
            bookmark.style.transform = `translateY(${y}px)`;
            const progress = maxDrag > 0 ? y / maxDrag : 0;
            if (progress > 0.7) {
                secretMessage.classList.add('show');
            } else {
                secretMessage.classList.remove('show');
            }
        }

        function onDragEnd() {
            if (!dragging) return;
            dragging = false;
            bookmark.classList.remove('dragging');
            bookmark.classList.add('released');
            const progress = maxDrag > 0 ? currentY / maxDrag : 0;
            if (progress > 0.7) {
                currentY = maxDrag;
                bookmark.style.transform = `translateY(${maxDrag}px)`;
                secretMessage.classList.add('show');
            } else {
                currentY = 0;
                bookmark.style.transform = 'translateY(0px)';
                secretMessage.classList.remove('show');
            }
        }

        bookmark.addEventListener('mousedown', (e) => onDragStart(e.clientY));
        window.addEventListener('mousemove', (e) => onDragMove(e.clientY));
        window.addEventListener('mouseup', onDragEnd);

        bookmark.addEventListener('touchstart', (e) => onDragStart(e.touches[0].clientY), {
            passive: true
        });
        bookmark.addEventListener('touchmove', (e) => onDragMove(e.touches[0].clientY), {
            passive: true
        });
        bookmark.addEventListener('touchend', onDragEnd);

        /* ---------------- Confetti (Page 10) ---------------- */
        const confettiLayer = document.getElementById('confetti');
        const confettiColors = ['#C9A24B', '#E8CE8B', '#D2B48C', '#F7F2EA'];
        let confettiLaunched = false;

        function launchConfetti() {
            if (confettiLaunched) return;
            confettiLaunched = true;
            const count = 34;
            for (let i = 0; i < count; i++) {
                const s = document.createElement('span');
                s.style.left = Math.random() * 100 + '%';
                s.style.background = confettiColors[Math.floor(Math.random() * confettiColors.length)];
                const duration = 3 + Math.random() * 2.5;
                const delay = Math.random() * 1.5;
                s.style.animation = `confetti-fall ${duration}s ease-in ${delay}s forwards`;
                confettiLayer.appendChild(s);
            }
        }

        /* ---------------- Replay ---------------- */
        const replayBtn = document.getElementById('replayBtn');
        replayBtn.addEventListener('click', () => {
            pages.forEach((p, i) => {
                p.style.transition = 'none';
                p.classList.remove('current', 'gone-prev', 'gone-next');
                p.style.zIndex = '';
                if (i === 0) p.classList.add('current');
            });
            // force reflow then restore transitions
            void pagesWrap.offsetWidth;
            pages.forEach((p) => {
                p.style.transition = '';
            });

            currentIndex = 0;
            updateDots();
            updateNavButtons();

            // reset envelope, polaroids, bookmark, confetti
            envelope.classList.remove('open');
            envelopeHint.style.opacity = '0.75';
            polaroids.forEach((p) => p.classList.remove('active'));
            bookmark.style.transform = 'translateY(0px)';
            bookmark.classList.remove('released');
            currentY = 0;
            secretMessage.classList.remove('show');
            confettiLayer.innerHTML = '';
            confettiLaunched = false;
        });

        /* ---------------- Initial page state ---------------- */
        // The pages are absolutely stacked and only `.current` carries a
        // z-index, so without this the last page in the DOM paints on top
        // until the first flip.
        pages[0].classList.add('current');
        pages[0].style.zIndex = 10;

        /* ---------------- Dashboard live-preview deep link ---------------- */
        // ?preview_page=N opens the book straight to book page N, so the
        // dashboard preview shows the page whose fields are being edited.
        const previewPageParam = new URLSearchParams(window.location.search).get('preview_page');
        if (previewPageParam !== null) {
            const target = Math.min(lastIndex, Math.max(0, (parseInt(previewPageParam, 10) || 1) - 1));
            book.classList.add('open');
            nav.classList.add('open');
            pages.forEach((p, i) => {
                p.style.transition = 'none';
                p.classList.remove('current', 'gone-prev', 'gone-next');
                p.style.zIndex = '';
                if (i < target) {
                    p.classList.add('gone-prev');
                    p.style.zIndex = 15;
                } else if (i === target) {
                    p.classList.add('current');
                    p.style.zIndex = 10;
                }
            });
            void pagesWrap.offsetWidth; // flush, then let transitions run again
            pages.forEach((p) => {
                p.style.transition = '';
            });
            currentIndex = target;
            updateDots();
            updateNavButtons();
            if (currentIndex === lastIndex) launchConfetti();
        }

        /* ---------------- Init ---------------- */
        updateNavButtons();
    })();
    </script>
</body>

</html>