<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::where('status', 'pending')
            ->with('pendaftaran.peserta.user')
            ->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verifikasi($id)
    {
        $p = Pembayaran::findOrFail($id);
        $p->status = 'verified';
        $p->save();

        $pendaftaran = $p->pendaftaran;
        $pendaftaran->terbayar += $p->jumlah;

        if ($pendaftaran->terbayar >= $pendaftaran->total_bayar) {
            $pendaftaran->status_pembayaran = 'lunas';
        }

        $pendaftaran->save();

        return back();
    }
}
