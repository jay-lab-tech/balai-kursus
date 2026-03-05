<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('no_sertifikat')->unique();
            $table->unsignedBigInteger('peserta_id')->nullable();
            $table->unsignedBigInteger('kursus_id')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->string('file_path')->nullable();
            $table->string('verification_code')->index();
            $table->enum('status', ['queued', 'generated', 'revoked'])->default('queued');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificates');
    }
}
