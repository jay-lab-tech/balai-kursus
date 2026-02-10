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
        // Pendaftarans table indexes
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->index('peserta_id');
            $table->index('kursus_id');
            $table->index('status_pembayaran');
            $table->index(['peserta_id', 'kursus_id']); // Composite index
            $table->index('created_at');
        });

        // Jadwals table indexes
        Schema::table('jadwals', function (Blueprint $table) {
            $table->index('kursus_id');
            $table->index('created_by');
            $table->index('tgl_pertemuan');
            $table->index(['kursus_id', 'tgl_pertemuan']);
        });

        // Scores table indexes
        Schema::table('scores', function (Blueprint $table) {
            $table->index('pendaftaran_id');
            $table->index('evaluated_by');
            $table->index('status');
            $table->index(['pendaftaran_id', 'status']);
            $table->index('evaluated_at');
        });

        // Risalahs table indexes
        Schema::table('risalahs', function (Blueprint $table) {
            $table->index('jadwal_id');
            $table->index('created_at');
        });

        // Absensis table indexes (add status index in addition to existing unique)
        Schema::table('absensis', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });

        // Instrukturs table indexes
        Schema::table('instrukturs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
        });

        // Pesertas table indexes
        Schema::table('pesertas', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
        });

        // Kursuses table indexes
        Schema::table('kursuses', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('level_id');
            $table->index('instruktur_id');
            $table->index('instruktur_id_2');
            $table->index('status');
            $table->index(['program_id', 'level_id']);
            $table->index('created_at');
        });

        // Levels table indexes
        Schema::table('levels', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('created_at');
        });

        // Programs table indexes
        Schema::table('programs', function (Blueprint $table) {
            $table->index('created_at');
        });

        // Pembayarans table indexes
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->index('pendaftaran_id');
            $table->index('status');
            $table->index(['pendaftaran_id', 'status']);
            $table->index('created_at');
        });

        // Lokasis table indexes
        Schema::table('lokasis', function (Blueprint $table) {
            $table->index('kota');
            $table->index('created_at');
        });

        // Kelas table indexes
        Schema::table('kelas', function (Blueprint $table) {
            $table->index('created_at');
        });

        // Haris table indexes
        Schema::table('haris', function (Blueprint $table) {
            $table->index('urutan');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropIndex(['peserta_id']);
            $table->dropIndex(['kursus_id']);
            $table->dropIndex(['status_pembayaran']);
            $table->dropIndex(['peserta_id', 'kursus_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropIndex(['kursus_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['tgl_pertemuan']);
            $table->dropIndex(['kursus_id', 'tgl_pertemuan']);
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->dropIndex(['pendaftaran_id']);
            $table->dropIndex(['evaluated_by']);
            $table->dropIndex(['status']);
            $table->dropIndex(['pendaftaran_id', 'status']);
            $table->dropIndex(['evaluated_at']);
        });

        Schema::table('risalahs', function (Blueprint $table) {
            $table->dropIndex(['jadwal_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('instrukturs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('pesertas', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('kursuses', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['level_id']);
            $table->dropIndex(['instruktur_id']);
            $table->dropIndex(['instruktur_id_2']);
            $table->dropIndex(['status']);
            $table->dropIndex(['program_id', 'level_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('levels', function (Blueprint $table) {
            $table->dropIndex(['program_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropIndex(['pendaftaran_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['pendaftaran_id', 'status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('lokasis', function (Blueprint $table) {
            $table->dropIndex(['kota']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('haris', function (Blueprint $table) {
            $table->dropIndex(['urutan']);
            $table->dropIndex(['created_at']);
        });
    }
};
