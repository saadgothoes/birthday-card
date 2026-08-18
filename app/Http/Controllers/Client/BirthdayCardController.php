<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    // Step 5 — Gift 1: theme choice (1-4) + up to 3 photos
    public function saveStep5(Request $request)
    {
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'nullable|image|max:5120',
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
        ]);

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

        $card->gift2_data = [
            'theme' => (int) $data['theme'],
            'photos' => $photos,
            'name_first' => $data['name_first'] ?? null,
            'name_second' => $data['name_second'] ?? null,
            'cal_date' => $data['cal_date'] ?? null,
            'message' => $this->normaliseNewlines($data['message'] ?? null),
            'signed' => $data['signed'] ?? null,
        ];
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
        $rules = [
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|image|max:5120',
            'dream_count' => 'required|integer|between:' . self::GIFT3_MIN_DREAMS . ',' . self::GIFT3_MAX_DREAMS,
        ];

        foreach (self::GIFT3_TEXT_LIMITS as $key => $limit) {
            $rules[$key] = 'nullable|string|max:' . $limit;
        }
        foreach (self::GIFT3_DATE_KEYS as $key) {
            $rules[$key] = 'nullable|date_format:Y-m-d';
        }
        foreach (self::GIFT3_FLAG_KEYS as $key) {
            $rules[$key] = 'nullable|boolean';
        }

        $validator = Validator::make($request->all(), $rules);

        // The letter is the one field where the character count alone doesn't
        // bound the height.
        $validator->after(function ($validator) use ($request) {
            $lines = substr_count($this->normaliseNewlines((string) $request->input('letter')), "\n") + 1;
            if ($lines > self::GIFT3_LETTER_MAX_LINES) {
                $validator->errors()->add(
                    'letter',
                    'The letter cannot be more than ' . self::GIFT3_LETTER_MAX_LINES . ' lines long.'
                );
            }
        });

        $data = $validator->validate();

        $card = $this->currentDraft();
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
            'dream_count' => (int) $data['dream_count'],
        ];
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

        $card->gift3_data = $gift3;
        $card->current_step = max($card->current_step, 8);
        $card->save();

        return response()->json([
            'success' => true,
            'card_id' => $card->id,
            'photo_urls' => array_map(fn ($p) => $p ? Storage::url($p) : null, $photos),
        ]);
    }
}
