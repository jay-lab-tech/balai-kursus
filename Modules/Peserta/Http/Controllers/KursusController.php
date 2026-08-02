<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InstrukturKursusLevel;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

/**
 * Peserta tidak pernah memilih kelas sendiri: ia mendaftar ke program, ikut
 * tes penempatan, lalu admin yang menempatkannya. Sisa alur lama — katalog
 * kelas, halaman show, dan aksi daftar langsung yang hanya membalas pesan
 * "dinonaktifkan" — sudah dibuang beserta rute dan view-nya.
 */
class KursusController extends Controller
{
    public function kursusSaya()
    {
        $pendaftarans = Pendaftaran::query()
            ->with(['program', 'level', 'kursus.program', 'kursus.level'])
            ->where('peserta_id', $this->pesertaAktif()->id)
            ->whereNotNull('kursus_id')
            ->latest('id')
            ->get();

        return view('peserta::kursus.kursus-saya', compact('pendaftarans'));
    }

    public function showDetail(Kursus $kursus)
    {
        $pendaftaran = $this->pendaftaranDiKelas($kursus);

        $kursus->load('program', 'level');

        $risalahs = $kursus->risalahs()
            ->withCount('absensis as jumlah_hadir')
            ->orderByDesc('pertemuan_ke')
            ->get();

        return view('peserta::kursus.detail', [
            'kursus' => $kursus,
            'pendaftaran' => $pendaftaran,
            'risalahs' => $risalahs,
            'instrukturs' => $this->instrukturKelas($kursus, $pendaftaran),
        ]);
    }

    public function showRisalah(Kursus $kursus)
    {
        $this->pendaftaranDiKelas($kursus);

        $kursus->load('program', 'level');

        $risalahs = $kursus->risalahs()
            ->withCount('absensis as jumlah_hadir')
            ->when(request('search'), function ($query, $cari) {
                $query->where(function ($builder) use ($cari) {
                    $builder->where('materi', 'like', "%{$cari}%")
                        ->orWhere('catatan', 'like', "%{$cari}%");
                });
            })
            ->orderByDesc('pertemuan_ke')
            ->get();

        return view('peserta::kursus.risalah', compact('kursus', 'risalahs'));
    }

    /**
     * Satu level bisa diampu lebih dari satu instruktur. Dulu hanya baris
     * pivot pertama yang dibaca, sehingga rekan pengajar tidak pernah muncul.
     * Nama diambil dari users.name lebih dulu — instrukturs.nama_instr
     * menyalin kolom itu dan bisa tertinggal saat pengguna ganti nama.
     */
    private function instrukturKelas(Kursus $kursus, Pendaftaran $pendaftaran)
    {
        if (! $pendaftaran->level_id) {
            return collect();
        }

        return InstrukturKursusLevel::query()
            ->where('kursus_id', $kursus->id)
            ->where('level_id', $pendaftaran->level_id)
            ->with('instruktur.user')
            ->get()
            ->map(fn ($baris) => optional(optional($baris->instruktur)->user)->name
                ?: optional($baris->instruktur)->nama_instr)
            ->filter()
            ->unique()
            ->values();
    }

    private function pesertaAktif()
    {
        $peserta = Auth::user()->peserta;

        abort_if(! $peserta, 403, 'Akun ini belum memiliki profil peserta.');

        return $peserta;
    }

    private function pendaftaranDiKelas(Kursus $kursus): Pendaftaran
    {
        $pendaftaran = Pendaftaran::query()
            ->with(['program', 'level'])
            ->where('peserta_id', $this->pesertaAktif()->id)
            ->where('kursus_id', $kursus->id)
            ->latest('id')
            ->first();

        abort_if(! $pendaftaran, 403, 'Anda tidak terdaftar di kelas ini.');

        return $pendaftaran;
    }
}
