{{--
    Proposal · the shared "Will you marry me?" module.

    All four proposal designs end on the same question and the same pair of
    buttons, so the behaviour lives here once rather than four times. Each
    design @includes this partial and calls `initTeaseButtons()` with its own
    root element and its own celebration:

        initTeaseButtons({
            root:  document.getElementById('askBlock'),
            onYes: () => celebrate(),
        });

    Markup the module expects inside `root` (a design supplies its own
    wrapper/typography — only the data attributes matter):

        <p data-tease-question>Will you marry me?</p>
        <div data-tease-row>
          <button data-tease-yes>Yes</button>
          <button data-tease-no>No</button>
        </div>
        <p data-tease-stage></p>          (optional running commentary)

    What it does:
      · Pointer over "No" (or a tap on it) moves the button somewhere else
        inside the row and shrinks it a little, each time, and puffs a sad
        emoji out of where it was.
      · Its label changes as it runs — "No" → "Are you sure?" → … so the
        joke reads even to someone who never catches it.
      · "Yes" grows as "No" shrinks, and always answers.

    Accessibility: the dodge is bound to *pointer* events only. A keyboard user
    tabbing to "No" is never teleported away from the focused control (that is
    a trap, not a joke) — pressing it plays the same emoji puff, changes the
    label and returns focus, and "Yes" is always one Tab away. Both buttons
    carry a visible focus ring. Under `prefers-reduced-motion` the button still
    moves, but instantly and without the emoji shower.

    The colours come from the design's own CSS custom properties, so the module
    is skinned by whichever theme is on the page:
        --pt-yes-bg, --pt-yes-ink, --pt-no-bg, --pt-no-ink, --pt-ink, --pt-ring
--}}
<style>
    [data-tease-row] {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        gap: clamp(0.6rem, 3vw, 1.1rem);
        align-items: center;
        justify-content: center;
        min-height: 74px;
        margin-top: clamp(0.9rem, 3vw, 1.3rem);
    }

    [data-tease-yes],
    [data-tease-no] {
        font: inherit;
        font-weight: 700;
        letter-spacing: .02em;
        border: 0;
        cursor: pointer;
        border-radius: 999px;
        padding: 0.78em 1.9em;
        font-size: clamp(0.98rem, 3.6vw, 1.12rem);
        transition: transform .28s cubic-bezier(.2, .9, .3, 1.2),
                    left .28s cubic-bezier(.2, .9, .3, 1.2),
                    top .28s cubic-bezier(.2, .9, .3, 1.2),
                    box-shadow .25s ease, background .25s ease, opacity .25s ease;
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
    }

    [data-tease-yes] {
        background: var(--pt-yes-bg, #a35a56);
        color: var(--pt-yes-ink, #fff);
        box-shadow: 0 10px 26px -12px rgba(0, 0, 0, .55);
    }

    [data-tease-yes]:hover {
        transform: translateY(-2px) scale(1.04);
    }

    [data-tease-no] {
        background: var(--pt-no-bg, rgba(255, 255, 255, .78));
        color: var(--pt-no-ink, #4a2f2a);
        box-shadow: 0 6px 18px -12px rgba(0, 0, 0, .5);
    }

    /* once it starts running it is taken out of the flow, so the Yes button
       does not slide sideways every time the No button shrinks */
    [data-tease-no].pt-loose {
        position: absolute;
        margin: 0;
        z-index: 3;
    }

    [data-tease-yes]:focus-visible,
    [data-tease-no]:focus-visible {
        outline: 3px solid var(--pt-ring, #ffffff);
        outline-offset: 3px;
    }

    [data-tease-stage] {
        margin: 0.85rem 0 0;
        min-height: 1.25em;
        font-size: clamp(0.78rem, 3vw, 0.9rem);
        opacity: .82;
        color: var(--pt-ink, inherit);
        transition: opacity .3s ease;
    }

    .pt-puff {
        position: fixed;
        z-index: 999;
        pointer-events: none;
        font-size: 1.5rem;
        animation: ptPuff 1.05s cubic-bezier(.2, .7, .3, 1) forwards;
    }

    @keyframes ptPuff {
        0%   { transform: translate(-50%, -50%) scale(.4); opacity: 0; }
        22%  { transform: translate(-50%, -80%) scale(1.15); opacity: 1; }
        100% { transform: translate(-50%, -230%) scale(.75); opacity: 0; }
    }

    @media (prefers-reduced-motion: reduce) {

        [data-tease-yes],
        [data-tease-no] {
            transition: background .2s ease, box-shadow .2s ease;
        }

        .pt-puff {
            display: none;
        }
    }
</style>
<script>
    /**
     * Wire one question block. Returns a small handle so a design can reset the
     * module when its own "ask again" runs.
     *
     * config: root (Element), onYes (Function), and optionally emojis, labels
     * and stages — arrays of strings a design can override for its own tone.
     */
    window.initTeaseButtons = function initTeaseButtons(config) {
        var root = config && config.root;
        if (!root || root.dataset.teaseReady === '1') return null;
        root.dataset.teaseReady = '1';

        var row = root.querySelector('[data-tease-row]');
        var yes = root.querySelector('[data-tease-yes]');
        var no = root.querySelector('[data-tease-no]');
        var stage = root.querySelector('[data-tease-stage]');
        if (!row || !yes || !no) return null;

        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var emojis = config.emojis || ['😢', '🥺', '💔', '😭', '🙈'];
        var labels = config.labels || ['No', 'Are you sure?', 'Really sure?', 'Think again!',
            'Last chance…', 'You can\'t catch me', 'Just say yes 🥹'];
        var stages = config.stages || ['', 'Hmm.', 'That button is not cooperating.',
            'It is getting away from you.', 'One of these two is the right answer.',
            'The other one is right there.', 'It has given up hiding. So should you.'];

        var dodges = 0;

        function puff(x, y) {
            if (reduced) return;
            var el = document.createElement('span');
            el.className = 'pt-puff';
            el.textContent = emojis[Math.floor(Math.random() * emojis.length)];
            el.style.left = x + 'px';
            el.style.top = y + 'px';
            document.body.appendChild(el);
            setTimeout(function () { el.remove(); }, 1100);
        }

        /** Move "No" to a fresh spot inside the row, never off screen. */
        function dodge() {
            var rowBox = row.getBoundingClientRect();
            var noBox = no.getBoundingClientRect();

            puff(noBox.left + noBox.width / 2, noBox.top + noBox.height / 2);

            if (!no.classList.contains('pt-loose')) {
                // freeze the row's height first, so taking the button out of
                // the flow does not make the block jump
                row.style.minHeight = Math.max(rowBox.height, 74) + 'px';
                no.classList.add('pt-loose');
            }

            dodges++;
            var scale = Math.max(0.45, 1 - dodges * 0.09);
            var maxX = Math.max(0, rowBox.width - noBox.width);
            var maxY = Math.max(0, rowBox.height - noBox.height);

            // keep it clear of the Yes button, so the two never overlap
            var yesBox = yes.getBoundingClientRect();
            var left, top, tries = 0;
            do {
                left = Math.random() * maxX;
                top = Math.random() * maxY;
                tries++;
            } while (tries < 12 && Math.abs((rowBox.left + left) - yesBox.left) < yesBox.width &&
                     Math.abs((rowBox.top + top) - yesBox.top) < yesBox.height);

            no.style.left = left + 'px';
            no.style.top = top + 'px';
            no.style.transform = 'scale(' + scale + ')';
            no.textContent = labels[Math.min(dodges, labels.length - 1)];
            if (stage) stage.textContent = stages[Math.min(dodges, stages.length - 1)];

            if (dodges >= labels.length - 1) {
                // it has run out of places to hide — leave it be, but harmless
                no.style.opacity = '0.75';
            }
        }

        // Pointer only: a keyboard user is never moved off the control they
        // are focused on.
        no.addEventListener('pointerenter', function (e) {
            if (e.pointerType === 'mouse') dodge();
        });
        no.addEventListener('click', function (e) {
            e.preventDefault();
            dodge();
            no.blur();
        });
        no.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            e.preventDefault();
            var box = no.getBoundingClientRect();
            puff(box.left + box.width / 2, box.top + box.height / 2);
            dodges++;
            no.textContent = labels[Math.min(dodges, labels.length - 1)];
            if (stage) stage.textContent = stages[Math.min(dodges, stages.length - 1)];
            yes.focus();
        });

        yes.addEventListener('click', function () {
            if (typeof config.onYes === 'function') config.onYes();
        });

        // The row is positioned, so a loose button's coordinates mean something.
        row.style.position = 'relative';

        return {
            reset: function () {
                dodges = 0;
                no.classList.remove('pt-loose');
                no.removeAttribute('style');
                no.textContent = labels[0];
                row.style.minHeight = '';
                if (stage) stage.textContent = stages[0];
            }
        };
    };
</script>
