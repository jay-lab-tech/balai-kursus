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
            // Get semua pendaftaran
            $pendaftarans = Pendaftaran::with('kursus', 'pembayarans')->get();

            return view('peserta.pendaftaran.index', compact('pendaftarans'));
        } catch (\Exception $e) {
            \Log::error('PendaftaranController Error: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
