<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Love Journey</title>
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
        font-family: 'Bebas Neue', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a0f0f 50%, #15080c 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        min-height: 100vh;
        overflow: hidden;
    }

    .container {
        width: 100%;
        max-width: 900px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 40px;
    }

    .title {
        text-align: center;
        font-size: clamp(36px, 7vw, 52px);
        color: #d4a850;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .cube-container {
        perspective: 1200px;
        width: 280px;
        height: 280px;
        position: relative;
        margin: 40px auto;
    }

    .cube {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
        animation: rotateCube 8s infinite linear;
    }

    .cube-face {
        position: absolute;
        width: 280px;
        height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 120px;
        background: rgba(45, 18, 10, 0.9);
        border: 2px solid #c8922a;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(200, 146, 42, 0.25);
        font-weight: 700;
        color: #d4a850;
    }

    .front {
        transform: rotateY(0deg) translateZ(140px);
        background: linear-gradient(135deg, #1a1410 0%, #2d1200 100%);
        border-color: #d4a850;
    }

    .back {
        transform: rotateY(180deg) translateZ(140px);
        background: linear-gradient(135deg, #2d1200 0%, #1a1410 100%);
        border-color: #c8922a;
    }

    .right {
        transform: rotateY(90deg) translateZ(140px);
        background: linear-gradient(135deg, #251708 0%, #2d1200 100%);
        border-color: #d4a850;
    }

    .left {
        transform: rotateY(-90deg) translateZ(140px);
        background: linear-gradient(135deg, #2d1200 0%, #251708 100%);
        border-color: #c8922a;
    }

    .top {
        transform: rotateX(90deg) translateZ(140px);
        background: linear-gradient(135deg, #3d1a00 0%, #2d1200 100%);
        border-color: #d4a850;
    }

    .bottom {
        transform: rotateX(-90deg) translateZ(140px);
        background: linear-gradient(135deg, #2d1200 0%, #3d1a00 100%);
        border-color: #c8922a;
    }

    @keyframes rotateCube {
        0% {
            transform: rotateX(0) rotateY(0);
        }

        25% {
            transform: rotateX(90deg) rotateY(45deg);
        }

        50% {
            transform: rotateX(180deg) rotateY(90deg);
        }

        75% {
            transform: rotateX(270deg) rotateY(135deg);
        }

        100% {
            transform: rotateX(360deg) rotateY(180deg);
        }
    }

    .message {
        text-align: center;
        font-size: clamp(14px, 2.5vw, 20px);
        color: #b8882a;
        line-height: 1.8;
        max-width: 600px;
        padding: 30px 20px;
        background: rgba(45, 18, 10, 0.8);
        border: 1px solid rgba(200, 146, 42, 0.2);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(200, 146, 42, 0.15);
        letter-spacing: 0.5px;
    }

    .nav-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 20px;
    }

    .btn {
        background: #c8922a;
        color: #2d1200;
        border: none;
        padding: 12px 28px;
        border-radius: 60px;
        font-family: 'Bebas Neue', sans-serif;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .btn:hover {
        background: #d4a850;
        box-shadow: 0 4px 12px rgba(200, 146, 42, 0.3);
    }

    .btn-prev {
        background: transparent;
        color: #c8922a;
        border: 1px solid #c8922a;
    }

    .btn-prev:hover {
        background: #c8922a;
        color: #2d1200;
    }

    @media (max-width: 768px) {
        .cube-container {
            width: 200px;
            height: 200px;
        }

        .cube-face {
            width: 200px;
            height: 200px;
            font-size: 80px;
        }

        .front,
        .back,
        .right,
        .left,
        .top,
        .bottom {
            transform-origin: center;
        }

        .front {
            transform: rotateY(0deg) translateZ(100px);
        }

        .back {
            transform: rotateY(180deg) translateZ(100px);
        }

        .right {
            transform: rotateY(90deg) translateZ(100px);
        }

        .left {
            transform: rotateY(-90deg) translateZ(100px);
        }

        .top {
            transform: rotateX(90deg) translateZ(100px);
        }

        .bottom {
            transform: rotateX(-90deg) translateZ(100px);
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">My Legacy 👑</div>

        <div class="cube-container">
            <div class="cube">
                <div class="cube-face front">👑</div>
                <div class="cube-face back">💪</div>
                <div class="cube-face right">⚔️</div>
                <div class="cube-face left">🔥</div>
                <div class="cube-face top">💎</div>
                <div class="cube-face bottom">🏆</div>
            </div>
        </div>

        <div class="message">
            You are my king, my warrior, my fire, and my greatest treasure. Every day with you is a victory, and I am
            honored to be by your side. Forever yours, completely. 👑
        </div>

        <div class="nav-buttons">
            <button class="btn btn-prev" onclick="goPrev()">← PREVIOUS</button>
            <button class="btn" onclick="goHome()">CLOSE GIFT</button>
        </div>
    </div>

    <script>
    function goPrev() {
        window.location.href = '/boy/gift-1/2';
    }

    function goHome() {
        window.location.href = '/boy/page/3';
    }
    </script>
</body>

</html>