{{-- Steps and the live wizard used to be two pinned sections and four screens
     of scroll. One section now: read the three steps, watch them happen. --}}
<section class="l-how" id="how">
    <div class="container">
        <div class="section-head section-head--center">
            <span class="eyebrow">How It Works</span>
            <h2>Three steps. <em>Five minutes.</em></h2>
            <p>The same wizard you sign in to — no design skill anywhere in it.</p>
        </div>

        <ol class="l-steps">
            <li class="l-step" data-step>
                <span class="l-step__num">01</span>
                <h3>Pick a theme</h3>
                <p>Four moods, each with its own frame shape, palette and gift formats.</p>
            </li>
            <li class="l-step" data-step>
                <span class="l-step__num">02</span>
                <h3>Add your photo &amp; PIN</h3>
                <p>Crop a picture into the frame and choose the code only they would guess.</p>
            </li>
            <li class="l-step" data-step>
                <span class="l-step__num">03</span>
                <h3>Share one link</h3>
                <p>A URL and a QR code — text it, print it, or tuck it inside a real gift.</p>
            </li>
        </ol>

        <div class="l-demo" id="demoStage">
            <div class="mock-app">
                <div class="mock-sidebar">
                    <div class="mock-sidebar__brand">
                        <img src="{{ asset('images/logo/clean/coloricon.png') }}" alt="">Giftloft
                    </div>
                    <div class="mock-step mock-step--active"><span class="mock-step__num">1</span> Choose Theme</div>
                    <div class="mock-step"><span class="mock-step__num">2</span> Set Lock Code</div>
                    <div class="mock-step"><span class="mock-step__num">3</span> Welcome Screen</div>
                    <div class="mock-step"><span class="mock-step__num">4</span> Gift Section</div>
                    <div class="mock-step"><span class="mock-step__num">5</span> Generate &amp; Share</div>
                </div>

                <div class="mock-main">
                    <div class="mock-topbar"><span class="mock-progress">Step 1 of 5</span></div>

                    <div class="mock-theme-cards">
                        <div class="pcard pcard--arch" id="demoThemeA">
                            <div class="pcard__frame pcard__frame--photo"
                                style="background-image:url('https://images.unsplash.com/photo-1607344645866-009c320b63e0?w=200&h=260&fit=crop&q=70')"><span>✶</span></div>
                            <div class="pcard__name">Midnight Gold</div>
                        </div>
                        <div class="pcard pcard--oval" id="demoThemeB">
                            <div class="pcard__frame pcard__frame--photo"
                                style="background-image:url('https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=200&h=260&fit=crop&q=70')"><span>❀</span></div>
                            <div class="pcard__name">Blush Petal</div>
                        </div>
                    </div>

                    <div class="mock-upload" id="demoUpload">Drop photo here or click to browse</div>

                    <div class="mock-pin-row" id="demoPinRow">
                        <div class="mock-pin-dot" data-pin-dot></div>
                        <div class="mock-pin-dot" data-pin-dot></div>
                        <div class="mock-pin-dot" data-pin-dot></div>
                        <div class="mock-pin-dot" data-pin-dot></div>
                    </div>

                    <div class="mock-gift-tabs">
                        <div class="mock-gift-tab mock-gift-tab--active" data-b-tab>🖼️ Gallery</div>
                        <div class="mock-gift-tab" data-b-tab>💌 Love Letter</div>
                        <div class="mock-gift-tab" data-b-tab>📖 Story Book</div>
                    </div>
                </div>
            </div>

            <div class="mock-photo-chip" id="demoChip">📸</div>

            <div class="fake-cursor" id="demoCursor">
                <div class="fake-cursor__ring"></div>
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M3 2l16 7-6.5 2-2 6.5L3 2z" fill="#8B5CF6" stroke="#fff" stroke-width="1"/></svg>
            </div>
        </div>
    </div>
</section>
