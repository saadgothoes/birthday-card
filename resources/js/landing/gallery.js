import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export function initGallery() {
    const pin = document.getElementById('galleryPin');
    const track = document.getElementById('galleryTrack');
    const progressFill = document.getElementById('galleryProgressFill');
    if (!pin || !track) return;

    const cards = track.querySelectorAll('.pcard');
    const tiltCards = track.querySelectorAll('[data-tilt]');

    function updateCardFocus(self) {
        const center = window.innerWidth / 2;
        cards.forEach((card) => {
            const rect = card.getBoundingClientRect();
            const cardCenter = rect.left + rect.width / 2;
            const dist = Math.abs(center - cardCenter);
            const proximity = gsap.utils.clamp(0, 1, 1 - dist / (window.innerWidth * 0.6));
            const scale = gsap.utils.interpolate(0.82, 1.08, proximity);
            const rotateY = gsap.utils.clamp(-18, 18, (cardCenter - center) / 14);
            gsap.set(card, { scale, rotateY, opacity: gsap.utils.interpolate(0.55, 1, proximity) });
        });

        if (progressFill && self) {
            gsap.set(progressFill, { width: `${self.progress * 100}%` });
        }
    }

    function initTilt() {
        tiltCards.forEach((card) => {
            const rotX = gsap.quickTo(card, 'rotateX', { duration: 0.4, ease: 'power3.out' });
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const relY = (e.clientY - rect.top - rect.height / 2) / rect.height;
                rotX(gsap.utils.clamp(-12, 12, -relY * 20));
            });
            card.addEventListener('mouseleave', () => rotX(0));
        });
    }

    const mm = gsap.matchMedia();

    mm.add({
        desktop: '(min-width: 901px) and (prefers-reduced-motion: no-preference)',
        reduced: '(max-width: 900px), (prefers-reduced-motion: reduce)',
    }, (context) => {
        const { desktop } = context.conditions;

        if (desktop) {
            const getScrollDistance = () => Math.max(0, track.scrollWidth - window.innerWidth + 160);

            const tween = gsap.to(track, {
                x: () => -getScrollDistance(),
                ease: 'none',
                scrollTrigger: {
                    trigger: pin,
                    start: 'top top',
                    end: () => `+=${getScrollDistance()}`,
                    scrub: 1,
                    pin: true,
                    anticipatePin: 1,
                    invalidateOnRefresh: true,
                    onUpdate: updateCardFocus,
                },
            });

            updateCardFocus({ progress: 0 });
            if (!('ontouchstart' in window)) initTilt();

            return () => {
                tween.scrollTrigger && tween.scrollTrigger.kill();
                tween.kill();
            };
        }

        gsap.set(cards, { scale: 1, rotateY: 0, opacity: 1 });
        if (progressFill) gsap.set(progressFill, { width: '100%' });
        return () => {};
    });
}
