{{-- One card in the Recent/Drafts/Completed grids. --}}
@php
    $step = min(10, max(1, (int) $card->current_step));
@endphp
<div class="tile">
    <div class="tile-top">
        <div class="tile-thumb">
            @if ($card->profile_image_path)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($card->profile_image_path) }}" alt="">
            @else
                {{ $card->theme === 'boy' ? '💙' : ($card->theme === 'girl' ? '💗' : '🎂') }}
            @endif
        </div>
        <div style="min-width:0;">
            {{-- The label the client gave it when saving the draft. --}}
            <div class="tile-name" title="{{ $card->displayTitle() }}">{{ $card->displayTitle() }}</div>
            <div class="tile-meta">
                {{ $card->theme ? ucfirst($card->theme) . ' theme' : 'No theme yet' }}
                · edited {{ $card->updated_at?->diffForHumans(null, true) ?? '—' }} ago
            </div>
        </div>
    </div>

    <div class="meter"><i style="width: {{ $card->progressPercent() }}%"></i></div>
    <div class="tile-meta">
        @if ($card->is_published)
            Finished — all 10 steps
        @else
            Saved at step {{ $step }} of 10
        @endif
    </div>

    @if ($card->is_published && $card->slug)
        @php $shareUrl = \App\Http\Controllers\Client\BirthdayCardController::shareUrl($card->slug); @endphp
        <div class="share-row" style="margin-top:.45rem;">
            <div class="tile-meta" style="word-break:break-all;color:var(--accent);">{{ $shareUrl }}</div>
            <button type="button" class="copy-link" onclick="copyCardLink(this, @js($shareUrl))">Copy</button>
        </div>
        @if ($card->hasQr())
            @php
                $qrImage = \App\Support\QrRenderer::dataUri(
                    $shareUrl,
                    \App\Http\Controllers\Client\BirthdayCardController::qrThemes($card->theme)[$card->qr_data['theme']],
                    180,
                );
            @endphp
            <div class="qr-block">
                <img src="{{ $qrImage }}" alt="QR code for {{ $card->displayTitle() }}">
                <a class="qr-download" href="{{ $qrImage }}"
                    download="birthday-card-{{ $card->id }}.svg">Download QR</a>
            </div>
        @endif
        <div class="tile-meta" style="margin-top:.35rem;">
            @if ($card->linkIsExpired())
                Link expired
            @elseif ($card->linkIsDisabled())
                Link disabled
            @else
                Active until {{ $card->link_expires_at?->format('d M Y') ?? '—' }}
            @endif
        </div>
    @endif

    <div class="tile-foot">
        <span class="pill {{ $card->isDraft() ? 'draft' : 'done' }}">
            {{ $card->isDraft() ? 'Draft' : 'Completed' }}
        </span>
        <div class="tile-actions">
            {{-- Edit reopens the wizard at the step this card was left on. --}}
            <a href="{{ route('client.cards.edit', $card->id) }}" class="btn btn-ghost btn-sm">
                {{ $card->isDraft() ? 'Continue' : 'Edit' }}
            </a>
            <button type="button" class="btn btn-ghost btn-sm" title="Rename"
                onclick="openRenameModal(@js(route('client.cards.rename', $card->id)), @js($card->title ?? ''))">✏️</button>
            @if ($card->is_published)
                <form method="POST" action="{{ route('client.cards.link.toggle', $card->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-ghost btn-sm"
                        title="{{ $card->linkIsDisabled() ? 'Enable link' : 'Disable link' }}">
                        {{ $card->linkIsDisabled() ? 'Enable' : 'Disable' }}
                    </button>
                </form>
            @endif
            @if ($card->isDraft())
                <form method="POST" action="{{ route('client.cards.destroy', $card->id) }}"
                    onsubmit="return confirm('Delete ' + @js($card->displayTitle()) + '? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">🗑</button>
                </form>
            @endif
        </div>
    </div>
</div>
