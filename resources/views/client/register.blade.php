@extends('client.layouts.auth')

@section('title', 'Create Account — Giftloft')
@section('form-width', '480px')
@section('brand-title')Your first card is <em>minutes away.</em>@endsection
@section('brand-sub')Sign up free, pick a theme and hand someone a link they will not forget.@endsection

@push('styles')
    <style>
        /* Signup carries the most fields of any auth screen, so it is the one
           that decides the layout: required fields two-up, the three optional
           ones folded away behind a toggle. That keeps the default state at
           four inputs — short enough to sit inside any viewport unscrolled. */

        .pw-rules {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: .3rem .35rem;
            margin-top: .4rem;
        }

        .pw-rules li {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            font-size: .67rem;
            font-weight: 600;
            color: var(--muted);
            background: #f4f3f8;
            border: 1px solid transparent;
            border-radius: 999px;
            padding: .16rem .5rem;
            transition: color .2s ease, background .2s ease, border-color .2s ease;
        }

        .pw-rules li::before {
            content: '○';
            font-size: .6rem;
            line-height: 1;
        }

        .pw-rules li.ok {
            color: #15803d;
            background: #edfcf2;
            border-color: #bbf7d0;
        }

        .pw-rules li.ok::before { content: '✓'; }

        .pw-match {
            font-size: .7rem;
            font-weight: 600;
            margin-top: .25rem;
        }

        .pw-match.ok { color: #15803d; }
        .pw-match.bad { color: #dc2626; }

        /* ── Optional details, folded ─────────────────────────── */
        .more {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            margin-top: .15rem;
            padding: .5rem .8rem;
            border: 1px dashed var(--border);
            border-radius: 11px;
            background: transparent;
            color: var(--muted);
            font-family: inherit;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            transition: border-color .2s ease, color .2s ease, background .2s ease;
        }

        .more:hover { border-color: var(--purple); color: var(--purple); background: rgba(139, 92, 246, .04); }
        .more i { font-style: normal; transition: transform .25s ease; }
        .more[aria-expanded="true"] i { transform: rotate(180deg); }

        .more-panel { display: none; margin-top: var(--gap); }
        .more-panel.is-open { display: block; }

        /* On a phone the three optional fields would otherwise stack into
           three full rows, which is the one thing that pushes this form past
           the fold — city and age share a row instead. */
        @media (max-width: 420px) {
            #morePanel .row { --cols: 2 !important; }
            #morePanel .field:first-child { grid-column: 1 / -1; }
        }
    </style>
@endpush

@section('form')
    @php
        // If anything optional was filled in or bounced back with an error,
        // the fold opens on load so nobody loses what they typed.
        $optionalOpen = collect(['phone', 'city', 'age'])
            ->contains(fn ($f) => old($f) !== null || $errors->has($f));
    @endphp

    <div class="auth-head">
        <h1>Create your account</h1>
        <p>Sign up free and build your first card.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert--bad"><span>⚠️</span> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('client.register.post') }}">
        @csrf

        <div class="row">
            <div class="field">
                <label>Full name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required autofocus>
                @error('name')<span class="err">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label>Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required>
                @error('email')<span class="err">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="row">
            <div class="field" style="margin-bottom:.35rem;">
                <label>Password</label>
                <input type="password" name="password" id="pw" placeholder="••••••••" required>
                @error('password')<span class="err">{{ $message }}</span>@enderror
            </div>
            <div class="field" style="margin-bottom:.35rem;">
                <label>Confirm password</label>
                <input type="password" name="password_confirmation" id="pwConfirm" placeholder="••••••••" required>
                <p class="pw-match" id="pwMatch" hidden></p>
            </div>
        </div>

        {{-- The same four rules the server enforces, ticked live, so nobody
             learns their password was too weak only after a rejected post. --}}
        <ul class="pw-rules" id="pwRules">
            <li data-rule="len">8+ characters</li>
            <li data-rule="upper">Uppercase</li>
            <li data-rule="lower">Lowercase</li>
            <li data-rule="number">A number</li>
        </ul>

        <button type="button" class="more" id="moreToggle" aria-expanded="{{ $optionalOpen ? 'true' : 'false' }}"
            aria-controls="morePanel" style="margin-top:var(--gap);">
            <span>Add phone, city &amp; age <span class="opt">Optional</span></span>
            <i>⌄</i>
        </button>

        <div class="more-panel {{ $optionalOpen ? 'is-open' : '' }}" id="morePanel">
            <div class="row" style="--cols:3;">
                <div class="field">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="03xx-xxxxxxx">
                    @error('phone')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label>City</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Your city">
                    @error('city')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label>Age</label>
                    <input type="number" name="age" value="{{ old('age') }}" min="1" max="120" placeholder="24">
                    @error('age')<span class="err">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <button type="submit" class="submit">Create account <i>→</i></button>
    </form>

    <p class="auth-alt">Already have an account? <a href="{{ route('client.login') }}">Sign in</a></p>
@endsection

@push('scripts')
    <script>
        (function () {
            var pw = document.getElementById('pw');
            var confirmField = document.getElementById('pwConfirm');
            var rules = document.getElementById('pwRules');
            var match = document.getElementById('pwMatch');

            var checks = {
                len: function (v) { return v.length >= 8; },
                upper: function (v) { return /[A-Z]/.test(v); },
                lower: function (v) { return /[a-z]/.test(v); },
                number: function (v) { return /[0-9]/.test(v); }
            };

            function paint() {
                var value = pw.value;
                var allOk = value.length > 0;

                Array.prototype.forEach.call(rules.querySelectorAll('li'), function (li) {
                    var ok = checks[li.dataset.rule](value);
                    li.classList.toggle('ok', ok);
                    if (!ok) allOk = false;
                });

                pw.classList.toggle('ok', allOk);

                // The verdict only means something once the second field has
                // something in it.
                if (!confirmField.value) {
                    match.hidden = true;
                    confirmField.classList.remove('ok');
                    return;
                }

                var same = confirmField.value === value && allOk;
                match.hidden = false;
                match.classList.toggle('ok', same);
                match.classList.toggle('bad', !same);
                match.textContent = same
                    ? '✓ Passwords matched'
                    : (confirmField.value === value ? '✓ Matched — finish the rules' : '✕ Does not match');
                confirmField.classList.toggle('ok', same);
            }

            pw.addEventListener('input', paint);
            confirmField.addEventListener('input', paint);
            paint();

            var toggle = document.getElementById('moreToggle');
            var panel = document.getElementById('morePanel');

            toggle.addEventListener('click', function () {
                var open = panel.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) { var first = panel.querySelector('input'); if (first) first.focus(); }
            });
        })();
    </script>
@endpush
