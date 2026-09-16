<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use App\Models\PaymentMethod;
use App\Models\SubscriptionRequest;
use App\Models\SupportContact;
use App\Support\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

/**
 * The card hub — the CapCut-style landing the client sees before the wizard.
 *
 * New Card starts something genuinely empty, Recent lists what they touched
 * last, and Drafts holds the cards that have not been finished. Each card is
 * opened by its own id, so editing one never bleeds into another.
 */
class CardManagerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cards = BirthdayCard::where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->get();

        $drafts = $cards->where('is_published', false)->values();
        $completed = $cards->where('is_published', true)->values();
        $cardsUsed = Schema::hasColumn('birthday_cards', 'is_revision')
            ? $cards->where('is_revision', false)->count()
            : $cards->count();

        return view('client.cards', [
            'recent' => $cards->take(6),
            'drafts' => $drafts,
            'completed' => $completed,
            'cardsUsed' => $cardsUsed,
            'cardLimit' => $user->cardLimit(),
            'cardsRemaining' => max(0, $user->cardLimit() - $cardsUsed),
            'plans' => SubscriptionPlans::all(),
            'paymentMethods' => PaymentMethod::active()->get(),
            'supportContacts' => SupportContact::active()->get(),
            'pendingRequest' => $user->pendingSubscriptionRequest(),
            'latestRequest' => $user->subscriptionRequests()->first(),
            // Recent activity is derived from the cards themselves — every
            // card records when it was created, last edited and last opened,
            // which is enough of a trail without a separate audit table.
            'activity' => $this->recentActivity($cards),
        ]);
    }

    /**
     * A short "what happened lately" feed for the dashboard, built from the
     * cards' own timestamps.
     *
     * One entry per card — the latest thing that happened to it. Emitting both
     * "Created" and "Edited" for the same card made the feed read as if every
     * draft had been duplicated.
     *
     * @return \Illuminate\Support\Collection
     */
    private function recentActivity($cards)
    {
        $events = collect();

        foreach ($cards as $card) {
            // Newest state wins: a published card reports its QR, an edited
            // draft reports the edit, and anything untouched since it was made
            // reports its creation.
            if ($card->is_published && $card->updated_at) {
                $events->push([
                    'at' => $card->updated_at,
                    'icon' => '🔗',
                    'text' => 'QR generated for',
                    'card' => $card,
                ]);
            } elseif ($card->updated_at && $card->created_at
                && $card->updated_at->gt($card->created_at->addMinute())) {
                $events->push([
                    'at' => $card->updated_at,
                    'icon' => '✏️',
                    'text' => 'Edited',
                    'card' => $card,
                ]);
            } elseif ($card->created_at) {
                $events->push([
                    'at' => $card->created_at,
                    'icon' => '✨',
                    'text' => 'Created',
                    'card' => $card,
                ]);
            }
        }

        return $events->sortByDesc('at')->take(6)->values();
    }

    /** New Card — a blank card, never a copy of the last one. */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user->canCreateCard()) {
            return redirect()->route('client.cards')->with(
                'error',
                'You have used all ' . $user->cardLimit() . ' cards on your plan. '
                    . 'Request a larger plan to create more.'
            );
        }

        $card = BirthdayCard::create([
            'user_id' => $user->id,
            'current_step' => 1,
            'is_published' => false,
            'last_opened_at' => now(),
        ]);

        if (Schema::hasColumn('birthday_cards', 'is_revision')) {
            $card->forceFill(['is_revision' => false])->save();
        }

        return redirect()->route('client.dashboard', ['card' => $card->id]);
    }

    /** Reopen an existing card in the wizard. */
    public function edit(int $card)
    {
        $model = BirthdayCard::where('user_id', Auth::id())->findOrFail($card);

        if ($model->is_published) {
            // Reopening a finished card clones it into a new version, and that
            // costs a card slot. With none left, send the client back to the
            // hub where the limit is explained in a dialog — the bare 403 page
            // this used to throw told them nothing and left them stranded.
            if (! Auth::user()->canCreateCard()) {
                return redirect()->route('client.cards')
                    ->with('card_limit_blocked', $model->displayTitle());
            }

            $model = $this->duplicateCardForEditing($model);
        }

        $model->forceFill(['last_opened_at' => now()])->save();

        return redirect()->route('client.dashboard', ['card' => $model->id]);
    }

    private function duplicateCardForEditing(BirthdayCard $source): BirthdayCard
    {
        $copy = $source->replicate();
        $copy->title = $source->title ? $source->title . ' (New Version)' : null;
        $copy->slug = null;
        $copy->qr_data = null;
        $copy->is_published = false;
        $copy->current_step = 10;

        if (Schema::hasColumn('birthday_cards', 'is_revision')) {
            $copy->is_revision = true;
        }
        $copy->last_opened_at = now();

        $copy->profile_image_path = $this->copyStoredFile($source->profile_image_path);
        $copy->gift1_data = $this->copyPhotoData($source->gift1_data);
        $copy->gift2_data = $this->copyPhotoData($source->gift2_data);
        $copy->gift3_data = $this->copyPhotoData($source->gift3_data, true);
        $copy->save();

        return $copy;
    }

    private function copyPhotoData(?array $data, bool $withVideos = false): ?array
    {
        if (! $data) {
            return $data;
        }

        $copy = $data;
        $copy['photos'] = array_map(fn ($path) => $this->copyStoredFile($path), $data['photos'] ?? []);

        if ($withVideos) {
            $copy['videos'] = array_map(fn ($path) => $this->copyStoredFile($path), $data['videos'] ?? []);
        }

        return $copy;
    }

    private function copyStoredFile(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return $path;
        }

        $target = dirname($path) . '/' . Str::uuid() . '-' . basename($path);
        Storage::disk('public')->copy($path, $target);

        return $target;
    }

    public function rename(Request $request, int $card)
    {
        $data = $request->validate(['title' => 'required|string|max:80']);

        $model = BirthdayCard::where('user_id', Auth::id())->findOrFail($card);
        $model->title = $data['title'];
        $model->save();

        return back()->with('success', 'Card renamed.');
    }

    public function toggleLink(int $card)
    {
        $model = BirthdayCard::where('user_id', Auth::id())
            ->where('is_published', true)
            ->findOrFail($card);

        if ($model->linkIsDisabled()) {
            if ($model->linkIsExpired()) {
                return back()->with('error', 'This link expired after 15 days and cannot be enabled again.');
            }

            $model->forceFill(['link_disabled_at' => null])->save();
            return back()->with('success', 'Card link enabled.');
        }

        $model->forceFill(['link_disabled_at' => now()])->save();
        return back()->with('success', 'Card link disabled.');
    }

    /**
     * Delete a card and the uploads that belong only to it, so a deleted card
     * gives its slot on the plan back cleanly.
     */
    public function destroy(int $card)
    {
        $model = BirthdayCard::where('user_id', Auth::id())->findOrFail($card);

        if ($model->is_published) {
            return back()->with('error', 'Generated cards and their share links cannot be deleted.');
        }

        $paths = array_filter(array_merge(
            [$model->profile_image_path],
            $model->gift1_data['photos'] ?? [],
            $model->gift2_data['photos'] ?? [],
            $model->gift3_data['photos'] ?? [],
            $model->gift3_data['videos'] ?? [],
        ));

        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }

        if (session('active_card_id') == $model->id) {
            session()->forget('active_card_id');
        }

        $model->delete();

        return redirect()->route('client.cards')->with('success', 'Card deleted.');
    }

    // ─── Subscription request ────────────────────────────────────

    /**
     * File a request for a plan. There is no payment step yet — the request
     * goes to the Super Admin, and their approval is what activates it.
     */
    public function requestSubscription(Request $request)
    {
        $data = $request->validate([
            'plan_amount' => 'required|integer|in:' . implode(',', SubscriptionPlans::amounts()),
            // The account they say they paid into has to be one the admin is
            // actually advertising — an inactive method is not an option.
            'payment_method_id' => [
                'required',
                Rule::exists('payment_methods', 'id')->where('is_active', true),
            ],
            'sender_name' => 'required|string|max:120',
            'sender_number' => 'required|string|max:60',
            'transaction_id' => 'nullable|string|max:120',
            'client_note' => 'nullable|string|max:500',
            'payment_screenshot' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'payment_method_id.required' => 'Please choose the account you sent the payment to.',
            'payment_screenshot.required' => 'Please attach a screenshot of your payment.',
        ]);

        $user = Auth::user();

        if ($user->pendingSubscriptionRequest()) {
            return $this->subscriptionResponse(
                $request,
                false,
                'You already have a request waiting for approval.'
            );
        }

        SubscriptionRequest::create([
            'user_id' => $user->id,
            'plan_amount' => $data['plan_amount'],
            'card_limit' => SubscriptionPlans::cardsFor($data['plan_amount']),
            'payment_method_id' => $data['payment_method_id'],
            'sender_name' => $data['sender_name'],
            'sender_number' => $data['sender_number'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'client_note' => $data['client_note'] ?? null,
            'payment_screenshot_path' => $request->file('payment_screenshot')
                ->store('payment-proofs', 'public'),
            'status' => SubscriptionRequest::PENDING,
        ]);

        // Filing a top-up must not disturb a plan that is already running.
        // Flipping an active client to "pending" here was the root of the
        // repeat-purchase failure: every other part of the system reads this
        // one flag, so the moment it flipped, (a) their card limit fell back
        // to the free allowance while they waited, (b) the approver saw them
        // as a first-time buyer and *replaced* their limit instead of adding
        // to it — so a second purchase delivered nothing — and (c) a rejection
        // revoked the plan they had already paid for.
        if (! $user->hasActiveSubscription()) {
            $user->forceFill(['subscription_status' => \App\Models\User::SUB_PENDING])->save();
        }

        return $this->subscriptionResponse(
            $request,
            true,
            'Payment submitted. The admin will verify it and activate your plan shortly.'
        );
    }

    private function subscriptionResponse(Request $request, bool $ok, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => $ok, 'message' => $message], $ok ? 200 : 422);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }
}
