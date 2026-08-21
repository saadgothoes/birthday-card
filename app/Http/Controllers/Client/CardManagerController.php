<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use App\Models\SubscriptionRequest;
use App\Support\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
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
     * cards' own timestamps. Creation and edit are separate entries only when
     * they actually differ, so a freshly made card does not show twice.
     *
     * @return \Illuminate\Support\Collection
     */
    private function recentActivity($cards)
    {
        $events = collect();

        foreach ($cards as $card) {
            if ($card->created_at) {
                $events->push([
                    'at' => $card->created_at,
                    'icon' => '✨',
                    'text' => 'Created',
                    'card' => $card,
                ]);
            }

            if ($card->is_published) {
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
            abort_unless(Auth::user()->canCreateCard(), 403,
                'This generated card is read-only. You have no card slot available for a new version.');

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
            'status' => SubscriptionRequest::PENDING,
        ]);

        $user->forceFill(['subscription_status' => \App\Models\User::SUB_PENDING])->save();

        return $this->subscriptionResponse(
            $request,
            true,
            'Request sent. The admin will review it shortly.'
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
