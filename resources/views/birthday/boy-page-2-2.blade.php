<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Light Theme Page 2</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Cormorant+Garamond:wght@300;400;600&display=swap');

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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;

            background:
                radial-gradient(circle at top left, rgba(150, 200, 255, 0.25), transparent 40%),
                radial-gradient(circle at bottom right, rgba(200, 225, 255, 0.25), transparent 40%),
                linear-gradient(135deg, #f6fbff 0%, #e8f3ff 40%, #d7eaff 100%);
        }

        .boy-bday {
            width: 100%;
            max-width: 800px;
            min-height: 480px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            position: relative;
            overflow: hidden;

            padding: clamp(30px, 8vw, 50px);
            text-align: center;

            border-radius: 18px;

            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(16px);

            border: 1px solid rgba(255, 255, 255, 0.6);

            box-shadow: 0 20px 60px rgba(90, 140, 255, 0.18);
        }

        /* glow */
        .bb-glow {
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(140, 190, 255, 0.25), transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* floating particles */
        .bb-ember {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, #7fb6ff, #4f8dff);
            opacity: 0;
            animation: rise linear infinite;
        }

        @keyframes rise {
            0% {
                transform: translateY(0) scale(0.5);
                opacity: 0;
            }

            20% {
                opacity: 0.5;
            }

            100% {
                transform: translateY(-200px) scale(1.4);
                opacity: 0;
            }
        }

        /* corners */
        .bb-corner {
            position: absolute;
            width: 50px;
            height: 50px;
            opacity: 0.25;
            border-color: #6fa8ff;
        }

        .tl {
            top: 16px;
            left: 16px;
            border-top: 1px solid #6fa8ff;
            border-left: 1px solid #6fa8ff;
        }

        .tr {
            top: 16px;
            right: 16px;
            border-top: 1px solid #6fa8ff;
            border-right: 1px solid #6fa8ff;
        }

        .bl {
            bottom: 16px;
            left: 16px;
            border-bottom: 1px solid #6fa8ff;
            border-left: 1px solid #6fa8ff;
        }

        .br {
            bottom: 16px;
            right: 16px;
            border-bottom: 1px solid #6fa8ff;
            border-right: 1px solid #6fa8ff;
        }

        /* text */
        .bb-tag {
            font-style: italic;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-size: 13px;
            color: rgba(80, 130, 255, 0.7);
            margin-bottom: 18px;
        }

        .bb-icon-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .bb-line {
            width: 60px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(90, 140, 255, 0.8));
        }

        .bb-line.r {
            background: linear-gradient(90deg, rgba(90, 140, 255, 0.8), transparent);
        }

        .bb-crown {
            font-size: 30px;
            filter: drop-shadow(0 0 10px rgba(90, 140, 255, 0.4));
        }

        .bb-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(34px, 9vw, 48px);
            color: #4e74d6;
            font-weight: 700;
            line-height: 1.1;
        }

        .bb-title em {
            display: block;
            font-style: italic;
            color: #6fa8ff;
        }

        /* divider */
        .bb-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 18px 0;
        }

        .bb-div-line {
            width: 80px;
            height: 1px;
            background: rgba(90, 140, 255, 0.4);
        }

        .bb-div-diamond {
            width: 6px;
            height: 6px;
            background: #6fa8ff;
            transform: rotate(45deg);
        }

        /* message */
        .bb-msg {
            max-width: 380px;
            font-size: 18px;
            color: rgba(60, 100, 200, 0.75);
            font-style: italic;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* button */
        .bb-next {
            padding: 14px 48px;
            border-radius: 50px;

            border: 1px solid rgba(90, 140, 255, 0.6);
            background: rgba(255, 255, 255, 0.4);

            color: #4e74d6;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;

            cursor: pointer;

            box-shadow: 0 10px 25px rgba(90, 140, 255, 0.15);

            transition: 0.3s;
        }

        .bb-next:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #8cc0ff, #7e9dff);
            color: white;
        }

        /* sparkle */
        .bb-sparkle {
            position: absolute;
            color: #6fa8ff;
            opacity: 0;
            animation: sparkle 4s ease-in-out infinite;
        }

        @keyframes sparkle {

            0%,
            100% {
                opacity: 0;
                transform: scale(0.5);
            }

            50% {
                opacity: 0.6;
                transform: scale(1);
            }
        }

        @media(max-width:768px) {
            .bb-corner {
                width: 35px;
                height: 35px;
            }
        }

        @media(max-width:480px) {
            .bb-msg {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    <div class="boy-bday">

        <div class="bb-glow"></div>

        <div class="bb-corner tl"></div>
        <div class="bb-corner tr"></div>
        <div class="bb-corner bl"></div>
        <div class="bb-corner br"></div>

        <div class="bb-tag">— a soft surprise —</div>

        <div class="bb-icon-row">
            <div class="bb-line"></div>
            <div class="bb-crown">👑</div>
            <div class="bb-line r"></div>
        </div>

        <div class="bb-title">
            Happy Birthday
            <em>King</em>
        </div>

        <div class="bb-divider">
            <div class="bb-div-line"></div>
            <div class="bb-div-diamond"></div>
            <div class="bb-div-line"></div>
        </div>

        <div class="bb-msg">
            You are the main character today.<br>
            Everything is built around your moment ✨
        </div>

        <button class="bb-next">NEXT →</button>

    </div>

</body>

</html>