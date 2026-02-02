<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KursusController extends Controller
{
    public function index()
    {
        $kursus = \App\Models\Kursus::with('program', 'level', 'instruktur')->get();
        return view('peserta.kursus.index', compact('kursus'));
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

            if ($kursus->pendaftaran()->count() >= $kursus->kuota) {
                return back()->with('error', 'Kuota Penuh');
            }
        });

        return redirect('/peserta/dashboard')
            ->with('success', 'Berhasil daftar, silakan bayar DP');
    }
}
