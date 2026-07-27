<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kursus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Use cache untuk dashboard stats (cache 1 jam)
        $totalPeserta = \Illuminate\Support\Facades\Cache::remember('total_peserta', 3600, function () {
            return User::where('role', 'peserta')->count();
        });

        $totalKursus = \Illuminate\Support\Facades\Cache::remember('total_kursus', 3600, function () {
            return Kursus::count();
        });

        $totalPemasukan = Payment::where('status', 'success')
            ->whereDate('created_at', '>=', now()->subMonth())
            ->sum('amount');

        $grafik = Payment::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 'success')
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
