@extends('client.layouts.auth')

@section('title', 'Reset Password — Giftloft')
@section('brand-title')Locked out? <em>Not for long.</em>@endsection
@section('brand-sub')Tell us the email on your account and a reset link lands in your inbox within a minute.@endsection

@push('styles')
    <style>
        .popup-overlay {
            position: fixed;
            inset: 0;
            z-index: 99;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            background: rgba(18, 13, 28, .55);
            backdrop-filter: blur(3px);
        }

        .popup {
            background: #fff;
            border-radius: 20px;
            padding: 2rem 1.8rem;
            max-width: 370px;
            width: 100%;
            text-align: center;
            box-shadow: 0 30px 70px -20px rgba(18, 13, 28, .45);
        }

        .popup__ico { font-size: 2.4rem; margin-bottom: .6rem; }

        .popup h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: #e11d48;
            margin-bottom: .5rem;
        }

        .popup p { color: var(--muted); font-size: .86rem; line-height: 1.55; margin-bottom: 1.3rem; }
    </style>
@endpush

@section('form')
    @php
        $isDisabledError = $errors->has('email') && str_contains($errors->first('email'), 'currently disabled');
    @endphp

    <div class="auth-head">
        <h1>Reset password</h1>
        <p>We will email you a link to set a new one.</p>
    </div>

    @if (session('status'))
        <div class="alert alert--good"><span>✅</span> {{ session('status') }}</div>
    @endif

    @if ($errors->any() && !$isDisabledError)
        <div class="alert alert--bad"><span>⚠️</span> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('client.forgot-password.send') }}">
        @csrf

        <div class="field">
            <label>Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
        </div>

        <button type="submit" class="submit">Send reset link <i>→</i></button>
    </form>

    <p class="auth-alt"><a href="{{ route('client.login') }}">&larr; Back to login</a></p>

    @if ($isDisabledError)
        <div class="popup-overlay" id="disabledPopup">
            <div class="popup">
                <div class="popup__ico">🚫</div>
                <h3>Account disabled</h3>
                <p>You are currently disabled from this app. Please contact support to reactivate your account
                    before resetting your password.</p>
                <button type="button" class="submit"
                    onclick="document.getElementById('disabledPopup').style.display='none'">Okay</button>
            </div>
        </div>
    @endif
@endsection
