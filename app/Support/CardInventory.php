<?php

namespace App\Support;

use App\Models\BirthdayCard;
use Illuminate\Support\Facades\Storage;

/**
 * Walks a card and reports everything a client put into it — every uploaded
 * image and clip, every text field, and the share link it generated.
 *
 * The uploads are spread across five places (the profile photo plus a photos
 * array on each of the three gifts, and a videos array on Gift 3), so this is
 * the one place that knows the full shape. BG Owner reads it to show a client's
 * complete content in one view.
 */
class CardInventory
{
    /** Where each photo slot comes from, in the order a client fills them. */
    private const PHOTO_SOURCES = [
        ['key' => 'gift1_data', 'label' => 'Gift 1', 'dir' => 'gift1'],
        ['key' => 'gift2_data', 'label' => 'Gift 2', 'dir' => 'gift2'],
        ['key' => 'gift3_data', 'label' => 'Gift 3', 'dir' => 'gift3'],
    ];

    /**
     * Every image on a card, newest section last.
     *
     * @return list<array{url:string, path:string, slot:string}>
     */
    public static function images(BirthdayCard $card): array
    {
        $images = [];

        if ($card->profile_image_path) {
            $images[] = self::item($card->profile_image_path, 'Profile photo');
        }

        foreach (self::PHOTO_SOURCES as $source) {
            $data = $card->{$source['key']} ?? [];
            foreach ($data['photos'] ?? [] as $i => $path) {
                if ($path) {
                    $images[] = self::item($path, $source['label'] . ' — photo ' . ($i + 1));
                }
            }
        }

        return $images;
    }

    /**
     * Every uploaded clip on a card.
     *
     * @return list<array{url:string, path:string, slot:string}>
     */
    public static function videos(BirthdayCard $card): array
    {
        $videos = [];

        foreach ($card->gift3_data['videos'] ?? [] as $i => $path) {
            if ($path) {
                $videos[] = self::item($path, 'Gift 3 — clip ' . ($i + 1));
            }
        }

        return $videos;
    }

    /**
     * The words a client wrote, flattened to label/value pairs. Nested gift
     * data is walked so nothing a client typed is left out, but the photo and
     * video arrays are skipped — those are reported as media, not as text.
     *
     * @return list<array{label:string, value:string}>
     */
    public static function texts(BirthdayCard $card): array
    {
        $texts = [];

        foreach ([
            'Card label' => $card->title,
            'Recipient' => $card->recipient_name,
            'Sender' => $card->sender_name,
            'Welcome heading' => $card->heading,
            'Welcome message' => $card->welcome_message,
            'Lock code (PIN)' => $card->lock_code,
            'Lock hint' => $card->lock_hint,
        ] as $label => $value) {
            if (self::filled($value)) {
                $texts[] = ['label' => $label, 'value' => (string) $value];
            }
        }

        foreach ([
            'gift1_data' => 'Gift 1',
            'gift2_data' => 'Gift 2',
            'gift3_data' => 'Gift 3',
            'ending_data' => 'Ending',
            'music_data' => 'Music',
        ] as $key => $section) {
            foreach (self::flatten($card->{$key} ?? []) as $field => $value) {
                $texts[] = ['label' => $section . ' — ' . self::humanise($field), 'value' => $value];
            }
        }

        return $texts;
    }

    /** The public story address, once the card has generated one. */
    public static function shareUrl(BirthdayCard $card): ?string
    {
        return $card->slug
            ? \App\Http\Controllers\Client\BirthdayCardController::shareUrl($card->slug)
            : null;
    }

    /** Total upload footprint of a card, in bytes. */
    public static function storageBytes(BirthdayCard $card): int
    {
        $bytes = 0;
        $disk = Storage::disk('public');

        foreach (array_merge(self::images($card), self::videos($card)) as $item) {
            try {
                if ($disk->exists($item['path'])) {
                    $bytes += $disk->size($item['path']);
                }
            } catch (\Throwable) {
                // A missing or unreadable file just contributes nothing.
            }
        }

        return $bytes;
    }

    public static function humanBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        foreach (['B', 'KB', 'MB', 'GB'] as $i => $unit) {
            if ($bytes < 1024 ** ($i + 1) || $unit === 'GB') {
                return round($bytes / (1024 ** $i), $i === 0 ? 0 : 1) . ' ' . $unit;
            }
        }

        return $bytes . ' B';
    }

    // ─── internals ──────────────────────────────────────────────

    private static function item(string $path, string $slot): array
    {
        return [
            'url' => Storage::url($path),
            'path' => $path,
            'slot' => $slot,
        ];
    }

    private static function filled($value): bool
    {
        return $value !== null && $value !== '' && $value !== [];
    }

    /**
     * Flatten one gift's JSON into `field => value`, skipping the media
     * arrays and anything empty.
     */
    private static function flatten($data, string $prefix = ''): array
    {
        if (! is_array($data)) {
            return [];
        }

        $out = [];
        foreach ($data as $key => $value) {
            if (in_array($key, ['photos', 'videos'], true)) {
                continue;
            }

            $name = $prefix === '' ? (string) $key : $prefix . '.' . $key;

            if (is_array($value)) {
                $out += self::flatten($value, $name);
                continue;
            }

            if (is_bool($value)) {
                $value = $value ? 'yes' : 'no';
            }

            if (self::filled($value)) {
                $out[$name] = (string) $value;
            }
        }

        return $out;
    }

    private static function humanise(string $field): string
    {
        return ucfirst(str_replace('_', ' ', $field));
    }
}
