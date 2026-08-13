import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

function relCenter(el, container) {
    const r = el.getBoundingClientRect();
    const c = container.getBoundingClientRect();
    return { x: r.left + r.width / 2 - c.left, y: r.top + r.height / 2 - c.top };
}

export function initBuilderDemo() {
    const section = document.getElementById('builder');
    const stage = document.getElementById('builderStage');
    if (!section || !stage) return;

    const cursor = document.getElementById('fakeCursor');
    const ring = cursor.querySelector('.fake-cursor__ring');
    const themeA = document.getElementById('builderThemeA');
    const themeB = document.getElementById('builderThemeB');
    const upload = document.getElementById('builderUpload');
    const crop = document.getElementById('builderCrop');
    const photoChip = document.getElementById('builderPhotoChip');
    const pinDots = section.querySelectorAll('[data-pin-dot]');
    const tabs = section.querySelectorAll('[data-b-tab]');

    let tl = null;
    let st = null;

    function build() {
        if (tl) tl.kill();
        if (st) st.kill();

        gsap.set(cursor, { opacity: 0, x: relCenter(themeA, stage).x, y: relCenter(themeA, stage).y });
        gsap.set(ring, { opacity: 0, scale: 0.4 });
        gsap.set(photoChip, { opacity: 0, ...relCenter(upload, stage) });
        gsap.set(pinDots, { clearProps: 'all' });

        const pTheme = relCenter(themeA, stage);
        const pUpload = relCenter(upload, stage);
        const pCrop = relCenter(crop, stage);
        const pTab2 = relCenter(tabs[1], stage);
        const pTab3 = relCenter(tabs[2], stage);

        tl = gsap.timeline({
            scrollTrigger: {
                trigger: section,
                start: 'top top',
                end: '+=280%',
                scrub: 1,
                pin: stage,
                anticipatePin: 1,
            },
        });
        st = tl.scrollTrigger;

        // 1. cursor appears, clicks theme A
        tl.to(cursor, { opacity: 1, duration: 0.3 }, 0)
            .to(themeA, { borderColor: 'var(--purple)', boxShadow: '0 14px 30px -14px rgba(139,92,246,.5)', duration: 0.3 }, 0.15)
            .to(ring, { opacity: 1, scale: 1, duration: 0.25 }, 0.15)
            .to(ring, { opacity: 0, duration: 0.2 }, 0.35)
            .to(themeB, { opacity: 0.45, duration: 0.3 }, 0.15);

        // 2. move to upload zone, "click"
        tl.to(cursor, { x: pUpload.x, y: pUpload.y, duration: 0.5, ease: 'power2.inOut' }, 0.45)
            .to(upload, { borderColor: 'var(--purple)', backgroundColor: 'var(--purple-soft)', duration: 0.3 }, 0.85);

        // 3. drag photo chip from upload zone into the crop frame
        tl.to(photoChip, { opacity: 1, duration: 0.2 }, 0.9)
            .to(cursor, { x: pCrop.x, y: pCrop.y, duration: 0.55, ease: 'power2.inOut' }, 1.0)
            .to(photoChip, { x: pCrop.x, y: pCrop.y, duration: 0.55, ease: 'power2.inOut' }, 1.0)
            .to(photoChip, { opacity: 0, scale: 0.6, duration: 0.25 }, 1.5)
            .to(crop, { backgroundColor: 'var(--purple)', color: '#fff', duration: 0.3 }, 1.55);

        // 4. move to pin row, fill digits one by one
        const pinY = relCenter(pinDots[0], stage);
        tl.to(cursor, { x: pinY.x, y: pinY.y, duration: 0.5, ease: 'power2.inOut' }, 1.85);
        pinDots.forEach((dot, i) => {
            tl.to(dot, { borderColor: 'var(--purple)', duration: 0.15 }, 2.1 + i * 0.12);
        });

        // 5. move to gift tab 2, activate
        tl.to(cursor, { x: pTab2.x, y: pTab2.y, duration: 0.5, ease: 'power2.inOut' }, 2.7)
            .to(tabs[0], { borderColor: 'var(--line)', color: 'var(--ink-faint)', backgroundColor: 'var(--white)', duration: 0.3 }, 3.05)
            .to(tabs[1], { borderColor: 'var(--purple)', color: 'var(--purple)', backgroundColor: 'var(--purple-soft)', duration: 0.3 }, 3.05);

        // 6. move to gift tab 3, activate
        tl.to(cursor, { x: pTab3.x, y: pTab3.y, duration: 0.5, ease: 'power2.inOut' }, 3.4)
            .to(tabs[1], { borderColor: 'var(--line)', color: 'var(--ink-faint)', backgroundColor: 'var(--white)', duration: 0.3 }, 3.75)
            .to(tabs[2], { borderColor: 'var(--purple)', color: 'var(--purple)', backgroundColor: 'var(--purple-soft)', duration: 0.3 }, 3.75);

        tl.to(cursor, { opacity: 0, duration: 0.3 }, 4.1);
    }

    const mm = gsap.matchMedia();

    mm.add({
        desktop: '(min-width: 901px) and (prefers-reduced-motion: no-preference)',
        reduced: '(max-width: 900px), (prefers-reduced-motion: reduce)',
    }, (context) => {
        const { desktop } = context.conditions;

        if (!desktop) {
            gsap.set(cursor, { opacity: 0 });
            gsap.set(pinDots, { borderColor: 'var(--purple)' });
            return () => {};
        }

        build();

        let resizeTimer;
        const onResize = () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(build, 250);
        };
        window.addEventListener('resize', onResize);

        return () => {
            window.removeEventListener('resize', onResize);
            if (tl) tl.kill();
        };
    });
}
