{{--
    Proposal · Design 4 — "Balloon Pop".

    One shared design, re-skinned four ways; the wrapper views
    (proposal-design-4-theme-{1..4}.blade.php) @include this with a
    `proposalTheme` (1-4).

    The light one, and the only one of the four that is not trying to be
    solemn. It commits to that all the way through rather than half-way: a
    single rounded sans-serif and nothing else (an "elegant" script on a bouncy
    bouquet would undercut the whole tone), balloon colours drawn from one
    curated set per theme rather than picked at random, and a celebration made
    of more balloons rather than of velvet.

        a tied bouquet of six balloons, swaying  →  tap
        →  they pop one after another, 80ms apart, each with a flash and a
           little confetti spray
        →  the ring drops into the middle and bounces to a stop
        →  the question fades up with Yes / No
        Yes  →  a second, much bigger wave of balloons floats up the whole
                screen and the closing message fades in among them

    Renders complete with no query parameters.

    Request params:
      to_name, from_name     who it is for / from
      heading                small kicker over the bouquet
      tap_label              "Tap the balloons"
      ring_photo             the ring that drops in (drawn ring fallback)
      question               "Will you marry me?"
      yes_label, no_label    the two buttons
      yes_heading            the celebration heading
      closing_line, signed   the closing line + signature
      theme                  overrides `proposalTheme`
      preview_stage          open | yes — skip ahead (dashboard preview)
--}}
@php
    $proposalTheme = (int) ($proposalTheme ?? request('theme', 1));

    // Balloon colours are a curated set per theme, never random — six hues that
    // were chosen together read as a bouquet; six random ones read as a clash.
    $themes = [
        1 => ['name' => 'Pastel Sky',
              'bg1' => '#cfe8f0', 'bg2' => '#f6d9e3',
              'ink' => '#3d4a55', 'soft' => '#6d7f8c', 'accent' => '#e2698c',
              'metal1' => '#ffd9a8', 'metal2' => '#e0a469', 'card' => '#ffffff',
              'balloons' => ['#8ecae6', '#ffb3c6', '#ffd6a5', '#a9def9', '#caffbf', '#f6a5c0']],
        2 => ['name' => 'Candy Blush',
              'bg1' => '#ffeef4', 'bg2' => '#ffd9c7',
              'ink' => '#57323c', 'soft' => '#8d6470', 'accent' => '#ef6f8e',
              'metal1' => '#ffdcc4', 'metal2' => '#dd9a76', 'card' => '#fffafb',
              'balloons' => ['#ff8fab', '#ffc2d1', '#ffd9a0', '#ffb5a7', '#fcd5ce', '#f79ab0']],
        3 => ['name' => 'Mint & Sunshine',
              'bg1' => '#d8f3e6', 'bg2' => '#fdf3cf',
              'ink' => '#28453b', 'soft' => '#5c7d70', 'accent' => '#2f9e7a',
              'metal1' => '#ffe6a3', 'metal2' => '#d3ab4f', 'card' => '#fbfffc',
              'balloons' => ['#7fd6ae', '#ffe17b', '#a8e6cf', '#ffd3a5', '#bde0fe', '#8fd694']],
        4 => ['name' => 'Bold Pop',
              'bg1' => '#dbe7ff', 'bg2' => '#ffe2e2',
              'ink' => '#22305c', 'soft' => '#5b6a94', 'accent' => '#2f5fe0',
              'metal1' => '#ffd88a', 'metal2' => '#d79b3a', 'card' => '#ffffff',
              'balloons' => ['#2f5fe0', '#ff5d73', '#ffc233', '#5ec2b7', '#a06bdb', '#ff8f4d']],
    ];
    $t = $themes[$proposalTheme] ?? $themes[1];

    // One copy of the sample wording, in the controller's design registry.
    $d = \App\Http\Controllers\Client\BirthdayCardController::proposalDefaults(4);

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
    $stage     = request('preview_stage');

    // Where each balloon sits in the bouquet: left %, top %, width %, tilt.
    $bouquet = [
        ['x' => 50, 'y' => 2,  's' => 30, 'r' => 0],
        ['x' => 22, 'y' => 12, 's' => 27, 'r' => -12],
        ['x' => 78, 'y' => 12, 's' => 27, 'r' => 12],
        ['x' => 33, 'y' => 34, 's' => 25, 'r' => -7],
        ['x' => 67, 'y' => 34, 's' => 25, 'r' => 7],
        ['x' => 50, 'y' => 26, 's' => 24, 'r' => 0],
    ];

    // Every string is tied to the same knot, so each one's length and angle is
    // worked out from where its own balloon hangs rather than guessed. The
    // maths runs against the bouquet's nominal box (the percentages scale with
    // it), and the knot sits at 50% across, 90% down.
    $boxW = 290;
    $boxH = 310;
    $knotX = $boxW * 0.5;
    $knotY = $boxH * 0.90;
    foreach ($bouquet as $i => $b) {
        $w = $b['s'] / 100 * $boxW;
        $cx = $b['x'] / 100 * $boxW;
        $bottom = $b['y'] / 100 * $boxH + $w * 6 / 5;
        $dx = $knotX - $cx;
        $dy = max(12, $knotY - $bottom);
        // stop a few pixels short so the strings tuck under the bow
        $bouquet[$i]['len'] = max(10, round(sqrt($dx * $dx + $dy * $dy)) - 6);
        // CSS rotate is clockwise and the string hangs from its top, so a
        // positive angle swings the free end to the left: hence the minus.
        $bouquet[$i]['tilt'] = round(-rad2deg(atan2($dx, $dy)), 1);
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
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* One typeface, front to back. The tone is the point. */
        :root {
            --bg1: {{ $t['bg1'] }};
            --bg2: {{ $t['bg2'] }};
            --ink: {{ $t['ink'] }};
            --soft: {{ $t['soft'] }};
            --accent: {{ $t['accent'] }};
            --metal1: {{ $t['metal1'] }};
            --metal2: {{ $t['metal2'] }};
            --card: {{ $t['card'] }};
            --round: 'Quicksand', system-ui, -apple-system, 'Segoe UI', sans-serif;

            --pt-yes-bg: {{ $t['accent'] }};
            --pt-yes-ink: #ffffff;
            --pt-no-bg: #ffffff;
            --pt-no-ink: {{ $t['ink'] }};
            --pt-ink: {{ $t['soft'] }};
            --pt-ring: {{ $t['accent'] }};
        }

        * { box-sizing: border-box; }

        html, body { height: 100%; }

        body {
            margin: 0;
            font-family: var(--round);
            font-weight: 500;
            color: var(--ink);
            background: linear-gradient(165deg, var(--bg1), var(--bg2));
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

        .stage { width: 100%; max-width: 540px; text-align: center; position: relative; z-index: 2; }

        .kicker {
            font-size: clamp(.7rem, 3vw, .8rem);
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--soft);
            margin: 0 0 .35rem;
            font-weight: 600;
        }

        .to-name {
            font-size: clamp(1.7rem, 8vw, 2.4rem);
            font-weight: 700;
            margin: 0 0 clamp(.8rem, 3vw, 1.2rem);
        }

        /* ── the bouquet ───────────────────────────────────────── */
        .bouquet {
            position: relative;
            width: clamp(230px, 74vw, 330px);
            height: clamp(250px, 80vw, 350px);
            margin: 0 auto;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            /* once the balloons are gone the bouquet's height is just a hole in
               the page, so it closes up around the ring that replaced them */
            transition: height .55s ease .25s;
        }

        .bouquet.done {
            height: clamp(150px, 46vw, 195px);
        }

        .bouquet:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 10px;
            border-radius: 20px;
        }

        .bouquet.sway { animation: sway 4.2s ease-in-out infinite; transform-origin: 50% 100%; }

        @keyframes sway {
            0%, 100% { transform: rotate(-1.6deg); }
            50% { transform: rotate(1.6deg); }
        }

        .balloon {
            position: absolute;
            width: 30%;
            aspect-ratio: 5 / 6;
            transform: translate(-50%, 0) rotate(var(--rot, 0deg));
            transition: transform .22s ease, opacity .22s ease;
        }

        .balloon .body {
            position: absolute;
            inset: 0;
            border-radius: 50% 50% 47% 47% / 42% 42% 58% 58%;
            box-shadow: inset -8px -10px 18px -8px rgba(0, 0, 0, .28),
                        inset 8px 8px 16px -10px rgba(255, 255, 255, .9),
                        0 12px 22px -16px rgba(0, 0, 0, .5);
        }

        .balloon .body::after {
            /* the highlight that makes it read as rubber, not a circle */
            content: '';
            position: absolute;
            left: 22%;
            top: 16%;
            width: 22%;
            height: 26%;
            border-radius: 50%;
            background: rgba(255, 255, 255, .55);
            filter: blur(1px);
        }

        .balloon .knot {
            position: absolute;
            left: 50%;
            bottom: -6px;
            width: 0;
            height: 0;
            transform: translateX(-50%);
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 9px solid currentColor;
        }

        .balloon .string {
            position: absolute;
            left: 50%;
            top: 100%;
            width: 1.5px;
            height: 90px;
            background: linear-gradient(180deg, rgba(0, 0, 0, .28), rgba(0, 0, 0, .06));
            transform-origin: top center;
        }

        .balloon.popped { opacity: 0; transform: translate(-50%, 0) rotate(var(--rot, 0deg)) scale(1.45); }

        /* the bow the six strings are tied into */
        .knot-tie {
            position: absolute;
            left: 50%;
            top: 90%;
            width: 28px;
            height: 14px;
            margin: -6px 0 0 -14px;
            transition: opacity .3s ease;
        }

        .knot-tie::before,
        .knot-tie::after {
            content: '';
            position: absolute;
            top: 1px;
            width: 12px;
            height: 12px;
            background: var(--metal2);
            border-radius: 70% 30% 60% 40%;
            opacity: .8;
        }

        /* the two loops meet in the middle rather than sitting apart */
        .knot-tie::before { left: 2px; transform: rotate(-24deg); }
        .knot-tie::after { right: 2px; transform: rotate(24deg) scaleX(-1); }

        .bouquet.done .knot-tie { opacity: 0; }

        .flash {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, #fff, transparent 70%);
            pointer-events: none;
            animation: flashOut .32s ease-out forwards;
        }

        @keyframes flashOut {
            0% { transform: translate(-50%, -50%) scale(.3); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(2.4); opacity: 0; }
        }

        .bit {
            position: fixed;
            width: 7px;
            height: 7px;
            border-radius: 2px;
            z-index: 40;
            pointer-events: none;
            animation: bitOut .9s cubic-bezier(.15, .75, .35, 1) forwards;
        }

        @keyframes bitOut {
            0% { transform: translate(-50%, -50%) scale(1) rotate(0); opacity: 1; }
            100% { transform: translate(calc(-50% + var(--bx)), calc(-50% + var(--by))) scale(.5) rotate(320deg); opacity: 0; }
        }

        /* ── the ring that drops in ────────────────────────────── */
        .ring {
            position: absolute;
            left: 50%;
            top: 34%;
            width: clamp(96px, 30vw, 124px);
            height: clamp(96px, 30vw, 124px);
            margin-left: calc(clamp(96px, 30vw, 124px) / -2);
            display: grid;
            place-items: center;
            opacity: 0;
            transform: translateY(-160px) scale(.7);
            z-index: 5;
        }

        .ring.drop { animation: dropIn .85s cubic-bezier(.28, 1.3, .4, 1) forwards; }

        @keyframes dropIn {
            0% { opacity: 0; transform: translateY(-170px) scale(.7); }
            55% { opacity: 1; transform: translateY(14px) scale(1.06); }
            75% { transform: translateY(-6px) scale(.99); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .ring img, .ring svg { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }

        .ring img { border: 3px solid var(--metal1); box-shadow: 0 14px 26px -16px rgba(0, 0, 0, .6); }

        /* ── chrome ────────────────────────────────────────────── */
        .tap-label {
            margin: clamp(.6rem, 2.5vw, 1rem) 0 0;
            font-size: clamp(.8rem, 3.4vw, .9rem);
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--soft);
            animation: bob 1.9s ease-in-out infinite;
        }

        @keyframes bob {
            0%, 100% { transform: translateY(0); opacity: .7; }
            50% { transform: translateY(4px); opacity: 1; }
        }

        .ask {
            margin-top: clamp(.8rem, 3vw, 1.2rem);
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: opacity .55s ease, transform .55s ease;
        }

        .stage.asked .ask { opacity: 1; transform: none; pointer-events: auto; }
        .stage.asked .tap-label { display: none; }

        .question {
            font-size: clamp(1.5rem, 7vw, 2.05rem);
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .party {
            position: fixed;
            inset: 0;
            z-index: 45;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity .8s ease, visibility .8s;
            pointer-events: none;
        }

        .party.on { opacity: 1; visibility: visible; }

        .party-inner {
            background: var(--card);
            border-radius: 26px;
            padding: clamp(1.4rem, 6vw, 2.2rem);
            max-width: 420px;
            box-shadow: 0 30px 60px -30px rgba(0, 0, 0, .45);
            text-align: center;
        }

        .party h2 {
            font-size: clamp(1.7rem, 8vw, 2.4rem);
            font-weight: 700;
            margin: 0 0 .5rem;
            color: var(--accent);
            line-height: 1.14;
        }

        .party p.closing {
            font-size: clamp(1rem, 4.2vw, 1.12rem);
            line-height: 1.6;
            margin: 0 0 .8rem;
        }

        .party p.sign {
            font-size: clamp(.74rem, 3vw, .82rem);
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--soft);
            margin: 0;
            font-weight: 600;
        }

        .floater {
            position: fixed;
            bottom: -22vh;
            width: 46px;
            aspect-ratio: 5 / 6;
            border-radius: 50% 50% 47% 47% / 42% 42% 58% 58%;
            z-index: 42;
            pointer-events: none;
            animation: floatUp linear forwards;
        }

        @keyframes floatUp {
            0% { transform: translateY(0) rotate(0); opacity: 0; }
            10% { opacity: .95; }
            100% { transform: translateY(-128vh) rotate(var(--tilt, 12deg)); opacity: .95; }
        }

        @media (prefers-reduced-motion: reduce) {

            .bouquet.sway, .tap-label { animation: none; }

            .ring.drop { animation-duration: .01ms; }

            .balloon, .ask, .party { transition-duration: .01ms !important; }

            .flash, .bit, .floater { display: none; }
        }
    </style>
</head>

<body>
    <div class="stage" id="stage">
        <p class="kicker">{{ $heading }}</p>
        <h1 class="to-name">{{ $toName }}</h1>

        <div class="bouquet sway" id="bouquet" role="button" tabindex="0" aria-label="{{ $tapLabel }}">
            @foreach ($bouquet as $i => $b)
                <div class="balloon" data-balloon
                    style="left:{{ $b['x'] }}%; top:{{ $b['y'] }}%; width:{{ $b['s'] }}%;
                           color:{{ $t['balloons'][$i % count($t['balloons'])] }};
                           --rot:{{ $b['r'] }}deg;">
                    <div class="body" style="background:
                        radial-gradient(circle at 34% 28%, #ffffffcc, transparent 46%),
                        linear-gradient(160deg, {{ $t['balloons'][$i % count($t['balloons'])] }},
                        {{ $t['balloons'][($i + 3) % count($t['balloons'])] }});"></div>
                    <div class="knot"></div>
                    <div class="string" style="height:{{ $b['len'] }}px;
                        transform: rotate({{ $b['tilt'] }}deg);"></div>
                </div>
            @endforeach
            <div class="knot-tie" aria-hidden="true"></div>

            <div class="ring" id="ring">
                @if ($ringPhoto)
                    <img src="{{ $ringPhoto }}" alt="The ring">
                @else
                    @include('birthday.partials._proposal_ring')
                @endif
            </div>
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
    </div>

    <div class="party" id="party" role="dialog" aria-live="polite" aria-hidden="true">
        <div class="party-inner">
            <h2>{{ $yesHead }}</h2>
            <p class="closing">{{ $closing }}</p>
            <p class="sign">{{ $signed }} · {{ $toName }} &amp; {{ $fromName }}</p>
        </div>
    </div>

    @include('birthday.partials._proposal_tease')

    <script>
        (function () {
            var stage = document.getElementById('stage');
            var bouquet = document.getElementById('bouquet');
            var ring = document.getElementById('ring');
            var tap = document.getElementById('tapLabel');
            var party = document.getElementById('party');
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var COLOURS = @json($t['balloons']);
            var balloons = [].slice.call(bouquet.querySelectorAll('[data-balloon]'));
            var popped = false;

            function bits(x, y, colour) {
                if (reduced) return;
                for (var i = 0; i < 10; i++) {
                    var el = document.createElement('i');
                    el.className = 'bit';
                    el.style.left = x + 'px';
                    el.style.top = y + 'px';
                    el.style.background = COLOURS[Math.floor(Math.random() * COLOURS.length)];
                    var a = Math.random() * Math.PI * 2;
                    var d = 40 + Math.random() * 70;
                    el.style.setProperty('--bx', Math.cos(a) * d + 'px');
                    el.style.setProperty('--by', Math.sin(a) * d + 'px');
                    document.body.appendChild(el);
                    (function (el) { setTimeout(function () { el.remove(); }, 950); })(el);
                }
            }

            function pop(el) {
                var box = el.getBoundingClientRect();
                var host = bouquet.getBoundingClientRect();
                if (!reduced) {
                    var f = document.createElement('i');
                    f.className = 'flash';
                    f.style.left = (box.left - host.left + box.width / 2) + 'px';
                    f.style.top = (box.top - host.top + box.height / 2) + 'px';
                    f.style.width = f.style.height = box.width + 'px';
                    bouquet.appendChild(f);
                    setTimeout(function () { f.remove(); }, 340);
                }
                bits(box.left + box.width / 2, box.top + box.height / 2);
                el.classList.add('popped');
            }

            function run() {
                if (popped) return;
                popped = true;
                bouquet.classList.remove('sway');
                bouquet.setAttribute('aria-expanded', 'true');
                if (tap) tap.style.display = 'none';

                balloons.forEach(function (b, i) {
                    setTimeout(function () { pop(b); }, reduced ? 0 : i * 80);
                });

                var after = reduced ? 0 : balloons.length * 80 + 160;
                setTimeout(function () {
                    bouquet.classList.add('done');
                    ring.classList.add('drop');
                    setTimeout(function () { stage.classList.add('asked'); }, reduced ? 0 : 620);
                }, after);
            }

            bouquet.addEventListener('click', run);
            bouquet.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); run(); }
            });

            /** The second, much bigger wave — the whole screen this time. */
            function wave() {
                if (reduced) return;
                for (var i = 0; i < 34; i++) {
                    (function (i) {
                        setTimeout(function () {
                            var el = document.createElement('i');
                            el.className = 'floater';
                            var c = COLOURS[i % COLOURS.length];
                            el.style.left = Math.random() * 96 + 'vw';
                            el.style.background = 'radial-gradient(circle at 34% 28%, #ffffffcc, transparent 46%),' +
                                'linear-gradient(160deg,' + c + ',' + COLOURS[(i + 3) % COLOURS.length] + ')';
                            var scale = .6 + Math.random() * 1.1;
                            el.style.width = (34 * scale) + 'px';
                            el.style.setProperty('--tilt', (Math.random() * 40 - 20) + 'deg');
                            el.style.animationDuration = (5.5 + Math.random() * 4) + 's';
                            document.body.appendChild(el);
                            setTimeout(function () { el.remove(); }, 10000);
                        }, i * 110);
                    })(i);
                }
            }

            function celebrate() {
                party.classList.add('on');
                party.setAttribute('aria-hidden', 'false');
                wave();
            }

            var tease = window.initTeaseButtons({
                root: document.getElementById('askBlock'),
                onYes: celebrate,
                // this page can afford the sillier emoji set
                emojis: ['😢', '🎈', '🥺', '💔', '😭'],
            });

            /** Blow the bouquet back up, for the looping demo. */
            function reset() {
                popped = false;
                balloons.forEach(function (b) { b.classList.remove('popped'); });
                bouquet.classList.remove('done');
                bouquet.classList.add('sway');
                bouquet.removeAttribute('aria-expanded');
                ring.classList.remove('drop');
                stage.classList.remove('asked');
                if (tap) tap.style.display = '';
                party.classList.remove('on');
                party.setAttribute('aria-hidden', 'true');
                if (tease) tease.reset();
            }

            window.__proposalDemo = {
                phases: [0, 700, 1300],
                open: run,
                yes: celebrate,
                reset: reset,
            };

            var pre = @json($stage);
            if (pre === 'open' || pre === 'yes') {
                balloons.forEach(function (b) { b.classList.add('popped'); });
                bouquet.classList.remove('sway');
                bouquet.classList.add('done');
                ring.classList.add('drop');
                stage.classList.add('asked');
                if (tap) tap.style.display = 'none';
                popped = true;
                if (pre === 'yes') celebrate();
            }
        })();
    </script>

    @include('birthday.partials._proposal_demo')
</body>

</html>
