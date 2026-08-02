<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Halaman profil dipakai bersama oleh ketiga peran. Yang membedakan hanya
     * kerangka di sekelilingnya, dan itu ditentukan di view lewat $tataLetak,
     * supaya isi formulirnya tidak perlu digandakan per peran.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'peserta' => $user->role === 'peserta' ? $user->peserta : null,
            'tataLetak' => $this->tataLetak($user->role),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Divalidasi lebih dulu, bukan setelah save(). Dulu nama dan email
        // sudah tersimpan sebelum no_hp diperiksa, jadi kalau no_hp ditolak
        // sebagian data tetap berubah padahal pengguna melihat pesan galat.
        // no_hp wajib karena kolomnya NOT NULL; dulu ditandai nullable sehingga
        // pengosongan formulir berujung galat basis data, bukan pesan validasi.
        $dataPeserta = $user->role === 'peserta'
            ? $request->validate([
                'no_hp' => ['required', 'string', 'max:20'],
                'instansi' => ['nullable', 'string', 'max:255'],
            ])
            : [];

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Baris peserta selalu dibuat saat pendaftaran akun, lengkap dengan
        // nomor_peserta yang unik. updateOrCreate() yang dipakai sebelumnya
        // justru tidak pernah bisa membuat baris baru — nomor_peserta dan
        // no_hp wajib diisi — jadi cukup perbarui yang sudah ada.
        if ($user->role === 'peserta' && $user->peserta) {
            $user->peserta->update($dataPeserta);
        }

        return redirect()
            ->route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Kerangka halaman mengikuti peran supaya menu samping pengguna tidak
     * hilang begitu ia membuka profil.
     */
    private function tataLetak(?string $role): string
    {
        return match ($role) {
            'admin' => 'layouts.admin',
            'instruktur' => 'instruktur::layouts.master',
            default => 'peserta::layouts.student',
        };
    }
}
