<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Step 8 (Ending Page) and Step 9 (QR Select) each store one JSON blob,
     * following the same shape the gift steps already use.
     *
     * `ending_variant` / `ending_message` are dropped here: they are orphan
     * columns left behind by a reverted attempt at this feature — no migration
     * ever created them, nothing reads them, and every row has them null.
     * `ending_data` replaces them, because the ending page has seven text slots,
     * not one.
     */
    public function up(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            if (! Schema::hasColumn('birthday_cards', 'ending_data')) {
                $table->json('ending_data')->nullable()->after('gift3_data');
            }
            if (! Schema::hasColumn('birthday_cards', 'qr_data')) {
                $table->json('qr_data')->nullable()->after('ending_data');
            }
        });

        Schema::table('birthday_cards', function (Blueprint $table) {
            foreach (['ending_variant', 'ending_message'] as $orphan) {
                if (Schema::hasColumn('birthday_cards', $orphan)) {
                    $table->dropColumn($orphan);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->dropColumn(['ending_data', 'qr_data']);
        });
    }
};
