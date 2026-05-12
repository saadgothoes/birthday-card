<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Girl Theme - Page 3</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Abril+Fatface&family=DM+Serif+Display:ital@0;1&family=Cormorant+Garamond:ital,wght@1,300;1,400;1,500&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            font-family: 'Cormorant Garamond', serif;
        }

        body {
            background: #fdf0f4;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
        }

        .gp3 {
            min-height: 500px;
            background: #fdf0f4;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: clamp(30px, 8vw, 50px);
            gap: 32px;
            width: 100%;
            max-width: 900px;
        }

        .gp3-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 0%, rgba(220, 100, 140, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 100%, rgba(200, 80, 120, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(240, 140, 170, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        .gp3-corner {
            position: absolute;
            width: 44px;
            height: 44px;
            opacity: 0.15;
        }

        .gp3-corner.tl {
            top: 14px;
            left: 14px;
            border-top: 1.5px solid #c4709a;
            border-left: 1.5px solid #c4709a;
        }

        .gp3-corner.tr {
            top: 14px;
            right: 14px;
            border-top: 1.5px solid #c4709a;
            border-right: 1.5px solid #c4709a;
        }

        .gp3-corner.bl {
            bottom: 14px;
            left: 14px;
            border-bottom: 1.5px solid #c4709a;
            border-left: 1.5px solid #c4709a;
        }

        .gp3-corner.br {
            bottom: 14px;
            right: 14px;
            border-bottom: 1.5px solid #c4709a;
            border-right: 1.5px solid #c4709a;
        }

        .gp3-top {
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .gp3-sub {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(11px, 2vw, 13px);
            color: #b06080;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .gp3-title {
            font-family: 'Abril Fatface', serif;
            font-size: clamp(36px, 8vw, 52px);
            color: #7a1a42;
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .gp3-title span {
            font-family: 'DM Serif Display', serif;
            font-style: italic;
            font-size: clamp(32px, 7vw, 44px);
            color: #c4507a;
            display: block;
        }

        .gp3-gifts {
            display: flex;
            gap: clamp(20px, 5vw, 28px);
            align-items: flex-end;
            justify-content: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .gp3-gift-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            position: relative;
        }

        .gp3-gift-box {
            position: relative;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: drop-shadow(0 8px 20px rgba(180, 60, 100, 0.2));
        }

        .gp3-gift-wrap:hover .gp3-gift-box {
            transform: translateY(-10px) scale(1.06);
            filter: drop-shadow(0 16px 30px rgba(180, 60, 100, 0.3));
        }

        .gp3-gift-wrap.opened .gp3-gift-box {
            animation: openGift 0.5s ease forwards;
        }

        @keyframes openGift {
            0% {
                transform: scale(1);
            }

            40% {
                transform: scale(1.15) rotate(-5deg);
            }

            60% {
                transform: scale(0.9) rotate(3deg);
            }

            100% {
                transform: scale(1) rotate(0deg);
            }
        }

        .gp3-gift-label {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(10px, 1.5vw, 12px);
            color: rgba(160, 60, 100, 0.6);
            letter-spacing: 1px;
            opacity: 0;
            transform: translateY(4px);
            transition: all 0.3s;
        }

        .gp3-gift-wrap:hover .gp3-gift-label {
            opacity: 1;
            transform: translateY(0);
        }

        .gp3-hint {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(14px, 2vw, 16px);
            color: rgba(160, 60, 100, 0.55);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }

        .gp3-hline {
            width: 50px;
            height: 0.5px;
            background: rgba(180, 80, 110, 0.25);
        }

        .gp3-sparkle {
            position: absolute;
            color: #e070a0;
            opacity: 0;
            animation: spg 3s ease-in-out infinite;
            font-size: clamp(10px, 2vw, 12px);
            pointer-events: none;
        }

        @keyframes spg {

            0%,
            100% {
                opacity: 0;
                transform: scale(0.4)
            }

            50% {
                opacity: 0.5;
                transform: scale(1)
            }
        }

        .petal-wrap {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .petal-g {
            position: absolute;
            opacity: 0;
            border-radius: 50% 0;
            animation: pfallg linear infinite;
        }

        @keyframes pfallg {
            0% {
                opacity: 0;
                transform: translateY(-20px) rotate(0deg);
            }

            15% {
                opacity: 0.5;
            }

            85% {
                opacity: 0.35;
            }

            100% {
                opacity: 0;
                transform: translateY(550px) rotate(110deg);
            }
        }

        .gift-reveal {
            position: fixed;
            inset: 0;
            background: rgba(240, 180, 200, 0.92);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            border-radius: 16px;
            flex-direction: column;
            gap: 20px;
            text-align: center;
            padding: clamp(20px, 5vw, 40px);
        }

        .gift-reveal.show {
            display: flex;
            animation: revealIn 0.4s ease;
        }

        @keyframes revealIn {
            0% {
                opacity: 0;
                transform: scale(0.85)
            }

            100% {
                opacity: 1;
                transform: scale(1)
            }
        }

        .reveal-emoji {
            font-size: clamp(48px, 12vw, 64px);
            filter: drop-shadow(0 4px 12px rgba(180, 60, 100, 0.3));
        }

        .reveal-title {
            font-family: 'Abril Fatface', serif;
            font-size: clamp(26px, 6vw, 36px);
            color: #7a1a42;
        }

        .reveal-msg {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: clamp(16px, 3vw, 20px);
            color: #9a4060;
            max-width: 320px;
            line-height: 1.6;
        }

        .reveal-close {
            background: #7a1a42;
            color: #fff5f8;
            border: none;
            padding: clamp(10px, 2vw, 12px) clamp(28px, 6vw, 36px);
            border-radius: 60px;
            font-family: 'DM Serif Display', serif;
            font-size: clamp(13px, 2vw, 15px);
            letter-spacing: 1.5px;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 6px 20px rgba(120, 20, 60, 0.22);
            transition: all 0.3s;
        }

        .reveal-close:hover {
            background: #a0315e;
            box-shadow: 0 10px 25px rgba(120, 20, 60, 0.3);
        }

        @media (max-width: 768px) {
            .gp3-gifts {
                gap: clamp(15px, 4vw, 20px);
            }
        }

        @media (max-width: 480px) {
            .gp3 {
                padding: 20px;
                gap: 20px;
            }

            .gp3-gifts {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="gp3" id="gp3">
        <div class="gp3-bg"></div>
        <div class="petal-wrap" id="gpetalWrap"></div>

        <div class="gp3-corner tl"></div>
        <div class="gp3-corner tr"></div>
        <div class="gp3-corner bl"></div>
        <div class="gp3-corner br"></div>

        <span class="gp3-sparkle" style="top:10%;left:8%;animation-delay:0s">✦</span>
        <span class="gp3-sparkle" style="top:15%;right:9%;animation-delay:1.4s">✦</span>
        <span class="gp3-sparkle" style="bottom:22%;left:7%;animation-delay:2.8s">✦</span>
        <span class="gp3-sparkle" style="bottom:18%;right:8%;animation-delay:0.9s">✦</span>

        <div class="gp3-top">
            <span class="gp3-sub">✦ something special awaits ✦</span>
            <div class="gp3-title">
                Gift
                <span>for you 🎁</span>
            </div>
        </div>

        <div class="gp3-gifts">
            <div class="gp3-gift-wrap" onclick="handleGiftClick(0)">
                <div class="gp3-gift-box">
                    <svg width="110" height="125" viewBox="0 0 110 125" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="45" width="94" height="72" rx="4" fill="#f472b6" />
                        <rect x="8" y="45" width="94" height="72" rx="4" fill="none" stroke="#ec4899" stroke-width="0.5" />
                        <rect x="8" y="45" width="94" height="14" rx="4" fill="#ec4899" />
                        <rect x="46" y="45" width="18" height="72" fill="#db2777" opacity="0.6" />
                        <rect x="46" y="45" width="18" height="14" fill="#be185d" />
                        <ellipse cx="55" cy="28" rx="18" ry="10" fill="#ec4899" opacity="0.3" />
                        <path d="M38 38 Q55 10 55 38" stroke="#be185d" stroke-width="4" fill="none" stroke-linecap="round" />
                        <path d="M72 38 Q55 10 55 38" stroke="#be185d" stroke-width="4" fill="none" stroke-linecap="round" />
                        <circle cx="55" cy="38" r="5" fill="#9d174d" />
                        <circle cx="25" cy="70" r="5" fill="#db2777" opacity="0.4" />
                        <circle cx="80" cy="85" r="4" fill="#db2777" opacity="0.35" />
                        <circle cx="40" cy="95" r="3.5" fill="#db2777" opacity="0.3" />
                    </svg>
                </div>
                <span class="gp3-gift-label">tap to open</span>
            </div>

            <div class="gp3-gift-wrap" onclick="openGiftG(1)" style="transform:translateY(-16px)">
                <div class="gp3-gift-box">
                    <svg width="120" height="138" viewBox="0 0 120 138" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="50" width="104" height="80" rx="4" fill="#f9a8d4" />
                        <rect x="8" y="50" width="104" height="80" rx="4" fill="none" stroke="#f472b6" stroke-width="0.5" />
                        <rect x="8" y="50" width="104" height="16" rx="4" fill="#f472b6" />
                        <rect x="50" y="50" width="20" height="80" fill="#ec4899" opacity="0.55" />
                        <rect x="50" y="50" width="20" height="16" fill="#db2777" />
                        <path d="M40 42 Q60 8 60 42" stroke="#be185d" stroke-width="5" fill="none" stroke-linecap="round" />
                        <path d="M80 42 Q60 8 60 42" stroke="#be185d" stroke-width="5" fill="none" stroke-linecap="round" />
                        <circle cx="60" cy="42" r="6" fill="#9d174d" />
                        <circle cx="28" cy="76" r="5.5" fill="#ec4899" opacity="0.35" />
                        <circle cx="90" cy="96" r="4.5" fill="#ec4899" opacity="0.3" />
                        <circle cx="45" cy="108" r="4" fill="#ec4899" opacity="0.28" />
                    </svg>
                </div>
                <span class="gp3-gift-label">tap to open</span>
            </div>

            <div class="gp3-gift-wrap" onclick="openGiftG(2)">
                <div class="gp3-gift-box">
                    <svg width="105" height="120" viewBox="0 0 105 120" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="43" width="89" height="70" rx="4" fill="#fda4af" />
                        <rect x="8" y="43" width="89" height="70" rx="4" fill="none" stroke="#fb7185" stroke-width="0.5" />
                        <rect x="8" y="43" width="89" height="13" rx="4" fill="#fb7185" />
                        <rect x="43" y="43" width="17" height="70" fill="#f43f5e" opacity="0.55" />
                        <rect x="43" y="43" width="17" height="13" fill="#e11d48" />
                        <path d="M34 36 Q52 10 52 36" stroke="#be123c" stroke-width="4" fill="none" stroke-linecap="round" />
                        <path d="M70 36 Q52 10 52 36" stroke="#be123c" stroke-width="4" fill="none" stroke-linecap="round" />
                        <circle cx="52" cy="36" r="5" fill="#9f1239" />
                        <circle cx="24" cy="66" r="5" fill="#f43f5e" opacity="0.35" />
                        <circle cx="76" cy="82" r="4" fill="#f43f5e" opacity="0.3" />
                    </svg>
                </div>
                <span class="gp3-gift-label">tap to open</span>
            </div>
        </div>

        <div class="gp3-hint">
            <div class="gp3-hline"></div>
            <span>click any gift to open</span>
            <div class="gp3-hline"></div>
        </div>

        <div class="gift-reveal" id="gReveal">
            <div class="reveal-emoji" id="gRevealEmoji">🌹</div>
            <div class="reveal-title" id="gRevealTitle">A little surprise!</div>
            <div class="reveal-msg" id="gRevealMsg">You are the most beautiful thing that ever happened to me.</div>
            <button class="reveal-close" onclick="closeReveal('gReveal')">close ♥</button>
        </div>
    </div>

    <script>
        const gGifts = [{
                emoji: '🌹',
                title: 'A little love note',
                msg: 'You are the most beautiful thing that ever happened to me. Happy Birthday, my love.'
            },
            {
                emoji: '💌',
                title: 'From my heart',
                msg: 'Every moment with you feels like magic. Thank you for being you, always.'
            },
            {
                emoji: '✨',
                title: 'A special wish',
                msg: 'May this year bring you all the joy you deserve, my love. I love you endlessly.'
            },
        ];

        function handleGiftClick(i) {
            if (i === 0) {
                window.location.href = '/girl/gift-1/1';
            } else {
                openGiftG(i);
            }
        }

        function openGiftG(i) {
            const d = gGifts[i];
            document.getElementById('gRevealEmoji').textContent = d.emoji;
            document.getElementById('gRevealTitle').textContent = d.title;
            document.getElementById('gRevealMsg').textContent = d.msg;
            const r = document.getElementById('gReveal');
            r.classList.add('show');
        }

        function closeReveal(id) {
            document.getElementById(id).classList.remove('show');
        }

        const pw = document.getElementById('gpetalWrap');
        const pc = ['#f9a8d4', '#fda4af', '#fbcfe8', '#f472b6', '#fce7f3'];
        for (let i = 0; i < 14; i++) {
            const el = document.createElement('div');
            el.className = 'petal-g';
            const s = 6 + Math.random() * 9;
            el.style.cssText = `left:${Math.random()*100}%;top:0;width:${s}px;height:${s*1.4}px;background:${pc[i%pc.length]};transform:rotate(${Math.random()*360}deg);animation-duration:${5+Math.random()*6}s;animation-delay:${Math.random()*5}s;`;
            pw.appendChild(el);
        }
    </script>
</body>

</html>