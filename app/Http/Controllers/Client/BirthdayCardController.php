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
}
