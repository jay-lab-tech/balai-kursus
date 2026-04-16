<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->after('id')->constrained('certificate_templates')->nullOnDelete();
            $table->string('certificate_number')->nullable()->after('certificate_name');
            $table->string('serial_number')->nullable()->after('certificate_number');
            $table->date('issued_date')->nullable()->after('serial_number');
            $table->string('status')->default('draft')->change();
            $table->string('pdf_path')->nullable()->after('certificate_image_path');
            $table->string('participant_name_snapshot')->nullable()->after('user_id');
            $table->string('program_name_snapshot')->nullable()->after('participant_name_snapshot');
            $table->string('course_name_snapshot')->nullable()->after('program_name_snapshot');
            $table->unsignedInteger('hours_snapshot')->nullable()->after('course_name_snapshot');
            $table->date('start_date_snapshot')->nullable()->after('hours_snapshot');
            $table->date('end_date_snapshot')->nullable()->after('start_date_snapshot');
            $table->string('signer_name_snapshot')->nullable()->after('end_date_snapshot');
            $table->string('signer_title_snapshot')->nullable()->after('signer_name_snapshot');
            $table->string('signer_nip_snapshot')->nullable()->after('signer_title_snapshot');
            $table->string('city_snapshot')->nullable()->after('signer_nip_snapshot');
        });

        DB::table('certificates')->where('status', 'pending')->update(['status' => 'draft']);
    }

    public function down(): void
    {
        DB::table('certificates')->where('status', 'draft')->update(['status' => 'pending']);

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('template_id');
            $table->dropColumn([
                'certificate_number',
                'serial_number',
                'issued_date',
                'pdf_path',
                'participant_name_snapshot',
                'program_name_snapshot',
                'course_name_snapshot',
                'hours_snapshot',
                'start_date_snapshot',
                'end_date_snapshot',
                'signer_name_snapshot',
                'signer_title_snapshot',
                'signer_nip_snapshot',
                'city_snapshot',
            ]);
            $table->string('status')->default('pending')->change();
        });
    }
};
