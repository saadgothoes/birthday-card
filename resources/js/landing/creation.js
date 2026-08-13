import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export function initCreation() {
    const section = document.getElementById('creation');
    if (!section) return;

    const nav = document.getElementById('canvasNav');
    const heading = document.getElementById('canvasHeading');
    const frame = document.getElementById('canvasFrame');
    const cards = document.querySelectorAll('#canvasCards .pcard');
    const sweep = document.getElementById('canvasSweep');

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
                    end: '+=200%',
                    scrub: 1,
                    pin: '#creationStage',
                    anticipatePin: 1,
                },
            });

            tl.to(nav, { width: '100%', duration: 1, ease: 'none' }, 0)
                .to(nav.querySelector('.canvas-nav__logo'), { opacity: 1, duration: 0.4 }, 0.5)
                .to(heading, { clipPath: 'inset(0 0% 0 0)', duration: 1, ease: 'none' }, 0.7)
                .to(frame, { opacity: 1, scale: 1, duration: 0.8, ease: 'power2.out' }, 1.5)
                .to(cards, { opacity: 1, y: 0, stagger: 0.15, duration: 0.8, ease: 'power2.out' }, 1.9)
                .fromTo(sweep, { xPercent: -140 }, { xPercent: 240, duration: 1.1, ease: 'power1.inOut' }, 2.5);

            return () => tl.scrollTrigger && tl.scrollTrigger.kill();
        }

        gsap.set(nav, { width: '100%' });
        gsap.set(nav.querySelector('.canvas-nav__logo'), { opacity: 1 });
        gsap.set(heading, { clipPath: 'inset(0 0% 0 0)' });
        gsap.set(frame, { opacity: 1, scale: 1 });
        gsap.set(cards, { opacity: 1, y: 0 });

        return () => {};
    });
}
