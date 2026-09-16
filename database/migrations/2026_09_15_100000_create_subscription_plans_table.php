<?php

use App\Support\SubscriptionPlans;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The plan catalogue moves out of a PHP constant and into a table the
     * Super Admin owns, so prices and card counts can change without a
     * deploy.
     *
     * `amount` is unique and stays the historical key: users and subscription
     * requests already store `plan_amount`, so an old row keeps resolving to
     * its plan even after the plan is hidden.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount')->unique();   // PKR — also the historical key
            $table->unsignedInteger('cards');
            $table->string('name')->nullable();            // optional headline, e.g. "Starter"
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);   // hidden plans stay resolvable
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        // Carry the three hard-coded plans over so nothing changes for
        // clients mid-flight.
        $now = now();
        $seed = [199 => 1, 399 => 3, 599 => 6];
        $rows = [];
        $i = 0;
        foreach ($seed as $amount => $cards) {
            $rows[] = [
                'amount' => $amount,
                'cards' => $cards,
                'is_active' => true,
                'sort_order' => $i++,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('subscription_plans')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
