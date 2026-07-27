<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\TrustedDeviceManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, TrustedDeviceManager $trustedDeviceManager): RedirectResponse
    {
        $request->merge([
            'username' => (string) $request->input('username', $request->input('name')),
            'email' => strtolower((string) $request->input('email')),
        ]);

        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => (string) $request->input('username'),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'peserta',
            ]);

            Peserta::create([
                'user_id' => $user->id,
                'nomor_peserta' => $this->generateParticipantNumber($user->id),
                'no_hp' => '-',
                'instansi' => 'Belum diisi',
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);
        $trustedDeviceManager->remember($user, $request);

        return redirect(RouteServiceProvider::HOME);
    }

    protected function generateParticipantNumber(int $userId): string
    {
        return 'PS-'.now()->format('Y').'-'.str_pad((string) $userId, 5, '0', STR_PAD_LEFT);
    }
}
