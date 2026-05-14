<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Birthday Card - Dark Girl Theme</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Cormorant+Garamond:wght@300;400;600&display=swap');

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
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background:
                radial-gradient(circle at top, rgba(255, 90, 140, 0.12), transparent 40%),
                linear-gradient(160deg, #09090f 0%, #11111a 45%, #1a1020 100%);
            overflow: hidden;
        }

        .girl-bday {
            width: 100%;
            max-width: 820px;
            min-height: 500px;
            position: relative;
            overflow: hidden;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;

            padding: clamp(30px, 7vw, 55px);

            border-radius: 24px;

            background:
                linear-gradient(145deg,
                    rgba(255, 255, 255, 0.04),
                    rgba(255, 255, 255, 0.01));

            border: 1px solid rgba(255, 255, 255, 0.08);

            backdrop-filter: blur(18px);

            box-shadow:
                0 0 60px rgba(255, 70, 130, 0.08),
                0 20px 80px rgba(0, 0, 0, 0.65);
        }

        .girl-bday::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 120, 170, 0.10), transparent 30%),
                radial-gradient(circle at 80% 80%, rgba(255, 80, 120, 0.08), transparent 30%);
            pointer-events: none;
        }

        .gb-deco {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .petal {
            position: absolute;
            opacity: 0;
            border-radius: 50% 0 50% 50%;
            animation: fall linear infinite;
            filter: blur(.2px);
        }

        @keyframes fall {
            0% {
                transform: translateY(-40px) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: .7;
            }

            100% {
                transform: translateY(650px) rotate(240deg);
                opacity: 0;
            }
        }

        .glow {
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 70, 140, 0.08);
            filter: blur(80px);
            z-index: 0;
        }

        .glow.one {
            top: -60px;
            left: -40px;
        }

        .glow.two {
            bottom: -80px;
            right: -40px;
        }

        .gb-tag {
            position: relative;
            z-index: 2;

            font-size: clamp(11px, 2vw, 14px);
            letter-spacing: 5px;
            text-transform: uppercase;
            color: #ff9bbb;
            opacity: .65;
            margin-bottom: 20px;
            font-style: italic;
        }

        .gb-icon-row {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }

        .gb-line {
            width: clamp(50px, 14vw, 80px);
            height: 1px;
            background: linear-gradient(to right, transparent, #ff7ba5);
            opacity: .5;
        }

        .gb-line.r {
            background: linear-gradient(to right, #ff7ba5, transparent);
        }

        .gb-heart {
            font-size: clamp(22px, 4vw, 30px);
            animation: pulse 2.5s infinite ease-in-out;
            filter: drop-shadow(0 0 12px rgba(255, 90, 150, .4));
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.12);
            }
        }

        .gb-title {
            position: relative;
            z-index: 2;

            font-family: 'Playfair Display', serif;
            font-size: clamp(40px, 9vw, 58px);
            line-height: 1.1;
            font-weight: 700;

            color: #ffffff;

            text-shadow:
                0 0 20px rgba(255, 110, 170, 0.15),
                0 0 50px rgba(255, 70, 140, 0.10);

            margin-bottom: 10px;
        }

        .gb-title em {
            display: block;
            font-style: italic;
            color: #ff7aa8;
            font-size: clamp(45px, 10vw, 65px);
        }

        .gb-divider {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0 24px;
        }

        .gb-div-line {
            width: clamp(60px, 14vw, 90px);
            height: 1px;
            background: rgba(255, 140, 180, 0.25);
        }

        .gb-div-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ff7aa8;
            box-shadow: 0 0 10px rgba(255, 122, 168, .6);
        }

        .gb-msg {
            position: relative;
            z-index: 2;

            max-width: 420px;

            font-size: clamp(16px, 3vw, 21px);
            line-height: 1.8;
            font-style: italic;

            color: rgba(255, 235, 242, 0.88);

            margin-bottom: 38px;
        }

        .gb-next {
            position: relative;
            z-index: 2;

            border: none;
            outline: none;
            cursor: pointer;

            padding: 14px 46px;
            border-radius: 100px;

            background:
                linear-gradient(135deg,
                    #ff4f8b,
                    #ff7ca8);

            color: white;

            font-family: 'Playfair Display', serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 2px;

            box-shadow:
                0 10px 30px rgba(255, 70, 130, 0.30);

            transition: .3s ease;
        }

        .gb-next:hover {
            transform: translateY(-3px) scale(1.02);

            box-shadow:
                0 14px 40px rgba(255, 70, 130, 0.45);
        }

        .gb-next::after {
            content: ' →';
            opacity: .8;
        }

        .spark {
            position: absolute;
            color: #ff8eb7;
            opacity: 0;
            animation: twinkle 3s infinite ease-in-out;
            z-index: 1;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0;
                transform: scale(.5);
            }

            50% {
                opacity: .7;
                transform: scale(1);
            }
        }

        .corner {
            position: absolute;
            width: 55px;
            height: 55px;
            opacity: .25;
        }

        .corner.tl {
            top: 18px;
            left: 18px;
            border-top: 1px solid #ff7aa8;
            border-left: 1px solid #ff7aa8;
        }

        .corner.tr {
            top: 18px;
            right: 18px;
            border-top: 1px solid #ff7aa8;
            border-right: 1px solid #ff7aa8;
        }

        .corner.bl {
            bottom: 18px;
            left: 18px;
            border-bottom: 1px solid #ff7aa8;
            border-left: 1px solid #ff7aa8;
        }

        .corner.br {
            bottom: 18px;
            right: 18px;
            border-bottom: 1px solid #ff7aa8;
            border-right: 1px solid #ff7aa8;
        }

        @media(max-width:768px) {
            body {
                padding: 12px;
            }

            .girl-bday {
                min-height: auto;
            }

            .corner {
                width: 35px;
                height: 35px;
            }
        }

        @media(max-width:480px) {

            .girl-bday {
                padding: 22px 18px;
            }

            .gb-msg {
                line-height: 1.6;
            }

            .corner {
                width: 24px;
                height: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="girl-bday">

        <div class="glow one"></div>
        <div class="glow two"></div>

        <div class="gb-deco" id="petals"></div>

        <div class="corner tl"></div>
        <div class="corner tr"></div>
        <div class="corner bl"></div>
        <div class="corner br"></div>

        <span class="spark" style="top:14%;left:12%;animation-delay:0s;">✦</span>
        <span class="spark" style="top:22%;right:14%;animation-delay:1s;">✦</span>
        <span class="spark" style="bottom:26%;left:10%;animation-delay:2s;">✦</span>
        <span class="spark" style="bottom:18%;right:13%;animation-delay:1.4s;">✦</span>

        <div class="gb-tag">— made with love —</div>

        <div class="gb-icon-row">
            <div class="gb-line"></div>
            <div class="gb-heart">💖</div>
            <div class="gb-line r"></div>
        </div>

        <div class="gb-title">
            Happy Birthday
            <em>Princess</em>
        </div>

        <div class="gb-divider">
            <div class="gb-div-line"></div>
            <div class="gb-div-dot"></div>
            <div class="gb-div-line"></div>
        </div>

        <p class="gb-msg">
            In this dark little universe,<br>
            you are the softest light in my life ✨
        </p>

        <button class="gb-next">NEXT</button>

    </div>

    <script>
        const petals = document.getElementById('petals');

        const colors = [
            '#ff5f95',
            '#ff7ba8',
            '#ff9fc0',
            '#ff4d88',
            '#ffb4cb'
        ];

        for (let i = 0; i < 18; i++) {

            const p = document.createElement('div');
            p.classList.add('petal');

            const size = 8 + Math.random() * 12;

            p.style.cssText = `
                left:${Math.random()*100}%;
                top:-20px;
                width:${size}px;
                height:${size*1.5}px;
                background:${colors[i % colors.length]};
                transform:rotate(${Math.random()*360}deg);
                animation-duration:${6 + Math.random()*6}s;
                animation-delay:${Math.random()*5}s;
            `;

            petals.appendChild(p);
        }
    </script>

</body>

</html>