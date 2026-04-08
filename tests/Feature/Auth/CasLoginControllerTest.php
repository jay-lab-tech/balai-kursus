<?php

namespace Tests\Feature\Auth;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CasLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_cas_login_updates_existing_user_by_email_without_creating_duplicates(): void
    {
        $existingUser = User::factory()->create([
            'name' => 'nim123',
            'email' => 'nim123@upi.edu',
            'role' => 'peserta',
        ]);

        Peserta::create([
            'user_id' => $existingUser->id,
            'nomor_peserta' => 'PS-2026-00001',
            'no_hp' => '-',
            'instansi' => 'Belum diisi',
        ]);

        $cas = Mockery::mock();
        $cas->shouldReceive('isAuthenticated')->once()->andReturn(true);
        $cas->shouldReceive('user')->once()->andReturn('nim123');
        $cas->shouldReceive('getAttribute')->with('email')->once()->andReturn('nim123@upi.edu');
        $cas->shouldReceive('getAttribute')->with('nama')->once()->andReturn('Nama Lengkap');
        $cas->shouldReceive('getAttribute')->with('role')->once()->andReturn('peserta');

        $this->app->instance('cas', $cas);

        $response = $this->get(route('login.cas'));

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($existingUser->fresh());
        $this->assertSame(1, User::where('email', 'nim123@upi.edu')->count());
        $this->assertEquals('Nama Lengkap', $existingUser->fresh()->name);
        $this->assertSame(1, Peserta::where('user_id', $existingUser->id)->count());
    }
}
