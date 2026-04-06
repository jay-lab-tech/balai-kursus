<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;

class DashboardController extends Controller
{
    public function index()
    {
        $peserta = auth()->user()->peserta;

        $pendaftarans = collect();
        if ($peserta) {
            $pendaftarans = Pendaftaran::with(['program', 'level', 'kursus', 'payments', 'placementScore'])
                ->where('peserta_id', $peserta->id)
                ->latest('id')
                ->get();
        }

        return view('peserta::dashboard.index', compact('pendaftarans'));
    }
}
