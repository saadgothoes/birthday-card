{{--
    Proposal · Design 3 — "Written in the Stars".

    One shared design, re-skinned four ways; the wrapper views
    (proposal-design-3-theme-{1..4}.blade.php) @include this with a
    `proposalTheme` (1-4).

    The cinematic one, and the quietest. There is no box, no card and no
    interface — a sky, and seven stars in it that are a little brighter than
    the rest. The restraint is the point: it is the design that trusts the
    question to be enough, so it is the one to choose when anything sweeter
    would be too much.

        a night sky, drifting  →  tap
        →  the seven stars join, one segment at a time, into a ring
        →  the ring holds and glows
        →  the question, written under it
        Yes  →  a meteor shower, and the letter resolves out of the sky

    The constellation is one SVG drawn with `stroke-dashoffset`, so a segment
    is *drawn* rather than faded in — at this size the difference between a
    line appearing and a line being drawn is the whole feeling.

    The sky behind it is its own canvas: 150-odd stars that drift and twinkle
    on a single rAF loop, paused when the tab is hidden.

    Renders complete with no query parameters.

    Request params:
      to_name, from_name     who it is for / from
      heading                the line above the sky ("Look up")
      tap_label              the hint under it
      ring_photo             the seal on the letter (drawn ring fallback)
      question               under the constellation
      yes_label, no_label    the two buttons
      yes_heading            the heading on the letter the Yes opens
      letter_text            that letter, one line per line, up to 6
      closing_line, signed   the letter's closing line + signature
      theme                  overrides `proposalTheme`
      preview_stage          open | reveal | yes — skip ahead (dashboard preview)
--}}
@php
    $proposalTheme = (int) ($proposalTheme ?? request('theme', 1));

    // All four are dark: a lit sky is not a sky. They differ in what colour
    // the dark is, and what the stars are made of.
    $themes = [
        1 => ['name' => 'Deep Indigo',
              'bg1' => '#1b2450', 'bg2' => '#101733', 'bg3' => '#05070f',
              'haze' => 'rgba(126,148,255,.18)', 'ink' => '#eef1fb', 'soft' => '#a6afd0',
              'accent' => '#ffe9a8', 'star' => '#ffffff', 'line' => 'rgba(255,233,168,.9)',
              'card1' => '#1d2545', 'card2' => '#121934', 'yesInk' => '#20200e',
              'metal1' => '#ffe9a8', 'metal2' => '#c9a24a', 'fx' => '#ffe9a8,#ffffff,#b9c6ff,#ffd27f'],
        2 => ['name' => 'Nebula Rose',
              'bg1' => '#43184a', 'bg2' => '#2a1030', 'bg3' => '#0d060f',
              'haze' => 'rgba(255,150,200,.2)', 'ink' => '#fbeaf3', 'soft' => '#cfa6bd',
              'accent' => '#ffbcd6', 'star' => '#ffffff', 'line' => 'rgba(255,188,214,.92)',
              'card1' => '#3a1640', 'card2' => '#240d29', 'yesInk' => '#3a0f22',
              'metal1' => '#ffd8e6', 'metal2' => '#c9789a', 'fx' => '#ffbcd6,#ffffff,#f5a3ff,#ffe0ec'],
        3 => ['name' => 'Aurora',
              'bg1' => '#0d3a48', 'bg2' => '#08202a', 'bg3' => '#030b10',
              'haze' => 'rgba(120,255,225,.16)', 'ink' => '#e6fbf6', 'soft' => '#93bfb8',
              'accent' => '#8ff0de', 'star' => '#ffffff', 'line' => 'rgba(143,240,222,.92)',
              'card1' => '#0e3b45', 'card2' => '#07222b', 'yesInk' => '#04231f',
              'metal1' => '#c8fff3', 'metal2' => '#5cbfae', 'fx' => '#8ff0de,#ffffff,#7fc9ff,#d8fff7'],
        4 => ['name' => 'Obsidian',
              'bg1' => '#1a1a1e', 'bg2' => '#0d0d10', 'bg3' => '#000000',
              'haze' => 'rgba(255,255,255,.12)', 'ink' => '#f3f2ef', 'soft' => '#a5a39d',
              'accent' => '#e8e6e1', 'star' => '#ffffff', 'line' => 'rgba(232,230,225,.92)',
              'card1' => '#1c1c20', 'card2' => '#111114', 'yesInk' => '#17171a',
              'metal1' => '#f0efec', 'metal2' => '#9d9b95', 'fx' => '#e8e6e1,#ffffff,#c9c6bf,#fff3cf'],
    ];
    $t = $themes[$proposalTheme] ?? $themes[1];

    $d = \App\Http\Controllers\Client\BirthdayCardController::proposalDefaults(3);

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

    // The letter the Yes opens — it resolves out of the sky the way the stars did.
    $letterLines = collect(preg_split('/\r\n|\r|\n/', (string) request('letter_text', $d['letter_text'])))
        ->map(fn ($l) => trim($l))
        ->filter()
        ->take(\App\Http\Controllers\Client\BirthdayCardController::PROPOSAL_MULTILINE['letter_text'])
        ->values();

    // The seven stars, in the order the line walks them: the stone at the top,
    // down one side of the band and back up the other. Worked out here rather
    // than in the markup so the segments below can be generated from them.
    // The ring is drawn the way a ring is shaped: the band is an **arc**
    // between each pair of stars rather than a straight chord, and the stone
    // is a shallow triangle over the one gap in it. Joining seven stars with
    // straight lines gives a kite every time, however the points are moved —
    // the curve is what makes it read as a ring.
    $cx = 100; $cy = 118; $r = 42;

    // band stars, in the order the line walks them: the left shoulder, then
    // clockwise all the way back round to it
    $angles = [228, 312, 0, 55, 125, 180];
    $band = array_map(function ($a) use ($cx, $cy, $r) {
        return [
            round($cx + $r * cos(deg2rad($a)), 1),
            round($cy + $r * sin(deg2rad($a)), 1),
        ];
    }, $angles);

    $stone = [100.0, 62.0];
    $stars = array_merge([$stone], $band);      // 0 is the stone, 1-6 the band

    // Each segment carries the two stars it joins, so lighting them up is not
    // a second copy of this geometry.
    $segments = [];
    $segments[] = ['d' => "M{$band[0][0]} {$band[0][1]} L{$stone[0]} {$stone[1]}", 'a' => 1, 'b' => 0];
    $segments[] = ['d' => "M{$stone[0]} {$stone[1]} L{$band[1][0]} {$band[1][1]}", 'a' => 0, 'b' => 2];
    for ($i = 1; $i < count($band); $i++) {
        $from = $band[$i];
        $to = $band[($i + 1) % count($band)];
        $segments[] = [
            'd' => "M{$from[0]} {$from[1]} A{$r} {$r} 0 0 1 {$to[0]} {$to[1]}",
            'a' => $i + 1,
            'b' => (($i + 1) % count($band)) + 1,
        ];
    }

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $question }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg1: {{ $t['bg1'] }};
            --bg2: {{ $t['bg2'] }};
            --bg3: {{ $t['bg3'] }};
            --haze: {{ $t['haze'] }};
            --ink: {{ $t['ink'] }};
            --soft: {{ $t['soft'] }};
            --accent: {{ $t['accent'] }};
            --star: {{ $t['star'] }};
            --line: {{ $t['line'] }};
            --metal1: {{ $t['metal1'] }};
            --metal2: {{ $t['metal2'] }};
            --serif: 'Instrument Serif', Georgia, serif;
            --sans: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --fx-colors: {{ $t['fx'] }};

            --pt-yes-bg: {{ $t['accent'] }};
            --pt-yes-ink: {{ $t['yesInk'] }};
            --pt-no-bg: rgba(255, 255, 255, .1);
            --pt-no-ink: {{ $t['ink'] }};
            --pt-ink: {{ $t['soft'] }};
            --pt-ring: {{ $t['accent'] }};

            --pk-scrim: rgba(0, 0, 0, .74);
            --pk-bg: linear-gradient(170deg, {{ $t['card1'] }}, {{ $t['card2'] }});
            --pk-ink: {{ $t['ink'] }};
            --pk-soft: rgba(255, 255, 255, .55);
            --pk-accent: {{ $t['accent'] }};
            --pk-line: rgba(255, 255, 255, .16);
            --pk-ring-bg: rgba(255, 255, 255, .08);
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
                radial-gradient(90% 60% at 50% 12%, var(--haze), transparent 65%),
                linear-gradient(180deg, var(--bg1), var(--bg2) 52%, var(--bg3));
            min-height: 100dvh;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        #sky {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .stage {
            position: relative;
            z-index: 2;
            min-height: 100dvh;
            display: grid;
            align-content: center;
            justify-items: center;
            gap: clamp(.4rem, 2vw, .8rem);
            padding: clamp(1.4rem, 6vw, 2.4rem) clamp(1rem, 5vw, 2rem);
            text-align: center;
        }

        .kicker {
            margin: 0;
            font-family: var(--serif);
            font-size: clamp(2rem, 9vw, 2.8rem);
            font-weight: 400;
            line-height: 1;
            transition: opacity .6s ease, transform .6s ease;
        }

        .hint {
            margin: .3rem 0 0;
            font-size: .76rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--soft);
            animation: pulse 2.8s ease-in-out infinite;
            transition: opacity .5s ease;
        }

        @keyframes pulse {
            0%, 100% { opacity: .45; }
            50%      { opacity: 1; }
        }

        /* Both are hidden outright once the sky has been tapped, not merely
           faded: a transparent element still takes the space and still gets
           read out, and the question is what the page is about now. */
        .stage.open .kicker,
        .stage.open .hint {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            animation: none;
            transition: opacity .5s ease, transform .5s ease, visibility 0s linear .5s;
        }

        /* ── the constellation ─────────────────────────────────── */
        .cons {
            width: min(74vw, 300px);
            margin: clamp(.2rem, 2vw, .6rem) 0;
            overflow: visible;
            transition: transform .9s cubic-bezier(.16, 1, .3, 1);
        }

        .stage.open .cons { transform: translateY(-2%) scale(1.03); }

        .cons .seg {
            fill: none;
            stroke: var(--line);
            stroke-width: 1.1;
            stroke-linecap: round;
            stroke-dasharray: var(--len) var(--len);
            stroke-dashoffset: var(--len);
            opacity: .9;
            transition: stroke-dashoffset .42s cubic-bezier(.4, 0, .2, 1);
        }

        .cons .seg.on { stroke-dashoffset: 0; }

        .cons .pt {
            fill: var(--star);
            opacity: .38;
            transform-box: fill-box;
            transform-origin: center;
            transition: opacity .45s ease, r .45s cubic-bezier(.16, 1, .3, 1);
        }

        .cons .pt.lit {
            opacity: 1;
            filter: drop-shadow(0 0 6px var(--line));
        }

        .cons.held { animation: shine 1.1s ease-out; }

        @keyframes shine {
            0%   { filter: none; }
            35%  { filter: drop-shadow(0 0 16px var(--line)); }
            100% { filter: drop-shadow(0 0 5px var(--line)); }
        }

        /* the whole sky is the tap target before it opens */
        .tapper {
            position: fixed;
            inset: 0;
            z-index: 3;
            border: 0;
            background: transparent;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        .tapper.off { display: none; }

        .tapper:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: -8px;
        }

        /* ── the question ──────────────────────────────────────── */
        .ask {
            width: min(100%, 420px);
            opacity: 0;
            transform: translateY(12px);
            visibility: hidden;
            transition: opacity .7s ease, transform .7s cubic-bezier(.16, 1, .3, 1),
                        visibility 0s linear .7s;
        }

        .ask.on {
            opacity: 1;
            transform: none;
            visibility: visible;
            transition: opacity .7s ease, transform .7s cubic-bezier(.16, 1, .3, 1), visibility 0s;
        }

        .ask .to {
            margin: 0 0 .35rem;
            font-size: .72rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--soft);
        }

        .ask .q {
            margin: 0;
            font-family: var(--serif);
            font-weight: 400;
            font-size: clamp(1.8rem, 8.4vw, 2.5rem);
            line-height: 1.08;
            letter-spacing: -.01em;
        }

        [data-tease-stage] { text-align: center; }

        @media (prefers-reduced-motion: reduce) {

            .hint { animation: none; opacity: .8; }

            .cons.held { animation: none; }

            /* the page still moves through every state — it just arrives at
               each one rather than travelling there */
            .cons,
            .cons .seg,
            .ask,
            .stage.open .kicker,
            .stage.open .hint {
                transition-duration: .01ms !important;
                transition-delay: 0s !important;
            }
        }
    </style>
