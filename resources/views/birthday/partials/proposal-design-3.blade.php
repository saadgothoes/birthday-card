{{--
    Proposal · Design 3 — "Countdown Reveal".

    One shared design, re-skinned four ways; the wrapper views
    (proposal-design-3-theme-{1..4}.blade.php) @include this with a
    `proposalTheme` (1-4).

    The suspense one, and deliberately the darkest and most different-feeling of
    the four. There is no box, no locket, no bouquet — the countdown *is* the
    hero, so nothing competes with it for attention while it runs:

        dark screen, a line of warning, a big numeral counting down
        (ambient specks drifting upward are the only other motion)
        →  each second the digit crossfades to the next, never flips or jumps
        →  at zero the digit bursts outward and fades (200ms)
        →  a radial wipe opens the reveal (500ms)
        →  the ring scales in over a flare, the question fades up, Yes / No
        Yes  →  three staggered firework bursts, then the heading and either
                the wedding-date card or the fallback line

    The payoff is sized to match what was withheld: fireworks, not a shrug.

    Renders complete with no query parameters.

    Request params:
      to_name, from_name      who it is for / from
      pre_label               the line above the countdown
      countdown_seconds       how long it counts (1-10, default 5)
      ring_photo              the ring in the reveal (drawn ring fallback)
      question                "Will you marry me?"
      yes_label, no_label     the two buttons
      yes_heading             the celebration heading
      wedding_date            a date — renders the "see you at the altar" card
      altar_label             the wording on that card
      fallback_line           shown instead, when there is no date yet
      closing_line, signed    the closing line + signature
      theme                   overrides `proposalTheme`
      preview_stage           reveal | yes — skip the countdown (dashboard preview)
--}}
@php
    $proposalTheme = (int) ($proposalTheme ?? request('theme', 1));

    // All four stay dark — a light countdown would give the withholding away.
    // 1 & 2 are the cool/deep pair, 3 & 4 the warm/soft-lit pair.
    $themes = [
        1 => ['name' => 'Midnight Violet',
              'bg1' => '#3a1f3d', 'bg2' => '#1b1330', 'bg3' => '#0d0918',
              'ink' => '#f6f1ff', 'soft' => '#b7a8cd', 'accent' => '#f0d08a',
              'accent2' => '#c78ce0', 'flare' => 'rgba(255,226,168,.85)',
              'spark' => ['#f0d08a', '#c78ce0', '#ffffff', '#8f7ad6']],
        2 => ['name' => 'Deep Sea',
              'bg1' => '#0d3b4d', 'bg2' => '#07202e', 'bg3' => '#03121b',
              'ink' => '#eafaff', 'soft' => '#93bcc9', 'accent' => '#7fe3d4',
              'accent2' => '#4aa8c9', 'flare' => 'rgba(160,255,240,.8)',
              'spark' => ['#7fe3d4', '#4aa8c9', '#ffffff', '#bff6ec']],
        3 => ['name' => 'Starlit Rose',
              'bg1' => '#5c2a44', 'bg2' => '#2a1526', 'bg3' => '#150a13',
              'ink' => '#fff0f6', 'soft' => '#d0a3b7', 'accent' => '#ffc2d4',
              'accent2' => '#e88fae', 'flare' => 'rgba(255,205,222,.85)',
              'spark' => ['#ffc2d4', '#e88fae', '#ffffff', '#ffe3ec']],
        4 => ['name' => 'Aurora Ice',
              'bg1' => '#2b3566', 'bg2' => '#151a33', 'bg3' => '#080b1a',
              'ink' => '#eef4ff', 'soft' => '#9fb0d6', 'accent' => '#bcd6ff',
              'accent2' => '#7f9fe8', 'flare' => 'rgba(200,222,255,.85)',
              'spark' => ['#bcd6ff', '#7f9fe8', '#ffffff', '#dfe9ff']],
    ];
    $t = $themes[$proposalTheme] ?? $themes[1];

    // One copy of the sample wording, in the controller's design registry.
    $d = \App\Http\Controllers\Client\BirthdayCardController::proposalDefaults(3);

    $toName    = request('to_name', $d['to_name']);
    $fromName  = request('from_name', $d['from_name']);
    $preLabel  = request('pre_label', $d['pre_label']);
    $seconds   = max(1, min(10, (int) request('countdown_seconds', $d['countdown_seconds'])));
    $question  = request('question', $d['question']);
    $yesLabel  = request('yes_label', $d['yes_label']);
    $noLabel   = request('no_label', $d['no_label']);
    $yesHead   = request('yes_heading', $d['yes_heading']);
    $closing   = request('closing_line', $d['closing_line']);
    $signed    = request('signed', $d['signed']);
    $ringPhoto = request('ring_photo');
    $altarLbl  = request('altar_label', $d['altar_label']);
    $fallback  = request('fallback_line', $d['fallback_line']);
    $rawDate   = request('wedding_date');
    $weddingTs = $rawDate ? strtotime((string) $rawDate) : false;
    $weddingOn = $weddingTs ? date('j F Y', $weddingTs) : null;
    $stage     = request('preview_stage');

    // The demo's three beats: counting, the moment it hits zero, and the
    // question. The countdown's own timing decides them — the first tick is
    // 1100ms in and every one after is a second apart.
    $zeroAt = 1100 + ($seconds - 1) * 1000;
    $demoPhases = [0, $zeroAt, $zeroAt + 700];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $question }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg1: {{ $t['bg1'] }};
            --bg2: {{ $t['bg2'] }};
            --bg3: {{ $t['bg3'] }};
            --ink: {{ $t['ink'] }};
            --soft: {{ $t['soft'] }};
            --accent: {{ $t['accent'] }};
            --accent2: {{ $t['accent2'] }};
            --flare: {{ $t['flare'] }};
            --metal1: {{ $t['accent'] }};
            --metal2: {{ $t['accent2'] }};
            --geo: 'Space Grotesk', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --serif: 'Cormorant Garamond', Georgia, serif;

            --pt-yes-bg: {{ $t['accent'] }};
            --pt-yes-ink: {{ $t['bg3'] }};
            --pt-no-bg: rgba(255, 255, 255, .12);
            --pt-no-ink: {{ $t['ink'] }};
            --pt-ink: {{ $t['soft'] }};
            --pt-ring: {{ $t['accent'] }};
        }

        * { box-sizing: border-box; }

        html, body { height: 100%; }

        body {
            margin: 0;
            font-family: var(--geo);
            color: var(--ink);
            background:
                radial-gradient(ellipse 70% 60% at 50% 30%, var(--bg1), transparent 72%),
                linear-gradient(170deg, var(--bg2), var(--bg3));
            background-attachment: fixed;
            min-height: 100svh;
            display: flex;
            align-items: center;
            /* a page taller than the phone must still scroll to its own top */
            align-items: safe center;
            justify-content: center;
            padding: max(1rem, env(safe-area-inset-top)) 1rem max(1.4rem, env(safe-area-inset-bottom));
            /* the drifting specks are position:fixed, so only the page's own
               content decides whether there is anything to scroll */
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* the only continuous motion on the page, and kept faint on purpose */
        .specks { position: fixed; inset: 0; pointer-events: none; z-index: 0; }

        .speck {
            position: absolute;
            bottom: -6vh;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--accent);
            opacity: .0;
            animation: rise linear infinite;
        }

        @keyframes rise {
            0% { transform: translateY(0); opacity: 0; }
            12% { opacity: .5; }
            85% { opacity: .35; }
            100% { transform: translateY(-112vh); opacity: 0; }
        }

        .stage { position: relative; z-index: 2; width: 100%; max-width: 560px; text-align: center; }

        /* ── the countdown ─────────────────────────────────────── */
        .count-view { transition: opacity .35s ease; }

        .count-view.gone { opacity: 0; pointer-events: none; }

        .pre-label {
            font-size: clamp(.72rem, 3.2vw, .86rem);
            letter-spacing: .28em;
            text-transform: uppercase;
            color: var(--soft);
            margin: 0 0 clamp(1.2rem, 5vw, 2rem);
        }

        .digit {
            font-family: var(--geo);
            font-weight: 700;
            font-size: clamp(6rem, 34vw, 11rem);
            line-height: 1;
            letter-spacing: -.03em;
            color: var(--ink);
            text-shadow: 0 0 60px var(--flare);
            transition: opacity .34s ease, transform .34s ease;
        }

        .digit.swap { opacity: 0; transform: scale(.9); }

        .digit.burst { animation: burst .2s ease-out forwards; }

        @keyframes burst {
            to { transform: scale(2.1); opacity: 0; filter: blur(6px); }
        }

        /* ── the wipe ──────────────────────────────────────────── */
        .wipe {
            position: fixed;
            left: 50%;
            top: 50%;
            width: 10px;
            height: 10px;
            margin: -5px 0 0 -5px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--flare), transparent 70%);
            transform: scale(0);
            opacity: 0;
            z-index: 5;
            pointer-events: none;
        }

        .wipe.go { animation: wipeOut .5s cubic-bezier(.3, .7, .4, 1) forwards; }

        @keyframes wipeOut {
            0% { transform: scale(0); opacity: .95; }
            70% { opacity: .8; }
            100% { transform: scale(260); opacity: 0; }
        }

        /* ── the reveal ────────────────────────────────────────── */
        .reveal {
            display: none;
        }

        .reveal.on { display: block; }

        .ring {
            position: relative;
            width: clamp(112px, 34vw, 152px);
            height: clamp(112px, 34vw, 152px);
            margin: 0 auto clamp(1rem, 4vw, 1.5rem);
            display: grid;
            place-items: center;
            transform: scale(.55);
            opacity: 0;
            transition: transform .8s cubic-bezier(.2, .9, .3, 1.35), opacity .6s ease;
        }

        .reveal.lit .ring { transform: scale(1); opacity: 1; }

        .ring::before {
            content: '';
            position: absolute;
            inset: -30%;
            border-radius: 50%;
            background: radial-gradient(circle, var(--flare), transparent 66%);
            animation: flare 2.8s ease-in-out infinite;
        }

        @keyframes flare {
            0%, 100% { opacity: .45; transform: scale(.92); }
            50% { opacity: .95; transform: scale(1.08); }
        }

        .ring img, .ring svg {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .ring img { border: 3px solid var(--accent); }

        .question {
            font-family: var(--serif);
            font-size: clamp(1.6rem, 7.6vw, 2.35rem);
            font-weight: 600;
            line-height: 1.2;
            margin: 0;
            opacity: 0;
            transform: translateY(12px);
            transition: opacity .7s ease .25s, transform .7s ease .25s;
        }

        .reveal.lit .question { opacity: 1; transform: none; }

        .ask {
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: opacity .6s ease .5s, transform .6s ease .5s;
        }

        .reveal.lit .ask { opacity: 1; transform: none; pointer-events: auto; }

        .reveal.said .ask, .reveal.said .question { display: none; }

        /* ── celebration ───────────────────────────────────────── */
        .party {
            display: none;
            margin-top: clamp(.8rem, 3vw, 1.2rem);
        }

        .reveal.said .party { display: block; animation: fadeUp .8s ease both; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: none; }
        }

        .party h2 {
            font-family: var(--serif);
            font-size: clamp(1.75rem, 8.2vw, 2.6rem);
            font-weight: 600;
            margin: 0 0 .8rem;
            line-height: 1.12;
        }

        .date-card {
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .07);
            border-radius: 16px;
            padding: .85rem 1.4rem;
            backdrop-filter: blur(4px);
        }

        .date-card small {
            display: block;
            font-size: clamp(.64rem, 2.8vw, .72rem);
            letter-spacing: .26em;
            text-transform: uppercase;
            color: var(--soft);
            margin-bottom: .3rem;
        }

        .date-card strong {
            font-family: var(--serif);
            font-weight: 600;
            font-size: clamp(1.15rem, 5vw, 1.5rem);
            color: var(--accent);
        }

        .party .closing {
            font-family: var(--serif);
            font-size: clamp(1rem, 4.3vw, 1.16rem);
            line-height: 1.6;
            color: var(--ink);
            opacity: .9;
            margin: 1rem 0 .5rem;
        }

        .party .sign {
            font-size: clamp(.72rem, 3vw, .8rem);
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--soft);
            margin: 0;
        }

        .fw {
            position: fixed;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            z-index: 50;
            pointer-events: none;
            animation: fwOut 1.25s cubic-bezier(.12, .8, .3, 1) forwards;
        }

        @keyframes fwOut {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            100% { transform: translate(calc(-50% + var(--fx)), calc(-50% + var(--fy))) scale(.2); opacity: 0; }
        }

        @media (prefers-reduced-motion: reduce) {

            .speck, .ring::before { animation: none; }

            .wipe.go { animation-duration: .01ms; }

            .digit, .ring, .question, .ask { transition-duration: .01ms !important; }

            .fw { display: none; }
        }
    </style>
