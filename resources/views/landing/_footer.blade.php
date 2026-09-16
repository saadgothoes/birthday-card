<footer class="l-footer landing">
    <div class="container">
        <div class="l-footer__top">
            <div>
                <a href="/" class="l-nav__logo">
                    <img src="{{ asset('images/logo/clean/primarylogo.png') }}" alt="Giftloft" class="l-nav__logo-img">
                </a>
                <p class="l-footer__tag">The gift-card builder studio — personalised,
                    PIN-locked birthday pages with animated gift reveals, ready to share in minutes.</p>
            </div>

            <div class="l-footer__links">
                {{-- These anchors only resolve on the landing page, so from any
                     other page they have to point back at "/" first. --}}
                @php $onLanding = request()->path() === '/'; @endphp

                <div class="l-footer__col">
                    <h4>Product</h4>
                    <a href="{{ $onLanding ? '#showcase' : url('/#showcase') }}">Themes</a>
                    <a href="{{ $onLanding ? '#builder' : url('/#builder') }}">How it works</a>
                    <a href="{{ $onLanding ? '#features' : url('/#features') }}">Features</a>
                    <a href="{{ $onLanding ? '#gallery' : url('/#gallery') }}">Gallery</a>
                    <a href="{{ route('contact') }}">Contact</a>
                </div>
                <div class="l-footer__col">
                    <h4>Account</h4>
                    <a href="{{ route('client.login') }}">Client Login</a>
                    <a href="{{ route('client.forgot-password') }}">Reset Password</a>
                </div>
                @if (($supportContacts ?? collect())->isNotEmpty())
                    <div class="l-footer__col">
                        <h4>Contact</h4>
                        @foreach ($supportContacts as $contact)
                            <a href="{{ $contact->url() }}" target="_blank" rel="noopener"
                                class="l-footer__social" style="--brand: {{ $contact->brandColour() }}">
                                <span class="l-footer__social-ico">
                                    @include('partials.channel-icon', ['channel' => $contact->channel, 'size' => 15])
                                </span>
                                {{ $contact->label }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="l-footer__bottom">
            <span>&copy; {{ date('Y') }} Giftloft. All rights reserved.</span>
            <span>Made for celebrations that deserve a proper reveal.</span>
        </div>
    </div>
</footer>
