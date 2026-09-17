import { gsap } from 'gsap';

export function initHero() {
    const section = document.getElementById('hero');
    if (!section) return;

    const lines = section.querySelectorAll('[data-line] span');
    const bits = section.querySelectorAll('[data-hero-in]');
    const visual = document.getElementById('heroVisual');
    const floats = visual ? visual.querySelectorAll('[data-parallax]') : [];

    // The hero used to pin for a screen and a half before you could read it.
    // It now just plays once, on load.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        gsap.set([lines, bits, visual], { opacity: 1, y: 0, yPercent: 0 });
        return;
    }

    gsap.set(lines, { yPercent: 115 });

    gsap.timeline({ delay: 0.12 })
        .to(lines, { yPercent: 0, duration: 0.95, ease: 'power4.out', stagger: 0.09 }, 0.1)
        .from(bits, { opacity: 0, y: 18, duration: 0.7, ease: 'power3.out', stagger: 0.09 }, 0.35)
        .from(visual, { opacity: 0, y: 40, scale: 0.94, duration: 1, ease: 'power3.out' }, 0.25);

    if (!visual || !floats.length) return;

    // Depth on pointer move: each layer drifts by its own weight.
    if (window.matchMedia('(hover: none), (pointer: coarse)').matches) return;

    const movers = Array.from(floats).map((el) => ({
        el,
        depth: parseFloat(el.dataset.parallax) || 4,
        x: gsap.quickTo(el, 'x', { duration: 0.9, ease: 'power3.out' }),
        y: gsap.quickTo(el, 'y', { duration: 0.9, ease: 'power3.out' }),
    }));

    section.addEventListener('mousemove', (e) => {
        const rect = section.getBoundingClientRect();
        const rx = (e.clientX - rect.left) / rect.width - 0.5;
        const ry = (e.clientY - rect.top) / rect.height - 0.5;
        movers.forEach((m) => { m.x(rx * m.depth * 6); m.y(ry * m.depth * 6); });
    });

    section.addEventListener('mouseleave', () => {
        movers.forEach((m) => { m.x(0); m.y(0); });
    });
}
