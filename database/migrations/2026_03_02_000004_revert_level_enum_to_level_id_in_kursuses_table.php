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
            if (Schema::hasColumn('kursuses', 'level')) {
                $table->dropColumn('level');
            }
            // Tidak perlu menambah level_id lagi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursuses', function (Blueprint $table) {
            if (! Schema::hasColumn('kursuses', 'level')) {
                $table->enum('level', ['Beginner', 'Elementary', 'Intermediate', 'Upper Intermediate', 'Advanced'])->after('program_id');
            }
            // Tidak perlu drop level_id
        });
    }
};
