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
        Schema::table('birthday_cards', function (Blueprint $table) {
            // Step 3 — welcome screen heading (welcome_message already exists)
            $table->string('heading')->nullable()->after('variant');

            // Step 4 — which gift-box screen design was chosen
            $table->unsignedTinyInteger('gift_screen_variant')->nullable()->after('gifts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->dropColumn(['heading', 'gift_screen_variant']);
        });
    }
};
