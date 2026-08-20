<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Our Camera Roll — Every memory has a story</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Inter:wght@400;500;600;700&family=Dancing+Script:wght@500;600;700&display=swap" rel="stylesheet">
<style>
/* ==================================================
   THEME VARIABLES — change these to re-skin everything
   THEME: Blush
   ================================================== */
:root {
    --bg-dark: #2a171f;
    --bg-light: #fdeef2;
    --primary: #e8899f;
    /* blush pink */
    --secondary: #f3b6c4;
    /* soft petal pink */
    --accent: #a84c68;
    /* deep rose */
    --text: #3a2028;
    --text-inverse: #fdeef2;
    --card: #fffafb;
    --card-glass: rgba(255, 250, 251, 0.55);
    --shadow: 0 20px 45px rgba(45, 10, 20, 0.4);
    --shadow-soft: 0 10px 30px rgba(45, 10, 20, 0.18);
    --radius: 26px;
    --radius-sm: 14px;
    --transition: 550ms cubic-bezier(.22, 1, .36, 1);
    --transition-fast: 280ms cubic-bezier(.22, 1, .36, 1);
    --font-heading: 'Playfair Display', serif;
    --font-body: 'Inter', sans-serif;
    --font-hand: 'Dancing Script', cursive;

    /* device frame */
    --stage-w: 380px;
    --stage-max-h: 860px;
    --frame-bezel: #1c1214;
    --frame-bezel-hi: #3a2a2a;
    --frame-titanium: #cbb9ac;
}

* {
    box-sizing: border-box;
}

html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
    overscroll-behavior: none;
}

body {
    font-family: var(--font-body);
    color: var(--text);
    min-height: 100vh;
    min-height: 100dvh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(120% 120% at 20% 0%, #402029 0%, var(--bg-dark) 55%, #170a0f 100%);
    overflow: hidden;
    position: relative;
    -webkit-tap-highlight-color: transparent;
}

button {
    font-family: inherit;
    cursor: pointer;
}

/* ==================================================
   LUXURY BACKGROUND — blur blobs, dust, glow lights
   ================================================== */
.bg-blur {
    position: fixed;
    inset: 0;
    z-index: 0;
    overflow: hidden;
    pointer-events: none;
}

.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(70px);
    opacity: .55;
    animation: drift 18s ease-in-out infinite;
}

.blob1 {
    width: 60vw;
    height: 60vw;
    max-width: 520px;
    max-height: 520px;
    background: radial-gradient(circle, var(--primary), transparent 70%);
    top: -15%;
    left: -15%;
    animation-delay: 0s;
}

.blob2 {
    width: 55vw;
    height: 55vw;
    max-width: 480px;
    max-height: 480px;
    background: radial-gradient(circle, var(--secondary), transparent 70%);
    bottom: -18%;
    right: -12%;
    animation-delay: -6s;
}

.blob3 {
    width: 40vw;
    height: 40vw;
    max-width: 360px;
    max-height: 360px;
    background: radial-gradient(circle, var(--accent), transparent 70%);
    top: 40%;
    left: 55%;
    animation-delay: -11s;
    opacity: .35;
}

@keyframes drift {

    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }

    33% {
        transform: translate(4%, 6%) scale(1.08);
    }

    66% {
        transform: translate(-5%, -3%) scale(0.95);
    }
}

.dust {
    position: absolute;
    border-radius: 50%;
    background: var(--text-inverse);
    opacity: 0;
    animation: dust-float linear infinite;
}

@keyframes dust-float {
    0% {
        transform: translateY(0) translateX(0);
        opacity: 0;
    }

    10% {
        opacity: .5;
    }

    90% {
        opacity: .35;
    }

    100% {
        transform: translateY(-110vh) translateX(20px);
        opacity: 0;
    }
}

.glow {
    position: absolute;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--primary);
    box-shadow: 0 0 12px 3px var(--primary);
    animation: twinkle 3.2s ease-in-out infinite;
}

@keyframes twinkle {

    0%,
    100% {
        opacity: .15;
        transform: scale(.8);
    }

    50% {
        opacity: 1;
        transform: scale(1.3);
    }
}

/* scattered "I love you" / "I miss you" whisper-text across the bg */
.love-word {
    position: absolute;
    font-family: var(--font-hand);
    color: var(--secondary);
    white-space: nowrap;
    opacity: 0;
    animation: word-breathe ease-in-out infinite;
    pointer-events: none;
    user-select: none;
}

@keyframes word-breathe {

    0%,
    100% {
        opacity: 0;
        transform: translateY(6px) scale(.94) rotate(var(--rot, 0deg));
    }

    50% {
        opacity: var(--peak, .22);
        transform: translateY(-4px) scale(1) rotate(var(--rot, 0deg));
    }
}

/* scattered cute emojis across the bg */
.love-emoji {
    position: absolute;
    opacity: 0;
    animation: emoji-breathe ease-in-out infinite;
    pointer-events: none;
    user-select: none;
    filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .25));
}

@keyframes emoji-breathe {

    0%,
    100% {
        opacity: 0;
        transform: translateY(8px) scale(.85) rotate(var(--rot, 0deg));
    }

    50% {
        opacity: var(--peak, .5);
        transform: translateY(-6px) scale(1) rotate(var(--rot, 0deg));
    }
}

/* ==================================================
   PHONE FRAME — realistic iPhone chrome around the
   9:16 stage: titanium body, side buttons, Dynamic
   Island, status bar, home indicator
   ================================================== */
