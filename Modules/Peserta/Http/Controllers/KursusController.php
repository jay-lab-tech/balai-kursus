<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class KursusController extends Controller
{
    public function index()
    {
        return redirect()->route('peserta.program.index');
    }

    public function kursusSaya()
    {
        $peserta = Auth::user()->peserta;

        if (!$peserta) {
            abort(403, 'Bukan peserta');
        }

        $pendaftarans = Pendaftaran::with(['program', 'level', 'kursus.program', 'kursus.level'])
            ->where('peserta_id', $peserta->id)
            ->whereNotNull('kursus_id')
            ->get();

        return view('peserta::kursus.kursus-saya', compact('pendaftarans'));
    }

    public function showDetail(Kursus $kursus)
    {
        $peserta = Auth::user()->peserta;

        if (!$peserta) {
            abort(403, 'Bukan peserta');
        }

        $pendaftaran = Pendaftaran::with(['program', 'level', 'kursus.program', 'kursus.level'])
            ->where('peserta_id', $peserta->id)
            ->where('kursus_id', $kursus->id)
            ->first();

        if (!$pendaftaran) {
            abort(403, 'Anda tidak terdaftar di kelas ini');
        }

        $levelPeserta = $pendaftaran->level?->nama;

        $instrukturPivot = null;
        if ($pendaftaran->level_id) {
            $instrukturPivot = \App\Models\InstrukturKursusLevel::where('kursus_id', $kursus->id)
                ->where('level_id', $pendaftaran->level_id)
                ->with('instruktur')
                ->first();
        }

        $instrukturPeserta = $instrukturPivot?->instruktur?->nama_instr;
        $risalahs = $kursus->risalahs()->orderBy('pertemuan_ke')->get();

        return view('peserta::kursus.detail', compact('kursus', 'risalahs', 'pendaftaran', 'levelPeserta', 'instrukturPeserta'));
    }

    public function show(Kursus $kursus)
    {
        $kursus->load('program', 'level', 'jadwals');

        return view('peserta::kursus.show', compact('kursus'));
    }

    public function showRisalah(Kursus $kursus)
    {
        $peserta = Auth::user()->peserta;

        if (!$peserta) {
            abort(403, 'Bukan peserta');
        }

        $pendaftaran = Pendaftaran::where('peserta_id', $peserta->id)
            ->where('kursus_id', $kursus->id)
            ->first();

        if (!$pendaftaran) {
            abort(403, 'Anda tidak terdaftar di kelas ini');
        }

        $query = $kursus->risalahs()->latest('pertemuan_ke');
        if ($search = request('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('materi', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        $risalahs = $query->get();

        return view('peserta::kursus.risalah', compact('kursus', 'risalahs'));
    }

    public function daftar(Kursus $kursus)
    {
        return redirect()->route('peserta.program.show', $kursus->program_id)
            ->with('error', 'Pendaftaran langsung ke kelas dinonaktifkan. Silakan daftar ke program terlebih dahulu.');
    }
}
