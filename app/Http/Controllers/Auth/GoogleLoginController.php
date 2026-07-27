<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\TrustedDeviceManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return redirect()->route('login')->with('error', $this->missingConfigurationMessage());
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => $this->redirectUri(),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'online',
            'include_granted_scopes' => 'true',
            'prompt' => 'select_account',
            'state' => $state,
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function callback(Request $request, TrustedDeviceManager $trustedDeviceManager): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return redirect()->route('login')->with('error', $this->missingConfigurationMessage());
        }

        $expectedState = $request->session()->pull('google_oauth_state');

        if (! $expectedState || ! hash_equals($expectedState, (string) $request->string('state'))) {
            return redirect()->route('login')->with('error', 'Sesi login Google tidak valid. Silakan coba lagi.');
        }

        if ($request->filled('error')) {
            return redirect()->route('login')->with('error', 'Login Google dibatalkan.');
        }

        $code = (string) $request->string('code');

        if ($code === '') {
            return redirect()->route('login')->with('error', 'Kode autentikasi Google tidak ditemukan.');
        }

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => $this->redirectUri(),
            'grant_type' => 'authorization_code',
        ]);

        if ($tokenResponse->failed() || ! $tokenResponse->json('access_token')) {
            return redirect()->route('login')->with('error', 'Gagal memproses login Google.');
        }

        $userInfoResponse = Http::withToken($tokenResponse->json('access_token'))
            ->get('https://openidconnect.googleapis.com/v1/userinfo');

        if ($userInfoResponse->failed()) {
            return redirect()->route('login')->with('error', 'Gagal mengambil data akun Google.');
        }

        $googleUser = $userInfoResponse->json();
        $email = Str::lower((string) ($googleUser['email'] ?? ''));

        if ($email === '' || ! ($googleUser['email_verified'] ?? false)) {
            return redirect()->route('login')->with('error', 'Akun Google harus memiliki email yang valid dan terverifikasi.');
        }

        $user = DB::transaction(function () use ($googleUser, $email) {
            $user = User::where('email', $email)->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser['name'] ?? $email,
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'role' => 'peserta',
                ]);
            }

            if (! $user->email_verified_at) {
                $user->forceFill([
                    'email_verified_at' => now(),
                ])->save();
            }

            if ($user->role === 'peserta' && ! $user->peserta) {
                Peserta::create([
                    'user_id' => $user->id,
                    'nomor_peserta' => $this->generateParticipantNumber($user->id),
                    'no_hp' => '-',
                    'instansi' => 'Belum diisi',
                ]);
            }

            return $user;
        });

        Auth::login($user, true);
        $request->session()->regenerate();
        $trustedDeviceManager->remember($user, $request);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    protected function isConfigured(): bool
    {
        return filled(config('services.google.client_id')) && filled(config('services.google.client_secret'));
    }

    protected function missingConfigurationMessage(): string
    {
        return 'Login Google belum siap. Isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET di file .env, lalu pastikan redirect URI Google adalah '
            .$this->redirectUri();
    }

    protected function redirectUri(): string
    {
        return config('services.google.redirect') ?: route('login.google.callback');
    }

    protected function generateParticipantNumber(int $userId): string
    {
        return 'PS-'.now()->format('Y').'-'.str_pad((string) $userId, 5, '0', STR_PAD_LEFT);
    }
}
