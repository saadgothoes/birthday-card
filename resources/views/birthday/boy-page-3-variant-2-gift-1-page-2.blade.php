<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Dusty Rose · birthday card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&family=Permanent+Marker&family=Dancing+Script:wght@600&display=swap"
        rel="stylesheet">
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background: #150e12;
        font-family: 'Caveat', cursive;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPSc5MCcgaGVpZ2h0PSc5MCc+Cjx0ZXh0IHg9JzEwJyB5PSczMCcgZm9udC1zaXplPScyMicgZmlsbD0nI2Q5ODk5MCcgb3BhY2l0eT0nMC4zNScgdHJhbnNmb3JtPSdyb3RhdGUoLTEyIDEwIDMwKSc+JiMxMDA4NDs8L3RleHQ+Cjx0ZXh0IHg9JzU1JyB5PSc3MCcgZm9udC1zaXplPScxNicgZmlsbD0nI2Q5ODk5MCcgb3BhY2l0eT0nMC4yNScgdHJhbnNmb3JtPSdyb3RhdGUoMTggNTUgNzApJz4mIzEwMDg0OzwvdGV4dD4KPHRleHQgeD0nNjAnIHk9JzIwJyBmb250LXNpemU9JzEyJyBmaWxsPScjZDk4OTkwJyBvcGFjaXR5PScwLjInIHRyYW5zZm9ybT0ncm90YXRlKC0yNSA2MCAyMCknPiYjMTAwODQ7PC90ZXh0Pgo8L3N2Zz4='),
            radial-gradient(ellipse at 30% 40%, #3a1f2a 0%, #1a0e14 100%);
        background-repeat: repeat, no-repeat;
        background-size: 80px 80px, 100% 100%;
        background-attachment: fixed, fixed;
    }

    .card-outer {
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .card {
        width: 100%;
        height: 100vh;
        height: 100dvh;
        aspect-ratio: auto;
        border-radius: 0;
        max-width: 480px;
        margin: 0 auto;
        background: #f5e6e6;
        position: relative;
        overflow: hidden;
        font-family: 'Caveat', cursive;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    }

    @media (min-width: 481px) {
        .card-outer {
            padding: 24px;
        }

        .card {
            width: min(100%, 480px);
            height: auto;
            aspect-ratio: 9 / 16;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
        }
    }

    /* lips – dusty rose */
    .lips {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
    }

    .lip {
        position: absolute;
        font-size: clamp(15px, 3vw, 22px);
        line-height: 1;
        color: #b56a7a;
        opacity: 0.28;
    }

    /* hb sticker – soft rose */
    .hb-sticker {
        position: absolute;
        top: 2.5%;
        left: 50%;
        transform: translateX(-50%);
        background: #fcf0f0;
        padding: 5px 16px 7px;
        border-radius: 30px;
        z-index: 20;
        white-space: nowrap;
        border: 1px solid #dbb0b8;
        box-shadow: 0 0 14px #eac0c8;
    }

    .hb-sticker .h1 {
        font-family: 'Permanent Marker', cursive;
        font-size: clamp(12px, 2.4vw, 16px);
        display: block;
        line-height: 1;
        color: #8a3a4a;
        letter-spacing: 1px;
    }

    .hb-sticker .h2 {
        font-family: 'Permanent Marker', cursive;
        font-size: clamp(16px, 3.2vw, 22px);
        display: block;
        line-height: 1.1;
        color: #8a3a4a;
        letter-spacing: 1px;
    }

    .sp {
        font-size: 9px;
        position: absolute;
        color: #b56a7a;
    }

    /* stamp – rose */
    .stamp {
        position: absolute;
        top: 13%;
        left: 4%;
        width: 20%;
        aspect-ratio: 1 / 0.92;
        border: 2px dashed #c09098;
        border-radius: 5px;
        z-index: 8;
        overflow: hidden;
        padding: 4px;
        background: #fcf0f0;
    }

    .stamp-num {
        position: absolute;
        top: 3px;
        right: 3px;
        width: 18px;
        height: 24px;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: sans-serif;
        font-size: 7px;
        font-weight: bold;
        background: #b56a7a;
        color: #fcf0f0;
    }

    .stamp-kisses {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2px;
        padding: 4px 4px 4px 4px;
        margin-top: 2px;
    }

    .stamp-kisses span {
        font-size: clamp(10px, 2vw, 17px);
        display: block;
        color: #b56a7a;
        opacity: 0.7;
    }

    .postmark {
        position: absolute;
        bottom: 4px;
        left: 4px;
        width: 40px;
        height: 28px;
        border: 1.5px solid #b09098;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: sans-serif;
        font-size: 4.5px;
        text-align: center;
        line-height: 1.3;
        color: #8a6a72;
    }

    /* fav sticker */
    .fav {
        position: absolute;
        top: 14%;
        right: 3%;
        border-radius: 7px;
        padding: 4px 8px;
        font-size: clamp(10px, 1.8vw, 14px);
        transform: rotate(2deg);
        z-index: 12;
        white-space: nowrap;
        border: 1px solid #dbb0b8;
        line-height: 1.3;
        background: #fcf0f0;
        color: #8a3a4a;
    }

    /* polaroids – soft rose */
    .pol {
        position: absolute;
        background: #fcf6f6;
        padding: clamp(4px, 1vw, 8px) clamp(4px, 1vw, 8px) clamp(14px, 3vw, 22px);
        border-radius: 4px;
        box-shadow: 2px 6px 16px rgba(80, 40, 50, 0.2);
        border: 1px solid #e8d0d0;
    }

    .pol-inner {
        overflow: hidden;
        width: 100%;
    }

    .pol-inner svg {
        display: block;
        width: 100%;
        height: 100%;
    }

    .pol1 {
        top: 14%;
        left: 38%;
        width: 38%;
        z-index: 8;
        transform: rotate(2deg);
    }

    .pol1 .pol-inner {
        aspect-ratio: 1 / 1;
    }

    .pol2 {
        top: 38%;
        left: 2%;
        width: 40%;
        z-index: 7;
        transform: rotate(-4deg);
    }

    .pol2 .pol-inner {
        aspect-ratio: 1 / 1;
    }

    .pol3 {
        top: 58%;
        left: 30%;
        width: 36%;
        z-index: 9;
        transform: rotate(3deg);
    }

    .pol3 .pol-inner {
        aspect-ratio: 1 / 1;
    }

    /* captions – rose */
    .cap-love {
        position: absolute;
        top: 51%;
        left: 38%;
        font-size: clamp(12px, 2.4vw, 19px);
        border-radius: 5px;
        padding: 2px 10px;
        z-index: 15;
        white-space: nowrap;
        transform: rotate(1deg);
        color: #6a2a3a;
        background: rgba(252, 240, 240, 0.7);
        backdrop-filter: blur(2px);
        border: 1px solid #dbb0b8;
    }

    .cap-pb {
        position: absolute;
        top: 52%;
        right: 3%;
        font-family: 'Dancing Script', cursive;
        font-size: clamp(10px, 1.8vw, 15px);
        z-index: 12;
        font-style: italic;
        color: #8a4a5a;
    }

    /* license plate – dusty rose */
    .plate {
        position: absolute;
        right: 3%;
        top: 58%;
        border-radius: 7px;
        padding: clamp(5px, 1.2vw, 9px) clamp(6px, 1.4vw, 12px);
        z-index: 10;
        width: 32%;
        font-family: 'Permanent Marker', cursive;
        border: 3px solid #dbb0b8;
        background: #fcf0f0;
    }

    .plate-top {
        font-size: clamp(6px, 1.1vw, 9px);
        text-align: center;
        letter-spacing: 2px;
        margin-bottom: 2px;
        color: #8a5a62;
    }

    .plate-hy {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        font-size: clamp(13px, 2.6vw, 21px);
        color: #5a2a3a;
    }

    .plate-heart {
        font-size: clamp(15px, 3vw, 23px);
        color: #b56a7a;
    }

    .plate-bot {
        font-size: clamp(5px, 0.9vw, 7.5px);
        text-align: center;
        letter-spacing: 1px;
        border-top: 1px solid #dbb0b8;
        padding-top: 2px;
        margin-top: 2px;
        color: #8a5a62;
    }

    /* hb bottom */
    .hb-bot {
        position: absolute;
        bottom: 3%;
        left: 3%;
        font-family: 'Permanent Marker', cursive;
        font-size: clamp(12px, 2.4vw, 18px);
        transform: rotate(-10deg);
        line-height: 1.25;
        z-index: 12;
        color: #6a2a3a;
    }

    /* kitty – soft rose */
    .kitty {
        position: absolute;
        bottom: 2%;
        left: 24%;
        width: clamp(30px, 6vw, 46px);
        z-index: 10;
    }

    /* small hearts */
    .sm-heart {
        position: absolute;
        font-size: clamp(10px, 1.8vw, 14px);
        z-index: 10;
        color: #b56a7a;
    }

    /* next button */
    .next-btn {
        position: fixed;
        right: 18px;
        bottom: 18px;
        z-index: 999;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 999px;
        border: none;
        font-family: 'Permanent Marker', cursive;
        font-size: 14px;
        letter-spacing: 0.5px;
        cursor: pointer;
        background: #b56a7a;
        color: #fcf0f0;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
        text-decoration: none;
        transition: 0.15s;
    }

    .next-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.5);
        background: #c07a8a;
    }

    .next-btn svg {
        width: 14px;
        height: 14px;
    }

    /* =========================================================
       CARD PEEK — click a polaroid to zoom it in over a blurred
       backdrop. Hover stays a small lift only; nothing zooms or
       blurs until the item is actually clicked.
       ========================================================= */

    .pol {
        cursor: pointer;
        transition: transform .3s ease, box-shadow .3s ease;
    }

    .pol:hover,
    .pol:focus-visible {
        box-shadow: 3px 6px 16px rgba(0, 0, 0, 0.4);
    }

    .peekbox {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, .78);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        opacity: 0;
        pointer-events: none;
        transition: opacity .35s ease;
        padding: 8vw;
    }

    .peekbox.open {
        opacity: 1;
        pointer-events: auto;
    }

    .peek-inner {
        transform: scale(.85);
        transition: transform .35s ease;
    }

    .peekbox.open .peek-inner {
        transform: scale(1);
    }

    .peek-inner .peek-clone {
        position: static !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        bottom: auto !important;
        transform: none !important;
        margin: 0 !important;
        width: min(80vw, 360px) !important;
        pointer-events: none;
    }

    .peekbox-close {
        position: absolute;
        top: 5vh;
        right: 6vw;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, .4);
        background: rgba(255, 255, 255, .1);
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background .25s ease, transform .2s ease;
    }

    .peekbox-close:hover {
        background: rgba(255, 255, 255, .22);
    }

    .peekbox-close:active {
        transform: scale(.9);
    }
    </style>

