{{--
    Proposal · the question and the two buttons.

    All four designs end on the same pair, so the behaviour lives here once.
    A design @includes this and calls:

        initTeaseButtons({
            root:  document.getElementById('askBlock'),
            onYes: () => celebrate(),
        });

    Markup it expects inside `root` (the design brings its own typography —
    only the data attributes matter):

        <p data-tease-question>Will you marry me?</p>
        <div data-tease-row>
          <button data-tease-yes>Yes</button>
          <button data-tease-no>No</button>
        </div>
        <p data-tease-stage></p>          (optional running commentary)

    What it does, and why it ends:

      · Pointer over "No" (or a tap on it) springs the button somewhere else
        inside the row, a little smaller each time, and puffs an emoji out of
        where it was.
      · Its label runs down a ladder — "No" → "are you sure" → … — so the joke
        reads even to someone who never catches the button.
      · After five dodges **it gives up**: it shrinks out of existence and the
        Yes takes the whole row. A gag with no ending is just an obstacle, and
        an endless chase on a page like this one starts to feel mean — so the
        page makes the decision the moment the joke stops being funny.

    Accessibility: the dodge is bound to *pointer* events only. A keyboard user
    tabbing to "No" is never teleported off the control they are focused on —
    that is a trap, not a joke. Pressing it plays the same puff, advances the
    ladder and returns focus to Yes, which is always one Tab away. Both buttons
    carry a visible focus ring. Under prefers-reduced-motion the button still
    moves, but instantly and without the emoji.

    Skinned by the design through:
        --pt-yes-bg, --pt-yes-ink, --pt-no-bg, --pt-no-ink, --pt-ink, --pt-ring
--}}
<style>
    [data-tease-row] {
        position: relative;
        display: flex;
        flex-wrap: nowrap;
        gap: clamp(.55rem, 3vw, .9rem);
        align-items: center;
        justify-content: center;
        min-height: 66px;
        margin-top: clamp(.9rem, 3vw, 1.25rem);
    }

    [data-tease-yes],
    [data-tease-no] {
        font: inherit;
        font-weight: 600;
        letter-spacing: .01em;
        border: 0;
        cursor: pointer;
        border-radius: 999px;
        padding: .8em 1.85em;
        font-size: clamp(.95rem, 3.6vw, 1.06rem);
        white-space: nowrap;
        transition: transform .34s cubic-bezier(.16, 1, .3, 1),
                    left .34s cubic-bezier(.16, 1, .3, 1),
                    top .34s cubic-bezier(.16, 1, .3, 1),
                    width .34s cubic-bezier(.16, 1, .3, 1),
                    box-shadow .25s ease, background .25s ease, opacity .3s ease;
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
    }

    [data-tease-yes] {
        background: var(--pt-yes-bg, #a35a56);
        color: var(--pt-yes-ink, #fff);
        box-shadow: 0 12px 28px -14px rgba(0, 0, 0, .7);
    }

    [data-tease-yes]:hover {
        transform: translateY(-2px) scale(1.03);
    }

    [data-tease-yes]:active {
        transform: translateY(0) scale(.98);
    }

    /* the Yes takes the row once the No has given up */
    [data-tease-yes].pt-sole {
        width: min(100%, 320px);
        transform: none;
    }

    [data-tease-no] {
        background: var(--pt-no-bg, rgba(255, 255, 255, .72));
        color: var(--pt-no-ink, #4a2f2a);
        box-shadow: 0 6px 18px -12px rgba(0, 0, 0, .5);
    }

    /* once it starts running it comes out of the flow, so the Yes does not
       slide sideways every time the No shrinks */
    [data-tease-no].pt-loose {
        position: absolute;
        margin: 0;
        z-index: 3;
    }

    [data-tease-no].pt-gone {
        opacity: 0;
        transform: scale(.2) !important;
        pointer-events: none;
    }

    [data-tease-yes]:focus-visible,
    [data-tease-no]:focus-visible {
        outline: 3px solid var(--pt-ring, #fff);
        outline-offset: 3px;
    }

    [data-tease-stage] {
        margin: .8rem 0 0;
        min-height: 1.3em;
        font-size: clamp(.76rem, 3vw, .88rem);
        opacity: .8;
        color: var(--pt-ink, inherit);
        transition: opacity .3s ease;
    }

    .pt-puff {
        position: fixed;
        z-index: 999;
        pointer-events: none;
        font-size: 1.45rem;
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
            transition: background .2s ease, box-shadow .2s ease, opacity .2s ease;
        }

        .pt-puff {
            display: none;
        }
    }
</style>
<script>
    /**
     * Wire one question block. Returns a handle so a design can put the module
     * back to the start — which is what the looping demo does every pass.
     *
     * config: root (Element), onYes (Function), and optionally emojis, labels,
     * stages (arrays a design can override for its own tone) and giveUpAt.
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
        var emojis = config.emojis || ['🥺', '😭', '💔', '🙈', '😔'];
        var labels = config.labels || ['No', 'are you sure', 'think about it',
            'last chance', 'you cannot catch me', 'ok fine'];
        var stages = config.stages || ['', 'hm.', 'it moved again.',
            'that button does not want to be pressed.',
            'one of these two is the right answer.',
            'and then there was one.'];
        var giveUpAt = config.giveUpAt || 5;

        var dodges = 0;
        var done = false;

        function buzz(ms) {
            if (reduced) return;
            try { navigator.vibrate && navigator.vibrate(ms); } catch (e) { /* not everywhere */ }
        }

        function puff(x, y) {
            if (reduced) return;
            var el = document.createElement('span');
            el.className = 'pt-puff';
            el.textContent = emojis[(Math.random() * emojis.length) | 0];
            el.style.left = x + 'px';
            el.style.top = y + 'px';
            document.body.appendChild(el);
            setTimeout(function () { el.remove(); }, 1100);
        }

        function say(n) {
            no.textContent = labels[Math.min(n, labels.length - 1)];
            if (stage) stage.textContent = stages[Math.min(n, stages.length - 1)];
        }

        /** The ending: the No stops being a button and the Yes takes the row. */
        function giveUp() {
            done = true;
            var box = no.getBoundingClientRect();
            puff(box.left + box.width / 2, box.top + box.height / 2);
            no.classList.add('pt-gone');
            no.setAttribute('tabindex', '-1');
            no.setAttribute('aria-hidden', 'true');
            yes.classList.add('pt-sole');
            if (stage) stage.textContent = stages[stages.length - 1];
            buzz([14, 60, 26]);
        }

        /** Move "No" to a fresh spot inside the row, never off screen. */
        function dodge() {
            if (done) return;

            var rowBox = row.getBoundingClientRect();
            var noBox = no.getBoundingClientRect();
            puff(noBox.left + noBox.width / 2, noBox.top + noBox.height / 2);
            buzz(12);

            if (!no.classList.contains('pt-loose')) {
                // freeze the row's height first, so taking the button out of
                // the flow does not make the block jump
                row.style.minHeight = Math.max(rowBox.height, 66) + 'px';
                no.classList.add('pt-loose');
            }

            dodges++;
            if (dodges >= giveUpAt) { say(dodges); giveUp(); return; }

            var scale = Math.max(.5, 1 - dodges * .1);
            var maxX = Math.max(0, rowBox.width - noBox.width);
            var maxY = Math.max(0, rowBox.height - noBox.height);

            // keep clear of the Yes, so the two never sit on top of each other
            var yesBox = yes.getBoundingClientRect();
            var left, top, tries = 0;
            do {
                left = Math.random() * maxX;
                top = Math.random() * maxY;
                tries++;
            } while (tries < 14 && Math.abs((rowBox.left + left) - yesBox.left) < yesBox.width &&
                     Math.abs((rowBox.top + top) - yesBox.top) < yesBox.height);

            no.style.left = left + 'px';
            no.style.top = top + 'px';
            no.style.transform = 'scale(' + scale + ')';
            say(dodges);
        }

        // Pointer only: a keyboard user is never moved off the focused control.
        no.addEventListener('pointerenter', function (e) {
            if (e.pointerType === 'mouse') dodge();
        });

        no.addEventListener('click', function (e) {
            e.preventDefault();
            dodge();
            no.blur();
        });

        // Keyboard: same joke, same ending, but the button stays where it is.
        no.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            e.preventDefault();
            if (done) { yes.focus(); return; }
            var box = no.getBoundingClientRect();
            puff(box.left + box.width / 2, box.top + box.height / 2);
            dodges++;
            say(dodges);
            if (dodges >= giveUpAt) giveUp();
            yes.focus();
        });

        yes.addEventListener('click', function () {
            buzz([10, 40, 18]);
            if (typeof config.onYes === 'function') config.onYes();
        });

        // The row is positioned, so a loose button's coordinates mean something.
        row.style.position = 'relative';

        return {
            reset: function () {
                dodges = 0;
                done = false;
                no.classList.remove('pt-loose', 'pt-gone');
                no.removeAttribute('style');
                no.removeAttribute('aria-hidden');
                no.removeAttribute('tabindex');
                yes.classList.remove('pt-sole');
                row.style.minHeight = '';
                say(0);
            }
        };
    };
</script>
