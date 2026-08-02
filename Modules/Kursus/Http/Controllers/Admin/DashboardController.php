<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kursus;
use App\Models\Payment;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Dua hitungan ini sebelumnya dicache satu jam. Keduanya cuma COUNT
        // pada tabel kecil, sementara efek sampingnya besar: peserta yang baru
        // ditambahkan tidak muncul di dashboard sampai cache kedaluwarsa.
        $totalPeserta = User::where('role', 'peserta')->count();
        $totalKursus = Kursus::count();

        // Angka pemasukan dibatasi 30 hari terakhir supaya terbaca sebagai
        // denyut terkini, bukan akumulasi sepanjang masa. Label di tampilan
        // menyebutkan rentang ini secara eksplisit.
        $totalPemasukan = Payment::where('status', 'success')
            ->whereDate('created_at', '>=', now()->subMonth())
            ->sum('amount');

        // Dua antrean kerja yang paling sering butuh tindakan admin.
        $menungguTes = Pendaftaran::whereIn('status_pendaftaran', [
            Pendaftaran::STATUS_MENUNGGU_TES,
            Pendaftaran::STATUS_MENUNGGU_PENEMPATAN,
        ])->count();

        $menungguPembayaran = Pendaftaran::where('status_pendaftaran', Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN)->count();

        $grafik = Payment::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 'success')
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            // Sumbu grafik sebelumnya menampilkan angka bulan mentah (1, 2, 3);
            // diterjemahkan di sini supaya terbaca sebagai nama bulan.
            ->map(fn ($baris) => [
                'bulan' => Carbon::create(null, (int) $baris->bulan)->translatedFormat('F'),
                'total' => (float) $baris->total,
            ]);

        return view('kursus::admin.dashboard.index', compact(
            'totalPeserta',
            'totalKursus',
            'totalPemasukan',
            'menungguTes',
            'menungguPembayaran',
            'grafik'
        ));
    }
}
