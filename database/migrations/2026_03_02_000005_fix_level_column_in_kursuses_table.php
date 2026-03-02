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
            if (!Schema::hasColumn('kursuses', 'level_id')) {
                $table->unsignedBigInteger('level_id')->nullable()->after('program_id');
                $table->foreign('level_id')->references('id')->on('levels')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursuses', function (Blueprint $table) {
            if (Schema::hasColumn('kursuses', 'level_id')) {
                $table->dropForeign(['level_id']);
                $table->dropColumn('level_id');
            }
        });
    }
};
