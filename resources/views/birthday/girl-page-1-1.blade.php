<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Girl Theme - Page 1 Variant 1</title>
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
            font-family: 'Lora', serif;
        }

        body {
            background: linear-gradient(135deg, #fef7f9 0%, #fceef2 30%, #f9e6ec 60%, #f4d9e3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
        }

        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Lora:ital,wght@0,400;0,600;1,400&display=swap');

        .girl2-wrap {
            background: linear-gradient(135deg, #fef7f9 0%, #fceef2 30%, #f9e6ec 60%, #f4d9e3 100%);
            width: 100%;
            max-width: 900px;
            aspect-ratio: 16 / 10;
            display: flex;
            align-items: stretch;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .girl2-deco {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .pink-particle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, #ff69b4, #ff1493);
            opacity: 0;
            animation: rise 6s ease-in-out infinite;
        }

        @keyframes rise {
            0% {
                opacity: 0;
                transform: translateY(0) scale(0.5);
            }

            30% {
                opacity: 0.5;
            }

            100% {
                opacity: 0;
                transform: translateY(-120px) scale(1.2);
            }
        }

        .girl2-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .arch-frame {
            width: min(210px, 25vw);
            height: auto;
            aspect-ratio: 210 / 275;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .arch-border {
            position: absolute;
            inset: 0;
            border: 2.5px solid #ff69b4;
            border-radius: 110px 110px 8px 8px;
            box-shadow: 0 0 0 6px rgba(255, 105, 180, 0.1), 0 0 0 12px rgba(255, 105, 180, 0.05), inset 0 0 30px rgba(255, 105, 180, 0.08);
        }

        .arch-border::before {
            content: '';
            position: absolute;
            inset: 5px;
            border: 1px solid rgba(255, 105, 180, 0.3);
            border-radius: 106px 106px 4px 4px;
        }

        .arch-photo {
            width: 90%;
            height: 93%;
            border-radius: 100px 100px 6px 6px;
            overflow: hidden;
            background: linear-gradient(180deg, #fceef2 0%, #f9e6ec 100%);
            position: relative;
        }

        .arch-photo svg {
            width: 100%;
            height: 100%;
        }

        .tiara-top {
            position: absolute;
            top: -22px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 28px;
            filter: drop-shadow(0 0 8px rgba(255, 20, 147, 0.6));
        }

        .pink-vine {
            position: absolute;
            font-size: 22px;
            opacity: 0.5;
        }

        .girl2-right {
            width: 35%;
            min-width: 280px;
            background: rgba(255, 105, 180, 0.04);
            border-left: 1px solid rgba(255, 105, 180, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 24px;
            gap: 18px;
        }

        .girl2-title {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: clamp(16px, 4vw, 19px);
            color: #ff1493;
            letter-spacing: 1px;
            text-align: center;
        }

        .girl2-subtitle {
            font-family: 'Lora', serif;
            font-size: clamp(9px, 2vw, 11px);
            color: rgba(255, 105, 180, 0.5);
            letter-spacing: 3px;
            text-transform: uppercase;
            text-align: center;
            margin-top: -12px;
        }

        .girl2-code-boxes {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .girl2-code-box {
            width: min(52px, 8vw);
            height: min(58px, 10vw);
            border: 1.5px solid rgba(255, 105, 180, 0.35);
            border-radius: 8px;
            background: rgba(255, 105, 180, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: clamp(20px, 5vw, 26px);
            color: #ff1493;
            position: relative;
        }

        .girl2-code-box.active {
            border-color: #ff69b4;
            box-shadow: 0 0 16px rgba(255, 105, 180, 0.25), inset 0 0 8px rgba(255, 105, 180, 0.06);
        }

        .girl2-code-box::after {
            content: '';
            position: absolute;
            bottom: 6px;
            width: 24px;
            height: 1.5px;
            background: rgba(255, 105, 180, 0.4);
            border-radius: 1px;
        }

        .girl2-code-box.active::after {
            background: #ffb6c1;
            box-shadow: 0 0 6px rgba(255, 182, 193, 0.5);
            animation: blink2 1s ease-in-out infinite;
        }

        @keyframes blink2 {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .girl2-numpad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 100%;
        }

        .girl2-btn {
            aspect-ratio: 1;
            min-height: 45px;
            border: 1px solid rgba(255, 105, 180, 0.2);
            border-radius: 8px;
            background: rgba(255, 105, 180, 0.05);
            color: #ff69b4;
            font-family: 'Playfair Display', serif;
            font-size: clamp(16px, 3vw, 20px);
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .girl2-btn:hover {
            background: rgba(255, 105, 180, 0.18);
            border-color: #ff69b4;
            box-shadow: 0 0 16px rgba(255, 105, 180, 0.2);
            transform: translateY(-1px);
        }

        .girl2-btn.sym {
            color: rgba(255, 105, 180, 0.45);
            font-size: clamp(12px, 2vw, 16px);
        }

        .hint-girl2 {
            font-family: 'Lora', serif;
            font-style: italic;
            font-size: clamp(11px, 2vw, 13px);
            color: rgba(255, 105, 180, 0.45);
            text-align: center;
        }

        .divider-pink {
            width: 80px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 105, 180, 0.5), transparent);
            margin: -4px 0;
        }

        .flicker {
            animation: flicker 4s ease-in-out infinite;
        }

        @keyframes flicker {

            0%,
            90%,
            100% {
                opacity: 0.5;
            }

            92%,
            96% {
                opacity: 0.2;
            }

            94%,
            98% {
                opacity: 0.6;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .girl2-wrap {
                flex-direction: column;
                aspect-ratio: auto;
                max-width: 100%;
                max-height: 100vh;
            }

            .girl2-left {
                padding: 30px 20px;
                flex: none;
                height: 45%;
            }

            .girl2-right {
                width: 100%;
                flex: 1;
                border-left: none;
                border-top: 1px solid rgba(255, 105, 180, 0.2);
            }

            .arch-frame {
                width: min(160px, 35vw);
            }

            .girl2-numpad {
                gap: 8px;
            }

            .girl2-btn {
                min-height: 40px;
            }
        }

        @media (max-width: 480px) {
            .girl2-left {
                padding: 20px 10px;
                height: 40%;
            }

            .arch-frame {
                width: min(130px, 30vw);
            }

            .girl2-right {
                padding: 20px 16px;
                gap: 12px;
            }

            .girl2-code-boxes {
                gap: 8px;
            }

            .girl2-numpad {
                gap: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="girl2-wrap">

        <div class="girl2-deco">
            <div class="pink-particle" style="width:4px;height:4px;left:15%;bottom:20%;animation-delay:0s"></div>
            <div class="pink-particle" style="width:3px;height:3px;left:25%;bottom:30%;animation-delay:1.5s"></div>
            <div class="pink-particle" style="width:5px;height:5px;left:10%;bottom:15%;animation-delay:3s"></div>
            <div class="pink-particle" style="width:3px;height:3px;left:35%;bottom:25%;animation-delay:2s"></div>
            <div class="pink-particle" style="width:4px;height:4px;left:20%;bottom:40%;animation-delay:4s"></div>

            <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:radial-gradient(ellipse at 30% 50%, rgba(255,105,180,0.08) 0%, transparent 60%);pointer-events:none"></div>

            <div class="flicker" style="position:absolute;top:10%;left:5%;font-size:18px;opacity:0.35">✦</div>
            <div class="flicker" style="position:absolute;bottom:15%;left:8%;font-size:12px;opacity:0.25;animation-delay:1s">✦</div>
            <div class="flicker" style="position:absolute;top:30%;left:18%;font-size:14px;opacity:0.2;animation-delay:2.5s">✦</div>
        </div>

        <div class="girl2-left">
            <div class="arch-frame">
                <span class="tiara-top">👸</span>
                <div class="arch-border"></div>
                <div class="arch-photo">
                    <svg viewBox="0 0 190 256" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="gsky" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#fceef2" />
                                <stop offset="50%" stop-color="#f9e6ec" />
                                <stop offset="100%" stop-color="#f4d9e3" />
                            </linearGradient>
                            <linearGradient id="sunset" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#ff69b4" stop-opacity="0.6" />
                                <stop offset="100%" stop-color="transparent" />
                            </linearGradient>
                            <clipPath id="archclip">
                                <rect x="0" y="0" width="190" height="256" rx="95" />
                            </clipPath>
                        </defs>
                        <rect width="190" height="256" fill="url(#gsky)" clip-path="url(#archclip)" />
                        <rect width="190" height="100" fill="url(#sunset)" clip-path="url(#archclip)" />
                        <ellipse cx="95" cy="300" rx="130" ry="70" fill="#f9e6ec" clip-path="url(#archclip)" />
                        <rect x="68" y="148" width="54" height="80" rx="5" fill="#f4d9e3" clip-path="url(#archclip)" />
                        <ellipse cx="95" cy="138" rx="26" ry="28" fill="#ff1493" clip-path="url(#archclip)" />
                        <path d="M68 148 Q95 125 122 148" stroke="#ff69b4" stroke-width="1.5" fill="none" clip-path="url(#archclip)" />
                        <rect x="62" y="200" width="22" height="45" rx="3" fill="#f4d9e3" clip-path="url(#archclip)" />
                        <rect x="106" y="200" width="22" height="45" rx="3" fill="#f4d9e3" clip-path="url(#archclip)" />
                        <ellipse cx="95" cy="50" rx="50" ry="20" fill="rgba(255,105,180,0.15)" clip-path="url(#archclip)" />
                        <circle cx="40" cy="30" r="4" fill="rgba(255,105,180,0.25)" clip-path="url(#archclip)" />
                        <circle cx="155" cy="45" r="3" fill="rgba(255,105,180,0.2)" clip-path="url(#archclip)" />
                        <circle cx="20" cy="80" r="2" fill="rgba(255,105,180,0.3)" clip-path="url(#archclip)" />
                        <circle cx="170" cy="90" r="2.5" fill="rgba(255,105,180,0.2)" clip-path="url(#archclip)" />
                    </svg>
                </div>
                <span class="pink-vine" style="bottom:-14px;left:50%;transform:translateX(-50%)">❀</span>
            </div>
        </div>

        <div class="girl2-right">
            <div class="girl2-title">Enter a Passcode</div>
            <div class="girl2-subtitle">— secret access —</div>

            <div class="divider-pink"></div>

            <div class="girl2-code-boxes">
                <div class="girl2-code-box active"></div>
                <div class="girl2-code-box"></div>
                <div class="girl2-code-box"></div>
                <div class="girl2-code-box"></div>
            </div>

            <div class="girl2-numpad">
                <button class="girl2-btn">1</button>
                <button class="girl2-btn">2</button>
                <button class="girl2-btn">3</button>
                <button class="girl2-btn">4</button>
                <button class="girl2-btn">5</button>
                <button class="girl2-btn">6</button>
                <button class="girl2-btn">7</button>
                <button class="girl2-btn">8</button>
                <button class="girl2-btn">9</button>
                <button class="girl2-btn sym">✱</button>
                <button class="girl2-btn">0</button>
                <button class="girl2-btn sym">#</button>
            </div>

            <div class="divider-pink"></div>
            <div class="hint-girl2">hint — its our fav code 💖</div>
        </div>

    </div>
</body>

</html>