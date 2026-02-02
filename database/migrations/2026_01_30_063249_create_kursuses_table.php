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
        Schema::create('kursuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->foreignId('instruktur_id')->constrained();
            $table->string('nama');
            $table->integer('harga');
            $table->integer('kuota');
            $table->enum('status', ['buka', 'tutup', 'berjalan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kursuses');
    }
};
