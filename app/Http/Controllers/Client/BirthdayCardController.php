<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use App\Support\QrRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BirthdayCardController extends Controller
{
    // Get (or create) the client's current in-progress draft card
    protected function currentDraft(): BirthdayCard
    {
        $card = BirthdayCard::where('user_id', Auth::id())
            ->where('is_published', false)
            ->latest()
            ->first();

        if (! $card) {
            $card = BirthdayCard::create(['user_id' => Auth::id()]);
        }

        return $card;
    }

    /**
     * Browsers submit textareas with CRLF. Normalising on the way in keeps
     * one convention in the stored JSON, so the templates' line handling and
     * the line counts here agree with what the client actually typed.
     */
    protected function normaliseNewlines(?string $value): ?string
    {
        return $value === null ? null : str_replace(["\r\n", "\r"], "\n", $value);
    }

    // Step 1 — save theme + variant selection
    public function saveStep1(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|in:boy,girl',
            'variant' => 'required|integer|in:1,2',
        ]);

        $card = $this->currentDraft();
        $card->theme = $data['theme'];
        $card->variant = $data['variant'];
        $card->current_step = max($card->current_step, 2);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    // Step 2 — save 4-digit PIN + photo (also re-confirms step 1 theme/variant)
    public function saveStep2(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|in:boy,girl',
            'variant' => 'required|integer|in:1,2',
            'lock_code' => 'required|digits:4',
            'photo' => 'nullable|image|max:5120',
        ]);

        $card = $this->currentDraft();
        $card->theme = $data['theme'];
        $card->variant = $data['variant'];
        $card->lock_code = $data['lock_code'];

        if ($request->hasFile('photo')) {
            if ($card->profile_image_path) {
                Storage::disk('public')->delete($card->profile_image_path);
            }
            $card->profile_image_path = $request->file('photo')->store('birthday-cards/profile', 'public');
        }

        $card->current_step = max($card->current_step, 3);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'profile_image_url' => $card->profile_image_path ? Storage::url($card->profile_image_path) : null,
        ]);
    }

    // Step 3 — save welcome screen heading + message
    public function saveStep3(Request $request)
    {
        $data = $request->validate([
            'heading' => 'nullable|string|max:' . self::WELCOME_LIMITS['heading'],
            'message' => 'nullable|string|max:' . self::WELCOME_LIMITS['message'],
        ]);

        $card = $this->currentDraft();
        $card->heading = $data['heading'] ?? null;
        $card->welcome_message = $this->normaliseNewlines($data['message'] ?? null);
        $card->current_step = max($card->current_step, 4);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    // Step 4 — save which gift-box screen design was chosen
    public function saveStep4(Request $request)
    {
        $data = $request->validate([
            'gift_screen_variant' => 'required|integer|in:1,2',
        ]);

        $card = $this->currentDraft();
        $card->gift_screen_variant = $data['gift_screen_variant'];
        $card->current_step = max($card->current_step, 5);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    /**
     * Step 5 — Gift 1: theme choice (1-4), up to 3 photos, and — for the girl
     * design — the special date its calendar marks and the note beside it.
     *
     * The girl fields are always accepted and stored, even on a boy card:
     * a client who switches theme after filling them in keeps their work, and
     * the boy templates simply have nowhere to show them.
     */
    public function saveStep5(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'nullable|image|max:5120',
            'cal_date' => 'nullable|date',
            'message' => 'nullable|string|max:' . self::GIFT1_GIRL_LIMITS['message'],
        ]);

        $card = $this->currentDraft();
        $existing = $card->gift1_data ?? [];
        $photos = $existing['photos'] ?? [null, null, null];

        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift1', 'public');
        }

        $card->gift1_data = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
            // Girl design only — the calendar's marked day and the note.
            // The full date is kept so the picker can be restored; the month
            // name and the month's length are derived at render time.
            'cal_date' => $data['cal_date'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
        ];
        $card->current_step = max($card->current_step, 6);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn ($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }

    // Step 6 — Gift 2: theme choice (1-4) + up to 4 photos + names + date + note
    public function saveStep6(Request $request)
    {
        $card = $this->currentDraft();
        $messageLimit = $card->theme === 'girl'
            ? self::GIFT2_LIMITS['message_girl']
            : self::GIFT2_LIMITS['message_boy'];

        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:4',
            'photos.*' => 'nullable|image|max:5120',
            'name_first' => 'nullable|string|max:' . self::GIFT2_LIMITS['name_first'],
            'name_second' => 'nullable|string|max:' . self::GIFT2_LIMITS['name_second'],
            'cal_date' => 'nullable|date',
            'message' => 'nullable|string|max:' . $messageLimit,
            'signed' => 'nullable|string|max:' . self::GIFT2_LIMITS['signed'],
        ] + array_map(
            fn ($limit) => 'nullable|string|max:' . $limit,
            self::GIFT2_GIRL_LIMITS
        ));

        $existing = $card->gift2_data ?? [];
        $photos = $existing['photos'] ?? [null, null, null, null];

        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift2', 'public');
        }

        $gift2 = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'cal_date' => $data['cal_date'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
            'signed' => $data['signed'] ?? null,
        ];
        // Girl design only — the wrapped box and the three polaroid captions.
        foreach (array_keys(self::GIFT2_GIRL_LIMITS) as $key) {
            $gift2[$key] = $data[$key] ?? null;
        }

        $card->gift2_data = $gift2;
        $card->current_step = max($card->current_step, 7);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn ($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }

    /**
     * Design-safe text limits, in characters.
     *
     * These cards are fixed layouts, not documents: every slot has only the
     * room its own design gives it, so each ceiling is derived from the space
     * that slot actually has at the smallest size the page renders at
     * (measured in a browser — see DASHBOARD_WIZARD_DOCUMENTATION.md).
     * The dashboard reads the same numbers for its `maxlength` and counters,
     * so the field cannot accept what the design cannot show.
     */

    /** Step 3 — the welcome screen. */
    public const WELCOME_LIMITS = [
        'heading' => 40,   // display face, ~16 chars a line, wraps to 2-3
        'message' => 160,  // ~40 chars a line, room for 4
    ];

    /**
     * Step 5 — Gift 1.
     *
     * Only the girl design has these: it pairs the three photos with a mini
     * calendar and a handwritten note. The boy design is a photo board and
     * nothing else, which is why this step used to collect photos alone — and
     * why the girl side was being asked for the boy design's fields.
     *
     * `cal_month` and the month's length are derived from the picked date, not
     * typed, so only the note needs a ceiling.
     */
    public const GIFT1_GIRL_LIMITS = [
        'message' => 90,   // a small note panel sharing a row with the calendar
    ];

    /** Step 6 — Gift 2. The note lives in a different box per theme. */
    public const GIFT2_LIMITS = [
        'name_first' => 14,
        'name_second' => 14,
        'signed' => 30,
        // Boy: a fixed note panel under the calendar. Girl: a letter sheet
        // that is both taller and scrollable.
        'message_boy' => 180,
        'message_girl' => 300,
    ];

    /**
     * Step 6 — the girl Gift 2 scene, which the boy design has no equivalent
     * for: a wrapped box that opens onto three polaroids, then an envelope.
     * Each slot is a single line in a fixed spot, so each ceiling is the width
     * that spot has at the narrowest render.
     */
    public const GIFT2_GIRL_LIMITS = [
        'box_title' => 34,   // the line above the gift box
        'box_hint' => 20,    // the smaller prompt under it
        'cap1' => 18,        // handwritten strip under a polaroid
        'cap2' => 18,
        'cap3' => 18,
    ];

    /**
     * Step 7 — every text slot in the Gift 3 book, in book-page order, with
     * its limit. Whitelisted so the stored JSON keeps a known shape, and so
     * the dashboard, the save endpoint and the template all agree on one set
     * of keys.
     */
    public const GIFT3_TEXT_LIMITS = [
        // Book page 1 — title
        'eyebrow' => 28,
        'from_name' => 18,        // script face at up to 42px
        'to_name' => 18,
        // Book page 2 — big photo
        'caption' => 45,
        // Book page 3 — memory
        'memory_text' => 70,      // narrow half-width column beside the photo
        // Book page 4 — polaroids
        'polaroid_label' => 32,
        'note1' => 18,            // handwritten strip under a polaroid
        'note2' => 18,
        'note3' => 18,
        // Book page 5 — letter
        'letter_label' => 32,
        'letter' => 280,          // see GIFT3_LETTER_MAX_LINES as well
        'envelope_hint' => 28,
        // Book page 6 — special dates
        'dates_label' => 32,
        'date1_name' => 22,       // shares its row with the date itself
        'date2_name' => 22,
        'date3_name' => 22,
        'date4_name' => 22,
        // Book page 7 — future dreams
        'dreams_label' => 32,
        'dream1' => 24,
        'dream2' => 24,
        'dream3' => 24,
        'dream4' => 24,
        // Book page 8 — quote
        'quote' => 120,
        // Book page 9 — secret
        'secret_label' => 32,
        'secret_button' => 20,
        'secret_message' => 48,
        // Book page 10 — final
        'final_line1' => 42,
        'final_line2' => 42,
        'replay_label' => 20,
        'close_label' => 20,
    ];

    /**
     * The letter is the one multi-line slot, so it needs a line ceiling too —
     * 280 characters of "a\n" would still walk off the paper.
     */
    public const GIFT3_LETTER_MAX_LINES = 10;

    /**
     * Step 7 — the girl Gift 3, which is a different thing entirely: a phone
     * with a camera roll on it, scrolled through card by card. Photos, a chat
     * screen, two video clips, a pinned note and a letter, rather than a book.
     *
     * Every slot below is a line in a fixed card, so each ceiling is the room
     * that slot has at the narrowest render.
     */
    public const GIFT3_GIRL_TEXT_LIMITS = [
        // The cover and the gallery header
        'cover_title' => 22,
        'cover_sub' => 34,
        'cover_tap' => 18,
        'gallery_title' => 22,
        // Card 1 — a photo with its date
        'p1_date' => 14,
        'p1_place' => 18,
        'p1_caption' => 34,       // three lines of the italic caption row
        // Card 2 — the video clip
        'v1_date' => 14,
        'v1_place' => 18,
        'v1_caption' => 34,
        'v1_duration' => 6,       // the pill over the play button, "0:18"
        // Card 3 — the chat, either an uploaded screenshot or these three lines
        'chat_name' => 18,
        'chat_date' => 14,
        'chat_caption' => 34,
        'chat1' => 44,            // a bubble, at most two lines of it
        'chat2' => 44,
        'chat3' => 44,
        // Card 4 — the second photo
        'p2_date' => 14,
        'p2_place' => 18,
        'p2_caption' => 34,
        // Card 5 — the letter, opened full screen
        'letter' => 420,          // see GIFT3_GIRL_LETTER_MAX_LINES
        'signoff' => 22,
    ];

    /** The letter is the one girl slot characters alone don't bound. */
    public const GIFT3_GIRL_LETTER_MAX_LINES = 12;

    /**
     * The girl camera roll's four image slots, in the order the dashboard
     * shows them: the first photo, the video's cover still, the chat
     * screenshot, then the second photo.
     */
    public const GIFT3_GIRL_PHOTO_KEYS = ['photo1', 'poster1', 'chat_shot', 'photo2'];

    /** The single clip the video card plays. */
    public const GIFT3_GIRL_VIDEO_KEYS = ['video1'];

    /**
     * A clip is by far the largest thing a client uploads, so it goes up on
     * its own (see uploadGift3Video) rather than riding along with the rest of
     * the step. That keeps every request comfortably inside the server's
     * `post_max_size`, and this ceiling is what both ends enforce.
     *
     * It must stay at or below PHP's own `upload_max_filesize`, or the file is
     * discarded before Laravel ever sees it.
     */
    public const GIFT3_GIRL_VIDEO_MAX_KB = 2048;

    /** Key order for the girl Gift 3 text slots. */
    public static function gift3GirlTextKeys(): array
    {
        return array_keys(self::GIFT3_GIRL_TEXT_LIMITS);
    }

    /** Book page 6 — the date pickers, stored as ISO so they can be restored. */
    public const GIFT3_DATE_KEYS = [
        'date1_value', 'date2_value', 'date3_value', 'date4_value',
    ];

    /** Checked/unchecked state of the "Future Dreams" checklist items. */
    public const GIFT3_FLAG_KEYS = [
        'dream1_done', 'dream2_done', 'dream3_done', 'dream4_done',
    ];

    /** The checklist page holds three rows, or four if the client adds one. */
    public const GIFT3_MIN_DREAMS = 3;

    public const GIFT3_MAX_DREAMS = 4;

    /** Key order for the Gift 3 text slots. */
    public static function gift3TextKeys(): array
    {
        return array_keys(self::GIFT3_TEXT_LIMITS);
    }

    /**
     * Step 7 — Gift 3: the "Our Story" book.
     *
     * Theme choice (1-4), the 5 photos the book lays out, and the text of
     * all 10 of its pages. Only the closed cover stays fixed.
     */
    public function saveStep7(Request $request)
    {
        $card = $this->currentDraft();
        $isGirl = $card->theme === 'girl';

        $rules = [
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|image|max:5120',
        ];

        if ($isGirl) {
            // The clip is not part of this request — it is uploaded on its own
            // beforehand, so a large video can never push the step past the
            // server's post_max_size. See uploadGift3Video.
            $rules['photos'] = 'nullable|array|max:4';
            foreach (self::GIFT3_GIRL_TEXT_LIMITS as $key => $limit) {
                $rules[$key] = 'nullable|string|max:' . $limit;
            }
        } else {
            $rules['dream_count'] = 'required|integer|between:'
                . self::GIFT3_MIN_DREAMS . ',' . self::GIFT3_MAX_DREAMS;
            foreach (self::GIFT3_TEXT_LIMITS as $key => $limit) {
                $rules[$key] = 'nullable|string|max:' . $limit;
            }
            foreach (self::GIFT3_DATE_KEYS as $key) {
                $rules[$key] = 'nullable|date_format:Y-m-d';
            }
            foreach (self::GIFT3_FLAG_KEYS as $key) {
                $rules[$key] = 'nullable|boolean';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        // The multi-line slots are the ones where a character count alone
        // doesn't bound the height.
        $lineCaps = $isGirl
            ? ['letter' => self::GIFT3_GIRL_LETTER_MAX_LINES]
            : ['letter' => self::GIFT3_LETTER_MAX_LINES];

        $validator->after(function ($validator) use ($request, $lineCaps) {
            foreach ($lineCaps as $field => $max) {
                $value = (string) $request->input($field);
                if ($value === '') {
                    continue;
                }
                $lines = substr_count($this->normaliseNewlines($value), "\n") + 1;
                if ($lines > $max) {
                    $validator->errors()->add(
                        $field,
                        'This cannot be more than ' . $max . ' lines long.'
                    );
                }
            }
        });

        $data = $validator->validate();

        $existing = $card->gift3_data ?? [];
        $photos = $existing['photos'] ?? [null, null, null, null, null];

        foreach ($request->file('photos', []) as $i => $file) {
            if (! $file) {
                continue;
            }
            if (! empty($photos[$i])) {
                Storage::disk('public')->delete($photos[$i]);
            }
            $photos[$i] = $file->store('birthday-cards/gift3', 'public');
        }

        $gift3 = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
        ];

        if ($isGirl) {
            // Whatever uploadGift3Video already stored stays as it is.
            $gift3['videos'] = $existing['videos'] ?? [null];

            foreach (self::GIFT3_GIRL_TEXT_LIMITS as $key => $limit) {
                $gift3[$key] = $data[$key] ?? null;
            }
            $gift3['letter'] = $this->normaliseNewlines($gift3['letter']);
        } else {
            $gift3['dream_count'] = (int) $data['dream_count'];
            foreach (self::GIFT3_TEXT_LIMITS as $key => $limit) {
                $gift3[$key] = $data[$key] ?? null;
            }
            $gift3['letter'] = $this->normaliseNewlines($gift3['letter']);
            foreach (self::GIFT3_DATE_KEYS as $key) {
                $gift3[$key] = $data[$key] ?? null;
            }
            foreach (self::GIFT3_FLAG_KEYS as $key) {
                $gift3[$key] = $request->boolean($key);
            }
        }

        // Anything the other side had stays put, so switching theme mid-build
        // doesn't throw away work.
        $card->gift3_data = array_merge($existing, $gift3);
        $card->current_step = max($card->current_step, 8);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn ($p) => $p ? Storage::url($p) : null, $photos),
            'video_urls' => array_map(
                fn ($v) => $v ? Storage::url($v) : null,
                $gift3['videos'] ?? [null]
            ),
        ]);
    }

    /**
     * Step 8 — the ending page ("One Last Thing").
     *
     * The design is an envelope that opens into a letter sheet. The sheet
     * scrolls, so the letter is the one slot with real slack; everything else
     * is a single line in a fixed spot and its ceiling is the width that spot
     * has at the narrowest render.
     */
    public const ENDING_TEXT_LIMITS = [
        'title' => 28,           // display face at up to 28px, one line
        'subtitle' => 48,        // 13px italic under the title
        'tap_label' => 20,       // 12px uppercase, 2px tracking, beside an icon
        'letter_heading' => 32,  // 11px uppercase, 3px tracking
        'letter' => 500,         // the paper scrolls; see ENDING_LETTER_MAX_LINES
        'signoff' => 28,         // 24px hand, right-aligned on one line
        'end_label' => 20,       // 11px uppercase closing stamp
    ];

    /**
     * The letter is written out a character at a time, so its length is a
     * time budget as much as a space one — 500 characters already takes the
     * best part of half a minute to appear.
     */
    public const ENDING_LETTER_MAX_LINES = 14;

    /**
     * Step 8 — the girl ending page, which is a different design, not a
     * recoloured boy one: a closed flower that blooms into a round keepsake
     * card. A circle holds noticeably less than a rectangular sheet, and the
     * card does not scroll, so its note is capped tighter than the boy's.
     */
    public const ENDING_GIRL_TEXT_LIMITS = [
        'title' => 24,           // display italic over the flower
        'subtitle' => 40,
        'tap_label' => 18,       // uppercase, 2.4px tracking, beside an icon
        'letter_heading' => 26,  // the small cap line inside the circle
        'letter' => 180,         // measured with every other slot at its own limit too
        'signoff' => 22,
        'end_label' => 18,
    ];

    public const ENDING_GIRL_LETTER_MAX_LINES = 8;

    /** The ending text slots for a theme, with the right ceilings. */
    public static function endingLimits(?string $theme): array
    {
        return $theme === 'girl' ? self::ENDING_GIRL_TEXT_LIMITS : self::ENDING_TEXT_LIMITS;
    }

    public static function endingLetterMaxLines(?string $theme): int
    {
        return $theme === 'girl' ? self::ENDING_GIRL_LETTER_MAX_LINES : self::ENDING_LETTER_MAX_LINES;
    }

    /**
     * Step 8 — the four ending-page designs per theme.
     *
     * Each one is an existing template reached at /{theme}/page/4/{n}, so the
     * registry only has to name them and say whether the side is wired up.
     * The girl designs exist as templates but are not integrated yet; flipping
     * `available` is what turns them on, and nothing else here changes.
     */
    public const ENDING_THEMES = [
        'boy' => [
            1 => ['name' => 'Cool Steel', 'blurb' => 'Ivory blue paper, midnight navy ink', 'available' => true],
            2 => ['name' => 'Graphite Ice', 'blurb' => 'Dark graphite card, ice blue ink', 'available' => true],
            3 => ['name' => 'Midnight Gold', 'blurb' => 'Deep navy card, warm gold ink', 'available' => true],
            4 => ['name' => 'Slate Emerald', 'blurb' => 'Slate charcoal card, emerald ink', 'available' => true],
        ],
        // The girl designs are their own thing — a flower that blooms into a
        // round keepsake card, not the boy's envelope in pink.
        'girl' => [
            1 => ['name' => 'Blush Rose', 'blurb' => 'Blush petals on cream', 'available' => true],
            2 => ['name' => 'Lilac Dusk', 'blurb' => 'Lavender petals on soft violet', 'available' => true],
            3 => ['name' => 'Rose Gold Noir', 'blurb' => 'Dark field, rose gold bloom', 'available' => true],
            4 => ['name' => 'Plum Midnight', 'blurb' => 'Midnight field, plum bloom', 'available' => true],
        ],
    ];

    /**
     * Step 9 — the four QR designs per theme.
     *
     * Everything here is cosmetic: App\Support\QrRenderer always keeps the
     * full quiet zone, high error correction and a light plate under the
     * symbol, so every theme scans. The girl set is defined but not wired up
     * yet, exactly like the ending designs above.
     *
     * @see \App\Support\QrRenderer
     */
    public const QR_THEMES = [
        'boy' => [
            1 => [
                'name' => 'Midnight Navy',
                'blurb' => 'Classic squares, ivory card',
                'available' => true,
                'bg' => '#EEF3F8',
                'plate' => '#FBFDFF',
                'module' => '#16233A',
                'eye_frame' => '#2C5A80',
                'eye_ball' => '#16233A',
                'shape' => 'square',
                'frame' => 'solid',
                'frame_color' => '#C6D8E8',
                'label' => 'Scan to open',
                'label_color' => '#2C5A80',
                'radius' => 0.06,
            ],
            2 => [
                'name' => 'Steel Glow',
                'blurb' => 'Rounded modules, blue gradient',
                'available' => true,
                'bg' => '#FFFFFF',
                'plate' => '#FFFFFF',
                'module' => '#2C5A80',
                'module_alt' => '#4E80B0',
                'eye_frame' => '#2C5A80',
                'eye_ball' => '#3E6E96',
                'shape' => 'rounded',
                'frame' => 'none',
                'label' => 'Open our story',
                'label_color' => '#2C5A80',
                'radius' => 0.09,
            ],
            3 => [
                'name' => 'Graphite Ice',
                'blurb' => 'Dark card, ice blue dots',
                'available' => true,
                'bg' => '#0D1117',
                'plate' => '#EAF3FA',
                'module' => '#16283A',
                'eye_frame' => '#274B67',
                'eye_ball' => '#16283A',
                'shape' => 'dot',
                'frame' => 'solid',
                'frame_color' => '#274B67',
                'label' => 'Scan me',
                'label_color' => '#BFD9EC',
                'radius' => 0.08,
            ],
            4 => [
                'name' => 'Blueprint',
                'blurb' => 'Dashed frame, plain squares',
                'available' => true,
                'bg' => '#E4EDF6',
                'plate' => '#FFFFFF',
                'module' => '#16233A',
                'eye_frame' => '#16233A',
                'eye_ball' => '#3E6E96',
                'shape' => 'square',
                'frame' => 'dashed',
                'frame_color' => '#6E9FC9',
                'label' => '',
                'radius' => 0.03,
            ],
        ],
        // The girl set is deliberately its own family: rounded and dot modules
        // throughout rather than the boy set's hard squares, warm blush and
        // lilac palettes instead of navy and graphite, and a double card border
        // the boy designs never use.
        'girl' => [
            1 => [
                'name' => 'Blush Petal',
                'blurb' => 'Rounded modules, petal corners, double blush border',
                'available' => true,
                'bg' => '#FFF3F6',
                'plate' => '#FFFBFC',
                'module' => '#7A3247',
                'module_alt' => '#B05B74',
                'eye_frame' => '#A34E67',
                'eye_ball' => '#7A3247',
                'shape' => 'rounded',
                'frame' => 'double',
                'frame_color' => '#E8899F',
                'label' => 'Scan to bloom',
                'label_color' => '#C96A85',
                'motif' => 'petal',
                'motif_color' => '#E8899F',
                'radius' => 0.10,
            ],
            2 => [
                'name' => 'Lilac Confetti',
                'blurb' => 'Soft dots on lilac, sparkle corners',
                'available' => true,
                'bg' => '#F5F1FD',
                'plate' => '#FFFFFF',
                'module' => '#3F2A63',
                'module_alt' => '#6D51A0',
                'eye_frame' => '#7A5BAE',
                'eye_ball' => '#3F2A63',
                'shape' => 'dot',
                'frame' => 'none',
                'label' => 'Open our story',
                'label_color' => '#7A5BAE',
                'motif' => 'sparkle',
                'motif_color' => '#A98BD8',
                'radius' => 0.13,
            ],
            3 => [
                'name' => 'Rose Gold Noir',
                'blurb' => 'Dark card, rose gold dots, heart corners',
                'available' => true,
                'bg' => '#1C1116',
                'plate' => '#FBF1F4',
                'module' => '#3A2028',
                'module_alt' => '#6B3B49',
                'eye_frame' => '#8C5566',
                'eye_ball' => '#3A2028',
                'shape' => 'dot',
                'frame' => 'double',
                'frame_color' => '#C58490',
                'label' => 'Scan me',
                'label_color' => '#E7C3C9',
                'motif' => 'heart',
                'motif_color' => '#C58490',
                'radius' => 0.11,
            ],
            4 => [
                'name' => 'Plum Midnight',
                'blurb' => 'Rounded modules on deep plum, heart corners',
                'available' => true,
                'bg' => '#150F22',
                'plate' => '#F4EFFC',
                'module' => '#2E2440',
                'module_alt' => '#54407C',
                'eye_frame' => '#6B52A0',
                'eye_ball' => '#2E2440',
                'shape' => 'rounded',
                'frame' => 'solid',
                'frame_color' => '#7C5AAE',
                'label' => 'A little something',
                'label_color' => '#C7A6F0',
                'motif' => 'heart',
                'motif_color' => '#9E7BD0',
                'radius' => 0.09,
            ],
        ],
    ];

    /** Path the published story will live under — see the share URL below. */
    public const PUBLIC_CARD_PATH = 'c';

    /**
     * The girl Gift 1 calendar, from one picked date: the month it prints over
     * the grid, the day it marks, and how many days that month has — so
     * February doesn't render 31 cells. The dashboard's live preview derives
     * the same three values in JavaScript.
     */
    public static function girlCalendarParams(?string $date): array
    {
        $timestamp = $date ? strtotime($date) : false;
        if (! $timestamp) {
            return [];
        }

        return [
            'cal_month' => date('F', $timestamp),
            'cal_day' => (string) date('j', $timestamp),
            'cal_days' => (string) date('t', $timestamp),
        ];
    }

    /** Key order for the ending-page text slots. */
    public static function endingTextKeys(): array
    {
        return array_keys(self::ENDING_TEXT_LIMITS);
    }

    /** The ending designs for a theme, boy by default. */
    public static function endingThemes(?string $theme): array
    {
        return self::ENDING_THEMES[$theme] ?? self::ENDING_THEMES['boy'];
    }

    /** The QR designs for a theme, boy by default. */
    public static function qrThemes(?string $theme): array
    {
        return self::QR_THEMES[$theme] ?? self::QR_THEMES['boy'];
    }

    /** Whether a side's designs are wired up yet (girl is not, for now). */
    public static function themeSideIsAvailable(array $themes): bool
    {
        foreach ($themes as $design) {
            if ($design['available'] ?? false) {
                return true;
            }
        }

        return false;
    }

    /**
     * The public address of a card. The story flow itself is not built yet,
     * but the address it will live at is fixed now, so a QR generated today
     * still points at the right place once that flow lands.
     */
    public static function shareUrl(?string $slug): ?string
    {
        return $slug ? url('/'.self::PUBLIC_CARD_PATH.'/'.$slug) : null;
    }

    /**
     * Every card gets its share slug as soon as it exists, not at publish
     * time: the QR encodes the URL, so the dashboard can only show a true preview
     * of the four designs if the address is already settled.
     */
    public static function ensureSlug(BirthdayCard $card): string
    {
        if ($card->slug) {
            return $card->slug;
        }

        $stem = Str::slug((string) ($card->gift3_data['to_name'] ?? $card->recipient_name ?? '')) ?: 'birthday';

        do {
            $slug = $stem.'-'.Str::lower(Str::random(6));
        } while (BirthdayCard::where('slug', $slug)->exists());

        $card->slug = $slug;
        $card->save();

        return $slug;
    }

    /** The four QR designs of a theme, rendered against a card's own URL. */
    public static function qrPreviews(?string $theme, ?string $slug, int $size = 300): array
    {
        $url = self::shareUrl($slug) ?? url('/'.self::PUBLIC_CARD_PATH.'/preview');

        $previews = [];
        foreach (self::qrThemes($theme) as $n => $design) {
            $previews[$n] = QrRenderer::dataUri($url, $design, $size);
        }

        return $previews;
    }

    /**
     * Step 7, girl side — the video clip, uploaded on its own.
     *
     * A clip dwarfs everything else the wizard sends. Bundled into the step's
     * multipart body alongside four photos and thirty text fields it pushed the
     * request past PHP's `post_max_size`, and the server answered 413 before
     * Laravel could say anything useful about it. One file per request keeps
     * every upload inside the budget, and the ceiling enforced here is the same
     * one the dashboard checks before it even starts sending.
     */
    public function uploadGift3Video(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime|max:'
                .self::GIFT3_GIRL_VIDEO_MAX_KB,
        ], [
            'video.max' => 'That clip is too large. The limit is '
                .round(self::GIFT3_GIRL_VIDEO_MAX_KB / 1024).' MB.',
            'video.mimetypes' => 'That file is not a video the card can play (MP4, WebM, OGG or MOV).',
        ]);

        $card = $this->currentDraft();
        $existing = $card->gift3_data ?? [];
        $videos = $existing['videos'] ?? [null];

        if (! empty($videos[0])) {
            Storage::disk('public')->delete($videos[0]);
        }
        $videos[0] = $request->file('video')->store('birthday-cards/gift3-video', 'public');

        $existing['videos'] = $videos;
        $card->gift3_data = $existing;
        $card->save();

        return response()->json([
            'success' => true,
            'video_url' => Storage::url($videos[0]),
        ]);
    }

    /**
     * Step 8 — the ending page: which of the four designs, and its text.
     */
    public function saveStep8(Request $request)
    {
        $card = $this->currentDraft();
        $limits = self::endingLimits($card->theme);
        $maxLines = self::endingLetterMaxLines($card->theme);

        // FormData submits textarea paragraph breaks as CRLF. Count the
        // normalised value instead, so those transport-only \r characters do
        // not make a note that fits the dashboard's maxlength fail validation.
        $input = $request->all();
        if (array_key_exists('letter', $input) && $input['letter'] !== null) {
            $input['letter'] = $this->normaliseNewlines((string) $input['letter']);
        }

        $rules = ['theme' => 'required|integer|in:1,2,3,4'];
        foreach ($limits as $key => $limit) {
            $rules[$key] = 'nullable|string|max:'.$limit;
        }

        $validator = Validator::make($input, $rules);

        // The letter is the one multi-line slot, so characters alone don't
        // bound it — the same treatment Gift 3's letter gets.
        $validator->after(function ($validator) use ($input, $maxLines) {
            $lines = substr_count((string) ($input['letter'] ?? ''), "\n") + 1;
            if ($lines > $maxLines) {
                $validator->errors()->add(
                    'letter',
                    'The letter cannot be more than '.$maxLines.' lines long.'
                );
            }
        });

        $data = $validator->validate();

        $ending = ['theme' => (int) $data['theme']];
        foreach ($limits as $key => $limit) {
            $ending[$key] = $data[$key] ?? null;
        }
        $ending['letter'] = $this->normaliseNewlines($ending['letter']);

        $card->ending_data = $ending;
        $card->current_step = max($card->current_step, 9);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
        ]);
    }

    /**
     * Step 9 — QR Select: save the chosen QR design and hand back the share
     * link plus the rendered code.
     *
     * The card's slug is settled here (or earlier, when the dashboard first
     * rendered the previews), so the link a client copies today is the link
     * the story will answer on once the public flow is built.
     */
    public function saveStep9(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
        ]);

        $card = $this->currentDraft();
        $slug = self::ensureSlug($card);
        $design = self::qrThemes($card->theme)[(int) $data['theme']];

        $card->qr_data = [
            'theme' => (int) $data['theme'],
            'generated_at' => now()->toIso8601String(),
        ];
        $card->current_step = max($card->current_step, 9);
        $card->save();

        $url = self::shareUrl($slug);

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'slug' => $slug,
            'share_url' => $url,
            'qr_svg' => QrRenderer::svg($url, $design, 720),
        ]);
    }
}
