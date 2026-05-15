<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'disabled'])->default('active')->after('plain_password');
            $table->date('subscription_start_date')->nullable()->after('status');
            $table->decimal('subscription_fee', 10, 2)->default(0)->after('subscription_start_date');
            $table->decimal('default_subscription_fee', 10, 2)->default(0)->after('subscription_fee');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'subscription_start_date', 'subscription_fee', 'default_subscription_fee']);
        });
    }
};
