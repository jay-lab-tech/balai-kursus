<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftarans', 'participant_email_snapshot')) {
                $table->string('participant_email_snapshot')->nullable()->after('peserta_id');
            }
        });

        $pendaftarans = DB::table('pendaftarans')
            ->leftJoin('pesertas', 'pendaftarans.peserta_id', '=', 'pesertas.id')
            ->leftJoin('users', 'pesertas.user_id', '=', 'users.id')
            ->whereNull('pendaftarans.participant_email_snapshot')
            ->select('pendaftarans.id', 'users.email')
            ->get();

        foreach ($pendaftarans as $pendaftaran) {
            if (!$pendaftaran->email) {
                continue;
            }

            DB::table('pendaftarans')
                ->where('id', $pendaftaran->id)
                ->update([
                    'participant_email_snapshot' => $pendaftaran->email,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftarans', 'participant_email_snapshot')) {
                $table->dropColumn('participant_email_snapshot');
            }
        });
    }
};
