<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            if (! Schema::hasColumn('levels', 'urutan')) {
                $table->unsignedInteger('urutan')->default(1)->after('warna');
            }

            if (! Schema::hasColumn('levels', 'nilai_min')) {
                $table->decimal('nilai_min', 5, 2)->nullable()->after('urutan');
            }

            if (! Schema::hasColumn('levels', 'nilai_max')) {
                $table->decimal('nilai_max', 5, 2)->nullable()->after('nilai_min');
            }

            if (! Schema::hasColumn('levels', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('nilai_max');
            }
        });

        $levels = DB::table('levels')->orderBy('id')->get();
        $defaultRanges = [
            [0, 39.99],
            [40, 54.99],
            [55, 69.99],
            [70, 84.99],
            [85, 100],
        ];

        foreach ($levels as $index => $level) {
            $range = $defaultRanges[$index] ?? [85, 100];

            DB::table('levels')
                ->where('id', $level->id)
                ->update([
                    'urutan' => $index + 1,
                    'nilai_min' => $level->nilai_min ?? $range[0],
                    'nilai_max' => $level->nilai_max ?? $range[1],
                ]);
        }

        Schema::table('scores', function (Blueprint $table) {
            if (! Schema::hasColumn('scores', 'jenis')) {
                $table->string('jenis', 24)->default('course')->after('pendaftaran_id');
                $table->index('jenis');
            }
        });

        Schema::table('pendaftarans', function (Blueprint $table) {
            if (! Schema::hasColumn('pendaftarans', 'program_id')) {
                $table->foreignId('program_id')->nullable()->after('peserta_id')->constrained('programs')->nullOnDelete();
            }

            if (! Schema::hasColumn('pendaftarans', 'level_id')) {
                $table->foreignId('level_id')->nullable()->after('program_id')->constrained('levels')->nullOnDelete();
            }

            if (! Schema::hasColumn('pendaftarans', 'status_pendaftaran')) {
                $table->string('status_pendaftaran', 32)->default('menunggu_tes')->after('kursus_id');
            }

            if (! Schema::hasColumn('pendaftarans', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('terbayar');
            }

            if (! Schema::hasColumn('pendaftarans', 'diklasifikasikan_at')) {
                $table->timestamp('diklasifikasikan_at')->nullable()->after('catatan_admin');
            }
        });

        DB::table('pendaftarans')
            ->join('kursuses', 'pendaftarans.kursus_id', '=', 'kursuses.id')
            ->whereNull('pendaftarans.program_id')
            ->update([
                'pendaftarans.program_id' => DB::raw('kursuses.program_id'),
            ]);

        DB::table('pendaftarans')
            ->join('kursuses', 'pendaftarans.kursus_id', '=', 'kursuses.id')
            ->whereNull('pendaftarans.level_id')
            ->update([
                'pendaftarans.level_id' => DB::raw('kursuses.level_id'),
            ]);

        DB::table('pendaftarans')
            ->whereNull('status_pendaftaran')
            ->update([
                'status_pendaftaran' => 'menunggu_tes',
            ]);

        if ($this->isMySql() && Schema::hasColumn('pendaftarans', 'kursus_id')) {
            DB::statement('ALTER TABLE `pendaftarans` DROP FOREIGN KEY `pendaftarans_kursus_id_foreign`');
            DB::statement('ALTER TABLE `pendaftarans` MODIFY `kursus_id` BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE `pendaftarans` ADD CONSTRAINT `pendaftarans_kursus_id_foreign` FOREIGN KEY (`kursus_id`) REFERENCES `kursuses`(`id`) ON DELETE SET NULL');
        }
    }

    public function down(): void
    {
        if ($this->isMySql() && Schema::hasColumn('pendaftarans', 'kursus_id')) {
            DB::statement('ALTER TABLE `pendaftarans` DROP FOREIGN KEY `pendaftarans_kursus_id_foreign`');
            DB::statement('ALTER TABLE `pendaftarans` MODIFY `kursus_id` BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE `pendaftarans` ADD CONSTRAINT `pendaftarans_kursus_id_foreign` FOREIGN KEY (`kursus_id`) REFERENCES `kursuses`(`id`)');
        }

        Schema::table('pendaftarans', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftarans', 'diklasifikasikan_at')) {
                $table->dropColumn('diklasifikasikan_at');
            }

            if (Schema::hasColumn('pendaftarans', 'catatan_admin')) {
                $table->dropColumn('catatan_admin');
            }

            if (Schema::hasColumn('pendaftarans', 'status_pendaftaran')) {
                $table->dropColumn('status_pendaftaran');
            }

            if (Schema::hasColumn('pendaftarans', 'level_id')) {
                $table->dropConstrainedForeignId('level_id');
            }

            if (Schema::hasColumn('pendaftarans', 'program_id')) {
                $table->dropConstrainedForeignId('program_id');
            }
        });

        Schema::table('scores', function (Blueprint $table) {
            if (Schema::hasColumn('scores', 'jenis')) {
                $table->dropIndex(['jenis']);
                $table->dropColumn('jenis');
            }
        });

        Schema::table('levels', function (Blueprint $table) {
            foreach (['deskripsi', 'nilai_max', 'nilai_min', 'urutan'] as $column) {
                if (Schema::hasColumn('levels', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function isMySql(): bool
    {
        return Schema::getConnection()->getDriverName() === 'mysql';
    }
};
