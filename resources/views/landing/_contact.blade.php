{{-- The channel grid for the public Contact page. Same rows the Super Admin
     publishes to clients, so a changed WhatsApp number needs no deploy.
     Renders nothing when no channel is active. --}}
@if (($supportContacts ?? collect())->isNotEmpty())
    <section class="l-contact" id="contact">
        <div class="container">
            <div class="l-contact__grid">
                @foreach ($supportContacts as $contact)
                    <a class="l-contact__card" href="{{ $contact->url() }}" target="_blank" rel="noopener"
                        data-cursor-hover style="--brand: {{ $contact->brandColour() }}">
                        <span class="l-contact__ico">
                            @include('partials.channel-icon', ['channel' => $contact->channel, 'size' => 24])
                        </span>
                        <span class="l-contact__body">
                            <strong>{{ $contact->label }}</strong>
                            <span>{{ $contact->displayValue() }}</span>
                        </span>
                        <span class="l-contact__arrow" aria-hidden="true">→</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
