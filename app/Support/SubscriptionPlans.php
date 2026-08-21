<?php

namespace App\Support;

/**
 * The plan catalogue. One place decides what a plan costs and how many cards
 * it buys, so the dashboard, the request form, the approval screen and the
 * backend limit check can never drift apart.
 *
 * Payment is not integrated yet — the amounts are what the client is asking
 * to be put on, not something they have paid.
 */
class SubscriptionPlans
{
    /** amount (PKR) => cards the plan allows */
    public const PLANS = [
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

    /** @return list<int> */
    public static function amounts(): array
    {
        return array_keys(self::PLANS);
    }

    public static function isValidAmount(int|string|null $amount): bool
    {
        return $amount !== null && array_key_exists((int) $amount, self::PLANS);
    }

    /** Cards a given plan allows, or the free allowance for an unknown plan. */
    public static function cardsFor(int|string|null $amount): int
    {
        return self::PLANS[(int) $amount] ?? self::FREE_CARD_LIMIT;
    }

    public static function label(int|string|null $amount): string
    {
        $cards = self::cardsFor($amount);

        return 'Rs ' . number_format((int) $amount) . ' — ' . $cards . ' ' . ($cards === 1 ? 'Card' : 'Cards');
    }

    /** @return list<array{amount:int, cards:int, label:string}> */
    public static function all(): array
    {
        $plans = [];
        foreach (self::PLANS as $amount => $cards) {
            $plans[] = [
                'amount' => $amount,
                'cards' => $cards,
                'label' => self::label($amount),
            ];
        }

        return $plans;
    }
}
