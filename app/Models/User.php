<?php

namespace App\Models;

use App\Support\SubscriptionPlans;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'city', 'age', 'plain_password', 'password_changed', 'status', 'subscription_start_date', 'subscription_fee', 'default_subscription_fee', 'bg_owner_pin', 'subscription_status', 'plan_amount', 'card_limit', 'subscription_activated_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const SUB_NONE = 'none';
    public const SUB_PENDING = 'pending';
    public const SUB_ACTIVE = 'active';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'subscription_start_date' => 'date',
            'subscription_fee' => 'decimal:2',
            'default_subscription_fee' => 'decimal:2',
            'subscription_activated_at' => 'datetime',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function hasChangedPassword(): bool
    {
        return $this->password_changed;
    }

    public function birthdayCards()
    {
        return $this->hasMany(BirthdayCard::class);
    }

    public function subscriptionRequests()
    {
        return $this->hasMany(SubscriptionRequest::class)->latest();
    }

    // ─── Subscription ────────────────────────────────────────────

    /** Only an approved plan unlocks QR generation. */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === self::SUB_ACTIVE;
    }

    /** A request already filed and still waiting on the Super Admin. */
    public function pendingSubscriptionRequest(): ?SubscriptionRequest
    {
        return $this->subscriptionRequests()
            ->where('status', SubscriptionRequest::PENDING)
            ->first();
    }

    /**
     * Cards this account may own. Without an approved plan an account still
     * gets the free allowance, so a new client can build a card all the way
     * to the QR step — which is where the subscription is actually required.
     *
     * The stored `card_limit` is the source of truth rather than the plan's
     * headline number, because a top-up adds its cards to what the account
     * already has instead of replacing the earlier plan.
     */
    public function cardLimit(): int
    {
        if (! $this->hasActiveSubscription()) {
            return SubscriptionPlans::FREE_CARD_LIMIT;
        }

        return max(
            (int) $this->card_limit,
            SubscriptionPlans::cardsFor($this->plan_amount)
        );
    }

    /** Every card on the plan is used up — nothing left to build with. */
    public function hasExhaustedPlan(): bool
    {
        return $this->cardsRemaining() === 0;
    }

    /**
     * The account is out of cards and needs a top-up. Distinct from simply
     * being unsubscribed: these clients paid, used what they bought, and now
     * need to be told to renew rather than to subscribe.
     */
    public function needsTopUp(): bool
    {
        return $this->hasActiveSubscription() && $this->hasExhaustedPlan();
    }

    public function cardsUsed(): int
    {
        return $this->birthdayCards()->count();
    }

    public function cardsRemaining(): int
    {
        return max(0, $this->cardLimit() - $this->cardsUsed());
    }

    public function canCreateCard(): bool
    {
        return $this->cardsRemaining() > 0;
    }

    /** Human label for the current plan, for dashboards and admin tables. */
    public function planLabel(): string
    {
        if (! $this->hasActiveSubscription()) {
            return 'No Plan';
        }

        return SubscriptionPlans::label($this->plan_amount);
    }

    // ─── Devices / logins ────────────────────────────────────────

    /**
     * Live logins for this account, read straight off the database session
     * store. This is every device/browser the account is currently signed in
     * on — it is what the session driver can tell us, so it is what the Super
     * Admin sees.
     */
    public function activeSessions()
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return DB::table(config('session.table', 'sessions'))
            ->where('user_id', $this->id)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'device' => self::describeDevice($session->user_agent),
                    'last_activity' => $session->last_activity
                        ? \Illuminate\Support\Carbon::createFromTimestamp($session->last_activity)
                        : null,
                ];
            });
    }

    public function activeSessionCount(): int
    {
        return $this->activeSessions()->count();
    }

    /**
     * Best-effort browser + platform from a user agent string. No external
     * parser is installed, so this covers the common cases and falls back to
     * "Unknown device" rather than showing a raw UA to the admin.
     */
    public static function describeDevice(?string $agent): string
    {
        if (! $agent) {
            return 'Unknown device';
        }

        $browser = 'Unknown browser';
        foreach ([
            'Edg' => 'Edge',
            'OPR' => 'Opera',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Firefox' => 'Firefox',
        ] as $needle => $label) {
            if (str_contains($agent, $needle)) {
                $browser = $label;
                break;
            }
        }

        $platform = 'Unknown OS';
        foreach ([
            'Android' => 'Android',
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'Windows' => 'Windows',
            'Macintosh' => 'macOS',
            'Linux' => 'Linux',
        ] as $needle => $label) {
            if (str_contains($agent, $needle)) {
                $platform = $label;
                break;
            }
        }

        return $browser . ' on ' . $platform;
    }
}
