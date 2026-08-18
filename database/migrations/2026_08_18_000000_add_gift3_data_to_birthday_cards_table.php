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
        // Guarded: an earlier, since-reverted attempt at Gift 3 left this
        // column behind on existing databases while its migration file was
        // deleted, so the column can already be present here even though a
        // fresh install still needs it created.
        if (Schema::hasColumn('birthday_cards', 'gift3_data')) {
            return;
        }

        Schema::table('birthday_cards', function (Blueprint $table) {
            // Step 7 — Gift 3, the "Our Story" book
            // (theme choice 1-4 + 5 photos + every page's text)
            $table->json('gift3_data')->nullable()->after('gift2_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->dropColumn('gift3_data');
        });
    }
};
