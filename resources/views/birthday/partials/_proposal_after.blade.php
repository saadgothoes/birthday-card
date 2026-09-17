{{--
    Proposal · what the Yes opens.

    The Yes used to land on a heading and one line of text, which made the best
    moment on the page the least designed one — and the least *surprising*. It
    now opens a letter: the thing they will actually read twice, written out
    line by line, sealed with both names and the date it happened.

    The sheet is shared so all four designs end on something equally finished.
    What is **not** shared is how it arrives — each design hands over its own
    entrance, because the surprise has to come out of the thing they were just
    looking at:

        chat    the letter swells up out of the last message
        flip    the foil card turns over, and the letter is on its back
        sky     it resolves out of the sky, the way the stars did
        photo   a polaroid turned over — the letter is written on the back

    Used by a design as:

        @include('birthday.partials._proposal_after', ['afterEnter' => 'flip'])
        …
        showAfter({ fx: 'hearts' });   // 'confetti' | 'hearts' | 'petals' | 'stars' | 'meteors'
        hideAfter();                   // the looping demo, starting over

    It reads the design's own blade variables — $letterLines, $yesHead,
    $closing, $signed, $toName, $fromName, $ringPhoto — and is skinned through
    --pk-scrim, --pk-bg, --pk-ink, --pk-soft, --pk-accent, --pk-line,
    --pk-display and --pk-letter (the face the letter itself is set in).
--}}
<style>
    .pa {
        position: fixed;
        inset: 0;
        z-index: 70;
        display: grid;
        place-items: center;
        padding: clamp(.8rem, 4vw, 2rem);
        perspective: 1400px;
        background: var(--pk-scrim, rgba(18, 12, 14, .74));
        backdrop-filter: blur(7px);
        -webkit-backdrop-filter: blur(7px);
        opacity: 0;
        visibility: hidden;
        transition: opacity .5s ease, visibility 0s linear .5s;
    }

    .pa.on {
        opacity: 1;
        visibility: visible;
        transition: opacity .5s ease, visibility 0s;
    }

    .pa-sheet {
        position: relative;
        width: min(100%, 430px);
        max-height: 100%;
        overflow: auto;
        box-sizing: border-box;
        padding: clamp(1.4rem, 5.5vw, 2.1rem) clamp(1.25rem, 5vw, 2rem) clamp(1.1rem, 4vw, 1.5rem);
        border-radius: 22px;
        text-align: center;
        color: var(--pk-ink, #2a1f1d);
        background: var(--pk-bg, linear-gradient(170deg, #fffaf4, #f6e9dc));
        box-shadow: 0 44px 96px -44px rgba(0, 0, 0, .75), 0 1px 0 rgba(255, 255, 255, .45) inset;
        transition: transform .7s cubic-bezier(.16, 1, .3, 1), opacity .5s ease, filter .6s ease;
        -webkit-overflow-scrolling: touch;
    }

    /* the hairline sits inside the radius, so the sheet keeps one clean edge */
    .pa-sheet::after {
        content: '';
        position: absolute;
        inset: 6px;
        border-radius: 17px;
        border: 1px solid var(--pk-line, rgba(0, 0, 0, .12));
        pointer-events: none;
    }

    /* ── the four entrances ────────────────────────────────────── */
    .pa[data-enter="chat"] .pa-sheet {
        transform: translateY(42px) scale(.9);
        transform-origin: bottom center;
        opacity: 0;
    }

    .pa[data-enter="flip"] .pa-sheet {
        transform: rotateY(-88deg);
        transform-origin: left center;
        opacity: .2;
    }

    .pa[data-enter="sky"] .pa-sheet {
        transform: scale(1.07);
        filter: blur(7px);
        opacity: 0;
    }

    .pa[data-enter="photo"] .pa-sheet {
        transform: rotate(-8deg) translateY(34px) scale(.88);
        opacity: 0;
    }

    .pa.on .pa-sheet {
        transform: none;
        filter: none;
        opacity: 1;
    }

    /* ── the letter ────────────────────────────────────────────── */
    .pa-seal {
        width: clamp(58px, 17vw, 70px);
        height: clamp(58px, 17vw, 70px);
        margin: 0 auto clamp(.7rem, 3vw, .95rem);
        border-radius: 50%;
        display: grid;
        place-items: center;
        overflow: hidden;
        background: var(--pk-ring-bg, rgba(255, 255, 255, .5));
        box-shadow: 0 0 0 1px var(--pk-line, rgba(0, 0, 0, .1)),
                    0 14px 28px -20px rgba(0, 0, 0, .7);
    }

    .pa-seal img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pa-seal svg {
        width: 76%;
        height: 76%;
    }

    .pa-date {
        margin: 0 0 clamp(.5rem, 2vw, .7rem);
        font-size: .64rem;
        letter-spacing: .24em;
        text-transform: uppercase;
        color: var(--pk-soft, rgba(0, 0, 0, .5));
    }

    .pa-head {
        margin: 0 0 clamp(.9rem, 3.5vw, 1.15rem);
        font-family: var(--pk-display, 'Instrument Serif', Georgia, serif);
        font-weight: 400;
        line-height: 1.02;
        font-size: clamp(2.05rem, 9.5vw, 2.9rem);
        letter-spacing: -.015em;
        color: var(--pk-accent, #a35a56);
    }

    .pa-rule {
        width: 46px;
        height: 1px;
        margin: 0 auto clamp(.95rem, 3.5vw, 1.25rem);
        background: var(--pk-line, rgba(0, 0, 0, .18));
        position: relative;
    }

    .pa-rule::after {
        content: '';
        position: absolute;
        left: 50%;
        top: -2px;
        width: 5px;
        height: 5px;
        margin-left: -2.5px;
        border-radius: 50%;
        background: var(--pk-accent, #a35a56);
    }

    /* A letter is written to one edge, not centred: at this width the lines
       wrap, and centred wrapped lines fray into a diamond shape. */
    .pa-letter {
        margin: 0 0 clamp(.9rem, 3.5vw, 1.2rem);
        padding: 0 clamp(0px, 1.5vw, 10px);
        text-align: left;
        font-family: var(--pk-letter, inherit);
        font-size: clamp(.92rem, 3.8vw, 1.02rem);
        line-height: 1.66;
        text-wrap: pretty;
    }

    .pa-line + .pa-line { margin-top: .12em; }

    /* each line is written, not printed all at once — the sheet has landed by
       the time the first one starts */
    .pa-line {
        margin: 0;
        opacity: 0;
        transform: translateY(7px);
        transition: opacity .5s ease, transform .5s cubic-bezier(.2, .9, .3, 1);
        transition-delay: calc(var(--i) * 190ms + 620ms);
    }

    .pa.on .pa-line {
        opacity: 1;
        transform: none;
    }

    .pa-closing {
        margin: 0 0 clamp(1rem, 4vw, 1.3rem);
        font-family: var(--pk-display, 'Instrument Serif', Georgia, serif);
        font-size: clamp(1.02rem, 4.2vw, 1.15rem);
        line-height: 1.4;
        color: var(--pk-accent, #a35a56);
        opacity: 0;
        transition: opacity .6s ease var(--closing-delay, 1.6s);
    }

    .pa.on .pa-closing { opacity: 1; }

    .pa-foot {
        padding-top: clamp(.8rem, 3vw, 1rem);
        border-top: 1px solid var(--pk-line, rgba(0, 0, 0, .12));
        opacity: 0;
        transition: opacity .6s ease var(--foot-delay, 1.85s);
    }

    .pa.on .pa-foot { opacity: 1; }

    .pa-names {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .65rem;
        margin: 0 0 .3rem;
        font-family: var(--pk-display, 'Instrument Serif', Georgia, serif);
        font-size: clamp(1.05rem, 4.4vw, 1.22rem);
    }

    .pa-names span:first-child,
    .pa-names span:last-child {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pa-names span:first-child { text-align: right; }
    .pa-names span:last-child { text-align: left; }

    .pa-amp {
        flex: none !important;
        color: var(--pk-accent, #a35a56);
        font-size: .9em;
    }

    .pa-sign {
        margin: 0;
        font-size: .84rem;
        color: var(--pk-soft, rgba(0, 0, 0, .55));
    }

    .pa-hint {
        margin: .55rem 0 0;
        font-size: .7rem;
        letter-spacing: .04em;
        color: var(--pk-soft, rgba(0, 0, 0, .45));
        opacity: .8;
    }

    @media (prefers-reduced-motion: reduce) {

        .pa,
        .pa-sheet,
        .pa-line,
        .pa-closing,
        .pa-foot {
            transition-duration: .01ms !important;
            transition-delay: 0ms !important;
        }

        .pa[data-enter] .pa-sheet {
            transform: none;
            filter: none;
            opacity: 1;
        }
    }
</style>

<div class="pa" id="paOverlay" data-enter="{{ $afterEnter ?? 'sky' }}" role="dialog" aria-modal="true"
    aria-labelledby="paHead" aria-hidden="true">
    <div class="pa-sheet" tabindex="-1">
        <div class="pa-seal">
            @if (!empty($ringPhoto))
                <img src="{{ $ringPhoto }}" alt="The ring">
            @else
                @include('birthday.partials._proposal_ring')
            @endif
        </div>

        <p class="pa-date" id="paDate"></p>
        <h2 class="pa-head" id="paHead">{{ $yesHead }}</h2>
        <div class="pa-rule" aria-hidden="true"></div>

        @if (!empty($letterLines) && count($letterLines))
            <div class="pa-letter">
                @foreach ($letterLines as $i => $line)
                    <p class="pa-line" style="--i:{{ $i }}">{{ $line }}</p>
                @endforeach
            </div>
        @endif

        <p class="pa-closing"
            style="--closing-delay:{{ (0.62 + 0.19 * (isset($letterLines) ? count($letterLines) : 0)) }}s">
            {{ $closing }}</p>

        <div class="pa-foot"
            style="--foot-delay:{{ (0.86 + 0.19 * (isset($letterLines) ? count($letterLines) : 0)) }}s">
            <div class="pa-names">
                <span>{{ $toName }}</span>
                <span class="pa-amp">&amp;</span>
                <span>{{ $fromName }}</span>
            </div>
            <p class="pa-sign">{{ $signed }}</p>
            <p class="pa-hint">screenshot this one 📸</p>
        </div>
    </div>
</div>

<script>
    (function () {
        var overlay = document.getElementById('paOverlay');
        var dateEl = document.getElementById('paDate');

        /**
         * `opts.fx` is the flavour thrown behind the sheet. The sheet is above
         * the particle layer, so the celebration happens around the letter
         * rather than over the words of it.
         */
        window.showAfter = function (opts) {
            opts = opts || {};

            // the date it actually happened — the reason the sheet is worth keeping
            try {
                dateEl.textContent = new Date().toLocaleDateString(undefined, {
                    day: 'numeric', month: 'long', year: 'numeric',
                });
            } catch (e) {
                dateEl.textContent = '';
            }

            overlay.classList.add('on');
            overlay.setAttribute('aria-hidden', 'false');

            if (!window.pfx) return;
            var fx = opts.fx || 'confetti';
            if (fx === 'meteors') {
                pfx.meteors({ count: 9 });
                pfx.burst('stars', { count: 42, y: innerHeight * .42, power: 8 });
            } else {
                // two cones from the lower corners read as one wide celebration
                pfx.burst(fx, { count: 44, x: innerWidth * .12, y: innerHeight * .94, power: 15, spread: .9 });
                pfx.burst(fx, { count: 44, x: innerWidth * .88, y: innerHeight * .94, power: 15, spread: .9 });
                pfx.rain(fx, { count: 28, duration: 1500 });
            }
        };

        window.hideAfter = function () {
            overlay.classList.remove('on');
            overlay.setAttribute('aria-hidden', 'true');
            if (window.pfx) pfx.clear();
        };
    })();
</script>
