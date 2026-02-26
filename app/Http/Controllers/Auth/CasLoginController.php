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
            
            $email = \Cas::getAttribute('email') ?? $username.'@upi.edu';
            $name = \Cas::getAttribute('nama') ?? $username;
            
            $role = \Cas::getAttribute('role') ?? 'peserta';

            
            $user = \App\Models\User::where('username', $username)->orWhere('email', $email)->first();
            if (!$user) {
                $user = \App\Models\User::create([
                    'name' => $name,
                    'email' => $email,
                    'username' => $username,
                    'role' => $role,
                    'password' => bcrypt(uniqid()),
                ]);
            } else {
            
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
