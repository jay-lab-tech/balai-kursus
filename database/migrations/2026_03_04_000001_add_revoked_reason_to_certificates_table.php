<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevokedReasonToCertificatesTable extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->text('revoked_reason')->nullable()->after('status');
            $table->timestamp('revoked_at')->nullable()->after('revoked_reason');
            $table->unsignedBigInteger('revoked_by')->nullable()->after('revoked_at');
        });
    }

    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['revoked_reason', 'revoked_at', 'revoked_by']);
        });
    }
}
