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
