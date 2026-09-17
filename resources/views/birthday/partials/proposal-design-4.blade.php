{{--
    Proposal · Design 4 — "The Roll".

    One shared design, re-skinned four ways; the wrapper views
    (proposal-design-4-theme-{1..4}.blade.php) @include this with a
    `proposalTheme` (1-4).

    The personal one. The other three are made of type and motion; this one is
    made of *their own photographs*, so it is the design that is different for
    every couple who sends it. The question arrives at the end of a stack they
    have already gone through — it is the last frame of a roll, not an opening
    statement.

        a stack of polaroids, captioned  →  flick one away
        →  the next, and the next, under their own thumb
        →  the last frame is blank, and develops in front of them
        →  the ring, and the question written on its border
        Yes  →  the whole roll flies back, the last frame is turned over,
                and the letter is written on the back of it

    The flick is a real drag: the card follows the finger, rotates with the
    distance, and is thrown when it is let go past the threshold — under the
    threshold it springs back, which is what makes the threshold discoverable
    without a word of instruction.

    A card with no photo uploaded for it is not an empty box: it falls back to
    a duotone in the theme's own colours, so a card sent with no photos at all
    still looks deliberate.

    Renders complete with no query parameters.

    Request params:
      to_name, from_name     who it is for / from
      heading                the line above the stack
      tap_label              the hint under it
      caption_text           one caption per line, up to 4 — one card each
      photo_1, photo_2, photo_3   the photographs, in order
      ring_photo             the last frame, and the seal on the letter (drawn fallback)
      question               written on the last frame's border
      yes_label, no_label    the two buttons
      yes_heading            the heading on the letter the Yes opens
      letter_text            that letter, written on the back of the last frame
      closing_line, signed   the letter's closing line + signature
      theme                  overrides `proposalTheme`
      preview_stage          open | reveal | yes — skip ahead (dashboard preview)
--}}
@php
    $proposalTheme = (int) ($proposalTheme ?? request('theme', 1));

    $themes = [
        1 => ['name' => 'Film Cream',
              'bg1' => '#f6efe3', 'bg2' => '#e4d8c6', 'ink' => '#3b3126', 'soft' => '#8b7c68',
              'accent' => '#c0654e', 'paper' => '#fffdf8', 'line' => 'rgba(59,49,38,.14)',
              'duo1' => '#e8d5bd', 'duo2' => '#c0654e', 'yesInk' => '#fffdf8',
              'card1' => '#fffdf8', 'card2' => '#f1e4d2', 'cardInk' => '#3b3126',
              'cardSoft' => 'rgba(59,49,38,.55)', 'cardLine' => 'rgba(59,49,38,.15)',
              'scrim' => 'rgba(46,36,26,.72)',
              'metal1' => '#e6c19c', 'metal2' => '#c0654e',
              'fx' => '#c0654e,#e8d5bd,#ffffff,#8b7c68'],
        2 => ['name' => 'Sunwash',
              'bg1' => '#fff3e6', 'bg2' => '#ffd9c0', 'ink' => '#4a2a1d', 'soft' => '#9a6b53',
              'accent' => '#e57a52', 'paper' => '#fffcf9', 'line' => 'rgba(74,42,29,.13)',
              'duo1' => '#ffd9c0', 'duo2' => '#e57a52', 'yesInk' => '#fffcf9',
              'card1' => '#fffcf9', 'card2' => '#ffe7d6', 'cardInk' => '#4a2a1d',
              'cardSoft' => 'rgba(74,42,29,.55)', 'cardLine' => 'rgba(74,42,29,.14)',
              'scrim' => 'rgba(66,34,22,.7)',
              'metal1' => '#ffd9c0', 'metal2' => '#e57a52',
              'fx' => '#e57a52,#ffd9c0,#ffffff,#ffb38a'],
        3 => ['name' => 'Darkroom',
              'bg1' => '#202124', 'bg2' => '#0e0f11', 'ink' => '#f2f0ea', 'soft' => '#a6a49d',
              'accent' => '#f0c05a', 'paper' => '#1c1d21', 'line' => 'rgba(255,255,255,.13)',
              'duo1' => '#3a3b42', 'duo2' => '#f0c05a', 'yesInk' => '#20190a',
              'card1' => '#212227', 'card2' => '#141519', 'cardInk' => '#f2f0ea',
              'cardSoft' => 'rgba(242,240,234,.55)', 'cardLine' => 'rgba(255,255,255,.15)',
              'scrim' => 'rgba(4,4,6,.84)',
              'metal1' => '#f6dfae', 'metal2' => '#f0c05a',
              'fx' => '#f0c05a,#f6dfae,#ffffff,#a6a49d'],
        4 => ['name' => 'Cobalt',
              'bg1' => '#1e3c70', 'bg2' => '#101f3c', 'ink' => '#eef3fb', 'soft' => '#a3b4d0',
              'accent' => '#ffd66b', 'paper' => '#16305e', 'line' => 'rgba(255,255,255,.14)',
              'duo1' => '#2c4d85', 'duo2' => '#ffd66b', 'yesInk' => '#251c05',
              'card1' => '#1c3767', 'card2' => '#122443', 'cardInk' => '#eef3fb',
              'cardSoft' => 'rgba(238,243,251,.55)', 'cardLine' => 'rgba(255,255,255,.16)',
              'scrim' => 'rgba(4,10,22,.82)',
              'metal1' => '#ffe6a8', 'metal2' => '#ffd66b',
              'fx' => '#ffd66b,#ffe6a8,#ffffff,#7fa8e8'],
    ];
    $t = $themes[$proposalTheme] ?? $themes[1];

    $d = \App\Http\Controllers\Client\BirthdayCardController::proposalDefaults(4);

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

    // One caption in is one card out; the photos fill them in order, and a
    // card without one is a duotone rather than a hole.
    $captions = collect(preg_split('/\r\n|\r|\n/', (string) request('caption_text', $d['caption_text'])))
        ->map(fn ($l) => trim($l))
        ->filter()
        ->take(\App\Http\Controllers\Client\BirthdayCardController::PROPOSAL_MULTILINE['caption_text'])
        ->values();

    $photos = [request('photo_1'), request('photo_2'), request('photo_3')];

    // What is written on the back of the last frame, opened by the Yes.
    $letterLines = collect(preg_split('/\r\n|\r|\n/', (string) request('letter_text', $d['letter_text'])))
        ->map(fn ($l) => trim($l))
        ->filter()
        ->take(\App\Http\Controllers\Client\BirthdayCardController::PROPOSAL_MULTILINE['letter_text'])
        ->values();

    // the stack is drawn back to front, so the first card is the one on top
    $cards = $captions->map(fn ($c, $i) => ['caption' => $c, 'photo' => $photos[$i] ?? null]);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $question }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg1: {{ $t['bg1'] }};
            --bg2: {{ $t['bg2'] }};
            --ink: {{ $t['ink'] }};
            --soft: {{ $t['soft'] }};
            --accent: {{ $t['accent'] }};
            --paper: {{ $t['paper'] }};
            --line: {{ $t['line'] }};
            --duo1: {{ $t['duo1'] }};
            --duo2: {{ $t['duo2'] }};
            --metal1: {{ $t['metal1'] }};
            --metal2: {{ $t['metal2'] }};
            --serif: 'Instrument Serif', Georgia, serif;
            --hand: 'Caveat', 'Segoe Script', cursive;
            --sans: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --fx-colors: {{ $t['fx'] }};

            --pt-yes-bg: {{ $t['accent'] }};
            --pt-yes-ink: {{ $t['yesInk'] }};
            --pt-no-bg: rgba(255, 255, 255, .16);
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
                radial-gradient(110% 70% at 50% 0%, rgba(255, 255, 255, .16), transparent 60%),
                linear-gradient(175deg, var(--bg1), var(--bg2));
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: clamp(1rem, 5vw, 2rem);
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .wrap {
            width: min(100%, 360px);
            text-align: center;
        }

        .kicker {
            margin: 0 0 .25rem;
            font-family: var(--serif);
            font-weight: 400;
            font-size: clamp(1.7rem, 7.5vw, 2.2rem);
            line-height: 1.05;
        }

        .hint {
            margin: 0 0 clamp(.9rem, 4vw, 1.2rem);
            font-size: .74rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--soft);
            transition: opacity .4s ease;
        }

        .wrap.done .hint { opacity: 0; }

        /* ── the stack ─────────────────────────────────────────── */
        .stack {
            position: relative;
            width: min(74vw, 268px);
            aspect-ratio: 1 / 1.22;
            margin: 0 auto;
        }

        .pol {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            padding: 9px 9px 0;
            border-radius: 5px;
            background: var(--paper);
            box-shadow: 0 22px 44px -26px rgba(0, 0, 0, .8), 0 0 0 1px var(--line);
            transform: rotate(var(--rot)) translateY(var(--lift));
            transition: transform .55s cubic-bezier(.16, 1, .3, 1), opacity .5s ease;
            touch-action: none;
            cursor: grab;
            will-change: transform;
        }

        .pol.dragging {
            transition: none;
            cursor: grabbing;
        }

        .pol .shot {
            flex: 1;
            min-height: 0;
            overflow: hidden;
            background: linear-gradient(145deg, var(--duo1), var(--duo2));
            display: grid;
            place-items: center;
        }

        .pol .shot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pol .shot svg {
            width: 62%;
            height: 62%;
        }

        .pol .cap {
            flex: none;
            display: grid;
            place-items: center;
            height: 3.1rem;
            padding: 0 .4rem;
            font-family: var(--hand);
            font-size: clamp(1.05rem, 4.6vw, 1.3rem);
            line-height: 1.15;
            color: var(--ink);
            overflow: hidden;
        }

        /* with no ring photo the frame is paper, so the drawn ring reads —
           on the duotone the gold sat on gold and vanished */
        .pol.last .shot {
            background: radial-gradient(120% 90% at 50% 42%, #ffffff, #f0e7dc);
        }

        /* the last frame comes out of the camera undeveloped */
        .pol.last .shot {
            filter: blur(9px) saturate(.15) brightness(1.5);
            opacity: .55;
            transition: filter 1.5s ease, opacity 1.5s ease;
        }

        .pol.last.developed .shot {
            filter: none;
            opacity: 1;
        }

        .pol.last .cap {
            font-family: var(--serif);
            font-size: clamp(1.15rem, 5.2vw, 1.45rem);
            color: var(--ink);
            opacity: 0;
            transition: opacity .6s ease .5s;
        }

        .pol.last.developed .cap { opacity: 1; }

        /* the Yes turns the last frame over — people write on the back of
           photographs, and that is where the letter is */
        .pol.last.turning {
            transform: perspective(1100px) rotate(-4deg) rotateY(-102deg);
            opacity: .15;
            transition: transform .66s cubic-bezier(.5, 0, .75, 0), opacity .5s ease .14s;
        }

        /* ── the question ──────────────────────────────────────── */
        .ask {
            margin-top: clamp(1rem, 4vw, 1.35rem);
            opacity: 0;
            transform: translateY(10px);
            visibility: hidden;
            transition: opacity .5s ease, transform .5s cubic-bezier(.16, 1, .3, 1),
                        visibility 0s linear .55s;
        }

        .ask.on {
            opacity: 1;
            transform: none;
            visibility: visible;
            transition: opacity .5s ease, transform .5s cubic-bezier(.16, 1, .3, 1), visibility 0s;
        }

        .ask .to {
            margin: 0 0 .3rem;
            font-size: .72rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--soft);
        }

        [data-tease-stage] { text-align: center; }

        /* the way through for a keyboard, and for anyone who would rather not
           swipe four times */
        .skip {
            display: block;
            margin: .9rem auto 0;
            padding: .5em 1.1em;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: transparent;
            color: var(--soft);
            font: inherit;
            font-size: .78rem;
            cursor: pointer;
            transition: color .2s ease, border-color .2s ease;
        }

        .skip:hover { color: var(--ink); border-color: var(--accent); }

        .skip:focus-visible { outline: 3px solid var(--accent); outline-offset: 3px; }

        .wrap.done .skip { display: none; }

        @media (prefers-reduced-motion: reduce) {

            .pol,
            .ask {
                transition-duration: .01ms !important;
                transition-delay: 0s !important;
            }

            /* the one exception: a photograph developing is the point of the
               last frame, so it still develops — just quickly */
            .pol.last .shot,
            .pol.last .cap {
                transition-duration: .3s !important;
                transition-delay: 0s !important;
            }
        }
    </style>
