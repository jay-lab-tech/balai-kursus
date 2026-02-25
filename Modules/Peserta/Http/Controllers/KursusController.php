<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\Risalah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KursusController extends Controller
{
    public function index()
    {
        $kursus = \App\Models\Kursus::with('program', 'level', 'instruktur')->get();
        return view('peserta::kursus.index', compact('kursus'));
    }

    public function kursusSaya()
    {
        $peserta = Auth::user()->peserta;
        
        if (!$peserta) {
            abort(403, 'Bukan peserta');
        }

        // Get kursus yang diikuti peserta
        $pendaftarans = Pendaftaran::with('kursus.program', 'kursus.level', 'kursus.instruktur')
            ->where('peserta_id', $peserta->id)
            ->get();

        return view('peserta::kursus.kursus-saya', compact('pendaftarans'));
    }

    public function showDetail(Kursus $kursus)
    {
        $peserta = Auth::user()->peserta;
        
        if (!$peserta) {
            abort(403, 'Bukan peserta');
        }

        $pendaftaran = Pendaftaran::where('peserta_id', $peserta->id)
            ->where('kursus_id', $kursus->id)
            ->first();

        if (!$pendaftaran) {
            abort(403, 'Anda tidak terdaftar di kursus ini');
        }

        // Get pertemuan (risalah) untuk kursus ini
        $risalahs = $kursus->risalahs()->orderBy('pertemuan_ke')->get();

        return view('peserta::kursus.detail', compact('kursus', 'risalahs', 'pendaftaran'));
    }

    public function show(\App\Models\Kursus $kursus)
    {
        $kursus->load('program','level','instruktur','jadwals');
        return view('peserta::kursus.show', compact('kursus'));
    }

    public function showRisalah(Kursus $kursus)
    {
        // Check if user is registered in this course
        $peserta = Auth::user()->peserta;
        
        if (!$peserta) {
            abort(403, 'Bukan peserta');
        }

        $pendaftaran = Pendaftaran::where('peserta_id', $peserta->id)
            ->where('kursus_id', $kursus->id)
            ->first();

        if (!$pendaftaran) {
            abort(403, 'Anda tidak terdaftar di kursus ini');
        }

        $query = $kursus->risalahs()->latest('pertemuan_ke');
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('materi', 'like', "%$search%")
                  ->orWhere('catatan', 'like', "%$search%");
            });
        }
        $risalahs = $query->get();
        return view('peserta::kursus.risalah', compact('kursus', 'risalahs'));
    }

    public function daftar(Kursus $kursus)
    {
        $peserta = Auth::user()->peserta;

        if (!$peserta) {
            abort(403, 'Bukan peserta');
        }

        if (Pendaftaran::where('peserta_id', $peserta->id)
            ->where('kursus_id', $kursus->id)->exists()
        ) {
            return back()->with('error', 'Sudah terdaftar di kursus ini');
        }

        DB::transaction(function () use ($peserta, $kursus) {

            $pendaftaran = Pendaftaran::create([
                'peserta_id' => $peserta->id,
                'kursus_id' => $kursus->id,
                'status_pembayaran' => 'dp',
                'total_bayar' => $kursus->harga,
                'terbayar' => 0
            ]);

            $dp = $kursus->harga * 0.3;

            Pembayaran::create([
                'pendaftaran_id' => $pendaftaran->id,
                'angsuran_ke' => 1,
                'jumlah' => $dp,
                'status' => 'pending'
            ]);

            if ($kursus->pendaftarans()->count() >= $kursus->kuota) {
                return back()->with('error', 'Kuota Penuh');
            }
        });

        return redirect('/peserta/dashboard')
            ->with('success', 'Berhasil daftar, silakan bayar DP');
    }
}
