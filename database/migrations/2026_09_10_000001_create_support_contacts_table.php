<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Where a client is told to go when a payment does not go through. Kept in
     * the database next to the payment methods because the two are always
     * changed together — a new number means a new place to complain to.
     */
    public function up(): void
    {
        Schema::create('support_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('channel', 30);   // whatsapp | instagram | facebook | email | phone | telegram | other
            $table->string('label');
            $table->string('value');         // number, handle, or address
            $table->string('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_contacts');
    }
};
