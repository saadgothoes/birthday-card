{{--
    Proposal · Design 1 — "Box & Ring Reveal".

    One shared design, re-skinned four ways. The wrapper views
    (proposal-design-1-theme-{1..4}.blade.php) each @include this with a
    `proposalTheme` (1-4) that picks the palette below.

    The whole page is one orchestrated sequence, and nothing in it starts on
    its own — every step answers the tap before it:

        closed box  →  ribbon unties and slides off (400ms)
                    →  lid lifts on its hinge (600ms)
                    →  the folded letter rises and unfolds (500ms)
                    →  the letter's lines fade in one after another
                    →  the ring fades up at the letter's base, glowing
                    →  the question and the Yes / No buttons arrive
        Yes         →  confetti + rose petals, the ring flies out to hero size,
                       "She Said YES!" and the closing line

    The Yes / No pair is the shared module in
    resources/views/birthday/partials/_proposal_tease.blade.php — every proposal
    design uses the same one rather than reimplementing the joke.

    Renders complete with no query parameters at all.

    Request params:
      to_name, from_name     who it is for / who it is from
      heading                small kicker over the box ("For you")
      tap_label              the hint under the box ("Tap to open")
      letter_text            the letter — one line per newline
      ring_photo             a photo of the ring (falls back to a drawn ring)
      question               "Will you marry me?"
      yes_label, no_label    the two buttons
      yes_heading            the celebration heading ("She Said YES! 💍")
      closing_line, signed   the line under the celebration + who signed it
      theme                  overrides `proposalTheme` when included without one
      preview_stage          open | yes — skip ahead (dashboard preview / wiring)
--}}
@php
    $proposalTheme = (int) ($proposalTheme ?? request('theme', 1));

    // Four palettes: 1 & 2 soft (rose gold, blush), 3 & 4 deep (midnight,
    // emerald). `ink` is read on `paper`, `glow` is the light behind the ring.
    $themes = [
        1 => ['name' => 'Rose Gold & Cream',
              'bg1' => '#f7ece2', 'bg2' => '#e8c9b0', 'spot' => 'rgba(255,255,255,.85)',
              'ink' => '#4a2f2a', 'soft' => '#8a6a5c', 'accent' => '#a35a56',
              'metal1' => '#e6c19c', 'metal2' => '#c98f6d', 'paper' => '#fffaf4',
              'box1' => '#b8746c', 'box2' => '#8f4f4b', 'lid1' => '#c98079', 'lid2' => '#9c5852',
              'ribbon1' => '#f3dcc4', 'ribbon2' => '#dcb08a'],
        2 => ['name' => 'Blush Pearl',
              'bg1' => '#fdf2f5', 'bg2' => '#f3d6e0', 'spot' => 'rgba(255,255,255,.9)',
              'ink' => '#4b2a37', 'soft' => '#8d6273', 'accent' => '#c2607f',
              'metal1' => '#f0cdd9', 'metal2' => '#d9a5b8', 'paper' => '#fffafc',
              'box1' => '#d98aa4', 'box2' => '#b45f7d', 'lid1' => '#e69cb3', 'lid2' => '#c06d8a',
              'ribbon1' => '#fbe6ee', 'ribbon2' => '#e9c0d1'],
        3 => ['name' => 'Midnight Velvet',
              'bg1' => '#2f3a63', 'bg2' => '#161b31', 'spot' => 'rgba(196,214,255,.30)',
              'ink' => '#f4f1e8', 'soft' => '#bcc4de', 'accent' => '#d9b26a',
              'metal1' => '#f0d9a4', 'metal2' => '#c19a55', 'paper' => '#fbf6ea',
              'box1' => '#2c3660', 'box2' => '#1a2143', 'lid1' => '#374372', 'lid2' => '#222a52',
              'ribbon1' => '#e7c987', 'ribbon2' => '#b9944e'],
        4 => ['name' => 'Emerald & Gold',
              'bg1' => '#1e5c4d', 'bg2' => '#0e332c', 'spot' => 'rgba(214,255,238,.28)',
              'ink' => '#f3f6f1', 'soft' => '#b6cfc4', 'accent' => '#e2b866',
              'metal1' => '#f2dda6', 'metal2' => '#c69f57', 'paper' => '#fbf8ec',
              'box1' => '#17493d', 'box2' => '#0d322a', 'lid1' => '#1d5a4a', 'lid2' => '#113b31',
              'ribbon1' => '#eed7a0', 'ribbon2' => '#bd9a55'],
    ];
    $t = $themes[$proposalTheme] ?? $themes[1];

    // The sample wording lives once, in the controller's design registry: the
    // page falls back to it and the wizard pre-fills its boxes from it, so a
    // preview with nothing typed shows exactly what an untouched card would.
    $d = \App\Http\Controllers\Client\BirthdayCardController::proposalDefaults(1);

    $toName    = request('to_name', $d['to_name']);
    $fromName  = request('from_name', $d['from_name']);
    $heading   = request('heading', $d['heading']);
    $tapLabel  = request('tap_label', $d['tap_label']);
    $question  = request('question', $d['question']);
    $yesLabel  = request('yes_label', $d['yes_label']);
    $noLabel   = request('no_label', $d['no_label']);
    $yesHead   = request('yes_heading', $d['yes_heading']);
    $closing   = request('closing_line', $d['closing_line']);
    $signed    = request('signed', $d['signed']);
    $ringPhoto = request('ring_photo');
    $letter    = request('letter_text', $d['letter_text']);
    $letterLines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $letter)), fn ($l) => $l !== ''));
    $stage = request('preview_stage');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $question }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        /* Two typefaces, two roles: the serif carries the question and the
           letter, the sans carries every piece of chrome. Nothing else. */
        :root {
            --bg1: {{ $t['bg1'] }};
            --bg2: {{ $t['bg2'] }};
            --spot: {{ $t['spot'] }};
            --ink: {{ $t['ink'] }};
            --soft: {{ $t['soft'] }};
            --accent: {{ $t['accent'] }};
            --metal1: {{ $t['metal1'] }};
            --metal2: {{ $t['metal2'] }};
            --paper: {{ $t['paper'] }};
            --box1: {{ $t['box1'] }};
            --box2: {{ $t['box2'] }};
            --lid1: {{ $t['lid1'] }};
            --lid2: {{ $t['lid2'] }};
            --ribbon1: {{ $t['ribbon1'] }};
            --ribbon2: {{ $t['ribbon2'] }};
            --serif: 'Cormorant Garamond', Georgia, 'Times New Roman', serif;
            --sans: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;

            --pt-yes-bg: {{ $t['accent'] }};
            --pt-yes-ink: #fff;
            --pt-no-bg: rgba(255, 255, 255, .8);
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
                radial-gradient(ellipse 70% 55% at 50% 38%, var(--spot), transparent 70%),
                linear-gradient(165deg, var(--bg1), var(--bg2));
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

        .stage {
            width: 100%;
            max-width: 560px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .kicker {
            font-size: clamp(.66rem, 2.9vw, .74rem);
            letter-spacing: .34em;
            text-transform: uppercase;
            color: var(--soft);
            margin: 0 0 .35rem;
        }

        .to-name {
            font-family: var(--serif);
            font-size: clamp(1.7rem, 8vw, 2.5rem);
            font-weight: 600;
            margin: 0 0 clamp(1rem, 4vw, 1.6rem);
            line-height: 1.1;
        }

        /* ── the box ───────────────────────────────────────────── */
        .box-wrap {
            position: relative;
            width: clamp(180px, 56vw, 250px);
            height: clamp(150px, 46vw, 205px);
            margin: 0 auto;
            cursor: pointer;
            transition: transform .5s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .box-wrap:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 10px;
            border-radius: 14px;
        }

        .box-wrap.idle { animation: breathe 3.4s ease-in-out infinite; }

        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.028); }
        }

        .box-base {
            position: absolute;
            left: 6%;
            bottom: 0;
            width: 88%;
            height: 60%;
            border-radius: 6px 6px 10px 10px;
            background: linear-gradient(170deg, var(--box1), var(--box2));
            box-shadow: 0 22px 34px -20px rgba(0, 0, 0, .65), inset 0 -12px 20px -14px rgba(0, 0, 0, .6);
        }

        .box-base::after {
            /* the ribbon running down the front of the box */
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 15%;
            transform: translateX(-50%);
            background: linear-gradient(180deg, var(--ribbon1), var(--ribbon2));
            opacity: .95;
        }

        .box-lid {
            position: absolute;
            left: 0;
            top: 22%;
            width: 100%;
            height: 26%;
            border-radius: 8px;
            background: linear-gradient(170deg, var(--lid1), var(--lid2));
            box-shadow: 0 14px 22px -14px rgba(0, 0, 0, .7);
            transform-origin: 6% 100%;
            transition: transform .6s cubic-bezier(.32, .9, .35, 1.15), opacity .5s ease .55s;
            z-index: 4;
        }

        .box-lid::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 15%;
            transform: translateX(-50%);
            background: linear-gradient(180deg, var(--ribbon1), var(--ribbon2));
        }

        .bow {
            position: absolute;
            left: 50%;
            top: 8%;
            width: 44%;
            height: 22%;
            transform: translateX(-50%);
            z-index: 5;
            transition: transform .42s cubic-bezier(.4, 0, .8, .6), opacity .42s ease;
        }

        .bow i {
            position: absolute;
            top: 0;
            width: 46%;
            height: 100%;
            background: linear-gradient(150deg, var(--ribbon1), var(--ribbon2));
            border-radius: 60% 40% 55% 45%;
            box-shadow: inset 0 -4px 8px -5px rgba(0, 0, 0, .5);
        }

        .bow i:first-child { left: 0; transform: rotate(-16deg); }
        .bow i:last-child { right: 0; transform: rotate(16deg) scaleX(-1); }

        .bow b {
            position: absolute;
            left: 50%;
            top: 34%;
            width: 20%;
            height: 34%;
            transform: translateX(-50%);
            border-radius: 50%;
            background: linear-gradient(160deg, var(--ribbon1), var(--ribbon2));
        }

        /* opened state */
        .box-wrap.open { animation: none; }
        .box-wrap.open .bow { transform: translate(70%, -190%) rotate(38deg); opacity: 0; }
        .box-wrap.open .box-lid { transform: rotate(-118deg) translateY(-8%); opacity: 0; }

        /* The letter and the ring are hidden with `hidden` rather than with
           opacity alone: an invisible letter still reserves its full height,
           which left a hand's width of empty page under the closed box. The
           attribute comes off one frame before the transitions start. */
        .reveal-area[hidden] { display: none; }

        /* ── the letter ────────────────────────────────────────── */
        .letter {
            position: relative;
            margin: clamp(-2.4rem, -8vw, -1.8rem) auto 0;
            width: min(100%, 430px);
            background: var(--paper);
            border-radius: 10px;
            padding: clamp(1.2rem, 5vw, 1.9rem) clamp(1rem, 5vw, 1.8rem) clamp(1rem, 4vw, 1.5rem);
            box-shadow: 0 26px 48px -26px rgba(0, 0, 0, .6);
            transform-origin: top center;
            transform: translateY(28px) scaleY(.3);
            opacity: 0;
            pointer-events: none;
            transition: transform .5s cubic-bezier(.25, .9, .3, 1.1), opacity .28s ease;
            z-index: 3;
        }

        .letter::before {
            /* the fold the paper was kept in */
            content: '';
            position: absolute;
            left: 8%;
            right: 8%;
            top: 50%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, .09), transparent);
        }

        .stage.opened .letter {
            transform: translateY(0) scaleY(1);
            opacity: 1;
            pointer-events: auto;
        }

        .letter p {
            font-family: var(--serif);
            font-size: clamp(1.02rem, 4.2vw, 1.22rem);
            line-height: 1.65;
            margin: 0 0 .5em;
            color: #2f2622;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity .5s ease, transform .5s ease;
        }

        .letter p.in { opacity: 1; transform: none; }

        .letter .sign {
            font-family: var(--serif);
            font-style: italic;
            font-size: clamp(.9rem, 3.6vw, 1rem);
            text-align: right;
            color: var(--metal2);
            margin: .8em 0 0;
        }

        /* ── the ring ──────────────────────────────────────────── */
        .ring {
            position: relative;
            width: clamp(96px, 30vw, 128px);
            height: clamp(96px, 30vw, 128px);
            margin: clamp(-1.5rem, -5vw, -1.1rem) auto 0;
            border-radius: 50%;
            display: grid;
            place-items: center;
            opacity: 0;
            transform: scale(.7);
            transition: opacity .6s ease, transform .6s cubic-bezier(.25, .9, .3, 1.3);
            z-index: 4;
        }

        .stage.opened .ring { opacity: 1; transform: scale(1); }

        .ring::before {
            content: '';
            position: absolute;
            inset: -22%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 240, 205, .85), transparent 68%);
            animation: glowPulse 2.6s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0%, 100% { opacity: .45; transform: scale(.94); }
            50% { opacity: .9; transform: scale(1.06); }
        }

        .ring img,
        .ring svg {
            position: relative;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .ring img {
            border: 3px solid var(--metal1);
            box-shadow: 0 12px 28px -14px rgba(0, 0, 0, .7);
        }

        /* ── question + chrome ─────────────────────────────────── */
        .tap-label {
            margin: clamp(1.1rem, 4vw, 1.5rem) 0 0;
            font-size: clamp(.76rem, 3.2vw, .86rem);
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--soft);
            animation: nudge 2.2s ease-in-out infinite;
        }

        @keyframes nudge {
            0%, 100% { opacity: .6; transform: translateY(0); }
            50% { opacity: 1; transform: translateY(3px); }
        }

        .ask {
            margin-top: clamp(1.1rem, 4vw, 1.6rem);
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: opacity .6s ease .15s, transform .6s ease .15s;
        }

        .stage.asked .ask { opacity: 1; transform: none; pointer-events: auto; }
        .stage.opened .tap-label { display: none; }

        .question {
            font-family: var(--serif);
            font-size: clamp(1.5rem, 7vw, 2.1rem);
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
        }

        /* ── celebration ───────────────────────────────────────── */
        .party {
            position: fixed;
            inset: 0;
            z-index: 40;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            background:
                radial-gradient(ellipse 65% 55% at 50% 45%, var(--spot), transparent 70%),
                linear-gradient(165deg, var(--bg1), var(--bg2));
            opacity: 0;
            visibility: hidden;
            transition: opacity .7s ease, visibility .7s;
        }

        .party.on { opacity: 1; visibility: visible; }

        .party-inner { text-align: center; max-width: 460px; }

        .party .ring-hero {
            width: clamp(130px, 40vw, 180px);
            height: clamp(130px, 40vw, 180px);
            margin: 0 auto clamp(1rem, 4vw, 1.5rem);
            position: relative;
            display: grid;
            place-items: center;
            transform: scale(.5);
            opacity: 0;
            transition: transform .9s cubic-bezier(.2, .9, .3, 1.4) .15s, opacity .7s ease .15s;
        }

        .party.on .ring-hero { transform: scale(1); opacity: 1; }

        .party .ring-hero::before {
            content: '';
            position: absolute;
            inset: -18%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 240, 205, .9), transparent 68%);
            animation: glowPulse 2.6s ease-in-out infinite;
        }

        .party .ring-hero img,
        .party .ring-hero svg {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .party .ring-hero img { border: 4px solid var(--metal1); }

        .party h2 {
            font-family: var(--serif);
            font-size: clamp(1.8rem, 8.5vw, 2.7rem);
            font-weight: 600;
            margin: 0 0 .5rem;
            line-height: 1.12;
        }

        .party .closing {
            font-family: var(--serif);
            font-size: clamp(1rem, 4.4vw, 1.2rem);
            line-height: 1.6;
            color: var(--ink);
            opacity: .9;
            margin: 0 0 .9rem;
        }

        .party .sign {
            font-size: clamp(.78rem, 3.2vw, .88rem);
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--soft);
            margin: 0;
        }

        /* confetti + petals */
        .fall {
            position: fixed;
            top: -8vh;
            z-index: 45;
            pointer-events: none;
            will-change: transform;
        }

        .fall.confetti {
            width: 8px;
            height: 13px;
            border-radius: 2px;
        }

        .fall.petal {
            width: 15px;
            height: 12px;
            border-radius: 60% 40% 55% 45%;
        }

        @keyframes drop {
            0% { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
            8% { opacity: 1; }
            100% { transform: translate3d(var(--dx, 20px), 112vh, 0) rotate(var(--spin, 540deg)); opacity: .9; }
        }

        @media (prefers-reduced-motion: reduce) {

            .box-wrap.idle,
            .tap-label,
            .ring::before,
            .party .ring-hero::before { animation: none; }

            .box-lid, .bow, .letter, .ring, .ask, .party, .party .ring-hero {
                transition-duration: .01ms !important;
            }

            .fall { display: none; }
        }
    </style>
</head>

<body>
    <div class="stage" id="stage">
        <p class="kicker">{{ $heading }}</p>
        <h1 class="to-name">{{ $toName }}</h1>

        <div class="box-wrap idle" id="box" role="button" tabindex="0"
            aria-label="{{ $tapLabel }}">
            <div class="box-base"></div>
            <div class="box-lid"></div>
            <div class="bow"><i></i><i></i><b></b></div>
        </div>

        <div class="reveal-area" id="revealArea" hidden>
        <div class="letter" id="letter">
            @foreach ($letterLines as $line)
                <p>{{ $line }}</p>
            @endforeach
            <p class="sign">{{ $signed }} {{ $fromName }}</p>
        </div>

        <div class="ring" id="ring">
            @if ($ringPhoto)
                <img src="{{ $ringPhoto }}" alt="The ring">
            @else
                @include('birthday.partials._proposal_ring')
            @endif
        </div>

        </div><!-- /.reveal-area -->

        <p class="tap-label" id="tapLabel">{{ $tapLabel }}</p>

        <div class="ask" id="askBlock">
            <p class="question" data-tease-question>{{ $question }}</p>
            <div data-tease-row>
                <button type="button" data-tease-yes>{{ $yesLabel }}</button>
                <button type="button" data-tease-no>{{ $noLabel }}</button>
            </div>
            <p data-tease-stage></p>
        </div>
    </div>

    <div class="party" id="party" role="dialog" aria-live="polite" aria-hidden="true">
        <div class="party-inner">
            <div class="ring-hero">
                @if ($ringPhoto)
                    <img src="{{ $ringPhoto }}" alt="The ring">
                @else
                    @include('birthday.partials._proposal_ring')
                @endif
            </div>
            <h2>{{ $yesHead }}</h2>
            <p class="closing">{{ $closing }}</p>
            <p class="sign">{{ $toName }} &amp; {{ $fromName }}</p>
        </div>
    </div>

    @include('birthday.partials._proposal_tease')

    <script>
        (function () {
            var stage = document.getElementById('stage');
            var box = document.getElementById('box');
            var letter = document.getElementById('letter');
            var reveal = document.getElementById('revealArea');
            var party = document.getElementById('party');
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var opened = false;

            var CONFETTI = ['{{ $t['accent'] }}', '{{ $t['metal1'] }}', '{{ $t['metal2'] }}', '#ffffff'];
            var PETALS = ['{{ $t['ribbon1'] }}', '{{ $t['ribbon2'] }}', '{{ $t['accent'] }}'];

            /** The one sequence: ribbon → lid → letter → lines → ring → question. */
            function open() {
                if (opened) return;
                opened = true;
                box.classList.remove('idle');
                box.classList.add('open');
                box.setAttribute('aria-expanded', 'true');

                // the lid has to be off before the letter can come out of it
                setTimeout(function () {
                    reveal.hidden = false;
                    // one frame with the letter in the flow but still folded,
                    // so `.opened` is a change the browser can animate
                    requestAnimationFrame(function () {
                        requestAnimationFrame(unfold);
                    });
                }, reduced ? 0 : 720);
            }

            /** The letter rises, reads itself out a line at a time, then asks. */
            function unfold() {
                stage.classList.add('opened');
                var lines = letter.querySelectorAll('p');
                lines.forEach(function (p, i) {
                    setTimeout(function () { p.classList.add('in'); },
                        reduced ? 0 : 260 + i * 320);
                });
                setTimeout(function () { stage.classList.add('asked'); },
                    reduced ? 0 : 320 + lines.length * 320);
            }

            box.addEventListener('click', open);
            box.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
            });

            function shower() {
                if (reduced) return;
                var total = 90;
                for (var i = 0; i < total; i++) {
                    (function (i) {
                        setTimeout(function () {
                            var el = document.createElement('i');
                            var petal = i % 3 === 0;
                            el.className = 'fall ' + (petal ? 'petal' : 'confetti');
                            el.style.left = Math.random() * 100 + 'vw';
                            el.style.background = (petal ? PETALS : CONFETTI)[
                                Math.floor(Math.random() * (petal ? PETALS : CONFETTI).length)];
                            el.style.setProperty('--dx', (Math.random() * 160 - 80) + 'px');
                            el.style.setProperty('--spin', (Math.random() * 900 - 300) + 'deg');
                            var dur = 3.2 + Math.random() * 2.2;
                            el.style.animation = 'drop ' + dur + 's linear forwards';
                            document.body.appendChild(el);
                            setTimeout(function () { el.remove(); }, dur * 1000 + 120);
                        }, i * 42);
                    })(i);
                }
            }

            function celebrate() {
                party.classList.add('on');
                party.setAttribute('aria-hidden', 'false');
                shower();
            }

            var tease = window.initTeaseButtons({
                root: document.getElementById('askBlock'),
                onYes: celebrate,
            });

            /** Put the page back to a closed box, for the looping demo. */
            function reset() {
                opened = false;
                box.classList.remove('open');
                box.classList.add('idle');
                box.removeAttribute('aria-expanded');
                reveal.hidden = true;
                stage.classList.remove('opened', 'asked');
                letter.querySelectorAll('p').forEach(function (p) { p.classList.remove('in'); });
                party.classList.remove('on');
                party.setAttribute('aria-hidden', 'true');
                if (tease) tease.reset();
            }

            // The demo below drives these three; it is inert without ?demo=1.
            window.__proposalDemo = {
                phases: [0, 760, 2400],
                open: open,
                yes: celebrate,
                reset: reset,
            };

            // Deep links, for the dashboard's live preview and for wiring:
            // `open` shows the letter, ring and question; `yes` goes all the way.
            var pre = @json($stage);
            if (pre === 'open' || pre === 'yes') {
                box.classList.remove('idle');
                box.classList.add('open');
                reveal.hidden = false;
                stage.classList.add('opened', 'asked');
                letter.querySelectorAll('p').forEach(function (p) { p.classList.add('in'); });
                opened = true;
                if (pre === 'yes') celebrate();
            }
        })();
    </script>

    @include('birthday.partials._proposal_demo')
</body>

</html>
