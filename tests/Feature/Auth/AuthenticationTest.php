<?php

namespace Tests\Feature\Auth;

use App\Http\Middleware\EncryptCookies;
use App\Models\TrustedDevice;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\TrustedDeviceManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSeeText('Masuk dengan email');
    }

    public function test_trusted_device_users_can_authenticate_using_email_only(): void
    {
        $this->disableCookieEncryption();
        $this->withoutMiddleware(EncryptCookies::class);

        $user = User::factory()->create();
        $plainToken = 'trusted-device-token';

        TrustedDevice::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plainToken),
            'last_used_at' => now(),
        ]);

        $response = $this->withCookie(
            TrustedDeviceManager::COOKIE_NAME,
            $user->id.'|'.$plainToken,
        )->post('/login', [
            'email' => $user->email,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_authenticate_with_password_on_a_new_device(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        $response->assertCookie(TrustedDeviceManager::COOKIE_NAME);
        $this->assertDatabaseCount('trusted_devices', 1);
    }

    public function test_users_can_not_authenticate_on_untrusted_device_without_password(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
