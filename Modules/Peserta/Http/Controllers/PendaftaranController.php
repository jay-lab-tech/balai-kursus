<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        $peserta = Auth::user()->peserta;

        $pendaftarans = $peserta->pendaftarans()
            ->with('kursus','pembayarans')
            ->get();

        return view('peserta.pendaftaran.index', compact('pendaftarans'));
    }
}
