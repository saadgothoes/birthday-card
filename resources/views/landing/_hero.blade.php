<section class="l-hero" id="hero">
    <div class="l-hero__bg"></div>

    <div class="container l-hero__content">
        <span class="eyebrow">Gift-Card Builder Studio</span>

        <h1 class="l-hero__title" id="heroTitle">
            <span class="l-hero__line" data-line><span>Craft.</span></span>
            <span class="l-hero__line" data-line><span>Surprise.</span></span>
            <span class="l-hero__line l-hero__line--accent" data-line><span>Celebrate.</span></span>
        </h1>

        <p class="l-hero__sub" id="heroSub">
            Design a personalised, PIN-locked birthday gift page in minutes — pick a theme,
            drop a photo, hide a surprise inside a gift box, and share one link.
        </p>

        <div class="l-hero__actions" id="heroActions">
            <a href="{{ route('client.login') }}" class="btn-magnetic btn-magnetic--solid" data-magnetic data-cursor-hover>
                <span class="btn-magnetic__fill"></span>
                <span class="btn-magnetic__label">
                    <span>Start Creating</span>
                    <span class="btn-magnetic__icon">→</span>
                </span>
            </a>
            <a href="#builder" class="l-hero__ghost" data-magnetic data-cursor-hover>
                <span>See it in action</span>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </div>

    <div class="l-hero__stage" id="heroStage">
        <div class="l-hero__mock" id="heroMock">
            <div class="mock-app" id="mockApp">
                <div class="mock-sidebar">
                    <div class="mock-sidebar__brand" id="mockBrand">✦ BirthdayCard</div>
                    <div class="mock-step mock-step--active" data-mock-step>
                        <span class="mock-step__num">1</span> Choose Theme
                    </div>
                    <div class="mock-step" data-mock-step>
                        <span class="mock-step__num">2</span> Set Lock Code
                    </div>
                    <div class="mock-step" data-mock-step>
                        <span class="mock-step__num">3</span> Welcome Screen
                    </div>
                    <div class="mock-step" data-mock-step>
                        <span class="mock-step__num">4</span> Gift Section
                    </div>
                    <div class="mock-step" data-mock-step>
                        <span class="mock-step__num">5</span> Generate &amp; Share
                    </div>
                </div>
                <div class="mock-main">
                    <div class="mock-topbar" id="mockTopbar">
                        <span class="mock-progress">Step 1 of 5</span>
                    </div>
                    <div class="mock-theme-cards" id="mockThemeCards">
                        <div class="pcard pcard--arch" data-mock-card>
                            <div class="pcard__frame pcard__frame--photo" style="background-image:url('https://images.unsplash.com/photo-1607344645866-009c320b63e0?w=200&h=260&fit=crop&q=70')"><span>✶</span></div>
                            <div class="pcard__name">Midnight Gold</div>
                            <div class="pcard__tag">Boy theme</div>
                        </div>
                        <div class="pcard pcard--oval" data-mock-card>
                            <div class="pcard__frame pcard__frame--photo" style="background-image:url('https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=200&h=260&fit=crop&q=70')"><span>❀</span></div>
                            <div class="pcard__name">Blush Petal</div>
                            <div class="pcard__tag">Girl theme</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
