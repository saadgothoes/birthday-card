import { gsap } from 'gsap';

export function initMagneticButtons(isTouch) {
    const buttons = document.querySelectorAll('[data-magnetic]');
    if (isTouch) return;

    buttons.forEach((btn) => {
        const strength = 0.35;

        const xTo = gsap.quickTo(btn, 'x', { duration: 0.5, ease: 'power3.out' });
        const yTo = gsap.quickTo(btn, 'y', { duration: 0.5, ease: 'power3.out' });

        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const relX = e.clientX - rect.left - rect.width / 2;
            const relY = e.clientY - rect.top - rect.height / 2;
            xTo(relX * strength);
            yTo(relY * strength);
        });

        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { x: 0, y: 0, duration: 0.7, ease: 'elastic.out(1, 0.4)' });
        });

        btn.addEventListener('mousedown', () => gsap.to(btn, { scale: 0.94, duration: 0.15 }));
        btn.addEventListener('mouseup', () => gsap.to(btn, { scale: 1, duration: 0.3, ease: 'back.out(2)' }));
    });
}
