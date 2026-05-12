<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Love Letter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #fdf0f4 0%, #fce4ee 100%);
            overflow: hidden;
            font-family: 'Cormorant Garamond', serif;
        }

        .scene {
            position: relative;
            width: 420px;
            height: 320px;
            perspective: 1500px;
        }

        .envelope {
            position: absolute;
            width: 100%;
            height: 100%;
            bottom: 0;
            cursor: pointer;
        }

        .envelope-back {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #fef5f1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(196, 112, 154, .2);
        }

        .flap {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            transform-origin: top;
            transition: 1s ease;
            z-index: 5;
        }

        .flap::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: #f5ebe5;
            clip-path: polygon(0 0, 50% 60%, 100% 0);
        }

        .left-fold {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #efe5dd;
            clip-path: polygon(0 0, 0 100%, 50% 100%);
            z-index: 2;
        }

        .right-fold {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #e8ddd5;
            clip-path: polygon(100% 0, 100% 100%, 50% 100%);
            z-index: 2;
        }

        .bottom-fold {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #f0e5dd;
            clip-path: polygon(0 100%, 50% 45%, 100% 100%);
            z-index: 3;
        }

        .letter {
            position: absolute;
            left: 50%;
            bottom: 20px;
            transform: translateX(-50%);
            width: 340px;
            height: 440px;
            background: #fffbf8;
            border-radius: 16px;
            padding: 35px 28px;
            transition: 1s ease;
            z-index: 1;
            box-shadow: 0 10px 30px rgba(196, 112, 154, .15);
        }

        .letter::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            background: radial-gradient(circle at top left, #ffffff66, transparent 40%);
            pointer-events: none;
        }

        .title {
            text-align: center;
            font-size: 38px;
            color: #c4507a;
            margin-bottom: 18px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .mini {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            color: #a04468;
            font-size: 16px;
        }

        .love {
            font-family: 'Great Vibes', cursive;
            font-size: 48px;
            text-align: center;
            color: #d4608a;
            margin-bottom: 18px;
        }

        .text {
            font-size: 20px;
            line-height: 1.6;
            color: #8b4e6a;
            text-align: center;
        }

        .seal {
            position: absolute;
            width: 78px;
            height: 78px;
            background: radial-gradient(circle at top, #e8789c, #c4507a);
            border-radius: 50%;
            bottom: -38px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 6;
            color: white;
            font-size: 30px;
            box-shadow: 0 10px 20px rgba(196, 112, 154, .3);
        }

        .scene.open .flap {
            transform: rotateX(180deg);
            z-index: 0;
        }

        .scene.open .letter {
            transform: translateX(-50%) translateY(-180px);
        }

        .hint {
            position: absolute;
            bottom: 40px;
            color: #8b4e6a;
            font-size: 16px;
            letter-spacing: 1px;
            opacity: .8;
        }

        .nav-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #c4507a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 60px;
            cursor: pointer;
            font-family: 'Cormorant Garamond', serif;
            font-size: 14px;
            z-index: 100;
            transition: all 0.3s;
        }

        .nav-btn:hover {
            background: #8b4e6a;
            box-shadow: 0 4px 12px rgba(196, 112, 154, .3);
        }
    </style>
</head>

<body>
    <div class="scene" id="scene">
        <div class="letter">
            <div class="title">MY LOVE</div>
            <div class="mini">
                <span>To: You</span>
                <span>From: Me</span>
            </div>
            <div class="love">💌</div>
            <div class="text">
                Every moment with you feels like a beautiful dream. You are my heart's greatest wish, and I am forever grateful for you.
            </div>
        </div>
        <div class="envelope">
            <div class="envelope-back">
                <div class="left-fold"></div>
                <div class="right-fold"></div>
                <div class="bottom-fold"></div>
                <div class="flap"></div>
            </div>
            <div class="seal">❤</div>
        </div>
    </div>
    <div class="hint">Click Envelope</div>
    <button class="nav-btn" onclick="goToNext()">Next Gift →</button>
    <script>
        const scene = document.getElementById('scene');
        scene.addEventListener('click', () => {
            scene.classList.toggle('open');
        });

        function goToNext() {
            window.location.href = '/girl/gift-1/2';
        }
    </script>
</body>

</html>