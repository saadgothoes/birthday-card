{{-- Chat-support channels the Super Admin has published.

     Used in two places — under the payment form and inside the "Need Help?"
     modal — so `bare` drops the heading when the surrounding box already
     says what these links are. --}}
@if ($supportContacts->isNotEmpty())
    <div class="support-box @if ($bare ?? false) bare @endif">
        @unless ($bare ?? false)
            <h4>💬 Payment issue? Talk to us</h4>
            <p>If your transfer failed, the amount was deducted twice, or anything else went wrong, message us
                directly — we reply on all of these.</p>
        @endunless

        <div class="support-links">
            @foreach ($supportContacts as $contact)
                <a class="support-link" href="{{ $contact->url() }}" target="_blank" rel="noopener">
                    <span class="support-link__ico" style="color:{{ $contact->brandColour() }}">
                        @include('partials.channel-icon', ['channel' => $contact->channel, 'size' => 18])
                    </span>
                    <span>
                        {{ $contact->label }}
                        <span class="val">{{ $contact->displayValue() }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endif
