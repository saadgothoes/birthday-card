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

    // Below 860px the links live in a drawer instead of disappearing.
    const burger = document.getElementById('navBurger');
    const drawer = document.getElementById('navDrawer');
    if (!burger || !drawer) return;

    const close = () => {
        nav.classList.remove('is-open');
        burger.setAttribute('aria-expanded', 'false');
    };

    burger.addEventListener('click', () => {
        const open = nav.classList.toggle('is-open');
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    drawer.querySelectorAll('a').forEach((a) => a.addEventListener('click', close));
    window.addEventListener('resize', () => { if (window.innerWidth > 860) close(); });
}
