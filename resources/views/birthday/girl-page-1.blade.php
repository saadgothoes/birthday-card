<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Girl Theme - Page 1</title>
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
            background: linear-gradient(135deg, #fff0f5 0%, #fce8f0 40%, #f5e0ec 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
        }

        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:wght@300;400;600&display=swap');

        .girl-wrap {
            background: linear-gradient(135deg, #fff0f5 0%, #fce8f0 40%, #f5e0ec 100%);
            width: 100%;
            max-width: 900px;
            aspect-ratio: 16 / 10;
            display: flex;
            align-items: stretch;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 60px rgba(196, 112, 154, 0.2);
        }

        .girl-deco {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .petal {
            position: absolute;
            border-radius: 50% 0;
            opacity: 0.12;
            animation: drift 8s ease-in-out infinite;
        }

        @keyframes drift {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(5deg);
            }
        }

        .girl-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .oval-frame {
            width: min(200px, 25vw);
            aspect-ratio: 200 / 270;
            border-radius: 50%;
            border: 3px solid #c4709a;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 8px rgba(196, 112, 154, 0.1), 0 0 0 16px rgba(196, 112, 154, 0.05), 0 20px 60px rgba(196, 112, 154, 0.2);
        }

        .oval-frame::before {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 1px dashed rgba(196, 112, 154, 0.3);
        }

        .oval-photo {
            width: 87.5%;
            height: 92%;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(180deg, #ffd6e8 0%, #f0a8c8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .oval-photo svg {
            width: 100%;
            height: 100%;
        }

        .rose-accent {
            position: absolute;
            font-size: 28px;
            opacity: 0.7;
        }

        .girl-right {
            width: 35%;
            min-width: 280px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(10px);
            border-left: 1px solid rgba(196, 112, 154, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 24px;
            gap: 18px;
            overflow-y: auto;
        }

        .girl-title {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: clamp(16px, 4vw, 20px);
            color: #9b4d76;
            letter-spacing: 1px;
            text-align: center;
        }

        .girl-code-boxes {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .girl-code-box {
            width: min(50px, 8vw);
            height: min(54px, 9vw);
            border: 0;
            border-bottom: 2px solid #c4709a;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: clamp(18px, 5vw, 24px);
            color: #9b4d76;
            position: relative;
        }

        .girl-code-box.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            width: 100%;
            height: 2px;
            background: #e060a0;
            box-shadow: 0 0 8px #e060a0;
            animation: blink 1s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .girl-numpad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 100%;
        }

        .girl-btn {
            aspect-ratio: 1;
            min-height: 45px;
            border: 1px solid rgba(196, 112, 154, 0.25);
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.7);
            color: #7a3460;
            font-family: 'Playfair Display', serif;
            font-size: clamp(16px, 3vw, 20px);
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(196, 112, 154, 0.1);
        }

        .girl-btn:hover {
            background: rgba(196, 112, 154, 0.15);
            border-color: #c4709a;
            transform: scale(1.05);
            box-shadow: 0 4px 16px rgba(196, 112, 154, 0.25);
        }

        .girl-btn.sym {
            color: #c4709a;
            font-size: clamp(12px, 2vw, 16px);
        }

        .hint-girl {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(12px, 2vw, 14px);
            color: rgba(155, 77, 118, 0.6);
            letter-spacing: 0.5px;
            text-align: center;
        }

        .sparkle {
            position: absolute;
            color: #e060a0;
            font-size: 12px;
            opacity: 0;
            animation: sparkle 3s ease-in-out infinite;
        }

        @keyframes sparkle {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }

            50% {
                opacity: 0.6;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: scale(0.5);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .girl-wrap {
                flex-direction: column;
                aspect-ratio: auto;
                max-width: 100%;
                max-height: 100vh;
            }

            .girl-left {
                padding: 30px 20px;
                flex: none;
                height: 45%;
            }

            .girl-right {
                width: 100%;
                flex: 1;
                border-left: none;
                border-top: 1px solid rgba(196, 112, 154, 0.2);
            }

            .oval-frame {
                width: min(160px, 35vw);
            }

            .girl-numpad {
                gap: 8px;
            }

            .girl-btn {
                min-height: 40px;
            }
        }

        @media (max-width: 480px) {
            .girl-left {
                padding: 20px 10px;
                height: 40%;
            }

            .oval-frame {
                width: min(130px, 30vw);
            }

            .girl-right {
                padding: 20px 16px;
                gap: 12px;
            }

            .girl-code-boxes {
                gap: 8px;
            }

            .girl-numpad {
                gap: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="girl-wrap">
        <div class="girl-deco">
            <div class="petal"
                style="width:80px;height:80px;background:#e060a0;top:10%;left:5%;animation-delay:0s;transform:rotate(20deg)">
            </div>
            <div class="petal"
                style="width:50px;height:50px;background:#f090b0;top:60%;left:8%;animation-delay:2s;transform:rotate(45deg)">
            </div>
            <div class="petal"
                style="width:60px;height:60px;background:#c4709a;top:20%;left:15%;animation-delay:4s;transform:rotate(70deg)">
            </div>
            <div class="petal"
                style="width:40px;height:40px;background:#e080b0;bottom:15%;left:20%;animation-delay:1s;transform:rotate(10deg)">
            </div>
        </div>

        <div class="girl-left">
            <span class="sparkle" style="top:15%;left:30%;animation-delay:0s">✦</span>
            <span class="sparkle" style="top:70%;left:25%;animation-delay:1.5s">✦</span>
            <span class="sparkle" style="top:40%;left:10%;animation-delay:3s">✦</span>

            <div class="oval-frame">
                <span class="rose-accent" style="top:-18px;left:50%;transform:translateX(-50%)">🌸</span>
                <div class="oval-photo">
                    <svg viewBox="0 0 175 248" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="gsky" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#ffc0d0" />
                                <stop offset="60%" stop-color="#ff9ab5" />
                                <stop offset="100%" stop-color="#e07090" />
                            </linearGradient>
                            <clipPath id="oval">
                                <ellipse cx="87.5" cy="124" rx="87.5" ry="124" />
                            </clipPath>
                        </defs>
                        <ellipse cx="87.5" cy="124" rx="87.5" ry="124" fill="url(#gsky)" />
                        <ellipse cx="87.5" cy="290" rx="100" ry="60" fill="#d06080" clip-path="url(#oval)" />
                        <rect x="65" y="130" width="48" height="80" rx="5" fill="#f07090" clip-path="url(#oval)" />
                        <ellipse cx="89" cy="118" rx="22" ry="26" fill="#ffc0c8" clip-path="url(#oval)" />
                        <path d="M67 118 Q89 95 111 118" stroke="#e08090" stroke-width="1" fill="none"
                            clip-path="url(#oval)" />
                        <ellipse cx="87" cy="60" rx="30" ry="15" fill="#c05070" clip-path="url(#oval)" opacity="0.4" />
                        <circle cx="30" cy="30" r="8" fill="rgba(255,255,255,0.2)" clip-path="url(#oval)" />
                        <circle cx="145" cy="50" r="5" fill="rgba(255,255,255,0.15)" clip-path="url(#oval)" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="girl-right">
            <div class="girl-title">Enter a Passcode</div>

            <div class="girl-code-boxes">
                <div class="girl-code-box active"></div>
                <div class="girl-code-box"></div>
                <div class="girl-code-box"></div>
                <div class="girl-code-box"></div>
            </div>

            <div class="girl-numpad">
                <button class="girl-btn">1</button>
                <button class="girl-btn">2</button>
                <button class="girl-btn">3</button>
                <button class="girl-btn">4</button>
                <button class="girl-btn">5</button>
                <button class="girl-btn">6</button>
                <button class="girl-btn">7</button>
                <button class="girl-btn">8</button>
                <button class="girl-btn">9</button>
                <button class="girl-btn sym">✱</button>
                <button class="girl-btn">0</button>
                <button class="girl-btn sym">#</button>
            </div>

            <div class="hint-girl">hint — its our fav code 🌷</div>
        </div>
    </div>
</body>

</html>