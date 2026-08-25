<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Dark — Espresso Night — Happy Birthday Card</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&amp;family=Permanent+Marker&amp;family=Dancing+Script:wght@600&amp;display=swap"
        rel="stylesheet" />
    <style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background: #111;
        font-family: 'Caveat', cursive;
        padding: 20px;
        min-height: 100vh;
    }

    h1.page-title {
        font-family: 'Permanent Marker', cursive;
        color: #f0d0a0;
        text-align: center;
        font-size: clamp(18px, 4vw, 28px);
        margin-bottom: 24px;
        letter-spacing: 2px;
    }

    /* ── GRID ── */
    .grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        max-width: 1000px;
        margin: 0 auto;
    }

    @media (max-width: 640px) {
        .grid {
            grid-template-columns: 1fr;
        }

        body {
            padding: 12px;
        }
    }

    .variant-wrap {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .v-label {
        font-family: 'Caveat', cursive;
        font-size: 13px;
        letter-spacing: 1px;
        text-align: center;
        color: #a09080;
    }

    /* ── CARD BASE ── */
    .card {
        position: relative;
        width: 100%;
        aspect-ratio: 9 / 16;
        border-radius: 14px;
        overflow: hidden;
        font-family: 'Caveat', cursive;
    }

    /* themes */
    .lt1 {
        background: #e8dcc8;
    }

    .lt2 {
        background: #f0e0d0;
    }

    .dk1 {
        background: #1e120a;
    }

    .dk2 {
        background: #2a1520;
    }

    /* ── LIP MARKS ── */
    .lips {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
    }

    .lip {
        position: absolute;
        font-size: clamp(16px, 3.5vw, 24px);
        line-height: 1;
    }

    .lt1 .lip,
    .lt2 .lip {
        color: #8a1a14;
        opacity: 0.32;
    }

    .dk1 .lip {
        color: #c0332a;
        opacity: 0.28;
    }

    .dk2 .lip {
        color: #d4507a;
        opacity: 0.30;
    }

    /* ── HB STICKER ── */
    .hb-sticker {
        position: absolute;
        top: 2.5%;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 20px;
        padding: 5px 16px 7px;
        text-align: center;
        z-index: 20;
        white-space: nowrap;
    }

    .lt1 .hb-sticker,
    .lt2 .hb-sticker {
        background: #fff;
    }

    .dk1 .hb-sticker {
        background: #2e1e0e;
    }

    .dk2 .hb-sticker {
        background: #2a1020;
    }

    .hb-sticker .h1 {
        font-family: 'Permanent Marker', cursive;
        font-size: clamp(11px, 2.2vw, 15px);
        display: block;
        line-height: 1;
    }

    .hb-sticker .h2 {
        font-family: 'Permanent Marker', cursive;
        font-size: clamp(15px, 3vw, 21px);
        display: block;
        line-height: 1.1;
    }

    .lt1 .hb-sticker .h1,
    .lt1 .hb-sticker .h2,
    .lt2 .hb-sticker .h1,
    .lt2 .hb-sticker .h2 {
        color: #111;
    }

    .dk1 .hb-sticker .h1,
    .dk1 .hb-sticker .h2 {
        color: #f0d090;
    }

    .dk2 .hb-sticker .h1,
    .dk2 .hb-sticker .h2 {
        color: #f0b0d0;
    }

    .sp {
        font-size: 9px;
        position: absolute;
    }

    /* ── STAMP ── */
    .stamp {
        position: absolute;
        top: 13%;
        left: 4%;
        width: 20%;
        aspect-ratio: 1 / 0.92;
        border: 2px dashed;
        border-radius: 5px;
        transform: rotate(-6deg);
        z-index: 8;
        overflow: hidden;
        padding: 4px;
    }

    .lt1 .stamp {
        background: #f5ede0;
        border-color: #b09070;
    }

    .lt2 .stamp {
        background: #fce8e0;
        border-color: #c09090;
    }

    .dk1 .stamp {
        background: #2e1e10;
        border-color: #5a4030;
    }

    .dk2 .stamp {
        background: #2e1a20;
        border-color: #5a3040;
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
    }

    .lt1 .stamp-num,
    .lt2 .stamp-num {
        background: #3a6b9e;
        color: #fff;
    }

    .dk1 .stamp-num {
        background: #5a3a1e;
        color: #f0c080;
    }

    .dk2 .stamp-num {
        background: #5a2038;
        color: #f0b0d0;
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
    }

    .lt1 .stamp-kisses span,
    .lt2 .stamp-kisses span {
        color: #c0332a;
        opacity: 0.85;
    }

    .dk1 .stamp-kisses span {
        color: #e05030;
        opacity: 0.9;
    }

    .dk2 .stamp-kisses span {
        color: #d04070;
        opacity: 0.9;
    }

    .postmark {
        position: absolute;
        bottom: 4px;
        left: 4px;
        width: 40px;
        height: 28px;
        border: 1.5px solid;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: sans-serif;
        font-size: 4.5px;
        text-align: center;
        line-height: 1.3;
    }

    .lt1 .postmark,
    .lt2 .postmark {
        border-color: #8a7060;
        color: #8a7060;
    }

    .dk1 .postmark {
        border-color: #806040;
        color: #906040;
    }

    .dk2 .postmark {
        border-color: #705060;
        color: #906070;
    }

    /* ── FAV STICKER ── */
    .fav {
        position: absolute;
        top: 14%;
        right: 3%;
        border-radius: 7px;
        padding: 4px 8px;
        font-size: clamp(10px, 1.8vw, 14px);
        transform: rotate(3deg);
        z-index: 12;
        white-space: nowrap;
        border: 1px solid;
        line-height: 1.3;
    }

    .lt1 .fav {
        background: #fff8e0;
        border-color: #e0d0a0;
        color: #333;
    }

    .lt2 .fav {
        background: #fce8e8;
        border-color: #d09090;
        color: #8a2030;
    }

    .dk1 .fav {
        background: #2a1e0a;
        border-color: #6a5020;
        color: #f0d090;
    }

    .dk2 .fav {
        background: #2a1220;
        border-color: #6a3050;
        color: #f0b0d0;
    }

    /* ── POLAROIDS ── */
    .pol {
        position: absolute;
        background: #fff;
        padding: clamp(4px, 1vw, 8px) clamp(4px, 1vw, 8px) clamp(14px, 3vw, 22px);
        border-radius: 2px;
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.28);
    }

    .dk1 .pol,
    .dk2 .pol {
        background: #f5ede0;
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

    /* pol 1 - couple - top right area */
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

    /* pol 2 - restaurant - middle left */
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

    /* pol 3 - solo - middle-lower */
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

    /* ── CAPTIONS ── */
    .cap-love {
        position: absolute;
        top: 51%;
        left: 38%;
        font-size: clamp(11px, 2.2vw, 18px);
        border-radius: 5px;
        padding: 2px 8px;
        z-index: 15;
        white-space: nowrap;
        transform: rotate(1deg);
    }

    .lt1 .cap-love {
        color: #111;
        background: #fff;
    }

    .lt2 .cap-love {
        color: #8a1a30;
        background: #fce8e0;
    }

    .dk1 .cap-love {
        color: #f0d0a0;
        background: rgba(0, 0, 0, 0.55);
    }

    .dk2 .cap-love {
        color: #f0c0d8;
        background: rgba(0, 0, 0, 0.55);
    }

    .cap-pb {
        position: absolute;
        top: 52%;
        right: 3%;
        font-family: 'Dancing Script', cursive;
        font-size: clamp(10px, 1.8vw, 15px);
        z-index: 12;
        font-style: italic;
    }

    .lt1 .cap-pb {
        color: #5a2010;
    }

    .lt2 .cap-pb {
        color: #8a2030;
    }

    .dk1 .cap-pb {
        color: #f0a080;
    }

    .dk2 .cap-pb {
        color: #f0b0c8;
    }

    /* ── LICENSE PLATE ── */
    .plate {
        position: absolute;
        right: 3%;
        top: 58%;
        border-radius: 7px;
        padding: clamp(5px, 1.2vw, 9px) clamp(6px, 1.4vw, 12px);
        z-index: 10;
        width: 32%;
        font-family: 'Permanent Marker', cursive;
        border: 3px solid;
    }

    .lt1 .plate {
        background: #f5f0e8;
        border-color: #b0a88a;
    }

    .lt2 .plate {
        background: #fce8e8;
        border-color: #c09090;
    }

    .dk1 .plate {
        background: #2a1a08;
        border-color: #6a5030;
    }

    .dk2 .plate {
        background: #2a1018;
        border-color: #6a3048;
    }

    .plate-top {
        font-size: clamp(6px, 1.1vw, 9px);
        text-align: center;
        letter-spacing: 2px;
        margin-bottom: 2px;
    }

    .lt1 .plate-top,
    .lt2 .plate-top {
        color: #555;
    }

    .dk1 .plate-top {
        color: #c09060;
    }

    .dk2 .plate-top {
        color: #c080a0;
    }

    .plate-hy {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        font-size: clamp(12px, 2.4vw, 20px);
    }

    .lt1 .plate-hy,
    .lt2 .plate-hy {
        color: #1a1a1a;
    }

    .dk1 .plate-hy,
    .dk2 .plate-hy {
        color: #f0e0c0;
    }

    .plate-heart {
        font-size: clamp(14px, 2.8vw, 22px);
    }

    .lt1 .plate-heart,
    .lt2 .plate-heart {
        color: #c0332a;
    }

    .dk1 .plate-heart {
        color: #c0332a;
    }

    .dk2 .plate-heart {
        color: #d4507a;
    }

    .plate-bot {
        font-size: clamp(5px, 0.9vw, 7.5px);
        text-align: center;
        letter-spacing: 1px;
        border-top: 1px solid;
        padding-top: 2px;
        margin-top: 2px;
    }

    .lt1 .plate-bot {
        color: #888;
        border-color: #ccc;
    }

    .lt2 .plate-bot {
        color: #b08080;
        border-color: #d0a0a0;
    }

    .dk1 .plate-bot {
        color: #907050;
        border-color: #503820;
    }

    .dk2 .plate-bot {
        color: #905070;
        border-color: #502030;
    }

    /* ── HB BOTTOM ── */
    .hb-bot {
        position: absolute;
        bottom: 3%;
        left: 3%;
        font-family: 'Permanent Marker', cursive;
        font-size: clamp(11px, 2.2vw, 17px);
        transform: rotate(-10deg);
        line-height: 1.25;
        z-index: 12;
    }

    .lt1 .hb-bot {
        color: #5a2010;
    }

    .lt2 .hb-bot {
        color: #8a1a30;
    }

    .dk1 .hb-bot {
        color: #f0c090;
    }

    .dk2 .hb-bot {
        color: #f0a0c0;
    }

    /* ── HELLO KITTY ── */
    .kitty {
        position: absolute;
        bottom: 2%;
        left: 24%;
        width: clamp(28px, 6vw, 44px);
        z-index: 10;
    }

    /* ── HEARTS ── */
    .sm-heart {
        position: absolute;
        font-size: clamp(9px, 1.6vw, 13px);
        z-index: 10;
    }

    .lt1 .sm-heart,
    .lt2 .sm-heart {
        color: #c0332a;
    }

    .lt2 .sm-heart {
        color: #8a1a30;
    }

    .dk1 .sm-heart,
    .dk2 .sm-heart {
        color: #e05070;
    }


    html,
    body {
        height: 100%;
    }

    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPSc5MCcgaGVpZ2h0PSc5MCc+Cjx0ZXh0IHg9JzEwJyB5PSczMCcgZm9udC1zaXplPScyMicgZmlsbD0nI2EwNzAzYycgb3BhY2l0eT0nMC41JyB0cmFuc2Zvcm09J3JvdGF0ZSgtMTIgMTAgMzApJz4mIzEwMDg0OzwvdGV4dD4KPHRleHQgeD0nNTUnIHk9JzcwJyBmb250LXNpemU9JzE2JyBmaWxsPScjYTA3MDNjJyBvcGFjaXR5PScwLjM1JyB0cmFuc2Zvcm09J3JvdGF0ZSgxOCA1NSA3MCknPiYjMTAwODQ7PC90ZXh0Pgo8dGV4dCB4PSc2MCcgeT0nMjAnIGZvbnQtc2l6ZT0nMTInIGZpbGw9JyNhMDcwM2MnIG9wYWNpdHk9JzAuMycgdHJhbnNmb3JtPSdyb3RhdGUoLTI1IDYwIDIwKSc+JiMxMDA4NDs8L3RleHQ+Cjwvc3ZnPg=='),
            radial-gradient(ellipse at center, #2a1a0e 0%, #0e0804 100%);
        background-repeat: repeat, no-repeat;
        background-size: 90px 90px, 100% 100%;
        background-attachment: fixed, fixed;
    }

    .card-outer {
        width: 100%;
        height: 100%;
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        position: relative;
        box-sizing: border-box;
    }

    .card {
        width: 100%;
        height: auto;
        aspect-ratio: 9 / 16;
        max-width: 420px;
        max-height: 100%;
        border-radius: 14px;
        margin: 0 auto;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
    }

    /* ── SMALL PHONES (≤360px) ── */
    @media (max-width: 360px) {
        .card-outer {
            padding: 8px;
        }

        .card {
            border-radius: 10px;
        }
    }

    /* ── TABLETS ── */
    @media (min-width: 641px) {
        .card-outer {
            padding: 32px;
        }

        .card {
            max-width: 440px;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55);
        }
    }

    /* ── LAPTOPS (≥1024px) ── */
    @media (min-width: 1024px) {
        .card-outer {
            padding: 56px 24px;
        }

        .card {
            max-width: 460px;
        }
    }

    /* ── LAPTOP L / 1440px ── */
    @media (min-width: 1200px) {
        .card-outer {
            padding: 90px 24px;
        }

        .card {
            max-width: 480px;
        }
    }

    /* ── LARGE DESKTOP / 4K (≥1920px) ── */
    @media (min-width: 1920px) {
        .card-outer {
            padding: 130px 24px;
        }

        .card {
            max-width: 520px;
        }
    }

    /* ── SHORT VIEWPORTS (landscape phones / low-height windows) ── */
    @media (max-height: 700px) {
        .card-outer {
            padding-top: 16px;
            padding-bottom: 16px;
        }
    }

    /* ── NEXT BUTTON ── */
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
        background: #f0c090;
        color: #1e120a;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .next-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.5);
    }

    .next-btn:active {
        transform: translateY(0px) scale(0.97);
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
        <div class="card dk1">
            <div class="lips">
                <span class="lip" style="top:2%;left:4%;transform:rotate(-18deg)">💋</span>
                <span class="lip" style="top:1%;left:32%;transform:rotate(12deg)">💋</span>
                <span class="lip" style="top:4%;right:5%;transform:rotate(-6deg)">💋</span>
                <span class="lip" style="top:10%;left:10%;transform:rotate(28deg)">💋</span>
                <span class="lip" style="top:9%;right:20%;transform:rotate(-38deg)">💋</span>
                <span class="lip" style="top:17%;left:1%;transform:rotate(14deg)">💋</span>
                <span class="lip" style="top:15%;right:2%;transform:rotate(-11deg)">💋</span>
                <span class="lip" style="top:22%;right:9%;transform:rotate(24deg)">💋</span>
                <span class="lip" style="top:27%;left:6%;transform:rotate(-16deg)">💋</span>
                <span class="lip" style="top:31%;left:39%;transform:rotate(4deg)">💋</span>
                <span class="lip" style="top:36%;right:15%;transform:rotate(-32deg)">💋</span>
                <span class="lip" style="top:41%;left:13%;transform:rotate(22deg)">💋</span>
                <span class="lip" style="top:46%;right:7%;transform:rotate(-9deg)">💋</span>
                <span class="lip" style="top:50%;left:46%;transform:rotate(11deg)">💋</span>
                <span class="lip" style="top:55%;left:3%;transform:rotate(-26deg)">💋</span>
                <span class="lip" style="top:60%;right:3%;transform:rotate(17deg)">💋</span>
                <span class="lip" style="top:64%;left:56%;transform:rotate(-36deg)">💋</span>
                <span class="lip" style="top:68%;left:20%;transform:rotate(38deg)">💋</span>
                <span class="lip" style="top:72%;right:23%;transform:rotate(-13deg)">💋</span>
                <span class="lip" style="top:76%;left:31%;transform:rotate(7deg)">💋</span>
                <span class="lip" style="top:80%;left:6%;transform:rotate(-23deg)">💋</span>
                <span class="lip" style="top:84%;right:11%;transform:rotate(15deg)">💋</span>
                <span class="lip" style="top:88%;left:43%;transform:rotate(-7deg)">💋</span>
                <span class="lip" style="top:92%;left:61%;transform:rotate(21deg)">💋</span>
                <span class="lip" style="top:6%;left:61%;transform:rotate(-19deg)">💋</span>
                <span class="lip" style="top:19%;left:49%;transform:rotate(31deg)">💋</span>
                <span class="lip" style="top:38%;left:66%;transform:rotate(-29deg)">💋</span>
                <span class="lip" style="top:53%;left:26%;transform:rotate(5deg)">💋</span>
                <span class="lip" style="top:74%;left:71%;transform:rotate(-17deg)">💋</span>
                <span class="lip" style="top:95%;left:16%;transform:rotate(23deg)">💋</span>
            </div>
            <div class="hb-sticker">
                <span class="sp" style="top:5px;left:6px;color:#f0c080">✦</span>
                <span class="sp" style="top:5px;right:6px;color:#f0c080">✦</span>
                <span class="h1">HAPPY</span><span class="h2">birthday</span>
            </div>
            <div class="stamp">
                <div class="stamp-num">60</div>
                <div class="stamp-kisses">
                    <span style="transform:rotate(-8deg)">💋</span><span style="transform:rotate(12deg)">💋</span><span
                        style="transform:rotate(-5deg)">💋</span>
                    <span style="transform:rotate(10deg)">💋</span><span style="transform:rotate(-15deg)">💋</span><span
                        style="transform:rotate(6deg)">💋</span>
                </div>
                <div class="postmark">29.V.45<br />PRAHA 21</div>
            </div>
            <div class="fav">Favorite<br />Person →</div>
            <div class="pol pol1">
                <div class="pol-inner">
                    @if(request('photo1'))
                    <img src="{{ request('photo1') }}" alt=""
                        style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                    <svg viewbox="0 0 118 118" xmlns="http://www.w3.org/2000/svg">
                        <rect fill="#1a0f0a" height="118" width="118"></rect>
                        <circle cx="25" cy="18" fill="#ff9900" opacity="0.22" r="10"></circle>
                        <ellipse cx="38" cy="44" fill="#c8a882" rx="8" ry="9"></ellipse>
                        <ellipse cx="38" cy="37" fill="#1a1008" rx="8" ry="6"></ellipse>
                        <rect fill="#4a3525" height="32" rx="4" width="18" x="29" y="53"></rect>
                        <rect fill="#4a3525" height="22" rx="3" width="7" x="25" y="55"></rect>
                        <rect fill="#4a3525" height="22" rx="3" width="7" x="41" y="55"></rect>
                        <rect fill="#6a4a30" height="18" rx="3" width="7" x="31" y="85"></rect>
                        <rect fill="#6a4a30" height="18" rx="3" width="7" x="39" y="85"></rect>
                        <ellipse cx="80" cy="44" fill="#c8a882" rx="8" ry="9"></ellipse>
                        <ellipse cx="80" cy="37" fill="#1a1008" rx="8" ry="6"></ellipse>
                        <rect fill="#d4c8b8" height="32" rx="4" width="18" x="71" y="53"></rect>
                        <rect fill="#d4c8b8" height="22" rx="3" width="7" x="67" y="55"></rect>
                        <rect fill="#d4c8b8" height="22" rx="3" width="7" x="83" y="55"></rect>
                        <rect fill="#8B6B4A" height="18" rx="3" width="7" x="73" y="85"></rect>
                        <rect fill="#8B6B4A" height="18" rx="3" width="7" x="81" y="85"></rect>
                        <ellipse cx="59" cy="66" fill="#c8a882" opacity="0.8" rx="5" ry="4"></ellipse>
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
                    <svg viewbox="0 0 124 124" xmlns="http://www.w3.org/2000/svg">
                        <rect fill="#2a1a0e" height="124" width="124"></rect>
                        <circle cx="95" cy="25" fill="#ff9900" opacity="0.28" r="16"></circle>
                        <rect fill="#1a0d06" height="32" width="124" x="0" y="92"></rect>
                        <ellipse cx="62" cy="47" fill="#c8a882" rx="11" ry="12"></ellipse>
                        <ellipse cx="62" cy="38" fill="#1a1008" rx="11" ry="7"></ellipse>
                        <ellipse cx="52" cy="43" fill="#1a1008" rx="5" ry="8"></ellipse>
                        <ellipse cx="72" cy="43" fill="#1a1008" rx="5" ry="8"></ellipse>
                        <rect fill="#e8e4de" height="36" rx="7" width="32" x="46" y="59"></rect>
                        <ellipse cx="40" cy="80" fill="#c8a882" rx="6" ry="4"></ellipse>
                        <rect fill="#4a3020" height="18" rx="3" width="9" x="52" y="95"></rect>
                        <rect fill="#4a3020" height="18" rx="3" width="9" x="63" y="95"></rect>
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
                    <svg viewbox="0 0 108 108" xmlns="http://www.w3.org/2000/svg">
                        <rect fill="#1e1408" height="108" width="108"></rect>
                        <circle cx="54" cy="20" fill="#ff8800" opacity="0.15" r="22"></circle>
                        <ellipse cx="54" cy="46" fill="#c8a882" rx="13" ry="14"></ellipse>
                        <ellipse cx="54" cy="36" fill="#1a1008" rx="13" ry="8"></ellipse>
                        <ellipse cx="43" cy="40" fill="#1a1008" rx="5" ry="9"></ellipse>
                        <ellipse cx="65" cy="40" fill="#1a1008" rx="5" ry="9"></ellipse>
                        <circle cx="48" cy="47" fill="#333" r="2"></circle>
                        <circle cx="60" cy="47" fill="#333" r="2"></circle>
                        <rect fill="#e0dcd6" height="36" rx="7" width="30" x="39" y="60"></rect>
                        <ellipse cx="41" cy="80" fill="#c8a882" rx="6" ry="4"></ellipse>
                        <rect fill="#4a3020" height="10" rx="3" width="8" x="42" y="96"></rect>
                        <rect fill="#4a3020" height="10" rx="3" width="8" x="58" y="96"></rect>
                    </svg>
                    @endif
                </div>
            </div>
            <div class="cap-love">i love you &lt;3</div>
            <div class="cap-pb">"o, pretty boy,"</div>
            <div class="plate">
                <div class="plate-top">SO SPECIAL</div>
                <div class="plate-hy"><span class="plate-heart">♥</span><span>YOU</span></div>
                <div class="plate-bot">LIVE · LAUGH · LOVE</div>
            </div>
            <div class="hb-bot">HAppY<br />BiRtHdAy<br /><span style="font-size:0.75em">&lt;3</span></div>
            <svg class="kitty" viewbox="0 0 38 44" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="19" cy="28" fill="#2a1a10" rx="15" ry="15" stroke="#5a3820" stroke-width="1"></ellipse>
                <polygon fill="#d04030" points="11,11 18,15 11,19"></polygon>
                <polygon fill="#d04030" points="27,11 20,15 27,19"></polygon>
                <ellipse cx="19" cy="14" fill="#d04030" rx="4" ry="3"></ellipse>
                <circle cx="14" cy="26" fill="#f0d090" r="2"></circle>
                <circle cx="24" cy="26" fill="#f0d090" r="2"></circle>
                <ellipse cx="19" cy="31" fill="#f9a" rx="2" ry="1.5"></ellipse>
                <line stroke="#7a6050" stroke-width="0.8" x1="4" x2="13" y1="27" y2="29"></line>
                <line stroke="#7a6050" stroke-width="0.8" x1="4" x2="13" y1="31" y2="31"></line>
                <line stroke="#7a6050" stroke-width="0.8" x1="25" x2="34" y1="29" y2="27"></line>
                <line stroke="#7a6050" stroke-width="0.8" x1="25" x2="34" y1="31" y2="31"></line>
            </svg>
            <span class="sm-heart" style="bottom:8%;left:46%">♥</span>
            <span class="sm-heart" style="bottom:6%;left:52%">♥</span>
        </div>
    </div>
    <a class="next-btn" href="card4_dark_midnight_rose.html">
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