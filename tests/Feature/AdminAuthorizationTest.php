<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_non_admin_user_cannot_access_admin_peserta_index(): void
    {
        $user = User::factory()->create(['role' => 'instruktur']);

        $this->actingAs($user)
            ->get('/admin/peserta')
            ->assertForbidden();
    }

    public function test_redirect_route_requires_authentication(): void
    {
        $this->get('/redirect')->assertRedirect(route('login.cas'));
    }
}
