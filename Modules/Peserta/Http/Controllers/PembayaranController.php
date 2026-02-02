<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1000',
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);

        if ($pendaftaran->peserta_id != auth()->user()->peserta->id) {
            abort(403);
        }

        // Cek apakah sudah lunas
        if ($pendaftaran->isLunas()) {
            return back()->with('error', 'Pembayaran sudah lunas, tidak bisa membayar lagi');
        }

        // Cek apakah ada pembayaran yang masih pending
        if ($pendaftaran->pembayarans()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Masih ada pembayaran yang di pending');
        }

        // Cek apakah jumlah pembayaran tidak melebihi sisa
        $sisa = $pendaftaran->sisa();
        if ($request->jumlah > $sisa) {
            return back()->with('error', "Jumlah pembayaran tidak boleh melebihi sisa Rp " . number_format($sisa));
        }

        $path = $request->file('bukti')->store('bukti', 'public');

        $angsuran = $pendaftaran->pembayarans()->count() + 1;

        Pembayaran::create([
            'pendaftaran_id' => $id,
            'angsuran_ke' => $angsuran,
            'jumlah' => $request->jumlah,
            'bukti_path' => $path,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Menunggu verifikasi admin');
    }
}
