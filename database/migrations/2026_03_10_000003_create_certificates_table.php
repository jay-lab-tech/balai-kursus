<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_name');
            $table->string('certificate_image_path');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('participant_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('kursuses')->onDelete('cascade');
            $table->foreign('participant_id')->references('id')->on('pesertas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificates');
    }
};
