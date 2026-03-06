<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $peserta = $user->peserta;

            if (!$peserta) {
                // Jika tidak ada profil peserta, tampilkan halaman kosong tanpa redirect
                $pendaftarans = collect();
                return view('peserta::pendaftaran.index', compact('pendaftarans'));
            }

            $pendaftarans = Pendaftaran::with('kursus', 'pembayarans')
                ->where('peserta_id', $peserta->id)
                ->get();

            return view('peserta::pendaftaran.index', compact('pendaftarans'));
        } catch (\Exception $e) {
            \Log::error('PendaftaranController Error: ' . $e->getMessage());
            return redirect('/peserta/dashboard')->with('error', 'Terjadi kesalahan, coba lagi nanti.');
        }
    }
}
