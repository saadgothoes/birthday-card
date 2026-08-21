<header class="l-nav" id="lNav">
    <div class="l-nav__inner">
        <a href="/" class="l-nav__logo">
            <span class="l-nav__logo-mark">✦</span>
            Birthday<span>Card</span>
        </a>

        <nav class="l-nav__links">
            <a href="#showcase">Themes</a>
            <a href="#builder">How it works</a>
            <a href="#features">Features</a>
            <a href="#gallery">Gallery</a>
        </nav>

        <div class="l-nav__actions">
            <a href="{{ route('client.login') }}" class="l-nav__login" data-cursor-hover>Login</a>

            <a href="{{ route('client.register') }}" class="btn-magnetic l-nav__cta" data-magnetic data-cursor-hover>
                <span class="btn-magnetic__fill"></span>
                <span class="btn-magnetic__label">
                    <span>Sign Up</span>
                    <span class="btn-magnetic__icon">→</span>
                </span>
            </a>
        </div>
    </div>
</header>
