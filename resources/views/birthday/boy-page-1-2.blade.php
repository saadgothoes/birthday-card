<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Birthday Card - Light Blue Theme</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lora:wght@400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
            font-family: 'Lora', serif;

            background:
                radial-gradient(circle at top left, rgba(130, 190, 255, 0.20), transparent 30%),
                radial-gradient(circle at bottom right, rgba(170, 220, 255, 0.18), transparent 30%),
                linear-gradient(135deg, #f6fbff 0%, #e8f3ff 35%, #d7eaff 70%, #c5deff 100%);
        }

        .boy2-wrap {
            width: 100%;
            max-width: 920px;
            aspect-ratio: 16 / 10;

            display: flex;
            overflow: hidden;
            position: relative;

            border-radius: 24px;

            background: rgba(255, 255, 255, 0.55);

            backdrop-filter: blur(18px);

            border: 1px solid rgba(255, 255, 255, 0.6);

            box-shadow:
                0 20px 60px rgba(92, 145, 255, 0.18),
                inset 0 0 0 1px rgba(255, 255, 255, 0.45);
        }

        /* floating glow */

        .floating-light {
            position: absolute;
            border-radius: 50%;
            filter: blur(30px);
            opacity: .45;
            animation: float 7s ease-in-out infinite;
        }

        .floating-light.one {
            width: 220px;
            height: 220px;
            background: #9ed0ff;
            top: -80px;
            left: -80px;
        }

        .floating-light.two {
            width: 180px;
            height: 180px;
            background: #bddbff;
            right: -60px;
            bottom: -60px;
            animation-delay: 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-18px);
            }
        }

        /* decorative particles */

        .particle {
            position: absolute;
            font-size: 18px;
            color: rgba(94, 149, 255, 0.45);
            animation: rise 7s linear infinite;
        }

        .particle.p1 {
            left: 10%;
            bottom: -10px;
        }

        .particle.p2 {
            left: 55%;
            bottom: -20px;
            animation-delay: 2s;
        }

        .particle.p3 {
            left: 82%;
            bottom: -10px;
            animation-delay: 4s;
        }

        @keyframes rise {
            0% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0;
            }

            20% {
                opacity: .6;
            }

            100% {
                transform: translateY(-180px) rotate(180deg);
                opacity: 0;
            }
        }

        /* left side */

        .boy2-left {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
        }

        .arch-frame {
            width: min(220px, 25vw);
            aspect-ratio: 210 / 275;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .arch-border {
            position: absolute;
            inset: 0;

            border-radius: 120px 120px 12px 12px;

            border: 2px solid #7bb2ff;

            background: rgba(255, 255, 255, 0.12);

            box-shadow:
                0 0 0 6px rgba(123, 178, 255, 0.10),
                0 0 0 12px rgba(123, 178, 255, 0.05),
                inset 0 0 30px rgba(123, 178, 255, 0.08);
        }

        .arch-border::before {
            content: '';
            position: absolute;
            inset: 6px;

            border-radius: 114px 114px 8px 8px;

            border: 1px solid rgba(123, 178, 255, 0.30);
        }

        .arch-photo {
            width: 90%;
            height: 92%;

            overflow: hidden;

            border-radius: 110px 110px 8px 8px;

            background:
                linear-gradient(180deg, #edf6ff 0%, #cfe5ff 100%);
        }

        .arch-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .crown-top {
            position: absolute;
            top: -22px;
            left: 50%;
            transform: translateX(-50%);

            width: 54px;
            height: 54px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: linear-gradient(135deg, #77a7ff, #5f7dff);

            color: white;
            font-size: 24px;

            box-shadow: 0 10px 25px rgba(95, 125, 255, 0.25);
        }

        .gold-vine {
            position: absolute;
            font-size: 22px;
            color: rgba(96, 145, 255, 0.4);
        }

        /* right side */

        .boy2-right {
            width: 36%;
            min-width: 300px;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            padding: 34px 26px;
            gap: 18px;

            background: rgba(255, 255, 255, 0.28);

            border-left: 1px solid rgba(255, 255, 255, 0.45);
        }

        .boy2-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(20px, 4vw, 26px);
            color: #4e74d6;
            text-align: center;
            font-weight: 700;
        }

        .boy2-subtitle {
            font-size: 11px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(78, 116, 214, 0.5);
            margin-top: -8px;
        }

        .divider {
            width: 90px;
            height: 1px;

            background:
                linear-gradient(to right,
                    transparent,
                    rgba(100, 145, 255, 0.5),
                    transparent);
        }

        /* passcode */

        .boy2-code-boxes {
            display: flex;
            gap: 12px;
        }

        .boy2-code-box {
            width: 54px;
            height: 60px;

            border-radius: 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 24px;
            font-family: 'Playfair Display', serif;

            color: #4e74d6;

            border: 1px solid rgba(100, 145, 255, 0.20);

            background: rgba(255, 255, 255, 0.55);

            box-shadow:
                0 8px 18px rgba(100, 145, 255, 0.08);
        }

        .boy2-code-box.active {
            border-color: #7ab0ff;

            box-shadow:
                0 0 18px rgba(122, 176, 255, 0.25),
                inset 0 0 8px rgba(122, 176, 255, 0.08);
        }

        /* keypad */

        .boy2-numpad {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .boy2-btn {
            height: 56px;

            border: none;
            border-radius: 16px;

            cursor: pointer;

            font-size: 20px;
            font-family: 'Playfair Display', serif;
            font-weight: 700;

            color: #5f83dd;

            background: rgba(255, 255, 255, 0.70);

            box-shadow:
                0 8px 20px rgba(100, 145, 255, 0.08);

            transition: .25s ease;
        }

        .boy2-btn:hover {
            transform: translateY(-3px);

            background:
                linear-gradient(135deg,
                    #8cc0ff,
                    #7e9dff);

            color: white;

            box-shadow:
                0 14px 28px rgba(106, 140, 255, 0.22);
        }

        .boy2-btn.sym {
            font-size: 16px;
            opacity: .7;
        }

        .hint-boy2 {
            text-align: center;
            font-size: 13px;
            color: rgba(78, 116, 214, 0.55);
            line-height: 1.7;
        }

        /* mobile */

        @media (max-width: 768px) {

            .boy2-wrap {
                flex-direction: column;
                aspect-ratio: auto;
            }

            .boy2-left {
                padding: 30px 20px;
            }

            .boy2-right {
                width: 100%;
                min-width: 100%;
                border-left: none;
                border-top: 1px solid rgba(255, 255, 255, 0.5);
            }

            .arch-frame {
                width: 180px;
            }
        }

        @media (max-width: 480px) {

            body {
                padding: 10px;
            }

            .boy2-wrap {
                border-radius: 18px;
            }

            .boy2-right {
                padding: 24px 18px;
            }

            .boy2-code-box {
                width: 48px;
                height: 54px;
            }

            .boy2-btn {
                height: 52px;
            }

            .arch-frame {
                width: 150px;
            }
        }
    </style>
</head>

<body>

    <div class="boy2-wrap">

        <!-- glow -->

        <div class="floating-light one"></div>
        <div class="floating-light two"></div>

        <!-- particles -->

        <div class="particle p1">✦</div>
        <div class="particle p2">✦</div>
        <div class="particle p3">✦</div>

        <!-- LEFT -->

        <div class="boy2-left">

            <div class="arch-frame">

                <div class="crown-top">
                    🎂
                </div>

                <div class="arch-border"></div>

                <div class="arch-photo">

                    <!-- replace image -->

                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=1200&auto=format&fit=crop"
                        alt="birthday boy">

                </div>

                <div class="gold-vine" style="left:-30px; top:30px;">
                    ❋
                </div>

                <div class="gold-vine" style="right:-30px; bottom:30px;">
                    ❋
                </div>

            </div>

        </div>

        <!-- RIGHT -->

        <div class="boy2-right">

            <div class="boy2-title">
                Enter Birthday Passcode
            </div>

            <div class="boy2-subtitle">
                secret celebration access
            </div>

            <div class="divider"></div>

            <!-- code -->

            <div class="boy2-code-boxes">

                <div class="boy2-code-box active">2</div>
                <div class="boy2-code-box">0</div>
                <div class="boy2-code-box">0</div>
                <div class="boy2-code-box">6</div>

            </div>

            <!-- keypad -->

            <div class="boy2-numpad">

                <button class="boy2-btn">1</button>
                <button class="boy2-btn">2</button>
                <button class="boy2-btn">3</button>

                <button class="boy2-btn">4</button>
                <button class="boy2-btn">5</button>
                <button class="boy2-btn">6</button>

                <button class="boy2-btn">7</button>
                <button class="boy2-btn">8</button>
                <button class="boy2-btn">9</button>

                <button class="boy2-btn sym">✱</button>
                <button class="boy2-btn">0</button>
                <button class="boy2-btn sym">#</button>

            </div>

            <div class="divider"></div>

            <div class="hint-boy2">
                hint — use your lucky birthday code 💙
            </div>

        </div>

    </div>

</body>

</html>