.phone {
    position: relative;
    z-index: 1;
    width: min(92vw, var(--stage-w));
    height: min(88vh, min(88dvh, calc(min(92vw, var(--stage-w)) * 2.16)));
    max-height: var(--stage-max-h);
    aspect-ratio: 9/19.5;
    padding: 14px;
    border-radius: 58px;
    background: linear-gradient(155deg, var(--frame-titanium) 0%, #8d7f74 45%, var(--frame-titanium) 100%);
    box-shadow: var(--shadow), inset 0 0 0 1px rgba(255, 255, 255, .25);
}

.phone::before {
    /* faux brushed-metal sheen */
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 58px;
    background: linear-gradient(115deg, rgba(255, 255, 255, .35) 0%, transparent 18%, transparent 82%, rgba(255, 255, 255, .2) 100%);
    pointer-events: none;
}

/* side buttons */
.phone-btn {
    position: absolute;
    background: linear-gradient(90deg, #7c6f65, var(--frame-titanium));
    border-radius: 3px;
}

.btn-mute {
    left: -3px;
    top: 20%;
    width: 3px;
    height: 26px;
}

.btn-vol-up {
    left: -3px;
    top: 28%;
    width: 3px;
    height: 46px;
}

.btn-vol-down {
    left: -3px;
    top: 38%;
    width: 3px;
    height: 46px;
}

.btn-power {
    right: -3px;
    top: 24%;
    width: 3px;
    height: 62px;
}

.stage {
    position: relative;
    width: 100%;
    height: 100%;
    border-radius: 44px;
    overflow: hidden;
    background: linear-gradient(160deg, #34211f, #1c1112);
    box-shadow: 0 0 0 2px #000 inset;
}

/* Dynamic Island */
.dynamic-island {
    position: absolute;
    top: 12px;
    left: 50%;
    transform: translateX(-50%);
    width: 96px;
    height: 28px;
    background: #000;
    border-radius: 20px;
    z-index: 40;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 8px;
}

.dynamic-island::after {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, #3a5a8a, #0a1220);
}

/* iOS status bar */
.status-bar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    z-index: 35;
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 14px;
    color: #fff;
    pointer-events: none;
    text-shadow: 0 1px 3px rgba(0, 0, 0, .25);
}

.status-time {
    letter-spacing: .3px;
}

.status-icons {
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-icons svg {
    display: block;
}

/* home indicator */
.home-indicator {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 118px;
    height: 5px;
    border-radius: 3px;
    background: rgba(255, 255, 255, .85);
    z-index: 40;
    opacity: .9;
}

@media (min-width: 900px) {
    .phone {
        box-shadow: var(--shadow), inset 0 0 0 1px rgba(255, 255, 255, .25), 0 0 90px rgba(232, 137, 159, .15);
    }
}

/* ==================================================
   COVER SCREEN
   ================================================== */
.cover {
    position: absolute;
    inset: 0;
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 28px;
    background:
        radial-gradient(120% 90% at 50% 0%, rgba(232, 137, 159, .25), transparent 60%),
        linear-gradient(165deg, #33202a 0%, #1e1017 100%);
    transition: opacity var(--transition), transform var(--transition), visibility var(--transition);
}

.cover.hidden {
    opacity: 0;
    transform: scale(1.06);
    visibility: hidden;
    pointer-events: none;
}

.cover-card {
    width: 100%;
    padding: 38px 26px 30px;
    border-radius: var(--radius);
    text-align: center;
    background: var(--card-glass);
    backdrop-filter: blur(18px) saturate(140%);
    -webkit-backdrop-filter: blur(18px) saturate(140%);
    border: 1px solid rgba(255, 255, 255, .35);
    box-shadow: var(--shadow-soft);
    animation: cover-float 4.5s ease-in-out infinite;
}

@keyframes cover-float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-8px);
    }
}

.cover-icon {
    width: 66px;
    height: 66px;
    margin: 0 auto 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(145deg, var(--primary), var(--secondary));
    box-shadow: 0 10px 24px rgba(232, 137, 159, .45);
}

.cover-icon svg {
    width: 30px;
    height: 30px;
    stroke: #fff;
}

.cover-title {
    font-family: var(--font-heading);
    font-size: clamp(24px, 6vw, 30px);
    font-weight: 600;
    color: var(--text-inverse);
    margin: 0 0 8px;
    letter-spacing: .2px;
}

.cover-sub {
    font-size: 14px;
    color: var(--primary);
    opacity: .9;
    margin: 0 0 26px;
    font-style: italic;
    font-family: var(--font-heading);
}

.cover-tap {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-inverse);
    opacity: .75;
    animation: pulse-tap 1.8s ease-in-out infinite;
}

@keyframes pulse-tap {

    0%,
    100% {
        opacity: .45;
    }

    50% {
        opacity: .9;
    }
}

.cover-tap svg {
    width: 14px;
    height: 14px;
    stroke: var(--primary);
}

/* ==================================================
   GALLERY / MAIN EXPERIENCE — styled like the native
   iOS Photos app (album view)
   ================================================== */
.gallery {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: flex;
    flex-direction: column;
    opacity: 0;
    filter: blur(10px);
    transition: opacity var(--transition), filter var(--transition);
}

.gallery.active {
    opacity: 1;
    filter: blur(0);
}

.gallery-header {
    flex: 0 0 auto;
    padding: 50px 20px 10px;
    text-align: center;
    background: linear-gradient(180deg, rgba(28, 17, 18, .92), rgba(28, 17, 18, 0));
    position: relative;
    z-index: 3;
}

.gallery-header h2 {
    font-family: var(--font-heading);
    font-size: 17px;
    color: var(--text-inverse);
    margin: 0;
    letter-spacing: .3px;
}

.gallery-header span {
    display: block;
    margin-top: 3px;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--primary);
}

.reel {
    flex: 1 1 auto;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 6px 14px 34px;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.reel::-webkit-scrollbar {
    display: none;
}

/* each memory card enters on its own, staged by JS */
.memory {
    opacity: 0;
    transform: translateY(46px) scale(.94);
    transition: opacity 900ms cubic-bezier(.22, 1, .36, 1), transform 900ms cubic-bezier(.22, 1, .36, 1);
    margin: 0 0 20px;
    will-change: transform, opacity;
}

.memory.show {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.memory:last-child {
    margin-bottom: 6px;
}

.card-shell {
    position: relative;
    background: var(--card);
    border-radius: var(--radius);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, .5);
    transition: transform var(--transition-fast), box-shadow var(--transition-fast);
}

.card-shell:active {
    transform: scale(.98);
}

/* subtle tilt on pointer move (desktop) */
.card-shell.tilt {
    transform: perspective(700px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg));
}

.meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px 14px;
    gap: 10px;
}

.meta-left {
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 0;
}

.meta-date {
    font-size: 10px;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: var(--accent);
    font-weight: 600;
}

.meta-caption {
    font-size: 13px;
    color: var(--text);
    font-family: var(--font-heading);
    font-style: italic;
    /* Was a single ellipsised line, which silently ate the back half of any
       caption past ~20 characters. It now runs to two lines, so a caption of
       the length the design's own defaults use is shown in full. */
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    overflow-wrap: break-word;
}

.meta-loc {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    color: var(--accent);
    opacity: .8;
    flex-shrink: 0;
}

.meta-loc svg {
    width: 11px;
    height: 11px;
    stroke: var(--accent);
}

/* ---- Photo card (native Photos-app framing) ---- */
.media-frame {
    position: relative;
    width: 100%;
    aspect-ratio: 4/5;
    overflow: hidden;
    background: #eee2d2;
}

.media-frame img,
.media-frame video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 700ms ease;
}

.card-shell:active .media-frame img {
    transform: scale(1.03);
}

/* live-photo style badge, like iOS Photos */
.live-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 4px 9px 4px 7px;
    border-radius: 20px;
    background: rgba(20, 14, 12, .45);
    backdrop-filter: blur(6px);
    color: #fff;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .5px;
}

.live-badge svg {
    width: 12px;
    height: 12px;
    stroke: #fff;
}

/* ---- Video card (native player thumbnail) ---- */
.play-btn {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(180deg, rgba(0, 0, 0, .08), rgba(0, 0, 0, .4));
}

.play-btn .circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .16);
    backdrop-filter: blur(10px);
    border: 1.5px solid rgba(255, 255, 255, .55);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .35);
    transition: transform var(--transition-fast);
}

.memory:hover .play-btn .circle {
    transform: scale(1.08);
}

.play-btn svg {
    width: 22px;
    height: 22px;
    fill: #fff;
    margin-left: 3px;
}

.video-duration {
    position: absolute;
    right: 10px;
    bottom: 10px;
    background: rgba(0, 0, 0, .55);
    color: #fff;
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 20px;
    letter-spacing: .5px;
    font-variant-numeric: tabular-nums;
}

/* ---- Chat screenshot card (native iMessage look) ---- */
.chat-shot {
    display: block;
    width: 100%;
    border-radius: 12px;
    object-fit: cover;
}

.chat-wrap {
    padding: 16px 16px 4px;
}

.chat-frame {
    border-radius: 20px;
    overflow: hidden;
    background: #f4f4f6;
    box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .05);
}

.chat-topbar {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 12px 10px 8px;
    background: rgba(244, 244, 246, .9);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 0, 0, .06);
}

.chat-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(145deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-family: var(--font-heading);
    font-size: 14px;
    font-weight: 600;
}

.chat-name {
    font-size: 10.5px;
    font-weight: 600;
    color: #6b6b70;
    letter-spacing: .2px;
}

.chat-bubbles {
    padding: 14px 12px;
    display: flex;
    flex-direction: column;
    gap: 7px;
    background: #fff;
}

.bubble {
    max-width: 76%;
    padding: 8px 13px;
    border-radius: 17px;
    font-size: 12.5px;
    line-height: 1.4;
}

.bubble.them {
    align-self: flex-start;
    background: #e9e9eb;
    color: #1c1c1e;
    border-bottom-left-radius: 5px;
}

.bubble.me {
    align-self: flex-end;
    background: linear-gradient(135deg, #4fa8ff, #2f7fe0);
    color: #fff;
    border-bottom-right-radius: 5px;
}

.chat-time {
    text-align: center;
    font-size: 9px;
    color: #a0a0a5;
    letter-spacing: .5px;
    margin: 2px 0 4px;
    text-transform: uppercase;
}

/* ---- Memory note card ---- */
.note {
    position: relative;
    padding: 30px 22px 26px;
    background:
        repeating-linear-gradient(180deg, transparent 0 27px, rgba(156, 91, 69, .08) 27px 28px),
        #fdf4ee;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.note-text {
    font-family: var(--font-hand);
    font-size: clamp(19px, 5.4vw, 24px);
    color: var(--accent);
    line-height: 1.5;
}

.tape {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%) rotate(-3deg);
    width: 70px;
    height: 24px;
    background: rgba(232, 180, 172, .55);
    box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
    border: 1px solid rgba(255, 255, 255, .4);
}

