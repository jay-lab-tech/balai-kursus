<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Peserta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class UserCertificateController extends Controller
{
    public function index()
    {
        $peserta = $this->pesertaSaya();

        $certificates = $peserta
            // course dipakai di setiap kartu; tanpa eager load satu daftar
            // sertifikat memicu satu kueri kursus per baris.
            ? Certificate::with('course.program')
                ->where('participant_id', $peserta->id)
                ->where('status', Certificate::STATUS_PUBLISHED)
                ->latest()
                ->get()
            : collect();

        return view('profile.certificates', compact('certificates'));
    }

    public function download($id)
    {
        $certificate = $this->sertifikatSaya($id, ['course.program', 'template', 'participant']);

        $pdf = Pdf::loadView('user.certificates.pdf', [
            'certificate' => $certificate,
            'participant' => $certificate->participant,
            'course' => $certificate->course,
            'template' => $certificate->template,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat-'.($certificate->serial_number ?: $certificate->id).'.pdf');
    }

    public function detail($id)
    {
        return view('profile.certificate-detail', [
            'certificate' => $this->sertifikatSaya($id, ['course.program']),
        ]);
    }

    private function pesertaSaya(): ?Peserta
    {
        return Peserta::where('user_id', Auth::id())->first();
    }

    /**
     * Ambil satu sertifikat milik peserta yang sedang masuk.
     *
     * Dulu id peserta dilewatkan apa adanya, dan bila akun belum punya baris
     * peserta nilainya null — Eloquent mengubah where('participant_id', null)
     * menjadi "participant_id IS NULL". Kolom itu NOT NULL sehingga tidak ada
     * baris yang cocok, jadi hasilnya kebetulan tetap 404. Ketiadaan peserta
     * ditutup di sini supaya izinnya tidak bergantung pada kebetulan skema.
     */
    private function sertifikatSaya($id, array $muat = []): Certificate
    {
        $peserta = $this->pesertaSaya();

        abort_if($peserta === null, 404);

        return Certificate::with($muat)
            ->where('id', $id)
            ->where('participant_id', $peserta->id)
            ->where('status', Certificate::STATUS_PUBLISHED)
            ->firstOrFail();
    }
}
