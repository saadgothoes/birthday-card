{{--
    Shared gift-reveal flourish for every page-3 gift-selection screen
    (boy ×2, girl ×2, anniversary ×4).

    @include this ONCE, right before </body> — AFTER the page's own <script>
    that defines openGiftPage(). It wraps window.openGiftPage so a tap first
    plays a light-burst / box-opening animation over the tapped box (the image
    hotspot on desktop, the CSS gift box on mobile), then hands off to the
    page's original navigation (loading screen + redirect) unchanged.

    Nothing to configure — it reads the tapped element's position at runtime
    and adapts to whichever layout (image / CSS boxes) is on screen.
--}}
<style>
    .grfx-dim {
        position: fixed;
        inset: 0;
        z-index: 18;
        background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%),
                transparent 0, rgba(0, 0, 0, .04) 20%, rgba(0, 0, 0, .5) 100%);
        opacity: 0;
        transition: opacity .4s ease;
        pointer-events: none;
    }

    .grfx-dim.on {
        opacity: 1;
    }

    .grfx {
        position: fixed;
        z-index: 19;
        pointer-events: none;
        will-change: transform;
    }

    .grfx-core {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: visible;
    }

    /* central flash */
    .grfx-burst {
        width: 58%;
        aspect-ratio: 1;
        border-radius: 50%;
        background: radial-gradient(circle, #fff 0%, #ffe9bd 32%,
                rgba(255, 197, 112, .5) 56%, transparent 74%);
        transform: scale(.2);
        opacity: 0;
    }

    .grfx.play .grfx-burst {
        animation: grfxBurst .7s cubic-bezier(.2, .8, .3, 1) forwards;
    }

    /* expanding halo ring */
    .grfx-ring {
        position: absolute;
        width: 34%;
        aspect-ratio: 1;
        border-radius: 50%;
        border: 2px solid rgba(255, 238, 198, .9);
        transform: scale(.3);
        opacity: 0;
    }

    .grfx.play .grfx-ring {
        animation: grfxRing .8s ease-out forwards;
    }

    /* light rays pouring out */
    .grfx-rays {
        position: absolute;
        width: 128%;
        aspect-ratio: 1;
        opacity: 0;
        background: repeating-conic-gradient(from 0deg,
                rgba(255, 241, 205, .55) 0deg 5deg, transparent 5deg 20deg);
        -webkit-mask: radial-gradient(circle, #000 6%, transparent 60%);
        mask: radial-gradient(circle, #000 6%, transparent 60%);
    }

    .grfx.play .grfx-rays {
        animation: grfxRays .8s ease-out forwards;
    }

    /* glint sweeping across — clipped to the box itself */
    .grfx-shinewrap {
        position: absolute;
        inset: var(--pad, 20px);
        overflow: hidden;
        border-radius: 10px;
    }

    .grfx-shine {
        position: absolute;
        top: -10%;
        bottom: -10%;
        left: -55%;
        width: 45%;
        background: linear-gradient(105deg, transparent, rgba(255, 255, 255, .8), transparent);
        transform: skewX(-16deg);
        opacity: 0;
    }

    .grfx.play .grfx-shine {
        animation: grfxShine .55s ease-in .04s forwards;
    }

    .grfx-sparks {
        position: absolute;
        inset: 0;
    }

    .grfx-spark {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 0 8px 2px rgba(255, 226, 165, .95);
        opacity: 0;
        transform: translate(-50%, -50%) scale(.3);
    }

    .grfx.play .grfx-spark {
        animation: grfxSpark .68s ease-out forwards;
    }

    @keyframes grfxBurst {
        0% { transform: scale(.2); opacity: 0; }
        18% { opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    @keyframes grfxRing {
        0% { transform: scale(.3); opacity: .9; }
        100% { transform: scale(1.9); opacity: 0; }
    }

    @keyframes grfxRays {
        0% { transform: rotate(-28deg) scale(.4); opacity: 0; }
        30% { opacity: .9; }
        100% { transform: rotate(18deg) scale(1.1); opacity: 0; }
    }

    @keyframes grfxShine {
        0% { left: -55%; opacity: 0; }
        25% { opacity: 1; }
        100% { left: 135%; opacity: 0; }
    }

    @keyframes grfxSpark {
        0% { transform: translate(-50%, -50%) scale(.3); opacity: 0; }
        20% { opacity: 1; }
        100% {
            transform: translate(calc(-50% + var(--dx)), calc(-50% + var(--dy))) scale(1);
            opacity: 0;
        }
    }

    /* ---- mobile: the CSS gift box actually opens ---- */
    .mobile-gift-box.is-opening .mgb-inner {
        transform: none;
    }

    .mobile-gift-box.is-opening .mgb-lid {
        animation: grfxLid .58s cubic-bezier(.3, .9, .3, 1) forwards;
    }

    .mobile-gift-box.is-opening .mgb-bow {
        animation: grfxBow .58s cubic-bezier(.3, .9, .3, 1) forwards;
    }

    .mobile-gift-box.is-opening .mgb-box {
        animation: grfxBoxGlow .7s ease forwards;
    }

    @keyframes grfxLid {
        0% { transform: translateY(0) rotate(0); }
        28% { transform: translateY(-14%) rotate(-5deg); }
        100% { transform: translateY(-165%) rotate(-26deg); opacity: 0; }
    }

    @keyframes grfxBow {
        0% { transform: translateX(-50%) translateY(0) scale(1) rotate(0); }
        28% { transform: translateX(-50%) translateY(-18%) scale(1); }
        100% { transform: translateX(-50%) translateY(-190%) scale(.7) rotate(20deg); opacity: 0; }
    }

    @keyframes grfxBoxGlow {
        0% { box-shadow: 0 10px 22px rgba(0, 0, 0, .45); }
        45% {
            box-shadow: 0 10px 22px rgba(0, 0, 0, .45),
                0 0 34px 10px rgba(255, 228, 160, .95),
                inset 0 22px 34px rgba(255, 241, 214, .85);
        }
        100% {
            box-shadow: 0 10px 22px rgba(0, 0, 0, .45),
                0 0 12px 2px rgba(255, 228, 160, .25);
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .grfx *,
        .mobile-gift-box.is-opening .mgb-lid,
        .mobile-gift-box.is-opening .mgb-bow,
        .mobile-gift-box.is-opening .mgb-box {
            animation-duration: .001s !important;
        }
    }
</style>

<div class="grfx-dim" id="grfxDim" aria-hidden="true"></div>

<script>
(function () {
    'use strict';
    if (typeof window.openGiftPage !== 'function') return;

    var original = window.openGiftPage;
    var busy = false;
    var dim = document.getElementById('grfxDim');
    var HANDOFF = 520;   // when to start the page's own loading/redirect
    var CLEANUP = 840;   // when to pull the flourish off the DOM

    function pickTarget(n) {
        if (window.matchMedia('(max-width: 1023px)').matches) {
            return document.querySelectorAll('.mobile-gift-box')[n - 1] || null;
        }
        return document.getElementById('gift' + n);
    }

    function playFX(target, done) {
        var r = target && target.getBoundingClientRect();
        if (!r || !r.width) { done(); return; }

        var cx = r.left + r.width / 2;
        var cy = r.top + r.height / 2;
        dim.style.setProperty('--gx', cx + 'px');
        dim.style.setProperty('--gy', cy + 'px');
        dim.classList.add('on');

        var pad = Math.max(r.width, r.height) * 0.28;
        var fx = document.createElement('div');
        fx.className = 'grfx';
        fx.style.setProperty('--pad', pad + 'px');
        fx.style.left = (r.left - pad) + 'px';
        fx.style.top = (r.top - pad) + 'px';
        fx.style.width = (r.width + pad * 2) + 'px';
        fx.style.height = (r.height + pad * 2) + 'px';

        var sparks = '';
        for (var i = 0; i < 9; i++) {
            var ang = Math.PI * 2 * (i / 9) + Math.random() * 0.5;
            var dist = 62 + Math.random() * 74;
            sparks += '<span class="grfx-spark" style="' +
                '--dx:' + (Math.cos(ang) * dist).toFixed(1) + 'px;' +
                '--dy:' + (Math.sin(ang) * dist).toFixed(1) + 'px;' +
                'animation-delay:' + (Math.random() * 0.09).toFixed(2) + 's"></span>';
        }
        fx.innerHTML =
            '<div class="grfx-shinewrap"><div class="grfx-shine"></div></div>' +
            '<div class="grfx-core">' +
            '<div class="grfx-rays"></div>' +
            '<div class="grfx-burst"></div>' +
            '<div class="grfx-ring"></div>' +
            '<div class="grfx-sparks">' + sparks + '</div>' +
            '</div>';
        document.body.appendChild(fx);

        if (target.classList.contains('mobile-gift-box')) {
            target.classList.add('is-opening');
        }

        requestAnimationFrame(function () { fx.classList.add('play'); });

        setTimeout(done, HANDOFF);
        setTimeout(function () { fx.remove(); }, CLEANUP);
    }

    window.openGiftPage = function (n) {
        if (busy) return;
        busy = true;
        playFX(pickTarget(n), function () { original(n); });
    };
})();
</script>
