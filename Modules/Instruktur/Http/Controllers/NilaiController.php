<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InstrukturKursusLevel;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NilaiController extends Controller
{
    /**
     * Komponen nilai kursus. Kolom warisan uktp/ukap/var1..4 sengaja tidak ikut:
     * di sisi admin keempat kolom var divalidasi sebagai teks bebas sementara
     * di sini sebagai angka 0–100, dan tidak satu pun dipakai saat menghitung
     * rata-rata atau menyusun ekspor. Kolomnya dibiarkan utuh di basis data
     * untuk nilai tes penempatan yang memang masih memakainya.
     */
    private const KOMPONEN = [
        'listening' => 'Listening',
        'speaking' => 'Speaking',
        'reading' => 'Reading',
        'writing' => 'Writing',
        'assignment' => 'Tugas',
    ];

    public function export(Kursus $kursus): StreamedResponse
    {
        $this->pastikanDitugaskan($kursus->id);

        $pendaftarans = $kursus->pendaftarans()
            ->with('peserta.user', 'courseScore')
            ->get();

        $namaBerkas = 'nilai_'.str_replace(' ', '_', strtolower($kursus->nama)).'_'.now()->format('Ymd').'.csv';

        // Dulu seluruh CSV dirangkai jadi satu string dengan sprintf berisi
        // tanda kutip manual — nama peserta yang mengandung kutip akan merusak
        // kolomnya. fputcsv menangani pelolosan itu sendiri.
        return response()->streamDownload(function () use ($pendaftarans) {
            $keluaran = fopen('php://output', 'w');

            // Penanda BOM supaya Excel membaca berkas ini sebagai UTF-8.
            fwrite($keluaran, "\xEF\xBB\xBF");
            fputcsv($keluaran, array_merge(
                ['Nomor Pendaftaran', 'Nama Peserta'],
                array_values(self::KOMPONEN),
                ['Nilai Akhir', 'Keterangan']
            ));

            foreach ($pendaftarans as $p) {
                $nilai = $p->courseScore;
                $baris = [$p->nomor, optional(optional($p->peserta)->user)->name];

                foreach (array_keys(self::KOMPONEN) as $kolom) {
                    $baris[] = $nilai->{$kolom} ?? '';
                }

                $baris[] = $nilai->final_score ?? '';
                $baris[] = $nilai ? ($nilai->isLulus() ? 'Lulus' : 'Belum lulus') : 'Belum dinilai';

                fputcsv($keluaran, $baris);
            }

            fclose($keluaran);
        }, $namaBerkas, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function index(Request $request, Kursus $kursus)
    {
        $this->pastikanDitugaskan($kursus->id);

        $query = $kursus->pendaftarans()->with('peserta.user', 'courseScore');

        if ($cari = $request->get('search')) {
            $query->whereHas('peserta.user', fn ($q) => $q->where('name', 'like', "%{$cari}%"));
        }

        if ($saring = $request->get('filter')) {
            if ($saring === 'lulus') {
                $query->whereHas('courseScore', fn ($q) => $q->where('final_score', '>=', Score::NILAI_LULUS));
            } elseif ($saring === 'tidak_lulus') {
                $query->whereHas('courseScore', fn ($q) => $q->where('final_score', '<', Score::NILAI_LULUS));
            } elseif ($saring === 'belum') {
                $query->whereDoesntHave('courseScore');
            }
        }

        $pendaftarans = $query->get();

        return view('instruktur::instruktur.nilai.index', [
            'kursus' => $kursus,
            'pendaftarans' => $pendaftarans,
            'komponen' => self::KOMPONEN,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request, [
            'pendaftaran_id' => ['required', 'exists:pendaftarans,id'],
        ]);

        $pendaftaran = Pendaftaran::findOrFail($data['pendaftaran_id']);
        $this->pastikanDitugaskan($pendaftaran->kursus_id);

        $data['jenis'] = Score::TYPE_COURSE;
        $data['evaluated_by'] = Auth::user()->instruktur->id;
        $data['evaluated_at'] = now();
        $data = $this->lengkapiNilaiAkhir($data);

        Score::updateOrCreate(
            [
                'pendaftaran_id' => $pendaftaran->id,
                'jenis' => Score::TYPE_COURSE,
            ],
            $data
        );

        return redirect()
            ->route('instruktur.nilai.index', $pendaftaran->kursus_id)
            ->with('success', 'Nilai berhasil disimpan');
    }

    public function show(Score $nilai)
    {
        $this->pastikanBolehMengelola($nilai);

        return response()->json($nilai->only(array_merge(
            ['id', 'pendaftaran_id', 'final_score', 'keterangan'],
            array_keys(self::KOMPONEN)
        )));
    }

    public function update(Request $request, Score $nilai)
    {
        $this->pastikanBolehMengelola($nilai);

        $data = $this->lengkapiNilaiAkhir($this->validasi($request));
        $data['jenis'] = Score::TYPE_COURSE;
        $nilai->update($data);

        return redirect()
            ->route('instruktur.nilai.index', $nilai->pendaftaran->kursus_id)
            ->with('success', 'Nilai berhasil diperbarui');
    }

    public function destroy(Score $nilai)
    {
        $this->pastikanBolehMengelola($nilai);

        $kursusId = $nilai->pendaftaran->kursus_id;
        $nilai->delete();

        return redirect()
            ->route('instruktur.nilai.index', $kursusId)
            ->with('success', 'Nilai berhasil dihapus');
    }

    private function validasi(Request $request, array $tambahan = []): array
    {
        $aturan = $tambahan;

        foreach (array_keys(self::KOMPONEN) as $kolom) {
            $aturan[$kolom] = ['nullable', 'numeric', 'min:0', 'max:100'];
        }

        $aturan['keterangan'] = ['nullable', 'string', 'max:1000'];

        return $request->validate($aturan);
    }

    /**
     * Nilai akhir hanya dihitung ulang bila ada komponen yang terisi, sehingga
     * baris yang seluruh komponennya dikosongkan tidak menyimpan angka lama
     * yang menyesatkan.
     */
    private function lengkapiNilaiAkhir(array $data): array
    {
        $data['final_score'] = Score::hitungRataRata(
            array_map(fn ($kolom) => $data[$kolom] ?? null, array_keys(self::KOMPONEN))
        );

        $data['status'] = is_null($data['final_score'])
            ? 'pending'
            : ($data['final_score'] >= Score::NILAI_LULUS ? 'pass' : 'fail');

        return $data;
    }

    /**
     * Wewenang dulu diukur dari `evaluated_by === instruktur->id`, sehingga
     * instruktur tidak bisa menyentuh nilai kelasnya sendiri kalau baris itu
     * diisi admin atau rekan pengajarnya — dan modal ubah di halaman nilai
     * gagal diam-diam dengan 403. Batas yang benar adalah penugasan kelas,
     * sama seperti yang dipakai index().
     */
    private function pastikanBolehMengelola(Score $nilai): void
    {
        abort_unless($nilai->jenis === Score::TYPE_COURSE, 404);
        $this->pastikanDitugaskan(optional($nilai->pendaftaran)->kursus_id);
    }

    private function pastikanDitugaskan(?int $kursusId): void
    {
        $instruktur = Auth::user()->instruktur;

        $boleh = $instruktur
            && $kursusId
            && InstrukturKursusLevel::where('instruktur_id', $instruktur->id)
                ->where('kursus_id', $kursusId)
                ->exists();

        abort_unless($boleh, 403);
    }
}
