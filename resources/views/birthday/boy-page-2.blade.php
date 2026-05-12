<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Boy Theme - Page 2</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400;1,700&family=Cormorant+Garamond:ital,wght@0,300;1,300;1,400&display=swap');

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
            background: linear-gradient(160deg, #0f0800 0%, #1e1000 40%, #150900 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
        }

        .boy-bday {
            min-height: 480px;
            width: 100%;
            max-width: 800px;
            background: linear-gradient(160deg, #0f0800 0%, #1e1000 40%, #150900 100%);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: clamp(30px, 8vw, 50px);
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .bb-deco {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .bb-ember {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, #ffb030, #ff6000);
            opacity: 0;
            animation: emberRise linear infinite;
        }

        @keyframes emberRise {
            0% {
                opacity: 0;
                transform: translateY(0) scale(0.5);
            }

            20% {
                opacity: 0.6;
            }

            100% {
                opacity: 0;
                transform: translateY(-200px) scale(1.5) translateX(var(--drift));
            }
        }

        .bb-tag {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(11px, 2.5vw, 13px);
            color: rgba(200, 146, 42, 0.55);
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: clamp(12px, 3vw, 18px);
        }

        .bb-icon-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: clamp(16px, 3vw, 22px);
            justify-content: center;
        }

        .bb-line {
            width: clamp(40px, 15vw, 60px);
            height: 0.5px;
            background: linear-gradient(90deg, transparent, rgba(200, 146, 42, 0.6));
        }

        .bb-line.r {
            background: linear-gradient(90deg, rgba(200, 146, 42, 0.6), transparent);
        }

        .bb-crown {
            font-size: clamp(24px, 6vw, 30px);
            filter: drop-shadow(0 0 10px rgba(255, 180, 0, 0.5));
        }

        .bb-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(36px, 10vw, 50px);
            font-weight: 700;
            color: #e8b840;
            line-height: 1.1;
            margin: 0 0 clamp(4px, 1vw, 6px);
            letter-spacing: -0.5px;
            text-shadow: 0 0 40px rgba(240, 160, 0, 0.25);
        }

        .bb-title em {
            font-style: italic;
            color: #f0d070;
            display: block;
            font-size: clamp(40px, 11vw, 56px);
            text-shadow: 0 0 30px rgba(255, 200, 50, 0.2);
        }

        .bb-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: clamp(12px, 3vw, 20px) 0;
            justify-content: center;
        }

        .bb-div-line {
            flex: 0 1 clamp(50px, 12vw, 80px);
            height: 0.5px;
            background: rgba(200, 146, 42, 0.3);
        }

        .bb-div-diamond {
            width: 6px;
            height: 6px;
            background: rgba(200, 146, 42, 0.5);
            transform: rotate(45deg);
            flex-shrink: 0;
        }

        .bb-msg {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(15px, 4vw, 19px);
            color: rgba(240, 200, 100, 0.7);
            line-height: 1.7;
            max-width: 360px;
            margin: 0 0 clamp(24px, 5vw, 36px);
        }

        .bb-next {
            background: transparent;
            color: #e8b840;
            border: 1px solid rgba(200, 146, 42, 0.5);
            padding: clamp(10px, 2vw, 14px) clamp(32px, 8vw, 48px);
            border-radius: 60px;
            font-family: 'Playfair Display', serif;
            font-size: clamp(13px, 2vw, 15px);
            font-weight: 700;
            letter-spacing: 3px;
            cursor: pointer;
            position: relative;
            z-index: 2;
            box-shadow: 0 0 20px rgba(200, 120, 0, 0.15), inset 0 0 20px rgba(200, 120, 0, 0.05);
            transition: all 0.25s;
            text-transform: uppercase;
        }

        .bb-next:hover {
            background: rgba(200, 146, 42, 0.1);
            border-color: #e8b840;
            box-shadow: 0 0 30px rgba(200, 120, 0, 0.25);
            transform: translateY(-2px);
        }

        .bb-next::after {
            content: ' →';
            opacity: 0.5;
        }

        .bb-sparkle {
            position: absolute;
            color: #e8b840;
            font-size: clamp(10px, 2vw, 13px);
            opacity: 0;
            animation: sparkleB 4s ease-in-out infinite;
        }

        @keyframes sparkleB {

            0%,
            100% {
                opacity: 0;
                transform: scale(0.4);
            }

            50% {
                opacity: 0.45;
                transform: scale(1);
            }
        }

        .bb-corner {
            position: absolute;
            width: 50px;
            height: 50px;
            opacity: 0.22;
        }

        .bb-corner.tl {
            top: 16px;
            left: 16px;
            border-top: 1px solid #c8922a;
            border-left: 1px solid #c8922a;
        }

        .bb-corner.tr {
            top: 16px;
            right: 16px;
            border-top: 1px solid #c8922a;
            border-right: 1px solid #c8922a;
        }

        .bb-corner.bl {
            bottom: 16px;
            left: 16px;
            border-bottom: 1px solid #c8922a;
            border-left: 1px solid #c8922a;
        }

        .bb-corner.br {
            bottom: 16px;
            right: 16px;
            border-bottom: 1px solid #c8922a;
            border-right: 1px solid #c8922a;
        }

        .bb-glow {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200, 100, 0, 0.12) 0%, transparent 70%);
            width: 400px;
            height: 400px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .boy-bday {
                min-height: auto;
                padding: clamp(20px, 5vw, 35px);
            }

            .bb-corner {
                width: 35px;
                height: 35px;
            }

            .bb-glow {
                width: 300px;
                height: 300px;
            }
        }

        @media (max-width: 480px) {
            .boy-bday {
                padding: 15px;
            }

            .bb-corner {
                width: 25px;
                height: 25px;
            }

            .bb-title {
                margin: 0 0 2px;
            }
        }
    </style>
