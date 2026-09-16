<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionRequest;
use App\Models\User;
use App\Support\SubscriptionPlans;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * The Super Admin's plan catalogue: which packages clients are offered, how
 * many cards each one buys, and what it costs.
 *
 * A plan's `amount` is its identity everywhere else (`users.plan_amount`,
 * `subscription_requests.plan_amount`), so the two destructive edits are
 * fenced off:
 *
 *  - the price of a plan that has already been sold cannot be changed, since
 *    that would silently rewrite what past buyers paid;
 *  - a plan that has been sold cannot be deleted, only deactivated, which
 *    hides it from clients while leaving every historical row resolvable.
 */
class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::ordered()->get();

        return view('admin.plans.index', [
            'plans' => $plans,
            'freeCardLimit' => SubscriptionPlans::FREE_CARD_LIMIT,
            // Purchases per plan, so the admin can see what is actually selling
            // before retiring something.
            'purchaseCounts' => SubscriptionRequest::where('status', SubscriptionRequest::APPROVED)
                ->selectRaw('plan_amount, COUNT(*) as total')
                ->groupBy('plan_amount')
                ->pluck('total', 'plan_amount'),
            'revenue' => SubscriptionRequest::where('status', SubscriptionRequest::APPROVED)
                ->selectRaw('plan_amount, SUM(plan_amount) as total')
                ->groupBy('plan_amount')
                ->pluck('total', 'plan_amount'),
            'subscriberCounts' => User::where('subscription_status', User::SUB_ACTIVE)
                ->selectRaw('plan_amount, COUNT(*) as total')
                ->groupBy('plan_amount')
                ->pluck('total', 'plan_amount'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:1000000', Rule::unique('subscription_plans', 'amount')],
            'cards' => 'required|integer|min:1|max:500',
            'name' => 'nullable|string|max:60',
            'description' => 'nullable|string|max:160',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ], [
            'amount.unique' => 'A plan at that price already exists — edit it instead.',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? (SubscriptionPlan::max('sort_order') + 1);

        SubscriptionPlan::create($data);
        SubscriptionPlans::flush();

        return back()->with('success', 'Plan added — clients will see it on the plan screen.');
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $sold = $this->soldCount($plan);

        $rules = [
            'cards' => 'required|integer|min:1|max:500',
            'name' => 'nullable|string|max:60',
            'description' => 'nullable|string|max:160',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ];

        // Repricing a plan nobody has bought is harmless; repricing one that
        // has been sold would rewrite history, because every past row points
        // at this plan by its amount.
        if ($sold === 0) {
            $rules['amount'] = ['required', 'integer', 'min:1', 'max:1000000',
                Rule::unique('subscription_plans', 'amount')->ignore($plan->id)];
        }

        $data = $request->validate($rules, [
            'amount.unique' => 'Another plan already uses that price.',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        if ($sold > 0) {
            unset($data['amount']);
        }

        $plan->update($data);
        SubscriptionPlans::flush();

        return back()->with('success', $sold > 0
            ? 'Plan updated. Its price is locked because it has already been purchased — deactivate it and add a new plan to change the price.'
            : 'Plan updated.');
    }

    /** Hide a plan from clients without breaking the rows that point at it. */
    public function toggle(SubscriptionPlan $plan)
    {
        $plan->update(['is_active' => ! $plan->is_active]);
        SubscriptionPlans::flush();

        return back()->with('success', $plan->is_active
            ? 'Plan is now visible to clients.'
            : 'Plan hidden from clients. Existing subscribers keep what they bought.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        if ($this->soldCount($plan) > 0) {
            return back()->with('error',
                'This plan has already been purchased, so it cannot be deleted — '
                . 'deactivate it instead and it will disappear from the client plan screen.');
        }

        $plan->delete();
        SubscriptionPlans::flush();

        return back()->with('success', 'Plan deleted.');
    }

    /** Approved purchases of this plan — what makes it historical. */
    private function soldCount(SubscriptionPlan $plan): int
    {
        return SubscriptionRequest::where('plan_amount', $plan->amount)
            ->where('status', SubscriptionRequest::APPROVED)
            ->count();
    }
}
