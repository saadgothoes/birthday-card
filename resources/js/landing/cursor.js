import { gsap } from 'gsap';

export function initCursor() {
    const cursor = document.getElementById('lCursor');
    if (!cursor) return;

    document.documentElement.classList.add('has-custom-cursor');

    const dot = cursor.querySelector('.l-cursor__dot');
    const ring = cursor.querySelector('.l-cursor__ring');

    const xDot = gsap.quickTo(dot, 'x', { duration: 0.12, ease: 'power3.out' });
    const yDot = gsap.quickTo(dot, 'y', { duration: 0.12, ease: 'power3.out' });
    const xRing = gsap.quickTo(ring, 'x', { duration: 0.35, ease: 'power3.out' });
    const yRing = gsap.quickTo(ring, 'y', { duration: 0.35, ease: 'power3.out' });

    window.addEventListener('mousemove', (e) => {
        xDot(e.clientX);
        yDot(e.clientY);
        xRing(e.clientX);
        yRing(e.clientY);
    });

    window.addEventListener('mousedown', () => cursor.classList.add('is-down'));
    window.addEventListener('mouseup', () => cursor.classList.remove('is-down'));

    document.addEventListener('mouseover', (e) => {
        if (e.target.closest('[data-cursor-hover]')) cursor.classList.add('is-hover');
    });
    document.addEventListener('mouseout', (e) => {
        if (e.target.closest('[data-cursor-hover]')) cursor.classList.remove('is-hover');
    });

    document.addEventListener('mouseleave', () => { cursor.style.opacity = '0'; });
    document.addEventListener('mouseenter', () => { cursor.style.opacity = '1'; });
}