</head>

<body>
    <canvas id="sky" aria-hidden="true"></canvas>

    <div class="stage" id="stage">
        <p class="kicker">{{ $heading }}</p>
        <p class="hint">{{ $tapLabel }}</p>

        <svg class="cons" id="cons" viewBox="0 0 200 190" role="img"
            aria-label="Seven stars joined into the shape of a ring">
            @foreach ($segments as $i => $seg)
                <path class="seg" data-i="{{ $i }}" data-a="{{ $seg['a'] }}" data-b="{{ $seg['b'] }}"
                    d="{{ $seg['d'] }}" />
            @endforeach
            @foreach ($stars as $i => $s)
                <circle class="pt" data-i="{{ $i }}" cx="{{ $s[0] }}" cy="{{ $s[1] }}"
                    r="{{ $i === 0 ? 3.6 : 2.6 }}" />
            @endforeach
        </svg>

        <div class="ask" id="askBlock">
            <p class="to">{{ $toName }}</p>
            <p class="q" data-tease-question>{{ $question }}</p>
            <div data-tease-row>
                <button type="button" data-tease-yes>{{ $yesLabel }}</button>
                <button type="button" data-tease-no>{{ $noLabel }}</button>
            </div>
            <p data-tease-stage></p>
        </div>
    </div>

    <button type="button" class="tapper" id="tapper" aria-label="{{ $tapLabel }}"></button>

    @include('birthday.partials._proposal_fx')
    {{-- the letter resolves out of the sky, the way the constellation did --}}
    @include('birthday.partials._proposal_after', ['afterEnter' => 'sky'])
    @include('birthday.partials._proposal_tease')

    <script>
        (function () {
            var stage = document.getElementById('stage');
            var cons = document.getElementById('cons');
            var tapper = document.getElementById('tapper');
            var ask = document.getElementById('askBlock');
            var segs = [].slice.call(cons.querySelectorAll('.seg'));
            var pts = [].slice.call(cons.querySelectorAll('.pt'));

            var STEP = 340;     // one segment to the next
            var HOLD = 520;     // the constellation holds before the question
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var timers = [];
            var opened = false;

            function at(ms, fn) { timers.push(setTimeout(fn, ms)); }
            function clearAll() { timers.forEach(clearTimeout); timers = []; }
            function questionAt() { return segs.length * STEP + HOLD; }

            // Each segment animates from its own length, so a long one and a
            // short one take the same time to draw.
            segs.forEach(function (s) {
                s.style.setProperty('--len', s.getTotalLength().toFixed(2));
            });

            /* ── the sky ───────────────────────────────────────── */
            var canvas = document.getElementById('sky');
            var ctx = canvas.getContext('2d');
            var stars = [];
            var dpr = Math.min(window.devicePixelRatio || 1, 2);
            var starColor = @json($t['star']);

            function seed() {
                var dpr2 = dpr;
                canvas.width = Math.floor(innerWidth * dpr2);
                canvas.height = Math.floor(innerHeight * dpr2);
                ctx.setTransform(dpr2, 0, 0, dpr2, 0, 0);
                var n = Math.round(Math.min(190, (innerWidth * innerHeight) / 5200));
                stars = [];
                for (var i = 0; i < n; i++) {
                    stars.push({
                        x: Math.random() * innerWidth,
                        y: Math.random() * innerHeight,
                        r: Math.random() * 1.25 + .28,
                        a: Math.random(),
                        // the faint ones twinkle faster; the bright ones sit still
                        sp: (Math.random() * .014 + .003) * (Math.random() < .5 ? 1 : -1),
                        dy: Math.random() * .012 + .002,
                    });
                }
            }

            function drawSky() {
                ctx.clearRect(0, 0, innerWidth, innerHeight);
                for (var i = 0; i < stars.length; i++) {
                    var s = stars[i];
                    if (!reduced) {
                        s.a += s.sp;
                        if (s.a > 1) { s.a = 1; s.sp *= -1; }
                        if (s.a < .12) { s.a = .12; s.sp *= -1; }
                        s.y -= s.dy;
                        if (s.y < -2) { s.y = innerHeight + 2; s.x = Math.random() * innerWidth; }
                    }
                    ctx.globalAlpha = s.a * .9;
                    ctx.fillStyle = starColor;
                    ctx.beginPath();
                    ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.globalAlpha = 1;
                if (!document.hidden) requestAnimationFrame(drawSky);
            }

            seed();
            requestAnimationFrame(drawSky);
            addEventListener('resize', seed);
            document.addEventListener('visibilitychange', function () {
                if (!document.hidden) requestAnimationFrame(drawSky);
            });

            /* ── the sequence ──────────────────────────────────── */
            /** Light a segment, and the two stars it joins. */
            function join(i) {
                var seg = segs[i];
                seg.classList.add('on');
                pts[+seg.dataset.a].classList.add('lit');
                pts[+seg.dataset.b].classList.add('lit');
            }

            function open() {
                if (opened) return;
                opened = true;
                stage.classList.add('open');
                tapper.classList.add('off');

                segs.forEach(function (_, i) {
                    at(i * STEP, function () { join(i); });
                });

                at(segs.length * STEP, function () { cons.classList.add('held'); });
                at(questionAt(), function () { ask.classList.add('on'); });
            }

            function showAll() {
                opened = true;
                stage.classList.add('open');
                tapper.classList.add('off');
                segs.forEach(function (_, i) { join(i); });
                ask.classList.add('on');
            }

            function celebrate() {
                if (window.pfx) {
                    pfx.meteors({ count: 9 });
                    var box = cons.getBoundingClientRect();
                    pfx.burst('stars', {
                        x: box.left + box.width / 2, y: box.top + box.height / 2,
                        count: 34, power: 8, spread: Math.PI * 2,
                    });
                }
                // the sky is given a moment of its own first — the meteors are
                // the surprise, and the letter is what they leave behind
                at(1150, function () { showAfter({ fx: 'meteors' }); });
            }

            function reset() {
                clearAll();
                hideAfter();
                opened = false;
                stage.classList.remove('open');
                tapper.classList.remove('off');
                cons.classList.remove('held');
                segs.forEach(function (s) { s.classList.remove('on'); });
                pts.forEach(function (p) { p.classList.remove('lit'); });
                ask.classList.remove('on');
                if (tease) tease.reset();
            }

            tapper.addEventListener('click', open);

            var tease = initTeaseButtons({
                root: ask,
                onYes: celebrate,
                labels: [@json($noLabel), 'are you sure', 'look up again', 'not written there',
                    'you cannot catch me', 'ok fine'],
                emojis: ['🥺', '💫', '😭', '🌙', '💔'],
                stages: ['', 'hm.', 'it moved.', 'the sky disagrees.',
                    'one of these two is the right answer.', 'and then there was one.'],
            });

            window.__proposalDemo = {
                phases: [0, STEP * 3, questionAt()],
                open: open,
                yes: celebrate,
                reset: reset,
            };

            var pre = @json($previewStage);
            if (pre === 'open' || pre === 'reveal' || pre === 'yes') {
                showAll();
                if (pre === 'yes') celebrate();
            }
        })();
    </script>

    @include('birthday.partials._proposal_demo')
</body>

</html>
