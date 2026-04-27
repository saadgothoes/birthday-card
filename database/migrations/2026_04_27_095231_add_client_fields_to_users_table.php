<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('role');
            $table->string('city')->nullable()->after('phone');
            $table->integer('age')->nullable()->after('city');
            $table->string('plain_password')->nullable()->after('age'); // email mein bhejna ke liye
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'city', 'age', 'plain_password']);
        });
    }
};