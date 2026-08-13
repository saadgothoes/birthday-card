import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export function initNavbar() {
    const nav = document.getElementById('lNav');
    if (!nav) return;

    ScrollTrigger.create({
        start: 40,
        end: 99999,
        onUpdate: (self) => {
            nav.classList.toggle('l-nav--float', self.scroll() > 40);
        },
    });

    gsap.set(nav, { y: -20, opacity: 0 });
    gsap.to(nav, { y: 0, opacity: 1, duration: 0.8, ease: 'power3.out', delay: 0.1 });
}
