<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();

            $table->string('type');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');

            // Format JSON: { "question": "...", "options": [...], "correct_answer": "...", "audio_url": "..." }
            $table->json('content');

            $table->boolean('is_ai_generated')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
