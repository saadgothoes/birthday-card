import { gsap } from 'gsap';

export function initFinalCta() {
    const section = document.getElementById('finalCta');
    const content = document.getElementById('finalContent');
    if (!section || !content) return;

    // The closing panel used to be pinned for a screen and a half while an orb
    // swallowed the viewport. It now simply arrives as you reach it.
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const tween = gsap.from(content.children, {
            opacity: 0,
            y: 26,
            duration: 0.8,
            stagger: 0.12,
            ease: 'power3.out',
            scrollTrigger: { trigger: section, start: 'top 68%' },
        });

        return () => {
            tween.scrollTrigger && tween.scrollTrigger.kill();
            tween.kill();
        };
    });

    mm.add('(prefers-reduced-motion: reduce)', () => {
        gsap.set(content.children, { opacity: 1, y: 0 });
        return () => {};
    });
}
