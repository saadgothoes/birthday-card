<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'plan_amount',
    'card_limit',
    'payment_method_id',
    'sender_name',
    'sender_number',
    'transaction_id',
    'payment_screenshot_path',
    'client_note',
    'status',
    'reviewed_by',
    'reviewed_at',
    'admin_note',
])]
class SubscriptionRequest extends Model
{
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /** Public URL of the transfer screenshot, or null when none was attached. */
    public function screenshotUrl(): ?string
    {
        return $this->payment_screenshot_path
            ? asset('storage/' . $this->payment_screenshot_path)
            : null;
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }
}
