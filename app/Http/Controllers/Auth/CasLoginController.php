<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Subfission\Cas\CasManager;
use App\Http\Controllers\Controller;

class CasLoginController extends Controller
{
    public function redirectToCas()
    {
        // Trigger SSO login
        \Cas::authenticate();
        $username = \Cas::getCurrentUser();
        if ($username) {
            // Ambil atribut SSO jika ada (misal email, nama, role)
            $email = \Cas::getAttribute('email') ?? $username.'@upi.edu';
            $name = \Cas::getAttribute('nama') ?? $username;
            // Contoh: role dari SSO, fallback ke peserta
            $role = \Cas::getAttribute('role') ?? 'peserta';

            // Cek user lokal, jika belum ada buat baru
            $user = \App\Models\User::where('username', $username)->orWhere('email', $email)->first();
            if (!$user) {
                $user = \App\Models\User::create([
                    'name' => $name,
                    'email' => $email,
                    'username' => $username,
                    'role' => $role,
                    'password' => bcrypt(uniqid()), // password random, tidak dipakai
                ]);
            } else {
                // Update data jika perlu
                $user->update([
                    'name' => $name,
                    'email' => $email,
                    'role' => $role,
                ]);
            }
            Auth::login($user);
            return redirect()->intended('/');
        }
        return redirect('/login')->withErrors('SSO gagal.');
    }

    public function logout(Request $request)
    {
        \Cas::logout();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
