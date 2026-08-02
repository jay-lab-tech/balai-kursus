<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InstrukturKursusLevel;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Risalah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RisalahController extends Controller
{
    public function index(Kursus $kursus)
    {
        // Dulu tidak ada pemeriksaan sama sekali di sini, padahal edit dan
        // update sudah dijaga: instruktur mana pun bisa membuka daftar risalah
        // kelas orang lain hanya dengan menebak id di alamat.
        if (! $this->ditugaskanKe($kursus->id)) {
            abort(403);
        }

        $risalahs = $kursus->risalahs()
            ->withCount('absensis as jumlah_absensi')
            ->latest()
            ->get();

        return view('instruktur::instruktur.risalah.index', compact('kursus', 'risalahs'));
    }

    public function edit(Risalah $risalah)
    {
        if (! $this->canManage($risalah)) {
            abort(403);
        }

        return view('instruktur::instruktur.risalah.edit', compact('risalah'));
    }

    public function update(Request $request, Risalah $risalah)
    {
        if (! $this->canManage($risalah)) {
            abort(403);
        }

        $request->validate([
            'materi' => 'required|string',
            'catatan' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only(['materi', 'catatan']);
        if ($request->hasFile('dokumen')) {
            $data['dokumen'] = $request->file('dokumen')->store('public/risalah');
        }
        $risalah->update($data);

        return redirect()
            ->route('instruktur.risalah.index', $risalah->kursus_id)
            ->with('success', 'Risalah diperbarui');
    }

    public function download(Risalah $risalah)
    {
        // Rute ini terbuka untuk semua peran yang login dan sebelumnya tidak
        // memeriksa apa pun: siapa saja yang punya akun bisa mengunduh dokumen
        // kelas mana saja dengan menebak id.
        if (! $this->bolehMengunduh($risalah)) {
            abort(403);
        }

        if (! $risalah->dokumen || ! Storage::exists($risalah->dokumen)) {
            abort(404, 'Dokumen tidak ditemukan');
        }

        $kursusNama = $risalah->kursus ? $risalah->kursus->nama : 'Kursus';
        $ext = pathinfo($risalah->dokumen, PATHINFO_EXTENSION);
        $nama = str_replace(' ', '_', $kursusNama).'_Pertemuan_'.($risalah->pertemuan_ke ?? '').($ext ? '.'.$ext : '');

        return Storage::download($risalah->dokumen, $nama);
    }

    private function canManage(Risalah $risalah): bool
    {
        return $this->ditugaskanKe($risalah->kursus_id);
    }

    private function ditugaskanKe(int $kursusId): bool
    {
        $instruktur = auth()->user()->instruktur;

        return $instruktur
            && InstrukturKursusLevel::where('instruktur_id', $instruktur->id)
                ->where('kursus_id', $kursusId)
                ->exists();
    }

    /**
     * Admin boleh mengunduh apa pun, instruktur hanya kelas yang ditugaskan
     * padanya, dan peserta hanya kelas yang ia ikuti.
     */
    private function bolehMengunduh(Risalah $risalah): bool
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'instruktur') {
            return $this->ditugaskanKe($risalah->kursus_id);
        }

        $peserta = $user->peserta;

        return $peserta && Pendaftaran::where('peserta_id', $peserta->id)
            ->where('kursus_id', $risalah->kursus_id)
            ->exists();
    }
}
