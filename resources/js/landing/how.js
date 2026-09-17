import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

function relCenter(el, container) {
    const r = el.getBoundingClientRect();
    const c = container.getBoundingClientRect();
    return { x: r.left + r.width / 2 - c.left, y: r.top + r.height / 2 - c.top };
}

export function initHow() {
    const section = document.getElementById('how');
    const stage = document.getElementById('demoStage');
    if (!section) return;

    const steps = section.querySelectorAll('[data-step]');

    if (steps.length && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        gsap.from(steps, {
            opacity: 0,
            y: 26,
            duration: 0.7,
            stagger: 0.12,
            ease: 'power3.out',
            scrollTrigger: { trigger: '.l-steps', start: 'top 82%' },
        });
    }

    if (!stage) return;

    const cursor = document.getElementById('demoCursor');
    const ring = cursor.querySelector('.fake-cursor__ring');
    const themeA = document.getElementById('demoThemeA');
    const themeB = document.getElementById('demoThemeB');
    const upload = document.getElementById('demoUpload');
    const chip = document.getElementById('demoChip');
    const pinDots = stage.querySelectorAll('[data-pin-dot]');
    const tabs = stage.querySelectorAll('[data-b-tab]');

    const mm = gsap.matchMedia();

    mm.add({
        play: '(min-width: 861px) and (prefers-reduced-motion: no-preference)',
        still: '(max-width: 860px), (prefers-reduced-motion: reduce)',
    }, (context) => {
        if (!context.conditions.play) {
            // Small screens get the finished state rather than a cursor they
            // cannot follow.
            gsap.set(cursor, { opacity: 0 });
            gsap.set(chip, { opacity: 0 });
            gsap.set(pinDots, { borderColor: 'var(--purple)' });
            return () => {};
        }

        let tl = null;
        let trigger = null;

        // Scroll no longer scrubs this — it loops on its own while it is on
        // screen, so the section costs one screen of height instead of three.
        function build() {
            if (tl) tl.kill();

            const pUpload = relCenter(upload, stage);
            const pPin = relCenter(pinDots[0], stage);
            const pTab2 = relCenter(tabs[1], stage);
            const pTab3 = relCenter(tabs[2], stage);
            const pTheme = relCenter(themeA, stage);

            gsap.set(cursor, { opacity: 0, x: pTheme.x, y: pTheme.y });
            gsap.set(ring, { opacity: 0, scale: 0.4 });
            gsap.set(chip, { opacity: 0, x: pUpload.x, y: pUpload.y, scale: 1 });
            gsap.set(pinDots, { clearProps: 'all' });

            tl = gsap.timeline({ repeat: -1, repeatDelay: 1.1, paused: true });

            // 1 — land on the theme card and pick it
            tl.to(cursor, { opacity: 1, duration: 0.3 }, 0)
                .to(ring, { opacity: 1, scale: 1, duration: 0.25 }, 0.3)
                .to(ring, { opacity: 0, duration: 0.2 }, 0.55)
                .to(themeA, { borderColor: 'var(--purple)', boxShadow: '0 14px 30px -14px rgba(139,92,246,.5)', duration: 0.3 }, 0.35)
                .to(themeB, { opacity: 0.45, duration: 0.3 }, 0.35)

                // 2 — drag a photo into the upload zone
                .to(cursor, { x: pUpload.x, y: pUpload.y, duration: 0.6, ease: 'power2.inOut' }, 0.9)
                .to(upload, { borderColor: 'var(--purple)', backgroundColor: 'var(--purple-soft)', duration: 0.3 }, 1.4)
                .to(chip, { opacity: 1, duration: 0.2 }, 1.45)
                .to(chip, { opacity: 0, scale: 0.6, duration: 0.3 }, 1.9)

                // 3 — tap out the lock code
                .to(cursor, { x: pPin.x, y: pPin.y, duration: 0.55, ease: 'power2.inOut' }, 2.0);

            pinDots.forEach((dot, i) => {
                tl.to(dot, { borderColor: 'var(--purple)', backgroundColor: 'var(--purple-soft)', duration: 0.15 }, 2.5 + i * 0.13);
            });

            // 4 — flick through the gift tabs
            tl.to(cursor, { x: pTab2.x, y: pTab2.y, duration: 0.5, ease: 'power2.inOut' }, 3.15)
                .to(tabs[0], { borderColor: 'var(--line)', color: 'var(--ink-faint)', backgroundColor: 'var(--white)', duration: 0.3 }, 3.5)
                .to(tabs[1], { borderColor: 'var(--purple)', color: 'var(--purple)', backgroundColor: 'var(--purple-soft)', duration: 0.3 }, 3.5)
                .to(cursor, { x: pTab3.x, y: pTab3.y, duration: 0.5, ease: 'power2.inOut' }, 3.85)
                .to(tabs[1], { borderColor: 'var(--line)', color: 'var(--ink-faint)', backgroundColor: 'var(--white)', duration: 0.3 }, 4.2)
                .to(tabs[2], { borderColor: 'var(--purple)', color: 'var(--purple)', backgroundColor: 'var(--purple-soft)', duration: 0.3 }, 4.2)
                .to(cursor, { opacity: 0, duration: 0.35 }, 4.6)

                // …and reset, so the loop starts clean
                .set([themeA, themeB, upload, tabs[0], tabs[2], pinDots], { clearProps: 'all' }, 5.1)
                .set(tabs[0], { borderColor: 'var(--purple)', color: 'var(--purple)', backgroundColor: 'var(--purple-soft)' }, 5.1);

            if (!trigger) {
                trigger = ScrollTrigger.create({
                    trigger: stage,
                    start: 'top 85%',
                    end: 'bottom 15%',
                    onEnter: () => tl.play(0),
                    onEnterBack: () => tl.play(),
                    onLeave: () => tl.pause(),
                    onLeaveBack: () => tl.pause(),
                });
            } else if (trigger.isActive) {
                tl.play(0);
            }
        }

        build();

        let resizeTimer;
        const onResize = () => { clearTimeout(resizeTimer); resizeTimer = setTimeout(build, 250); };
        window.addEventListener('resize', onResize);

        return () => {
            window.removeEventListener('resize', onResize);
            if (tl) tl.kill();
            if (trigger) trigger.kill();
        };
    });
}
