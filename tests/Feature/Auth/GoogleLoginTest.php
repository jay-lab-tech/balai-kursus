<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\TrustedDeviceManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.google.client_id', 'google-client-id');
        config()->set('services.google.client_secret', 'google-client-secret');
        config()->set('services.google.redirect', 'http://localhost/auth/google/callback');
    }

    public function test_google_redirect_route_redirects_to_google_account_chooser(): void
    {
        $response = $this->get(route('login.google'));

        $response->assertRedirectContains('https://accounts.google.com/o/oauth2/v2/auth');
        $response->assertRedirectContains('prompt=select_account');
        $this->assertNotEmpty(session('google_oauth_state'));
    }

    public function test_google_callback_creates_participant_account_without_password_input(): void
    {
        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'google-access-token',
                'token_type' => 'Bearer',
            ], 200),
            'https://openidconnect.googleapis.com/v1/userinfo' => Http::response([
                'email' => 'peserta.google@example.com',
                'email_verified' => true,
                'name' => 'Peserta Google',
            ], 200),
        ]);

        $response = $this->withSession([
            'google_oauth_state' => 'state-123',
        ])->get(route('login.google.callback', [
            'state' => 'state-123',
            'code' => 'auth-code-123',
        ]));

        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'peserta.google@example.com',
            'role' => 'peserta',
        ]);
        $this->assertDatabaseHas('pesertas', [
            'user_id' => User::where('email', 'peserta.google@example.com')->value('id'),
        ]);
        $this->assertDatabaseCount('trusted_devices', 1);
        $response->assertCookie(TrustedDeviceManager::COOKIE_NAME);
    }

    public function test_google_callback_logs_in_existing_user_with_same_email(): void
    {
        $user = User::factory()->create([
            'role' => 'peserta',
            'email' => 'anggota@example.com',
        ]);

        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'google-access-token',
                'token_type' => 'Bearer',
            ], 200),
            'https://openidconnect.googleapis.com/v1/userinfo' => Http::response([
                'email' => 'anggota@example.com',
                'email_verified' => true,
                'name' => 'Anggota Lama',
            ], 200),
        ]);

        $response = $this->withSession([
            'google_oauth_state' => 'state-456',
        ])->get(route('login.google.callback', [
            'state' => 'state-456',
            'code' => 'auth-code-456',
        ]));

        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticatedAs($user->fresh());
        $this->assertSame(1, User::where('email', 'anggota@example.com')->count());
        $this->assertDatabaseCount('trusted_devices', 1);
        $response->assertCookie(TrustedDeviceManager::COOKIE_NAME);
    }
}
