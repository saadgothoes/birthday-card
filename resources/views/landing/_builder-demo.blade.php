<section class="l-builder" id="builder">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">The Builder</span>
            <h2>Watch a card <em>build itself.</em></h2>
            <p>The exact same wizard your clients use — choose a theme, crop a photo into
                frame, set a lock code, arrange the gift sections. No design skill required.</p>
        </div>
    </div>

    <div class="container">
        <div class="l-builder__stage" id="builderStage">
            <div class="mock-app" id="builderApp">
                <div class="mock-sidebar">
                    <div class="mock-sidebar__brand">✦ BirthdayCard</div>
                    <div class="mock-step mock-step--active" data-b-step="1"><span class="mock-step__num">1</span> Choose Theme</div>
                    <div class="mock-step" data-b-step="2"><span class="mock-step__num">2</span> Set Lock Code</div>
                    <div class="mock-step" data-b-step="3"><span class="mock-step__num">3</span> Welcome Screen</div>
                    <div class="mock-step" data-b-step="4"><span class="mock-step__num">4</span> Gift Section</div>
                    <div class="mock-step" data-b-step="5"><span class="mock-step__num">5</span> Generate &amp; Share</div>
                </div>

                <div class="mock-main">
                    <div class="mock-topbar"><span class="mock-progress" id="builderProgress">Step 1 of 5</span></div>

                    <div class="mock-theme-cards" id="builderThemeCards">
                        <div class="pcard pcard--arch" id="builderThemeA" data-b-click="theme">
                            <div class="pcard__frame pcard__frame--photo" style="background-image:url('https://images.unsplash.com/photo-1607344645866-009c320b63e0?w=200&h=260&fit=crop&q=70')"><span>✶</span></div>
                            <div class="pcard__name">Midnight Gold</div>
                        </div>
                        <div class="pcard pcard--oval" id="builderThemeB" data-b-click="theme">
                            <div class="pcard__frame pcard__frame--photo" style="background-image:url('https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=200&h=260&fit=crop&q=70')"><span>❀</span></div>
                            <div class="pcard__name">Blush Petal</div>
                        </div>
                    </div>

                    <div class="mock-upload" id="builderUpload" data-b-click="upload">Drop photo here or click to browse</div>
                    <div class="mock-crop" id="builderCrop">
                        <span>🖼</span>
                        <div class="mock-crop__photo" id="builderCropPhoto" style="background-image:url('https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=220&h=280&fit=crop&q=75')"></div>
                    </div>

                    <div class="mock-pin-row" id="builderPinRow">
                        <div class="mock-pin-dot" data-pin-dot></div>
                        <div class="mock-pin-dot" data-pin-dot></div>
                        <div class="mock-pin-dot" data-pin-dot></div>
                        <div class="mock-pin-dot" data-pin-dot></div>
                    </div>

                    <div class="mock-gift-tabs" id="builderGiftTabs">
                        <div class="mock-gift-tab mock-gift-tab--active" data-b-tab="1">🖼️ Gallery</div>
                        <div class="mock-gift-tab" data-b-tab="2">💌 Love Letter</div>
                        <div class="mock-gift-tab" data-b-tab="3">📖 Story Book</div>
                    </div>
                </div>
            </div>

            <div class="mock-photo-chip" id="builderPhotoChip" style="opacity:0">📸</div>

            <div class="fake-cursor" id="fakeCursor">
                <div class="fake-cursor__ring"></div>
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M3 2l16 7-6.5 2-2 6.5L3 2z" fill="#8B5CF6" stroke="#fff" stroke-width="1"/></svg>
            </div>
        </div>

        <p class="l-builder__caption">Everything here mirrors the real dashboard — <strong>theme &amp; variant, arch-shaped photo crop, DOB lock code, gift tabs</strong> — generated live as your client scrolls.</p>
    </div>
</section>
