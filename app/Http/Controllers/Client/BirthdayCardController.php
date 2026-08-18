<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'heading' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        $card = $this->currentDraft();
        $card->heading = $data['heading'] ?? null;
        $card->welcome_message = $data['message'] ?? null;
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
        $data = $request->validate([
            'theme' => 'required|integer|in:1,2,3,4',
            'photos' => 'nullable|array|max:4',
            'photos.*' => 'nullable|image|max:5120',
            'name_first' => 'nullable|string|max:100',
            'name_second' => 'nullable|string|max:100',
            'cal_date' => 'nullable|date',
            'message' => 'nullable|string|max:2000',
            'signed' => 'nullable|string|max:100',
        ]);

        $card = $this->currentDraft();
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
            'message' => $data['message'] ?? null,
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
     * Every text slot in the Gift 3 book, in book-page order. Whitelisted so
     * the stored JSON keeps a known shape, and so the dashboard, the save
     * endpoint and the template all agree on one set of keys.
     */
    public const GIFT3_TEXT_KEYS = [
        // Book page 1 — title
        'eyebrow', 'from_name', 'to_name',
        // Book page 2 — big photo
        'caption',
        // Book page 3 — memory
        'memory_text',
        // Book page 4 — polaroids
        'polaroid_label', 'note1', 'note2', 'note3',
        // Book page 5 — letter
        'letter_label', 'letter', 'envelope_hint',
        // Book page 6 — special dates
        'dates_label',
        'date1_name', 'date1_value', 'date2_name', 'date2_value',
        'date3_name', 'date3_value', 'date4_name', 'date4_value',
        // Book page 7 — future dreams
        'dreams_label',
        'dream1', 'dream2', 'dream3', 'dream4', 'dream5',
        // Book page 8 — quote
        'quote',
        // Book page 9 — secret
        'secret_label', 'secret_message',
        // Book page 10 — final
        'final_line1', 'final_line2', 'replay_label',
    ];

    /** Checked/unchecked state of the "Future Dreams" checklist items. */
    public const GIFT3_FLAG_KEYS = [
        'dream1_done', 'dream2_done', 'dream3_done', 'dream4_done', 'dream5_done',
    ];

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
            'letter' => 'nullable|string|max:2000',
        ];

        foreach (self::GIFT3_TEXT_KEYS as $key) {
            if ($key === 'letter') {
                continue; // already given a longer limit above
            }
            $rules[$key] = 'nullable|string|max:255';
        }
        foreach (self::GIFT3_FLAG_KEYS as $key) {
            $rules[$key] = 'nullable|boolean';
        }

        $data = $request->validate($rules);

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
        ];
        foreach (self::GIFT3_TEXT_KEYS as $key) {
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
