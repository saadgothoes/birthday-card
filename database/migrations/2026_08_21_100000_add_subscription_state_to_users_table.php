<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Clients now sign themselves up, so an account starts with no plan at
     * all. `subscription_status` is the single source of truth for whether
     * the QR step is unlocked; `card_limit` is denormalised off the approved
     * plan so a limit change never has to walk the plan table.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('subscription_status', 20)->default('none')->after('subscription_fee');
            $table->unsignedInteger('plan_amount')->nullable()->after('subscription_status');
            $table->unsignedInteger('card_limit')->default(1)->after('plan_amount');
            $table->timestamp('subscription_activated_at')->nullable()->after('card_limit');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'plan_amount',
                'card_limit',
                'subscription_activated_at',
            ]);
        });
    }
};
