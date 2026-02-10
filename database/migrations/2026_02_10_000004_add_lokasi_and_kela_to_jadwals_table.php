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
        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->after('kursus_id');
            $table->foreignId('kela_id')->nullable()->constrained('kelas')->after('lokasi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeignIdFor('kela');
            $table->dropForeignIdFor('lokasi');
        });
    }
};
