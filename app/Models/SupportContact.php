<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * A channel a client can reach support on. Rendered as a link wherever
 * possible, so a WhatsApp number opens a chat rather than asking the client
 * to copy it out by hand.
 */
#[Fillable([
    'channel',
    'label',
    'value',
    'note',
    'is_active',
    'sort_order',
])]
class SupportContact extends Model
{
    /** channel => [label, icon] */
    public const CHANNELS = [
        'whatsapp'  => ['WhatsApp', '💬'],
        'instagram' => ['Instagram', '📸'],
        'facebook'  => ['Facebook', '📘'],
        'telegram'  => ['Telegram', '✈️'],
        'email'     => ['Email', '✉️'],
        'phone'     => ['Phone', '📞'],
        'other'     => ['Other', '🔗'],
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function channelLabel(): string
    {
        return self::CHANNELS[$this->channel][0] ?? 'Contact';
    }

    public function channelIcon(): string
    {
        return self::CHANNELS[$this->channel][1] ?? '🔗';
    }

    /** channel => brand colour, used to tint the icon on the contact page. */
    public const COLOURS = [
        'whatsapp'  => '#25D366',
        'instagram' => '#E1306C',
        'facebook'  => '#1877F2',
        'telegram'  => '#229ED9',
        'email'     => '#EA4335',
        'phone'     => '#0F9D58',
        'other'     => '#6B7280',
    ];

    public function brandColour(): string
    {
        return self::COLOURS[$this->channel] ?? self::COLOURS['other'];
    }

    /** What to print under the label — the admin's note, else the raw value. */
    public function displayValue(): string
    {
        return $this->note ?: $this->value;
    }

    /**
     * A clickable address for this channel. WhatsApp and phone need the value
     * stripped to digits; Instagram and Telegram take a bare handle with or
     * without the leading "@"; anything already a URL is used as-is.
     */
    public function url(): string
    {
        $value = trim($this->value);

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $digits = preg_replace('/\D+/', '', $value);
        $handle = ltrim($value, '@');

        return match ($this->channel) {
            'whatsapp' => 'https://wa.me/' . $digits,
            'phone'    => 'tel:+' . $digits,
            'email'    => 'mailto:' . $value,
            'instagram' => 'https://instagram.com/' . $handle,
            'facebook'  => 'https://facebook.com/' . $handle,
            'telegram'  => 'https://t.me/' . $handle,
            default     => $value,
        };
    }
}
