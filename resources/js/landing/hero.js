import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export function initHero() {
    const section = document.getElementById('hero');
    if (!section) return;

    const lines = section.querySelectorAll('[data-line] span');
    const sub = document.getElementById('heroSub');
    const actions = document.getElementById('heroActions');
    const mock = document.getElementById('heroMock');
    const eyebrow = section.querySelector('.eyebrow');

    // Entrance (plays once, independent of scroll direction)
    gsap.set(lines, { yPercent: 115 });

    const intro = gsap.timeline({ delay: 0.15 });
    intro
        .from(eyebrow, { opacity: 0, y: 14, duration: 0.6, ease: 'power3.out' })
        .to(lines, { yPercent: 0, duration: 0.9, ease: 'power4.out', stagger: 0.1 }, 0.1)
        .from(sub, { opacity: 0, y: 18, duration: 0.7, ease: 'power3.out' }, 0.55)
        .from(actions, { opacity: 0, y: 18, duration: 0.7, ease: 'power3.out' }, 0.68)
        .from(mock, { opacity: 0, scale: 0.5, duration: 0.9, ease: 'power3.out' }, 0.4);

    const mm = gsap.matchMedia();

    mm.add({
        desktop: '(min-width: 901px) and (prefers-reduced-motion: no-preference)',
        reduced: '(max-width: 900px), (prefers-reduced-motion: reduce)',
    }, (context) => {
        const { desktop } = context.conditions;

        if (desktop) {
            const steps = section.querySelectorAll('[data-mock-step]');
            const cards = section.querySelectorAll('[data-mock-card]');
            const brand = document.getElementById('mockBrand');
            const topbar = document.getElementById('mockTopbar');

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: section,
                    start: 'top top',
                    end: '+=160%',
                    scrub: 1,
                    pin: true,
                    anticipatePin: 1,
                },
            });

            tl.to([eyebrow, lines, sub, actions], { opacity: 0, yPercent: -40, duration: 1, ease: 'none' }, 0)
                .to(mock, { scale: 1.05, duration: 1.4, ease: 'none' }, 0.05)
                .to(brand, { opacity: 1, duration: 0.4 }, 0.25)
                .to(steps, { opacity: 1, x: 0, stagger: 0.08, duration: 0.6 }, 0.3)
                .to(topbar, { opacity: 1, duration: 0.4 }, 0.55)
                .to(cards, { opacity: 1, y: 0, stagger: 0.12, duration: 0.6 }, 0.6)
                .to(mock, { scale: 1.35, duration: 1, ease: 'none' }, 0.85);

            return () => tl.scrollTrigger && tl.scrollTrigger.kill();
        }

        // Reduced-motion / mobile: simple reveal, no pin
        gsap.set([section.querySelectorAll('[data-mock-step]'), section.querySelectorAll('[data-mock-card]'), '#mockBrand', '#mockTopbar'], {
            opacity: 1, x: 0, y: 0,
        });

        return () => {};
    });
}
