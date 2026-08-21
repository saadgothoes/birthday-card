<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->timestamp('link_expires_at')->nullable()->after('qr_data');
            $table->timestamp('link_disabled_at')->nullable()->after('link_expires_at');
        });

        DB::table('birthday_cards')
            ->where('is_published', true)
            ->whereNotNull('slug')
            ->update(['link_expires_at' => DB::raw('DATE_ADD(updated_at, INTERVAL 15 DAY)')]);
    }

    public function down(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->dropColumn(['link_expires_at', 'link_disabled_at']);
        });
    }
};