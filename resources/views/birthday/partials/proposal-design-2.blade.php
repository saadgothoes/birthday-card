{{--
    Proposal · Design 2 — "Locket / Heart Open".

    One shared design, re-skinned four ways; the wrapper views
    (proposal-design-2-theme-{1..4}.blade.php) @include this with a
    `proposalTheme` (1-4).

    The heirloom one. It spends all of its boldness in a single place — the
    heart-shaped locket splitting down its seam and swinging open like a pair
    of doors — and keeps everything around that deliberately quiet: a slow
    breathing idle before the tap, and a celebration that is two portraits
    drifting into one another rather than a firework.

        closed locket (breathing)  →  tap
        →  the two halves swing outward on the seam (500ms)
        →  behind them, a round photo of the two of you and the ring
        →  the question and the Yes / No buttons
        Yes  →  photo and ring pull together into an overlap, hearts burst
                out of the join, the closing line fades in

    Each half is the same heart, clipped to its side (`inset(0 50% 0 0)` /
    `inset(0 0 0 50%)`) and hinged on its outer edge, so the seam is exactly
    down the middle and the two doors are guaranteed to match.

    Renders complete with no query parameters.

    Request params:
      to_name, from_name     who it is for / from
      heading                small kicker ("Open me")
      tap_label              hint under the locket
      couple_photo           the round photo inside (drawn silhouette fallback)
      ring_photo             the ring beside it (drawn ring fallback)
      question               "Will you marry me?"
      yes_label, no_label    the two buttons
      yes_heading            the celebration heading
      closing_line, signed   closing line + signature
      theme                  overrides `proposalTheme`
      preview_stage          open | yes — skip ahead (dashboard preview)
--}}
@php
    $proposalTheme = (int) ($proposalTheme ?? request('theme', 1));

    // 1 & 4 are the deep, metal-heavy pair; 2 & 3 the soft pair.
    $themes = [
        1 => ['name' => 'Burgundy & Gold',
              'bg1' => '#a35a56', 'bg2' => '#5c1420', 'spot' => 'rgba(255,222,180,.30)',
              'ink' => '#f8ece0', 'soft' => '#e0bcae', 'accent' => '#c9a75c',
              'metal1' => '#f0d79b', 'metal2' => '#a37f36', 'seam' => '#f5e3ae',
              'heart1' => '#c9a75c', 'heart2' => '#8a6524', 'plate' => '#f6ecd6'],
        2 => ['name' => 'Rose Quartz',
              'bg1' => '#f6dce4', 'bg2' => '#dba9bd', 'spot' => 'rgba(255,255,255,.85)',
              'ink' => '#4b2937', 'soft' => '#8b6274', 'accent' => '#a4485f',
              'metal1' => '#f2cfda', 'metal2' => '#c98ea3', 'seam' => '#ffffff',
              'heart1' => '#e5a7bb', 'heart2' => '#b4728a', 'plate' => '#fffafc'],
        3 => ['name' => 'Champagne Ivory',
              'bg1' => '#f7efe3', 'bg2' => '#e2cdae', 'spot' => 'rgba(255,255,255,.88)',
              'ink' => '#463524', 'soft' => '#87715a', 'accent' => '#9c7247',
              'metal1' => '#f0dcb8', 'metal2' => '#c0a071', 'seam' => '#fffdf6',
              'heart1' => '#e3c99c', 'heart2' => '#b0905f', 'plate' => '#fffaf0'],
        4 => ['name' => 'Onyx & Silver',
              'bg1' => '#33393f', 'bg2' => '#14171b', 'spot' => 'rgba(214,226,238,.22)',
              'ink' => '#eef2f6', 'soft' => '#a9b4be', 'accent' => '#cfd6dd',
              'metal1' => '#e7edf3', 'metal2' => '#909aa4', 'seam' => '#ffffff',
              'heart1' => '#b9c3cc', 'heart2' => '#6f7982', 'plate' => '#f4f7fa'],
    ];
    $t = $themes[$proposalTheme] ?? $themes[1];

    // One copy of the sample wording, in the controller's design registry.
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
    $couple   = request('couple_photo');
    $ring     = request('ring_photo');
    $stage    = request('preview_stage');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $question }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg1: {{ $t['bg1'] }};
            --bg2: {{ $t['bg2'] }};
            --spot: {{ $t['spot'] }};
            --ink: {{ $t['ink'] }};
            --soft: {{ $t['soft'] }};
            --accent: {{ $t['accent'] }};
            --metal1: {{ $t['metal1'] }};
            --metal2: {{ $t['metal2'] }};
            --seam: {{ $t['seam'] }};
            --heart1: {{ $t['heart1'] }};
            --heart2: {{ $t['heart2'] }};
            --plate: {{ $t['plate'] }};
            --serif: 'Cormorant Garamond', Georgia, serif;
            --sans: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;

            --pt-yes-bg: {{ $t['accent'] }};
            --pt-yes-ink: {{ $proposalTheme === 4 || $proposalTheme === 2 || $proposalTheme === 3 ? '#ffffff' : '#3a2408' }};
            --pt-no-bg: rgba(255, 255, 255, .16);
            --pt-no-ink: {{ $t['ink'] }};
            --pt-ink: {{ $t['soft'] }};
            --pt-ring: {{ $t['accent'] }};
        }

        * { box-sizing: border-box; }

        html, body { height: 100%; }

        body {
            margin: 0;
            font-family: var(--sans);
            color: var(--ink);
            background:
                radial-gradient(ellipse 68% 52% at 50% 40%, var(--spot), transparent 70%),
                linear-gradient(160deg, var(--bg1), var(--bg2));
            background-attachment: fixed;
            min-height: 100svh;
            display: flex;
            align-items: center;
            /* a page taller than the phone must still scroll to its own top */
            align-items: safe center;
            justify-content: center;
            padding: max(1rem, env(safe-area-inset-top)) 1rem max(1.4rem, env(safe-area-inset-bottom));
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .stage { width: 100%; max-width: 540px; text-align: center; }

        .kicker {
            font-size: clamp(.66rem, 2.9vw, .74rem);
            letter-spacing: .34em;
            text-transform: uppercase;
            color: var(--soft);
            margin: 0 0 .4rem;
        }

        .to-name {
            font-family: var(--serif);
            font-size: clamp(1.7rem, 8vw, 2.4rem);
            font-weight: 600;
            margin: 0 0 clamp(1.1rem, 4vw, 1.7rem);
        }

        /* ── the locket ────────────────────────────────────────── */
        .locket {
            position: relative;
            width: clamp(210px, 64vw, 290px);
            height: clamp(195px, 59vw, 268px);
            margin: 0 auto;
            perspective: 900px;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        .locket:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 12px;
            border-radius: 18px;
        }

        .locket.idle { animation: breathe 3.6s ease-in-out infinite; }

        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* the two halves are the same heart, each clipped to its own side and
           hinged on its outer edge — so the seam is exactly down the middle */
        .half {
            position: absolute;
            inset: 0;
            transition: transform .5s cubic-bezier(.3, .85, .35, 1.05), filter .5s ease;
            backface-visibility: hidden;
            z-index: 3;
        }

        .half svg { width: 100%; height: 100%; display: block; }

        .half.l { clip-path: inset(0 50% 0 0); transform-origin: left center; }
        .half.r { clip-path: inset(0 0 0 50%); transform-origin: right center; }

        .locket.open .half.l { transform: rotateY(-112deg); filter: brightness(.82); }
        .locket.open .half.r { transform: rotateY(112deg); filter: brightness(.82); }

        .clasp {
            position: absolute;
            left: 50%;
            top: 46%;
            width: 10px;
            height: 26px;
            margin-left: -5px;
            border-radius: 5px;
            background: linear-gradient(180deg, var(--seam), var(--metal2));
            box-shadow: 0 2px 6px rgba(0, 0, 0, .35);
            z-index: 4;
            transition: opacity .3s ease;
        }

        .locket.open .clasp { opacity: 0; }

        /* ── what is inside ────────────────────────────────────── */
        .inside {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(.5rem, 3vw, 1rem);
            opacity: 0;
            transform: scale(.85);
            transition: opacity .5s ease .25s, transform .6s cubic-bezier(.25, .9, .3, 1.2) .25s;
            z-index: 2;
        }

        .locket.open .inside { opacity: 1; transform: scale(1); }

        .disc {
            width: clamp(74px, 23vw, 100px);
            height: clamp(74px, 23vw, 100px);
            border-radius: 50%;
            overflow: hidden;
            background: var(--plate);
            border: 3px solid var(--metal1);
            box-shadow: 0 10px 24px -12px rgba(0, 0, 0, .65);
            display: grid;
            place-items: center;
            transition: transform .9s cubic-bezier(.25, .9, .3, 1.25);
        }

        .disc img, .disc svg { width: 100%; height: 100%; object-fit: cover; }

        .disc.ring-disc { background: radial-gradient(circle at 50% 40%, #fff, var(--plate)); }

        /* the embrace: the two discs drift into one overlap */
        .locket.joined .disc.photo-disc { transform: translateX(28%) rotate(-4deg); }
        .locket.joined .disc.ring-disc { transform: translateX(-28%) rotate(4deg); z-index: 2; }

        /* ── chrome ────────────────────────────────────────────── */
        .tap-label {
            margin: clamp(1.1rem, 4vw, 1.5rem) 0 0;
            font-size: clamp(.76rem, 3.2vw, .86rem);
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--soft);
            animation: nudge 2.4s ease-in-out infinite;
        }

        @keyframes nudge {
            0%, 100% { opacity: .58; }
            50% { opacity: 1; }
        }

        .locket.open ~ .tap-label { display: none; }

        .ask {
            margin-top: clamp(1.2rem, 4.5vw, 1.7rem);
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: opacity .6s ease, transform .6s ease;
        }

        .stage.asked .ask { opacity: 1; transform: none; pointer-events: auto; }

        .question {
            font-family: var(--serif);
            font-size: clamp(1.5rem, 7vw, 2.1rem);
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
        }

        .closing {
            margin: clamp(1.1rem, 4vw, 1.6rem) auto 0;
            max-width: 420px;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity .8s ease .5s, transform .8s ease .5s;
        }

        .stage.said .closing { opacity: 1; transform: none; }
        .stage.said .ask { display: none; }

        .closing h2 {
            font-family: var(--serif);
            font-size: clamp(1.6rem, 7.5vw, 2.3rem);
            font-weight: 600;
            margin: 0 0 .45rem;
        }

        .closing p {
            font-family: var(--serif);
            font-size: clamp(1rem, 4.3vw, 1.18rem);
            line-height: 1.6;
            margin: 0 0 .8rem;
            opacity: .92;
        }

        .closing .sign {
            font-family: var(--sans);
            font-size: clamp(.74rem, 3.1vw, .84rem);
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--soft);
        }

        .hburst {
            position: fixed;
            z-index: 60;
            pointer-events: none;
            font-size: 1.4rem;
            animation: hb 1.5s cubic-bezier(.2, .7, .3, 1) forwards;
        }

        @keyframes hb {
            0% { transform: translate(-50%, -50%) scale(.3); opacity: 0; }
            18% { opacity: 1; }
            100% { transform: translate(calc(-50% + var(--hx)), calc(-50% + var(--hy))) scale(1.05); opacity: 0; }
        }

        @media (prefers-reduced-motion: reduce) {

            .locket.idle, .tap-label { animation: none; }

            .half, .inside, .disc, .ask, .closing { transition-duration: .01ms !important; }

            .hburst { display: none; }
        }
    </style>
</head>

<body>
    <div class="stage" id="stage">
        <p class="kicker">{{ $heading }}</p>
        <h1 class="to-name">{{ $toName }}</h1>

        <div class="locket idle" id="locket" role="button" tabindex="0" aria-label="{{ $tapLabel }}">
            <div class="inside">
                <div class="disc photo-disc">
                    @if ($couple)
                        <img src="{{ $couple }}" alt="Us">
                    @else
                        <svg viewBox="0 0 100 100" role="img" aria-label="The two of us" focusable="false">
                            <rect width="100" height="100" fill="var(--plate)" />
                            <!-- two heads leaning together over their shoulders -->
                            <g fill="var(--heart2)">
                                <circle cx="35" cy="41" r="13" />
                                <path d="M12 100c0-19 10-30 23-30s23 11 23 30z" />
                            </g>
                            <g fill="var(--heart1)">
                                <circle cx="64" cy="38" r="13" />
                                <path d="M41 100c0-19 10-30 23-30s23 11 23 30z" />
                            </g>
                            <path d="M50 22c2.6-5 10-4.4 10 1.6 0 4.6-6 8.4-10 11.4-4-3-10-6.8-10-11.4 0-6 7.4-6.6 10-1.6z"
                                fill="var(--metal1)" opacity=".9" />
                        </svg>
                    @endif
                </div>
                <div class="disc ring-disc">
                    @if ($ring)
                        <img src="{{ $ring }}" alt="The ring">
                    @else
                        @include('birthday.partials._proposal_ring')
                    @endif
                </div>
            </div>

            @foreach (['l', 'r'] as $side)
                <div class="half {{ $side }}" aria-hidden="true">
                    <svg viewBox="0 0 120 110" focusable="false">
                        <defs>
                            <linearGradient id="lk{{ $side }}{{ $proposalTheme }}" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0" stop-color="var(--metal1)" />
                                <stop offset=".45" stop-color="var(--heart1)" />
                                <stop offset="1" stop-color="var(--heart2)" />
                            </linearGradient>
                        </defs>
                        <path
                            d="M60,102 C60,102 6,66 6,36 C6,18 20,8 34,8 C46,8 56,16 60,26 C64,16 74,8 86,8 C100,8 114,18 114,36 C114,66 60,102 60,102 Z"
                            fill="url(#lk{{ $side }}{{ $proposalTheme }})" stroke="var(--metal2)" stroke-width="2" />
                        <path
                            d="M60,102 C60,102 6,66 6,36 C6,18 20,8 34,8 C46,8 56,16 60,26 C64,16 74,8 86,8 C100,8 114,18 114,36 C114,66 60,102 60,102 Z"
                            fill="none" stroke="rgba(255,255,255,.5)" stroke-width="1.4"
                            transform="translate(60 55) scale(.86) translate(-60 -55)" />
                    </svg>
                </div>
            @endforeach

            <div class="clasp" aria-hidden="true"></div>
        </div>

        <p class="tap-label" id="tapLabel">{{ $tapLabel }}</p>

        <div class="ask" id="askBlock">
            <p class="question" data-tease-question>{{ $question }}</p>
            <div data-tease-row>
                <button type="button" data-tease-yes>{{ $yesLabel }}</button>
                <button type="button" data-tease-no>{{ $noLabel }}</button>
            </div>
            <p data-tease-stage></p>
        </div>

        <div class="closing" aria-live="polite">
            <h2>{{ $yesHead }}</h2>
            <p>{{ $closing }}</p>
            <p class="sign">{{ $signed }} · {{ $toName }} &amp; {{ $fromName }}</p>
        </div>
    </div>

    @include('birthday.partials._proposal_tease')

    <script>
        (function () {
            var stage = document.getElementById('stage');
            var locket = document.getElementById('locket');
            var tap = document.getElementById('tapLabel');
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var opened = false;

            function open() {
                if (opened) return;
                opened = true;
                locket.classList.remove('idle');
                locket.classList.add('open');
                locket.setAttribute('aria-expanded', 'true');
                if (tap) tap.style.display = 'none';
                setTimeout(function () { stage.classList.add('asked'); }, reduced ? 0 : 900);
            }

            locket.addEventListener('click', open);
            locket.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
            });

            /** Hearts out of the join where the two discs meet. */
            function hearts() {
                if (reduced) return;
                var box = locket.getBoundingClientRect();
                var cx = box.left + box.width / 2;
                var cy = box.top + box.height / 2;
                for (var i = 0; i < 22; i++) {
                    (function (i) {
                        setTimeout(function () {
                            var el = document.createElement('span');
                            el.className = 'hburst';
                            el.textContent = i % 4 === 0 ? '💗' : (i % 3 === 0 ? '💍' : '❤️');
                            el.style.left = cx + 'px';
                            el.style.top = cy + 'px';
                            var a = Math.random() * Math.PI * 2;
                            var d = 70 + Math.random() * 130;
                            el.style.setProperty('--hx', Math.cos(a) * d + 'px');
                            el.style.setProperty('--hy', (Math.sin(a) * d - 40) + 'px');
                            document.body.appendChild(el);
                            setTimeout(function () { el.remove(); }, 1550);
                        }, i * 55);
                    })(i);
                }
            }

            function celebrate() {
                locket.classList.add('joined');
                stage.classList.add('said');
                setTimeout(hearts, reduced ? 0 : 320);
            }

            var tease = window.initTeaseButtons({
                root: document.getElementById('askBlock'),
                onYes: celebrate,
            });

            /** Close the locket again, for the looping demo. */
            function reset() {
                opened = false;
                locket.classList.remove('open', 'joined');
                locket.classList.add('idle');
                locket.removeAttribute('aria-expanded');
                stage.classList.remove('asked', 'said');
                if (tap) tap.style.display = '';
                if (tease) tease.reset();
            }

            window.__proposalDemo = {
                phases: [0, 450, 900],
                open: open,
                yes: celebrate,
                reset: reset,
            };

            var pre = @json($stage);
            if (pre === 'open' || pre === 'yes') {
                locket.classList.remove('idle');
                locket.classList.add('open');
                stage.classList.add('asked');
                if (tap) tap.style.display = 'none';
                opened = true;
                if (pre === 'yes') celebrate();
            }
        })();
    </script>

    @include('birthday.partials._proposal_demo')
</body>

</html>
