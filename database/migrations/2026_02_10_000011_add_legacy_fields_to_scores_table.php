<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->integer('uktp')->nullable()->after('assignment');
            $table->integer('ukap')->nullable()->after('uktp');
            $table->string('var1')->nullable()->after('ukap');
            $table->string('var2')->nullable()->after('var1');
            $table->string('var3')->nullable()->after('var2');
            $table->string('var4')->nullable()->after('var3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn(['uktp', 'ukap', 'var1', 'var2', 'var3', 'var4']);
        });
    }
};
