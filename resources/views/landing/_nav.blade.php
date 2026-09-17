<header class="l-nav" id="lNav">
    <div class="l-nav__inner">
        <a href="/" class="l-nav__logo">
            <img src="{{ asset('images/logo/clean/primarylogo.png') }}" alt="Giftloft" class="l-nav__logo-img">
        </a>

        {{-- The in-page anchors only resolve on the landing page itself, so
             from any other page they point back at "/" first. --}}
        @php $onLanding = request()->routeIs('landing') || request()->path() === '/'; @endphp

        <nav class="l-nav__links">
            <a href="{{ $onLanding ? '#how' : url('/#how') }}">How it works</a>
            <a href="{{ $onLanding ? '#themes' : url('/#themes') }}">Themes</a>
            <a href="{{ $onLanding ? '#features' : url('/#features') }}">Features</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'is-active' : '' }}">Contact</a>
        </nav>

        <div class="l-nav__actions">
            @if (($supportContacts ?? collect())->isNotEmpty())
                <div class="l-nav__social">
                    @foreach ($supportContacts->take(2) as $contact)
                        <a href="{{ $contact->url() }}" target="_blank" rel="noopener" data-cursor-hover
                            style="--brand: {{ $contact->brandColour() }}"
                            aria-label="{{ $contact->channelLabel() }}" title="{{ $contact->label }}">
                            @include('partials.channel-icon', ['channel' => $contact->channel, 'size' => 17])
                        </a>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('client.login') }}" class="l-nav__login" data-cursor-hover>Login</a>

            <a href="{{ route('client.register') }}" class="btn-magnetic l-nav__cta" data-magnetic data-cursor-hover>
                <span class="btn-magnetic__fill"></span>
                <span class="btn-magnetic__label">
                    <span>Sign Up</span>
                    <span class="btn-magnetic__icon">→</span>
                </span>
            </a>

            {{-- Below 860px the links collapse; without this they were simply
                 gone, so the whole nav was a logo and a button. --}}
            <button type="button" class="l-nav__burger" id="navBurger" aria-expanded="false"
                aria-controls="navDrawer" aria-label="Menu">
                <span></span><span></span>
            </button>
        </div>
    </div>

    <div class="l-nav__drawer" id="navDrawer">
        <a href="{{ $onLanding ? '#how' : url('/#how') }}">How it works</a>
        <a href="{{ $onLanding ? '#themes' : url('/#themes') }}">Themes</a>
        <a href="{{ $onLanding ? '#features' : url('/#features') }}">Features</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('client.login') }}">Login</a>
    </div>
</header>
