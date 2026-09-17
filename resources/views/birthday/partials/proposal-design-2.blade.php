{{--
    Proposal · Design 2 — "Scratch the Foil".

    One shared design, re-skinned four ways; the wrapper views
    (proposal-design-2-theme-{1..4}.blade.php) @include this with a
    `proposalTheme` (1-4).

    The tactile one. Every other design happens *at* the recipient — this one
    does not move until they move it, and it comes away under their own finger
    at whatever speed they choose. That is the whole idea: the question is
    something they uncover, not something they are shown.

        a foil card, shimmering  →  a finger dragged across it
        →  the foil comes away in dust wherever they touch
        →  past halfway it gives up the rest of itself at once
        →  the ring and the question, which were under it all along
        Yes  →  gold dust, the card turns over, and the letter is what
                was written on the back of it

    The foil is a real canvas with `destination-out` under the finger, not an
    image fading — the torn edge follows exactly where they went, and the
    percentage cleared is measured off the pixels rather than guessed from the
    number of strokes.

    Renders complete with no query parameters.

    Request params:
      to_name, from_name     who it is for / from
      heading                the line above the card
      tap_label              what is printed on the foil ("Scratch here")
      ring_photo             under the foil, and the seal on the letter (drawn fallback)
      question               under the foil, beside the ring
      yes_label, no_label    the two buttons
      yes_heading            the heading on the letter the Yes opens
      letter_text            that letter, one line per line, up to 6
      closing_line, signed   the letter's closing line + signature
      theme                  overrides `proposalTheme`
      preview_stage          open | reveal | yes — skip ahead (dashboard preview)
--}}
@php
    $proposalTheme = (int) ($proposalTheme ?? request('theme', 1));

    // `foil1`-`foil3` are the three stops of the metal itself; `flake` is what
    // it turns into when it comes off.
    $themes = [
        1 => ['name' => 'Gold on Cream',
              'bg1' => '#fbf5ea', 'bg2' => '#ead9bd', 'ink' => '#3a2d1d', 'soft' => '#8a7454',
              'accent' => '#a8813c', 'card' => '#fffdf7', 'line' => 'rgba(58,45,29,.14)',
              'foil1' => '#f4dfa8', 'foil2' => '#c9a24a', 'foil3' => '#8f6f2a', 'foilInk' => 'rgba(78,56,14,.55)',
              'card1' => '#fffaf0', 'card2' => '#f3e5cb', 'cardInk' => '#3a2d1d',
              'cardSoft' => 'rgba(58,45,29,.55)', 'cardLine' => 'rgba(58,45,29,.16)',
              'scrim' => 'rgba(48,36,20,.72)', 'yesInk' => '#fffdf7',
              'metal1' => '#f0d79b', 'metal2' => '#a8813c',
              'fx' => '#c9a24a,#f4dfa8,#ffffff,#8f6f2a'],
        2 => ['name' => 'Rose Foil',
              'bg1' => '#fff4f5', 'bg2' => '#f3d3d9', 'ink' => '#4a2530', 'soft' => '#946a76',
              'accent' => '#c06078', 'card' => '#fffafb', 'line' => 'rgba(74,37,48,.13)',
              'foil1' => '#ffd9de', 'foil2' => '#e2a1b0', 'foil3' => '#ab6a79', 'foilInk' => 'rgba(88,40,52,.5)',
              'card1' => '#fffafb', 'card2' => '#fbe3e8', 'cardInk' => '#4a2530',
              'cardSoft' => 'rgba(74,37,48,.55)', 'cardLine' => 'rgba(74,37,48,.14)',
              'scrim' => 'rgba(66,30,40,.7)', 'yesInk' => '#fffafb',
              'metal1' => '#f7cdd6', 'metal2' => '#c06078',
              'fx' => '#c06078,#f7cdd6,#ffffff,#e2a1b0'],
        3 => ['name' => 'Holo Black',
              'bg1' => '#1a1b21', 'bg2' => '#0a0b0e', 'ink' => '#edf1f6', 'soft' => '#98a1ae',
              'accent' => '#9ad7ff', 'card' => '#16171d', 'line' => 'rgba(255,255,255,.12)',
              'foil1' => '#dff0ff', 'foil2' => '#9fb6d6', 'foil3' => '#6f7fa8', 'foilInk' => 'rgba(20,28,44,.5)',
              'card1' => '#1c1e26', 'card2' => '#111218', 'cardInk' => '#edf1f6',
              'cardSoft' => 'rgba(237,241,246,.55)', 'cardLine' => 'rgba(255,255,255,.14)',
              'scrim' => 'rgba(4,5,8,.84)', 'yesInk' => '#0d1118',
              'metal1' => '#dff0ff', 'metal2' => '#9ad7ff',
              'fx' => '#9ad7ff,#dff0ff,#ffffff,#c9b6ff'],
        4 => ['name' => 'Emerald Foil',
              'bg1' => '#17493d', 'bg2' => '#0a241e', 'ink' => '#eef7f2', 'soft' => '#a3c4b7',
              'accent' => '#d8b262', 'card' => '#123b31', 'line' => 'rgba(255,255,255,.13)',
              'foil1' => '#f2debf', 'foil2' => '#cba85f', 'foil3' => '#8d7135', 'foilInk' => 'rgba(52,40,14,.5)',
              'card1' => '#15453a', 'card2' => '#0d2a23', 'cardInk' => '#eef7f2',
              'cardSoft' => 'rgba(238,247,242,.55)', 'cardLine' => 'rgba(255,255,255,.15)',
              'scrim' => 'rgba(4,18,14,.82)', 'yesInk' => '#20180a',
              'metal1' => '#f2debf', 'metal2' => '#d8b262',
              'fx' => '#d8b262,#f2debf,#ffffff,#7fc9a8'],
    ];
    $t = $themes[$proposalTheme] ?? $themes[1];

    $d = \App\Http\Controllers\Client\BirthdayCardController::proposalDefaults(2);

    $toName   = request('to_name', $d['to_name']);
    $fromName = request('from_name', $d['from_name']);
    $heading  = request('heading', $d['heading']);
    $tapLabel = request('tap_label', $d['tap_label']);
    $question = request('question', $d['question']);
    $yesLabel = request('yes_label', $d['yes_label']);
    $noLabel  = request('no_label', $d['no_label']);
    $yesHead  = request('yes_heading', $d['yes_heading']);
    $closing  = request('closing_line', $d['closing_line']);
    $signed   = request('signed', $d['signed']);
    $ringPhoto = request('ring_photo');
    $previewStage = request('preview_stage');

    // What is written on the back of the card, opened by the Yes.
    $letterLines = collect(preg_split('/\r\n|\r|\n/', (string) request('letter_text', $d['letter_text'])))
        ->map(fn ($l) => trim($l))
        ->filter()
        ->take(\App\Http\Controllers\Client\BirthdayCardController::PROPOSAL_MULTILINE['letter_text'])
        ->values();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $question }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg1: {{ $t['bg1'] }};
            --bg2: {{ $t['bg2'] }};
            --ink: {{ $t['ink'] }};
            --soft: {{ $t['soft'] }};
            --accent: {{ $t['accent'] }};
            --card: {{ $t['card'] }};
            --line: {{ $t['line'] }};
            --metal1: {{ $t['metal1'] }};
            --metal2: {{ $t['metal2'] }};
            --serif: 'Instrument Serif', Georgia, serif;
            --sans: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --fx-colors: {{ $t['fx'] }};

            --pt-yes-bg: {{ $t['accent'] }};
            --pt-yes-ink: {{ $t['yesInk'] }};
            --pt-no-bg: rgba(255, 255, 255, .14);
            --pt-no-ink: {{ $t['ink'] }};
            --pt-ink: {{ $t['soft'] }};
            --pt-ring: {{ $t['accent'] }};

            --pk-scrim: {{ $t['scrim'] }};
            --pk-bg: linear-gradient(170deg, {{ $t['card1'] }}, {{ $t['card2'] }});
            --pk-ink: {{ $t['cardInk'] }};
            --pk-soft: {{ $t['cardSoft'] }};
            --pk-accent: {{ $t['accent'] }};
            --pk-line: {{ $t['cardLine'] }};
            --pk-ring-bg: rgba(255, 255, 255, .12);
            --pk-display: 'Instrument Serif', Georgia, serif;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: var(--sans);
            color: var(--ink);
            background:
                radial-gradient(120% 80% at 50% 0%, rgba(255, 255, 255, .18), transparent 60%),
                linear-gradient(175deg, var(--bg1), var(--bg2));
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: clamp(1rem, 5vw, 2rem);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .wrap {
            width: min(100%, 360px);
            text-align: center;
        }

        .kicker {
            margin: 0 0 clamp(.8rem, 4vw, 1.1rem);
            font-size: .74rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--soft);
        }

        /* ── the card ──────────────────────────────────────────── */
        .card {
            position: relative;
            width: 100%;
            aspect-ratio: 3 / 4;
            border-radius: 22px;
            overflow: hidden;
            background: var(--card);
            box-shadow: 0 34px 70px -34px rgba(0, 0, 0, .65),
                        0 0 0 1px var(--line) inset;
            touch-action: none;   /* the drag is the scratch, not a page scroll */
        }

        .under {
            position: absolute;
            inset: 0;
            display: grid;
            align-content: center;
            justify-items: center;
            gap: clamp(.7rem, 3vw, 1rem);
            padding: clamp(1.1rem, 5vw, 1.6rem);
        }

        .under .ring {
            width: clamp(74px, 24vw, 96px);
            height: clamp(74px, 24vw, 96px);
            border-radius: 50%;
            overflow: hidden;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, .1);
            box-shadow: 0 0 0 1px var(--line);
        }

        .under .ring img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .under .ring svg {
            width: 78%;
            height: 78%;
        }

        .under .to {
            margin: 0;
            font-size: .72rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--soft);
        }

        .under .q {
            margin: 0;
            font-family: var(--serif);
            font-weight: 400;
            font-size: clamp(1.55rem, 7.4vw, 2.05rem);
            line-height: 1.1;
            letter-spacing: -.01em;
            color: var(--ink);
        }

        /* the foil itself */
        #foil {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            cursor: grab;
            transition: opacity .55s ease, transform .55s ease;
        }

        /* the Yes turns the card over — the letter is on the back of it */
        .card.turning {
            transform: perspective(1200px) rotateY(-104deg);
            opacity: .2;
            transition: transform .62s cubic-bezier(.5, 0, .75, 0), opacity .5s ease .12s;
        }

        .card.done #foil {
            opacity: 0;
            transform: scale(1.06);
            pointer-events: none;
        }

        /* the shimmer that says "this is metal" — it goes as soon as they
           start, because after that the torn edge is the interesting part */
        .sheen {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(115deg,
                    transparent 35%, rgba(255, 255, 255, .55) 48%, transparent 62%);
            background-size: 260% 100%;
            mix-blend-mode: screen;
            animation: sweep 3.4s ease-in-out infinite;
            transition: opacity .4s ease;
        }

        .card.touched .sheen,
        .card.done .sheen { opacity: 0; }

        @keyframes sweep {
            0%, 12%  { background-position: 130% 0; }
            70%, 100% { background-position: -30% 0; }
        }

        /* ── under the card ────────────────────────────────────── */
        .ask {
            margin-top: clamp(1rem, 4vw, 1.4rem);
            opacity: 0;
            transform: translateY(10px);
            visibility: hidden;
            transition: opacity .5s ease .12s, transform .5s cubic-bezier(.16, 1, .3, 1) .12s,
                        visibility 0s linear .62s;
        }

        .ask.on {
            opacity: 1;
            transform: none;
            visibility: visible;
            transition: opacity .5s ease .12s, transform .5s cubic-bezier(.16, 1, .3, 1) .12s,
                        visibility 0s;
        }

        [data-tease-stage] { text-align: center; }

        /* Always available, and the only way through for a keyboard or a
           screen reader — a canvas you have to drag is not an interface. */
        .skip {
            display: block;
            margin: clamp(.8rem, 3vw, 1rem) auto 0;
            padding: .5em 1.1em;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: transparent;
            color: var(--soft);
            font: inherit;
            font-size: .78rem;
            cursor: pointer;
            transition: opacity .3s ease, color .2s ease, border-color .2s ease;
        }

        .skip:hover { color: var(--ink); border-color: var(--accent); }

        .skip:focus-visible { outline: 3px solid var(--accent); outline-offset: 3px; }

        .card.done ~ .skip { display: none; }

        @media (prefers-reduced-motion: reduce) {

            .sheen { animation: none; }

            .ask,
            #foil {
                transition-duration: .01ms !important;
                transition-delay: 0s !important;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <p class="kicker">{{ $heading }}</p>

        <div class="card" id="card">
            <div class="under">
                <div class="ring">
                    @if ($ringPhoto)
                        <img src="{{ $ringPhoto }}" alt="The ring">
                    @else
                        @include('birthday.partials._proposal_ring')
                    @endif
                </div>
                <p class="to">For {{ $toName }}</p>
                <p class="q">{{ $question }}</p>
            </div>
            <canvas id="foil" role="img" aria-label="A foil panel to scratch away"></canvas>
            <div class="sheen" aria-hidden="true"></div>
        </div>

        <button type="button" class="skip" id="skip">Reveal it instead</button>

        <div class="ask" id="askBlock">
            <div data-tease-row>
                <button type="button" data-tease-yes>{{ $yesLabel }}</button>
                <button type="button" data-tease-no>{{ $noLabel }}</button>
            </div>
            <p data-tease-stage></p>
        </div>
    </div>

    @include('birthday.partials._proposal_fx')
    {{-- the card turns over, and the letter is what was on the back --}}
    @include('birthday.partials._proposal_after', ['afterEnter' => 'flip'])
    @include('birthday.partials._proposal_tease')

    <script>
        (function () {
            var card = document.getElementById('card');
            var canvas = document.getElementById('foil');
            var ctx = canvas.getContext('2d', { willReadFrequently: true });
            var ask = document.getElementById('askBlock');
            var skip = document.getElementById('skip');

            var FOIL = [@json($t['foil1']), @json($t['foil2']), @json($t['foil3'])];
            var FOIL_INK = @json($t['foilInk']);
            var LABEL = @json($tapLabel);
            var CLEAR_AT = 0.48;    // past this it gives up the rest at once
            var BRUSH = 26;

            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var dpr = Math.min(window.devicePixelRatio || 1, 2);
            var revealed = false;
            var drawing = false;
            var lastPt = null;
            var timers = [];

            function at(ms, fn) { timers.push(setTimeout(fn, ms)); }
            function clearAll() { timers.forEach(clearTimeout); timers = []; }

            /** Paint the metal: three stops, brushed bands, and the invitation. */
            function paintFoil() {
                var w = card.clientWidth, h = card.clientHeight;
                if (!w || !h) return;
                canvas.width = Math.floor(w * dpr);
                canvas.height = Math.floor(h * dpr);
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                ctx.globalCompositeOperation = 'source-over';
                ctx.clearRect(0, 0, w, h);

                var g = ctx.createLinearGradient(0, 0, w, h);
                g.addColorStop(0, FOIL[0]);
                g.addColorStop(.42, FOIL[1]);
                g.addColorStop(.72, FOIL[0]);
                g.addColorStop(1, FOIL[2]);
                ctx.fillStyle = g;
                ctx.fillRect(0, 0, w, h);

                // brushed metal: many thin diagonal bands, alternating light
                ctx.save();
                ctx.globalAlpha = .09;
                for (var i = -h; i < w + h; i += 7) {
                    ctx.fillStyle = (i / 7) % 2 ? '#ffffff' : '#000000';
                    ctx.beginPath();
                    ctx.moveTo(i, 0);
                    ctx.lineTo(i + 4, 0);
                    ctx.lineTo(i + 4 + h * .4, h);
                    ctx.lineTo(i + h * .4, h);
                    ctx.fill();
                }
                ctx.restore();

                ctx.fillStyle = FOIL_INK;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var size = Math.max(11, Math.min(15, w * 0.042));
                ctx.font = '600 ' + size + 'px Inter, system-ui, sans-serif';
                // letterSpacing is not everywhere; space the characters by hand
                var text = LABEL.toUpperCase().split('').join(' ');
                ctx.fillText(text, w / 2, h / 2);
                ctx.font = (size * 1.9) + 'px serif';
                ctx.fillText('👆', w / 2, h / 2 + size * 2.6);

                ctx.globalCompositeOperation = 'destination-out';
            }

            /** How much of the metal is gone, measured off the pixels. */
            function cleared() {
                var w = canvas.width, h = canvas.height;
                if (!w || !h) return 0;
                var data = ctx.getImageData(0, 0, w, h).data;
                var step = 4 * 10;      // every tenth pixel is plenty
                var gone = 0, seen = 0;
                for (var i = 3; i < data.length; i += step) {
                    seen++;
                    if (data[i] < 24) gone++;
                }
                return seen ? gone / seen : 0;
            }

            function scratchTo(x, y) {
                ctx.lineWidth = BRUSH * 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.beginPath();
                if (lastPt) {
                    ctx.moveTo(lastPt.x, lastPt.y);
                    ctx.lineTo(x, y);
                    ctx.stroke();
                }
                ctx.beginPath();
                ctx.arc(x, y, BRUSH, 0, Math.PI * 2);
                ctx.fill();
                lastPt = { x: x, y: y };

                // the metal that just came off
                if (window.pfx && Math.random() < .5) {
                    var box = card.getBoundingClientRect();
                    pfx.burst('dust', {
                        x: box.left + x, y: box.top + y, count: 3, power: 3.5,
                        colors: FOIL, spread: Math.PI * 2,
                    });
                }
            }

            function pointAt(e) {
                var box = canvas.getBoundingClientRect();
                return { x: e.clientX - box.left, y: e.clientY - box.top };
            }

            function reveal(silent) {
                if (revealed) return;
                revealed = true;
                card.classList.add('done');
                ask.classList.add('on');
                if (!silent && window.pfx) {
                    var box = card.getBoundingClientRect();
                    pfx.burst('dust', {
                        x: box.left + box.width / 2, y: box.top + box.height / 2,
                        count: 46, power: 11, colors: FOIL, spread: Math.PI * 2,
                    });
                }
                try { navigator.vibrate && !reduced && navigator.vibrate([12, 40, 20]); } catch (e) {}
            }

            canvas.addEventListener('pointerdown', function (e) {
                if (revealed) return;
                drawing = true;
                lastPt = null;
                card.classList.add('touched');
                canvas.setPointerCapture(e.pointerId);
                var p = pointAt(e);
                scratchTo(p.x, p.y);
            });

            canvas.addEventListener('pointermove', function (e) {
                if (!drawing || revealed) return;
                var p = pointAt(e);
                scratchTo(p.x, p.y);
            });

            function endStroke() {
                if (!drawing) return;
                drawing = false;
                lastPt = null;
                if (!revealed && cleared() > CLEAR_AT) reveal();
            }

            canvas.addEventListener('pointerup', endStroke);
            canvas.addEventListener('pointercancel', endStroke);
            canvas.addEventListener('pointerleave', endStroke);

            skip.addEventListener('click', function () { reveal(); });

            /** The demo — and anyone who would rather watch than scratch. */
            function autoScratch() {
                if (revealed) return;
                card.classList.add('touched');
                var w = card.clientWidth, h = card.clientHeight;
                var strokes = 4;
                for (var s = 0; s < strokes; s++) {
                    (function (s) {
                        var y = h * (0.26 + s * 0.16);
                        for (var i = 0; i <= 14; i++) {
                            (function (i) {
                                at(s * 260 + i * 17, function () {
                                    if (revealed) return;
                                    if (i === 0) lastPt = null;
                                    var dir = s % 2 ? 1 - i / 14 : i / 14;
                                    scratchTo(w * (0.1 + dir * 0.8),
                                        y + Math.sin(i * 0.7) * h * 0.035);
                                });
                            })(i);
                        }
                    })(s);
                }
                at(strokes * 260 + 180, function () { reveal(); });
            }

            function celebrate() {
                reveal(true);
                if (window.pfx) {
                    var box = card.getBoundingClientRect();
                    pfx.burst('dust', {
                        x: box.left + box.width / 2, y: box.top + box.height / 2,
                        count: 40, power: 12, colors: FOIL, spread: Math.PI * 2,
                    });
                }
                // the surprise is that the card had a back
                at(260, function () { card.classList.add('turning'); });
                at(900, function () { showAfter({ fx: 'confetti' }); });
            }

            function reset() {
                clearAll();
                hideAfter();
                revealed = false;
                drawing = false;
                lastPt = null;
                card.classList.remove('done', 'touched', 'turning');
                ask.classList.remove('on');
                paintFoil();
                if (tease) tease.reset();
            }

            var tease = initTeaseButtons({
                root: ask,
                onYes: celebrate,
                labels: [@json($noLabel), 'are you sure', 'scratch again', 'not that one',
                    'you cannot catch me', 'ok fine'],
                emojis: ['🥺', '😭', '💔', '✨', '🙈'],
            });

            // Repaint on a resize — a foil half scratched is not worth keeping
            // across a rotation, and a stretched canvas would look like a bug.
            var ro = new ResizeObserver(function () {
                if (!revealed) paintFoil();
            });
            ro.observe(card);
            paintFoil();

            window.__proposalDemo = {
                phases: [0, 420, 1300],
                open: autoScratch,
                yes: celebrate,
                reset: reset,
            };

            var pre = @json($previewStage);
            if (pre === 'open' || pre === 'reveal' || pre === 'yes') {
                reveal(true);
                if (pre === 'yes') celebrate();
            }
        })();
    </script>

    @include('birthday.partials._proposal_demo')
</body>

</html>
