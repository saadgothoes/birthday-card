<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * One account the client can send money to. The Super Admin owns this list;
 * the client only ever reads it.
 */
#[Fillable([
    'type',
    'label',
    'account_name',
    'account_number',
    'bank_name',
    'branch_code',
    'instructions',
    'qr_image_path',
    'is_active',
    'sort_order',
])]
class PaymentMethod extends Model
{
    /** type => [label, icon] shown wherever a method is listed. */
    public const TYPES = [
        'jazzcash'  => ['JazzCash', '📱'],
        'easypaisa' => ['EasyPaisa', '💚'],
        'bank'      => ['Bank Account', '🏦'],
        'payoneer'  => ['Payoneer', '🌐'],
        'nayapay'   => ['NayaPay', '💠'],
        'sadapay'   => ['SadaPay', '💳'],
        'other'     => ['Other', '💰'],
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function subscriptionRequests()
    {
        return $this->hasMany(SubscriptionRequest::class);
    }

    /** Only active methods, in the order the admin arranged them. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type][0] ?? 'Other';
    }

    public function typeIcon(): string
    {
        return self::TYPES[$this->type][1] ?? '💰';
    }

    /** Bank transfers need the bank's name alongside the number; wallets do not. */
    public function isBank(): bool
    {
        return $this->type === 'bank';
    }
}
