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
        Schema::create('risalahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kursus_id')->constrained();
            $table->foreignId('instruktur_id')->constrained();
            $table->integer('pertemuan_ke');
            $table->date('tgl_pertemuan');
            $table->string('materi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risalahs');
    }
};
