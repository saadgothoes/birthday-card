<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('birthday_cards', 'music_data')) {
            Schema::table('birthday_cards', function (Blueprint $table) {
                $table->json('music_data')->nullable()->after('ending_data');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('birthday_cards', 'music_data')) {
            Schema::table('birthday_cards', function (Blueprint $table) {
                $table->dropColumn('music_data');
            });
        }
    }
};
