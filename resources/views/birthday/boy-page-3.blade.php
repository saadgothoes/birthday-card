<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Card - Boy Theme - Page 3</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Abril+Fatface&family=DM+Serif+Display:ital@0;1&family=Bebas+Neue&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            font-family: 'Bebas Neue', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0f0f 50%, #15080c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
        }

        .bp3 {
            min-height: 500px;
            background: linear-gradient(135deg, rgba(20, 10, 10, 0.95), rgba(30, 15, 15, 0.95));
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
            box-shadow: 0 0 60px rgba(200, 146, 42, 0.15), inset 0 0 40px rgba(200, 146, 42, 0.05);
        }

        .bp3-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 0%, rgba(200, 120, 60, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 100%, rgba(180, 100, 40, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(220, 140, 60, 0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .bp3-corner {
            position: absolute;
            width: 44px;
            height: 44px;
            opacity: 0.12;
        }

        .bp3-corner.tl {
            top: 14px;
            left: 14px;
            border-top: 1.5px solid #c8922a;
            border-left: 1.5px solid #c8922a;
        }

        .bp3-corner.tr {
            top: 14px;
            right: 14px;
            border-top: 1.5px solid #c8922a;
            border-right: 1.5px solid #c8922a;
        }

        .bp3-corner.bl {
            bottom: 14px;
            left: 14px;
            border-bottom: 1.5px solid #c8922a;
            border-left: 1.5px solid #c8922a;
        }

        .bp3-corner.br {
            bottom: 14px;
            right: 14px;
            border-bottom: 1.5px solid #c8922a;
            border-right: 1.5px solid #c8922a;
        }

        .bp3-top {
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .bp3-sub {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(10px, 1.8vw, 12px);
            color: #a07040;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .bp3-title {
            font-family: 'Abril Fatface', serif;
            font-size: clamp(36px, 8vw, 52px);
            color: #d4a850;
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .bp3-title span {
            font-family: 'DM Serif Display', serif;
            font-style: italic;
            font-size: clamp(28px, 6vw, 40px);
            color: #c8922a;
            display: block;
        }

        .bp3-gifts {
            display: flex;
            gap: clamp(20px, 5vw, 28px);
            align-items: flex-end;
            justify-content: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .bp3-gift-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            position: relative;
        }

        .bp3-gift-box {
            position: relative;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: drop-shadow(0 8px 20px rgba(200, 100, 40, 0.25));
        }

        .bp3-gift-wrap:hover .bp3-gift-box {
            transform: translateY(-10px) scale(1.06);
            filter: drop-shadow(0 16px 30px rgba(200, 100, 40, 0.4));
        }

        .bp3-gift-wrap.opened .bp3-gift-box {
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

        .bp3-gift-label {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(9px, 1.4vw, 11px);
            color: rgba(200, 120, 60, 0.5);
            letter-spacing: 1px;
            opacity: 0;
            transform: translateY(4px);
            transition: all 0.3s;
            text-transform: uppercase;
        }

        .bp3-gift-wrap:hover .bp3-gift-label {
            opacity: 1;
            transform: translateY(0);
        }

        .bp3-hint {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(13px, 1.8vw, 15px);
            color: rgba(200, 120, 60, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
            text-transform: uppercase;
        }

        .bp3-hline {
            width: 50px;
            height: 0.5px;
            background: rgba(200, 120, 60, 0.2);
        }

        .bp3-sparkle {
            position: absolute;
            color: #d4a850;
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
                opacity: 0.4;
                transform: scale(1)
            }
        }

        .ember-wrap {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .ember-g {
            position: absolute;
            opacity: 0;
            border-radius: 50%;
            animation: emberise linear infinite;
        }

        @keyframes emberise {
            0% {
                opacity: 0;
                transform: translateY(0) scale(1)
            }

            50% {
                opacity: 0.7
            }

            95% {
                opacity: 0.3
            }

            100% {
                opacity: 0;
                transform: translateY(-350px) scale(0);
            }
        }

        .gift-reveal {
            position: fixed;
            inset: 0;
            background: rgba(60, 30, 30, 0.95);
            backdrop-filter: blur(12px);
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
            filter: drop-shadow(0 4px 12px rgba(200, 100, 40, 0.4));
        }

        .reveal-title {
            font-family: 'Abril Fatface', serif;
            font-size: clamp(26px, 6vw, 36px);
            color: #d4a850;
        }

        .reveal-msg {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(14px, 2.5vw, 18px);
            color: #b8882a;
            line-height: 1.6;
            letter-spacing: 0.5px;
        }

        .reveal-close {
            background: #2d1200;
            color: #d4a850;
            border: 1.5px solid #c8922a;
            padding: clamp(10px, 2vw, 12px) clamp(28px, 6vw, 36px);
            border-radius: 60px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(13px, 2vw, 15px);
            letter-spacing: 1.5px;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 6px 20px rgba(200, 100, 40, 0.2);
            transition: all 0.3s;
            text-transform: uppercase;
        }

        .reveal-close:hover {
            background: #3d1a00;
            box-shadow: 0 10px 25px rgba(200, 100, 40, 0.3);
            color: #e8b840;
        }

        @media (max-width: 768px) {
            .bp3-gifts {
                gap: clamp(15px, 4vw, 20px);
            }
        }

        @media (max-width: 480px) {
            .bp3 {
                padding: 20px;
                gap: 20px;
            }

            .bp3-gifts {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="bp3" id="bp3">
        <div class="bp3-bg"></div>
        <div class="ember-wrap" id="bemberWrap"></div>

        <div class="bp3-corner tl"></div>
        <div class="bp3-corner tr"></div>
        <div class="bp3-corner bl"></div>
        <div class="bp3-corner br"></div>

        <span class="bp3-sparkle" style="top:10%;left:8%;animation-delay:0s">✦</span>
        <span class="bp3-sparkle" style="top:15%;right:9%;animation-delay:1.4s">✦</span>
        <span class="bp3-sparkle" style="bottom:22%;left:7%;animation-delay:2.8s">✦</span>
        <span class="bp3-sparkle" style="bottom:18%;right:8%;animation-delay:0.9s">✦</span>

        <div class="bp3-top">
            <span class="bp3-sub">✦ YOURS TO DISCOVER ✦</span>
            <div class="bp3-title">
                GIFTS
                <span>for you 🎁</span>
            </div>
        </div>

        <div class="bp3-gifts">
            <div class="bp3-gift-wrap" onclick="handleGiftClick(0)">
                <div class="bp3-gift-box">
                    <svg width="110" height="125" viewBox="0 0 110 125" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="45" width="94" height="72" rx="4" fill="#2d1200" stroke="#c8922a" stroke-width="2" />
                        <rect x="8" y="45" width="94" height="14" rx="4" fill="#3d1a00" stroke="#d4a850" stroke-width="1.5" />
                        <rect x="46" y="45" width="18" height="72" fill="#c8922a" opacity="0.5" />
                        <rect x="46" y="45" width="18" height="14" fill="#d4a850" />
                        <ellipse cx="55" cy="28" rx="18" ry="10" fill="#d4a850" opacity="0.4" />
                        <path d="M38 38 Q55 8 55 38" stroke="#a07040" stroke-width="4" fill="none" stroke-linecap="round" />
                        <path d="M72 38 Q55 8 55 38" stroke="#a07040" stroke-width="4" fill="none" stroke-linecap="round" />
                        <circle cx="55" cy="38" r="5" fill="#c8922a" />
                        <circle cx="25" cy="70" r="5" fill="#c8922a" opacity="0.3" />
                        <circle cx="80" cy="85" r="4" fill="#c8922a" opacity="0.25" />
                        <circle cx="40" cy="95" r="3.5" fill="#c8922a" opacity="0.2" />
                    </svg>
                </div>
                <span class="bp3-gift-label">TAP TO OPEN</span>
            </div>

            <div class="bp3-gift-wrap" onclick="openGiftB(1)" style="transform:translateY(-16px)">
                <div class="bp3-gift-box">
                    <svg width="120" height="138" viewBox="0 0 120 138" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="50" width="104" height="80" rx="4" fill="#2d1200" stroke="#c8922a" stroke-width="2" />
                        <rect x="8" y="50" width="104" height="16" rx="4" fill="#3d1a00" stroke="#d4a850" stroke-width="1.5" />
                        <rect x="50" y="50" width="20" height="80" fill="#c8922a" opacity="0.5" />
                        <rect x="50" y="50" width="20" height="16" fill="#d4a850" />
                        <path d="M40 42 Q60 8 60 42" stroke="#a07040" stroke-width="5" fill="none" stroke-linecap="round" />
                        <path d="M80 42 Q60 8 60 42" stroke="#a07040" stroke-width="5" fill="none" stroke-linecap="round" />
                        <circle cx="60" cy="42" r="6" fill="#c8922a" />
                        <circle cx="28" cy="76" r="5.5" fill="#c8922a" opacity="0.3" />
                        <circle cx="90" cy="96" r="4.5" fill="#c8922a" opacity="0.25" />
                        <circle cx="45" cy="108" r="4" fill="#c8922a" opacity="0.2" />
                    </svg>
                </div>
                <span class="bp3-gift-label">TAP TO OPEN</span>
            </div>

            <div class="bp3-gift-wrap" onclick="openGiftB(2)">
                <div class="bp3-gift-box">
                    <svg width="105" height="120" viewBox="0 0 105 120" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="43" width="89" height="70" rx="4" fill="#2d1200" stroke="#c8922a" stroke-width="2" />
                        <rect x="8" y="43" width="89" height="13" rx="4" fill="#3d1a00" stroke="#d4a850" stroke-width="1.5" />
                        <rect x="43" y="43" width="17" height="70" fill="#c8922a" opacity="0.5" />
                        <rect x="43" y="43" width="17" height="13" fill="#d4a850" />
                        <path d="M34 36 Q52 8 52 36" stroke="#a07040" stroke-width="4" fill="none" stroke-linecap="round" />
                        <path d="M70 36 Q52 8 52 36" stroke="#a07040" stroke-width="4" fill="none" stroke-linecap="round" />
                        <circle cx="52" cy="36" r="5" fill="#c8922a" />
                        <circle cx="24" cy="66" r="5" fill="#c8922a" opacity="0.3" />
                        <circle cx="76" cy="82" r="4" fill="#c8922a" opacity="0.25" />
                    </svg>
                </div>
                <span class="bp3-gift-label">TAP TO OPEN</span>
            </div>
        </div>

        <div class="bp3-hint">
            <div class="bp3-hline"></div>
            <span>CLICK ANY GIFT TO OPEN</span>
            <div class="bp3-hline"></div>
        </div>

        <div class="gift-reveal" id="bReveal">
            <div class="reveal-emoji" id="bRevealEmoji">👑</div>
            <div class="reveal-title" id="bRevealTitle">MY KING</div>
            <div class="reveal-msg" id="bRevealMsg">You are my greatest treasure.</div>
            <button class="reveal-close" onclick="closeReveal('bReveal')">close</button>
        </div>
    </div>

    <script>
        const bGifts = [{
                emoji: '👑',
                title: 'MY KING',
                msg: 'You are my greatest treasure. Every moment with you is a victory.'
            },
            {
                emoji: '⚔️',
                title: 'MY WARRIOR',
                msg: 'Strong, fierce, and utterly mine. Thank you for being incredible.'
            },
            {
                emoji: '✨',
                title: 'MY LOVE',
                msg: 'In you, I found my forever. Happy Birthday to the man of my dreams.'
            },
        ];

        function handleGiftClick(i) {
            if (i === 0) {
                window.location.href = '/boy/gift-1/1';
            } else {
                openGiftB(i);
            }
        }

        function openGiftB(i) {
            const d = bGifts[i];
            document.getElementById('bRevealEmoji').textContent = d.emoji;
            document.getElementById('bRevealTitle').textContent = d.title;
            document.getElementById('bRevealMsg').textContent = d.msg;
            const r = document.getElementById('bReveal');
            r.classList.add('show');
        }

        function closeReveal(id) {
            document.getElementById(id).classList.remove('show');
        }

        const ew = document.getElementById('bemberWrap');
        const ec = ['#d4a850', '#e8b840', '#c8922a', '#f0d070', '#b8882a'];
        for (let i = 0; i < 12; i++) {
            const el = document.createElement('div');
            el.className = 'ember-g';
            const s = 4 + Math.random() * 8;
            const dur = 3 + Math.random() * 4;
            const del = Math.random() * 2;
            el.style.cssText = `left:${Math.random()*100}%;bottom:0;width:${s}px;height:${s}px;background:${ec[i%ec.length]};border-radius:50%;animation-duration:${dur}s;animation-delay:${del}s;box-shadow:0 0 ${s+2}px ${ec[i%ec.length]};`;
            ew.appendChild(el);
        }
    </script>
</body>

</html>