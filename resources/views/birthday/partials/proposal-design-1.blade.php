{{--
    Proposal · Design 1 — "The Last Message".

    One shared design, re-skinned four ways; the wrapper views
    (proposal-design-1-theme-{1..4}.blade.php) @include this with a
    `proposalTheme` (1-4).

    The modern one, and the one that does not perform. A proposal written as
    the thing it would actually be written in: a chat thread. Nobody has to be
    told how to read it, and the words carry it rather than the ornament —
    which is exactly why it survives being read by someone who winces at
    romance.

        a thread with one unread  →  tap
        →  typing…                 (a real pause, not a loading state)
        →  the messages land, one at a time, each after its own typing
        →  the last one is the question, in a bubble of its own
        →  the reply row slides up where the keyboard would be
        Yes  →  the answer is sent as a bubble and read, the thread lifts
                into hearts — and then they are typing again, and the
                letter arrives

    The typing pause before each message is the whole trick: it is the only
    part of the page that makes a reader wait, and waiting for a message is a
    feeling they already have.

    Renders complete with no query parameters.

    Request params:
      to_name, from_name     who it is for / from (the thread is from them)
      heading                the thread's name ("Us")
      tap_label              the hint under the unread pill
      chat_text              one message per line, up to 5
      couple_photo           the thread's avatar (initials fallback)
      ring_photo             the seal on the letter (drawn ring fallback)
      question               the last message
      yes_label, no_label    the two replies
      yes_heading            the heading on the letter the Yes opens
      letter_text            that letter, one line per line, up to 6
      closing_line, signed   the letter's closing line + signature
      theme                  overrides `proposalTheme`
      preview_stage          open | reveal | yes — skip ahead (dashboard preview)
--}}
@php
    $proposalTheme = (int) ($proposalTheme ?? request('theme', 1));

    // 1 & 2 are the light pair, 3 & 4 the dark pair — a chat thread is one of
    // the few places a dark theme is the *expected* one, so two of the four are.
    $themes = [
        1 => ['name' => 'Paper',
              'bg1' => '#f7f3ec', 'bg2' => '#e6ded1', 'ink' => '#2f2a25', 'soft' => '#8a7f72',
              'accent' => '#b5654a', 'surface' => 'rgba(255,255,255,.66)', 'line' => 'rgba(0,0,0,.09)',
              'in' => '#ffffff', 'inInk' => '#2f2a25', 'outInk' => '#fff8f4',
              'card1' => '#fffaf4', 'card2' => '#f4e8da', 'cardInk' => '#2f2a25',
              'cardSoft' => 'rgba(47,42,37,.55)', 'cardLine' => 'rgba(0,0,0,.12)',
              'scrim' => 'rgba(40,30,24,.72)',
              'metal1' => '#e6c19c', 'metal2' => '#b5654a',
              'fx' => '#b5654a,#e6c19c,#ffffff,#8a7f72'],
        2 => ['name' => 'Bubblegum',
              'bg1' => '#fff1f6', 'bg2' => '#ffd9e6', 'ink' => '#4a2130', 'soft' => '#96677c',
              'accent' => '#e0507f', 'surface' => 'rgba(255,255,255,.7)', 'line' => 'rgba(0,0,0,.08)',
              'in' => '#ffffff', 'inInk' => '#4a2130', 'outInk' => '#ffffff',
              'card1' => '#fffafc', 'card2' => '#ffe6ef', 'cardInk' => '#4a2130',
              'cardSoft' => 'rgba(74,33,48,.55)', 'cardLine' => 'rgba(0,0,0,.1)',
              'scrim' => 'rgba(70,25,42,.7)',
              'metal1' => '#f7c9d8', 'metal2' => '#e0507f',
              'fx' => '#e0507f,#ffb3cc,#ffffff,#ffd9e6'],
        3 => ['name' => 'Night Mode',
              'bg1' => '#1b1c22', 'bg2' => '#0b0c10', 'ink' => '#e9eaf0', 'soft' => '#9aa0b4',
              'accent' => '#8b9cff', 'surface' => 'rgba(255,255,255,.05)', 'line' => 'rgba(255,255,255,.09)',
              'in' => '#23252e', 'inInk' => '#e9eaf0', 'outInk' => '#10111a',
              'card1' => '#1e202a', 'card2' => '#14151c', 'cardInk' => '#e9eaf0',
              'cardSoft' => 'rgba(233,234,240,.55)', 'cardLine' => 'rgba(255,255,255,.14)',
              'scrim' => 'rgba(5,6,9,.82)',
              'metal1' => '#c9d2ff', 'metal2' => '#8b9cff',
              'fx' => '#8b9cff,#c9d2ff,#ffffff,#5b6bd6'],
        4 => ['name' => 'Matcha',
              'bg1' => '#1c3a31', 'bg2' => '#0d1f1a', 'ink' => '#eaf5ee', 'soft' => '#9dbdae',
              'accent' => '#9fe0b4', 'surface' => 'rgba(255,255,255,.06)', 'line' => 'rgba(255,255,255,.1)',
              'in' => '#24473c', 'inInk' => '#eaf5ee', 'outInk' => '#0f2e24',
              'card1' => '#1f4438', 'card2' => '#14302a', 'cardInk' => '#eaf5ee',
              'cardSoft' => 'rgba(234,245,238,.55)', 'cardLine' => 'rgba(255,255,255,.14)',
              'scrim' => 'rgba(6,20,16,.82)',
              'metal1' => '#cdf0da', 'metal2' => '#9fe0b4',
              'fx' => '#9fe0b4,#cdf0da,#ffffff,#5fae83'],
    ];
    $t = $themes[$proposalTheme] ?? $themes[1];

    // One copy of the sample wording, in the controller's design registry.
    $d = \App\Http\Controllers\Client\BirthdayCardController::proposalDefaults(1);

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
    $ringPhoto = request('ring_photo');
    $previewStage = request('preview_stage');

    // The letter the Yes opens. One line per line, same as the thread.
    $letterLines = collect(preg_split('/\r\n|\r|\n/', (string) request('letter_text', $d['letter_text'])))
        ->map(fn ($l) => trim($l))
        ->filter()
        ->take(\App\Http\Controllers\Client\BirthdayCardController::PROPOSAL_MULTILINE['letter_text'])
        ->values();

    // One line in is one bubble out; the limit is the controller's.
    $lines = collect(preg_split('/\r\n|\r|\n/', (string) request('chat_text', $d['chat_text'])))
        ->map(fn ($l) => trim($l))
        ->filter()
        ->take(\App\Http\Controllers\Client\BirthdayCardController::PROPOSAL_MULTILINE['chat_text'])
        ->values();

    // the avatar falls back to the sender's initial rather than a grey person
    $initial = mb_strtoupper(mb_substr(trim($fromName) ?: '♡', 0, 1));
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $question }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg1: {{ $t['bg1'] }};
            --bg2: {{ $t['bg2'] }};
            --ink: {{ $t['ink'] }};
            --soft: {{ $t['soft'] }};
            --accent: {{ $t['accent'] }};
            --surface: {{ $t['surface'] }};
            --line: {{ $t['line'] }};
            --in: {{ $t['in'] }};
            --in-ink: {{ $t['inInk'] }};
            --out-ink: {{ $t['outInk'] }};
            --metal1: {{ $t['metal1'] }};
            --metal2: {{ $t['metal2'] }};
            --serif: 'Instrument Serif', Georgia, serif;
            --sans: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --fx-colors: {{ $t['fx'] }};

            /* the shared question buttons */
            --pt-yes-bg: {{ $t['accent'] }};
            --pt-yes-ink: {{ $t['outInk'] }};
            --pt-no-bg: {{ $t['in'] }};
            --pt-no-ink: {{ $t['inInk'] }};
            --pt-ink: {{ $t['soft'] }};
            --pt-ring: {{ $t['accent'] }};

            /* the shared letter the Yes opens */
            --pk-scrim: {{ $t['scrim'] }};
            --pk-bg: linear-gradient(170deg, {{ $t['card1'] }}, {{ $t['card2'] }});
            --pk-ink: {{ $t['cardInk'] }};
            --pk-soft: {{ $t['cardSoft'] }};
            --pk-accent: {{ $t['accent'] }};
            --pk-line: {{ $t['cardLine'] }};
            --pk-ring-bg: {{ $t['surface'] }};
            --pk-display: 'Instrument Serif', Georgia, serif;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            height: 100%;
        }

        body {
            font-family: var(--sans);
            color: var(--ink);
            background: linear-gradient(175deg, var(--bg1), var(--bg2));
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── the thread ────────────────────────────────────────── */
        .thread {
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
            max-width: 520px;
            margin: 0 auto;
        }

        .bar {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: max(.85rem, env(safe-area-inset-top)) 1rem .85rem;
            border-bottom: 1px solid var(--line);
            background: var(--surface);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .avatar {
            position: relative;
            flex: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            overflow: hidden;
            font-weight: 600;
            font-size: .95rem;
            color: var(--out-ink);
            background: var(--accent);
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bar-name {
            min-width: 0;
            line-height: 1.25;
        }

        .bar-name b {
            display: block;
            font-size: .98rem;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bar-name small {
            font-size: .72rem;
            color: var(--soft);
        }

        .dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            margin-right: .3rem;
            border-radius: 50%;
            background: #4ec97f;
            vertical-align: middle;
        }

        .log {
            flex: 1;
            min-height: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: .42rem;
            /* the bottom padding is what keeps the last bubble off the
               composer's edge — without it the question is clipped */
            padding: 1rem 1rem .7rem;
        }

        .msg {
            max-width: 82%;
            padding: .68em .95em;
            border-radius: 20px;
            font-size: clamp(.95rem, 3.8vw, 1.02rem);
            line-height: 1.45;
            background: var(--in);
            color: var(--in-ink);
            border-bottom-left-radius: 7px;
            align-self: flex-start;
            box-shadow: 0 6px 18px -14px rgba(0, 0, 0, .8);
            transform: translateY(10px) scale(.96);
            opacity: 0;
            transform-origin: left bottom;
            animation: pop .42s cubic-bezier(.16, 1, .3, 1) forwards;
        }

        .msg.out {
            align-self: flex-end;
            background: var(--accent);
            color: var(--out-ink);
            border-radius: 20px;
            border-bottom-right-radius: 7px;
            transform-origin: right bottom;
        }

        /* the question is a message too — it is just the one that is typeset */
        .msg.ask {
            max-width: 90%;
            font-family: var(--serif);
            font-size: clamp(1.5rem, 6.6vw, 1.95rem);
            line-height: 1.16;
            letter-spacing: -.01em;
            padding: .62em .7em;
            background: transparent;
            color: var(--ink);
            border: 1px solid var(--line);
            border-left: 3px solid var(--accent);
            border-radius: 18px;
            border-bottom-left-radius: 7px;
        }

        @keyframes pop {
            to { transform: none; opacity: 1; }
        }

        .receipt {
            align-self: flex-end;
            margin: -.1rem .2rem 0;
            font-size: .68rem;
            color: var(--soft);
            opacity: 0;
            transition: opacity .3s ease;
        }

        .receipt.on { opacity: .9; }

        /* ── typing ────────────────────────────────────────────── */
        .typing {
            align-self: flex-start;
            display: none;
            gap: 4px;
            padding: .8em 1em;
            border-radius: 20px;
            border-bottom-left-radius: 7px;
            background: var(--in);
        }

        .typing.on { display: flex; }

        .typing i {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--soft);
            animation: blink 1.25s infinite;
        }

        .typing i:nth-child(2) { animation-delay: .18s; }
        .typing i:nth-child(3) { animation-delay: .36s; }

        @keyframes blink {
            0%, 60%, 100% { opacity: .3; transform: translateY(0); }
            30%           { opacity: 1;  transform: translateY(-3px); }
        }

        /* ── the unread state, before anything is tapped ───────── */
        .opener {
            position: absolute;
            inset: 0;
            z-index: 5;
            display: grid;
            place-content: center;
            justify-items: center;
            gap: .9rem;
            padding: 1.5rem;
            text-align: center;
            background: linear-gradient(175deg, var(--bg1), var(--bg2));
            border: 0;
            font: inherit;
            color: inherit;
            cursor: pointer;
            transition: opacity .45s ease, visibility 0s linear .45s;
        }

        .opener.off {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .unread {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .62em 1.15em;
            border-radius: 999px;
            background: var(--accent);
            color: var(--out-ink);
            font-size: .92rem;
            font-weight: 600;
            box-shadow: 0 16px 34px -18px rgba(0, 0, 0, .8);
            animation: nudge 2.6s ease-in-out infinite;
        }

        @keyframes nudge {
            0%, 88%, 100% { transform: translateY(0) rotate(0); }
            92%           { transform: translateY(-4px) rotate(-1.5deg); }
            96%           { transform: translateY(-1px) rotate(1deg); }
        }

        .opener h1 {
            margin: .2rem 0 0;
            font-family: var(--serif);
            font-weight: 400;
            font-size: clamp(2.1rem, 10vw, 3rem);
            line-height: 1.05;
        }

        .opener p {
            margin: 0;
            font-size: .88rem;
            color: var(--soft);
        }

        /* ── the reply row, where the keyboard would be ────────── */
        .composer {
            padding: .8rem 1rem calc(.9rem + env(safe-area-inset-bottom));
            border-top: 1px solid var(--line);
            background: var(--surface);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transform: translateY(105%);
            transition: transform .5s cubic-bezier(.16, 1, .3, 1);
        }

        .composer.up { transform: none; }

        .composer .hint {
            margin: 0 0 .1rem;
            text-align: center;
            font-size: .72rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--soft);
        }

        [data-tease-row] { min-height: 58px; }

        [data-tease-stage] { text-align: center; }

        @media (prefers-reduced-motion: reduce) {

            .msg { animation-duration: .01ms; }

            .unread { animation: none; }

            .composer,
            .opener { transition-duration: .01ms; transition-delay: 0s; }
        }
    </style>
</head>

<body>
    <div class="thread">
        <header class="bar">
            <div class="avatar">
                @if ($couple)
                    <img src="{{ $couple }}" alt="">
                @else
                    {{ $initial }}
                @endif
            </div>
            <div class="bar-name">
                <b>{{ $heading }}</b>
                <small><span class="dot"></span>{{ $fromName }}</small>
            </div>
        </header>

        <div class="log" id="log" aria-live="polite">
            <div class="typing" id="typing" aria-hidden="true"><i></i><i></i><i></i></div>
        </div>

        <div class="composer" id="composer">
            <div id="askBlock">
                <p class="hint">Reply</p>
                <div data-tease-row>
                    <button type="button" data-tease-yes>{{ $yesLabel }}</button>
                    <button type="button" data-tease-no>{{ $noLabel }}</button>
                </div>
                <p data-tease-stage></p>
            </div>
        </div>

        <button type="button" class="opener" id="opener" aria-label="Open the thread">
            <span class="unread">1 new message</span>
            <h1>{{ $toName }}</h1>
            <p>{{ $tapLabel }}</p>
        </button>
    </div>

    @include('birthday.partials._proposal_fx')
    {{-- the letter swells up out of the last message --}}
    @include('birthday.partials._proposal_after', ['afterEnter' => 'chat'])
    @include('birthday.partials._proposal_tease')

    <script>
        (function () {
            var log = document.getElementById('log');
            var typing = document.getElementById('typing');
            var opener = document.getElementById('opener');
            var composer = document.getElementById('composer');

            var LINES = @json($lines);
            var QUESTION = @json($question);
            var YES = @json($yesLabel);

            var LEAD = 520;     // how long "typing…" shows before its message
            var STEP = 700;     // message to message
            var GAP = 1000;     // the pause before the question, which is longer
            var timers = [];
            var tease = null;
            var opened = false;

            function at(ms, fn) { timers.push(setTimeout(fn, ms)); }
            function clearAll() { timers.forEach(clearTimeout); timers = []; }

            /** When the question lands — the design's own clock, shared with the demo. */
            function questionAt() {
                return LEAD + Math.max(0, LINES.length - 1) * STEP + GAP;
            }

            function bubble(text, cls) {
                var el = document.createElement('div');
                el.className = 'msg' + (cls ? ' ' + cls : '');
                el.textContent = text;
                log.insertBefore(el, typing);
                return el;
            }

            function showTyping(on) {
                typing.classList.toggle('on', on);
                if (on) log.appendChild(typing);   // always the last thing in the log
            }

            function open() {
                if (opened) return;
                opened = true;
                opener.classList.add('off');
                showTyping(true);

                LINES.forEach(function (line, i) {
                    at(LEAD + i * STEP, function () {
                        bubble(line);
                        // the last message is followed by a longer pause: the
                        // one before someone says the thing they came to say
                        showTyping(true);
                    });
                });

                at(questionAt(), function () {
                    showTyping(false);
                    bubble(QUESTION, 'ask');
                    composer.classList.add('up');
                });
            }

            function celebrate() {
                // the answer is sent the way every other message was
                composer.classList.remove('up');
                var sent = bubble(YES, 'out');
                var read = document.createElement('div');
                read.className = 'receipt';
                read.textContent = 'Read ✓✓';
                log.insertBefore(read, typing);
                requestAnimationFrame(function () { read.classList.add('on'); });

                if (window.pfx) {
                    var box = sent.getBoundingClientRect();
                    pfx.burst('hearts', {
                        x: box.left + box.width / 2, y: box.top, count: 18, power: 7,
                    });
                }

                // and then they are typing again — which is the surprise: the
                // answer was not the end of the conversation
                at(520, function () { showTyping(true); });
                at(1450, function () {
                    showTyping(false);
                    showAfter({ fx: 'hearts' });
                });
            }

            function reset() {
                clearAll();
                opened = false;
                hideAfter();
                log.querySelectorAll('.msg, .receipt').forEach(function (el) { el.remove(); });
                showTyping(false);
                composer.classList.remove('up');
                opener.classList.remove('off');
                if (tease) tease.reset();
            }

            opener.addEventListener('click', open);

            tease = initTeaseButtons({
                root: document.getElementById('askBlock'),
                onYes: celebrate,
                labels: [@json($noLabel), 'are you sure', 'wrong one', 'be serious',
                    'you cannot catch me', 'ok fine'],
                stages: ['', 'hm.', 'it moved.', 'that one is not working.',
                    'one of these two is the right answer.', 'and then there was one.'],
            });

            // The looping demo drives this page through its own flow.
            window.__proposalDemo = {
                phases: [0, LEAD, questionAt()],
                open: open,
                yes: celebrate,
                reset: reset,
            };

            // The dashboard preview parks the page at a stage instead of
            // playing the reveal on every keystroke.
            var pre = @json($previewStage);
            if (pre === 'open' || pre === 'reveal' || pre === 'yes') {
                opened = true;
                opener.classList.add('off');
                LINES.forEach(function (line) { bubble(line); });
                bubble(QUESTION, 'ask');
                composer.classList.add('up');
                if (pre === 'yes') celebrate();
            }
        })();
    </script>

    @include('birthday.partials._proposal_demo')
</body>

</html>
