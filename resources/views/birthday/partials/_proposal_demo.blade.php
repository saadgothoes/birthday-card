{{--
    Proposal · the looping demo.

    Included by all four designs; inert unless the page is asked for with
    `?demo=1`. It exists so the dashboard's design cards can show the *whole*
    flow rather than a thumbnail of one moment — the client watches the thread
    type itself out (or the foil come off, or the stars join), the question
    arrive, the No button run away, the Yes open its letter, then it starts
    again.

    It is not a video file. It is the real page driving itself: the same code
    path a recipient triggers with a real tap, played by a script instead of a
    finger. So a demo can never drift out of step with the design, and there is
    nothing to re-render when a design changes.

    Each design registers its own hooks before including this:

        window.__proposalDemo = {
            phases: [0, 760, 2400],   ms after open() when beats 1, 2 and 3 land
            open:  function () {},    what a tap does
            yes:   function () {},    what pressing Yes does
            reset: function () {},    put it back to the very beginning
        };

    The five beats it reports (0-4) are the five in PROPOSAL_DESIGNS[n]['beats'],
    posted to the parent window as `{proposalBeat: n}` so the dashboard card can
    light up the matching chip as the demo reaches it. The current beat is also
    written to `<html data-pd-beat>`, which is what makes the loop testable.

    Everything after the tap runs off the design's own `phases`, because the
    design already knows how long its sequence takes — the same numbers drive
    the captions and the moment the No button starts running, so the two can
    never drift apart.
--}}
@if (request()->boolean('demo'))
<style>
    /* Nothing in a demo should look interactive — it is a moving picture of the
       page, not the page. The cursor and the focus rings would both be lies. */
    html.pd-demo, html.pd-demo * {
        cursor: default !important;
    }

    html.pd-demo *:focus,
    html.pd-demo *:focus-visible {
        outline: none !important;
    }
</style>
<script>
    (function () {
        document.documentElement.classList.add('pd-demo');

        var api = window.__proposalDemo;
        if (!api) return;

        var IDLE_BEFORE_TAP = 1100;   // long enough to read the invitation
        var AFTER_QUESTION = 900;     // before the No button starts running
        var NUDGE_GAP = 700;
        var BEFORE_YES = 850;
        // long enough for the letter the Yes opens to arrive *and* be read —
        // the loop used to restart while it was still writing itself out
        var HOLD_CELEBRATION = 6200;
        var timers = [];

        function at(ms, fn) { timers.push(setTimeout(fn, ms)); }

        function clearAll() {
            timers.forEach(clearTimeout);
            timers = [];
        }

        function beat(n) {
            // on the element, so a test (or a person with devtools open) can
            // see exactly how far the loop has got
            document.documentElement.setAttribute('data-pd-beat', n);
            try {
                window.parent.postMessage({ proposalBeat: n }, '*');
            } catch (e) { /* a demo opened on its own has no parent to tell */ }
        }

        /**
         * Make the No button run, the way a real pointer would. The tease
         * module listens for `pointerenter` from a mouse, so that is what it
         * is sent — no private hook, and no second copy of the dodge.
         */
        function nudgeNo() {
            var no = document.querySelector('[data-tease-no]');
            if (!no) return;
            no.dispatchEvent(new PointerEvent('pointerenter', {
                bubbles: true, pointerType: 'mouse',
            }));
        }

        function run() {
            clearAll();
            api.reset();
            beat(0);

            at(IDLE_BEFORE_TAP, function () {
                api.open();

                // The design declares when its own beats land; the tease and
                // the Yes hang off the last of them, so the captions and the
                // action are always the same clock.
                var phases = api.phases || [0, 600, 1800];
                var question = phases[2];

                at(phases[0], function () { beat(1); });
                at(phases[1], function () { beat(2); });
                at(question, function () { beat(3); });

                at(question + AFTER_QUESTION, nudgeNo);
                at(question + AFTER_QUESTION + NUDGE_GAP, nudgeNo);
                at(question + AFTER_QUESTION + NUDGE_GAP + BEFORE_YES, function () {
                    beat(4);
                    api.yes();
                    at(HOLD_CELEBRATION, run);
                });
            });
        }

        // A demo in a hidden or scrolled-away card is wasted work, and four of
        // them animating at once for nothing is worse. It runs only while the
        // card is actually on screen.
        var running = false;
        function start() { if (!running) { running = true; run(); } }
        function stop() { running = false; clearAll(); }

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                entries[0].isIntersecting ? start() : stop();
            }, { threshold: 0.05 }).observe(document.body);
        } else {
            start();
        }

        document.addEventListener('visibilitychange', function () {
            document.hidden ? stop() : start();
        });
    })();
</script>
@endif
