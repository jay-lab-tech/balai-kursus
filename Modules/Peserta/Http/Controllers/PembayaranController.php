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

        $path = $request->file('bukti')->store('bukti', 'public');

        $pendaftaran = Pendaftaran::findOrFail($id);

        if ($pendaftaran->peserta_id != auth()->user()->peserta->id) {
            abort(403);
        } 

        if ($pendaftaran->status == 'lunas') {
            return back()->with('error', 'Pembayaran sudah lunas');
        }

        if ($pendaftaran->pembayarans()->where('status', 'pending')->exists()) {
            return back()->with('error','Masih ada pembayaran yang di pending');
        }

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
