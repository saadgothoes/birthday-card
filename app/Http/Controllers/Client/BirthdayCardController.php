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
}
