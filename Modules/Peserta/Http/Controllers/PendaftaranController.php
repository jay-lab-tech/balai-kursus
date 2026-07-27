<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        try {
            $peserta = Auth::user()->peserta;

            if (! $peserta) {
                $pendaftarans = collect();

                return view('peserta::pendaftaran.index', compact('pendaftarans'));
            }

            $pendaftarans = Pendaftaran::with(['program', 'level', 'kursus', 'placementScore', 'payments'])
                ->where('peserta_id', $peserta->id)
                ->latest('id')
                ->get();

            return view('peserta::pendaftaran.index', compact('pendaftarans'));
        } catch (\Throwable $exception) {
            \Log::error('PendaftaranController Error: '.$exception->getMessage());

            return redirect('/peserta/dashboard')->with('error', 'Terjadi kesalahan, coba lagi nanti.');
        }
    }
}
