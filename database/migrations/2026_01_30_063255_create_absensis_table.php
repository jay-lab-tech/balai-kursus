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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risalah_id')->constrained();
            $table->foreignId('pendaftaran_id')->constrained();
            $table->enum('status', ['H', 'S', 'I', 'A']);
            $table->time('jam_datang')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['risalah_id', 'pendaftaran_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
