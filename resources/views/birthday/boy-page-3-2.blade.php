<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Boy Theme - Page 3 Variant 2</title>
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

    .loading-screen {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background:
            radial-gradient(circle at top, rgba(92, 135, 255, 0.35), transparent 40%),
            linear-gradient(160deg, rgba(4, 11, 37, 0.98), rgba(2, 41, 95, 0.94));
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
        color: #f6fbff;
        background: linear-gradient(180deg, rgba(18, 37, 71, 0.96), rgba(9, 20, 48, 0.96));
        border: 1px solid rgba(145, 219, 255, 0.22);
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.42);
        overflow: hidden;
    }

    .loading-card::before,
    .loading-card::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(125, 211, 252, 0.22), transparent 68%);
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
        background: linear-gradient(180deg, #8de0ff, #53b5ff);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18);
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
        border: 4px solid #d8f4ff;
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
        background: linear-gradient(180deg, #2563eb, #1d4ed8);
        box-shadow: 0 18px 30px rgba(8, 15, 35, 0.28);
        overflow: hidden;
    }

    .loading-gift-box::before,
    .loading-gift-box::after {
        content: '';
        position: absolute;
        background: rgba(232, 248, 255, 0.92);
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
        background: #7dd3fc;
        animation-delay: 0.1s;
    }

    .loading-confetti span:nth-child(2) {
        left: 28px;
        background: #fde68a;
        animation-delay: 0.35s;
    }

    .loading-confetti span:nth-child(3) {
        right: 28px;
        background: #93c5fd;
        animation-delay: 0.2s;
    }

    .loading-confetti span:nth-child(4) {
        right: 10px;
        background: #bfdbfe;
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
        color: rgba(236, 247, 255, 0.82);
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
        background: #7dd3fc;
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
    <div class="gift-container" style="position: relative;">
        <img src="/images/giftbox/1.png" alt="Birthday Gift" class="gift-image" usemap="#giftmap">

        <!-- Clickable gift areas -->
        <div class="gift-overlay">
            <!-- Gift 1 - Top Left -->
            <div class="gift-area" id="gift1" style="top: 33%; left: 13%; width: 20%; height: 35%;"
                onclick="openGiftPage(1)">
            </div>

            <!-- Gift 2 - Top Right -->
            <div class="gift-area" id="gift2" style="top: 33%; right: 11%; width: 20%; height: 35%;"
                onclick="openGiftPage(2)">
            </div>

            <!-- Gift 3 - Bottom Center -->
            <div class="gift-area" id="gift3" style="bottom: 33%; left: 41%; width: 20%; height: 35%;"
                onclick="openGiftPage(3)">
            </div>
        </div>
    </div>

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
            <div class="loading-text">Special gift open ho raha hai. Bas ek second mein surprise reveal hone wala hai.</div>
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
            window.location.href = '/boy/gift-1/' + pageNumber;
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
