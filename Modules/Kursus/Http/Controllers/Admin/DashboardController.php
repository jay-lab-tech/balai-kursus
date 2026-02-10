<?php

namespace Modules\Kursus\Http\Controllers\Admin;

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
        // Use cache untuk dashboard stats (cache 1 jam)
        $totalPeserta = \Illuminate\Support\Facades\Cache::remember('total_peserta', 3600, function() {
            return User::where('role', 'peserta')->count();
        });
        
        $totalKursus = \Illuminate\Support\Facades\Cache::remember('total_kursus', 3600, function() {
            return \App\Models\Kursus::count();
        });
        
        $totalPemasukan = Pembayaran::where('status', 'verified')
            ->whereDate('created_at', '>=', now()->subMonth())
            ->sum('jumlah');

        $grafik = Pembayaran::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(jumlah) as total')
        )
            ->where('status', 'verified')
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('kursus::admin.dashboard.index', compact(
            'totalPeserta',
            'totalKursus',
            'totalPemasukan',
            'grafik'
        ));
    }
}
