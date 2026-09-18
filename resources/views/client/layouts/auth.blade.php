{{-- Shared shell for every client auth screen (login, signup, password reset).

     The brief was "no scrolling on any screen": the shell is locked to the
     viewport height and only the form column may scroll, as a last-resort
     safety valve. Everything inside is sized off two custom properties that
     shrink on short viewports, so a laptop at 640px tall gets the same layout
     as a 27" monitor — just tighter. --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Giftloft')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo/clean/appicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/clean/appicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,600&family=DM+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --purple: #8B5CF6;
            --purple-deep: #6d28d9;
            --purple-ink: #2b1259;
            --surface: #ffffff;
            --border: #e7e0fa;
            --field: #faf9fe;
            --text: #120d1c;
            --muted: #6b6478;
            --faint: rgba(18, 13, 28, .42);

            /* The two knobs every size is derived from. */
            --gap: 0.85rem;
            --pad: clamp(1.5rem, 4vw, 2.75rem);
            --field-h: 2.85rem;
        }

        /* Short viewports (small laptops, landscape phones) tighten instead of
           scrolling. */
        @media (max-height: 780px) {
            :root { --gap: 0.65rem; --field-h: 2.6rem; }
        }

        @media (max-height: 640px) {
            :root { --gap: 0.5rem; --field-h: 2.35rem; --pad: 1.25rem; }
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body { height: 100%; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--surface);
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: inherit; }

        .auth-shell {
            height: 100dvh;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
        }

        /* ── Brand column ───────────────────────────────────────── */
        .auth-brand {
            position: relative;
            overflow: hidden;
            padding: var(--pad);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            background:
                radial-gradient(120% 90% at 15% 0%, #a78bfa 0%, transparent 55%),
                radial-gradient(110% 80% at 100% 100%, #7c3aed 0%, transparent 60%),
                linear-gradient(150deg, #6d28d9 0%, #35127a 100%);
        }

        /* Soft grain of light so the flat gradient reads as a surface. */
        .auth-brand::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 78% 22%, rgba(255, 255, 255, .16) 0%, transparent 38%),
                radial-gradient(circle at 12% 82%, rgba(255, 255, 255, .10) 0%, transparent 42%);
            pointer-events: none;
        }

        .auth-brand > * { position: relative; z-index: 1; }

        .auth-brand__logo {
            height: clamp(34px, 4.4vh, 46px);
            width: auto;
            filter: brightness(0) invert(1);
        }

        .auth-brand__title {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: clamp(1.9rem, 3.1vw, 3rem);
            line-height: 1.1;
            letter-spacing: -.01em;
            margin-bottom: .8rem;
        }

        .auth-brand__title em { font-style: italic; color: #e9d8ff; }

        .auth-brand__sub {
            font-size: clamp(.9rem, 1.05vw, 1.02rem);
            line-height: 1.6;
            color: rgba(255, 255, 255, .78);
            max-width: 34ch;
        }

        .auth-brand__list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .7rem;
            margin-top: clamp(1.2rem, 3vh, 2.2rem);
        }

        .auth-brand__list li {
            display: flex;
            align-items: center;
            gap: .65rem;
            font-size: .88rem;
            color: rgba(255, 255, 255, .86);
        }

        .auth-brand__list li span {
            width: 26px;
            height: 26px;
            flex: none;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .22);
            font-size: .8rem;
        }

        .auth-brand__foot {
            font-size: .76rem;
            color: rgba(255, 255, 255, .55);
        }

        /* ── Form column ────────────────────────────────────────── */
        .auth-panel {
            padding: var(--pad);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow-y: auto;   /* safety valve only — the form is built to fit */
            background:
                radial-gradient(90% 60% at 100% 0%, rgba(139, 92, 246, .07) 0%, transparent 60%),
                var(--surface);
        }

        .auth-form {
            width: 100%;
            max-width: var(--form-w, 400px);
        }

        .auth-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: calc(var(--gap) * 1.6);
        }

        .auth-back {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .8rem;
            font-weight: 600;
            color: var(--muted);
            transition: color .2s ease, transform .2s ease;
        }

        .auth-back:hover { color: var(--purple); transform: translateX(-2px); }

        .auth-top__logo { height: 30px; width: auto; display: none; }

        .auth-head h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: clamp(1.5rem, 2.4vw, 1.95rem);
            letter-spacing: -.01em;
            line-height: 1.15;
        }

        .auth-head p {
            color: var(--muted);
            font-size: .88rem;
            margin-top: .3rem;
        }

        form { margin-top: calc(var(--gap) * 1.5); }

        .field { display: block; margin-bottom: var(--gap); }

        .field > label {
            display: block;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--faint);
            margin-bottom: .32rem;
        }

        .field input {
            width: 100%;
            height: var(--field-h);
            padding: 0 .95rem;
            background: var(--field);
            border: 1.5px solid var(--border);
            border-radius: 11px;
            color: var(--text);
            font-family: inherit;
            font-size: .92rem;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .field input::placeholder { color: #b8b2c4; }

        .field input:focus {
            border-color: var(--purple);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, .12);
        }

        .field input.ok {
            border-color: #22c55e;
            background: #f4fdf7;
        }

        .field input.ok:focus { box-shadow: 0 0 0 4px rgba(34, 197, 94, .13); }

        .row {
            display: grid;
            grid-template-columns: repeat(var(--cols, 2), 1fr);
            gap: 0 .7rem;
        }

        .opt {
            margin-left: .35rem;
            font-size: .58rem;
            font-weight: 700;
            letter-spacing: .04em;
            color: var(--purple);
            background: rgba(139, 92, 246, .1);
            border-radius: 999px;
            padding: .12rem .4rem;
        }

        .err {
            display: block;
            color: #dc2626;
            font-size: .72rem;
            margin-top: .25rem;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            padding: .6rem .8rem;
            border-radius: 10px;
            font-size: .8rem;
            line-height: 1.4;
            margin-top: calc(var(--gap) * 1.3);
        }

        .alert--bad { background: #fff1f2; color: #be123c; border: 1px solid #ffe4e6; }
        .alert--good { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }

        .submit {
            position: relative;
            width: 100%;
            height: calc(var(--field-h) + .35rem);
            margin-top: calc(var(--gap) * 1.2);
            border: none;
            border-radius: 100px;
            background: var(--purple);
            color: #fff;
            font-family: inherit;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            overflow: hidden;
            isolation: isolate;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            box-shadow: 0 10px 26px -8px rgba(139, 92, 246, .6);
            transition: transform .3s cubic-bezier(.16, 1, .3, 1), box-shadow .3s ease;
        }

        .submit::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -1;
            background: var(--purple-ink);
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform .45s cubic-bezier(.16, 1, .3, 1);
        }

        .submit:hover::before { transform: scaleX(1); }
        .submit:hover { transform: translateY(-2px); box-shadow: 0 16px 32px -10px rgba(139, 92, 246, .7); }
        .submit:active { transform: translateY(0) scale(.99); }
        .submit i { font-style: normal; transition: transform .35s cubic-bezier(.16, 1, .3, 1); }
        .submit:hover i { transform: translateX(4px); }

        .auth-alt {
            text-align: center;
            margin-top: calc(var(--gap) * 1.4);
            font-size: .82rem;
            color: var(--muted);
        }

        .auth-alt a { color: var(--purple); font-weight: 600; }
        .auth-alt a:hover { text-decoration: underline; }

        .link-right {
            display: block;
            text-align: right;
            font-size: .76rem;
            font-weight: 600;
            color: var(--purple);
            margin-top: -.15rem;
        }

        .link-right:hover { text-decoration: underline; }

        /* ── Responsive: brand column folds away, form stays centred ── */
        @media (max-width: 960px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-brand { display: none; }
            .auth-top__logo { display: block; }
            .auth-panel {
                background:
                    radial-gradient(90% 55% at 50% 0%, rgba(139, 92, 246, .12) 0%, transparent 65%),
                    var(--surface);
            }
        }

        @media (max-width: 420px) {
            .row { --cols: 1 !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; animation: none !important; }
        }
    </style>

    @stack('styles')
    @include('partials.no-input-zoom')

</head>

<body>
    <div class="auth-shell">

        <aside class="auth-brand">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo/clean/primarylogo.png') }}" alt="Giftloft" class="auth-brand__logo">
            </a>

            <div>
                <h2 class="auth-brand__title">@yield('brand-title')</h2>
                <p class="auth-brand__sub">@yield('brand-sub')</p>

                <ul class="auth-brand__list">
                    <li><span>✦</span> Pick a theme, drop a photo, done</li>
                    <li><span>🔒</span> Every card locked behind a private PIN</li>
                    <li><span>🎁</span> Animated gift reveal on one shared link</li>
                </ul>
            </div>

            <p class="auth-brand__foot">&copy; {{ date('Y') }} Giftloft — craft, surprise, celebrate.</p>
        </aside>

        <main class="auth-panel">
            <div class="auth-form" style="--form-w: @yield('form-width', '400px')">

                <div class="auth-top">
                    <a href="{{ url('/') }}" class="auth-back">&larr; Back to home</a>
                    <img src="{{ asset('images/logo/clean/primarylogo.png') }}" alt="Giftloft" class="auth-top__logo">
                </div>

                @yield('form')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>
