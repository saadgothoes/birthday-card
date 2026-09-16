<?php

namespace App\Support;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Schema;

/**
 * The plan catalogue. One place decides what a plan costs and how many cards
 * it buys, so the dashboard, the request form, the approval screen and the
 * backend limit check can never drift apart.
 *
 * The plans themselves now live in the `subscription_plans` table, which the
 * Super Admin edits — this class is the read side of that table and keeps the
 * API the rest of the app already calls.
 *
 * Two rules make an editable catalogue safe for rows that already point at a
 * plan:
 *
 *  - A plan's `amount` is its identity. `users.plan_amount` and
 *    `subscription_requests.plan_amount` store it, so lookups by amount keep
 *    resolving after a plan is hidden.
 *  - Anything client-facing (`all()`, `amounts()`, `isValidAmount()`) is
 *    active-only, while `cardsFor()` and `label()` resolve *any* plan — an old
 *    approved request must still report what it bought.
 *
 * Payment is not integrated yet — the amounts are what the client is asking
 * to be put on, not something they have paid.
 */
class SubscriptionPlans
{
    /**
     * The catalogue as it was before it became editable. Used only when the
     * table is not there yet (a migration mid-flight, or a fresh checkout
     * whose database has not been migrated), so nothing fatals during boot.
     *
     * @var array<int,int>
     */
    private const FALLBACK_PLANS = [
        199 => 1,
        399 => 3,
        599 => 6,
    ];

    /**
     * Cards an account may build before it has any approved plan. One is
     * enough to walk the whole builder and reach the QR step, which is where
     * the subscription gate actually lives.
     */
    public const FREE_CARD_LIMIT = 1;

    /** Per-request memo, so one page render hits the table once. */
    private static ?array $cache = null;

    /** Forget the memo — for tests and straight after an admin edit. */
    public static function flush(): void
    {
        self::$cache = null;
    }

    /**
     * Every plan, active or not, keyed by amount.
     *
     * @return array<int,array{amount:int, cards:int, name:?string, description:?string, is_active:bool, sort_order:int}>
     */
    private static function catalogue(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (! Schema::hasTable('subscription_plans')) {
            $rows = [];
            $i = 0;
            foreach (self::FALLBACK_PLANS as $amount => $cards) {
                $rows[$amount] = [
                    'amount' => $amount,
                    'cards' => $cards,
                    'name' => null,
                    'description' => null,
                    'is_active' => true,
                    'sort_order' => $i++,
                ];
            }

            return self::$cache = $rows;
        }

        $rows = [];
        foreach (SubscriptionPlan::ordered()->get() as $plan) {
            $rows[(int) $plan->amount] = [
                'amount' => (int) $plan->amount,
                'cards' => (int) $plan->cards,
                'name' => $plan->name,
                'description' => $plan->description,
                'is_active' => (bool) $plan->is_active,
                'sort_order' => (int) $plan->sort_order,
            ];
        }

        return self::$cache = $rows;
    }

    /** The plans a client may actually choose right now. */
    private static function activeCatalogue(): array
    {
        return array_filter(self::catalogue(), fn ($plan) => $plan['is_active']);
    }

    /**
     * Amounts a client may pick — active plans only, since this is what the
     * request form validates against.
     *
     * @return list<int>
     */
    public static function amounts(): array
    {
        return array_values(array_keys(self::activeCatalogue()));
    }

    public static function isValidAmount(int|string|null $amount): bool
    {
        return $amount !== null && array_key_exists((int) $amount, self::activeCatalogue());
    }

    /**
     * Cards a given plan allows, or the free allowance for an unknown plan.
     *
     * Deliberately resolves hidden plans too: a request approved last month
     * has to keep reporting the cards it bought even if the plan has since
     * been retired.
     */
    public static function cardsFor(int|string|null $amount): int
    {
        return self::catalogue()[(int) $amount]['cards'] ?? self::FREE_CARD_LIMIT;
    }

    /** The plan's own name, when the admin gave it one. */
    public static function nameFor(int|string|null $amount): ?string
    {
        return self::catalogue()[(int) $amount]['name'] ?? null;
    }

    public static function label(int|string|null $amount): string
    {
        $cards = self::cardsFor($amount);
        $label = 'Rs ' . number_format((int) $amount) . ' — ' . $cards . ' ' . ($cards === 1 ? 'Card' : 'Cards');

        $name = self::nameFor($amount);

        return $name ? $name . ' (' . $label . ')' : $label;
    }

    /**
     * The picker list — active plans, in the admin's chosen order.
     *
     * @return list<array{amount:int, cards:int, name:?string, description:?string, label:string}>
     */
    public static function all(): array
    {
        $plans = [];
        foreach (self::activeCatalogue() as $plan) {
            $plans[] = [
                'amount' => $plan['amount'],
                'cards' => $plan['cards'],
                'name' => $plan['name'],
                'description' => $plan['description'],
                'label' => self::label($plan['amount']),
            ];
        }

        return $plans;
    }
}