/* ---- Final letter card ---- */
.letter-card {
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

.letter-fold {
    position: relative;
    aspect-ratio: 4/5;
    background:
        linear-gradient(160deg, #f0dcc8, #e3c19f);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 10px;
    cursor: pointer;
}

.letter-seal {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 30%, #c76b53, #8a3323);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 16px rgba(0, 0, 0, .3);
}

.letter-seal svg {
    width: 22px;
    height: 22px;
    stroke: #fdeef2;
}

.letter-fold p {
    font-family: var(--font-heading);
    font-style: italic;
    font-size: 14px;
    color: var(--accent);
    margin: 0;
}

/* small badge marking end of reel */
.end-mark {
    text-align: center;
    padding: 10px 0 4px;
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--primary);
    opacity: .6;
}

/* ==================================================
   FULLSCREEN VIEWER (photo / chat) — mimics iOS Photos
   detail view with a translucent top/bottom bar
   ================================================== */
.viewer {
    position: fixed;
    inset: 0;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(6, 4, 4, .95);
    backdrop-filter: blur(6px);
    opacity: 0;
    visibility: hidden;
    transition: opacity var(--transition), visibility var(--transition);
    padding: 26px;
}

.viewer.open {
    opacity: 1;
    visibility: visible;
}

.viewer-inner {
    max-width: 480px;
    width: 100%;
    max-height: 100%;
    transform: scale(.85);
    transition: transform var(--transition);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 70px rgba(0, 0, 0, .6);
}

.viewer.open .viewer-inner {
    transform: scale(1);
}

.viewer-inner img {
    width: 100%;
    display: block;
}

.viewer-close {
    position: absolute;
    top: 18px;
    right: 18px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .12);
    border: 1px solid rgba(255, 255, 255, .25);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.viewer-close svg {
    width: 16px;
    height: 16px;
    stroke: #fff;
}

/* ==================================================
   VIDEO POPUP — native-feeling player
   ================================================== */
.video-modal {
    position: fixed;
    inset: 0;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(6, 4, 4, .95);
    backdrop-filter: blur(6px);
    opacity: 0;
    visibility: hidden;
    transition: opacity var(--transition), visibility var(--transition);
    padding: 20px;
}

.video-modal.open {
    opacity: 1;
    visibility: visible;
}

.video-modal-inner {
    width: 100%;
    max-width: 380px;
    border-radius: 30px;
    overflow: hidden;
    background: #000;
    transform: scale(.85) translateY(20px);
    transition: transform var(--transition);
    box-shadow: 0 25px 70px rgba(0, 0, 0, .6);
    border: 4px solid #111;
}

.video-modal.open .video-modal-inner {
    transform: scale(1) translateY(0);
}

.video-modal video {
    width: 100%;
    display: block;
    aspect-ratio: 9/16;
    background: #000;
}

/* ==================================================
   THE LETTER OVERLAY (unfold + handwriting)
   ================================================== */
.letter-overlay {
    position: fixed;
    inset: 0;
    z-index: 60;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(6, 4, 4, .95);
    opacity: 0;
    visibility: hidden;
    transition: opacity var(--transition);
    padding: 24px;
}

.letter-overlay.open {
    opacity: 1;
    visibility: visible;
}

.letter-paper {
    width: 100%;
    max-width: 380px;
    max-height: 86vh;
    background: #fdf8ee;
    border-radius: 6px;
    box-shadow: 0 30px 80px rgba(0, 0, 0, .55);
    padding: 34px 26px;
    transform-origin: top center;
    transform: scaleY(.08);
    transition: transform 900ms cubic-bezier(.65, 0, .35, 1);
    overflow-y: auto;
    position: relative;
}

.letter-overlay.open .letter-paper {
    transform: scaleY(1);
}

.letter-paper::before {
    content: '';
    position: absolute;
    inset: 10px;
    border: 1px solid rgba(156, 91, 69, .18);
    pointer-events: none;
}

.letter-body {
    font-family: var(--font-hand);
    font-size: clamp(20px, 6vw, 25px);
    line-height: 1.75;
    color: var(--accent);
    white-space: pre-wrap;
    min-height: 180px;
}

.pen-caret {
    display: inline-block;
    width: 2px;
    height: 1em;
    background: var(--accent);
    vertical-align: -3px;
    animation: caret-blink 900ms steps(1) infinite;
}

@keyframes caret-blink {

    0%,
    49% {
        opacity: 1;
    }

    50%,
    100% {
        opacity: 0;
    }
}

.letter-overlay-close {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .1);
    border: 1px solid rgba(255, 255, 255, .2);
    display: flex;
    align-items: center;
    justify-content: center;
}

.letter-overlay-close svg {
    width: 14px;
    height: 14px;
    stroke: #fdeef2;
}

.letter-signoff {
    margin-top: 18px;
    text-align: right;
    font-family: var(--font-hand);
    font-size: 22px;
    color: var(--accent);
    opacity: 0;
    transition: opacity 600ms ease;
}

.letter-signoff.show {
    opacity: 1;
}

/* ==================================================
   RIPPLE micro-interaction
   ================================================== */
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, .55);
    transform: scale(0);
    animation: ripple-anim 650ms ease-out forwards;
    pointer-events: none;
}

@keyframes ripple-anim {
    to {
        transform: scale(3.2);
        opacity: 0;
    }
}

/* ==================================================
   RESPONSIVE — tablet / desktop just gives more room
   around the same iPhone frame
   ================================================== */
@media (min-width: 641px) {
    :root {
        --stage-w: 390px;
    }
}

@media (min-width: 1024px) {
    body {
        padding: 40px;
    }

    .phone {
        box-shadow: var(--shadow), inset 0 0 0 1px rgba(255, 255, 255, .25), 0 0 130px rgba(232, 137, 159, .18);
    }
}

@media (max-height: 760px) {
    .phone {
        max-height: 92vh;
    }
}
</style>
</head>

<body>

<!-- Background ambience -->
<div class="bg-blur" aria-hidden="true">
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    <div class="blob blob3"></div>
    <div id="wordLayer"></div>
    <div id="emojiLayer"></div>
    <div id="dustLayer"></div>
    <div id="glowLayer"></div>
</div>

