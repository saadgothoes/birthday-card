<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Models\User;
use App\Support\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Super Admin's approval queue for subscription requests.
 *
 * Payment is not integrated yet, so approval here is the whole activation:
 * approving a request is what switches the client's subscription on and sets
 * the card limit their plan buys.
 */
class SubscriptionController extends Controller
{
    public function index()
    {
        return view('admin.subscriptions.index', [
            'pending' => SubscriptionRequest::with('user')
                ->where('status', SubscriptionRequest::PENDING)
                ->latest()
                ->get(),
            'reviewed' => SubscriptionRequest::with(['user', 'reviewer'])
                ->where('status', '!=', SubscriptionRequest::PENDING)
                ->latest('reviewed_at')
                ->limit(50)
                ->get(),
            'plans' => SubscriptionPlans::all(),
        ]);
    }

    public function approve(Request $request, SubscriptionRequest $subscriptionRequest)
    {
        abort_if(! $subscriptionRequest->isPending(), 422, 'This request has already been reviewed.');

        $cards = SubscriptionPlans::cardsFor($subscriptionRequest->plan_amount);

        $subscriptionRequest->update([
            'status' => SubscriptionRequest::APPROVED,
            'card_limit' => $cards,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_note' => $request->input('admin_note'),
        ]);

        $subscriptionRequest->user->forceFill([
            'subscription_status' => User::SUB_ACTIVE,
            'plan_amount' => $subscriptionRequest->plan_amount,
            'card_limit' => $cards,
            'subscription_fee' => $subscriptionRequest->plan_amount,
            'subscription_start_date' => now(),
            'subscription_activated_at' => now(),
        ])->save();

        return back()->with('success', 'Subscription approved — the client can now generate QR codes.');
    }

    public function reject(Request $request, SubscriptionRequest $subscriptionRequest)
    {
        abort_if(! $subscriptionRequest->isPending(), 422, 'This request has already been reviewed.');

        $subscriptionRequest->update([
            'status' => SubscriptionRequest::REJECTED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_note' => $request->input('admin_note'),
        ]);

        $user = $subscriptionRequest->user;

        // A rejection only clears the "pending" flag — an already-active plan
        // from an earlier approval is left alone.
        if (! $user->hasActiveSubscription()) {
            $user->forceFill(['subscription_status' => User::SUB_NONE])->save();
        }

        return back()->with('success', 'Subscription request rejected.');
    }

    /** Turn an active subscription back off. */
    public function revoke(User $user)
    {
        abort_if(! $user->isClient(), 404);

        $user->forceFill([
            'subscription_status' => User::SUB_NONE,
            'plan_amount' => null,
            'card_limit' => SubscriptionPlans::FREE_CARD_LIMIT,
            'subscription_activated_at' => null,
        ])->save();

        return back()->with('success', 'Subscription revoked.');
    }
}
