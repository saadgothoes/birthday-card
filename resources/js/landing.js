import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { initCursor } from './landing/cursor';
import { initMagneticButtons } from './landing/magneticButton';
import { initNavbar } from './landing/navbar';
import { initHero } from './landing/hero';
import { initShowcase } from './landing/showcase';
import { initBuilderDemo } from './landing/builderDemo';
import { initCreation } from './landing/creation';
import { initMorph } from './landing/morph';
import { initGallery } from './landing/gallery';
import { initFeatures } from './landing/features';
import { initFinalCta } from './landing/finalCta';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('.landing');
    if (!root) return;

    const isTouch = window.matchMedia('(hover: none), (pointer: coarse)').matches;

    const ctx = gsap.context(() => {
        initNavbar();
        if (!isTouch) initCursor();
        initMagneticButtons(isTouch);

        initHero();
        initShowcase();
        initBuilderDemo();
        initCreation();
        initMorph();
        initGallery();
        initFeatures();
        initFinalCta();
    }, root);

    window.addEventListener('beforeunload', () => ctx.revert());

    ScrollTrigger.refresh();
});
