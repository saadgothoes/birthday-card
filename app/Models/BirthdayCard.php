<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'title',
    'theme',
    'variant',
    'heading',
    'dob',
    'lock_code',
    'lock_hint',
    'recipient_name',
    'sender_name',
    'welcome_message',
    'profile_image_path',
    'gifts',
    'gift_screen_variant',
    'gift1_data',
    'gift2_data',
    'gift3_data',
    'ending_data',
    'music_data',
    'qr_data',
    'current_step',
    'slug',
    'is_published',
    'last_opened_at',
])]
class BirthdayCard extends Model
{
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'gifts' => 'array',
            'gift1_data' => 'array',
            'gift2_data' => 'array',
            'gift3_data' => 'array',
            'ending_data' => 'array',
            'music_data' => 'array',
            'qr_data' => 'array',
            'is_published' => 'boolean',
            'last_opened_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * What the Recent and Drafts lists call this card. Clients never name a
     * card explicitly, so fall back to whoever it is for, then to its id.
     */
    public function displayTitle(): string
    {
        return $this->title
            ?: ($this->gift3_data['to_name'] ?? null)
            ?: $this->recipient_name
            ?: $this->heading
            ?: 'Untitled Card #' . $this->id;
    }

    /** Wizard progress as a percentage, for the card list's progress bar. */
    public function progressPercent(): int
    {
        $total = 10;
        $done = min($total, max(1, (int) $this->current_step) - 1);

        return (int) round($done / $total * 100);
    }

    /** A card is a draft until its QR has been generated. */
    public function isDraft(): bool
    {
        return ! $this->is_published;
    }

    public function hasQr(): bool
    {
        return ! empty($this->qr_data['theme']);
    }
}
