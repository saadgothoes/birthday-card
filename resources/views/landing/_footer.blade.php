<footer class="l-footer landing">
    <div class="container">
        <div class="l-footer__top">
            <div>
                <a href="/" class="l-nav__logo">
                    <span class="l-nav__logo-mark">✦</span>
                    Birthday<span>Card</span>
                </a>
                <p class="l-footer__tag">The gift-card builder studio — personalised,
                    PIN-locked birthday pages with animated gift reveals, ready to share in minutes.</p>
            </div>

            <div class="l-footer__links">
                <div class="l-footer__col">
                    <h4>Product</h4>
                    <a href="#showcase">Themes</a>
                    <a href="#builder">How it works</a>
                    <a href="#features">Features</a>
                    <a href="#gallery">Gallery</a>
                </div>
                <div class="l-footer__col">
                    <h4>Account</h4>
                    <a href="{{ route('client.login') }}">Client Login</a>
                    <a href="{{ route('client.forgot-password') }}">Reset Password</a>
                </div>
            </div>
        </div>

        <div class="l-footer__bottom">
            <span>&copy; {{ date('Y') }} BirthdayCard. All rights reserved.</span>
            <span>Made for celebrations that deserve a proper reveal.</span>
        </div>
    </div>
</footer>
