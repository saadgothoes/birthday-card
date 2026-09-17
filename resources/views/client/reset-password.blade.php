@extends('client.layouts.auth')

@section('title', 'Set New Password — Giftloft')
@section('brand-title')One new password and <em>you are back in.</em>@endsection
@section('brand-sub')Pick something you will remember — eight characters or more, with a capital and a number.@endsection

@section('form')
    <div class="auth-head">
        <h1>Set a new password</h1>
        <p>Choose the password you will sign in with from now on.</p>
    </div>

    @if (session('status'))
        <div class="alert alert--good"><span>✅</span> {{ session('status') }}</div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="alert alert--bad"><span>⚠️</span> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('client.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label>Email address</label>
            <input type="email" name="email" value="{{ old('email', $email ?? '') }}" required>
        </div>

        <div class="row">
            <div class="field">
                <label>New password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="field">
                <label>Confirm password</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="submit">Reset password <i>→</i></button>
    </form>

    <p class="auth-alt"><a href="{{ route('client.login') }}">&larr; Back to login</a></p>
@endsection
