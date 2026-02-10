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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained()->cascadeOnDelete();
            $table->integer('listening')->nullable();
            $table->integer('speaking')->nullable();
            $table->integer('reading')->nullable();
            $table->integer('writing')->nullable();
            $table->integer('assignment')->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->enum('status', ['pass', 'fail', 'pending'])->default('pending');
            $table->foreignId('evaluated_by')->nullable()->constrained('instrukturs');
            $table->date('evaluated_at')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
