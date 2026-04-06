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
        Schema::create('peserta_kursus_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->onDelete('cascade');
            $table->foreignId('kursus_id')->constrained('kursuses')->onDelete('cascade');
            $table->foreignId('level_id')->nullable()->constrained('levels')->onDelete('set null');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
            $table->unique(['peserta_id', 'kursus_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_kursus_levels');
    }
};
