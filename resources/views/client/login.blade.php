@extends('client.layouts.auth')

@section('title', 'Client Login — Giftloft')
@section('brand-title')Welcome back to <em>your studio.</em>@endsection
@section('brand-sub')Every card you have built is waiting exactly where you left it — open one up and keep going.@endsection

@section('form')
    <div class="auth-head">
        <h1>Sign in</h1>
        <p>Log in to create your magic.</p>
    </div>

    @if (session('status'))
        <div class="alert alert--good"><span>✅</span> {{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert--bad"><span>⚠️</span> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('client.login.post') }}">
        @csrf

        <div class="field">
            <label>Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
        </div>

        <div class="field" style="margin-bottom:.4rem;">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <a href="{{ route('client.forgot-password') }}" class="link-right">Forgot password?</a>

        <button type="submit" class="submit">Sign in to dashboard <i>→</i></button>
    </form>

    <p class="auth-alt">New here? <a href="{{ route('client.register') }}">Create an account</a></p>
@endsection
