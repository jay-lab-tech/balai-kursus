<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvancedFieldsToCertificatesTable extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->nullable()->change();
            $table->timestamp('expires_at')->nullable()->after('issued_at');
            $table->integer('validity_days')->nullable()->after('expires_at');
            $table->string('signature_type')->default('none')->after('meta');
            $table->string('signature_path')->nullable()->after('signature_type');
        });
    }

    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['expires_at', 'validity_days', 'signature_type', 'signature_path']);
        });
    }
}
