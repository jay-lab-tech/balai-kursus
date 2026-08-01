<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuang sisa skema yang tidak dipakai kode mana pun:
 *
 * - kursuses.instruktur_id_2: kolom instruktur kedua yang tidak pernah dibaca
 *   atau ditulis. Penugasan instruktur sekarang lewat instruktur_kursus_levels.
 * - peserta_kursus: pivot lama yang digantikan peserta_kursus_levels. Hanya
 *   punya migrasi dan seeder, tidak ada model maupun relasi yang memakainya.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('kursuses', 'instruktur_id_2')) {
            Schema::table('kursuses', function (Blueprint $table) {
                $table->dropForeign(['instruktur_id_2']);
                $table->dropColumn('instruktur_id_2');
            });
        }

        Schema::dropIfExists('peserta_kursus');
    }

    public function down(): void
    {
        if (! Schema::hasColumn('kursuses', 'instruktur_id_2')) {
            Schema::table('kursuses', function (Blueprint $table) {
                $table->foreignId('instruktur_id_2')->nullable()->constrained('instrukturs')->after('instruktur_id');
            });
        }

        if (! Schema::hasTable('peserta_kursus')) {
            Schema::create('peserta_kursus', function (Blueprint $table) {
                $table->id();
                $table->foreignId('peserta_id')->constrained('pesertas')->onDelete('cascade');
                $table->foreignId('kursus_id')->constrained('kursuses')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }
};