</head>

<body>
    <div class="specks" id="specks" aria-hidden="true"></div>
    <div class="wipe" id="wipe" aria-hidden="true"></div>

    <div class="stage">
        <div class="count-view" id="countView">
            <p class="pre-label">{{ $preLabel }}</p>
            <div class="digit" id="digit" aria-live="polite">{{ $seconds }}</div>
        </div>

        <div class="reveal" id="reveal">
            <div class="ring">
                @if ($ringPhoto)
                    <img src="{{ $ringPhoto }}" alt="The ring">
                @else
                    @include('birthday.partials._proposal_ring')
                @endif
            </div>

            <p class="question" data-tease-question>{{ $question }}</p>

            <div class="ask" id="askBlock">
                <div data-tease-row>
                    <button type="button" data-tease-yes>{{ $yesLabel }}</button>
                    <button type="button" data-tease-no>{{ $noLabel }}</button>
                </div>
                <p data-tease-stage></p>
            </div>

            <div class="party" aria-live="polite">
                <h2>{{ $yesHead }}</h2>
                @if ($weddingOn)
                    <div class="date-card">
                        <small>{{ $altarLbl }}</small>
                        <strong>{{ $weddingOn }}</strong>
                    </div>
                @else
                    <div class="date-card">
                        <small>{{ $altarLbl }}</small>
                        <strong>{{ $fallback }}</strong>
                    </div>
                @endif
                <p class="closing">{{ $closing }}</p>
                <p class="sign">{{ $signed }} · {{ $toName }} &amp; {{ $fromName }}</p>
            </div>
        </div>
    </div>

    @include('birthday.partials._proposal_tease')

    <script>
        (function () {
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var digit = document.getElementById('digit');
            var countView = document.getElementById('countView');
            var reveal = document.getElementById('reveal');
            var wipe = document.getElementById('wipe');
            var SPARKS = @json($t['spark']);
            var start = {{ $seconds }};
            var n = start;
            var timer = null;
            var DEMO = @json(request()->boolean('demo'));

            // ambient specks — low opacity, slow, and the only thing moving
            (function specks() {
                if (reduced) return;
                var host = document.getElementById('specks');
                for (var i = 0; i < 26; i++) {
                    var s = document.createElement('i');
                    s.className = 'speck';
                    s.style.left = Math.random() * 100 + 'vw';
                    s.style.animationDuration = (11 + Math.random() * 12) + 's';
                    s.style.animationDelay = (-Math.random() * 18) + 's';
                    s.style.opacity = '1';
                    s.style.transform = 'scale(' + (0.6 + Math.random()) + ')';
                    host.appendChild(s);
                }
            })();

            function tick() {
                n--;
                if (n > 0) {
                    // crossfade, never a flip: fade out, swap the glyph, fade in
                    digit.classList.add('swap');
                    setTimeout(function () {
                        digit.textContent = n;
                        digit.classList.remove('swap');
                    }, reduced ? 0 : 300);
                    timer = setTimeout(tick, 1000);
                    return;
                }
                zero();
            }

            function zero() {
                digit.classList.add('burst');
                setTimeout(function () {
                    countView.classList.add('gone');
                    wipe.classList.add('go');
                    setTimeout(showReveal, reduced ? 0 : 280);
                }, reduced ? 0 : 200);
            }

            /** Start (or restart) the countdown from the top. */
            function startCountdown() {
                clearTimeout(timer);
                n = start;
                digit.textContent = start;
                digit.classList.remove('burst', 'swap');
                countView.style.display = '';
                countView.classList.remove('gone');
                timer = setTimeout(tick, reduced ? 300 : 1100);
            }

            function showReveal() {
                countView.style.display = 'none';
                reveal.classList.add('on');
                // one frame, so the transitions have a state to move from
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () { reveal.classList.add('lit'); });
                });
            }

            function firework(cx, cy, colour) {
                for (var i = 0; i < 26; i++) {
                    var el = document.createElement('i');
                    el.className = 'fw';
                    el.style.left = cx + 'px';
                    el.style.top = cy + 'px';
                    el.style.background = colour;
                    el.style.boxShadow = '0 0 10px ' + colour;
                    var a = (Math.PI * 2 * i) / 26 + Math.random() * .2;
                    var d = 80 + Math.random() * 120;
                    el.style.setProperty('--fx', Math.cos(a) * d + 'px');
                    el.style.setProperty('--fy', Math.sin(a) * d + 'px');
                    document.body.appendChild(el);
                    (function (el) { setTimeout(function () { el.remove(); }, 1300); })(el);
                }
            }

            function celebrate() {
                reveal.classList.add('said');
                if (reduced) return;
                var w = window.innerWidth, h = window.innerHeight;
                [[0.26, 0.3], [0.72, 0.24], [0.5, 0.44], [0.35, 0.2]].forEach(function (p, i) {
                    setTimeout(function () {
                        firework(w * p[0], h * p[1], SPARKS[i % SPARKS.length]);
                    }, i * 380);
                });
            }

            var tease = window.initTeaseButtons({
                root: document.getElementById('askBlock'),
                onYes: celebrate,
            });

            /** Back to the dark screen and the first numeral, for the demo. */
            function reset() {
                clearTimeout(timer);
                reveal.classList.remove('on', 'lit', 'said');
                wipe.classList.remove('go');
                // the wipe and the burst are animations, not transitions: they
                // only replay if the browser is made to notice they left
                void wipe.offsetWidth;
                digit.classList.remove('burst', 'swap');
                digit.textContent = start;
                n = start;
                countView.style.display = '';
                countView.classList.remove('gone');
                if (tease) tease.reset();
            }

            window.__proposalDemo = {
                phases: @json($demoPhases),
                open: startCountdown,
                yes: celebrate,
                reset: reset,
            };

            var pre = @json($stage);
            if (pre === 'reveal' || pre === 'yes') {
                countView.style.display = 'none';
                reveal.classList.add('on', 'lit');
                if (pre === 'yes') celebrate();
            } else if (!DEMO) {
                // The countdown is the hero; it starts on its own, because there
                // is nothing else on the page to tap first. In a demo the loop
                // starts it instead, so the two do not both run one countdown.
                timer = setTimeout(tick, reduced ? 300 : 1100);
            }
        })();
    </script>

    @include('birthday.partials._proposal_demo')
</body>

</html>
