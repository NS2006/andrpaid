<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paper_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained('papers')->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->integer('year');
            $table->string('publication')->nullable();
            $table->string('url')->nullable();
            $table->json('key_points')->nullable(); 
            $table->boolean('is_analyzed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paper_references');
    }
};
