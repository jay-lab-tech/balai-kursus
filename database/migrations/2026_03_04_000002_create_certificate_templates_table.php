<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kursus_id')->nullable();
            $table->string('name');
            $table->text('html_template');
            $table->string('signature_path')->nullable();
            $table->text('design_config')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('kursus_id')->references('id')->on('kursuses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_templates');
    }
}
