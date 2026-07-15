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
        Schema::create('birthday_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Step 1 — theme
            $table->enum('theme', ['boy', 'girl'])->nullable();
            $table->unsignedTinyInteger('variant')->nullable();

            // Step 2 — lock code
            $table->date('dob')->nullable();
            $table->string('lock_code', 20)->nullable();
            $table->string('lock_hint')->nullable();

            // Step 3 — welcome screen
            $table->string('recipient_name')->nullable();
            $table->string('sender_name')->nullable();
            $table->text('welcome_message')->nullable();
            $table->string('profile_image_path')->nullable();

            // Step 4 — gift sections (flexible JSON per gift)
            $table->json('gifts')->nullable();

            // Wizard progress + share link
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->string('slug')->nullable()->unique();
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_cards');
    }
};
