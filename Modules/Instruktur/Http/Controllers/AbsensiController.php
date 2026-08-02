<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\InstrukturKursusLevel;
use App\Models\Jadwal;
use App\Models\Kursus;
use App\Models\Risalah;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $kursus = Kursus::query()
            ->whereIn('id', $this->kursusDitugaskan())
            ->with('program')
            ->withCount(['pendaftarans as peserta_count', 'risalahs as risalah_count'])
            ->orderBy('nama')
            ->get();

        return view('instruktur::instruktur.absensi.index', compact('kursus'));
    }

    public function show(Kursus $kursus)
    {
        $this->pastikanDitugaskan($kursus->id);

        // Jumlah absensi per pertemuan dulunya dihitung di dalam view dengan
        // `$r->absensis()->count()` — satu kueri per baris. Sekarang ikut
        // dalam kueri daftar risalah.
        $risalah = $kursus->risalahs()
            ->withCount('absensis as jumlah_absensi')
            ->latest()
            ->get();

        $kursus->loadCount('pendaftarans as jumlah_peserta');

        return view('instruktur::instruktur.absensi.show', compact('kursus', 'risalah'));
    }

    public function absensi(Risalah $risalah)
    {
        $this->pastikanDitugaskan($risalah->kursus_id);

        $pendaftaran = $risalah->kursus
            ->pendaftarans()
            ->with('peserta.user')
            ->get();

        // Status yang sudah tersimpan diambil sekali lalu dipetakan per
        // pendaftaran. Sebelumnya form memanggil
        // `$risalah->absensis()->where(...)->value('status')` di setiap baris.
        $statusTersimpan = $risalah->absensis()
            ->pluck('status', 'pendaftaran_id');

        return view('instruktur::instruktur.absensi.form', compact('risalah', 'pendaftaran', 'statusTersimpan'));
    }

    public function jadwal()
    {
        $jadwals = Jadwal::query()
            ->whereIn('kursus_id', $this->kursusDitugaskan())
            ->with(['kursus.program', 'lokasi', 'kela', 'hari'])
            ->orderBy('tgl_pertemuan')
            ->orderBy('jam_mulai')
            ->get();

        return view('instruktur::instruktur.jadwal.index', compact('jadwals'));
    }

    public function store(Request $request, Risalah $risalah)
    {
        $this->pastikanDitugaskan($risalah->kursus_id);

        $validated = $request->validate([
            'absen' => ['required', 'array'],
            'absen.*' => ['required', 'in:H,S,I,A'],
        ]);

        // Kunci array `absen` adalah pendaftaran_id yang datang dari form, dan
        // dulunya ditulis apa adanya. Tanpa pemeriksaan ini seorang instruktur
        // bisa mengirim id peserta kelas lain dan membuat baris absensi di
        // sana. Hanya peserta kelas milik risalah ini yang diterima.
        $pendaftaranSah = $risalah->kursus
            ->pendaftarans()
            ->pluck('id')
            ->all();

        $absen = array_intersect_key(
            $validated['absen'],
            array_flip($pendaftaranSah)
        );

        if (! $absen) {
            return back()->with('error', 'Tidak ada peserta yang cocok dengan kelas pertemuan ini.');
        }

        foreach ($absen as $pendaftaranId => $status) {
            Absensi::updateOrCreate(
                [
                    'risalah_id' => $risalah->id,
                    'pendaftaran_id' => $pendaftaranId,
                ],
                [
                    'status' => $status,
                    'jam_datang' => now(),
                ]
            );
        }

        return back()->with('success', 'Absensi tersimpan');
    }

    /**
     * Id kelas yang ditugaskan ke instruktur yang sedang masuk.
     */
    private function kursusDitugaskan()
    {
        return InstrukturKursusLevel::query()
            ->where('instruktur_id', optional(auth()->user()->instruktur)->id)
            ->pluck('kursus_id')
            ->unique();
    }

    private function pastikanDitugaskan(int $kursusId): void
    {
        if (! $this->kursusDitugaskan()->contains($kursusId)) {
            abort(403);
        }
    }
}
