<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Girl Theme - Page 2</title>
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
            background: linear-gradient(160deg, #fff0f5 0%, #fce4ee 40%, #f5d6e8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
        }

        .girl-bday {
            min-height: 480px;
            width: 100%;
            max-width: 800px;
            background: linear-gradient(160deg, #fff0f5 0%, #fce4ee 40%, #f5d6e8 100%);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: clamp(30px, 8vw, 50px);
            text-align: center;
            box-shadow: 0 20px 60px rgba(196, 112, 154, 0.2);
        }

        .gb-deco {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .petal-svg {
            position: absolute;
            opacity: 0;
            animation: petalFall linear infinite;
        }

        @keyframes petalFall {
            0% {
                opacity: 0;
                transform: translateY(-30px) rotate(0deg);
            }

            10% {
                opacity: 0.55;
            }

            90% {
                opacity: 0.4;
            }

            100% {
                opacity: 0;
                transform: translateY(520px) rotate(120deg);
            }
        }

        .gb-tag {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(11px, 2.5vw, 13px);
            color: #c07090;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: clamp(12px, 3vw, 18px);
            opacity: 0.7;
        }

        .gb-icon-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: clamp(16px, 3vw, 22px);
            justify-content: center;
        }

        .gb-line {
            width: clamp(40px, 15vw, 60px);
            height: 1px;
            background: linear-gradient(90deg, transparent, #d4829a);
        }

        .gb-line.r {
            background: linear-gradient(90deg, #d4829a, transparent);
        }

        .gb-rose {
            font-size: clamp(22px, 5vw, 28px);
            filter: drop-shadow(0 2px 6px rgba(200, 80, 100, 0.3));
        }

        .gb-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(36px, 10vw, 52px);
            font-weight: 700;
            color: #8b2252;
            line-height: 1.1;
            margin: 0 0 clamp(4px, 1vw, 6px);
            letter-spacing: -0.5px;
            text-shadow: 0 2px 20px rgba(180, 60, 100, 0.12);
        }

        .gb-title em {
            font-style: italic;
            color: #c0406a;
            display: block;
            font-size: clamp(40px, 11vw, 58px);
        }

        .gb-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: clamp(12px, 3vw, 20px) 0;
            justify-content: center;
        }

        .gb-div-line {
            flex: 0 1 clamp(50px, 12vw, 80px);
            height: 0.5px;
            background: rgba(196, 112, 154, 0.35);
        }

        .gb-div-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #c4709a;
            opacity: 0.5;
            flex-shrink: 0;
        }

        .gb-msg {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(15px, 4vw, 19px);
            color: #a04468;
            line-height: 1.7;
            max-width: 360px;
            opacity: 0.85;
            margin: 0 0 clamp(24px, 5vw, 36px);
        }

        .gb-next {
            background: #8b2252;
            color: #fff5f8;
            border: none;
            padding: clamp(10px, 2vw, 14px) clamp(32px, 8vw, 48px);
            border-radius: 60px;
            font-family: 'Playfair Display', serif;
            font-size: clamp(13px, 2vw, 16px);
            font-weight: 700;
            letter-spacing: 2px;
            cursor: pointer;
            position: relative;
            z-index: 2;
            box-shadow: 0 8px 24px rgba(139, 34, 82, 0.25);
            transition: transform 0.2s, box-shadow 0.2s;
            text-transform: uppercase;
        }

        .gb-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(139, 34, 82, 0.3);
        }

        .gb-next::after {
            content: ' →';
            opacity: 0.7;
        }

        .gb-sparkle {
            position: absolute;
            font-size: clamp(10px, 2vw, 14px);
            opacity: 0;
            animation: sparkleAnim 3s ease-in-out infinite;
            color: #e060a0;
        }

        @keyframes sparkleAnim {

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

        .gb-corner {
            position: absolute;
            width: 50px;
            height: 50px;
            opacity: 0.18;
        }

        .gb-corner.tl {
            top: 16px;
            left: 16px;
            border-top: 1.5px solid #c4709a;
            border-left: 1.5px solid #c4709a;
        }

        .gb-corner.tr {
            top: 16px;
            right: 16px;
            border-top: 1.5px solid #c4709a;
            border-right: 1.5px solid #c4709a;
        }

        .gb-corner.bl {
            bottom: 16px;
            left: 16px;
            border-bottom: 1.5px solid #c4709a;
            border-left: 1.5px solid #c4709a;
        }

        .gb-corner.br {
            bottom: 16px;
            right: 16px;
            border-bottom: 1.5px solid #c4709a;
            border-right: 1.5px solid #c4709a;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .girl-bday {
                min-height: auto;
                padding: clamp(20px, 5vw, 35px);
            }

            .gb-corner {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 480px) {
            .girl-bday {
                padding: 15px;
            }

            .gb-corner {
                width: 25px;
                height: 25px;
            }

            .gb-title {
                margin: 0 0 2px;
            }
        }
    </style>
</head>

<body>
    <div class="girl-bday" id="gbday">
        <div class="gb-deco" id="gbDeco"></div>

        <div class="gb-corner tl"></div>
        <div class="gb-corner tr"></div>
        <div class="gb-corner bl"></div>
        <div class="gb-corner br"></div>

        <span class="gb-sparkle" style="top:18%;left:12%;animation-delay:0s">✦</span>
        <span class="gb-sparkle" style="top:25%;right:14%;animation-delay:1.2s">✦</span>
        <span class="gb-sparkle" style="bottom:28%;left:10%;animation-delay:2.4s">✦</span>
        <span class="gb-sparkle" style="bottom:22%;right:12%;animation-delay:0.8s">✦</span>

        <div class="gb-tag">— a little surprise —</div>

        <div class="gb-icon-row">
            <div class="gb-line"></div>
            <span class="gb-rose">🌹</span>
            <div class="gb-line r"></div>
        </div>

        <div class="gb-title">
            Happy Birthday
            <em>My Love</em>
        </div>

        <div class="gb-divider">
            <div class="gb-div-line"></div>
            <div class="gb-div-dot"></div>
            <div class="gb-div-line"></div>
        </div>

        <p class="gb-msg">You make every day feel like a celebration.<br>Today is all about you, my love. 🌸</p>

        <button class="gb-next">NEXT</button>
    </div>

    <script>
        const deco = document.getElementById('gbDeco');
        const petalColors = ['#f4a0b8', '#e87898', '#f8c0d0', '#d47090', '#fbd0e0'];
        for (let i = 0; i < 12; i++) {
            const el = document.createElement('div');
            el.className = 'petal-svg';
            const size = 8 + Math.random() * 10;
            el.style.cssText = `
                left:${Math.random()*100}%;
                top:0;
                width:${size}px;
                height:${size*1.4}px;
                background:${petalColors[i%petalColors.length]};
                border-radius:50% 0;
                transform:rotate(${Math.random()*360}deg);
                animation-duration:${5+Math.random()*6}s;
                animation-delay:${Math.random()*5}s;
            `;
            deco.appendChild(el);
        }
    </script>
</body>

</html>