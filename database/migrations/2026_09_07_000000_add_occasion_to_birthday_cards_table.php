<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The card builder now begins with an occasion choice — "birthday" (the
     * existing boy/girl flow, unchanged) or "anniversary" (its own theme set).
     * Stored alongside `theme` so a reopened draft skips the picker and the
     * birthday flow is never touched.
     *
     * Guarded with hasColumn so re-running against a database where an earlier
     * attempt already added it is a no-op.
     */
    public function up(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            if (! Schema::hasColumn('birthday_cards', 'occasion')) {
                $table->string('occasion')->nullable()->after('theme');
            }
        });
    }

    public function down(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            if (Schema::hasColumn('birthday_cards', 'occasion')) {
                $table->dropColumn('occasion');
            }
        });
    }
};
