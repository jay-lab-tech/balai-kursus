<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $peserta = Auth::user()->peserta;

        if (! $peserta) {
            $payments = collect();

            return view('peserta::riwayat.index', compact('payments'));
        }

        $payments = Payment::with(['pendaftaran.program', 'pendaftaran.kursus'])
            ->whereHas('pendaftaran', function ($query) use ($peserta) {
                $query->where('peserta_id', $peserta->id);
            })
            ->latest('id')
            ->get();

        return view('peserta::riwayat.index', compact('payments'));
    }
}
