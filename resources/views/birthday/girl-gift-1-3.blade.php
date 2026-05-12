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
            font-family: 'Cormorant Garamond', serif;
        }

        body {
            background: linear-gradient(135deg, #fdf0f4 0%, #fce4ee 100%);
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
            color: #c4507a;
            font-weight: 600;
            letter-spacing: 2px;
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
            background: rgba(255, 251, 248, 0.95);
            border: 2px solid #c4507a;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(196, 112, 154, 0.2);
            font-weight: 600;
            color: #c4507a;
        }

        .front {
            transform: rotateY(0deg) translateZ(140px);
            background: linear-gradient(135deg, #fff5f9 0%, #ffe8f0 100%);
        }

        .back {
            transform: rotateY(180deg) translateZ(140px);
            background: linear-gradient(135deg, #fff0f5 0%, #ffe0eb 100%);
        }

        .right {
            transform: rotateY(90deg) translateZ(140px);
            background: linear-gradient(135deg, #fffbf8 0%, #fff0f5 100%);
        }

        .left {
            transform: rotateY(-90deg) translateZ(140px);
            background: linear-gradient(135deg, #fff5f9 0%, #fffbf8 100%);
        }

        .top {
            transform: rotateX(90deg) translateZ(140px);
            background: linear-gradient(135deg, #ffe8f0 0%, #fff0f5 100%);
        }

        .bottom {
            transform: rotateX(-90deg) translateZ(140px);
            background: linear-gradient(135deg, #fff0f5 0%, #ffe8f0 100%);
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
            font-size: clamp(16px, 2.5vw, 22px);
            color: #8b4e6a;
            line-height: 1.8;
            max-width: 600px;
            padding: 30px 20px;
            background: rgba(255, 251, 248, 0.9);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(196, 112, 154, 0.1);
        }

        .nav-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 20px;
        }

        .btn {
            background: #c4507a;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 60px;
            font-family: 'Cormorant Garamond', serif;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #8b4e6a;
            box-shadow: 0 4px 12px rgba(196, 112, 154, 0.3);
        }

        .btn-prev {
            background: rgba(196, 112, 154, 0.2);
            color: #c4507a;
            border: 1px solid #c4507a;
        }

        .btn-prev:hover {
            background: #c4507a;
            color: white;
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
        <div class="title">Our Love Story 💝</div>

        <div class="cube-container">
            <div class="cube">
                <div class="cube-face front">💕</div>
                <div class="cube-face back">💖</div>
                <div class="cube-face right">🌹</div>
                <div class="cube-face left">✨</div>
                <div class="cube-face top">💐</div>
                <div class="cube-face bottom">💝</div>
            </div>
        </div>

        <div class="message">
            You are my greatest adventure, my sweetest dream, and my forever love. Every day with you is a beautiful chapter in our love story. I love you more than words could ever express. 💕
        </div>

        <div class="nav-buttons">
            <button class="btn btn-prev" onclick="goPrev()">← Previous</button>
            <button class="btn" onclick="goHome()">Close Gift</button>
        </div>
    </div>

    <script>
        function goPrev() {
            window.location.href = '/girl/gift-1/2';
        }

        function goHome() {
            window.location.href = '/girl/page/3';
        }
    </script>
</body>

</html>