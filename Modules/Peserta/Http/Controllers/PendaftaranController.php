<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    /**
     * Metode ini dulu dibungkus try/catch yang menangkap Throwable apa pun,
     * mencatatnya lewat \Log tanpa import, lalu mengalihkan ke URL literal
     * dengan pesan "Terjadi kesalahan, coba lagi nanti". Satu-satunya
     * kegagalan yang benar-benar diperkirakan — akun tanpa profil peserta —
     * sudah ditangani tepat di bawah, jadi selebihnya hanya menyembunyikan
     * galat sungguhan di balik pesan yang tidak bisa ditindaklanjuti siapa pun.
     */
    public function index()
    {
        $peserta = Auth::user()->peserta;

        $pendaftarans = $peserta
            ? Pendaftaran::query()
                ->with(['program', 'level', 'kursus', 'placementScore', 'payments'])
                ->where('peserta_id', $peserta->id)
                ->latest('id')
                ->get()
            : collect();

        return view('peserta::pendaftaran.index', compact('pendaftarans'));
    }
}
