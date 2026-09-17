<section class="l-hero" id="hero">
    {{-- Three blurred blobs doing the work a hero image used to — cheap to
         paint, and they read as light rather than decoration. --}}
    <div class="l-aurora" aria-hidden="true"><span></span><span></span><span></span></div>

    <div class="container l-hero__grid">
        <div class="l-hero__copy">
            <span class="eyebrow" data-hero-in>Gift-Card Builder Studio</span>

            <h1 class="l-hero__title">
                <span class="l-hero__line" data-line><span>Make a birthday</span></span>
                <span class="l-hero__line" data-line><span>they <em>actually</em></span></span>
                <span class="l-hero__line" data-line><span>remember.</span></span>
            </h1>

            <p class="l-hero__sub" data-hero-in>
                Pick a theme, drop in a photo, lock it with a PIN and hide a gift inside.
                One link — that is the whole present.
            </p>

            <div class="l-hero__actions" data-hero-in>
                <a href="{{ route('client.register') }}" class="btn-magnetic btn-magnetic--solid" data-magnetic
                    data-cursor-hover>
                    <span class="btn-magnetic__fill"></span>
                    <span class="btn-magnetic__label">
                        <span>Create your card — free</span>
                        <span class="btn-magnetic__icon">→</span>
                    </span>
                </a>
                <a href="#how" class="l-hero__ghost" data-magnetic data-cursor-hover>
                    <span>See how it works</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            <ul class="l-hero__trust" data-hero-in>
                <li><span>✦</span> Free to start</li>
                <li><span>🔒</span> Private PIN</li>
                <li><span>⚡</span> Ready in 5 minutes</li>
            </ul>
        </div>

        {{-- The finished thing, not a diagram of it — a real card, mid-reveal. --}}
        <div class="l-hero__visual" id="heroVisual">
            <div class="cardview" data-parallax="3">
                <div class="cardview__bar">
                    <span class="cardview__dot"></span>
                    <span class="cardview__dot"></span>
                    <span class="cardview__dot"></span>
                    <span class="cardview__url">giftloft.app/c/aisha-25</span>
                </div>
                <div class="cardview__body">
                    <div class="cardview__frame"
                        style="background-image:url('https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=340&h=420&fit=crop&q=80')">
                        <span>❀</span>
                    </div>
                    <p class="cardview__kicker">A little something for</p>
                    <h3 class="cardview__name">Aisha</h3>
                    <div class="cardview__gifts">
                        <span>🖼️ Gallery</span>
                        <span>💌 Letter</span>
                        <span>📖 Story</span>
                    </div>
                </div>
            </div>

            <div class="hero-chip hero-chip--pin" data-parallax="9">
                <strong>0 4 1 2</strong>
                <span>PIN unlocked</span>
            </div>

            <div class="hero-chip hero-chip--gift" data-parallax="7">
                <span class="hero-chip__ico">🎁</span>
                <span>Gift revealed</span>
            </div>
        </div>
    </div>

    <span class="l-hero__cue" aria-hidden="true"><i></i> Scroll</span>
</section>
