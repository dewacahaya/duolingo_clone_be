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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();

            $table->string('name');
            $table->string('description')->nullable(); // Deskripsi singkat unit
            $table->string('topic_keyword'); // PENTING untuk Prompt AI (misal: "Food", "Travel")

            $table->text('guide_md')->nullable(); // Materi grammar (Markdown) dari AI

            $table->integer('order_sequence')->default(0);
            $table->integer('total_levels')->default(3); // Berapa kali user harus main untuk tamat

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
