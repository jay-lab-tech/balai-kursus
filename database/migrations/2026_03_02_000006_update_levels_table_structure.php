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
        Schema::table('levels', function (Blueprint $table) {
            // Drop only if column exists
            if (Schema::hasColumn('levels', 'program_id')) {
                try {
                    $table->dropForeign(['program_id']);
                } catch (\Exception $e) {
                }
                $table->dropColumn('program_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            // This will add the 'program_id' column and its foreign key constraint back.
            // It assumes the 'programs' table exists.
            $table->foreignId('program_id')->after('id')->constrained()->cascadeOnDelete();
        });
    }
};
