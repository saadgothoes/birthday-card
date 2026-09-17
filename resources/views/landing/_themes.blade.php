{{-- The catalogue, drifting past on its own — all the browsing, none of the
     scroll a pinned horizontal track used to cost. --}}
<section class="l-themes" id="themes">
    <div class="container">
        <div class="section-head section-head--center">
            <span class="eyebrow">Themes &amp; Gifts</span>
            <h2>Every mood, <em>every gift.</em></h2>
        </div>
    </div>

    <div class="l-marquee">
        <div class="l-marquee__track">
            @php
                $cards = [
                    ['arch', '✶', 'Midnight Gold', 'Boy theme', 'photo-1607344645866-009c320b63e0'],
                    ['arch', '☁', 'Light Blue Sky', 'Boy theme', 'photo-1519681393784-d120267933ba'],
                    ['oval', '❀', 'Blush Petal', 'Girl theme', 'photo-1549465220-1a8b9238cd48'],
                    ['oval', '✿', 'Rose Bloom', 'Girl theme', 'photo-1576919228236-a097c32a5cd4'],
                    ['round', '🖼️', 'Gallery Wall', 'Gift · Photos', 'photo-1530103862676-de8c9debad1d'],
                    ['round', '💌', 'Love Letter', 'Gift · Envelope', 'photo-1512909006721-3d6018887383'],
                    ['hex', '📖', 'Story Book', 'Gift · Pages', 'photo-1516414447565-b14be0adf13e'],
                    ['hex', '🎁', 'Surprise Box', 'Gift · Reveal', 'photo-1513151233558-d860c5398176'],
                ];
            @endphp

            {{-- Rendered twice: the second pass is what the loop wraps onto. --}}
            @foreach ([1, 2] as $pass)
                @foreach ($cards as $card)
                    <div class="pcard pcard--{{ $card[0] }}" @if ($pass === 2) aria-hidden="true" @endif>
                        <div class="pcard__frame pcard__frame--photo"
                            style="background-image:url('https://images.unsplash.com/{{ $card[4] }}?w=300&h=380&fit=crop&q=80')">
                            <span>{{ $card[1] }}</span>
                        </div>
                        <div class="pcard__name">{{ $card[2] }}</div>
                        <div class="pcard__tag">{{ $card[3] }}</div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
