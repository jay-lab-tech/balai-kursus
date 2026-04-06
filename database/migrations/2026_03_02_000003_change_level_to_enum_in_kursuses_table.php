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
        Schema::table('kursuses', function (Blueprint $table) {
            // Kolom level_id sudah dihapus, cukup tambahkan enum jika perlu
            if (!Schema::hasColumn('kursuses', 'level')) {
                $table->enum('level', ['Dasar', 'Menengah', 'Lanjutan'])->after('program_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursuses', function (Blueprint $table) {
            if (Schema::hasColumn('kursuses', 'level')) {
                $table->dropColumn('level');
            }
            // Tidak perlu menambah level_id lagi
        });
    }
};
