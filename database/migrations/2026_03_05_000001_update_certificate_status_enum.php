<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateCertificateStatusEnum extends Migration
{
    public function up()
    {
        // First, update any existing 'queued' status to 'generated'
        DB::table('certificates')->where('status', 'queued')->update(['status' => 'generated']);
        
        // Change enum to include new statuses: 'applied', 'rejected'
        // Old: queued, generated, revoked
        // New: generated, applied, rejected, revoked
        Schema::table('certificates', function (Blueprint $table) {
            $table->enum('status', ['generated', 'applied', 'rejected', 'revoked'])->default('generated')->change();
        });
    }

    public function down()
    {
        // Update 'generated' back to 'queued' for rollback
        DB::table('certificates')->where('status', 'generated')->update(['status' => 'queued']);
        
        Schema::table('certificates', function (Blueprint $table) {
            $table->enum('status', ['queued', 'generated', 'revoked'])->default('queued')->change();
        });
    }
}

