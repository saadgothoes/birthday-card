import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const STATES = [
    {
        w: 320, h: 400, rtl: '26px', rtr: '26px', rbr: '26px', rbl: '26px',
        icon: '✶', label: 0, name: 'Midnight Gold',
        photo: 'https://images.unsplash.com/photo-1607344645866-009c320b63e0?w=260&h=340&fit=crop&q=75',
        caption: 'Espresso & gold, arch-framed, crown accent — the boy signature theme.',
    },
    {
        w: 300, h: 340, rtl: '26px', rtr: '26px', rbr: '6px', rbl: '6px',
        icon: '☁', label: 1, name: 'Light Blue Sky',
        photo: 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=260&h=340&fit=crop&q=75',
        caption: 'A softer, wide-format boy variant — pale blues and open sky.',
    },
    {
        w: 300, h: 400, rtl: '150px', rtr: '150px', rbr: '20px', rbl: '20px',
        icon: '❀', label: 2, name: 'Blush Petal',
        photo: 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=260&h=340&fit=crop&q=75',
        caption: 'Oval framing, rose-gold ribbon detailing — the girl signature theme.',
    },
    {
        w: 270, h: 420, rtl: '135px', rtr: '135px', rbr: '135px', rbl: '135px',
        icon: '✿', label: 3, name: 'Rose Bloom',
        photo: 'https://images.unsplash.com/photo-1576919228236-a097c32a5cd4?w=260&h=340&fit=crop&q=75',
        caption: 'Full oval seal with floral accents — a dreamier girl variant.',
    },
];

export function initMorph() {
    const section = document.getElementById('morph');
    if (!section) return;

    const card = document.getElementById('morphCard');
    const icon = document.getElementById('morphIcon');
    const photoA = document.getElementById('morphPhotoA');
    const photoB = document.getElementById('morphPhotoB');
    const topLabel = document.getElementById('morphTopLabel');
    const caption = document.getElementById('morphCaption');
    const labels = section.querySelectorAll('[data-morph-label]');

    let showingA = true;

    function setState(index) {
        const state = STATES[index];
        icon.textContent = state.icon;
        topLabel.textContent = state.name;
        caption.textContent = state.caption;
        labels.forEach((label) => label.classList.toggle('l-morph__label--active', Number(label.dataset.morphLabel) === state.label));

        const incoming = showingA ? photoB : photoA;
        const outgoing = showingA ? photoA : photoB;
        incoming.style.backgroundImage = `url('${state.photo}')`;
        gsap.to(incoming, { opacity: 1, duration: 0.4, ease: 'power1.out' });
        gsap.to(outgoing, { opacity: 0, duration: 0.4, ease: 'power1.out' });
        showingA = !showingA;
    }

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
                    end: '+=220%',
                    scrub: 1,
                    pin: '#morphStage',
                    anticipatePin: 1,
                },
            });

            for (let i = 0; i < STATES.length - 1; i += 1) {
                const to = STATES[i + 1];
                const segStart = i;

                tl.to(card, {
                    width: to.w,
                    height: to.h,
                    '--r-tl': to.rtl,
                    '--r-tr': to.rtr,
                    '--r-br': to.rbr,
                    '--r-bl': to.rbl,
                    duration: 1,
                    ease: 'none',
                }, segStart);

                tl.call(() => {
                    const dir = tl.scrollTrigger ? tl.scrollTrigger.direction : 1;
                    setState(dir > 0 ? i + 1 : i);
                }, null, segStart + 0.5);
            }

            return () => tl.scrollTrigger && tl.scrollTrigger.kill();
        }

        setState(0);
        return () => {};
    });
}
