<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('institution_name')->default('UNIVERSITAS PENDIDIKAN INDONESIA');
            $table->string('unit_name')->default('BALAI BAHASA');
            $table->string('city')->default('Bandung');
            $table->string('header_logo_path')->nullable();
            $table->string('background_image_path')->nullable();
            $table->string('signature_image_path')->nullable();
            $table->string('stamp_image_path')->nullable();
            $table->string('signer_name')->nullable();
            $table->string('signer_title')->nullable();
            $table->string('signer_nip')->nullable();
            $table->string('certificate_prefix')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        DB::table('certificate_templates')->insert([
            'name' => 'Template Sertifikat Resmi',
                'institution_name' => 'UNIVERSITAS PENDIDIKAN INDONESIA',
                'unit_name' => 'BALAI BAHASA',
                'city' => 'Bandung',
                'header_logo_path' => 'images/certificate/logo_upi_ttd.png',
                'background_image_path' => 'images/certificate/template.jpeg',
                'signature_image_path' => 'images/certificate/ttd.png',
                'stamp_image_path' => 'images/certificate/label_ttd.png',
            'signer_name' => 'Prof. Ika Lestari Damayanti, M.A., Ph.D.',
            'signer_title' => 'Kepala Balai Bahasa',
            'signer_nip' => '197709192001122001',
            'certificate_prefix' => 'UN40.J7/TA.05.00',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