<!-- ============ PHONE FRAME (realistic iPhone chrome) ============ -->
<div class="phone" id="phone">
    <div class="phone-btn btn-mute"></div>
    <div class="phone-btn btn-vol-up"></div>
    <div class="phone-btn btn-vol-down"></div>
    <div class="phone-btn btn-power"></div>

    <!-- ============ STAGE (9:19.5 screen) ============ -->
    <div class="stage" id="stage">

        <div class="dynamic-island"></div>

        <div class="status-bar">
            <span class="status-time" id="statusTime">9:41</span>
            <span class="status-icons">
                <!-- signal -->
                <svg width="17" height="11" viewBox="0 0 17 11" fill="none"><rect x="0" y="6" width="3" height="5" rx="0.7" fill="#fff"/><rect x="4.5" y="4" width="3" height="7" rx="0.7" fill="#fff"/><rect x="9" y="2" width="3" height="9" rx="0.7" fill="#fff"/><rect x="13.5" y="0" width="3" height="11" rx="0.7" fill="#fff"/></svg>
                <!-- wifi -->
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none"><path d="M8 9.6a1.3 1.3 0 1 0 0 2.6 1.3 1.3 0 0 0 0-2.6Z" fill="#fff"/><path d="M4.5 7.2a5 5 0 0 1 7 0" stroke="#fff" stroke-width="1.4" stroke-linecap="round"/><path d="M1.8 4.5a9 9 0 0 1 12.4 0" stroke="#fff" stroke-width="1.4" stroke-linecap="round"/></svg>
                <!-- battery -->
                <svg width="25" height="12" viewBox="0 0 25 12" fill="none"><rect x="0.75" y="0.75" width="20.5" height="10.5" rx="2.5" stroke="#fff" stroke-opacity=".5" stroke-width="1"/><rect x="2.2" y="2.2" width="17.6" height="7.6" rx="1.4" fill="#fff"/><path d="M23 4v4a1.7 1.7 0 0 0 1-1.5V5.5A1.7 1.7 0 0 0 23 4Z" fill="#fff" fill-opacity=".5"/></svg>
            </span>
        </div>

        <!-- ============ OPENING COVER ============ -->
        <div class="cover" id="cover">
            <div class="cover-card">
                <div class="cover-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="6" width="18" height="14" rx="3"></rect>
                        <circle cx="12" cy="13" r="3.4"></circle>
                        <path d="M8 6l1.4-2.2h5.2L16 6"></path>
                    </svg>
                </div>
                <h1 class="cover-title">{{ request('cover_title', 'Our Camera Roll') }}</h1>
                <p class="cover-sub">{{ request('cover_sub', 'Every memory has a story.') }}</p>
                <div class="cover-tap">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11.5V6a2 2 0 1 1 4 0v5"></path>
                        <path d="M13 6a2 2 0 1 1 4 0v6"></path>
                        <path d="M17 8.5a2 2 0 1 1 4 0V14a7 7 0 0 1-7 7h-1a7 7 0 0 1-6-3.4L4.7 13a1.8 1.8 0 0 1 2.9-2.1L9 13"></path>
                    </svg>
                    {{ request('cover_tap', 'Tap to Open') }}
                </div>
            </div>
        </div>

        <!-- ============ MAIN GALLERY (memory reel) ============ -->
        <div class="gallery" id="gallery">
            <div class="gallery-header">
                <h2>{{ request('gallery_title', 'Our Camera Roll') }}</h2>
                <span id="counter">0 / 0 memories</span>
            </div>

            <div class="reel" id="reel">
                <!-- Memory cards are injected by JS from MEMORY_DATA below,
                     then revealed one-by-one for the stacked entrance effect. -->
            </div>
        </div>

        <div class="home-indicator"></div>
    </div>
</div>

<!-- ============ FULLSCREEN PHOTO / CHAT VIEWER ============ -->
<div class="viewer" id="viewer">
    <button class="viewer-close" id="viewerClose" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6l12 12M18 6L6 18"></path>
        </svg>
    </button>
    <div class="viewer-inner" id="viewerInner"></div>
</div>

<!-- ============ VIDEO POPUP ============ -->
<div class="video-modal" id="videoModal">
    <button class="viewer-close" id="videoClose" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6l12 12M18 6L6 18"></path>
        </svg>
    </button>
    <div class="video-modal-inner">
        <video id="modalVideo" controls playsinline></video>
    </div>
</div>

<!-- ============ FINAL LETTER OVERLAY ============ -->
<div class="letter-overlay" id="letterOverlay">
    <button class="letter-overlay-close" id="letterClose" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6l12 12M18 6L6 18"></path>
        </svg>
    </button>
    <div class="letter-paper">
        <div class="letter-body" id="letterBody"><span class="pen-caret"></span></div>
        <div class="letter-signoff" id="letterSignoff">{{ request('signoff', '— with love') }}</div>
    </div>
</div>

<script>
/* ==================================================
   REUSABLE MEMORY DATA
   Replace src / text values with real user uploads.
   type: "photo" | "video" | "chat" | "note" | "letter"
   ================================================== */
