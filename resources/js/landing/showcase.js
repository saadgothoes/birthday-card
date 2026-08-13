import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export function initShowcase() {
    const section = document.getElementById('showcase');
    if (!section) return;

    const center = document.getElementById('showcaseCenter');
    const cards = section.querySelectorAll('[data-orbit-card]');

    const positions = {
        tl: { x: -300, y: -170, rotate: -8 },
        tr: { x: 300, y: -170, rotate: 8 },
        bl: { x: -300, y: 170, rotate: 6 },
        br: { x: 300, y: 170, rotate: -6 },
    };

    gsap.set(cards, { opacity: 0, scale: 0.5 });

    const mm = gsap.matchMedia();

    mm.add({
        desktop: '(min-width: 901px) and (prefers-reduced-motion: no-preference)',
        reduced: '(max-width: 900px), (prefers-reduced-motion: reduce)',
    }, (context) => {
        const { desktop } = context.conditions;

        if (desktop) {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: section,
                    start: 'top top',
                    end: '+=130%',
                    scrub: 1,
                    pin: '#showcaseStage',
                    anticipatePin: 1,
                },
            });

            tl.to(center, { scale: 0.72, rotate: -4, duration: 1, ease: 'none' }, 0);

            cards.forEach((card) => {
                const key = card.dataset.orbitCard;
                const pos = positions[key];
                tl.to(card, {
                    opacity: 1,
                    scale: 1,
                    x: pos.x,
                    y: pos.y,
                    rotate: pos.rotate,
                    duration: 1,
                    ease: 'none',
                }, 0.15);
            });

            return () => tl.scrollTrigger && tl.scrollTrigger.kill();
        }

        const scaledPositions = { tl: { x: -90, y: -120 }, tr: { x: 90, y: -120 }, bl: { x: -90, y: 120 }, br: { x: 90, y: 120 } };
        gsap.set(center, { scale: 0.8 });
        cards.forEach((card) => {
            const key = card.dataset.orbitCard;
            gsap.set(card, { opacity: 1, scale: 0.7, x: scaledPositions[key].x, y: scaledPositions[key].y });
        });

        return () => {};
    });
}