</head>

<body>
    <div class="card-outer">
        <div class="card">
            <!-- lips -->
            <div class="lips">
                <span class="lip" style="top:3%;left:6%;transform:rotate(16deg)">💋</span>
                <span class="lip" style="top:0%;left:36%;transform:rotate(-9deg)">💋</span>
                <span class="lip" style="top:5%;right:9%;transform:rotate(21deg)">💋</span>
                <span class="lip" style="top:11%;left:16%;transform:rotate(-31deg)">💋</span>
                <span class="lip" style="top:8%;right:26%;transform:rotate(36deg)">💋</span>
                <span class="lip" style="top:18%;left:3%;transform:rotate(-13deg)">💋</span>
                <span class="lip" style="top:16%;right:4%;transform:rotate(9deg)">💋</span>
                <span class="lip" style="top:23%;right:13%;transform:rotate(-23deg)">💋</span>
                <span class="lip" style="top:28%;left:9%;transform:rotate(19deg)">💋</span>
                <span class="lip" style="top:32%;left:41%;transform:rotate(-6deg)">💋</span>
                <span class="lip" style="top:37%;right:19%;transform:rotate(29deg)">💋</span>
                <span class="lip" style="top:42%;left:16%;transform:rotate(-21deg)">💋</span>
                <span class="lip" style="top:47%;right:8%;transform:rotate(11deg)">💋</span>
                <span class="lip" style="top:52%;left:49%;transform:rotate(-15deg)">💋</span>
                <span class="lip" style="top:56%;left:4%;transform:rotate(27deg)">💋</span>
                <span class="lip" style="top:62%;right:5%;transform:rotate(-19deg)">💋</span>
                <span class="lip" style="top:66%;left:59%;transform:rotate(33deg)">💋</span>
                <span class="lip" style="top:70%;left:23%;transform:rotate(-39deg)">💋</span>
                <span class="lip" style="top:74%;right:25%;transform:rotate(17deg)">💋</span>
                <span class="lip" style="top:78%;left:33%;transform:rotate(-5deg)">💋</span>
                <span class="lip" style="top:82%;left:9%;transform:rotate(25deg)">💋</span>
                <span class="lip" style="top:86%;right:13%;transform:rotate(-17deg)">💋</span>
                <span class="lip" style="top:90%;left:45%;transform:rotate(7deg)">💋</span>
                <span class="lip" style="top:93%;left:63%;transform:rotate(-25deg)">💋</span>
                <span class="lip" style="top:7%;left:63%;transform:rotate(19deg)">💋</span>
                <span class="lip" style="top:20%;left:51%;transform:rotate(-31deg)">💋</span>
                <span class="lip" style="top:39%;left:68%;transform:rotate(23deg)">💋</span>
                <span class="lip" style="top:55%;left:29%;transform:rotate(-9deg)">💋</span>
                <span class="lip" style="top:72%;left:73%;transform:rotate(15deg)">💋</span>
                <span class="lip" style="top:96%;left:19%;transform:rotate(-21deg)">💋</span>
            </div>

            <!-- sticker -->
            <div class="hb-sticker">
                <span class="sp" style="top:5px;left:6px;">✦</span>
                <span class="sp" style="top:5px;right:6px;">✦</span>
                <span class="h1">HAPPY</span><span class="h2">birthday</span>
            </div>

            <!-- stamp -->
            <div class="stamp">
                <div class="stamp-num">60</div>
                <div class="stamp-kisses">
                    <span style="transform:rotate(-6deg)">💋</span><span style="transform:rotate(10deg)">💋</span><span
                        style="transform:rotate(-4deg)">💋</span>
                    <span style="transform:rotate(8deg)">💋</span><span style="transform:rotate(-12deg)">💋</span><span
                        style="transform:rotate(5deg)">💋</span>
                </div>
                <div class="postmark">29.V.45<br />PRAGUE 2</div>
            </div>

            <!-- fav -->
            <div class="fav">Favorite<br />Person →</div>

            <!-- polaroids (soft rose) -->
            <div class="pol pol1">
                <div class="pol-inner">
                    @if(request('photo1'))
                    <img src="{{ request('photo1') }}" alt=""
                        style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                    <svg viewBox="0 0 118 118" xmlns="http://www.w3.org/2000/svg">
                        <rect fill="#1a0f0a" height="118" width="118" />
                        <circle cx="25" cy="18" fill="#d98a90" opacity="0.2" r="10" />
                        <ellipse cx="38" cy="44" fill="#c8a882" rx="8" ry="9" />
                        <ellipse cx="38" cy="37" fill="#1a1008" rx="8" ry="6" />
                        <rect fill="#4a3525" height="32" rx="4" width="18" x="29" y="53" />
                        <rect fill="#4a3525" height="22" rx="3" width="7" x="25" y="55" />
                        <rect fill="#4a3525" height="22" rx="3" width="7" x="41" y="55" />
                        <rect fill="#6a4a30" height="18" rx="3" width="7" x="31" y="85" />
                        <rect fill="#6a4a30" height="18" rx="3" width="7" x="39" y="85" />
                        <ellipse cx="80" cy="44" fill="#c8a882" rx="8" ry="9" />
                        <ellipse cx="80" cy="37" fill="#1a1008" rx="8" ry="6" />
                        <rect fill="#d4c8b8" height="32" rx="4" width="18" x="71" y="53" />
                        <rect fill="#d4c8b8" height="22" rx="3" width="7" x="67" y="55" />
                        <rect fill="#d4c8b8" height="22" rx="3" width="7" x="83" y="55" />
                        <rect fill="#8B6B4A" height="18" rx="3" width="7" x="73" y="85" />
                        <rect fill="#8B6B4A" height="18" rx="3" width="7" x="81" y="85" />
                        <ellipse cx="59" cy="66" fill="#c8a882" opacity="0.8" rx="5" ry="4" />
                    </svg>
                    @endif
                </div>
            </div>

            <div class="pol pol2">
                <div class="pol-inner">
                    @if(request('photo2'))
                    <img src="{{ request('photo2') }}" alt=""
                        style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                    <svg viewBox="0 0 124 124" xmlns="http://www.w3.org/2000/svg">
                        <rect fill="#2a1a0e" height="124" width="124" />
                        <circle cx="95" cy="25" fill="#d98a90" opacity="0.25" r="16" />
                        <rect fill="#1a0d06" height="32" width="124" x="0" y="92" />
                        <ellipse cx="62" cy="47" fill="#c8a882" rx="11" ry="12" />
                        <ellipse cx="62" cy="38" fill="#1a1008" rx="11" ry="7" />
                        <ellipse cx="52" cy="43" fill="#1a1008" rx="5" ry="8" />
                        <ellipse cx="72" cy="43" fill="#1a1008" rx="5" ry="8" />
                        <rect fill="#e8e4de" height="36" rx="7" width="32" x="46" y="59" />
                        <ellipse cx="40" cy="80" fill="#c8a882" rx="6" ry="4" />
                        <rect fill="#4a3020" height="18" rx="3" width="9" x="52" y="95" />
                        <rect fill="#4a3020" height="18" rx="3" width="9" x="63" y="95" />
                    </svg>
                    @endif
                </div>
            </div>

            <div class="pol pol3">
                <div class="pol-inner">
                    @if(request('photo3'))
                    <img src="{{ request('photo3') }}" alt=""
                        style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                    <svg viewBox="0 0 108 108" xmlns="http://www.w3.org/2000/svg">
                        <rect fill="#1e1408" height="108" width="108" />
                        <circle cx="54" cy="20" fill="#d98a90" opacity="0.15" r="22" />
                        <ellipse cx="54" cy="46" fill="#c8a882" rx="13" ry="14" />
                        <ellipse cx="54" cy="36" fill="#1a1008" rx="13" ry="8" />
                        <ellipse cx="43" cy="40" fill="#1a1008" rx="5" ry="9" />
                        <ellipse cx="65" cy="40" fill="#1a1008" rx="5" ry="9" />
                        <circle cx="48" cy="47" fill="#333" r="2" />
                        <circle cx="60" cy="47" fill="#333" r="2" />
                        <rect fill="#e0dcd6" height="36" rx="7" width="30" x="39" y="60" />
                        <ellipse cx="41" cy="80" fill="#c8a882" rx="6" ry="4" />
                        <rect fill="#4a3020" height="10" rx="3" width="8" x="42" y="96" />
                        <rect fill="#4a3020" height="10" rx="3" width="8" x="58" y="96" />
                    </svg>
                    @endif
                </div>
            </div>

            <!-- captions -->
            <div class="cap-love">i love you &lt;3</div>
            <div class="cap-pb">"o, pretty boy,"</div>

            <!-- plate -->
            <div class="plate">
                <div class="plate-top">SO SPECIAL</div>
                <div class="plate-hy"><span class="plate-heart">♥</span><span>YOU</span></div>
                <div class="plate-bot">LIVE · LAUGH · LOVE</div>
            </div>

            <!-- hb bottom -->
            <div class="hb-bot">HAppY<br />BiRtHdAy<br /><span style="font-size:0.7em">&lt;3</span></div>

            <!-- kitty (soft rose) -->
            <svg class="kitty" viewBox="0 0 38 44" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="19" cy="28" fill="#fcf0f0" rx="15" ry="15" stroke="#dbb0b8" stroke-width="1" />
                <polygon fill="#b56a7a" points="11,11 18,15 11,19" />
                <polygon fill="#b56a7a" points="27,11 20,15 27,19" />
                <ellipse cx="19" cy="14" fill="#b56a7a" rx="4" ry="3" />
                <circle cx="14" cy="26" fill="#5a2a3a" r="2" />
                <circle cx="24" cy="26" fill="#5a2a3a" r="2" />
                <ellipse cx="19" cy="31" fill="#d98a90" rx="2" ry="1.5" />
                <line stroke="#b09098" stroke-width="0.8" x1="4" x2="13" y1="27" y2="29" />
                <line stroke="#b09098" stroke-width="0.8" x1="4" x2="13" y1="31" y2="31" />
                <line stroke="#b09098" stroke-width="0.8" x1="25" x2="34" y1="29" y2="27" />
                <line stroke="#b09098" stroke-width="0.8" x1="25" x2="34" y1="31" y2="31" />
            </svg>

            <span class="sm-heart" style="bottom:8%;left:46%">♥</span>
            <span class="sm-heart" style="bottom:6%;left:52%">♥</span>
        </div>
    </div>

    <a class="next-btn" href="card3_dark_espresso.html">
        Next
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </a>

    <!-- Card peek overlay (click a polaroid to zoom) -->
    <div class="peekbox" id="peekbox">
        <button class="peekbox-close" id="peekboxClose" aria-label="Close">✕</button>
        <div class="peek-inner" id="peekInner"></div>
    </div>

    <script>
    (function() {
        'use strict';

        var peekbox = document.getElementById('peekbox');
        var peekInner = document.getElementById('peekInner');
        var peekboxClose = document.getElementById('peekboxClose');
        var peekTargets = document.querySelectorAll('.pol');

        function openPeek(el) {
            var clone = el.cloneNode(true);
            clone.classList.add('peek-clone');
            peekInner.innerHTML = '';
            peekInner.appendChild(clone);
            peekbox.classList.add('open');
        }

        function closePeek() {
            peekbox.classList.remove('open');
        }

        peekTargets.forEach(function(el) {
            el.setAttribute('tabindex', '0');
            el.addEventListener('click', function() {
                openPeek(el);
            });
            el.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openPeek(el);
                }
            });
        });

        peekboxClose.addEventListener('click', closePeek);
        peekbox.addEventListener('click', function(e) {
            if (e.target === peekbox) closePeek();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePeek();
        });
    })();
    </script>
</body>


</html>