<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anniversary Card - Page 3 - Bright Red &amp; White</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    /* ================= THEME TOKENS — only this block changes per colour variant ================= */
    :root {
        --wrap-bg: radial-gradient(circle at 30% 20%, #e3bcab 0%, #cf9880 55%, #c4917c 100%);
        --tray-bg: linear-gradient(160deg, #f5efe8 0%, #e9ddd3 100%);
        --tray-border: #e8281a;
        --box-bg: linear-gradient(135deg, #ff3b28 0%, #e8281a 55%, #c01d12 100%);
        --lid-bg: linear-gradient(135deg, #ff4835 0%, #ef2c1d 55%, #cc2015 100%);
        --ribbon-v: linear-gradient(90deg, #cfc7bd 0%, #f5efe8 25%, #ffffff 50%, #f5efe8 75%, #cfc7bd 100%);
        --ribbon-h: linear-gradient(180deg, #cfc7bd 0%, #f5efe8 25%, #ffffff 50%, #f5efe8 75%, #cfc7bd 100%);
        --bow-loop: linear-gradient(135deg, #ffffff, #f5efe8 45%, #ddd3c7 80%, #c3b8a9 100%);
        --bow-tail: linear-gradient(180deg, #f5efe8, #cfc4b5);
        --bow-knot: radial-gradient(circle at 35% 30%, #ffffff, #f0e9de 60%, #cfc4b5 100%);
        --hint: rgba(92, 23, 18, 0.55);
        --ink: #5c1712;
        --accent: #e8281a;
        --accent-line: rgba(232, 40, 26, 0.5);
        --accent-wash: rgba(232, 40, 26, 0.12);
        --load-screen-bg: radial-gradient(circle at top, rgba(232, 40, 26, 0.22), transparent 40%), linear-gradient(160deg, rgba(227, 188, 171, 0.97), rgba(196, 145, 124, 0.96));
        --load-card-bg: linear-gradient(180deg, rgba(245, 239, 232, 0.97), rgba(233, 221, 211, 0.97));
        --load-card-ink: #5c1712;
        --load-card-border: rgba(232, 40, 26, 0.3);
        --load-glow: rgba(232, 40, 26, 0.18);
        --load-lid: linear-gradient(180deg, #ff4835, #e8281a);
        --load-lid-border: #ef2c1d;
        --load-box: linear-gradient(180deg, #f5efe8, #e2d6ca);
        --load-ribbon: rgba(232, 40, 26, 0.85);
        --load-text: rgba(92, 23, 18, 0.78);
        --load-dot: #e8281a;
        --confetti-1: #e8281a;
        --confetti-2: #ffffff;
        --confetti-3: #f5efe8;
        --confetti-4: #c01d12;
    }

    body {
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: Arial, sans-serif;
    }

    /* ================= DESKTOP / LAPTOP (image based, 1024px and up) ================= */

    .gift-image {
        width: 100vw;
        height: 100vh;
        object-fit: cover;
        display: block;
    }

    .gift-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
    }

    .gift-area {
        position: absolute;
        cursor: pointer;
        border: 0;
        background: transparent;
        outline: none;
    }

    .gift-area:hover,
    .gift-area:focus,
    .gift-area:active {
        background: transparent;
        border-color: transparent;
        outline: none;
    }

    .gift-area.show-borders {
        border: 2px dashed rgba(128, 204, 255, 0.9);
        background: rgba(58, 127, 255, 0.18);
    }

    /* ================= MOBILE / TABLET (pure CSS gift boxes, below 1024px) ================= */

    .mobile-gifts-wrap {
        display: none;
        width: 100vw;
        height: 100vh;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: var(--wrap-bg);
    }

    .mobile-gifts-card {
        width: 100%;
        max-width: 640px;
        aspect-ratio: 3 / 2;
        border-radius: 18px;
        background: var(--tray-bg);
        border: 10px solid var(--tray-border);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        padding: 20px;
    }

    .mobile-gift-box {
        position: relative;
        width: min(26vw, 130px);
        height: min(26vw, 130px);
        cursor: pointer;
        background: transparent;
        border: 0;
        outline: none;
        -webkit-tap-highlight-color: transparent;
    }

    .mobile-gift-box:focus {
        outline: none;
    }

    .mgb-inner {
        position: absolute;
        inset: 14% 0 0 0;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        transition: transform 0.15s ease;
    }

    .mobile-gift-box:active .mgb-inner {
        transform: scale(0.94);
    }

    /* box body */
    .mgb-box {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 78%;
        border-radius: 4px 4px 6px 6px;
        background: var(--box-bg);
        box-shadow:
            0 10px 22px rgba(0, 0, 0, 0.45),
            inset 0 0 0 1px rgba(255, 255, 255, 0.04);
    }

    /* lid */
    .mgb-lid {
        position: absolute;
        left: -4%;
        right: -4%;
        top: 0;
        height: 20%;
        border-radius: 5px;
        background: var(--lid-bg);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.4);
    }

    /* vertical ribbon */
    .mgb-box::before,
    .mgb-lid::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 22%;
        transform: translateX(-50%);
        background: var(--ribbon-v);
    }

    /* horizontal band across the box */
    .mgb-box::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: 14%;
        height: 18%;
        background: var(--ribbon-h);
    }

    /* bow */
    .mgb-bow {
        position: absolute;
        top: -14%;
        left: 50%;
        transform: translateX(-50%);
        width: 46%;
        height: 34%;
        z-index: 3;
    }

    .mgb-bow-loop {
        position: absolute;
        top: 0;
        width: 55%;
        height: 100%;
        background: var(--bow-loop);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.35);
    }

    .mgb-bow-loop.left {
        left: -6%;
        border-radius: 100% 10% 60% 100% / 100% 10% 100% 60%;
        transform: rotate(-18deg);
    }

    .mgb-bow-loop.right {
        right: -6%;
        border-radius: 10% 100% 100% 60% / 10% 100% 60% 100%;
        transform: rotate(18deg);
    }

    .mgb-bow-tail {
        position: absolute;
        bottom: -30%;
        width: 16%;
        height: 42%;
        background: var(--bow-tail);
    }

    .mgb-bow-tail.l {
        left: 24%;
        transform: rotate(10deg);
        clip-path: polygon(0 0, 100% 0, 70% 100%, 0% 80%);
    }

    .mgb-bow-tail.r {
        right: 24%;
        transform: rotate(-10deg);
        clip-path: polygon(0 0, 100% 0, 100% 80%, 30% 100%);
    }

    .mgb-bow-knot {
        position: absolute;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 22%;
        height: 40%;
        border-radius: 40%;
        background: var(--bow-knot);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
        z-index: 4;
    }

    .mobile-gifts-hint {
        position: absolute;
        bottom: 6%;
        left: 0;
        right: 0;
        text-align: center;
        color: var(--hint);
        font-size: 12px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: Arial, sans-serif;
    }

    /* ================= NEXT BUTTON (static) ================= */

    .next-btn {
        position: fixed;
        left: 50%;
        bottom: clamp(20px, 5vh, 40px);
        transform: translateX(-50%);
        z-index: 15;
        background: transparent;
        color: var(--ink);
        border: 1px solid var(--accent-line);
        padding: 12px 40px;
        border-radius: 60px;
        font-family: Arial, sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s;
    }

    .next-btn:hover {
        background: var(--accent-wash);
        border-color: var(--accent);
        transform: translateX(-50%) translateY(-2px);
    }

    .next-btn::after {
        content: ' \2192';
        opacity: 0.5;
    }

    @media (max-width: 767px) {
        .mobile-gifts-card {
            aspect-ratio: auto;
            width: min(88vw, 300px);
            flex-direction: column;
            gap: 18px;
            padding: 24px 16px;
        }

        .mobile-gift-box {
            width: min(38vw, 150px);
            height: min(38vw, 150px);
        }
    }

    @media (max-width: 480px) {
        .mobile-gifts-wrap {
            padding: 14px;
        }

        .mobile-gifts-card {
            border-width: 7px;
            padding: 20px 14px;
            gap: 14px;
        }

        .mobile-gift-box {
            width: min(34vw, 120px);
            height: min(34vw, 120px);
        }
    }

    /* ================= BREAKPOINT SWITCH ================= */

    @media (max-width: 1023px) {

        .gift-container,
        .gift-overlay {
            display: none !important;
        }

        .mobile-gifts-wrap {
            display: flex;
        }
    }

    /* ================= LOADING SCREEN (shared) ================= */

    .loading-screen {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: var(--load-screen-bg);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.35s ease, visibility 0.35s ease;
        z-index: 20;
    }

    .loading-screen.is-visible {
        opacity: 1;
        visibility: visible;
        pointer-events: all;
    }

    .loading-card {
        width: min(88vw, 360px);
        padding: 28px 26px;
        border-radius: 28px;
        text-align: center;
        color: var(--load-card-ink);
        background: var(--load-card-bg);
        border: 1px solid var(--load-card-border);
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.55);
        overflow: hidden;
    }

    .loading-card::before,
    .loading-card::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, var(--load-glow), transparent 68%);
        pointer-events: none;
    }

    .loading-card::before {
        top: -45px;
        left: -30px;
    }

    .loading-card::after {
        right: -35px;
        bottom: -50px;
    }

    .loading-gift {
        position: relative;
        width: 104px;
        height: 112px;
        margin: 0 auto 20px;
        animation: floatGift 1.8s ease-in-out infinite;
    }

    .loading-gift-lid {
        position: absolute;
        top: 12px;
        left: 12px;
        width: 80px;
        height: 24px;
        border-radius: 14px;
        background: var(--load-lid);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.3);
        transform-origin: bottom center;
        animation: lidBounce 1.25s ease-in-out infinite;
    }

    .loading-gift-lid::before,
    .loading-gift-lid::after {
        content: '';
        position: absolute;
        top: -12px;
        width: 24px;
        height: 20px;
        border: 4px solid var(--load-lid-border);
        border-bottom: 0;
        border-radius: 16px 16px 0 0;
    }

    .loading-gift-lid::before {
        left: 18px;
        transform: rotate(-14deg);
    }

    .loading-gift-lid::after {
        right: 18px;
        transform: rotate(14deg);
    }

    .loading-gift-box {
        position: absolute;
        left: 14px;
        bottom: 0;
        width: 76px;
        height: 68px;
        border-radius: 18px;
        background: var(--load-box);
        box-shadow: 0 18px 30px rgba(0, 0, 0, 0.5);
        overflow: hidden;
    }

    .loading-gift-box::before,
    .loading-gift-box::after {
        content: '';
        position: absolute;
        background: var(--load-ribbon);
    }

    .loading-gift-box::before {
        top: 0;
        bottom: 0;
        left: 50%;
        width: 10px;
        transform: translateX(-50%);
    }

    .loading-gift-box::after {
        left: 0;
        right: 0;
        top: 26px;
        height: 10px;
    }

    .loading-confetti span {
        position: absolute;
        top: -6px;
        width: 10px;
        height: 18px;
        border-radius: 999px;
        opacity: 0;
        animation: confettiDrop 1.5s linear infinite;
    }

    .loading-confetti span:nth-child(1) {
        left: 8px;
        background: var(--confetti-1);
        animation-delay: 0.1s;
    }

    .loading-confetti span:nth-child(2) {
        left: 28px;
        background: var(--confetti-2);
        animation-delay: 0.35s;
    }

    .loading-confetti span:nth-child(3) {
        right: 28px;
        background: var(--confetti-3);
        animation-delay: 0.2s;
    }

    .loading-confetti span:nth-child(4) {
        right: 10px;
        background: var(--confetti-4);
        animation-delay: 0.55s;
    }

    .loading-title {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 0.04em;
        margin-bottom: 10px;
    }

    .loading-text {
        font-size: 15px;
        line-height: 1.5;
        color: var(--load-text);
    }

    .loading-dots {
        display: inline-flex;
        gap: 6px;
        margin-top: 18px;
    }

    .loading-dots span {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        background: var(--load-dot);
        animation: pulseDots 1.2s infinite ease-in-out;
    }

    .loading-dots span:nth-child(2) {
        animation-delay: 0.15s;
    }

    .loading-dots span:nth-child(3) {
        animation-delay: 0.3s;
    }

    @keyframes floatGift {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }

    @keyframes lidBounce {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        40% {
            transform: translateY(-4px) rotate(-6deg);
        }

        70% {
            transform: translateY(-2px) rotate(4deg);
        }
    }

    @keyframes confettiDrop {
        0% {
            transform: translateY(0) scale(0.8) rotate(0deg);
            opacity: 0;
        }

        20% {
            opacity: 1;
        }

        100% {
            transform: translateY(88px) scale(1) rotate(22deg);
            opacity: 0;
        }
    }

    @keyframes pulseDots {

        0%,
        80%,
        100% {
            transform: scale(0.75);
            opacity: 0.45;
        }

        40% {
            transform: scale(1);
            opacity: 1;
        }
    }
    </style>
</head>

<body>

    <!-- ============ DESKTOP / LAPTOP (1024px and up) ============ -->
    <div class="gift-container" style="position: relative;">
        <img src="/images/giftbox/anivar4.png" alt="Anniversary Gift" class="gift-image">

        <!-- Clickable gift areas -->
        <div class="gift-overlay">
            <!-- Gift 1 - Left -->
            <div class="gift-area" id="gift1" style="top: 30%; left: 9%; width: 25%; height: 40%;"
                onclick="openGiftPage(1)">
            </div>

            <!-- Gift 2 - Centre -->
            <div class="gift-area" id="gift2" style="top: 30%; left: 38%; width: 25%; height: 40%;"
                onclick="openGiftPage(2)">
            </div>

            <!-- Gift 3 - Right -->
            <div class="gift-area" id="gift3" style="top: 30%; left: 64%; width: 26%; height: 40%;"
                onclick="openGiftPage(3)">
            </div>
        </div>
    </div>

    <!-- ============ MOBILE / TABLET (below 1024px) - pure CSS gift boxes ============ -->
    <div class="mobile-gifts-wrap">
        <div class="mobile-gifts-card">

            <button class="mobile-gift-box" type="button" onclick="openGiftPage(1)" aria-label="Open gift 1">
                <div class="mgb-inner">
                    <div class="mgb-lid"></div>
                    <div class="mgb-box"></div>
                    <div class="mgb-bow">
                        <div class="mgb-bow-tail l"></div>
                        <div class="mgb-bow-tail r"></div>
                        <div class="mgb-bow-loop left"></div>
                        <div class="mgb-bow-loop right"></div>
                        <div class="mgb-bow-knot"></div>
                    </div>
                </div>
            </button>

            <button class="mobile-gift-box" type="button" onclick="openGiftPage(2)" aria-label="Open gift 2">
                <div class="mgb-inner">
                    <div class="mgb-lid"></div>
                    <div class="mgb-box"></div>
                    <div class="mgb-bow">
                        <div class="mgb-bow-tail l"></div>
                        <div class="mgb-bow-tail r"></div>
                        <div class="mgb-bow-loop left"></div>
                        <div class="mgb-bow-loop right"></div>
                        <div class="mgb-bow-knot"></div>
                    </div>
                </div>
            </button>

            <button class="mobile-gift-box" type="button" onclick="openGiftPage(3)" aria-label="Open gift 3">
                <div class="mgb-inner">
                    <div class="mgb-lid"></div>
                    <div class="mgb-box"></div>
                    <div class="mgb-bow">
                        <div class="mgb-bow-tail l"></div>
                        <div class="mgb-bow-tail r"></div>
                        <div class="mgb-bow-loop left"></div>
                        <div class="mgb-bow-loop right"></div>
                        <div class="mgb-bow-knot"></div>
                    </div>
                </div>
            </button>

        </div>
    </div>

    <!-- Static next button -->
    <button class="next-btn" type="button">NEXT</button>

    <div class="loading-screen" id="loadingScreen" aria-hidden="true">
        <div class="loading-card">
            <div class="loading-gift">
                <div class="loading-confetti" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="loading-gift-lid"></div>
                <div class="loading-gift-box"></div>
            </div>
            <div class="loading-title">Big Surprise</div>
            <div class="loading-text">Special gift open ho raha hai. Bas ek second mein surprise reveal hone wala hai.
            </div>
            <div class="loading-dots" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <script>
    let isOpeningGift = false;

    function openGiftPage(pageNumber) {
        if (isOpeningGift) {
            return;
        }

        isOpeningGift = true;

        const loadingScreen = document.getElementById('loadingScreen');
        loadingScreen.classList.add('is-visible');
        loadingScreen.setAttribute('aria-hidden', 'false');

        setTimeout(function() {
            window.location.href = '/anniversary/gift-1/' + pageNumber;
        }, 900);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('debug') === 'true') {
            document.querySelectorAll('.gift-area').forEach(area => {
                area.classList.add('show-borders');
            });
        }
    });
    </script>
</body>

</html>
