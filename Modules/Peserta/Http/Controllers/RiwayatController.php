<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            // Jika tidak ada profil peserta, tampilkan halaman kosong tanpa redirect
            $pendaftarans = collect();
            return view('peserta::riwayat.index', compact('pendaftarans'));
        }

        $pendaftarans = Pendaftaran::with('kursus','pembayarans')
            ->where('peserta_id', $peserta->id)
            ->get();

        return view('peserta::riwayat.index', compact('pendaftarans'));
    }
}