</head>

<body>
    <div class="boy-bday" id="bbday">
        <div class="bb-deco" id="bbDeco"></div>
        <div class="bb-glow"></div>

        <div class="bb-corner tl"></div>
        <div class="bb-corner tr"></div>
        <div class="bb-corner bl"></div>
        <div class="bb-corner br"></div>

        <span class="bb-sparkle" style="top:18%;left:13%;animation-delay:0s">✦</span>
        <span class="bb-sparkle" style="top:22%;right:15%;animation-delay:1.5s">✦</span>
        <span class="bb-sparkle" style="bottom:25%;left:11%;animation-delay:3s">✦</span>
        <span class="bb-sparkle" style="bottom:20%;right:13%;animation-delay:0.7s">✦</span>

        <div class="bb-tag">— a little surprise —</div>

        <div class="bb-icon-row">
            <div class="bb-line"></div>
            <span class="bb-crown">👑</span>
            <div class="bb-line r"></div>
        </div>

        <div class="bb-title">
            Happy Birthday
            <em>My Love</em>
        </div>

        <div class="bb-divider">
            <div class="bb-div-line"></div>
            <div class="bb-div-diamond"></div>
            <div class="bb-div-line"></div>
        </div>

        <p class="bb-msg">You are my favourite person in this world.<br>Today is all yours, my king. 🕯️</p>

        <button class="bb-next">NEXT</button>
    </div>

    <script>
        const bbDeco = document.getElementById('bbDeco');
        for (let i = 0; i < 14; i++) {
            const el = document.createElement('div');
            el.className = 'bb-ember';
            const size = 3 + Math.random() * 4;
            const drift = (Math.random() - 0.5) * 60;
            el.style.cssText = `
                left:${10+Math.random()*80}%;
                bottom:${Math.random()*20}%;
                width:${size}px;
                height:${size}px;
                --drift:${drift}px;
                animation-duration:${3+Math.random()*4}s;
                animation-delay:${Math.random()*4}s;
            `;
            bbDeco.appendChild(el);
        }
    </script>
</body>

</html>