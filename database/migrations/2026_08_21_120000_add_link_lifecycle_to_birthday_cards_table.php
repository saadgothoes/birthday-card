<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->timestamp('link_expires_at')->nullable()->after('qr_data');
            $table->timestamp('link_disabled_at')->nullable()->after('link_expires_at');
        });

        $cards = DB::table('birthday_cards')
            ->where('is_published', true)
            ->whereNotNull('slug')
            ->get(['id', 'updated_at']);

        foreach ($cards as $card) {
            $expiresAt = Carbon::parse($card->updated_at)->addDays(15);

            DB::table('birthday_cards')
                ->where('id', $card->id)
                ->update([
                    'link_expires_at' => $expiresAt,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->dropColumn([
                'link_expires_at',
                'link_disabled_at',
            ]);
        });
    }
};