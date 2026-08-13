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
            // Step 5 — Gift 1 (theme choice 1-4 + uploaded photos)
            $table->json('gift1_data')->nullable()->after('gift_screen_variant');

            // Step 6 — Gift 2 (theme choice 1-4 + photos + names + date + note)
            $table->json('gift2_data')->nullable()->after('gift1_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->dropColumn(['gift1_data', 'gift2_data']);
        });
    }
};
