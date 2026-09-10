<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A request now carries the client's proof of payment: which account they
     * sent to, the number they sent from, and a screenshot of the transfer.
     * All nullable — requests filed before payment was collected still have to
     * load, and the Super Admin can still approve one by hand.
     */
    public function up(): void
    {
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('card_limit')
                ->constrained('payment_methods')->nullOnDelete();
            $table->string('sender_name')->nullable()->after('payment_method_id');
            $table->string('sender_number')->nullable()->after('sender_name');
            $table->string('transaction_id')->nullable()->after('sender_number');
            $table->string('payment_screenshot_path')->nullable()->after('transaction_id');
            $table->string('client_note')->nullable()->after('payment_screenshot_path');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn([
                'payment_method_id',
                'sender_name',
                'sender_number',
                'transaction_id',
                'payment_screenshot_path',
                'client_note',
            ]);
        });
    }
};
