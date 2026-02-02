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

        $pendaftarans = Pendaftaran::with('kursus','pembayarans')
            ->where('peserta_id', $user->peserta->id)
            ->get();

        return view('peserta.riwayat.index', compact('pendaftarans'));
    }
}
