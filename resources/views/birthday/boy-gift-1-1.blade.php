<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Love Letter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Bebas+Neue&display=swap" rel="stylesheet">
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
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0f0f 50%, #15080c 100%);
            overflow: hidden;
            font-family: 'Bebas Neue', sans-serif;
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
            background: #2d1a0a;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(200, 146, 42, .25);
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
            background: #3d2a15;
            clip-path: polygon(0 0, 50% 60%, 100% 0);
        }

        .left-fold {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #2a1a0a;
            clip-path: polygon(0 0, 0 100%, 50% 100%);
            z-index: 2;
        }

        .right-fold {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #251708;
            clip-path: polygon(100% 0, 100% 100%, 50% 100%);
            z-index: 2;
        }

        .bottom-fold {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #3d2a15;
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
            background: #1a1410;
            border-radius: 16px;
            padding: 35px 28px;
            transition: 1s ease;
            z-index: 1;
            box-shadow: 0 10px 30px rgba(200, 146, 42, .2);
        }

        .letter::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            background: radial-gradient(circle at top left, #444444, transparent 40%);
            pointer-events: none;
        }

        .title {
            text-align: center;
            font-size: 38px;
            color: #d4a850;
            margin-bottom: 18px;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .mini {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            color: #a07040;
            font-size: 16px;
        }

        .love {
            font-family: 'Great Vibes', cursive;
            font-size: 48px;
            text-align: center;
            color: #e8b840;
            margin-bottom: 18px;
        }

        .text {
            font-size: 20px;
            line-height: 1.6;
            color: #b8882a;
            text-align: center;
        }

        .seal {
            position: absolute;
            width: 78px;
            height: 78px;
            background: radial-gradient(circle at top, #d4a850, #8b6f2f);
            border-radius: 50%;
            bottom: -38px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 6;
            color: #2d1200;
            font-size: 30px;
            box-shadow: 0 10px 20px rgba(200, 146, 42, .3);
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
            color: #b8882a;
            font-size: 16px;
            letter-spacing: 2px;
            opacity: .8;
        }

        .nav-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #c8922a;
            color: #2d1200;
            border: none;
            padding: 10px 20px;
            border-radius: 60px;
            cursor: pointer;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 14px;
            z-index: 100;
            transition: all 0.3s;
            letter-spacing: 1px;
        }

        .nav-btn:hover {
            background: #d4a850;
            box-shadow: 0 4px 12px rgba(200, 146, 42, .4);
        }
    </style>
</head>

<body>
    <div class="scene" id="scene">
        <div class="letter">
            <div class="title">MY KING</div>
            <div class="mini">
                <span>To: You</span>
                <span>From: Me</span>
            </div>
            <div class="love">👑</div>
            <div class="text">
                You are the strongest, the kindest, and the most incredible man I know. Every day with you is a blessing, and I am endlessly grateful for you.
            </div>
        </div>
        <div class="envelope">
            <div class="envelope-back">
                <div class="left-fold"></div>
                <div class="right-fold"></div>
                <div class="bottom-fold"></div>
                <div class="flap"></div>
            </div>
            <div class="seal">💛</div>
        </div>
    </div>
    <div class="hint">Click Envelope</div>
    <button class="nav-btn" onclick="goToNext()">NEXT GIFT →</button>
    <script>
        const scene = document.getElementById('scene');
        scene.addEventListener('click', () => {
            scene.classList.toggle('open');
        });

        function goToNext() {
            window.location.href = '/boy/gift-1/2';
        }
    </script>
</body>

</html>