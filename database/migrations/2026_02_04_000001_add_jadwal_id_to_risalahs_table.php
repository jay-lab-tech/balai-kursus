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
        Schema::table('risalahs', function (Blueprint $table) {
            $table->foreignId('jadwal_id')->nullable()->after('instruktur_id')->constrained('jadwals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risalahs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('jadwal_id');
        });
    }
};
