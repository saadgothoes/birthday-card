<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Models\User;

/**
 * What the client base has actually paid.
 *
 * Income is summed from approved subscription requests rather than from
 * `users.subscription_fee`: that column only ever holds the *last* plan a
 * client bought, so a client who has topped up three times counted once.
 */
class PaymentController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')->latest()->get();

        $approved = SubscriptionRequest::where('status', SubscriptionRequest::APPROVED)->get();

        // Per-client totals, so the table can show how many times each one has
        // bought and what they have spent in all.
        $purchaseCounts = $approved->groupBy('user_id')->map->count();
        $spendTotals = $approved->groupBy('user_id')->map(fn ($rows) => $rows->sum('plan_amount'));

        return view('admin.payments.index', [
            'clients' => $clients,
            'totalPayments' => $approved->sum('plan_amount'),
            'todayPayments' => $approved->where('reviewed_at', '>=', today())->sum('plan_amount'),
            'weekPayments' => $approved->where('reviewed_at', '>=', now()->startOfWeek())->sum('plan_amount'),
            'approvedCount' => $approved->count(),
            // Clients who have come back and bought again — the number this
            // screen exists to make visible.
            'repeatBuyers' => $purchaseCounts->filter(fn ($n) => $n > 1)->count(),
            'purchaseCounts' => $purchaseCounts,
            'spendTotals' => $spendTotals,
        ]);
    }
}
