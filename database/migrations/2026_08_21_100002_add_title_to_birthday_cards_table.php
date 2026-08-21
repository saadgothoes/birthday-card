<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Recent/Drafts lists need something to call each card by, and each
     * card now needs to be addressable on its own rather than "the client's
     * latest unpublished one".
     */
    public function up(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->string('title')->nullable()->after('user_id');
            $table->timestamp('last_opened_at')->nullable()->after('is_published');
            $table->index(['user_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::table('birthday_cards', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_published']);
            $table->dropColumn(['title', 'last_opened_at']);
        });
    }
};
