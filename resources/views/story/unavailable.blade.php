{{--
    The page a recipient lands on when a share link will not open.

    `PublicStoryController::story()` used to `abort(410)` here, which meant the
    person who was sent a card — someone who has never seen this product and is
    only here because a friend sent them a link — got a bare framework error
    page. That is the worst possible first impression, and it is also a wasted
    one: a recipient with a dead link in their hand is exactly the person who
    might want a card of their own.

    So this is a real page. It says plainly why the link will not open, it is
    honest about whether that is temporary (disabled) or final (expired), and
    it offers the one next step that makes sense from here — make your own.

    $reason is one of:
      disabled     the owner switched the link off; it can come back
      expired      past its 15 days; it cannot come back
      unavailable  anything else (never published, QR never generated)
--}}
@php
    $copy = [
        'disabled' => [
            'pill' => 'Link disabled',
            'title' => 'This link has been disabled for now',
            'body' => "The person who created this card has switched its link off. Nothing is lost — if they turn it back on, this same link will open right up again.",
            'note' => 'Sent this card yourself? Log in to your dashboard and enable the link again.',
            'icon' => 'lock',
        ],
        'expired' => [
            'pill' => 'Link expired',
            'title' => 'This link has expired',
            'body' => "Card links stay live for 15 days after they are generated, and this one has run its course. It cannot be reopened — but a brand new card takes only a few minutes.",
            'note' => 'Sent this card yourself? Log in and generate a fresh card to share again.',
            'icon' => 'clock',
        ],
        'unavailable' => [
            'pill' => 'Link unavailable',
            'title' => 'This link is not available',
            'body' => "We could not open a card at this address. It may not have been shared yet, or the link may have been copied incompletely.",
            'note' => 'Double-check the link you were sent, or ask the sender to share it again.',
            'icon' => 'question',
        ],
    ][$reason ?? 'unavailable'];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex">
    <title>{{ $copy['pill'] }} — Giftloft</title>
    {{-- Tab icon — the app tile, same mark on every surface. --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo/clean/appicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/clean/appicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,600&family=DM+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    {{--
        A card that goes dark while the story shell is already open leaves this
        page rendering inside the shell's frame, boxed in behind a music badge
        that is playing nothing. Take the whole window instead. Same origin, so
        `top.location` is ours to set.
    --}}
    <script>
        if (window.top !== window.self) {
            try { window.top.location.replace(window.location.href); } catch (e) { }
        }
    </script>

    <style>
        :root {
            --bg: #f7f5fc;
            --surface: #ffffff;
            --border: #e7e0fa;
            --text: #120d1c;
            --text-muted: #6b6478;
            --accent: #8B5CF6;
            --accent-strong: #7c3aed;
            --accent-soft: #f3edfe;
            --radius: 28px;
            --shadow: 0 30px 80px -28px rgba(139, 92, 246, .38);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
            min-height: 100svh;
            padding: clamp(1rem, 4vw, 2rem);
            padding-top: max(clamp(1rem, 4vw, 2rem), env(safe-area-inset-top));
            padding-bottom: max(clamp(1rem, 4vw, 2rem), env(safe-area-inset-bottom));
            position: relative;
            overflow-x: hidden;
        }

        /* Two soft blooms and a faint dot grid — the landing page's backdrop,
           kept quiet so the card in the middle is the only thing to read. */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(60vw 60vw at 12% 8%, rgba(139, 92, 246, .16), transparent 60%),
                radial-gradient(50vw 50vw at 88% 92%, rgba(139, 92, 246, .12), transparent 60%),
                radial-gradient(circle, rgba(18, 13, 28, .045) 1px, transparent 1px);
            background-size: auto, auto, 26px 26px;
            pointer-events: none;
            z-index: 0;
        }

        .top {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            padding-bottom: clamp(1rem, 4vw, 2rem);
        }

        .top img {
            height: 34px;
            width: auto;
            display: block;
        }

        .wrap {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 100%;
            max-width: 560px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: clamp(1.75rem, 5vw, 3rem);
            text-align: center;
            animation: rise .6s cubic-bezier(.2, .8, .3, 1) both;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
        }

        /* ─── The illustration ─────────────────────────────────────────────
           A wrapped gift that is not going to open, with a badge naming why.
           The box drifts; the badge breathes. Nothing spins or flashes — the
           news here is mildly disappointing and the page should read that way. */
        .art {
            position: relative;
            width: 172px;
            height: 158px;
            margin: 0 auto clamp(1.25rem, 4vw, 1.75rem);
        }

        .art__box {
            width: 100%;
            height: 100%;
            animation: float 5s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(-1.5deg);
            }

            50% {
                transform: translateY(-9px) rotate(1.5deg);
            }
        }

        .art__badge {
            position: absolute;
            right: -6px;
            bottom: 4px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--surface);
            border: 1px solid var(--border);
            box-shadow: 0 10px 24px -8px rgba(139, 92, 246, .5);
            display: grid;
            place-items: center;
            color: var(--accent-strong);
        }

        /* The ring that keeps saying it. */
        .art__badge::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 50%;
            border: 2px solid var(--accent);
            opacity: 0;
            animation: ping 2.8s ease-out infinite;
        }

        @keyframes ping {
            0% {
                opacity: .55;
                transform: scale(1);
            }

            70%,
            100% {
                opacity: 0;
                transform: scale(1.45);
            }
        }

        .spark {
            position: absolute;
            border-radius: 50%;
            background: var(--accent);
            opacity: .28;
            animation: bob 4.5s ease-in-out infinite;
        }

        .spark.s1 {
            width: 8px;
            height: 8px;
            left: -6px;
            top: 18px;
        }

        .spark.s2 {
            width: 5px;
            height: 5px;
            right: 10px;
            top: -2px;
            animation-delay: .8s;
        }

        .spark.s3 {
            width: 6px;
            height: 6px;
            left: 16px;
            bottom: -4px;
            animation-delay: 1.6s;
        }

        @keyframes bob {

            0%,
            100% {
                transform: translateY(0);
                opacity: .28;
            }

            50% {
                transform: translateY(-10px);
                opacity: .6;
            }
        }

        /* ─── Copy ─────────────────────────────────────────────────────── */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--accent-strong);
            background: var(--accent-soft);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: .45rem 1rem;
        }

        .pill i {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent);
            display: block;
        }

        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 600;
            font-size: clamp(1.6rem, 5.4vw, 2.3rem);
            line-height: 1.14;
            letter-spacing: -.01em;
            margin: 1.1rem 0 .85rem;
        }

        .body {
            color: var(--text-muted);
            font-size: clamp(.95rem, 2.6vw, 1.02rem);
            line-height: 1.68;
            max-width: 44ch;
            margin-inline: auto;
        }

        .rule {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: clamp(1.5rem, 5vw, 2rem) 0 clamp(1.25rem, 4vw, 1.5rem);
        }

        .kicker {
            font-size: .95rem;
            font-weight: 600;
            margin-bottom: .4rem;
        }

        .kicker+p {
            color: var(--text-muted);
            font-size: .9rem;
            line-height: 1.6;
            max-width: 40ch;
            margin: 0 auto 1.25rem;
        }

        /* ─── Actions ──────────────────────────────────────────────────── */
        .cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            width: 100%;
            max-width: 320px;
            padding: .95rem 1.75rem;
            border-radius: 100px;
            background: var(--accent);
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 16px 34px -14px rgba(139, 92, 246, .85);
            transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
        }

        .cta:hover,
        .cta:focus-visible {
            background: var(--accent-strong);
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -14px rgba(139, 92, 246, .95);
        }

        .cta span:last-child {
            transition: transform .2s ease;
        }

        .cta:hover span:last-child {
            transform: translateX(4px);
        }

        .links {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: .35rem 1.25rem;
            margin-top: 1.1rem;
            font-size: .88rem;
            color: var(--text-muted);
        }

        .links a {
            color: var(--accent-strong);
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid transparent;
        }

        .links a:hover,
        .links a:focus-visible {
            border-bottom-color: currentColor;
        }

        .note {
            margin-top: clamp(1.25rem, 4vw, 1.6rem);
            font-size: .82rem;
            line-height: 1.6;
            color: var(--text-muted);
            background: var(--accent-soft);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: .8rem 1rem;
        }

        .note a {
            color: var(--accent-strong);
            font-weight: 600;
            text-decoration: none;
        }

        .note a:hover {
            text-decoration: underline;
        }

        .foot {
            position: relative;
            z-index: 1;
            text-align: center;
            padding-top: clamp(1rem, 4vw, 2rem);
            font-size: .8rem;
            color: var(--text-muted);
        }

        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 3px;
            border-radius: 6px;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>

    <header class="top">
        <a href="{{ url('/') }}" aria-label="Giftloft home">
            <img src="{{ asset('images/logo/clean/primarylogo.png') }}" alt="Giftloft">
        </a>
    </header>

    <main class="wrap">
        <div class="card">

            <div class="art">
                <span class="spark s1"></span>
                <span class="spark s2"></span>
                <span class="spark s3"></span>

                <svg class="art__box" viewBox="0 0 172 158" fill="none" aria-hidden="true">
                    <defs>
                        <linearGradient id="gBody" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#b79bfb" />
                            <stop offset="1" stop-color="#8B5CF6" />
                        </linearGradient>
                        <linearGradient id="gLid" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#a685f9" />
                            <stop offset="1" stop-color="#7c3aed" />
                        </linearGradient>
                    </defs>

                    {{-- the shadow it sits on --}}
                    <ellipse cx="86" cy="149" rx="50" ry="7" fill="#8B5CF6" opacity=".16" />

                    {{--
                        The bow sits above the lid and therefore against the
                        card, not against the box — so it is drawn in the deep
                        purple. A white bow, matching the ribbon below it,
                        disappears into the white card and leaves the box
                        reading as two bare pillars.
                    --}}
                    <path d="M86 56C73 54 57 48 57 35c0-9 11-11 17-2 5 8 10 17 12 23Z" fill="url(#gLid)" />
                    <path d="M86 56c13-2 29-8 29-21 0-9-11-11-17-2-5 8-10 17-12 23Z" fill="url(#gLid)" />

                    {{-- box --}}
                    <rect x="36" y="76" width="100" height="68" rx="9" fill="url(#gBody)" />
                    <rect x="26" y="53" width="120" height="27" rx="8" fill="url(#gLid)" />

                    {{-- ribbon --}}
                    <rect x="79" y="76" width="14" height="68" fill="#fff" opacity=".86" />
                    <rect x="79" y="53" width="14" height="27" fill="#fff" opacity=".95" />

                    {{-- the seam, so the lid reads as a lid --}}
                    <rect x="36" y="80" width="100" height="2" fill="#5b21b6" opacity=".16" />

                    {{-- the knot, last so it caps both loops --}}
                    <circle cx="86" cy="57" r="7.5" fill="url(#gLid)" />
                    <circle cx="86" cy="55.5" r="2.6" fill="#fff" opacity=".45" />
                </svg>

                <div class="art__badge">
                    @if ($copy['icon'] === 'lock')
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="4" y="10.5" width="16" height="10.5" rx="2.5" />
                            <path d="M8 10.5V7.5a4 4 0 0 1 8 0v3" />
                            <circle cx="12" cy="15.5" r="1.3" fill="currentColor" stroke="none" />
                        </svg>
                    @elseif ($copy['icon'] === 'clock')
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="8.5" />
                            <path d="M12 7.5V12l3 2" />
                        </svg>
                    @else
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="8.5" />
                            <path d="M9.6 9.4a2.5 2.5 0 0 1 4.8.8c0 1.7-2.4 2-2.4 3.4" />
                            <circle cx="12" cy="16.6" r="1.1" fill="currentColor" stroke="none" />
                        </svg>
                    @endif
                </div>
            </div>

            <span class="pill"><i></i>{{ $copy['pill'] }}</span>

            <h1>{{ $copy['title'] }}</h1>
            <p class="body">{{ $copy['body'] }}</p>

            <div class="rule"></div>

            <p class="kicker">Want to send one of these yourself?</p>
            <p>Build a personalised, PIN-locked card with your own photos, music and hidden
                gifts — then share it as a link or a QR code.</p>

            <a class="cta" href="{{ route('client.register') }}">
                <span>Create your own card</span>
                <span aria-hidden="true">→</span>
            </a>

            <div class="links">
                <span>Already have an account? <a href="{{ route('client.login') }}">Log in</a></span>
                <a href="{{ url('/') }}">See how it works</a>
            </div>

            <p class="note">
                {{ $copy['note'] }}
                @if (($reason ?? '') !== 'unavailable')
                    <a href="{{ route('client.login') }}">Go to dashboard</a>
                @endif
            </p>
        </div>
    </main>

    <footer class="foot">
        &copy; {{ date('Y') }} Giftloft — craft, surprise, celebrate.
    </footer>

</body>

</html>
