<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $peserta = auth()->user()->peserta;

        $pendaftarans = \App\Models\Pendaftaran::with('kursus')
            ->where('peserta_id', $peserta->id)
            ->get();
        
        return view('peserta.dashboard.index', compact('pendaftarans'));
    }
}
