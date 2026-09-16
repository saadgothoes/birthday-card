<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Repair accounts left stranded by the top-up bug.
     *
     * Filing a second subscription request used to set `subscription_status`
     * to "pending" even for a client whose plan was already running. If that
     * request was then rejected — or simply never reviewed — the account was
     * left flagged pending with no pending request behind it, which reads
     * everywhere as "no plan": the card limit fell back to the free allowance
     * and the client lost access to cards they had paid for.
     *
     * The fix lives in CardManagerController@requestSubscription; this repairs
     * the rows it already damaged. Only accounts with no live request are
     * touched, so a genuinely pending client is left exactly as they are.
     */
    public function up(): void
    {
        $stuck = DB::table('users')
            ->where('subscription_status', 'pending')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('subscription_requests')
                    ->whereColumn('subscription_requests.user_id', 'users.id')
                    ->where('subscription_requests.status', 'pending');
            })
            ->get(['id']);

        foreach ($stuck as $user) {
            // What they actually bought, summed over every approved request —
            // the stored card_limit cannot be trusted here, because a bugged
            // approval may have overwritten it with just the last plan.
            $approved = DB::table('subscription_requests')
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->orderByDesc('reviewed_at')
                ->get();

            if ($approved->isEmpty()) {
                // Never bought anything — they are simply unsubscribed.
                DB::table('users')->where('id', $user->id)
                    ->update(['subscription_status' => 'none']);

                continue;
            }

            DB::table('users')->where('id', $user->id)->update([
                'subscription_status' => 'active',
                'plan_amount' => $approved->first()->plan_amount,
                'card_limit' => max(1, (int) $approved->sum('card_limit')),
            ]);
        }
    }

    public function down(): void
    {
        // A data repair: there is nothing meaningful to put back.
    }
};
