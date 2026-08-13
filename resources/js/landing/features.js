import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

function buildConfetti(container, count = 16) {
    const pieces = [];
    for (let i = 0; i < count; i += 1) {
        const el = document.createElement('i');
        container.appendChild(el);
        pieces.push(el);
    }
    return pieces;
}

function initGiftFeature(row) {
    const gift = row.querySelector('#fvGift');
    const lid = row.querySelector('#fvLid');
    const confettiWrap = row.querySelector('#fvConfetti');
    if (!gift) return null;

    const pieces = buildConfetti(confettiWrap);

    const tl = gsap.timeline({ paused: true });
    tl.to(gift, { x: -4, duration: 0.08 })
        .to(gift, { x: 4, duration: 0.08 })
        .to(gift, { x: -3, duration: 0.08 })
        .to(gift, { x: 0, duration: 0.08 })
        .to(lid, { rotate: -35, y: -18, x: -6, duration: 0.45, ease: 'back.out(2)' }, 0.4)
        .to(pieces, {
            opacity: 1,
            x: () => gsap.utils.random(-70, 70),
            y: () => gsap.utils.random(-90, -10),
            rotate: () => gsap.utils.random(-180, 180),
            duration: 0.7,
            ease: 'power2.out',
            stagger: 0.02,
        }, 0.55)
        .to(pieces, { opacity: 0, y: '+=30', duration: 0.5 }, 1.1);

    return tl;
}

function initPreviewFeature(row) {
    const lines = row.querySelectorAll('.fv-preview__line');
    if (!lines.length) return null;
    const tl = gsap.timeline({ paused: true });
    tl.to(lines, { scaleX: 1, duration: 0.5, stagger: 0.15, ease: 'power2.out' });
    return tl;
}

function initPinFeature(row) {
    const spans = row.querySelectorAll('[data-pin]');
    if (!spans.length) return null;
    const tl = gsap.timeline({ paused: true });
    tl.to(spans, { opacity: 1, y: 0, duration: 0.35, stagger: 0.12, ease: 'back.out(2)' });
    return tl;
}

function initQrFeature(row) {
    const qr = row.querySelector('#fvQr');
    if (!qr) return null;
    qr.innerHTML = '';
    const cells = [];
    for (let i = 0; i < 25; i += 1) {
        const el = document.createElement('i');
        if (Math.random() > 0.42) qr.appendChild(el);
        cells.push(el);
    }
    const tl = gsap.timeline({ paused: true });
    tl.to(qr.children, { scale: 1, duration: 0.35, stagger: { each: 0.02, from: 'random' }, ease: 'back.out(2)' });
    return tl;
}

function initMosaicFeature(row) {
    const tiles = row.querySelectorAll('.fv-mosaic__tile');
    if (!tiles.length) return null;
    const tl = gsap.timeline({ paused: true });
    tl.to(tiles, {
        opacity: 1,
        scale: 1,
        rotate: 0,
        duration: 0.55,
        stagger: { each: 0.12, from: 'start', grid: [2, 2] },
        ease: 'back.out(1.8)',
    });
    return tl;
}

export function initFeatures() {
    const rows = document.querySelectorAll('[data-feature]');
    if (!rows.length) return;

    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const builders = [initPreviewFeature, initPinFeature, initGiftFeature, initQrFeature, initMosaicFeature];
        const triggers = [];

        rows.forEach((row, i) => {
            const build = builders[i] || initPreviewFeature;
            const tl = build(row);
            if (!tl) return;

            const st = ScrollTrigger.create({
                trigger: row,
                start: 'top 72%',
                onEnter: () => tl.play(),
                onLeaveBack: () => tl.pause(0),
            });
            triggers.push(st);

            const textTl = gsap.timeline({
                scrollTrigger: { trigger: row, start: 'top 75%' },
            });
            textTl
                .from(row.querySelector('.feature-row__text'), { opacity: 0, y: 30, duration: 0.8, ease: 'power3.out' })
                .to(row.querySelectorAll('.feature-row__tag'), { opacity: 1, y: 0, duration: 0.4, stagger: 0.08, ease: 'power2.out' }, 0.3)
                .fromTo(row.querySelector('.feature-row__visual'), { opacity: 0, scale: 0.94 }, { opacity: 1, scale: 1, duration: 0.8, ease: 'power3.out' }, 0);
        });

        return () => triggers.forEach((st) => st.kill());
    });

    mm.add('(prefers-reduced-motion: reduce)', () => {
        rows.forEach((row) => {
            row.querySelectorAll('.fv-preview__line').forEach((l) => gsap.set(l, { scaleX: 1 }));
            row.querySelectorAll('[data-pin]').forEach((p) => gsap.set(p, { opacity: 1, y: 0 }));
            row.querySelectorAll('.feature-row__tag').forEach((t) => gsap.set(t, { opacity: 1, y: 0 }));
            row.querySelectorAll('.fv-mosaic__tile').forEach((t) => gsap.set(t, { opacity: 1, scale: 1, rotate: 0 }));
        });
        return () => {};
    });
}
