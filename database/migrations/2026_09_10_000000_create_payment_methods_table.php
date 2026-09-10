<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The accounts a client can send money to. The Super Admin maintains this
     * list, and it is what the client is shown when they pick a plan — so a
     * changed account number reaches every client immediately instead of
     * being hard-coded into a view.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);              // jazzcash | easypaisa | bank | payoneer | other
            $table->string('label');                 // what the client sees, e.g. "JazzCash (Personal)"
            $table->string('account_name');          // title of the account
            $table->string('account_number');        // number / IBAN / email
            $table->string('bank_name')->nullable(); // only meaningful for bank transfers
            $table->string('branch_code', 40)->nullable();
            $table->text('instructions')->nullable();
            $table->string('qr_image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
