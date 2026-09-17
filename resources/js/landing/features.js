import { gsap } from 'gsap';

export function initFeatures() {
    const cards = document.querySelectorAll('[data-feature]');
    if (!cards.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    // Four cards on one band — they arrive together rather than one screen
    // at a time, which is what the old five-row layout cost.
    gsap.from(cards, {
        opacity: 0,
        y: 24,
        duration: 0.65,
        stagger: 0.09,
        ease: 'power3.out',
        scrollTrigger: { trigger: '#features', start: 'top 80%' },
    });
}
