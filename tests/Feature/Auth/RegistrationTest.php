<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\TrustedDeviceManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->with('peserta')->first();

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertNotNull($user);
        $this->assertSame('peserta', $user->role);
        $this->assertNotNull($user->peserta);
        $this->assertSame($user->id, $user->peserta->user_id);
        $this->assertMatchesRegularExpression('/^PS-\d{4}-\d{5}$/', $user->peserta->nomor_peserta);
        $this->assertSame('testuser', $user->name);
        $response->assertCookie(TrustedDeviceManager::COOKIE_NAME);
        $this->assertDatabaseCount('trusted_devices', 1);
    }
}
