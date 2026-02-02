<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Kursus;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPeserta = User::where('role', 'peserta')->count();
        $totalKursus = \App\Models\Kursus::count();
        $totalPemasukan = Pembayaran::where('status', 'verified')->sum('jumlah');

        $grafik = Pembayaran::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(jumlah) as total')
        )
            ->where('status', 'verified')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalPeserta',
            'totalKursus',
            'totalPemasukan',
            'grafik'
        ));
    }
}
