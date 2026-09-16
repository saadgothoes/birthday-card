<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * One purchasable plan. The Super Admin owns this list; clients only read it.
 *
 * `amount` doubles as the plan's identity everywhere else in the system —
 * `users.plan_amount` and `subscription_requests.plan_amount` both store it —
 * so it is unique and a plan is retired by deactivating it, never by changing
 * its price out from under the rows that point at it.
 */
#[Fillable([
    'amount',
    'cards',
    'name',
    'description',
    'is_active',
    'sort_order',
])]
class SubscriptionPlan extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'amount' => 'integer',
            'cards' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('amount');
    }

    /** How many clients are currently sitting on this plan. */
    public function subscriberCount(): int
    {
        return User::where('plan_amount', $this->amount)
            ->where('subscription_status', User::SUB_ACTIVE)
            ->count();
    }

    /** Approved purchases of this plan, ever. */
    public function purchaseCount(): int
    {
        return SubscriptionRequest::where('plan_amount', $this->amount)
            ->where('status', SubscriptionRequest::APPROVED)
            ->count();
    }

    public function label(): string
    {
        return 'Rs ' . number_format($this->amount) . ' — '
            . $this->cards . ' ' . ($this->cards === 1 ? 'Card' : 'Cards');
    }
}