@php
    // ── Girl Gift 3 — the camera roll ────────────────────────────────
    // Five cards: a photo with its date, one video clip, the chat, a second
    // photo, and the letter. Every card is built here so the dashboard drives
    // the whole thing through query parameters, and each default is the
    // design's own original content — with no parameters the page renders
    // exactly as it did before.
    $memoryData = [
        [
            'type' => 'photo',
            'src' => request('photo1', 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?q=80&w=800&auto=format&fit=crop'),
            'date' => request('p1_date', 'March 14'),
            'location' => request('p1_place', 'Rooftop Cafe'),
            'caption' => request('p1_caption', 'The evening the sky turned gold.'),
            'live' => true,
        ],
        [
            'type' => 'video',
            'poster' => request('poster1', 'https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=800&auto=format&fit=crop'),
            'src' => request('video1', ''),
            'duration' => request('v1_duration', '0:18'),
            'date' => request('v1_date', 'April 2'),
            'location' => request('v1_place', 'Home'),
            'caption' => request('v1_caption', 'That laugh I never get tired of.'),
        ],
        [
            'type' => 'chat',
            // A real screenshot if the client uploaded one, otherwise the chat
            // is drawn from their own three lines.
            'shot' => request('chat_shot', ''),
            'name' => request('chat_name', 'My Person'),
            'bubbles' => [
                ['from' => 'them', 'text' => request('chat1', 'guess where I am rn 👀')],
                ['from' => 'me', 'text' => request('chat2', "no way. tell me you didn't")],
                ['from' => 'them', 'text' => request('chat3', 'i did. saved us seats already 🎬')],
            ],
            'date' => request('chat_date', 'April 9'),
            'caption' => request('chat_caption', 'My favorite conversation.'),
        ],
        [
            'type' => 'photo',
            'src' => request('photo2', 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?q=80&w=800&auto=format&fit=crop'),
            'date' => request('p2_date', 'May 21'),
            'location' => request('p2_place', 'Coastline Drive'),
            'caption' => request('p2_caption', 'Windows down, nowhere to be.'),
        ],
        [
            'type' => 'letter',
            'text' => request('letter', "Thank you for every smile.\n\nEvery photo here reminds me how lucky I am to have you.\n\nHere's to a thousand more memories, and to you \u{2014} always, endlessly, you.\n\nHappy birthday. I love you."),
        ],
    ];
@endphp
const MEMORY_DATA = @json($memoryData);

/* ==================================================
   LIVE CLOCK for status bar (feels real, not stuck at 9:41)
   ================================================== */
(function tickClock() {
    const el = document.getElementById('statusTime');
    function update() {
        const d = new Date();
        let h = d.getHours();
        const m = d.getMinutes();
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        el.textContent = h + ':' + String(m).padStart(2, '0');
    }
    update();
    setInterval(update, 15000);
})();

/* ==================================================
   BACKGROUND LOVE WORDS + EMOJIS
   Scattered "I love you" / "I miss you" whispers and
   cute emojis across the whole page background.
   ================================================== */
(function buildLoveBackground() {
    const wordLayer = document.getElementById('wordLayer');
    const emojiLayer = document.getElementById('emojiLayer');
    const words = ['I love you', 'I miss you', 'i love you', 'i miss u', 'love you', 'miss you', 'always you'];
    const emojis = ['💗', '💕', '💖', '💘', '🥰', '😽', '💝', '🌸', '✨', '💌', '😘', '🩷'];

    const wordCount = window.innerWidth < 640 ? 22 : 36;
    const emojiCount = window.innerWidth < 640 ? 18 : 30;

    for (let i = 0; i < wordCount; i++) {
        const w = document.createElement('span');
        w.className = 'love-word';
        w.textContent = words[Math.floor(Math.random() * words.length)];
        w.style.left = Math.random() * 96 + 'vw';
        w.style.top = Math.random() * 96 + 'vh';
        w.style.fontSize = (13 + Math.random() * 14) + 'px';
        w.style.setProperty('--rot', (Math.random() * 30 - 15) + 'deg');
        w.style.setProperty('--peak', (.14 + Math.random() * .18).toFixed(2));
        w.style.animationDuration = (5 + Math.random() * 5) + 's';
        w.style.animationDelay = (Math.random() * 6) + 's';
        wordLayer.appendChild(w);
    }

    for (let i = 0; i < emojiCount; i++) {
        const e = document.createElement('span');
        e.className = 'love-emoji';
        e.textContent = emojis[Math.floor(Math.random() * emojis.length)];
        e.style.left = Math.random() * 96 + 'vw';
        e.style.top = Math.random() * 96 + 'vh';
        e.style.fontSize = (12 + Math.random() * 16) + 'px';
        e.style.setProperty('--rot', (Math.random() * 40 - 20) + 'deg');
        e.style.setProperty('--peak', (.35 + Math.random() * .35).toFixed(2));
        e.style.animationDuration = (4 + Math.random() * 5) + 's';
        e.style.animationDelay = (Math.random() * 6) + 's';
        emojiLayer.appendChild(e);
    }
})();

/* ==================================================
   BACKGROUND PARTICLES
   ================================================== */
(function buildParticles() {
    const dustLayer = document.getElementById('dustLayer');
    const glowLayer = document.getElementById('glowLayer');
    const dustCount = window.innerWidth < 640 ? 16 : 26;
    const glowCount = window.innerWidth < 640 ? 8 : 14;

    for (let i = 0; i < dustCount; i++) {
        const d = document.createElement('div');
        d.className = 'dust';
        const size = 2 + Math.random() * 3;
        d.style.width = size + 'px';
        d.style.height = size + 'px';
        d.style.left = Math.random() * 100 + 'vw';
        d.style.bottom = -10 + 'px';
        d.style.animationDuration = (14 + Math.random() * 12) + 's';
        d.style.animationDelay = (Math.random() * 14) + 's';
        dustLayer.appendChild(d);
    }

    for (let i = 0; i < glowCount; i++) {
        const g = document.createElement('div');
        g.className = 'glow';
        g.style.left = Math.random() * 100 + 'vw';
        g.style.top = Math.random() * 100 + 'vh';
        g.style.animationDelay = (Math.random() * 3) + 's';
        g.style.animationDuration = (2.6 + Math.random() * 2.4) + 's';
        glowLayer.appendChild(g);
    }
})();

/* ==================================================
   BUILD MEMORY CARDS FROM DATA
   ================================================== */
const reel = document.getElementById('reel');
const counterEl = document.getElementById('counter');

// Cards are built with innerHTML, so anything the client typed is escaped
// on the way in. Without this a quote in a caption would break the attribute
// it sits in and a stray angle bracket could rewrite the card.
function esc(value) {
    return String(value === null || value === undefined ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function svgLocationIcon() {
    return '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
}

function svgPlayIcon() {
    return '<svg viewBox="0 0 24 24"><polygon points="6,4 20,12 6,20"></polygon></svg>';
}

function svgLiveIcon() {
    return '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><circle cx="12" cy="12" r="8" opacity=".5"></circle></svg>';
}

function createPhotoCard(m) {
    const el = document.createElement('div');
    el.innerHTML = `
        <div class="card-shell" data-tilt>
            <div class="media-frame" data-open="photo" data-src="${esc(m.src)}">
                <img src="${esc(m.src)}" alt="${esc(m.caption)}" loading="lazy">
                ${m.live ? `<div class="live-badge">${svgLiveIcon()}LIVE</div>` : ''}
            </div>
            <div class="meta-row">
                <div class="meta-left">
                    <span class="meta-date">${esc(m.date)}</span>
                    <span class="meta-caption">${esc(m.caption)}</span>
                </div>
                ${m.location ? `<div class="meta-loc">${svgLocationIcon()}${esc(m.location)}</div>` : ''}
            </div>
        </div>`;
    return el.firstElementChild;
}

function createVideoCard(m) {
    const el = document.createElement('div');
    el.innerHTML = `
        <div class="card-shell" data-tilt>
            <div class="media-frame" data-open="video" data-src="${esc(m.src)}">
                <img src="${esc(m.poster)}" alt="${esc(m.caption)}" loading="lazy">
                <div class="play-btn"><div class="circle">${svgPlayIcon()}</div></div>
                ${m.duration ? `<div class="video-duration">${esc(m.duration)}</div>` : ''}
            </div>
            <div class="meta-row">
                <div class="meta-left">
                    <span class="meta-date">${esc(m.date)}</span>
                    <span class="meta-caption">${esc(m.caption)}</span>
                </div>
                ${m.location ? `<div class="meta-loc">${svgLocationIcon()}${esc(m.location)}</div>` : ''}
            </div>
        </div>`;
    return el.firstElementChild;
}

function createChatCard(m) {
    const bubbles = (m.bubbles || []).map(b => `<div class="bubble ${b.from === 'me' ? 'me' : 'them'}">${esc(b.text)}</div>`).join('');
    const initial = (m.name || '?').trim().charAt(0).toUpperCase();
    // An uploaded screenshot stands in for the drawn conversation; without
    // one the client's own three lines are rendered as the chat instead.
    const inner = m.shot
        ? `<img class="chat-shot" src="${esc(m.shot)}" alt="${esc(m.caption)}" loading="lazy">`
        : `<div class="chat-frame" data-open="chat">
                    <div class="chat-topbar">
                        <div class="chat-avatar">${esc(initial)}</div>
                        <div class="chat-name">${esc(m.name)}</div>
                    </div>
                    <div class="chat-bubbles">
                        <div class="chat-time">${esc(m.date)}</div>
                        ${bubbles}
                    </div>
                </div>`;
    const el = document.createElement('div');
    el.innerHTML = `
        <div class="card-shell" data-tilt>
            <div class="chat-wrap">
                ${inner}
            </div>
            <div class="meta-row">
                <div class="meta-left">
                    <span class="meta-date">${esc(m.date)}</span>
                    <span class="meta-caption">${esc(m.caption)}</span>
                </div>
            </div>
        </div>`;
    return el.firstElementChild;
}

function createNoteCard(m) {
    const el = document.createElement('div');
    el.innerHTML = `
        <div class="card-shell">
            <div class="note">
                <div class="tape"></div>
                <div class="note-text">${esc(m.text).replace(/\n/g, '<br>')}</div>
            </div>
            <div class="meta-row">
                <div class="meta-left"><span class="meta-date">${esc(m.date)}</span></div>
            </div>
        </div>`;
    return el.firstElementChild;
}

function createLetterCard(m) {
    const el = document.createElement('div');
    el.innerHTML = `
        <div class="letter-card" id="letterTrigger" data-letter="${encodeURIComponent(m.text || '')}">
            <div class="letter-fold">
                <div class="letter-seal">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21s-7-4.6-9.6-9.1C.6 8.4 2.4 5 6 5c2 0 3.5 1.1 4.3 2.4a1 1 0 0 0 1.4 0C12.5 6.1 14 5 16 5c3.6 0 5.4 3.4 3.6 6.9C19 16.4 12 21 12 21Z"></path>
                    </svg>
                </div>
                <p>A letter, just for you.</p>
                <p style="font-size:11px; letter-spacing:2px; text-transform:uppercase; opacity:.7;">Tap to open</p>
            </div>
        </div>`;
    return el.firstElementChild;
}

function buildCard(m) {
    switch (m.type) {
        case 'photo': return createPhotoCard(m);
        case 'video': return createVideoCard(m);
        case 'chat': return createChatCard(m);
        case 'note': return createNoteCard(m);
        case 'letter': return createLetterCard(m);
        default: return document.createElement('div');
    }
}

MEMORY_DATA.forEach(m => {
    const wrap = document.createElement('div');
    wrap.className = 'memory';
    wrap.appendChild(buildCard(m));
    reel.appendChild(wrap);
});

const endMark = document.createElement('div');
endMark.className = 'end-mark';
endMark.textContent = '— end of roll —';
reel.appendChild(endMark);

/* ==================================================
   OPENING: cover tap -> gallery reveal -> staged cards
   ================================================== */
const cover = document.getElementById('cover');
const gallery = document.getElementById('gallery');
const memories = Array.from(document.querySelectorAll('.memory'));

function updateCounter(shown) {
    counterEl.textContent = shown + ' / ' + memories.length + ' memories';
}

function revealMemoriesSequentially() {
    let i = 0;
    updateCounter(0);
    const step = () => {
        if (i >= memories.length) return;
        memories[i].classList.add('show');
        i++;
        updateCounter(i);
        if (i < memories.length) {
            setTimeout(step, 420);
        }
    };
    setTimeout(step, 250);
}

function openGallery() {
    cover.classList.add('hidden');
    gallery.classList.add('active');
    revealMemoriesSequentially();
}

cover.addEventListener('click', openGallery);
cover.addEventListener('keyup', e => { if (e.key === 'Enter' || e.key === ' ') openGallery(); });

/* ==================================================
   RIPPLE micro-interaction on tap
   ================================================== */
document.addEventListener('pointerdown', (e) => {
    const target = e.target.closest('.card-shell, .cover-card, .letter-card');
    if (!target) return;
    const rect = target.getBoundingClientRect();
    const r = document.createElement('span');
    const size = Math.max(rect.width, rect.height);
    r.className = 'ripple';
    r.style.width = r.style.height = size + 'px';
    r.style.left = (e.clientX - rect.left - size / 2) + 'px';
    r.style.top = (e.clientY - rect.top - size / 2) + 'px';
    target.style.position = target.style.position || 'relative';
    target.style.overflow = target.style.overflow || 'hidden';
    target.appendChild(r);
    setTimeout(() => r.remove(), 650);
});

/* subtle desktop tilt */
document.addEventListener('pointermove', (e) => {
    const el = e.target.closest('[data-tilt]');
    document.querySelectorAll('.card-shell.tilt').forEach(c => { if (c !== el) { c.style.setProperty('--rx', '0deg'); c.style.setProperty('--ry', '0deg'); c.classList.remove('tilt'); } });
    if (!el || window.innerWidth < 1024) return;
    const rect = el.getBoundingClientRect();
    const px = (e.clientX - rect.left) / rect.width - .5;
    const py = (e.clientY - rect.top) / rect.height - .5;
    el.classList.add('tilt');
    el.style.setProperty('--ry', (px * 6) + 'deg');
    el.style.setProperty('--rx', (py * -6) + 'deg');
});

/* ==================================================
   PHOTO / CHAT FULLSCREEN VIEWER
   ================================================== */
const viewer = document.getElementById('viewer');
const viewerInner = document.getElementById('viewerInner');
const viewerClose = document.getElementById('viewerClose');

function openViewer(kind, sourceEl) {
    viewerInner.innerHTML = '';
    if (kind === 'photo') {
        const img = document.createElement('img');
        img.src = sourceEl.dataset.src;
        viewerInner.appendChild(img);
    } else if (kind === 'chat') {
        viewerInner.appendChild(sourceEl.cloneNode(true));
    }
    viewer.classList.add('open');
}

function closeViewer() {
    viewer.classList.remove('open');
}

reel.addEventListener('click', (e) => {
    const openTarget = e.target.closest('[data-open]');
    if (!openTarget) return;
    const kind = openTarget.dataset.open;
    if (kind === 'photo') openViewer('photo', openTarget);
    if (kind === 'chat') openViewer('chat', openTarget);
    if (kind === 'video') openVideo(openTarget.dataset.src, openTarget.querySelector('img')?.src);
});

viewerClose.addEventListener('click', closeViewer);
viewer.addEventListener('click', (e) => { if (e.target === viewer) closeViewer(); });

/* ==================================================
   VIDEO POPUP
   ================================================== */
const videoModal = document.getElementById('videoModal');
const modalVideo = document.getElementById('modalVideo');
const videoClose = document.getElementById('videoClose');

function openVideo(src, poster) {
    modalVideo.pause();
    modalVideo.src = src || '';
    if (poster) modalVideo.poster = poster;
    videoModal.classList.add('open');
    if (src) modalVideo.play().catch(() => {});
}

function closeVideo() {
    videoModal.classList.remove('open');
    modalVideo.pause();
}

videoClose.addEventListener('click', closeVideo);
videoModal.addEventListener('click', (e) => { if (e.target === videoModal) closeVideo(); });

/* ==================================================
   FINAL LETTER — unfold + pen handwriting animation
   ================================================== */
const letterOverlay = document.getElementById('letterOverlay');
const letterBody = document.getElementById('letterBody');
const letterSignoff = document.getElementById('letterSignoff');
const letterClose = document.getElementById('letterClose');
let letterTyped = false;

function typewriteHandwriting(text) {
    letterBody.innerHTML = '';
    const caret = document.createElement('span');
    caret.className = 'pen-caret';

    let i = 0;
    const textNode = document.createTextNode('');
    letterBody.appendChild(textNode);
    letterBody.appendChild(caret);

    function tick() {
        if (i >= text.length) {
            caret.remove();
            letterSignoff.classList.add('show');
            return;
        }
        textNode.textContent += text[i];
        i++;
        // irregular pen-like timing: pauses on punctuation & line breaks
        const ch = text[i - 1];
        let delay = 34 + Math.random() * 26;
        if (ch === ',' ) delay += 140;
        if (ch === '.' ) delay += 260;
        if (ch === '\n') delay += 420;
        setTimeout(tick, delay);
    }
    tick();
}

reel.addEventListener('click', (e) => {
    const trigger = e.target.closest('#letterTrigger, [data-letter]');
    if (!trigger) return;
    const text = decodeURIComponent(trigger.dataset.letter || '');
    letterOverlay.classList.add('open');
    letterSignoff.classList.remove('show');
    if (!letterTyped) {
        letterTyped = true;
        setTimeout(() => typewriteHandwriting(text), 500);
    } else {
        letterBody.textContent = text;
        letterSignoff.classList.add('show');
    }
});

letterClose.addEventListener('click', () => letterOverlay.classList.remove('open'));
letterOverlay.addEventListener('click', (e) => { if (e.target === letterOverlay) letterOverlay.classList.remove('open'); });

/* Escape key closes any open overlay */
document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    closeViewer();
    closeVideo();
    letterOverlay.classList.remove('open');
});

/* ==================================================
   DASHBOARD PREVIEW
   The roll opens on its cover and stages the cards in one at a time, so
   ?preview_card=N skips both and scrolls straight to the card being edited.
   Card 0 is the cover itself.
   ================================================== */
(function previewCard() {
    const raw = @json(request('preview_card'));
    if (raw === null || raw === undefined || raw === '') return;
    const index = parseInt(raw, 10);
    if (isNaN(index) || index < 0) return;

    cover.classList.add('hidden');
    gallery.classList.add('active');
    memories.forEach(m => m.classList.add('show'));
    updateCounter(memories.length);

    const target = memories[Math.min(index, memories.length - 1)];
    if (target) {
        requestAnimationFrame(() => target.scrollIntoView({ block: 'center' }));
    }
})();

</script>

</body>
</html>