</head>

<body>
    <div class="wrap" id="wrap">
        <h1 class="kicker">{{ $heading }}</h1>
        <p class="hint">{{ $tapLabel }}</p>

        <div class="stack" id="stack">
            {{-- drawn back to front: the last card in the DOM is the one on top --}}
            <div class="pol last" id="lastCard" style="--rot:1.5deg; --lift:0px;">
                <div class="shot">
                    @if ($ringPhoto)
                        <img src="{{ $ringPhoto }}" alt="The ring">
                    @else
                        @include('birthday.partials._proposal_ring')
                    @endif
                </div>
                <div class="cap">{{ $question }}</div>
            </div>

            {{-- ->values() matters: reverse() keeps the original keys, and
                 the depth below is worked out from the position --}}
            @foreach ($cards->reverse()->values() as $i => $c)
                @php
                    // back to front: the first card gets the smallest lift and
                    // the straightest angle, so the top of the stack is tidy
                    $depth = $cards->count() - 1 - $i;
                    $rot = [-3.4, 2.6, -1.8, 3.2][$depth % 4];
                @endphp
                <div class="pol" data-card="{{ $depth }}"
                    style="--rot:{{ $rot }}deg; --lift:{{ -$depth * 3 }}px; z-index:{{ 10 + $i }};">
                    <div class="shot">
                        @if ($c['photo'])
                            <img src="{{ $c['photo'] }}" alt="">
                        @endif
                    </div>
                    <div class="cap">{{ $c['caption'] }}</div>
                </div>
            @endforeach
        </div>

        <button type="button" class="skip" id="skip">Skip to the last one</button>

        <div class="ask" id="askBlock">
            <p class="to">{{ $toName }}</p>
            <div data-tease-row>
                <button type="button" data-tease-yes>{{ $yesLabel }}</button>
                <button type="button" data-tease-no>{{ $noLabel }}</button>
            </div>
            <p data-tease-stage></p>
        </div>
    </div>

    @include('birthday.partials._proposal_fx')
    {{-- the last frame is turned over, and the letter is written on its back --}}
    @include('birthday.partials._proposal_after', ['afterEnter' => 'photo'])
    @include('birthday.partials._proposal_tease')

    <script>
        (function () {
            var wrap = document.getElementById('wrap');
            var stack = document.getElementById('stack');
            var lastCard = document.getElementById('lastCard');
            var ask = document.getElementById('askBlock');
            var skip = document.getElementById('skip');

            // top of the stack first — the order a finger meets them
            var cards = [].slice.call(stack.querySelectorAll('.pol:not(.last)')).reverse();
            var THROW = 74;        // how far a drag has to go to count
            var AUTO_STEP = 620;   // the demo's own pace
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var timers = [];
            var flicked = [];
            var idx = 0;
            var done = false;

            function at(ms, fn) { timers.push(setTimeout(fn, ms)); }
            function clearAll() { timers.forEach(clearTimeout); timers = []; }
            function questionAt() { return cards.length * AUTO_STEP + 1250; }

            function top() { return idx < cards.length ? cards[idx] : null; }

            /** Throw the top card off screen; when the stack is empty, develop. */
            function flick(dir) {
                var card = top();
                if (!card || done) return;
                idx++;
                flicked.push(card);
                card.classList.remove('dragging');
                card.style.transition = 'transform .62s cubic-bezier(.3,.8,.4,1), opacity .5s ease .1s';
                card.style.transform = 'translate(' + (dir * 135) + '%, -14%) rotate(' +
                    (dir * 24) + 'deg)';
                card.style.opacity = '0';
                try { navigator.vibrate && !reduced && navigator.vibrate(9); } catch (e) {}
                if (idx >= cards.length) at(260, develop);
            }

            function develop() {
                if (done) return;
                done = true;
                wrap.classList.add('done');
                lastCard.classList.add('developed');
                at(reduced ? 300 : 1300, function () { ask.classList.add('on'); });
            }

            /* ── the drag ──────────────────────────────────────── */
            var start = null;
            var active = null;

            stack.addEventListener('pointerdown', function (e) {
                var card = top();
                if (!card || done || !card.contains(e.target)) return;
                active = card;
                start = { x: e.clientX, y: e.clientY };
                card.classList.add('dragging');
                card.setPointerCapture(e.pointerId);
            });

            stack.addEventListener('pointermove', function (e) {
                if (!active || !start) return;
                var dx = e.clientX - start.x;
                var dy = e.clientY - start.y;
                active.style.transform = 'translate(' + dx + 'px,' + dy * .4 + 'px) rotate(' +
                    (dx / 14) + 'deg)';
            });

            function release(e) {
                if (!active || !start) return;
                var dx = e.clientX - start.x;
                var card = active;
                active = null;
                start = null;
                card.classList.remove('dragging');
                if (Math.abs(dx) > THROW) {
                    flick(dx > 0 ? 1 : -1);
                } else if (Math.abs(dx) < 6) {
                    // a tap, not a drag — that counts too
                    card.style.transform = '';
                    flick(Math.random() < .5 ? -1 : 1);
                } else {
                    card.style.transform = '';   // back to the stack
                }
            }

            stack.addEventListener('pointerup', release);
            stack.addEventListener('pointercancel', function () {
                if (active) { active.style.transform = ''; active.classList.remove('dragging'); }
                active = null;
                start = null;
            });

            skip.addEventListener('click', function () {
                while (top()) flick(Math.random() < .5 ? -1 : 1);
            });

            /** The demo — one card at a time, at a readable pace. */
            function auto() {
                cards.forEach(function (_, i) {
                    at(i * AUTO_STEP, function () { flick(i % 2 ? 1 : -1); });
                });
            }

            function celebrate() {
                // the roll comes back: every card that was thrown away returns,
                // scattered across the screen, before the frame is turned over
                flicked.forEach(function (card, i) {
                    at(i * 90, function () {
                        card.style.transition = 'transform .8s cubic-bezier(.16,1,.3,1), opacity .5s ease';
                        card.style.opacity = '.92';
                        card.style.transform = 'translate(' + ((Math.random() - .5) * 150).toFixed(0) +
                            '%,' + ((Math.random() - .5) * 120).toFixed(0) + '%) rotate(' +
                            ((Math.random() - .5) * 40).toFixed(0) + 'deg) scale(.82)';
                    });
                });

                if (window.pfx) {
                    var box = lastCard.getBoundingClientRect();
                    pfx.burst('hearts', {
                        x: box.left + box.width / 2, y: box.top + box.height / 2,
                        count: 22, power: 8,
                    });
                }

                // the roll comes back first, then the frame they are holding is
                // turned over — the letter was on the back of it all along
                at(620, function () { lastCard.classList.add('turning'); });
                at(1180, function () { showAfter({ fx: 'confetti' }); });
            }

            function reset() {
                clearAll();
                hideAfter();
                idx = 0;
                done = false;
                flicked = [];
                wrap.classList.remove('done');
                lastCard.classList.remove('developed', 'turning');
                ask.classList.remove('on');
                cards.forEach(function (card) {
                    card.style.transition = '';
                    card.style.transform = '';
                    card.style.opacity = '';
                });
                if (tease) tease.reset();
            }

            var tease = initTeaseButtons({
                root: ask,
                onYes: celebrate,
                labels: [@json($noLabel), 'are you sure', 'swipe again', 'wrong frame',
                    'you cannot catch me', 'ok fine'],
                emojis: ['🥺', '😭', '📷', '💔', '🙈'],
                stages: ['', 'hm.', 'it moved.', 'that one is not in the roll.',
                    'one of these two is the right answer.', 'and then there was one.'],
            });

            window.__proposalDemo = {
                phases: [0, AUTO_STEP, questionAt()],
                open: auto,
                yes: celebrate,
                reset: reset,
            };

            var pre = @json($previewStage);
            if (pre === 'open' || pre === 'reveal' || pre === 'yes') {
                cards.forEach(function (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translate(-135%,-14%) rotate(-24deg)';
                });
                idx = cards.length;
                done = true;
                wrap.classList.add('done');
                lastCard.classList.add('developed');
                ask.classList.add('on');
                flicked = cards.slice();
                if (pre === 'yes') celebrate();
            }
        })();
    </script>

    @include('birthday.partials._proposal_demo')
</body>

</html>
