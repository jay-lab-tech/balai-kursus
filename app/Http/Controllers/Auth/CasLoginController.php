<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use App\Services\Auth\TrustedDeviceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CasLoginController extends Controller
{
    public function redirectToCas(Request $request, TrustedDeviceManager $trustedDeviceManager)
    {
        // Jika belum login ke CAS, redirect ke server CAS
        if (! \Cas::isAuthenticated()) {
            return \Cas::authenticate();
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Data dari CAS
        |--------------------------------------------------------------------------
        */

        $username = \Cas::user(); // Biasanya NIM (unik)
        $email = \Cas::getAttribute('email') ?? $username.'@upi.edu';
        $name = \Cas::getAttribute('nama') ?? $username;
        $role = \Cas::getAttribute('role') ?? 'peserta';

        /*
        |--------------------------------------------------------------------------
        | Cari atau Buat User
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::where('name', $username)->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'password' => bcrypt(uniqid()), // dummy password
            ]);
        } else {
            $user->update([
                'name' => $name,
                'email' => $email,
                'role' => $role,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Auto Create Peserta Jika Role Peserta
        |--------------------------------------------------------------------------
        */

        if ($role === 'peserta') {
            if (! $user->peserta) {
                $nomorPeserta = 'PS-'.date('Y').'-'.str_pad($user->id, 5, '0', STR_PAD_LEFT);
                Peserta::create([
                    'user_id' => $user->id,
                    'nomor_peserta' => $nomorPeserta,
                    'no_hp' => '-',
                    'instansi' => 'Belum diisi',

                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Login ke Laravel
        |--------------------------------------------------------------------------
        */

        Auth::login($user);
        $trustedDeviceManager->remember($user, $request);

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        \Cas::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
