<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Girl Theme - Page 3</title>
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
        border: 2px dashed rgba(255, 160, 206, 0.9);
        background: rgba(255, 120, 186, 0.18);
    }

    /* ================= MOBILE / TABLET (pure CSS lavender-pink gift boxes, below 1024px) ================= */

    .mobile-gifts-wrap {
        display: none;
        width: 100vw;
        height: 100vh;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: radial-gradient(circle at 30% 20%, #e0538f 0%, #c93d78 55%, #a12a5f 100%);
    }

    .mobile-gifts-card {
        width: 100%;
        max-width: 640px;
        aspect-ratio: 3 / 2;
        border-radius: 18px;
        background: linear-gradient(160deg, #d94a85 0%, #c33d75 100%);
        border: 10px solid #2c1a26;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
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
        background: linear-gradient(135deg, #cdd8fb 0%, #a9bdf5 55%, #8aa3ec 100%);
        box-shadow:
            0 10px 22px rgba(0, 0, 0, 0.35),
            inset 0 0 0 1px rgba(255, 255, 255, 0.35);
    }

    /* lid */
    .mgb-lid {
        position: absolute;
        left: -4%;
        right: -4%;
        top: 0;
        height: 20%;
        border-radius: 5px;
        background: linear-gradient(135deg, #d6e0fc 0%, #b3c5f7 55%, #93aaef 100%);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.28);
    }

    /* vertical pink ribbon */
    .mgb-box::before,
    .mgb-lid::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 22%;
        transform: translateX(-50%);
        background: linear-gradient(90deg, #d84e93 0%, #ff9dc7 22%, #ffd6eb 50%, #ff9dc7 78%, #d84e93 100%);
    }

    /* horizontal pink band across the box */
    .mgb-box::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: 14%;
        height: 18%;
        background: linear-gradient(180deg, #d84e93 0%, #ff9dc7 30%, #ffd6eb 50%, #ff9dc7 70%, #d84e93 100%);
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
        background: linear-gradient(135deg, #ffd6eb, #ff9dc7 45%, #e35ba0 80%, #d84e93 100%);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.25);
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
        background: linear-gradient(180deg, #ff9dc7, #c94b8d);
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
        background: radial-gradient(circle at 35% 30%, #ffd6eb, #f078b3 60%, #d84e93 100%);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        z-index: 4;
    }

    .mobile-gifts-hint {
        position: absolute;
        bottom: 6%;
        left: 0;
        right: 0;
        text-align: center;
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: Arial, sans-serif;
    }

    /* mobile: stack vertically */
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
        background:
            radial-gradient(circle at top, rgba(255, 255, 255, 0.24), transparent 35%),
            linear-gradient(160deg, rgba(255, 192, 214, 0.96), rgba(255, 123, 172, 0.95));
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
        color: #7a2144;
        background: linear-gradient(180deg, rgba(255, 247, 250, 0.96), rgba(255, 225, 237, 0.96));
        border: 1px solid rgba(255, 255, 255, 0.75);
        box-shadow: 0 24px 70px rgba(162, 37, 99, 0.24);
    }

    .loading-sparkle {
        position: relative;
        width: 96px;
        height: 96px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.95), rgba(255, 202, 228, 0.8));
        box-shadow: 0 0 0 10px rgba(255, 255, 255, 0.18);
        animation: bloomGlow 1.4s ease-in-out infinite;
    }

    .loading-sparkle::before,
    .loading-sparkle::after {
        content: '✦';
        position: absolute;
        color: #ff5ea3;
        animation: twinkle 1.6s ease-in-out infinite;
    }

    .loading-sparkle::before {
        top: 8px;
        right: 10px;
        font-size: 20px;
    }

    .loading-sparkle::after {
        bottom: 10px;
        left: 10px;
        font-size: 16px;
        animation-delay: 0.35s;
    }

    .loading-heart {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        font-size: 32px;
        animation: floatHeart 1.3s ease-in-out infinite;
    }

    .loading-title {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 0.03em;
        margin-bottom: 10px;
    }

    .loading-text {
        font-size: 15px;
        line-height: 1.5;
        color: rgba(122, 33, 68, 0.82);
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
        background: #ff5ea3;
        animation: pulseDots 1.2s infinite ease-in-out;
    }

    .loading-dots span:nth-child(2) {
        animation-delay: 0.15s;
    }

    .loading-dots span:nth-child(3) {
        animation-delay: 0.3s;
    }

    @keyframes bloomGlow {

        0%,
        100% {
            transform: scale(0.96);
            box-shadow: 0 0 0 10px rgba(255, 255, 255, 0.18);
        }

        50% {
            transform: scale(1.04);
            box-shadow: 0 0 0 16px rgba(255, 255, 255, 0.26);
        }
    }

    @keyframes twinkle {

        0%,
        100% {
            transform: scale(0.8) rotate(0deg);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.12) rotate(12deg);
            opacity: 1;
        }
    }

    @keyframes floatHeart {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-7px);
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
        <img src="/images/giftbox/4.png" alt="Birthday Gift" class="gift-image" usemap="#giftmap">

        <!-- Clickable gift areas -->
        <div class="gift-overlay">
            <!-- Gift 1 - Top Left -->
            <div class="gift-area" id="gift1" style="top: 25%; left: 13%; width: 20%; height: 40%;"
                onclick="openGiftPage(1)">
            </div>

            <!-- Gift 2 - Top Right -->
            <div class="gift-area" id="gift2" style="top: 25%; right: 13%; width: 20%; height: 40%;"
                onclick="openGiftPage(2)">
            </div>

            <!-- Gift 3 - Bottom Center -->
            <div class="gift-area" id="gift3" style="bottom: 35%; left: 40%; width: 20%; height: 40%;"
                onclick="openGiftPage(3)">
            </div>
        </div>
    </div>

    <!-- ============ MOBILE / TABLET (below 1024px) - pure CSS lavender-pink gift boxes ============ -->
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

    <div class="loading-screen" id="loadingScreen" aria-hidden="true">
        <div class="loading-card">
            <div class="loading-sparkle">
                <div class="loading-heart">🎀</div>
            </div>
            <div class="loading-title">Sweet Surprise</div>
            <div class="loading-text">Gift khul raha hai. Cute sa surprise bas abhi samne aane wala hai.</div>
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
            window.location.href = '/girl/gift-1/' + pageNumber;
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