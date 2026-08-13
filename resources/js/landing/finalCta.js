import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export function initFinalCta() {
    const section = document.getElementById('finalCta');
    const orb = document.getElementById('finalOrb');
    const content = document.getElementById('finalContent');
    if (!section || !orb) return;

    const mm = gsap.matchMedia();

    mm.add({
        desktop: '(prefers-reduced-motion: no-preference)',
        reduced: '(prefers-reduced-motion: reduce)',
    }, (context) => {
        const { desktop } = context.conditions;

        if (desktop) {
            const targetScale = () => (Math.max(window.innerWidth, window.innerHeight) * 1.6) / 60;

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: section,
                    start: 'top top',
                    end: '+=120%',
                    scrub: 1,
                    pin: true,
                    anticipatePin: 1,
                },
            });

            tl.to(orb, { scale: () => targetScale(), duration: 1, ease: 'none' }, 0)
                .to(content, { opacity: 1, duration: 0.4, ease: 'none' }, 0.55);

            return () => tl.scrollTrigger && tl.scrollTrigger.kill();
        }

        gsap.set(orb, { scale: 40 });
        gsap.set(content, { opacity: 1 });
        return () => {};
    });
}
