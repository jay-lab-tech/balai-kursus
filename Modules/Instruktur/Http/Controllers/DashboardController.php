<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InstrukturKursusLevel;
use App\Models\Kursus;

class DashboardController extends Controller
{
    public function index()
    {
        // Seluruh perhitungan halaman ini dulunya berada di dalam view: satu
        // kueri penugasan, lalu `->pendaftarans()->count()` dan
        // `->risalahs()->count()` dipanggil sekali per kartu. Dua hitungan itu
        // sekarang diambil lewat withCount dalam satu kueri.
        $instruktur = auth()->user()->instruktur;

        $penugasan = InstrukturKursusLevel::query()
            ->where('instruktur_id', optional($instruktur)->id)
            ->with('level')
            ->get();

        $kursus = Kursus::query()
            ->whereIn('id', $penugasan->pluck('kursus_id')->unique())
            ->with('program')
            ->withCount(['pendaftarans as jumlah_peserta', 'risalahs as jumlah_pertemuan'])
            ->orderBy('nama')
            ->get();

        // Satu kelas bisa ditugaskan lebih dari sekali (level berbeda). Level
        // yang menempel pada tiap kelas dikumpulkan supaya kartu kelas bisa
        // menyebutkannya tanpa merender kelas yang sama dua kali.
        $levelPerKursus = $penugasan
            ->groupBy('kursus_id')
            ->map(fn ($baris) => $baris->pluck('level.nama')->filter()->unique()->values());

        return view('instruktur::instruktur.dashboard.index', [
            'kursus' => $kursus,
            'levelPerKursus' => $levelPerKursus,
            'jumlahPeserta' => $kursus->sum('jumlah_peserta'),
            'jumlahPertemuan' => $kursus->sum('jumlah_pertemuan'),
        ]);
    }
}
