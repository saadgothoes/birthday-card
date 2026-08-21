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
            <form method="POST" action="{{ route('client.cards.destroy', $card->id) }}"
                onsubmit="return confirm('Delete ' + @js($card->displayTitle()) + '? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" title="Delete">🗑</button>
            </form>
        </div>
    </div>
</div>
